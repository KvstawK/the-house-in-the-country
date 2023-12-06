<?php

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Includes the files needed for the Booking admin area
 *
 */
function wpbs_bm_include_files_admin_bookings()
{

    // Get legend admin dir path
    $dir_path = plugin_dir_path(__FILE__);

    // Include submenu page
    if (file_exists($dir_path . 'class-submenu-page-bookings.php')) {
        include $dir_path . 'class-submenu-page-bookings.php';
    }

    // Include bookings list table
    if (file_exists($dir_path . 'class-list-table-bookings.php')) {
        include $dir_path . 'class-list-table-bookings.php';
    }

    // Include bookings list table
    if (file_exists($dir_path . 'class-calendar-view-bookings.php')) {
        include $dir_path . 'class-calendar-view-bookings.php';
    }

    // Include csv export functions file
    if (file_exists($dir_path . 'functions-export-csv.php')) {
        include $dir_path . 'functions-export-csv.php';
    }

}
add_action('wpbs_bm_include_files', 'wpbs_bm_include_files_admin_bookings');

/**
 * Register the Bookings admin submenu page
 *
 */
function wpbs_register_submenu_page_booking_manager($submenu_pages)
{

    if (!is_array($submenu_pages)) {
        return $submenu_pages;
    }

    $submenu_pages['bookings'] = array(
        'class_name' => 'WPBS_Submenu_Page_Bookings',
        'data' => array(
            'page_title' => __('Bookings', 'wp-booking-system-booking-manager'),
            'menu_title' => __('Bookings', 'wp-booking-system-booking-manager'),
            'capability' => apply_filters('wpbs_submenu_page_capability_bookings', 'manage_options'),
            'menu_slug' => 'wpbs-bookings',
        ),
    );

    return $submenu_pages;

}
add_filter('wpbs_register_submenu_page', 'wpbs_register_submenu_page_booking_manager', 30);

/**
 * Get the default view of the dashboard
 *
 */
function wpbs_bm_get_dashboard_view()
{
    $view = get_user_meta(get_current_user_id(), 'wpbs_bm_dashboard_view', true);

    return $view ?: 'list';
}

/**
 * Save the current view of the dashboard
 *
 */
function wpbs_bm_save_dashboard_view()
{

    if (!isset($_GET['view'])) {
        return false;
    }

    if (!isset($_GET['page']) || $_GET['page'] != 'wpbs-bookings') {
        return false;
    }

    if (!in_array($_GET['view'], array('list', 'calendar'))) {
        return false;
    }

    update_user_meta(get_current_user_id(), 'wpbs_bm_dashboard_view', $_GET['view']);

}
add_action('init', 'wpbs_bm_save_dashboard_view');

/**
 * Get the hide bookings filter
 *
 */
function wpbs_bm_get_past_bookings_filter()
{
    $past_bookings = get_user_meta(get_current_user_id(), 'wpbs_bm_past_bookings_filter', true);

    return $past_bookings ?: '';
}

/**
 * Save the hide bookings filter
 *
 */
function wpbs_set_hide_bookings_filter()
{

    if (empty($_POST['action']) || $_POST['action'] != 'wpbs_set_hide_bookings_filter') {
        echo __('', 'wp-booking-system');
        wp_die();
    }

    if (!isset($_POST['hide_past_bookings']) || $_POST['hide_past_bookings'] != 'on') {
        update_user_meta(get_current_user_id(), 'wpbs_bm_past_bookings_filter', '');
    } else {
        update_user_meta(get_current_user_id(), 'wpbs_bm_past_bookings_filter', $_POST['hide_past_bookings']);
    }

    wp_die();

}
add_action('wp_ajax_wpbs_set_hide_bookings_filter', 'wpbs_set_hide_bookings_filter');

/**
 * Function for getting filtered bookings
 *
 */
function wpbs_bm_get_bookings($args = [], $count = false)
{
    if (isset($args['search'])) {
        $search = $args['search'];
        unset($args['search']);
    }

    if (isset($args['number'])) {
        $number = $args['number'];
        unset($args['number']);
    }

    if (isset($args['offset'])) {
        $offset = $args['offset'];
        unset($args['offset']);
    } else {
        $offset = 0;
    }

    $bookings = wpbs_get_bookings($args);

    $calendars = wpbs_get_calendars();
    $calendar_ids = [];
    foreach($calendars as $calendar){
        $calendar_ids[] = $calendar->get('id');
    }
    
    $matching_bookings = [];
    foreach($bookings as $booking){
        if(in_array($booking->get('calendar_id'), $calendar_ids)){
            $matching_bookings[] = $booking;
        }
    }

    $bookings = $matching_bookings;

    // Filter past bookings
    if (wpbs_bm_get_past_bookings_filter() == 'on') {

        $matching_bookings = [];
        foreach ($bookings as $booking) {
            if (strtotime($booking->get('start_date')) > current_time('timestamp') - DAY_IN_SECONDS) {
                $matching_bookings[] = $booking;
            }
        }

        $bookings = $matching_bookings;
    }

    // Filter start date
    if (isset($args['start_date']) && !empty($args['start_date'])) {
        $start_date = DateTime::createFromFormat('Y-m-d', $args['start_date']);
        $start_date->setTime(0, 0, 0, 0);
        if ($start_date !== false) {

            $matching_bookings = [];
            foreach ($bookings as $booking) {
                if (strtotime($booking->get('start_date')) >= $start_date->getTimestamp()) {
                    $matching_bookings[] = $booking;
                }
            }

            $bookings = $matching_bookings;
        }
    }

    // Filter end date
    if (isset($args['end_date']) && !empty($args['end_date'])) {
        $end_date = DateTime::createFromFormat('Y-m-d', $args['end_date']);
        $end_date->setTime(0, 0, 0, 0);
        if ($end_date !== false) {

            $matching_bookings = [];
            foreach ($bookings as $booking) {
                if (strtotime($booking->get('end_date')) <= $end_date->getTimestamp()) {
                    $matching_bookings[] = $booking;
                }
            }

            $bookings = $matching_bookings;
        }
    }

    if (isset($search) && !empty($search)) {
        $booking_strings = [];
        foreach ($bookings as $booking) {
            $booking_string = date('j F Y', strtotime($booking->get('start_date'))) . ' ';
            $booking_string .= date('j F Y', strtotime($booking->get('end_date'))) . ' ';
            $booking_string .= '#' . $booking->get('id') . ' ';
            foreach ($booking->get('fields') as $field) {
                if (isset($field['user_value']) && $field['user_value']) {
                    if (is_array($field['user_value'])) {
                        $booking_string .= implode(' ', $field['user_value']) . ' ';
                    } else {
                        $booking_string .= $field['user_value'] . ' ';
                    }
                }
            }
            $booking_strings[$booking->get('id')] = array(
                'string' => strtolower($booking_string),
                'booking' => $booking,
            );

        }

        $matching_bookings = [];

        foreach ($booking_strings as $id => $booking_filter) {
            $keywords = explode(' ', strtolower(sanitize_text_field($search)));
            $keyword_count = 0;
            $expected = count($keywords);
            foreach ($keywords as $keyword) {
                if (strpos($booking_filter['string'], $keyword) !== false) {
                    $keyword_count++;
                }
            }
            if ($keyword_count == $expected) {
                $matching_bookings[] = $booking_filter['booking'];
            }
        }

        $bookings = $matching_bookings;

    }

    if(isset($args['orderby']) && $args['orderby'] == 'calendar_id'){

        $sorted_bookings = [];
        $calendar_names = [];
        $calendars = wpbs_get_calendars();
        foreach($calendars as $calendar){
            $calendar_names[$calendar->get('id')] = $calendar->get('name');
        }

        foreach($bookings as $booking){
            $sorted_bookings[$calendar_names[$booking->get('calendar_id')] . $booking->get('id')] = $booking;
        }

        if(isset($args['order']) && $args['order'] == 'DESC'){
            krsort($sorted_bookings);
        } else {
            ksort($sorted_bookings);
        }

        $bookings = $sorted_bookings;
    }

    $bookings = apply_filters('wpbs_booking_manager_results', $bookings, $args);

    if ($count == true) {
        return count($bookings);
    }

    return array_slice($bookings, $offset, $number);
}
