<?php

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Add Payment Reminder tab to form editor page
 *
 * @param array $tabs
 *
 * @return array
 *
 */
function wpbs_submenu_page_edit_form_tabs_payment_reminder($tabs)
{

    if (!wpbs_is_pricing_enabled()) {
        return $tabs;
    }

    $settings = get_option('wpbs_settings', array());

    if (!isset($settings['payment_part_payments_method']) || $settings['payment_part_payments_method'] != 'initial') {
        return $tabs;
    }

    $tabs['email-notifications']['final_payment_reminder'] = __('Final Payment Reminder Notification', 'wp-booking-system');
    $tabs['email-notifications']['final_payment_success'] = __('Final Payment Success Notification', 'wp-booking-system');

    return $tabs;
}
add_filter('wpbs_submenu_page_edit_form_sub_tabs', 'wpbs_submenu_page_edit_form_tabs_payment_reminder', 10, 1);

/**
 * Add Payment Reminder tab content to form editor page
 *
 */
function wpbs_submenu_page_edit_form_tab_final_payment_reminder()
{
    include 'views/view-edit-form-tab-final-payment-reminder.php';
}
add_action('wpbs_submenu_page_edit_form_tabs_email_notifications_final_payment_reminder', 'wpbs_submenu_page_edit_form_tab_final_payment_reminder');

/**
 * Add Final Payment Success tab content to form editor page
 *
 */
function wpbs_submenu_page_edit_form_tab_final_payment_success()
{
    include 'views/view-edit-form-tab-final-payment-success.php';
}
add_action('wpbs_submenu_page_edit_form_tabs_email_notifications_final_payment_success', 'wpbs_submenu_page_edit_form_tab_final_payment_success');

/**
 * Add the Payment Options tabto the Payment Settings page
 *
 */
function wpbs_submenu_page_edit_form_sub_tabs_payment_options($tabs)
{

    if (!wpbs_is_pricing_enabled()) {
        return $tabs;
    }

    $tabs['form-options']['payment_options'] = __('Payment Options', 'wp-booking-system');

    return $tabs;
}
add_filter('wpbs_submenu_page_edit_form_sub_tabs', 'wpbs_submenu_page_edit_form_sub_tabs_payment_options', 1, 1);

/**
 * Add the Payment Options tab content  to the Payment Settings page
 *
 */
function wpbs_submenu_page_edit_form_tabs_form_options_payment_options()
{
    include 'views/view-edit-form-tab-payment-options.php';

}
add_action('wpbs_submenu_page_edit_form_tabs_form_options_payment_options', 'wpbs_submenu_page_edit_form_tabs_form_options_payment_options', 100, 1);