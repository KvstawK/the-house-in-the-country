<?php

if(!class_exists('LC_Rentals_Shortcodes')) {
    class LC_Rentals_Shortcodes {
        public function __construct() {
            add_shortcode('lc_rentals_facilities_shortcode', array($this, 'add_rentals_facilities_shortcode'));
            add_shortcode('lc_rentals_search_shortcode', array($this, 'add_rentals_search_shortcode'));
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
            require (LC_RENTALS_PATH . 'views/lc-rentals-facilities-shortcode.php');
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
            require (LC_RENTALS_PATH . 'views/lc-rentals-search-shortcode.php');
            return ob_get_clean();
        }
    }
}