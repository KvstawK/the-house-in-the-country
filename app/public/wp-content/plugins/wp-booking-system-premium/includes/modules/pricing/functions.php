<?php

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Check if pricing is enabled
 *
 * @return bool
 *
 */
function wpbs_is_pricing_enabled()
{
    // If payment plugin is enabled
    if (defined('WPBS_ENABLE_PRICING') && WPBS_ENABLE_PRICING === true) {
        return true;
    }

    // If enabled from Settings
    $settings = get_option('wpbs_settings', array());
    if (isset($settings['enable_pricing']) && $settings['enable_pricing'] == 'on') {
        return true;
    }

    return false;
}

/**
 * Includes the Base files
 *
 */
function wpbs_include_files_payment()
{

    // Get legend dir path
    $dir_path = plugin_dir_path(__FILE__);

    // Include payment object class
    if (file_exists($dir_path . 'class-payment.php')) {
        include $dir_path . 'class-payment.php';
    }

    // Include payment VAT object class
    if (file_exists($dir_path . 'class-vat.php')) {
        include $dir_path . 'class-vat.php';
    }

    // Include payment object db class
    if (file_exists($dir_path . 'class-object-db-payments.php')) {
        include $dir_path . 'class-object-db-payments.php';
    }

    // Include pricing functions
    if (file_exists($dir_path . 'functions-pricing.php')) {
        include $dir_path . 'functions-pricing.php';
    }

    // Include payment on arrival functions
    if (file_exists($dir_path . 'functions-payment-on-arrival.php')) {
        include $dir_path . 'functions-payment-on-arrival.php';
    }

    // Include bank transfer functions
    if (file_exists($dir_path . 'functions-bank-transfer.php')) {
        include $dir_path . 'functions-bank-transfer.php';
    }

    // Include part payment functions
    if (file_exists($dir_path . 'functions-part-payments.php')) {
        include $dir_path . 'functions-part-payments.php';
    }
    
    // Include refund functions
    if (file_exists($dir_path . 'functions-refunds.php')) {
        include $dir_path . 'functions-refunds.php';
    }

    // Include security deposit functions
    if (file_exists($dir_path . 'functions-security-deposit.php')) {
        include $dir_path . 'functions-security-deposit.php';
    }

}
add_action('wpbs_include_files', 'wpbs_include_files_payment');

/**
 * Register the class that handles database queries for the payments
 *
 * @param array $classes
 *
 * @return array
 *
 */
function wpbs_register_database_classes_payments($classes)
{

    $classes['payments'] = 'WPBS_Object_DB_Payments';

    return $classes;

}
add_filter('wpbs_register_database_classes', 'wpbs_register_database_classes_payments');

/**
 * Returns an array with WPBS_Payment objects from the database
 *
 * @param array $args
 * @param bool  $count
 *
 * @return array
 *
 */
function wpbs_get_payments($args = array(), $count = false)
{

    $payments = wp_booking_system()->db['payments']->get_payments($args, $count);

    /**
     * Add a filter hook just before returning
     *
     * @param array $payments
     * @param array $args
     * @param bool  $count
     *
     */
    return apply_filters('wpbs_get_payments', $payments, $args, $count);

}

/**
 * Gets a payment from the database
 *
 * @param mixed int|object      - payment id or object representing the payment
 *
 * @return WPBS_Payment|false
 *
 */
function wpbs_get_payment($payment)
{

    return wp_booking_system()->db['payments']->get_object($payment);

}

/**
 * Inserts a new payment into the database
 *
 * @param array $data
 *
 * @return mixed int|false
 *
 */
function wpbs_insert_payment($data)
{

    return wp_booking_system()->db['payments']->insert($data);

}

/**
 * Updates a payment from the database
 *
 * @param int     $payment_id
 * @param array $data
 *
 * @return bool
 *
 */
function wpbs_update_payment($payment_id, $data)
{

    return wp_booking_system()->db['payments']->update($payment_id, $data);

}

/**
 * Deletes a payment from the database
 *
 * @param int $payment_id
 *
 * @return bool
 *
 */
function wpbs_delete_payment($payment_id)
{

    return wp_booking_system()->db['payments']->delete($payment_id);

}

/**
 * Gets a payment from the database based on a booking id
 *
 * @param int - the booking id
 *
 * @return WPBS_Payment|false
 *
 */
function wpbs_get_payment_by_booking_id($booking_id)
{
    $payments = wpbs_get_payments(array('booking_id' => $booking_id));

    // Check if there is an order for this booking
    if (empty($payments)) {
        return false;
    }

    $payment = array_shift($payments);

    // Check if there is an order for this booking
    if (is_null($payment)) {
        return false;
    }

    return $payment;
}
