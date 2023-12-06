<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;


/**
 * Adds a new tab to the Settings page of the plugin
 *
 * @param array $tabs
 *
 * @return $tabs
 *
 */
function wpbs_submenu_page_settings_tabs_tools( $tabs ) {

	$tabs['tools'] = __( 'Tools', 'wp-booking-system' );

	return $tabs;

}
add_filter( 'wpbs_submenu_page_settings_tabs', 'wpbs_submenu_page_settings_tabs_tools', 100 );


/**
 * Adds the HTML for the Tools tab
 *
 */
function wpbs_submenu_page_settings_tab_tools() {

	include 'views/view-tools.php';

}
add_action( 'wpbs_submenu_page_settings_tab_tools', 'wpbs_submenu_page_settings_tab_tools' );


/**
 * Action that uninstalls the plugin
 *
 */
function wpbs_action_uninstall_plugin() {

	// Verify for nonce
	if( empty( $_GET['wpbs_token'] ) || ! wp_verify_nonce( $_GET['wpbs_token'], 'wpbs_uninstall_plugin' ) )
		return;

	/**
	 * Drop db tables
	 *
	 */
	global $wpdb;

	$registered_tables = wp_booking_system()->db;

	foreach( $registered_tables as $table )
		$wpdb->query( "DROP TABLE IF EXISTS {$table->table_name}" );

	/**
	 * Remove options
	 *
	 */
	delete_option( 'wpbs_version' );
	delete_option( 'wpbs_first_activation' );
	delete_option( 'wpbs_upgrade_5_0_0' );
	delete_option( 'wpbs_upgrade_5_0_0_skipped' );
	delete_option( 'wpbs_serial_key' );
	delete_option( 'wpbs_registered_website_id' );
	delete_option( 'wpbs_settings' );


	/**
	 * Deactivate the plugin and redirect to Plugins
	 *
	 */
    deactivate_plugins( WPBS_BASENAME );
    
    wp_redirect( admin_url( 'plugins.php' ) );
    exit;

}
add_action( 'wpbs_action_uninstall_plugin', 'wpbs_action_uninstall_plugin' );

/**
 * Action that wipes all booking data
 *
 */
function wpbs_action_wipe_booking_data() {

	// Verify for nonce
	if( empty( $_GET['wpbs_token'] ) || ! wp_verify_nonce( $_GET['wpbs_token'], 'wpbs_wipe_booking_data' ) )
		return;

	/**
	 * Truncate db tables
	 *
	 */
	global $wpdb;

	$wpdb->query( "TRUNCATE TABLE {$wpdb->prefix}wpbs_bookings" );
	$wpdb->query( "TRUNCATE TABLE {$wpdb->prefix}wpbs_booking_meta" );
	$wpdb->query( "TRUNCATE TABLE {$wpdb->prefix}wpbs_payments" );

	// Remove existing cron jobs
    $crons = _get_cron_array();

    foreach ($crons as $timestamp => $cron) {
        if (isset($cron['wpbs_part_payments_payment_reminder_email'])) {
            unset($crons[$timestamp]['wpbs_part_payments_payment_reminder_email']);
        }
		if (isset($cron['wpbs_er_reminder_email'])) {
            unset($crons[$timestamp]['wpbs_er_reminder_email']);
        }
        if (isset($cron['wpbs_er_follow_up_email'])) {
            unset($crons[$timestamp]['wpbs_er_follow_up_email']);
        }
        if (isset($cron['wpbs_sms_reminder_notification'])) {
            unset($crons[$timestamp]['wpbs_sms_reminder_notification']);
        }
        if (isset($cron['wpbs_sms_follow_up_notification'])) {
            unset($crons[$timestamp]['wpbs_sms_follow_up_notification']);
        }
        if (isset($cron['wpbs_sms_payment_notification'])) {
            unset($crons[$timestamp]['wpbs_sms_payment_notification']);
        }
    }

	foreach ($crons as $timestamp => $cron) {
		if(!$cron){
			unset($crons[$timestamp]);
		}
	}

    _set_cron_array($crons);
    
    wp_redirect(add_query_arg(array('page' => 'wpbs-settings', 'tab' => 'tools', 'wpbs_message' => 'booking_wipe_successful'), admin_url('admin.php')));
    exit;

}
add_action( 'wpbs_action_wipe_booking_data', 'wpbs_action_wipe_booking_data' );


/**
 * Action that regenerates cron jobs
 *
 */
function wpbs_action_regenerate_payment_reminder_cron_jobs()
{

    // Verify for nonce
    if (empty($_GET['wpbs_token']) || !wp_verify_nonce($_GET['wpbs_token'], 'wpbs_regenerate_payment_reminder_cron_jobs')) {
        return;
    }

    // Delete crons
    $crons = _get_cron_array();

    foreach ($crons as $timestamp => $cron) {
        if (isset($cron['wpbs_part_payments_payment_reminder_email'])) {
            unset($crons[$timestamp]['wpbs_part_payments_payment_reminder_email']);
        }
    }

	foreach ($crons as $timestamp => $cron) {
		if(!$cron){
			unset($crons[$timestamp]);
		}
	}

    _set_cron_array($crons);

    $bookings = wpbs_get_bookings();

	$settings = get_option('wpbs_settings', array());

    foreach ($bookings as $booking) {

		if ($booking->get('status') == 'trash') {
            continue;
        }

		if (!isset($settings['payment_part_payments_method'])) {
			continue;
		}

		if ($settings['payment_part_payments_method'] != 'initial') {
			continue;
		}

		$form = wpbs_get_form($booking->get('form_id'));

		if (wpbs_get_form_meta($form->get('id'), 'payment_notification_enable', true) != 'on') {
			continue;
		}

		$payment = wpbs_get_payment_by_booking_id($booking->get('id'));
		if (empty($payment)) {
			continue;
		}

		$details = $payment->get('details');

		if (!$payment->is_part_payment()) {
			continue;
		}

		// If payment has a deposit
		if (!$payment->is_deposit_paid() && $payment->get('gateway') != 'bank_transfer') {
			continue;
		}

		// Check if a email was submitted
		$form_fields = $booking->get('fields');
		$has_email = false;
		foreach ($form_fields as $field) {
			if ($field['type'] == 'email' && isset($field['user_value']) && $field['user_value']) {
				$has_email = true;
				break;
			}
		}

		if ($has_email == false) {
			continue;
		}

		$start_date = strtotime($booking->get('start_date'));
        $end_date = strtotime($booking->get('end_date'));

		if ($start_date < current_time('timestamp')) {
            continue;
        }

		$calendar = wpbs_get_calendar($booking->get('calendar_id'));

		// When to send?
		$days_before = wpbs_get_form_meta($form->get('id'), 'payment_notification_when_to_send', true) * DAY_IN_SECONDS;
		$when_to_send = $start_date - $days_before + wpbs_scheduled_email_delivery_hour();

		if ($when_to_send < current_time('timestamp')) {
            continue;
        }

		// Schedule email
		wp_schedule_single_event($when_to_send, 'wpbs_part_payments_payment_reminder_email', array($form, $calendar, $booking->get('id'), $form_fields, wpbs_get_booking_meta($booking->get('id'), 'submitted_language', true), $start_date, $end_date));

	}		

    // Redirect to the current page
    wp_redirect(add_query_arg(array('page' => 'wpbs-settings', 'tab' => 'tools', 'wpbs_message' => 'payment_reminder_cron_regenerate_success'), admin_url('admin.php')));
    exit;

}
add_action('wpbs_action_regenerate_payment_reminder_cron_jobs', 'wpbs_action_regenerate_payment_reminder_cron_jobs');