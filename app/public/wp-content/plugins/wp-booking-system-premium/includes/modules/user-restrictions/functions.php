<?php

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Includes the Base files
 *
 */
function wpbs_include_files_restrictions()
{

    // Get legend dir path
    $dir_path = plugin_dir_path(__FILE__);

    // Include calendar restriction functions
    if (file_exists($dir_path . 'functions-restrictions-calendar.php')) {
        include $dir_path . 'functions-restrictions-calendar.php';
    }

    // Include form restriction functions
    if (file_exists($dir_path . 'functions-restrictions-form.php')) {
        include $dir_path . 'functions-restrictions-form.php';
    }

    // Include dashboard restriction functions
    if (file_exists($dir_path . 'functions-restrictions-dashboard.php')) {
        include $dir_path . 'functions-restrictions-dashboard.php';
    }

}
add_action('wpbs_include_files', 'wpbs_include_files_restrictions');

/**
 * Add the General Settings fields needed by this module
 *
 * @param array $settings
 *
 */
function wpbs_add_user_restrictions_general_settings_fields($settings)
{

    if (!current_user_can('manage_options')) {
        return;
    }

    include 'views/view-settings-tab-general-fields.php';

}
add_action('wpbs_submenu_page_settings_tab_general_bottom', 'wpbs_add_user_restrictions_general_settings_fields');

/**
 * Determines whether the current user has capabilities to edit plugin settings
 *
 * This is determined by checking if the current user has one of the roles from the Settings page
 *
 * @return bool
 *
 */
function wpbs_current_user_can_edit_plugin()
{

    if (current_user_can('manage_options')) {
        return true;
    }

    $settings = get_option('wpbs_settings', array());
    $user = wp_get_current_user();

    $user_role_permissions = (!empty($settings['user_role_permissions']) ? $settings['user_role_permissions'] : array());

    foreach ($user_role_permissions as $user_role) {

        if (in_array($user_role, $user->roles)) {
            return true;
        }

    }

    return false;

}

/**
 * Allow other user roles to save settings
 *
 */
function wpbs_settings_page_capability($capability)
{
    return apply_filters('wpbs_submenu_page_capability_settings', 'manage_options');
}
add_filter('option_page_capability_wpbs_settings', 'wpbs_settings_page_capability', 10, 1);

/**
 * Check if a user has the required capabilities to access a subpage
 *
 */
function wpbs_can_user_access_subpage($subpage, $user = false)
{
    if($user === false){
        $user = wp_get_current_user();
    }

    $subpages = wp_booking_system()->submenu_pages;

    if(!isset($subpages[$subpage])){
        return false;
    }

    $caps = array_keys( $user->allcaps );

    if(in_array($subpages[$subpage]->get('capability'), $caps)){
        return true;
    }

    return false;

}