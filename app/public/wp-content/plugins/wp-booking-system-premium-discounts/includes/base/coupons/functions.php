<?php

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Includes the files needed for the coupons
 *
 */
function wpbs_d_include_files_coupon()
{

    // Get coupon dir path
    $dir_path = plugin_dir_path(__FILE__);

    // Include main coupon class
    if (file_exists($dir_path . 'class-coupon.php')) {
        include $dir_path . 'class-coupon.php';
    }

    // Include the db layer classes
    if (file_exists($dir_path . 'class-object-db-coupons.php')) {
        include $dir_path . 'class-object-db-coupons.php';
    }

    if (file_exists($dir_path . 'class-object-meta-db-coupons.php')) {
        include $dir_path . 'class-object-meta-db-coupons.php';
    }

    // Include coupon related functions
    if (file_exists($dir_path . 'functions-coupons.php')) {
        include $dir_path . 'functions-coupons.php';
    }


}
add_action('wpbs_d_include_files', 'wpbs_d_include_files_coupon');

/**
 * Register the class that handles database queries for the coupons
 *
 * @param array $classes
 *
 * @return array
 *
 */
function wpbs_d_register_database_classes_coupons($classes)
{

    $classes['coupons'] = 'WPBS_Object_DB_Coupons';
    $classes['couponmeta'] = 'WPBS_Object_Meta_DB_Coupons';

    return $classes;

}
add_filter('wpbs_register_database_classes', 'wpbs_d_register_database_classes_coupons');

/**
 * Returns an array with WPBS_Coupon objects from the database
 *
 * @param array $args
 * @param bool  $count
 *
 * @return array
 *
 */
function wpbs_get_coupons($args = array(), $count = false)
{

    $coupons = wp_booking_system()->db['coupons']->get_coupons($args, $count);

    /**
     * Add a filter hook just before returning
     *
     * @param array $coupons
     * @param array $args
     * @param bool  $count
     *
     */
    return apply_filters('wpbs_get_coupons', $coupons, $args, $count);

}

/**
 * Gets a coupon from the database
 *
 * @param mixed int|object      - coupon id or object representing the coupon
 *
 * @return WPBS_Coupon|false
 *
 */
function wpbs_get_coupon($coupon)
{

    return wp_booking_system()->db['coupons']->get_object($coupon);

}

/**
 * Inserts a new coupon into the database
 *
 * @param array $data
 *
 * @return mixed int|false
 *
 */
function wpbs_insert_coupon($data)
{

    return wp_booking_system()->db['coupons']->insert($data);

}

/**
 * Updates a coupon from the database
 *
 * @param int     $coupon_id
 * @param array $data
 *
 * @return bool
 *
 */
function wpbs_update_coupon($coupon_id, $data)
{

    return wp_booking_system()->db['coupons']->update($coupon_id, $data);

}

/**
 * Deletes a coupon from the database
 *
 * @param int $coupon_id
 *
 * @return bool
 *
 */
function wpbs_delete_coupon($coupon_id)
{

    return wp_booking_system()->db['coupons']->delete($coupon_id);

}

/**
 * Inserts a new meta entry for the coupon
 *
 * @param int    $coupon_id
 * @param string $meta_key
 * @param string $meta_value
 * @param bool   $unique
 *
 * @return mixed int|false
 *
 */
function wpbs_add_coupon_meta($coupon_id, $meta_key, $meta_value, $unique = false)
{

    return wp_booking_system()->db['couponmeta']->add($coupon_id, $meta_key, $meta_value, $unique);

}

/**
 * Updates a meta entry for the coupon
 *
 * @param int    $coupon_id
 * @param string $meta_key
 * @param string $meta_value
 * @param bool   $prev_value
 *
 * @return bool
 *
 */
function wpbs_update_coupon_meta($coupon_id, $meta_key, $meta_value, $prev_value = '')
{

    return wp_booking_system()->db['couponmeta']->update($coupon_id, $meta_key, $meta_value, $prev_value);

}

/**
 * Returns a meta entry for the coupon
 *
 * @param int    $coupon_id
 * @param string $meta_key
 * @param bool   $single
 *
 * @return mixed
 *
 */
function wpbs_get_coupon_meta($coupon_id, $meta_key = '', $single = false)
{

    return wp_booking_system()->db['couponmeta']->get($coupon_id, $meta_key, $single);

}

/**
 * Returns the translated meta entry for the coupon
 *
 * @param int    $coupon_id
 * @param string $meta_key
 * @param string $language_code
 *
 * @return mixed
 *
 */
function wpbs_get_translated_coupon_meta($coupon_id, $meta_key, $language_code)
{
    $translated_meta = wpbs_get_coupon_meta($coupon_id, $meta_key . '_translation_' . $language_code, true);

    if (!empty($translated_meta)) {
        return $translated_meta;
    }

    return wpbs_get_coupon_meta($coupon_id, $meta_key, true);
}

/**
 * Removes a meta entry for the coupon
 *
 * @param int    $coupon_id
 * @param string $meta_key
 * @param string $meta_value
 * @param bool   $delete_all
 *
 * @return bool
 *
 */
function wpbs_delete_coupon_meta($coupon_id, $meta_key, $meta_value = '', $delete_all = '')
{

    return wp_booking_system()->db['couponmeta']->delete($coupon_id, $meta_key, $meta_value, $delete_all);

}
