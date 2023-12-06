<?php

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Add the Edit Calendar fields needed by this module
 *
 * @param WPBS_Calendar
 *
 */
function wpbs_add_user_restrictions_edit_calendar_fields($calendar)
{

    if (!wpbs_current_user_can_edit_plugin()) {
        return;
    }

    include 'views/view-edit-calendar-main-fields.php';

}
add_action('wpbs_view_edit_calendar_main', 'wpbs_add_user_restrictions_edit_calendar_fields');

/**
 * Save the Edit Calendar fields
 *
 * @param array $post_data
 *
 */
function wpbs_save_user_restrictions_edit_calendar_fields($post_data)
{

    if (empty($post_data['form_data'])) {
        return;
    }

    $form_data = $post_data['form_data'];

    if (empty($form_data['calendar_id'])) {
        return;
    }

    if (empty($form_data['wpbs_token_calendar_user_premissions']) || !wp_verify_nonce($form_data['wpbs_token_calendar_user_premissions'], 'wpbs_token_calendar_user_premissions')) {
        return;
    }

    $calendar_id = (int) $form_data['calendar_id'];

    // Delete the user permission meta
    wpbs_delete_calendar_meta($calendar_id, 'user_permission');

    // Update calendar user permissions meta if the value exists
    if (!empty($form_data['calendar_user_permissions']) && is_array($form_data['calendar_user_permissions'])) {

        foreach ($form_data['calendar_user_permissions'] as $user_id) {
            wpbs_add_calendar_meta($calendar_id, 'user_permission', $user_id);
        }

    }

}
add_action('wpbs_save_calendar_data', 'wpbs_save_user_restrictions_edit_calendar_fields');

/**
 * Determines whether the current user has capabilities to edit any calendars
 *
 * @return bool
 *
 */
function wpbs_current_user_can_edit_any_calendars()
{

    if (current_user_can('manage_options')) {
        return true;
    }

    $calendars = wpbs_get_calendars();
    $user = wp_get_current_user();

    foreach ($calendars as $calendar) {

        if (wpbs_current_user_can_edit_calendar($calendar->get('id'))) {
            return true;
        }

    }

    return false;

}

/**
 * Determines whether the current user has capabilities to edit the given calendar
 *
 * @param int $calendar_id
 *
 * @return bool
 *
 */
function wpbs_current_user_can_edit_calendar($calendar_id)
{

    $user = wp_get_current_user();
    $user_permissions = wpbs_get_calendar_meta($calendar_id, 'user_permission');

    if (empty($user_permissions)) {
        $user_permissions = array();
    }

    if (in_array($user->ID, $user_permissions)) {
        return true;
    }

    return false;

}

/**
 * Modifies the permisions for the main plugin admin page and submenu pages
 *
 * @param string $capability
 *
 * @return string
 *
 */
function wpbs_set_calendars_submenu_page_capabilities($capability = 'manage_options')
{

    if (current_user_can('manage_options')) {
        return 'manage_options';
    }

    if (wpbs_current_user_can_edit_plugin()) {
        return 'read';
    }

    // If on add new calendar page
    if (!empty($_GET['subpage']) && $_GET['subpage'] == 'add-calendar') {

        if (wpbs_current_user_can_edit_any_calendars()) {
            return 'manage_options';
        }

    }

    // If on edit calendar page
    if (!empty($_GET['calendar_id'])) {

        if (wpbs_current_user_can_edit_calendar((int) $_GET['calendar_id'])) {
            return 'read';
        }

    } else {

        if (wpbs_current_user_can_edit_any_calendars()) {
            return 'read';
        }

    }

    return $capability;

}
add_filter('wpbs_menu_page_capability', 'wpbs_set_calendars_submenu_page_capabilities');
add_filter('wpbs_submenu_page_capability_calendars', 'wpbs_set_calendars_submenu_page_capabilities');
add_filter('wpbs_submenu_page_capability_bookings', 'wpbs_set_calendars_submenu_page_capabilities');
add_filter('wpbs_submenu_page_capability_reporting', 'wpbs_set_calendars_submenu_page_capabilities');

/**
 * Remove calendars from the wpbs_get_calendars returned value on plugin pages
 *
 * @param array $calendars
 * @param array $args
 * @param bool  $count
 *
 * @return mixed array|int
 *
 */
function wpbs_get_calendars_user_capabilities($calendars, $args, $count = false)
{

    if (wpbs_current_user_can_edit_plugin()) {
        return $calendars;
    }

    if (!is_admin()) {
        return $calendars;
    }

    if (empty($_GET['page']) || ($_GET['page'] != 'wpbs-calendars' && $_GET['page'] != 'wpbs-bookings' && $_GET['page'] != 'wpbs-reporting')) {
        return $calendars;
    }

    // Get all calendar args
    $all_calendars_args = array(
        'number' => -1,
        'offset' => 0,
        'status' => (!empty($args['status']) ? $args['status'] : 'active'),
    );

    $all_calendars = wp_booking_system()->db['calendars']->get_calendars($all_calendars_args);
    $user_calendars = array();

    foreach ($all_calendars as $calendar) {

        if (wpbs_current_user_can_edit_calendar($calendar->get('id'))) {
            $user_calendars[] = $calendar;
        }

    }

    // Handle the case where calendars are being present
    if (is_array($calendars)) {

        $calendars = array_slice($user_calendars, (!empty($args['offset']) ? $args['offset'] : 0), (!empty($args['number']) ? $args['number'] : 9999));

        // Handle the case where the count of the calendars is present
    } else {

        $calendars = count($user_calendars);

        if ($calendars < 0) {
            $calendars = 0;
        }

    }

    return $calendars;

}
add_filter('wpbs_get_calendars', 'wpbs_get_calendars_user_capabilities', 10, 3);

/**
 * Remove calendars from the wpbs_get_calendars returned value on all pages
 *
 * @param array $calendars
 * @param array $args
 * @param bool  $count
 *
 * @return mixed array|int
 *
 */
function wpbs_get_calendars_user_capabilities_global($calendars, $args, $count = false)
{

    if (wpbs_current_user_can_edit_plugin()) {
        return $calendars;
    }

    if (!is_admin()) {
        return $calendars;
    }

    // Get all calendar args
    $all_calendars_args = array(
        'number' => -1,
        'offset' => 0,
        'status' => (!empty($args['status']) ? $args['status'] : 'active'),
    );

    $all_calendars = wp_booking_system()->db['calendars']->get_calendars($all_calendars_args);
    $user_calendars = array();

    foreach ($all_calendars as $calendar) {

        if (wpbs_current_user_can_edit_calendar($calendar->get('id'))) {
            $user_calendars[] = $calendar;
        }

    }

    // Handle the case where calendars are being present
    if (is_array($calendars)) {

        $calendars = array_slice($user_calendars, (!empty($args['offset']) ? $args['offset'] : 0), (!empty($args['number']) ? $args['number'] : 9999));

        // Handle the case where the count of the calendars is present
    } else {

        $calendars = count($user_calendars);

        if ($calendars < 0) {
            $calendars = 0;
        }

    }

    return $calendars;

}

/**
 * Remove the Calendar Table views for users that do not have complete access to the plugin
 *
 * @param array $views
 *
 * @return array
 *
 */
function wpbs_list_table_calendars_remove_views($views)
{

    if (wpbs_current_user_can_edit_plugin()) {
        return $views;
    }

    return array();

}
add_filter('wpbs_list_table_calendars_views', 'wpbs_list_table_calendars_remove_views', 100);

/**
 * Remove the Add New Calendar page title action button for users that do not have
 * complete access to the plugin
 *
 */
function wpbs_calendars_page_remove_title_actions()
{

    if (!wpbs_current_user_can_edit_plugin()) {
        echo '<style>.wpbs-wrap-calendars .page-title-action { display: none; }</style>';
    }

}
add_action('admin_head', 'wpbs_calendars_page_remove_title_actions');

/**
 * Removes the Calendar Table row actions for users that do not have complete access to the plugin
 *
 * @param array $actions
 * @param array $item
 *
 * @return array
 *
 */
function wpbs_list_table_calendars_remove_row_actions($actions, $item)
{

    if (wpbs_current_user_can_edit_plugin()) {
        return $actions;
    }

    // Remove all actions if the calendar is in Trash
    if ($item['status'] == 'trash') {
        return array();
    }

    // Remove the Trash option if it exists
    if (!empty($actions['trash'])) {
        unset($actions['trash']);
    }

    // Remove the Duplicate Calendar option if it exists
    if (!empty($actions['duplicate_calendar'])) {
        unset($actions['duplicate_calendar']);
    }

    return $actions;

}
add_filter('wpbs_list_table_calendars_row_actions', 'wpbs_list_table_calendars_remove_row_actions', 100, 2);
