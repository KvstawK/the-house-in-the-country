<?php

/**
 * Plugin name: RC Rentals
 * Plugin URI: https://www.example.com
 * Description: A rental management plugin for WordPress, created by Rentals Collective
 * Version: 1.0
 * Requires at least: 5.6
 * Author: Rental Collective
 * Author URI: https://rentalscollective.com/
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text domain: rc-rentals
 * Domain path: /languages
 */

/*
RC Rentals is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License, or
any later version.

RC Rentals is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with RC Rentals. If not, see https://www.gnu.org/licenses/gpl-2.0.html.
*/

if(!defined('ABSPATH')) {
	exit;
}

if(!class_exists('RC_Rentals')) {
	class RC_Rentals {
		function __construct() {
			$this->define_constants();

			$this->load_textdomain();

			require_once (RC_RENTALS_PATH . 'inc/functions.php');

			require_once( RC_RENTALS_PATH . 'inc/class.rc-rentals-cpt.php' );
			$RC_Rentals_Post_Type = new RC_Rentals_Post_Type();

			require_once( RC_RENTALS_PATH . 'inc/class.rc-rentals-shortcodes.php' );
			$RC_Rentals_Shortcodes = new RC_Rentals_Shortcodes();

			require_once( RC_RENTALS_PATH . 'inc/class.rc-rentals-reviews.php' );
			$RC_Rentals_Reviews = new RC_Rentals_Reviews();
		}

		public function define_constants() {
			define( 'RC_RENTALS_PATH', plugin_dir_path(__FILE__));
			define( 'RC_RENTALS_URL', plugin_dir_url(__FILE__));
			define( 'RC_RENTALS_VERSION', '1.0.0' );
		}

		public static function activate() {
			update_option('rewrite_rules', '');
		}

		public static function deactivate() {
			flush_rewrite_rules();
			unregister_post_type('rc-rentals');
		}

		public static function uninstall() {
			delete_option('rc_rentals_options');

			$posts = get_posts(
				array(
					'post_type' => 'rc-rentals',
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
				'rc-rentals',
				false,
				dirname(plugin_basename(__FILE__)) . '/languages/'
			);
		}
	}
}

if(class_exists('RC_Rentals')) {
	register_activation_hook(__FILE__, array('RC_Rentals', 'activate'));
	register_deactivation_hook(__FILE__, array('RC_Rentals', 'deactivate'));
	register_uninstall_hook(__FILE__, array('RC_Rentals', 'uninstall'));
	$ea_rentals = new RC_Rentals();
}
