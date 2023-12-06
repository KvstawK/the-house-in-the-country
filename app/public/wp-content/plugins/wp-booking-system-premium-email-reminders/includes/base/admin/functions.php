<?php

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Includes the files needed for the Emails
 *
 */
function wpbs_er_include_files_emails() {

	// Get legend dir path
	$dir_path = plugin_dir_path( __FILE__ );

	// Reminder Email
	if( file_exists( $dir_path . 'functions-reminder-email.php' ) )
		include $dir_path . 'functions-reminder-email.php';

    // Follow Up Email
	if( file_exists( $dir_path . 'functions-follow-up-email.php' ) )
		include $dir_path . 'functions-follow-up-email.php';

}
add_action( 'wpbs_include_files', 'wpbs_er_include_files_emails' );