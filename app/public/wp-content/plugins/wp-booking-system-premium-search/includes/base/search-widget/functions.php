<?php

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Includes the Search Widget files
 *
 */
function wpbs_s_include_files_search_widget()
{

    // Get legend dir path
    $dir_path = plugin_dir_path(__FILE__);

    // Include the shortcodes class
    if (file_exists($dir_path . 'class-search-widget-outputter.php')) {
        include $dir_path . 'class-search-widget-outputter.php';
    }

    // Include the ajax functions
    if (file_exists($dir_path . 'functions-ajax.php')) {
        include $dir_path . 'functions-ajax.php';
    }
}
add_action('wpbs_s_include_files', 'wpbs_s_include_files_search_widget');

/**
 * Default Search Widget strings
 *
 */
function wpbs_s_search_widget_default_strings()
{
    return array(
        'widget_title' => __('Search', 'wp-booking-system-search'),
        'start_date_label' => __('Start Date', 'wp-booking-system-search'),
        'start_date_placeholder' => __('', 'wp-booking-system-search'),
        'end_date_label' => __('End Date', 'wp-booking-system-search'),
        'end_date_placeholder' => __('', 'wp-booking-system-search'),
        'date_label' => __('Date', 'wp-booking-system-search'),
        'search_button_label' => __("Search", 'wp-booking-system-search'),
        'no_start_date' => __("Please select a starting date.", 'wp-booking-system-search'),
        'no_end_date' => __("Please select an ending date.", 'wp-booking-system-search'),
        'invalid_start_date' => __("Invalid start date.", 'wp-booking-system-search'),
        'invalid_end_date' => __("Invalid end date", 'wp-booking-system-search'),
        'results_title' => __("Search Results", 'wp-booking-system-search'),
        'no_results' => __("No available dates were found.", 'wp-booking-system-search'),
        'view_button_label' => __("View", 'wp-booking-system-search'),
        'starting_from' => __("Starting from % per day.", 'wp-booking-system-search'),
        'label_previous' => __("Previous", 'wp-booking-system-search'),
        'label_next' => __("Next", 'wp-booking-system-search'),
    );
}

/**
 * Returns the default arguments for the calendar outputter
 *
 * @return array
 *
 */
function wpbs_s_get_search_widget_default_args()
{
    $args = array(
        'calendars' => 'all',
        'language' => 'auto',
        'title' => 'yes',
        'mark_selection' => 'yes',
        'start_day' => 1,
        'selection_type' => 'multiple',
        'minimum_stay' => 0,
        'featured_image' => 'no',
        'starting_price' => 'no',
        'show_results_on_load' => 'no',
        'results_layout' => 'list',
        'results_per_page' => 10,
        'show_results_on_load' => 'no',
        'redirect' => ''
    );

    return $args;
}

/**
 * Parse additional search fields
 * 
 * @return array
 * 
 */
function wpbs_s_get_additional_search_fields()
{

    $fields = apply_filters('wpbs_search_widget_additional_fields', []);

    $default_fields = [
        'name' => '',
        'slug' => '',
        'type' => '',
        'required' => false,
        'required_message' => false,
        'values' => [],
        'placeholder' => '',
        'validation' => function ($value, $data) {
            return true;
        }
    ];

    foreach ($fields as &$field) {
        if (empty($field['slug'])) {
            $field['slug'] = 'wpbs-search-' . sanitize_title($field['name']);
        }
        if (empty($field['required_message'])) {
            $field['required_message'] = sprintf(__('The %s field cannot be empty.', 'wp-booking-system-search'), $field['name']);
        }

        if(wpbs_s_is_list_array($field['values'])){
            $field['values'] = array_combine($field['values'], $field['values']);
        }

        $field = shortcode_atts($default_fields, $field);
    }

    return $fields;
}

/**
 * Check if an array is a list or an associative array
 * 
 */
function wpbs_s_is_list_array($array)
{
    $expectedKey = 0;
    foreach ($array as $i => $_) {
        if ($i !== $expectedKey) {
            return false;
        }
        $expectedKey++;
    }
    return true;
}
