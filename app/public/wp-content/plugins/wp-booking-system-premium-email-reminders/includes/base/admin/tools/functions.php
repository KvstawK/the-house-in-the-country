<?php

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Adds the HTML for the Cron Regeneration tab
 *
 */
function wpbs_submenu_page_settings_tab_regenerate_crons()
{

    include 'views/view-regenerate-crons.php';

}
add_action('wpbs_submenu_page_settings_tab_tools', 'wpbs_submenu_page_settings_tab_regenerate_crons', 20, 1);

/**
 * Registers the admin notices
 *
 */
function wpbs_register_admin_notices_regenerate_cron_jobs()
{

    if (empty($_GET['wpbs_message'])) {
        return;
    }

    /**
     * Register website notices
     *
     */
    wpbs_admin_notices()->register_notice('cron_regenerate_success', '<p>' . __('Cron jobs successfully regenerated.', 'wp-booking-system') . '</p>');

}
add_action('admin_init', 'wpbs_register_admin_notices_regenerate_cron_jobs');

/**
 * Action that regenerates cron jobs
 *
 */
function wpbs_action_regenerate_cron_jobs()
{

    // Verify for nonce
    if (empty($_GET['wpbs_token']) || !wp_verify_nonce($_GET['wpbs_token'], 'wpbs_regenerate_cron_jobs')) {
        return;
    }

    // Delete crons
    $crons = _get_cron_array();

    foreach ($crons as $timestamp => $cron) {
        if (isset($cron['wpbs_er_reminder_email'])) {
            unset($crons[$timestamp]['wpbs_er_reminder_email']);
        }
        if (isset($cron['wpbs_er_follow_up_email'])) {
            unset($crons[$timestamp]['wpbs_er_follow_up_email']);
        }
    }

    foreach ($crons as $timestamp => $cron) {
		if(!$cron){
			unset($crons[$timestamp]);
		}
	}

    _set_cron_array($crons);

    $bookings = wpbs_get_bookings();

    // Reschedule Reminder emails
    foreach ($bookings as $booking) {

        if ($booking->get('status') == 'trash') {
            continue;
        }

        if (wpbs_get_form_meta($booking->get('form_id'), 'reminder_notification_enable', true) != 'on') {
            continue;
        }

        $start_date = strtotime($booking->get('start_date'));
        $end_date = strtotime($booking->get('end_date'));

        if ($start_date < current_time('timestamp')) {
            continue;
        }

        // When to send?
        $days_before = wpbs_get_form_meta($booking->get('form_id'), 'reminder_notification_when_to_send', true) * DAY_IN_SECONDS;
        $when_to_send = $start_date - $days_before;

        if(function_exists('wpbs_scheduled_email_delivery_hour')){
            $when_to_send += wpbs_scheduled_email_delivery_hour();
        }

        if ($when_to_send < current_time('timestamp')) {
            continue;
        }

        $form = wpbs_get_form($booking->get('form_id'));
        $calendar = wpbs_get_calendar($booking->get('calendar_id'));

        // Schedule the email
        wp_schedule_single_event($when_to_send, 'wpbs_er_reminder_email', array($form, $calendar, $booking->get('id'), $booking->get('fields'), wpbs_get_booking_meta($booking->get('id'), 'submitted_language', true), $start_date, $end_date));
    }


    // Reschedule Follow-up emails
    foreach ($bookings as $booking) {

        if ($booking->get('status') == 'trash') {
            continue;
        }

        if (wpbs_get_form_meta($booking->get('form_id'), 'followup_notification_enable', true) != 'on') {
            continue;
        }

        $start_date = strtotime($booking->get('start_date'));
        $end_date = strtotime($booking->get('end_date'));

        // When to send?
        $days_after = wpbs_get_form_meta($booking->get('form_id'), 'followup_notification_when_to_send', true) * DAY_IN_SECONDS;
        $when_to_send = $end_date + $days_after;

        if(function_exists('wpbs_scheduled_email_delivery_hour')){
            $when_to_send += wpbs_scheduled_email_delivery_hour();
        }

        if ($when_to_send < current_time('timestamp')) {
            continue;
        }

        $form = wpbs_get_form($booking->get('form_id'));
        $calendar = wpbs_get_calendar($booking->get('calendar_id'));

        // Schedule the email
        wp_schedule_single_event($when_to_send, 'wpbs_er_follow_up_email', array($form, $calendar, $booking->get('id'), $booking->get('fields'), wpbs_get_booking_meta($booking->get('id'), 'submitted_language', true), $start_date, $end_date));
    }

    // Redirect to the current page
    wp_redirect(add_query_arg(array('page' => 'wpbs-settings', 'tab' => 'tools', 'wpbs_message' => 'cron_regenerate_success'), admin_url('admin.php')));
    exit;

}
add_action('wpbs_action_regenerate_cron_jobs', 'wpbs_action_regenerate_cron_jobs');
