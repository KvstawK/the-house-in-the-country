<?php

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Add the Bank Transfer tab to the Payment Settings page
 *
 */
function wpbs_submenu_page_settings_payment_tabs_bank_transfer($payment_tabs)
{
    if (!wpbs_is_pricing_enabled()) {
        return $payment_tabs;
    }

    $payment_tabs['bank_transfer'] = __('Bank Transfer', 'wp-booking-system');
    return $payment_tabs;
}
add_filter('wpbs_submenu_page_settings_payment_tabs', 'wpbs_submenu_page_settings_payment_tabs_bank_transfer', 10);

/**
 * Default form values
 *
 */
function wpbs_settings_bank_transfer_defaults()
{
    return array(
        'display_name' => __('Bank Transfer', 'wp-booking-system'),
        'description' => __('Transfer money from your bank account.'),
    );
}

/**
 * Check if payment method is enabled in settings page
 *
 */
function wpbs_form_outputter_payment_method_enabled_bank_transfer()
{
    $settings = get_option('wpbs_settings', array());
    if (isset($settings['payment_bt_enable']) && $settings['payment_bt_enable'] == 'on') {
        return true;
    }
    return false;
}
add_filter('wpbs_form_outputter_payment_method_enabled_bank_transfer', 'wpbs_form_outputter_payment_method_enabled_bank_transfer');

/**
 * Get the payment method's name
 *
 */
function wpbs_form_outputter_payment_method_name_bank_transfer($active, $language)
{
    $settings = get_option('wpbs_settings', array());
    if (!empty($settings['payment_bt_name_translation_' . $language])) {
        return $settings['payment_bt_name_translation_' . $language];
    }
    if (!empty($settings['payment_bt_name'])) {
        return $settings['payment_bt_name'];
    }
    return wpbs_settings_bank_transfer_defaults()['display_name'];
}
add_filter('wpbs_form_outputter_payment_method_name_bank_transfer', 'wpbs_form_outputter_payment_method_name_bank_transfer', 10, 2);

/**
 * Get the payment method's name
 *
 */
function wpbs_form_outputter_payment_method_description_bank_transfer($active, $language)
{
    $settings = get_option('wpbs_settings', array());
    if (!empty($settings['payment_bt_description_translation_' . $language])) {
        return $settings['payment_bt_description_translation_' . $language];
    }
    if (!empty($settings['payment_bt_description'])) {
        return $settings['payment_bt_description'];
    }
    return wpbs_settings_bank_transfer_defaults()['description'];
}
add_filter('wpbs_form_outputter_payment_method_description_bank_transfer', 'wpbs_form_outputter_payment_method_description_bank_transfer', 10, 2);

/**
 * Save the order in the database
 *
 */
function wpbs_action_bank_transfer_save_payment_details($booking_id, $post_data, $form, $form_args, $form_fields)
{
    $payment_found = false;

    // Check if payment method is enabled.
    foreach ($form_fields as $form_field) {
        if ($form_field['type'] == 'payment_method' && $form_field['user_value'] == 'bank_transfer') {
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
        'gateway' => 'bank_transfer',
        'order_id' => '-',
        'order_status' => '-',
        'details' => $details,
        'date_created' => current_time('Y-m-d H:i:s'),
    ));

}
add_action('wpbs_submit_form_after', 'wpbs_action_bank_transfer_save_payment_details', 10, 5);

/**
 * Add payment details to the Booking Modal
 *
 * @param WPBS_Booking
 *
 */
function wpbs_booking_modal_tab_content_payment_bank_transfer($booking)
{
    $payment = wpbs_get_payment_by_booking_id($booking->get('id'));

    // Check if there is an order for this booking
    if (empty($payment)) {
        return false;
    }

    // Check if it's a Bank Transfer order
    if ($payment->get('gateway') != 'bank_transfer') {
        return false;
    }

    $payment_information = array(
        array('label' => __('Payment Gateway', 'wp-booking-system'), 'value' => wpbs_form_outputter_payment_method_name_bank_transfer(false, wpbs_get_locale())),
        array('label' => __('Date', 'wp-booking-system'), 'value' => date('j F Y, H:i:s', strtotime($payment->get('date_created')))),
        array('label' => __('ID', 'wp-booking-system'), 'value' => '#' . $payment->get('id')),
    );

    $order_information = $payment->get_line_items();

    $order_information = apply_filters('wpbs_booking_details_order_information', $order_information, $payment);

    include 'booking/views/view-modal-payment-details-content.php';

}
add_action('wpbs_booking_modal_tab_content_payment', 'wpbs_booking_modal_tab_content_payment_bank_transfer', 10, 1);

/**
 * Get the bank transfer instructions
 *
 * @param string $language
 * @param WPBS_Payment $payment
 *
 * @return string
 */
function wpbs_get_bank_transfer_instructions($language, $payment)
{

    $settings = get_option('wpbs_settings', array());

    $booking = wpbs_get_booking($payment->get('booking_id'));

    $form_id = $booking->get('form_id');

    // Get the price
    $amount_to_pay = 0;
    if ($payment->is_part_payment()) {
        if (!$payment->is_deposit_paid() && $payment->get_total_first_payment() > 0) {
            $amount_to_pay = $payment->get_total_first_payment();
        }

        if ($payment->is_deposit_paid() && !$payment->is_final_payment_paid()) {
            $amount_to_pay = $payment->get_total_second_payment();
        }
    } else {
        $amount_to_pay = $payment->get_total();
    }

    if($amount_to_pay == 0){
        return false;
    }

    // Get the message in the correct language
    if (!empty(wpbs_get_form_meta($form_id, 'bt_instructions_translation_' . $language, true))) {
        $confirmation_message = wpbs_get_form_meta($form_id, 'bt_instructions_translation_' . $language, true);
    } elseif (!empty($settings['payment_bt_instructions_translation_' . $language])) {
        $confirmation_message = $settings['payment_bt_instructions_translation_' . $language];
    } elseif (!empty(wpbs_get_form_meta($form_id, 'bt_instructions', true))) {
        $confirmation_message = wpbs_get_form_meta($form_id, 'bt_instructions', true);
    } else {
        $confirmation_message = $settings['payment_bt_instructions'];
    }

    $confirmation_message = apply_filters('wpbs_bank_transfer_instructions', $confirmation_message, $payment, $language);

    // Add the amount to the instructions.
    if (strpos($confirmation_message, '{Amount}') !== false) {

        $amount_to_pay = wpbs_get_formatted_price($amount_to_pay, $payment->get_currency());
        // Add to confirmation message
        $confirmation_message = str_replace('{Amount}', $amount_to_pay, $confirmation_message);
    }

    // Add the booking ID to the instructions.
    if (strpos($confirmation_message, '{Booking ID}') !== false) {
        $confirmation_message = str_replace('{Booking ID}', $payment->get('booking_id'), $confirmation_message);
    }

    return $confirmation_message;
}

/**
 * Add the bank transfer instructions to the confirmation message
 *
 * @param string $confirmation_message
 * @param int $booking_id
 * @param string $language
 *
 * @return string
 *
 */
function wpbs_submit_form_confirmation_message_bank_transfer($confirmation_message, $booking_id, $language)
{

    $payment = wpbs_get_payment_by_booking_id($booking_id);

    // Check if there is an order for this booking
    if (empty($payment)) {
        return $confirmation_message;
    }

    // Check if it's a Bank Transfer order
    if ($payment->get('gateway') != 'bank_transfer') {
        return $confirmation_message;
    }

    $instructions = wpbs_get_bank_transfer_instructions($language, $payment);

    if($instructions){
        $confirmation_message .= PHP_EOL . PHP_EOL;
        $confirmation_message .= $instructions;
    }

    return $confirmation_message;
}
add_filter('wpbs_submit_form_confirmation_message', 'wpbs_submit_form_confirmation_message_bank_transfer', 10, 3);

/**
 * Add {Bank Transfer Instructions} Email Tag
 *
 * @param string $output
 *
 * @return string
 *
 */
function wpbs_email_tags_bank_transfer_instructions($tags)
{

    $settings = get_option('wpbs_settings', array());

    if (!isset($settings['payment_bt_enable']) || empty($settings['payment_bt_enable'])) {
        return $tags;
    }

    $tags['payment']['bank-transfer-instructions'] = 'Bank Transfer Instructions';

    return $tags;

}
add_filter('wpbs_email_tags', 'wpbs_email_tags_bank_transfer_instructions', 30, 1);

/**
 * Replace {Bank Transfer Instructions} Email Tag with proper value
 *
 * @param string        $text
 * @param string        $tag
 * @param WPBS_Payment  $payment
 * @param string        $language
 *
 * @return string
 *
 */
function wpbs_form_mailer_custom_tag_bank_transfer_instructions($text, $tag, $payment, $language)
{

    if ($tag != '{Bank Transfer Instructions}') {
        return $text;
    }

    if (empty($payment) || $payment->get('gateway') != 'bank_transfer') {
        $bank_transfer_instructions = '';
    } else {
        $bank_transfer_instructions = wpbs_get_bank_transfer_instructions($language, $payment);
    }

    $text = str_replace($tag, $bank_transfer_instructions, $text);

    return $text;

}
add_filter('wpbs_form_mailer_custom_tag', 'wpbs_form_mailer_custom_tag_bank_transfer_instructions', 10, 4);

/**
 * Add {Bank Transfer Instructions} Email Tag to {All Fields}
 *
 * @param string        $text
 * @param WPBS_Payment  $payment
 * @param string        $language
 *
 * @return string
 *
 */
function wpbs_form_mailer_all_fields_after_bank_transfer_instructions($text, $payment, $language)
{
    if (empty($payment)) {
        return $text;
    }

    if ($payment->get('gateway') != 'bank_transfer') {
        return $text;
    }

    return wpbs_get_bank_transfer_instructions($language, $payment);
}
add_filter('wpbs_form_mailer_all_fields_after', 'wpbs_form_mailer_all_fields_after_bank_transfer_instructions', 10, 3);
add_filter('wpbs_booking_mailer_all_fields_after', 'wpbs_form_mailer_all_fields_after_bank_transfer_instructions', 10, 3);
