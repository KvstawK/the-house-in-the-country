<?php

if (!defined('ABSPATH')) exit; // Exit if accessed directly

class WPBS_Bricks_Single_Calendar extends \Bricks\Element
{
    // Element properties
    public $category     = 'wp-booking-system';
    public $name         = 'wpbs-single-calendar';
    public $icon         = 'ti-calendar';
    public $css_selector = '.wpbs-bricks-single-calendar-wrapper';
    public $scripts      = [];

    // Return localised element label
    public function get_label()
    {
        return esc_html__('Single Calendar', 'wp-booking-system');
    }

    // Set builder control groups
    public function set_control_groups()
    {
        $this->control_groups['wpbs_single_calendar'] = [
            'title' => esc_html__('Calendar', 'wp-booking-system'),
            'tab' => 'content',
        ];

        $this->control_groups['wpbs_single_form'] = [
            'title' => esc_html__('Form', 'wp-booking-system'),
            'tab' => 'content',
        ];

        $this->control_groups['wpbs_single_calendar_options'] = [
            'title' => esc_html__('Calendar Options', 'wp-booking-system'),
            'tab' => 'content',
        ];

        $this->control_groups['wpbs_single_form_options'] = [
            'title' => esc_html__('Form Options', 'wp-booking-system'),
            'tab' => 'content',
        ];
    }

    // Set builder controls
    public function set_controls()
    {



        $calendars = wpbs_get_calendars(array('status' => 'active'));
        $calendarDropdown = array('0' => '-');
        foreach ($calendars as $calendar) {
            $calendarDropdown[$calendar->get('id')] = $calendar->get('name');
        }

        $this->controls['calendar_id'] = [
            'tab' => 'content',
            'group' => 'wpbs_single_calendar',
            'label' => esc_html__('Calendar', 'wp-booking-system'),
            'type' => 'select',
            'options' => $calendarDropdown,
            'default' => '0'
        ];



        $forms = wpbs_get_forms(array('status' => 'active'));
        $formDropdown = array('0' => '-');
        foreach ($forms as $form) {
            $formDropdown[$form->get('id')] = $form->get('name');
        }


        $this->controls['form_id'] = [
            'tab' => 'content',
            'group' => 'wpbs_single_form',
            'label' => esc_html__('Form', 'wp-booking-system'),
            'type' => 'select',
            'options' => $formDropdown,
            'default' => '0'
        ];


        $this->controls['title'] = [
            'tab' => 'content',
            'group' => 'wpbs_single_calendar_options',
            'label' => esc_html__('Display Calendar Title', 'wp-booking-system'),
            'type' => 'select',
            'options' => array(
                'yes' => __('Yes', 'wp-booking-system'),
                'no' => __('No', 'wp-booking-system'),
            ),
            'default' => 'no'
        ];

        $this->controls['legend'] = [
            'tab' => 'content',
            'group' => 'wpbs_single_calendar_options',
            'label' => esc_html__('Display Legend', 'wp-booking-system'),
            'type' => 'select',
            'options' => array(
                'yes' => __('Yes', 'wp-booking-system'),
                'no' => __('No', 'wp-booking-system'),
            ),
            'default' => 'yes',
        ];

        $this->controls['legend_position'] = [
            'tab' => 'content',
            'group' => 'wpbs_single_calendar_options',
            'label' => esc_html__('Legend Position', 'wp-booking-system'),
            'type' => 'select',
            'options' => array(
                'side' => __('Side', 'wp-booking-system'),
                'top' => __('Top', 'wp-booking-system'),
                'bottom' => __('Bottom', 'wp-booking-system'),
            ),
            'default' => 'side',
        ];

        $this->controls['display'] = [
            'tab' => 'content',
            'group' => 'wpbs_single_calendar_options',
            'label' => esc_html__('Months to Display', 'wp-booking-system'),
            'type' => 'select',
            'options' => array_combine(range(1, 24), range(1, 24)),
            'default' => '1',
        ];

        $this->controls['year'] = [
            'tab' => 'content',
            'group' => 'wpbs_single_calendar_options',
            'label' => esc_html__('Start Year', 'wp-booking-system'),
            'type' => 'select',
            'options' => ['current' => __('Current Year', 'wp-booking-system')] + array_combine(range(date('Y'), date('Y') + 10), range(date('Y'), date('Y') + 10)),
            'default' => 'current',
        ];

        $this->controls['month'] = [
            'tab' => 'content',
            'group' => 'wpbs_single_calendar_options',
            'label' => esc_html__('Start Month', 'wp-booking-system'),
            'type' => 'select',
            'options' => array(
                'current' => __('Current Month', 'wp-booking-system'),
                1 => __('January', 'wp-booking-system'),
                2 => __('February', 'wp-booking-system'),
                3 => __('March', 'wp-booking-system'),
                4 => __('April', 'wp-booking-system'),
                5 => __('May', 'wp-booking-system'),
                6 => __('June', 'wp-booking-system'),
                7 => __('July', 'wp-booking-system'),
                8 => __('August', 'wp-booking-system'),
                9 => __('September', 'wp-booking-system'),
                10 => __('October', 'wp-booking-system'),
                11 => __('November', 'wp-booking-system'),
                12 => __('December', 'wp-booking-system')
            ),
            'default' => 'current',
        ];

        $this->controls['dropdown'] = [
            'tab' => 'content',
            'group' => 'wpbs_single_calendar_options',
            'label' => esc_html__('Display Dropdown', 'wp-booking-system'),
            'type' => 'select',
            'options' => array(
                'yes' => __('Yes', 'wp-booking-system'),
                'no' => __('No', 'wp-booking-system'),
            ),
            'default' => 'yes',
        ];

        $this->controls['start'] = [
            'tab' => 'content',
            'group' => 'wpbs_single_calendar_options',
            'label' => esc_html__('Week Start Day', 'wp-booking-system'),
            'type' => 'select',
            'options' => array(
                '1' => __('Monday', 'wp-booking-system'),
                '2' => __('Tuesday', 'wp-booking-system'),
                '3' => __('Wednesday', 'wp-booking-system'),
                '4' => __('Thursday', 'wp-booking-system'),
                '5' => __('Friday', 'wp-booking-system'),
                '6' => __('Saturday', 'wp-booking-system'),
                '7' => __('Sunday', 'wp-booking-system'),
            ),
            'default' => '1',
        ];

        $this->controls['history'] = [
            'tab' => 'content',
            'group' => 'wpbs_single_calendar_options',
            'label' => esc_html__('Show History', 'wp-booking-system'),
            'type' => 'select',
            'options' => array(
                '1' => __('Display booking history', 'wp-booking-system'),
                '2' => __('Replace booking history with the default legend item', 'wp-booking-system'),
                '3' => __('Use the Booking History Color from the Settings', 'wp-booking-system'),
            ),
            'default' => '1',
        ];

        $this->controls['tooltip'] = [
            'tab' => 'content',
            'group' => 'wpbs_single_calendar_options',
            'label' => esc_html__('Display Tooltips', 'wp-booking-system'),
            'type' => 'select',
            'options' => array(
                '1' => __('No', 'wp-booking-system'),
                '2' => __('Yes', 'wp-booking-system'),
                '3' => __('Yes, with red indicator', 'wp-booking-system'),
            ),
            'default' => '1',
        ];

        $this->controls['highlighttoday'] = [
            'tab' => 'content',
            'group' => 'wpbs_single_calendar_options',
            'label' => esc_html__('Highlight Today', 'wp-booking-system'),
            'type' => 'select',
            'options' => array(
                'no' => __('No', 'wp-booking-system'),
                'yes' => __('Yes', 'wp-booking-system'),
            ),
            'default' => 'no',
        ];

        $this->controls['weeknumbers'] = [
            'tab' => 'content',
            'group' => 'wpbs_single_calendar_options',
            'label' => esc_html__('Show Week Numbers', 'wp-booking-system'),
            'type' => 'select',
            'options' => array(
                'yes' => __('Yes', 'wp-booking-system'),
                'no' => __('No', 'wp-booking-system'),
            ),
            'default' => 'no',
        ];

        $this->controls['show_prices'] = [
            'tab' => 'content',
            'group' => 'wpbs_single_calendar_options',
            'label' => esc_html__('Show Prices', 'wp-booking-system'),
            'type' => 'select',
            'options' => array(
                'yes' => __('Yes', 'wp-booking-system'),
                'no' => __('No', 'wp-booking-system'),
            ),
            'default' => 'no',
        ];

        $settings = get_option('wpbs_settings');
        $languages = wpbs_get_languages();
        $languagesDropdown = array('auto' => 'Auto');

        if (!empty($settings['active_languages'])) {

            foreach ($settings['active_languages'] as $key => $code) {

                if (empty($languages[$code])) {
                    continue;
                }

                $languagesDropdown[$code] = $languages[$code];
            }
        }

        $this->controls['language'] = [
            'tab' => 'content',
            'group' => 'wpbs_single_calendar_options',
            'label' => esc_html__('Language', 'wp-booking-system'),
            'type' => 'select',
            'options' => $languagesDropdown,
            'default' => 'auto',
        ];


        $this->controls['form_position'] = [
            'tab' => 'content',
            'group' => 'wpbs_single_form_options',
            'label' => esc_html__('Form Position', 'wp-booking-system'),
            'type' => 'select',
            'options' => array(
                'bottom' => __('Bottom', 'wp-booking-system'),
                'side' => __('Side', 'wp-booking-system'),
            ),
            'default' => 'bottom',
        ];

        $this->controls['auto_pending'] = [
            'tab' => 'content',
            'group' => 'wpbs_single_form_options',
            'label' => esc_html__('Auto Accept Bookings', 'wp-booking-system'),
            'type' => 'select',
            'options' => array(
                'yes' => __('Yes', 'wp-booking-system'),
                'no' => __('No', 'wp-booking-system'),
            ),
            'default' => 'yes',
        ];

        $this->controls['selection_type'] = [
            'tab' => 'content',
            'group' => 'wpbs_single_form_options',
            'label' => esc_html__('Selection Type', 'wp-booking-system'),
            'type' => 'select',
            'options' => array(
                'multiple' => __('Date Range', 'wp-booking-system'),
                'single' => __('Single Day', 'wp-booking-system'),
            ),
            'default' => 'multiple',
        ];

        $this->controls['selection_style'] = [
            'tab' => 'content',
            'group' => 'wpbs_single_form_options',
            'label' => esc_html__('Selection Style', 'wp-booking-system'),
            'type' => 'select',
            'options' => array(
                'normal' => __('Normal', 'wp-booking-system'),
                'split' => __('Split', 'wp-booking-system'),
            ),
            'default' => 'split',
        ];

        $this->controls['minimum_days'] = [
            'tab' => 'content',
            'group' => 'wpbs_single_form_options',
            'label' => esc_html__('Minimum Days', 'wp-booking-system'),
            'type' => 'number',
            'min' => 0,
            'default' => 0,
        ];

        $this->controls['maximum_days'] = [
            'tab' => 'content',
            'group' => 'wpbs_single_form_options',
            'label' => esc_html__('Maximum Days', 'wp-booking-system'),
            'type' => 'number',
            'min' => 0,
            'default' => 0,
        ];

        $this->controls['booking_start_day'] = [
            'tab' => 'content',
            'group' => 'wpbs_single_form_options',
            'label' => esc_html__('Booking Start Day', 'wp-booking-system'),
            'type' => 'select',
            'options' => array(
                '1' => __('Monday', 'wp-booking-system'),
                '2' => __('Tuesday', 'wp-booking-system'),
                '3' => __('Wednesday', 'wp-booking-system'),
                '4' => __('Thursday', 'wp-booking-system'),
                '5' => __('Friday', 'wp-booking-system'),
                '6' => __('Saturday', 'wp-booking-system'),
                '7' => __('Sunday', 'wp-booking-system'),
            ),
            'default' => '',
        ];

        $this->controls['booking_end_day'] = [
            'tab' => 'content',
            'group' => 'wpbs_single_form_options',
            'label' => esc_html__('Booking End Day', 'wp-booking-system'),
            'type' => 'select',
            'options' => array(
                '1' => __('Monday', 'wp-booking-system'),
                '2' => __('Tuesday', 'wp-booking-system'),
                '3' => __('Wednesday', 'wp-booking-system'),
                '4' => __('Thursday', 'wp-booking-system'),
                '5' => __('Friday', 'wp-booking-system'),
                '6' => __('Saturday', 'wp-booking-system'),
                '7' => __('Sunday', 'wp-booking-system'),
            ),
            'default' => '',
        ];

        $this->controls['show_date_selection'] = [
            'tab' => 'content',
            'group' => 'wpbs_single_form_options',
            'label' => esc_html__('Show Date Selection', 'wp-booking-system'),
            'type' => 'select',
            'options' => array(
                'yes' => __('Yes', 'wp-booking-system'),
                'no' => __('No', 'wp-booking-system'),
            ),
            'default' => 'no',
        ];
    }

    // Enqueue element styles and scripts
    public function enqueue_scripts()
    {
    }

    public function render()
    {
        // Set element attributes
        $root_classes[] = 'wpbs-bricks-single-calendar-wrapper';

        // Add 'class' attribute to element root tag
        $this->set_attribute('_root', 'class', $root_classes);

        if($this->settings['month'] == 'current') $this->settings['month'] = '0';
        if($this->settings['year'] == 'current') $this->settings['year'] = '0';

        echo "<div {$this->render_attributes('_root')}>";
        if (empty($this->settings['calendar_id']) || $this->settings['calendar_id'] == '0') {
            echo __("Please select a calendar to display", 'wp-booking-system');
        } else {
            $this->settings['id'] = $this->settings['calendar_id'];

            echo WPBS_Shortcodes::single_calendar($this->settings);
        }
        echo '</div>';
    }
}
