<?php

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Add the Payment on Arrival tab to the Payment Settings page
 *
 */
function wpbs_submenu_page_settings_payment_tabs_payment_on_arrival($payment_tabs)
{
    if (!wpbs_is_pricing_enabled()) {
        return $payment_tabs;
    }

    $payment_tabs['payment_on_arrival'] = __('Payment on Arrival', 'wp-booking-system');
    return $payment_tabs;
}
add_filter('wpbs_submenu_page_settings_payment_tabs', 'wpbs_submenu_page_settings_payment_tabs_payment_on_arrival', 10);

/**
 * Default form values
 *
 */
function wpbs_settings_payment_on_arrival_defaults()
{
    return array(
        'display_name' => __('Payment on Arrival', 'wp-booking-system'),
        'description' => __('Pay with cash when you arrive.'),
    );
}

/**
 * Check if payment method is enabled in settings page
 *
 */
function wpbs_form_outputter_payment_method_enabled_payment_on_arrival()
{
    $settings = get_option('wpbs_settings', array());
    if (isset($settings['payment_poa_enable']) && $settings['payment_poa_enable'] == 'on') {
        return true;
    }
    return false;
}
add_filter('wpbs_form_outputter_payment_method_enabled_payment_on_arrival', 'wpbs_form_outputter_payment_method_enabled_payment_on_arrival');

/**
 * Get the payment method's name
 *
 */
function wpbs_form_outputter_payment_method_name_payment_on_arrival($active, $language)
{
    $settings = get_option('wpbs_settings', array());
    if (!empty($settings['payment_poa_name_translation_' . $language])) {
        return $settings['payment_poa_name_translation_' . $language];
    }
    if (!empty($settings['payment_poa_name'])) {
        return $settings['payment_poa_name'];
    }
    return wpbs_settings_payment_on_arrival_defaults()['display_name'];
}
add_filter('wpbs_form_outputter_payment_method_name_payment_on_arrival', 'wpbs_form_outputter_payment_method_name_payment_on_arrival', 10, 2);

/**
 * Get the payment method's name
 *
 */
function wpbs_form_outputter_payment_method_description_payment_on_arrival($active, $language)
{
    $settings = get_option('wpbs_settings', array());
    if (!empty($settings['payment_poa_description_translation_' . $language])) {
        return $settings['payment_poa_description_translation_' . $language];
    }
    if (!empty($settings['payment_poa_description'])) {
        return $settings['payment_poa_description'];
    }
    return wpbs_settings_payment_on_arrival_defaults()['description'];
}
add_filter('wpbs_form_outputter_payment_method_description_payment_on_arrival', 'wpbs_form_outputter_payment_method_description_payment_on_arrival', 10, 2);

/**
 * Save the order in the database
 *
 */
function wpbs_action_payment_on_arrival_save_payment_details($booking_id, $post_data, $form, $form_args, $form_fields)
{
    $payment_found = false;

    // Check if payment method is enabled.
    foreach ($form_fields as $form_field) {
        if ($form_field['type'] == 'payment_method' && $form_field['user_value'] == 'payment_on_arrival') {
            $payment_found = true;
            break;
        }
    }

    if ($payment_found === false) {
        return false;
    }

    // Get price
    $payment = new WPBS_Payment;
    $details['price'] = $payment->calculate_prices($post_data, $form, $form_args, $form_fields);

    // Save Order
    wpbs_insert_payment(array(
        'booking_id' => $booking_id,
        'gateway' => 'payment_on_arrival',
        'order_id' => '-',
        'order_status' => '-',
        'details' => $details,
        'date_created' => current_time('Y-m-d H:i:s'),
    ));

}
add_action('wpbs_submit_form_after', 'wpbs_action_payment_on_arrival_save_payment_details', 10, 5);


/**
 * Add payment details to the Booking Modal
 *
 * @param WPBS_Booking
 *
 */
function wpbs_booking_modal_tab_content_payment_payment_on_arrival($booking)
{
    $payment = wpbs_get_payment_by_booking_id($booking->get('id'));

    // Check if there is an order for this booking
    if (empty($payment)) {
        return false;
    }

    // Check if it's a Payment on Arrival order
    if ($payment->get('gateway') != 'payment_on_arrival') {
        return false;
    }

    $payment_information = array(
        array('label' => __('Payment Gateway', 'wp-booking-system'), 'value' => wpbs_form_outputter_payment_method_name_payment_on_arrival(false, wpbs_get_locale())),
        array('label' => __('Date', 'wp-booking-system'), 'value' => date('j F Y, H:i:s', strtotime($payment->get('date_created')))),
        array('label' => __('ID', 'wp-booking-system'), 'value' => '#' . $payment->get('id')),
    );

    $order_information = $payment->get_line_items();

    $order_information = apply_filters('wpbs_booking_details_order_information', $order_information, $payment);

    include 'booking/views/view-modal-payment-details-content.php';

}
add_action('wpbs_booking_modal_tab_content_payment', 'wpbs_booking_modal_tab_content_payment_payment_on_arrival', 1, 10);
