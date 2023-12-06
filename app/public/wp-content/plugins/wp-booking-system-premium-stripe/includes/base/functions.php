<?php

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Includes the Base files
 *
 */
function wpbs_stripe_include_files_base()
{
    // Get legend dir path
    $dir_path = plugin_dir_path(__FILE__);

    // Include Payment Ajax Functions
    if (file_exists($dir_path . 'functions-actions-stripe.php')) {
        include $dir_path . 'functions-actions-stripe.php';
    }

    // Include Part Payments Functions
    if (file_exists($dir_path . 'functions-part-payments.php')) {
        include $dir_path . 'functions-part-payments.php';
    }

    // Include Refund Functions
    if (file_exists($dir_path . 'functions-refunds.php')) {
        include $dir_path . 'functions-refunds.php';
    }
}
add_action('wpbs_stripe_include_files', 'wpbs_stripe_include_files_base');

/**
 * Register Payment Method
 *
 * @param array
 *
 */
function wpbs_stripe_register_payment_method($payment_methods)
{
    $payment_methods['stripe'] = 'Stripe';
    return $payment_methods;
}
add_filter('wpbs_payment_methods', 'wpbs_stripe_register_payment_method');

/**
 * Default form values
 *
 */
function wpbs_stripe_settings_stripe_defaults()
{
    return array(
        'display_name' => __('Stripe', 'wp-booking-system-stripe'),
        'description' => __('Pay with your credit card using Stripe.', 'wp-booking-system-stripe'),
    );
}

/**
 * Check if payment method is enabled in settings page
 *
 */
function wpbs_stripe_form_outputter_payment_method_enabled_stripe($active)
{
    $settings = get_option('wpbs_settings', array());
    if (isset($settings['payment_stripe_enable']) && $settings['payment_stripe_enable'] == 'on') {
        return true;
    }
    return false;
}
add_filter('wpbs_form_outputter_payment_method_enabled_stripe', 'wpbs_stripe_form_outputter_payment_method_enabled_stripe');

/**
 * Get the payment method's name
 *
 */
function wpbs_stripe_form_outputter_payment_method_name_stripe($active, $language)
{
    $settings = get_option('wpbs_settings', array());
    if (!empty($settings['payment_stripe_name_translation_' . $language])) {
        return $settings['payment_stripe_name_translation_' . $language];
    }
    if (!empty($settings['payment_stripe_name'])) {
        return $settings['payment_stripe_name'];
    }
    return wpbs_stripe_settings_stripe_defaults()['display_name'];
}
add_filter('wpbs_form_outputter_payment_method_name_stripe', 'wpbs_stripe_form_outputter_payment_method_name_stripe', 10, 2);

/**
 * Get the payment method's name
 *
 */
function wpbs_stripe_form_outputter_payment_method_description_stripe($active, $language)
{
    $settings = get_option('wpbs_settings', array());
    if (!empty($settings['payment_stripe_description_translation_' . $language])) {
        return $settings['payment_stripe_description_translation_' . $language];
    }
    if (!empty($settings['payment_stripe_description'])) {
        return $settings['payment_stripe_description'];
    }
    return wpbs_stripe_settings_stripe_defaults()['description'];
}
add_filter('wpbs_form_outputter_payment_method_description_stripe', 'wpbs_stripe_form_outputter_payment_method_description_stripe', 10, 2);
