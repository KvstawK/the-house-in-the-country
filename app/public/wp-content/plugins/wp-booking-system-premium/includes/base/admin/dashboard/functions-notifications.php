<?php

// Exit if accessed directly
if (!defined('ABSPATH')) exit;


/**
 * Get important notifications
 * 
 * @return array
 * 
 */
function wpbs_get_dashboard_notifications()
{
    global $wpdb;

    $notifications = [];

    // Get due part payments
    $booking_ids = $wpdb->get_results("SELECT bookings.id, bookings.status FROM {$wpdb->prefix}wpbs_bookings bookings INNER JOIN {$wpdb->prefix}wpbs_booking_meta booking_meta ON booking_meta.booking_id = bookings.id WHERE meta_key='final_payment_email_sent' AND meta_value='1' AND bookings.status != 'trash'");

    foreach ($booking_ids as $booking) {
        $booking_id = $booking->id;
        $payment = wpbs_get_payment_by_booking_id($booking_id);
        if (!$payment->is_final_payment_paid()) {

            $notification_date = wpbs_get_booking_meta($booking_id, 'final_payment_email_sent_date', true);

            if (current_time('Ymd') > date('Ymd', $notification_date)) {
                $notifications[] = wpbs_get_notification((object) [
                    'booking_id' => $booking_id,
                    'notification_type' => 'payment_due',
                    'notification_status' => 'active',
                    'notification_group' => 'notification',
                    'date_created' => wp_date('Y-m-d H:i:s', $notification_date),
                    'dismissable' => false
                ]);
            }
        }
    }

    // Get WooCommerce unfinished payments
    $payments = wpbs_get_payments(array('gateway' => 'woocommerce', 'order_status' => 'pending'));
    foreach ($payments as $payment) {
        $booking_id = $payment->get('booking_id');
        $booking = wpbs_get_booking($booking_id);

        if (!$booking) {
            continue;
        }

        if (current_time('Ymd') > date('Ymd', strtotime($booking->get('end_date')))) {
            continue;
        }

        $notifications[] = wpbs_get_notification((object) [
            'booking_id' => $booking_id,
            'notification_type' => 'woocommerce_payment_due',
            'notification_status' => 'active',
            'notification_group' => 'notification',
            'date_created' => wp_date('Y-m-d H:i:s', strtotime($payment->get('date_created'))),
            'dismissable' => false
        ]);
    }

    $notifications = array_merge($notifications, wpbs_get_notifications(['group' => 'notification', 'status' => 'active']));

    usort($notifications, function ($a, $b) {
        return strcmp($b->get('date_created'), $a->get('date_created'));
    });

    return $notifications;
}

/**
 * Get the link to the booking modal
 * 
 */
function wpbs_dashboard_get_booking_href($notification, $booking)
{
    $notification_type = wpbs_get_notification_event_type($notification);

    $query_args = array(
        'page' => 'wpbs-calendars',
        'subpage' => 'edit-calendar',
        'calendar_id' => $booking->get('calendar_id'),
        'booking_id' => $booking->get('id')
    );

    if (strtolower($notification_type) == 'email') {
        $query_args['tab'] = 'email-logs';
    }

    if (strtolower($notification_type) == 'payment') {
        $query_args['tab'] = 'payment-details';
    }

    if (strtolower($notification_type) == 'sms') {
        $query_args['tab'] = 'sms';
    }

    return add_query_arg($query_args, admin_url('admin.php'));
}
