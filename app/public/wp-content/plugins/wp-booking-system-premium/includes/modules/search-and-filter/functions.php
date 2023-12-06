<?php

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

if ((
    in_array('search-filter-pro/search-filter-pro.php', (array) get_option('active_plugins')) ||
    (is_multisite() && array_key_exists('search-filter-pro/search-filter-pro.php', (array) get_site_option('active_sitewide_plugins')))
)) {
    add_action('pre_get_posts', 'wpbs_search_filter_query', 99999);
}

function wpbs_search_filter_query($query)
{
    if (!$query->get('search_filter_id')) {
        return false;
    }

    // Check if it's a date range
    $date_query = $query->get('date_query');

    if ($date_query) {
        // Search through calendars
        $date = DateTime::createFromFormat('Ymd', $date_query['after']['year'] . $date_query['after']['month'] . $date_query['after']['day']);
        $start_date = $date->format('Ymd');

        // Format end date
        $date = DateTime::createFromFormat('Ymd', $date_query['before']['year'] . $date_query['before']['month'] . $date_query['before']['day']);
        $end_date = $date->format('Ymd');
    } else {
        if ($query->get('day') && $query->get('monthnum') && $query->get('year')) {
            $date = DateTime::createFromFormat('Ymd', $query->get('year') . str_pad($query->get('monthnum'), 2, '0', STR_PAD_LEFT) . str_pad($query->get('day'), 2, '0', STR_PAD_LEFT));
            $start_date = $end_date = $date->format('Ymd');
        }
    }

    if (!isset($start_date)) {
        return false;
    }

    $existing_matches = $query->get('post__in');

    $matches = [];

    $query->set('date_query', []);
    $query->set('day', 0);
    $query->set('monthnum', 0);
    $query->set('year', 0);

    // Get all calendars
    $calendars = wpbs_get_calendars(array('status' => 'active', 'orderby' => 'name', 'order' => 'asc'));

    foreach ($calendars as $calendar) {

        // Check if calendar has a page attached to it
        $calendar_link = wpbs_get_translated_calendar_meta($calendar->get('id'), 'calendar_link_internal', wpbs_get_site_language());

        if (empty($calendar_link)) {
            continue;
        }

        if (wpbs_check_if_date_range_is_bookable($calendar->get('id'), $start_date, $end_date) === true) {
            $matches[] = $calendar_link;
        }

    }

    if (empty($matches)) {
        $matches = [0];
    }

    $matches = array_intersect($matches, $existing_matches);

    if (empty($matches)) {
        $matches = [0];
    }

    $query->set('post__in', $matches);

}



function wpbs_search_filter_post_link($permalink, $post, $leavename)
{

    // Check if a Date Facet is selected
    if (!isset($_GET['sfid'])) {
        return $permalink;
    }

    $sf_date = explode(' ', (isset($_GET['post_date'])) ? urldecode($_GET['post_date']) : '');

    $sf_date = array_filter($sf_date);

    //Check if start and end date are both present
    if (count($sf_date) != 2) {
        return $permalink;
    }

    // Get start and end dates
    list($start_date, $end_date) = $sf_date;

    if(!strtotime($start_date)){
        return $permalink;
    }

    if(!strtotime($end_date)){
        return $permalink;
    }

    $start_date = new DateTime($start_date);
    $end_date = new DateTime($end_date);
    
    if(!$start_date){
        return $permalink;
    }

    if(!$end_date){
        return $permalink;
    }

    // Add them to the permalink
    $permalink = add_query_arg(array(
        'wpbs-start-month' => $start_date->format('n'),
        'wpbs-start-year' => $start_date->format('Y'),
        'wpbs-selection-start' => $start_date->format('Y-m-d'),
        'wpbs-selection-end' => $end_date->format('Y-m-d'),
    ), $permalink);

    return $permalink;

}
add_filter('post_link', 'wpbs_search_filter_post_link', 10, 3);
add_filter('page_link', 'wpbs_search_filter_post_link', 10, 3);
add_filter('post_type_link', 'wpbs_search_filter_post_link', 10, 3);