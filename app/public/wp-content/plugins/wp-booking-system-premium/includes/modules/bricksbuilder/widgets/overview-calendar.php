<?php

if (!defined('ABSPATH')) exit; // Exit if accessed directly

class WPBS_Bricks_Overview_Calendar extends \Bricks\Element
{
    // Element properties
    public $category     = 'wp-booking-system';
    public $name         = 'wpbs-overview-calendar';
    public $icon         = 'ti-calendar';
    public $css_selector = '.wpbs-bricks-overview-calendar-wrapper';
    public $scripts      = [];

    // Return localised element label
    public function get_label()
    {
        return esc_html__('Overview Calendar', 'wp-booking-system');
    }

    // Set builder control groups
    public function set_control_groups()
    {
        $this->control_groups['wpbs_overview_calendars'] = [
            'title' => esc_html__('Calendars', 'wp-booking-system'),
            'tab' => 'content',
        ];


        $this->control_groups['wpbs_overview_calendars_options'] = [
            'title' => esc_html__('Calendar Options', 'wp-booking-system'),
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

        $this->controls['calendars_type'] = [
            'tab' => 'content',
            'group' => 'wpbs_overview_calendars',
            'label' => esc_html__('Display', 'wp-booking-system'),
            'type' => 'select',
            'options' => array(
                'all' => __('All Calendars', 'wp-booking-system'),
                'selected' => __('Selected Calendars', 'wp-booking-system'),
            ),
            'default' => 'all',
        ];

        $this->controls['calendars'] = [
            'tab' => 'content',
            'group' => 'wpbs_overview_calendars',
            'label' => esc_html__('Calendars', 'wp-booking-system'),
            'type' => 'select',
            'options' => $calendarDropdown,
            'multiple' => true,
            'condition' => array('calendars_type' => 'selected'),
        ];


        $this->controls['title'] = [
            'tab' => 'content',
            'group' => 'wpbs_overview_calendars_options_options',
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
            'group' => 'wpbs_overview_calendars_options',
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
            'group' => 'wpbs_overview_calendars_options',
            'label' => esc_html__('Legend Position', 'wp-booking-system'),
            'type' => 'select',
            'options' => array(
                'top' => __('Top', 'wp-booking-system'),
                'bottom' => __('Bottom', 'wp-booking-system'),
            ),
            'default' => 'top',
        ];

        $this->controls['start_year'] = [
            'tab' => 'content',
            'group' => 'wpbs_overview_calendars_options',
            'label' => esc_html__('Start Year', 'wp-booking-system'),
            'type' => 'select',
            'options' => ['current' => __('Current Year', 'wp-booking-system')] + array_combine(range(date('Y'), date('Y') + 10), range(date('Y'), date('Y') + 10)),
            'default' => 'current',
        ];

        $this->controls['start_month'] = [
            'tab' => 'content',
            'group' => 'wpbs_overview_calendars_options',
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
                12 => __('December', 'wp-booking-system'),
            ),
            'default' => 'current',
        ];

        $this->controls['history'] = [
            'tab' => 'content',
            'group' => 'wpbs_overview_calendars_options',
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
            'group' => 'wpbs_overview_calendars_options',
            'label' => esc_html__('Display Tooltips', 'wp-booking-system'),
            'type' => 'select',
            'options' => array(
                '1' => __('No', 'wp-booking-system'),
                '2' => __('Yes', 'wp-booking-system'),
                '3' => __('Yes, with red indicator', 'wp-booking-system'),
            ),
            'default' => '1',
        ];

        $this->controls['weeknumbers'] = [
            'tab' => 'content',
            'group' => 'wpbs_overview_calendars_options',
            'label' => esc_html__('Show Weekday Abbreviations', 'wp-booking-system'),
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
            'group' => 'wpbs_overview_calendars_options',
            'label' => esc_html__('Language', 'wp-booking-system'),
            'type' => 'select',
            'options' => $languagesDropdown,
            'default' => 'auto',
        ];
    }

    // Enqueue element styles and scripts
    public function enqueue_scripts()
    {
    }

    public function render()
    {
        // Set element attributes
        $root_classes[] = 'wpbs-bricks-overview-calendar-wrapper';

        // Add 'class' attribute to element root tag
        $this->set_attribute('_root', 'class', $root_classes);

        echo "<div {$this->render_attributes('_root')}>";

        if($this->settings['start_month'] == 'current') $this->settings['start_month'] = '0';
        if($this->settings['start_year'] == 'current') $this->settings['start_year'] = '0';

        if ($this->settings['calendars_type'] == 'all') {
            $this->settings['calendars'] = 'all';
        } else {
            $this->settings['calendars'] = implode(',', $this->settings['calendars']);

            if (empty($this->settings['calendars'])) {
                $this->settings['calendars'] = 'all';
            }
        }

        echo WPBS_Shortcodes::calendar_overview($this->settings);

        echo '</div>';
    }
}
