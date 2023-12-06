<?php

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Ignore reCaptcha on confirmation screen
 *
 */
function wpbs_validate_recaptcha_payment_confirmation_stripe($response, $form_data)
{
    if (isset($form_data['wpbs-stripe-confirmation-loaded']) && $form_data['wpbs-stripe-confirmation-loaded'] == '1') {
        return true;
    }
    return $response;
}
add_filter('wpbs_validate_recaptcha_payment_confirmation', 'wpbs_validate_recaptcha_payment_confirmation_stripe', 10, 2);

/**
 * Show the payment confirmation page after submitting the form
 *
 */
function wpbs_stripe_submit_form_payment_confirmation($response, $post_data, $form, $form_args, $form_fields, $calendar_id)
{
    // Check if another payment method was already found
    if ($response !== false) {
        return $response;
    }

    $payment_found = false;

    // Check if payment method is enabled.
    foreach ($form_fields as $form_field) {
        if ($form_field['type'] == 'payment_method' && $form_field['user_value'] == 'stripe') {
            $payment_found = true;
            break;
        }
    }

    if ($payment_found === false) {
        return false;
    }

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

    // Parse POST data
    parse_str($post_data['form_data'], $form_data);

    // Check if the payment screen was shown
    if (isset($form_data['wpbs-stripe-confirmation-loaded']) && $form_data['wpbs-stripe-confirmation-loaded'] == '1') {
        return false;
    }

    // Add a field to the input so we can check if the payment screen was already shown
    add_filter('wpbs_form_outputter_form_fields_after', function () {
        return '<input type="hidden" name="wpbs-stripe-confirmation-loaded" value="1">';
    });

    // Generate form
    $form_outputter = new WPBS_Form_Outputter($form, $form_args, $form_fields, $calendar_id);

    // Check if post data exists and matches form values
    if (wpbs_validate_payment_form_consistency($form_fields) === false) {
        return json_encode(
            array(
                'success' => false,
                'html' => '<strong>' . __('Something went wrong. Please refresh the page and try again.', 'wp-booking-system-stripe') . '</strong>',
            )
        );
    }

    // Get price
    $payment = new WPBS_Payment;
    $payment->calculate_prices($post_data, $form, $form_args, $form_fields);

    $total = $payment->get_total();

    // Check if part payments are used
    if (wpbs_part_payments_enabled() == true && $payment->is_part_payment()) {
        $total = $payment->get_total_first_payment();
    }

    // Check if price is greater than the minimum allowed, 0.5;
    if ($total <= 0.5) {
        return json_encode(
            array(
                'success' => false,
                'html' => '<p class="wpbs-form-general-error">' . __("The minimum payable amount is 0.50$", 'wp-booking-system-stripe') . '</p>',
            )
        );
    }

    // Get plugin settings
    $settings = get_option('wpbs_settings', array());

    // Invoice item description
    $invoice_item_description = (!empty($settings['payment_stripe_invoice_name_translation_' . $form_outputter->get_language()])) ? $settings['payment_stripe_invoice_name_translation_' . $form_outputter->get_language()] : (!empty($settings['payment_stripe_invoice_name']) ? $settings['payment_stripe_invoice_name'] : get_bloginfo('name') . ' Booking');

    $calendar = wpbs_get_calendar($calendar_id);

    $invoice_item_description .= ' - ' . $calendar->get_name();
    $invoice_item_description .= ' - ' . wpbs_date_i18n(get_option('date_format'), wpbs_convert_js_to_php_timestamp($post_data['calendar']['start_date'])) . '-' . wpbs_date_i18n(get_option('date_format'), wpbs_convert_js_to_php_timestamp($post_data['calendar']['end_date']));
    $invoice_item_description = apply_filters('wpbs_stripe_invoice_item_description', $invoice_item_description, $calendar, $payment);

    /**
     * Create the Payment Intent on Stripe
     *
     */

    $metadata = [];
    $client_email = false;
    foreach($form_fields as $field){
        if(!isset($field['user_value'])){
            continue;
        }
        $value = wpbs_get_field_display_user_value($field['user_value']);
        $value = substr($value, 0 , 500);

        if($field['type'] == 'email'){
            $client_email = $value;
            $metadata['_stripe_email'] = $client_email;
        }

        $metadata['field_' . $field['id']] = $value;
        if(!empty($field['values']['default']['label'])){
            $key = $field['values']['default']['label'] ? sanitize_title($field['values']['default']['label']) : ''; 
            $key = substr('field_' . $key, 0, 40);

            $metadata[$key] = $value;
        }
    }

    $metadata = array_slice($metadata, 0, 50);

    $intent = WPBS_Stripe_PaymentIntent::createPaymentIntent($total, $payment->get_currency(), $invoice_item_description, $metadata);

    /**
     * Prepare Response
     *
     */
    ob_start();
    ?>
    
    <?php if ($api['environment'] == 'test'):?>
        <p class="wpbs-payment-test-mode-enabled"><?php echo __('Stripe Test mode is enabled.', 'wp-booking-system-stripe'); ?></p>
    <?php endif;?>

    <div class="wpbs-payment-confirmation-stripe-form">

    <?php if (wpbs_part_payments_enabled() == true && $payment->is_part_payment()):?>
        <label><?php echo wpbs_get_payment_default_string('amount_billed', $form_outputter->get_language()); ?></label><input class="wpbs-payment-confirmation-stripe-input" type="text" value="<?php echo wpbs_get_formatted_price($total, $payment->get_currency()); ?>" readonly>
    <?php endif; ?>

    <?php if(isset($settings['payment_stripe_apple_pay']) && $settings['payment_stripe_apple_pay']): ?>
    <div id="wpbs-stripe-payment-request-wrap">
        <div id="wpbs-stripe-payment-request-button"></div>
        <p><?php echo wpbs_get_payment_default_string('payment_apple_pay', $form_outputter->get_language()); ?></p>
    </div>
    <?php endif; ?>

    <label><?php echo wpbs_get_payment_default_string('cardholder_name', $form_outputter->get_language()); ?></label>
        <input id="wpbs-stripe-cardholder-name" class="wpbs-payment-confirmation-stripe-input" type="text" required>
        <div id="wpbs-stripe-cardholder-name-error" class="wpbs-stripe-error"></div>
        <label><?php echo wpbs_get_payment_default_string('card_details', $form_outputter->get_language()); ?></label>
        <div id="wpbs-stripe-card-element"></div>
        <div id="wpbs-stripe-card-errors" class="wpbs-stripe-error"></div>
        <button id="wpbs-stripe-card-button" data-secret="<?php echo $intent->client_secret; ?>"><?php echo wpbs_get_payment_default_string('payment_submit', $form_outputter->get_language()); ?></button>
        <div class="wpbs-stripe-powered-by"><img src="<?php echo WPBS_STRIPE_PLUGIN_DIR_URL; ?>assets/img/powered-by-stripe.svg"></div>
    </div>

    <script>

    wpbs_lazy_load_script("https://js.stripe.com/v3/",wpbs_init_stripe);

    function wpbs_init_stripe(){
        var stripe = Stripe("<?php echo $api['publishable_key']; ?>", {locale: "<?php echo $form_outputter->get_language();?>"});
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
            console.log(result)
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
                                wpbs_stripe_submit_form(confirmResult.paymentIntent.id)
                            }
                        });
                    } else {
                        wpbs_stripe_submit_form(confirmResult.paymentIntent.id)
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
            // Show Loader
            jQuery(".wpbs-payment-confirmation-inner").append('<div class="wpbs-overlay"><div class="wpbs-overlay-spinner"><div class="wpbs-overlay-bounce1"></div><div class="wpbs-overlay-bounce2"></div><div class="wpbs-overlay-bounce3"></div></div></div>');
            jQuery("#wpbs-stripe-cardholder-name-error").empty();
            if( !jQuery("#wpbs-stripe-cardholder-name").val() ) {
                jQuery("#wpbs-stripe-cardholder-name-error").text("<?php echo wpbs_get_payment_default_string('payment_required_field', $form_outputter->get_language()); ?>");
            }
            stripe.handleCardPayment(
                clientSecret, cardElement, {
                    payment_method_data: {
                        billing_details: {
                            name: cardholderName.value,
                            <?php if($client_email): ?>email: '<?php echo $client_email;?>'<?php endif; ?>
                        }
                    },
					<?php if($client_email && apply_filters('wpbs_stripe_customer_email_receipt', true)): ?> receipt_email: '<?php echo $client_email;?>'<?php endif; ?>
                }
            ).then(function(result) {
                jQuery("#wpbs-stripe-card-button").attr("disabled", false);
                jQuery(".wpbs-payment-confirmation-inner .wpbs-overlay").remove();
                if (result.error) {
                    if(result.error.code == "parameter_invalid_empty" && result.error.param == "payment_method_data[billing_details][name]"){
                        jQuery("#wpbs-stripe-cardholder-name-error").text("<?php echo wpbs_get_payment_default_string('payment_required_field', $form_outputter->get_language()); ?>");
                    } else {
                        jQuery("#wpbs-stripe-card-errors").text(result.error.message);
                    }
                } else {
                    wpbs_stripe_submit_form(result.paymentIntent.id);
                }
            });
        });
        jQuery(".wpbs-stripe-payment-confirmation-inner-<?php echo $form_outputter->get_unique(); ?>").parents(".wpbs-main-wrapper").find(".wpbs-container").addClass("wpbs-disable-selection");

        function wpbs_stripe_submit_form(payment_intent_id){
            jQuery(".wpbs-stripe-payment-confirmation-inner-<?php echo $form_outputter->get_unique(); ?>").hide();
            jQuery(".wpbs-stripe-payment-confirmation-<?php echo $form_outputter->get_unique(); ?>").append("<h4><?php echo wpbs_get_payment_default_string('processing_payment', $form_outputter->get_language()); ?></h4>");
            jQuery(".wpbs-stripe-payment-confirmation-<?php echo $form_outputter->get_unique(); ?>").parents(".wpbs-main-wrapper").find(".wpbs-calendar").append('<div class="wpbs-overlay"><div class="wpbs-overlay-spinner"><div class="wpbs-overlay-bounce1"></div><div class="wpbs-overlay-bounce2"></div><div class="wpbs-overlay-bounce3"></div></div></div>');
            jQuery('.wpbs-stripe-payment-confirmation-<?php echo $form_outputter->get_unique(); ?>').parents(".wpbs-container").addClass("wpbs-is-loading");
            jQuery('.wpbs-stripe-payment-confirmation-<?php echo $form_outputter->get_unique(); ?> form').append('<input type="hidden" name="wpbs-stripe-payment-intent-id" value="'+payment_intent_id+'">')
            jQuery('.wpbs-stripe-payment-confirmation-<?php echo $form_outputter->get_unique(); ?> form').append('<input type="hidden" name="wpbs-custom-currency" value="<?php echo $payment->get_currency();?>">')
            jQuery('.wpbs-stripe-payment-confirmation-<?php echo $form_outputter->get_unique(); ?> form').submit();
        }
    }
    </script>
    
    <?php
    $stripe_output = ob_get_contents();
    ob_end_clean();

    $output = wpbs_form_payment_confirmation_screen($form_outputter, $payment, 'stripe', $stripe_output);

    return json_encode(
        array(
            'success' => false,
            'html' => $output,
        )
    );

}
add_filter('wpbs_submit_form_before', 'wpbs_stripe_submit_form_payment_confirmation', 10, 6);

/**
 * Save the order in the database and maybe capture the payment
 *
 */
function wpbs_stripe_action_save_payment_details($booking_id, $post_data, $form, $form_args, $form_fields)
{

    // Parse POST data
    parse_str($post_data['form_data'], $form_data);

    // Check if stripe is enabled
    if (!isset($form_data['wpbs-stripe-confirmation-loaded'])) {
        return false;
    }

    // Check if we got an payment intent ID
    if (!isset($form_data['wpbs-stripe-payment-intent-id'])) {
        return false;
    }

    // Get site options
    $settings = get_option('wpbs_settings', array());

    // Include Stripe SDK
    include_once WPBS_STRIPE_PLUGIN_DIR . 'includes/libs/stripe-api/stripe-api.php';

    // Get price
    $payment = new WPBS_Payment;
    $details['price'] = $payment->calculate_prices($post_data, $form, $form_args, $form_fields);

    if (wpbs_part_payments_enabled() == true && $payment->is_part_payment()) {
        $details['part_payments'] = array('deposit' => false, 'final_payment' => false);
    }

    // Authorization method
    $default_booking_status = wpbs_get_form_meta($form->get('id'), 'form_default_booking_status', true) ? : 'pending';

    if (isset($settings['payment_stripe_delayed_capture']) && $settings['payment_stripe_delayed_capture'] == 'on' && $default_booking_status == 'pending') {
        // Save temporary data, and capture the payment when the booking is accepted.
        $details['raw'] = array();
        $details['payment_intent_id'] = $form_data['wpbs-stripe-payment-intent-id'];

        if (isset($details['part_payments']['deposit'])) {
            $details['part_payments']['deposit'] = true;
        }

        $status = 'authorized';
        $id = 'N/A';
    } else {
        // Capture payment when booking.

        // Get Order
        $order = WPBS_Stripe_PaymentIntent::getPaymentIntent($form_data['wpbs-stripe-payment-intent-id']);

        if ($order['success'] == true) {
            $details['raw'] = $order['data'];

            if (isset($details['part_payments']['deposit'])) {
                $details['part_payments']['deposit'] = true;
            }

            $status = 'completed';
            $id = $order['data']->id;
        } else {
            $details['error'] = $order['error'];
            $status = 'error';
            $id = 'N/A';
        }
    }

    // Save Order
    wpbs_insert_payment(array(
        'booking_id' => $booking_id,
        'gateway' => 'stripe',
        'order_id' => $id,
        'order_status' => $status,
        'details' => $details,
        'date_created' => current_time('Y-m-d H:i:s'),
    ));

}
add_action('wpbs_submit_form_after', 'wpbs_stripe_action_save_payment_details', 10, 5);

/**
 * Capture the order if needed
 *
 * @param WPBS_Booking $booking
 *
 */
function wpbs_stripe_save_booking_data_accept_booking($booking)
{
    // Get site options
    $settings = get_option('wpbs_settings', array());

    // Check if delayed capture is enabled
    if (!isset($settings['payment_stripe_delayed_capture']) || $settings['payment_stripe_delayed_capture'] != 'on') {
        return false;
    }

    // Get Payment
    $payments = wpbs_get_payments(array('booking_id' => $booking->get('id')));

    if (is_null($payments)) {
        return false;
    }

    $payment = array_shift($payments);

    if(is_null($payment)){
		return false;
	}

    if($payment->get('gateway') != 'stripe'){
        return false;
    }

    // Exit if status is not "authorized"
    if ($payment->get('order_status') != 'authorized') {
        return false;
    }

    // Include Stripe SDK
    include_once WPBS_STRIPE_PLUGIN_DIR . 'includes/libs/stripe-api/stripe-api.php';

    // Get Order
    $details = $payment->get('details');

    if (wpbs_part_payments_enabled() == true && $payment->is_part_payment()) {
        $details['part_payments'] = array('deposit' => false, 'final_payment' => false);
    }

    // Capture order
    $order = WPBS_Stripe_PaymentIntent::getPaymentIntent($details['payment_intent_id']);

    // Prepare details
    if ($order['success'] == true) {
        $details['raw'] = $order['data'];

        if (isset($details['part_payments']['deposit'])) {
            $details['part_payments']['deposit'] = true;
        }

        $status = 'completed';
        $id = $order['data']->id;
    } else {
        $details['error'] = $order['error'];
        $details['raw'] = $order['data'];
        $status = 'error';
        $id = 'N/A';
    }

    // Update payment with correct details
    wpbs_update_payment($payment->get('id'), array(
        'order_id' => $id,
        'order_status' => $status,
        'details' => $details,
    ));

}
add_action('wpbs_save_booking_data_accept_booking', 'wpbs_stripe_save_booking_data_accept_booking', 1, 10);

/**
 * Cancel the authorization if the booking is deleted
 *
 * @param int $booking_id
 *
 */
function wpbs_stripe_permanently_delete_booking($booking_id)
{

    // Get site options
    $settings = get_option('wpbs_settings', array());

    // Check if delayed capture is enabled
    if (!isset($settings['payment_stripe_delayed_capture']) || $settings['payment_stripe_delayed_capture'] != 'on') {
        return false;
    }

    // Get booking
    $booking = wpbs_get_booking($booking_id);

    // Get payment
    $payments = wpbs_get_payments(array('booking_id' => $booking->get('id')));

    if (empty($payments)) {
        return false;
    }

    $payment = array_shift($payments);

    if (is_null($payment)) {
        return false;
    }

    // Exit if status is not "authorized"
    if ($payment->get('order_status') != 'authorized') {
        return false;
    }

    // Include Stripe SDK
    include_once WPBS_STRIPE_PLUGIN_DIR . 'includes/libs/stripe-api/stripe-api.php';

    // Get Order
    $details = $payment->get('details');

    // Cancel the order
    $order = WPBS_Stripe_PaymentIntent::cancelPayment($details['payment_intent_id']);

}
add_action('wpbs_permanently_delete_booking', 'wpbs_stripe_permanently_delete_booking', 1, 10);
