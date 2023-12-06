<?php

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Validates and handles the adding of the new coupon in the database
 *
 */
function wpbs_action_add_coupon()
{

    // Verify for nonce
    if (empty($_POST['wpbs_token']) || !wp_verify_nonce($_POST['wpbs_token'], 'wpbs_add_coupon')) {
        return;
    }

    // Verify for coupon name
    if (empty($_POST['coupon_name'])) {

        wpbs_admin_notices()->register_notice('coupon_name_missing', '<p>' . __('Please add a name for your new coupon.', 'wp-booking-system-coupons-discounts') . '</p>', 'error');
        wpbs_admin_notices()->display_notice('coupon_name_missing');

        return;

    }

    // Prepare coupon data to be inserted
    $coupon_data = array(
        'name' => sanitize_text_field($_POST['coupon_name']),
        'date_created' => current_time('Y-m-d H:i:s'),
        'date_modified' => current_time('Y-m-d H:i:s'),
        'status' => 'active',
        'fields' => array(),
    );

    // Insert coupon into the database
    $coupon_id = wpbs_insert_coupon($coupon_data);

    // If the coupon could not be inserted show a message to the user
    if (!$coupon_id) {

        wpbs_admin_notices()->register_notice('coupon_insert_false', '<p>' . __('Something went wrong. Could not create the coupon. Please try again.', 'wp-booking-system-coupons-discounts') . '</p>', 'error');
        wpbs_admin_notices()->display_notice('coupon_insert_false');

        return;

    }


    // Redirect to the edit page of the coupon with a success message
    wp_redirect(add_query_arg(array('page' => 'wpbs-coupons', 'subpage' => 'edit-coupon', 'coupon_id' => $coupon_id, 'wpbs_message' => 'coupon_insert_success'), admin_url('admin.php')));
    exit;

}
add_action('wpbs_action_add_coupon', 'wpbs_action_add_coupon', 50);

/**
 * Validates and handles the editing of an existing coupon
 *
 */
function wpbs_action_edit_coupon()
{
    // Verify for nonce
    if (empty($_POST['wpbs_token']) || !wp_verify_nonce($_POST['wpbs_token'], 'wpbs_edit_coupon')) {
        return;
    }

    $_POST = stripslashes_deep($_POST);

    if (empty($_POST['coupon_id'])) {

        wpbs_admin_notices()->register_notice('coupon_update_failed', '<p>' . __('Something went wrong. Could not update the coupon.', 'wp-booking-system-coupons-discounts') . '</p>', 'error');
        wpbs_admin_notices()->display_notice('coupon_update_failed');

        return;

    }

    // Get Settings
    $settings = get_option('wpbs_settings', array());

    /**
     * Prepare variables
     *
     */
    $coupon_id = absint($_POST['coupon_id']);
    $coupon_name = sanitize_text_field($_POST['coupon_name']);

    // Get coupon
    $coupon = wpbs_get_coupon($coupon_id);

    $active_languages = (!empty($settings['active_languages']) ? $settings['active_languages'] : array());

    /**
     * Set coupon Options
     */
    $coupon_options = array();
    $coupon_code = sanitize_text_field($_POST['coupon_code']);
    $coupon_code = sanitize_title($coupon_code);
    $coupon_code = strtoupper($coupon_code);

    $coupon_options['code'] = $coupon_code;
    $coupon_options['type'] = sanitize_text_field($_POST['coupon_type']);
    $coupon_options['description'] = sanitize_text_field($_POST['coupon_description']);
    foreach($active_languages as $language){
        $coupon_options['description_translation_' . $language] = sanitize_text_field($_POST['coupon_description_translation_' . $language]);
    }
    $coupon_options['value'] = sanitize_text_field($_POST['coupon_value']);
    $coupon_options['calendars'] = isset($_POST['coupon_calendars']) ? $_POST['coupon_calendars'] : array();
    $coupon_options['apply_to'] = sanitize_text_field($_POST['coupon_apply_to']);
    $coupon_options['weekdays'] = isset($_POST['coupon_weekdays']) ? $_POST['coupon_weekdays'] : array();
    $coupon_options['minimum_stay'] = isset($_POST['coupon_minimum_stay']) && !empty($_POST['coupon_minimum_stay']) ? absint($_POST['coupon_minimum_stay']) : false;
    $coupon_options['maximum_stay'] = isset($_POST['coupon_maximum_stay']) && !empty($_POST['coupon_maximum_stay']) ? absint($_POST['coupon_maximum_stay']) : false;
    $coupon_options['validity_from'] = sanitize_text_field($_POST['coupon_validity_from']);
    $coupon_options['validity_to'] = sanitize_text_field($_POST['coupon_validity_to']);
    $coupon_options['inclusion'] = sanitize_text_field($_POST['coupon_inclusion']);
    $coupon_options['application_order'] = sanitize_text_field($_POST['coupon_application_order']);

    $usage_limit = intval($_POST['coupon_usage_limit']);
    if(!empty($usage_limit)){
        $coupon_options['usage_limit'] = $usage_limit;
    }

    // Update coupon
    $update_data = array(
        'name' => (!empty($coupon_name) ? $coupon_name : $coupon->get('name')),
        'date_modified' => current_time('Y-m-d H:i:s'),
        'options' => $coupon_options,
    );

    wpbs_update_coupon($coupon_id, $update_data);

    // Update coupon Name Translations
    if (isset($settings['active_languages']) && count($settings['active_languages']) > 0) {
        foreach ($settings['active_languages'] as $language) {
            wpbs_update_coupon_meta($coupon_id, 'coupon_name_translation_' . $language, sanitize_text_field($_POST['coupon_name_translation_' . $language]));
        }
    }


    /**
     * Action hook to save extra coupon coupon data
     *
     * @param array $_POST
     *
     */
    do_action('wpbs_save_coupon_data', $_POST);

    /**
     * Success redirect
     *
     */
    wp_redirect(add_query_arg(array('page' => 'wpbs-coupons', 'subpage' => 'edit-coupon', 'coupon_id' => $coupon_id, 'wpbs_message' => 'coupon_edit_success'), admin_url('admin.php')));
    exit;

}
add_action('wpbs_action_edit_coupon', 'wpbs_action_edit_coupon', 50);

/**
 * Handles the trash coupon action, which changes the status of the coupon from active to trash
 *
 */
function wpbs_action_trash_coupon()
{

    // Verify for nonce
    if (empty($_GET['wpbs_token']) || !wp_verify_nonce($_GET['wpbs_token'], 'wpbs_trash_coupon')) {
        return;
    }

    if (empty($_GET['coupon_id'])) {
        return;
    }

    $coupon_id = absint($_GET['coupon_id']);

    $coupon_data = array(
        'status' => 'trash',
    );

    $updated = wpbs_update_coupon($coupon_id, $coupon_data);

    if (!$updated) {
        return;
    }

    // Redirect to the current page
    wp_redirect(add_query_arg(array('page' => 'wpbs-coupons', 'coupon_status' => 'active', 'wpbs_message' => 'coupon_trash_success'), admin_url('admin.php')));
    exit;

}
add_action('wpbs_action_trash_coupon', 'wpbs_action_trash_coupon', 50);

/**
 * Handles the restore coupon action, which changes the status of the coupon from trash to active
 *
 */
function wpbs_action_restore_coupon()
{

    // Verify for nonce
    if (empty($_GET['wpbs_token']) || !wp_verify_nonce($_GET['wpbs_token'], 'wpbs_restore_coupon')) {
        return;
    }

    if (empty($_GET['coupon_id'])) {
        return;
    }

    $coupon_id = absint($_GET['coupon_id']);

    $coupon_data = array(
        'status' => 'active',
    );

    $updated = wpbs_update_coupon($coupon_id, $coupon_data);

    if (!$updated) {
        return;
    }

    // Redirect to the current page
    wp_redirect(add_query_arg(array('page' => 'wpbs-coupons', 'coupon_status' => 'trash', 'wpbs_message' => 'coupon_restore_success'), admin_url('admin.php')));
    exit;

}
add_action('wpbs_action_restore_coupon', 'wpbs_action_restore_coupon', 50);

/**
 * Handles the delete coupon action, which removes all coupon data, legend items and events data
 * associated with the coupon
 *
 */
function wpbs_action_delete_coupon()
{

    // Verify for nonce
    if (empty($_GET['wpbs_token']) || !wp_verify_nonce($_GET['wpbs_token'], 'wpbs_delete_coupon')) {
        return;
    }

    if (empty($_GET['coupon_id'])) {
        return;
    }

    $coupon_id = absint($_GET['coupon_id']);

    /**
     * Delete the coupon
     *
     */
    $deleted = wpbs_delete_coupon($coupon_id);

    if (!$deleted) {
        return;
    }

    foreach (wpbs_get_coupon_meta($coupon_id) as $key => $value) {
        wpbs_delete_coupon_meta($coupon_id, $key);
    }

    // Redirect to the current page
    wp_redirect(add_query_arg(array('page' => 'wpbs-coupons', 'coupon_status' => 'trash', 'wpbs_message' => 'coupon_delete_success'), admin_url('admin.php')));
    exit;

}
add_action('wpbs_action_delete_coupon', 'wpbs_action_delete_coupon', 50);


/**
 * Handles the duplication of a coupon
 *
 */
function wpbs_action_duplicate_coupon()
{

    // Verify for nonce
    if (empty($_GET['wpbs_token']) || !wp_verify_nonce($_GET['wpbs_token'], 'wpbs_duplicate_coupon')) {
        return;
    }

    if (empty($_GET['coupon_id'])) {
        return;
    }

    $coupon_id = absint($_GET['coupon_id']);

    $coupon = wpbs_get_coupon($coupon_id);

    $new_coupon_id = wpbs_insert_coupon(array(
        'name' => __('Duplicate of', 'wp-booking-system-coupons-discounts') . ' ' . $coupon->get('name'),
        'options' => $coupon->get('options'),
        'date_created' => date('Y-m-d H:i:s', current_time('timestamp')),
        'date_modified' => date('Y-m-d H:i:s', current_time('timestamp')),
        'status' => $coupon->get('status'),
    ));

    $meta_fields = wpbs_get_coupon_meta($coupon_id);

    foreach ($meta_fields as $meta_key => $meta_value) {
        wpbs_add_coupon_meta($new_coupon_id, $meta_key, $meta_value[0]);
    }

    // Redirect to the current page
    wp_redirect(add_query_arg(array('page' => 'wpbs-coupons', 'wpbs_message' => 'coupon_duplicate_success'), admin_url('admin.php')));
    exit;

}
add_action('wpbs_action_duplicate_coupon', 'wpbs_action_duplicate_coupon', 50);