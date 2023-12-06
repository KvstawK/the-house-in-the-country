<?php

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}


/**
 * Modifies the permisions for the dashboard submenu page
 *
 * @param string $capability
 *
 * @return string
 *
 */
function wpbs_set_dashboard_submenu_page_capabilities($capability = 'manage_options')
{

    if (current_user_can('manage_options')) {
        return 'manage_options';
    }

    if (wpbs_current_user_can_edit_plugin()) {
        return 'read';
    }

    return $capability;

}
add_filter('wpbs_submenu_page_capability_dashboard', 'wpbs_set_dashboard_submenu_page_capabilities');