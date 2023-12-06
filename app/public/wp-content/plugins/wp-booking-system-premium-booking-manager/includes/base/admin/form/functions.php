<?php

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Add Submenu item
 *
 */
function wpbs_bm_form_options_subtabs($section)
{
    $section['form-options']['bookings_list'] = __('Booking Manager Field Mapping', 'wp-booking-system-booking-manager');
    return $section;
}
add_filter('wpbs_submenu_page_edit_form_sub_tabs', 'wpbs_bm_form_options_subtabs', 50, 1);

/**
 * Submenu item view.
 *
 */
function wpbs_submenu_page_edit_form_tabs_bookings_list()
{
    include 'views/view-edit-form-tab-bookings-manager.php';
}
add_action('wpbs_submenu_page_edit_form_tabs_form_options_bookings_list', 'wpbs_submenu_page_edit_form_tabs_bookings_list');

/**
 * Save meta fields
 *
 */
function wpbs_bm_edit_form_meta_fields($meta_fields)
{
    $meta_fields['booking_manager_fields'] = array('translations' => false, 'sanitization' => '_wpbs_array_esc_attr_text_field');

    return $meta_fields;
}
add_filter('wpbs_edit_forms_meta_fields', 'wpbs_bm_edit_form_meta_fields', 10, 1);
