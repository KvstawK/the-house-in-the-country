<?php

/**
 * Plugin name: RC Slider
 * Plugin URI: https://www.example.com
 * Description: A slider for WordPress, created by Rentals Collective
 * Version: 1.0
 * Requires at least: 5.6
 * Author: Rentals Collective
 * Author URI: https://rentalscollective.com
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text domain: rc-slider
 * Domain path: /languages
 */

/*
RC Slider is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License, or
any later version.

RC Slider is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with RC Slider. If not, see https://www.gnu.org/licenses/gpl-2.0.html.
*/

if(!defined('ABSPATH')) {
    exit;
}

if(!class_exists('RC_Slider')) {
    class RC_Slider {
        function __construct() {

            $this->define_constants();

            $this->load_textdomain();

            require_once (RC_SLIDER_PATH . 'inc/functions.php');
	        require_once (RC_SLIDER_PATH . 'inc/rc-slider-uploader-field.php');

            require_once( RC_SLIDER_PATH . 'inc/class.rc-slider-cpt.php' );
            $RC_Slider_Post_Type = new RC_Slider_Post_Type();

            require_once (RC_SLIDER_PATH . 'inc/class.rc-slider-shortcode.php');
            $RC_Slider_Shortcode = new RC_Slider_Shortcode();

//            add_action('wp_enqueue_scripts', array($this, 'register_scripts'), 999);
//            add_action('admin_enqueue_scripts', array($this, 'register_admin_scripts'));
        }

        public function define_constants() {
            define( 'RC_SLIDER_PATH', plugin_dir_path(__FILE__));
            define( 'RC_SLIDER_URL', plugin_dir_url(__FILE__));
            define( 'RC_SLIDER_VERSION', '1.0.0' );
        }

        public static function activate() {
            update_option('rewrite_rules', '');
        }

        public static function deactivate() {
            flush_rewrite_rules();
            unregister_post_type('rc-slider');
        }

        public static function uninstall() {
            delete_option('rc_slider_options');

            $posts = get_posts(
                array(
                    'post_type' => 'rc-slider',
                    'number_posts' => -1,
                    'post_status' => 'any'
                )
            );

            foreach ($posts as $post) {
                wp_delete_post($post->ID, true);
            }
        }

        public function load_textdomain() {
            load_textdomain(
                'rc-slider',
                false,
                dirname(plugin_basename(__FILE__)) . '/languages/'
            );
        }

//        public function register_scripts() {
//            wp_register_script('rc-slider-script', RC_SLIDER_URL . 'assets/js/main.js', array(), RC_SLIDER_VERSION, true);
//
//            wp_register_style('rc-slider-styles', RC_SLIDER_URL . 'assets/styles/style.css', array(), RC_SLIDER_VERSION, 'all');
//        }

//        public function register_admin_scripts() {
//            global $typenow;
//            if($typenow == 'rc-slider') {
//                wp_enqueue_script('rc-slider-admin-scripts', RC_SLIDER_URL . 'assets/js/admin.js', array(), RC_SLIDER_VERSION, true);
//
//                wp_enqueue_style('rc-slider-admin-styles', RC_SLIDER_URL . 'assets/styles/admin.css', array(), RC_SLIDER_VERSION, 'all');
//            }
//        }
    }
}

if(class_exists('RC_Slider')) {
    register_activation_hook(__FILE__, array('RC_Slider', 'activate'));
    register_deactivation_hook(__FILE__, array('RC_Slider', 'deactivate'));
    register_uninstall_hook(__FILE__, array('RC_Slider', 'uninstall'));
    $rc_slider = new RC_Slider();
}