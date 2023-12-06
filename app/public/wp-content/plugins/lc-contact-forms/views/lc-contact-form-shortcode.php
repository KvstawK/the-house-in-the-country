<?php

function html_form_code() {
	// Set a maximum number of submissions per hour
	$max_submissions = 5;

	// Get the current IP address
	$ip_address = $_SERVER['REMOTE_ADDR'];

	// Get the number of submissions this IP address has made this hour
	$submissions = get_transient( 'submissions_' . $ip_address );

	// If the number of submissions exceeds the maximum, show an error message and exit
	if ( $submissions >= $max_submissions ) {
		echo '<style>.lc-contact-forms-form { display: none; }</style>';
		echo '<style>.contact__form-container-form { box-shadow: none; }</style>';
		echo '<p class="headline-3 headline-3--orange">' . esc_html__('You have exceeded the maximum number of submissions. Please try again later!', 'lc-contact-forms') . '</p>';
		return;
	}

	echo '<form action="' . esc_url( $_SERVER['REQUEST_URI'] ) . '" method="post">';
	wp_nonce_field( 'lc_contact_form_action', 'lc_contact_form_nonce' );
	echo '<p>';
	echo '<textarea rows="10" cols="35" aria-label="' . esc_html__('Please enter your message', 'lc-contact-forms') . '" placeholder="' . esc_html__('Your Message *', 'lc-contact-forms') . '" name="lc_contact_form-message" required>' . ( isset( $_POST["lc_contact_form-message"] ) ? esc_attr( $_POST["lc_contact_form-message"] ) : '' ) . '</textarea>';
	echo '</p>';
	echo '<div class="contact__info-container-form-inputs">';
	echo '<p>';
	echo '<input type="text" aria-label="' . esc_html__('Please enter your name', 'lc-contact-forms') . '" name="lc_contact_form-name" placeholder="' . esc_html__('Your Name *', 'lc-contact-forms') . '" pattern="[\u0370-\u03FF\u1F00-\u1FFF\a-zA-Z0-9\s]+" value="' . ( isset( $_POST["lc_contact_form-name"] ) ? esc_attr( $_POST["lc_contact_form-name"] ) : '' ) . '" size="40" required />';
	echo '</p>';
	echo '<p>';
	echo '<input type="email" aria-label="' . esc_html__('Please enter your email', 'lc-contact-forms') . '" name="lc_contact_form-email" placeholder="' . esc_html__('Your Email *', 'lc-contact-forms') . '" value="' . ( isset( $_POST["lc_contact_form-email"] ) ? esc_attr( $_POST["lc_contact_form-email"] ) : '' ) . '" size="40" required />';
	echo '</p>';
	echo '</div>';
	echo '<p><button class="btn" type="submit" name="lc_contact_form-submitted" value="">' . esc_html__('Send', 'lc-contact-forms') . '</button></p>';
	echo '</form>';
}

html_form_code();

function deliver_mail() {
	// Array for storing error messages
	$errors = array();

	// If the submit button is clicked, send the email
	if ( isset( $_POST['lc_contact_form-submitted'] ) ) {
		// Check nonce
		if( !isset( $_POST['lc_contact_form_nonce'] ) || !wp_verify_nonce( $_POST['lc_contact_form_nonce'], 'lc_contact_form_action' ) ) {
			$errors[] = 'Invalid nonce';
		}

		// Check referrer
		if( !check_admin_referer( 'lc_contact_form_action', 'lc_contact_form_nonce' ) ) {
			$errors[] = 'Invalid form submission.';
		}

		// Validate email
		$email = sanitize_email( $_POST["lc_contact_form-email"] );
		if (!is_email($email)) {
			$errors[] = 'Invalid email format';
		}

		// Sanitize and validate name field
		$name    = sanitize_text_field( $_POST["lc_contact_form-name"] );
		if ( empty( $name ) ) {
			$errors[] = 'Name is required.';
		}

		// Get the site domain name
		$domain_name = $_SERVER['SERVER_NAME'];

		// Sanitize message field
		$message = esc_textarea( $_POST["lc_contact_form-message"] );
		if ( empty( $message ) ) {
			$errors[] = 'Message is required.';
		}

		// Get the blog administrator's email address
		$to = get_option( 'admin_email' );

		// Set email subject
		$subject = "Message from the TheHouseInTheCountry contact form";

		$headers = "From: $name <$email>" . "\r\n";
		$headers .= "Reply-To: $email" . "\r\n";

		// If there are errors, output them and return
		if (!empty($errors)) {
			echo '<div>';
			foreach ($errors as $error) {
				echo '<p>'. esc_html__($error, 'lc-contact-forms') .'</p>';
			}
			echo '</div>';
			return;
		}

		// Increase the number of submissions for this IP address
		$submissions = get_transient( 'submissions_' . $_SERVER['REMOTE_ADDR'] );
		set_transient( 'submissions_' . $_SERVER['REMOTE_ADDR'], $submissions + 1, HOUR_IN_SECONDS );

		// If email has been processed for sending, display a success message
		if ( wp_mail( $to, $subject, $message, $headers ) ) {
			echo '<style>.contact__info-container-form form { display: none; }</style>';
			echo '<div>';
			echo '<p class="headline-3 headline-3--success">' . esc_html__('Thanks for contacting me, expect a response soon!', 'lc-contact-forms') . '</p>';
			echo '</div>';
		} else {
			echo 'An unexpected error occurred';
		}
	}
}

deliver_mail();

