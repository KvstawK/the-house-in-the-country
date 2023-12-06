<?php

// Exit if accessed directly
if (!defined('ABSPATH')) exit;


/**
 * Registers the Single Calendar block
 *
 */
if (function_exists('register_block_type')) {

	function wpbs_register_block_type_single_calendar()
	{

		$settings = get_option('wpbs_settings', array());

		wp_register_script('wpbs-script-block-single-calendar', WPBS_PLUGIN_DIR_URL . 'includes/modules/blocks/single-calendar/assets/js/build/script-block-single-calendar.js', array('wp-blocks', 'wp-element', 'wp-editor', 'wp-i18n'));
		wp_register_style('wpbs-block-front-end-style', WPBS_PLUGIN_DIR_URL . 'assets/css/style-front-end.min.css', array(), WPBS_VERSION);

		if (!isset($settings['form_styling']) || $settings['form_styling'] == 'default') {
			wp_register_style('wpbs-block-style-form', WPBS_PLUGIN_DIR_URL . 'assets/css/style-front-end-form.min.css', array(), WPBS_VERSION);
		}

		register_block_type(
			'wp-booking-system/single-calendar',
			array(
				'attributes' => array(
					'id' => array(
						'type' => 'string'
					),
					'form_id' => array(
						'type' => 'string'
					),
					'title' => array(
						'type' => 'string'
					),
					'legend' => array(
						'type' => 'string'
					),
					'legend_position' => array(
						'type' => 'string'
					),
					'display' => array(
						'type' => 'string'
					),
					'year' => array(
						'type' => 'string'
					),
					'month' => array(
						'type' => 'string'
					),
					'start' => array(
						'type' => 'string'
					),
					'dropdown' => array(
						'type' => 'string'
					),
					'jump' => array(
						'type' => 'string'
					),
					'history' => array(
						'type' => 'string'
					),
					'tooltip' => array(
						'type' => 'string'
					),
					'highlighttoday' => array(
						'type' => 'string'
					),
					'weeknumbers' => array(
						'type' => 'string'
					),
					'show_prices' => array(
						'type' => 'string'
					),
					'language' => array(
						'type' => 'string'
					),
					'auto_pending' => array(
						'type' => 'string'
					),
					'selection_type' => array(
						'type' => 'string'
					),
					'selection_style' => array(
						'type' => 'string'
					),
					'minimum_days' => array(
						'type' => 'string'
					),
					'maximum_days' => array(
						'type' => 'string'
					),
					'booking_start_day' => array(
						'type' => 'string'
					),
					'booking_end_day' => array(
						'type' => 'string'
					),
					'show_date_selection' => array(
						'type' => 'string'
					),
					'form_position' => array(
						'type' => 'string'
					),
				),
				'editor_script'   => 'wpbs-script-block-single-calendar', 
				'editor_style' => ['wpbs-block-front-end-style', 'wpbs-block-style-form'],
				'render_callback' => 'wpbs_block_to_shortcode_single_calendar'
			)
		);
	}
	add_action('init', 'wpbs_register_block_type_single_calendar');
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
function wpbs_block_to_shortcode_single_calendar($args)
{

	if (empty($args['id'])) {
		return '<div style="padding: 20px; background-color: #f1f1f1;">' . __('Please select a calendar to display.') . '</div>';
	}

	// Execute the shortcode
	return WPBS_Shortcodes::single_calendar($args);
}
