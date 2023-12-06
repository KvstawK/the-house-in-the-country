<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;


/**
 * Handles the "sort legend items" ajax action
 *
 */
function wpbs_action_ajax_sort_legend_items() {

	// Prepare a redirect url in case of failures
	$redirect_url_error = add_query_arg( array( 'page' => 'wpbs-calendars', 'wpbs_message' => 'sort_legend_items_fail' ), admin_url( 'admin.php' ) );

	// Exit if the token is not present
	if( empty( $_POST['token'] ) || ! wp_verify_nonce( $_POST['token'], 'wpbs_list_table_legend_items' ) ) {
		echo json_encode( array( 'success' => 0, 'redirect_url_error' => $redirect_url_error ) );
		wp_die();
	}

	// Exit if the calendar id is not present
	if( empty( $_POST['calendar_id'] ) ) {
		echo json_encode( array( 'success' => 0, 'redirect_url_error' => $redirect_url_error ) );
		wp_die();
	}

	// Exit if the legend items are not present
	if( empty( $_POST['legend_item_ids'] ) ) {
		echo json_encode( array( 'success' => 0, 'redirect_url_error' => $redirect_url_error ) );
		wp_die();
	}

	$calendar_id     = absint( $_POST['calendar_id'] );
	$legend_item_ids = array_map( 'absint', $_POST['legend_item_ids'] );

	// Update the sort order in the database
	$updated = wpbs_update_calendar_meta( $calendar_id, 'legend_items_sort_order', $legend_item_ids );
	
	if( ! $updated ) {

		$redirect_url_error = add_query_arg( array( 'subpage' => 'view-legend', 'calendar_id' => $calendar_id ), $redirect_url_error );

		echo json_encode( array( 'success' => 0, 'redirect_url_error' => $redirect_url_error ) );
		wp_die();
	}

	echo json_encode( array( 'success' => 1 ) );
	wp_die();

}
add_action( 'wp_ajax_wpbs_sort_legend_items', 'wpbs_action_ajax_sort_legend_items' );