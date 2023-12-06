<?php

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Includes the Base files
 *
 */
function wpbs_cntrct_include_files_base()
{

    // Get legend admin dir path
    $dir_path = plugin_dir_path(__FILE__);

    // Include endpoint functions file
    if (file_exists($dir_path . 'functions-endpoint.php')) {
        include $dir_path . 'functions-endpoint.php';
    }

    // Include contract class
    if (file_exists($dir_path . 'class-contract.php')) {
        include $dir_path . 'class-contract.php';
    }

}
add_action('wpbs_cntrct_include_files', 'wpbs_cntrct_include_files_base');

/**
 * Helper function to get the contract link
 * 
 * @param string $hash
 * @param bool $download
 * 
 * @return string
 * 
 */
function wpbs_get_contract_link($hash, $download = false)
{
    return add_query_arg(
        array(
            'wpbs-contract' => $hash,
            'dl' => $download
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

function wpbs_submit_form_after_save_contract_details($booking_id, $post_data, $form, $form_args, $form_fields)
{
    $contract_content = wpbs_get_translated_form_meta($form->get('id'), 'contract_content', $form_args['language']);

    $booking = wpbs_get_booking($booking_id);

    $calendar = wpbs_get_calendar($booking->get('calendar_id'));

    $email_tags = new WPBS_Email_Tags($form, $calendar, $booking_id, $form_fields, $form_args['language'], strtotime($booking->get('start_date')), strtotime($booking->get('end_date')));
    $contract_content = $email_tags->parse($contract_content);

    wpbs_add_booking_meta($booking_id, 'contract_content', $contract_content);
    
}
add_action('wpbs_submit_form_after', 'wpbs_submit_form_after_save_contract_details', 50, 5);
