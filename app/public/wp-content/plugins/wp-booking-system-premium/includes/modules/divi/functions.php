<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! function_exists( 'wpbs_initialize_divi_extension' ) ):
/**
 * Creates the extension's main class instance.
 *
 * @since 1.0.0
 */
function wpbs_initialize_divi_extension() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/SingleCalendar.php';
	require_once plugin_dir_path( __FILE__ ) . 'includes/OverviewCalendar.php';
}
add_action( 'divi_extensions_init', 'wpbs_initialize_divi_extension' );
endif;
