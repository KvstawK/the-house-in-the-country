<?php

if(!class_exists('RC_Rentals_Shortcodes')) {
    class RC_Rentals_Shortcodes {
        public function __construct() {
            add_shortcode('rc_rentals_facilities_shortcode', array($this, 'add_rentals_facilities_shortcode'));
            add_shortcode('rc_rentals_search_shortcode', array($this, 'add_rentals_search_shortcode'));
        }

        public function add_rentals_facilities_shortcode($atts = array(), $content = null, $tag = '') {
            $atts = array_change_key_case((array) $atts, CASE_LOWER);

            extract(shortcode_atts(
                array(
                    'id' => '',
                    'orderby' => 'DESC'
                ),
                $atts,
                $tag
            ));

            if(!empty($id)) {
                $id = array_map('absint', explode(',', $id));
            }

            ob_start();
            require (RC_RENTALS_PATH . 'views/rc-rentals-facilities-shortcode.php');
            return ob_get_clean();
        }

        public function add_rentals_search_shortcode($atts = array(), $content = null, $tag = '') {
            $atts = array_change_key_case((array) $atts, CASE_LOWER);

            extract(shortcode_atts(
                array(
                    'id' => '',
                    'orderby' => 'DESC'
                ),
                $atts,
                $tag
            ));

            if(!empty($id)) {
                $id = array_map('absint', explode(',', $id));
            }

            ob_start();
            require (RC_RENTALS_PATH . 'views/rc-rentals-search-shortcode.php');
            return ob_get_clean();
        }
    }
}