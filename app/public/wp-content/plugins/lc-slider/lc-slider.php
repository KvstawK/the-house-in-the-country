<?php

/**
 * Plugin name: LC Slider
 * Plugin URI: https://www.example.com
 * Description: A slider for WordPress, created by Lodgings Collective
 * Version: 1.0
 * Requires at least: 5.6
 * Author: Lodgings Collective
 * Author URI: https://lodgingscollective.com
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text domain: lc-slider
 * Domain path: /languages
 */

/*
LC Slider is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License, or
any later version.

LC Slider is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with LC Slider. If not, see https://www.gnu.org/licenses/gpl-2.0.html.
*/

if(!defined('ABSPATH')) {
    exit;
}

if(!class_exists('LC_Slider')) {
    class LC_Slider {
        function __construct() {

            $this->define_constants();

            $this->load_textdomain();

            require_once (LC_SLIDER_PATH . 'inc/functions.php');
	        require_once (LC_SLIDER_PATH . 'inc/lc-slider-uploader-field.php');

            require_once( LC_SLIDER_PATH . 'inc/class.lc-slider-cpt.php' );
            $Lc_Slider_Post_Type = new Lc_Slider_Post_Type();

            require_once (LC_SLIDER_PATH . 'inc/class.lc-slider-shortcode.php');
            $Lc_Slider_Shortcode = new Lc_Slider_Shortcode();

//            add_action('wp_enqueue_scripts', array($this, 'register_scripts'), 999);
//            add_action('admin_enqueue_scripts', array($this, 'register_admin_scripts'));
        }

        public function define_constants() {
            define( 'LC_SLIDER_PATH', plugin_dir_path(__FILE__));
            define( 'LC_SLIDER_URL', plugin_dir_url(__FILE__));
            define( 'LC_SLIDER_VERSION', '1.0.0' );
        }

        public static function activate() {
            update_option('rewrite_rules', '');
        }

        public static function deactivate() {
            flush_rewrite_rules();
            unregister_post_type('lc-slider');
        }

        public static function uninstall() {
            delete_option('lc_slider_options');

            $posts = get_posts(
                array(
                    'post_type' => 'lc-slider',
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
                'lc-slider',
                false,
                dirname(plugin_basename(__FILE__)) . '/languages/'
            );
        }

//        public function register_scripts() {
//            wp_register_script('lc-slider-script', LC_SLIDER_URL . 'assets/js/main.js', array(), lc_slider_VERSION, true);
//
//            wp_register_style('lc-slider-styles', LC_SLIDER_URL . 'assets/styles/style.css', array(), lc_slider_VERSION, 'all');
//        }

//        public function register_admin_scripts() {
//            global $typenow;
//            if($typenow == 'lc-slider') {
//                wp_enqueue_script('lc-slider-admin-scripts', LC_SLIDER_URL . 'assets/js/admin.js', array(), lc_slider_VERSION, true);
//
//                wp_enqueue_style('lc-slider-admin-styles', LC_SLIDER_URL . 'assets/styles/admin.css', array(), lc_slider_VERSION, 'all');
//            }
//        }
    }
}

if(class_exists('LC_Slider')) {
    register_activation_hook(__FILE__, array('LC_Slider', 'activate'));
    register_deactivation_hook(__FILE__, array('LC_Slider', 'deactivate'));
    register_uninstall_hook(__FILE__, array('LC_Slider', 'uninstall'));
    $lc_slider = new LC_Slider();
}