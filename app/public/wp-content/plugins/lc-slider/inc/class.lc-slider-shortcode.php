<?php

if(!class_exists('LC_Slider_Shortcode')) {
    class LC_Slider_Shortcode {
        public function __construct() {
            add_shortcode('lc_slider', array($this, 'add_slider_shortcode'));
        }

        public function add_slider_shortcode($atts = array(), $content = null, $tag = '') {
            $atts = array_change_key_case((array) $atts, CASE_LOWER);

            extract(shortcode_atts(
                array(
                    'id' => '',
                    'orderby' => 'date'
                ),
                $atts,
                $tag
            ));

            if(!empty($id)) {
                $id = array_map('absint', explode(',', $id));
            }

            ob_start();
            require (LC_SLIDER_PATH . 'views/lc-slider_shortcode.php');
//            wp_enqueue_script('lc-slider-script');
//            wp_enqueue_style('lc-slider-styles');
            return ob_get_clean();
        }
    }
}