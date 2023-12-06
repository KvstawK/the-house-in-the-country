<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;


Class WPBS_Submenu_Page_Discounts extends WPBS_Submenu_Page {

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

		// Discount insert success
		wpbs_admin_notices()->register_notice( 'discount_insert_success', '<p>' . __( 'Discount created successfully.', 'wp-booking-system-coupons-discounts') . '</p>' );

        // Discount trash success
		wpbs_admin_notices()->register_notice( 'discount_trash_success', '<p>' . __( 'Discount successfully moved to Trash.', 'wp-booking-system-coupons-discounts') . '</p>' );

		// Discount restore success
		wpbs_admin_notices()->register_notice( 'discount_restore_success', '<p>' . __( 'Discount has been successfully restored.', 'wp-booking-system-coupons-discounts') . '</p>' );

		// Discount delete success
		wpbs_admin_notices()->register_notice( 'discount_delete_success', '<p>' . __( 'Discount has been successfully deleted.', 'wp-booking-system-coupons-discounts') . '</p>' );

		// Discount edit success
		wpbs_admin_notices()->register_notice( 'discount_edit_success', '<p>' . __( 'Discount updated successfully.', 'wp-booking-system-coupons-discounts') . '</p>' );

		// Discount duplicate success
		wpbs_admin_notices()->register_notice( 'discount_duplicate_success', '<p>' . __( 'Discount has been successfully duplicated.', 'wp-booking-system' ) . '</p>' );

	}


	/**
	 * Callback for the HTML output for the Calendar page
	 *
	 */
	public function output() {

		if( empty( $this->current_subpage ) )
			include 'views/view-discounts.php';

		else {

			if( $this->current_subpage == 'add-discount' )
				include 'views/view-add-discount.php';
            
            if( $this->current_subpage == 'edit-discount' )
				include 'views/view-edit-discount.php';

		}

	}

}