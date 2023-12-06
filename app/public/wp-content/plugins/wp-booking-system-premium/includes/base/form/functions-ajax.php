<?php

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Ajax callback when submitting the form
 *
 * This function handles everything when submitting the form: validation, emails, payments screens, auto-pending functionality.
 *
 */
function wpbs_submit_form()
{

    $validate_nonce = apply_filters('wpbs_form_ajax_nonce', true);

    // Nonce
    if ($validate_nonce && (empty($_POST['wpbs_token']) || !wp_verify_nonce($_POST['wpbs_token'], 'wpbs_form_ajax'))) {
        $response = array('success' => false);
        $response['html'] = '<div class="wpbs-form-general-error">' . sprintf(__("Nonce validation failed. %sClick here to find out how to fix this.%s", 'wp-booking-system'), '<a href="https://www.wpbookingsystem.com/documentation/nonce-validation-failed/" target="_blank" title="">', '</a>') . '</div>';
        echo json_encode($response);
        wp_die();
    }

    // Get Form ID
    $form_id = absint(!empty($_POST['form']['id']) ? $_POST['form']['id'] : 0);
    $form = wpbs_get_form($form_id);

    if (is_null($form)) {
        return;
    }

    $calendar_id = absint(!empty($_POST['calendar']['id']) ? $_POST['calendar']['id'] : 0);
    $calendar = wpbs_get_calendar($calendar_id);

    if (is_null($calendar)) {
        return;
    }

    // Set the form arguments
    $form_args = array(
        'minimum_days' => (int) $_POST['form']['minimum_days'],
        'maximum_days' => (int) $_POST['form']['maximum_days'],
        'booking_start_day' => (int) $_POST['form']['booking_start_day'],
        'booking_end_day' => (int) $_POST['form']['booking_end_day'],
        'selection_type' => $_POST['form']['selection_type'],
        'selection_style' => $_POST['form']['selection_style'],
        'auto_pending' => $_POST['form']['auto_pending'],
        'show_date_selection' => (int) $_POST['form']['show_date_selection'],
        'language' => ($_POST['form']['language'] == 'auto' ? wpbs_get_locale() : $_POST['form']['language']),
        'currency' => $_POST['form']['currency'],
        'manual_booking' => (isset($_POST['form']['manual_booking']) ? $_POST['form']['manual_booking'] : ''),
    );

    // Validate Form
    $form_validator = new WPBS_Form_Validator($form, $calendar, $_POST['form_data'], $_POST['form']['language']);
    $form_validator->sanitize_fields();
    $form_validator->validate_fields($form_args, $_POST['calendar']);
    $form_validator->validate_dates($_POST['form'], $_POST['calendar']);
    $form_validator->set_error_message();

    // Get form fields after sanitization and validation
    $form_fields = $form_validator->get_form_fields();

    if ($form_validator->has_errors() === true) {

        // Errors were found, we show the form again
        $form_outputter = new WPBS_Form_Outputter($form, $form_args, $form_fields, $calendar_id);

        // Response
        $response = array('success' => false);
        $response['html'] = $form_outputter->get_display();

    } else {

        $payment_confirmation = apply_filters('wpbs_submit_form_before', false, $_POST, $form, $form_args, $form_fields, $calendar_id);
        if ($payment_confirmation !== false) {
            echo $payment_confirmation;
            wp_die();
        }

        // Process booking, events, emails and confirmation messages.
        $handler = new WPBS_Form_Handler($_POST, $form_id, $form_args, $form_fields, $calendar_id);
        $response = $handler->get_response();

    }

    echo json_encode($response);

    wp_die();
}

add_action('wp_ajax_nopriv_wpbs_submit_form', 'wpbs_submit_form');
add_action('wp_ajax_wpbs_submit_form', 'wpbs_submit_form');

/**
 * Ajax callback when selecting a date
 *
 * The function validates the date selection
 *
 */
function wpbs_validate_date_selection()
{

    $validate_nonce = apply_filters('wpbs_form_ajax_nonce', true);

    if ($validate_nonce) {
        // Nonce
        check_ajax_referer('wpbs_form_ajax', 'wpbs_token');
    }

    // Get Form ID
    $form_id = absint(!empty($_POST['form']['id']) ? $_POST['form']['id'] : 0);
    $form = wpbs_get_form($form_id);

    if (is_null($form)) {
        return;
    }

    $calendar_id = absint(!empty($_POST['calendar']['id']) ? $_POST['calendar']['id'] : 0);
    $calendar = wpbs_get_calendar($calendar_id);

    if (is_null($calendar)) {
        return;
    }

    // Validate Form
    $form_validator = new WPBS_Form_Validator($form, $calendar, $_POST['form_data'], $_POST['form']['language']);
    $form_validator->sanitize_fields();
    $form_validator->validate_dates($_POST['form'], $_POST['calendar']);
    $form_validator->set_error_message();

    // Get form fields after sanitization and validation
    $form_fields = $form_validator->get_form_fields();

    // Set the form arguments
    $form_args = array(
        'minimum_days' => (int) $_POST['form']['minimum_days'],
        'maximum_days' => (int) $_POST['form']['maximum_days'],
        'booking_start_day' => (int) $_POST['form']['booking_start_day'],
        'booking_end_day' => (int) $_POST['form']['booking_end_day'],
        'selection_type' => $_POST['form']['selection_type'],
        'selection_style' => $_POST['form']['selection_style'],
        'auto_pending' => $_POST['form']['auto_pending'],
        'show_date_selection' => (int) $_POST['form']['show_date_selection'],
        'language' => ($_POST['form']['language'] == 'auto' ? wpbs_get_locale() : $_POST['form']['language']),
        'currency' => $_POST['form']['currency'],
        'manual_booking' => (isset($_POST['form']['manual_booking']) ? $_POST['form']['manual_booking'] : ''),
    );

    if ($form_validator->has_errors() === true) {

        // Errors were found, we show the form again
        $form_outputter = new WPBS_Form_Outputter($form, $form_args, $form_fields, $calendar_id);

        // Response
        $response = array('success' => false);
        $response['html'] = $form_outputter->get_display();

    } else {
        $response = array('success' => true);
    }
    echo json_encode($response);

    wp_die();
}
add_action('wp_ajax_nopriv_wpbs_validate_date_selection', 'wpbs_validate_date_selection');
add_action('wp_ajax_wpbs_validate_date_selection', 'wpbs_validate_date_selection');
