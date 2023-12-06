<?php

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

class WPBS_Submenu_Page_Addons extends WPBS_Submenu_Page
{

    /**
     * Helper init method that runs on parent __construct
     *
     */
    protected function init()
    {
        add_action('admin_init', array($this, 'register_admin_notices'), 10);
    }

    /**
     * Callback method to register admin notices that are sent via URL parameters
     *
     */
    public function register_admin_notices()
    {

        if (empty($_GET['wpbs_message'])) {
            return;
        }

        // Add-on Installed
        wpbs_admin_notices()->register_notice('addon_installed_successfully', '<p>' . __('Add-on successfully installed.', 'wp-booking-system') . '</p>');

        // Add-on Actiated
        wpbs_admin_notices()->register_notice('addon_activated_successfully', '<p>' . __('Add-on successfully activated.', 'wp-booking-system') . '</p>');

    }

    /**
     * Callback for the HTML output for the Add-on page
     *
     */
    public function output()
    {

        $subscription_type = str_replace('unlimited', 'developer', get_transient('wpbs_subscription_type'));

        $addons = wpbs_get_addons_list();
        

        include 'views/view-addons.php';

    }

}
