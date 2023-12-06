<?php

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Validates and handles the resetting of the Calendar's ical_hash
 *
 */
function wpbs_action_csv_export()
{

    // Verify for nonce
    if (empty($_POST['wpbs_token']) || !wp_verify_nonce($_POST['wpbs_token'], 'wpbs_csv_export')) {
        return;
    }

    // Verify the calendar id
    if (empty($_POST['calendar_id'])) {
        wpbs_admin_notices()->register_notice('csv_export_calendar_id_missing', '<p>' . __('Invalid Calendar ID.', 'wp-booking-system') . '</p>', 'error');
        wpbs_admin_notices()->display_notice('csv_export_calendar_id_missing');
        return;
    }

    // Get the calendar
    $calendar_id = absint($_POST['calendar_id']);

    // Verify for start and end date
    if (empty($_POST['wpbs-export-csv-start-date']) || empty($_POST['wpbs-export-csv-end-date'])) {
        wpbs_admin_notices()->register_notice('csv_export_date_missing', '<p>' . __('Please select a starting date and an ending date.', 'wp-booking-system') . '</p>', 'error');
        wpbs_admin_notices()->display_notice('csv_export_date_missing');
        return;
    }

    $selected_legend_items = array();

    // Get legent item names
    $default_legend = false;
    $default_legend = false;

    if(isset($_POST['csv-export-legend-items']) && !empty($_POST['csv-export-legend-items'])){
        foreach ($_POST['csv-export-legend-items'] as $export_legend_item) {
            $legend_item = wpbs_get_legend_item($export_legend_item);
            if ($legend_item->get('is_default')) {
                $default_legend = $legend_item->get('name');
                $default_legend_id = $legend_item->get('id');
            }
            $selected_legend_items[$export_legend_item] = $legend_item->get('name');
        }
    } else {
        foreach (wpbs_get_legend_items(['calendar_id' => $calendar_id]) as $legend_item) {
            if ($legend_item->get('is_default')) {
                $default_legend = $legend_item->get('name');
                $default_legend_id = $legend_item->get('id');
            }
            $selected_legend_items[$legend_item->get('id')] = $legend_item->get('name');
        }
    }

    // Get events
    $events = wpbs_get_events(array('calendar_id' => $calendar_id, 'orderby' => 'date_year, date_month, date_day', 'order' => 'ASC'));

    // Include iCalendar events as well?
    if (isset($_POST['csv-icalendar-events']) && $_POST['csv-icalendar-events'] == 'yes') {
        $ical_events = [];
        $ical_events_array = array_merge($events, wpbs_get_ical_feeds_as_events($calendar_id, $events));

        foreach($ical_events_array as $ical_event){
            $ical_events[$ical_event->get('date_year') . $ical_event->get('date_month') . $ical_event->get('date_day') ] = $ical_event;
        }
    }

    //CSV Header
    if ($_POST['csv-date-format'] == 'groupped_date') {
        $csv_header = array('Date' => '-',);
    } else {
        $csv_header = array('Year' => '-', 'Month' => '-', 'Day' => '-');
    }

    $csv_header['Legend'] = '-';
    $csv_header['Description'] = '-';

    if (wpbs_is_pricing_enabled()) {
        $csv_header['Price'] = '-';
    }

    if (wpbs_is_inventory_enabled()) {
        $csv_header['Inventory'] = '-';
    }
    
    $csv_header['Tooltip'] = '-';

    // Set Start Date
    if (isset($_POST['wpbs-export-csv-start-date']) && !empty($_POST['wpbs-export-csv-start-date'])) {
        $start_date = DateTime::createFromFormat('Y-m-d', $_POST['wpbs-export-csv-start-date']);
    }

    // Set End Date
    if (isset($_POST['wpbs-export-csv-end-date']) && !empty($_POST['wpbs-export-csv-end-date'])) {
        $end_date = DateTime::createFromFormat('Y-m-d', $_POST['wpbs-export-csv-end-date']);
    }

    $csv_lines = array();

    // Add the CSV header
    foreach ($csv_header as $header_key => $header_value) {
        $csv_lines[0][$header_key] = $header_key;
    }

    $i = 1;

    // Loop through events
    $interval = DateInterval::createFromDateString('1 day');
    $period = new DatePeriod($start_date, $interval, $end_date);
    foreach ($period as $date) {
        $events = wpbs_get_events(array('calendar_id' => $calendar_id, 'date_day' => $date->format('d'), 'date_month' => $date->format('m'), 'date_year' => $date->format('Y')));

        if (!isset($events[0])) {
            $event_data = array(
                'id' => null,
                'calendar_id' => $calendar_id,
                'legend_item_id' => $default_legend_id,
                'date_year' => $date->format('Y'),
                'date_month' => $date->format('m'),
                'date_day' => $date->format('d'),
                'description' => '',
                'tooltip' => '',
                'price' => wpbs_get_calendar_meta($calendar_id, 'default_price', true),
                'inventory' => wpbs_get_calendar_meta($calendar_id, 'default_inventory', true),
            );

            $event = wpbs_get_event((object) $event_data);
        } else {
            $event = $events[0];
        }

        if (isset($_POST['csv-icalendar-events']) && $_POST['csv-icalendar-events'] == 'yes' && isset($ical_events[$date->format('Y').$date->format('m').$date->format('d')])) {
            $event_data = $ical_events[$date->format('Y').$date->format('m').$date->format('d')]->to_array();
            $event_data['price'] = $event->get('price');
            $event_data['inventory'] = $event->get('inventory');
            $event = wpbs_get_event((object) $event_data);
        }

        // Check if legend item is correct
        if (!($default_legend && $event->get('legend_item_id') == 0) && !array_key_exists($event->get('legend_item_id'), $selected_legend_items)) {
            continue;
        }

        $csv_lines[$i] = $csv_header;

        $legend_name = ($default_legend && $event->get('legend_item_id') == 0) ? $default_legend : $selected_legend_items[$event->get('legend_item_id')];

        // Add event to CSV
        if ($_POST['csv-date-format'] == 'groupped_date') {
            $csv_lines[$i] = array(
                'Date' => $date->format(get_option('date_format')),

            );
        } else {
            $csv_lines[$i] = array(
                'Year' => $date->format('Y'),
                'Month' => $date->format('n'),
                'Day' => $date->format('j'),

            );
        }

        $csv_lines[$i]['Legend'] = $legend_name;
        $csv_lines[$i]['Description'] = $event->get('description');

        if (wpbs_is_pricing_enabled()) {
            $price = $event->get('price') ?: wpbs_get_calendar_meta($calendar_id, 'default_price', true);
            $csv_lines[$i]['Price'] = $price;
        }

        if (wpbs_is_inventory_enabled()) {
            $inventory = $event->get('inventory') ?: wpbs_get_calendar_meta($calendar_id, 'default_inventory', true);
            $csv_lines[$i]['Inventory'] = $inventory;
        }

        $i++;
    }

    if ($i === 1) {
        wpbs_admin_notices()->register_notice('csv_export_file_empty', '<p>' . __('No events matched your criteria.', 'wp-booking-system') . '</p>', 'error');
        wpbs_admin_notices()->display_notice('csv_export_file_empty');
        return;
    }

    // Output headers so that the file is downloaded rather than displayed
    header('Content-type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename="wpbs-dates-export-calendar-' . $calendar_id . '-' . time() . '.csv"');

    // Do not cache the file
    header('Pragma: no-cache');
    header('Expires: 0');
    echo "\xEF\xBB\xBF"; // UTF-8 BOM

    // Create a file pointer connected to the output stream
    $file = fopen('php://output', 'w');

    // Output each row of the data
    foreach ($csv_lines as $line) {
        $delimiter = apply_filters('wpbs_csv_delimiter', ',');
        fputcsv($file, $line, $delimiter);
    }

    exit();
}
add_action('wpbs_action_csv_export', 'wpbs_action_csv_export');
