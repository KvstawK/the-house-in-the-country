<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

?>

<h2><?php _e('Regenerate Email Reminder & Follow Up cron jobs', 'wp-booking-system-email-reminders') ?></h2>

<div class="wpbs-notice-error">

	<p><?php echo __( 'This will remove all existing cron jobs and rebuild them based on current form settings.', 'wp-booking-system-email-reminders' ); ?></p>

</div>

<a class="button-primary" onclick="return confirm('<?php _e('Are you sure you want to proceed?','wp-booking-system-email-reminders');?>');" href="<?php echo add_query_arg( array( 'wpbs_action' => 'regenerate_cron_jobs', 'wpbs_token' => wp_create_nonce( 'wpbs_regenerate_cron_jobs' ) ), admin_url( 'admin.php' ) ); ?>"><?php echo __( 'Regenerate Reminder & Follow-up Email Cron Jobs', 'wp-booking-system-email-reminders' ); ?></a>