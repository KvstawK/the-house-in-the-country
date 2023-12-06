<?php

if(!class_exists('RC_Slider_Shortcode')) {
    class RC_Slider_Shortcode {
        public function __construct() {
            add_shortcode('rc_slider', array($this, 'add_slider_shortcode'));
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
            require (RC_SLIDER_PATH . 'views/rc-slider_shortcode.php');
//            wp_enqueue_script('rc-slider-script');
//            wp_enqueue_style('rc-slider-styles');
            return ob_get_clean();
        }
    }
}