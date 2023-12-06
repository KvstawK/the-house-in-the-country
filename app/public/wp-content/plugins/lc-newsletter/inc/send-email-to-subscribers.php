<?php

function send_email_to_subscribers($post_id) {
	// Get all the subscribers from the "lc-newsletter" custom post type
	$subscribers = get_posts(array(
		'post_type' => 'lc-newsletter',
		'posts_per_page' => -1
	));

	// Get the new post information
	$post = get_post($post_id);

	// Prepare the email contents
	$to = array();
	$subject = 'New Blog Post From Lodgings Collective: ' . $post->post_title;

	$message = '<html><body>';
	$message .= '<h1>' . esc_html__('A new blog post has been created:') . '</h1>';
	$message .= '<p><a href="' . get_permalink($post_id) . '">' . $post->post_title . '</a></p>';
	$message .= '<p>' . get_the_post_thumbnail($post_id, 'thumbnail') . '</p>';
	$message .= '<p>' . wp_trim_words($post->post_content, 55, '...') . '</p>';
	$message .= '<p><a href="http://localhost:10004/lc-unsubscribe?unsubscribe=1&email=' . $subscriber->post_title . '">' . esc_html__('Unsubscribe') . '</a></p>';
	$message .= '</body></html>';

	$headers = array('Content-Type: text/html; charset=UTF-8');

	// Loop through all the subscribers and send them the email
	foreach ($subscribers as $subscriber) {
		$to[] = $subscriber->post_title;
	}
	wp_mail($to, $subject, $message, $headers);
}
add_action('publish_post', 'send_email_to_subscribers');

