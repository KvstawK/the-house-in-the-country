<?php

// Exit if accessed directly
if (!defined('ABSPATH')) exit;


/**
 * Dashboard card: Events
 *
 */
class WPBS_Admin_Dashboard_Card_Events extends WPBS_Admin_Dashboard_Card
{

    /**
     * Initialize the card.
     *
     */
    protected function init()
    {

        $this->slug    = 'wpbs_card_events';
        $this->name    = __('Events', 'wp-booking-system');
        $this->context = 'secondary';
    }

    /**
     * Output the card's content.
     *
     */
    public function output()
    {

        $notifications_count = wpbs_get_notifications(['group' => 'event', 'status' => 'active'], true);
        $number = isset($_GET['show-all-events']) ? -1 : 15;
        $notifications = wpbs_get_notifications(['group' => 'event', 'status' => 'active', 'number' => $number]); ?>

        <?php if (count($notifications) > 0) : ?>

            <a href="#" class="wpbs-event-dismiss-all" data-group="event"><?php _e('dismiss all', 'wp-booking-system') ?></a>
            <table class="wpbs-card-table-full-width">

                <thead>
                    <tr>
                        <th class="wpbs-column wpbs-column-type"><?php echo __('Type', 'wp-booking-system'); ?></th>
                        <th class="wpbs-column wpbs-column-booking-id"><?php echo __('Booking', 'wp-booking-system'); ?></th>
                        <th class="wpbs-column"><?php echo __('Event', 'wp-booking-system'); ?></th>
                        <th class="wpbs-column"><?php echo __('Date', 'wp-booking-system'); ?></th>
                        <th class="wpbs-column wpbs-column-dismiss"></th>
                    </tr>
                </thead>

                <tbody>
                    <?php foreach ($notifications as $notification) :
                        $booking = wpbs_get_booking($notification->get('booking_id'));
                        if (!$booking) {
                            continue;
                        }
                    ?>
                        <tr>
                            <td class="wpbs-column-type"><span class="wpbs-event wpbs-event-type-<?php echo sanitize_title(wpbs_get_notification_event_type($notification)) ?>"><?php echo wpbs_get_notification_event_type($notification) ?></span></td>
                            <td class="wpbs-column-booking-id"><a target="_blank" href="<?php echo wpbs_dashboard_get_booking_href($notification, $booking); ?>"><span class="wpbs-list-table-id <?php echo $booking->get('status') == 'accepted' ? 'wpbs-booking-color-' . $booking->get('id') % 10 : ''; ?>">#<?php echo $booking->get('id'); ?></span></a></td>
                            <td><?php echo wpbs_get_notification_event_description($notification, $booking) ?></td>
                            <td><?php echo wpbs_date_i18n(get_option('date_format') . ' ' . get_option('time_format'), strtotime($notification->get('date_created'))) ?></td>
                            <td class="wpbs-column-dismiss"><a href="#" class="wpbs-event-dismiss" data-id="<?php echo $notification->get('id'); ?>" title="<?php _e('dismiss', 'wp-booking-system'); ?>"><i class="wpbs-icon-close"></i></a></td>
                        </tr>

                    <?php endforeach; ?>

                    <?php if ($notifications_count > 15 && $number != -1) : ?>
                        <tr class="wpbs-show-all-events">
                            <td colspan="5"><?php echo sprintf(__('Showing 15 out of %d events.', 'wp-booking-system'), $notifications_count); ?> <a href="<?php echo add_query_arg(['page' => 'wpbs-dashboard', 'show-all-events' => true], admin_url('admin.php')); ?>"><?php _e('Show all?', 'wp-booking-system') ?></a></td>
                        </tr>
                    <?php endif; ?>
                </tbody>

            </table>

        <?php endif; ?>

        <div class="wpbs-dashboard-no-entries <?php if (count($notifications) > 0) : ?>wpbs-hide<?php endif; ?>">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512">
                <path d="M48 24C48 10.7 37.3 0 24 0S0 10.7 0 24V64 350.5 400v88c0 13.3 10.7 24 24 24s24-10.7 24-24V388l80.3-20.1c41.1-10.3 84.6-5.5 122.5 13.4c44.2 22.1 95.5 24.8 141.7 7.4l34.7-13c12.5-4.7 20.8-16.6 20.8-30V66.1c0-23-24.2-38-44.8-27.7l-9.6 4.8c-46.3 23.2-100.8 23.2-147.1 0c-35.1-17.6-75.4-22-113.5-12.5L48 52V24zm0 77.5l96.6-24.2c27-6.7 55.5-3.6 80.4 8.8c54.9 27.4 118.7 29.7 175 6.8V334.7l-24.4 9.1c-33.7 12.6-71.2 10.7-103.4-5.4c-48.2-24.1-103.3-30.1-155.6-17.1L48 338.5v-237z" />
            </svg>
            <p><?php echo __('No events logged yet.', 'wp-booking-system') ?></p>
        </div>
        <?php
    }
}
