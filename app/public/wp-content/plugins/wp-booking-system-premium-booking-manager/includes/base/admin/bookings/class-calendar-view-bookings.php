<?php

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}



/**
 * List table class outputter for Bookings
 *
 */
class WPBS_BM_Calendar_View_Bookings
{

    public $calendars;

    public $now;

    public $previous_month;

    public $next_month;

    public $legend_items;

    public $default_legend_items;

    public $events;

    public $ical_events;

    public function __construct()
    {

        $this->calendars = wpbs_get_calendars(array('status' => 'active'));

        $this->now = !isset($_GET['wpbs_date']) ? mktime(0, 0, 0, date('n'), 15, date('Y')) : $_GET['wpbs_date'];
        $this->previous_month = mktime(0, 0, 0, date('n', $this->now) - 1, 15, date('Y', $this->now));
        $this->next_month = mktime(0, 0, 0, date('n', $this->now) + 1, 15, date('Y', $this->now));

        foreach ($this->calendars as $calendar) {

            if (function_exists('wpbs_maybe_refresh_icalendar_feed')) {
                wpbs_maybe_refresh_icalendar_feed($calendar->get('id'));
            }

            $this->legend_items[$calendar->get('id')] = wpbs_get_legend_items(array('calendar_id' => $calendar->get('id')));
            $this->events[$calendar->get('id')] = wpbs_get_events(array('calendar_id' => $calendar->get('id')));
            $this->ical_events[$calendar->get('id')] = wpbs_get_ical_feeds_as_events($calendar->get('id'), $this->events[$calendar->get('id')]);
        }

        foreach ($this->legend_items as $calendar_id => $legend_items) {

            foreach ($legend_items as $legend_item) {

                if ($legend_item->get('is_default') != 1) {
                    continue;
                }

                $this->default_legend_items[$calendar_id] = $legend_item;
            }
        }
    }

    public function navigation_dropdown()
    {
        $output = '';
        $currentYear = false;
        for ($i = -12; $i <= 24; $i++) {

            $month = mktime(0, 0, 0, date('n', $this->now) + $i, 15, date('Y', $this->now));

            if ($currentYear != date('Y', $month)) {
                if ($currentYear != false) {
                    $output .= '</optgroup>';
                }
                $output .= '<optgroup label="' . date('Y', $month) . '">';

                $currentYear = date('Y', $month);
            }

            $output .= '<option value="' . $month . '" ' . ($month == $this->now ? 'selected="true"' : '') . '>' . wp_date('F Y', $month) . '</option>';
        }

        $output .= '</optgroup>';

        return $output;
    }

    /**
     * List normal bookings
     * 
     */
    public function list_calendar_bookings($calendar_id)
    {

        $output = '';

        $year = date('Y', $this->now);
        $month = date('m', $this->now);

        $bookings = wpbs_get_bookings(array('calendar_id' => $calendar_id, 'status' => array('pending', 'accepted'), 'custom_query' =>
        ' AND ' . $year . $month . ' BETWEEN EXTRACT(YEAR_MONTH FROM start_date) and EXTRACT(YEAR_MONTH FROM end_date)'));

        foreach ($bookings as $booking) {
            $start = strtotime($booking->get('start_date'));
            $end = strtotime($booking->get('end_date'));

            $booking_span = min(50, ($end - $start) / DAY_IN_SECONDS);

            $booking_start = date('j', $start);

            // If booking starts in another month
            if (date('mY', $this->now) != date('mY', $start)) {

                // And if it ends in another month
                if (date('mY', $this->now) != date('mY', $end)) {
                    $booking_span = 50;
                    $booking_start = 0;
                } else {
                    $booking_span = date('j', $end) - 1;
                    $booking_start = 0;
                }
            }

            $dynamic_field_values = '';

            $fields = wpbs_get_form_meta($booking->get('form_id'), 'booking_manager_fields', true);

            if ($fields) {
                foreach ($fields as $field_id) {
                    foreach ($booking->get('fields') as $field) {

                        if ($field['id'] != $field_id) {
                            continue;
                        }

                        $dynamic_field_values .= (isset($field['user_value']) ? $field['user_value'] : '') . ' ';
                    }
                }
            }

            $dynamic_field_values = trim($dynamic_field_values);

            $output .= '<a target="_blank" href="' . add_query_arg(array('page' => 'wpbs-calendars', 'subpage' => 'edit-calendar', 'calendar_id' => $booking->get('calendar_id'), 'booking_id' => $booking->get('id')), admin_url('admin.php')) . '" data-id="' . $booking->get('id') . '" class="wpbs-bm-booking wpbs-bm-booking-id-' . $booking->get('id') . ' wpbs-booking-color-status-' . $booking->get('status') . ' wpbs-booking-color-' . ($booking->get('id') % 10) . ' wpbs-bm-booking-span-' . $booking_span . ' wpbs-bm-booking-start-' . $booking_start . '" title="#' . $booking->get('id') . ($dynamic_field_values ? ' - ' . $dynamic_field_values : '') . '"><span>#' . $booking->get('id') . '</span> <small>' . $dynamic_field_values . '</small></a>';
        }

        return $output;
    }

    /**
     * List iCalendar bookings 
     * 
     */
    public function list_icalendar_bookings($calendar_id)
    {

        $output = '';
        $ical_feeds = wpbs_get_calendar_meta_ical_feeds($calendar_id);

        // Include the iCal Reader
        include_once WPBS_PLUGIN_DIR . 'includes/libs/iCalReader/class-ical-reader.php';

        // Initial loop to temporarily store iCal events
        foreach ($ical_feeds as $ical_feed) {

            if (empty($ical_feed['file_contents'])) {
                continue;
            }

            if (empty($ical_feed['url'])) {
                continue;
            }

            if (isset($ical_feed['disabled']) && $ical_feed['disabled'] == true) {
                continue;
            }

            // Extract the file in an array format
            $ical_reader = new WPBS_ICal_Reader();
            $ical_arr = $ical_reader->init_contents($ical_feed['file_contents']);

            if (empty($ical_arr['VEVENT']) || !is_array($ical_arr['VEVENT'])) {
                continue;
            }

            foreach ($ical_arr['VEVENT'] as $ical_event) {

                $ical_event = apply_filters('wpbs_ical_import_from_url_event', $ical_event);

                if ($ical_event === false) {
                    continue;
                }

                // Remove timezones from strings
                $dtstart = wpbs_remove_timezone_from_date_string($ical_event['DTSTART']);
                $dtend = wpbs_remove_timezone_from_date_string($ical_event['DTEND']);

                // Check for invalid dates
                if (!is_numeric($dtstart) || !is_numeric($dtend)) {
                    continue;
                }

                $begin = new DateTime($dtstart);
                $end = new DateTime($dtend);

                $begin->setTime(0, 0, 0);
                $end->setTime(23, 59, 59);

                // Check if it's an hourly event
                $interval = $begin->diff($end);
                if ($interval->days == 0) {
                    $end->modify('+1 day');
                }

                $end->modify('-1 day');

                if (!$ical_feed['split_days']) {
                    $end->modify('-1 day');
                }

                if (date('Ym', $this->now) < $begin->format('Ym') || date('Ym', $this->now) > $end->format('Ym')) {
                    continue;
                }

                /**
                 * Allow adding an offset to iCalendar feeds.
                 */
                $start_offset = apply_filters('wpbs_ical_import_from_url_offset_start', false);
                $end_offset = apply_filters('wpbs_ical_import_from_url_offset_end', false);

                if ($start_offset !== false) {
                    $begin->modify($start_offset);
                }

                if ($end_offset !== false) {
                    $end->modify($end_offset);
                }

                $start_timestamp = $begin->format('U');
                $end_timestamp = $end->format('U');

                $booking_span = min(50, ($end_timestamp - $start_timestamp) / DAY_IN_SECONDS);
                if ($booking_span == 50) {
                    continue;
                }
                $booking_start = date('j', $start_timestamp);

                // If booking starts in another month
                if (date('mY', $this->now) != date('mY', $start_timestamp)) {

                    // And if it ends in another month
                    if (date('mY', $this->now) != date('mY', $end_timestamp)) {
                        $booking_span = 50;
                        $booking_start = 0;
                    } else {
                        $booking_span = date('j', $end_timestamp);
                        $booking_start = 0;
                    }
                }
                $booking_span = round($booking_span);

                $output .= '<span target="_blank" href="' . add_query_arg(array('page' => 'wpbs-calendars', 'subpage' => 'edit-calendar', 'calendar_id' => $calendar_id), admin_url('admin.php')) . '" class="wpbs-bm-booking  wpbs-booking-color-status-pending  wpbs-bm-booking-span-' . $booking_span . ' wpbs-bm-booking-start-' . $booking_start . '" title="iCalendar Event - ' . ($ical_feed['name'] ? $ical_feed['name'] . ' - ' : '') . $ical_event['SUMMARY'] . '"><small>' . ($ical_feed['name'] ? $ical_feed['name'] . ' - ' : '') .  $ical_event['SUMMARY'] . '</small></span>';
            }
        }

        return $output;
    }

    public function display()
    {
?>
        <div class="wpbs-bm-calendar-wrapper">

            <div class="wpbs-bm-calendar">

                <!-- Header -->
                <div class="wpbs-bm-calendar-row-header-wrapper">
                    <div class="wpbs-bm-calendar-row wpbs-bm-calendar-row-header">

                        <div class="wpbs-bm-calendar-col-fixed">
                            <div class="wpbs-bm-calendar-header-navigation">
                                <a href="<?php echo add_query_arg(array('page' => 'wpbs-bookings', 'wpbs_date' => $this->previous_month), admin_url('admin.php')); ?>" class="wpbs-prev"><span class="wpbs-arrow"></span></a>
                                <div class="wpbs-bm-select-container">
                                    <form action="<?php echo admin_url('admin.php'); ?>" method="get">
                                        <input type="hidden" name="page" value="wpbs-bookings">
                                        <select name="wpbs_date">
                                            <?php echo $this->navigation_dropdown(); ?>
                                        </select>
                                    </form>
                                </div>
                                <a href="<?php echo add_query_arg(array('page' => 'wpbs-bookings', 'wpbs_date' => $this->next_month), admin_url('admin.php')); ?>" class="wpbs-next"><span class="wpbs-arrow"></span></a>
                            </div>
                        </div>

                        <div class="wpbs-bm-calendar-col-dates">
                            <?php for ($i = 1; $i <= 31; $i++) : $day = $i; ?>
                                <?php if ($i > date('t', $this->now)) {
                                    $day = $i - date('t', $this->now);
                                }
                                ?>
                                <div class="wpbs-bm-calendar-day <?php echo $day != $i ? 'wpbs-bm-calendar-day-other-month' : ''; ?>">
                                    <small><?php echo date_i18n('D', $this->now + ($i - 1) * DAY_IN_SECONDS) ?></small>
                                    <span><?php echo $day; ?></span>
                                </div>
                            <?php endfor; ?>
                        </div>
                    </div>
                </div>

                <!-- Dates -->
                <div class="wpbs-bm-calendar-rows-wrapper">
                    <?php foreach ($this->calendars as $j => $calendar) : ?>

                        <div class="wpbs-bm-calendar-row wpbs-bm-calendar-row-body wpbs-bm-calendar-row-body-<?php echo ($j % 2 != 0) ? 'odd' : 'even'; ?>">
                            <div class="wpbs-bm-calendar-col-fixed wpbs-bm-calendar-name">
                                <span><a href="<?php echo add_query_arg(array('page' => 'wpbs-calendars', 'subpage' => 'edit-calendar', 'calendar_id' => $calendar->get('id')), admin_url('admin.php')); ?>" target="_blank"><?php echo $calendar->get_name(); ?></a></span>
                                <a target="_blank" href="<?php echo add_query_arg(array('page' => 'wpbs-calendars', 'subpage' => 'edit-calendar', 'calendar_id' => $calendar->get('id'), 'add_booking' => ''), admin_url('admin.php')); ?>" class="wpbs-bm-add-booking"><?php _e('add booking', 'wp-booking-system-booking-manager') ?></a>
                            </div>

                            <div class="wpbs-bm-calendar-col-dates">
                                <?php for ($i = 1; $i <= 31; $i++) :
                                    $display_date = mktime(0, 0, 0, date('m', $this->now), $i, date('Y', $this->now));
                                ?>
                                    <div class="wpbs-bm-calendar-day">
                                        <?php echo $this->get_display_day($calendar, date('Y', $display_date), date('m', $display_date), date('d', $display_date)); ?>
                                    </div>
                                <?php endfor; ?>

                                <?php echo $this->list_calendar_bookings($calendar->get('id')) ?>
                                <?php echo $this->list_icalendar_bookings($calendar->get('id')) ?>

                            </div>

                        </div>

                    <?php endforeach ?>
                </div>

            </div>
        </div>

<?php
        echo $this->get_custom_css();
    }

    protected function get_display_day($calendar, $year, $month, $day)
    {

        $output = '';

        /**
         * Get the event for the current day
         *
         */

        $event = $this->get_event_by_date($calendar, $year, $month, $day);

        /**
         * Get the event for the current day from the iCal feeds
         *
         */
        $ical_event = $this->get_ical_event_by_date($calendar, $year, $month, $day);

        if (!is_null($ical_event)) {
            $_event = $event;
            $event = $ical_event;
        }

        /**
         * Get the legend item for the current day
         *
         */
        $legend_item = null;

        if (!is_null($event)) {

            foreach ($this->legend_items[$calendar->get('id')] as $li) {

                if ($event->get('legend_item_id') == $li->get('id')) {
                    $legend_item = $li;
                }
            }
        }

        if (is_null($legend_item)) {
            $legend_item = $this->default_legend_items[$calendar->get('id')];
        }

        /**
         * Legend item output
         *
         */
        $legend_item_id_icon = $legend_item->get('id');
        $legend_item_type_icon = $legend_item->get('type');

        $ical_changeover = $ical_event && isset($ical_event->get('meta')['ical-changeover']) && $ical_event->get('meta')['ical-changeover'] ? '<svg class="ical-changeover" preserveAspectRatio="none" viewBox="0 0 50 50" style="fill: #ddffcc;"><polygon points="3,50 50,3 50,0 47,0 0,47 0,50"></polygon></svg>' : '';

        $output .= '<div class="wpbs-date wpbs-legend-item-' . $legend_item_id_icon . '" ' . ('data-year="' . esc_attr($year) . '" data-month="' . esc_attr($month) . '" data-day="' . esc_attr($day) . '"') . '>';

        $output .= wpbs_get_legend_item_icon($legend_item_id_icon, $legend_item_type_icon, [], $ical_changeover);

        /**
         * Pricing
         *
         */

        if (wpbs_is_pricing_enabled()) {

            $default_price = wpbs_get_calendar_meta($calendar->get('id'), 'default_price', true);

            if (!is_null($ical_event)) {
                $price = (!is_null($_event) && (!empty($_event->get('price')) || $_event->get('price') !== "")) ? $_event->get('price') : $default_price;
            } else {
                $price = (!is_null($event) && (!empty($event->get('price')) || $event->get('price') !== "")) ? $event->get('price') : $default_price;
            }

            $plugin_settings = get_option('wpbs_settings', array());
            $currency_position = isset($plugin_settings['currency_position']) && !empty($plugin_settings['currency_position']) ? $plugin_settings['currency_position'] : 'left';
            $price_display = trim(wpbs_get_formatted_price($price, ''));
            $price_display = substr($price_display, -3) == ',00' ? str_replace(',00', '', $price_display) : str_replace('.00', '', $price_display);
            if ($currency_position == 'left') {
                $price_display = wpbs_get_currency_symbol(wpbs_get_currency()) . $price_display;
            } else {
                $price_display = $price_display . wpbs_get_currency_symbol(wpbs_get_currency());
            }

            $output .= '<span class="wpbs-daily-price">' . $price_display . '</span>';
        }

        $output .= '</div>';

        return $output;
    }

    protected function get_event_by_date($calendar, $year, $month, $day)
    {

        foreach ($this->events[$calendar->get('id')] as $event) {

            if ($event->get('date_year') == $year && $event->get('date_month') == $month && $event->get('date_day') == $day) {
                return $event;
            }
        }

        return null;
    }

    protected function get_ical_event_by_date($calendar, $year, $month, $day)
    {

        foreach ($this->ical_events[$calendar->get('id')] as $event) {

            if ($event->get('date_year') == $year && $event->get('date_month') == $month && $event->get('date_day') == $day) {
                return $event;
            }
        }

        return null;
    }

    protected function get_custom_css()
    {

        $output = '<style type="text/css">';

        // Set the parent calendar class
        $calendar_parent_class = '.wpbs-bm-calendar-wrapper';

        /**
         * Legend Items CSS
         *
         */
        foreach ($this->calendars as $calendar) {

            foreach ($this->legend_items[$calendar->get('id')] as $legend_item) {

                $colors = $legend_item->get('color');

                $output .= $calendar_parent_class . ' .wpbs-legend-item-icon-' . esc_attr($legend_item->get('id')) . ' div:first-of-type { background-color: ' . (!empty($colors[0]) ? esc_attr($colors[0]) : 'transparent') . '; }';
                $output .= $calendar_parent_class . ' .wpbs-legend-item-icon-' . esc_attr($legend_item->get('id')) . ' div:nth-of-type(2) { background-color: ' . (!empty($colors[1]) ? esc_attr($colors[1]) : 'transparent') . '; }';

                $output .= $calendar_parent_class . ' .wpbs-legend-item-icon-' . esc_attr($legend_item->get('id')) . ' div:first-of-type svg { fill: ' . (!empty($colors[0]) ? esc_attr($colors[0]) : 'transparent') . '; }';
                $output .= $calendar_parent_class . ' .wpbs-legend-item-icon-' . esc_attr($legend_item->get('id')) . ' div:nth-of-type(2) svg { fill: ' . (!empty($colors[1]) ? esc_attr($colors[1]) : 'transparent') . '; }';

                // Text color
                $color_text = $legend_item->get('color_text');

                if (!empty($color_text))
                    $output .= $calendar_parent_class . ' .wpbs-legend-item-' . esc_attr($legend_item->get('id')) . ' .wpbs-daily-price { color: ' . esc_attr($color_text) . ' !important; }';
            }
        }

        $output .= '</style>';

        return $output;
    }
}
