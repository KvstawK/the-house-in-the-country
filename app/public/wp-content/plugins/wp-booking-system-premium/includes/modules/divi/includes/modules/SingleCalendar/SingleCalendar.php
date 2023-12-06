<?php
class WPBS_Divi_Module_SingleCalendar extends ET_Builder_Module
{

    public $slug = 'wpbs_divi_single_calendar';
    public $vb_support = 'on';

    public function init()
    {
        $this->name = esc_html__('Single Calendar', 'wp-booking-system');
    }

    public function get_fields()
    {

        // Calendars
        $calendars_dropdown = array();
        $calendars_dropdown['0-no-calendar'] = '-';
        $calendars = wpbs_get_calendars(array('status' => 'active'));
        foreach ($calendars as $calendar) {
            $calendars_dropdown[$calendar->get('id')] = $calendar->get('name') . ' (ID: '.$calendar->get('id').')';
        }

        // Forms
        $forms_dropdown = array();
        $forms_dropdown['0-no-form'] = __('No Form', 'wp-booking-system');
        $forms = wpbs_get_forms(array('status' => 'active'));
        foreach ($forms as $form) {
            $forms_dropdown[$form->get('id')] = $form->get('name');
        }

        // Months to Display
        $months_to_display = array();
        for ($i = 1; $i <= 24; $i++) {
            $months_to_display[$i] = (string) $i;
        }

        // Start Year
        $start_year = array();
        $start_year['0-wpbs-default'] = __('Current Year', 'wp-booking-system');
        for ($i = date('Y'); $i <= date('Y') + 10; $i++) {
            $start_year[$i] = (string) $i;
        }

        // Start Month
        $start_month = array(
            '0-wpbs-default' => __('Current Month', 'wp-booking-system'),
            '1' => __('January', 'wp-booking-system'),
            '2' => __('February', 'wp-booking-system'),
            '3' => __('March', 'wp-booking-system'),
            '4' => __('April', 'wp-booking-system'),
            '5' => __('May', 'wp-booking-system'),
            '6' => __('June', 'wp-booking-system'),
            '7' => __('July', 'wp-booking-system'),
            '8' => __('August', 'wp-booking-system'),
            '9' => __('September', 'wp-booking-system'),
            '10' => __('October', 'wp-booking-system'),
            '11' => __('November', 'wp-booking-system'),
            '12' => __('December', 'wp-booking-system'),
        );

        // Languages
        $languages_dropdown = array();
        $settings = get_option('wpbs_settings', array());
        $languages = wpbs_get_languages();
        $active_languages = (!empty($settings['active_languages']) ? $settings['active_languages'] : array());

        $languages_dropdown['auto'] = __('Auto (let WP choose)', 'wp-booking-system');

        foreach ($active_languages as $code) {
            $languages_dropdown[esc_attr($code)] = (!empty($languages[$code]) ? $languages[$code] : '');
        }

        // Week Days
        $week_days = array(
            '1' => __('Monday', 'wp-booking-system'),
            '2' => __('Tuesday', 'wp-booking-system'),
            '3' => __('Wednesday', 'wp-booking-system'),
            '4' => __('Thursday', 'wp-booking-system'),
            '5' => __('Friday', 'wp-booking-system'),
            '6' => __('Saturday', 'wp-booking-system'),
            '7' => __('Sunday', 'wp-booking-system'),
        );

        $restriction_week_days = array(
            '0-wpbs-default' => '-',
            '1' => __('Monday', 'wp-booking-system'),
            '2' => __('Tuesday', 'wp-booking-system'),
            '3' => __('Wednesday', 'wp-booking-system'),
            '4' => __('Thursday', 'wp-booking-system'),
            '5' => __('Friday', 'wp-booking-system'),
            '6' => __('Saturday', 'wp-booking-system'),
            '7' => __('Sunday', 'wp-booking-system'),
        );

        return array(
            'id' => array(
                'label' => esc_html__('Calendar', 'wp-booking-system'),
                'type' => 'select',
                'option_category' => 'basic_option',
                'options' => $calendars_dropdown,
                'default' => '0-no-calendar',
            ),

            'form_id' => array(
                'label' => esc_html__('Form', 'wp-booking-system'),
                'type' => 'select',
                'option_category' => 'basic_option',
                'options' => $forms_dropdown,
                'default' => '0-no-form',
            ),

            'title' => array(
                'label' => esc_html__('Display Calendar Title', 'wp-booking-system'),
                'type' => 'select',
                'default' => 'yes',
                'option_category' => 'basic_option',
                'options' => array('yes' => __('Yes', 'wp-booking-system'), 'no' => __('No', 'wp-booking-system')),
            ),

            'legend' => array(
                'label' => esc_html__('Display Legend', 'wp-booking-system'),
                'type' => 'select',
                'default' => 'yes',
                'option_category' => 'basic_option',
                'options' => array('yes' => __('Yes', 'wp-booking-system'), 'no' => __('No', 'wp-booking-system')),
            ),

            'legend_position' => array(
                'label' => esc_html__('Legend Position', 'wp-booking-system'),
                'type' => 'select',
                'default' => 'side',
                'option_category' => 'basic_option',
                'options' => array('side' => __('Side', 'wp-booking-system'), 'top' => __('Top', 'wp-booking-system'), 'bottom' => __('Bottom', 'wp-booking-system')),
            ),

            'display' => array(
                'label' => esc_html__('Months to Display', 'wp-booking-system'),
                'type' => 'select',
                'default' => '1',
                'option_category' => 'basic_option',
                'options' => $months_to_display,
            ),

            'year' => array(
                'label' => esc_html__('Start Year', 'wp-booking-system'),
                'type' => 'select',
                'default' => '0-wpbs-default',
                'option_category' => 'basic_option',
                'options' => $start_year,
            ),

            'month' => array(
                'label' => esc_html__('Start Month', 'wp-booking-system'),
                'type' => 'select',
                'default' => '0-wpbs-default',
                'option_category' => 'basic_option',
                'options' => $start_month,
            ),

            'dropdown' => array(
                'label' => esc_html__('Display Dropdown', 'wp-booking-system'),
                'type' => 'select',
                'default' => 'yes',
                'option_category' => 'basic_option',
                'options' => array('yes' => __('Yes', 'wp-booking-system'), 'no' => __('No', 'wp-booking-system')),
            ),

            'language' => array(
                'label' => esc_html__('Language', 'wp-booking-system'),
                'type' => 'select',
                'default' => 'auto',
                'option_category' => 'basic_option',
                'options' => $languages_dropdown,
            ),

            'start' => array(
                'label' => esc_html__('Week Start Day', 'wp-booking-system'),
                'type' => 'select',
                'default' => '1',
                'option_category' => 'basic_option',
                'options' => $week_days,
            ),

            'dropdown' => array(
                'label' => esc_html__('Display Selection Dropdown', 'wp-booking-system'),
                'type' => 'select',
                'default' => 'yes',
                'option_category' => 'basic_option',
                'options' => array('yes' => __('Yes', 'wp-booking-system'), 'no' => __('No', 'wp-booking-system')),
                'description' => __("If set to yes, the calendar will display a month and year drop-down select field as a navigation alternative to the arrows.", 'wp-booking-system'),
            ),

            'jump' => array(
                'label' => esc_html__('Use Jump Switch', 'wp-booking-system'),
                'type' => 'select',
                'option_category' => 'basic_option',
                'default' => 'no',
                'options' => array('yes' => __('Yes', 'wp-booking-system'), 'no' => __('No', 'wp-booking-system')),
                'description' => __("If set to yes and if the calendar displays multiple months, when the user uses the arrows to navigate the calendar, the calendar will switch the number of months selected, rather than just one month.", 'wp-booking-system'),
            ),

            'history' => array(
                'label' => esc_html__('Show History', 'wp-booking-system'),
                'type' => 'select',
                'default' => '1',
                'option_category' => 'basic_option',
                'options' => array(
                    '1' => __('Display booking history', 'wp-booking-system'),
                    '2' => __('Replace booking history with the default legend item', 'wp-booking-system'),
                    '3' => __('Use the Booking History Color from the Settings', 'wp-booking-system'),
                ),
                'description' => __("This option lets you decide how past dates are being displayed for the user in the front-end.", 'wp-booking-system'),
            ),

            'tooltip' => array(
                'label' => esc_html__('Display Tooltips', 'wp-booking-system'),
                'type' => 'select',
                'default' => '1',
                'option_category' => 'basic_option',
                'options' => array(
                    '1' => __('No', 'wp-booking-system'),
                    '2' => __('Yes', 'wp-booking-system'),
                    '3' => __('Yes, with red indicator', 'wp-booking-system'),
                ),
            ),

            'highlighttoday' => array(
                'label' => esc_html__('Highlight Today', 'wp-booking-system'),
                'type' => 'select',
                'option_category' => 'basic_option',
                'default' => 'no',
                'options' => array('yes' => __('Yes', 'wp-booking-system'), 'no' => __('No', 'wp-booking-system')),
            ),

            'weeknumbers' => array(
                'label' => esc_html__('Show Week Numbers', 'wp-booking-system'),
                'type' => 'select',
                'option_category' => 'basic_option',
                'default' => 'no',
                'options' => array('yes' => __('Yes', 'wp-booking-system'), 'no' => __('No', 'wp-booking-system')),
                'description' => __("If set to yes, it will display at the beginning of each week the week's number counted from the beginning of the year.", 'wp-booking-system'),
            ),

            'show_prices' => array(
                'label' => esc_html__('Show Prices', 'wp-booking-system'),
                'type' => 'select',
                'option_category' => 'basic_option',
                'default' => 'no',
                'options' => array('yes' => __('Yes', 'wp-booking-system'), 'no' => __('No', 'wp-booking-system')),
                'description' => __("If set to yes, the price will be displayed below each calendar date.", 'wp-booking-system'),
            ),

            'form_position' => array(
                'label' => esc_html__('Form Position', 'wp-booking-system'),
                'type' => 'select',
                'default' => 'bottom',
                'option_category' => 'basic_option',
                'options' => array('bottom' => __('Bottom', 'wp-booking-system'), 'side' => __('Side', 'wp-booking-system')),
            ),

            'auto_pending' => array(
                'label' => esc_html__('Auto Accept Bookings', 'wp-booking-system'),
                'type' => 'select',
                'default' => 'yes',
                'option_category' => 'basic_option',
                'options' => array('yes' => __('Yes', 'wp-booking-system'), 'no' => __('No', 'wp-booking-system')),
                'description' => __("If set to yes, when a booking is made, the dates in the calendar will automatically be changed to the 'Booked' legend", 'wp-booking-system'),
            ),

            'selection_type' => array(
                'label' => esc_html__('Selection Type', 'wp-booking-system'),
                'type' => 'select',
                'default' => 'multiple',
                'option_category' => 'basic_option',
                'options' => array('multiple' => __('Date Range', 'wp-booking-system'), 'single' => __('Single Day', 'wp-booking-system')),
                'description' => __("Change the way the visitor selects dates in the calendar.", 'wp-booking-system'),
            ),

            'selection_style' => array(
                'label' => esc_html__('Selection Style', 'wp-booking-system'),
                'type' => 'select',
                'default' => 'split',
                'option_category' => 'basic_option',
                'options' => array('normal' => __('Normal', 'wp-booking-system'), 'split' => __('Split', 'wp-booking-system')),
                'description' => __("Change the way the selection of dates looks. Normal will highlight days entirely, while Split will make the first and last day of the selection appear as half days.", 'wp-booking-system'),
            ),

            'minimum_days' => array(
                'label' => esc_html__('Minimum Days', 'wp-booking-system'),
                'type' => 'text',
                'default' => '0',
                'option_category' => 'basic_option',
                'description' => __('The minimum number of days of a booking. If you are using the "Split" selection type, the number of nights will be counted instead.', 'wp-booking-system'),
            ),

            'maximum_days' => array(
                'label' => esc_html__('Maximum Days', 'wp-booking-system'),
                'type' => 'text',
                'default' => '0',
                'option_category' => 'basic_option',
                'description' => __('The maximum number of days of a booking. If you are using the "Split" selection type, the number of nights will be counted instead.', 'wp-booking-system'),
            ),

            'booking_start_day' => array(
                'label' => esc_html__('Booking Start Day', 'wp-booking-system'),
                'type' => 'select',
                'default' => '0-wpbs-default',
                'option_category' => 'basic_option',
                'options' => $restriction_week_days,
                'description' => __("Force the booking to start on a specific day.", 'wp-booking-system'),
            ),

            'booking_end_day' => array(
                'label' => esc_html__('Booking End Day', 'wp-booking-system'),
                'type' => 'select',
                'default' => '0-wpbs-default',
                'option_category' => 'basic_option',
                'options' => $restriction_week_days,
                'description' => __("Force the booking to end on a specific day.", 'wp-booking-system'),
            ),

            'show_date_selection' => array(
                'label' => esc_html__('Show Selected Dates', 'wp-booking-system'),
                'type' => 'select',
                'default' => 'no',
                'option_category' => 'basic_option',
                'options' => array('yes' => __('Yes', 'wp-booking-system'), 'no' => __('No', 'wp-booking-system')),
                'description' => __("If set to yes, the dates selected in the calendar will appear in the form as well. This just provides a visual confirmation of the dates selected.", 'wp-booking-system'),
            ),

        );
    }

    public function render($attrs, $content = null, $render_slug = null)
    {
        if (empty($this->props['id'])) {
            return '<div style="padding: 20px; background-color: #f1f1f1;">' . __('Please select a calendar to display.') . '</div>';
        }

        $props = array();

        foreach($this->props as $key => $prop){
            $props[$key] = str_replace('0-wpbs-default', '0', $prop);
        }

        // Execute the shortcode
        return WPBS_Shortcodes::single_calendar($props);
    }
}

new WPBS_Divi_Module_SingleCalendar;
