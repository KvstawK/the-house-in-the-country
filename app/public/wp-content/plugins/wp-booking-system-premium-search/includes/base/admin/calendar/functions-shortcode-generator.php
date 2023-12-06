<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;


/**
 * Adds the Search Widget tab to the shortcode generator
 *
 */
function wpbs_s_shortcode_generator_tabs_search($tabs){
	$tabs['insert-search-widget'] = __( 'Insert Search Widget', 'wp-booking-system-search');

	return $tabs;
}
add_filter('wpbs_shortcode_generator_tabs','wpbs_s_shortcode_generator_tabs_search');


/**
 * Adds the View for the Search tab
 * 
 */

function wpbs_s_shortcode_generator_tab_insert_search_widget(){
	include 'views/view-shortcode-generator-insert-search-widget.php'; 
}
add_action('wpbs_shortcode_generator_tab_insert-search-widget', 'wpbs_s_shortcode_generator_tab_insert_search_widget');