<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;


Class WPBS_Submenu_Page_Settings extends WPBS_Submenu_Page {

	/**
	 * Helper init method that runs on parent __construct
	 *
	 */
	protected function init() {

		add_action( 'admin_init', array( $this, 'register_settings' ), 10 );
		add_action( 'admin_init', array( $this, 'register_admin_notices' ), 10 );

	}

	/**
	 * Callback method to register admin notices that are sent via URL parameters
	 *
	 */
	public function register_admin_notices() {

		// Settings save success
		if( ! empty( $_GET['settings-updated'] ) ) {
			wpbs_admin_notices()->register_notice( 'settings_save_success', '<p>' . __( 'Settings saved successfully.', 'wp-booking-system' ) . '</p>' );
			wpbs_admin_notices()->display_notice( 'settings_save_success' );
		}

		wpbs_admin_notices()->register_notice('booking_wipe_successful', '<p>' . __('All bookings and booking related data were successfully removed.', 'wp-booking-system') . '</p>');

		wpbs_admin_notices()->register_notice('payment_reminder_cron_regenerate_success', '<p>' . __('Cron jobs successfully regenerated.', 'wp-booking-system') . '</p>');

	}


	/**
	 * Returns an array with the page tabs that should be displayed on the page
	 *
	 * @return array
	 *
	 */
	protected function get_tabs() {

		$tabs = array(
			'general' 	=> __( 'General', 'wp-booking-system' ),
			'languages' => __( 'Languages', 'wp-booking-system' ),
			'form' 		=> __( 'Form', 'wp-booking-system' ),
			'email' 	=> __( 'Email', 'wp-booking-system' ),
			'strings' 	=> __( 'Strings & Translations', 'wp-booking-system' ),
		);

		/**
		 * Filter the tabs before returning
		 *
		 * @param array $tabs
		 *
		 */
		return apply_filters( 'wpbs_submenu_page_settings_tabs', $tabs );

	}

	/**
	 * Returns an array with the sub tabs for the String & Translations page
	 *
	 * @return array
	 *
	 */
	protected function get_string_tabs(){
		$tabs = array(
			'form' => __('Form Strings', 'wp-booking-system'),
		);

		/**
		 * Filter the tabs before returning
		 *
		 * @param array $tabs
		 *
		 */
		return apply_filters('wpbs_submenu_page_settings_strings_tabs', $tabs);

	}


	/**
	 * Registers the settings option
	 *
	 */
	public function register_settings() {

		register_setting( 'wpbs_settings', 'wpbs_settings', array( $this, 'settings_sanitize' ) );

	}


	/**
	 * Sanitizes the settings before saving them to the db
	 *
	 */
	public function settings_sanitize( $settings ) {

		return $settings;

	}


	/**
	 * Callback for the HTML output for the Calendar page
	 *
	 */
	public function output() {

		include 'views/view-settings.php';

	}

}