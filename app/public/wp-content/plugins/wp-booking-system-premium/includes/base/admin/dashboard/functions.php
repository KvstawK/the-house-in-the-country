<?php

// Exit if accessed directly
if (!defined('ABSPATH')) exit;

/**
 * Includes the files needed for the Dashboard admin area
 *
 */
function wpbs_include_files_admin_dashboard()
{

    // Get dir path.
    $dir_path = plugin_dir_path(__FILE__);

    // Include submenu page.
    if (file_exists($dir_path . 'class-submenu-page-dashboard.php'))
        include $dir_path . 'class-submenu-page-dashboard.php';

    // Include notifications function
    if (file_exists($dir_path . 'functions-notifications.php'))
        include $dir_path . 'functions-notifications.php';

    // Include all dashboard cards.
    $cards = scandir($dir_path . 'cards');

    foreach ($cards as $card) {

        if (false === strpos($card, '.php'))
            continue;

        include $dir_path . 'cards/' . $card;
    }
}
add_action('wpbs_include_files', 'wpbs_include_files_admin_dashboard');


/**
 * Register the Dashboard admin submenu page
 *
 */
function wpbs_register_submenu_page_dashboard($submenu_pages)
{

    if (!is_array($submenu_pages))
        return $submenu_pages;

    $notifications = wpbs_get_dashboard_notifications();

    $submenu_pages['dashboard'] = array(
        'class_name' => 'WPBS_Submenu_Page_Dashboard',
        'data'          => array(
            'page_title' => __('Dashboard', 'wp-booking-system'),
            'menu_title' => __('Dashboard', 'wp-booking-system') . (count($notifications) > 0 ? ' <span class="update-plugins wpbs-notifications-removable-count count-' . count($notifications) . '"><span class="plugin-count wpbs-notifications-count-circle">' . count($notifications) . '</span></span>' : ''),
            'capability' => apply_filters('wpbs_submenu_page_capability_dashboard', 'manage_options'),
            'menu_slug'  => 'wpbs-dashboard'
        )
    );

    return $submenu_pages;
}
add_filter('wpbs_register_submenu_page', 'wpbs_register_submenu_page_dashboard', 15);


/**
 * Initializez the dashboard cards.
 *
 */
function wpbs_initialize_dashboard_cards()
{

    // Built-in cards.
    $card_classes = array(
        'upcoming_bookings'  => 'WPBS_Admin_Dashboard_Card_Upcoming_Bookings',
        'help'               => 'WPBS_Admin_Dashboard_Card_Help',

        'quick_links'        => 'WPBS_Admin_Dashboard_Card_Quick_Links',
        'events'             => 'WPBS_Admin_Dashboard_Card_Events',
    );

    /**
     * Hook to register dashboard card handles.
     * The array element should be 'card_id' => 'class_name'
     *
     * @param array
     *
     */
    $card_classes = apply_filters('wpbs_register_dashboard_card', $card_classes);

    if (empty($card_classes))
        return;

    foreach ($card_classes as $card_class_slug => $card_class_name) {

        new $card_class_name;
    }
}
add_action('wpbs_view_dashboard_top', 'wpbs_initialize_dashboard_cards', 9);

/**
 * Get Notification Event type
 * 
 * @return string
 * 
 */
function wpbs_get_notification_event_type($event)
{
    switch ($event->get('type')) {
        case 'user_email_sent':
        case 'followup_email_sent':
        case 'reminder_email_sent':
        case 'payment_email_sent':
        case 'user_email_failed':
        case 'followup_email_failed':
        case 'reminder_email_failed':
        case 'payment_email_failed':
            $type = __('Email', 'wp-booking-system');
            break;
        case 'final_payment_received':
        case 'deposit_payment_received':
        case 'security_deposit_refunded':
        case 'security_deposit_failed':
        case 'payment_received':
        case 'payment_due':
        case 'woocommerce_payment_due':
        case 'payment_failed':
            $type = __('Payment', 'wp-booking-system');
            break;
        case 'new_booking_created':
            $type = __('Booking', 'wp-booking-system');
            break;
        case 'payment_sms_sent':
        case 'reminder_sms_sent':
        case 'followup_sms_sent':
        case 'payment_sms_failed':
        case 'reminder_sms_failed':
        case 'followup_sms_failed':
            $type = __('SMS', 'wp-booking-system');
            break;
        default:
            $type = __('Other', 'wp-booking-system');
    }
    return $type;
}

/**
 * Get Notification Event description
 * 
 * @return string
 * 
 */
function wpbs_get_notification_event_description($event, $booking)
{
    switch ($event->get('type')) {
        case 'user_email_sent':
            $message = __('User notification email sent.', 'wp-booking-system');
            break;
        case 'followup_email_sent':
            $message = __('Follow-up email sent.', 'wp-booking-system');
            break;
        case 'reminder_email_sent':
            $message = __('Reminder email sent.', 'wp-booking-system');
            break;
        case 'payment_email_sent':
            $message = __('Payment reminder email sent.', 'wp-booking-system');
            break;
        case 'user_email_failed':
            $message = __('User notification email failed to send.', 'wp-booking-system');
            break;
        case 'followup_email_failed':
            $message = __('Follow-up email failed to send.', 'wp-booking-system');
            break;
        case 'reminder_email_failed':
            $message = __('Reminder email failed to send.', 'wp-booking-system');
            break;
        case 'payment_email_failed':
            $message = __('Payment reminder email failed to send.', 'wp-booking-system');
            break;
        case 'payment_received':
            $payment = wpbs_get_payment_by_booking_id($booking->get('id'));
            $message = sprintf(__('Payment of %s was received.', 'wp-booking-system'), wpbs_get_formatted_price($payment->get_total(), $payment->get_currency()));
            break;
        case 'final_payment_received':
            $payment = wpbs_get_payment_by_booking_id($booking->get('id'));
            $message = sprintf(__('Final payment of %s was received.', 'wp-booking-system'), wpbs_get_formatted_price($payment->get_total_second_payment(), $payment->get_currency()));
            break;
        case 'deposit_payment_received':
            $payment = wpbs_get_payment_by_booking_id($booking->get('id'));
            $message = sprintf(__('Deposit payment of %s was received.', 'wp-booking-system'), wpbs_get_formatted_price($payment->get_total_first_payment(), $payment->get_currency()));
            break;
        case 'security_deposit_refunded':
            $message = __('Security deposit was refunded.', 'wp-booking-system');
            break;
        case 'security_deposit_failed':
            $message = __('Security deposit was not refunded when scheduled.', 'wp-booking-system');
            break;
        case 'new_booking_created':
            $message = __('New booking received.', 'wp-booking-system');
            break;
        case 'payment_sms_sent':
            $message = __('Payment reminder SMS sent.', 'wp-booking-system');
            break;
        case 'reminder_sms_sent':
            $message = __('Reminder SMS sent.', 'wp-booking-system');
            break;
        case 'followup_sms_sent':
            $message = __('Follow-up SMS sent.', 'wp-booking-system');
            break;
        case 'payment_sms_failed':
            $message = __('Payment reminder SMS failed to send.', 'wp-booking-system');
            break;
        case 'reminder_sms_failed':
            $message = __('Reminder SMS failed to send.', 'wp-booking-system');
            break;
        case 'followup_sms_failed':
            $message = __('Follow-up SMS failed to send.', 'wp-booking-system');
        case 'payment_failed':

            $payment = wpbs_get_payment_by_booking_id($booking->get('id'));
            if ($payment->is_part_payment()) {
                if (!$payment->is_deposit_paid()) {
                    $message = sprintf(__('Deposit payment of %s has failed.', 'wp-booking-system'), wpbs_get_formatted_price($payment->get_total_first_payment(), $payment->get_currency()));
                } else {
                    $message = sprintf(__('Final payment of %s has failed.', 'wp-booking-system'), wpbs_get_formatted_price($payment->get_total_second_payment(), $payment->get_currency()));
                }
            } else {
                $message = sprintf(__('Payment of %s has failed', 'wp-booking-system'), wpbs_get_formatted_price($payment->get_total(), $payment->get_currency()));
            }
            break;
        case 'payment_due':
            $message = __('Final payment is due.', 'wp-booking-system');
            break;
        case 'woocommerce_payment_due':
            $payment = wpbs_get_payment_by_booking_id($booking->get('id'));
            $message = sprintf(__("Payment for WooCommerce order %s missing.", 'wp-booking-system'), '<a href="' . add_query_arg(['post' => $payment->get('order_id'), 'action' => 'edit'], admin_url('post.php')) . '" target="_blank">#' . $payment->get('order_id') . '</a>');
            break;

        default:
            $message = $event->get('type');
    }
    return $message;
}


/**
 * Handles the "dismiss notification" ajax action
 *
 */
function wpbs_action_ajax_dismiss_notification()
{

    // Exit if the token is not present
    if (empty($_POST['token']) || !wp_verify_nonce($_POST['token'], 'wpbs_dashboard_actions')) {
        wp_die();
    }

    // Exit if the calendar id is not present
    if (empty($_POST['notification_id'])) {
        wp_die();
    }

    wpbs_update_notification(absint($_POST['notification_id']), array(
        'notification_status' => 'dismissed'
    ));

    wp_die();
}
add_action('wp_ajax_wpbs_dismiss_notification', 'wpbs_action_ajax_dismiss_notification');

/**
 * Handles the "dismiss all notifications" ajax action
 *
 */
function wpbs_action_ajax_dismiss_all_notifications()
{

    // Exit if the token is not present
    if (empty($_POST['token']) || !wp_verify_nonce($_POST['token'], 'wpbs_dashboard_actions')) {
        wp_die();
    }

    // Exit if the calendar id is not present
    if (empty($_POST['group'])) {
        wp_die();
    }

    if (!in_array($_POST['group'], ['event', 'notification'])) {
        wp_die();
    }

    global $wpdb;

    $wpdb->query($wpdb->prepare("UPDATE {$wpdb->prefix}wpbs_notifications SET notification_status = 'dismissed' WHERE `notification_group` = %s", $_POST['group']));

    wp_die();
}
add_action('wp_ajax_wpbs_dismiss_all_notifications', 'wpbs_action_ajax_dismiss_all_notifications');
