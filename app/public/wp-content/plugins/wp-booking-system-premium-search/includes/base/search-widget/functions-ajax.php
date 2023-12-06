<?php

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Ajax callback when the search form is submitted
 * 
 */
function wpbs_s_ajax_search_calendars()
{
    check_ajax_referer('wpbs_s_search_form', 'wpbs_s_token');
    // Check args
    foreach ($_POST['args'] as $key => $val) {
        if (in_array($key, array_keys(wpbs_s_get_search_widget_default_args()))) {
            $search_widget_args[$key] = sanitize_text_field($val);
        }
    }

    $start_date = isset($_POST['start_date']) ? sanitize_text_field($_POST['start_date']) : '';
    $end_date = isset($_POST['end_date']) ? sanitize_text_field($_POST['end_date']) : '';

    $additional_search_fields = wpbs_s_get_additional_search_fields();

    $additional_data = [];

    if ($additional_search_fields) {
        parse_str($_POST['form_data'], $form_data);

        foreach ($additional_search_fields as $field) {
            if (isset($form_data[$field['slug']]) && !empty($form_data[$field['slug']])) {
                $additional_data[$field['slug']] = sanitize_text_field($form_data[$field['slug']]);
            }
        }
    }

    $search_widget_outputter = new WPBS_S_Search_Widget_Outputter($search_widget_args, $start_date, $end_date, $additional_data);

    echo $search_widget_outputter->get_display();

    exit;
}

add_action('wp_ajax_wpbs_s_search_calendars', 'wpbs_s_ajax_search_calendars');
add_action('wp_ajax_nopriv_wpbs_s_search_calendars', 'wpbs_s_ajax_search_calendars');
