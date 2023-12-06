<?php

/**
 * Plugin name: LC Rentals
 * Plugin URI: https://www.example.com
 * Description: A rental management plugin for WordPress, created by Lodgings Collective
 * Version: 1.0
 * Requires at least: 5.6
 * Author: Lodgings Collective
 * Author URI: https://lodgingscollective.com/
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text domain: lc-rentals
 * Domain path: /languages
 */

/*
LC Rentals is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License, or
any later version.

LC Rentals is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with LC Rentals. If not, see https://www.gnu.org/licenses/gpl-2.0.html.
*/

if(!defined('ABSPATH')) {
	exit;
}

if(!class_exists('LC_Rentals')) {
	class LC_Rentals {
		function __construct() {
			$this->define_constants();

			$this->load_textdomain();

			require_once (LC_RENTALS_PATH . 'inc/functions.php');

			require_once( LC_RENTALS_PATH . 'inc/class.lc-rentals-cpt.php' );
			$LC_Rentals_Post_Type = new LC_Rentals_Post_Type();

			require_once( LC_RENTALS_PATH . 'inc/class.lc-rentals-shortcodes.php' );
			$LC_Rentals_Shortcodes = new LC_Rentals_Shortcodes();

			require_once( LC_RENTALS_PATH . 'inc/class.lc-rentals-reviews.php' );
			$LC_Rentals_Reviews = new LC_Rentals_Reviews();
		}

		public function define_constants() {
			define( 'LC_RENTALS_PATH', plugin_dir_path(__FILE__));
			define( 'LC_RENTALS_URL', plugin_dir_url(__FILE__));
			define( 'LC_RENTALS_VERSION', '1.0.0' );
		}

		public static function activate() {
			update_option('rewrite_rules', '');
		}

		public static function deactivate() {
			flush_rewrite_rules();
			unregister_post_type('lc-rentals');
		}

		public static function uninstall() {
			delete_option('lc_rentals_options');

			$posts = get_posts(
				array(
					'post_type' => 'lc-rentals',
					'number_posts' => -1,
					'post_status' => 'any'
				)
			);

			foreach ($posts as $post) {
				wp_delete_post($post->ID, true);
			}
		}

		public function load_textdomain() {
			load_plugin_textdomain(
				'lc-rentals',
				false,
				dirname(plugin_basename(__FILE__)) . '/languages/'
			);
		}
	}
}

if(class_exists('LC_Rentals')) {
	register_activation_hook(__FILE__, array('LC_Rentals', 'activate'));
	register_deactivation_hook(__FILE__, array('LC_Rentals', 'deactivate'));
	register_uninstall_hook(__FILE__, array('LC_Rentals', 'uninstall'));
	$ea_rentals = new LC_Rentals();
}
