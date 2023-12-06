<?php

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Adds a new tab to the Settings page of the plugin
 *
 * @param array $tabs
 *
 * @return $tabs
 *
 */
function wpbs_submenu_page_settings_tabs_payment($tabs)
{
    if (!wpbs_is_pricing_enabled()) {
        return $tabs;
    }

    $tabs['payment'] = __('Payment', 'wp-booking-system');

    return $tabs;

}
add_filter('wpbs_submenu_page_settings_tabs', 'wpbs_submenu_page_settings_tabs_payment', 40, 1);

/**
 * Adds the HTML for the Payment Setting tab
 *
 */
function wpbs_submenu_page_settings_tab_payment()
{
    if (!wpbs_is_pricing_enabled()) {
        return false;
    }

    $payment_tabs = array(
        'general_settings' => __('General Settings', 'wp-booking-system'),
        'taxes' => __('Taxes', 'wp-booking-system'),
    );

    $payment_tabs = apply_filters('wpbs_submenu_page_settings_payment_tabs', $payment_tabs);

    include 'views/view-payment-settings.php';

}
add_action('wpbs_submenu_page_settings_tab_payment', 'wpbs_submenu_page_settings_tab_payment');

/**
 * Add the Payment Strings tab to the Strings & Translations Settings page
 *
 */
function wpbs_submenu_page_settings_payment_strings($tabs)
{
    if (!wpbs_is_pricing_enabled()) {
        return $tabs;
    }

    $tabs['payment'] = __('Payment Strings', 'wp-booking-system');
    return $tabs;
}
add_filter('wpbs_submenu_page_settings_strings_tabs', 'wpbs_submenu_page_settings_payment_strings', 10, 1);

/**
 * Adds the HTML for the Payment Strings tab
 *
 */
function wpbs_submenu_page_string_settings_tab_payment() {

	include 'views/view-settings-tab-strings-payment.php';

}
add_action( 'wpbs_submenu_page_string_settings_tab_payment', 'wpbs_submenu_page_string_settings_tab_payment' );