<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;


/**
 * Registers the Multiple Overview Calendar block
 *
 */
if( function_exists( 'register_block_type' ) ) {

	function wpbs_register_block_type_overview_calendar() {

		wp_register_script( 'wpbs-script-block-overview-calendar', WPBS_PLUGIN_DIR_URL . 'includes/modules/blocks/overview-calendar/assets/js/build/script-block-overview-calendar.js', array( 'wp-blocks', 'wp-element', 'wp-editor', 'wp-i18n' ) );

		register_block_type( 
			'wp-booking-system/overview-calendar', 
			array(
				'attributes' => array(
					'calendars' => array(
						'type' => 'string'
					),
					'legend' => array(
						'type' => 'string'
					),
					'legend_position' => array(
						'type' => 'string'
					),
					'start_year' => array(
						'type' => 'string'
					),
					'start_month' => array(
						'type' => 'string'
					),
					'history' => array(
						'type' => 'string'
					),
					'tooltip' => array(
						'type' => 'string'
					),
					'weeknumbers' => array(
						'type' => 'string'
					),
					'language' => array(
						'type' => 'string'
					)
				),
				'editor_script'   => 'wpbs-script-block-overview-calendar', 
				'render_callback' => 'wpbs_block_to_shortcode_overview_calendar'
			)	
		);

	}
	add_action( 'init', 'wpbs_register_block_type_overview_calendar' );

}


/**
 * Render callback for the server render block
 * Transforms the attributes from the blocks into the needed shortcode arguments
 *
 * @param array $args
 *
 * @return string
 *
 */
function wpbs_block_to_shortcode_overview_calendar( $args ) {

	// Transform the values for the calendars
	if( ! empty( $args['calendars'] ) ) {

		$calendars = json_decode( $args['calendars'], true );

		foreach( $calendars as $key => $value ) {

			$calendars[$key] = $value['value'];

		}

		$args['calendars'] = implode( ',', $calendars );

	}
	
	// Execute the shortcode
	return WPBS_Shortcodes::calendar_overview( $args );

}