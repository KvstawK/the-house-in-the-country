<?php

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Evaluates discount rules and calculates the discount for an order
 *
 * @param array      $prices
 * @param int        $calendar_id
 * @param array      $form_args
 * @param WPBS_Form  $form
 * @param array      $form_fields
 * @param string     $start_date
 * @param string     $end_date
 *
 * @return array
 *
 */
function wpbs_d_add_discount_to_checkout_price($prices, $payment, $calendar_id, $form_args, $form, $form_fields, $start_date, $end_date)
{

    // Get discounts
    $discounts = wpbs_get_discounts(array('status' => 'active'));

    // Save price so we always discount from the same price
    $subtotal = $prices['subtotal'];

    $language = $form_args['language'];

    $start_date_timestamp = wpbs_convert_js_to_php_timestamp($start_date);
    $original_start_date = new DateTime();
    $original_start_date->setTimestamp($start_date_timestamp);

    $end_date_timestamp = wpbs_convert_js_to_php_timestamp($end_date);
    $original_end_date = new DateTime();
    $original_end_date->setTimestamp($end_date_timestamp);

    foreach ($discounts as $discount) {

        $start_date = clone $original_start_date;
        $end_date = clone $original_end_date;

        $options = $discount->get('options');

        if (empty($options)) {
            continue;
        }

        if (empty($options['value'])) {
            continue;
        }

        // Skip if discount doesn't apply to this calendar
        if (!empty($options['calendars']) && !in_array($calendar_id, $options['calendars'])) {
            continue;
        }

        $inclusion = isset($options['inclusion']) && $options['inclusion'] ? $options['inclusion'] : 'start_date';

        // Backwards compatibility
        if (isset($options['validity_from']) || isset($options['validity_to'])) {
            $options['validity_period'] = array(array(
                'from' => (isset($options['validity_from']) ? $options['validity_from'] : ''),
                'to' => (isset($options['validity_to']) ? $options['validity_to'] : ''),
            ));
        }

        $validity_period_included = false;

        // Get validity periods
        if (!$options['validity_period']) {
            $validity_period_included = true;
        } else {
            foreach ($options['validity_period'] as $period) {

                $period['from'] = wpbs_discounts_get_date($period['from'], 'from');
                $period['to'] = wpbs_discounts_get_date($period['to'], 'to');

                $validity_from = DateTime::createFromFormat('Y-m-d', $period['from']);
                $validity_from->setTime(0, 0, 0);

                $validity_to = DateTime::createFromFormat('Y-m-d', $period['to']);
                $validity_to->setTime(0, 0, 0);

                if ($inclusion == 'start_date' && $start_date >= $validity_from && $start_date <= $validity_to) {
                    $validity_period_included = true;
                }

                if ($inclusion == 'entire_date' && $start_date >= $validity_from && $start_date <= $validity_to && $end_date >= $validity_from && $end_date <= $validity_to) {
                    $validity_period_included = true;
                }

                if ($inclusion == 'any_date' && (($start_date >= $validity_from && $start_date <= $validity_to) || ($end_date >= $validity_from && $end_date <= $validity_to))) {
                    $validity_period_included = true;
                }
            }
        }

        // Skip if date range doesn't match
        if ($validity_period_included === false) {
            continue;
        }

        // Evaluate discount rules
        $evaluation = new WPBS_Evaluate_Discounts($options, $prices, $form, $form_fields, $start_date_timestamp, $end_date_timestamp);

        if ($evaluation->get_evaluation() === true) {

            // Calculate the number of days the discount is applicable to
            $days_applicable_num = 0;
            $days_applicable = array();
            $days_total = 0;

            $same_start_end_date = ($start_date == $end_date) ? true : false;

            // Set loop interval
            if ($form_args['selection_type'] == 'single' || ($form_args['selection_type'] == 'multiple' && $same_start_end_date) || ($form_args['selection_style'] == 'normal' && !$same_start_end_date)) {
                $end_date->modify('+1 day');
            }
            $interval = DateInterval::createFromDateString('1 day');
            $period = new DatePeriod($start_date, $interval, $end_date);
            $difference = $start_date->diff($end_date);
            $days_total = $difference->days;

            if (!$options['validity_period']) {
                $options['validity_period'][] = ['from' => '', 'to' => ''];
            }

            foreach ($options['validity_period'] as $validity_period) {

                $validity_period['from'] = wpbs_discounts_get_date($validity_period['from'], 'from');
                $validity_period['to'] = wpbs_discounts_get_date($validity_period['to'], 'to');


                $validity_from = DateTime::createFromFormat('Y-m-d', $validity_period['from']);
                $validity_from->setTime(0, 0, 0);

                $validity_to = DateTime::createFromFormat('Y-m-d', $validity_period['to']);
                $validity_to->setTime(0, 0, 0);

                foreach ($period as $date) {
                    if ($validity_from && $validity_to) {
                        if ($date >= $validity_from && $date <= $validity_to) {
                            $days_applicable_num++;
                            $days_applicable[] = $date;
                        }
                    } elseif ($validity_from && !$validity_to) {
                        if ($date >= $validity_from) {
                            $days_applicable_num++;
                            $days_applicable[] = $date;
                        }
                    } elseif (!$validity_from && $validity_to) {
                        if ($date <= $validity_to) {
                            $days_applicable_num++;
                            $days_applicable[] = $date;
                        }
                    } else {
                        $days_applicable_num++;
                        $days_applicable[] = $date;
                    }
                }
            }

            // Fixed Amount
            if ($options['type'] == 'fixed_amount') {

                $fixed_discount_amount = apply_filters('wpbs_pricing_item_modifier', $options['value'], $prices, 'discount', $form_fields);

                if (isset($options['application']) && $options['application'] == 'per_day') {

                    $difference = ($end_date->diff($start_date))->days;

                    if ($form_args['selection_style'] == 'normal') {
                        $difference++;
                    }

                    $fixed_discount_amount = (int) min($days_applicable_num, $difference) * $fixed_discount_amount;
                }

                $value = $fixed_discount_amount * -1;
                $value = apply_filters('wpbs_pricing_discount_value', $value, $options, $prices, $payment, $calendar_id, $form_args, $form, $form_fields, $start_date, $end_date);
                $value_with_vat = $value;
                $value = $payment->vat->deduct_vat($value);
            } else {
                // Percentage amount

                if (isset($options['apply_to']) && $options['apply_to'] == 'calendar') {
                    // From Calendar Price
                    $discount_price = 0;

                    // Apply the discount individually for each day
                    foreach ($days_applicable as $date) {
                        $discount_price += $prices['events']['individual_days'][$date->format('Ymd')] * $options['value'];
                    }

                    // Make a percentage out of it.
                    $value = $discount_price / 100 * (-1);
                    $value = apply_filters('wpbs_pricing_discount_value', $value, $options, $prices, $payment, $calendar_id, $form_args, $form, $form_fields, $start_date, $end_date);

                    $value_with_vat = $value;
                    $value = $payment->vat->deduct_vat($value);
                } else {
                    // From Calendar and Form Prices
                    $value = (($subtotal * $options['value'] / $days_total) * $days_applicable_num) / 100 * (-1);
                    $value = apply_filters('wpbs_pricing_discount_value', $value, $options, $prices, $payment, $calendar_id, $form_args, $form, $form_fields, $start_date, $end_date);

                    if (isset($prices['vat_display_only']) && $prices['vat_display_only']) {
                        $payment->vat->add_vat_amount(($value - ($value / $payment->vat->percentage_calculation)));
                        $value_with_vat = $value;
                    } else {
                        $payment->vat->add_vat_amount(($value - $value * $payment->vat->percentage_calculation) * (-1));
                        $value_with_vat = round($value * $payment->vat->percentage_calculation, 2);
                    }
                }
            }

            if ($value == 0) {
                continue;
            }

            // Apply the discount

            $value = round($value, 2);

            $prices['total'] += $value;

            if (isset($options['visibility']) && $options['visibility'] == 'hide') {
                $prices['events']['price_with_vat'] += $value_with_vat;
                $prices['events']['price'] += $value;
            } else {
                $prices['discount'][] = array(
                    'name' => (!empty(wpbs_get_discount_meta($discount->get('id'), 'discount_name_translation_' . $language, true)) ? wpbs_get_discount_meta($discount->get('id'), 'discount_name_translation_' . $language, true) : $discount->get('name')),
                    'type' => $options['type'],
                    'description' => (isset($options['description_translation_' . $language]) && !empty($options['description_translation_' . $language]) ? $options['description_translation_' . $language] : (isset($options['description']) ? $options['description'] : '')),
                    'discount' => $options['value'],
                    'value_with_vat' => $value_with_vat,
                    'value' => $value,
                );
            }
        }
    }

    return $prices;
}
add_filter('wpbs_get_checkout_price_before_subtotal', 'wpbs_d_add_discount_to_checkout_price', 10, 8);

/**
 * Add discounts to line items
 *
 * @param array $line_items
 * @param WPBS_Payment $payment
 *
 * @return string
 *
 */
function wpbs_d_add_discount_to_checkout_pricing_table($line_items, $payment)
{

    $prices = $payment->get('prices');

    if (isset($prices['discount'])) {
        foreach ($prices['discount'] as $discount) {

            $line_items[] = array(
                'label' => '<span class="wpbs-line-item-bold">' . $discount['name'] . '</span>',
                'value' => wpbs_get_formatted_price($discount['value'], $payment->get_display_currency(), true),
                'description' => (!empty($discount['description'])) ? sanitize_text_field($discount['description']) : '',
                'quantity' => 1,
                'price' => $discount['value'],
                'price_with_vat' => isset($discount['value_with_vat']) ? $discount['value_with_vat'] : false,
                'individual_price' => $discount['value'],
                'individual_price_with_vat' => isset($discount['value_with_vat']) ? $discount['value_with_vat'] : false,
                'type' => 'discount',
                'class' => 'wpbs-pricing-table-discount wpbs-pricing-table-discount-' . sanitize_title($discount['name']),
                'editable' => true,
            );
        }
    }

    return $line_items;
}
add_filter('wpbs_line_items_before_subtotal', 'wpbs_d_add_discount_to_checkout_pricing_table', 10, 2);

/**
 * Get the correctly formatted date for the validity period
 * 
 * @param string $date 
 * @param string $type
 * 
 * @return string|bool
 * 
 */
function wpbs_discounts_get_date($date, $type = 'from')
{

    if (empty($date)) {
        if ($type == 'from') {
            return date('Y-m-d', strtotime('1 days ago'));
        }
        if ($type == 'to') {
            return date('Y-m-d', strtotime('+10 years'));
        }
    }

    if (DateTime::createFromFormat('Y-m-d', $date)) {
        return $date;
    }

    if (strtotime($date) !==  false) {
        return date('Y-m-d', strtotime($date));
    }

    return date('Y-m-d');
}

/**
 * Helper function to check if periods overlap
 *
 * @param array $periods
 *
 * @return bool
 *
 */
function wpbs_discounts_check_overlapping_periods($periods)
{
    $overlap = false;

    if (!count($periods)) {
        return false;
    }

    $timestamp_periods = [];

    foreach ($periods as $i => $period) {

        $period['from'] = wpbs_discounts_get_date($period['from'], 'from');
        $period['to'] = wpbs_discounts_get_date($period['to'], 'to');

        $from = DateTime::createFromFormat('Y-m-d', $period['from']);
		
		if($from === false){
			$from = DateTime::createFromFormat('Y-m-d', date('Y-m-d', strtotime('1 days ago')));
		}
		
        $from->setTime(0, 0, 0);

        $to = DateTime::createFromFormat('Y-m-d', $period['to']);
		
		if($to === false){
			$to = DateTime::createFromFormat('Y-m-d', date('Y-m-d', strtotime('+10 years')));
		}
		
        $to->setTime(0, 0, 0);

        if($from > $to){
            return true;
        }

        $timestamp_periods[$i] = array(
            'from' => $from->getTimestamp(),
            'to' => $to->getTimestamp(),
        );
    }

    foreach ($timestamp_periods as $i => $original) {
        foreach ($timestamp_periods as $j => $compare) {

            if ($i == $j) {
                continue;
            }

            if ($compare['to'] > $original['from'] && $original['to'] > $compare['from']) {
                return true;
            }
        }
    }

    return $overlap;
}

/**
 * Helper function to check if all period dates are valid
 *
 * @param array $periods
 *
 * @return bool|string
 *
 */
function wpbs_discounts_check_invalid_dates($periods)
{

    foreach ($periods as $period) {

        foreach ($period as $date) {

            if (empty($date)) {
                continue;
            }

            if (DateTime::createFromFormat('Y-m-d', $date)) {
                continue;
            }

            if (strtotime($date) !==  false) {
                continue;
            }

            return $date;
        }
    }

    return false;
}
