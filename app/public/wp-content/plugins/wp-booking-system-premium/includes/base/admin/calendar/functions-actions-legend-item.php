<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Handles "add new legend item" action
 *
 */
function wpbs_action_add_legend_item() {

	// Verify for nonce
	if( empty( $_POST['wpbs_token'] ) || ! wp_verify_nonce( $_POST['wpbs_token'], 'wpbs_add_legend_item' ) )
		return;

	// Verify for the calendar id
	if( empty( $_GET['calendar_id'] ) ) {

		wpbs_admin_notices()->register_notice( 'legend_item_calendar_id_missing', '<p>' . __( 'Something went wrong. Could not add the legend item.', 'wp-booking-system' ) . '</p>', 'error' );
		wpbs_admin_notices()->display_notice( 'legend_item_calendar_id_missing' );

		return;

	}

	// Verify for legend item name
	if( empty( $_POST['legend_item_name'] ) ) {

		wpbs_admin_notices()->register_notice( 'legend_item_name_missing', '<p>' . __( 'Please add a name for your legend item.', 'wp-booking-system' ) . '</p>', 'error' );
		wpbs_admin_notices()->display_notice( 'legend_item_name_missing' );

		return;

	}

	// Verify for legend item type
	if( empty( $_POST['legend_item_type'] ) ) {

		wpbs_admin_notices()->register_notice( 'legend_item_type_missing', '<p>' . __( 'Please select a type for your legend item.', 'wp-booking-system' ) . '</p>', 'error' );
		wpbs_admin_notices()->display_notice( 'legend_item_type_missing' );

		return;

	}

	// Remove empty values from the colors array
	if( isset( $_POST['legend_item_color'] ) && is_array( $_POST['legend_item_color'] ) )
		$_POST['legend_item_color'] = array_filter( $_POST['legend_item_color'] );

	// Verify for legend item type
	if( empty( $_POST['legend_item_color'] ) || ( $_POST['legend_item_type'] != 'single' && count( $_POST['legend_item_color'] ) <= 1 ) ) {

		wpbs_admin_notices()->register_notice( 'legend_item_color_missing', '<p>' . __( 'Please select the color for your legend item.', 'wp-booking-system' ) . '</p>', 'error' );
		wpbs_admin_notices()->display_notice( 'legend_item_color_missing' );

		return;

	}

	// Get legend items to see if there are any added, if not we set this one to be the default one
	$legend_items = wpbs_get_legend_items( array( 'calendar_id' => absint( $_GET['calendar_id'] ), 'number' => 1 ) );

	// Prepare legend item data to be inserted into the database
	$legend_item_data = array(
		'name'  	  => sanitize_text_field( $_POST['legend_item_name'] ),
		'type'  	  => sanitize_text_field( $_POST['legend_item_type'] ),
		'color' 	  => $_POST['legend_item_color'],
		'color_text'  => ( ! empty( $_POST['legend_item_color_text'] ) ? sanitize_text_field( $_POST['legend_item_color_text'] ) : '' ),
		'is_visible'  => 1,
		'is_bookable'  => 1,
		'is_default'  => ( empty( $legend_items ) ? 1 : 0 ),
		'calendar_id' => absint( $_GET['calendar_id'] )
	);


	// Insert the legend item
	$inserted = wpbs_insert_legend_item( $legend_item_data );

	// If the legend item could not be inserted show a message to the user
	if( ! $inserted ) {

		wpbs_admin_notices()->register_notice( 'legend_item_insert_fail', '<p>' . __( 'Something went wrong. Could not add the legend item. Please try again.', 'wp-booking-system' ) . '</p>', 'error' );
		wpbs_admin_notices()->display_notice( 'legend_item_insert_fail' );

		return;

	}

	/**
	 * Handle translations
	 *
	 */
	$settings 		  = get_option( 'wpbs_settings', array() );
	$active_languages = ( ! empty( $settings['active_languages'] ) ? $settings['active_languages'] : array() );

	foreach( $active_languages as $code ) {

		// Add translation
		if( ! empty( $_POST['legend_item_translation_' . $code] ) )
			wpbs_add_legend_item_meta( $inserted, 'translation_' . $code, sanitize_text_field( $_POST['legend_item_translation_' . $code] ) );

	}

	// Redirect to the edit page of the calendar with a success message
	wp_redirect( add_query_arg( array( 'page' => 'wpbs-calendars', 'subpage' => 'view-legend', 'calendar_id' => absint( $_GET['calendar_id'] ), 'wpbs_message' => 'legend_item_insert_success' ), admin_url( 'admin.php' ) ) );
	exit;

}
add_action( 'wpbs_action_add_legend_item', 'wpbs_action_add_legend_item' );


/**
 * Handles "add new legend item" action
 *
 */
function wpbs_action_edit_legend_item() {

	// Verify for nonce
	if( empty( $_POST['wpbs_token'] ) || ! wp_verify_nonce( $_POST['wpbs_token'], 'wpbs_edit_legend_item' ) )
		return;

	// Verify for legend item name
	if( empty( $_POST['legend_item_name'] ) ) {

		wpbs_admin_notices()->register_notice( 'legend_item_name_missing', '<p>' . __( 'Please add a name for your legend item.', 'wp-booking-system' ) . '</p>', 'error' );
		wpbs_admin_notices()->display_notice( 'legend_item_name_missing' );

		return;

	}

	// Verify for legend item type
	if( empty( $_POST['legend_item_type'] ) ) {

		wpbs_admin_notices()->register_notice( 'legend_item_type_missing', '<p>' . __( 'Please select a type for your legend item.', 'wp-booking-system' ) . '</p>', 'error' );
		wpbs_admin_notices()->display_notice( 'legend_item_type_missing' );

		return;

	}

	// Remove empty values from the colors array
	if( isset( $_POST['legend_item_color'] ) && is_array( $_POST['legend_item_color'] ) )
		$_POST['legend_item_color'] = array_filter( $_POST['legend_item_color'] );

	// Verify for legend item type
	if( empty( $_POST['legend_item_color'] ) || ( $_POST['legend_item_type'] != 'single' && count( $_POST['legend_item_color'] ) <= 1 ) ) {

		wpbs_admin_notices()->register_notice( 'legend_item_color_missing', '<p>' . __( 'Please select the color for your legend item.', 'wp-booking-system' ) . '</p>', 'error' );
		wpbs_admin_notices()->display_notice( 'legend_item_color_missing' );

		return;

	}

	// Prepare legend item data to be inserted into the database
	$legend_item_data = array(
		'name'  	  => sanitize_text_field( $_POST['legend_item_name'] ),
		'type'  	  => sanitize_text_field( $_POST['legend_item_type'] ),
		'color' 	  => $_POST['legend_item_color'],
		'color_text'  => ( ! empty( $_POST['legend_item_color_text'] ) ? sanitize_text_field( $_POST['legend_item_color_text'] ) : '' ),
	);

	// Insert the legend item
	$updated = wpbs_update_legend_item( absint( $_GET['legend_item_id'] ), $legend_item_data );

	// If the legend item could not be updated show a message to the user
	if( ! $updated ) {

		wpbs_admin_notices()->register_notice( 'legend_item_update_fail', '<p>' . __( 'Something went wrong. Could not updated the legend item. Please try again.', 'wp-booking-system' ) . '</p>', 'error' );
		wpbs_admin_notices()->display_notice( 'legend_item_update_fail' );

		return;

	}

	/**
	 * Handle translations
	 *
	 */
	$settings 		  = get_option( 'wpbs_settings', array() );
	$active_languages = ( ! empty( $settings['active_languages'] ) ? $settings['active_languages'] : array() );

	foreach( $active_languages as $code ) {

		// Firstly remove the translation
		wpbs_delete_legend_item_meta( absint( $_GET['legend_item_id'] ), 'translation_' . $code );

		// Add translation
		if( ! empty( $_POST['legend_item_translation_' . $code] ) )
			wpbs_add_legend_item_meta( absint( $_GET['legend_item_id'] ), 'translation_' . $code, sanitize_text_field( $_POST['legend_item_translation_' . $code] ) );

	}

	// Redirect to the edit page of the calendar with a success message
	wp_redirect( add_query_arg( array( 'page' => 'wpbs-calendars', 'subpage' => 'edit-legend-item', 'legend_item_id' => absint( $_GET['legend_item_id'] ), 'calendar_id' => absint( $_GET['calendar_id'] ), 'wpbs_message' => 'legend_item_update_success' ), admin_url( 'admin.php' ) ) );
	exit;

}
add_action( 'wpbs_action_edit_legend_item', 'wpbs_action_edit_legend_item' );


/**
 * Handles the "delete legend item" action
 *
 */
function wpbs_action_delete_legend_item() {

	// Verify for nonce
	if( empty( $_GET['wpbs_token'] ) || ! wp_verify_nonce( $_GET['wpbs_token'], 'wpbs_delete_legend_item' ) )
		return;

	// Verify for legend item id
	if( empty( $_GET['legend_item_id'] ) ) {

		wpbs_admin_notices()->register_notice( 'legend_item_id_missing', '<p>' . __( 'Something went wrong. Could not delete the legend item. Please try again.', 'wp-booking-system' ) . '</p>', 'error' );
		wpbs_admin_notices()->display_notice( 'legend_item_id_missing' );

		return;

	}

	// Set legend item id
	$legend_item_id = absint( $_GET['legend_item_id'] );

	// Get legend item to see if it exists
	$legend_item = wpbs_get_legend_item( $legend_item_id );

	if( is_null( $legend_item ) ) {

		wpbs_admin_notices()->register_notice( 'legend_item_is_null', '<p>' . __( 'Something went wrong. Could not delete the legend item. Please try again.', 'wp-booking-system' ) . '</p>', 'error' );
		wpbs_admin_notices()->display_notice( 'legend_item_is_null' );

		return;

	}

	// Set calendar_id
	$calendar_id = $legend_item->get('calendar_id');

	// Delete the legend item
	$deleted = wpbs_delete_legend_item( $legend_item_id );

	if( ! $deleted ) {

		wpbs_admin_notices()->register_notice( 'legend_item_delete_fail', '<p>' . __( 'Something went wrong. Could not delete the legend item. Please try again.', 'wp-booking-system' ) . '</p>', 'error' );
		wpbs_admin_notices()->display_notice( 'legend_item_delete_fail' );

		return;

	}

	// Delete the legend item meta data
	$legend_item_meta = wpbs_get_legend_item_meta( $legend_item_id );

	if( ! empty( $legend_item_meta ) ) {

		foreach( $legend_item_meta as $meta_key => $meta_value )
			wpbs_delete_legend_item_meta( $legend_item_id, $meta_key );

	}
	
	// Redirect to the view legend page with a success message
	wp_redirect( add_query_arg( array( 'wpbs_message' => 'legend_item_delete_success', 'calendar_id' => $calendar_id ), remove_query_arg( array( 'wpbs_action', 'wpbs_token' ) ) ) );
	exit;

}
add_action( 'wpbs_action_delete_legend_item', 'wpbs_action_delete_legend_item' );


/**
 * Handles the "make legend item default" action
 *
 */
function wpbs_action_make_default_legend_item() {

	// Verify for nonce
	if( empty( $_GET['wpbs_token'] ) || ! wp_verify_nonce( $_GET['wpbs_token'], 'wpbs_make_default_legend_item' ) )
		return;

	if( empty( $_GET['legend_item_id'] ) )
		return;

	$legend_item = wpbs_get_legend_item( absint( $_GET['legend_item_id'] ) );

	if( is_null( $legend_item ) )
		return;

	// Get default legend item
	$default_legend_item = wpbs_get_legend_items( array( 'calendar_id' => $legend_item->get('calendar_id'), 'is_default' => 1 ) );
	$default_legend_item = ( ! empty( $default_legend_item ) && is_array( $default_legend_item ) ? $default_legend_item[0] : null );

	// Update the default legend item to not be the default one
	if( ! is_null( $default_legend_item ) ) {

		$legend_item_data = array(
			'is_default' => 0
		);

		$updated = wpbs_update_legend_item( $default_legend_item->get('id'), $legend_item_data );

	} else
		$updated = true;

	if( ! $updated )
		return;

	// Update the current legend item to be the default one
	$legend_item_data = array(
		'is_default' => 1
	);

	$updated = wpbs_update_legend_item( $legend_item->get('id'), $legend_item_data );

	if( ! $updated )
		return;

	// Redirect to the edit page of the legend with a success message
	wp_redirect( add_query_arg( array( 'wpbs_message' => 'legend_item_make_default_success' ), remove_query_arg( array( 'wpbs_action', 'wpbs_token' ) ) ) );
	exit;

}
add_action( 'wpbs_action_make_default_legend_item', 'wpbs_action_make_default_legend_item' );


/**
 * Handles the "make legend item visible" action
 *
 */
function wpbs_action_make_visible_legend_item() {

	// Verify for nonce
	if( empty( $_GET['wpbs_token'] ) || ! wp_verify_nonce( $_GET['wpbs_token'], 'wpbs_make_visible_legend_item' ) )
		return;

	if( empty( $_GET['legend_item_id'] ) )
		return;

	$legend_item = wpbs_get_legend_item( absint( $_GET['legend_item_id'] ) );

	if( is_null( $legend_item ) )
		return;

	// Prepare legend item data to be updated
	$legend_item_data = array(
		'is_visible' => 1
	);

	// Update legend item in the database
	$updated = wpbs_update_legend_item( $legend_item->get('id'), $legend_item_data );

	if( ! $updated )
		return;

	// Redirect to the edit page of the legend with a success message
	wp_redirect( add_query_arg( array( 'wpbs_message' => 'legend_item_make_visible_success' ), remove_query_arg( array( 'wpbs_action', 'wpbs_token' ) ) ) );
	exit;

}
add_action( 'wpbs_action_make_visible_legend_item', 'wpbs_action_make_visible_legend_item', 50 );


/**
 * Handles the "make legend item invisible" action
 *
 */
function wpbs_action_make_invisible_legend_item() {

	// Verify for nonce
	if( empty( $_GET['wpbs_token'] ) || ! wp_verify_nonce( $_GET['wpbs_token'], 'wpbs_make_invisible_legend_item' ) )
		return;

	if( empty( $_GET['legend_item_id'] ) )
		return;

	$legend_item = wpbs_get_legend_item( absint( $_GET['legend_item_id'] ) );

	if( is_null( $legend_item ) )
		return;

	// Prepare legend item data to be updated
	$legend_item_data = array(
		'is_visible' => 0
	);

	// Update legend item in the database
	$updated = wpbs_update_legend_item( $legend_item->get('id'), $legend_item_data );

	if( ! $updated )
		return;

	// Redirect to the edit page of the legend with a success message
	wp_redirect( add_query_arg( array( 'wpbs_message' => 'legend_item_make_invisible_success' ), remove_query_arg( array( 'wpbs_action', 'wpbs_token' ) ) ) );
	exit;

}
add_action( 'wpbs_action_make_invisible_legend_item', 'wpbs_action_make_invisible_legend_item', 50 );


/**
 * Handles the "make legend item bookable" action
 *
 */
function wpbs_action_make_bookable_legend_item() {
	
	// Verify for nonce
	if( empty( $_GET['wpbs_token'] ) || ! wp_verify_nonce( $_GET['wpbs_token'], 'wpbs_make_bookable_legend_item' ) )
		return;
		
	if( empty( $_GET['legend_item_id'] ) )
		return;


	$legend_item = wpbs_get_legend_item( absint( $_GET['legend_item_id'] ) );

	if( is_null( $legend_item ) )
		return;

	// Prepare legend item data to be updated
	$legend_item_data = array(
		'is_bookable' => 1
	);

	// Update legend item in the database
	$updated = wpbs_update_legend_item( $legend_item->get('id'), $legend_item_data );

	if( ! $updated )
		return;

	// Redirect to the edit page of the legend with a success message
	wp_redirect( add_query_arg( array( 'wpbs_message' => 'legend_item_make_bookable_success' ), remove_query_arg( array( 'wpbs_action', 'wpbs_token' ) ) ) );
	exit;

}
add_action( 'wpbs_action_make_bookable_legend_item', 'wpbs_action_make_bookable_legend_item', 50 );


/**
 * Handles the "make legend item unbookable" action
 *
 */
function wpbs_action_make_unbookable_legend_item() {

	// Verify for nonce
	if( empty( $_GET['wpbs_token'] ) || ! wp_verify_nonce( $_GET['wpbs_token'], 'wpbs_make_unbookable_legend_item' ) )
		return;

	if( empty( $_GET['legend_item_id'] ) )
		return;

	$legend_item = wpbs_get_legend_item( absint( $_GET['legend_item_id'] ) );

	if( is_null( $legend_item ) )
		return;

	// Prepare legend item data to be updated
	$legend_item_data = array(
		'is_bookable' => 0
	);

	// Update legend item in the database
	$updated = wpbs_update_legend_item( $legend_item->get('id'), $legend_item_data );

	if( ! $updated )
		return;

	// Redirect to the edit page of the legend with a success message
	wp_redirect( add_query_arg( array( 'wpbs_message' => 'legend_item_make_unbookable_success' ), remove_query_arg( array( 'wpbs_action', 'wpbs_token' ) ) ) );
	exit;

}
add_action( 'wpbs_action_make_unbookable_legend_item', 'wpbs_action_make_unbookable_legend_item', 50 );


/**
 * Handles the "auto pending" action
 *
 */
function wpbs_action_change_legend_auto_pending() {

	// Verify for nonce
	if( empty( $_POST['wpbs_token'] ) || ! wp_verify_nonce( $_POST['wpbs_token'], 'wpbs_change_legend_auto_pending' ) )
		return;

	if( empty( $_POST['legend_item_id'] ) )
		return;

	$legend_item = wpbs_get_legend_item( absint( $_POST['legend_item_id'] ) );

	if( is_null( $legend_item ) )
		return;

	
	$existing_legends = wpbs_get_legend_items( array('calendar_id' => $legend_item->get('calendar_id'), 'auto_pending' => $_POST['auto_pending']) );

	foreach($existing_legends as $existing_legend){
		$existing_legend_data = array(
			'auto_pending' => ''
		);
		wpbs_update_legend_item( $existing_legend->get('id'), $existing_legend_data );
	}

	// Prepare legend item data to be updated
	$legend_item_data = array(
		'auto_pending' => esc_sql($_POST['auto_pending'])
	);

	// Update legend item in the database
	$updated = wpbs_update_legend_item( $legend_item->get('id'), $legend_item_data );

	if( ! $updated )
		return;

	// Redirect to the edit page of the legend with a success message
	wp_redirect( add_query_arg( array( 'wpbs_message' => 'legend_item_change_auto_pending_success', 'legend_item_id' => $legend_item->get('id') ), remove_query_arg( array( 'wpbs_action', 'wpbs_token' ) ) ) );
	exit;

}
add_action( 'wpbs_action_change_legend_auto_pending', 'wpbs_action_change_legend_auto_pending', 50 );

