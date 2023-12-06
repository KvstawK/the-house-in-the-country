<?php

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Includes the files needed for the coupons admin area
 *
 */
function wpbs_d_include_files_admin_coupons()
{

    // Get legend admin dir path
    $dir_path = plugin_dir_path(__FILE__);

    // Include submenu page
    if (file_exists($dir_path . 'class-submenu-page-coupons.php')) {
        include $dir_path . 'class-submenu-page-coupons.php';
    }

    // Include coupons list table
    if (file_exists($dir_path . 'class-list-table-coupons.php')) {
        include $dir_path . 'class-list-table-coupons.php';
    }

    // Include admin actions
    if (file_exists($dir_path . 'functions-actions-coupon.php')) {
        include $dir_path . 'functions-actions-coupon.php';
    }


}
add_action('wpbs_d_include_files', 'wpbs_d_include_files_admin_coupons');

/**
 * Register the coupons admin submenu page
 *
 */
function wpbs_d_register_submenu_page_coupons($submenu_pages)
{

    if (!is_array($submenu_pages)) {
        return $submenu_pages;
    }

    $submenu_pages['coupons'] = array(
        'class_name' => 'WPBS_Submenu_Page_Coupons',
        'data' => array(
            'page_title' => __('Coupons', 'wp-booking-system-coupons-discounts'),
            'menu_title' => __('Coupons', 'wp-booking-system-coupons-discounts'),
            'capability' => apply_filters('wpbs_submenu_page_capability_coupons', 'manage_options'),
            'menu_slug' => 'wpbs-coupons',
        ),
    );

    return $submenu_pages;

}
add_filter('wpbs_register_submenu_page', 'wpbs_d_register_submenu_page_coupons', 50);
