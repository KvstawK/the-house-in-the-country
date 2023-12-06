<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

/**
 * Elementor Single Calendar Widget.
 *
 * Elementor widget that inserts an embbedable content into the page, from any given URL.
 *
 * @since 1.0.0
 */
class Elementor_WPBS_Single_Calendar_Widget extends \Elementor\Widget_Base
{

    /**
     * Get widget name.
     *
     * Retrieve Single Calendar widget name.
     *
     * @since 1.0.0
     * @access public
     * @return string Widget name.
     */
    public function get_name()
    {
        return 'Single Calendar';
    }

    /**
     * Get widget title.
     *
     * Retrieve Single Calendar widget title.
     *
     * @since 1.0.0
     * @access public
     * @return string Widget title.
     */
    public function get_title()
    {
        return esc_html__('WP Booking System - Single Calendar', 'wp-booking-system');
    }

    /**
     * Get widget icon.
     *
     * Retrieve Single Calendar widget icon.
     *
     * @since 1.0.0
     * @access public
     * @return string Widget icon.
     */
    public function get_icon()
    {
        return 'eicon-calendar';
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
     * Retrieve the list of categories the Single Calendar widget belongs to.
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
     * Retrieve the list of keywords the Single Calendar widget belongs to.
     *
     * @since 1.0.0
     * @access public
     * @return array Widget keywords.
     */
    public function get_keywords()
    {
        return ['Single', 'Calendar', 'Booking', 'WP Booking System', 'wpbookingsystem'];
    }

    /**
     * Register Single Calendar widget controls.
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
                'label' => esc_html__('Calendar', 'wp-booking-system'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $calendars = wpbs_get_calendars(array('status' => 'active'));
        $calendarDropdown = array('0' => '-');
        foreach ($calendars as $calendar) {
            $calendarDropdown[$calendar->get('id')] = $calendar->get('name');
        }

        $this->add_control(
            'calendar_id',
            [
                'label' => esc_html__('Calendar', 'wp-booking-system'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => $calendarDropdown,
                'default' => '0',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'form',
            [
                'label' => esc_html__('Form', 'wp-booking-system'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $forms = wpbs_get_forms(array('status' => 'active'));
        $formDropdown = array('0' => '-');
        foreach ($forms as $form) {
            $formDropdown[$form->get('id')] = $form->get('name');
        }

        $this->add_control(
            'form_id',
            [
                'label' => esc_html__('Form', 'wp-booking-system'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => $formDropdown,
                'default' => '0',
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
            'title',
            [
                'label' => esc_html__('Display Calendar Title', 'wp-booking-system'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => array(
                    'yes' => __('Yes', 'wp-booking-system'),
                    'no' => __('No', 'wp-booking-system'),
                ),
                'default' => 'no',
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
                    'side' => __('Side', 'wp-booking-system'),
                    'top' => __('Top', 'wp-booking-system'),
                    'bottom' => __('Bottom', 'wp-booking-system'),
                ),
                'default' => 'side',
            ]
        );

        $this->add_control(
            'display',
            [
                'label' => esc_html__('Months to Display', 'wp-booking-system'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => array_combine(range(1, 24), range(1, 24)),
                'default' => '1',
            ]
        );

        $this->add_control(
            'year',
            [
                'label' => esc_html__('Start Year', 'wp-booking-system'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => [0 => __('Current Year', 'wp-booking-system')] + array_combine(range(date('Y'), date('Y') + 10), range(date('Y'), date('Y') + 10)),
                'default' => '0',
            ]
        );

        $this->add_control(
            'month',
            [
                'label' => esc_html__('Start Month', 'wp-booking-system'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => array(
                    0 => __( 'Current Month', 'wp-booking-system' ),
                    1 => __( 'January', 'wp-booking-system' ),
                    2 => __( 'February', 'wp-booking-system' ),
                    3 => __( 'March', 'wp-booking-system' ),
                    4 => __( 'April', 'wp-booking-system' ),
                    5 => __( 'May', 'wp-booking-system' ),
                    6 => __( 'June', 'wp-booking-system' ),
                    7 => __( 'July', 'wp-booking-system' ),
                    8 => __( 'August', 'wp-booking-system' ),
                    9 => __( 'September', 'wp-booking-system' ),
                    10 => __( 'October', 'wp-booking-system' ),
                    11 => __( 'November', 'wp-booking-system' ),
                    12 => __( 'December', 'wp-booking-system')
                ),
                'default' => '0',
            ]
        );

        $this->add_control(
            'dropdown',
            [
                'label' => esc_html__('Display Dropdown', 'wp-booking-system'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => array(
                    'yes' => __('Yes', 'wp-booking-system'),
                    'no' => __('No', 'wp-booking-system'),
                ),
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'start',
            [
                'label' => esc_html__('Week Start Day', 'wp-booking-system'),
                'type' => \Elementor\Controls_Manager::SELECT,
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
            'highlighttoday',
            [
                'label' => esc_html__('Highlight Today', 'wp-booking-system'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => array(
                    'no' => __('No', 'wp-booking-system'),
                    'yes' => __('Yes', 'wp-booking-system'),
                ),
                'default' => 'no',
            ]
        );

        $this->add_control(
            'weeknumbers',
            [
                'label' => esc_html__('Show Week Numbers', 'wp-booking-system'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => array(
                    'yes' => __('Yes', 'wp-booking-system'),
                    'no' => __('No', 'wp-booking-system'),
                ),
                'default' => 'no',
            ]
        );

        $this->add_control(
            'show_prices',
            [
                'label' => esc_html__('Show Prices', 'wp-booking-system'),
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

        $this->start_controls_section(
            'form_options',
            [
                'label' => esc_html__('Form Options', 'wp-booking-system'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'form_position',
            [
                'label' => esc_html__('Form Position', 'wp-booking-system'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => array(
                    'bottom' => __('Bottom', 'wp-booking-system'),
                    'side' => __('Side', 'wp-booking-system'),
                ),
                'default' => 'bottom',
            ]
        );

        $this->add_control(
            'auto_pending',
            [
                'label' => esc_html__('Auto Accept Bookings', 'wp-booking-system'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => array(
                    'yes' => __('Yes', 'wp-booking-system'),
                    'no' => __('No', 'wp-booking-system'),
                ),
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'selection_type',
            [
                'label' => esc_html__('Selection Type', 'wp-booking-system'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => array(
                    'multiple' => __('Date Range', 'wp-booking-system'),
                    'single' => __('Single Day', 'wp-booking-system'),
                ),
                'default' => 'multiple',
            ]
        );

        $this->add_control(
            'selection_style',
            [
                'label' => esc_html__('Selection Style', 'wp-booking-system'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => array(
                    'normal' => __('Normal', 'wp-booking-system'),
                    'split' => __('Split', 'wp-booking-system'),
                ),
                'default' => 'split',
            ]
        );

        $this->add_control(
            'minimum_days',
            [
                'label' => esc_html__('Minimum Days', 'wp-booking-system'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'min' => 0,
                'default' => 0,
            ]
        );

        $this->add_control(
            'maximum_days',
            [
                'label' => esc_html__('Maximum Days', 'wp-booking-system'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'min' => 0,
                'default' => 0,
            ]
        );

        $this->add_control(
            'booking_start_day',
            [
                'label' => esc_html__('Booking Start Day', 'wp-booking-system'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => array(
                    '0' => '-',
                    '1' => __('Monday', 'wp-booking-system'),
                    '2' => __('Tuesday', 'wp-booking-system'),
                    '3' => __('Wednesday', 'wp-booking-system'),
                    '4' => __('Thursday', 'wp-booking-system'),
                    '5' => __('Friday', 'wp-booking-system'),
                    '6' => __('Saturday', 'wp-booking-system'),
                    '7' => __('Sunday', 'wp-booking-system'),
                ),
                'default' => '0',
            ]
        );

        $this->add_control(
            'booking_end_day',
            [
                'label' => esc_html__('Booking End Day', 'wp-booking-system'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => array(
                    '0' => '-',
                    '1' => __('Monday', 'wp-booking-system'),
                    '2' => __('Tuesday', 'wp-booking-system'),
                    '3' => __('Wednesday', 'wp-booking-system'),
                    '4' => __('Thursday', 'wp-booking-system'),
                    '5' => __('Friday', 'wp-booking-system'),
                    '6' => __('Saturday', 'wp-booking-system'),
                    '7' => __('Sunday', 'wp-booking-system'),
                ),
                'default' => '0',
            ]
        );

        $this->add_control(
            'show_date_selection',
            [
                'label' => esc_html__('Show Date Selection', 'wp-booking-system'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => array(
                    'yes' => __('Yes', 'wp-booking-system'),
                    'no' => __('No', 'wp-booking-system'),
                ),
                'default' => 'no',
            ]
        );

        $this->end_controls_section();

    }

    /**
     * Render Single Calendar widget output on the frontend.
     *
     * Written in PHP and used to generate the final HTML.
     *
     * @since 1.0.0
     * @access protected
     */
    protected function render()
    {

        $settings = $this->get_settings_for_display();

        if (empty($settings['calendar_id']) || $settings['calendar_id'] == '0') {
            echo __("Please select a calendar to display", 'wp-booking-system');
            return;
        }

        $settings['id'] = $settings['calendar_id'];

        echo WPBS_Shortcodes::single_calendar($settings);

    }

}
