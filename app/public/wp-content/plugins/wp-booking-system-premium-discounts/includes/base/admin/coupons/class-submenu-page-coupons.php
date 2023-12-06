<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;


Class WPBS_Submenu_Page_Coupons extends WPBS_Submenu_Page {

	/**
	 * Helper init method that runs on parent __construct
	 *
	 */
	protected function init() {

		add_action( 'admin_init', array( $this, 'register_admin_notices' ), 10 );

	}


	/**
	 * Callback method to register admin notices that are sent via URL parameters
	 *
	 */
	public function register_admin_notices() {

		if( empty( $_GET['wpbs_message'] ) )
			return;

		// Coupon insert success
		wpbs_admin_notices()->register_notice( 'coupon_insert_success', '<p>' . __( 'Coupon created successfully.', 'wp-booking-system-coupons-discounts') . '</p>' );

        // Coupon trash success
		wpbs_admin_notices()->register_notice( 'coupon_trash_success', '<p>' . __( 'Coupon successfully moved to Trash.', 'wp-booking-system-coupons-discounts') . '</p>' );

		// Coupon restore success
		wpbs_admin_notices()->register_notice( 'coupon_restore_success', '<p>' . __( 'Coupon has been successfully restored.', 'wp-booking-system-coupons-discounts') . '</p>' );

		// Coupon delete success
		wpbs_admin_notices()->register_notice( 'coupon_delete_success', '<p>' . __( 'Coupon has been successfully deleted.', 'wp-booking-system-coupons-discounts') . '</p>' );

		// Coupon edit success
		wpbs_admin_notices()->register_notice( 'coupon_edit_success', '<p>' . __( 'Coupon updated successfully.', 'wp-booking-system-coupons-discounts') . '</p>' );

		// Coupon duplicate success
		wpbs_admin_notices()->register_notice( 'coupon_duplicate_success', '<p>' . __( 'Coupon has been successfully duplicated.', 'wp-booking-system' ) . '</p>' );

	}


	/**
	 * Callback for the HTML output for the Calendar page
	 *
	 */
	public function output() {

		if( empty( $this->current_subpage ) )
			include 'views/view-coupons.php';

		else {

			if( $this->current_subpage == 'add-coupon' )
				include 'views/view-add-coupon.php';
            
            if( $this->current_subpage == 'edit-coupon' )
				include 'views/view-edit-coupon.php';

		}

	}

}