<?php

if (!defined('ABSPATH')) exit; // Exit if accessed directly

class WPBS_Bricks_Search_Widget extends \Bricks\Element
{
    // Element properties
    public $category     = 'wp-booking-system';
    public $name         = 'wpbs-search-widget';
    public $icon         = 'ti-calendar';
    public $css_selector = '.wpbs-bricks-search-widget-wrapper';
    public $scripts      = [];

    // Return localised element label
    public function get_label()
    {
        return esc_html__('Search Widget', 'wp-booking-system');
    }

    // Set builder control groups
    public function set_control_groups()
    {
        $this->control_groups['wpbs_search_widget_calendars'] = [
            'title' => esc_html__('Calendar', 'wp-booking-system'),
            'tab' => 'content',
        ];


        $this->control_groups['wpbs_search_widget_calendar_options'] = [
            'title' => esc_html__('Calendar Options', 'wp-booking-system'),
            'tab' => 'content',
        ];
    }

    // Set builder controls
    public function set_controls()
    {


        $calendars = wpbs_get_calendars(array('status' => 'active'));
        $calendarDropdown = array();
        foreach ($calendars as $calendar) {
            $calendarDropdown[$calendar->get('id')] = $calendar->get('name');
        }

        $this->controls['calendars_type'] = [
            'tab' => 'content',
            'group' => 'wpbs_search_widget_calendars',
            'label' => esc_html__('Search in', 'wp-booking-system-search'),
            'type' => 'select',
            'options' => array(
                'all' => __('All Calendars', 'wp-booking-system-search'),
                'selected' => __('Selected Calendars', 'wp-booking-system-search'),
            ),
            'default' => 'all',
        ];

        $this->controls['calendars'] = [
            'tab' => 'content',
            'group' => 'wpbs_search_widget_calendars',
            'label' => esc_html__('Calendars', 'wp-booking-system-search'),
            'type' => 'select',
            'options' => $calendarDropdown,
            'multiple' => true,
            'condition' => array('calendars_type' => 'selected'),
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
            'group' => 'wpbs_search_widget_calendar_options',
            'label' => esc_html__('Language', 'wp-booking-system-search'),
            'type' => 'select',
            'options' => $languagesDropdown,
            'default' => 'auto',
        ];

        $this->controls['title'] = [
            'tab' => 'content',
            'group' => 'wpbs_search_widget_calendar_options',
            'label' => esc_html__('Widget Title', 'wp-booking-system-search'),
            'type' => 'select',
            'options' => array(
                'yes' => __('Yes', 'wp-booking-system-search'),
                'no' => __('No', 'wp-booking-system-search'),
            ),
            'default' => 'yes',
        ];

        $this->controls['start_day'] = [
            'tab' => 'content',
            'group' => 'wpbs_search_widget_calendar_options',
            'label' => esc_html__('Week Start Day', 'wp-booking-system-search'),
            'type' => 'select',
            'options' => array(
                '1' => __('Monday', 'wp-booking-system-search'),
                '2' => __('Tuesday', 'wp-booking-system-search'),
                '3' => __('Wednesday', 'wp-booking-system-search'),
                '4' => __('Thursday', 'wp-booking-system-search'),
                '5' => __('Friday', 'wp-booking-system-search'),
                '6' => __('Saturday', 'wp-booking-system-search'),
                '7' => __('Sunday', 'wp-booking-system-search'),
            ),
            'default' => '1',
        ];

        $this->controls['mark_selection'] = [
            'tab' => 'content',
            'group' => 'wpbs_search_widget_calendar_options',
            'label' => esc_html__('Automatically Mark Selection', 'wp-booking-system-search'),
            'type' => 'select',
            'options' => array(
                'yes' => __('Yes', 'wp-booking-system-search'),
                'no' => __('No', 'wp-booking-system-search'),
            ),
            'default' => 'yes',
        ];

        $this->controls['selection_type'] = [
            'tab' => 'content',
            'group' => 'wpbs_search_widget_calendar_options',
            'label' => esc_html__('Selection Type', 'wp-booking-system-search'),
            'type' => 'select',
            'options' => array(
                'multiple' => __('Date Range', 'wp-booking-system-search'),
                'single' => __('Single Day', 'wp-booking-system-search'),
            ),
            'default' => 'multiple',
        ];

        $this->controls['minimum_stay'] = [
            'tab' => 'content',
            'group' => 'wpbs_search_widget_calendar_options',
            'label' => esc_html__('Minimum Stay', 'wp-booking-system-search'),
            'type' =>'number',
            'min' => 0,
            'default' => 0,
        ];

        $this->controls['featured_image'] = [
            'tab' => 'content',
            'group' => 'wpbs_search_widget_calendar_options',
            'label' => esc_html__('Show Featured Image', 'wp-booking-system-search'),
            'type' => 'select',
            'options' => array(
                'yes' => __('Yes', 'wp-booking-system-search'),
                'no' => __('No', 'wp-booking-system-search'),
            ),
            'default' => 'no',
        ];

        $this->controls['starting_price'] = [
            'tab' => 'content',
            'group' => 'wpbs_search_widget_calendar_options',
            'label' => esc_html__('Show Starting Price', 'wp-booking-system-search'),
            'type' => 'select',
            'options' => array(
                'yes' => __('Yes', 'wp-booking-system-search'),
                'no' => __('No', 'wp-booking-system-search'),
            ),
            'default' => 'no',
        ];

        $this->controls['show_results_on_load'] = [
            'tab' => 'content',
            'group' => 'wpbs_search_widget_calendar_options',
            'label' => esc_html__('Show Results on Load', 'wp-booking-system-search'),
            'type' => 'select',
            'options' => array(
                'yes' => __('Yes', 'wp-booking-system-search'),
                'no' => __('No', 'wp-booking-system-search'),
            ),
            'default' => 'no',
        ];

        $this->controls['results_layout'] = [
            'tab' => 'content',
            'group' => 'wpbs_search_widget_calendar_options',
            'label' => esc_html__('Results Layout', 'wp-booking-system-search'),
            'type' => 'select',
            'options' => array(
                'list' => __('List', 'wp-booking-system-search'),
                'grid' => __('Grid', 'wp-booking-system-search'),
            ),
            'default' => 'list',
        ];

        $this->controls['results_per_page'] = [
            'tab' => 'content',
            'group' => 'wpbs_search_widget_calendar_options',
            'label' => esc_html__('Results per Page', 'wp-booking-system-search'),
            'type' => 'number',
            'min' => 1,
            'default' => 10,
        ];

        $this->controls['redirect'] = [
            'tab' => 'content',
            'group' => 'wpbs_search_widget_calendar_options',
            'label' => esc_html__('Redirect', 'wp-booking-system-search'),
            'type' => 'text',
        ];
    }

    // Enqueue element styles and scripts
    public function enqueue_scripts()
    {
    }

    public function render()
    {
        // Set element attributes
        $root_classes[] = 'wpbs-bricks-search-widget-wrapper';

        // Add 'class' attribute to element root tag
        $this->set_attribute('_root', 'class', $root_classes);


        echo "<div {$this->render_attributes('_root')}>";

        if ($this->settings['calendars_type'] == 'all') {
            $this->settings['calendars'] = 'all';
        } else {
            $this->settings['calendars'] = implode(',', $this->settings['calendars']);

            if (empty($this->settings['calendars'])) {
                $this->settings['calendars'] = 'all';
            }
        }

        echo WPBS_S_Shortcodes::search_widget($this->settings);

        echo '</div>';
    }
}
