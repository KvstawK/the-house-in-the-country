<?php

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Add the Stripe submenu to the Payments Tab
 *
 */
function wpbs_stripe_settings_page_tab($tabs)
{

    $tabs['stripe'] = 'Stripe';
    return $tabs;
}
add_filter('wpbs_submenu_page_settings_payment_tabs', 'wpbs_stripe_settings_page_tab', 1);

/**
 * Add the Stripe Settings to the Stripe Payments tab
 *
 */
function wpbs_stripe_settings_page_tab_stripe()
{
    $settings = get_option('wpbs_settings', array());
    $defaults = wpbs_stripe_settings_stripe_defaults();
    $stripe_api = get_option('wpbs_stripe_api', array());

    include 'views/view-payment-settings-stripe.php';
}
add_action('wpbs_submenu_page_payment_settings_tab_stripe', 'wpbs_stripe_settings_page_tab_stripe');

/**
 * Make strings translatable - add default strings
 *
 */
function wpbs_stripe_payment_default_strings($strings)
{
    $strings['cardholder_name'] = __('Cardholder Name', 'wp-booking-system-stripe');
    $strings['card_details']    = __('Card Details', 'wp-booking-system-stripe');
    $strings['payment_required_field']  = __('This field is required.', 'wp-booking-system-stripe');
    $strings['payment_submit']  = __('Submit', 'wp-booking-system-stripe');
    $strings['payment_apple_pay'] = __('or enter your card details', 'wp-booking-system-stripe');

    return $strings;
}
add_filter('wpbs_payment_default_strings', 'wpbs_stripe_payment_default_strings');

/**
 * Make strings translatable - add form fields strings
 *
 */
function wpbs_stripe_payment_default_strings_labels($strings)
{
    $strings['cardholder_name'] = array(
        'label' => __('Cardholder Name Label', 'wp-booking-system-stripe'),
        'tooltip' => __("The label for the Cardholder's Name in the payment form.", 'wp-booking-system-stripe'),
    );

    $strings['card_details'] = array(
        'label' => __('Card Details Label', 'wp-booking-system-stripe'),
        'tooltip' => __("The label for the Card Details in the payment form.", 'wp-booking-system-stripe'),
    );

    $strings['payment_required_field'] = array(
        'label' => __('Payment Required Field', 'wp-booking-system-stripe'),
        'tooltip' => __("The error message when a payment form field is empty.", 'wp-booking-system-stripe'),
    );

    $strings['payment_submit'] = array(
        'label' => __('Payment Submit Button Label', 'wp-booking-system-stripe'),
        'tooltip' => __("The button label when submitting a payment form.", 'wp-booking-system-stripe'),
    );

    $strings['payment_apple_pay'] = array(
        'label' => __('Apple/Goole Pay alternative', 'wp-booking-system-stripe'),
        'tooltip' => __("Will appear under the Apple/Google Pay button.", 'wp-booking-system-stripe'),
    );

    return $strings;
}
add_filter('wpbs_payment_default_strings_labels', 'wpbs_stripe_payment_default_strings_labels');

/**
 * Save Stripe API Keys in a separate option field.
 *
 */
function wpbs_stripe_save_api_keys($option_name, $old_value, $value)
{
    // If wpbs_settings are being saved
    if ($option_name != 'wpbs_settings') {
        return false;
    }

    // If isset stripe api post data
    if (!isset($_POST['wpbs_stripe_api'])) {
        return false;
    }

    // Do the update
    update_option('wpbs_stripe_api', $_POST['wpbs_stripe_api']);

};
add_action('updated_option', 'wpbs_stripe_save_api_keys', 10, 3);
