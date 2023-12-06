<?php

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Includes the files needed for the Booking admin area
 *
 */
function wpbs_include_files_admin_booking_restrictions()
{

    // Get legend admin dir path
    $dir_path = plugin_dir_path(__FILE__);

    // Include the booking restrictions functions
    if (file_exists($dir_path . 'functions-booking-restrictions.php')) {
        include $dir_path . 'functions-booking-restrictions.php';
    }

    // Include the booking restrictions class
    if (file_exists($dir_path . 'class-form-validator-booking-restrictions.php')) {
        include $dir_path . 'class-form-validator-booking-restrictions.php';
    }

}
add_action('wpbs_br_include_files', 'wpbs_include_files_admin_booking_restrictions');

/**
 * Join array elements separated by "," and the last separator "or"
 *
 */
function wpbs_natural_language_join($list, $conjunction = 'or')
{
    if (!is_array($list)) {
        return $list;
    }
    $last = array_pop($list);
    if ($list) {
        return implode(', ', $list) . ' ' . $conjunction . ' ' . $last;
    }
    return $last;
}