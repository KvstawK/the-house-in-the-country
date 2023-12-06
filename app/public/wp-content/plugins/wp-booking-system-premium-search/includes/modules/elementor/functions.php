<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;


/**
 * Register the Widgets
 * 
 */
function wpbs_elementor_register_widget_search( $widgets_manager ) {

	require_once( __DIR__ . '/widgets/search-widget.php' );

	$widgets_manager->register( new \Elementor_WPBS_Search_Widget() );
  
}
add_action( 'elementor/widgets/register', 'wpbs_elementor_register_widget_search' );