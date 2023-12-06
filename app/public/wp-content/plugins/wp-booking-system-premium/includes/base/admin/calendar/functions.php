<?php

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Includes the files needed for the Calendar admin area
 *
 */
function wpbs_include_files_admin_calendar()
{

    // Get legend admin dir path
    $dir_path = plugin_dir_path(__FILE__);

    // Include submenu page
    if (file_exists($dir_path . 'class-submenu-page-calendar.php')) {
        include $dir_path . 'class-submenu-page-calendar.php';
    }

    // Include calendars list table
    if (file_exists($dir_path . 'class-list-table-calendars.php')) {
        include $dir_path . 'class-list-table-calendars.php';
    }

    // Include legend items list table
    if (file_exists($dir_path . 'class-list-table-legend-items.php')) {
        include $dir_path . 'class-list-table-legend-items.php';
    }

    // Include calendar editor outputter
    if (file_exists($dir_path . 'class-calendar-editor-outputter.php')) {
        include $dir_path . 'class-calendar-editor-outputter.php';
    }

    // Include admin actions
    if (file_exists($dir_path . 'functions-actions-ical.php')) {
        include $dir_path . 'functions-actions-ical.php';
    }

    if (file_exists($dir_path . 'functions-notes.php')) {
        include $dir_path . 'functions-notes.php';
    }

    if (file_exists($dir_path . 'functions-actions-csv.php')) {
        include $dir_path . 'functions-actions-csv.php';
    }

    if (file_exists($dir_path . 'functions-actions-calendar.php')) {
        include $dir_path . 'functions-actions-calendar.php';
    }

    if (file_exists($dir_path . 'functions-actions-legend-item.php')) {
        include $dir_path . 'functions-actions-legend-item.php';
    }

    if (file_exists($dir_path . 'functions-actions-ajax-calendar.php')) {
        include $dir_path . 'functions-actions-ajax-calendar.php';
    }

    if (file_exists($dir_path . 'functions-actions-ajax-legend-item.php')) {
        include $dir_path . 'functions-actions-ajax-legend-item.php';
    }

    if (file_exists($dir_path . 'functions-shortcode-generator.php')) {
        include $dir_path . 'functions-shortcode-generator.php';
    }

}
add_action('wpbs_include_files', 'wpbs_include_files_admin_calendar');

/**
 * Register the Calendars admin submenu page
 *
 */
function wpbs_register_submenu_page_calendars($submenu_pages)
{

    if (!is_array($submenu_pages)) {
        return $submenu_pages;
    }

    $count = 0;

    // Change filter to get count only from available calendars
    remove_filter('wpbs_get_calendars', 'wpbs_get_calendars_user_capabilities');
    add_filter('wpbs_get_calendars', 'wpbs_get_calendars_user_capabilities_global', 10, 3);

    $calendars = wpbs_get_calendars();

    // Reset filters
    add_filter('wpbs_get_calendars', 'wpbs_get_calendars_user_capabilities', 10, 3);
    remove_filter('wpbs_get_calendars', 'wpbs_get_calendars_user_capabilities_global');

    // New bookings count
    foreach ($calendars as $calendar) {
        $count += wpbs_get_bookings(array('calendar_id' => $calendar->get('id'), 'is_read' => 0), true);
    }

    $submenu_pages['calendars'] = array(
        'class_name' => 'WPBS_Submenu_Page_Calendars',
        'data' => array(
            'page_title' => __('Calendars', 'wp-booking-system'),
            'menu_title' => __('Calendars', 'wp-booking-system') . ($count > 0 ? ' <span class="update-plugins wpbs-bookings-removable-count count-'.$count.'"><span class="plugin-count wpbs-bookings-count-circle">'.$count.'</span></span>' : ''),
            'capability' => apply_filters('wpbs_submenu_page_capability_calendars', 'manage_options'),
            'menu_slug' => 'wpbs-calendars',
        ),
    );

    return $submenu_pages;

}
add_filter('wpbs_register_submenu_page', 'wpbs_register_submenu_page_calendars', 20);

/**
 * Returns the HTML for the legend item icon
 *
 * @param int    $legend_item_id
 * @param string $type
 * @param array  $color
 *
 * @return string
 *
 */
function wpbs_get_legend_item_icon($legend_item_id, $type, $color = array(), $append = '')
{

    $output = '<div class="wpbs-legend-item-icon wpbs-legend-item-icon-' . esc_attr($legend_item_id) . '" data-type="' . esc_attr($type) . '">';

    for ($i = 0; $i <= 1; $i++) {

        $svg = '';
        if ($type == "split") {
            $svg = ($i == 0) ? '<svg preserveAspectRatio="none" viewBox="0 0 200 200"><polygon points="0,0 0,200 200,0" /></svg>' : '<svg preserveAspectRatio="none" viewBox="0 0 200 200"><polygon points="0,200 200,200 200,0" /></svg>';
        }

        $output .= '<div class="wpbs-legend-item-icon-color" ' . (!empty($color[$i]) ? 'style="background-color: ' . esc_attr($color[$i]) . ';"' : '') . '>' . $svg . '</div>';
        
    }

    $output .= $append;

    $output .= '</div>';

    return $output;

}

/**
 * Get post types as dropdown
 * 
 * @return array
 * 
 */
function wpbs_get_post_types_as_dropdown()
{
	$post_types_dropdown = array();
    $post_types = get_post_types(array('public' => true));
    $ignored_post_types = apply_filters('wpbs_get_post_types_as_dropdown_ignored_post_types', array('attachment', 'revision', 'nav_menu_item', 'custom_css', 'customize_changeset'));

    foreach ($post_types as $post_type) {
        if (in_array($post_type, $ignored_post_types)) {
            continue;
        }

        $posts = get_posts(array('numberposts' => apply_filters('wpbs_get_post_types_as_dropdown_numberposts', 1000), 'post_type' => $post_type, 'suppress_filters' => true, 'post_status' => array('publish', 'pending', 'draft', 'private')));

        if (empty($posts)) {
            continue;
        }

        foreach ($posts as $post_obj) {
			$page_title = ($post_obj->post_title) ? : '#' . $post_obj->ID . ' (no title)';
            
			// Append PolyLang language to posts
			if(function_exists('pll_get_post_language')){
				$page_title .= ' ('.pll_get_post_language($post_obj->ID, 'name').')';
			}

            if(in_array($post_obj->post_status, ['draft', 'private'])){
                $page_title .= ' ('.$post_obj->post_status.')';
            }
			
			$post_types_dropdown[ucwords($post_type)][$post_obj->ID] = $page_title ;
        }
    }

	return $post_types_dropdown;
}
