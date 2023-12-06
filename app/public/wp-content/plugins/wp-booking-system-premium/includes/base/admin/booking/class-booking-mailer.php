<?php

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

class WPBS_Booking_Mailer extends WPBS_Mailer
{

    /**
     * The $_POST data
     *
     * @access protected
     * @var    array
     *
     */
    protected $post_data;

    /**
     * The booking
     *
     * @access protected
     * @var    WPBS_Booking
     *
     */
    protected $booking;

    /**
     * The language of the email
     *
     * @access protected
     * @var    string
     *
     */
    protected $language;

    public function __construct($booking, $post_data)
    {

        /**
         * Set booking
         *
         */
        $this->booking = $booking;

        /**
         * Set the form fields
         *
         */
        $this->post_data = $post_data;

        /**
         * Set the language
         *
         */
        $this->language = wpbs_get_booking_meta($this->booking->get('id'), 'submitted_language', true);

    }

    /**
     * Prepare the email fields
     *
     * @param string $type
     *
     */
    public function prepare($type)
    {

        switch_to_locale(wpbs_language_to_locale($this->language));

        $this->type = $type;

        // Check if $type is a valid notification type
        if (!in_array($type, array('accept_booking', 'customer'))) {
            return false;
        }

        // Set Fields
        $this->send_to = apply_filters('wpbs_booking_mailer_send_to', $this->get_field('send_to', $type), $type, $this->booking, $this->post_data);
        $this->send_to_cc = apply_filters('wpbs_booking_mailer_send_to_cc', $this->get_field('send_to_cc', $type), $type, $this->booking, $this->post_data);
        $this->send_to_bcc = apply_filters('wpbs_booking_mailer_send_to_bcc', $this->get_field('send_to_bcc', $type), $type, $this->booking, $this->post_data);
        $this->from_name = apply_filters('wpbs_booking_mailer_from_name', $this->get_field('from_name', $type), $type, $this->booking, $this->post_data);
        $this->from_email = apply_filters('wpbs_booking_mailer_from_email', $this->get_field('from_email', $type), $type, $this->booking, $this->post_data);
        $this->reply_to = apply_filters('wpbs_booking_mailer_reply_to', $this->get_field('reply_to', $type), $type, $this->booking, $this->post_data);
        $this->subject = apply_filters('wpbs_booking_mailer_subject', $this->get_field('subject', $type), $type, $this->booking, $this->post_data);
        $this->message = apply_filters('wpbs_booking_mailer_message', $this->get_field('message', $type), $type, $this->booking, $this->post_data);
        $this->attachments = apply_filters('wpbs_booking_mailer_attachments', array(), $type, $this->booking, $this->post_data);

        if ($this->get_field('include_booking_details', $type) !== null && !empty($this->get_field('include_booking_details', $type))) {
            $this->include_booking_details();
        }

    }

    /**
     * Helper function to get the value of a field
     *
     * @param string $field
     * @param string $type
     *
     * @return string
     *
     */
    protected function get_field($field, $type)
    {
        if (isset($this->post_data['booking_email_' . $type . '_' . $field])) {
            if ($field == 'message') {
                return wp_kses_post($this->post_data['booking_email_' . $type . '_' . $field]);
            } else {
                return sanitize_text_field($this->post_data['booking_email_' . $type . '_' . $field]);
            }
        }
    }

    /**
     * Helper function to include the booking details
     *
     * @param string $field
     * @param string $type
     *
     * @return string
     *
     */
    protected function include_booking_details()
    {

        // Get the order
        $payment = wpbs_get_payment_by_booking_id($this->booking->get('id'));

        $this->message .= '<br /><br />';

        $this->message .= '<table>';

        $this->message .= '<tr class="wpbs-table-first-row"><th style="text-align:left;"><strong>' . wpbs_get_form_default_string($this->booking->get('form_id'), 'booking_id', $this->language) . '</strong></th><td>#' . $this->booking->get('id') . '</td></tr>';
        $this->message .= '<tr><th style="text-align:left;"><strong>' . wpbs_get_form_default_string($this->booking->get('form_id'), 'start_date', $this->language) . '</strong></th><td>' . wpbs_date_i18n(get_option('date_format'), strtotime($this->booking->get('start_date'))) . '</td></tr>';
        $this->message .= '<tr><th style="text-align:left;"><strong>' . wpbs_get_form_default_string($this->booking->get('form_id'), 'end_date', $this->language) . '</strong></th><td>' . wpbs_date_i18n(get_option('date_format'), strtotime($this->booking->get('end_date'))) . '</td></tr>';
        $this->message .= '<tr><th style="text-align:left;"><strong>' . wpbs_get_form_default_string($this->booking->get('form_id'), 'booked_on', $this->language) . '</strong></th><td>' . wpbs_date_i18n(get_option('date_format'), strtotime($this->booking->get('date_created'))) . '</td></tr>';

        $this->message .= apply_filters('wpbs_booking_mailer_all_fields_before', '', $payment, $this->language);

        foreach ($this->booking->get('fields') as $field) {

            if (in_array($field['type'], wpbs_get_excluded_fields())) {
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
                $value = isset(wpbs_get_payment_methods()[$value]) ? apply_filters('wpbs_form_outputter_payment_method_name_' . $value, '', $this->language) : '';
            }

            // Maybe skip empty fields
            if(apply_filters('wpbs_email_tags_all_fields_skip_empty', false) && $value == '-'){
                continue;
            }

            $this->message .= '<tr><th style="text-align:left;"><strong>' . $this->get_form_field_translation($field['values'], 'label', $this->language) . '</strong></th><td>' . $value . '</td></tr>';

        }

        $this->message .= '</table>';

        if (!empty($payment)) {

            $this->message .= '<br>';

            $line_items = $payment->get_localized_line_items($this->language);

            $this->message .= '<h2>' . wpbs_get_payment_default_string('your_order', $this->language) . '</h2>';

            $this->message .= '<table>';

            $i = 0;
            foreach ($line_items as $line_item) {
                $this->message .= '
                    <tr' . ($i++ == 0 ? ' class="wpbs-table-first-row"' : '') . '>
                        <th style="text-align:left;">
                            <strong>'
                    . $line_item['label'] .
                    (isset($line_item['description']) && !empty($line_item['description']) ? '<br><small>' . $line_item['description'] . '</small>' : '') .
                    '</strong>
                        </th>
                        <td>'
                    . $line_item['value'] .
                    '</td>
                    </tr>';
            }

            $this->message .= '</table>';

            $this->message .= '<br>';
        }

        $this->message .= apply_filters('wpbs_booking_mailer_all_fields_after', '', $payment, $this->language);

    }

    /**
     * Helper function to get translations
     *
     * @param array $values
     * @param string $key
     * @param string $language
     *
     * @return string
     *
     */
    protected function get_form_field_translation($values, $key, $language)
    {
        if (array_key_exists($language, $values) && !empty($values[$language][$key])) {
            return $values[$language][$key];
        }

        return $values['default'][$key];
    }

}
