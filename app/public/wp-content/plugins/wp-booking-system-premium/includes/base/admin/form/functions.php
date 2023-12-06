<?php

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Includes the files needed for the Forms admin area
 *
 */
function wpbs_include_files_admin_form()
{

    // Get legend admin dir path
    $dir_path = plugin_dir_path(__FILE__);

    // Include submenu page
    if (file_exists($dir_path . 'class-submenu-page-form.php')) {
        include $dir_path . 'class-submenu-page-form.php';
    }

    // Include forms list table
    if (file_exists($dir_path . 'class-list-table-forms.php')) {
        include $dir_path . 'class-list-table-forms.php';
    }

    // Include admin actions
    if (file_exists($dir_path . 'functions-actions-form.php')) {
        include $dir_path . 'functions-actions-form.php';
    }

}
add_action('wpbs_include_files', 'wpbs_include_files_admin_form');

/**
 * Register the Forms admin submenu page
 *
 */
function wpbs_register_submenu_page_forms($submenu_pages)
{

    if (!is_array($submenu_pages)) {
        return $submenu_pages;
    }

    $submenu_pages['forms'] = array(
        'class_name' => 'WPBS_Submenu_Page_Forms',
        'data' => array(
            'page_title' => __('Forms', 'wp-booking-system'),
            'menu_title' => __('Forms', 'wp-booking-system'),
            'capability' => apply_filters('wpbs_submenu_page_capability_forms', 'manage_options'),
            'menu_slug' => 'wpbs-forms',
        ),
    );

    return $submenu_pages;

}
add_filter('wpbs_register_submenu_page', 'wpbs_register_submenu_page_forms', 20);

/**
 * Groups for displaying the field types in an orderly fashion
 *
 * @return array
 *
 */
function wpbs_form_available_field_types_groups()
{
    $groups = array(
        'basic' => __('Basic Form Elements', 'wp-booking-system'),
        'advanced' => __('Advanced Form Elements', 'wp-booking-system'),
    );

    if (wpbs_is_pricing_enabled()) {
        $groups['pricing'] = __('Pricing Form Elements', 'wp-booking-system');
    }

    return $groups;
}

/**
 * Declare all the field types available when building forms
 *
 * @return array
 *
 */
function wpbs_form_available_field_types()
{
    $fields = array();

    $fields['text'] = array(
        'type' => 'text',
        'group' => 'basic',
        'supports' => array(
            'primary' => array('label', 'required'),
            'secondary' => array('placeholder', 'value', 'description', 'layout', 'class', 'hide_label', 'dynamic_population'),
        ),
        'values' => array(),
    );

    $fields['email'] = array(
        'type' => 'email',
        'group' => 'basic',
        'supports' => array(
            'primary' => array('label', 'required'),
            'secondary' => array('placeholder', 'value', 'description', 'layout', 'class', 'hide_label', 'dynamic_population'),
        ),
        'values' => array(),
    );

    $fields['phone'] = array(
        'type' => 'phone',
        'group' => 'basic',
        'supports' => array(
            'primary' => array('label', 'required'),
            'secondary' => array('placeholder', 'value', 'description', 'layout', 'class', 'hide_label', 'dynamic_population'),
        ),
        'values' => array(),
    );

    $fields['textarea'] = array(
        'type' => 'textarea',
        'group' => 'basic',
        'supports' => array(
            'primary' => array('label', 'required'),
            'secondary' => array('placeholder', 'value_textarea', 'description', 'layout', 'class', 'hide_label', 'dynamic_population'),
        ),
        'values' => array(),
    );

    $fields['dropdown'] = array(
        'type' => 'dropdown',
        'group' => 'basic',
        'supports' => array(
            'primary' => array('label', 'required', 'options'),
            'secondary' => array('placeholder', 'value', 'description', 'layout', 'class', 'hide_label', 'dynamic_population'),
        ),
        'values' => array(),
    );

    $fields['checkbox'] = array(
        'type' => 'checkbox',
        'group' => 'basic',
        'supports' => array(
            'primary' => array('label', 'required', 'options'),
            'secondary' => array('description', 'layout', 'class', 'hide_label', 'dynamic_population'),
        ),
        'values' => array(),
    );

    $fields['radio'] = array(
        'type' => 'radio',
        'group' => 'basic',
        'supports' => array(
            'primary' => array('label', 'required', 'options'),
            'secondary' => array('description', 'value', 'layout', 'class', 'hide_label', 'dynamic_population'),
        ),
        'values' => array(),
    );

    $fields['number'] = array(
        'type' => 'number',
        'group' => 'basic',
        'supports' => array(
            'primary' => array('label', 'required', 'min', 'max', 'decimals'),
            'secondary' => array('description', 'value', 'layout', 'class', 'hide_label', 'dynamic_population'),
        ),
        'values' => array(),
    );

    $fields['html'] = array(
        'type' => 'html',
        'group' => 'advanced',
        'supports' => array(
            'primary' => array('value_textarea'),
        ),
        'values' => array(),
    );

    $fields['datepicker'] = array(
        'type' => 'datepicker',
        'group' => 'advanced',
        'supports' => array(
            'primary' => array('label', 'required', 'date_format'),
            'secondary' => array('description', 'layout', 'class', 'hide_label', 'dynamic_population'),
        ),
        'values' => array(),
    );

    $fields['hidden'] = array(
        'type' => 'hidden',
        'group' => 'advanced',
        'supports' => array(
            'primary' => array('label', 'value'),
            'secondary' => array('class', 'dynamic_population'),
        ),
        'values' => array(),
    );

    $fields['captcha'] = array(
        'type' => 'captcha',
        'group' => 'advanced',
        'supports' => array(
            'primary' => array('notice_captcha', 'label'),
            'secondary' => array('hide_label'),
        ),
        'values' => array(),
    );

    $fields['consent'] = array(
        'type' => 'consent',
        'group' => 'advanced',
        'supports' => array(
            'primary' => array('label', 'checkbox_label', 'link'),
            'secondary' => array('description', 'layout', 'class', 'hide_label'),
        ),
        'values' => array(),
    );

    $fields = apply_filters('wpbs_form_available_field_types', $fields, 1);

    return $fields;

}

/**
 * Declare all the options for field types
 *
 * @return array
 *
 */
function wpbs_form_available_field_types_options()
{
    $options = array();

    $options['label'] = array('key' => 'label', 'label' => __('Label', 'wp-booking-system'), 'translatable' => true);
    $options['required'] = array('key' => 'required', 'label' => __('Required', 'wp-booking-system'), 'translatable' => false);
    $options['options'] = array('key' => 'options', 'label' => __('Options', 'wp-booking-system'), 'translatable' => true);

    $options['placeholder'] = array('key' => 'placeholder', 'label' => __('Placeholder', 'wp-booking-system'), 'translatable' => true);
    $options['value'] = array('key' => 'value', 'label' => __('Default Value', 'wp-booking-system'), 'translatable' => true);
    $options['value_textarea'] = array('key' => 'value', 'label' => __('Default Value', 'wp-booking-system'), 'input' => 'textarea', 'translatable' => true);
    $options['class'] = array('key' => 'class', 'label' => __('Custom Class', 'wp-booking-system'), 'translatable' => false);
    $options['description'] = array('key' => 'description', 'label' => __('Description', 'wp-booking-system'), 'translatable' => true);
    $options['hide_label'] = array('key' => 'hide_label', 'label' => __('Hide Label', 'wp-booking-system'), 'translatable' => false);
    $options['dynamic_population'] = array('key' => 'dynamic_population', 'label' => __('Dynamic Population', 'wp-booking-system'), 'translatable' => false);

    $options['min'] = array('key' => 'min', 'label' => __('Minimum Value', 'wp-booking-system'), 'translatable' => false);
    $options['max'] = array('key' => 'max', 'label' => __('Maximum Value', 'wp-booking-system'), 'translatable' => false);
    $options['decimals'] = array('key' => 'decimals', 'label' => __('Decimals', 'wp-booking-system'), 'translatable' => false, 'options' => array(
        '0' => '0',
        '1' => '1',
        '2' => '2',
        '3' => '3'
    ));

    $options['date_format'] = array('key' => 'date_format', 'label' => __('Date Format', 'wp-booking-system'), 'translatable' => true, 'options' => array(
        'dd/mm/yy' => wp_date('d/m/Y'),
        'mm/dd/yy' => wp_date('m/d/Y'),
        'dd-mm-yy' => wp_date('d-m-Y'),
        'mm-dd-yy' => wp_date('m-d-Y'),
        'd MM, yy' => wp_date('d F, Y'),
        'yy-mm-dd' => wp_date('Y-m-d'),
    ));

    $options['layout'] = array('key' => 'layout', 'label' => __('Layout', 'wp-booking-system'), 'translatable' => false, 'options' => array(
        'wpbs-field-layout-default' => __('Full width', 'wp-booking-system'),
        'wpbs-field-layout-left' => __('Half width - Left', 'wp-booking-system'),
        'wpbs-field-layout-right' => __('Half width - Right', 'wp-booking-system'),
    ));

    // Consent
    $options['checkbox_label'] = array('key' => 'checkbox_label', 'label' => __('Checkbox Label', 'wp-booking-system'), 'translatable' => true);
    $options['link'] = array('key' => 'link', 'label' => __('Checkbox Link', 'wp-booking-system'), 'translatable' => true);

    $options['notice_captcha'] = array('key' => 'notice_captcha', 'label' => __('To use reCAPTCHA you must add your API Keys in the', 'wp-booking-system') . ' <a target="_blank" href="' . add_query_arg(array('page' => 'wpbs-settings', 'tab' => 'form'), admin_url('admin.php')) . '">' . __('Settings Page', 'wp-booking-system') . '</a>.', 'translatable' => false);

    $options = apply_filters('wpbs_form_available_field_types_options', $options);

    return $options;
}

function wpbs_form_available_field_types_languages($fields)
{

    $settings = get_option('wpbs_settings', array());
    $active_languages = (!empty($settings['active_languages']) ? $settings['active_languages'] : array());

    foreach ($fields as &$field) {
        $field['values']['default'] = [];
    }

    if (!$active_languages) {
        return $fields;
    }

    foreach ($fields as &$field) {
        foreach ($active_languages as $language) {
            $field['languages'][] = $language;
            $field['values'][$language] = [];
        }
    }

    return $fields;
}
add_filter('wpbs_form_available_field_types', 'wpbs_form_available_field_types_languages', 100, 1);

/**
 * Return all the email fields from an existing form
 *
 * @param  array $form_data
 *
 * @return array
 *
 */
function wpbs_form_get_email_fields($form_data)
{

    $email_fields = array();
    foreach ($form_data as $field) {
        if ($field['type'] == 'email') {
            $email_fields[] = $field;
        }
    }
    if (empty($email_fields)) {
        return false;
    }

    return $email_fields;
}

/**
 * Return all the phone fields from an existing form
 *
 * @param  array $form_data
 *
 * @return array
 *
 */
function wpbs_form_get_phone_fields($form_data)
{

    $phone_fields = array();
    foreach ($form_data as $field) {
        if ($field['type'] == 'phone') {
            $phone_fields[] = $field;
        }
    }
    if (empty($phone_fields)) {
        return false;
    }

    return $phone_fields;
}

/**
 * Checks for unused fields in the User and Admin Notification pages
 *
 * @param array $form_id
 * @param array $form_data
 * @param array $form_meta
 *
 * @return bool
 */
function wpbs_form_notifications_check_unused_fields($form_id, $form_data, $form_meta)
{

    $used_fields = array();

    $available_fields = array();

    // Go through both notification types
    foreach (array('admin_notification', 'user_notification') as $notification_type) {

        // Skip if notification is not enabled
        if (wpbs_get_form_meta($form_id, $notification_type . '_enable', true) != 'on') {
            continue;
        }

        foreach ($form_meta as $meta_key => $meta_value) {
            // Skip if field is not notification related
            if (strpos($meta_key, $notification_type) === false) {
                continue;
            }

            if (!wpbs_form_get_email_tag_ids($meta_value[0])) {
                continue;
            }

            $found_tags = wpbs_form_get_email_tag_ids($meta_value[0]);

            foreach ($found_tags as $tag) {
                // If it's a general tag, continue
                if (!is_numeric($tag)) {
                    continue;
                }

                $used_fields[] = $tag;
            }

        }
    }

    // Set Available Fields
    foreach ($form_data as $field) {
        $available_fields[] = $field['id'];
    }
    // Check fields
    foreach ($used_fields as $used_field) {
        if (!in_array($used_field, $available_fields)) {
            return false;
        }
    }

    return true;

}

/**
 * Checks for duplicate field IDs
 *
 * @param array $form_id
 * @param array $form_data
 * @param array $form_meta
 *
 * @return bool
 */
function wpbs_form_check_duplicate_field_ids($form_id, $form_data, $form_meta)
{
    $ids = array();
    foreach ($form_data as $field) {
        $ids[$field['id']] = true;
    }

    return count($ids) == count($form_data);
}

/**
 * Paste as Text on email builder TinyMCE
 *
 */
function wpbs_tinmyce_enable_paste_as_text()
{
    $screen = get_current_screen();

    if (!in_array($screen->id, array('wp-booking-system_page_wpbs-settings', 'wp-booking-system_page_wpbs-forms'))) {
        return false;
    }

    // always paste as plain text
    add_filter('teeny_mce_before_init', function ($mceInit) {
        $mceInit['paste_text_sticky'] = true;
        $mceInit['paste_text_sticky_default'] = true;
        return $mceInit;
    });

    // load 'paste' plugin in minimal/pressthis editor
    add_filter('teeny_mce_plugins', function ($plugins) {
        $plugins[] = 'paste';
        return $plugins;
    });
}
add_action('current_screen', 'wpbs_tinmyce_enable_paste_as_text');

/**
 * A list of all the available email tags
 *
 * @return array
 *
 */
function wpbs_email_tags()
{
    $tags = array(
        'general' => array(
            'all-fields' => 'All Fields',
            'start-date' => 'Start Date',
            'end-date' => 'End Date',
            'booking-id' => 'Booking ID',
            'booking-date' => 'Booking Date',
            'calendar-title' => 'Calendar Title',
            'number-of-nights' => 'Number of Nights',
            'number-of-days' => 'Number of Days',
            'ip-address' => 'IP Address',
            'signature' => 'Signature',
            'calendar-assigned-user-emails' => 'Calendar Assigned User Emails',
        ),
    );

    $tags = apply_filters('wpbs_email_tags', $tags);
    return $tags;
}

/**
 * Generate the HTML output for email tags
 *
 * @param array $form_data
 *
 * @return string
 *
 */
function wpbs_output_email_tags($form_data)
{

    $tags = wpbs_email_tags();

    $output = '';

    $output .= '<div class="wpbs-email-tags">';

    $output .= '<h4>' . __('General Tags', 'wp-booking-system') . '</h4>';

    foreach ($tags['general'] as $tag_id => $tag_name) {
        $output .= '<div class="wpbs-email-tag wpbs-email-tag--' . $tag_id . '"><div>{' . $tag_name . '}</div></div>';
    }

    if (isset($tags['payment'])) {
        $output .= '<h4>' . __('Payment Tags', 'wp-booking-system') . '</h4>';

        foreach ($tags['payment'] as $tag_id => $tag_name) {
            $output .= '<div class="wpbs-email-tag wpbs-email-tag--' . $tag_id . '"><div>{' . $tag_name . '}</div></div>';
        }
    }

    $output .= '<h4>' . __('Form Tags', 'wp-booking-system') . '</h4>';
    foreach ($form_data as $field):

        if (in_array($field['type'], array('html', 'captcha', 'total', 'signature', 'security_deposit'))) {
            continue;
        }

        $label = (!empty($field['values']['default']['label'])) ? $field['values']['default']['label'] : __('no-label', 'wp-booking-system');
        $output .= ' <div class="wpbs-email-tag"><div>{' . $field['id'] . ':' . $label . '}</div></div>';

    endforeach;

    $output .= apply_filters('wpbs_output_email_tags', ''); // Deprecated

    $output .= '</div>';

    echo $output;
}

/**
 * Default form strings
 *
 */
function wpbs_form_default_strings()
{
    $strings = array(
        'required_field' => __('This field is required.', 'wp-booking-system'),
        'invalid_email' => __('Invalid email address.', 'wp-booking-system'),
        'invalid_phone' => __('Invalid phone number.', 'wp-booking-system'),
        'invalid_number_min' => __('Please enter a value greater than %s.', 'wp-booking-system'),
        'invalid_number_max' => __('Please enter a value lower than %s.', 'wp-booking-system'),
        'select_date' => __('Please select a date.', 'wp-booking-system'),
        'invalid_selection' => __('It appears you have selected an unavailable or invalid date. Please refresh the calendar and try again.', 'wp-booking-system'),
        'minimum_selection' => __("Please select a minimum of %s days.", 'wp-booking-system'),
        'maximum_selection' => __("Please select a maximum of %s days.", 'wp-booking-system'),
        'start_day' => __("The booking must start on a %s.", 'wp-booking-system'),
        'end_day' => __("The booking must end on a %s.", 'wp-booking-system'),
        'validation_errors' => __('Please check the fields below for errors.', 'wp-booking-system'),
        'booking_id' => __('Booking ID', 'wp-booking-system'),
        'booked_on' => __('Booked On', 'wp-booking-system'),
        'start_date' => __('Start Date', 'wp-booking-system'),
        'end_date' => __('End Date', 'wp-booking-system'),
    );

    return apply_filters('wpbs_form_default_strings', $strings);
}

/**
 * Default form string values
 *
 */
function wpbs_form_default_string_values()
{
    $strings = array(
        'validation-strings' => array(
            'label' => __('Validation Strings', 'wp-booking-system'),
            'strings' => array(
                'validation_errors' => array(
                    'label' => __('Validation Errors', 'wp-booking-system'),
                ),
                'required_field' => array(
                    'label' => __('Required Field', 'wp-booking-system'),
                ),
                'invalid_email' => array(
                    'label' => __('Invalid Email', 'wp-booking-system'),
                ),
                'invalid_phone' => array(
                    'label' => __('Invalid Phone', 'wp-booking-system'),
                ),
                'invalid_number_min' => array(
                    'label' => __('Invalid Number - Min', 'wp-booking-system'),
                ),
                'invalid_number_max' => array(
                    'label' => __('Invalid Number - Max', 'wp-booking-system'),
                ),
                'select_date' => array(
                    'label' => __('No Date Selected', 'wp-booking-system'),
                ),
                'invalid_selection' => array(
                    'label' => __('Invalid Date Selection', 'wp-booking-system'),
                ),
                'minimum_selection' => array(
                    'label' => __('Minimum Days', 'wp-booking-system'),
                    'tooltip' => __('The "%s" will be replaced by the number of days set in the widget or shortcode.', 'wp-booking-system'),
                ),
                'maximum_selection' => array(
                    'label' => __('Maximum Days', 'wp-booking-system'),
                    'tooltip' => __('The "%s" will be replaced by the number of days set in the widget or shortcode.', 'wp-booking-system'),
                ),
                'start_day' => array(
                    'label' => __('Starting Day', 'wp-booking-system'),
                    'tooltip' => __('The "%s" will be replaced by the week day set in the widget or shortcode.', 'wp-booking-system'),
                ),
                'end_day' => array(
                    'label' => __('Ending Day', 'wp-booking-system'),
                    'tooltip' => __('The "%s" will be replaced by the week day set in the widget or shortcode.', 'wp-booking-system'),
                ),
            ),
        ),
        'other-strings' => array(
            'label' => __('Other Strings', 'wp-booking-system'),
            'strings' => array(
                'booking_id' => array(
                    'label' => __('Booking ID', 'wp-booking-system'),
                    'tooltip' => __('This appears in the email if the {All Fields} tag is used.', 'wp-booking-system'),
                ),
                'start_date' => array(
                    'label' => __('Start Date', 'wp-booking-system'),
                    'tooltip' => __('This appears in the email if the {All Fields} tag is used.', 'wp-booking-system'),
                ),
                'end_date' => array(
                    'label' => __('End Date', 'wp-booking-system'),
                    'tooltip' => __('This appears in the email if the {All Fields} tag is used.', 'wp-booking-system'),
                ),
                'booked_on' => array(
                    'label' => __('Booked On', 'wp-booking-system'),
                    'tooltip' => __('The date the booking was made on. This appears in the email if the {All Fields} tag is used.', 'wp-booking-system'),
                ),
            ),
        ),
    );

    $strings = apply_filters('wpbs_form_default_strings_settings_page', $strings);

    return $strings;
}

/**
 * Display a notice if an email address doesn't use the same domain as the plugin is installed on
 * 
 * @param string $email
 * 
 * @return mixed bool|string
 * 
 */
function wpbs_same_email_domain_notice($email){
    if(wpbs_check_email_domain($email)){
        return;
    }

    echo '<div class="wpbs-page-notice wpbs-page-notice-email-domain notice-warning"><p>'. __('Warning! Using a third-party email in the From Email field may prevent your email from being delivered. It is best to use an email with the same domain as your website.', 'wp-booking-system') . '</p></div>';
    
}

/**
 * Check if an email address uses the same domain as the plugin is installed on
 * 
 * @param string $email
 * 
 * @return bool
 * 
 */
function wpbs_check_email_domain($email){

    if(!$email){
        return true;
    }

    $site_domain = parse_url( get_bloginfo( 'url' ), PHP_URL_HOST );

    $email_domain = explode( '@', $email );

    return ( strpos( $site_domain, array_pop( $email_domain ) ) !== false ) ? true : false;
}
