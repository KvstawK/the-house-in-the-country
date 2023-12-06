<?php

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Includes the files needed for the Add-ons admin area
 *
 */
function wpbs_include_files_admin_addons()
{

    // Get legend admin dir path
    $dir_path = plugin_dir_path(__FILE__);

    // Include actions
    if (file_exists($dir_path . 'class-submenu-page-addons.php')) {
        include $dir_path . 'class-submenu-page-addons.php';
    }

    // Include actions
    if (file_exists($dir_path . 'functions-actions-addons.php')) {
        include $dir_path . 'functions-actions-addons.php';
    }

}
add_action('wpbs_include_files', 'wpbs_include_files_admin_addons');


/**
 * Register the Add-ons admin submenu page
 *
 */
function wpbs_register_submenu_page_addons($submenu_pages)
{

    if (!is_array($submenu_pages)) {
        return $submenu_pages;
    }

    $submenu_pages['addons'] = array(
        'class_name' => 'WPBS_Submenu_Page_Addons',
        'data' => array(
            'page_title' => __('Add-ons', 'wp-booking-system'),
            'menu_title' => __('Add-ons', 'wp-booking-system'),
            'capability' => apply_filters('wpbs_submenu_page_capability_addons', 'manage_options'),
            'menu_slug' => 'wpbs-addons',
        ),
    );

    return $submenu_pages;

}
add_filter('wpbs_register_submenu_page', 'wpbs_register_submenu_page_addons', 75);


/**
 * Available Addons
 * 
 * @return array
 *
 */
function wpbs_get_addons_list()
{
    return array(
        array(
            'name' => 'Stripe',
            'slug' => 'wp-booking-system-premium-stripe',
            'description' => __('Accept credit cards using the Stripe payment gateway.','wp-booking-system'),
            'image' => WPBS_PLUGIN_DIR_URL . 'assets/img/addons/wp-booking-system-premium-stripe.png',
            'plans' => array(
                'wpbs-business-license' => 'Business',
                'wpbs-developer-license' => 'Developer',
            ),
            'minimum_required_version' => '1.0.7'
        ),
        array(
            'name' => 'PayPal',
            'slug' => 'wp-booking-system-premium-paypal',
            'description' => __('Start receiving payments with PayPal Smart Payment Buttons. Your visitors can pay with their PayPal account or with their Credit Cards.','wp-booking-system'),
            'image' => WPBS_PLUGIN_DIR_URL . 'assets/img/addons/wp-booking-system-premium-paypal.png',
            'plans' => array(
                'wpbs-business-license' => 'Business',
                'wpbs-developer-license' => 'Developer',
            ),
            'minimum_required_version' => '1.0.5'
        ),

        array(
            'name' => 'Square',
            'slug' => 'wp-booking-system-premium-square',
            'description' => __('Accept credit card payments using the Square payment gateway.','wp-booking-system'),
            'image' => WPBS_PLUGIN_DIR_URL . 'assets/img/addons/wp-booking-system-premium-square.png',
            'plans' => array(
                'wpbs-business-license' => 'Business',
                'wpbs-developer-license' => 'Developer',
            ),
            'minimum_required_version' => '1.0.0'
        ),
        array(
            'name' => 'WooCommerce Checkout',
            'slug' => 'wp-booking-system-premium-woocommerce',
            'description' => __('Allow your customers to checkout with WooCommerce, using any of the payment gateways supported by WooCommerce.','wp-booking-system'),
            'image' => WPBS_PLUGIN_DIR_URL . 'assets/img/addons/wp-booking-system-premium-woocommerce.png',
            'plans' => array(
                'wpbs-business-license' => 'Business',
                'wpbs-developer-license' => 'Developer',
            ),
            'minimum_required_version' => '1.0.4'
        ),
        array(
            'name' => 'Authorize.Net',
            'slug' => 'wp-booking-system-premium-authorize-net',
            'description' => __('Accept credit cards using the Authorize.Net payment gateway.','wp-booking-system'),
            'image' => WPBS_PLUGIN_DIR_URL . 'assets/img/addons/wp-booking-system-premium-authorize-net.png',
            'plans' => array(
                'wpbs-business-license' => 'Business',
                'wpbs-developer-license' => 'Developer',
            ),
            'minimum_required_version' => '1.0.1'
        ),
        array(
            'name' => 'Mollie',
            'slug' => 'wp-booking-system-premium-mollie',
            'description' => __('Accept credit cards, bank transfers, iDEAL and many other payment methods with Mollie.','wp-booking-system'),
            'image' => WPBS_PLUGIN_DIR_URL . 'assets/img/addons/wp-booking-system-premium-mollie.png',
            'plans' => array(
                'wpbs-business-license' => 'Business',
                'wpbs-developer-license' => 'Developer',
            ),
            'minimum_required_version' => '1.0.1'
        ),
        array(
            'name' => 'GoPay',
            'slug' => 'wp-booking-system-premium-gopay',
            'description' => __('Accept credit cards and bank transfers using the GoPay payment gateway.','wp-booking-system'),
            'image' => WPBS_PLUGIN_DIR_URL . 'assets/img/addons/wp-booking-system-premium-gopay.png',
            'plans' => array(
                'wpbs-business-license' => 'Business',
                'wpbs-developer-license' => 'Developer',
            ),
            'minimum_required_version' => '1.0.2'
        ),
        array(
            'name' => 'Redsys',
            'slug' => 'wp-booking-system-premium-redsys',
            'description' => __('Accept payments using the Redsys payment gateway.','wp-booking-system'),
            'image' => WPBS_PLUGIN_DIR_URL . 'assets/img/addons/wp-booking-system-premium-redsys.png',
            'plans' => array(
                'wpbs-business-license' => 'Business',
                'wpbs-developer-license' => 'Developer',
            ),
            'minimum_required_version' => '1.0.0'
        ),
        array(
            'name' => 'PriceLabs.co Sync',
            'slug' => 'wp-booking-system-premium-pricelabs',
            'description' => __('Import property prices from PriceLabs.co directly into your calendar.','wp-booking-system'),
            'image' => WPBS_PLUGIN_DIR_URL . 'assets/img/addons/wp-booking-system-premium-pricelabs.png',
            'plans' => array(
                'wpbs-business-license' => 'Business',
                'wpbs-developer-license' => 'Developer',
            ),
            'minimum_required_version' => '1.0.0'
        ),
        array(
            'name' => 'Search',
            'slug' => 'wp-booking-system-premium-search',
            'description' => __('This Add-on allows you to create a search widget so website visitors can search through calendars.','wp-booking-system'),
            'image' => WPBS_PLUGIN_DIR_URL . 'assets/img/addons/wp-booking-system-premium-search.png',
            'plans' => array(
                'wpbs-personal-license' => 'Personal',
                'wpbs-business-license' => 'Business',
                'wpbs-developer-license' => 'Developer',
            ),
            'minimum_required_version' => '1.0.8'
        ),
        array(
            'name' => 'Inventory',
            'slug' => 'wp-booking-system-premium-inventory',
            'description' => __('Allow multiple bookings per day and automatically make the dates unavailable when the maximum number of bookings is reached.','wp-booking-system'),
            'image' => WPBS_PLUGIN_DIR_URL . 'assets/img/addons/wp-booking-system-premium-maximum-bookings-per-day.png',
            'plans' => array(
                'wpbs-personal-license' => 'Personal',
                'wpbs-business-license' => 'Business',
                'wpbs-developer-license' => 'Developer',
            ),
            'minimum_required_version' => '1.0.7'
        ),
        array(
            'name' => 'Email Reminders',
            'slug' => 'wp-booking-system-premium-email-reminders',
            'description' => __('Automatically email your customers to remind them about their bookings.','wp-booking-system'),
            'image' => WPBS_PLUGIN_DIR_URL . 'assets/img/addons/wp-booking-system-premium-email-reminder.png',
            'plans' => array(
                'wpbs-personal-license' => 'Personal',
                'wpbs-business-license' => 'Business',
                'wpbs-developer-license' => 'Developer',
            ),
            'minimum_required_version' => '1.0.9'
        ),
        array(
            'name' => 'SMS Notifications',
            'slug' => 'wp-booking-system-premium-sms',
            'description' => __('Send SMS notifications to your customers.','wp-booking-system'),
            'image' => WPBS_PLUGIN_DIR_URL . 'assets/img/addons/wp-booking-system-premium-sms-notifications.png',
            'plans' => array(
                'wpbs-business-license' => 'Business',
                'wpbs-developer-license' => 'Developer',
            ),
            'minimum_required_version' => '1.0.1'
        ),
        array(
            'name' => 'Booking Restrictions',
            'slug' => 'wp-booking-system-premium-booking-restrictions',
            'description' => __('Set advanced booking restrictions like minimum or maximum days, enforce a day of the week, and more.','wp-booking-system'),
            'image' => WPBS_PLUGIN_DIR_URL . 'assets/img/addons/wp-booking-system-premium-booking-restrictions.png',
            'plans' => array(
                'wpbs-personal-license' => 'Personal',
                'wpbs-business-license' => 'Business',
                'wpbs-developer-license' => 'Developer',
            ),
            'minimum_required_version' => '1.0.4'
        ),
        array(
            'name' => 'Discounts & Coupons',
            'slug' => 'wp-booking-system-premium-discounts',
            'description' => __('Create discounts and coupon codes that are applied to the checkout form based on specific conditions.','wp-booking-system'),
            'image' => WPBS_PLUGIN_DIR_URL . 'assets/img/addons/wp-booking-system-premium-discounts.png',
            'plans' => array(
                'wpbs-business-license' => 'Business',
                'wpbs-developer-license' => 'Developer',
            ),
            'minimum_required_version' => '1.0.16'
        ),
        array(
            'name' => 'Reports',
            'slug' => 'wp-booking-system-premium-reporting',
            'description' => __('Creates a dashboard with reports and statistics for your bookings.','wp-booking-system'),
            'image' => WPBS_PLUGIN_DIR_URL . 'assets/img/addons/wp-booking-system-premium-reporting.png',
            'plans' => array(
                'wpbs-personal-license' => 'Personal',
                'wpbs-business-license' => 'Business',
                'wpbs-developer-license' => 'Developer',
            ),
            'minimum_required_version' => '1.0.3'
        ),
        array(
            'name' => 'Invoices',
            'slug' => 'wp-booking-system-premium-invoices',
            'description' => __('Generate professional looking invoices and email them to your customers.','wp-booking-system'),
            'image' => WPBS_PLUGIN_DIR_URL . 'assets/img/addons/wp-booking-system-premium-invoices.png',
            'plans' => array(
                'wpbs-business-license' => 'Business',
                'wpbs-developer-license' => 'Developer',
            ),
            'minimum_required_version' => '1.0.18'
        ),
        array(
            'name' => 'Multiple Currencies',
            'slug' => 'wp-booking-system-premium-multiple-currencies',
            'description' => __('Allow your customers to switch between currencies when making a payment.','wp-booking-system'),
            'image' => WPBS_PLUGIN_DIR_URL . 'assets/img/addons/wp-booking-system-premium-multiple-currencies.png',
            'plans' => array(
                'wpbs-business-license' => 'Business',
                'wpbs-developer-license' => 'Developer',
            ),
            'minimum_required_version' => '1.0.0'
        ),
        array(
            'name' => 'Contracts/Agreements',
            'slug' => 'wp-booking-system-premium-contracts',
            'description' => __('Create PDF contracts or agreements and email them to your customers.','wp-booking-system'),
            'image' => WPBS_PLUGIN_DIR_URL . 'assets/img/addons/wp-booking-system-premium-contracts.png',
            'plans' => array(
                'wpbs-business-license' => 'Business',
                'wpbs-developer-license' => 'Developer',
            ),
            'minimum_required_version' => '1.0.5'
        ),
        array(
            'name' => 'Booking Manager',
            'slug' => 'wp-booking-system-premium-booking-manager',
            'description' => __('Display all your bookings from all the calendars in a dashboard style layout.','wp-booking-system'),
            'image' => WPBS_PLUGIN_DIR_URL . 'assets/img/addons/wp-booking-system-premium-booking-manager.png',
            'plans' => array(
                'wpbs-personal-license' => 'Personal',
                'wpbs-business-license' => 'Business',
                'wpbs-developer-license' => 'Developer',
            ),
            'minimum_required_version' => '1.0.0'
        ),
    );
}
