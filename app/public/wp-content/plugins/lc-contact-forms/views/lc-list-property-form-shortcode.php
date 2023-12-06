<?php
// Create a function to display a form for listing properties
function html_list_property_form_code() {
	// Check if the form has already been submitted
	$form_submitted = isset( $_POST['lc_list_property_form-submitted'] );

	// Output the success message if the form has been submitted
	if ( $form_submitted ) {
		// Use esc_html() for outputting user-supplied data
		echo '<div class="success-message">';
		echo '<p class="headline-3 headline-3--orange">' . esc_html__( 'Thanks for contacting us. We will get back to you soon!', 'lc-contact-forms' ) . '</p>';
		echo '</div>';
	} else {
		// Output the HTML form if it hasn't been submitted yet
		echo '<form action="' . esc_url( $_SERVER['REQUEST_URI'] ) . '" method="post" class="list-property-form">';

		// Add a nonce field for security
		wp_nonce_field( 'lc_list_property_form_action', 'lc_list_property_form_nonce' );

		// Add a select for choosing the promotion package
		echo '<p>';
		echo '<label for="lc_list_property_form-promotion">';
		esc_html_e( 'Choose Your Package:', 'lc-contact-forms' );
		echo '</label><br>';
		echo '<select name="lc_list_property_form-promotion" id="lc_list_property_form-promotion" required>';
		echo '<option title="' . esc_html__( 'Online Marketplaces Management Package, 5%', 'lc-contact-forms' ) . '" value="' . esc_html__( 'Online Marketplaces Management Package, 5', 'lc-contact-forms' ) . '" selected>' . esc_html__( 'Online Marketplaces Management Package, 5%', 'lc-contact-forms' ) . '</option>';
		echo '<option title="' . esc_html__( 'Posting Your Rental In Our Platform Package, 10%', 'lc-contact-forms' ) . '" value="' . esc_html__( 'Posting Your Rental In Our Platform Package, 10', 'lc-contact-forms' ) . '">' . esc_html__( 'Posting Your Rental In Our Platform Package, 10%', 'lc-contact-forms' ) . '</option>';
		echo '<option title="' . esc_html__( 'Vacation Rental Website Creation & SEO Promotion Package, 10%', 'lc-contact-forms' ) . '" value="' . esc_html__( 'Vacation Rental Website Creation & SEO Promotion Package, 10', 'lc-contact-forms' ) . '">' . esc_html__( 'Vacation Rental Website Creation & SEO Promotion Package, 10%', 'lc-contact-forms' ) . '</option>';
		echo '<option title="' . esc_html__( 'Full Vacation Rental Online Solution Package, 10%', 'lc-contact-forms' ) . '" value="' . esc_html__( 'Full Vacation Rental Online Solution Package, 10', 'lc-contact-forms' ) . '">' . esc_html__( 'Full Vacation Rental Online Solution Package, 10%', 'lc-contact-forms' ) . '</option>';
		echo '</select>';
		echo '</p>';

		// Add an input for the property name
		echo '<p>';
		echo '<label for="lc_list_property_form-name">';
		esc_html_e( 'Please enter your property\'s title', 'lc-contact-forms' );
		echo '</label>';
		echo '<input type="text" name="lc_list_property_form-name" id="lc_list_property_form-name" placeholder="' . esc_html__( 'ex: Villa Lovely', 'lc-contact-forms' ) . '" pattern="[\u0370-\u03FF\u1F00-\u1FFFa-zA-Z0-9\s]+" value="' . esc_attr( isset( $_POST["lc_list_property_form-name"] ) ? $_POST["lc_list_property_form-name"] : '' ) . '" size="40" required />';
		echo '</p>';

		// Add an input for the email address
		echo '<p>';
		echo '<label for="lc_list_property_form-email" required>';
		esc_html_e( 'Please enter your email', 'lc-contact-forms' );
		echo '</label>';
		echo '<input type="email" name="lc_list_property_form-email" id="lc_list_property_form-email" placeholder="' . esc_html__( 'Email', 'lc-contact-forms' ) . '" value="' . esc_attr( isset( $_POST["lc_list_property_form-email"] ) ? $_POST["lc_list_property_form-email"] : '' ) . '" size="40" required />';
		echo '</p>';

		// Add a textarea for entering the property URLs
		echo '<p>';
		echo '<label for="lc_list_property_form-urls">';
		esc_html_e( 'Please enter the URLs of your vacation rental (one per line)', 'lc-contact-forms' );
		echo '</label>';
		echo '<textarea name="lc_list_property_form-urls" id="lc_list_property_form-urls" rows="7" cols="40" placeholder="' . esc_html__( "ex:\nhttps://www.airbnb.com/your-apartment-name.com,\nhttps://www.booking.com/your-villa-name.com,\nhttps://www.your-website.com", 'lc-contact-forms' ) . '" rows="4" cols="35">' . esc_textarea( isset( $_POST["lc_list_property_form-urls"] ) ? $_POST["lc_list_property_form-urls"] : '' ) . '</textarea>';
		echo '</p>';

		// Add a textarea for entering an optional message
		echo '<p>';
		echo '<label for="lc_list_property_form-message">';
		esc_html_e( 'Please enter a message for us (optional)', 'lc-contact-forms' );
		echo '</label>';
		echo '<textarea name="lc_list_property_form-message" id="lc_list_property_form-message" rows="5" cols="40" placeholder="' . esc_html__( 'Your Message', 'lc-contact-forms' ) . '">' . esc_textarea( isset( $_POST["lc_list_property_form-message"] ) ? $_POST["lc_list_property_form-message"] : '' ) . '</textarea>';
		echo '</p>';

		// Add the submit button
		echo '<p><input class="btn" type="submit" name="lc_list_property_form-submitted" value="' . esc_html__( 'Send', 'lc-contact-forms' ) . '"></p>';

		// Close the form
		echo '</form>';
	}
}

html_list_property_form_code();


function list_property_deliver_mail() {
	// Use the init hook to only run this code when it's appropriate
	if (isset($_POST['lc_list_property_form-submitted'])) {
		// IP Based rate limiting
		$max_submissions = 5;
		$ip_address = $_SERVER['REMOTE_ADDR'];
		$submissions = get_transient( 'submissions_' . $ip_address );
		if ( $submissions >= $max_submissions ) {
			echo '<style>.success-message { display: none; }</style>';
			die( '<p class="headline-3 headline-3--orange">' . esc_html__('You have exceeded the maximum number of submissions. Please try again later.', 'lc-contact-forms') . '</p>' );
		}

		// Check nonce
		if (!isset($_POST['lc_list_property_form_nonce']) || !wp_verify_nonce($_POST['lc_list_property_form_nonce'], 'lc_list_property_form_action')) {
			die( esc_html__('Invalid nonce', 'lc-contact-forms') );
		}

		// Sanitize and check the form values
		$name = sanitize_text_field($_POST["lc_list_property_form-name"]);
		$email = sanitize_email($_POST["lc_list_property_form-email"]);
		$urls = isset($_POST['lc_list_property_form-urls']) ? sanitize_textarea_field($_POST['lc_list_property_form-urls']) : '';
		$message = esc_textarea($_POST["lc_list_property_form-message"]);
		$promotion_package = isset($_POST['lc_list_property_form-promotion']) ? sanitize_text_field($_POST['lc_list_property_form-promotion']) : '';

		// Validate the fields
		if (empty($name) || !is_email($email)) {
			die(esc_html__('Please fill in all the required fields.', 'lc-contact-forms'));
		}

		// Get the blog administrator's email address
		$to = get_option('admin_email');

		// Email subject
		$subject = $name . ' - ' . esc_html__('Vacation Rental Submitted', 'lc-contact-forms');

		// Email headers
		$headers = array(
			'Content-Type: text/html; charset=UTF-8',
			"From: $name <$email>"
		);

		// Add promotion package to message body
		$message .= '<br><br>' . esc_html__('Promotion Package:', 'lc-contact-forms') . ' ' . esc_html($promotion_package) . '%';

		// Add URLs to message body
		$message .= '<br><br>' . esc_html__('URLs:', 'lc-contact-forms') . ' ' . nl2br(esc_html($urls));

		// Increase the number of submissions for this IP address
		set_transient( 'submissions_' . $ip_address, ++$submissions, HOUR_IN_SECONDS );

		// Send the email and display a success message or error
		if (wp_mail($to, $subject, $message, $headers)) {
			// Hide the form and display a success message
		} else {
			error_log('wp_mail failed: ' . print_r(error_get_last(), true));
			wp_die(esc_html__('An unexpected error occurred', 'lc-contact-forms'));
		}
	}
}

list_property_deliver_mail();
