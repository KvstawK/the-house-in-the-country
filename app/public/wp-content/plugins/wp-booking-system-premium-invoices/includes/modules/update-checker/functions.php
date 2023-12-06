<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Initializes the update checker
 *
 */
function wpbs_invc_init_plugin_update_checker() {

	if(!class_exists('WPBS_PluginUpdateChecker')){
		return false;
	}

	$serial_key = get_option( 'wpbs_serial_key', '' );

	if( empty( $serial_key ) )
		return;

	$url_args = array(
		'request'      => 'get_update',
		'product_slug' => 'wp-booking-system-premium-invoices',
		'serial_key'   => $serial_key
	);
	
	$update_checker = new WPBS_PluginUpdateChecker( add_query_arg( $url_args, 'https://www.wpbookingsystem.com/u/' ), WPBS_INVC_FILE, 'wp-booking-system-premium-invoices', 24 );

}
add_action( 'plugins_loaded', 'wpbs_invc_init_plugin_update_checker' );

/**
 * Hooks into the main plugin's 'check for updates' function
 * 
 */
function wpbs_invc_check_addon_updates(){
	do_action_ref_array( 'check_plugin_updates-wp-booking-system-premium-invoices', array() );
}
add_action('wpbs_check_addon_updates', 'wpbs_invc_check_addon_updates');