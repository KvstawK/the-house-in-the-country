<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

/**
 * Elementor Search Widget.
 *
 * Elementor widget that inserts an embbedable content into the page, from any given URL.
 *
 * @since 1.0.0
 */
class Elementor_WPBS_Search_Widget extends \Elementor\Widget_Base
{

    /**
     * Get widget name.
     *
     * Retrieve Search widget name.
     *
     * @since 1.0.0
     * @access public
     * @return string Widget name.
     */
    public function get_name()
    {
        return 'Search';
    }

    /**
     * Get widget title.
     *
     * Retrieve Search widget title.
     *
     * @since 1.0.0
     * @access public
     * @return string Widget title.
     */
    public function get_title()
    {
        return esc_html__('WP Booking System - Search Widget', 'wp-booking-system-search');
    }

    /**
     * Get widget icon.
     *
     * Retrieve Search widget icon.
     *
     * @since 1.0.0
     * @access public
     * @return string Widget icon.
     */
    public function get_icon()
    {
        return 'eicon-search';
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
     * Retrieve the list of categories the Search widget belongs to.
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
     * Retrieve the list of keywords the Search widget belongs to.
     *
     * @since 1.0.0
     * @access public
     * @return array Widget keywords.
     */
    public function get_keywords()
    {
        return ['Search', 'Calendar', 'Widget', 'WP Booking System', 'wpbookingsystem'];
    }

    /**
     * Register Search widget controls.
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
                'label' => esc_html__('Calendars', 'wp-booking-system-search'),
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
                'label' => esc_html__('Search in', 'wp-booking-system-search'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => array(
                    'all' => __('All Calendars', 'wp-booking-system-search'),
                    'selected' => __('Selected Calendars', 'wp-booking-system-search'),
                ),
                'default' => 'all',
            ]
        );

        $this->add_control(
            'calendars',
            [
                'label' => esc_html__('Calendars', 'wp-booking-system-search'),
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
                'label' => esc_html__('Widget Options', 'wp-booking-system-search'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
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
                'label' => esc_html__('Language', 'wp-booking-system-search'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => $languagesDropdown,
                'default' => 'auto',
            ]
        );

        $this->add_control(
            'title',
            [
                'label' => esc_html__('Widget Title', 'wp-booking-system-search'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => array(
                    'yes' => __('Yes', 'wp-booking-system-search'),
                    'no' => __('No', 'wp-booking-system-search'),
                ),
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'start_day',
            [
                'label' => esc_html__('Week Start Day', 'wp-booking-system-search'),
                'type' => \Elementor\Controls_Manager::SELECT,
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
            ]
        );

        $this->add_control(
            'mark_selection',
            [
                'label' => esc_html__('Automatically Mark Selection', 'wp-booking-system-search'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => array(
                    'yes' => __('Yes', 'wp-booking-system-search'),
                    'no' => __('No', 'wp-booking-system-search'),
                ),
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'selection_type',
            [
                'label' => esc_html__('Selection Type', 'wp-booking-system-search'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => array(
                    'multiple' => __('Date Range', 'wp-booking-system-search'),
                    'single' => __('Single Day', 'wp-booking-system-search'),
                ),
                'default' => 'multiple',
            ]
        );

        $this->add_control(
            'minimum_stay',
            [
                'label' => esc_html__('Minimum Stay', 'wp-booking-system-search'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'min' => 0,
                'default' => 0,
            ]
        );

        $this->add_control(
            'featured_image',
            [
                'label' => esc_html__('Show Featured Image', 'wp-booking-system-search'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => array(
                    'yes' => __('Yes', 'wp-booking-system-search'),
                    'no' => __('No', 'wp-booking-system-search'),
                ),
                'default' => 'no',
            ]
        );

        $this->add_control(
            'starting_price',
            [
                'label' => esc_html__('Show Starting Price', 'wp-booking-system-search'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => array(
                    'yes' => __('Yes', 'wp-booking-system-search'),
                    'no' => __('No', 'wp-booking-system-search'),
                ),
                'default' => 'no',
            ]
        );

        $this->add_control(
            'show_results_on_load',
            [
                'label' => esc_html__('Show Results on Load', 'wp-booking-system-search'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => array(
                    'yes' => __('Yes', 'wp-booking-system-search'),
                    'no' => __('No', 'wp-booking-system-search'),
                ),
                'default' => 'no',
            ]
        );

        $this->add_control(
            'results_layout',
            [
                'label' => esc_html__('Results Layout', 'wp-booking-system-search'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => array(
                    'list' => __('List', 'wp-booking-system-search'),
                    'grid' => __('Grid', 'wp-booking-system-search'),
                ),
                'default' => 'list',
            ]
        );

        $this->add_control(
            'results_per_page',
            [
                'label' => esc_html__('Results per Page', 'wp-booking-system-search'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'min' => 1,
                'default' => 10,
            ]
        );
        
        $this->add_control(
            'redirect',
            [
                'label' => esc_html__('Redirect', 'wp-booking-system-search'),
                'type' => \Elementor\Controls_Manager::TEXT,
            ]
        );
       
        $this->end_controls_section();

    }

    /**
     * Render Search widget output on the frontend.
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

        echo WPBS_S_Shortcodes::search_widget($settings);

    }

}
