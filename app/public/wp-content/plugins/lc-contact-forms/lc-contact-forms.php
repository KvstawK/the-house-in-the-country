<?php

/**
 * Plugin name: LC Contact Forms
 * Plugin URI: https://www.example.com
 * Description: A contact forms plugin for WordPress, created by Lodgings Collective
 * Version: 1.0
 * Requires at least: 5.6
 * Author: Lodgings Collective
 * Author URI: https://lodgingsscollective.com/
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text domain: lc-contact-forms
 * Domain path: /languages
 */

/*
LC Contact Forms is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License, or
any later version.

LC Contact Forms is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with LC Contact Forms. If not, see https://www.gnu.org/licenses/gpl-2.0.html.
*/

if (!defined('ABSPATH')) {
	exit; // Ensures the script is being run within WordPress
}

if (!class_exists('LC_Contact_Forms')) {
	class LC_Contact_Forms {
		function __construct() {
			$this->define_constants();
			$this->load_textdomain();
			require_once( LC_CONTACT_FORMS_PATH . 'inc/class.lc-contact-forms-shortcodes.php' );
			new LC_Contact_Forms_Shortcodes();
		}

		public function define_constants() {
			define('LC_CONTACT_FORMS_PATH', plugin_dir_path(__FILE__));
			define('LC_CONTACT_FORMS_URL', plugin_dir_url(__FILE__));
			define('LC_CONTACT_FORMS_VERSION', '1.0.0');
		}

		public static function activate() {
			// Perform any tasks needed upon plugin activation
		}

		public static function deactivate() {
			// Perform any tasks needed upon plugin deactivation
		}

		public function load_textdomain() {
			load_plugin_textdomain(
				'lc-contact-forms',
				false,
				dirname(plugin_basename(__FILE__)) . '/languages/'
			);
		}
	}
}

if (class_exists('LC_Contact_Forms')) {
	register_activation_hook(__FILE__, ['LC_Contact_Forms', 'activate']);
	register_deactivation_hook(__FILE__, ['LC_Contact_Forms', 'deactivate']);
	new LC_Contact_Forms();
}






