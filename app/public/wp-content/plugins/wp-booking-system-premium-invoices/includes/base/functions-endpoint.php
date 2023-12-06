<?php

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Generates the invoice for a given booking
 *
 */
function wpbs_invc_generate_invoice()
{

    // Check if there's an invoice hash in the URL
    if (!isset($_GET['wpbs-invoice']) || empty($_GET['wpbs-invoice'])) {
        return;
    }

    $hash = sanitize_text_field($_GET['wpbs-invoice']);

    $booking = wpbs_get_booking_by_hash($hash);

    if ($booking === false) {
        return false;
    }

    // Also check if a payment was made
    $payments = wpbs_get_payments(array('booking_id' => $booking->get('id')));

    if (empty($payments)) {
        return false;
    }

    $output = (isset($_GET['dl']) && $_GET['dl'] == 1) ? 'D' : 'I';

    // Get the invoice
    $invoice = new WPBS_Invoice($booking, $output);

    die();

}
add_action('init', 'wpbs_invc_generate_invoice');
