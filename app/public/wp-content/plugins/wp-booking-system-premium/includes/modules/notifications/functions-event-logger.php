<?php

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Log emails
 * 
 */
add_action('wpbs_mailer_end', function($mailer){

    if(!in_array($mailer->type, ['user', 'payment', 'reminder' , 'followup'])){
        return false;
    }

    wpbs_insert_notification([
        'notification_group' => 'event',
        'booking_id' => $mailer->booking->get('id'),
        'notification_type' => $mailer->type . '_email_sent',
        'notification_status' => 'active',
        'date_created' => date('Y-m-d H:i:s', current_time('timestamp'))
    ]);
});

/**
 * Log automatic refund of security deposit
 * 
 */
add_action('wpbs_security_deposit_automatically_refunded', function($booking_id){

    $payment = wpbs_get_payment_by_booking_id($booking_id);
    $details = $payment->get('details');
    
    if($details['security_deposit']['status'] == 'error'){
        wpbs_insert_notification([
            'notification_group' => 'notification',
            'booking_id' => $booking_id,
            'notification_type' => 'security_deposit_failed',
            'notification_status' => 'active',
            'date_created' => date('Y-m-d H:i:s', current_time('timestamp'))
        ]);

    } else {
        wpbs_insert_notification([
            'notification_group' => 'event',
            'booking_id' => $booking_id,
            'notification_type' => 'security_deposit_refunded',
            'notification_status' => 'active',
            'date_created' => date('Y-m-d H:i:s', current_time('timestamp'))
        ]);
    }

});

/**
 * Log SMS notifications
 * 
 */
add_action('wpbs_sms_notification_sent', function($booking_id, $type, $response){

    if(!in_array($type, ['payment', 'reminder' , 'followup'])){
        return false;
    }

    $status = isset($response['success']) && $response['success'] ? 'sent' : 'failed';
    $group = isset($response['success']) && $response['success'] ? 'event' : 'notification';

    wpbs_insert_notification([
        'notification_group' => $group,
        'booking_id' => $booking_id,
        'notification_type' => $type . '_sms_' . $status,
        'notification_status' => 'active',
        'date_created' => date('Y-m-d H:i:s', current_time('timestamp'))
    ]);
}, 10, 3);


/**
 * Log final payment received
 * 
 */
add_action('wpbs_save_final_payment', function($payment){

    wpbs_insert_notification([
        'notification_group' => 'event',
        'booking_id' => $payment->get('booking_id'),
        'notification_type' => 'final_payment_received',
        'notification_status' => 'active',
        'date_created' => date('Y-m-d H:i:s', current_time('timestamp'))
    ]);
    
});

/**
 * Log email error
 * 
 */
add_action('wpbs_mailer_email_failed_to_send', function($mailer){

    if(!in_array($mailer->type, ['user', 'payment', 'reminder' , 'followup'])){
        return false;
    }

    wpbs_insert_notification([
        'notification_group' => 'notification',
        'booking_id' => $mailer->booking->get('id'),
        'notification_type' => $mailer->type . '_email_failed',
        'notification_status' => 'active',
        'date_created' => date('Y-m-d H:i:s', current_time('timestamp'))
    ]);
    
});


/**
 * Log payment received
 * 
 */
add_action('wpbs_submit_form_after', function($booking_id){

    wpbs_insert_notification([
        'notification_group' => 'event',
        'booking_id' => $booking_id,
        'notification_type' => 'new_booking_created',
        'notification_status' => 'active',
        'date_created' => date('Y-m-d H:i:s', current_time('timestamp'))
    ]);
    
    $payment = wpbs_get_payment_by_booking_id($booking_id);

    if(!$payment){
        return false;
    }

    if($payment->get('order_id') == '-'){
        return false;
    }


    if($payment->get('order_status') == 'error'){
        wpbs_insert_notification([
            'notification_group' => 'notification',
            'booking_id' => $booking_id,
            'notification_type' => 'payment_failed',
            'notification_status' => 'active',
            'date_created' => date('Y-m-d H:i:s', current_time('timestamp'))
        ]);

        return false;
    }

    if($payment->get('order_status') == 'completed'){

        $type = ($payment->is_part_payment()) ? 'deposit_payment_received' : 'payment_received';

        if ($type == 'deposit_payment_received' && wpbs_get_booking_meta($booking_id, 'manual_booking', true)) {
            return false;
        }

        wpbs_insert_notification([
            'notification_group' => 'event',
            'booking_id' => $booking_id,
            'notification_type' => $type,
            'notification_status' => 'active',
            'date_created' => date('Y-m-d H:i:s', current_time('timestamp'))
        ]);

        return false;
    }

}, 99, 5);