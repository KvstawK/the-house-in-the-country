<?php

class WPBS_Customize
{

    public static function register($wp_customize)
    {

        $wp_customize->add_panel('wpbs_options', array(
            'priority' => 200,
            'capability' => 'edit_theme_options',
            'title' => 'WP Booking System',
            'description' => __('Customize the look of the WP Booking System calendar.', 'wp-booking-system'),
        ));

        $wp_customize->add_section('wpbs_calendar_options',
            array(
                'title' => 'Calendar',
                'priority' => 10,
                'panel' => 'wpbs_options',
                'capability' => 'edit_theme_options',
            )
        );

        $wp_customize->add_section('wpbs_form_options',
            array(
                'title' => 'Form',
                'priority' => 10,
                'panel' => 'wpbs_options',
                'capability' => 'edit_theme_options',
            )
        );

        $wp_customize->add_section('wpbs_presets_options',
            array(
                'title' => 'Load Presets',
                'priority' => 30,
                'panel' => 'wpbs_options',
                'capability' => 'edit_theme_options',
            )
        );

        // Date Padding Cells
        $wp_customize->add_setting('preset_themes',
            array(
                'default' => '0',
                'type' => 'theme_mod',
                'transport' => 'postMessage',
            )
        );

        $wp_customize->add_control(new WP_Customize_Control($wp_customize,
            'wpbs_preset_themes',
            array(
                'type' => 'select',
                'label' => __('Presets', 'wp-booking-system'),
                'settings' => 'preset_themes',
                'choices' => array(
                    '' => '',
                    time() . '-default' => __('Default Theme', 'wp-booking-system'),
                    time() . '-dark' => __('Dark Theme', 'wp-booking-system'),
                ),
                'section' => 'wpbs_presets_options',
            )
        ));

        $wp_customize->get_setting('preset_themes')->transport = 'postMessage';

        /**
         * Header
         */

        // Header Background Color
        $wp_customize->add_setting('header_background_color',
            array(
                'default' => '#f5f5f5',
                'type' => 'theme_mod',
                'transport' => 'postMessage',
            )
        );

        $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize,
            'wpbs_header_background_color',
            array(
                'label' => __('Header - Background Color', 'wp-booking-system'),
                'settings' => 'header_background_color',
                'section' => 'wpbs_calendar_options',
            )
        ));

        $wp_customize->get_setting('header_background_color')->transport = 'postMessage';

        // Header Text Color
        $wp_customize->add_setting('header_text_color',
            array(
                'default' => '#000000',
                'type' => 'theme_mod',
                'transport' => 'postMessage',
            )
        );

        $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize,
            'wpbs_header_text_color',
            array(
                'label' => __('Header - Text Color', 'wp-booking-system'),
                'settings' => 'header_text_color',
                'section' => 'wpbs_calendar_options',
            )
        ));

        $wp_customize->get_setting('header_text_color')->transport = 'postMessage';

        // Header Arrow Background Color
        $wp_customize->add_setting('header_arrow_background_color',
            array(
                'default' => '#bdc3c7',
                'type' => 'theme_mod',
                'transport' => 'postMessage',
            )
        );

        $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize,
            'wpbs_header_arrow_background_color',
            array(
                'label' => __('Header - Arrow Background Color', 'wp-booking-system'),
                'settings' => 'header_arrow_background_color',
                'section' => 'wpbs_calendar_options',
            )
        ));

        $wp_customize->get_setting('header_arrow_background_color')->transport = 'postMessage';

        // Header Arrow Color
        $wp_customize->add_setting('header_arrow_color',
            array(
                'default' => '#fff',
                'type' => 'theme_mod',
                'transport' => 'postMessage',
            )
        );

        $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize,
            'wpbs_header_arrow_color',
            array(
                'label' => __('Header - Arrow Color', 'wp-booking-system'),
                'settings' => 'header_arrow_color',
                'section' => 'wpbs_calendar_options',
            )
        ));

        $wp_customize->get_setting('header_arrow_color')->transport = 'postMessage';

        // Dropdown Background Color
        $wp_customize->add_setting('header_dropdown_background_color',
            array(
                'default' => '#fff',
                'type' => 'theme_mod',
                'transport' => 'postMessage',
            )
        );

        $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize,
            'wpbs_header_dropdown_background_color',
            array(
                'label' => __('Header - Dropdown Background Color', 'wp-booking-system'),
                'settings' => 'header_dropdown_background_color',
                'section' => 'wpbs_calendar_options',
            )
        ));

        $wp_customize->get_setting('header_dropdown_background_color')->transport = 'postMessage';

        // Dropdown Text Color
        $wp_customize->add_setting('header_dropdown_color',
            array(
                'default' => '#000',
                'type' => 'theme_mod',
                'transport' => 'postMessage',
            )
        );

        $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize,
            'wpbs_header_dropdown_color',
            array(
                'label' => __('Header - Dropdown Text Color', 'wp-booking-system'),
                'settings' => 'header_dropdown_color',
                'section' => 'wpbs_calendar_options',
            )
        ));

        $wp_customize->get_setting('header_dropdown_color')->transport = 'postMessage';

        // Dropdown Text Color
        $wp_customize->add_setting('header_dropdown_border_color',
            array(
                'default' => '#bdc3c7',
                'type' => 'theme_mod',
                'transport' => 'postMessage',
            )
        );

        $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize,
            'wpbs_header_dropdown_border_color',
            array(
                'label' => __('Header - Dropdown Border Color', 'wp-booking-system'),
                'settings' => 'header_dropdown_border_color',
                'section' => 'wpbs_calendar_options',
            )
        ));

        $wp_customize->get_setting('header_dropdown_border_color')->transport = 'postMessage';

        // Calendar Background Color
        $wp_customize->add_setting('calendar_background_color',
            array(
                'default' => '#fff',
                'type' => 'theme_mod',
                'transport' => 'postMessage',
            )
        );

        $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize,
            'wpbs_calendar_background_color',
            array(
                'label' => __('Calendar - Background Color', 'wp-booking-system'),
                'settings' => 'calendar_background_color',
                'section' => 'wpbs_calendar_options',
            )
        ));

        $wp_customize->get_setting('calendar_background_color')->transport = 'postMessage';

        // Calendar Border Color
        $wp_customize->add_setting('calendar_text_color',
            array(
                'default' => '#000',
                'type' => 'theme_mod',
                'transport' => 'postMessage',
            )
        );

        $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize,
            'wpbs_calendar_text_color',
            array(
                'label' => __('Calendar - Text Color', 'wp-booking-system'),
                'settings' => 'calendar_text_color',
                'section' => 'wpbs_calendar_options',
            )
        ));

        $wp_customize->get_setting('calendar_text_color')->transport = 'postMessage';

        // Calendar Border Color
        $wp_customize->add_setting('calendar_border_color',
            array(
                'default' => '#f1f1f1',
                'type' => 'theme_mod',
                'transport' => 'postMessage',
            )
        );

        $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize,
            'wpbs_calendar_border_color',
            array(
                'label' => __('Calendar - Border Color', 'wp-booking-system'),
                'settings' => 'calendar_border_color',
                'section' => 'wpbs_calendar_options',
            )
        ));

        $wp_customize->get_setting('calendar_border_color')->transport = 'postMessage';

        // Date Padding Cells
        $wp_customize->add_setting('dates_pad_cells',
            array(
                'default' => '#f7f7f7',
                'type' => 'theme_mod',
                'transport' => 'postMessage',
            )
        );

        $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize,
            'wpbs_dates_pad_cells',
            array(
                'label' => __('Dates - Other Month Dates Background', 'wp-booking-system'),
                'settings' => 'dates_pad_cells',
                'section' => 'wpbs_calendar_options',
            )
        ));

        $wp_customize->get_setting('dates_pad_cells')->transport = 'postMessage';

        // Date Week Numbers
        $wp_customize->add_setting('dates_weeknumbers',
            array(
                'default' => '#e8e8e8',
                'type' => 'theme_mod',
                'transport' => 'postMessage',
            )
        );

        $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize,
            'wpbs_dates_weeknumbers',
            array(
                'label' => __('Dates - Week Numbers Background', 'wp-booking-system'),
                'settings' => 'dates_weeknumbers',
                'section' => 'wpbs_calendar_options',
            )
        ));

        $wp_customize->get_setting('dates_weeknumbers')->transport = 'postMessage';

        // Date Border Radius
        $wp_customize->add_setting('dates_border_radius',
            array(
                'default' => '0',
                'type' => 'theme_mod',
                'transport' => 'postMessage',
            )
        );

        $wp_customize->add_control(new WP_Customize_Control($wp_customize,
            'wpbs_dates_border_radius',
            array(
                'type' => 'number',
                'label' => __('Dates - Border Radius', 'wp-booking-system'),
                'settings' => 'dates_border_radius',
                'section' => 'wpbs_calendar_options',
            )
        ));

        $wp_customize->get_setting('dates_border_radius')->transport = 'postMessage';

       

        // Form - Text Color
        $wp_customize->add_setting('form_text_color',
            array(
                'default' => '',
                'type' => 'theme_mod',
                'transport' => 'postMessage',
            )
        );

        $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize,
            'wpbs_form_text_color',
            array(
                'label' => __('Text Color', 'wp-booking-system'),
                'settings' => 'form_text_color',
                'section' => 'wpbs_form_options',
            )
        ));

        $wp_customize->get_setting('form_text_color')->transport = 'postMessage';

        // Field - Backgorund Color
        $wp_customize->add_setting('form_field_background_color',
            array(
                'default' => '#ffffff',
                'type' => 'theme_mod',
                'transport' => 'postMessage',
            )
        );

        $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize,
            'wpbs_form_field_background_color',
            array(
                'label' => __('Field - Background Color', 'wp-booking-system'),
                'settings' => 'form_field_background_color',
                'section' => 'wpbs_form_options',
            )
        ));

        $wp_customize->get_setting('form_field_background_color')->transport = 'postMessage';

        // Field - Border Color
        $wp_customize->add_setting('form_field_border_color',
            array(
                'default' => '#cccccc',
                'type' => 'theme_mod',
                'transport' => 'postMessage',
            )
        );

        $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize,
            'wpbs_form_field_border_color',
            array(
                'label' => __('Field - Border Color', 'wp-booking-system'),
                'settings' => 'form_field_border_color',
                'section' => 'wpbs_form_options',
            )
        ));

        $wp_customize->get_setting('form_field_border_color')->transport = 'postMessage';

        // Field - Text Color
        $wp_customize->add_setting('form_field_text_color',
            array(
                'default' => '#000000',
                'type' => 'theme_mod',
                'transport' => 'postMessage',
            )
        );

        $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize,
            'wpbs_form_field_text_color',
            array(
                'label' => __('Field - Text Color', 'wp-booking-system'),
                'settings' => 'form_field_text_color',
                'section' => 'wpbs_form_options',
            )
        ));

        $wp_customize->get_setting('form_field_text_color')->transport = 'postMessage';

        // Field - Placeholder Text Color
        $wp_customize->add_setting('form_field_placeholder_text_color',
            array(
                'default' => '#ccc',
                'type' => 'theme_mod',
                'transport' => 'refresh',
            )
        );

        $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize,
            'wpbs_form_field_placeholder_text_color',
            array(
                'label' => __('Field - Placeholder Text Color', 'wp-booking-system'),
                'settings' => 'form_field_placeholder_text_color',
                'section' => 'wpbs_form_options',
            )
        ));

        // Field - Description Text Color
        $wp_customize->add_setting('form_field_description_text_color',
            array(
                'default' => '#333333',
                'type' => 'theme_mod',
                'transport' => 'postMessage',
            )
        );

        $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize,
            'wpbs_form_field_description_text_color',
            array(
                'label' => __('Field - Description Text Color', 'wp-booking-system'),
                'settings' => 'form_field_description_text_color',
                'section' => 'wpbs_form_options',
            )
        ));

        $wp_customize->get_setting('form_field_description_text_color')->transport = 'postMessage';

        // Field - Border Radius
        $wp_customize->add_setting('form_field_border_radius',
            array(
                'default' => '2',
                'type' => 'theme_mod',
                'transport' => 'postMessage',
            )
        );

        $wp_customize->add_control(new WP_Customize_Control($wp_customize,
            'wpbs_form_field_border_radius',
            array(
                'type' => 'number',
                'label' => __('Field - Border Radius', 'wp-booking-system'),
                'settings' => 'form_field_border_radius',
                'section' => 'wpbs_form_options',
            )
        ));

        $wp_customize->get_setting('form_field_border_radius')->transport = 'postMessage';

        // Checkbox - Background Color
        $wp_customize->add_setting('form_checkbox_background_color',
            array(
                'default' => '#e2e2e2',
                'type' => 'theme_mod',
                'transport' => 'refresh',
            )
        );

        $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize,
            'wpbs_form_checkbox_background_color',
            array(
                'label' => __('Checkbox/Radio - Background Color', 'wp-booking-system'),
                'settings' => 'form_checkbox_background_color',
                'section' => 'wpbs_form_options',
            )
        ));


        // Checkbox - Hover Background Color
        $wp_customize->add_setting('form_checkbox_hover_background_color',
            array(
                'default' => '#aaa',
                'type' => 'theme_mod',
                'transport' => 'refresh',
            )
        );

        $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize,
            'wpbs_form_checkbox_hover_background_color',
            array(
                'label' => __('Checkbox/Radio - Hover Background Color', 'wp-booking-system'),
                'settings' => 'form_checkbox_hover_background_color',
                'section' => 'wpbs_form_options',
            )
        ));


        // Button - Background Color
        $wp_customize->add_setting('form_button_background_color',
            array(
                'default' => '#aaaaaa',
                'type' => 'theme_mod',
                'transport' => 'postMessage',
            )
        );

        $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize,
            'wpbs_form_button_background_color',
            array(
                'label' => __('Button - Background Color', 'wp-booking-system'),
                'settings' => 'form_button_background_color',
                'section' => 'wpbs_form_options',
            )
        ));

        $wp_customize->get_setting('form_button_background_color')->transport = 'postMessage';

        // Button - Text Color
        $wp_customize->add_setting('form_button_text_color',
            array(
                'default' => '#ffffff',
                'type' => 'theme_mod',
                'transport' => 'postMessage',
            )
        );

        $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize,
            'wpbs_form_button_text_color',
            array(
                'label' => __('Button - Text Color', 'wp-booking-system'),
                'settings' => 'form_button_text_color',
                'section' => 'wpbs_form_options',
            )
        ));

        $wp_customize->get_setting('form_button_text_color')->transport = 'postMessage';

        // Button Hover - Background Color
        $wp_customize->add_setting('form_button_hover_background_color',
            array(
                'default' => '#7f7f7f',
                'type' => 'theme_mod',
                'transport' => 'refresh',
            )
        );

        $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize,
            'wpbs_form_button_hover_background_color',
            array(
                'label' => __('Button - Hover Background Color', 'wp-booking-system'),
                'settings' => 'form_button_hover_background_color',
                'section' => 'wpbs_form_options',
            )
        ));


        // Button Hover - Text Color
        $wp_customize->add_setting('form_button_hover_text_color',
            array(
                'default' => '#ffffff',
                'type' => 'theme_mod',
                'transport' => 'refresh',
            )
        );

        $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize,
            'wpbs_form_button_hover_text_color',
            array(
                'label' => __('Button - Hover Text Color', 'wp-booking-system'),
                'settings' => 'form_button_hover_text_color',
                'section' => 'wpbs_form_options',
            )
        ));

        // Button - Border Radius
        $wp_customize->add_setting('form_button_border_radius',
            array(
                'default' => '2',
                'type' => 'theme_mod',
                'transport' => 'postMessage',
            )
        );

        $wp_customize->add_control(new WP_Customize_Control($wp_customize,
            'wpbs_form_button_border_radius',
            array(
                'type' => 'number',
                'label' => __('Button - Border Radius', 'wp-booking-system'),
                'settings' => 'form_button_border_radius',
                'section' => 'wpbs_form_options',
            )
        ));

        $wp_customize->get_setting('form_button_border_radius')->transport = 'postMessage';

        //Form Error Message
        $wp_customize->add_setting('form_error_message_color',
            array(
                'default' => '#ff2300',
                'type' => 'theme_mod',
                'transport' => 'postMessage',
            )
        );

        $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize,
            'wpbs_form_error_message_color',
            array(
                'label' => __('Form - Error Message Color', 'wp-booking-system'),
                'settings' => 'form_error_message_color',
                'section' => 'wpbs_form_options',
            )
        ));

        $wp_customize->get_setting('form_error_message_color')->transport = 'postMessage';

        //Form Payment Description
        $wp_customize->add_setting('form_payment_description',
            array(
                'default' => '#f7f7f7',
                'type' => 'theme_mod',
                'transport' => 'postMessage',
            )
        );

        $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize,
            'wpbs_form_payment_description',
            array(
                'label' => __('Form - Payment Method Description Background', 'wp-booking-system'),
                'settings' => 'form_payment_description',
                'section' => 'wpbs_form_options',
            )
        ));

        $wp_customize->get_setting('form_payment_description')->transport = 'postMessage';

        //Table Heading Background
        $wp_customize->add_setting('form_table_heading_background',
            array(
                'default' => '#f7f7f7',
                'type' => 'theme_mod',
                'transport' => 'postMessage',
            )
        );

        $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize,
            'wpbs_form_table_heading_background',
            array(
                'label' => __('Pricing Table - Heading Background Color', 'wp-booking-system'),
                'settings' => 'form_table_heading_background',
                'section' => 'wpbs_form_options',
            )
        ));

        $wp_customize->get_setting('form_table_heading_background')->transport = 'postMessage';

         // Form - Max Width
        $wp_customize->add_setting('form_max_width',
            array(
                'default' => '500',
                'type' => 'theme_mod',
                'transport' => 'postMessage',
            )
        );

        $wp_customize->add_control(new WP_Customize_Control($wp_customize,
            'wpbs_form_max_width',
            array(
                'type' => 'number',
                'label' => __('Form - Maximum Width', 'wp-booking-system'),
                'settings' => 'form_max_width',
                'section' => 'wpbs_form_options',
            )
        ));

        $wp_customize->get_setting('form_max_width')->transport = 'postMessage';

    }

    public static function header_output()
    {
        echo '<style type="text/css">';
        {
            self::generate_css('.wpbs-container .wpbs-calendars .wpbs-calendar .wpbs-calendar-header', 'background-color', 'header_background_color');
            self::generate_css('.wpbs-container .wpbs-calendars .wpbs-calendar .wpbs-calendar-header .wpbs-calendar-header-navigation', 'color', 'header_text_color');
            self::generate_css('.wpbs-container .wpbs-calendars .wpbs-calendar .wpbs-calendar-header .wpbs-calendar-header-navigation .wpbs-next, .wpbs-container .wpbs-calendars .wpbs-calendar .wpbs-calendar-header .wpbs-calendar-header-navigation .wpbs-prev', 'background-color', 'header_arrow_background_color');
            self::generate_css('.wpbs-container .wpbs-calendars .wpbs-calendar .wpbs-calendar-header .wpbs-calendar-header-navigation .wpbs-prev .wpbs-arrow', 'border-color', 'header_arrow_color', 'transparent ', ' transparent transparent');
            self::generate_css('.wpbs-container .wpbs-calendars .wpbs-calendar .wpbs-calendar-header .wpbs-calendar-header-navigation .wpbs-next .wpbs-arrow', 'border-color', 'header_arrow_color', 'transparent transparent transparent ');
            self::generate_css('.wpbs-container .wpbs-calendars .wpbs-calendar .wpbs-calendar-header .wpbs-select-container select', 'background-color', 'header_dropdown_background_color');
            self::generate_css('.wpbs-container .wpbs-calendars .wpbs-calendar .wpbs-calendar-header .wpbs-select-container select', 'border-color', 'header_dropdown_border_color');
            self::generate_css('.wpbs-container .wpbs-calendars .wpbs-calendar .wpbs-calendar-header .wpbs-select-container select', 'color', 'header_dropdown_color');

            self::generate_css('.wpbs-container .wpbs-calendars .wpbs-calendar, .wpbs-legend', 'background-color', 'calendar_background_color');
            self::generate_css('.wpbs-container .wpbs-calendars .wpbs-calendar, .wpbs-legend', 'border-color', 'calendar_border_color');
            self::generate_css('.wpbs-container .wpbs-calendars .wpbs-calendar, .wpbs-legend', 'color', 'calendar_text_color');

            self::generate_css('.wpbs-container .wpbs-calendars .wpbs-calendar table tr td .wpbs-date.wpbs-gap', 'background-color', 'dates_pad_cells');
            self::generate_css('.wpbs-container .wpbs-calendars .wpbs-calendar table td .wpbs-week-number', 'background-color', 'dates_weeknumbers');
            self::generate_css('.wpbs-container .wpbs-calendars .wpbs-calendar table tr td .wpbs-date, .wpbs-container .wpbs-calendars .wpbs-calendar table td .wpbs-week-number', 'border-radius', 'dates_border_radius', '', 'px');

            self::generate_css('.wpbs-main-wrapper .wpbs-form-container, .wpbs-payment-confirmation-inner, .wpbs-payment-confirmation-inner h2, .wpbs-main-wrapper .wpbs-form-confirmation-message', 'color', 'form_text_color');
            self::generate_css('.wpbs-main-wrapper .wpbs-form-container .wpbs-form-field input[type=email], .wpbs-main-wrapper .wpbs-payment-confirmation input[type="text"], .wpbs-main-wrapper .wpbs-form-container .wpbs-form-field input[type=number], .wpbs-main-wrapper .wpbs-form-container .wpbs-form-field input[type=text], .wpbs-main-wrapper .wpbs-form-container .wpbs-form-field textarea, .wpbs-main-wrapper .wpbs-form-container .wpbs-form-field select, .wpbs-form-field-signature .wpbs-signature-pad canvas, .wpbs-main-wrapper .wpbs-form-container .wpbs-currency-toggle-wrapper a.wpbs-currency-toggle-button.wpbs-currency-toggle-button-active, .wpbs-main-wrapper .wpbs-form-container .wpbs-currency-toggle-wrapper .wpbs-currency-toggle-list', 'background-color', 'form_field_background_color', '', ' !important');
            self::generate_css('.wpbs-main-wrapper .wpbs-form-container .wpbs-form-field input[type=email], .wpbs-main-wrapper .wpbs-payment-confirmation input[type="text"], .wpbs-main-wrapper .wpbs-form-container .wpbs-form-field input[type=number], .wpbs-main-wrapper .wpbs-form-container .wpbs-form-field input[type=text], .wpbs-main-wrapper .wpbs-form-container .wpbs-form-field textarea, .wpbs-main-wrapper .wpbs-form-container .wpbs-form-field select, .wpbs-form-field-signature .wpbs-signature-pad canvas, .wpbs-main-wrapper .wpbs-form-container .wpbs-currency-toggle-wrapper a.wpbs-currency-toggle-button.wpbs-currency-toggle-button-active, .wpbs-main-wrapper .wpbs-form-container .wpbs-currency-toggle-wrapper .wpbs-currency-toggle-list', 'border-color', 'form_field_border_color', '', ' !important');
            
            self::generate_css('.wpbs-main-wrapper .wpbs-form-container .wpbs-form-field input[type=email], .wpbs-main-wrapper .wpbs-payment-confirmation input[type="text"], .wpbs-main-wrapper .wpbs-form-container .wpbs-form-field input[type=number], .wpbs-main-wrapper .wpbs-form-container .wpbs-form-field input[type=text], .wpbs-main-wrapper .wpbs-form-container .wpbs-form-field textarea, .wpbs-main-wrapper .wpbs-form-container .wpbs-form-field select, .wpbs-payment-method-description ', 'color', 'form_field_text_color', '', ' !important');
            self::generate_css('.wpbs-form-field-signature .wpbs-signature-pad a.wpbs-clear-signature svg path ', 'fill', 'form_field_text_color');
            self::generate_css('.wpbs-main-wrapper .wpbs-form-container .wpbs-form-field.wpbs-form-field-dropdown .wpbs-form-field-input:after, .wpbs-main-wrapper .wpbs-form-container .wpbs-form-field.wpbs-form-field-inventory .wpbs-form-field-input:after, .wpbs-main-wrapper .wpbs-form-container .wpbs-form-field.wpbs-form-field-product_dropdown .wpbs-form-field-input:after', 'border-color', 'form_field_text_color');

            self::generate_css('.wpbs-main-wrapper .wpbs-form-container .wpbs-form-field input[type=email], .wpbs-main-wrapper .wpbs-payment-confirmation input[type="text"], .wpbs-main-wrapper .wpbs-form-container .wpbs-form-field input[type=number], .wpbs-main-wrapper .wpbs-form-container .wpbs-form-field input[type=text], .wpbs-main-wrapper .wpbs-form-container .wpbs-form-field textarea, .wpbs-main-wrapper .wpbs-form-container .wpbs-form-field select, .wpbs-form-field-signature .wpbs-signature-pad canvas', 'border-radius', 'form_field_border_radius', '', 'px !important');
            self::generate_css('.wpbs-main-wrapper .wpbs-form-container .wpbs-form-field button.wpbs-coupon-code-button', 'border-top-right-radius', 'form_field_border_radius', '', 'px !important');
            self::generate_css('.wpbs-main-wrapper .wpbs-form-container .wpbs-form-field button.wpbs-coupon-code-button', 'border-bottom-right-radius', 'form_field_border_radius', '', 'px !important');

            self::generate_css('.iti--separate-dial-code .iti__selected-flag', 'border-top-left-radius', 'form_field_border_radius', '', 'px !important');
            self::generate_css('.iti--separate-dial-code .iti__selected-flag', 'border-bottom-left-radius', 'form_field_border_radius', '', 'px !important');


            self::generate_css('.wpbs-main-wrapper .wpbs-form-container .wpbs-form-field-description small', 'color', 'form_field_description_text_color', '', ' !important');

            self::generate_css('.wpbs-form-container ::-webkit-input-placeholder', 'color', 'form_field_placeholder_text_color');
            self::generate_css('.wpbs-form-container ::-moz-placeholder', 'color', 'form_field_placeholder_text_color');
            self::generate_css('.wpbs-form-container ::-ms-input-placeholder', 'color', 'form_field_placeholder_text_color');
            self::generate_css('.wpbs-form-container ::-moz-placeholder', 'color', 'form_field_placeholder_text_color');

            // Radio & Checkbox

            
            self::generate_css('.wpbs-main-wrapper .wpbs-form-container .wpbs-form-field.wpbs-form-field-checkbox .wpbs-form-field-input label span, .wpbs-main-wrapper .wpbs-form-container .wpbs-form-field.wpbs-form-field-consent .wpbs-form-field-input label span, .wpbs-main-wrapper .wpbs-form-container .wpbs-form-field.wpbs-form-field-payment_method .wpbs-form-field-input label span, .wpbs-main-wrapper .wpbs-form-container .wpbs-form-field.wpbs-form-field-product_checkbox .wpbs-form-field-input label span, .wpbs-main-wrapper .wpbs-form-container .wpbs-form-field.wpbs-form-field-product_radio .wpbs-form-field-input label span, .wpbs-main-wrapper .wpbs-form-container .wpbs-form-field.wpbs-form-field-radio .wpbs-form-field-input label span', 'background-color', 'form_checkbox_background_color', '', ' !important');
            
            self::generate_css('.wpbs-main-wrapper .wpbs-form-container .wpbs-form-field.wpbs-form-field-checkbox .wpbs-form-field-input label:hover input~span, .wpbs-main-wrapper .wpbs-form-container .wpbs-form-field.wpbs-form-field-consent .wpbs-form-field-input label:hover input~span, .wpbs-main-wrapper .wpbs-form-container .wpbs-form-field.wpbs-form-field-payment_method .wpbs-form-field-input label:hover input~span, .wpbs-main-wrapper .wpbs-form-container .wpbs-form-field.wpbs-form-field-product_checkbox .wpbs-form-field-input label:hover input~span, .wpbs-main-wrapper .wpbs-form-container .wpbs-form-field.wpbs-form-field-product_radio .wpbs-form-field-input label:hover input~span, .wpbs-main-wrapper .wpbs-form-container .wpbs-form-field.wpbs-form-field-radio .wpbs-form-field-input label:hover input~span', 'background-color', 'form_checkbox_hover_background_color', '', ' !important');

            

            // Buttons
            self::generate_css('.wpbs-main-wrapper .wpbs-form-container .wpbs-form-field button.wpbs-coupon-code-button, .wpbs-main-wrapper .wpbs-form-container .wpbs-form-field button[type="submit"], .wpbs-main-wrapper .wpbs-form-container .wpbs-form-field input[type="submit"], .wpbs-payment-confirmation-stripe-form #wpbs-stripe-card-button, .wpbs-payment-confirmation-square-form #wpbs-square-card-button, #wpbs-authorize-net-button-container #wpbs-authorize-net-submit, .wpbs-main-wrapper .wpbs-form-container .wpbs-form-field.wpbs-form-field-radio .wpbs-form-field-input label input:checked ~ span, .wpbs-main-wrapper .wpbs-form-container .wpbs-form-field.wpbs-form-field-payment_method .wpbs-form-field-input label input:checked ~ span, .wpbs-main-wrapper .wpbs-form-container .wpbs-form-field.wpbs-form-field-checkbox .wpbs-form-field-input label input:checked ~ span, .wpbs-main-wrapper .wpbs-form-container .wpbs-form-field.wpbs-form-field-consent .wpbs-form-field-input label input:checked ~ span, .wpbs-main-wrapper .wpbs-form-container .wpbs-form-field.wpbs-form-field-product_radio .wpbs-form-field-input label input:checked ~ span, .wpbs-main-wrapper .wpbs-form-container .wpbs-form-field.wpbs-form-field-product_checkbox .wpbs-form-field-input label input:checked ~ span, .wpbs_s-search-widget .wpbs_s-search-widget-form .wpbs_s-search-widget-field button.wpbs_s-search-widget-datepicker-submit, .wpbs_s-search-widget .wpbs_s-search-widget-form .wpbs_s-search-widget-field input[type="submit"], .wpbs_s-search-widget .wpbs_s-search-widget-results-wrap .wpbs_s-search-widget-result .wpbs_s-search-widget-result-button, .ui-datepicker.wpbs-datepicker td.ui-datepicker-current-day, .wpbs-payment-confirmation-redsys-form input[type="submit"]', 'background-color', 'form_button_background_color', '', ' !important');
            self::generate_css('.wpbs-main-wrapper #wpbs-edit-order, .wpbs-main-wrapper .wpbs-form-container .wpbs-currency-toggle-wrapper a.wpbs-currency-toggle-button', 'color', 'form_button_background_color', '', ' !important');
            self::generate_css('.wpbs-main-wrapper .wpbs-form-container .wpbs-currency-toggle-wrapper a.wpbs-currency-toggle-button:after', 'border-color', 'form_button_background_color', '', ' !important');

            self::generate_css('.wpbs-main-wrapper .wpbs-form-container .wpbs-form-field button.wpbs-coupon-code-button, .wpbs-main-wrapper .wpbs-form-container .wpbs-form-field button[type="submit"], .wpbs-main-wrapper .wpbs-form-container .wpbs-form-field input[type="submit"], .wpbs-payment-confirmation-stripe-form #wpbs-stripe-card-button, .wpbs-payment-confirmation-square-form #wpbs-square-card-button, #wpbs-authorize-net-button-container #wpbs-authorize-net-submit, .wpbs_s-search-widget .wpbs_s-search-widget-form .wpbs_s-search-widget-field button.wpbs_s-search-widget-datepicker-submit, .wpbs_s-search-widget .wpbs_s-search-widget-form .wpbs_s-search-widget-field input[type="submit"], .wpbs_s-search-widget .wpbs_s-search-widget-results-wrap .wpbs_s-search-widget-result .wpbs_s-search-widget-result-button, .ui-datepicker.wpbs-datepicker td.ui-datepicker-current-day a, .wpbs-payment-confirmation-redsys-form input[type="submit"]', 'color', 'form_button_text_color', '', ' !important');
            self::generate_css('.wpbs-main-wrapper .wpbs-form-container .wpbs-form-field.wpbs-form-field-checkbox .wpbs-form-field-input label span:after, .wpbs-main-wrapper .wpbs-form-container .wpbs-form-field.wpbs-form-field-consent .wpbs-form-field-input label span:after, .wpbs-main-wrapper .wpbs-form-container .wpbs-form-field.wpbs-form-field-product_checkbox .wpbs-form-field-input label span:after', 'border-color', 'form_button_text_color', '', ' !important');
            self::generate_css('.wpbs-main-wrapper .wpbs-form-container .wpbs-form-field.wpbs-form-field-payment_method .wpbs-form-field-input label input[type="radio"]:checked~span:after, .wpbs-main-wrapper .wpbs-form-container .wpbs-form-field.wpbs-form-field-product_radio .wpbs-form-field-input label input[type="radio"]:checked~span:after, .wpbs-main-wrapper .wpbs-form-container .wpbs-form-field.wpbs-form-field-radio .wpbs-form-field-input label input[type="radio"]:checked~span:after', 'background', 'form_button_text_color', '', ' !important');

            self::generate_css('.wpbs-main-wrapper .wpbs-form-container .wpbs-form-field button.wpbs-coupon-code-button:hover, .wpbs-main-wrapper .wpbs-form-container .wpbs-form-field button[type="submit"]:hover, .wpbs-main-wrapper .wpbs-form-container .wpbs-form-field input[type="submit"]:hover, .wpbs-payment-confirmation-stripe-form #wpbs-stripe-card-button:hover, .wpbs-payment-confirmation-square-form #wpbs-square-card-button:hover, #wpbs-authorize-net-button-container #wpbs-authorize-net-submit:hover, .wpbs-main-wrapper .wpbs-form-container .wpbs-currency-toggle-wrapper .wpbs-currency-toggle-list li a:hover, .wpbs_s-search-widget .wpbs_s-search-widget-form .wpbs_s-search-widget-field button.wpbs_s-search-widget-datepicker-submit:hover, .wpbs_s-search-widget .wpbs_s-search-widget-form .wpbs_s-search-widget-field input[type="submit"]:hover , .wpbs_s-search-widget .wpbs_s-search-widget-results-wrap .wpbs_s-search-widget-result .wpbs_s-search-widget-result-button:hover, .ui-datepicker.wpbs-datepicker td .ui-state-default.ui-state-hover, .wpbs-payment-confirmation-redsys-form input[type="submit"]:hover', 'background-color', 'form_button_hover_background_color', '', ' !important');
            self::generate_css('.wpbs-main-wrapper #wpbs-edit-order:hover, .wpbs-main-wrapper .wpbs-form-container .wpbs-currency-toggle-wrapper a.wpbs-currency-toggle-button:hover, .wpbs-main-wrapper .wpbs-form-container .wpbs-currency-toggle-wrapper a.wpbs-currency-toggle-button.wpbs-currency-toggle-button-active, .wpbs-main-wrapper .wpbs-form-container .wpbs-currency-toggle-wrapper .wpbs-currency-toggle-list li a.wpbs-currency-toggle-selected', 'color', 'form_button_hover_background_color', '', ' !important');
            self::generate_css('.wpbs-main-wrapper .wpbs-form-container .wpbs-currency-toggle-wrapper a.wpbs-currency-toggle-button:hover:after, .wpbs-main-wrapper .wpbs-form-container .wpbs-currency-toggle-wrapper a.wpbs-currency-toggle-button.wpbs-currency-toggle-button-active:after', 'border-color', 'form_button_hover_background_color', '', ' !important');

            self::generate_css('.wpbs-main-wrapper .wpbs-form-container .wpbs-form-field button.wpbs-coupon-code-button:hover, .wpbs-main-wrapper .wpbs-form-container .wpbs-form-field button[type="submit"]:hover, .wpbs-main-wrapper .wpbs-form-container .wpbs-form-field input[type="submit"]:hover, .wpbs-payment-confirmation-stripe-form #wpbs-stripe-card-button:hover, .wpbs-payment-confirmation-square-form #wpbs-square-card-button:hover, #wpbs-authorize-net-button-container #wpbs-authorize-net-submit:hover, .wpbs-main-wrapper .wpbs-form-container .wpbs-currency-toggle-wrapper .wpbs-currency-toggle-list li a:hover, .wpbs-main-wrapper .wpbs-form-container .wpbs-currency-toggle-wrapper .wpbs-currency-toggle-list li a.wpbs-currency-toggle-selected:hover, .wpbs_s-search-widget .wpbs_s-search-widget-form .wpbs_s-search-widget-field button.wpbs_s-search-widget-datepicker-submit:hover, .wpbs_s-search-widget .wpbs_s-search-widget-form .wpbs_s-search-widget-field input[type="submit"]:hover , .wpbs_s-search-widget .wpbs_s-search-widget-results-wrap .wpbs_s-search-widget-result .wpbs_s-search-widget-result-button:hover, .ui-datepicker.wpbs-datepicker td .ui-state-default.ui-state-hover,.wpbs-payment-confirmation-redsys-form input[type="submit"]:hover', 'color', 'form_button_hover_text_color', '', ' !important');
            
            self::generate_css('.wpbs-main-wrapper .wpbs-form-general-error, .wpbs-main-wrapper .wpbs-form-container .wpbs-form-field-error', 'color', 'form_error_message_color', '', ' !important');

            self::generate_css('.wpbs-main-wrapper .wpbs-form-container .wpbs-form-field.wpbs-form-field-payment_method .wpbs-payment-method-description', 'background-color', 'form_payment_description', '', ' !important');
            
            self::generate_css('.wpbs-main-wrapper .wpbs-form-container .wpbs-form-field button[type="submit"], .wpbs-main-wrapper .wpbs-form-container .wpbs-form-field input[type="submit"], .wpbs-payment-confirmation-stripe-form #wpbs-stripe-card-button, .wpbs-payment-confirmation-square-form #wpbs-square-card-button, #wpbs-authorize-net-button-container #wpbs-authorize-net-submit', 'border-radius', 'form_button_border_radius', '', 'px !important');
            
            self::generate_css('.wpbs-main-wrapper .wpbs-payment-confirmation, .wpbs-main-wrapper .wpbs-form-container', 'max-width', 'form_max_width', '', 'px !important');
            
            self::generate_css('.wpbs-main-wrapper table.wpbs-pricing-table thead th, .wpbs-main-wrapper table.wpbs-pricing-table tr.wpbs-line-item-subtotal td, .wpbs-main-wrapper table.wpbs-pricing-table tr.wpbs-line-item-total td', 'background-color', 'form_table_heading_background', '', '!important');
            
            self::generate_css('.wpbs-main-wrapper table.wpbs-pricing-table td, .wpbs-main-wrapper table.wpbs-pricing-table th, .wpbs-main-wrapper table.wpbs-pricing-table td:first-child, .wpbs-main-wrapper table.wpbs-pricing-table th:first-child', 'border-color', 'form_table_heading_background', '', '!important');



        }
        echo '</style>';
    }

    public static function live_preview()
    {
        wp_register_script('wpbs-customizer', WPBS_PLUGIN_DIR_URL . 'assets/js/script-customizer.js', array('jquery', 'customize-preview'), WPBS_VERSION);
        wp_enqueue_script('wpbs-customizer');
    }

    public static function generate_css($selector, $style, $mod_name, $prefix = '', $suffix = '')
    {
        $mod = get_theme_mod($mod_name);
        if (!empty($mod)) {
            echo sprintf(' %s { %s:%s; }',
                $selector,
                $style,
                $prefix . $mod . $suffix
            );
        }
    }
}

add_action('customize_register', array('WPBS_Customize', 'register'));
add_action('wp_head', array('WPBS_Customize', 'header_output'));
add_action('customize_preview_init', array('WPBS_Customize', 'live_preview'));
