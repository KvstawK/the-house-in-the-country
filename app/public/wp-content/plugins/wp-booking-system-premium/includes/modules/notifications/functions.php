<?php

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Includes the Base files
 *
 */
function wpbs_include_files_notification()
{

    // Get legend dir path
    $dir_path = plugin_dir_path(__FILE__);

    // Include notification object class
    if (file_exists($dir_path . 'class-notification.php')) {
        include $dir_path . 'class-notification.php';
    }

    // Include notification object db class
    if (file_exists($dir_path . 'class-object-db-notifications.php')) {
        include $dir_path . 'class-object-db-notifications.php';
    }

    // Include notification logger
    if (file_exists($dir_path . 'functions-event-logger.php')) {
        include $dir_path . 'functions-event-logger.php';
    }

    // Include notification logger
    if (file_exists($dir_path . 'functions-notifications.php')) {
        include $dir_path . 'functions-notifications.php';
    }

}
add_action('wpbs_include_files', 'wpbs_include_files_notification');

/**
 * Register the class that handles database queries for the notifications
 *
 * @param array $classes
 *
 * @return array
 *
 */
function wpbs_register_database_classes_notifications($classes)
{

    $classes['notifications'] = 'WPBS_Object_DB_Notifications';

    return $classes;

}
add_filter('wpbs_register_database_classes', 'wpbs_register_database_classes_notifications');

/**
 * Returns an array with WPBS_Notification objects from the database
 *
 * @param array $args
 * @param bool  $count
 *
 * @return array
 *
 */
function wpbs_get_notifications($args = array(), $count = false)
{

    $notifications = wp_booking_system()->db['notifications']->get_notifications($args, $count);

    /**
     * Add a filter hook just before returning
     *
     * @param array $notifications
     * @param array $args
     * @param bool  $count
     *
     */
    return apply_filters('wpbs_get_notifications', $notifications, $args, $count);

}

/**
 * Gets a notification from the database
 *
 * @param mixed int|object      - notification id or object representing the notification
 *
 * @return WPBS_Notification|false
 *
 */
function wpbs_get_notification($notification)
{

    return wp_booking_system()->db['notifications']->get_object($notification);

}

/**
 * Inserts a new notification into the database
 *
 * @param array $data
 *
 * @return mixed int|false
 *
 */
function wpbs_insert_notification($data)
{

    return wp_booking_system()->db['notifications']->insert($data);

}

/**
 * Updates a notification from the database
 *
 * @param int     $notification_id
 * @param array $data
 *
 * @return bool
 *
 */
function wpbs_update_notification($notification_id, $data)
{

    return wp_booking_system()->db['notifications']->update($notification_id, $data);

}

/**
 * Deletes a notification from the database
 *
 * @param int $notification_id
 *
 * @return bool
 *
 */
function wpbs_delete_notification($notification_id)
{

    return wp_booking_system()->db['notifications']->delete($notification_id);

}
