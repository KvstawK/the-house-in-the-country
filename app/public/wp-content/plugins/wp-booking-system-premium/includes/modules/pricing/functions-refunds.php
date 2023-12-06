<?php

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * AJAX callback for manually processing refunds
 *
 */
function wpbs_action_ajax_wpbs_process_refund()
{
    if (!isset($_POST['booking_id'])) {
        wp_die();
    }

    $booking_id = absint($_POST['booking_id']);

    if (!isset($_POST['amount'])) {
        wp_die();
    }

    $charge_id = sanitize_text_field($_POST['charge_id']);

    if (!$charge_id) {
        wp_die();
    }

    $amount = floatval($_POST['amount']);

    $reason = sanitize_text_field($_POST['reason']);

    $response = wpbs_process_refund($booking_id, $charge_id, $amount, $reason);

    if (!isset($response['success'])) {
        echo json_encode($response);
        wp_die();
    }

    ob_start();
    wpbs_modal_payment_details_refunds_view(wpbs_get_payment_by_booking_id($booking_id));
    $html = ob_get_contents();
    ob_end_clean();

    echo json_encode([
        'success' => true,
        'html' => $html,
    ]);

    wp_die();
}
add_action('wp_ajax_wpbs_process_refund', 'wpbs_action_ajax_wpbs_process_refund');

/**
 * Process a refund
 *
 * @param int $booking_id
 * @param string $charge_id
 * @param float $amount
 * @param string $reason
 * @param string $type
 *
 * @return string
 *
 */
function wpbs_process_refund($booking_id, $charge_id, $amount, $reason = '', $type = 'manual')
{

    if ($amount <= 0) {
        return ['error' => true, 'error_message' => __('Amount must be greater than 0.', 'wp-booking-system')];
    }

    $payment = wpbs_get_payment_by_booking_id($booking_id);

    $payment_details = $payment->get('details');

    if (!function_exists('wpbs_process_refund_' . $payment->get('payment_gateway'))) {
        return ['error' => true, 'error_message' => __('Payment gateway refund functionality not implemented.', 'wp-booking-system')];
    }

    $refund_response = call_user_func_array('wpbs_process_refund_' . $payment->get('payment_gateway'), array($charge_id, $amount, $payment));

    if (isset($refund_response['error'])) {
        return $refund_response;
    }

    if (isset($refund_response['success'])) {

        $payment_details['refunds'][] = [
            'response' => $refund_response['response'],
            'amount' => $refund_response['amount'],
            'reason' => $reason,
            'date' => current_time('timestamp'),
            'charge' => $charge_id,
            'type' => $type,
        ];

        // Update payment with correct details
        wpbs_update_payment($payment->get('id'), array(
            'details' => $payment_details,
        ));

        return ['success' => true];
    }
}

/**
 * Refunds template file
 * 
 */
function wpbs_modal_payment_details_refunds_view($payment)
{
    include 'booking/views/view-modal-payment-details-content-refunds.php';
}

/**
 * Get all the charges for an order
 *
 * @param WPBS_Payment $payment
 *
 * @return array
 *
 */
function wpbs_payment_get_charges($payment)
{

    $charges = apply_filters('wpbs_payment_details_refund_charges', [], $payment);

    $refunded = 0;
    $available = 0;

    foreach ($charges as $charge) {
        $refunded += $charge['refunded'];
        $available += $charge['available'];
    }

    $status = 'not_refunded';

    if ($refunded > 0) {
        $status = 'partially_refunded';

        if ($available <= 0) {
            if (!$payment->is_part_payment() || $payment->is_final_payment_paid()) {
                $status = 'fully_refunded';
            }
        }
    }

    $payment_details = $payment->get('details');

    $payment_details['refund_status'] = $status;

    // Update payment with correct details
    wpbs_update_payment($payment->get('id'), array(
        'details' => $payment_details,
    ));

    return $charges;
}

/**
 * Check wheter a payment gateway supports refunds
 * 
 */
function wpbs_payment_gateway_supports_refunds($payment)
{
    return apply_filters('wpbs_payment_details_refunds_available', false, $payment);
}
