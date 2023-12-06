<?php

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Registers the Search Widget block
 *
 */
if (function_exists('register_block_type')) {

    function wpbs_s_register_block_type_search_widget()
    {

        wp_register_script('wpbs-s-script-block-search-widget', WPBS_S_PLUGIN_DIR_URL . 'includes/modules/blocks/search-widget/assets/js/build/script-block-search-widget.js', array('wp-blocks', 'wp-element', 'wp-editor', 'wp-i18n'));

        register_block_type(
            'wp-booking-system/search-widget',
            array(
                'attributes' => array(
                    'calendars' => array(
                        'type' => 'string',
                    ),
                    'language' => array(
                        'type' => 'string',
                    ),
                    'title' => array(
                        'type' => 'string',
                    ),
                    'start_day' => array(
                        'type' => 'string',
                    ),
                    'mark_selection' => array(
                        'type' => 'string',
                    ),
                    'selection_type' => array(
                        'type' => 'string',
                    ),
                    'minimum_stay' => array(
                        'type' => 'string',
                    ),
                    'featured_image' => array(
                        'type' => 'string',
                    ),
                    'starting_price' => array(
                        'type' => 'string',
                    ),
                    'show_results_on_load' => array(
                        'type' => 'string',
                    ),
                    'results_layout' => array(
                        'type' => 'string',
                    ),
                    'results_per_page' => array(
                        'type' => 'string',
                    ),
                    'redirect' => array(
                        'type' => 'string',
                    ),

                ),
                'editor_script' => 'wpbs-s-script-block-search-widget',
                'render_callback' => 'wpbs_s_block_to_shortcode_search_widget',
            )
        );

    }
    add_action('init', 'wpbs_s_register_block_type_search_widget');

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
function wpbs_s_block_to_shortcode_search_widget($args)
{

    // Transform the values for the calendars
    if (!empty($args['calendars'])) {

        $calendars = json_decode($args['calendars'], true);

        if (!empty($calendars)) {

            foreach ($calendars as $key => $value) {

                $calendars[$key] = $value['value'];

            }

            $args['calendars'] = implode(',', $calendars);

        } else {
			$args['calendars'] = 'all';
		}

    }

    // Execute the shortcode
    return WPBS_S_Shortcodes::search_widget($args);

}
