<?php

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Add Pricing Tab to Booking Details
 *
 */
function wpbs_booking_modal_add_invoice_tab($tabs, $booking)
{
    if (!wpbs_is_pricing_enabled()) {
        return $tabs;
    }

    $payments = wpbs_get_payments(array('booking_id' => $booking->get('id')));

    if (empty($payments)) {
        return $tabs;
    }

    $tabs['invoice'] = '<svg aria-hidden="true" focusable="false" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512"><path fill="currentColor" d="M219.09 327.42l-45-13.5c-5.16-1.55-8.77-6.78-8.77-12.73 0-7.27 5.3-13.19 11.8-13.19h28.11c4.56 0 8.96 1.29 12.82 3.72 3.24 2.03 7.36 1.91 10.13-.73l11.75-11.21c3.53-3.37 3.33-9.21-.57-12.14-9.1-6.83-20.08-10.77-31.37-11.35V232c0-4.42-3.58-8-8-8h-16c-4.42 0-8 3.58-8 8v24.12c-23.62.63-42.67 20.55-42.67 45.07 0 19.97 12.98 37.81 31.58 43.39l45 13.5c5.16 1.55 8.77 6.78 8.77 12.73 0 7.27-5.3 13.19-11.8 13.19h-28.11c-4.56 0-8.96-1.29-12.82-3.72-3.24-2.03-7.36-1.91-10.13.73l-11.75 11.21c-3.53 3.37-3.33 9.21.57 12.14 9.1 6.83 20.08 10.77 31.37 11.35V440c0 4.42 3.58 8 8 8h16c4.42 0 8-3.58 8-8v-24.12c23.62-.63 42.67-20.54 42.67-45.07 0-19.97-12.98-37.81-31.58-43.39zM72 96h112c4.42 0 8-3.58 8-8V72c0-4.42-3.58-8-8-8H72c-4.42 0-8 3.58-8 8v16c0 4.42 3.58 8 8 8zm120 56v-16c0-4.42-3.58-8-8-8H72c-4.42 0-8 3.58-8 8v16c0 4.42 3.58 8 8 8h112c4.42 0 8-3.58 8-8zm177.9-54.02L286.02 14.1c-9-9-21.2-14.1-33.89-14.1H47.99C21.5.1 0 21.6 0 48.09v415.92C0 490.5 21.5 512 47.99 512h288.02c26.49 0 47.99-21.5 47.99-47.99V131.97c0-12.69-5.1-24.99-14.1-33.99zM256.03 32.59c2.8.7 5.3 2.1 7.4 4.2l83.88 83.88c2.1 2.1 3.5 4.6 4.2 7.4h-95.48V32.59zm95.98 431.42c0 8.8-7.2 16-16 16H47.99c-8.8 0-16-7.2-16-16V48.09c0-8.8 7.2-16.09 16-16.09h176.04v104.07c0 13.3 10.7 23.93 24 23.93h103.98v304.01z"></path></svg>' . __('Invoice', 'wp-booking-system-invoices');
    
    return $tabs;
}
add_action('wpbs_booking_modal_tabs', 'wpbs_booking_modal_add_invoice_tab', 20, 2);

/**
 * Add Pricing Tab view
 * 
 * @param WPBS_Booking $booking
 * @param WPBS_Calendar $calendat
 *
 */
function wpbs_booking_modal_add_invoice_view($booking, $calendar)
{
    if (!wpbs_is_pricing_enabled()) {
        return false;
    }

    include 'views/view-modal-invoice.php';
}
add_action('wpbs_booking_modal_tab_invoice', 'wpbs_booking_modal_add_invoice_view', 10, 2);

/**
 * Add the invoice links to the Payment Details tab in the Booking Modal
 * 
 * @param array $order_information
 * @param WPBS_Payment $payment
 * 
 * @return array
 * 
 */
function wpbs_booking_details_order_information_invoice_link($order_information, $payment)
{

    $booking = wpbs_get_booking($payment->get('booking_id'));

    $order_information[] = array(
        'label' => __('Invoice', 'wp-booking-system-invoices'),
        'value' => '<a href="' . wpbs_get_invoice_link($booking->get('invoice_hash')) . '" target="_blank" title="' . __('View Invoice', 'wp-booking-system-invoices') . '">' . __('View Invoice', 'wp-booking-system-invoices') . '</a> | <a href="' . wpbs_get_invoice_link($booking->get('invoice_hash'), true) . '" target="_blank" title="' . __('Download Invoice', 'wp-booking-system-invoices') . '">' . __('Download Invoice', 'wp-booking-system-invoices') . '</a>',
    );

    return $order_information;
}
add_filter('wpbs_booking_details_order_information', 'wpbs_booking_details_order_information_invoice_link', 50, 2);

/**
 * AJAX callback function to update the buyer details
 * 
 */
function wpbs_action_ajax_update_invoice_details()
{
    // Nonce
    check_ajax_referer('wpbs_update_invoice_details', 'wpbs_token');

    if(!isset($_POST['booking_id'])){
        return false;
    }

    $booking_id = absint($_POST['booking_id']);

    $buyer_details = sanitize_textarea_field($_POST['buyer_details']);

    wpbs_update_booking_meta($booking_id, 'invoice_buyer_details', $buyer_details);

    wp_die();

}
add_action('wp_ajax_wpbs_update_invoice_details', 'wpbs_action_ajax_update_invoice_details');

/**
 * Add option to include invoice in the Email Customer section of the Booking Modal
 * 
 */
function wpbs_invc_booking_modal_email_customer_after($booking){
    
    $payments = wpbs_get_payments(array('booking_id' => $booking->get('id')));

    if (empty($payments)) {
        return false;
    }
    
    ?>
        <div class="wpbs-settings-field-wrapper wpbs-settings-field-inline wpbs-settings-field-xlarge">
        <label class="wpbs-settings-field-label" for="booking_email_customer_attach_invoice"><?php echo __( 'Attach Invoice to Email', 'wp-booking-system-invoices' ); ?></label>
        <div class="wpbs-settings-field-inner">
            <label for="booking_email_customer_attach_invoice" class="wpbs-checkbox-switch">
                <input name="booking_email_customer_attach_invoice" type="checkbox" id="booking_email_customer_attach_invoice"  class="regular-text wpbs-settings-toggle">
                <div class="wpbs-checkbox-slider"></div>
            </label>
        </div>
    </div>
    <?php
}
add_action('wpbs_booking_modal_email_customer_after', 'wpbs_invc_booking_modal_email_customer_after', 10, 1);

/**
 * Add option to include invoice in the Accept Booking section of the Booking Modal
 * 
 */
function wpbs_invc_booking_modal_email_accept_booking_after($booking){

    $payments = wpbs_get_payments(array('booking_id' => $booking->get('id')));

    if (empty($payments)) {
        return false;
    }
    
    ?>
        <div class="wpbs-settings-field-wrapper wpbs-settings-field-inline wpbs-settings-field-xlarge">
        <label class="wpbs-settings-field-label" for="booking_email_accept_booking_attach_invoice"><?php echo __( 'Attach Invoice to Email', 'wp-booking-system-invoices' ); ?></label>
        <div class="wpbs-settings-field-inner">
            <label for="booking_email_accept_booking_attach_invoice" class="wpbs-checkbox-switch">
                <input name="booking_email_accept_booking_attach_invoice" type="checkbox" id="booking_email_accept_booking_attach_invoice"  class="regular-text wpbs-settings-toggle">
                <div class="wpbs-checkbox-slider"></div>
            </label>
        </div>
    </div>
    <?php
}
add_action('wpbs_booking_modal_email_accept_booking_after', 'wpbs_invc_booking_modal_email_accept_booking_after', 10, 1);