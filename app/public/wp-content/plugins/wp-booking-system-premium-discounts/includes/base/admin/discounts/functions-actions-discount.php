<?php

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Validates and handles the adding of the new discount in the database
 *
 */
function wpbs_action_add_discount()
{

    // Verify for nonce
    if (empty($_POST['wpbs_token']) || !wp_verify_nonce($_POST['wpbs_token'], 'wpbs_add_discount')) {
        return;
    }

    // Verify for discount name
    if (empty($_POST['discount_name'])) {

        wpbs_admin_notices()->register_notice('discount_name_missing', '<p>' . __('Please add a name for your new discount.', 'wp-booking-system-coupons-discounts') . '</p>', 'error');
        wpbs_admin_notices()->display_notice('discount_name_missing');

        return;

    }

    // Prepare discount data to be inserted
    $discount_data = array(
        'name' => sanitize_text_field($_POST['discount_name']),
        'date_created' => current_time('Y-m-d H:i:s'),
        'date_modified' => current_time('Y-m-d H:i:s'),
        'status' => 'active',
        'fields' => array(),
    );

    // Insert discount into the database
    $discount_id = wpbs_insert_discount($discount_data);

    // If the discount could not be inserted show a message to the user
    if (!$discount_id) {

        wpbs_admin_notices()->register_notice('discount_insert_false', '<p>' . __('Something went wrong. Could not create the discount. Please try again.', 'wp-booking-system-coupons-discounts') . '</p>', 'error');
        wpbs_admin_notices()->display_notice('discount_insert_false');

        return;

    }

    // Redirect to the edit page of the discount with a success message
    wp_redirect(add_query_arg(array('page' => 'wpbs-discounts', 'subpage' => 'edit-discount', 'discount_id' => $discount_id, 'wpbs_message' => 'discount_insert_success'), admin_url('admin.php')));
    exit;

}
add_action('wpbs_action_add_discount', 'wpbs_action_add_discount', 50);

/**
 * Validates and handles the editing of an existing discount
 *
 */
function wpbs_action_edit_discount()
{
    // Verify for nonce
    if (empty($_POST['wpbs_token']) || !wp_verify_nonce($_POST['wpbs_token'], 'wpbs_edit_discount')) {
        return;
    }

    $_POST = stripslashes_deep($_POST);

    if (empty($_POST['discount_id'])) {

        wpbs_admin_notices()->register_notice('discount_update_failed', '<p>' . __('Something went wrong. Could not update the discount.', 'wp-booking-system-coupons-discounts') . '</p>', 'error');
        wpbs_admin_notices()->display_notice('discount_update_failed');

        return;

    }

    // Get Settings
    $settings = get_option('wpbs_settings', array());

    /**
     * Prepare variables
     *
     */
    $discount_id = absint($_POST['discount_id']);
    $discount_name = sanitize_text_field($_POST['discount_name']);

    // Get discount
    $discount = wpbs_get_discount($discount_id);

    $active_languages = (!empty($settings['active_languages']) ? $settings['active_languages'] : array());

    /**
     * Set Discount Options
     */
    $discount_options = array();
    $discount_options['description'] = sanitize_text_field($_POST['discount_description']);
    foreach($active_languages as $language){
        $discount_options['description_translation_' . $language] = sanitize_text_field($_POST['discount_description_translation_' . $language]);
    }

    $validity_period = _wpbs_array_sanitize_text_field($_POST['discount_validity_period']);

    foreach($validity_period as $i => $period){
        if(empty($period['from']) && empty($period['to'])){
            unset($validity_period[$i]);
        }
    }

    $discount_options['type'] = sanitize_text_field($_POST['discount_type']);
    $discount_options['value'] = sanitize_text_field($_POST['discount_value']);
    $discount_options['calendars'] = isset($_POST['discount_calendars']) ? $_POST['discount_calendars'] : array();
    $discount_options['validity_period'] = $validity_period;
    $discount_options['apply_to'] = sanitize_text_field($_POST['discount_apply_to']);
    $discount_options['visibility'] = sanitize_text_field($_POST['discount_visibility']);
    $discount_options['inclusion'] = sanitize_text_field($_POST['discount_inclusion']);
    $discount_options['application'] = sanitize_text_field($_POST['discount_application']);

    foreach ($_POST['discount_rules'] as $i => $discount_rule_group) {
        for ($j = 0; $j < count($discount_rule_group['condition']); $j++) {
            $discount_options['rules'][$i][] = array(
                'condition' => $discount_rule_group['condition'][$j],
                'form_field' => $discount_rule_group['form_field'][$j],
                'comparison' => $discount_rule_group['comparison'][$j],
                'value' => $discount_rule_group['value'][$j],
                'value-weekday' => $discount_rule_group['value-weekday'][$j],
                'value-user-role' => $discount_rule_group['value-user-role'][$j],
            );
        }
    }

    // Update discount
    $update_data = array(
        'name' => (!empty($discount_name) ? $discount_name : $discount->get('name')),
        'date_modified' => current_time('Y-m-d H:i:s'),
        'options' => $discount_options,
    );

    wpbs_update_discount($discount_id, $update_data);

    // Update Discount Name Translations
    if (isset($settings['active_languages']) && count($settings['active_languages']) > 0) {
        foreach ($settings['active_languages'] as $language) {
            wpbs_update_discount_meta($discount_id, 'discount_name_translation_' . $language, sanitize_text_field($_POST['discount_name_translation_' . $language]));
        }
    }

    /**
     * Action hook to save extra discount discount data
     *
     * @param array $_POST
     *
     */
    do_action('wpbs_save_discount_data', $_POST);

    /**
     * Success redirect
     *
     */
    wp_redirect(add_query_arg(array('page' => 'wpbs-discounts', 'subpage' => 'edit-discount', 'discount_id' => $discount_id, 'wpbs_message' => 'discount_edit_success'), admin_url('admin.php')));
    exit;

}
add_action('wpbs_action_edit_discount', 'wpbs_action_edit_discount', 50);

/**
 * Handles the trash discount action, which changes the status of the discount from active to trash
 *
 */
function wpbs_action_trash_discount()
{

    // Verify for nonce
    if (empty($_GET['wpbs_token']) || !wp_verify_nonce($_GET['wpbs_token'], 'wpbs_trash_discount')) {
        return;
    }

    if (empty($_GET['discount_id'])) {
        return;
    }

    $discount_id = absint($_GET['discount_id']);

    $discount_data = array(
        'status' => 'trash',
    );

    $updated = wpbs_update_discount($discount_id, $discount_data);

    if (!$updated) {
        return;
    }

    // Redirect to the current page
    wp_redirect(add_query_arg(array('page' => 'wpbs-discounts', 'discount_status' => 'active', 'wpbs_message' => 'discount_trash_success'), admin_url('admin.php')));
    exit;

}
add_action('wpbs_action_trash_discount', 'wpbs_action_trash_discount', 50);

/**
 * Handles the restore discount action, which changes the status of the discount from trash to active
 *
 */
function wpbs_action_restore_discount()
{

    // Verify for nonce
    if (empty($_GET['wpbs_token']) || !wp_verify_nonce($_GET['wpbs_token'], 'wpbs_restore_discount')) {
        return;
    }

    if (empty($_GET['discount_id'])) {
        return;
    }

    $discount_id = absint($_GET['discount_id']);

    $discount_data = array(
        'status' => 'active',
    );

    $updated = wpbs_update_discount($discount_id, $discount_data);

    if (!$updated) {
        return;
    }

    // Redirect to the current page
    wp_redirect(add_query_arg(array('page' => 'wpbs-discounts', 'discount_status' => 'trash', 'wpbs_message' => 'discount_restore_success'), admin_url('admin.php')));
    exit;

}
add_action('wpbs_action_restore_discount', 'wpbs_action_restore_discount', 50);

/**
 * Handles the delete discount action, which removes all discount data, legend items and events data
 * associated with the discount
 *
 */
function wpbs_action_delete_discount()
{

    // Verify for nonce
    if (empty($_GET['wpbs_token']) || !wp_verify_nonce($_GET['wpbs_token'], 'wpbs_delete_discount')) {
        return;
    }

    if (empty($_GET['discount_id'])) {
        return;
    }

    $discount_id = absint($_GET['discount_id']);

    /**
     * Delete the discount
     *
     */
    $deleted = wpbs_delete_discount($discount_id);

    if (!$deleted) {
        return;
    }

    foreach (wpbs_get_discount_meta($discount_id) as $key => $value) {
        wpbs_delete_discount_meta($discount_id, $key);
    }

    // Redirect to the current page
    wp_redirect(add_query_arg(array('page' => 'wpbs-discounts', 'discount_status' => 'trash', 'wpbs_message' => 'discount_delete_success'), admin_url('admin.php')));
    exit;

}
add_action('wpbs_action_delete_discount', 'wpbs_action_delete_discount', 50);

/**
 * Handles the duplication of a discount
 *
 */
function wpbs_action_duplicate_discount()
{

    // Verify for nonce
    if (empty($_GET['wpbs_token']) || !wp_verify_nonce($_GET['wpbs_token'], 'wpbs_duplicate_discount')) {
        return;
    }

    if (empty($_GET['discount_id'])) {
        return;
    }

    $discount_id = absint($_GET['discount_id']);

    $discount = wpbs_get_discount($discount_id);

    $new_discount_id = wpbs_insert_discount(array(
        'name' => __('Duplicate of', 'wp-booking-system-coupons-discounts') . ' ' . $discount->get('name'),
        'options' => $discount->get('options'),
        'date_created' => date('Y-m-d H:i:s', current_time('timestamp')),
        'date_modified' => date('Y-m-d H:i:s', current_time('timestamp')),
        'status' => $discount->get('status'),
    ));

    $meta_fields = wpbs_get_discount_meta($discount_id);

    foreach ($meta_fields as $meta_key => $meta_value) {
        wpbs_add_discount_meta($new_discount_id, $meta_key, $meta_value[0]);
    }

    // Redirect to the current page
    wp_redirect(add_query_arg(array('page' => 'wpbs-discounts', 'wpbs_message' => 'discount_duplicate_success'), admin_url('admin.php')));
    exit;

}
add_action('wpbs_action_duplicate_discount', 'wpbs_action_duplicate_discount', 50);
