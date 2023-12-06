<?php

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Installs and activates an Add-on
 *
 */
function wpbs_action_install_addon()
{

    // Verify for nonce
    if (empty($_GET['wpbs_token']) || !wp_verify_nonce($_GET['wpbs_token'], 'wpbs_install_addon')) {
        return;
    }

    if (!isset($_GET['addon']) || empty($_GET['addon'])) {
        return;
    }

    $serial_key = get_option('wpbs_serial_key', '');

    if (empty($serial_key)) {
        return;
    }

    $addon_slug = sanitize_text_field($_GET['addon']);

    $addon = wp_remote_get('https://www.wpbookingsystem.com/u/', array(
        'body' => array('request' => 'get_addon', 'serial_key' => $serial_key, 'addon' => $addon_slug),
    ));

    $addon = json_decode($addon['body']);

    if (isset($addon->error)) {
        wpbs_admin_notices()->register_notice('addon_install_error', '<p>Error installing add-on: ' . $addon->error . '</p>', 'error');
        wpbs_admin_notices()->display_notice('addon_install_error');
        return;
    }

    //includes necessary for Plugin_Upgrader and Plugin_Installer_Skin
    include_once ABSPATH . 'wp-admin/includes/file.php';
    include_once ABSPATH . 'wp-admin/includes/misc.php';
    include_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';

    class WPBS_Custom_Plugin_Installer_Skin extends \WP_Upgrader_Skin
    {
        public function feedback($string, ...$args)
        {
            // Please
        }
        public function header()
        {
            // Keep
        }
        public function footer()
        {
            // Quiet.
        }
        public function request_filesystem_credentials($error = false, $context = '', $allow_relaxed_file_ownership = false)
        {
            // Thanks
        }
    }

    // Start the upgrader
    $installer = new Plugin_Upgrader(new WPBS_Custom_Plugin_Installer_Skin);
    $installer_result = $installer->install($addon->attachment_url);

    // Check for errors.
    if (isset($installer->skin->result->errors)) {
        wpbs_admin_notices()->register_notice('addon_install_error', '<p>Error installing add-on: ' . array_shift($installer->skin->result->errors)[0] . '</p>', 'error');
        wpbs_admin_notices()->display_notice('addon_install_error');
        return;
    }

    // Check for output.
    if (!$installer_result) {
        wpbs_admin_notices()->register_notice('addon_install_error', '<p>Error installing add-on: Please ensure the file system is writeable.</p>', 'error');
        wpbs_admin_notices()->display_notice('addon_install_error');
        return;
    }

    // If no errors found, activate the plugin
    activate_plugin($addon_slug . '/index.php');

    // And redirect
    wp_redirect(add_query_arg(array('page' => 'wpbs-addons', 'wpbs_message' => 'addon_installed_successfully'), admin_url('admin.php')));
    exit;

}
add_action('wpbs_action_install_addon', 'wpbs_action_install_addon');

/**
 * Activate an addon
 *
 */
function wpbs_action_activate_addon()
{

    // Verify for nonce
    if (empty($_GET['wpbs_token']) || !wp_verify_nonce($_GET['wpbs_token'], 'wpbs_activate_addon')) {
        return;
    }

    if (!isset($_GET['addon']) || empty($_GET['addon'])) {
        return;
    }

    $addon_slug = sanitize_text_field($_GET['addon']);

    activate_plugin($addon_slug . '/index.php');

    wp_redirect(add_query_arg(array('page' => 'wpbs-addons', 'wpbs_message' => 'addon_activated_successfully'), admin_url('admin.php')));
    exit;

}
add_action('wpbs_action_activate_addon', 'wpbs_action_activate_addon');
