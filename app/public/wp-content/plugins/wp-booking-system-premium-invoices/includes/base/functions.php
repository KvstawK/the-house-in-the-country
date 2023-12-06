<?php

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Includes the Base files
 *
 */
function wpbs_invc_include_files_base()
{

    // Get legend admin dir path
    $dir_path = plugin_dir_path(__FILE__);

    // Include endpoint functions file
    if (file_exists($dir_path . 'functions-endpoint.php')) {
        include $dir_path . 'functions-endpoint.php';
    }

    // Include invoice class
    if (file_exists($dir_path . 'class-invoice.php')) {
        include $dir_path . 'class-invoice.php';
    }

}
add_action('wpbs_invc_include_files', 'wpbs_invc_include_files_base');

/**
 * Add an invoice hash to all the existing bookings.
 *
 */
function wpbs_invc_update_booking_hashes($db_version)
{
    $bookings = wpbs_get_bookings();

    foreach ($bookings as $booking) {
        $hash = $booking->get('invoice_hash');
        if (!empty($hash)) {
            continue;
        }

        wpbs_update_booking($booking->get('id'), array('invoice_hash' => wpbs_generate_hash()));

    }
}
add_action('wpbs_invc_update_check', 'wpbs_invc_update_booking_hashes', 10, 1);

/**
 * Helper function to get the invoice link
 *
 * @param string $hash
 * @param bool $download
 *
 * @return string
 *
 */
function wpbs_get_invoice_link($hash, $download = false)
{
    return add_query_arg(
        array(
            'wpbs-invoice' => $hash,
            'dl' => $download,
        ),
        site_url()
    );
}

/**
 * Parses the Buyer Details and saves them in the booking_meta table.
 *
 * @param int $booking_id
 * @param array $post_data
 * @param WPBS_Form $form
 * @param array $form_args
 * @param array $form_fields
 *
 */
function wpbs_submit_form_after_save_buyer_details($booking_id, $post_data, $form, $form_args, $form_fields)
{
    $buyer_details = wpbs_get_form_meta($form->get('id'), 'invoice_buyer_details', true);

    $booking = wpbs_get_booking($booking_id);

    $calendar = wpbs_get_calendar($booking->get('calendar_id'));

    $booking = wpbs_get_booking($booking_id);

    $email_tags = new WPBS_Email_Tags($form, $calendar, $booking_id, $form_fields, $form_args['language'],  strtotime($booking->get('start_date')), strtotime($booking->get('end_date')));
    $buyer_details = $email_tags->parse($buyer_details);

    wpbs_add_booking_meta($booking_id, 'invoice_buyer_details', $buyer_details);
}
add_action('wpbs_submit_form_after', 'wpbs_submit_form_after_save_buyer_details', 99, 5);
