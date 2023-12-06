<?php

function html_form() {
	echo '<form action="' . esc_url( $_SERVER['REQUEST_URI'] ) . '" method="post">';
	wp_nonce_field('lc_newsletter_form_action', 'lc_newsletter_form_nonce');
	echo '<input type="email" id="lc-newsletter-email" name="lc-newsletter-email" aria-label="' . esc_attr__('Please enter your email', 'lc-newsletter') . '" placeholder="' . esc_attr__('Your email', 'lc-newsletter') . '">';
	echo '<input type="submit" class="btn" name="lc-newsletter-submit" value="' . esc_attr__('Subscribe', 'lc-newsletter') . '">';
	echo '<input type="hidden" name="action" value="lc-newsletter">';
	echo '</form>';
}

function subscribe() {
	if ('POST' === $_SERVER['REQUEST_METHOD'] && !empty($_POST['action']) && 'lc-newsletter' === $_POST['action']) {
		// Check nonce
		if (!isset($_POST['lc_newsletter_form_nonce']) || !wp_verify_nonce($_POST['lc_newsletter_form_nonce'], 'lc_newsletter_form_action')) {
			return;
		}

		$email = sanitize_email($_POST['lc-newsletter-email']);
		$post_type = 'lc-newsletter';

		$existing_post = get_page_by_title($email, OBJECT, $post_type);
		if (!empty($existing_post)) {
			echo '<style>.home__newsletter-container-text form, .home__newsletter-container-text p, .home__newsletter-container-text h2 { display: none }</style>';
			echo '<div id="subscription-error" class="subscription-error">';
			echo esc_html__('You have already subscribed!', 'lc-newsletter');
			echo '</div>';
			echo '<script type="text/javascript">
				setTimeout(function() {
					document.querySelector("#subscription-error").scrollIntoView({ behavior: "smooth", block: "center" });
				}, 100);
			</script>';
			return;
		}

		$new_post = array(
			'post_title' => $email,
			'post_status' => 'publish',
			'post_type' => $post_type,
		);

		$pid = wp_insert_post($new_post);
		if (!is_wp_error($pid)) {
			add_post_meta($pid, 'lc-newsletter-email', $email, true);
			echo '<style>.home__newsletter-container-text form, .home__newsletter-container-text p, .home__newsletter-container-text h2 { display: none }</style>';
			echo '<div id="subscription-success" class="subscription-success">';
			echo esc_html__('You are now subscribed!', 'lc-newsletter');
			echo '</div>';
			echo '<script type="text/javascript">
				setTimeout(function() {
					document.querySelector("#subscription-success").scrollIntoView({ behavior: "smooth", block: "center" });
				}, 100);
			</script>';
		}
	}
}






