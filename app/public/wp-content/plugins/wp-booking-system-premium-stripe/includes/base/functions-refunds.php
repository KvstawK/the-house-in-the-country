<?php

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

add_filter('wpbs_payment_details_refunds_available', function ($status, $payment) {
    if ($payment->get_payment_gateway() == 'stripe') {
        return true;
    }
    return $status;
}, 10, 2);

add_filter('wpbs_payment_details_refund_charges', function ($charges, $payment) {

    if ($payment->get_payment_gateway() != 'stripe') {
        return $charges;
    }

    $payment_details = $payment->get('details');

    include_once WPBS_STRIPE_PLUGIN_DIR . 'includes/libs/stripe-api/stripe-api.php';

    $charge_ids = [];

    if (!$payment->is_part_payment()) {
        if (isset($payment_details['raw']['latest_charge'])) {
            $charge_ids[] = [
                'id' => $payment_details['raw']['latest_charge'],
                'name' => 'Charge',
            ];
        } elseif (isset($payment_details['raw']['charges']['data'][0]['id'])) {
            $charge_ids[] = [
                'id' => $payment_details['raw']['charges']['data'][0]['id'],
                'name' => 'Charge',
            ];
        }
    } else {
        if ($payment->is_deposit_paid()) {
            if (isset($payment_details['raw']['latest_charge'])) {
                $charge_ids[] = [
                    'id' => $payment_details['raw']['latest_charge'],
                    'name' => __('Deposit', 'wp-booking-system-stripe'),
                ];
            } elseif (isset($payment_details['raw']['charges']['data'][0]['id'])) {
                $charge_ids[] = [
                    'id' => $payment_details['raw']['charges']['data'][0]['id'],
                    'name' => __('Deposit', 'wp-booking-system-stripe'),
                ];
            }
        }
        if ($payment->is_final_payment_paid()) {
            if (isset($payment_details['final_payment']['latest_charge'])) {
                $charge_ids[] = [
                    'id' => $payment_details['final_payment']['latest_charge'],
                    'name' => __('Final Payment', 'wp-booking-system-stripe'),
                ];
            } elseif (isset($payment_details['final_payment']['charges']['data'][0]['id'])) {
                $charge_ids[] = [
                    'id' => $payment_details['final_payment']['charges']['data'][0]['id'],
                    'name' => __('Final Payment', 'wp-booking-system-stripe'),
                ];
            }
        }
    }

    foreach ($charge_ids as $charge_id) {

        try {
            $charge = WPBS_Stripe_GetCharge::retrieve($charge_id['id']);
        } catch (\Stripe\Exception\CardException $e) {
        } catch (\Stripe\Exception\RateLimitException $e) {
        } catch (\Stripe\Exception\InvalidRequestException $e) {
        } catch (\Stripe\Exception\AuthenticationException $e) {
        } catch (\Stripe\Exception\ApiConnectionException $e) {
        } catch (\Stripe\Exception\ApiErrorException $e) {
        } catch (Exception $e) {
        }

        if (isset($e)) {
            echo $e->getMessage();
            wp_die();
        }

        $charges[] = [
            'name' => $charge_id['name'],
            'id' => $charge->id,
            'amount' => $charge->amount / 100,
            'refunded' => $charge->amount_refunded / 100,
            'available' => ($charge->amount - $charge->amount_refunded) / 100,
        ];
    }

    return $charges;
}, 10, 3);

/**
 * Remove scheduled event
 *
 */
function wpbs_process_refund_stripe($charge_id, $amount, $payment)
{

    if (empty($charge_id)) {
        return ['error' => true, 'error_message' => 'Missing Charge ID'];
    }

    if (empty($amount)) {
        return ['error' => true, 'error_message' => 'Missing Amount'];
    }

    $amount = floatval($amount);

    // Include Stripe API
    include_once WPBS_STRIPE_PLUGIN_DIR . 'includes/libs/stripe-api/stripe-api.php';

    try {
        $refund_response = WPBS_Stripe_Refund::refundOrder($charge_id, $amount * 100);
    } catch (\Stripe\Exception\CardException $e) {
    } catch (\Stripe\Exception\RateLimitException $e) {
    } catch (\Stripe\Exception\InvalidRequestException $e) {
    } catch (\Stripe\Exception\AuthenticationException $e) {
    } catch (\Stripe\Exception\ApiConnectionException $e) {
    } catch (\Stripe\Exception\ApiErrorException $e) {
    } catch (Exception $e) {
    }

    if (isset($e)) {
        return ['error' => true, 'error_message' => $e->getMessage()];
    }

    if (isset($refund_response)) {
        return ['success' => true, 'response' => $refund_response, 'amount' => ($refund_response->amount / 100)];
    }
}
