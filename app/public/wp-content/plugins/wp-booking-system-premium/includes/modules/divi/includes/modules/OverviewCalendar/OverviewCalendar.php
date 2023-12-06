<?php
class WPBS_Divi_Module_OverviewCalendar extends ET_Builder_Module
{

    public $slug = 'wpbs_divi_overview_calendar';
    public $vb_support = 'on';

    public function init()
    {
        $this->name = esc_html__('Overview Calendar', 'wp-booking-system');
    }

    public function get_fields()
    {

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

        return array(

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
                'default' => 'top',
                'option_category' => 'basic_option',
                'options' => array('top' => __('Top', 'wp-booking-system'), 'bottom' => __('Bottom', 'wp-booking-system')),
            ),

            'start_year' => array(
                'label' => esc_html__('Start Year', 'wp-booking-system'),
                'type' => 'select',
                'default' => '0',
                'option_category' => 'basic_option',
                'options' => $start_year,
            ),

            'start_month' => array(
                'label' => esc_html__('Start Month', 'wp-booking-system'),
                'type' => 'select',
                'default' => '0',
                'option_category' => 'basic_option',
                'options' => $start_month,
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

            'weeknumbers' => array(
                'label' => esc_html__('Show Day Abbreviations', 'wp-booking-system'),
                'type' => 'select',
                'option_category' => 'basic_option',
                'default' => 'no',
                'options' => array('yes' => __('Yes', 'wp-booking-system'), 'no' => __('No', 'wp-booking-system')),
                'description' => __("If set to yes, it will display at the beginning of each week the week's number counted from the beginning of the year.", 'wp-booking-system'),
            ),

            'language' => array(
                'label' => esc_html__('Language', 'wp-booking-system'),
                'type' => 'select',
                'default' => 'auto',
                'option_category' => 'basic_option',
                'options' => $languages_dropdown,
            ),

        );
    }

    public function render($attrs, $content = null, $render_slug = null)
    {

        $props = array();

        foreach($this->props as $key => $prop){
            $props[$key] = str_replace('0-wpbs-default', '0', $prop);
        }

        // Execute the shortcode
        return WPBS_Shortcodes::calendar_overview($props);
    }
}

new WPBS_Divi_Module_OverviewCalendar;
