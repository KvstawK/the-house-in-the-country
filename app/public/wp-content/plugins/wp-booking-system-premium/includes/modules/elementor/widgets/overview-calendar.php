<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

/**
 * Elementor Overview Calendar Widget.
 *
 * Elementor widget that inserts an embbedable content into the page, from any given URL.
 *
 * @since 1.0.0
 */
class Elementor_WPBS_Overview_Calendar_Widget extends \Elementor\Widget_Base
{

    /**
     * Get widget name.
     *
     * Retrieve Overview Calendar widget name.
     *
     * @since 1.0.0
     * @access public
     * @return string Widget name.
     */
    public function get_name()
    {
        return 'Multiple Overview Calendar';
    }

    /**
     * Get widget title.
     *
     * Retrieve Overview Calendar widget title.
     *
     * @since 1.0.0
     * @access public
     * @return string Widget title.
     */
    public function get_title()
    {
        return esc_html__('WP Booking System - Overview Calendar', 'wp-booking-system');
    }

    /**
     * Get widget icon.
     *
     * Retrieve Overview Calendar widget icon.
     *
     * @since 1.0.0
     * @access public
     * @return string Widget icon.
     */
    public function get_icon()
    {
        return 'eicon-table-of-contents';
    }

    /**
     * Get custom help URL.
     *
     * Retrieve a URL where the user can get more information about the widget.
     *
     * @since 1.0.0
     * @access public
     * @return string Widget help URL.
     */
    public function get_custom_help_url()
    {
        return 'https://www.wpbookingsystem.com/documentation/inserting-a-calendar-with-elementor/';
    }

    /**
     * Get widget categories.
     *
     * Retrieve the list of categories the Overview Calendar widget belongs to.
     *
     * @since 1.0.0
     * @access public
     * @return array Widget categories.
     */
    public function get_categories()
    {
        return ['wp-booking-system'];
    }

    /**
     * Get widget keywords.
     *
     * Retrieve the list of keywords the Overview Calendar widget belongs to.
     *
     * @since 1.0.0
     * @access public
     * @return array Widget keywords.
     */
    public function get_keywords()
    {
        return ['Overview', 'Calendar', 'Multiple', 'WP Booking System', 'wpbookingsystem'];
    }

    /**
     * Register Overview Calendar widget controls.
     *
     * Add input fields to allow the user to customize the widget settings.
     *
     * @since 1.0.0
     * @access protected
     */
    protected function register_controls()
    {

        $this->start_controls_section(
            'calendar',
            [
                'label' => esc_html__('Calendars', 'wp-booking-system'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $calendars = wpbs_get_calendars(array('status' => 'active'));
        $calendarDropdown = array();
        foreach ($calendars as $calendar) {
            $calendarDropdown[$calendar->get('id')] = $calendar->get('name');
        }

        $this->add_control(
            'calendars_type',
            [
                'label' => esc_html__('Display', 'wp-booking-system'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => array(
                    'all' => __('All Calendars', 'wp-booking-system'),
                    'selected' => __('Selected Calendars', 'wp-booking-system'),
                ),
                'default' => 'all',
            ]
        );

        $this->add_control(
            'calendars',
            [
                'label' => esc_html__('Calendars', 'wp-booking-system'),
                'type' => \Elementor\Controls_Manager::SELECT2,
                'options' => $calendarDropdown,
                'multiple' => true,
                'condition' => array('calendars_type' => 'selected'),
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'calendar_options',
            [
                'label' => esc_html__('Calendar Options', 'wp-booking-system'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'legend',
            [
                'label' => esc_html__('Display Legend', 'wp-booking-system'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => array(
                    'yes' => __('Yes', 'wp-booking-system'),
                    'no' => __('No', 'wp-booking-system'),
                ),
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'legend_position',
            [
                'label' => esc_html__('Legend Position', 'wp-booking-system'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => array(
                    'top' => __('Top', 'wp-booking-system'),
                    'bottom' => __('Bottom', 'wp-booking-system'),
                ),
                'default' => 'top',
            ]
        );

        $this->add_control(
            'start_year',
            [
                'label' => esc_html__('Start Year', 'wp-booking-system'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => [0 => __('Current Year', 'wp-booking-system')] + array_combine(range(date('Y'), date('Y') + 10), range(date('Y'), date('Y') + 10)),
                'default' => '0',
            ]
        );

        $this->add_control(
            'start_month',
            [
                'label' => esc_html__('Start Month', 'wp-booking-system'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => array(
                    0 => __('Current Month', 'wp-booking-system'),
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
                'default' => '0',
            ]
        );

        $this->add_control(
            'history',
            [
                'label' => esc_html__('Show History', 'wp-booking-system'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => array(
                    '1' => __('Display booking history', 'wp-booking-system'),
                    '2' => __('Replace booking history with the default legend item', 'wp-booking-system'),
                    '3' => __('Use the Booking History Color from the Settings', 'wp-booking-system'),
                ),
                'default' => '1',
            ]
        );

        $this->add_control(
            'tooltip',
            [
                'label' => esc_html__('Display Tooltips', 'wp-booking-system'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => array(
                    '1' => __('No', 'wp-booking-system'),
                    '2' => __('Yes', 'wp-booking-system'),
                    '3' => __('Yes, with red indicator', 'wp-booking-system'),
                ),
                'default' => '1',
            ]
        );

        $this->add_control(
            'weeknumbers',
            [
                'label' => esc_html__('Show Weekday Abbreviations', 'wp-booking-system'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => array(
                    'yes' => __('Yes', 'wp-booking-system'),
                    'no' => __('No', 'wp-booking-system'),
                ),
                'default' => 'no',
            ]
        );

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

        $this->add_control(
            'language',
            [
                'label' => esc_html__('Language', 'wp-booking-system'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => $languagesDropdown,
                'default' => 'auto',
            ]
        );

        $this->end_controls_section();

    }

    /**
     * Render Overview Calendar widget output on the frontend.
     *
     * Written in PHP and used to generate the final HTML.
     *
     * @since 1.0.0
     * @access protected
     */
    protected function render()
    {

        $settings = $this->get_settings_for_display();

        if ($settings['calendars_type'] == 'all') {
            $settings['calendars'] = 'all';
        } else {
            $settings['calendars'] = implode(',', $settings['calendars']);

            if (empty($settings['calendars'])) {
                $settings['calendars'] = 'all';
            }

        }

        // Execute the shortcode
        echo WPBS_Shortcodes::calendar_overview($settings);

    }

}
