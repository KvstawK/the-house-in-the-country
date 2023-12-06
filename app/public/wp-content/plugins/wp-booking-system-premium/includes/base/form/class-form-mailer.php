<?php

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

class WPBS_Form_Mailer extends WPBS_Mailer
{

    /**
     * The WPBS_Form
     *
     * @access public
     * @var    WPBS_Form
     *
     */
    public $form = null;

    /**
     * The WPBS_Calendar
     *
     * @access public
     * @var    WPBS_Calendar
     *
     */
    public $calendar = null;

    /**
     * The booking
     *
     * @access public
     * @var    WPBS_Booking
     *
     */
    public $booking = null;

    /**
     * The booking id
     *
     * @access public
     * @var    int
     *
     */
    public $booking_id = null;

    /**
     * The form fields
     *
     * @access public
     * @var    array
     *
     */
    public $form_fields = null;

    /**
     * The language of the email
     *
     * @access public
     * @var    string
     *
     */
    public $language;

    /**
     * Booking Start Date
     *
     * @access public
     * @var    string
     *
     */
    public $booking_start_date;

    /**
     * Booking End Date
     *
     * @access public
     * @var    string
     *
     */
    public $booking_end_date;

    /**
     * Constructor
     *
     * @param WPBS_Form $form
     * @param array     $args
     *
     */
    public function __construct($form, $calendar, $booking_id, $form_fields, $language, $booking_start_date, $booking_end_date)
    {

        /**
         * Set the form
         *
         */
        $this->form = $form;

        /**
         * Set the calendar
         *
         */
        $this->calendar = $calendar;

        /**
         * Set the booking id
         *
         */
        $this->booking_id = $booking_id;

        /**
         * Set the booking object
         *
         */
        $this->booking = wpbs_get_booking($booking_id);

        /**
         * Set the form fields
         *
         */
        $this->form_fields = $form_fields;

        /**
         * Set the language
         *
         */
        $this->language = $language;

        /**
         * Set the booking dates
         *
         */
        $this->booking_start_date = $booking_start_date;
        $this->booking_end_date = $booking_end_date;

        /**
         * Load the Email Tags class
         * 
         */
        $this->email_tags = new WPBS_Email_Tags($this->form, $this->calendar, $this->booking_id, $this->form_fields, $this->language, $this->booking_start_date, $this->booking_end_date);

        /**
         * Attach iCalendar file if necessary
         * 
         */
        add_action('wpbs_form_mailer_attachments', array($this, 'attach_icalendar_file'), 10, 5);

    }

    public function prepare($type)
    {

        switch_to_locale(wpbs_language_to_locale($this->language));

        $this->type = $type;

        // Check if $type is a valid notification type
        if (!in_array($type, array('user', 'admin', 'payment', 'payment_success', 'reminder', 'followup'))) {
            return false;
        }

        // Check if notification is enabled
        $notification = $this->get_field('enable', $type);
        if ($notification != 'on') {
            return false;
        }

        // Set Fields
        $this->send_to = $this->email_tags->parse($this->get_field('send_to', $type));
        $this->send_to_cc = $this->email_tags->parse($this->get_field('send_to_cc', $type));
        $this->send_to_bcc = $this->email_tags->parse($this->get_field('send_to_bcc', $type));
        $this->from_name = $this->email_tags->parse($this->get_field('from_name', $type));
        $this->from_email = $this->email_tags->parse($this->get_field('from_email', $type));
        $this->reply_to = $this->email_tags->parse($this->get_field('reply_to', $type));
        $this->subject = $this->email_tags->parse($this->get_field('subject', $type));
        $this->message = $this->email_tags->parse(do_shortcode(nl2br($this->get_field('message', $type))));
        $this->attachments = apply_filters('wpbs_form_mailer_attachments', array(), $type, $this->form, $this->calendar, $this->booking_id);

        // Add "Manage Booking" link to the end of the email for admin emails.
        if($this->type == 'admin' && apply_filters('wpbs_email_show_manage_booking_link', true)){
            
            $manage_booking_url = apply_filters(
                'wpbs_email_manage_booking_url', 
                add_query_arg(
                    array(
                        'page' => 'wpbs-calendars', 
                        'subpage' => 'edit-calendar', 
                        'calendar_id' => $this->calendar->get('id'), 
                        'booking_id' => $this->booking_id
                    ), 
                    admin_url('admin.php')
                ), 
                $this->calendar->get('id'), 
                $this->booking_id
            );

            $manage_booking_label = apply_filters('wpbs_email_manage_booking_label', __('Manage Booking in WP Admin', 'wp-booking-system'));

            $this->message = '<a style="display:block; text-align:center;" href="' . $manage_booking_url . '">' . $manage_booking_label . '</a>' . $this->message;
        }

    }

    /**
     * Helper function to get the translated value of a field
     *
     * @param string $field
     * @param string $type
     *
     * @return string
     *
     */
    protected function get_field($field, $type)
    {
        return wpbs_get_translated_form_meta($this->form->get('id'), $type . '_notification_' . $field, $this->language);
    }

    /**
     * Generates the iCalendar file and attaches it to the user notification email
     *
     * @param array $attachments
     * @param string $type
     * @param WPBS_Form $form
     * @param WPBS_Calendar $calendar
     * @param int $booking_id
     *
     */
    public function attach_icalendar_file($attachments, $type, $form, $calendar, $booking_id)
    {

        if (!in_array($type, array('user','reminder')) ) {
            return $attachments;
        }

        if ($type == 'user' && wpbs_get_form_meta($form->get('id'), 'user_notification_ical_file', true) != 'on') {
            return $attachments;
        }

        if ($type == 'reminder' && wpbs_get_form_meta($form->get('id'), 'reminder_notification_ical_file', true) != 'on') {
            return $attachments;
        }

        $start_date = DateTime::createFromFormat('Y-m-d H:i:s', $this->booking->get('start_date'));
        $end_date = DateTime::createFromFormat('Y-m-d H:i:s', $this->booking->get('end_date'));

        $selection_style = wpbs_get_booking_meta($booking_id, 'selection_style', true);
        if($selection_style == 'normal'){
            $end_date->modify('+1 day');
        }

        include_once WPBS_PLUGIN_DIR . 'includes/libs/iCal/iCalcreator.php';

        // Get timezone
        $tzid = get_option('timezone_string');

        if (empty($tzid)) {
            $date = date_create();
            $tz = date_timezone_get($date);
            $tzid = timezone_name_get($tz);
        }

        $vargs = array(
            'unique_id' => sprintf('WP Booking System - Booking #' . $booking_id),
            'TZID' => $tzid,
        );

        // Create a new calendar instance
        $v = new vcalendar($vargs);

        $v->setProperty('METHOD', 'PUBLISH');
        if(apply_filters('wpbs_ical_export_remove_calname', false) == false){
            $v->setProperty('x-wr-calname', 'WP Booking System - Booking #' . $booking_id);
            $v->setProperty('X-WR-CALDESC', 'ICS File generated with WP Booking System');
        }
        $v->setProperty('X-WR-TIMEZONE', $tzid);

        $xprops = array(
            'X-LIC-LOCATION' => $tzid,
        );

        // Create new vevent component
        $vevent = $v->newComponent('vevent');

        $vevent->setProperty('DTSTART', date('Ymd', $start_date->getTimestamp()), array('VALUE' => 'DATE'));
        $vevent->setProperty('DTEND', date('Ymd', $end_date->getTimestamp()), array('VALUE' => 'DATE'));
        $vevent->setProperty('UID', 'WPBS-' . base64_encode($booking_id));
        $vevent->setProperty('LOCATION', '');
        $vevent->setProperty('DESCRIPTION', $this->email_tags->parse($this->get_field('ical_description', $type)));
        $vevent->setProperty('SUMMARY', $this->email_tags->parse($this->get_field('ical_summary', $type)));
        $vevent->setProperty('CLASS', 'PUBLIC');
        $vevent->setProperty('STATUS', 'CONFIRMED');
        $vevent->setProperty('TRANSP', 'TRANSPARENT');
        $vevent->setProperty('COMMENT', '');
        $vevent->setProperty('ORGANIZER', $this->from_email);
        $vevent->setProperty('SEQUENCE', 0);

        $calendar = $v->createCalendar();
        $filename = WPBS_PLUGIN_DIR . 'temp/booking-' . $booking_id . '.ics';
        $path = file_put_contents($filename, $calendar);

        // Save the iCalendar file and attach it to the email
        $attachments[] = $filename;

        return $attachments;
    }

}
