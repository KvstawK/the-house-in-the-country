<?php

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

class WPBS_Booking_Details_Outputter
{

    /**
     * The calendar
     *
     * @access protected
     * @var    WPBS_Calendar
     *
     */
    protected $calendar;

    /**
     * The booking
     *
     * @access protected
     * @var    WPBS_Booking
     *
     */
    protected $booking;

    /**
     * Tabs
     *
     * @access protected
     * @var    array
     *
     */
    protected $tabs;

    /**
     * Plugin Settings
     *
     * @access protected
     * @var    array
     *
     */
    protected $plugin_settings;

    /**
     * Constructor
     *
     * @param WPBS_Booking $booking
     *
     */
    public function __construct($booking)
    {
        /**
         * Get Booking
         *
         */
        $this->booking = $booking;

        /**
         * Get Calendar
         *
         */
        $this->calendar = wpbs_get_calendar($this->booking->get('calendar_id'));

        /**
         * Set plugin settings
         *
         */
        $this->plugin_settings = get_option('wpbs_settings', array());

        /**
         * Set default tabs
         *
         */

        $this->tabs = array(
            'manage-booking' => '<svg aria-hidden="true" focusable="false" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512"><path fill="currentColor" d="M417.8 315.5l20-20c3.8-3.8 10.2-1.1 10.2 4.2V464c0 26.5-21.5 48-48 48H48c-26.5 0-48-21.5-48-48V112c0-26.5 21.5-48 48-48h292.3c5.3 0 8 6.5 4.2 10.2l-20 20c-1.1 1.1-2.7 1.8-4.2 1.8H48c-8.8 0-16 7.2-16 16v352c0 8.8 7.2 16 16 16h352c8.8 0 16-7.2 16-16V319.7c0-1.6.6-3.1 1.8-4.2zm145.9-191.2L251.2 436.8l-99.9 11.1c-13.4 1.5-24.7-9.8-23.2-23.2l11.1-99.9L451.7 12.3c16.4-16.4 43-16.4 59.4 0l52.6 52.6c16.4 16.4 16.4 43 0 59.4zm-93.6 48.4L403.4 106 169.8 339.5l-8.3 75.1 75.1-8.3 233.5-233.6zm71-85.2l-52.6-52.6c-3.8-3.8-10.2-4-14.1 0L426 83.3l66.7 66.7 48.4-48.4c3.9-3.8 3.9-10.2 0-14.1z"></path></svg>' . __('Manage Booking', 'wp-booking-system'),
            'booking-details' => '<svg aria-hidden="true" focusable="false" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><path fill="currentColor" d="M400 64h-48V12c0-6.627-5.373-12-12-12h-8c-6.627 0-12 5.373-12 12v52H128V12c0-6.627-5.373-12-12-12h-8c-6.627 0-12 5.373-12 12v52H48C21.49 64 0 85.49 0 112v352c0 26.51 21.49 48 48 48h352c26.51 0 48-21.49 48-48V112c0-26.51-21.49-48-48-48zM48 96h352c8.822 0 16 7.178 16 16v48H32v-48c0-8.822 7.178-16 16-16zm352 384H48c-8.822 0-16-7.178-16-16V192h384v272c0 8.822-7.178 16-16 16zm-66.467-194.937l-134.791 133.71c-4.7 4.663-12.288 4.642-16.963-.046l-67.358-67.552c-4.683-4.697-4.672-12.301.024-16.985l8.505-8.48c4.697-4.683 12.301-4.672 16.984.024l50.442 50.587 117.782-116.837c4.709-4.671 12.313-4.641 16.985.068l8.458 8.527c4.672 4.709 4.641 12.313-.068 16.984z"></path></svg>' . __('Booking Details', 'wp-booking-system'),
            'email-customer' => '<svg aria-hidden="true" focusable="false" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path fill="currentColor" d="M464 64H48C21.5 64 0 85.5 0 112v288c0 26.5 21.5 48 48 48h416c26.5 0 48-21.5 48-48V112c0-26.5-21.5-48-48-48zM48 96h416c8.8 0 16 7.2 16 16v41.4c-21.9 18.5-53.2 44-150.6 121.3-16.9 13.4-50.2 45.7-73.4 45.3-23.2.4-56.6-31.9-73.4-45.3C85.2 197.4 53.9 171.9 32 153.4V112c0-8.8 7.2-16 16-16zm416 320H48c-8.8 0-16-7.2-16-16V195c22.8 18.7 58.8 47.6 130.7 104.7 20.5 16.4 56.7 52.5 93.3 52.3 36.4.3 72.3-35.5 93.3-52.3 71.9-57.1 107.9-86 130.7-104.7v205c0 8.8-7.2 16-16 16z"></path></svg>' . __('Email Customer', 'wp-booking-system'),
            'email-logs' => '<svg aria-hidden="true" focusable="false" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path fill="currentColor" d="M20 24h10c6.627 0 12 5.373 12 12v94.625C85.196 57.047 165.239 7.715 256.793 8.001 393.18 8.428 504.213 120.009 504 256.396 503.786 393.181 392.834 504 256 504c-63.926 0-122.202-24.187-166.178-63.908-5.113-4.618-5.354-12.561-.482-17.433l7.069-7.069c4.503-4.503 11.749-4.714 16.482-.454C150.782 449.238 200.935 470 256 470c117.744 0 214-95.331 214-214 0-117.744-95.331-214-214-214-82.862 0-154.737 47.077-190.289 116H164c6.627 0 12 5.373 12 12v10c0 6.627-5.373 12-12 12H20c-6.627 0-12-5.373-12-12V36c0-6.627 5.373-12 12-12zm321.647 315.235l4.706-6.47c3.898-5.36 2.713-12.865-2.647-16.763L272 263.853V116c0-6.627-5.373-12-12-12h-8c-6.627 0-12 5.373-12 12v164.147l84.884 61.734c5.36 3.899 12.865 2.714 16.763-2.646z"></path></svg>' . __('Email Logs', 'wp-booking-system'),
            'notes' => '<svg aria-hidden="true" focusable="false" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512"><path fill="currentColor" d="M336 64h-88.6c.4-2.6.6-5.3.6-8 0-30.9-25.1-56-56-56s-56 25.1-56 56c0 2.7.2 5.4.6 8H48C21.5 64 0 85.5 0 112v352c0 26.5 21.5 48 48 48h288c26.5 0 48-21.5 48-48V112c0-26.5-21.5-48-48-48zM192 32c13.3 0 24 10.7 24 24s-10.7 24-24 24-24-10.7-24-24 10.7-24 24-24zm160 432c0 8.8-7.2 16-16 16H48c-8.8 0-16-7.2-16-16V112c0-8.8 7.2-16 16-16h48v20c0 6.6 5.4 12 12 12h168c6.6 0 12-5.4 12-12V96h48c8.8 0 16 7.2 16 16z"></path></svg>' . __('Notes', 'wp-booking-system') . $this->get_notes_count() ,
        );

        $this->tabs = apply_filters('wpbs_booking_modal_tabs', $this->tabs, $this->booking);

        $this->check_tabs();

    }

    /**
     * Displays the modal HTML
     *
     */
    public function display()
    {
        include 'views/view-modal.php';
    }

    /**
     * Show or hide tabs depending on the stastus of the booking
     *
     */
    protected function check_tabs()
    {
        if ($this->booking->get('status') == 'trash') {
            unset($this->tabs['email-customer']);
        }

        if ($this->get_email_addresses() === false) {
            unset($this->tabs['email-customer']);
            unset($this->tabs['email-logs']);
        }

        if (!isset($this->plugin_settings['email_logs']) || $this->plugin_settings['email_logs'] != 'on') {
            unset($this->tabs['email-logs']);
        }

    }

    /**
     * Get the active tab depending on the stastus of the booking
     *
     * @return string
     *
     */
    protected function get_active_tab()
    {
        if ($this->booking->get('status') == 'accepted') {
            return 'booking-details';
        }
        return $this->active_tab = 'manage-booking';
    }

    /**
     * Get the button label depending on the stastus of the booking
     *
     * @return string
     *
     */
    protected function get_manage_booking_button_label()
    {
        if ($this->booking->get('status') == 'pending') {
            return __('Accept Booking', 'wp-booking-system');
        } else if ($this->booking->get('status') == 'trash') {
            return __('Restore Booking', 'wp-booking-system');
        }
        return __('Update Booking', 'wp-booking-system');
    }

    /**
     * Get email heading depending on the stastus of the booking
     *
     * @return string
     *
     */
    protected function get_email_customer_heading()
    {
        if ($this->booking->get('status') == 'pending') {
            return __('Send an email to the customer when accepting the booking', 'wp-booking-system');
        }
        return __('Send an email to the customer when updating the booking', 'wp-booking-system');
    }

    /**
     * Get booking data
     *
     * @return array
     *
     */
    protected function get_booking_data()
    {
        $data = array();

        $data[] = array(
            'editable' => false,
            'name' => 'booking_id',
            'label' => __('Booking ID', 'wp-booking-system'),
            'value' => '#' . $this->booking->get('id'),
        );

        $data[] = array(
            'editable' => false,
            'name' => 'calendar',
            'label' => __('Calendar', 'wp-booking-system'),
            'value' => $this->calendar->get_name(),
        );

        $data[] = array(
            'editable' => true,
            'time' => strtotime($this->booking->get('start_date')),
            'name' => 'start_date',
            'label' => __('Start Date', 'wp-booking-system'),
            'value' => wpbs_date_i18n(get_option('date_format'), strtotime($this->booking->get('start_date'))),
        );

        $data[] = array(
            'editable' => true,
            'time' => strtotime($this->booking->get('end_date')),
            'name' => 'end_date',
            'label' => __('End Date', 'wp-booking-system'),
            'value' => wpbs_date_i18n(get_option('date_format'), strtotime($this->booking->get('end_date'))),
        );

        $data[] = array(
            'editable' => false,
            'name' => 'booked_on',
            'label' => __('Booked on', 'wp-booking-system'),
            'value' => wpbs_date_i18n(get_option('date_format') . ' ' . get_option('time_format'), strtotime($this->booking->get('date_created'))),
        );

        if (wpbs_translations_active()) {

            $languages = wpbs_get_languages();

            $data[] = array(
                'editable' => false,
                'name' => 'language',
                'label' => __('Language', 'wp-booking-system'),
                'value' => $languages[wpbs_get_booking_meta($this->booking->get('id'), 'submitted_language', true)],
            );
        }

        if (wpbs_get_booking_meta($this->booking->get('id'), 'customer_ip', true)) {
            $data[] = array(
                'editable' => false,
                'name' => 'ip_address',
                'label' => __('IP Address', 'wp-booking-system'),
                'value' => wpbs_get_booking_meta($this->booking->get('id'), 'customer_ip', true),
            );
        }

        $crons = _get_cron_array();

        $payment = wpbs_get_payment_by_booking_id($this->booking->get('id'));

        /**
         * Email Reminder cron
         */
        foreach ($crons as $timestamp => $cron) {
            if (isset($cron['wpbs_er_reminder_email'])) {
                foreach ($cron['wpbs_er_reminder_email'] as $job) {
                    if ($job['args'][2] == $this->booking->get('id')) {
                        $data[] = array(
                            'editable' => true,
                            'time' => $timestamp,
                            'name' => 'reminder_email_date',
                            'label' => __('Email Reminder', 'wp-booking-system'),
                            'value' => sprintf(__('Scheduled to be sent on <strong>%s</strong>.', 'wp-booking-system'), wpbs_date_i18n(get_option('date_format') . ', ' . get_option('time_format'), $timestamp + get_option('gmt_offset') * HOUR_IN_SECONDS))
                            . ($this->booking->get('status') != 'accepted' ? '<br><small><em>' . __('Email will be sent only if the booking is Accepted.', 'wp-booking-system') . '</em></small>' : '<a class="wpbs-remove-scheduled-action"  data-cron="wpbs_er_reminder_email" data-id="'.$this->booking->get('id').'" href="#">'.__('delete', 'wp-booking-system').'</a>'),
                        );
                    }
                }
            }
        }

        /**
         * SMS Reminder Cron
         */
        foreach ($crons as $timestamp => $cron) {
            if (isset($cron['wpbs_sms_reminder_notification'])) {
                foreach ($cron['wpbs_sms_reminder_notification'] as $job) {
                    if ($job['args'][2] == $this->booking->get('id')) {
                        $data[] = array(
                            'editable' => true,
                            'time' => $timestamp,
                            'name' => 'reminder_sms_date',
                            'label' => __('Reminder SMS', 'wp-booking-system'),
                            'value' => sprintf(__('Scheduled to be sent on <strong>%s</strong>.', 'wp-booking-system'), wpbs_date_i18n(get_option('date_format') . ', ' . get_option('time_format'), $timestamp + get_option('gmt_offset') * HOUR_IN_SECONDS))
                            . ($this->booking->get('status') != 'accepted' ? '<br><em><small>' . __('SMS will be sent only if the booking is Accepted.', 'wp-booking-system') . '</small></em>' : '<a class="wpbs-remove-scheduled-action"  data-cron="wpbs_sms_reminder_notification" data-id="'.$this->booking->get('id').'" href="#">'.__('delete', 'wp-booking-system').'</a>'),
                        );

                    }
                }
            }
        }

        /**
         * Follow Up Email cron
         */
        foreach ($crons as $timestamp => $cron) {
            if (isset($cron['wpbs_er_follow_up_email'])) {
                foreach ($cron['wpbs_er_follow_up_email'] as $job) {
                    if ($job['args'][2] == $this->booking->get('id')) {
                        $data[] = array(
                            'editable' => true,
                            'time' => $timestamp,
                            'name' => 'follow_up_email_date',
                            'label' => __('Follow up Email', 'wp-booking-system'),
                            'value' => sprintf(__('Scheduled to be sent on <strong>%s</strong>.', 'wp-booking-system'), wpbs_date_i18n(get_option('date_format') . ', ' . get_option('time_format'), $timestamp + get_option('gmt_offset') * HOUR_IN_SECONDS))
                            . ($this->booking->get('status') != 'accepted' ? '<br><em><small>' . __('Email will be sent only if the booking is Accepted.', 'wp-booking-system') . '</small></em>' : '<a class="wpbs-remove-scheduled-action"  data-cron="wpbs_er_follow_up_email" data-id="'.$this->booking->get('id').'" href="#">'.__('delete', 'wp-booking-system').'</a>'),
                        );

                    }
                }
            }
        }

        /**
         * Follow up SMS cron
         */
        foreach ($crons as $timestamp => $cron) {
            if (isset($cron['wpbs_sms_follow_up_notification'])) {
                foreach ($cron['wpbs_sms_follow_up_notification'] as $job) {
                    if ($job['args'][2] == $this->booking->get('id')) {
                        $data[] = array(
                            'editable' => true,
                            'time' => $timestamp,
                            'name' => 'follow_up_sms_date',
                            'label' => __('Follow up SMS', 'wp-booking-system'),
                            'value' => sprintf(__('Scheduled to be sent on <strong>%s</strong>.', 'wp-booking-system'), wpbs_date_i18n(get_option('date_format') . ', ' . get_option('time_format'), $timestamp + get_option('gmt_offset') * HOUR_IN_SECONDS))
                            . ($this->booking->get('status') != 'accepted' ? '<br><em><small>' . __('SMS will be sent only if the booking is Accepted.', 'wp-booking-system') . '</small></em>' : '<a class="wpbs-remove-scheduled-action"  data-cron="wpbs_sms_follow_up_notification" data-id="'.$this->booking->get('id').'" href="#">'.__('delete', 'wp-booking-system').'</a>'),
                        );

                    }
                }
            }
        }

        /**
         * Payment Reminder Email cron
         */
        if ($payment && !$payment->is_final_payment_paid()) {
            foreach ($crons as $timestamp => $cron) {
                if (isset($cron['wpbs_part_payments_payment_reminder_email'])) {
                    foreach ($cron['wpbs_part_payments_payment_reminder_email'] as $job) {
                        if ($job['args'][2] == $this->booking->get('id')) {
                            $data[] = array(
                                'editable' => true,
                                'time' => $timestamp,
                                'name' => 'payment_email_date',
                                'label' => __('Part Payment Reminder Email', 'wp-booking-system'),
                                'value' => sprintf(__('Scheduled to be sent on <strong>%s</strong>.', 'wp-booking-system'), wpbs_date_i18n(get_option('date_format') . ', ' . get_option('time_format'), $timestamp + get_option('gmt_offset') * HOUR_IN_SECONDS))
                                . ($this->booking->get('status') != 'accepted' ? '<br><small><em>' . __('Email will be sent only if the booking is Accepted.', 'wp-booking-system') . '</em></small>' : '<a class="wpbs-remove-scheduled-action"  data-cron="wpbs_part_payments_payment_reminder_email" data-id="'.$this->booking->get('id').'" href="#">'.__('delete', 'wp-booking-system').'</a>'),
                            );
                        }
                    }
                }
            }
        }

        /**
         * Payment Reminder SMS cron
         */
        foreach ($crons as $timestamp => $cron) {
            if (isset($cron['wpbs_sms_payment_notification'])) {
                foreach ($cron['wpbs_sms_payment_notification'] as $job) {
                    if ($job['args'][2] == $this->booking->get('id')) {
                        $data[] = array(
                            'editable' => true,
                            'time' => $timestamp,
                            'name' => 'payment_sms_date',
                            'label' => __('Part Payment Reminder SMS', 'wp-booking-system'),
                            'value' => sprintf(__('Scheduled to be sent on <strong>%s</strong>.', 'wp-booking-system'), wpbs_date_i18n(get_option('date_format') . ', ' . get_option('time_format'), $timestamp + get_option('gmt_offset') * HOUR_IN_SECONDS))
                            . ($this->booking->get('status') != 'accepted' ? '<br><em><small>' . __('SMS will be sent only if the booking is Accepted.', 'wp-booking-system') . '</small></em>' : '<a class="wpbs-remove-scheduled-action"  data-cron="wpbs_sms_payment_notification" data-id="'.$this->booking->get('id').'" href="#">'.__('delete', 'wp-booking-system').'</a>'),
                        );

                    }
                }
            }
        }

        return $data;
    }

    /**
     * Get form data
     *
     * @return array
     *
     */
    protected function get_form_data()
    {

        $data = array();

        foreach ($this->booking->get('fields') as $field) {

            if (in_array($field['type'], wpbs_get_excluded_fields(array('hidden', 'signature')))) {
                continue;
            }

            // Get value
            $value = (isset($field['user_value'])) ? $field['user_value'] : '';

            // Handle Pricing options differently
            if (wpbs_form_field_is_product($field['type'])) {
                $value = wpbs_get_form_field_product_values($field);
            }

            $value = wpbs_get_field_display_user_value($value);

            if ($field['type'] == 'textarea') {
                $value = nl2br($value);
            }

            if ($field['type'] == 'payment_method') {
                $value = isset(wpbs_get_payment_methods()[$value]) ? wpbs_get_payment_methods()[$value] : '';
            }

            if ($field['type'] == 'signature') {
                $value = $value != '-' ? __('Signed', 'wp-booking-system') : __('Not signed', 'wp-booking-system');
            }

            $data[] = array(
                'field' => $field,
                'editable' => (in_array($field['type'], array('consent', 'payment_method', 'coupon', 'signature')) ? false : true),
                'label' => $this->get_translated_label($field),
                'value' => $value,
            );
        }

        $data = apply_filters('wpbs_booking_modal_form_data', $data, $this->booking);

        return $data;

    }

    /**
     * Get notes
     *
     * @return array
     *
     */
    protected function get_notes()
    {
        return wpbs_get_booking_meta($this->booking->get('id'), 'booking_notes', true);
    }

    /**
     * Helper function to get label translations
     *
     * @param array $field
     *
     * @return string
     *
     */
    protected function get_translated_label($field)
    {
        $language = wpbs_get_locale();

        if (isset($field['values'][$language]['label']) && !empty($field['values'][$language]['label'])) {
            return $field['values'][$language]['label'];
        }

        return $field['values']['default']['label'];
    }

    /**
     * Get the calendar editor
     *
     * @return string
     *
     */
    protected function calendar_editor()
    {

        $output = '';

        // Set start date
        $start_date = new DateTime();
        $start_date->setTimestamp(strtotime($this->booking->get('start_date')));

        // Set end date
        $end_date = new DateTime();
        $end_date->setTimestamp(strtotime($this->booking->get('end_date')));
        $end_date->modify('+1 day');

        // Set loop interval
        $interval = DateInterval::createFromDateString('1 day');
        $period = new DatePeriod($start_date, $interval, $end_date);

        $months = array();

        // Loop through dates
        foreach ($period as $date) {
            // Set the first day of the month
            if (!isset($months[$date->format('n')]['start'])) {
                $months[$date->format('n')]['start'] = $date->getTimestamp();
            }

            // Set the last day of the month
            $months[$date->format('n')]['end'] = $date->getTimestamp();
        }

        // Output Calendar Editor
        foreach ($months as $month => $days) {
            $month_object = DateTime::createFromFormat('!m', $month);
            $output .= '<h3>' . wpbs_date_i18n('F', $month_object->getTimestamp()) . '</h3>';

            $calendar_args = array(
                'current_year' => date('Y', $days['start']),
                'current_month' => date('n', $days['start']),
                'booking_view' => true,
                'booking_start_date' => $days['start'],
                'booking_end_date' => $days['end'],
                'skip_ical_events' => true
            );
            $calendar_editor_outputter = new WPBS_Calendar_Editor_Outputter($this->calendar, $calendar_args);
            $output .= $calendar_editor_outputter->get_display();
        }

        return $output;
    }


    /**
     * Add a counter to the Notes tab 
     * 
     * @return string
     * 
     */
    protected function get_notes_count(){
        $notes = $this->get_notes();

        if(!$notes){
            return false;
        }

        $count = count($notes);

        return '<span class="wpbs-booking-modal-notes-count">'.$count.'</span>';
    }

    /**
     * Get calendar legends as <option> tags
     *
     * @return string
     *
     */
    protected function get_legends_as_options()
    {

        $legend_items = wpbs_get_legend_items(array('calendar_id' => $this->calendar->get('id')));

        $output = '';
        foreach ($legend_items as $legend_item) {

            $output .= '<option value="' . esc_attr($legend_item->get('id')) . '">' . $legend_item->get_name(wpbs_get_locale()) . '</option>';

        }

        return $output;
    }

    /**
     * Get the email addresses submitted in the form
     *
     * @return array
     *
     */
    protected function get_email_addresses()
    {

        $emails = array();

        foreach ($this->booking->get('fields') as $field) {
            if ($field['type'] != 'email') {
                continue;
            }

            if (empty($field['user_value'])) {
                continue;
            }

            $emails[] = $field['user_value'];
        }

        if (empty($emails)) {
            return false;
        }

        return $emails;

    }

    /**
     * Get the email addresses submitted in the form as <option> tags
     *
     * @return string
     *
     */
    protected function get_email_addresses_as_options()
    {

        $emails = $this->get_email_addresses();

        $output = '';

        if (!empty($emails)) {
            foreach ($emails as $email) {
                $output .= '<option value="' . $email . '">' . $email . '</option>';
            }
        }

        return $output;

    }

}
