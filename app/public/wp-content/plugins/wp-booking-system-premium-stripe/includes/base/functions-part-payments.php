<?php

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Display the final payment of the part payments checkout form.
 * 
 * @param string $output 
 * @param WPBS_Payment $payment
 * @param string $language
 * 
 * @return string
 * 
 */
function wpbs_final_payment_stripe($output, $payment, $language)
{

    // Get total amount
    $total = $payment->get_total_second_payment();

    // Include Stripe API
    include_once WPBS_STRIPE_PLUGIN_DIR . 'includes/libs/stripe-api/stripe-api.php';

    $api = WPBS_Stripe_API::keys();

    // Check for API Keys
    if (empty($api['publishable_key']) || empty($api['secret_key'])) {
        return json_encode(
            array(
                'success' => false,
                'html' => '<p class="wpbs-form-general-error">' . __("Please add your API keys in the plugin's Settings Page.", 'wp-booking-system-stripe') . '</p>',
            )
        );
    }

    // Get plugin settings
    $settings = get_option('wpbs_settings', array());

    // Invoice item description
    $invoice_item_description = (!empty($settings['payment_stripe_invoice_name_translation_' . $language])) ? $settings['payment_stripe_invoice_name_translation_' . $language] : (!empty($settings['payment_stripe_invoice_name']) ? $settings['payment_stripe_invoice_name'] : get_bloginfo('name') . ' Booking');

    // Get the email address
    $client_email = false;
    $booking = wpbs_get_booking($payment->get('booking_id'));
    foreach($booking->get('fields') as $field){
        if(!isset($field['user_value'])){
            continue;
        }
        $value = wpbs_get_field_display_user_value($field['user_value']);

        if($field['type'] == 'email'){
            $client_email = $value;
        }
    }

    /**
     * Create the Payment Intent on Stripe
     *
     */

    $intent = WPBS_Stripe_PaymentIntent::createPaymentIntent($total, $payment->get_currency(), $invoice_item_description);

    /**
     * Prepare Response
     *
     */
    ob_start();
    ?>

    <?php if ($api['environment'] == 'test'): ?>
        <p class="wpbs-payment-test-mode-enabled"><?php echo __('Stripe Test mode is enabled.', 'wp-booking-system-stripe'); ?></p>
    <?php endif; ?>

    
    
    <div class="wpbs-payment-confirmation-stripe-form">
        <label><?php echo wpbs_get_payment_default_string('amount_billed', $language); ?></label>
        <input class="wpbs-payment-confirmation-stripe-input" type="text" value="<?php echo wpbs_get_formatted_price($total, $payment->get_currency()); ?>" readonly>

        <?php if(isset($settings['payment_stripe_apple_pay']) && $settings['payment_stripe_apple_pay']): ?>
        <div id="wpbs-stripe-payment-request-wrap">
            <div id="wpbs-stripe-payment-request-button"></div>
            <p><?php echo wpbs_get_payment_default_string('payment_apple_pay', $language); ?></p>
        </div>
        <?php endif; ?>

        <label><?php echo wpbs_get_payment_default_string('cardholder_name', $language); ?></label>
        <input id="wpbs-stripe-cardholder-name" class="wpbs-payment-confirmation-stripe-input" type="text" required>
        <div id="wpbs-stripe-cardholder-name-error" class="wpbs-stripe-error"></div>
        <label><?php echo wpbs_get_payment_default_string('card_details', $language); ?></label>
        <div id="wpbs-stripe-card-element"></div>
        <div id="wpbs-stripe-card-errors" class="wpbs-stripe-error"></div>
        <button id="wpbs-stripe-card-button" data-secret="<?php echo $intent->client_secret; ?>"><?php echo wpbs_get_payment_default_string('payment_submit', $language); ?></button>
        <div class="wpbs-stripe-powered-by"><img src="<?php echo WPBS_STRIPE_PLUGIN_DIR_URL; ?>assets/img/powered-by-stripe.svg"></div>
    </div>

    <script>

    jQuery(document).ready(function(){
        wpbs_lazy_load_script("https://js.stripe.com/v3/",wpbs_init_stripe);
    });

    function wpbs_init_stripe(){
        var stripe = Stripe("<?php echo $api['publishable_key']; ?>", {locale: "<?php echo $language;?>"});
        var elements = stripe.elements();

        // Apple Pay
        <?php if(isset($settings['payment_stripe_apple_pay']) && $settings['payment_stripe_apple_pay']): ?>
        var paymentRequest = stripe.paymentRequest({
            country: 'US',
            currency: '<?php echo strtolower($payment->get_currency());?>',
            total: {
                label: '<?php echo esc_attr($invoice_item_description);?>',
                amount: <?php echo $total * 100;?>,
            },
            requestPayerName: true,
            requestPayerEmail: true,
        });

        var prButton = elements.create('paymentRequestButton', {
            paymentRequest: paymentRequest,
        });

        // Check the availability of the Payment Request API first.
        paymentRequest.canMakePayment().then(function(result) {
            if (result) {
                prButton.mount('#wpbs-stripe-payment-request-button');
            } else {
                document.getElementById('wpbs-stripe-payment-request-wrap').style.display = 'none';
            }
        });

        paymentRequest.on('paymentmethod', function(ev) {
            // Confirm the PaymentIntent without handling potential next actions (yet).
            stripe.confirmCardPayment(
                clientSecret,
                {
                    payment_method: ev.paymentMethod.id, 
                    <?php if($client_email && apply_filters('wpbs_stripe_customer_email_receipt', true)): ?>receipt_email: '<?php echo $client_email;?>'<?php endif; ?>
                },
                {handleActions: false}
                
            ).then(function(confirmResult) {
                if (confirmResult.error) {
                    ev.complete('fail');
                } else {
                    ev.complete('success');
                    if (confirmResult.paymentIntent.status === "requires_action") {
                        // Let Stripe.js handle the rest of the payment flow.
                        stripe.confirmCardPayment(clientSecret).then(function(result) {
                            if (result.error) {
                                jQuery("#wpbs-stripe-card-errors").text(result.error.message);
                                console.log(result);
                            } else {
                                wpbs_stripe_final_payment_submit_form(confirmResult.paymentIntent.id)
                            }
                        });
                    } else {
                        wpbs_stripe_final_payment_submit_form(confirmResult.paymentIntent.id)
                    }
                }
            });
        });
        <?php endif; ?>

        // Card Form
        var cardElement = elements.create("card", {
            hidePostalCode: true,
            style: {
                base: {
                    lineHeight: "38px",
                    iconColor: "#1a1a1a"
                },
            }
        });
        cardElement.mount("#wpbs-stripe-card-element");
        var cardholderName = document.getElementById("wpbs-stripe-cardholder-name");
        var cardButton = document.getElementById("wpbs-stripe-card-button");
        var clientSecret = cardButton.dataset.secret;
        cardButton.addEventListener("click", function(ev) {
            jQuery("#wpbs-stripe-card-button").attr("disabled", true);
            jQuery(".wpbs-payment-confirmation-inner").append('<div class="wpbs-overlay"><div class="wpbs-overlay-spinner"><div class="wpbs-overlay-bounce1"></div><div class="wpbs-overlay-bounce2"></div><div class="wpbs-overlay-bounce3"></div></div></div>');
            jQuery("#wpbs-stripe-cardholder-name-error").empty();
            if( !jQuery("#wpbs-stripe-cardholder-name").val() ) {
                jQuery("#wpbs-stripe-cardholder-name-error").text("<?php echo wpbs_get_payment_default_string('payment_required_field', $language); ?>");
            }
            stripe.handleCardPayment(
                clientSecret, cardElement, {
                    payment_method_data: {
                        billing_details: {
                            name: cardholderName.value,
                            <?php if($client_email): ?>email: '<?php echo $client_email;?>'<?php endif; ?>
                        }
                    },
                    <?php if($client_email && apply_filters('wpbs_stripe_customer_email_receipt', true)): ?>receipt_email: '<?php echo $client_email;?>'<?php endif; ?>
                }
            ).then(function(result) {
                jQuery("#wpbs-stripe-card-button").attr("disabled", false);
                jQuery(".wpbs-payment-confirmation-inner .wpbs-overlay").remove();
                if (result.error) {
                    if(result.error.code == "parameter_invalid_empty" && result.error.param == "payment_method_data[billing_details][name]"){
                        jQuery("#wpbs-stripe-cardholder-name-error").text("<?php echo wpbs_get_payment_default_string('payment_required_field', $language); ?>");
                    } else {
                        jQuery("#wpbs-stripe-card-errors").text(result.error.message);
                    }
                } else {
                    wpbs_stripe_final_payment_submit_form(result.paymentIntent.id);
                }
            });
        });

        function wpbs_stripe_final_payment_submit_form(payment_intent_id){
            jQuery(".wpbs-stripe-payment-confirmation-inner").hide();
            jQuery(".wpbs-final-payment-confirmation").append('<h4><?php echo wpbs_get_payment_default_string('processing_payment', $language); ?></h4>');
            jQuery(".wpbs-final-payment-confirmation form").append('<input type="hidden" name="wpbs-stripe-payment-intent-id" value="'+payment_intent_id+'">')
            jQuery(".wpbs-final-payment-confirmation form").submit();
        }
    }
    </script>
    <?php

    $output = ob_get_contents();
    ob_end_clean();
    return $output;

}
add_filter('wpbs_final_payment_stripe', 'wpbs_final_payment_stripe', 10, 3);

/**
 * Process the final payment of the part payments checkout form.
 * 
 * @param array $post_data 
 * @param WPBS_Payment $payment
 * 
 */
function wpbs_save_final_payment_stripe($post_data, $payment)
{

    // Check if we got an payment intent ID
    if (!isset($post_data['wpbs-stripe-payment-intent-id'])) {
        wp_die();
    }

    // Get site options
    $settings = get_option('wpbs_settings', array());

    // Include Stripe SDK
    include_once WPBS_STRIPE_PLUGIN_DIR . 'includes/libs/stripe-api/stripe-api.php';

    // Get price
    $details = $payment->get('details');

    // Check if final payment wasn't made yet
    if ($payment->is_final_payment_paid()) {
        wp_die();
    }

    // Get Order
    $order = WPBS_Stripe_PaymentIntent::getPaymentIntent($post_data['wpbs-stripe-payment-intent-id']);

    if ($order['success'] == true) {
        $details['final_payment'] = $order['data'];
        $details['part_payments']['final_payment'] = true;

        if(isset($details['raw']['id']) && !empty($details['raw']['id'])){
            $details['raw']['id'] .= ', ' . $order['data']->id;
        } else {
            $details['raw']['id'] = $order['data']->id;
        }

        if(isset($details['raw']['amount_received']) && !empty($details['raw']['amount_received'])){
            $details['raw']['amount_received'] = $details['raw']['amount_received'] + $order['data']->amount_received;
        } else {
            $details['raw']['amount_received'] = $order['data']->amount_received;
        }

        $status = 'completed';
    } else {
        $details['error'] = $order['error'];
        $status = 'error';
    }

    $payment_data = array(
        'order_status' => $status,
        'details' => $details,
    );

    // Save Order
    wpbs_update_payment($payment->get('id'), $payment_data);
}
add_action('wpbs_save_final_payment_stripe', 'wpbs_save_final_payment_stripe', 10, 2);
