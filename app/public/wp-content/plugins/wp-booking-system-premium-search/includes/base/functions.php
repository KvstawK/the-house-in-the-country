<?php

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Includes the Base files
 *
 */
function wpbs_s_include_files_base()
{

    // Get legend dir path
    $dir_path = plugin_dir_path(__FILE__);

    // Include the shortcodes class
    if (file_exists($dir_path . 'class-shortcode.php')) {
        include $dir_path . 'class-shortcode.php';
    }

    // Include the widget class
    if (file_exists($dir_path . 'class-widget-search.php')) {
        include $dir_path . 'class-widget-search.php';
    }

}
add_action('wpbs_s_include_files', 'wpbs_s_include_files_base');
