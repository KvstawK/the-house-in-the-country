<?php

// Exit if accessed directly
if (!defined('ABSPATH')) exit;


/**
 * Dashboard card: Upcoming Bookings
 *
 */
class WPBS_Admin_Dashboard_Card_Upcoming_Bookings extends WPBS_Admin_Dashboard_Card
{

    /**
     * Initialize the card.
     *
     */
    protected function init()
    {

        $this->slug    = 'wpbs_card_upcoming_bookings';
        $this->name    = __('Upcoming Bookings', 'wp-booking-system');
        $this->context = 'primary';
    }


    /**
     * Output the card's content.
     *
     */
    public function output()
    {
    $bookings = wpbs_get_bookings(array('status' => ['accepted', 'pending'], 'custom_query' => 'AND start_date >= "'.date('Y-m-d').' 00:00:00" AND end_date < "'.date('Y-m-d', strtotime('next month')).' 00:00:00"', 'orderby' => 'start_date', 'order' => 'asc', 'number' => 7));
    
    if($bookings):
    ?>

        <table class="wpbs-card-table-full-width">

            <thead>
                <tr>
                    <th class="wpbs-column wpbs-column-booking-id"><?php echo __('Booking', 'wp-booking-system'); ?></th>
                    <th class="wpbs-column wpbs-column-calendar-name"><?php echo __('Calendar', 'wp-booking-system'); ?></th>
                    <th class="wpbs-column"><?php echo __('Start Date', 'wp-booking-system'); ?></th>
                    <th class="wpbs-column"><?php echo __('End Date', 'wp-booking-system'); ?></th>
                    <th class="wpbs-column"><?php echo __('Stay Length', 'wp-booking-system'); ?></th>
                </tr>
            </thead>

            <tbody>

                <?php foreach($bookings as $booking): 
                    $calendar = wpbs_get_calendar($booking->get('calendar_id'));
                    if(is_null($calendar)){
                        continue;
                    }
                    ?>
                    
                    <tr>
                        <td class="wpbs-column-booking-id">
                            <a target="_blank" href="<?php echo add_query_arg(array('page' => 'wpbs-calendars', 'subpage' => 'edit-calendar', 'calendar_id' => $booking->get('calendar_id'), 'booking_id' => $booking->get('id')), admin_url('admin.php'));?>"><span class="wpbs-list-table-id <?php echo $booking->get('status') == 'accepted' ? 'wpbs-booking-color-' . $booking->get('id') % 10 : '';?>">#<?php echo $booking->get('id');?></span></a>
                        </td>
                        <td class="wpbs-column-calendar-name">
                            <a target="_blank" href="<?php echo add_query_arg(array('page' => 'wpbs-calendars', 'subpage' => 'edit-calendar', 'calendar_id' => $booking->get('calendar_id')), admin_url('admin.php'));?>"><?php echo $calendar->get_name();?></a>
                        </td>
                        <td>
                            <?php if(date('Ymd') == date('Ymd', strtotime($booking->get('start_date')))): ?>
                                <strong><?php echo __('Today', 'wp-booking-system') ?></strong>
                            <?php elseif(date('Ymd') == date('Ymd', strtotime($booking->get('start_date')) - DAY_IN_SECONDS)): ?>
                                <strong><?php echo __('Tomorrow', 'wp-booking-system') ?></strong>
                            <?php else: ?>
                                <?php echo wpbs_date_i18n(get_option('date_format'), strtotime($booking->get('start_date'))) ?>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php echo wpbs_date_i18n(get_option('date_format'), strtotime($booking->get('end_date'))) ?>
                        </td>
                        <td>
                            <?php echo $this->get_stay_length($booking); ?>
                        </td>
                    </tr>

                <?php endforeach; ?>

            </tbody>

        </table>

    <?php else: ?>
        <div class="wpbs-dashboard-no-entries">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M160 0c13.3 0 24 10.7 24 24V64H328V24c0-13.3 10.7-24 24-24s24 10.7 24 24V64h40c35.3 0 64 28.7 64 64v16 48V448c0 35.3-28.7 64-64 64H96c-35.3 0-64-28.7-64-64V192 144 128c0-35.3 28.7-64 64-64h40V24c0-13.3 10.7-24 24-24zM432 192H80V448c0 8.8 7.2 16 16 16H416c8.8 0 16-7.2 16-16V192zm-95 89l-47 47 47 47c9.4 9.4 9.4 24.6 0 33.9s-24.6 9.4-33.9 0l-47-47-47 47c-9.4 9.4-24.6 9.4-33.9 0s-9.4-24.6 0-33.9l47-47-47-47c-9.4-9.4-9.4-24.6 0-33.9s24.6-9.4 33.9 0l47 47 47-47c9.4-9.4 24.6-9.4 33.9 0s9.4 24.6 0 33.9z"/></svg>
            <p><?php echo __('No bookings coming up in the next month.', 'wp-booking-system') ?></p>
        </div>
    <?php endif;

    }

    private function get_stay_length($booking){

        $difference = (strtotime($booking->get('end_date')) - strtotime($booking->get('start_date'))) / DAY_IN_SECONDS;

        $abbr = '';
        $abbr .= ($difference + 1) . ' ' . (($difference + 1) == 1 ? __('day', 'wp-booking-system-booking-manager') : __('days', 'wp-booking-system-booking-manager'));
        $abbr .= ' / ';
        $abbr .= $difference . ' ' . ($difference == 1 ? __('night', 'wp-booking-system-booking-manager') : __('nights', 'wp-booking-system-booking-manager'));

        $output = '<abbr title="' . $abbr . '">';
        if ($difference == 0) {
            $output .= '1 ' . __('day', 'wp-booking-system-booking-manager');
        } elseif (wpbs_get_booking_meta($booking->get('id'), 'selection_style', true) == 'normal') {
            $output .= ($difference + 1) . ' ' . (($difference + 1) == 1 ? __('day', 'wp-booking-system-booking-manager') : __('days', 'wp-booking-system-booking-manager'));
        } else {
            $output .= $difference . ' ' . ($difference == 1 ? __('night', 'wp-booking-system-booking-manager') : __('nights', 'wp-booking-system-booking-manager'));
        }
        $output .= '</abbr>';

        return $output;
        
    }
}
