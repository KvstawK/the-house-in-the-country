<?php

// Exit if accessed directly
if (!defined('ABSPATH')) exit;

?>

<div class="wrap wpbs-wrap wpbs-wrap-dashboard">

    <!-- Page Heading -->
    <h1 class="wp-heading-inline"><?php echo __('Dashboard', 'wp-booking-system'); ?></h1>
    <hr class="wp-header-end" />

    <?php

    /**
     * Hook to output extra elements.
     *
     */
    do_action('wpbs_view_dashboard_top');

    ?>

    <div id="wpbs-dashboard-widgets-wrap">

        <div id="dashboard-widgets-wrap">

            <div id="dashboard-widgets" class="metabox-holder">

                <div id="postbox-container-1" class="postbox-container">

                    <div id="wpbs-card-notifications" class="postbox ">
                        <?php $notifications = wpbs_get_dashboard_notifications(); ?>
                        <div class="postbox-header">
                            <h2>
                                <?php _e('Notifications', 'wp-booking-system') ?>
                                <?php if (count($notifications) > 0) : ?>
                                    <span class="wpbs-dashboard-notification-count wpbs-notifications-removable-count wpbs-notifications-count-circle"><?php echo count($notifications); ?></span>
                                <?php endif; ?>
                            </h2>
                        </div>
                        <div class="inside">
                            <?php if (count($notifications) > 0) : ?>
                                <table class="wpbs-card-table-full-width">

                                    <thead>
                                        <tr>
                                            <th class="wpbs-column wpbs-column-type"><?php echo __('Type', 'wp-booking-system'); ?></th>
                                            <th class="wpbs-column  wpbs-column-booking-id"><?php echo __('Booking', 'wp-booking-system'); ?></th>
                                            <th class="wpbs-column"><?php echo __('Message', 'wp-booking-system'); ?></th>
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
                                                <td class="wpbs-column-booking-id"><a target="_blank" href="<?php echo wpbs_dashboard_get_booking_href($notification, $booking);?>"><span class="wpbs-list-table-id <?php echo $booking->get('status') == 'accepted' ? 'wpbs-booking-color-' . $booking->get('id') % 10 : ''; ?>">#<?php echo $booking->get('id'); ?></span></a></td>
                                                <td><?php echo wpbs_get_notification_event_description($notification, $booking) ?></td>
                                                <td><?php echo wpbs_date_i18n(get_option('date_format') . ' ' . get_option('time_format'), strtotime($notification->get('date_created'))) ?></td>
                                                <td class="wpbs-column-dismiss">
                                                    <?php if ($notification->get('dismissable')) : ?>
                                                        <a href="#" class="wpbs-event-dismiss" data-id="<?php echo $notification->get('id'); ?>" title="dismiss"><i class="wpbs-icon-close"></i></a>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>


                                        <?php endforeach; ?>

                                    </tbody>

                                </table>
                            <?php endif; ?>

                            <div class="wpbs-dashboard-no-entries <?php if (count($notifications) > 0) : ?>wpbs-hide<?php endif; ?>">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512">
                                    <path d="M224 0c-17.7 0-32 14.3-32 32V49.9C119.5 61.4 64 124.2 64 200v33.4c0 45.4-15.5 89.5-43.8 124.9L5.3 377c-5.8 7.2-6.9 17.1-2.9 25.4S14.8 416 24 416H424c9.2 0 17.6-5.3 21.6-13.6s2.9-18.2-2.9-25.4l-14.9-18.6C399.5 322.9 384 278.8 384 233.4V200c0-75.8-55.5-138.6-128-150.1V32c0-17.7-14.3-32-32-32zm0 96h8c57.4 0 104 46.6 104 104v33.4c0 47.9 13.9 94.6 39.7 134.6H72.3C98.1 328 112 281.3 112 233.4V200c0-57.4 46.6-104 104-104h8zm64 352H224 160c0 17 6.7 33.3 18.7 45.3s28.3 18.7 45.3 18.7s33.3-6.7 45.3-18.7s18.7-28.3 18.7-45.3z" />
                                </svg>
                                <p><?php _e('No new notifications.', 'wp-booking-system') ?></p>
                            </div>

                        </div>
                    </div>

                    <?php do_meta_boxes('wp-booking-system_page_wpbs-dashboard', 'primary', null); ?>

                </div>

                <div id="postbox-container-2" class="postbox-container">
                    <?php do_meta_boxes('wp-booking-system_page_wpbs-dashboard', 'secondary', null); ?>
                </div>

                <?php wp_nonce_field('wpbs_dashboard_actions', 'wpbs_token', false); ?>

            </div>

        </div>

    </div>

    <?php

    /**
     * Hook to output extra elements.
     *
     */
    do_action('wpbs_view_dashboard_bottom');

    ?>

    <?php

    wp_nonce_field('closedpostboxes', 'closedpostboxesnonce', false);
    wp_nonce_field('meta-box-order', 'meta-box-order-nonce', false);

    ?>

</div>