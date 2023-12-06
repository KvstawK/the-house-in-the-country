<?php

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Add the Edit Calendar fields needed by this module
 *
 * @param WPBS_Form
 *
 */
function wpbs_add_user_restrictions_edit_form_fields($form)
{

    if (!wpbs_current_user_can_edit_plugin()) {
        return;
    }

    include 'views/view-edit-form-main-fields.php';

}
add_action('wpbs_submenu_page_edit_form_tab_general_bottom', 'wpbs_add_user_restrictions_edit_form_fields');

/**
 * Save the Edit Form fields
 *
 * @param array $post_data
 *
 */
function wpbs_save_user_restrictions_edit_form_fields($post_data)
{

    if (!isset($post_data['user_permission'])) {
        return;
    }

    if (empty($post_data)) {
        return;
    }

    $form_id = absint($post_data['form_id']);

    if (empty($form_id)) {
        return;
    }

    // Delete the user permission meta
    wpbs_delete_form_meta($form_id, 'user_permission');

    // Update form user permissions meta if the value exists
    if (!empty($post_data['user_permission']) && is_array($post_data['user_permission'])) {

        foreach ($post_data['user_permission'] as $user_id) {
            wpbs_add_form_meta($form_id, 'user_permission', $user_id);
        }

    }

}
add_action('wpbs_save_form_data', 'wpbs_save_user_restrictions_edit_form_fields');

/**
 * Determines whether the current user has capabilities to edit any forms
 *
 * @return bool
 *
 */
function wpbs_current_user_can_edit_any_forms()
{

    if (current_user_can('manage_options')) {
        return true;
    }

    $forms = wpbs_get_forms();
    $user = wp_get_current_user();

    foreach ($forms as $form) {

        if (wpbs_current_user_can_edit_form($form->get('id'))) {
            return true;
        }

    }

    return false;

}

/**
 * Determines whether the current user has capabilities to edit the given form
 *
 * @param int $form_id
 *
 * @return bool
 *
 */
function wpbs_current_user_can_edit_form($form_id)
{

    $user = wp_get_current_user();
    $user_permissions = wpbs_get_form_meta($form_id, 'user_permission');

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
function wpbs_set_forms_submenu_page_capabilities($capability = 'manage_options')
{

    if (current_user_can('manage_options')) {
        return 'manage_options';
    }

    if (wpbs_current_user_can_edit_plugin()) {
        return 'read';
    }

    // If on add new form page
    if (!empty($_GET['subpage']) && $_GET['subpage'] == 'add-form') {

        if (wpbs_current_user_can_edit_any_forms()) {
            return 'manage_options';
        }

    }

    // If on edit form page
    if (!empty($_GET['form_id'])) {

        if (wpbs_current_user_can_edit_form((int) $_GET['form_id'])) {
            return 'read';
        }

    } else {

        if (wpbs_current_user_can_edit_any_forms()) {
            return 'read';
        }

    }

    return $capability;

}
add_filter('wpbs_menu_page_capability', 'wpbs_set_forms_submenu_page_capabilities');
add_filter('wpbs_submenu_page_capability_forms', 'wpbs_set_forms_submenu_page_capabilities');

/**
 * Remove forms from the wpbs_get_forms returned value on plugin pages
 *
 * @param array $forms
 * @param array $args
 * @param bool  $count
 *
 * @return mixed array|int
 *
 */
function wpbs_get_forms_user_capabilities($forms, $args, $count = false)
{

    if (wpbs_current_user_can_edit_plugin()) {
        return $forms;
    }

    if (!is_admin()) {
        return $forms;
    }

    if (empty($_GET['page']) || $_GET['page'] != 'wpbs-forms') {
        return $forms;
    }

    // Get all form args
    $all_forms_args = array(
        'number' => -1,
        'offset' => 0,
        'status' => (!empty($args['status']) ? $args['status'] : 'active'),
    );

    $all_forms = wp_booking_system()->db['forms']->get_forms($all_forms_args);
    $user_forms = array();

    foreach ($all_forms as $form) {

        if (wpbs_current_user_can_edit_form($form->get('id'))) {
            $user_forms[] = $form;
        }

    }

    // Handle the case where forms are being present
    if (is_array($forms)) {

        $forms = array_slice($user_forms, (!empty($args['offset']) ? $args['offset'] : 0), (!empty($args['number']) ? $args['number'] : 9999));

        // Handle the case where the count of the forms is present
    } else {

        $forms = count($user_forms);

        if ($forms < 0) {
            $forms = 0;
        }

    }

    return $forms;

}
add_filter('wpbs_get_forms', 'wpbs_get_forms_user_capabilities', 10, 3);

/**
 * Remove forms from the wpbs_get_forms returned value on all pages
 *
 * @param array $forms
 * @param array $args
 * @param bool  $count
 *
 * @return mixed array|int
 *
 */
function wpbs_get_forms_user_capabilities_global($forms, $args, $count = false)
{

    if (wpbs_current_user_can_edit_plugin()) {
        return $forms;
    }

    if (!is_admin()) {
        return $forms;
    }

    // Get all form args
    $all_forms_args = array(
        'number' => -1,
        'offset' => 0,
        'status' => (!empty($args['status']) ? $args['status'] : 'active'),
    );

    $all_forms = wp_booking_system()->db['forms']->get_forms($all_forms_args);
    $user_forms = array();

    foreach ($all_forms as $form) {

        if (wpbs_current_user_can_edit_form($form->get('id'))) {
            $user_forms[] = $form;
        }

    }

    // Handle the case where forms are being present
    if (is_array($forms)) {

        $forms = array_slice($user_forms, (!empty($args['offset']) ? $args['offset'] : 0), (!empty($args['number']) ? $args['number'] : 9999));

        // Handle the case where the count of the forms is present
    } else {

        $forms = count($user_forms);

        if ($forms < 0) {
            $forms = 0;
        }

    }

    return $forms;

}

/**
 * Remove the Form Table views for users that do not have complete access to the plugin
 *
 * @param array $views
 *
 * @return array
 *
 */
function wpbs_list_table_forms_remove_views($views)
{

    if (wpbs_current_user_can_edit_plugin()) {
        return $views;
    }

    return array();

}
add_filter('wpbs_list_table_forms_views', 'wpbs_list_table_forms_remove_views', 100);

/**
 * Remove the Add New form page title action button for users that do not have
 * complete access to the plugin
 *
 */
function wpbs_forms_page_remove_title_actions()
{

    if (!wpbs_current_user_can_edit_plugin()) {
        echo '<style>.wpbs-wrap-forms .page-title-action { display: none; }</style>';
    }

}
add_action('admin_head', 'wpbs_forms_page_remove_title_actions');

/**
 * Removes the form Table row actions for users that do not have complete access to the plugin
 *
 * @param array $actions
 * @param array $item
 *
 * @return array
 *
 */
function wpbs_list_table_forms_remove_row_actions($actions, $item)
{

    if (wpbs_current_user_can_edit_plugin()) {
        return $actions;
    }

    // Remove all actions if the form is in Trash
    if ($item['status'] == 'trash') {
        return array();
    }

    // Remove the Trash option if it exists
    if (!empty($actions['trash'])) {
        unset($actions['trash']);
    }

    return $actions;

}
add_filter('wpbs_list_table_forms_row_actions', 'wpbs_list_table_forms_remove_row_actions', 100, 2);
