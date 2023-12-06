<?php

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Check whether the Inventory Add-on is active or now.
 *
 */
function wpbs_is_inventory_enabled()
{
    if (in_array('wp-booking-system-premium-inventory/index.php', apply_filters('active_plugins', get_option('active_plugins')))) {
        return true;
    }
    return false;
}
