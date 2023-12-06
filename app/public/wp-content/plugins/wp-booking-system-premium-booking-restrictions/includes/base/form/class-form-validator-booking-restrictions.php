<?php

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

class WPBS_Form_Validator_Booking_Restrictions
{

    /**
     * The WPBS_Calendar
     *
     * @access protected
     * @var    WPBS_Calendar
     *
     */
    protected $calendar = null;

    /**
     * The WPBS_Form
     *
     * @access protected
     * @var    WPBS_Form
     *
     */
    protected $form = null;

    /**
     * The form arguments
     *
     * @access protected
     * @var    array
     *
     */
    protected $form_args = null;

    /**
     * The calendar arguments
     *
     * @access protected
     * @var    array
     *
     */
    protected $calendar_args = null;

    /**
     * Store errors
     *
     * @access protected
     * @var    bool
     *
     */
    protected $error = false;

    /**
     * Store error message
     *
     * @access protected
     * @var    string
     *
     */
    protected $error_message = null;

    /**
     * Form Language
     *
     * @access protected
     * @var    string
     *
     */
    protected $language = null;

    /**
     * Constructor
     *
     * @param WPBS_Form     $form
     * @param array         $form_args
     * @param WPBS_Calendar $calendar
     * @param array         $calendar_args
     * @param string        $language
     *
     */
    public function __construct($form, $form_args, $calendar, $calendar_args, $language)
    {

        /**
         * Set the form
         *
         */
        $this->form = $form;

        /**
         * Set the calendar
         *
         */
        $this->calendar = $calendar;

        /**
         * Set the form arguments
         *
         */
        $this->form_args = $form_args;

        /**
         * Set the calendar arguments
         *
         */
        $this->calendar_args = $calendar_args;

        /**
         * Set the form language
         *
         */
        $this->language = $language;

        /**
         * Set the booking restrictions
         *
         */
        $this->rules = wpbs_get_form_meta($this->form->get('id'), 'booking_restrictions', true);

        /**
         * Set the fixed date intervals
         *
         */
        $this->fixed_intervals = wpbs_get_form_meta($this->form->get('id'), 'fixed_intervals', true);

    }

    /**
     * Set a form argument
     *
     * @param string $key
     * @param string $value
     *
     */
    public function set_form_arg($key, $value)
    {
        $this->form_args[$key] = $value;
    }

    /**
     * Validate Rules
     *
     */
    public function validate()
    {

        if($this->check_fixed_intervals() === true){
            return true;
        }

        foreach (array('end_date', 'start_date') as $date) {

            // Get the applicable rule
            $rule = $this->get_applicable_rule($date);

            // Validate its conditions
            $this->validate_rule($rule, $date);
        }

    }

    /**
     * Validate Fixed Intervals
     *
     */
    public function validate_intervals()
    {
        $this->check_fixed_intervals();
    }

    /**
     * Search for any fixed date intervals and do not allow bookings including any other dates than the set inverval ones
     *
     */
    public function check_fixed_intervals()
    {

        if (wpbs_get_form_meta($this->form->get('id'), 'fixed_intervals_enable', true) != 'on') {
            return false;
        }

        $fixed_interval_match = false;

        if ($this->fixed_intervals) {
            foreach ($this->fixed_intervals as $interval) {

                $start_period = $interval['start_period_fixed'];
                $end_period = $interval['end_period_fixed'];

                $start_timestamp = ctype_digit($start_period) ? wpbs_convert_js_to_php_timestamp($start_period) : strtotime($start_period);
                $end_timestamp = ctype_digit($end_period) ? wpbs_convert_js_to_php_timestamp($end_period) : strtotime($end_period);

                if($this->calendar_args['start_date'] == $start_timestamp && $this->calendar_args['end_date'] == $end_timestamp){
                    $fixed_interval_match = true;
                }

                if (
                    !$this->check_date_range_exclusive($this->calendar_args['start_date'], $start_timestamp, $end_timestamp) &&
                    !$this->check_date_range_exclusive($this->calendar_args['end_date'], $start_timestamp, $end_timestamp) &&
                    !$this->check_date_range_exclusive($start_timestamp, $this->calendar_args['start_date'], $this->calendar_args['end_date']) &&
                    !$this->check_date_range_exclusive($end_timestamp, $this->calendar_args['start_date'], $this->calendar_args['end_date'])
                ) {
                    continue;
                }
                
                $this->error = true;
                $this->error_message = sprintf(wpbs_get_form_default_string($this->form->get('id'), 'fixed_interval', $this->language), wpbs_date_i18n(get_option('date_format'), $start_timestamp), wpbs_date_i18n(get_option('date_format'), $end_timestamp));

                return false;

            }
        }

        return $fixed_interval_match;

    }

    /**
     * Get the applicable rule for the given start date
     *
     * This function searches if a custom date range exists for the given date.
     * If a custom date range doesn't exist, it applies the general rules.
     *
     * @return array $rule
     *
     */
    protected function get_applicable_rule($date)
    {

        // Assume no custom date range exists for the selected date
        $date_range_exists = false;
        $applicable_rules = [];

        // Search for a custom date range
        foreach ($this->rules as $rule) {

            $priority = isset($rule['priority']) ? absint($rule['priority'])  : 0;

            // Skip if not a date range
            if ($rule['period'] == 'all') {
                continue;
            }

            // Get period types
            $date_range_type = isset($rule['date_range_type']) && $rule['date_range_type'] == 'fixed_date' ? 'fixed_date' : 'recurring';

            $date_range_type_suffix = $date_range_type == 'fixed_date' ? '_fixed' : '';

            $check_date_range_function = $date_range_type == 'fixed_date' ? 'check_date_range' : 'check_date_range_recurring';

            $start_period = $rule['start_period' . $date_range_type_suffix];
            $end_period = $rule['end_period' . $date_range_type_suffix];

            $start_timestamp = ctype_digit($start_period) ? wpbs_convert_js_to_php_timestamp($start_period) : strtotime($start_period);
            $end_timestamp = ctype_digit($end_period) ? wpbs_convert_js_to_php_timestamp($end_period) : strtotime($end_period);

            $selection_date = $this->calendar_args[$date];

            // Remove the last day from the selection if we are using split selection
            if($this->form_args['selection_style'] == 'split' && $date == 'end_date'){
                $selection_date -=  DAY_IN_SECONDS;
            }

            // Check date range
            if (!$this->$check_date_range_function($selection_date, $start_timestamp, $end_timestamp)) {
                continue;
            }

            // If found, set the $date_range_exists to true
            $date_range_exists = true;

            // And return the $rule
            $applicable_rules[$priority] = $rule;

        }

        // If no date range was found for the booking start date, get the general rule
        if ($date_range_exists === false) {

            foreach ($this->rules as $rule) {

                // Skip if it's a custom date range rule
                if ($rule['period'] == 'custom') {
                    continue;
                }

                // Return the $rule
                $applicable_rules[0] = $rule;

            }

        }

        // Check if multiple rules were found

        krsort($applicable_rules);

        return array_shift($applicable_rules);

    }

    /**
     * Check if a date is between a range, without taking the year into consideration
     *
     * @param timestamp $compare
     * @param timestamp $start
     * @param timestamp $end
     *
     * @return bool
     *
     */
    protected function check_date_range_recurring($compare, $start, $end)
    {
        $start = new DateTime(date('F j', $start));

        $end = new DateTime(date('F j', $end));

        $compare = new DateTime(date('F j', $compare));

        if ($start <= $end) {
            return $start <= $compare && $compare <= $end;
        } else {
            return $compare >= $start || $compare <= $end;
        }

    }

    /**
     * Check if a date is between a range, including the current date
     *
     * @param timestamp $compare
     * @param timestamp $start
     * @param timestamp $end
     *
     * @return bool
     *
     */
    protected function check_date_range($compare, $start, $end)
    {
        $start = new DateTime(date('j F Y', $start));

        $end = new DateTime(date('j F Y', $end));

        $compare = new DateTime(date('j F Y', $compare));

        if ($start <= $end) {
            return $start <= $compare && $compare <= $end;
        } else {
            return $compare >= $start || $compare <= $end;
        }

    }

    /**
     * Check if a date is between a range, without including the current date
     *
     * @param timestamp $compare
     * @param timestamp $start
     * @param timestamp $end
     *
     * @return bool
     *
     */
    protected function check_date_range_exclusive($compare, $start, $end)
    {
        $start = new DateTime(date('j F Y', $start));

        $end = new DateTime(date('j F Y', $end));

        $compare = new DateTime(date('j F Y', $compare));

        if ($start <= $end) {
            return $start < $compare && $compare < $end;
        } else {
            return $compare > $start || $compare < $end;
        }

    }

    /**
     * Validate the rules of a rule group
     *
     */
    protected function validate_rule($rule, $date)
    {

        // Get the number of selected days
        $number_of_selected_days = ($this->calendar_args['end_date'] - $this->calendar_args['start_date']) / DAY_IN_SECONDS;
        if ($this->form_args['selection_style'] != 'split') {
            $number_of_selected_days += 1;
        }

        $start_day_of_the_week = date('N', $this->calendar_args['start_date']);
        $end_day_of_the_week = date('N', $this->calendar_args['end_date']);

        $weekdays = function_exists('wpbs_get_translated_weekdays') ? wpbs_get_translated_weekdays() : wpbs_get_weekdays();

        // Validate Starting and Ending Days
        $force_start_day_rules = $force_end_day_rules = array();

        if (isset($rule['booking_day_start'])) {
            foreach ($rule['booking_day_start'] as $day) {
                if ($day == 'any') {
                    continue;
                }

                $force_start_day_rules[] = $day;
            }
        }

        if (isset($rule['booking_day_end'])) {
            foreach ($rule['booking_day_end'] as $day) {
                if ($day == 'any') {
                    continue;
                }

                $force_end_day_rules[] = $day;
            }
        }

        $force_start_day_rules = array_filter($force_start_day_rules);
        $force_end_day_rules = array_filter($force_end_day_rules);

        // Check starting day
        if (
            $date == 'start_date' &&
            !empty($force_start_day_rules) &&
            !in_array($start_day_of_the_week, $force_start_day_rules)
        ) {

            $valid_start_days = $force_start_day_rules;
            sort($valid_start_days);

            foreach ($valid_start_days as $i => $start_day) {
                $valid_start_days[$i] = $weekdays[$start_day - 1];
            }

            $this->error = true;
            $this->error_message = sprintf(wpbs_get_form_default_string($this->form->get('id'), 'start_day', $this->language), wpbs_natural_language_join($valid_start_days, wpbs_get_form_default_string($this->form->get('id'), 'weekday_separator', $this->language)));
            return false;

        }

        // Check ending day
        if (
            $date == 'end_date' &&
            !empty($force_end_day_rules) &&
            !in_array($end_day_of_the_week, $force_end_day_rules)
        ) {

            $valid_end_days = $force_end_day_rules;
            sort($valid_end_days);

            foreach ($valid_end_days as $i => $start_day) {
                $valid_end_days[$i] = $weekdays[$start_day - 1];
            }

            $this->error = true;
            $this->error_message = sprintf(wpbs_get_form_default_string($this->form->get('id'), 'end_day', $this->language), wpbs_natural_language_join($valid_end_days, wpbs_get_form_default_string($this->form->get('id'), 'weekday_separator', $this->language)));
            return false;

        }

        // Validate Minimum Stay
        if (!empty($rule['minimum_stay'])) {
            if ($number_of_selected_days < $rule['minimum_stay']) {
                $this->error = true;
                $this->error_message = sprintf(wpbs_get_form_default_string($this->form->get('id'), 'minimum_selection', $this->language), $rule['minimum_stay']);
                return false;
            }
        }

        // Validate Maximum Stay
        if (!empty($rule['maximum_stay'])) {
            if ($number_of_selected_days > $rule['maximum_stay']) {
                $this->error = true;
                $this->error_message = sprintf(wpbs_get_form_default_string($this->form->get('id'), 'maximum_selection', $this->language), $rule['maximum_stay']);
                return false;
            }
        }

        // Validate stay length by fixed days
        if (!empty($rule['fixed_days'])) {
            $fixed_days = (array) explode(',', $rule['fixed_days']);
            $fixed_days = array_filter(array_map('trim', $fixed_days));
            if (!in_array($number_of_selected_days, $fixed_days)) {
                $this->error = true;
                $this->error_message = sprintf(wpbs_get_form_default_string($this->form->get('id'), 'fixed_days', $this->language), wpbs_natural_language_join($fixed_days, wpbs_get_form_default_string($this->form->get('id'), 'weekday_separator', $this->language)));
                return false;
            }
        }

        // Validate Minimum Stay based on Week Day
        if (!empty($rule['minimum_stay_per_day'][$start_day_of_the_week])) {
            if ($number_of_selected_days < $rule['minimum_stay_per_day'][$start_day_of_the_week]) {
                $this->error = true;
                $this->error_message = sprintf(wpbs_get_form_default_string($this->form->get('id'), 'minimum_selection', $this->language), $rule['minimum_stay_per_day'][$start_day_of_the_week]);
                return false;
            }
        }

        // Validate Minimum Advance Booking
        if (!empty($rule['minimum_advance_reservation'])) {
            $minimum_advance_difference = ceil(($this->calendar_args['start_date'] - current_time('timestamp')) / DAY_IN_SECONDS);
            if ($minimum_advance_difference < $rule['minimum_advance_reservation']) {
                $this->error = true;
                $this->error_message = sprintf(wpbs_get_form_default_string($this->form->get('id'), 'minimum_advance', $this->language), $rule['minimum_advance_reservation']);
                return false;
            }
        }

        // Validate Maximum Advance Booking
        if (!empty($rule['maximum_advance_reservation'])) {
            $maximum_advance_difference = ceil(($this->calendar_args['start_date'] - current_time('timestamp')) / DAY_IN_SECONDS);
            if ($maximum_advance_difference > $rule['maximum_advance_reservation']) {
                $this->error = true;
                $this->error_message = sprintf(wpbs_get_form_default_string($this->form->get('id'), 'maximum_advance', $this->language), $rule['maximum_advance_reservation']);
                return false;
            }
        }

        // Validate Turn around time
        if (!empty($rule['turnaround_time'])) {

            $turnaround_dates = array();

            // Get nearby dates
            for ($i = 1; $i <= $rule['turnaround_time']; $i++) {
                $turnaround_dates[] = $this->calendar_args['start_date'] - DAY_IN_SECONDS * $i;
                $turnaround_dates[] = $this->calendar_args['end_date'] + DAY_IN_SECONDS * $i;
            }

            // Get non bookable legend IDs
            $legend_items = wpbs_get_legend_items(array('calendar_id' => $this->calendar->get('id'), 'is_bookable' => 0));

            $non_bookable_ids = array();

            foreach ($legend_items as $legend_item) {
                $non_bookable_ids[] = $legend_item->get('id');
            }

            // Check if nearby dates are non bookable.
            foreach ($turnaround_dates as $turnaround_date) {

                // Check for regualr events
                $events = wpbs_get_events(array('calendar_id' => $this->calendar->get('id'), 'date_day' => wpbs_date_i18n('j', $turnaround_date), 'date_month' => wpbs_date_i18n('n', $turnaround_date), 'date_year' => wpbs_date_i18n('Y', $turnaround_date)));

                // Check for iCalendar events as well
                $ical_events = wpbs_get_ical_feeds_as_events($this->calendar->get('id'), $events);

                foreach ($ical_events as $ical_event) {
                    if (
                        $ical_event->get('date_day') == wpbs_date_i18n('j', $turnaround_date) &&
                        $ical_event->get('date_month') == wpbs_date_i18n('n', $turnaround_date) &&
                        $ical_event->get('date_year') == wpbs_date_i18n('Y', $turnaround_date)) {
                        $events[] = $ical_event;
                    }
                }

                if ($events) {
                    foreach ($events as $event) {
                        if (in_array($event->get('legend_item_id'), $non_bookable_ids)) {
                            $this->error = true;
                            $this->error_message = sprintf(wpbs_get_form_default_string($this->form->get('id'), 'turnaround_time', $this->language), $rule['turnaround_time']);
                            return false;
                        }
                    }
                }
            }

        }

    }

    /**
     * Return validation result
     *
     * @return array
     *
     */
    public function output()
    {
        return array(
            'form_args' => $this->form_args,
            'calendar_args' => $this->calendar_args,
            'error_message' => $this->error_message,
            'error' => $this->error,
        );
    }

}
