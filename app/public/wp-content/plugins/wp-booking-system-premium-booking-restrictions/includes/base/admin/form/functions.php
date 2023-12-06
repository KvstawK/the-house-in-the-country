<?php

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Add Booking Restrictions tab to form editor page
 *
 * @param array $tabs
 *
 * @return array
 *
 */
function wpbs_submenu_page_edit_form_tabs_booking_restrictions($tabs)
{

    $tabs['booking_restrictions'] = __('Booking Restrictions', 'wp-booking-system-booking-restrictions');

    return $tabs;
}
add_filter('wpbs_submenu_page_edit_form_tabs', 'wpbs_submenu_page_edit_form_tabs_booking_restrictions', 5, 1);

/**
 * Add Email Reminder tab content to form editor page
 *
 */
function wpbs_submenu_page_edit_form_tab_booking_restrictions()
{
    include 'views/view-edit-form-tab-booking-restrictions.php';
}
add_action('wpbs_submenu_page_edit_form_tab_booking_restrictions', 'wpbs_submenu_page_edit_form_tab_booking_restrictions');

/**
 * Save meta fields when form is saved
 *
 * @param array $meta_fields
 *
 * @return array
 *
 */
function wpbs_br_edit_forms_meta_fields($meta_fields)
{

    $meta_fields['booking_restrictions_enable'] = array('translations' => true, 'sanitization' => 'sanitize_text_field', 'checkbox' => true);
    $meta_fields['booking_restrictions'] = array('translations' => true, 'sanitization' => '_wpbs_array_sanitize_text_field');

    $meta_fields['fixed_intervals_enable'] = array('translations' => true, 'sanitization' => 'sanitize_text_field', 'checkbox' => true);
    $meta_fields['fixed_intervals'] = array('translations' => true, 'sanitization' => '_wpbs_array_sanitize_text_field');

    return $meta_fields;
}
add_filter('wpbs_edit_forms_meta_fields', 'wpbs_br_edit_forms_meta_fields', 10, 1);

/**
 * Make strings translatable
 *
 * @param array $strings
 *
 * @return array
 *
 */
function wpbs_br_form_default_strings($strings)
{
    $strings['minimum_advance'] = __("The booking must start at least %s days from today.", 'wp-booking-system-booking-restrictions');
    $strings['maximum_advance'] = __("The booking must start no later than %s days from today.", 'wp-booking-system-booking-restrictions');
    $strings['fixed_days'] = __("Please select exactly %s days.", 'wp-booking-system-booking-restrictions');
    $strings['turnaround_time'] = __("The turnaround period must be %s days.", 'wp-booking-system-booking-restrictions');
    $strings['weekday_separator'] = __("or", 'wp-booking-system-booking-restrictions');
    $strings['fixed_interval'] = __("%s to %s is a fixed date range and cannot be booked in any other combination.", 'wp-booking-system-booking-restrictions');

    return $strings;
}
add_filter('wpbs_form_default_strings', 'wpbs_br_form_default_strings', 10, 1);

/**
 * Add strings to Form Strings page.
 *
 * @param array $strings
 *
 * @return array
 *
 */
function wpbs_br_form_default_strings_settings_page($strings)
{

    $strings['validation-strings']['strings']['fixed_days'] = array(
        'label' => __('Fixed Days', 'wp-booking-system-booking-restrictions'),
        'tooltip' => __('The "%s" will be replaced by the number of days set in the Booking Restriction tab.', 'wp-booking-system-booking-restrictions'),
    );

    $strings['validation-strings']['strings']['weekday_separator'] = array(
        'label' => __('Weekday Separator', 'wp-booking-system-booking-restrictions'),
        'tooltip' => __('Used for joining weekdays when there are multiple Starting or Ending days. Eg. "Monday *or* Sunday"', 'wp-booking-system-booking-restrictions'),
    );

    $strings['validation-strings']['strings']['minimum_advance'] = array(
        'label' => __('Minimum Advance Days', 'wp-booking-system-booking-restrictions'),
        'tooltip' => __('The "%s" will be replaced by the number of days set in the Booking Restriction tab.', 'wp-booking-system-booking-restrictions'),
    );

    $strings['validation-strings']['strings']['maximum_advance'] = array(
        'label' => __('Maximum Advance Days', 'wp-booking-system-booking-restrictions'),
        'tooltip' => __('The "%s" will be replaced by the number of days set in the Booking Restriction tab.', 'wp-booking-system-booking-restrictions'),
    );

    $strings['validation-strings']['strings']['turnaround_time'] = array(
        'label' => __('Turnaround Time', 'wp-booking-system-booking-restrictions'),
        'tooltip' => __('The "%s" will be replaced by the number of days set in the Booking Restriction tab.', 'wp-booking-system-booking-restrictions'),
    );

    $strings['validation-strings']['strings']['fixed_interval'] = array(
        'label' => __('Fixed Intervals', 'wp-booking-system-booking-restrictions'),
        'tooltip' => __('The "%s" will be replaced with the interval Start and End dates.', 'wp-booking-system-booking-restrictions'),
    );

    return $strings;
}
add_filter('wpbs_form_default_strings_settings_page', 'wpbs_br_form_default_strings_settings_page', 10, 1);

/**
 * Validate booking rules on the Settings Page
 *
 * @param array $rules
 *
 * @return array
 *
 */
function wpbs_br_validate_restriction_settings($rules)
{

    if (empty($rules)) {
        return false;
    }

    // Assume there are no problems
    $problems = array();

    $periods = array();

    foreach ($rules as $rule) {
        if ($rule['period'] == 'custom') {

            // Get period types
            $date_range_type = isset($rule['date_range_type']) && $rule['date_range_type'] == 'fixed_date' ? '_fixed' : '';
            $start_period = $rule['start_period' . $date_range_type];
            $end_period = $rule['end_period' . $date_range_type];

            // Save periods and check later if they overlap
            $periods[] = array(
                'start' => wpbs_convert_js_to_php_timestamp($start_period),
                'end' => wpbs_convert_js_to_php_timestamp($end_period),
                'priority' => (isset($rule['priority']) ? absint($rule['priority']) : 0),
            );

            // Check if Start Period is not empty
            if (empty($start_period)) {
                $problems['empty-periods-start'] = __('One or more custom periods are missing a start date.', 'wp-booking-system-booking-restrictions');
            }

            // Check if End Period is not empty
            if (empty($end_period)) {
                $problems['empty-periods-end'] = __('One or more custom periods are missing an end date.', 'wp-booking-system-booking-restrictions');
            }

            // Check if End Period is not empty
            if ($date_range_type == '_fixed' && $start_period > $end_period) {
                $problems['invalid-fixed-date'] = __('The start date for a Fixed Date rule is higher than the end date.', 'wp-booking-system-booking-restrictions');
            }

        }

        // Check if min_stay < max_stay
        if (!empty($rule['minimum_stay']) && !empty($rule['maximum_stay']) && $rule['minimum_stay'] > $rule['maximum_stay']) {
            $problems['invalid-minimum-maximum'] = __('The minimum stay cannot be greater than the maximum stay.', 'wp-booking-system-booking-restrictions');
        }

        // Check min_stay_per_day > min_stay
        if (!empty($rule['minimum_stay_per_day'])) {
            foreach ($rule['minimum_stay_per_day'] as $min) {
                if (!empty($min) && $min < $rule['minimum_stay']) {
                    $problems['invalid-minimum-per-day'] = __('The minimum stay per day cannot be lower than the general minimum stay.', 'wp-booking-system-booking-restrictions');
                }
            }
        }

        // Check if min advance reservation < max advance reservation
        if (!empty($rule['minimum_advance_reservation']) && !empty($rule['maximum_advance_reservation']) && $rule['minimum_advance_reservation'] > $rule['maximum_advance_reservation']) {
            $problems['invalid-advance'] = __('The minimum advance reservation stay cannot be greater than the maximum advance reservation stay.', 'wp-booking-system-booking-restrictions');
        }
    }

    // Check if custom periods overlap
    if (count($periods) > 1) {
        $overlap = false;
        foreach ($periods as $i => $original) {
            foreach ($periods as $j => $compare) {

                if($original['priority'] != $compare['priority']){
                    continue;
                }

                if ($overlap == true) {
                    continue;
                }

                if ($i == $j) {
                    continue;
                }

                if ($compare['end'] > $original['start'] && $original['end'] > $compare['start']) {
                    $problems['overlapping-periods'] = __('One or more custom periods overlap.', 'wp-booking-system-booking-restrictions');
                    $overlap = true;
                }
            }
        }
    }

    return $problems;

}

/**
 * Validate fixed intervals on the Settings Page
 *
 * @param array $fixed_intervals
 *
 * @return array
 *
 */
function wpbs_br_validate_fixed_intervals_settings($fixed_intervals)
{

    if (empty($fixed_intervals)) {
        return false;
    }

    // Assume there are no problems
    $problems = array();

    $periods = array();

    foreach ($fixed_intervals as $interval) {

        if (!isset($interval['start_period_fixed'])) {
            continue;
        }

        // Get period types
        $start_period = isset($interval['start_period_fixed']) ? $interval['start_period_fixed'] : false;
        $end_period = isset($interval['end_period_fixed']) ? $interval['end_period_fixed'] : false;

        // Save periods and check later if they overlap
        $periods[] = array(
            'start' => wpbs_convert_js_to_php_timestamp($start_period),
            'end' => wpbs_convert_js_to_php_timestamp($end_period),
        );

        // Check if Start Period is not empty
        if (empty($start_period)) {
            $problems['empty-intervals-start'] = __('One or more intervals are missing a start date.', 'wp-booking-system-booking-restrictions');
        }

        // Check if End Period is not empty
        if (empty($end_period)) {
            $problems['empty-intervals-end'] = __('One or more intervals are missing an end date.', 'wp-booking-system-booking-restrictions');
        }

        // Check if $start_period > $end_period
        if ($start_period > $end_period) {
            $problems['invalid-dates'] = __('One or more intervals contain a start date greater than the end date.', 'wp-booking-system-booking-restrictions');
        }
    }

    // Check if custom periods overlap
    if (count($periods) > 1) {
        $overlap = false;
        foreach ($periods as $i => $original) {
            foreach ($periods as $j => $compare) {

                if ($overlap == true) {
                    continue;
                }

                if ($i == $j) {
                    continue;
                }

                if ($compare['end'] > $original['start'] && $original['end'] > $compare['start']) {
                    $problems['overlapping-periods'] = __('One or more date intervals overlap.', 'wp-booking-system-booking-restrictions');
                    $overlap = true;
                }
            }
        }
    }

    return $problems;

}
