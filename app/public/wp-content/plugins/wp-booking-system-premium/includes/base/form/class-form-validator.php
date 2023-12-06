<?php

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

class WPBS_Form_Validator
{

    /**
     * The WPBS_Calendar
     *
     * @access protected
     * @var    WPBS_Calendar
     *
     */
    protected $calendar = null;

    /**
     * The WPBS_Form
     *
     * @access protected
     * @var    WPBS_Form
     *
     */
    protected $form = null;

    /**
     * The $_POST data
     *
     * @access protected
     * @var    array
     *
     */
    protected $form_data = null;

    /**
     * The form fields
     *
     * @access protected
     * @var    array
     *
     */
    protected $form_fields = null;

    /**
     * Store form errors
     *
     * @access protected
     * @var    bool
     *
     */
    protected $has_errors = null;

    /**
     * Form Language
     *
     * @access protected
     * @var    string
     *
     */
    protected $language = null;

    /**
     * The plugin general settings
     *
     * @access protected
     * @var    array
     *
     */
    protected $plugin_settings = array();

    /**
     * Constructor
     *
     * @param WPBS_Form     $form
     * @param string        $form_data
     * @param WPBS_Calendar $calendar
     *
     */
    public function __construct($form, $calendar, $form_data, $language)
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
         * Set plugin settings
         *
         */
        $this->plugin_settings = get_option('wpbs_settings', array());

        /**
         * Set the form fields
         *
         */
        $this->form_fields = $form->get('fields');

        /**
         * Set the form data
         *
         */
        parse_str($form_data, $this->form_data);

        /**
         * Set the form language
         *
         */
        $this->language = ($language == 'auto' ? wpbs_get_locale() : $language);
    }

    /**
     * Sanitize POST data
     *
     */
    public function sanitize_fields()
    {

        foreach ($this->form_fields as &$field) {
            // Skip empty fields
            if (
                !isset($this->form_data['wpbs-input-' . $this->form->get('id') . '-' . $field['id']]) ||
                (empty($this->form_data['wpbs-input-' . $this->form->get('id') . '-' . $field['id']]) &&
                    strlen($this->form_data['wpbs-input-' . $this->form->get('id') . '-' . $field['id']]) === 0) // Allow zeros
            ) {
                continue;
            }

            switch ($field['type']) {

                    // Sanitize Textarea
                case 'textarea':
                    $value = $this->form_data['wpbs-input-' . $this->form->get('id') . '-' . $field['id']];
                    $value = stripslashes($value);
                    $value = sanitize_textarea_field($value);
                    $value = esc_textarea($value);
                    $field['user_value'] = apply_filters('wpbs_form_field_sanitization', $value, $this->form->get('id'), $field['id']);
                    break;

                    // Sanitize Checkbox
                case 'checkbox':
                case 'consent':
                case 'product_checkbox':
                    foreach ($this->form_data['wpbs-input-' . $this->form->get('id') . '-' . $field['id']] as $option) {
                        $value = $option;
                        $value = stripslashes($value);
                        $value = sanitize_text_field($value);
                        $value = esc_attr($value);
                        $field['user_value'][] = apply_filters('wpbs_form_field_sanitization', $value, $this->form->get('id'), $field['id']);
                    }
                    break;

                    // Coupon Code
                case 'coupon':
                    $value = $this->form_data['wpbs-input-' . $this->form->get('id') . '-' . $field['id']];
                    $value = stripslashes($value);
                    $value = sanitize_text_field($value);
                    $value = esc_attr($value);
                    $value = strtoupper($value);
                    $field['user_value'] = apply_filters('wpbs_form_field_sanitization', $value, $this->form->get('id'), $field['id']);
                    break;

                    // Sanitize everything else
                default:
                    $value = $this->form_data['wpbs-input-' . $this->form->get('id') . '-' . $field['id']];
                    $value = stripslashes($value);
                    $value = sanitize_text_field($value);
                    $value = esc_attr($value);
                    $field['user_value'] = apply_filters('wpbs_form_field_sanitization', $value, $this->form->get('id'), $field['id']);
            }
        }
    }

    /**
     * Validate POST data
     *
     */
    public function validate_fields($form_args = false, $calendar_args = false)
    {

        $calendar_args['start_date'] = wpbs_convert_js_to_php_timestamp($calendar_args['start_date']);
        $calendar_args['end_date'] = wpbs_convert_js_to_php_timestamp($calendar_args['end_date']);

        /**
         * Get the fields that are hidden by the conditional logic.
         */
        $conditional_logic = new WPBS_Form_Conditional_Logic($this->form_fields, $calendar_args['start_date'], $calendar_args['end_date'], ['selection_style' => $form_args['selection_style'], 'calendar_id' => $calendar_args['id']]);
        $this->conditional_logic_hidden_fields = $conditional_logic->get_hidden_fields();

        foreach ($this->form_fields as &$field) {

            if (!isset($field['type'])) {
                continue;
            }

            switch ($field['type']) {

                    // Validate Email
                case 'email':

                    // If email is not required, still check if it's valid
                    if (!empty($field['user_value']) && !is_email($field['user_value'])) {
                        $field['error'] = wpbs_get_form_default_string($this->form->get('id'), 'invalid_email', $this->language);
                        $this->has_errors = true;
                    }

                    // Check if required
                    $field = $this->check_if_required_and_empty($field, $form_args, $calendar_args);
                    break;
                case 'phone':
                    // If email is not required, still check if it's valid
                    if (!empty($field['user_value']) && !$this->is_phone($field['user_value'])) {
                        $field['error'] = wpbs_get_form_default_string($this->form->get('id'), 'invalid_phone', $this->language);
                        $this->has_errors = true;
                    }

                    // Check if required
                    $field = $this->check_if_required_and_empty($field, $form_args, $calendar_args);
                    break;

                case 'number':
                case 'product_number':
                    if (!empty($field['user_value']) && !empty($field['values']['default']['min']) && $field['user_value'] < absint($field['values']['default']['min'])) {
                        $field['error'] =  sprintf(wpbs_get_form_default_string($this->form->get('id'), 'invalid_number_min', $this->language), $field['values']['default']['min']);
                        $this->has_errors = true;
                    }

                    if (!empty($field['user_value']) && !empty($field['values']['default']['max']) && $field['user_value'] > absint($field['values']['default']['max'])) {
                        $field['error'] =  sprintf(wpbs_get_form_default_string($this->form->get('id'), 'invalid_number_max', $this->language), $field['values']['default']['max']);
                        $this->has_errors = true;
                    }

                    // Check if required
                    $field = $this->check_if_required_and_empty($field, $form_args, $calendar_args);
                    break;

                    // Validate Captcha
                case 'captcha':
                    $field = $this->validate_recaptcha($field, $form_args);
                    break;

                    // Validate all other fields
                default:
                    $field = $this->check_if_required_and_empty($field, $form_args, $calendar_args);
            }
        }

        $custom_validation = false;

        // Filter for custom validation
        $custom_validation = apply_filters('wpbs_form_validation', $custom_validation, [
            'start_date' => $calendar_args['start_date'],
            'end_date' => $calendar_args['end_date'],
            'form' => $this->form,
            'form_args' => $form_args,
            'form_fields' => $this->form_fields,
            'calendar' => $this->calendar,
            'calendar_args' => $calendar_args,
            'language' => $this->language,
        ]);

        $custom_validation = apply_filters('wpbs_form_validation_' . $this->form->get('id'), $custom_validation, [
            'start_date' => $calendar_args['start_date'],
            'end_date' => $calendar_args['end_date'],
            'form' => $this->form,
            'form_args' => $form_args,
            'form_fields' => $this->form_fields,
            'calendar' => $this->calendar,
            'calendar_args' => $calendar_args,
            'language' => $this->language,
        ]);

        $custom_validation = apply_filters('wpbs_form_validator_custom_field_validation', $custom_validation, $this->form, $this->calendar, $this->language, $this->form_fields);

        if ($custom_validation !== false) {

            if ($custom_validation['error'] === true) {
                $this->has_errors = true;
                $this->form_fields['form_error'] = $custom_validation['error_message'];
            }
        }
    }

    /**
     * Validate Dates
     *
     * @param array $form_args
     * @param array $calendar_args
     *
     * @return bool
     *
     */
    public function validate_dates($form_args, $calendar_args)
    {

        // Check if dates are set.
        if (empty($calendar_args['start_date']) || empty($calendar_args['end_date'])) {
            $this->has_errors = true;
            $this->form_fields['form_error'] = wpbs_get_form_default_string($this->form->get('id'), 'select_date', $this->language);
            return false;
        }

        // Check if dates are valid
        if (!is_numeric($calendar_args['start_date']) || !is_numeric($calendar_args['end_date'])) {
            $this->has_errors = true;
            $this->form_fields['form_error'] = __('Invalid date.', 'wp-booking-system');
            return false;
        }

        // Convert JS timestamp to PHP Timestamp
        $calendar_args['start_date'] = wpbs_convert_js_to_php_timestamp($calendar_args['start_date']);
        $calendar_args['end_date'] = wpbs_convert_js_to_php_timestamp($calendar_args['end_date']);

        // Filter for custom validation
        $custom_validation = apply_filters('wpbs_form_validator_custom_validation', false , $this->form, $form_args, $this->calendar, $calendar_args, $this->language, $this->form_fields);

        $custom_validation = apply_filters('wpbs_date_validation', $custom_validation, [
            'start_date' => $calendar_args['start_date'],
            'end_date' => $calendar_args['end_date'],
            'form' => $this->form,
            'form_args' => $form_args,
            'form_fields' => $this->form_fields,
            'calendar' => $this->calendar,
            'calendar_args' => $calendar_args,
            'language' => $this->language,
        ]);

        $custom_validation = apply_filters('wpbs_date_validation_' . $this->form->get('id'), $custom_validation, [
            'start_date' => $calendar_args['start_date'],
            'end_date' => $calendar_args['end_date'],
            'form' => $this->form,
            'form_args' => $form_args,
            'form_fields' => $this->form_fields,
            'calendar' => $this->calendar,
            'calendar_args' => $calendar_args,
            'language' => $this->language,
        ]);

        if ($custom_validation !== false) {
            if(isset($custom_validation['form_args'])){
                $form_args = $custom_validation['form_args'];
            }

            if(isset($custom_validation['calendar_args'])){
                $calendar_args = $custom_validation['calendar_args'];
            }

            if ($custom_validation['error'] === true) {
                $this->has_errors = true;
                $this->form_fields['form_error'] = $custom_validation['error_message'];
                return false;
            }
        }

        // Get the number of selected days
        $number_of_selected_days = ($calendar_args['end_date'] - $calendar_args['start_date']) / DAY_IN_SECONDS;
        if ($form_args['selection_style'] != 'split') {
            $number_of_selected_days += 1;
        }

        $weekdays = function_exists('wpbs_get_translated_weekdays') ? wpbs_get_translated_weekdays() : wpbs_get_weekdays();

        // Check if the booking must start on a specific day
        if (isset($form_args['booking_start_day']) && $form_args['booking_start_day'] != 0) {
            if ($form_args['booking_start_day'] != date('N', $calendar_args['start_date'])) {
                $this->has_errors = true;
                $this->form_fields['form_error'] = sprintf(wpbs_get_form_default_string($this->form->get('id'), 'start_day', $this->language), $weekdays[$form_args['booking_start_day'] - 1]);
                return false;
            }
        }

        // Check if the booking must end on a specific day
        if (isset($form_args['booking_end_day']) && $form_args['booking_end_day'] != 0) {
            if ($form_args['booking_end_day'] != date('N', $calendar_args['end_date'])) {
                $this->has_errors = true;
                $this->form_fields['form_error'] = sprintf(wpbs_get_form_default_string($this->form->get('id'), 'end_day', $this->language), $weekdays[$form_args['booking_end_day'] - 1]);
                return false;
            }
        }

        // Check if there is a miminum number of days.
        if (isset($form_args['minimum_days']) && $form_args['minimum_days'] != 0) {
            if ($number_of_selected_days < $form_args['minimum_days']) {
                $this->has_errors = true;
                $this->form_fields['form_error'] = sprintf(wpbs_get_form_default_string($this->form->get('id'), 'minimum_selection', $this->language), $form_args['minimum_days']);
                return false;
            }
        }

        // Check if there is a maximum number of days.
        if (isset($form_args['maximum_days']) && $form_args['maximum_days'] != 0) {
            if ($number_of_selected_days > $form_args['maximum_days']) {
                $this->has_errors = true;
                $this->form_fields['form_error'] = sprintf(wpbs_get_form_default_string($this->form->get('id'), 'maximum_selection', $this->language), $form_args['maximum_days']);
                return false;
            }
        }

        // Do not allow single date selection when split selection is enabled.
        if ($form_args['selection_style'] == 'split' && $form_args['selection_type'] == 'multiple' && $number_of_selected_days == 0 && apply_filters('wpbs_disable_split_single_day_selection', true)) {
            $this->has_errors = true;
            $this->form_fields['form_error'] = sprintf(wpbs_get_form_default_string($this->form->get('id'), 'minimum_selection', $this->language), 1);
            return false;
        }

        /**
         * Check if dates are available
         * 
         */
        if (wpbs_check_if_date_range_is_bookable($this->calendar->get('id'), date('Ymd', $calendar_args['start_date']), date('Ymd', $calendar_args['end_date'])) === false) {
            $this->has_errors = true;
            $this->form_fields['form_error'] = wpbs_get_form_default_string($this->form->get('id'), 'invalid_selection', $this->language);
            return false;
        }
    }

    /**
     * Set the general error message if validation failed.
     *
     */
    public function set_error_message()
    {
        if ($this->has_errors == true && empty($this->form_fields['form_error'])) {
            $this->form_fields['form_error'] = wpbs_get_form_default_string($this->form->get('id'), 'validation_errors', $this->language);
        }
    }

    /**
     * Check if there are any errors
     *
     * @return bool
     *
     */
    public function has_errors()
    {
        return $this->has_errors;
    }

    /**
     * Returns the form fields populated with errors and POST data
     *
     * @return array
     *
     */
    public function get_form_fields()
    {
        return $this->form_fields;
    }

    /**
     * Check if a field is required and validates it
     *
     * @param array $field
     *
     * @return array
     *
     */
    protected function check_if_required_and_empty($field, $form_args, $calendar_args)
    {
        if (
            (isset($field['values']['default']['required']) && $field['values']['default']['required'] == 'on' && !array_key_exists($field['id'], $this->conditional_logic_hidden_fields))
            ||
            $field['type'] == 'payment_method'
            ||
            $field['type'] == 'consent'
        ) {
            if (!isset($field['user_value']) || (empty($field['user_value']) && strlen($field['user_value']) === 0)) {
                $field['error'] = wpbs_get_form_default_string($this->form->get('id'), 'required_field', $this->language);
            }
        }


        $field = apply_filters('wpbs_field_validation', $field, [
            'start_date' => $calendar_args['start_date'],
            'end_date' => $calendar_args['end_date'],
            'form' => $this->form,
            'form_args' => $form_args,
            'form_fields' => $this->form_fields,
            'calendar' => $this->calendar,
            'calendar_args' => $calendar_args,
            'language' => $this->language,
        ]);

        $field = apply_filters('wpbs_field_validation_' . $this->form->get('id'), $field, [
            'start_date' => $calendar_args['start_date'],
            'end_date' => $calendar_args['end_date'],
            'form' => $this->form,
            'form_args' => $form_args,
            'form_fields' => $this->form_fields,
            'calendar' => $this->calendar,
            'calendar_args' => $calendar_args,
            'language' => $this->language,
        ]);

        $field = apply_filters('wpbs_field_validation_' . $this->form->get('id') . '_' . $field['id'], $field, [
            'start_date' => $calendar_args['start_date'],
            'end_date' => $calendar_args['end_date'],
            'form' => $this->form,
            'form_args' => $form_args,
            'form_fields' => $this->form_fields,
            'calendar' => $this->calendar,
            'calendar_args' => $calendar_args,
            'language' => $this->language,
        ]);

        if(isset($field['error']) && !empty($field['error'])){
            $this->has_errors = true;
        }

        return $field;
    }

    /**
     * Check if the reCAPTCHA v2 is valid
     *
     * @param array $field
     *
     * @return array
     *
     */
    protected function validate_recaptcha($field, $args)
    {

        // Check if we're on a payment confirmation screen. The form is rendered again, but captcha doesn't need to be validated.
        $payment_confirmation_screen = apply_filters('wpbs_validate_recaptcha_payment_confirmation', false, $this->form_data);
        if ($payment_confirmation_screen === true) {
            return $field;
        }

        if (isset($args['manual_booking']) && $args['manual_booking']) {
            return $field;
        }

        $captcha = $this->form_data['g-recaptcha-response'];

        if (empty($captcha)) {
            $field['error'] = wpbs_get_form_default_string($this->form->get('id'), 'required_field', $this->language);
            $this->has_errors = true;
            return $field;
        }

        $recaptcha_type = wpbs_get_recaptcha_type();
        $recaptcha_keys = wpbs_get_recaptcha_keys();

        $recaptcha_secret_key = ($recaptcha_keys !== false) ? $recaptcha_keys['secret_key'] : '';

        $recaptcha_response_get = wp_remote_get("https://www.google.com/recaptcha/api/siteverify?secret=" . $recaptcha_secret_key . "&response=" . $captcha . "&remoteip=" . $_SERVER['REMOTE_ADDR']);
        $recaptcha_response = json_decode($recaptcha_response_get['body'], true);

        if ($recaptcha_type == 'v2') {
            if ($recaptcha_response['success'] != true) {
                $field['error'] = implode(', ', $recaptcha_response['error-codes']);
                $this->has_errors = true;
            }
        }

        if ($recaptcha_type == 'v3') {
            if (!isset($recaptcha_response['action']) || $recaptcha_response['action'] != 'wpbs_form' || $recaptcha_response['score'] < 0.5) {
                $field['error'] = 'Failed reCAPTCHA validation';
                $this->has_errors = true;
                $this->form_fields['form_error'] = 'Google reCAPTCHA v3 validation failed.';
            }
        }

        return $field;
    }

    /**
     * Validate Phone Number
     *
     * @param string $text
     *
     * @return bool
     */
    private function is_phone($text)
    {
        $valid = true;
        // Check if it only contains numbers, (, ), -, +
        if (!preg_match('/^[0-9 +\-\(\)]+$/', $text)) {
            $valid = false;
        }

        // Check if it contains at least 9 numbers or at most 15 numbers
        $numeric_length = strlen(preg_replace('/\D/', '', $text));
        if ($numeric_length < 8 || $numeric_length > 15) {
            $valid = false;
        }

        $valid = apply_filters('wpbs_phone_number_validation', $valid, $text);

        return $valid;
    }
}
