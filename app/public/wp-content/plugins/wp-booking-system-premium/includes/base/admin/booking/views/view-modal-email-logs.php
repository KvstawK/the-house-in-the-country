<?php $logs = wpbs_get_booking_meta($this->booking->get('id'), 'email_log');  ?>

<table>
    <tr>
        <th><?php echo __('Send Date', 'wp-booking-system') ?></th>
        <th><?php echo __('Email Type', 'wp-booking-system') ?></th>
        <th><?php echo __('Recepient', 'wp-booking-system') ?></th>
        <th><?php echo __('Message', 'wp-booking-system') ?></th>
    </tr>

    <?php if(count($logs) > 0): ?>
        <?php foreach($logs as $meta_id => $log): ?>

            <tr>
                <td>
                    <?php echo wpbs_date_i18n( get_option('date_format') . ' ' . get_option('time_format'), $log['send_date'] ) ?>
                </td>
                <td>
                    <?php 
                    $email_type_from = array('user', 'customer', 'accept_booking', 'reminder', 'followup', 'payment_success', 'payment',);
                    $email_type_to = array(
                        __('User Notification', 'wp-booking-system'),
                        __('Booking Custom Email', 'wp-booking-system'),
                        __('Booking Accept/Delete Email', 'wp-booking-system'),
                        __('Classic Reminder', 'wp-booking-system'),
                        __('Follow Up', 'wp-booking-system'),
                        __('Payment Success Notification', 'wp-booking-system'),
                        __('Payment Reminder', 'wp-booking-system'),
                    );
                    echo str_replace($email_type_from, $email_type_to, $log['email_type']);
                    ?>
                </td>
                <td><?php echo $log['send_to'] ?></td>
            
                <td><a target="_blank" href="<?php echo add_query_arg(array('page' => 'wpbs-calendars', 'wpbs_action' => 'email_logs', 'booking_id' => $this->booking->get('id'), 'email_log_id' => $meta_id, 'noheader' => 'true'), admin_url('admin.php'));?>"><?php echo __('View email body', 'wp-booking-system') ?></a></td>
            </tr>

        <?php endforeach; ?>
    <?php else: ?>
        <tr>
            <td colspan="4"><?php echo __('No emails sent yet.', 'wp-booking-system') ?></td>
        </tr>
    <?php endif; ?>
</table>