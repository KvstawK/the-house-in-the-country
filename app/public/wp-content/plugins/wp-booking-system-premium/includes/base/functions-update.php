<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * In version 5.7, the default Form Strings & Translations were created
 * in the plugin's Settings page. These can be overwritten from each 
 * form's Settings page.
 * 
 * When updating, we enable the overwrite option for all installs so 
 * users won't have to move their translations.
 * 
 */
function wpbs_update_action_5_7() {

	$update = get_option( 'wpbs_update_action_5_7' );
    
    if($update === false){

        $forms = wpbs_get_forms();

        foreach($forms as $form){
            wpbs_update_form_meta($form->get('id'), 'overwrite_strings_and_translations', 'on');
        }

        add_option( 'wpbs_update_action_5_7', true );

    }

}
add_action( 'wpbs_update_check', 'wpbs_update_action_5_7' );

/**
 * In version 5.8, the Customizer was added. We need to move some settings 
 * from the setings page to the Customizer Settings.
 * 
 */
function wpbs_update_action_5_8() {
    
	$update = get_option( 'wpbs_update_action_5_8' );
    
    if($update === false){

        $plugin_settings = get_option('wpbs_settings', array());

        set_theme_mod('form_button_background_color', (isset($plugin_settings['button_background_color']) ? $plugin_settings['button_background_color'] : '') );
        set_theme_mod('form_button_text_color', (isset($plugin_settings['button_text_color']) ? $plugin_settings['button_text_color'] : '') );
        set_theme_mod('form_button_hover_background_color', (isset($plugin_settings['button_background_hover_color']) ? $plugin_settings['button_background_hover_color'] : '') );
        set_theme_mod('form_button_hover_text_color', (isset($plugin_settings['button_text_hover_color']) ? $plugin_settings['button_text_hover_color'] : '') );

        add_option( 'wpbs_update_action_5_8', true );

    }

}
add_action( 'wpbs_update_check', 'wpbs_update_action_5_8' );