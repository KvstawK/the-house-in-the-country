<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

?>

<h2><?php _e('Uninstall', 'wp-booking-system') ?></h2>

<div class="wpbs-notice-error">

	<p><strong><?php echo __( 'Important!', 'wp-booking-system' ) ?></strong></p>

	<p><?php echo __( 'The uninstaller will remove all WP Booking System information stored in the database for version 5 and higher.', 'wp-booking-system' ); ?></p>

	<p><?php echo __( 'This includes, but is not limited to, all calendars, all legend items, all bookings, all plugin settings.', 'wp-booking-system' ); ?></p>

	<p><?php echo __( 'Data related to versions lower than version 5 will not be removed.', 'wp-booking-system' ); ?></p>

	<p><?php echo __( 'After the uninstall process is complete the plugin will be automatically deactivated.', 'wp-booking-system' ); ?></p>

</div>

<div id="wpbs-uninstaller-confirmation" class="wpbs-notice-error">

	<p><?php echo __( 'To confirm that you really want to uninstall WP Booking System, please type REMOVE in the field below.', 'wp-booking-system' ); ?></p>

	<p><input id="wpbs-uninstaller-confirmation-field" type="text" /></p>

</div>

<a id="wpbs-uninstaller-button" class="button-primary" href="<?php echo add_query_arg( array( 'wpbs_action' => 'uninstall_plugin', 'wpbs_token' => wp_create_nonce( 'wpbs_uninstall_plugin' ) ), admin_url( 'admin.php' ) ); ?>"><?php echo __( 'Uninstall Plugin', 'wp-booking-system' ); ?></a>


<h2><?php _e('Wipe all Booking and Payment data', 'wp-booking-system') ?></h2>

<div class="wpbs-notice-error">

	<p><strong><?php echo __( 'Important!', 'wp-booking-system' ) ?></strong></p>

	<p><?php echo __( 'This will remove all your bookings, booking data and payment data. It will also reset the booking IDs to 1.', 'wp-booking-system' ); ?></p>

	<p><?php echo __( 'This will not affect the events in your calendar, regardless if they were created manually or automatically when a booking was made.', 'wp-booking-system' ); ?></p>

</div>

<div id="wpbs-wipe-bookings-confirmation" class="wpbs-notice-error">

	<p><?php echo __( 'To confirm that you really want to remove all booking and payment data, please type REMOVE in the field below.', 'wp-booking-system' ); ?></p>

	<p><input id="wpbs-wipe-bookings-confirmation-field" type="text" /></p>

</div>

<a id="wpbs-wipe-bookings-button" class="button-primary" href="<?php echo add_query_arg( array( 'wpbs_action' => 'wipe_booking_data', 'wpbs_token' => wp_create_nonce( 'wpbs_wipe_booking_data' ) ), admin_url( 'admin.php' ) ); ?>"><?php echo __( 'Wipe Booking Data', 'wp-booking-system' ); ?></a>


<h2><?php _e('Regenerate Payment Reminder Email cron jobs', 'wp-booking-system') ?></h2>

<div class="wpbs-notice-error">

	<p><?php echo __( 'This will remove all existing cron jobs and rebuild them based on current form settings.', 'wp-booking-system' ); ?></p>

</div>

<a class="button-primary" onclick="return confirm('<?php _e('Are you sure you want to proceed?','wp-booking-system');?>');" href="<?php echo add_query_arg( array( 'wpbs_action' => 'regenerate_payment_reminder_cron_jobs', 'wpbs_token' => wp_create_nonce( 'wpbs_regenerate_payment_reminder_cron_jobs' ) ), admin_url( 'admin.php' ) ); ?>"><?php echo __( 'Regenerate Part Payment Email Cron Jobs', 'wp-booking-system' ); ?></a>