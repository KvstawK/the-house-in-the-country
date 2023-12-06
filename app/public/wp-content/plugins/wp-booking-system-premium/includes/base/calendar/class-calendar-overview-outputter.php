<?php

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

class WPBS_Calendar_Overview_Outputter
{

    /**
     * The arguments for the calendar overview outputter
     *
     * @access protected
     * @var    array
     *
     */
    protected $args;

    /**
     * An array with WPBS_Calendar object
     *
     * @access protected
     * @var    array
     *
     */
    protected $calendars = array();

    /**
     * An array with calendar ids
     *
     * @access protected
     * @var    array
     *
     */
    protected $calendar_ids = array();

    /**
     * The list of legend items associated with each calendar
     *
     * @access protected
     * @var    array
     *
     */
    protected $legend_items = array();

    /**
     * The list of default legend items for each calendar
     *
     * @access protected
     * @var    WPBS_Legend_Item
     *
     */
    protected $default_legend_items = array();

    /**
     * The list of events for each calendar for the given displayed range
     *
     * @access protected
     * @var    array
     *
     */
    protected $events = array();

    /**
     * The list of events from the linked iCal feeds
     *
     * @access protected
     * @var    array
     *
     */
    protected $ical_events = array();

    /**
     * The plugin general settings
     *
     * @access protected
     * @var    array
     *
     */
    protected $plugin_settings = array();

    /**
     * Constructor
     *
     * @param array $calendars
     * @param array $args
     *
     */
    public function __construct($calendars, $args = array())
    {

        /**
         * Set arguments
         *
         */
        $this->args = wp_parse_args($args, wpbs_get_calendar_overview_output_default_args());

        /**
         * Set the calendars
         *
         */
        $this->calendars = $calendars;

        /**
         * Set calendar ids
         *
         */
        foreach ($calendars as $calendar) {
            $this->calendar_ids[] = $calendar->get('id');
            
            /**
             * Refresh iCalendar feeds
             * 
             */
            $this->refresh_ical_feeds($calendar);
        }

        /**
         * Set legend items
         *
         */
        foreach ($calendars as $calendar) {

            $this->legend_items[$calendar->get('id')] = wpbs_get_legend_items(array('calendar_id' => $calendar->get('id')));

        }

        /**
         * Set default legend items
         *
         */
        foreach ($this->legend_items as $calendar_id => $legend_items) {

            foreach ($legend_items as $legend_item) {

                if (!$legend_item->get('is_default')) {
                    continue;
                }

                $this->default_legend_items[$calendar_id] = $legend_item;

            }

        }

        /**
         * Set the calendar events
         *
         */
        foreach ($calendars as $calendar) {

            $this->events[$calendar->get('id')] = wpbs_get_events(array('calendar_id' => $calendar->get('id')));

        }

        /**
         * Set the calendar iCal events
         *
         */
        foreach ($calendars as $calendar) {

            $this->ical_events[$calendar->get('id')] = wpbs_get_ical_feeds_as_events($calendar->get('id'), $this->events[$calendar->get('id')]);

        }

        /**
         * Set plugin settings
         *
         */
        $this->plugin_settings = get_option('wpbs_settings', array());

        /**
         * Refresh the iCal feeds
         *
         */
        foreach ($calendars as $calendar) {
            /**
             * Hook some stuff in here if needed
             * 
             */
            do_action('wpbs_overview_calendar_outputter_calendar', $calendar);

        }

    }

    /**
     * Constructs and returns the HTML for the entire calendar overview
     *
     * @return string
     *
     */
    public function get_display()
    {

        /**
         * Prepare needed data
         *
         */
        $year_to_show = (int) $this->args['current_year'];
        $month_to_show = (int) $this->args['current_month'];

        $calendar_html_data = 'data-ids="' . implode(',', $this->calendar_ids) . '" ';

        foreach ($this->args as $arg => $val) {
            $calendar_html_data .= 'data-' . $arg . '="' . esc_attr($val) . '" ';
        }

        /**
         * Handle output for the calendar overview
         *
         */
        $output = '<div class="wpbs-overview-container" ' . $calendar_html_data . '>';

        /**
         * Calendar Legend Top
         *
         */
        if ($this->args['show_legend'] && $this->args['legend_position'] != 'bottom') {
            $output .= $this->get_display_legend();
        }

        $output .= '<div class="wpbs-overview-inner">';

        /**
         * Display the navigation and date numbers
         *
         */
        $output .= $this->get_display_header_row($year_to_show, $month_to_show);

        /**
         * Display the dates abbreviation
         *
         */
        if ($this->args['show_day_abbreviation']) {
            $output .= $this->get_display_header_abbreviations_row($year_to_show, $month_to_show);
        }

        /**
         * Display the calendars month
         *
         */
        foreach ($this->calendars as $calendar) {

            $output .= $this->get_display_calendar_row($calendar, $year_to_show, $month_to_show);

        }

        $output .= '</div>'; // end of .wpbs-overview-inner

        /**
         * Calendar Legend Top
         *
         */
        if ($this->args['show_legend'] && $this->args['legend_position'] == 'bottom') {
            $output .= $this->get_display_legend();
        }

        /**
         * Calendar Custom CSS
         *
         */
        $output .= $this->get_custom_css();

        /**
         * Flag needed for Gutenberg block to properly display the calendar
         * in the editor after the block settings are changed
         *
         */
        $output .= '<div class="wpbs-overview-container-loaded" data-just-loaded="1"></div>';

        $output .= '</div>'; // end of .wpbs-overview-container

        return $output;

    }

    /**
     * Constructs and returns the HTML row for the main header
     *
     * @param int $year
     * @param int $month
     *
     * @return string
     *
     */
    protected function get_display_header_row($year, $month)
    {

        $total_days = 31;
        $days_in_month = date('t', mktime(0, 0, 0, $month, 1, $year));

        $output = '<div class="wpbs-overview-row wpbs-overview-header">';

        /**
         * Calendar navigation
         *
         */
        $output .= '<div class="wpbs-overview-header-navigation wpbs-overview-row-header">';
        $output .= '<div class="wpbs-overview-row-header-inner">' . $this->get_display_month_selector($year, $month) . '</div>';
        $output .= '</div>';

        /**
         * Header date numbers
         *
         */
        $output .= '<div class="wpbs-overview-row-content">';

        for ($day = 1; $day <= $total_days; $day++) {

            $output .= '<div><div class="wpbs-date"><div class="wpbs-date-inner">';

            if ($day <= $days_in_month) {
                $output .= $day;
            }

            $output .= '</div></div></div>';

        }

        $output .= '</div>'; // end of .wpbs-overview-row-content

        $output .= '</div>';

        return $output;

    }

    /**
     * Constructs and returns the HTML row for the dates abbreviations
     *
     * @param int $year
     * @param int $month
     *
     * @return string
     *
     */
    protected function get_display_header_abbreviations_row($year, $month)
    {

        $total_days = 31;
        $days_in_month = date('t', mktime(0, 0, 0, $month, 1, $year));

        $output = '<div class="wpbs-overview-row wpbs-overview-row-abbreviations">';

        /**
         * Empty row header
         *
         */
        $output .= '<div class="wpbs-calendar-header-navigation wpbs-overview-row-header">';
        $output .= '<div class="wpbs-overview-row-header-inner"></div>';
        $output .= '</div>';

        /**
         * Header abbreviations
         *
         */
        $output .= '<div class="wpbs-overview-row-content">';

        for ($day = 1; $day <= $total_days; $day++) {

            $day_names = wpbs_get_days_first_letters($this->args['language']);
            $index = date('N', mktime(0, 0, 0, $month, $day, $year)) - 1;

            $output .= '<div><div class="wpbs-date"><div class="wpbs-date-inner"><strong>';

            if ($day <= $days_in_month) {
                $output .= $day_names[$index];
            }

            $output .= '</strong></div></div></div>';

        }

        $output .= '</div>'; // end of .wpbs-overview-row-content

        $output .= '</div>';

        return $output;

    }

    /**
     * Constructs and returns the calendar HTML row for the given month of the given year
     *
     * @param WPBS_Calendar $calendar
     * @param int              $year
     * @param int              $month
     *
     * @return string
     *
     */
    protected function get_display_calendar_row($calendar, $year, $month)
    {

        $total_days = 31;
        $days_in_month = date('t', mktime(0, 0, 0, $month, 1, $year));

        $output = '<div class="wpbs-calendar wpbs-overview-row">';

        /**
         * Calendar heading
         *
         */
        $output .= '<div class="wpbs-calendar-header wpbs-overview-row-header">';
        $output .= '<div class="wpbs-calendar-header-inner wpbs-overview-row-header-inner">';

        $calendar_link = $this->get_calendar_link($calendar->get('id'), $this->args['language']);

        // Calendar Name
        $calendar_name = apply_filters('wpbs_calendar_overview_output_calendar_name', $calendar->get_name($this->args['language']), $calendar->get('id'), $calendar_link);

        if (!empty($calendar_link)) {
            $output .= '<a href="' . $calendar_link . '" ' . apply_filters('wpbs_overview_calendar_link_attributes', '') . '>' . $calendar_name . '</a>';
        } else {
            $output .= $calendar_name;
        }

        $output .= '</div>';
        $output .= '</div>';

        /**
         * Calendar dates
         *
         */
        $output .= '<div class="wpbs-calendar-wrapper wpbs-overview-row-content">';

        for ($day = 1; $day <= $total_days; $day++) {

            if ($day <= $days_in_month) {
                $output .= '<div>' . $this->get_display_day($calendar, $year, $month, $day) . '</div>';
            } else {
                $output .= '<div>' . $this->get_blank_day() . '</div>';
            }

        }

        $output .= '</div>'; // end of .wpbs-calendar-wrapper

        $output .= '</div>'; // end of .wpbs-calendar

        return $output;

    }

    /**
     * Constructs and returns the HTML of the calendar month selector from the header
     *
     * @param int $year
     * @param int $month
     *
     * @return string
     *
     */
    protected function get_display_month_selector($year, $month)
    {

        $output = '<div class="wpbs-select-container">';

        /**
         * Hook to modify how many months are being displayed in the select dropdown
         * before the current given month of the year
         *
         * @param int $months_before
         * @param int $year
         * @param int $month
         *
         */
        $months_before = apply_filters('wpbs_calendar_overview_output_month_selector_months_before', 3, $year, $month);

        /**
         * Hook to modify how many months are being displayed in the select dropdown
         * after the current given month of the year
         *
         * @param int $months_after
         * @param int $year
         * @param int $month
         *
         */
        $months_after = apply_filters('wpbs_calendar_overview_output_month_selector_months_after', 12, $year, $month);

        /**
         * Hook to modify the maximum number of months to display before now()
         *
         * @param int $months_before_max
         * @param int $year
         * @param int $month
         *
         */
        $months_before_max = apply_filters('wpbs_calendar_overview_output_month_selector_months_before_max', -1, $year, $month);

        /**
         * Hook to modify the maximum number of months to display after now()
         *
         * @param int $months_after_max
         * @param int $year
         * @param int $month
         *
         */
        $months_after_max = apply_filters('wpbs_calendar_overview_output_month_selector_months_after_max', -1, $year, $month);

        /**
         * Hook to exclude past months from the date selector
         *
         * @param bool
         *
         */
        $show_past_months = apply_filters('wpbs_calendar_overview_output_month_selector_hide_past_months', true);

        /**
         * Build the months array
         *
         */

        // The array that will contain all options data
        $select_options = array();

        // Maximum before time
        $before_max_month = date('n') + (12 * ceil($months_before_max / 12)) - $months_before_max;
        $before_max_year = $year - ceil($months_before_max / 12);
        $time_before_max = mktime(0, 0, 0, $before_max_month, 1, $before_max_year);

        // Maximum after time
        $after_max_month = (date('n') + $months_after_max - (12 * floor((date('n') + $months_after_max) / 12)));
        $after_max_year = $year + floor((date('n') + $months_after_max) / 12);
        $time_after_max = mktime(0, 0, 0, $after_max_month, 1, $after_max_year);

        /**
         * Add past months
         *
         */
        $_year = $year;
        $_month = $month;

        for ($i = 1; $i <= $months_before; $i++) {

            // Exit loop if the max number of months has been reached
            if ($months_before_max != -1 && mktime(0, 0, 0, $_month, 1, $_year) <= $time_before_max) {
                break;
            }

            $_month -= 1;

            if ($_month < 1) {
                $_month += 12;
                $_year -= 1;
            }

            if ($show_past_months === false && mktime(0, 0, 0, $_month + 1, 1, $_year) <= current_time('timestamp')) {
                break;
            }

            $select_options[] = array(
                'value' => mktime(0, 0, 0, $_month, 15, $_year),
                'option' => wpbs_get_month_name($_month, $this->args['language']) . ' ' . $_year,
            );

        }

        $select_options = array_reverse($select_options);

        /**
         * Add given current month
         *
         */
        $select_options[] = array(
            'value' => mktime(0, 0, 0, $month, 15, $year),
            'option' => wpbs_get_month_name($month, $this->args['language']) . ' ' . $year,
        );

        /**
         * Add future months
         *
         */
        $_year = $year;
        $_month = $month;

        for ($i = 1; $i <= $months_after; $i++) {

            if ($months_after_max != -1 && mktime(0, 0, 0, $_month, 1, $_year) >= $time_after_max) {
                break;
            }

            $_month += 1;

            if ($_month > 12) {
                $_month -= 12;
                $_year += 1;
            }

            $select_options[] = array(
                'value' => mktime(0, 0, 0, $_month, 15, $_year),
                'option' => wpbs_get_month_name($_month, $this->args['language']) . ' ' . $_year,
            );

        }

        /**
         * Output select
         *
         */
        $output .= '<select>';

        foreach ($select_options as $select_option) {
            $output .= '<option value="' . esc_attr($select_option['value']) . '" ' . selected($select_option['value'], mktime(0, 0, 0, $month, 15, $year), false) . '>' . $select_option['option'] . '</option>';
        }

        $output .= '</select>';

        $output .= '</div>'; // end .wpbs-select-container

        return $output;

    }

    /**
     * Constructs and returns the calendar HTML for the given day of the given month of the given year
     *
     * @param WPBS_Calendar $calendar
     * @param int              $year
     * @param int              $month
     * @param int              $day
     * @param bool              $blank
     *
     * @return string
     *
     */
    protected function get_display_day($calendar, $year, $month, $day)
    {

        $output = '';

        /**
         * Get the event for the current day
         *
         */
        $event = $this->get_event_by_date($calendar, $year, $month, $day);

        /**
         * Get the event for the current day from the iCal feeds
         *
         */
        $ical_event = $this->get_ical_event_by_date($calendar, $year, $month, $day);

        if (!is_null($ical_event)) {
            $event = $ical_event;
        }

        /**
         * Get the legend item for the current day
         *
         */
        $legend_item = null;

        if (!is_null($event)) {

            foreach ($this->legend_items[$calendar->get('id')] as $li) {

                if ($event->get('legend_item_id') == $li->get('id')) {
                    $legend_item = $li;
                }

            }

        }

        if (is_null($legend_item)) {
            $legend_item = $this->default_legend_items[$calendar->get('id')];
        }

        // Determine if the current day is in the past
        $is_past = $this->is_date_past($year, $month, $day);

        $output .= '<div class="wpbs-date" ' . ('data-year="' . esc_attr($year) . '" data-month="' . esc_attr($month) . '" data-day="' . esc_attr($day) . '"') . '>';

        /**
         * Legend item output
         *
         */
        $legend_item_id_icon = $legend_item->get('id');
        $legend_item_type_icon = $legend_item->get('type');

        // If date is in the past
        if ($is_past) {

            if ($this->args['history'] == 2) {
                $legend_item_id_icon = $this->default_legend_items[$calendar->get('id')]->get('id');
                $legend_item_type_icon = $this->default_legend_items[$calendar->get('id')]->get('type');
            }

            if ($this->args['history'] == 3) {
                $legend_item_id_icon = 0;
                $legend_item_type_icon = 'single';
            }

        }

        $output .= wpbs_get_legend_item_icon($legend_item_id_icon, $legend_item_type_icon);

        $output .= apply_filters('wpbs_calendar_overview_output_display_day', '', $year, $month, $day, $event);

        /**
         * Tooltip output
         *
         */
        $output .= $this->get_display_day_tooltip($calendar, $year, $month, $day);

        $output .= '</div>';

        return $output;

    }

    /**
     * Constructs and returns the a blank day, which is appended to the end of the months that have less than 31 days.
     *
     * @return string
     *
     */
    protected function get_blank_day()
    {
        $output = '';

        $output .= '<div class="wpbs-date">';

        $legend_item_id_icon = 'blank';
        $legend_item_type_icon = 'blank';

        $output .= wpbs_get_legend_item_icon($legend_item_id_icon, $legend_item_type_icon);

        $output .= '</div>';

        return $output;
    }

    /**
     * Constructs and returns the Tooltip HTML for the given day of the given month of the given year
     *
     * @param int $year
     * @param int $month
     * @param int $day
     *
     * @return string
     *
     */
    protected function get_display_day_tooltip($calendar, $year, $month, $day)
    {

        // Get event for the current day
        $event = $this->get_event_by_date($calendar, $year, $month, $day);

        if (is_null($event)) {
            return '';
        }

        if (!in_array($this->args['show_tooltip'], array(2, 3))) {
            return '';
        }

        if ($this->is_date_past($year, $month, $day) && in_array($this->args['history'], array(2, 3))) {
            return '';
        }

        // Set tooltip value
        $tooltip = $event->get('tooltip');

        if (empty($tooltip)) {
            return '';
        }

        // Output the actual tooltip
        $output = '<div class="wpbs-tooltip">';
        $output .= '<strong>' . wpbs_date_i18n(get_option('date_format'), mktime( 0, 0, 0, $month, $day, $year ), $this->args['language'] ) . '</strong>';
        $output .= $tooltip;
        $output .= '</div>';

        // Output the red tooltip indicator
        if ($this->args['show_tooltip'] == 3) {
            $output .= '<span class="wpbs-tooltip-corner"><!-- --></span>';
        }

        return $output;

    }

    /**
     * Constructs and returns the HTML for the calendar's legend
     *
     * @return string
     *
     */
    protected function get_display_legend()
    {

        $output = '<div class="wpbs-legend">';

        /**
         * Filter out elements that are identical
         *
         */
        $legend_items_data = array();
        $legend_items = array();

        foreach ($this->calendars as $calendar) {

            foreach ($this->legend_items[$calendar->get('id')] as $legend_item) {

                $legend_items_data[$legend_item->get('id')]['name'] = $legend_item->get('name');
                $legend_items_data[$legend_item->get('id')]['color'] = array_map('strtolower', $legend_item->get('color'));

            }

        }

        foreach ($legend_items_data as $legend_item_id => $legend_item_data) {

            if (!in_array($legend_item_data, $legend_items)) {
                $legend_items[$legend_item_id] = $legend_item_data;
            }

        }

        foreach ($this->calendars as $calendar) {

            foreach ($this->legend_items[$calendar->get('id')] as $legend_item) {

                if (in_array($legend_item->get('id'), array_keys($legend_items))) {
                    $legend_items[$legend_item->get('id')] = $legend_item;
                }

            }

        }

        /**
         * Concatenate the output
         *
         */
        foreach ($legend_items as $legend_item) {

            if (!$legend_item->get('is_visible')) {
                continue;
            }

            $output .= '<div class="wpbs-legend-item">';
            $output .= wpbs_get_legend_item_icon($legend_item->get('id'), $legend_item->get('type'));
            $output .= '<span class=wpbs-legend-item-name>' . $legend_item->get_name($this->args['language']) . '</span>';
            $output .= '</div>';

        }

        $output .= '</div>';

        return $output;

    }

    /**
     * Constructs and returns the calendar's custom CSS
     *
     * @return string
     *
     */
    protected function get_custom_css()
    {

        $output = '<style type="text/css">';

        // Set the parent calendar class
        $calendar_parent_class = '.wpbs-overview-container';

        /**
         * Legend Items CSS
         *
         */
        foreach ($this->calendars as $calendar) {

            foreach ($this->legend_items[$calendar->get('id')] as $legend_item) {

                $colors = $legend_item->get('color');

                $output .= $calendar_parent_class . ' .wpbs-legend-item-icon-' . esc_attr($legend_item->get('id')) . ' div:first-of-type { background-color: ' . (!empty($colors[0]) ? esc_attr($colors[0]) : 'transparent') . '; }';
                $output .= $calendar_parent_class . ' .wpbs-legend-item-icon-' . esc_attr($legend_item->get('id')) . ' div:nth-of-type(2) { background-color: ' . (!empty($colors[1]) ? esc_attr($colors[1]) : 'transparent') . '; }';

                $output .= $calendar_parent_class . ' .wpbs-legend-item-icon-' . esc_attr($legend_item->get('id')) . ' div:first-of-type svg { fill: ' . (!empty($colors[0]) ? esc_attr($colors[0]) : 'transparent') . '; }';
                $output .= $calendar_parent_class . ' .wpbs-legend-item-icon-' . esc_attr($legend_item->get('id')) . ' div:nth-of-type(2) svg { fill: ' . (!empty($colors[1]) ? esc_attr($colors[1]) : 'transparent') . '; }';

            }

        }

        /**
         * Legend Item for Past Dates CSS
         *
         */
        $output .= $calendar_parent_class . ' .wpbs-legend-item-icon-0 div:first-of-type { background-color: ' . (!empty($this->plugin_settings['booking_history_color']) ? esc_attr($this->plugin_settings['booking_history_color']) : '#e1e1e1') . '; }';

        $output .= '</style>';

        return $output;

    }

    /**
     * Passes through all stored events and searches for the event that matches the given date
     * If an event is found it is returned, else null is returned
     *
     * @param WPBS_Calendar $calendar
     * @param int              $year
     * @param int              $month
     * @param int              $day
     *
     * @return mixed WPBS_Event|null
     *
     */
    protected function get_event_by_date($calendar, $year, $month, $day)
    {

        foreach ($this->events[$calendar->get('id')] as $event) {

            if ($event->get('date_year') == $year && $event->get('date_month') == $month && $event->get('date_day') == $day) {
                return $event;
            }

        }

        return null;

    }

    /**
     * Passes through all stored ical events and searches for the event that matches the given date
     * If an event is found it is returned, else null is returned
     *
     * @param WPBS_Calendar $calendar
     * @param int              $year
     * @param int              $month
     * @param int              $day
     *
     * @return mixed WPBS_Event|null
     *
     */
    protected function get_ical_event_by_date($calendar, $year, $month, $day)
    {

        foreach ($this->ical_events[$calendar->get('id')] as $event) {

            if ($event->get('date_year') == $year && $event->get('date_month') == $month && $event->get('date_day') == $day) {
                return $event;
            }

        }

        return null;

    }

    /**
     * Determines whether the given date is in the past or not
     *
     * @param int $year
     * @param int $month
     * @param int $day
     *
     * @return bool
     *
     */
    protected function is_date_past($year, $month, $day)
    {

        $today = mktime(0, 0, 0, current_time('n'), current_time('j'), current_time('Y'));
        $date = mktime(0, 0, 0, $month, $day, $year);

        return ($today > $date);

    }

    /**
     * Refreshes the iCal feeds attached to the calendar
     *
     * @param WPBS_Calendar $calendar
     *
     */
    protected function refresh_ical_feeds($calendar)
    {

        if (defined('DOING_AJAX') && DOING_AJAX) {
            return;
        }

        wpbs_maybe_refresh_icalendar_feed($calendar->get('id'));

    }

    /**
     * Helper function that prints the calendar overview
     *
     */
    public function display()
    {

        echo $this->get_display();

    }

    /**
     * Get the calendar link, internal or external
     *
     * @param int $calendar_id
     * @param string $language
     *
     * @return string
     *
     */
    public static function get_calendar_link($calendar_id, $language)
    {

        // Get calendar link type
        $calendar_link_type = wpbs_get_calendar_meta($calendar_id, 'calendar_link_type', true);

        // Internal Link
        if ($calendar_link_type == 'internal') {
            $calendar_link_internal = wpbs_get_translated_calendar_meta($calendar_id, 'calendar_link_internal', $language);
            
            if (!empty($calendar_link_internal)) {
                return apply_filters('wpbs_calendar_overview_output_calendar_link', get_permalink(absint($calendar_link_internal)), $calendar_link_internal, $calendar_id);
            }
        }

        // External Link
        if ($calendar_link_type == 'external') {
            if (wpbs_get_calendar_meta($calendar_id, 'calendar_link_external_translation_' . $language, true)) {
                $calendar_link_external = wpbs_get_calendar_meta($calendar_id, 'calendar_link_external_translation_' . $language, true);
            } else {
                $calendar_link_external = wpbs_get_calendar_meta($calendar_id, 'calendar_link_external', true);
            }
            return esc_url($calendar_link_external);
        }

        return '';

    }

    /**
     * Get the calendar linked post id
     *
     * @param int $calendar_id
     * @param string $language
     *
     * @return string
     *
     */
    public static function get_calendar_link_post_id($calendar_id, $language)
    {

        // Get calendar link type
        $calendar_link_type = wpbs_get_calendar_meta($calendar_id, 'calendar_link_type', true);

        // Internal Link
        if ($calendar_link_type == 'internal') {

            $calendar_link_internal = wpbs_get_translated_calendar_meta($calendar_id, 'calendar_link_internal', $language);
            return absint($calendar_link_internal);
        }

        return false;

    }

}
