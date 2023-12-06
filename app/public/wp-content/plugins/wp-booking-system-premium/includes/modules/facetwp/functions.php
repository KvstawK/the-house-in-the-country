<?php

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Includes the Base files
 *
 */
function wpbs_include_files_facet_wp()
{
    // Get legend dir path
    $dir_path = plugin_dir_path(__FILE__);

    // Include the FacetWP filters class
    if (in_array('facetwp/index.php', apply_filters('active_plugins', get_option('active_plugins'))) && file_exists($dir_path . 'functions-integration-facet-wp.php')) {
        include $dir_path . 'functions-integration-facet-wp.php';
    }
}
add_action('wpbs_include_files', 'wpbs_include_files_facet_wp');
