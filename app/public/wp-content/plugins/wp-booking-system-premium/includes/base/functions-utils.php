<?php

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Sanitizes the values of an array recursively using sanitize_text_field
 *
 * @param array $array
 *
 * @return array
 *
 */
function _wpbs_array_sanitize_text_field($array = array())
{

    if (empty($array) || !is_array($array)) {
        return array();
    }

    foreach ($array as $key => $value) {

        if (is_array($value)) {
            $array[$key] = _wpbs_array_sanitize_text_field($value);
        } else {
            $array[$key] = sanitize_text_field($value);
        }

    }

    return $array;

}

/**
 * Sanitizes the values of an array recursively using sanitize_textarea_field
 *
 * @param array $array
 *
 * @return array
 *
 */
function _wpbs_array_sanitize_textarea_field($array = array())
{

    if (empty($array) || !is_array($array)) {
        return array();
    }

    foreach ($array as $key => $value) {

        if (is_array($value)) {
            $array[$key] = _wpbs_array_sanitize_textarea_field($value);
        } else {
            $array[$key] = sanitize_textarea_field($value);
        }

    }

    return $array;

}

function _wpbs_recursive_array_replace($find, $replace, $array)
{
    if (!is_array($array)) {
        return str_replace($find, $replace, $array);
    }
    $newArray = array();
    foreach ($array as $key => $value) {
        $newArray[$key] = _wpbs_recursive_array_replace($find, $replace, $value);
    }
    return $newArray;
}

/**
 * Sanitizes the values of an array recursively and allows HTML tags
 *
 * @param array $array
 *
 * @return array
 *
 */
function _wpbs_array_wp_kses_post($array = array())
{

    if (empty($array) || !is_array($array)) {
        return array();
    }

    foreach ($array as $key => $value) {

        if (is_array($value)) {
            $array[$key] = _wpbs_array_wp_kses_post($value);
        } else {
            $array[$key] = wp_kses_post($value);
        }

    }

    return $array;

}

/**
 * Escapes the values of an array recursively using esc_attr
 *
 * @param array $array
 *
 * @return array
 *
 */
function _wpbs_array_esc_attr_text_field($array = array())
{
    if (empty($array) || !is_array($array)) {
        return array();
    }

    foreach ($array as $key => $value) {
        if (is_array($value)) {
            $array[$key] = _wpbs_array_esc_attr_text_field($value);
        } else {
            $array[$key] = esc_attr($value);
        }
    }

    return $array;
}

/**
 * Escapes the values of an array recursively using esc_textarea
 *
 * @param array $array
 *
 * @return array
 *
 */
function _wpbs_array_esc_attr_textarea_field($array = array())
{
    if (empty($array) || !is_array($array)) {
        return array();
    }

    foreach ($array as $key => $value) {
        if (is_array($value)) {
            $array[$key] = _wpbs_array_esc_attr_textarea_field($value);
        } else {
            $array[$key] = esc_textarea($value);
        }
    }

    return $array;
}

/**
 * Helper function to search an array
 *
 * @param string
 * @param array
 *
 * @return bool
 *
 */
function _wpbs_recursive_array_search($needle, $haystack)
{
    foreach ($haystack as $key => $value) {
        if ($key === 'user_value') {
            continue;
        }

        foreach ($haystack as $key => $value) {
            $current_key = $key;
            if ($needle === $value or (is_array($value) && _wpbs_recursive_array_search($needle, $value) !== false)) {
                return true;
            }
        }
    }
    return false;
}

/**
 * Returns the current locale
 *
 * @return string
 *
 */
function wpbs_get_locale()
{

    return substr(get_locale(), 0, 2);

}

/**
 * Generates and returns a random 32 character long string
 *
 * @return string
 *
 */
function wpbs_generate_hash()
{

    $chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $chars_length = strlen($chars);
    $hash = '';

    for ($i = 0; $i < 19; $i++) {

        $hash .= $chars[rand(0, $chars_length - 1)];

    }

    return $hash . uniqid();

}

/**
 * Replace date_i18n with wp_date, but fallback to date_i18n if WP < 5.3
 *
 * @param string $format
 * @param string $timestamp
 * @param string $language
 *
 * @return string
 *
 */
function wpbs_date_i18n($format, $timestamp, $language = false)
{

    if($language !== false){
        $original_locale = get_locale();
        switch_to_locale(wpbs_language_to_locale($language));
    }
    
    if (function_exists('wp_date')) {
        $zone = apply_filters('wpbs_date_timezone', new DateTimeZone('UTC'));
        $date = wp_date($format, $timestamp, $zone);
    } else {
        $date = date_i18n($format, $timestamp);
    }

    if($language !== false){
        switch_to_locale($original_locale);
    }

    return $date;
}

/**
 * Get users IP Address
 *
 */
function wpbs_get_user_ip_address()
{
    if (array_key_exists('HTTP_X_FORWARDED_FOR', $_SERVER) && !empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        if (strpos($_SERVER['HTTP_X_FORWARDED_FOR'], ',') > 0) {
            $addr = explode(",", $_SERVER['HTTP_X_FORWARDED_FOR']);
            return trim($addr[0]);
        } else {
            return $_SERVER['HTTP_X_FORWARDED_FOR'];
        }
    } else {
        return $_SERVER['REMOTE_ADDR'];
    }
}

/**
 * Check if a date is contained within a range of dates
 * Works for recurring dates.
 * 
 * @param DateTime $start
 * @param DateTime $end
 * @param DateTime $compare
 * 
 * @return bool
 * 
 */
function wpbs_is_date_in_range($start, $end, $compare){
    if ($start < $end) {
        return $start <= $compare && $compare <= $end;
    } 

    return $compare >= $start || $compare <= $end;
}

/**
 * Get the time when scheduled emails should go out
 * 
 * @return int
 * 
 */
function wpbs_scheduled_email_delivery_hour(){
    $settings = get_option('wpbs_settings'); 

    if(!isset($settings['when_to_send_hour'])){
        $hour = 12;
    } else {
        $hour = absint($settings['when_to_send_hour']);
    }

    $hour = $hour - get_option('gmt_offset');

    return $hour * HOUR_IN_SECONDS;

}