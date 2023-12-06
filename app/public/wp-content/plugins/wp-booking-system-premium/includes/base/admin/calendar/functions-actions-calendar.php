<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;


/**
 * Validates and handles the adding of the new calendar in the database
 *
 */
function wpbs_action_add_calendar() {

	// Verify for nonce
	if( empty( $_POST['wpbs_token'] ) || ! wp_verify_nonce( $_POST['wpbs_token'], 'wpbs_add_calendar' ) )
		return;

	// Verify for calendar name
	if( empty( $_POST['calendar_name'] ) ) {

		wpbs_admin_notices()->register_notice( 'calendar_name_missing', '<p>' . __( 'Please add a name for your new calendar.', 'wp-booking-system' ) . '</p>', 'error' );
		wpbs_admin_notices()->display_notice( 'calendar_name_missing' );

		return;

	}

	// Prepare calendar data to be inserted
	$calendar_data = array(
		'name' 		    => sanitize_text_field( stripslashes($_POST['calendar_name']) ),
		'date_created'  => current_time( 'Y-m-d H:i:s' ),
		'date_modified' => current_time( 'Y-m-d H:i:s' ),
		'status'		=> 'active',
		'ical_hash'		=> wpbs_generate_hash()
	);

	// Insert calendar into the database
	$calendar_id = wpbs_insert_calendar( $calendar_data );

	// If the calendar could not be inserted show a message to the user
	if( ! $calendar_id ) {

		wpbs_admin_notices()->register_notice( 'calendar_insert_false', '<p>' . __( 'Something went wrong. Could not create the calendar. Please try again.', 'wp-booking-system' ) . '</p>', 'error' );
		wpbs_admin_notices()->display_notice( 'calendar_insert_false' );

		return;

	}

	/**
	 * Add default legend items if no legend has been selected
	 *
	 */
	if( empty( $_POST['calendar_legend'] ) ) {

		$legend_items_data = wpbs_get_default_legend_items_data();

		foreach( $legend_items_data as $legend_item_data ) {

			// Set the calendar id for the legend items data
			$legend_item_data['calendar_id'] = $calendar_id;

			// Insert legend item
			wpbs_insert_legend_item( $legend_item_data );

		}

	}


	/**
	 * Add legend items from another calendar
	 *
	 */
	if( ! empty( $_POST['calendar_legend'] ) ) {

		$copy_calendar_id 			= absint( $_POST['calendar_legend'] );
		$copy_calendar_legend_items = wpbs_get_legend_items( array( 'calendar_id' => $copy_calendar_id ) );

		if( ! empty( $copy_calendar_legend_items ) ) {

			foreach( $copy_calendar_legend_items as $legend_item ) {

				// Prepare data
				$copy_legend_item_data = $legend_item->to_array();
				$copy_legend_item_data['calendar_id'] = $calendar_id;

				// Unset the legend item id from the array
				unset( $copy_legend_item_data['id'] );

				$copy_legend_item_id   = $legend_item->get('id');

				// Insert the new legend item
				$legend_item_id = wpbs_insert_legend_item( $copy_legend_item_data );

				if( ! $legend_item_id )
					continue;

				// Get all meta from the copy calendar legend items
				$copy_legend_item_meta = wpbs_get_legend_item_meta( $copy_legend_item_id );

				if( empty( $copy_legend_item_meta ) )
					continue;

				foreach( $copy_legend_item_meta as $meta_key => $meta_values ) {

					foreach( $meta_values as $meta_value )
						wpbs_add_legend_item_meta( $legend_item_id, $meta_key, $meta_value );

				}

			}

		}

	}

	$ical_export_legend_ids = [];

	foreach(wpbs_get_legend_items( array( 'calendar_id' => $copy_calendar_id ) ) as $legend_item){
		if($legend_item->get('auto_pending') == 'booked'){
			$ical_export_legend_ids[] = $legend_item->get('id');
		}

		if($legend_item->get('auto_pending') == 'changeover_start'){
			$ical_export_legend_ids[] = $legend_item->get('id');
		}
	} 

	wpbs_update_calendar_meta($calendar_id, 'ical_export_legend_items', $ical_export_legend_ids);

	if( isset( $_POST['calendar_price'] ) ) {
		wpbs_update_calendar_meta($calendar_id, 'default_price', absint($_POST['calendar_price']));
	} else {
		wpbs_update_calendar_meta($calendar_id, 'default_price', 0);
	}

	wpbs_update_calendar_meta($calendar_id, 'default_inventory', 1);

	// Redirect to the edit page of the calendar with a success message
	wp_redirect( add_query_arg( array( 'page' => 'wpbs-calendars', 'subpage' => 'edit-calendar', 'calendar_id' => $calendar_id, 'wpbs_message' => 'calendar_insert_success' ), admin_url( 'admin.php' ) ) );
	exit;

}
add_action( 'wpbs_action_add_calendar', 'wpbs_action_add_calendar', 50 );


/**
 * Handles the trash calendar action, which changes the status of the calendar from active to trash
 *
 */
function wpbs_action_trash_calendar() {

	// Verify for nonce
	if( empty( $_GET['wpbs_token'] ) || ! wp_verify_nonce( $_GET['wpbs_token'], 'wpbs_trash_calendar' ) )
		return;

	if( empty( $_GET['calendar_id'] ) )
		return;

	$calendar_id = absint( $_GET['calendar_id'] );

	$calendar_data = array(
		'status' => 'trash'
	);

	$updated = wpbs_update_calendar( $calendar_id, $calendar_data );

	if( ! $updated )
		return;

	// Redirect to the current page
	wp_redirect( add_query_arg( array( 'page' => 'wpbs-calendars', 'calendar_status' => 'active', 'wpbs_message' => 'calendar_trash_success' ), admin_url( 'admin.php' ) ) );
	exit;

}
add_action( 'wpbs_action_trash_calendar', 'wpbs_action_trash_calendar', 50 );


/**
 * Handles the restore calendar action, which changes the status of the calendar from trash to active
 *
 */
function wpbs_action_restore_calendar() {

	// Verify for nonce
	if( empty( $_GET['wpbs_token'] ) || ! wp_verify_nonce( $_GET['wpbs_token'], 'wpbs_restore_calendar' ) )
		return;

	if( empty( $_GET['calendar_id'] ) )
		return;

	$calendar_id = absint( $_GET['calendar_id'] );

	$calendar_data = array(
		'status' => 'active'
	);

	$updated = wpbs_update_calendar( $calendar_id, $calendar_data );

	if( ! $updated )
		return;

	// Redirect to the current page
	wp_redirect( add_query_arg( array( 'page' => 'wpbs-calendars', 'calendar_status' => 'trash', 'wpbs_message' => 'calendar_restore_success' ), admin_url( 'admin.php' ) ) );
	exit;

}
add_action( 'wpbs_action_restore_calendar', 'wpbs_action_restore_calendar', 50 );


/**
 * Handles the delete calendar action, which removes all calendar data, legend items and events data
 * associated with the calendar
 *
 */
function wpbs_action_delete_calendar() {

	// Verify for nonce
	if( empty( $_GET['wpbs_token'] ) || ! wp_verify_nonce( $_GET['wpbs_token'], 'wpbs_delete_calendar' ) )
		return;

	if( empty( $_GET['calendar_id'] ) )
		return;

	$calendar_id = absint( $_GET['calendar_id'] );

	/**
	 * Delete the calendar
	 *
	 */
	$deleted = wpbs_delete_calendar( $calendar_id );

	if( ! $deleted )
		return;
	
	/**
	 * Delete calendar meta
	 *
	 */
	$calendar_meta = wpbs_get_calendar_meta( $calendar_id );

	if( ! empty( $calendar_meta ) ) {

		foreach( $calendar_meta as $key => $value ) {

			wpbs_delete_calendar_meta( $calendar_id, $key );

		}

	}


	/**
	 * Delete legend items
	 *
	 */
	$legend_items = wpbs_get_legend_items( array( 'calendar_id' => $calendar_id ) );

	foreach( $legend_items as $legend_item ) {

		wpbs_delete_legend_item( $legend_item->get('id') );

	}

	/**
	 * Delete legend items meta
	 *
	 */
	foreach( $legend_items as $legend_item ) {

		$legend_item_meta = wpbs_get_legend_item_meta( $legend_item->get('id') );

		if( ! empty( $legend_item_meta ) ) {

			foreach( $legend_item_meta as $key => $value ) {

				wpbs_delete_legend_item_meta( $legend_item->get('id'), $key );

			}

		}

	}

	/**
	 * Delete events
	 *
	 */
	$events = wpbs_get_events( array( 'calendar_id' => $calendar_id ) );

	foreach( $events as $event ) {

		wpbs_delete_event( $event->get('id') );

	}

	/**
	 * Delete events meta
	 *
	 */
	foreach( $events as $event ) {

		$event_meta = wpbs_get_event_meta( $event->get('id') );

		if( ! empty( $event_meta ) ) {

			foreach( $event_meta as $key => $value ) {

				wpbs_delete_event_meta( $event->get('id'), $key );

			}

		}

	}

	/**
	 * Delete bookings
	 *
	 */
	
	$bookings = wpbs_get_bookings( array( 'calendar_id' => $calendar_id ) );

	foreach( $bookings as $booking ) {
		
		wpbs_delete_booking( $booking->get('id') );

		$bookings_meta = wpbs_get_booking_meta( $booking->get('id') );

		if( ! empty( $bookings_meta ) ) {

			foreach( $bookings_meta as $key => $value ) {

				wpbs_delete_booking_meta( $booking->get('id'), $key );

			}

		}
	}

	// Redirect to the current page
	wp_redirect( add_query_arg( array( 'page' => 'wpbs-calendars', 'calendar_status' => 'trash', 'wpbs_message' => 'calendar_delete_success' ), admin_url( 'admin.php' ) ) );
	exit;

}
add_action( 'wpbs_action_delete_calendar', 'wpbs_action_delete_calendar', 50 );

/**
 * Handles enabling iCalendar import URLs
 *
 */
function wpbs_action_enable_icalendar_links() {

	// Verify for nonce
	if( empty( $_GET['wpbs_token'] ) || ! wp_verify_nonce( $_GET['wpbs_token'], 'wpbs_enable_icalendar_links' ) )
		return;

	if( empty( $_GET['calendar_id'] ) )
		return;

	$calendar_id = absint( $_GET['calendar_id'] );

	wpbs_update_calendar_meta($calendar_id, 'disable_icalendar_links', false);

	// Redirect to the current page
	wp_redirect( add_query_arg( array( 'page' => 'wpbs-calendars', 'subpage' => 'ical-import-export', 'calendar_id' => $calendar_id ), admin_url( 'admin.php' ) ) );
	exit;

}
add_action( 'wpbs_action_enable_icalendar_links', 'wpbs_action_enable_icalendar_links', 50 );

/**
 * Handles disabling iCalendar import URLs
 *
 */
function wpbs_action_disable_icalendar_links() {

	// Verify for nonce
	if( empty( $_GET['wpbs_token'] ) || ! wp_verify_nonce( $_GET['wpbs_token'], 'wpbs_disable_icalendar_links' ) )
		return;

	if( empty( $_GET['calendar_id'] ) )
		return;

	$calendar_id = absint( $_GET['calendar_id'] );

	wpbs_update_calendar_meta($calendar_id, 'disable_icalendar_links', true);

	// Redirect to the current page
	wp_redirect( add_query_arg( array( 'page' => 'wpbs-calendars', 'subpage' => 'ical-import-export', 'calendar_id' => $calendar_id ), admin_url( 'admin.php' ) ) );
	exit;

}
add_action( 'wpbs_action_disable_icalendar_links', 'wpbs_action_disable_icalendar_links', 50 );


/**
 * Handles the duplication of a calendar
 *
 */
function wpbs_action_duplicate_calendar()
{

    // Verify for nonce
    if (empty($_GET['wpbs_token']) || !wp_verify_nonce($_GET['wpbs_token'], 'wpbs_duplicate_calendar')) {
        return;
    }

    if (empty($_GET['calendar_id'])) {
        return;
    }

    $calendar_id = absint($_GET['calendar_id']);
	
    $calendar = wpbs_get_calendar($calendar_id);

    $new_calendar_id = wpbs_insert_calendar(array(
        'name' => __('Duplicate of', 'wp-booking-system') . ' ' . $calendar->get('name'),
        'date_created' => date('Y-m-d H:i:s', current_time('timestamp')),
        'date_modified' => date('Y-m-d H:i:s', current_time('timestamp')),
        'status' => $calendar->get('status'),
        'ical_hash' => wpbs_generate_hash()
    ));

	// // Copy relevant meta fields
    wpbs_add_calendar_meta($new_calendar_id, 'default_inventory', wpbs_get_calendar_meta($calendar_id, 'default_inventory', true));
    wpbs_add_calendar_meta($new_calendar_id, 'default_price', wpbs_get_calendar_meta($calendar_id, 'default_price', true));

	// Copy legend
	$legend_items = wpbs_get_legend_items(array('calendar_id' => $calendar_id));

	$legend_item_ids = [0 => 0];
	foreach($legend_items as $legend_item){

		$new_legend_item_id = wpbs_insert_legend_item(array(
			'type' => $legend_item->get('type'),
            'name' => $legend_item->get('name'),
            'color' => $legend_item->get('color'),
            'color_text' => $legend_item->get('color_text'),
            'is_default' => $legend_item->get('is_default'),
            'is_visible' => $legend_item->get('is_visible'),
            'is_bookable' => $legend_item->get('is_bookable'),
            'auto_pending' => $legend_item->get('auto_pending'),
            'calendar_id' => $new_calendar_id,
		));

		$meta_fields = wpbs_get_legend_item_meta( $legend_item->get('id') );

		foreach($meta_fields as $meta_key => $meta_value){
			wpbs_add_legend_item_meta($new_legend_item_id, $meta_key, $meta_value[0]);
		}


		$legend_item_ids[$legend_item->get('id')] = $new_legend_item_id;

	}

	// Copy Events
	$events = wpbs_get_events(array('calendar_id' => $calendar_id));

	foreach($events as $event){
		$new_event_id = wpbs_insert_event(array(
			'date_year' => $event->get('date_year'),
			'date_month' => $event->get('date_month'),
			'date_day' => $event->get('date_day'),
			'calendar_id' => $new_calendar_id,
			'booking_id' => 0,
			'legend_item_id' => $legend_item_ids[$event->get('legend_item_id')],
			'description' => $event->get('description'),
			'tooltip' => $event->get('tooltip'),
			'price' => $event->get('price'),
			'inventory' => $event->get('inventory'),
			'meta' => $event->get('meta'),
		));

		$meta_fields = wpbs_get_event_meta( $event->get('id') );

		foreach($meta_fields as $meta_key => $meta_value){
			wpbs_add_event_meta($new_event_id, $meta_key, $meta_value[0]);
		}
			
	}

    // Redirect to the current page
    wp_redirect(add_query_arg(array('page' => 'wpbs-calendars', 'wpbs_message' => 'calendar_duplicate_success'), admin_url('admin.php')));
    exit;

}
add_action('wpbs_action_duplicate_calendar', 'wpbs_action_duplicate_calendar', 50);