<?php

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Includes the files needed for the Discounts
 *
 */
function wpbs_d_include_files_discount()
{

    // Get discount dir path
    $dir_path = plugin_dir_path(__FILE__);

    // Include main discount class
    if (file_exists($dir_path . 'class-discount.php')) {
        include $dir_path . 'class-discount.php';
    }

    // Include the db layer classes
    if (file_exists($dir_path . 'class-object-db-discounts.php')) {
        include $dir_path . 'class-object-db-discounts.php';
    }

    if (file_exists($dir_path . 'class-object-meta-db-discounts.php')) {
        include $dir_path . 'class-object-meta-db-discounts.php';
    }

    // Include discount calculation class
    if (file_exists($dir_path . 'class-evaluate-discounts.php')) {
        include $dir_path . 'class-evaluate-discounts.php';
    }

    // Include discount calculation function
    if (file_exists($dir_path . 'functions-discounts.php')) {
        include $dir_path . 'functions-discounts.php';
    }

}
add_action('wpbs_d_include_files', 'wpbs_d_include_files_discount');

/**
 * Register the class that handles database queries for the Discounts
 *
 * @param array $classes
 *
 * @return array
 *
 */
function wpbs_d_register_database_classes_discounts($classes)
{

    $classes['discounts'] = 'WPBS_Object_DB_Discounts';
    $classes['discountmeta'] = 'WPBS_Object_Meta_DB_Discounts';

    return $classes;

}
add_filter('wpbs_register_database_classes', 'wpbs_d_register_database_classes_discounts');

/**
 * Returns an array with WPBS_Discount objects from the database
 *
 * @param array $args
 * @param bool  $count
 *
 * @return array
 *
 */
function wpbs_get_discounts($args = array(), $count = false)
{

    $discounts = wp_booking_system()->db['discounts']->get_discounts($args, $count);

    /**
     * Add a filter hook just before returning
     *
     * @param array $discounts
     * @param array $args
     * @param bool  $count
     *
     */
    return apply_filters('wpbs_get_discounts', $discounts, $args, $count);

}

/**
 * Gets a discount from the database
 *
 * @param mixed int|object      - discount id or object representing the discount
 *
 * @return WPBS_Discount|false
 *
 */
function wpbs_get_discount($discount)
{

    return wp_booking_system()->db['discounts']->get_object($discount);

}

/**
 * Inserts a new discount into the database
 *
 * @param array $data
 *
 * @return mixed int|false
 *
 */
function wpbs_insert_discount($data)
{

    return wp_booking_system()->db['discounts']->insert($data);

}

/**
 * Updates a discount from the database
 *
 * @param int     $discount_id
 * @param array $data
 *
 * @return bool
 *
 */
function wpbs_update_discount($discount_id, $data)
{

    return wp_booking_system()->db['discounts']->update($discount_id, $data);

}

/**
 * Deletes a discount from the database
 *
 * @param int $discount_id
 *
 * @return bool
 *
 */
function wpbs_delete_discount($discount_id)
{

    return wp_booking_system()->db['discounts']->delete($discount_id);

}

/**
 * Inserts a new meta entry for the discount
 *
 * @param int    $discount_id
 * @param string $meta_key
 * @param string $meta_value
 * @param bool   $unique
 *
 * @return mixed int|false
 *
 */
function wpbs_add_discount_meta($discount_id, $meta_key, $meta_value, $unique = false)
{

    return wp_booking_system()->db['discountmeta']->add($discount_id, $meta_key, $meta_value, $unique);

}

/**
 * Updates a meta entry for the discount
 *
 * @param int    $discount_id
 * @param string $meta_key
 * @param string $meta_value
 * @param bool   $prev_value
 *
 * @return bool
 *
 */
function wpbs_update_discount_meta($discount_id, $meta_key, $meta_value, $prev_value = '')
{

    return wp_booking_system()->db['discountmeta']->update($discount_id, $meta_key, $meta_value, $prev_value);

}

/**
 * Returns a meta entry for the discount
 *
 * @param int    $discount_id
 * @param string $meta_key
 * @param bool   $single
 *
 * @return mixed
 *
 */
function wpbs_get_discount_meta($discount_id, $meta_key = '', $single = false)
{

    return wp_booking_system()->db['discountmeta']->get($discount_id, $meta_key, $single);

}

/**
 * Returns the translated meta entry for the discount
 *
 * @param int    $discount_id
 * @param string $meta_key
 * @param string $language_code
 *
 * @return mixed
 *
 */
function wpbs_get_translated_discount_meta($discount_id, $meta_key, $language_code)
{
    $translated_meta = wpbs_get_discount_meta($discount_id, $meta_key . '_translation_' . $language_code, true);

    if (!empty($translated_meta)) {
        return $translated_meta;
    }

    return wpbs_get_discount_meta($discount_id, $meta_key, true);
}

/**
 * Removes a meta entry for the discount
 *
 * @param int    $discount_id
 * @param string $meta_key
 * @param string $meta_value
 * @param bool   $delete_all
 *
 * @return bool
 *
 */
function wpbs_delete_discount_meta($discount_id, $meta_key, $meta_value = '', $delete_all = '')
{

    return wp_booking_system()->db['discountmeta']->delete($discount_id, $meta_key, $meta_value, $delete_all);

}
