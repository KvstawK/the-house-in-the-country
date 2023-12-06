<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Add the contract submenu to the Payments Tab
 *
 */
function wpbs_contract_settings_page_tab($tabs)
{

    $tabs['contract'] = __('Contracts', 'wp-booking-system-contracts');
    return $tabs;
}
add_filter('wpbs_submenu_page_settings_payment_tabs', 'wpbs_contract_settings_page_tab', 60);

/**
 * Add the contract Settings to the contract Payments tab
 *
 */
function wpbs_contract_settings_page_tab_contract()
{
    $settings = get_option('wpbs_settings', array());

    include 'views/view-payment-settings-contract.php';
}
add_action('wpbs_submenu_page_payment_settings_tab_contract', 'wpbs_contract_settings_page_tab_contract');

/**
 * Default contract strings
 *
 * @return array
 */
function wpbs_contract_default_strings()
{
    $strings = array(
        'contract' => __('Contract', 'wp-booking-system-contracts'),
        
    );

    $strings = apply_filters('wpbs_contract_default_strings', $strings);

    return $strings;
}
