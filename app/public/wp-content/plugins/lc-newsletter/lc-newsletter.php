<?php

/**
 * Plugin name: LC Newsletter
 * Plugin URI: https://www.wordpress.org/lc-newsletter
 * Description: A newsletter plugin for WordPress, created by Lodgings Collective
 * Version: 1.0
 * Requires at least: 5.6
 * Author: Lodgings Collective
 * Author URI: https://www.lodgingscollective.com
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text domain: lc-newsletter
 * Domain path: /languages
 */

/*
LC Newsletter is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License, or
any later version.

LC Newsletter is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MELCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with LC Newsletter. If not, see https://www.gnu.org/licenses/gpl-2.0.html.
*/

if(!defined('ABSPATH')) {
	exit;
}

if(!class_exists('LC_Newsletter')) {
	class LC_Newsletter {
		function __construct() {

			$this->define_constants();
			$this->load_textdomain();

			require_once( LC_NEWSLETTER_PATH . 'inc/send-email-to-subscribers.php' );
			require_once( LC_NEWSLETTER_PATH . 'inc/create-unsubscribe-page-in-theme.php' );

			require_once( LC_NEWSLETTER_PATH . 'inc/class.lc-newsletter-cpt.php' );
			$LC_Newsletter_Post_Type = new LC_Newsletter_Post_Type();

			require_once( LC_NEWSLETTER_PATH . 'inc/class.lc-newsletter-shortcodes.php' );
			$LC_Newsletter_Shortcodes = new LC_Newsletter_Shortcodes();
		}

		public function define_constants() {
			define( 'LC_NEWSLETTER_PATH', plugin_dir_path(__FILE__));
			define( 'LC_NEWSLETTER_URL', plugin_dir_url(__FILE__));
			define( 'LC_NEWSLETTER_VERSION', '1.0.0' );
		}

		public static function activate() {
			if (function_exists('update_option')) {
				update_option('rewrite_rules', '');
			}
		}

		public static function deactivate() {
			if (function_exists('flush_rewrite_rules')) {
				flush_rewrite_rules();
			}

			if (function_exists('unregister_post_type')) {
				unregister_post_type('lc-newsletter');
			}

			$unsubscribe_page_path = get_stylesheet_directory() . '/page-lc-newsletter-unsubscribe.php';
			if (file_exists($unsubscribe_page_path) && function_exists('unlink')) {
				unlink($unsubscribe_page_path);
			}

			$unsubscribe_page = get_page_by_title( 'LC Newsletter Unsubscribe' );
			if ($unsubscribe_page && function_exists('wp_delete_post')) {
				wp_delete_post( $unsubscribe_page->ID, true );
			}
		}



		public static function uninstall() {
			$posts = get_posts(
				array(
					'post_type' => 'lc-newsletter',
					'number_posts' => -1,
					'post_status' => 'any'
				)
			);

			foreach ($posts as $post) {
				wp_delete_post($post->ID, true);
			}

			$theme_path = get_stylesheet_directory() . '/page-lc-newsletter-unsubscribe.php';
			if (file_exists($theme_path)) {
				unlink($theme_path);
			}

			$page = get_page_by_title('LC Newsletter Unsubscribe');
			if ($page) {
				wp_delete_post($page->ID, true);
			}
		}


		public function load_textdomain() {
			load_plugin_textdomain(
				'lc-newsletter',
				false,
				dirname(plugin_basename(__FILE__)) . '/languages/'
			);
		}
	}
}

if(class_exists('LC_Newsletter')) {
	register_activation_hook(__FILE__, array('LC_Newsletter', 'activate'));
	register_deactivation_hook(__FILE__, array('LC_Newsletter', 'deactivate'));
	register_uninstall_hook(__FILE__, array('LC_Newsletter', 'uninstall'));
	$lc_newsletter = new LC_Newsletter();
}