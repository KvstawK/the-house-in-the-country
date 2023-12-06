<?php

if(!class_exists('LC_Contact_Forms_Shortcodes')) {
	class LC_Contact_Forms_Shortcodes {
		public function __construct() {
			add_shortcode('lc_contact_form', array($this, 'add_lc_contact_form_shortcode'));
			add_shortcode('lc_list_property_form', array($this, 'add_list_property_form_shortcode'));
		}

		public function add_lc_contact_form_shortcode() {
			ob_start();
			require (LC_CONTACT_FORMS_PATH . 'views/lc-contact-form-shortcode.php');
			return ob_get_clean();
		}

		public function add_list_property_form_shortcode() {
			ob_start();
			require (LC_CONTACT_FORMS_PATH . 'views/lc-list-property-form-shortcode.php');
			return ob_get_clean();
		}
	}
}
