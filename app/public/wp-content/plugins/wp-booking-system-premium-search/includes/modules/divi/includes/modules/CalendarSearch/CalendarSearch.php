<?php
class WPBS_S_Divi_Module_CalendarSearch extends ET_Builder_Module
{

    public $slug = 'wpbs_s_divi_calendar_search';
    public $vb_support = 'on';

    public function init()
    {
        $this->name = esc_html__('Calendar Search', 'wp-booking-system-search');
    }

    public function get_fields()
    {

      
        // Languages
        $languages_dropdown = array();
        $settings = get_option('wpbs_settings', array());
        $languages = wpbs_get_languages();
        $active_languages = (!empty($settings['active_languages']) ? $settings['active_languages'] : array());

        $languages_dropdown['auto'] = __('Auto (let WP choose)', 'wp-booking-system-search');

        foreach ($active_languages as $code) {
            $languages_dropdown[esc_attr($code)] = (!empty($languages[$code]) ? $languages[$code] : '');
        }

        // Week Days
        $week_days = array(
            '1' => __('Monday', 'wp-booking-system-search'),
            '2' => __('Tuesday', 'wp-booking-system-search'),
            '3' => __('Wednesday', 'wp-booking-system-search'),
            '4' => __('Thursday', 'wp-booking-system-search'),
            '5' => __('Friday', 'wp-booking-system-search'),
            '6' => __('Saturday', 'wp-booking-system-search'),
            '7' => __('Sunday', 'wp-booking-system-search'),
        );

        return array(
            

            'title' => array(
                'label' => esc_html__('Widget Title', 'wp-booking-system-search'),
                'type' => 'select',
                'default' => 'yes',
                'option_category' => 'basic_option',
                'options' => array('yes' => __('Yes', 'wp-booking-system-search'), 'no' => __('No', 'wp-booking-system-search')),
            ),

            'start_day' => array(
                'label' => esc_html__('Week Start Day', 'wp-booking-system-search'),
                'type' => 'select',
                'default' => '1',
                'option_category' => 'basic_option',
                'options' => $week_days,
            ),

            'mark_selection' => array(
                'label' => esc_html__('Automatically Mark Selection', 'wp-booking-system-search'),
                'type' => 'select',
                'default' => 'yes',
                'option_category' => 'basic_option',
                'options' => array('yes' => __('Yes', 'wp-booking-system-search'), 'no' => __('No', 'wp-booking-system-search')),
            ),

            'selection_type' => array(
                'label' => esc_html__('Selection Type', 'wp-booking-system-search'),
                'type' => 'select',
                'default' => 'multiple',
                'option_category' => 'basic_option',
                'options' => array('multiple' => __('Date Range', 'wp-booking-system-search'), 'single' => __('Single Day', 'wp-booking-system-search')),
            ),

            'minimum_stay' => array(
                'label' => esc_html__('Minimum Stay', 'wp-booking-system-search'),
                'type' => 'text',
                'default' => '0',
                'option_category' => 'basic_option'
            ),

            'language' => array(
                'label' => esc_html__('Language', 'wp-booking-system-search'),
                'type' => 'select',
                'default' => 'auto',
                'option_category' => 'basic_option',
                'options' => $languages_dropdown,
            ),

            'featured_image' => array(
                'label' => esc_html__('Show Featured Image', 'wp-booking-system-search'),
                'type' => 'select',
                'default' => 'no',
                'option_category' => 'basic_option',
                'options' => array('yes' => __('Yes', 'wp-booking-system-search'), 'no' => __('No', 'wp-booking-system-search')),
            ),

            'starting_price' => array(
                'label' => esc_html__('Show Starting Price', 'wp-booking-system-search'),
                'type' => 'select',
                'default' => 'no',
                'option_category' => 'basic_option',
                'options' => array('yes' => __('Yes', 'wp-booking-system-search'), 'no' => __('No', 'wp-booking-system-search')),
            ),

            'show_results_on_load' => array(
                'label' => esc_html__('Show Results on Load', 'wp-booking-system-search'),
                'type' => 'select',
                'default' => 'no',
                'option_category' => 'basic_option',
                'options' => array('yes' => __('Yes', 'wp-booking-system-search'), 'no' => __('No', 'wp-booking-system-search')),
            ),

            'results_layout' => array(
                'label' => esc_html__('Results Layout', 'wp-booking-system-search'),
                'type' => 'select',
                'default' => 'list',
                'option_category' => 'basic_option',
                'options' => array('list' => __('List', 'wp-booking-system-search'), 'grid' => __('Grid', 'wp-booking-system-search')),
            ),

            'results_per_page' => array(
                'label' => esc_html__('Results per Page', 'wp-booking-system-search'),
                'type' => 'text',
                'default' => '10',
                'option_category' => 'basic_option'
            ),

            'redirect' => array(
                'label' => esc_html__('Redirect', 'wp-booking-system-search'),
                'type' => 'text',
                'default' => '',
                'option_category' => 'basic_option'
            ),

        );
    }

    public function render($attrs, $content = null, $render_slug = null)
    {
        // Execute the shortcode
        return WPBS_S_Shortcodes::search_widget($this->props);
    }
}

new WPBS_S_Divi_Module_CalendarSearch;
