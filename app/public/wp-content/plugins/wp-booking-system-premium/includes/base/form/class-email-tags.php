<?php

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

class WPBS_Email_Tags
{

    /**
     * The WPBS_Form
     *
     * @access protected
     * @var    WPBS_Form
     *
     */
    protected $form = null;

    /**
     * The WPBS_Calendar
     *
     * @access protected
     * @var    WPBS_Calendar
     *
     */
    protected $calendar = null;

    /**
     * The booking id
     *
     * @access protected
     * @var    int
     *
     */
    protected $booking_id = null;

    /**
     * The form fields
     *
     * @access protected
     * @var    array
     *
     */
    protected $form_fields = null;

    /**
     * The language of the email
     *
     * @access protected
     * @var    string
     *
     */
    protected $language;

    /**
     * Booking Start Date
     *
     * @access protected
     * @var    string
     *
     */
    protected $booking_start_date;

    /**
     * Booking End Date
     *
     * @access protected
     * @var    string
     *
     */
    protected $booking_end_date;

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

    }

    /**
     * Replaces the email tags with the correct values submitted in the form.
     *
     * @param string $text
     *
     * @return string
     *
     */
    public function parse($text)
    {
        // Exit if $text is empty
        if (empty($text)) {
            return false;
        }

        $booking = wpbs_get_booking($this->booking_id);

        // Get the order
        $payment = wpbs_get_payment_by_booking_id($this->booking_id);

        // Get email tags
        $tags = wpbs_form_get_email_tags($text);

        // Loop through them
        if ($tags) {
            foreach ($tags as $tag) {

                // Get the id of the tag
                $tag_id = wpbs_form_get_email_tag_id($tag);

                switch ($tag_id) {
                    case 'All Fields':
                        $all_fields = '<table>';

                        $all_fields .= '<tr class="wpbs-table-first-row"><th style="text-align:left;"><strong>' . wpbs_get_form_default_string($this->form->get('id'), 'booking_id', $this->language) . '</strong></th><td>#' . $this->booking_id . '</td></tr>';
                        // Add Dates
                        $all_fields .= '<tr><th style="text-align:left;"><strong>' . wpbs_get_form_default_string($this->form->get('id'), 'start_date', $this->language) . '</strong></th><td>' . wpbs_date_i18n(get_option('date_format'), $this->booking_start_date) . '</td></tr>';
                        $all_fields .= '<tr><th style="text-align:left;"><strong>' . wpbs_get_form_default_string($this->form->get('id'), 'end_date', $this->language) . '</strong></th><td>' . wpbs_date_i18n(get_option('date_format'), $this->booking_end_date) . '</td></tr>';

                        $all_fields .= apply_filters('wpbs_form_mailer_all_fields_before', '', $payment, $this->language);

                        /**
                         * Get the fields that are hidden by the conditional logic.
                         * 
                         */
                        $conditional_logic = new WPBS_Form_Conditional_Logic($this->form_fields, $this->booking_start_date, $this->booking_end_date, [
                            'selection_style' => wpbs_get_booking_meta($this->booking_id, 'selection_style', true),
                            'calendar_id' => $this->calendar->get('id')
                        ]);
                        $this->conditional_logic_hidden_fields = $conditional_logic->get_hidden_fields();

                        // Loop through fields
                        foreach ($this->form_fields as $form_field) {

                            // Skip excluded fields
                            if (in_array($form_field['type'], wpbs_get_excluded_fields())) {
                                continue;
                            }

                            // Skip field if it was hidden in the form by the conditional logic.
                            if(array_key_exists($form_field['id'], $this->conditional_logic_hidden_fields)){
                                continue;
                            }

                            // Get field name
                            $field_name = $this->get_form_field_translation($form_field['values'], 'label');

                            // Get value
                            $value = $this->parse_email_tags__dynamic_field($form_field);

                            // Maybe skip empty fields
                            if(apply_filters('wpbs_email_tags_all_fields_skip_empty', false) && $value == '-'){
                                continue;
                            }

                            $all_fields .= '<tr><th style="text-align:left;"><strong>' . $field_name . '</strong></th><td>' . $value . '</td></tr>';
                        }

                        $all_fields .= '</table>';

                        // Add Order Details
                        if ($payment !== false) {
                            $all_fields .= $this->parse_email_tags__order_details($payment);
                        }

                        $all_fields .= apply_filters('wpbs_form_mailer_all_fields_after', '', $payment, $this->language);

                        $text = str_replace($tag, $all_fields, $text);
                        break;
                    case 'Start Date':
                        $text = str_replace($tag, wpbs_date_i18n(get_option('date_format'), $this->booking_start_date), $text);
                        break;
                    case 'End Date':
                        $text = str_replace($tag, wpbs_date_i18n(get_option('date_format'), $this->booking_end_date), $text);
                        break;
                    case 'Booking Date':
                        $text = str_replace($tag, wpbs_date_i18n(get_option('date_format'), strtotime($booking->get('date_created'))), $text);
                        break;
                    case 'Booking ID':
                        $booking_id = apply_filters('wpbs_email_tags_booking_id', '#' . $this->booking_id);
                        $text = str_replace($tag, $booking_id, $text);
                        break;
                    case 'Calendar Title':
                        $calendar_name = $this->calendar->get_name($this->language);
                        $text = str_replace($tag, $calendar_name, $text);
                        break;
                    case 'Order Details':
                        if ($payment !== false) {
                            $order_details = $this->parse_email_tags__order_details($payment);
                        } else {
                            $order_details = __('No payment was received for this booking.', 'wp-booking-system');
                        }
                        $text = str_replace($tag, $order_details, $text);
                        break;
                    case 'Final Payment Link':
                        $final_payment_link = '';
                        $settings = get_option('wpbs_settings', array());
                        if (isset($settings['payment_part_payments_method']) && $settings['payment_part_payments_method'] == 'initial' && $payment !== false) {

                            if ($payment->get('order_status') == 'completed' && $payment->is_deposit_paid() && !$payment->is_final_payment_paid() && !empty($payment->get('order_id'))) {
                                $final_payment_link = get_permalink($settings['payment_part_payments_page']) . '?wpbs-payment-id=' . $payment->get('order_id');
                            }
                        }

                        $text = str_replace($tag, $final_payment_link, $text);
                        break;
                    case 'Outstanding Amount':
                        $outstanding_amount = __('No payment was received for this booking.', 'wp-booking-system');
                        if($payment !== false){
                            if(!$payment->is_part_payment() && $payment->get_payment_method() != 'bank_transfer' ){
                                $outstanding_amount = wpbs_get_formatted_price('0', $payment->get_currency());
                            } elseif ($payment->is_deposit_paid() && !$payment->is_final_payment_paid()) {
                                $outstanding_amount = wpbs_get_formatted_price($payment->get_total_second_payment(), $payment->get_currency());
                            } elseif (!$payment->is_deposit_paid() && !$payment->is_final_payment_paid()) {
                                $outstanding_amount = wpbs_get_formatted_price($payment->get_total(), $payment->get_currency());
                            } elseif ($payment->is_deposit_paid() && $payment->is_final_payment_paid()) {
                                $outstanding_amount = wpbs_get_formatted_price(0, $payment->get_currency());
                            }
                        }

                        $text = str_replace($tag, $outstanding_amount, $text);
                        break;
                    
                    case 'Deposit - First Payment':
                    case 'Deposit – First Payment':
                        $outstanding_amount = __('No payment was received for this booking.', 'wp-booking-system');
                        if($payment !== false){
                            if($payment->is_part_payment()){
                                $outstanding_amount = wpbs_get_formatted_price($payment->get_total_first_payment(), $payment->get_currency());
                            } else {
                                $outstanding_amount = wpbs_get_formatted_price(0, $payment->get_currency());
                            }
                        }

                        $text = str_replace($tag, $outstanding_amount, $text);
                        break;

                    case 'Deposit - Second Payment':
                    case 'Deposit – Second Payment':
                        $outstanding_amount = __('No payment was received for this booking.', 'wp-booking-system');
                        if($payment !== false){
                            if($payment->is_part_payment()){
                                $outstanding_amount = wpbs_get_formatted_price($payment->get_total_second_payment(), $payment->get_currency());
                            } else {
                                $outstanding_amount = wpbs_get_formatted_price(0, $payment->get_currency());
                            }
                        }

                        $text = str_replace($tag, $outstanding_amount, $text);
                        break;
                    
                    case 'Total Amount':
                        $total_amount = __('No payment was received for this booking.', 'wp-booking-system');
                        if($payment !== false){
                            $total_amount = wpbs_get_formatted_price($payment->get_total(), $payment->get_currency());
                        }
                        $text = str_replace($tag, $total_amount, $text);
                        break;
                    
                    case 'IP Address':
                        $text = str_replace($tag, wpbs_get_booking_meta($this->booking_id, 'customer_ip', true), $text);
                        break;
                    
                    case 'Number of Nights': 
                        $difference = ($this->booking_end_date - $this->booking_start_date) / DAY_IN_SECONDS;
                        $text = str_replace($tag, $difference, $text);
                        break;

                    case 'Number of Days': 
                        $difference = 1 + ($this->booking_end_date - $this->booking_start_date) / DAY_IN_SECONDS;
                        $text = str_replace($tag, $difference, $text);
                        break;
                    case 'Calendar Assigned User Emails':
                        $emails = '';
                        $user_ids = wpbs_get_calendar_meta($this->calendar->get('id'), 'user_permission');
                        if($user_ids){
                            foreach($user_ids as $user_id){
                                $userdata = get_userdata($user_id);
                                $emails .= $userdata->user_email . ',';
                            }

                            $emails = trim($emails, ',');
                        }
                        $text = str_replace($tag, $emails, $text);
                        break;

                    default:
                        // Dynamic Field

                        // Search for the matching form field
                        foreach ($this->form_fields as $form_field) {
                            // Skip of not the one we're looking for
                            if ($form_field['id'] != $tag_id) {
                                continue;
                            }

                            // Get value
                            $value = $this->parse_email_tags__dynamic_field($form_field);

                            // If found, replace with form value
                            $text = str_replace($tag, $value, $text);
                        }

                        // Filter for parsing custom tags
                        $text = apply_filters('wpbs_form_mailer_custom_tag', $text, $tag, $payment, $this->language, $this->booking_start_date, $this->booking_end_date, $this->calendar, $booking);

                        $text = apply_filters('wpbs_dynamic_tag', $text, $tag, [
                            'start_date' => $this->booking_start_date,
                            'end_date' => $this->booking_end_date,
                            'calendar' => $this->calendar,
                            'booking' => $booking,
                            'payment' => $payment,
                            'language' => $this->language,
                        ]);

                }

            }
        }
        
        return $text;
    }

    /**
     * Helper function to get the order details
     *
     * @param WPBS_Payment $payment
     *
     * @return string
     *
     */
    private function parse_email_tags__order_details($payment)
    {

        if ($payment === false) {
            return '';
        }

        $order_details = '<h2 class="wpbs-email-your-order-heading">' . wpbs_get_payment_default_string('your_order', $this->language) . '</h2>';


        $order_details .= '<div class="wpbs-email-your-order-table"><table>';

        $line_items = $payment->get_localized_line_items($this->language);

        $hide_empty_line_items = wpbs_get_form_meta($this->form->get('id'), 'hide_zero_line_items', true);

        $i = 0; 
        foreach ($line_items as $line_item) {

            // Skip zero value line items if option is enabled
            if($hide_empty_line_items && $line_item['price'] == 0){
                continue;
            }

            $order_details .= '
                <tr'.($i++ == 0 ? ' class="wpbs-table-first-row"' : '').'>
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

        if($payment->get('order_status') == 'error'){
            $order_details .= '
                <tr>
                    <th style="text-align:left;">
                        <strong>' . __('Payment Status', 'wp-booking-system') . '</strong>
                    </th>
                    <td><strong style="color:#E64A19">' . __('FAILED', 'wp-booking-system') . '</strong></td>
                </tr>';    

        }


        $order_details .= '</table></div>';

        return $order_details;
    }

    /**
     * Helper function to get the value of a field
     *
     * @param array $form_field
     *
     * @return string
     *
     */
    private function parse_email_tags__dynamic_field($form_field)
    {

        // Get the value
        $value = (isset($form_field['user_value'])) ? $form_field['user_value'] : '';

        // Handle Pricing options differently
        if (wpbs_form_field_is_product($form_field['type'])) {
            $value = wpbs_get_form_field_product_values($form_field);
        }

        // Handle empty strings and implode arrays
        $value = wpbs_get_field_display_user_value($value);

        // Add line breaks for textareas
        if ($form_field['type'] == 'textarea') {
            $value = nl2br($value);
        }

        // Translate payment method field
        if ($form_field['type'] == 'payment_method') {
            $value = apply_filters('wpbs_form_outputter_payment_method_name_' . $value, '', $this->language);
        }

        return $value;
    }

    /**
     * Helper function to get translations
     *
     * @param array $values
     * @param string $key
     *
     * @return string
     *
     */
    protected function get_form_field_translation($values, $key)
    {
        if (array_key_exists($this->language, $values) && !empty($values[$this->language][$key])) {
            return $values[$this->language][$key];
        }

        return $values['default'][$key];
    }

}
