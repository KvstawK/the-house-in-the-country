<?php

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Add contract Settings tab to form editor page
 *
 * @param array $tabs
 *
 * @return array
 *
 */
function wpbs_submenu_page_edit_form_tabs_contracts($tabs)
{

    $tabs['form-options']['contract'] = __('Contract Settings', 'wp-booking-system-contracts');

    return $tabs;
}
add_filter('wpbs_submenu_page_edit_form_sub_tabs', 'wpbs_submenu_page_edit_form_tabs_contracts', 10, 1);

/**
 * Add contract Settings tab content to form editor page
 *
 */
function wpbs_submenu_page_edit_form_tab_contracts()
{
    include 'views/view-edit-form-tab-contract.php';
}
add_action('wpbs_submenu_page_edit_form_tabs_form_options_contract', 'wpbs_submenu_page_edit_form_tab_contracts');

/**
 * Save meta fields when form is saved
 *
 * @param array $meta_fields
 *
 * @return array
 *
 */
function wpbs_cntrct_edit_forms_meta_fields($meta_fields)
{

    // Save meta fields
    $meta_fields['contract_content'] = array('translations' => true, 'sanitization' => 'wp_kses_post');

    foreach (wpbs_cntrct_get_attachment_email_types() as $email_type => $email_name) {
        $meta_fields['contract_attach_to_' . $email_type . '_email'] = array('translations' => false, 'sanitization' => 'sanitize_text_field', 'checkbox' => true);
    }

    return $meta_fields;
}
add_filter('wpbs_edit_forms_meta_fields', 'wpbs_cntrct_edit_forms_meta_fields', 10, 1);

/**
 * Generates the contract and attaches it to a form notification email
 *
 * @param array $attachments
 * @param string $type
 * @param WPBS_Form $form
 * @param WPBS_Calendar $calendar
 * @param int $booking_id
 *
 */
function wpbs_cntrct_form_mailer_attachments($attachments, $type, $form, $calendar, $booking_id)
{

    if (wpbs_get_form_meta($form->get('id'), 'contract_attach_to_' . $type . '_email', true) !== 'on') {
        return $attachments;
    }

    // Get the booking
    $booking = wpbs_get_booking($booking_id);

    // Save the contract and attach it to the email
    $contract = new WPBS_Contract($booking, 'F');
    $attachments[] = $contract->get_contract_file_name();

    return $attachments;
}
add_filter('wpbs_form_mailer_attachments', 'wpbs_cntrct_form_mailer_attachments', 10, 5);

/**
 * Generates the contract and attaches it to a booking email
 *
 * @param array $attachments
 * @param string $type
 * @param WPBS_Booking $booking
 * @param array $post_data
 *
 */
function wpbs_cntrct_booking_mailer_attachments($attachments, $type, $booking, $post_data)
{
    
    // Check if we're sending to the $email_type
    if (!isset($post_data['booking_email_' . $type . '_attach_contract']) || $post_data['booking_email_' . $type . '_attach_contract'] !== 'on') {
        return $attachments;
    }

    // Save the contract and attach it to the email
    $contract = new WPBS_Contract($booking, 'F');
    $attachments[] = $contract->get_contract_file_name();

    return $attachments;
}
add_filter('wpbs_booking_mailer_attachments', 'wpbs_cntrct_booking_mailer_attachments', 10, 4);

/**
 * The default email types attachments can be added to.
 *
 */
function wpbs_cntrct_get_attachment_email_types()
{
    return apply_filters('wpbs_contract_attachment_email_types', array('user' => 'User', 'admin' => 'Admin'));
}

/**
 * Include the Payment Reminder email in the contract Settings tab
 *
 * @param array $email_types
 *
 * @return array
 *
 */
function wpbs_part_payments_contract_attachment($email_types)
{

    if (!wpbs_is_pricing_enabled()) {
        return $email_types;
    }

    if (!wpbs_part_payments_enabled()) {
        return $email_types;
    }

    $email_types['payment'] = __('Payment Reminder', 'wp-booking-system');

    return $email_types;
}
add_filter('wpbs_contract_attachment_email_types', 'wpbs_part_payments_contract_attachment', 10, 1);

/**
 * Include the Email Reminder email in the contract Settings tab
 *
 * @param array $email_types
 *
 * @return array
 *
 */
function wpbs_email_reminders_contract_attachment($email_types)
{
    if (!defined('WPBS_ER_VERSION')) {
        return $email_types;
    }

    $email_types['reminder'] = __('Reminder', 'wp-booking-system');
    $email_types['followup'] = __('Follow Up', 'wp-booking-system');

    return $email_types;
}
add_filter('wpbs_contract_attachment_email_types', 'wpbs_email_reminders_contract_attachment', 20, 1);


/**
 * Add Signature Field to Form Builder
 *
 */
function wpbs_form_available_field_types_signature($fields)
{

    if (!wpbs_is_pricing_enabled()) {
        return $fields;
    }

    $fields['signature'] = array(
        'type' => 'signature',
        'group' => 'advanced',
        'supports' => array(
            'primary' => array('label', 'required'),
            'secondary' => array('description', 'layout', 'class', 'hide_label'),
        ),
        'values' => array(),
    );

    return $fields;
}
add_filter('wpbs_form_available_field_types', 'wpbs_form_available_field_types_signature', 10, 1);