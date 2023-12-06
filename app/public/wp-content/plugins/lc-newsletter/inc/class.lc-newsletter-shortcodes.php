<?php

if(!class_exists('LC_Newsletter_Shortcodes')) {
	class LC_Newsletter_Shortcodes {
		public function __construct() {
			add_shortcode('lc_newsletter_shortcode', array($this, 'add_newsletter_shortcode'));
		}

		public function add_newsletter_shortcode($atts = array(), $content = null, $tag = '') {
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
			require (LC_NEWSLETTER_PATH . 'views/lc-newsletter-shortcode.php');
			if ($_SERVER["REQUEST_METHOD"] == "POST") {
				subscribe();
			}
			html_form();
			return ob_get_clean();
		}
	}
}