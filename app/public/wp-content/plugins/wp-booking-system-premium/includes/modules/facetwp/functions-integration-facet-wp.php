<?php

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Add WP Booking System as a Facet source
 *
 * @param array $sources
 *
 * @return array
 *
 */
function wpbs_facetwp_facet_sources($sources)
{
    $sources['wpbs'] = array(
        'label' => __('WP Booking System', 'wp-booking-system'),
        'choices' => [
            'wpbs_date' => __('Calendar Dates', 'wp-booking-system'),
        ],
        'weight' => 10,
    );

    return $sources;
}
add_filter('facetwp_facet_sources', 'wpbs_facetwp_facet_sources', 10, 1);

/**
 * Filter results
 *
 * @param bool|array $return
 * @param array $params
 *
 * @return bool|array
 *
 */

function wpbs_facetwp_facet_filter_posts($return, $params)
{

    // Check if Facet type is "Date Range" and source is "WP Booking System", and both fields are present
    if ($params['facet']['type'] != 'date_range' || $params['facet']['source'] != 'wpbs_date' || $params['facet']['fields'] != 'both') {
        return $return;
    }

    // Check if both start and end date are selected
    if (empty($params['selected_values'][0]) || empty($params['selected_values'][1])) {
        return 'continue';
    }

    // Search through calendars
    $date = DateTime::createFromFormat('Y-m-d', $params['selected_values'][0]);
    $start_date = $date->format('Ymd');

    // Format end date
    $date = DateTime::createFromFormat('Y-m-d', $params['selected_values'][1]);
    $end_date = $date->format('Ymd');

    $matches = [];

    // Get all calendars
    $calendars = wpbs_get_calendars(array('status' => 'active', 'orderby' => 'name', 'order' => 'asc'));

    foreach ($calendars as $calendar) {

        // Check if calendar has a page attached to it
        $calendar_link = wpbs_get_translated_calendar_meta($calendar->get('id'), 'calendar_link_internal', wpbs_get_site_language());

        if (empty($calendar_link)) {
            continue;
        }

        // If date is available, add it to $matches
        if (wpbs_check_if_date_range_is_bookable($calendar->get('id'), $start_date, $end_date) === true) {
            $matches[] = $calendar_link;
        }
    }

    if (!empty($matches)) {
        return $matches;
    }

    return $return;

}
add_filter('facetwp_facet_filter_posts', 'wpbs_facetwp_facet_filter_posts', 10, 2);

//Add current date to permalink

function wpbs_facetwp_query_string($permalink, $post, $leavename)
{
    // Check if a Date Facet is selected
    if (!isset($_GET['fwp_date']) && !isset($_POST['data']['http_params']['get']['fwp_date'])) {
        return $permalink;
    }
    
    $fwp_date = explode(',', (isset($_GET['fwp_date'])) ? $_GET['fwp_date'] : urldecode($_POST['data']['http_params']['get']['fwp_date']));
    
    $fwp_date = array_filter($fwp_date);

    //Check if start and end date are both present
    if (count($fwp_date) != 2) {
        return $permalink;
    }

    // Get start and end dates
    list($start_date, $end_date) = $fwp_date;

    $date = DateTime::createFromFormat("Y-m-d", $start_date);

    // Add them to the permalink
    $permalink = add_query_arg(array(
        'wpbs-start-month' => $date->format('n'),
        'wpbs-start-year' => $date->format('Y'),
        'wpbs-selection-start' => $start_date,
        'wpbs-selection-end' => $end_date,
    ), $permalink);

    return $permalink;

}
add_filter('post_link', 'wpbs_facetwp_query_string', 10, 3);
add_filter('page_link', 'wpbs_facetwp_query_string', 10, 3);
add_filter('post_type_link', 'wpbs_facetwp_query_string', 10, 3);
