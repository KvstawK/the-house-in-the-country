<?php

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Add Coupon Form Field in the form builder
 *
 */
function wpbs_d_form_available_field_types_coupon($fields)
{
    $fields['coupon'] = array(
        'type' => 'coupon',
        'group' => 'pricing',
        'supports' => array(
            'primary' => array('label'),
            'secondary' => array('description', 'placeholder', 'layout', 'class', 'hide_label'),
        ),
        'values' => array(),
    );

    return $fields;
}

add_filter('wpbs_form_available_field_types', 'wpbs_d_form_available_field_types_coupon', 10, 1);

/**
 * Evaluates coupon code and calculates the coupon discount for an order
 *
 * @param array     $prices
 * @param int       $calendar_id
 * @param array     $form_args
 * @param WPBS_Form $form
 * @param array     $form_fields
 *
 * @return array
 *
 */
function wpbs_d_get_checkout_price_coupon_code($prices, $payment, $calendar_id, $form_args, $form, $form_fields, $start_date, $end_date)
{
    // Save price so we always discount from the same price
    $subtotal = $prices['subtotal'];

    // Loop through form fields and find the coupon field
    foreach ($form_fields as $field) {
        if ($field['type'] != 'coupon') {
            continue;
        }

        if (empty($field['user_value'])) {
            continue;
        }

        $coupon_code = sanitize_text_field($field['user_value']);

        // Validate coupon code
        $coupon = wpbs_d_validate_coupon_code($coupon_code, $calendar_id, $start_date, $end_date);

        if ($coupon !== false) {

            $options = $coupon->get('options');

            // Apply coupon after discounts?
            if(isset($options['application_order']) && $options['application_order'] == 'after'){
                $subtotal = $prices['total'];
            }

            $language = $form_args['language'];

            // Calculate discount

            // Fixed Amount
            if ($options['type'] == 'fixed_amount') {
                $fixed_coupon_amount = apply_filters('wpbs_pricing_item_modifier', $options['value'], $prices, 'coupon');
                $value = $fixed_coupon_amount * (-1);
                $value_with_vat = $value;
                $value = $payment->vat->deduct_vat($value);
            } else {
                // Percentage amount
                if (isset($options['apply_to']) && $options['apply_to'] == 'calendar') {

                    // From Calendar Price
                    $value = $prices['events']['price'] * $options['value'] / 100 * (-1);
                    
                    if(isset($prices['vat_display_only']) && $prices['vat_display_only']){
                        $payment->vat->add_vat_amount(($value - ($value / $payment->vat->percentage_calculation)) );
                        $value_with_vat = $value;
                    } else {
                        $payment->vat->add_vat_amount(($value - $value * $payment->vat->percentage_calculation) * (-1));
                        $value_with_vat = round($value * $payment->vat->percentage_calculation, 2);
                    }
                    
                } else {

                    // From Calendar and Form Prices
                    $value = $subtotal * $options['value'] / 100 * (-1);

                    if(isset($prices['vat_display_only']) && $prices['vat_display_only']){
                        $payment->vat->add_vat_amount(($value - ($value / $payment->vat->percentage_calculation)) );
                        $value_with_vat = $value;
                    } else {
                        $payment->vat->add_vat_amount(($value - $value * $payment->vat->percentage_calculation) * (-1));
                        $value_with_vat = round($value * $payment->vat->percentage_calculation, 2);
                    }
                }
            }

            $value = round($value, 2);
            

            $prices['total'] += $value;
            
            // Add to prices array
            $prices['coupon'] = array(
                'name' => (!empty(wpbs_get_coupon_meta($coupon->get('id'), 'coupon_name_translation_' . $language, true)) ? wpbs_get_coupon_meta($coupon->get('id'), 'coupon_name_translation_' . $language, true) : $coupon->get('name')),
                'type' => $options['type'],
                'description' => (isset($options['description_translation_' . $language]) && !empty($options['description_translation_' . $language]) ? $options['description_translation_' . $language] : (isset($options['description']) ? $options['description'] : '')),
                'discount' => $options['value'],
                'value_with_vat' => $value_with_vat,
                'value' => $value,
            );

        }
    }

    return $prices;
}
add_filter('wpbs_get_checkout_price_before_subtotal', 'wpbs_d_get_checkout_price_coupon_code', 20, 8);

/**
 * Add coupons to line items
 *
 * @param array $line_items
 * @param WPBS_Payment $payment
 *
 *
 * @return string
 *
 */
function wpbs_d_add_coupon_to_checkout_pricing_table($line_items, $payment)
{

    $prices = $payment->get('prices');

    if (isset($prices['coupon'])) {

        $line_items[] = array(
            'label' => '<span class="wpbs-line-item-bold">' . $prices['coupon']['name'] . '</span>',
            'value' => wpbs_get_formatted_price($prices['coupon']['value'], $payment->get_display_currency(), true),
            'description' => (!empty($prices['coupon']['description'])) ? sanitize_text_field($prices['coupon']['description']) : '',
            'quantity' => 1,
            'price' => $prices['coupon']['value'],
            'price_with_vat' => isset($prices['coupon']['value_with_vat']) ? $prices['coupon']['value_with_vat'] : false,
            'individual_price' => $prices['coupon']['value'],
            'individual_price_with_vat' => isset($prices['coupon']['value_with_vat']) ? $prices['coupon']['value_with_vat'] : false,
            'type' => 'coupon',
            'class' => 'wpbs-pricing-table-coupon wpbs-pricing-table-coupon-' . sanitize_title($prices['coupon']['name']),
            'editable' => true,
        );

    }

    return $line_items;

}
add_filter('wpbs_line_items_before_subtotal', 'wpbs_d_add_coupon_to_checkout_pricing_table', 10, 2);

/**
 * Validate the coupon code
 *
 * @param string $coupon_code
 * @param int $calendar_id
 * @param array $form_args
 *
 * @return string
 *
 */
function wpbs_d_validate_coupon_code($coupon_code, $calendar_id, $start_date = false, $end_date = false)
{

    // Get coupons
    $coupons = wpbs_get_coupons(array('status' => 'active'));

    if (empty($coupons)) {
        return false;
    }

    // Check if a date is selected
    if (empty($start_date) || empty($end_date)) {
        return false;
    }

    foreach ($coupons as $coupon) {
        $options = $coupon->get('options');

        // Skip if coupon code doesn't have a code
        if (!isset($options['code'])) {
            continue;
        }

        // Skip if coupon doesn't apply to this calendar
        if (!empty($options['calendars']) && !in_array($calendar_id, $options['calendars'])) {
            continue;
        }

        // Skip if coupon code doesn't match
        if ($options['code'] != strtoupper($coupon_code)) {
            continue;
        }

        // Check usages
        if (isset($options['usage_limit'])) {
            $usages_left = $options['usage_limit'] - absint(wpbs_get_coupon_meta($coupon->get('id'), 'usages', true));

            if ($usages_left <= 0) {
                continue;
            }

        }

        
        $start_date = wpbs_convert_js_to_php_timestamp($start_date);
        $end_date = wpbs_convert_js_to_php_timestamp($end_date);

        // Validate weekdays
        if(isset($options['weekdays']) && !empty($options['weekdays'])){
            if(!in_array(date('N', $start_date), $options['weekdays'])){
                continue;
            }
        }

        $stay_length = ($end_date - $start_date) / DAY_IN_SECONDS;

        // Validate minimum stay
        if (isset($options['minimum_stay']) && absint($options['minimum_stay']) > 0) {
            if($stay_length < absint($options['minimum_stay'])){
                continue;
            }
        }

        // Validate maximum stay
        if (isset($options['maximum_stay']) && absint($options['maximum_stay']) > 0) {
            if($stay_length > absint($options['maximum_stay'])){
                continue;
            }
        }

        $inclusion = isset($options['inclusion']) && $options['inclusion'] ? $options['inclusion'] : 'start_date';

        $validity_from = $validity_to = false;

        // Skip if date range doesn't match
        if (isset($options['validity_from']) && !empty($options['validity_from'])) {
            $validity_from = DateTime::createFromFormat('Y-m-d', $options['validity_from']);
            $validity_from->setTime(0, 0, 0);
            if ($inclusion == 'entire_date' && ($start_date < $validity_from->getTimestamp() || $end_date < $validity_from->getTimestamp())) {
                continue;
            }
            if ($inclusion == 'start_date' && $start_date < $validity_from->getTimestamp()) {
                continue;
            }
        }

        if (isset($options['validity_to']) && !empty($options['validity_to'])) {
            $validity_to = DateTime::createFromFormat('Y-m-d', $options['validity_to']);
            $validity_to->setTime(0, 0, 0);
            if ($inclusion == 'entire_date' && ($start_date > $validity_to->getTimestamp() || $end_date > $validity_to->getTimestamp())) {
                continue;
            }

            if ($inclusion == 'start_date' && $start_date > $validity_to->getTimestamp()) {
                continue;
            }
        }

        return $coupon;
    }

    return false;
}

/**
 * Ajax callback for applying the coupon code
 *
 */
function wpbs_ajax_apply_coupon()
{

    // Get Form ID
    $form_id = absint(!empty($_POST['form']['id']) ? $_POST['form']['id'] : 0);

    // Get Calendar ID
    $calendar_atts = $_POST['calendar'];
    $calendar_id = absint(!empty($calendar_atts['id']) ? $calendar_atts['id'] : 0);
    $calendar = wpbs_get_calendar($calendar_id);

    // Get Coupon
    $coupon_code = sanitize_text_field($_POST['coupon_code']);

    if (empty($calendar_atts['start_date']) || empty($calendar_atts['end_date'])) {
        echo json_encode(
            array('success' => false, 'error' => wpbs_get_form_default_string($form_id, 'select_date', $_POST['form']['language']))
        );
    } elseif ($error = wpbs_d_validate_coupon_code($coupon_code, $calendar_id, $calendar_atts['start_date'], $calendar_atts['end_date']) === false) {
        echo json_encode(
            array('success' => false, 'error' => wpbs_get_form_default_string($form_id, 'invalid_coupon_code', $_POST['form']['language']))
        );
    } else {
        echo json_encode(
            array('success' => true)
        );
    }

    wp_die();
}
add_action('wp_ajax_nopriv_wpbs_apply_coupon', 'wpbs_ajax_apply_coupon');
add_action('wp_ajax_wpbs_apply_coupon', 'wpbs_ajax_apply_coupon');

/**
 * Add default coupon related strings
 *
 * @param array $strings
 *
 * @return array
 *
 */
function wpbs_d_form_default_strings($strings)
{

    $strings['apply_coupon_code'] = __('Apply Code', 'wp-booking-system-coupons-discounts');
    $strings['invalid_coupon_code'] = __('Invalid Coupon Code', 'wp-booking-system-coupons-discounts');

    return $strings;
}
add_filter('wpbs_form_default_strings', 'wpbs_d_form_default_strings');

/**
 * Add default coupon related strings to settings page
 *
 * @param array $strings
 *
 * @return array
 *
 */
function wpbs_d_form_default_strings_settings_page($strings)
{

    $strings['coupon-codes'] = array(
        'label' => __('Coupon Codes', 'wp-booking-system-coupons-discounts'),
        'strings' => array(
            'apply_coupon_code' => array(
                'label' => __('Apply Code', 'wp-booking-system-coupons-discounts'),
            ),
            'invalid_coupon_code' => array(
                'label' => __('Invalid Code', 'wp-booking-system-coupons-discounts'),
            ),
        ),
    );

    return $strings;
}
add_filter('wpbs_form_default_strings_settings_page', 'wpbs_d_form_default_strings_settings_page');

function wpbs_d_increment_coupon_usage($booking_id, $post_data, $form, $form_args, $form_fields)
{

    $coupon_code = false;

    // Get the coupon code used in the form
    foreach ($form_fields as $form_field) {
        if ($form_field['type'] != 'coupon') {
            continue;
        }

        if (empty($form_field['user_value'])) {
            continue;
        }

        $coupon_code = $form_field['user_value'];
    }

    // If a coupon code was used
    if ($coupon_code !== false) {

        // Get coupons
        $coupons = wpbs_get_coupons(array('status' => 'active'));

        if (empty($coupons)) {
            return false;
        }

        foreach ($coupons as $coupon) {
            $options = $coupon->get('options');

            // Skip if coupon code doesn't have a code
            if (!isset($options['code'])) {
                continue;
            }

            // Skip if coupon code doesn't match
            if ($options['code'] != strtoupper($coupon_code)) {
                continue;
            }

            // Skip if usage limit does not exist.
            if (!isset($options['usage_limit'])) {
                return false;
            }

            // Get current usage
            $usages = absint(wpbs_get_coupon_meta($coupon->get('id'), 'usages', true));

            // Increment
            $usages++;

            // Put it back
            wpbs_update_coupon_meta($coupon->get('id'), 'usages', $usages);

        }
    }

}
add_action('wpbs_submit_form_after', 'wpbs_d_increment_coupon_usage', 50, 5);
