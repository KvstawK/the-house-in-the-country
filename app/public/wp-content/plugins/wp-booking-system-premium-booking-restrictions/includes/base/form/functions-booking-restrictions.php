<?php

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Function that hooks into the main WPBS_Form_Validator Class
 * 
 * @param array $output
 * @param WPBS_Form $form
 * @param array $form_args
 * @param WPBS_Calendar $calendar
 * @param string $language
 * 
 * @return array
 * 
 */
function wpbs_br_validate_form($output, $form, $form_args, $calendar, $calendar_args, $language)
{

    $validator = new WPBS_Form_Validator_Booking_Restrictions($form, $form_args, $calendar, $calendar_args, $language);

    if (wpbs_get_form_meta($form->get('id'), 'booking_restrictions_enable', true)) {

        // Reset shortcode restictions
        $validator->set_form_arg('minimum_days', 0);
        $validator->set_form_arg('maximum_days', 0);
        $validator->set_form_arg('booking_start_day', 0);
        $validator->set_form_arg('booking_end_day', 0);

        // Validate
        $validator->validate();
    }

    // Fixed Intervals validation
    if (wpbs_get_form_meta($form->get('id'), 'fixed_intervals_enable', true)) {
        $validator->validate_intervals();
    }


    // Return
    return $validator->output();
}
add_filter('wpbs_form_validator_custom_validation', 'wpbs_br_validate_form', 10, 6);

/**
 * Add a price modifier if a fixed date interval is selected
 *
 */
add_filter('wpbs_pricing_events_price', function ($events_price, $prices, $calendar_id, $form_args, $form, $form_fields, $start_date, $end_date) {

    // Check if fixed intervals are enabled
    if (wpbs_get_form_meta($form->get('id'), 'fixed_intervals_enable', true) != 'on') {
        return $events_price;
    }

    // Get fixed intervals
    $fixed_intervals = (array) wpbs_get_form_meta($form->get('id'), 'fixed_intervals', true);

    if (empty($fixed_intervals)) {
        return $events_price;
    }

    foreach ($fixed_intervals as $interval) {

        // Check if a price is set
        if (!isset($interval['override_price'])) {
            continue;
        }

        if (floatval($interval['override_price']) <= 0) {
            continue;
        }

        // Check if the dates match
        $interval_start_date = ctype_digit($interval['start_period_fixed']) ? wpbs_convert_js_to_php_timestamp($interval['start_period_fixed']) : strtotime($interval['start_period_fixed']);
        $interval_end_date = ctype_digit($interval['end_period_fixed']) ? wpbs_convert_js_to_php_timestamp($interval['end_period_fixed']) : strtotime($interval['end_period_fixed']);

        $interval_start_date = date('Ymd', $interval_start_date);
        $interval_end_date = date('Ymd', $interval_end_date);

        if (date('Ymd', $start_date) != $interval_start_date || date('Ymd', $end_date) != $interval_end_date) {
            continue;
        }

        $custom_price = floatval($interval['override_price']);

        // Check for currency conversions.
        if (isset($prices['custom_currency'])) {
            return $custom_price * $prices['conversion_rate'];
        }

        // Return the custom price
        return $custom_price;
    }

    return $events_price;
}, 5, 8);
