<?php

include_once WPBS_STRIPE_PLUGIN_DIR . 'includes/libs/stripe-api/vendor/autoload.php';

class WPBS_Stripe_API
{
    public static function keys()
    {
        $settings = get_option('wpbs_settings', array());
        $stripe_api = get_option('wpbs_stripe_api', array());

        if ((isset($settings['payment_stripe_test']) && $settings['payment_stripe_test'] == 'on')) {
            return array(
                'environment' => 'test',
                'publishable_key' => isset($stripe_api['payment_stripe_test_api_publishable_key']) ? $stripe_api['payment_stripe_test_api_publishable_key'] : '',
                'secret_key' => isset($stripe_api['payment_stripe_test_api_secret_key']) ? $stripe_api['payment_stripe_test_api_secret_key'] : '',
            );
        } else {
            return array(
                'environment' => 'live',
                'publishable_key' => isset($stripe_api['payment_stripe_live_api_publishable_key']) ? $stripe_api['payment_stripe_live_api_publishable_key'] : '',
                'secret_key' => isset($stripe_api['payment_stripe_live_api_secret_key']) ? $stripe_api['payment_stripe_live_api_secret_key'] : '',
            );
        }
    }
}

class WPBS_Stripe_Client
{
    public static function client()
    {
        $api = WPBS_Stripe_API::keys();
        \Stripe\Stripe::setApiKey($api['secret_key']);
        \Stripe\Stripe::setApiVersion("2023-08-16");
    }
}

class WPBS_Stripe_Refund
{
    public static function refundOrder($charge, $amount)
    {
        WPBS_Stripe_Client::client();

        return \Stripe\Refund::create([
            'amount' => $amount,
            'charge' => $charge,
        ]);
    }
}

class WPBS_Stripe_GetCharge
{
    public static function retrieve($charge_id)
    {
        WPBS_Stripe_Client::client();

        return \Stripe\Charge::retrieve($charge_id);
    }
}

class WPBS_Stripe_PaymentIntent
{
    public static function createPaymentIntent($amount, $currency, $description, $metadata = [])
    {
        WPBS_Stripe_Client::client();

        if (!in_array($currency, ['BIF', 'CLP', 'DJF', 'GNF', 'JPY', 'KMF', 'KRW', 'MGA', 'PYG', 'RWF', 'UGX', 'VND', 'VUV', 'XAF', 'XOF', 'XPF'])) {
            $amount = $amount * 100;
        }

        $paymentIntent = [
            'amount' => $amount,
            'currency' => $currency,
            'description' => $description,
            'payment_method_types' => ['card'],
            'capture_method' => 'manual',
            'setup_future_usage' => 'off_session',
            'metadata' => $metadata
        ];

        $email = isset($metadata['_stripe_email']) && $metadata['_stripe_email'] ? $metadata['_stripe_email'] : false;

        if ($email && apply_filters('wpbs_stripe_create_customer', true)) {
            $customer = \Stripe\Customer::search([
                'query' => 'email:\'' . $email . '\'',
            ]);

            if (isset($customer->data[0]->id)) {
                // Assign customer
                $paymentIntent['customer'] = $customer->data[0]->id;
            } else {
                // Create new customer
                $customer_id = \Stripe\Customer::create([
                    'email' => $email,
                ]);
                $paymentIntent['customer'] = $customer_id;
            }
        }

        return \Stripe\PaymentIntent::create($paymentIntent);
    }

    public static function getPaymentIntent($id)
    {

        WPBS_Stripe_Client::client();

        try {
            $intent = \Stripe\PaymentIntent::retrieve($id);
            if ($intent->amount_capturable == 0) {
                if ($intent->amount_received == 0) {
                    $response = array(
                        'success' => false,
                        'data' => array(),
                        'error' => 'Authorization expired or was cancelled. Amount received is 0.',
                    );
                } else {
                    $response = array(
                        'success' => true,
                        'data' => $intent,
                    );
                }
            } else {
                $intent->capture();

                $response = array(
                    'success' => true,
                    'data' => $intent,
                );
            }
        } catch (\Stripe\Error\Base $e) {
            $response = array(
                'success' => false,
                'error' => $e->getMessage(),
            );
        }
        return $response;
    }

    public static function cancelPayment($id)
    {

        WPBS_Stripe_Client::client();

        try {
            $intent = \Stripe\PaymentIntent::retrieve($id);

            if ($intent->amount_capturable > 0) {

                try {

                    $intent->cancel();

                    $response = array(
                        'success' => true,
                        'data' => $intent,
                    );
                } catch (\Stripe\Error\Base $e) {
                    $response = array(
                        'success' => false,
                        'error' => $e->getMessage(),
                    );
                }

                return $response;
            }
        } catch (\Stripe\Exception\CardException $e) {
        } catch (\Stripe\Exception\RateLimitException $e) {
        } catch (\Stripe\Exception\InvalidRequestException $e) {
        } catch (\Stripe\Exception\AuthenticationException $e) {
        } catch (\Stripe\Exception\ApiConnectionException $e) {
        } catch (\Stripe\Exception\ApiErrorException $e) {
        } catch (Exception $e) {
        }
    }
}
