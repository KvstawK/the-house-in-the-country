<?php

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Add Pricing Tab to Booking Details
 *
 */
function wpbs_booking_modal_add_payment_tab($tabs)
{
    if (!wpbs_is_pricing_enabled()) {
        return $tabs;
    }

    $tabs['payment-details'] = '<svg aria-hidden="true" focusable="false" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 512"><path fill="currentColor" d="M625.941 293.823L421.823 497.941c-18.746 18.746-49.138 18.745-67.882 0l-1.775-1.775 22.627-22.627 1.775 1.775c6.253 6.253 16.384 6.243 22.627 0l204.118-204.118c6.238-6.239 6.238-16.389 0-22.627L391.431 36.686A15.895 15.895 0 0 0 380.117 32h-19.549l-32-32h51.549a48 48 0 0 1 33.941 14.059L625.94 225.941c18.746 18.745 18.746 49.137.001 67.882zM252.118 32H48c-8.822 0-16 7.178-16 16v204.118c0 4.274 1.664 8.292 4.686 11.314l211.882 211.882c6.253 6.253 16.384 6.243 22.627 0l204.118-204.118c6.238-6.239 6.238-16.389 0-22.627L263.431 36.686A15.895 15.895 0 0 0 252.118 32m0-32a48 48 0 0 1 33.941 14.059l211.882 211.882c18.745 18.745 18.745 49.137 0 67.882L293.823 497.941c-18.746 18.746-49.138 18.745-67.882 0L14.059 286.059A48 48 0 0 1 0 252.118V48C0 21.49 21.49 0 48 0h204.118zM144 124c-11.028 0-20 8.972-20 20s8.972 20 20 20 20-8.972 20-20-8.972-20-20-20m0-28c26.51 0 48 21.49 48 48s-21.49 48-48 48-48-21.49-48-48 21.49-48 48-48z"></path></svg>' . __('Payment Details', 'wp-booking-system');
    return $tabs;
}
add_action('wpbs_booking_modal_tabs', 'wpbs_booking_modal_add_payment_tab', 10, 1);

/**
 * Add Pricing Tab view
 *
 */
function wpbs_booking_modal_add_payment_view($booking, $calendar)
{
    if (!wpbs_is_pricing_enabled()) {
        return false;
    }

    include 'views/view-modal-payment-details.php';
}
add_action('wpbs_booking_modal_tab_payment-details', 'wpbs_booking_modal_add_payment_view', 10, 2);

/**
 * Default message for no payment received.
 *
 * @param WPBS_Booking
 *
 */
function wpbs_booking_modal_payment_tab_content_no_payment($booking)
{
    if (!wpbs_is_pricing_enabled()) {
        return false;
    }

    $payments = wpbs_get_payments(array('booking_id' => $booking->get('id')));

    // Check if there is an order for this booking
    if (empty($payments)) {
        echo '<h3>' . __('No payment was received for this booking.', 'wp-booking-system') . '</h3>';
    }
}
add_action('wpbs_booking_modal_tab_content_payment', 'wpbs_booking_modal_payment_tab_content_no_payment', 20, 1);