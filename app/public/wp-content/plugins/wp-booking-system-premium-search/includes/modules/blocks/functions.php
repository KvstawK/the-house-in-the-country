<?php

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Adds the needed JavaScript variables up in the WordPress admin head
 *
 */
function wpbs_s_add_javascript_variables()
{

    if (!function_exists('get_current_screen')) {
        return;
    }

    $screen = get_current_screen();

    if (is_null($screen)) {
        return;
    }

    wp_register_script('wpbs_s-script', WPBS_S_PLUGIN_DIR_URL . 'assets/js/script-front-end.min.js', array('jquery', 'jquery-ui-datepicker'), WPBS_S_VERSION, true);
    wp_localize_script('wpbs_s-script', 'wpbs_s_localized_data', array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'search_form_nonce' => wp_create_nonce('wpbs_s_search_form'),
    ));
    wp_enqueue_script('wpbs_s-script');

    wp_register_style('wpbs_s-style', WPBS_S_PLUGIN_DIR_URL . 'assets/css/style-front-end.min.css', array(), WPBS_S_VERSION);
    wp_enqueue_style('wpbs_s-style');

}
add_action('admin_enqueue_scripts', 'wpbs_s_add_javascript_variables', 10);
