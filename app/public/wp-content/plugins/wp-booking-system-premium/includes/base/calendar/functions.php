<?php

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Includes the files needed for the Calendars
 *
 */
function wpbs_include_files_calendar()
{

    // Get calendar dir path
    $dir_path = plugin_dir_path(__FILE__);

    // Include other functions files
    if (file_exists($dir_path . 'functions-ajax.php')) {
        include $dir_path . 'functions-ajax.php';
    }

    // Include main Calendar class
    if (file_exists($dir_path . 'class-calendar.php')) {
        include $dir_path . 'class-calendar.php';
    }

    // Include the db layer classes
    if (file_exists($dir_path . 'class-object-db-calendars.php')) {
        include $dir_path . 'class-object-db-calendars.php';
    }

    if (file_exists($dir_path . 'class-object-meta-db-calendars.php')) {
        include $dir_path . 'class-object-meta-db-calendars.php';
    }

    // Include calendar outputters
    if (file_exists($dir_path . 'class-calendar-outputter.php')) {
        include $dir_path . 'class-calendar-outputter.php';
    }

    if (file_exists($dir_path . 'class-calendar-overview-outputter.php')) {
        include $dir_path . 'class-calendar-overview-outputter.php';
    }
}
add_action('wpbs_include_files', 'wpbs_include_files_calendar');

/**
 * Register the class that handles database queries for the Calendars
 *
 * @param array $classes
 *
 * @return array
 *
 */
function wpbs_register_database_classes_calendars($classes)
{

    $classes['calendars'] = 'WPBS_Object_DB_Calendars';
    $classes['calendarmeta'] = 'WPBS_Object_Meta_DB_Calendars';

    return $classes;
}
add_filter('wpbs_register_database_classes', 'wpbs_register_database_classes_calendars');

/**
 * Returns an array with WPBS_Calendar objects from the database
 *
 * @param array $args
 * @param bool  $count
 *
 * @return array
 *
 */
function wpbs_get_calendars($args = array(), $count = false)
{

    $calendars = wp_booking_system()->db['calendars']->get_calendars($args, $count);

    /**
     * Add a filter hook just before returning
     *
     * @param array $calendars
     * @param array $args
     * @param bool  $count
     *
     */
    return apply_filters('wpbs_get_calendars', $calendars, $args, $count);
}

/**
 * Gets a calendar from the database
 *
 * @param mixed int|object      - calendar id or object representing the calendar
 *
 * @return WPBS_Calendar|false
 *
 */
function wpbs_get_calendar($calendar)
{

    return wp_booking_system()->db['calendars']->get_object($calendar);
}

/**
 * Inserts a new calendar into the database
 *
 * @param array $data
 *
 * @return mixed int|false
 *
 */
function wpbs_insert_calendar($data)
{

    return wp_booking_system()->db['calendars']->insert($data);
}

/**
 * Updates a calendar from the database
 *
 * @param int     $calendar_id
 * @param array $data
 *
 * @return bool
 *
 */
function wpbs_update_calendar($calendar_id, $data)
{

    return wp_booking_system()->db['calendars']->update($calendar_id, $data);
}

/**
 * Deletes a calendar from the database
 *
 * @param int $calendar_id
 *
 * @return bool
 *
 */
function wpbs_delete_calendar($calendar_id)
{

    return wp_booking_system()->db['calendars']->delete($calendar_id);
}

/**
 * Inserts a new meta entry for the calendar
 *
 * @param int    $calendar_id
 * @param string $meta_key
 * @param string $meta_value
 * @param bool   $unique
 *
 * @return mixed int|false
 *
 */
function wpbs_add_calendar_meta($calendar_id, $meta_key, $meta_value, $unique = false)
{

    return wp_booking_system()->db['calendarmeta']->add($calendar_id, $meta_key, $meta_value, $unique);
}

/**
 * Updates a meta entry for the calendar
 *
 * @param int    $calendar_id
 * @param string $meta_key
 * @param string $meta_value
 * @param bool   $prev_value
 *
 * @return bool
 *
 */
function wpbs_update_calendar_meta($calendar_id, $meta_key, $meta_value, $prev_value = '')
{

    return wp_booking_system()->db['calendarmeta']->update($calendar_id, $meta_key, $meta_value, $prev_value);
}

/**
 * Returns a meta entry for the calendar
 *
 * @param int    $calendar_id
 * @param string $meta_key
 * @param bool   $single
 *
 * @return mixed
 *
 */
function wpbs_get_calendar_meta($calendar_id, $meta_key = '', $single = false)
{

    return wp_booking_system()->db['calendarmeta']->get($calendar_id, $meta_key, $single);
}

/**
 * Returns the translated meta entry for the calendar
 *
 * @param int    $calendar_id
 * @param string $meta_key
 * @param string $language_code
 *
 * @return mixed
 *
 */
function wpbs_get_translated_calendar_meta($calendar_id, $meta_key, $language_code)
{
    $settings           = get_option('wpbs_settings', array());
    $active_languages = (!empty($settings['active_languages']) ? $settings['active_languages'] : array());

    if (in_array($language_code, $active_languages)) {

        $translated_meta = wpbs_get_calendar_meta($calendar_id, $meta_key . '_translation_' . $language_code, true);

        if (!empty($translated_meta)) {
            return $translated_meta;
        }
    }

    return wpbs_get_calendar_meta($calendar_id, $meta_key, true);
}

/**
 * Removes a meta entry for the calendar
 *
 * @param int    $calendar_id
 * @param string $meta_key
 * @param string $meta_value
 * @param bool   $delete_all
 *
 * @return bool
 *
 */
function wpbs_delete_calendar_meta($calendar_id, $meta_key, $meta_value = '', $delete_all = '')
{

    return wp_booking_system()->db['calendarmeta']->delete($calendar_id, $meta_key, $meta_value, $delete_all);
}

/**
 * Returns the default arguments for the calendar outputter
 *
 * @return array
 *
 */
function wpbs_get_calendar_output_default_args()
{

    $args = array(
        'show_title' => 1,
        'months_to_show' => 1,
        'start_weekday' => 1,
        'show_legend' => 1,
        'legend_position' => 'side',
        'show_button_navigation' => 1,
        'show_selector_navigation' => 1,
        'show_week_numbers' => 0,
        'current_year' => date('Y'),
        'current_month' => date('n'),
        'jump_months' => 0,
        'highlight_today' => 0,
        'history' => 1,
        'show_tooltip' => 1,
        'show_prices' => 0,
        'language' => wpbs_get_locale(),
        'min_width' => '200',
        'max_width' => '380',
        'start_date' => 0,
        'end_date' => 0,
        'changeover_start' => 0,
        'changeover_end' => 0,
        'currency' => '',
        'form_position' => 'bottom',
        'manual_booking' => '',
    );

    /**
     * Filter the args before returning
     *
     * @param array $args
     *
     */
    $args = apply_filters('wpbs_get_calendar_output_default_args', $args);

    return $args;
}

/**
 * Returns the default arguments for the calendar overview outputter
 *
 * @return array
 *
 */
function wpbs_get_calendar_overview_output_default_args()
{

    $args = array(
        'show_legend' => 1,
        'legend_position' => 'top',
        'show_day_abbreviation' => 0,
        'current_year' => date('Y'),
        'current_month' => date('n'),
        'history' => 1,
        'show_tooltip' => 1,
        'language' => wpbs_get_locale(),
    );

    /**
     * Filter the args before returning
     *
     * @param array $args
     *
     */
    $args = apply_filters('wpbs_get_calendar_overview_output_default_args', $args);

    return $args;
}

/**
 * Returns an array with all iCal feeds saved in the database
 *
 * @param int $calendar_id
 *
 * @return array
 *
 */
function wpbs_get_calendar_meta_ical_feeds($calendar_id)
{

    global $wpdb;

    $calendar_id = absint($calendar_id);
    $table_name = wp_booking_system()->db['calendarmeta']->table_name;

    $results = $wpdb->get_results("SELECT meta_value FROM {$table_name} WHERE calendar_id = '{$calendar_id}' AND meta_key LIKE '%ical_feed_%'", ARRAY_A);

    if (!is_array($results)) {
        return array();
    }

    foreach ($results as $key => $result) {

        $meta_value = $results[$key]['meta_value'];

        unset($results[$key]);

        $results[$key] = maybe_unserialize($meta_value);
    }

    return $results;
}

/**
 * Returns the last added ical_feed id
 *
 * @param int $calendar_id
 *
 * @return int
 *
 */
function wpbs_get_ical_feeds_last_id($calendar_id)
{

    $ical_feeds = wpbs_get_calendar_meta_ical_feeds($calendar_id);
    $last_id = 0;

    foreach ($ical_feeds as $ical_feed) {

        if ($ical_feed['id'] > $last_id) {
            $last_id = $ical_feed['id'];
        }
    }

    return $last_id;
}

/**
 * Gets all ical feed events, from all linked URLs and returns them as
 * WPBS_Event objects that can be added to the calendar output
 *
 * @param int $calendar_id
 * @param array $existing_events
 *
 * @return array
 *
 */
function wpbs_get_ical_feeds_as_events($calendar_id, $existing_events)
{

    if (wpbs_get_calendar_meta($calendar_id, 'disable_icalendar_links', true) == true) {
        return array();
    }

    $ical_events = $temporary_ical_events = array();

    // Get the default legend item
    $legend_items = wpbs_get_legend_items(array('calendar_id' => $calendar_id));
    $legend_item_ids = [];
    foreach ($legend_items as $legend_item) {
        if ($legend_item->get('is_default')) {
            $default_legend = $legend_item->get('id');
        }
        $legend_item_ids[] = $legend_item->get('id');
    }

    // Loop for building temporary events
    $events = wpbs_get_ical_feeds_as_array($calendar_id);

    foreach ($existing_events as $event) {
        if ($event->get('legend_item_id') == $default_legend || $event->get('legend_item_id') == 0 || !in_array($event->get('legend_item_id'), $legend_item_ids)) {
            continue;
        }

        $temporary_ical_events[$event->get('date_year') . str_pad($event->get('date_month'), 2, '0', STR_PAD_LEFT) . str_pad($event->get('date_day'), 2, '0', STR_PAD_LEFT)] = $event->get('legend_item_id');
    }

    foreach ($events as $event) {

        if ($event['legend_item_id'] == $default_legend) {
            continue;
        }

        $temporary_ical_events[$event['date_year'] . $event['date_month'] . $event['date_day']] = 'ical_event';
    }

    // Loop for building the correct events array
    foreach ($events as $event) {

        $event_date = DateTime::createFromFormat('Y-m-d', $event['date_year'] . '-' . $event['date_month'] . '-' . $event['date_day']);

        $previous_day_event = $next_day_event = false;

        // Check if there are any past events for the current date
        $previous_day = clone $event_date;
        $previous_day->modify('-1 day');
        if (array_key_exists($previous_day->format('Ymd'), $temporary_ical_events)) {
            $previous_day_event = $temporary_ical_events[$previous_day->format('Ymd')];
        }

        // Check if there are any future events for the current date
        $next_day = clone $event_date;
        $next_day->modify('+1 day');
        if (array_key_exists($next_day->format('Ymd'), $temporary_ical_events)) {
            $next_day_event = $temporary_ical_events[$next_day->format('Ymd')];
        }

        /**
         *  If we don't use split days, it's easy. Just add in the events.
         *
         */
        if (!$event['split_days']) {
            $ical_events[] = wpbs_ical_create_event($event, $event_date, $calendar_id, $event['legend_item_id']);
        } else {

            /**
             * If there are previous AND next day events, we mark the date as fully booked.
             *
             */
            if ($previous_day_event && $next_day_event) {

                // We treat this as a fully booked day.
                $legend_item_id = $event['legend_item_id'];

                // But if the previous legend is an ending changeover, we keep this as a starting changeover.
                if ($previous_day_event == $event['legend_item_id_split_end']) {
                    $legend_item_id = $event['legend_item_id_split_start'];
                }

                $ical_events[] = wpbs_ical_create_event($event, $event_date, $calendar_id, $legend_item_id);

                // Check if the next day is overlapping a changeover
                $next_day_event = clone $event_date;
                $next_day_event->modify('+1 day');

                if (
                    array_key_exists($next_day_event->format('Ymd'), $temporary_ical_events) &&
                    $temporary_ical_events[$next_day_event->format('Ymd')] == $event['legend_item_id_split_start']
                ) {
                    $legend_item_id = $event['legend_item_id'];
                    // Then we also add the 'changeover' split in the calendar.
                    $event['meta']['ical-changeover'] = true;

                    $ical_events[] = wpbs_ical_create_event($event, $next_day_event, $calendar_id, $legend_item_id);
                }

                /**
                 *  If there's no previous day event, but there's an event next day,
                 * it means it's a starting booking and the date should be marked as a starting changeover
                 *
                 */
            } elseif (!$previous_day_event && $next_day_event) {

                $ical_events[] = wpbs_ical_create_event($event, $event_date, $calendar_id, $event['legend_item_id_split_start']);

                if ($next_day_event == $event['legend_item_id_split_start']) {
                    $event_date->modify('+1 day');
                    $ical_events[] = wpbs_ical_create_event($event, $event_date, $calendar_id, $event['legend_item_id']);
                }

                /**
                 * If there's a previous day event and there's no next day event,
                 * it means this should be an ending changeover
                 *
                 */
            } elseif ($previous_day_event && !$next_day_event) {

                // We treat this as a fully booked day.
                $legend_item_id = $event['legend_item_id'];

                // But if the previous legend is an ending changeover, we keep this as a starting changeover.
                if ($previous_day_event == $event['legend_item_id_split_end']) {
                    $legend_item_id = $event['legend_item_id_split_start'];
                }

                $ical_events[] = wpbs_ical_create_event($event, $event_date, $calendar_id, $legend_item_id);

                // In this case, we also need to add an ending changeover.

                $event_date->modify('+1 day');

                $next_day_event = clone $event_date;
                $next_day_event->modify('+1 day');

                // By default, this should be an ending changeover
                $legend_item_id = $event['legend_item_id_split_end'];

                // Unless the day after this is a booked event, in which case the next changeover is a fully booked day as well.
                if (
                    array_key_exists($next_day_event->format('Ymd'), $temporary_ical_events) &&
                    $temporary_ical_events[$next_day_event->format('Ymd')] != $event['legend_item_id_split_start'] &&
                    $temporary_ical_events[$next_day_event->format('Ymd')] != 'ical_event'
                ) {
                    $legend_item_id = $event['legend_item_id'];
                    // Then we also add the 'changeover' split in the calendar.
                    $event['meta']['ical-changeover'] = true;
                }

                $ical_events[] = wpbs_ical_create_event($event, $event_date, $calendar_id, $legend_item_id);

                /**
                 * If there's no previous or next event (this is a single day booking)
                 * it we should add a starting changeover followed by an ending changeover
                 *
                 */
            } elseif (!$previous_day_event && !$next_day_event) {
                $legend_item_id = $event['legend_item_id_split_start'];
                $ical_events[] = wpbs_ical_create_event($event, $event_date, $calendar_id, $legend_item_id);

                $event_date->modify('+1 day');

                $next_day_event = clone $event_date;
                $next_day_event->modify('+1 day');

                // By default, this should be an ending changeover
                $legend_item_id = $event['legend_item_id_split_end'];

                // Unless the day after this is a booked event, in which case the next changeover is a fully booked day as well.
                if (
                    array_key_exists($next_day_event->format('Ymd'), $temporary_ical_events) &&
                    $temporary_ical_events[$next_day_event->format('Ymd')] != $event['legend_item_id_split_start'] &&
                    $temporary_ical_events[$next_day_event->format('Ymd')] != 'ical_event'
                ) {
                    $legend_item_id = $event['legend_item_id'];
                    // Then we also add the 'changeover' split in the calendar.
                    $event['meta']['ical-changeover'] = true;
                }

                $ical_events[] = wpbs_ical_create_event($event, $event_date, $calendar_id, $legend_item_id);
            }
        }
    }

    return $ical_events;
}

/**
 * Create a iCal event to add to the calendar
 *
 */
function wpbs_ical_create_event($event, $event_date, $calendar_id, $legend_item_id)
{
    $event_data = array(
        'id' => null,
        'calendar_id' => $calendar_id,
        'legend_item_id' => $legend_item_id,
        'date_year' => $event_date->format('Y'),
        'date_month' => $event_date->format('m'),
        'date_day' => $event_date->format('d'),
        'description' => $event['description'],
        'tooltip' => $event['tooltip'],
        'meta' => $event['meta'],
    );

    return wpbs_get_event((object) $event_data);
}

/**
 * Gets all ical feed events, from all linked URLs and returns them as
 * an array of dates
 *
 * @param int $calendar_id
 *
 * @return array
 *
 */
function wpbs_get_ical_feeds_as_array($calendar_id)
{

    $ical_feeds = wpbs_get_calendar_meta_ical_feeds($calendar_id);

    $events = array();

    // Include the iCal Reader
    include_once WPBS_PLUGIN_DIR . 'includes/libs/iCalReader/class-ical-reader.php';

    // Initial loop to temporarily store iCal events
    foreach ($ical_feeds as $ical_feed) {

        if (empty($ical_feed['file_contents'])) {
            continue;
        }

        if (empty($ical_feed['url'])) {
            continue;
        }

        if (isset($ical_feed['disabled']) && $ical_feed['disabled'] == true) {
            continue;
        }

        // Extract the file in an array format
        $ical_reader = new WPBS_ICal_Reader();
        $ical_arr = $ical_reader->init_contents($ical_feed['file_contents']);

        if (empty($ical_arr['VEVENT']) || !is_array($ical_arr['VEVENT'])) {
            continue;
        }

        foreach ($ical_arr['VEVENT'] as $ical_event) {

            $ical_event = apply_filters('wpbs_ical_import_from_url_event', $ical_event);

            if ($ical_event === false) {
                continue;
            }

            // Remove timezones from strings
            $dtstart = wpbs_remove_timezone_from_date_string($ical_event['DTSTART']);
            $dtend = wpbs_remove_timezone_from_date_string($ical_event['DTEND']);
            
            // If no dtend exists, create it as a single day event.
            if(empty($dtend)){
                $dtend = DateTime::createFromFormat('Ymd', $dtstart);
                $dtend->modify('+1 day');
                $dtend = $dtend->format('Ymd');
            }

            // Check for invalid dates
            if (!is_numeric($dtstart) || !is_numeric($dtend)) {
                continue;
            }

            $begin = new DateTime($dtstart);
            $end = new DateTime($dtend);

            $begin->setTime(0, 0, 0);
            $end->setTime(23, 59, 59);

            // Check if it's an hourly event
            $interval = $begin->diff($end);
            if ($interval->days == 0) {
                $end->modify('+1 day');
            }

            $end->modify('-1 day');

            /**
             * Allow adding an offset to iCalendar feeds.
             */
            $start_offset = apply_filters('wpbs_ical_import_from_url_offset_start', false);
            $end_offset = apply_filters('wpbs_ical_import_from_url_offset_end', false);

            if ($start_offset !== false) {
                $begin->modify($start_offset);
            }

            if ($end_offset !== false) {
                $end->modify($end_offset);
            }

            for ($i = clone $begin; $i <= $end; $i->modify('+1 day')) {

                $meta = array();

                if (($i == $begin || $i == $end) && $ical_feed['split_days']) {
                    $meta['ical-changeover'] = true;
                }

                if (!empty($ical_event['DESCRIPTION'])) {
                    $meta['ical-description'] = wp_kses_post($ical_event['DESCRIPTION']);
                }

                $event_data = array(
                    'legend_item_id' => $ical_feed['legend_item_id'],
                    'split_days' => isset($ical_feed['split_days']) ? $ical_feed['split_days'] : 0,
                    'legend_item_id_split_start' => isset($ical_feed['legend_item_id_split_start']) ? $ical_feed['legend_item_id_split_start'] : false,
                    'legend_item_id_split_end' => isset($ical_feed['legend_item_id_split_end']) ? $ical_feed['legend_item_id_split_end'] : false,
                    'date_year' => $i->format('Y'),
                    'date_month' => $i->format('m'),
                    'date_day' => $i->format('d'),
                    'description' => $ical_feed['name'] . ' - ' . (!empty($ical_event['SUMMARY']) ? wp_kses_post($ical_event['SUMMARY']) : ''),
                    'tooltip' => (!empty($ical_event['SUMMARY']) ? wp_kses_post($ical_event['SUMMARY']) : ''),
                    'meta' => $meta,
                );

                $events[] = $event_data;
            }
        }
    }

    return $events;
}

/**
 * Get an iCalendar event by day
 *
 * @param int $calendar_id
 * @param int $day
 * @param int $month
 * @param int $year
 *
 * @return mixed WPBS_Event || false
 *
 */
function wpbs_get_ical_event_by_date($calendar_id, $day, $month, $year)
{
    $events = wpbs_get_ical_feeds_as_events($calendar_id, array());
    foreach ($events as $event) {
        if ($event->get('date_year') == $year && $event->get('date_month') == $month && $event->get('date_day') == $day) {
            return $event;
        }
    }
    return false;
}

/**
 * Gets all the bookings, loops through the interval and returns WPBS_Event objects
 *
 * @param int $calendar_id
 *
 * @return array
 *
 */
function wpbs_get_bookings_as_events($calendar_id, $events, $year, $month)
{

    $booking_events = array();
    $month = str_pad($month, 2, '0', STR_PAD_LEFT);

    $bookings = wpbs_get_bookings(array('calendar_id' => $calendar_id, 'orderby' => 'id', 'order' => 'asc', 'status' => array('pending', 'accepted'), 'custom_query' =>
    ' AND ' . $year . $month . ' BETWEEN EXTRACT(YEAR_MONTH FROM start_date) and EXTRACT(YEAR_MONTH FROM end_date)'));

    foreach ($bookings as $booking) {
        $events_begin = new DateTime($booking->get('start_date'));

        $events_end = new DateTime($booking->get('end_date'));
        $events_end->modify('+1 day');

        $interval = DateInterval::createFromDateString('1 day');
        $period = new DatePeriod($events_begin, $interval, $events_end);

        foreach ($period as $event_date) {

            $event_data = array(
                'id' => null,
                'calendar_id' => $calendar_id,
                'booking_id' => $booking->get('id'),
                'date_year' => $event_date->format('Y'),
                'date_month' => $event_date->format('m'),
                'date_day' => $event_date->format('d'),
            );
            $booking_events[] = wpbs_get_event((object) $event_data);
        }
    }

    return $booking_events;
}

/**
 * Remove Timezone from Date strings.
 *
 * @param string $date
 *
 * @return string
 *
 */
function wpbs_remove_timezone_from_date_string($date)
{
    return trim(explode('T', $date)[0], 'Z');
}

/**
 * Check if a date range in a calendar is bookable.
 *
 * @since 5.7
 *
 * @param int $calendar_id
 * @param string $start_date Ymd
 * @param string $end_date Ymd
 *
 * @return bool
 *
 */
function wpbs_check_if_date_range_is_bookable($calendar_id, $start_date, $end_date)
{
    $non_bookable_legend_items = array();
    $legend_items = wpbs_get_legend_items(array('calendar_id' => $calendar_id));

    $changeover_start = $changeover_end = -1;

    foreach ($legend_items as $legend_item) {
        if (!$legend_item->get('is_bookable')) {
            $non_bookable_legend_items[] = $legend_item->get('id');
        }

        if ($legend_item->get('auto_pending') == 'changeover_start') {
            $changeover_start = $legend_item->get('id');
        }

        if ($legend_item->get('auto_pending') == 'changeover_end') {
            $changeover_end = $legend_item->get('id');
        }
    }

    $changeover_start_found = $changeover_end_found = 0;

    // Loop through events
    $calendar_events = wpbs_get_events(array('calendar_id' => $calendar_id, 'custom_query' => ' AND date_year >= ' . date('Y'))); // Calendar Events
    $ical_events = wpbs_get_ical_feeds_as_events($calendar_id, $calendar_events); // iCalendar Events

    $events = array_merge($calendar_events, $ical_events);

    $sorted_events = array();
    foreach ($events as $event) {
        $sorted_events[$event->get('date_year') . str_pad($event->get('date_month'), 2, '0', STR_PAD_LEFT) . str_pad($event->get('date_day'), 2, '0', STR_PAD_LEFT)] = $event->get('legend_item_id');
    }

    ksort($sorted_events);

    $interval = DateInterval::createFromDateString('1 day');
    $start = DateTime::createFromFormat('Ymd', $start_date);
    $end = DateTime::createFromFormat('Ymd', $end_date);
    $end->modify('+1 day');
    $period = new DatePeriod($start, $interval, $end);

    $selection = [];

    foreach ($period as $date) {
        $selection[$date->format('Ymd')] = false;
    }

    foreach ($sorted_events as $event_date => $event_legend_item_id) {
        // If event date is outside search range, continue;
        if ($event_date < $start_date || $event_date > $end_date) {
            continue;
        }

        // Check if the event found is not bookable
        if (in_array($event_legend_item_id, $non_bookable_legend_items)) {
            return false;
        }

        // Check for changeovers. The rule is that if a start changeover exists in an array, we shouln't have an end changeover

        // We found a starting changeover date
        if ($event_legend_item_id == $changeover_start) {
            $changeover_start_found++;
        }

        if ($event_legend_item_id == $changeover_end) {
            $changeover_end_found++;
        }

        // Now if we find an ending changeover date and a starting changeover date was previously found, we mark the date as not available.
        if ($event_legend_item_id == $changeover_end && $changeover_start_found !== 0) {
            return false;
        }

        // If more than 2 starting changeovers are found, selection is invalid
        if ($changeover_start_found !== 0 && $changeover_start_found > 1) {
            return false;
        }

        // If more than 2 ending changeovers are found, selection is invalid
        if ($changeover_end_found !== 0 && $changeover_end_found > 1) {
            return false;
        }

        $selection[$event_date] = $event_legend_item_id;
    }

    foreach ($selection as $i => $legend_id) {

        if ($legend_id === false) {
            continue;
        }

        // Check if the selection starts with a starting changeover
        if ($i == $start_date && $legend_id == $changeover_start) {
            return false;
        }

        // Check if the selection ends with an ending changeover.
        if ($i == $end_date && $legend_id == $changeover_end) {
            return false;
        }

        // Check if we have any changeover in the middle of our selection range
        if ($i != $start_date && $i != $end_date && ($legend_id == $changeover_start || $legend_id == $changeover_end)) {
            return false;
        }
    }

    return true;
}

/**
 * Change the current starting month or starting year of the calendar with an url parameter
 *
 * @param array $calendar_args
 *
 * @return array
 *
 */
function wpbs_calendar_shortcode_dynamic_args($calendar_args, $calendar_id)
{
    // Allow dynamic changing of month
    if (isset($_GET['wpbs-start-month']) && !empty($_GET['wpbs-start-month'])) {
        $calendar_args['current_month'] = absint($_GET['wpbs-start-month']);
    }

    // Allow dynamic changing of month
    if (isset($_GET['wpbs-start-year']) && !empty($_GET['wpbs-start-year'])) {
        $calendar_args['current_year'] = absint($_GET['wpbs-start-year']);
    }

    // Allow dynamic setting of selection
    if (
        isset($_GET['wpbs-selection-start']) && !empty($_GET['wpbs-selection-start']) &&
        isset($_GET['wpbs-selection-end']) && !empty($_GET['wpbs-selection-end'])
    ) {

        $start_date = DateTime::createFromFormat('Y-m-d', $_GET['wpbs-selection-start']);
        $end_date = DateTime::createFromFormat('Y-m-d', $_GET['wpbs-selection-end']);

        // Silently fail if an invalid date was passed
        if (!empty(DateTime::getLastErrors()['error_count'])) {
            return $calendar_args;
        }

        // ..or if the date is in the past
        if ($start_date < (new DateTime())->setTime(0, 0)) {
            return $calendar_args;
        }

        // ..or if the starting date is grater than the ending date
        if ($start_date > $end_date) {
            return $calendar_args;
        }

        // Set time time to 0
        $start_date->setTime(0,0,0);
        $end_date->setTime(0,0,0);

        if (wpbs_check_if_date_range_is_bookable($calendar_id, $start_date->format('Ymd'), $end_date->format('Ymd')) == false) {
            return $calendar_args;
        }

        $calendar_args['start_date'] = $start_date->getTimestamp() * 1000;
        $calendar_args['end_date'] = $end_date->getTimestamp() * 1000;
    }

    return $calendar_args;
}
add_filter('wpbs_calendar_shortcode_args', 'wpbs_calendar_shortcode_dynamic_args', 10, 2);

/**
 * Change the current starting month or starting year of the calendar with an url parameter
 *
 * @param array $calendar_args
 *
 * @return array
 *
 */
function wpbs_overview_calendar_shortcode_dynamic_args($calendar_args)
{
    // Allow dynamic changing of month
    if (isset($_GET['wpbs-start-month']) && !empty($_GET['wpbs-start-month'])) {
        $calendar_args['current_month'] = absint($_GET['wpbs-start-month']);
    }

    // Allow dynamic changing of month
    if (isset($_GET['wpbs-start-year']) && !empty($_GET['wpbs-start-year'])) {
        $calendar_args['current_year'] = absint($_GET['wpbs-start-year']);
    }

    return $calendar_args;
}
add_filter('wpbs_overview_calendar_shortcode_args', 'wpbs_overview_calendar_shortcode_dynamic_args', 10, 1);

/**
 * Refresh an icalendar feed
 * 
 * @param int $calendar_id
 * 
 */
function wpbs_maybe_refresh_icalendar_feed($calendar_id)
{

    $plugin_settings = get_option( 'wpbs_settings', array() );

    // Get iCal feeds
    $ical_feeds = wpbs_get_calendar_meta_ical_feeds($calendar_id);

    if (empty($ical_feeds))
        return;

    $refresh_time = 0;

    // Get and set refresh time
    if (empty($plugin_settings['ical_refresh_times']) || $plugin_settings['ical_refresh_times'] == 'hourly') {

        $refresh_time = HOUR_IN_SECONDS;
    } else {

        if ($plugin_settings['ical_refresh_times'] == 'hourly')
            $refresh_time = HOUR_IN_SECONDS;

        elseif ($plugin_settings['ical_refresh_times'] == 'daily')
            $refresh_time = DAY_IN_SECONDS;

        elseif ($plugin_settings['ical_refresh_times'] == 'custom') {

            if (empty($plugin_settings['ical_custom_refresh_time']) || $plugin_settings['ical_custom_refresh_time'] < 0)
                $refresh_time = 0;

            else {

                $refresh_unit = (empty($plugin_settings['ical_custom_refresh_time_unit']) || $plugin_settings['ical_custom_refresh_time_unit'] == 'minutes' ? MINUTE_IN_SECONDS : HOUR_IN_SECONDS);
                $refresh_time = absint($plugin_settings['ical_custom_refresh_time']) * $refresh_unit;
            }
        }
    }

    // Fetch new feeds
    foreach ($ical_feeds as $ical_feed) {

        if (empty($ical_feed['id']))
            continue;

        if (empty($ical_feed['url']))
            continue;

        if ($refresh_time != 0 && strtotime($ical_feed['last_updated']) > (current_time('timestamp') - $refresh_time))
            continue;

        $ical_contents = wp_remote_get($ical_feed['url'], array('timeout' => 30, 'user-agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.114 Safari/537.36'));

        if (wp_remote_retrieve_response_code($ical_contents) != 200)
            continue;

        $ical_contents = wp_remote_retrieve_body($ical_contents);

        if (0 !== strpos($ical_contents, 'BEGIN:VCALENDAR') || false === strpos($ical_contents, 'END:VCALENDAR'))
            continue;

        $ical_contents = addslashes($ical_contents);

        $ical_feed['file_contents'] = $ical_contents;
        $ical_feed['last_updated']  = current_time('Y-m-d H:i:s');

        wpbs_update_calendar_meta($calendar_id, 'ical_feed_' . $ical_feed['id'], $ical_feed);
    }
}
