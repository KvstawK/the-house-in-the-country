<?php

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Add payment details to the Booking Modal
 *
 * @param WPBS_Booking
 *
 */
function wpbs_stripe_booking_modal_payment_tab_content($booking)
{
    $payment = wpbs_get_payment_by_booking_id($booking->get('id'));

    // Check if there is an order for this booking
    if (empty($payment)) {
        return false;
    }

    // Check if it's a Stripe order
    if ($payment->get('gateway') != 'stripe') {
        return false;
    }

    $order_details = $payment->get('details');
    $stripe_data = $order_details['raw'];

    $order_status = $payment->get('order_status');

    if ($order_status == 'authorized' && strtotime($payment->get('date_created')) < current_time('timestamp') - (DAY_IN_SECONDS * 7)) {
        $order_status = 'error';
        $order_details['error'] = __('Authorization expired. You can still accept the booking but no payment will be made.', 'wp-booking-system-stripe');
    }
    // Payment Information
    $payment_information = array(
        array('label' => __('Order Status', 'wp-booking-system-stripe'), 'value' => ucwords($order_status)),
        array('label' => __('Payment Gateway', 'wp-booking-system-stripe'), 'value' => 'Stripe'),
        array('label' => __('Date', 'wp-booking-system-stripe'), 'value' => date('j F Y, H:i:s', strtotime($payment->get('date_created')))),
        array('label' => __('ID', 'wp-booking-system-stripe'), 'value' => '#' . $payment->get('id')),
    );

    if ($order_status == 'error') {
        $payment_information[] = array('label' => __('Error', 'wp-booking-system-stripe'), 'value' => $order_details['error']);
    }

    if (isset($stripe_data['id'])) {
        $link = 'https://dashboard.stripe.com/' . (empty($stripe_data['livemode']) ? 'test/' : '') . 'payments/' . $stripe_data['id'];
        $payment_information[] = array('label' => __('Transaction ID', 'wp-booking-system-stripe'), 'value' => $stripe_data['id'] . ' (<a href="' . $link . '" target="_blank">' . __('Open in Stripe', 'wp-booking-system-stripe') . '</a>)');
    }

    if (isset($stripe_data['charges']['data'][0]['billing_details']['name'])) {
        $payment_information[] = array('label' => __('Buyer Name', 'wp-booking-system-stripe'), 'value' => $stripe_data['charges']['data'][0]['billing_details']['name']);
    }

    // Order Information

    $order_information = $payment->get_line_items();

    $order_information = apply_filters('wpbs_booking_details_order_information', $order_information, $payment);

    $amount_received = (isset($stripe_data['amount_received'])) ? ($stripe_data['amount_received'] / 100) : 0;

    $order_information[] = array('label' => __('Amount Received', 'wp-booking-system-stripe'), 'value' => wpbs_get_formatted_price($amount_received, strtoupper($payment->get_currency())));

    // Include view file
    include WPBS_PLUGIN_DIR . '/includes/modules/pricing/booking/views/view-modal-payment-details-content.php';

}
add_action('wpbs_booking_modal_tab_content_payment', 'wpbs_stripe_booking_modal_payment_tab_content', 10, 1);
