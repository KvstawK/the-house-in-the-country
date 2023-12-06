<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Add the Invoice submenu to the Payments Tab
 *
 */
function wpbs_invoice_settings_page_tab($tabs)
{

    $tabs['invoice'] = __('Invoices', 'wp-booking-system-invoices');
    return $tabs;
}
add_filter('wpbs_submenu_page_settings_payment_tabs', 'wpbs_invoice_settings_page_tab', 50);

/**
 * Add the Invoice Settings to the Invoice Payments tab
 *
 */
function wpbs_invoice_settings_page_tab_invoice()
{
    $settings = get_option('wpbs_settings', array());

    include 'views/view-payment-settings-invoice.php';
}
add_action('wpbs_submenu_page_payment_settings_tab_invoice', 'wpbs_invoice_settings_page_tab_invoice');

/**
 * Default Invoice strings
 *
 * @return array
 */
function wpbs_invoice_default_strings()
{
    $strings = array(
        'invoice' => __('Invoice', 'wp-booking-system-invoices'),
        'seller' => __('Seller', 'wp-booking-system-invoices'),
        'buyer' => __('Buyer', 'wp-booking-system-invoices'),
        'details' => __('Details', 'wp-booking-system-invoices'),
        'booking_details' => __('Booking Details', 'wp-booking-system-invoices'),
        'calendar' => __('Calendar', 'wp-booking-system-invoices'),
        'invoice_number' => __('Invoice Number', 'wp-booking-system-invoices'),
        'invoice_date' => __('Invoice Date', 'wp-booking-system-invoices'),
        'due_date' => __('Due Date', 'wp-booking-system-invoices'),
        'description' => __('Description', 'wp-booking-system-invoices'),
        'quantity' => __('Qty', 'wp-booking-system-invoices'),
        'unit_price' => __('Unit Price', 'wp-booking-system-invoices'),
        'vat' => __('VAT', 'wp-booking-system-invoices'),
        'subtotal' => __('Subtotal', 'wp-booking-system-invoices'),
        'total' => __('Total', 'wp-booking-system-invoices'),
    );

    $strings = apply_filters('wpbs_invoice_default_strings', $strings);

    return $strings;
}


/**
 * Add the Invoice Strings tab to the Strings & Translations Settings page
 *
 */
function wpbs_submenu_page_settings_strings_invoice($tabs)
{
    if (!wpbs_is_pricing_enabled()) {
        return $tabs;
    }

    $tabs['invoice'] = __('Invoice Strings', 'wp-booking-system-invoices');
    return $tabs;
}
add_filter('wpbs_submenu_page_settings_strings_tabs', 'wpbs_submenu_page_settings_strings_invoice', 30, 1);

/**
 * Adds the HTML for the Invoice Strings tab
 *
 */
function wpbs_submenu_page_string_settings_tab_invoice() {

	include 'views/view-settings-tab-strings-invoice.php';

}
add_action( 'wpbs_submenu_page_string_settings_tab_invoice', 'wpbs_submenu_page_string_settings_tab_invoice' );