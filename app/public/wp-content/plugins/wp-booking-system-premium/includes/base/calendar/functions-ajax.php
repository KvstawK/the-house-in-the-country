<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;


/**
 * Ajax callback to refresh the calendar
 *
 */
function wpbs_refresh_calendar() {

	if( empty( $_POST['action'] ) || $_POST['action'] != 'wpbs_refresh_calendar' ) {
		echo __( '', 'wp-booking-system' );
		wp_die();
	}

	if( empty( $_POST['id'] ) ) {
		wp_die();
	}

	$calendar_id   = absint( $_POST['id'] );
	$calendar      = wpbs_get_calendar( $calendar_id );
	$calendar_args = array();

	foreach( $_POST as $key => $val ) {

		if( in_array( $key, array_keys(wpbs_get_calendar_output_default_args()) ) )
			$calendar_args[$key] = sanitize_text_field( $val );

	}

	$calendar_outputter = new WPBS_Calendar_Outputter( $calendar, $calendar_args );
	
	echo $calendar_outputter->get_display();
	wp_die();

}
add_action( 'wp_ajax_nopriv_wpbs_refresh_calendar', 'wpbs_refresh_calendar' );
add_action( 'wp_ajax_wpbs_refresh_calendar', 'wpbs_refresh_calendar' );


/**
 * Ajax callback to refresh the calendar overview
 *
 */
function wpbs_refresh_calendar_overview() {

	if( empty( $_POST['action'] ) || $_POST['action'] != 'wpbs_refresh_calendar_overview' ) {
		echo __( '', 'wp-booking-system' );
		wp_die();
	}

	if( empty( $_POST['ids'] ) ) {
		wp_die();
	}

	$calendar_ids  = array_map( 'trim', explode( ',', $_POST['ids'] ) );
	
	$args = array(
		'include' => $calendar_ids,
		'orderby' => 'FIELD( id, ' . implode( ',', $calendar_ids ) . ')',
		'order'   => ''
	);

	$calendars = wpbs_get_calendars( $args );

	$calendar_args = array();

	foreach( $_POST as $key => $val ) {

		if( in_array( $key, array_keys(wpbs_get_calendar_overview_output_default_args()) ) )
			$calendar_args[$key] = sanitize_text_field( $val );

	}

	$calendar_outputter = new WPBS_Calendar_Overview_Outputter( $calendars, $calendar_args );
	
	echo $calendar_outputter->get_display();
	wp_die();

}
add_action( 'wp_ajax_nopriv_wpbs_refresh_calendar_overview', 'wpbs_refresh_calendar_overview' );
add_action( 'wp_ajax_wpbs_refresh_calendar_overview', 'wpbs_refresh_calendar_overview' );