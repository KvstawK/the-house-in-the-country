<?php

function lc_newsletter_unsubscribe_page_activate() {
	$theme_dir = get_stylesheet_directory();

	$unsubscribe_page_template = $theme_dir . '/page-lc-newsletter-unsubscribe.php';
	if ( ! file_exists( $unsubscribe_page_template ) ) {
		$page_template = '<h1>' . esc_html__('You Have Successfully Unsubscribed From Our Newsletter!', 'lc-newsletter') . '</h1>';
		file_put_contents( $unsubscribe_page_template, $page_template );
	}

	// Check if the page exists
	$unsubscribe_page = get_page_by_title( 'LC Newsletter Unsubscribe' );
	if ( ! $unsubscribe_page ) {
		// Create the page
		$unsubscribe_page = array(
			'post_type'    => 'page',
			'post_title'   => 'LC Newsletter Unsubscribe',
			'post_content' => '',
			'post_status'  => 'publish',
			'post_author'  => 1,
		);
		$unsubscribe_page_id = wp_insert_post( $unsubscribe_page );

		// Set the page template
		update_post_meta( $unsubscribe_page_id, '_wp_page_template', 'page-lc-newsletter-unsubscribe.php' );
	}
}
add_action( 'init', 'lc_newsletter_unsubscribe_page_activate' );


function handle_unsubscribe() {
	// Check if the unsubscribe action is being performed
	if ( isset( $_GET['unsubscribe'] ) && isset( $_GET['email'] ) ) {
		$email = sanitize_email( $_GET['email'] );

		// Delete the post associated with this email address
		$subscriber = get_posts( array(
			'post_type'      => 'lc-newsletter',
			'posts_per_page' => 1,
			'title'          => $email
		) );
		wp_delete_post( $subscriber[0]->ID );
	}
}
add_action( 'init', 'handle_unsubscribe' );
