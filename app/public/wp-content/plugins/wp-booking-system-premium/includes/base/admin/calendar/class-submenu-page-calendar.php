<?php

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

class WPBS_Submenu_Page_Calendars extends WPBS_Submenu_Page
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

        // Calendar insert success
        wpbs_admin_notices()->register_notice('calendar_insert_success', '<p>' . __('Calendar created successfully.', 'wp-booking-system') . '</p>');

        // Calendar updated successfully
        wpbs_admin_notices()->register_notice('calendar_update_success', '<p>' . __('Calendar updated successfully.', 'wp-booking-system') . '</p>');

        // Calendar updated fail
        wpbs_admin_notices()->register_notice('calendar_update_fail', '<p>' . __('Something went wrong. Could not update the calendar.', 'wp-booking-system') . '</p>', 'error');

        // Calendar trash success
        wpbs_admin_notices()->register_notice('calendar_trash_success', '<p>' . __('Calendar successfully moved to Trash.', 'wp-booking-system') . '</p>');

        // Calendar restore success
        wpbs_admin_notices()->register_notice('calendar_restore_success', '<p>' . __('Calendar has been successfully restored.', 'wp-booking-system') . '</p>');

        // Calendar delete success
        wpbs_admin_notices()->register_notice('calendar_delete_success', '<p>' . __('Calendar has been successfully deleted.', 'wp-booking-system') . '</p>');

        // Legend item insert success
        wpbs_admin_notices()->register_notice('legend_item_insert_success', '<p>' . __('Legend item created successfully.', 'wp-booking-system') . '</p>');

        // Legend item update success
        wpbs_admin_notices()->register_notice('legend_item_update_success', '<p>' . __('Legend item updated successfully.', 'wp-booking-system') . '</p>');

        // Legend item delete success
        wpbs_admin_notices()->register_notice('legend_item_delete_success', '<p>' . __('Legend item deleted successfully.', 'wp-booking-system') . '</p>');

        // Legend items sort fail
        wpbs_admin_notices()->register_notice('sort_legend_items_fail', '<p>' . __('Something went wrong. Could not sort the legend items.', 'wp-booking-system') . '</p>', 'error');

        // Legend item make visible/invisible
        if ($_GET['wpbs_message'] == 'legend_item_make_visible_success' || $_GET['wpbs_message'] == 'legend_item_make_invisible_success') {

            $legend_item_id = absint($_GET['legend_item_id']);
            $legend_item = wpbs_get_legend_item($legend_item_id);

            wpbs_admin_notices()->register_notice('legend_item_make_visible_success', '<p>' . sprintf(__('Legend item %s is now visible.', 'wp-booking-system'), '<strong>' . $legend_item->get('name') . '</strong>') . '</p>');
            wpbs_admin_notices()->register_notice('legend_item_make_invisible_success', '<p>' . sprintf(__('Legend item %s is now hidden.', 'wp-booking-system'), '<strong>' . $legend_item->get('name') . '</strong>') . '</p>');

        }

        // Legend item make bookable/unbookable
        if ($_GET['wpbs_message'] == 'legend_item_make_bookable_success' || $_GET['wpbs_message'] == 'legend_item_make_unbookable_success') {

            $legend_item_id = absint($_GET['legend_item_id']);
            $legend_item = wpbs_get_legend_item($legend_item_id);

            wpbs_admin_notices()->register_notice('legend_item_make_bookable_success', '<p>' . sprintf(__('Legend item %s is now bookable.', 'wp-booking-system'), '<strong>' . $legend_item->get('name') . '</strong>') . '</p>');
            wpbs_admin_notices()->register_notice('legend_item_make_unbookable_success', '<p>' . sprintf(__('Legend item %s is no longer bookable.', 'wp-booking-system'), '<strong>' . $legend_item->get('name') . '</strong>') . '</p>');

        }

        // Legend item "change auto accept as"
        if ($_GET['wpbs_message'] == 'legend_item_change_auto_pending_success') {

            $legend_item_id = absint($_GET['legend_item_id']);
            $legend_item = wpbs_get_legend_item($legend_item_id);

            wpbs_admin_notices()->register_notice('legend_item_change_auto_pending_success', '<p>' . sprintf(__('Auto Accept for %s was updated.', 'wp-booking-system'), '<strong>' . $legend_item->get('name') . '</strong>') . '</p>');

        }

        // Legend item make default
        if ($_GET['wpbs_message'] == 'legend_item_make_default_success') {

            $legend_item_id = absint($_GET['legend_item_id']);
            $legend_item = wpbs_get_legend_item($legend_item_id);

            wpbs_admin_notices()->register_notice('legend_item_make_default_success', '<p>' . sprintf(__('%s legend item is now the default one.', 'wp-booking-system'), '<strong>' . $legend_item->get('name') . '</strong>') . '</p>');

        }

        // iCal link reset successful
        wpbs_admin_notices()->register_notice('ical_reset_private_link_success', '<p>' . __('iCalendar private link reset successful.', 'wp-booking-system') . '</p>');

        // iCal save preferences successfully
        wpbs_admin_notices()->register_notice('ical_save_preferences_success', '<p>' . __('Preferences saved successfully.', 'wp-booking-system') . '</p>');

        // iCal imported file successfully
        wpbs_admin_notices()->register_notice('ical_import_file_success', '<p>' . __('iCal events imported successfully into the calendar.', 'wp-booking-system') . '</p>');

        // iCal imported url successfully
        wpbs_admin_notices()->register_notice('ical_import_url_success', '<p>' . __('iCal feed URL added successfully.', 'wp-booking-system') . '</p>');

        // iCal imported url removed successfully
        wpbs_admin_notices()->register_notice('ical_import_url_remove_success', '<p>' . __('iCal feed URL removed successfully.', 'wp-booking-system') . '</p>');

        // iCal refresh ical feeds successfully
        wpbs_admin_notices()->register_notice('ical_import_url_refresh_success', '<p>' . __('iCal feeds refreshed successfully.', 'wp-booking-system') . '</p>');

        // Booking Permanently Deleted
        wpbs_admin_notices()->register_notice('booking_permanently_delete_success', '<p>' . __('Booking successfully deleted.', 'wp-booking-system') . '</p>');
       
        // Bookings Permanently Deleted
        wpbs_admin_notices()->register_notice('bookings_permanently_delete_success', '<p>' . __('Bookings successfully deleted.', 'wp-booking-system') . '</p>');
        
        // Booking Moved
        wpbs_admin_notices()->register_notice('booking_moved_success', '<p>' . __('Booking successfully moved.', 'wp-booking-system') . '</p>');

        // Calendar duplicate success
		wpbs_admin_notices()->register_notice( 'calendar_duplicate_success', '<p>' . __( 'Calendar has been successfully duplicated.', 'wp-booking-system' ) . '</p>' );

        // PriceLabs refresh successful
        wpbs_admin_notices()->register_notice('pricelabs_refresh_success', '<p>' . __('PriceLabs data refreshed successfully.', 'wp-booking-system') . '</p>');

    }

    /**
     * Callback for the HTML output for the Calendar page
     *
     */
    public function output()
    {

        if (empty($this->current_subpage)) {
            include 'views/view-calendars.php';
        } else {

            if ($this->current_subpage == 'add-calendar') {
                include 'views/view-add-calendar.php';
            }

            if ($this->current_subpage == 'edit-calendar') {
                include 'views/view-edit-calendar.php';
            }

            if ($this->current_subpage == 'view-legend') {
                include 'views/view-legend.php';
            }

            if ($this->current_subpage == 'add-legend-item') {
                include 'views/view-add-edit-legend-item.php';
            }

            if ($this->current_subpage == 'edit-legend-item') {
                include 'views/view-add-edit-legend-item.php';
            }

            if ($this->current_subpage == 'ical-import-export') {
                include 'views/view-ical-import-export.php';
            }

            if ($this->current_subpage == 'csv-export') {
                include 'views/view-csv-export.php';
            }

        }

    }

}
