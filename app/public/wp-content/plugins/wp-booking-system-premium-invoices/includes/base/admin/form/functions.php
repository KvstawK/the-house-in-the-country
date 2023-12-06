<?php

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Add Invoice Settings tab to form editor page
 *
 * @param array $tabs
 *
 * @return array
 *
 */
function wpbs_submenu_page_edit_form_tabs_invoices($tabs)
{

    if (!wpbs_is_pricing_enabled()) {
        return $tabs;
    }

    $tabs['form-options']['invoice'] = __('Invoice Settings', 'wp-booking-system-invoices');

    return $tabs;
}
add_filter('wpbs_submenu_page_edit_form_sub_tabs', 'wpbs_submenu_page_edit_form_tabs_invoices', 10, 1);

/**
 * Add Invoice Settings tab content to form editor page
 *
 */
function wpbs_submenu_page_edit_form_tab_invoices()
{
    include 'views/view-edit-form-tab-invoice.php';
}
add_action('wpbs_submenu_page_edit_form_tabs_form_options_invoice', 'wpbs_submenu_page_edit_form_tab_invoices');

/**
 * Save meta fields when form is saved
 *
 * @param array $meta_fields
 *
 * @return array
 *
 */
function wpbs_invc_edit_forms_meta_fields($meta_fields)
{

    // Save meta fields
    $meta_fields['invoice_buyer_details'] = array('translations' => false, 'sanitization' => 'sanitize_textarea_field');
    $meta_fields['invoice_seller_details'] = array('translations' => true, 'sanitization' => 'sanitize_textarea_field');
    $meta_fields['invoice_footer_notes_heading'] = array('translations' => true, 'sanitization' => 'sanitize_textarea_field');
    $meta_fields['invoice_footer_notes'] = array('translations' => true, 'sanitization' => 'sanitize_textarea_field');

    foreach (wpbs_invc_get_attachment_email_types() as $email_type => $email_name) {
        $meta_fields['invoice_attach_to_' . $email_type . '_email'] = array('translations' => false, 'sanitization' => 'sanitize_text_field', 'checkbox' => true);
    }

    return $meta_fields;
}
add_filter('wpbs_edit_forms_meta_fields', 'wpbs_invc_edit_forms_meta_fields', 10, 1);

/**
 * Generates the invoice and attaches it to a form notification email
 *
 * @param array $attachments
 * @param string $type
 * @param WPBS_Form $form
 * @param WPBS_Calendar $calendar
 * @param int $booking_id
 *
 */
function wpbs_invc_form_mailer_attachments($attachments, $type, $form, $calendar, $booking_id)
{

    if (wpbs_get_form_meta($form->get('id'), 'invoice_attach_to_' . $type . '_email', true) !== 'on') {
        return $attachments;
    }

    // Get the booking
    $booking = wpbs_get_booking($booking_id);

    // Save the invoice and attach it to the email
    $invoice = new WPBS_Invoice($booking, 'F');
    $attachments[] = $invoice->get_invoice_file_name();

    return $attachments;
}
add_filter('wpbs_form_mailer_attachments', 'wpbs_invc_form_mailer_attachments', 10, 5);

/**
 * Generates the invoice and attaches it to a booking email
 *
 * @param array $attachments
 * @param string $type
 * @param WPBS_Booking $booking
 * @param array $post_data
 *
 */
function wpbs_invc_booking_mailer_attachments($attachments, $type, $booking, $post_data)
{
    
    // Check if we're sending to the $email_type
    if (!isset($post_data['booking_email_' . $type . '_attach_invoice']) || $post_data['booking_email_' . $type . '_attach_invoice'] !== 'on') {
        return $attachments;
    }

    // Save the invoice and attach it to the email
    $invoice = new WPBS_Invoice($booking, 'F');
    $attachments[] = $invoice->get_invoice_file_name();

    return $attachments;
}
add_filter('wpbs_booking_mailer_attachments', 'wpbs_invc_booking_mailer_attachments', 10, 4);

/**
 * The default email types attachments can be added to.
 *
 */
function wpbs_invc_get_attachment_email_types()
{
    return apply_filters('wpbs_invoice_attachment_email_types', array('user' => 'User', 'admin' => 'Admin'));
}

/**
 * Include the Payment Reminder email in the Invoice Settings tab
 *
 * @param array $email_types
 *
 * @return array
 *
 */
function wpbs_part_payments_invoice_attachment($email_types)
{
    if (!wpbs_part_payments_enabled()) {
        return $email_types;
    }

    $email_types['payment'] = __('Payment Reminder', 'wp-booking-system-invoices');

    return $email_types;
}
add_filter('wpbs_invoice_attachment_email_types', 'wpbs_part_payments_invoice_attachment', 10, 1);

/**
 * Include the Email Reminder email in the Invoice Settings tab
 *
 * @param array $email_types
 *
 * @return array
 *
 */
function wpbs_email_reminders_invoice_attachment($email_types)
{
    if (!defined('WPBS_ER_VERSION')) {
        return $email_types;
    }

    $email_types['reminder'] = __('Reminder', 'wp-booking-system-invoices');
    $email_types['followup'] = __('Follow Up', 'wp-booking-system-invoices');

    return $email_types;
}
add_filter('wpbs_invoice_attachment_email_types', 'wpbs_email_reminders_invoice_attachment', 20, 1);
