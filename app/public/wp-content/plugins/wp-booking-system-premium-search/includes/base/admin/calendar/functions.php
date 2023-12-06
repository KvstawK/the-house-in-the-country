<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;


/**
 * Includes the files needed for the Calendar admin area
 *
 */
function wpbs_s_include_files_admin_calendar() {

	// Get legend admin dir path
	$dir_path = plugin_dir_path( __FILE__ );

	if( file_exists( $dir_path . 'functions-shortcode-generator.php' ) )
		include $dir_path . 'functions-shortcode-generator.php';

}
add_action( 'wpbs_s_include_files', 'wpbs_s_include_files_admin_calendar' );

