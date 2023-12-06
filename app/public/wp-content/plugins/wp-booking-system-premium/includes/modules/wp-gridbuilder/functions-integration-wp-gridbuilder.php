<?php

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Create the data source for the facet
 *
 */
add_filter(
    'wp_grid_builder/custom_fields',
    function ($fields) {

        $fields['WP Booking System'] = [
            'wpbs_calendar_dates' => __('Calendar Dates', 'wp-bookingsystem'),
        ];

        return $fields;

    }
);

/**
 * Do some filtering :)
 *
 */
function wpbs_prefix_query_objects($object_ids, $facet)
{

    if ($facet['filter_type'] != 'date') {
        return $object_ids;
    }

    if ($facet['source'] != 'post_meta/wpbs_calendar_dates') {
        return $object_ids;
    }

    $start_date = $end_date = false;

    if (isset($facet['selected'][0])) {
        $start_date = DateTime::createFromFormat('Y-m-d', $facet['selected'][0]);
        $start_date = $start_date->format('Ymd');
    }

    if ($facet['date_type'] == 'range' && isset($facet['selected'][1])) {
        $end_date = DateTime::createFromFormat('Y-m-d', $facet['selected'][1]);
        $end_date = $end_date->format('Ymd');
    } else {
        $end_date = $start_date;
    }

    if ($start_date === false) {
        return $object_ids;
    }

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

    return $matches;

}
add_filter('wp_grid_builder/facet/query_objects', 'wpbs_prefix_query_objects', 10, 2);
