<?php

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Save Booking Options
 *
 */
function wpbs_action_ajax_add_booking_save_calendar_options()
{

    parse_str($_POST['options'], $options);

    if (!isset($options['calendar_id'])) {
        return false;
    }

    $calendar_id = absint($options['calendar_id']);

    wpbs_update_calendar_meta($calendar_id, 'manual_booking_options', $options);

    wpbs_output_add_booking_calendar($calendar_id);

    wp_die();
}
add_action('wp_ajax_wpbs_add_booking_save_calendar_options', 'wpbs_action_ajax_add_booking_save_calendar_options');

/**
 * Check if all the options necessary to make a manual booking are saved
 *
 */
function wpbs_is_add_booking_settings_configured($calendar_id)
{
    $booking_options = wpbs_get_calendar_meta($calendar_id, 'manual_booking_options', true);

    if (!$booking_options) {
        return false;
    }

    foreach ($booking_options as $booking_option) {
        if (!$booking_option) {
            return false;
        }
    }

    return true;
}

/**
 * Output the calendar with the necessary parameters
 *
 */
function wpbs_output_add_booking_calendar($calendar_id)
{
    if (!wpbs_is_add_booking_settings_configured($calendar_id)) {
        echo '<p>' . __('Please configure the options before creating a booking.', 'wp-booking-system') . '</p>';
    } else {
        $booking_options = wpbs_get_calendar_meta($calendar_id, 'manual_booking_options', true);
        $settings = get_option('wpbs_settings', array());
        echo apply_shortcodes('[wpbs id="' . $calendar_id . '" form_id="' . $booking_options['form_id'] . '" display="2" title="no" selection_style="' . $booking_options['selection_style'] . '" auto_pending="' . $booking_options['auto_pending'] . '" manual_booking="true" language="' . (isset($booking_options['language']) && !empty($booking_options['language']) ? $booking_options['language'] : wpbs_get_locale()) . '" start="' . (!empty($settings['backend_start_day']) ? (int) $settings['backend_start_day'] : 1) . '"]');
    }
}

/**
 * Remove the "manual_booking" argument if it's not used.
 *
 */
function wpbs_remove_unused_manual_booking_arg($args)
{
    foreach ($args as $arg => $val) {
        if ($arg == 'manual_booking' && empty($val)) {
            unset($args['manual_booking']);
        }
    }
    return $args;
}
add_filter('wpbs_form_outputter_args', 'wpbs_remove_unused_manual_booking_arg', 10, 1);
add_filter('wpbs_calendar_outputter_args', 'wpbs_remove_unused_manual_booking_arg', 10, 1);

/**
 * Add Bank Transfer and Payment on Arrival for manual bookings, or remove online payment methods if no part payments page is set.
 *
 */
add_filter('wpbs_form_outputter_payment_methods', function ($payment_methods, $args) {
    if (!isset($args['manual_booking']) || !$args['manual_booking']) {
        return $payment_methods;
    }

    $settings = get_option('wpbs_settings', array());

    if (isset($settings['payment_part_payments_page']) && !empty($settings['payment_part_payments_page'])) {
        return array_merge(array('payment_on_arrival', 'bank_transfer'), $payment_methods);
    }

    return array('payment_on_arrival', 'bank_transfer');
}, 10, 2);

/**
 * Make the booking a part payment only when using online payment gateways
 * 
 */
add_filter('wpbs_get_checkout_price_after_total', function ($prices, $post_data, $calendar_id, $form_args) {
    if (!isset($form_args['manual_booking']) || !$form_args['manual_booking']) {
        return $prices;
    }

    if (in_array($prices['payment_method'], array('payment_on_arrival'))) {
        $prices['is_part_payment'] = false;
        return $prices;
    }

    if ($prices['payment_method'] == 'bank_transfer') {
        return $prices;
    }

    $prices['is_part_payment'] = true;

    return $prices;
}, 1, 4);

/**
 * Always set the final payment method to "initial"
 * 
 */
add_filter('wpbs_get_checkout_price_after_total', function ($prices, $post_data, $calendar_id, $form_args) {
    if (!isset($form_args['manual_booking']) || !$form_args['manual_booking']) {
        return $prices;
    }

    if (in_array($prices['payment_method'], array('payment_on_arrival', 'bank_transfer'))) {
        return $prices;
    }

    $prices['part_payments']['method'] = 'initial';

    return $prices;
}, 99, 4);


/**
 * Set the first payment to 0
 * 
 */
add_filter('wpbs_part_payments_deposit', function ($first_payment, $prices, $post_data, $calendar_id, $form_args) {
    if (!isset($form_args['manual_booking']) || !$form_args['manual_booking']) {
        return $first_payment;
    }

    if ($prices['payment_method'] == 'bank_transfer') {
        return $first_payment;
    }

    return 0;
    
}, 10, 5);

/**
 * Check whether to send emails or not
 *
 */
add_filter('wpbs_form_handler_send_email_notifications', function ($send, $booking_id) {

    $booking = wpbs_get_booking($booking_id);

    if (is_null($booking)) {
        return $send;
    }

    if (!wpbs_get_booking_meta($booking_id, 'manual_booking', true)) {
        return $send;
    }

    $calendar_id = $booking->get('calendar_id');

    $manual_booking_options = wpbs_get_calendar_meta($calendar_id, 'manual_booking_options', true);

    if (!isset($manual_booking_options['send_emails'])) {
        return false;
    }

    return $send;
}, 10, 2);

/**
 * Check wither or not to ignore validation rules
 *
 */
add_filter('wpbs_form_validator_custom_validation', function ($validation, $form, $form_args, $calendar, $calendar_args) {

    if (!isset($form_args['manual_booking']) || !$form_args['manual_booking']) {
        return $validation;
    }

    $manual_booking_options = wpbs_get_calendar_meta($calendar->get('id'), 'manual_booking_options', true);

    if (isset($manual_booking_options['ignore_validation'])) {
        return array(
            'form_args' => $form_args,
            'calendar_args' => $calendar_args,
            'error' => false,
        );
    }

    return $validation;
}, 99, 5);

/**
 * Set the status of manually created bookings to "accepted"
 *
 */
add_action('wpbs_submit_form_after', function ($booking_id) {

    if (!wpbs_get_booking_meta($booking_id, 'manual_booking', true)) {
        return false;
    }

    wpbs_update_booking($booking_id, array(
        'status' => apply_filters('wpbs_manual_booking_default_status', 'accepted'),
        'is_read' => 1,
    ));
}, 10, 1);

/**
 * Set custom confirmation message
 *
 */
add_filter('wpbs_submit_form_confirmation_message', function ($message, $booking_id) {
    if (!wpbs_get_booking_meta($booking_id, 'manual_booking', true)) {
        return $message;
    }

    $booking = wpbs_get_booking($booking_id);

    $button_url = add_query_arg(
        array(
            'page' => 'wpbs-calendars',
            'subpage' => 'edit-calendar',
            'calendar_id' => $booking->get('calendar_id'),
            'booking_id' => $booking_id,
        ),
        admin_url('admin.php')
    );

    $message = '<p>' . __('The booking was successfully created.', 'wp-booking-system') . '</p>';
    $message .= '<p><a href="' . $button_url . '" class="button-primary">' . __('View Booking', 'wp-booking-system') . '</a></p>';

    return $message;
}, 99, 2);
