<?php

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Add Pricing Tab to Booking Details
 *
 */
function wpbs_booking_modal_add_contract_tab($tabs, $booking)
{
    
    $tabs['contract'] = '<svg aria-hidden="true" focusable="false" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512"><path fill="currentColor" d="M560.83 135.96l-24.79-24.79c-20.23-20.24-53-20.26-73.26 0L384 189.72v-57.75c0-12.7-5.1-25-14.1-33.99L286.02 14.1c-9-9-21.2-14.1-33.89-14.1H47.99C21.5.1 0 21.6 0 48.09v415.92C0 490.5 21.5 512 47.99 512h288.02c26.49 0 47.99-21.5 47.99-47.99v-80.54c6.29-4.68 12.62-9.35 18.18-14.95l158.64-159.3c9.79-9.78 15.17-22.79 15.17-36.63s-5.38-26.84-15.16-36.63zM256.03 32.59c2.8.7 5.3 2.1 7.4 4.2l83.88 83.88c2.1 2.1 3.5 4.6 4.2 7.4h-95.48V32.59zm95.98 431.42c0 8.8-7.2 16-16 16H47.99c-8.8 0-16-7.2-16-16V48.09c0-8.8 7.2-16.09 16-16.09h176.04v104.07c0 13.3 10.7 23.93 24 23.93h103.98v61.53l-48.51 48.24c-30.14 29.96-47.42 71.51-47.47 114-3.93-.29-7.47-2.42-9.36-6.27-11.97-23.86-46.25-30.34-66-14.17l-13.88-41.62c-3.28-9.81-12.44-16.41-22.78-16.41s-19.5 6.59-22.78 16.41L103 376.36c-1.5 4.58-5.78 7.64-10.59 7.64H80c-8.84 0-16 7.16-16 16s7.16 16 16 16h12.41c18.62 0 35.09-11.88 40.97-29.53L144 354.58l16.81 50.48c4.54 13.51 23.14 14.83 29.5 2.08l7.66-15.33c4.01-8.07 15.8-8.59 20.22.34C225.44 406.61 239.9 415.7 256 416h32c22.05-.01 43.95-4.9 64.01-13.6v61.61zm27.48-118.05A129.012 129.012 0 0 1 288 384v-.03c0-34.35 13.7-67.29 38.06-91.51l120.55-119.87 52.8 52.8-119.92 120.57zM538.2 186.6l-21.19 21.19-52.8-52.8 21.2-21.19c7.73-7.73 20.27-7.74 28.01 0l24.79 24.79c7.72 7.73 7.72 20.27-.01 28.01z"></path></svg>' . __('Contract', 'wp-booking-system-contracts');
    return $tabs;
}
add_action('wpbs_booking_modal_tabs', 'wpbs_booking_modal_add_contract_tab', 20, 2);

/**
 * Add Contract Tab view
 *
 * @param WPBS_Booking $booking
 * @param WPBS_Calendar $calendat
 *
 */
function wpbs_booking_modal_add_contract_view($booking, $calendar)
{
    include 'views/view-modal-contract.php';
}
add_action('wpbs_booking_modal_tab_contract', 'wpbs_booking_modal_add_contract_view', 10, 2);

/**
 * Add the contract links to the Payment Details tab in the Booking Modal
 *
 * @param array $order_information
 * @param WPBS_Payment $payment
 *
 * @return array
 *
 */
function wpbs_booking_details_order_information_contract_link($order_information, $payment)
{

    if (empty(wpbs_get_booking_meta($payment->get('booking_id'), 'contract_content', true))) {
        $value = __('Contract content not available for this booking.', 'wp-booking-system-contracts');
    } else {
        $booking = wpbs_get_booking($payment->get('booking_id'));
        $value = '<a href="' . wpbs_get_contract_link($booking->get('invoice_hash')) . '" target="_blank" title="' . __('View Contract', 'wp-booking-system-contracts') . '">' . __('View Contract', 'wp-booking-system-contracts') . '</a> | <a href="' . wpbs_get_contract_link($booking->get('invoice_hash'), true) . '" target="_blank" title="' . __('Download Contract', 'wp-booking-system-contracts') . '">' . __('Download Contract', 'wp-booking-system-contracts') . '</a>';
    }

    $order_information[] = array(
        'label' => __('Contract', 'wp-booking-system-contracts'),
        'value' => $value,
    );

    return $order_information;
}
add_filter('wpbs_booking_details_order_information', 'wpbs_booking_details_order_information_contract_link', 60, 2);

/**
 * AJAX callback function to update the buyer details
 *
 */
function wpbs_action_ajax_update_contract_details()
{
    // Nonce
    check_ajax_referer('wpbs_update_contract_details', 'wpbs_token');

    if (!isset($_POST['booking_id'])) {
        return false;
    }

    $booking_id = absint($_POST['booking_id']);

    $contract_content = wp_kses_post($_POST['contract_content']);

    wpbs_update_booking_meta($booking_id, 'contract_content', $contract_content);

    wp_die();

}
add_action('wp_ajax_wpbs_update_contract_details', 'wpbs_action_ajax_update_contract_details');

/**
 * AJAX callback function to update the buyer details
 *
 */
function wpbs_action_ajax_regenerate_contract_details()
{
    // Nonce
    check_ajax_referer('wpbs_update_contract_details', 'wpbs_token');

    if (!isset($_POST['booking_id'])) {
        return false;
    }

    $booking_id = absint($_POST['booking_id']);

    $booking = wpbs_get_booking($booking_id);
    
    $form = wpbs_get_form($booking->get('form_id'));

    $calendar = wpbs_get_calendar($booking->get('calendar_id'));

    $contract_content = wpbs_get_translated_form_meta($booking->get('form_id'), 'contract_content', wpbs_get_booking_meta($booking_id, 'submitted_language', true));

    $email_tags = new WPBS_Email_Tags($form, $calendar, $booking_id, $booking->get('fields'), wpbs_get_booking_meta($booking_id, 'submitted_language', true), strtotime($booking->get('start_date')), strtotime($booking->get('end_date')));
    $contract_content = $email_tags->parse($contract_content);

    wpbs_update_booking_meta($booking_id, 'contract_content', $contract_content);

    echo apply_filters('the_content', $contract_content);

    wp_die();

}
add_action('wp_ajax_wpbs_regenerate_contract_details', 'wpbs_action_ajax_regenerate_contract_details');

/**
 * Add option to include contract in the Email Customer section of the Booking Modal
 *
 */
function wpbs_cntrct_booking_modal_email_customer_after($booking)
{
    ?>
        <div class="wpbs-settings-field-wrapper wpbs-settings-field-inline wpbs-settings-field-xlarge">
        <label class="wpbs-settings-field-label" for="booking_email_customer_attach_contract"><?php echo __('Attach Contract to Email', 'wp-booking-system-contracts'); ?></label>
        <div class="wpbs-settings-field-inner">
            <label for="booking_email_customer_attach_contract" class="wpbs-checkbox-switch">
                <input name="booking_email_customer_attach_contract" type="checkbox" id="booking_email_customer_attach_contract"  class="regular-text wpbs-settings-toggle">
                <div class="wpbs-checkbox-slider"></div>
            </label>
        </div>
    </div>
    <?php
}
add_action('wpbs_booking_modal_email_customer_after', 'wpbs_cntrct_booking_modal_email_customer_after', 10, 1);

/**
 * Add option to include contract in the Accept Booking section of the Booking Modal
 *
 */
function wpbs_cntrct_booking_modal_email_accept_booking_after($booking)
{

    ?>
        <div class="wpbs-settings-field-wrapper wpbs-settings-field-inline wpbs-settings-field-xlarge">
        <label class="wpbs-settings-field-label" for="booking_email_accept_booking_attach_contract"><?php echo __('Attach Contract to Email', 'wp-booking-system-contracts'); ?></label>
        <div class="wpbs-settings-field-inner">
            <label for="booking_email_accept_booking_attach_contract" class="wpbs-checkbox-switch">
                <input name="booking_email_accept_booking_attach_contract" type="checkbox" id="booking_email_accept_booking_attach_contract"  class="regular-text wpbs-settings-toggle">
                <div class="wpbs-checkbox-slider"></div>
            </label>
        </div>
    </div>
    <?php
}
add_action('wpbs_booking_modal_email_accept_booking_after', 'wpbs_cntrct_booking_modal_email_accept_booking_after', 10, 1);