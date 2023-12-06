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
function wpbs_submenu_page_settings_tabs_search_addon( $tabs ) {

	$tabs['search_widget'] = __( 'Search Widget Strings', 'wp-booking-system-search');

	return $tabs;

}
add_filter( 'wpbs_submenu_page_settings_strings_tabs', 'wpbs_submenu_page_settings_tabs_search_addon', 80 );


/**
 * Adds the HTML for the Search Add-on Setting tab
 *
 */
function wpbs_submenu_page_string_settings_tab_search_widget() {

	include 'views/view-settings-tab-strings-search-widget.php';

}
add_action( 'wpbs_submenu_page_string_settings_tab_search_widget', 'wpbs_submenu_page_string_settings_tab_search_widget' );