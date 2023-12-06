<?php

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Includes the files needed for the Forms
 *
 */
function wpbs_include_files_form()
{

    // Get form dir path
    $dir_path = plugin_dir_path(__FILE__);

    // Include main Form class
    if (file_exists($dir_path . 'class-form.php')) {
        include $dir_path . 'class-form.php';
    }

    // Include other functions files
    if (file_exists($dir_path . 'functions-ajax.php')) {
        include $dir_path . 'functions-ajax.php';
    }

    // Include the db layer classes
    if (file_exists($dir_path . 'class-object-db-forms.php')) {
        include $dir_path . 'class-object-db-forms.php';
    }

    if (file_exists($dir_path . 'class-object-meta-db-forms.php')) {
        include $dir_path . 'class-object-meta-db-forms.php';
    }

    // Include the Form Outputter class
    if (file_exists($dir_path . 'class-form-outputter.php')) {
        include $dir_path . 'class-form-outputter.php';
    }

    // Include the Form Conditional Logic class
    if (file_exists($dir_path . 'class-form-conditional-logic.php')) {
        include $dir_path . 'class-form-conditional-logic.php';
    }

    // Include the Form Validator class
    if (file_exists($dir_path . 'class-form-validator.php')) {
        include $dir_path . 'class-form-validator.php';
    }

    // Include the Form Handler class
    if (file_exists($dir_path . 'class-form-handler.php')) {
        include $dir_path . 'class-form-handler.php';
    }

    // Include the Form Mailer class
    if (file_exists($dir_path . 'class-email-tags.php')) {
        include $dir_path . 'class-email-tags.php';
    }

    // Include the Form Mailer class
    if (file_exists($dir_path . 'class-form-mailer.php')) {
        include $dir_path . 'class-form-mailer.php';
    }

}
add_action('wpbs_include_files', 'wpbs_include_files_form');

/**
 * Register the class that handles database queries for the Forms
 *
 * @param array $classes
 *
 * @return array
 *
 */
function wpbs_register_database_classes_forms($classes)
{

    $classes['forms'] = 'WPBS_Object_DB_Forms';
    $classes['formmeta'] = 'WPBS_Object_Meta_DB_Forms';

    return $classes;

}
add_filter('wpbs_register_database_classes', 'wpbs_register_database_classes_forms');

/**
 * Returns an array with WPBS_Form objects from the database
 *
 * @param array $args
 * @param bool  $count
 *
 * @return array
 *
 */
function wpbs_get_forms($args = array(), $count = false)
{

    $forms = wp_booking_system()->db['forms']->get_forms($args, $count);

    /**
     * Add a filter hook just before returning
     *
     * @param array $forms
     * @param array $args
     * @param bool  $count
     *
     */
    return apply_filters('wpbs_get_forms', $forms, $args, $count);

}

/**
 * Gets a form from the database
 *
 * @param mixed int|object      - form id or object representing the form
 *
 * @return WPBS_Form|false
 *
 */
function wpbs_get_form($form)
{

    return wp_booking_system()->db['forms']->get_object($form);

}

/**
 * Inserts a new form into the database
 *
 * @param array $data
 *
 * @return mixed int|false
 *
 */
function wpbs_insert_form($data)
{

    return wp_booking_system()->db['forms']->insert($data);

}

/**
 * Updates a form from the database
 *
 * @param int     $form_id
 * @param array $data
 *
 * @return bool
 *
 */
function wpbs_update_form($form_id, $data)
{

    return wp_booking_system()->db['forms']->update($form_id, $data);

}

/**
 * Deletes a form from the database
 *
 * @param int $form_id
 *
 * @return bool
 *
 */
function wpbs_delete_form($form_id)
{

    return wp_booking_system()->db['forms']->delete($form_id);

}

/**
 * Inserts a new meta entry for the form
 *
 * @param int    $form_id
 * @param string $meta_key
 * @param string $meta_value
 * @param bool   $unique
 *
 * @return mixed int|false
 *
 */
function wpbs_add_form_meta($form_id, $meta_key, $meta_value, $unique = false)
{

    return wp_booking_system()->db['formmeta']->add($form_id, $meta_key, $meta_value, $unique);

}

/**
 * Updates a meta entry for the form
 *
 * @param int    $form_id
 * @param string $meta_key
 * @param string $meta_value
 * @param bool   $prev_value
 *
 * @return bool
 *
 */
function wpbs_update_form_meta($form_id, $meta_key, $meta_value, $prev_value = '')
{

    return wp_booking_system()->db['formmeta']->update($form_id, $meta_key, $meta_value, $prev_value);

}

/**
 * Returns a meta entry for the form
 *
 * @param int    $form_id
 * @param string $meta_key
 * @param bool   $single
 *
 * @return mixed
 *
 */
function wpbs_get_form_meta($form_id, $meta_key = '', $single = false)
{

    return wp_booking_system()->db['formmeta']->get($form_id, $meta_key, $single);

}

/**
 * Returns the translated meta entry for the form
 *
 * @param int    $form_id
 * @param string $meta_key
 * @param string $language_code
 *
 * @return mixed
 *
 */
function wpbs_get_translated_form_meta($form_id, $meta_key, $language_code)
{
    $settings 		  = get_option( 'wpbs_settings', array() );
	$active_languages = ( ! empty( $settings['active_languages'] ) ? $settings['active_languages'] : array() );

    if(in_array($language_code, $active_languages)){

        $translated_meta = wpbs_get_form_meta($form_id, $meta_key . '_translation_' . $language_code, true);

        if (!empty($translated_meta)) {
            return $translated_meta;
        }

    }

    return wpbs_get_form_meta($form_id, $meta_key, true);
}

/**
 * Removes a meta entry for the form
 *
 * @param int    $form_id
 * @param string $meta_key
 * @param string $meta_value
 * @param bool   $delete_all
 *
 * @return bool
 *
 */
function wpbs_delete_form_meta($form_id, $meta_key, $meta_value = '', $delete_all = '')
{

    return wp_booking_system()->db['formmeta']->delete($form_id, $meta_key, $meta_value, $delete_all);

}

/**
 * Returns the default arguments for the calendar outputter
 *
 * @return array
 *
 */
function wpbs_get_form_output_default_args()
{

    $args = array(
        'minimum_days' => 0,
        'maximum_days' => 0,
        'booking_start_day' => 0,
        'booking_end_day' => 0,
        'selection_type' => 'multiple',
        'selection_style' => 'split',
        'language' => wpbs_get_locale(),
        'auto_pending' => 'yes',
        'show_date_selection' => 'no',
        'currency' => '',
        'manual_booking' => 0,
    );

    /**
     * Filter the args before returning
     *
     * @param array $args
     *
     */
    $args = apply_filters('wpbs_get_form_output_default_args', $args);

    return $args;

}

/**
 * Get all email tag fields IDs used in a text, matching the {ID:NAME} format
 *
 * @param string    $text
 *
 * @return mixed
 *
 */
function wpbs_form_get_email_tag_ids($text)
{
    $found_tags = array();
    preg_match_all('/{(.*?)}/', $text, $matches);
    foreach ($matches[1] as $match) {
        $found_tags[] = wpbs_form_get_email_tag_id($match);
    }

    if (empty($found_tags)) {
        return false;
    }

    return $found_tags;
}

/**
 * Gets the id of an email tag
 *
 * @param string    $tag
 *
 * @return mixed
 *
 */
function wpbs_form_get_email_tag_id($tag)
{
    $tag = trim($tag, '{}');
    $match_id = explode(':', $tag);
    return $match_id[0];
}

/**
 * Get all email tag fields used in a text, matching the {ID:NAME} format
 *
 * @param string    $text
 *
 * @return mixed
 *
 */
function wpbs_form_get_email_tags($text)
{
    $found_tags = array();
    preg_match_all('/{(.*?)}/', $text, $matches);
    foreach ($matches[0] as $match) {
        $found_tags[] = $match;
    }

    if (empty($found_tags)) {
        return false;
    }

    return $found_tags;
}

/**
 * Helper function to convert JS timestamp to a PHP Timestamp
 *
 * @param int $js_timestamp
 *
 * @return int
 *
 */
function wpbs_convert_js_to_php_timestamp($js_timestamp)
{
    // Check if it's a js timestamp
    if (strlen($js_timestamp) < 11) {
        return $js_timestamp;
    }
    $php_timestamp = ceil($js_timestamp / 1000);
    return $php_timestamp;
}

/**
 * Helper function that returns the field nicely formatted value
 *
 * @param mixed $value
 *
 * @return string
 */

function wpbs_get_field_display_user_value($value)
{

    $value = apply_filters('wpbs_get_field_display_user_value', $value);

    // Check if it's empty
    if (empty($value)) {
        return '-';
    }

    // Check if it's an array
    if (is_array($value)) {
        return trim(implode(', ', $value), ', ');
    }
    return $value;
}

/**
 * Helper function that returns the field value
 *
 * @param array $field
 *
 * @return string|array
 */

function wpbs_get_field_value($field)
{

    if(!isset($field['user_value'])){
        return '';
    }

    $value = $field['user_value'];

    // Check if it's empty
    if (empty($value)) {
        return '-';
    }

    // Check if it's an array
    if (is_array($value)) {
        foreach($value as &$val){
            if (strpos($val, '|') !== false) {
                list($option_price, $option_value) = explode('|', $val);
                $val = ['price' => $option_price, 'value' => $option_value];
            }
        }
    } else {
        if (strpos($value, '|') !== false) {
            list($option_price, $option_value) = explode('|', $value);
            $value = ['price' => $option_price, 'value' => $option_value];
        }
    }
    
    return $value;
}

/**
 * List of Form Fields to exclude from display templates or emails
 *
 */
function wpbs_get_excluded_fields($exclude = array())
{
    return array_diff(array('html', 'hidden', 'captcha', 'total', 'product_field', 'signature', 'security_deposit'), $exclude);
}

/**
 * Get translated form strings
 *
 * @param int $form_id
 * @param string $string
 * @param string $language
 *
 * @return string
 *
 */
function wpbs_get_form_default_string($form_id, $string, $language)
{

    // Check if individual strings are enabled in the form
    if (wpbs_get_form_meta($form_id, 'overwrite_strings_and_translations', true)) {

        // Check for translation
        if (!empty(wpbs_get_form_meta($form_id, 'form_strings_' . $string . '_translation_' . $language, true))) {
            return wpbs_get_form_meta($form_id, 'form_strings_' . $string . '_translation_' . $language, true);
        }

        // Check for default
        if (!empty(wpbs_get_form_meta($form_id, 'form_strings_' . $string, true))) {
            return wpbs_get_form_meta($form_id, 'form_strings_' . $string, true);
        }

    } else {

        $settings = get_option('wpbs_settings', array());

        // Check for translation
        if (!empty($settings['form_strings_' . $string . '_translation_' . $language])) {
            return $settings['form_strings_' . $string . '_translation_' . $language];
        }

        // Check for default
        if (!empty($settings['form_strings_' . $string])) {
            return $settings['form_strings_' . $string];
        }

    }

    return wpbs_form_default_strings()[$string];
}

function wpbs_convert_php_to_moment_format($php_format)
{
    $replacements = [
        'A' => 'A', // for the sake of escaping below
        'a' => 'a', // for the sake of escaping below
        'B' => '', // Swatch internet time (.beats), no equivalent
        'c' => 'YYYY-MM-DD[T]HH:mm:ssZ', // ISO 8601
        'D' => 'ddd',
        'd' => 'DD',
        'e' => 'zz', // deprecated since version 1.6.0 of moment.js
        'F' => 'MMMM',
        'G' => 'H',
        'g' => 'h',
        'H' => 'HH',
        'h' => 'hh',
        'I' => '', // Daylight Saving Time? => moment().isDST();
        'i' => 'mm',
        'j' => 'D',
        'L' => '', // Leap year? => moment().isLeapYear();
        'l' => 'dddd',
        'M' => 'MMM',
        'm' => 'MM',
        'N' => 'E',
        'n' => 'M',
        'O' => 'ZZ',
        'o' => 'YYYY',
        'P' => 'Z',
        'r' => 'ddd, DD MMM YYYY HH:mm:ss ZZ', // RFC 2822
        'S' => 'o',
        's' => 'ss',
        'T' => 'z', // deprecated since version 1.6.0 of moment.js
        't' => '', // days in the month => moment().daysInMonth();
        'U' => 'X',
        'u' => 'SSSSSS', // microseconds
        'v' => 'SSS', // milliseconds (from PHP 7.0.0)
        'W' => 'W', // for the sake of escaping below
        'w' => 'e',
        'Y' => 'YYYY',
        'y' => 'YY',
        'Z' => '', // time zone offset in minutes => moment().zone();
        'z' => 'DDD',
    ];

    // Converts escaped characters.
    foreach ($replacements as $from => $to) {
        $replacements['\\' . $from] = '[' . $from . ']';
    }

    return strtr($php_format, $replacements);
}

/**
 * Helper function to convert the php date format to jQuery UI compatible date format
 *
 */
function wpbs_convert_php_to_jqueryui_format($php_format)
{
    $symbols = array(
        // Day
        'd' => 'dd',
        'D' => 'D',
        'j' => 'd',
        'l' => 'DD',
        'N' => '',
        'S' => '',
        'w' => '',
        'z' => 'o',
        // Week
        'W' => '',
        // Month
        'F' => 'MM',
        'm' => 'mm',
        'M' => 'M',
        'n' => 'm',
        't' => '',
        // Year
        'L' => '',
        'o' => '',
        'Y' => 'yy',
        'y' => 'y',
        // Time
        'a' => '',
        'A' => '',
        'B' => '',
        'g' => '',
        'G' => '',
        'h' => '',
        'H' => '',
        'i' => '',
        's' => '',
        'u' => '',
    );
    $jqueryui_format = "";
    $escaping = false;
    for ($i = 0; $i < strlen($php_format); $i++) {
        $char = $php_format[$i];
        if ($char === '\\') // PHP date format escaping character
        {
            $i++;
            if ($escaping) {
                $jqueryui_format .= $php_format[$i];
            } else {
                $jqueryui_format .= '\'' . $php_format[$i];
            }

            $escaping = true;
        } else {
            if ($escaping) {$jqueryui_format .= "'";
                $escaping = false;}
            if (isset($symbols[$char])) {
                $jqueryui_format .= $symbols[$char];
            } else {
                $jqueryui_format .= $char;
            }

        }
    }
    return $jqueryui_format;
}

/**
 * Helper function to get the type of reCAPTCHA we are using
 *
 * @return string
 *
 */
function wpbs_get_recaptcha_type()
{
    $settings = get_option('wpbs_settings', array());

    if (!isset($settings['recaptcha_type']) || $settings['recaptcha_type'] == 'v2_tickbox') {
        return 'v2';
    }

    if ($settings['recaptcha_type'] == 'v3') {
        return 'v3';
    }
}

/**
 * Helper function to get the reCAPTCHA keys
 *
 * @return mixed string|bool
 *
 */
function wpbs_get_recaptcha_keys()
{

    $settings = get_option('wpbs_settings', array());

    $type = wpbs_get_recaptcha_type();

    if ($type == 'v2' && isset($settings['recaptcha_v2_site_key']) && !empty($settings['recaptcha_v2_site_key'])) {
        return array(
            'site_key' => $settings['recaptcha_v2_site_key'],
            'secret_key' => $settings['recaptcha_v2_secret_key'],
        );
    }

    if ($type == 'v3' && isset($settings['recaptcha_v3_site_key']) && !empty($settings['recaptcha_v3_site_key'])) {
        return array(
            'site_key' => $settings['recaptcha_v3_site_key'],
            'secret_key' => $settings['recaptcha_v3_secret_key'],
        );
    }

    return false;
}

/**
 * Delete temporary files that are generated when sending emails
 *
 * @param int $booking_id
 *
 */
function wpbs_delete_temporary_files($booking_id)
{
    foreach (scandir(WPBS_PLUGIN_DIR . 'temp') as $item) {
        if ($item == '.' || $item == '..' || $item == 'index.php') {
            continue;
        }

        if (strpos($item, '-' . $booking_id . '.') === false) {
            continue;
        }

        unlink(WPBS_PLUGIN_DIR . 'temp/' . $item);

    }
}
