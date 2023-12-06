<?php

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Generates the contract for a given booking
 *
 */
function wpbs_cntrct_generate_contract()
{

    // Check if there's an contract hash in the URL
    if (!isset($_GET['wpbs-contract']) || empty($_GET['wpbs-contract'])) {
        return;
    }

    $hash = sanitize_text_field($_GET['wpbs-contract']);

    $booking = wpbs_get_booking_by_hash($hash);

    if ($booking === false) {
        return false;
    }

    $output = (isset($_GET['dl']) && $_GET['dl'] == 1) ? 'D' : 'I';

    // Get the contract
    $contract = new WPBS_Contract($booking, $output);

    die();

}
add_action('init', 'wpbs_cntrct_generate_contract');
