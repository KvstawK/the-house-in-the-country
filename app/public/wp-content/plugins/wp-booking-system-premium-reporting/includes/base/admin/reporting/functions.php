<?php

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Includes the files needed for the reporting admin area
 *
 */
function wpbs_r_include_files_admin_reporting()
{

    // Get legend admin dir path
    $dir_path = plugin_dir_path(__FILE__);

    // Include submenu page
    if (file_exists($dir_path . 'class-submenu-page-reporting.php')) {
        include $dir_path . 'class-submenu-page-reporting.php';
    }

    // Include main class file
    if (file_exists($dir_path . 'class-reporting.php')) {
        include $dir_path . 'class-reporting.php';
    }
}
add_action('wpbs_r_include_files', 'wpbs_r_include_files_admin_reporting');

/**
 * Register the reporting admin submenu page
 *
 */
function wpbs_r_register_submenu_page_reporting($submenu_pages)
{

    if (!is_array($submenu_pages)) {
        return $submenu_pages;
    }

    $submenu_pages['reporting'] = array(
        'class_name' => 'WPBS_Submenu_Page_Reporting',
        'data' => array(
            'page_title' => __('Reports', 'wp-booking-system-reporting'),
            'menu_title' => __('Reports', 'wp-booking-system-reporting'),
            'capability' => apply_filters('wpbs_submenu_page_capability_reporting', 'manage_options'),
            'menu_slug' => 'wpbs-reporting',
        ),
    );

    return $submenu_pages;
}
add_filter('wpbs_register_submenu_page', 'wpbs_r_register_submenu_page_reporting', 50);

add_action('init', function () {

    if (!isset($_GET['wpbs-reports-export'])) {
        return false;
    }

    if (!class_exists('\Mpdf\Mpdf')) {
        require WPBS_R_PLUGIN_DIR . 'libs/mfpdf/vendor/autoload.php';
    }

    if (isset($_GET['wpbs-reporting-start-date']) && !empty($_GET['wpbs-reporting-start-date'])) {
        $start_date = DateTime::createFromFormat('Y-m-d H:i:s', $_GET['wpbs-reporting-start-date'] . ' 00:00:00');
    } else {
        $start_date = new DateTime('7 days ago');
    }

    if (isset($_GET['wpbs-reporting-end-date']) && !empty($_GET['wpbs-reporting-end-date'])) {
        $end_date = DateTime::createFromFormat('Y-m-d H:i:s', $_GET['wpbs-reporting-end-date'] . ' 00:00:00');
        $end_date->modify('+1 day');
    } else {
        $end_date = new DateTime('now');
        $end_date->setTime(23, 59, 59);
    }

    $available_payment_methods = wpbs_get_payment_methods();

    $pdf = new \Mpdf\Mpdf(['format' => 'A4-L']);

    $footer = array(
        'C' => array(
            'content' => '{PAGENO} / {nb}',
            'color' => '#5a5a5a',
        ),
        'line' => 0,
    );

    $pdf->SetFooter($footer, 'O');
    $pdf->SetFooter($footer, 'E');

    $pdf->AddPage();

    ob_start();

?>

    <br><br><br><br><br><br><br><br><br><br><br><br>
    <h1>WP Booking System - <?php echo  __('Financial Report', 'wp-booking-system-reporting'); ?></h1>
    <h3><?php echo  __('Period', 'wp-booking-system-reporting') . ': ' . $start_date->format(get_option('date_format')) . ' - ' . $end_date->format(get_option('date_format')); ?></h3>
    <p class="generated-on"><?php echo  __('Generated on', 'wp-booking-system-reporting'); ?>: <?php echo  wpbs_date_i18n(get_option('date_format') . ' ' . get_option('time_format'), time()); ?></p>

    <style>
        body {
            font-family: Arial, sans-serif;
        }

        h1,
        h3 {
            text-align: center;
            margin: 0;
            padding: 0;
        }

        h3 {
            font-weight: normal;
        }

        p {
            margin: 0 0 20px 0;
            padding: 0;
        }

        .wpbs-reports-export {
            border: 1px solid #000;
            border-spacing: 0;
            table-layout: fixed;
            border-collapse: collapse;
            width: 100%;
            font-size: 12px;
        }

        .wpbs-reports-export td,
        .wpbs-reports-export th {
            border: 1px solid #000;
            padding: 4px 7px;
            text-align: left;
        }

        p.generated-on {
            text-align: center;
            font-size: 12px;
        }
    </style>
    <?php

    $content = ob_get_contents();
    ob_clean();
    $pdf->WriteHTML($content);

    $calendars = wpbs_get_calendars(array('status' => 'active'));

    foreach ($calendars as $calendar) {

        $bookings = wpbs_get_bookings(array('calendar_id' => $calendar->get('id'), 'status' => ['accepted', 'pending']));

        if (!count($bookings)) {
            continue;
        }

        // Add some default fields
        $csv_header = array(__('Booking ID', 'wp-booking-system-reporting') => '-', __('Booking Date', 'wp-booking-system-reporting') => '-', __('Start Date', 'wp-booking-system-reporting') => '-', __('End Date', 'wp-booking-system-reporting') => '-');

        // Assume no payment was made for bookings
        $payment_exists = false;

        $settings = get_option('wpbs_settings');

        // Loop through all bookings and get all the form fields - in case bookings were accepted from more than one form
        foreach ($bookings as $booking) {

            $booking_date = DateTime::createFromFormat('Y-m-d H:i:s', $booking->get('date_created'));
            if ($booking_date <= $start_date || $booking_date >= $end_date) {
                continue;
            }

            // Check if at least one payment was made, to include the Total field
            if ($payment_exists === false) {
                $payments = wpbs_get_payments(array('booking_id' => $booking->get('id')));
                if (!empty($payments)) {
                    $payment_exists = true;
                }
            }

            $payment = wpbs_get_payment_by_booking_id($booking->get('id'));

            if (!empty($payment)) {
                $csv_header[$settings['payment_product_name']] = 0;
                foreach ($payment->get_line_items() as $line_item_key => $line_item) {

                    if (in_array((string) $line_item_key, ['total', 'events', 'first_payment', 'second_payment'])) {
                        continue;
                    }

                    $csv_header[(isset($line_item['label_raw']) ? $line_item['label_raw'] : wpbs_format_html_string($line_item['label']))] = 0;
                }
            }
        }

        $totals = [];
        // If a payment method was found, add the Total Amount field to the header
        if ($payment_exists === true) {
            $csv_header['Total Amount'] = 0;
            $csv_header['Payment Method'] = '<strong>' . __('No Payment', 'wp-booking-system-reporting') . '</strong>';
        }

        // This is where all the data will be;
        $csv_lines = array();

        // Add the CSV header
        foreach ($csv_header as $header_key => $header_value) {
            $csv_lines[0][$header_key] = $header_key;
        }

        // Loop through bookings again to get field data
        foreach ($bookings as $index => $booking) {

            $booking_date = DateTime::createFromFormat('Y-m-d H:i:s', $booking->get('date_created'));

            if ($booking_date <= $start_date || $booking_date >= $end_date) {
                continue;
            }

            $i = $index + 1;
            $csv_lines[$i] = $csv_header;

            $fields = $booking->get('fields');

            // Add standard fields
            $csv_lines[$i][__('Booking ID', 'wp-booking-system-reporting')] = '#' . $booking->get('id');
            $csv_lines[$i][__('Booking Date', 'wp-booking-system-reporting')] = wpbs_date_i18n('d-m-Y', strtotime($booking->get('date_created')));
            $csv_lines[$i][__('Start Date', 'wp-booking-system-reporting')] = wpbs_date_i18n('d-m-Y', strtotime($booking->get('start_date')));
            $csv_lines[$i][__('End Date', 'wp-booking-system-reporting')] = wpbs_date_i18n('d-m-Y', strtotime($booking->get('end_date')));

            // Check if payments were found when building the headers
            if ($payment_exists === true) {

                // Get payment for current booking
                $payment = wpbs_get_payment_by_booking_id($booking->get('id'));
                if (empty($payment)) {
                    continue;
                }

                $currency = $payment->get_currency();

                if (!is_null($payment)) {

                    foreach ($payment->get_line_items() as $line_item_key => $line_item) {
                        if (in_array($line_item_key, ['total', 'first_payment', 'second_payment'])) {
                            continue;
                        }

                        $price = $line_item['price'];

                        if ($line_item_key == 'events') {
                            $csv_lines[$i][$settings['payment_product_name']] = $price;
                        } else {
                            $csv_lines[$i][(isset($line_item['label_raw']) ? $line_item['label_raw'] : wpbs_format_html_string($line_item['label']))] = $price;
                        }
                    }
                }

                if ($payment->get('order_status') != 'completed' && !in_array($payment->get('gateway'), array('payment_on_arrival', 'bank_transfer'))) {
                    $csv_lines[$i]['Total Amount'] = 0;
                } else {
                    // Add payment data to CSV
                    $csv_lines[$i]['Total Amount'] = $payment->get_total();
                }
                
                $csv_lines[$i]['Payment Method'] = isset($available_payment_methods[$payment->get_payment_method()]) ? $available_payment_methods[$payment->get_payment_method()] : '-' ;
            }
        }

    ?>

        <pagebreak>

            <h2><?php echo $calendar->get_name(); ?> (#<?php echo $calendar->get('id'); ?>)</h2>


            <?php if (count($csv_lines) == 1) {
                echo __('No Bookings.', 'wp-booking-system-reporting');
                continue;
            } ?>
            <p><?php _e('Total Bookings', 'wp-booking-system-reporting') ?>: <?php echo count($csv_lines) - 1; ?></p>

            <table class="wpbs-reports-export">

                <thead>
                    <tr>
                        <?php $head = array_shift($csv_lines); ?>
                        <?php foreach ($head as $cell) : ?>
                            <th><?php echo $cell; ?></th>
                        <?php endforeach; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($csv_lines as $row) : ?>
                        <tr>
                            <?php foreach ($row as $i => $cell) : ?>
                                <td><?php echo is_numeric($cell) ? wpbs_get_formatted_price($cell, $currency) : $cell; ?></td>

                                <?php if (!is_numeric($cell)) {
                                    continue;
                                }
                                if (!isset($totals[$i])) {
                                    $totals[$i] = 0;
                                }
                                $totals[$i] += $cell; ?>
                            <?php endforeach; ?>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <?php foreach ($totals as $cell) : ?>
                            <th><?php echo wpbs_get_formatted_price($cell, $currency) ?></th>
                        <?php endforeach; ?>
                        <th></th>
                    </tr>
                </tfoot>

            </table>

            <?php do_action('wpbs_financial_report_after_table', $calendar, $totals, $currency) ?>

    <?php

        $content = ob_get_contents();
        ob_clean();
        $pdf->WriteHTML($content);
    }

    ob_end_clean();

    $pdf->Output(time() . '.pdf', 'I');
    exit;
});

