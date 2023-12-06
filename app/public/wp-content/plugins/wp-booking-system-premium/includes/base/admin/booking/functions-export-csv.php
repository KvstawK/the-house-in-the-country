<?php

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Export Bookings in CSV Format
 *
 */
function wpbs_action_export_bookings()
{
    // Verify for nonce
    if (empty($_GET['wpbs_token']) || !wp_verify_nonce($_GET['wpbs_token'], 'wpbs_export_bookings')) {
        return;
    }

    // Get the calendar ID
    $calendar_id = $_GET['calendar_id'];

    // Get the bookings
    $args = apply_filters('wpbs_export_bookings_args', array('status' => array('pending', 'accepted'), 'calendar_id' => $calendar_id));
    $bookings = wpbs_get_bookings($args);

    $csv_lines = wpbs_get_csv_file_contents($bookings);

    // Output headers so that the file is downloaded rather than displayed
    header('Content-type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename="wpbs-bookings-export-calendar-' . $calendar_id . '-' . time() . '.csv"');

    // Do not cache the file
    header('Pragma: no-cache');
    header('Expires: 0');
    echo "\xEF\xBB\xBF"; // UTF-8 BOM

    // Create a file pointer connected to the output stream
    $file = fopen('php://output', 'w');

    // Output each row of the data
    foreach ($csv_lines as $line) {
        $delimiter = apply_filters('wpbs_csv_delimiter', ',');
        fputcsv($file, $line, $delimiter);
    }

    exit();
}

add_action('wpbs_action_export_bookings', 'wpbs_action_export_bookings');


/**
 * Generate the CSV file contents
 * 
 * @param array $bookings
 * 
 * @return array
 * 
 */
function wpbs_get_csv_file_contents($bookings)
{
    /**
     * Build the CSV header
     *
     */

    // Add some default fields
    $csv_header = array('Booking ID' => '-', 'Calendar ID' => '-', 'Calendar Name' => '-', 'Start Date' => '-', 'End Date' => '-', 'Date Created' => '-');

    // Assume no payment was made for bookings
    $payment_exists = false;

    $settings = get_option('wpbs_settings');

    // Loop through all bookings and get all the form fields - in case bookings were accepted from more than one form
    foreach ($bookings as $booking) {

        // Get fields
        $fields = $booking->get('fields');

        $field_names = [];

        // Check if at least one payment was made, to include the Total field
        if ($payment_exists === false) {
            $payments = wpbs_get_payments(array('booking_id' => $booking->get('id')));
            if (!empty($payments)) {
                $payment_exists = true;
            }
        }

        // Loop through fields
        foreach ($fields as $field) {

            // Check for excluded fields
            if (in_array($field['type'], wpbs_get_excluded_fields(array('hidden')))) {
                continue;
            }

            // Check if a label exists
            if (empty($field['values']['default']['label'])) {
                continue;
            }

            $label = $field['values']['default']['label'];

            $field_names[$field['id']] = $label;

            // Handle Pricing options differently
            if (wpbs_form_field_is_product($field['type'])) {
                $label = $label . ' - Value';
            }

            // Add the label to the CSV header
            $csv_header[$label] = '-';
        }

        $payment = wpbs_get_payment_by_booking_id($booking->get('id'));
        if (!empty($payment)) {
            $csv_header[$settings['payment_product_name']] = '-';
            foreach ($payment->get_line_items() as $line_item_key => $line_item) {

                if (in_array((string) $line_item_key, array('events', 'total'))) {
                    continue;
                }

                if (isset($line_item['field_id'])) {
                    $csv_header[$field_names[$line_item['field_id']]  . ' - Price'] = '-';
                } else {
                    $csv_header[(isset($line_item['label_raw']) ? $line_item['label_raw'] : wpbs_format_html_string($line_item['label'])) . ' - Price'] = '-';
                }
            }
        }
    }

    // If a payment method was found, add the Total Amount field to the header
    if ($payment_exists === true) {
        $csv_header['Payment Status'] = '-';
        $csv_header['Total Amount'] = '-';
        $csv_header['Currency'] = '-';
    }

    // This is where all the data will be;
    $csv_lines = array();

    // Add the CSV header
    foreach ($csv_header as $header_key => $header_value) {
        $csv_lines[0][$header_key] = $header_key;
    }

    // Loop through bookings again to get field data
    foreach ($bookings as $index => $booking) {

        $i = $index + 1;
        $csv_lines[$i] = $csv_header;
        $field_names = [];

        $fields = $booking->get('fields');

        $calendar = wpbs_get_calendar($booking->get('calendar_id'));

        // Add standard fields
        $csv_lines[$i]['Booking ID'] = $booking->get('id');
        $csv_lines[$i]['Calendar ID'] = $booking->get('calendar_id');
        $csv_lines[$i]['Calendar Name'] = $calendar->get_name();
        $csv_lines[$i]['Start Date'] = wpbs_date_i18n(get_option('date_format'), strtotime($booking->get('start_date')));
        $csv_lines[$i]['End Date'] = wpbs_date_i18n(get_option('date_format'), strtotime($booking->get('end_date')));
        $csv_lines[$i]['Date Created'] = wpbs_date_i18n(get_option('date_format'), strtotime($booking->get('date_created')));

        // Loop through fields
        foreach ($fields as $field) {

            // Check for exluded fields
            if (in_array($field['type'], wpbs_get_excluded_fields(array('hidden')))) {
                continue;
            }

            $label = $field['values']['default']['label'];
            $field_names[$field['id']] = $label;

            // Get value
            $value = (isset($field['user_value'])) ? $field['user_value'] : '';

            // Handle Pricing options differently
            if (wpbs_form_field_is_product($field['type'])) {
                $label = $label . ' - Value';
                $value = wpbs_get_form_field_product_values($field);
            }

            // Check if key exists in header
            if (!array_key_exists($label, $csv_lines[$i])) {
                continue;
            }

            // Format arrays
            $value = wpbs_get_field_display_user_value($value);

            // Payment methods
            if ($field['type'] == 'payment_method' && isset(wpbs_get_payment_methods()[$value])) {
                $value = wpbs_get_payment_methods()[$value];
            }

            // Add data to CSV
            $csv_lines[$i][$label] = $value;
        }

        // Check if payments were found when building the headers
        if ($payment_exists === true) {

            // Get payment for current booking
            $payment = wpbs_get_payment_by_booking_id($booking->get('id'));
            if (empty($payment)) {
                continue;
            }

            $payment = wpbs_get_payment_by_booking_id($booking->get('id'));
            if (!is_null($payment)) {
                foreach ($payment->get_line_items() as $line_item_key => $line_item) {
                    if ($line_item_key === 'total') {
                        continue;
                    }
                    if ($line_item_key === 'events') {
                        $csv_lines[$i][$settings['payment_product_name']] = $line_item['price'];
                    } else {
                        if (isset($line_item['field_id'])) {
                            $csv_lines[$i][$field_names[$line_item['field_id']]  . ' - Price'] = $line_item['price'];
                        } else {
                            $csv_lines[$i][(isset($line_item['label_raw']) ? $line_item['label_raw'] : wpbs_format_html_string($line_item['label']))  . ' - Price'] = $line_item['price'];
                        }
                    }
                }
            }

            // Add payment data to CSV
            $csv_lines[$i]['Payment Status'] = strip_tags(WPBS_Bookings_Outputter::payment_status($booking));
            $csv_lines[$i]['Total Amount'] = $payment->get_total();
            $csv_lines[$i]['Currency'] = $payment->get_currency();
        }
    }

    return $csv_lines;
}
