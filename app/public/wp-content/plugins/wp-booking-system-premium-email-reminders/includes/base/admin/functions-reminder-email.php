<?php

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Add Email Reminder tab to form editor page
 *
 * @param array $tabs
 *
 * @return array
 *
 */
function wpbs_submenu_page_edit_form_tabs_email_reminder($tabs)
{

    $tabs['email-notifications']['email_reminder'] = __('Email Reminder Notification', 'wp-booking-system-email-reminders');

    return $tabs;
}
add_filter('wpbs_submenu_page_edit_form_sub_tabs', 'wpbs_submenu_page_edit_form_tabs_email_reminder', 10, 1);

/**
 * Add Email Reminder tab content to form editor page
 *
 */
function wpbs_submenu_page_edit_form_tab_email_reminder()
{
    include 'form/views/view-edit-form-tab-email-reminder.php';
}
add_action('wpbs_submenu_page_edit_form_tabs_email_notifications_email_reminder', 'wpbs_submenu_page_edit_form_tab_email_reminder');


/**
 * Save meta fields when form is saved
 *
 * @param array $meta_fields
 *
 * @return array
 *
 */
function wpbs_er_edit_forms_meta_fields_reminder_email($meta_fields)
{

    // Email Reminder Notification
    $meta_fields['reminder_notification_enable'] = array('translations' => false, 'sanitization' => 'sanitize_text_field', 'checkbox' => true);
    $meta_fields['reminder_notification_when_to_send'] = array('translations' => false, 'sanitization' => 'sanitize_text_field');
    $meta_fields['reminder_notification_send_to'] = array('translations' => false, 'sanitization' => 'sanitize_text_field');
    $meta_fields['reminder_notification_send_to_cc'] = array('translations' => false, 'sanitization' => 'sanitize_text_field');
    $meta_fields['reminder_notification_send_to_bcc'] = array('translations' => false, 'sanitization' => 'sanitize_text_field');
    $meta_fields['reminder_notification_from_name'] = array('translations' => false, 'sanitization' => 'sanitize_text_field');
    $meta_fields['reminder_notification_from_email'] = array('translations' => false, 'sanitization' => 'sanitize_text_field');
    $meta_fields['reminder_notification_reply_to'] = array('translations' => false, 'sanitization' => 'sanitize_text_field');
    $meta_fields['reminder_notification_subject'] = array('translations' => true, 'sanitization' => 'sanitize_text_field');
    $meta_fields['reminder_notification_message'] = array('translations' => true, 'sanitization' => 'wp_kses_post');
    $meta_fields['reminder_notification_ical_file'] = array('translations' => false, 'sanitization' => 'sanitize_text_field', 'checkbox' => true);
    $meta_fields['reminder_notification_ical_summary'] = array('translations' => true, 'sanitization' => 'sanitize_text_field');
    $meta_fields['reminder_notification_ical_description'] = array('translations' => true, 'sanitization' => 'sanitize_text_field');

    return $meta_fields;
}
add_filter('wpbs_edit_forms_meta_fields', 'wpbs_er_edit_forms_meta_fields_reminder_email', 10, 1);

/**
 * Schedule reminder email
 *
 * @param WPBS_Form     $form
 * @param WPBS_Calendar $calendar
 * @param int           $booking_id
 * @param array         $form_fields
 * @param string        $language
 * @param timestamp     $start_date
 * @param timestamp     $end_date
 * 
 */
function wpbs_er_submit_form_reminder_email($form, $calendar, $booking_id, $form_fields, $language, $start_date, $end_date)
{

    if (wpbs_get_form_meta($form->get('id'), 'reminder_notification_enable', true) != 'on') {
        return false;
    }

    // Check if a email was submitted
    $has_email = false;
    foreach ($form_fields as $field) {
        if ($field['type'] == 'email' && isset($field['user_value']) && $field['user_value']) {
            $has_email = true;
            break;
        }
    }

    if ($has_email == false) {
        return false;
    }

    // When to send?
    $days_before = wpbs_get_form_meta($form->get('id'), 'reminder_notification_when_to_send', true) * DAY_IN_SECONDS;
    $when_to_send = $start_date - $days_before;

    if(function_exists('wpbs_scheduled_email_delivery_hour')){
        $when_to_send += wpbs_scheduled_email_delivery_hour();
    }

    // Schedule the email
    wp_schedule_single_event($when_to_send, 'wpbs_er_reminder_email', array($form, $calendar, $booking_id, $form_fields, $language, $start_date, $end_date));
}
add_action('wpbs_submit_form_emails', 'wpbs_er_submit_form_reminder_email', 20, 7);

/**
 * Callback function for setting the email reminder schedule.
 * 
 */
function wpbs_er_reminder_email($form, $calendar, $booking_id, $form_fields, $language, $start_date, $end_date, $deprecated = false)
{

    $booking = wpbs_get_booking($booking_id);

    if (is_null($booking)) {
        return false;
    }

    if($booking->get('status') == 'trash'){
        return false;
    }

    if($booking->get('status') == 'pending'){

        // Reschedule the event to be sent after an hour
        if($start_date < current_time('timestamp')){
            return false;
        }

        $when_to_send = current_time('timestamp') + HOUR_IN_SECONDS;
        wp_schedule_single_event($when_to_send, 'wpbs_er_reminder_email', array($form, $calendar, $booking_id, $form_fields, $language, $start_date, $end_date));

        return false;
    }

    $email = new WPBS_Form_Mailer($form, $calendar, $booking_id, $form_fields, $language, $start_date, $end_date);
    $email->prepare('reminder');
    $email->send();

    wpbs_delete_temporary_files($booking_id);

}
add_action('wpbs_er_reminder_email', 'wpbs_er_reminder_email', 10, 7);
