<?php

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Save booking data
 *
 */
function wpbs_save_booking_data($data)
{

    if (!isset($_POST['booking_id'])) {
        return false;
    }

    /**
     * Save Calendar Data
     */

    $booking_id = absint($_POST['booking_id']);

    // Get booking
    $booking = wpbs_get_booking($booking_id);

    // Get action
    $action = sanitize_text_field($_POST['booking_action']);

    // Set status
    if ($action == 'delete') {
        $status = 'trash';

        // Save current status
        wpbs_add_booking_meta($booking_id, 'before_trash_status', $booking->get('status'));
    } elseif ($action == 'restore') {

        // Get old status
        $status = (in_array(wpbs_get_booking_meta($booking_id, 'before_trash_status', true), array('pending', 'accepted'))) ? wpbs_get_booking_meta($booking_id, 'before_trash_status', true) : 'pending';

        // Delete it from the database
        wpbs_delete_booking_meta($booking_id, 'before_trash_status');
    } elseif ($action == 'accept') {

        do_action('wpbs_save_booking_data_accept_booking', $booking);

        $status = 'accepted';
    }

    // Prepare Data
    $booking_data = array(
        'status' => $status,
        'date_modified' => current_time('Y-m-d H:i:s'),
    );

    // Update Booking
    wpbs_update_booking($booking_id, $booking_data);

    /**
     * Send Email
     */

    $language = wpbs_get_booking_meta($booking_id, 'submitted_language', true);

    // Parse $_POST data
    parse_str($_POST['email_form_data'], $_POST['email_form_data']);

    // Check if we need to send an email
    if (isset($_POST['email_form_data']['booking_email_accept_booking_enable']) && !empty($_POST['email_form_data']['booking_email_accept_booking_enable'])) {

        switch_to_locale(wpbs_language_to_locale($language));

        $email_form_data = $_POST['email_form_data'];

        // Parse some form tags
        $email_tags = new WPBS_Email_Tags(wpbs_get_form($booking->get('form_id')), wpbs_get_calendar($booking->get('calendar_id')), $booking_id, $booking->get('fields'), $language, strtotime($booking->get('start_date')), strtotime($booking->get('end_date')));

        $email_form_data['booking_email_accept_booking_message'] = $email_tags->parse(do_shortcode(nl2br($email_form_data['booking_email_accept_booking_message'])));
        $email_form_data['booking_email_accept_booking_subject'] = $email_tags->parse($email_form_data['booking_email_accept_booking_subject']);

        // Send the email
        $mailer = new WPBS_Booking_Mailer($booking, $email_form_data);
        $mailer->prepare('accept_booking');
        $mailer->send();
    }
}
add_action('wpbs_save_calendar_data', 'wpbs_save_booking_data');

/**
 * Permanently Delete Booking
 *
 */
function wpbs_action_permanently_delete_booking()
{

    // Verify for nonce
    if (empty($_GET['wpbs_token']) || !wp_verify_nonce($_GET['wpbs_token'], 'wpbs_permanently_delete_booking')) {
        return;
    }

    if (empty($_GET['booking_id'])) {
        return;
    }

    if (empty($_GET['calendar_id'])) {
        return;
    }

    $booking_id = $_GET['booking_id'];

    $calendar_id = $_GET['calendar_id'];

    do_action('wpbs_permanently_delete_booking', $booking_id);

    // Delete Booking
    wpbs_delete_booking($booking_id);

    // Delete Booking Meta
    $booking_meta = wpbs_get_booking_meta($booking_id);
    if (!empty($booking_meta)) {
        foreach ($booking_meta as $key => $value) {
            wpbs_delete_booking_meta($booking_id, $key);
        }
    }

    // Redirect to the current page
    wp_redirect(add_query_arg(array('page' => 'wpbs-calendars', 'subpage' => 'edit-calendar', 'calendar_id' => $calendar_id, 'wpbs_message' => 'booking_permanently_delete_success'), admin_url('admin.php')));
}
add_action('wpbs_action_permanently_delete_booking', 'wpbs_action_permanently_delete_booking');


/**
 * Permanently Delete Booking
 *
 */
function wpbs_action_permanently_delete_all_bookings()
{

    // Verify for nonce
    if (empty($_GET['wpbs_token']) || !wp_verify_nonce($_GET['wpbs_token'], 'wpbs_permanently_delete_all_bookings')) {
        return;
    }

    if (empty($_GET['calendar_id'])) {
        return;
    }

    $calendar_id = absint($_GET['calendar_id']);

    do_action('wpbs_permanently_delete_all_bookings', $calendar_id);

    $bookings = wpbs_get_bookings(array('status' => ['trash'], 'calendar_id' => $calendar_id));

    // Delete Bookings
    foreach ($bookings as $booking) {
        wpbs_delete_booking($booking->get('id'));

        // Delete Booking Meta
        $booking_meta = wpbs_get_booking_meta($booking->get('id'));
        if (!empty($booking_meta)) {
            foreach ($booking_meta as $key => $value) {
                wpbs_delete_booking_meta($booking->get('id'), $key);
            }
        }
    }

    // Redirect to the current page
    wp_redirect(add_query_arg(array('page' => 'wpbs-calendars', 'subpage' => 'edit-calendar', 'calendar_id' => $calendar_id, 'wpbs_message' => 'bookings_permanently_delete_success'), admin_url('admin.php')));
}
add_action('wpbs_action_permanently_delete_all_bookings', 'wpbs_action_permanently_delete_all_bookings');

/**
 * Move Booking
 * 
 */
function wpbs_action_move_booking()
{

    // Verify for nonce
    if (empty($_POST['wpbs_token']) || !wp_verify_nonce($_POST['wpbs_token'], 'wpbs_move_booking')) {
        return;
    }

    if (!isset($_POST['new_calendar_id']) || empty($_POST['new_calendar_id'])) {
        return;
    }

    $new_calendar_id = absint($_POST['new_calendar_id']);

    $calendar = wpbs_get_calendar($new_calendar_id);

    if (!$calendar) {
        return;
    }

    if (!isset($_POST['booking_id']) || empty($_POST['booking_id'])) {
        return;
    }

    $booking_id = absint($_POST['booking_id']);

    $booking_data = array(
        'calendar_id' => $new_calendar_id,
    );

    // Update Booking
    wpbs_update_booking($booking_id, $booking_data);

    $editable_crons = wpbs_get_editable_crons();

    foreach ($editable_crons as $field_name => $cron_name) {

        $crons = _get_cron_array();
        foreach ($crons as $timestamp => $cron) {
            if (isset($cron[$cron_name])) {
                foreach ($cron[$cron_name] as $job_id => $job) {
                    if ($job['args'][2] == $booking_id) {

                        // Update cron data
                        $job['args'][1] = $calendar;

                        unset($crons[$timestamp][$cron_name][$job_id]);
                        $new_job_id = md5(serialize($job['args']));
                        $crons[$timestamp][$cron_name][$new_job_id] = $job;

                        // Save
                        _set_cron_array($crons);
                        break;
                    }
                }
            }
        }
    }

    wp_redirect(add_query_arg(array('page' => 'wpbs-calendars', 'subpage' => 'edit-calendar', 'calendar_id' => $new_calendar_id, 'wpbs_message' => 'booking_moved_success'), admin_url('admin.php')));
}
add_action('wpbs_action_move_booking', 'wpbs_action_move_booking');

/**
 * AJAX loaded bookings ourputter
 * 
 */
function wpbs_bookings_outputter_ajax()
{

    $bookings_outputter = new WPBS_Bookings_Outputter($_POST['data']['calendarId'], $_POST['data']);
    $bookings_outputter->display();

    wp_die();
}
add_action('wp_ajax_wpbs_bookings_outputter_ajax', 'wpbs_bookings_outputter_ajax');

/**
 * Save "Include Booking Details" option
 *
 */
function wpbs_action_ajax_booking_modal_remember_include_booking_details()
{
    // Nonce
    update_user_meta(get_current_user_id(), 'wpbs_remember_include_booking_details', ($_POST['value'] == 'true' ? true : false));
    wp_die();
}
add_action('wp_ajax_wpbs_booking_modal_remember_include_booking_details', 'wpbs_action_ajax_booking_modal_remember_include_booking_details');
