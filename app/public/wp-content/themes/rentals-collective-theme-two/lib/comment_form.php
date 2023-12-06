<?php

function rentals_collective_theme_two_comment_form_args() {
	$commenter = wp_get_current_commenter();
	$comment_args = array(
		'title_reply' => esc_html__('Leave a Comment', 'rentals_collective_theme_two'),
		'fields' => array(
			'author' => '<div class="author-email-fields"><p class="comment-form-author">' . '<label for="author">' . esc_html('') . '</label> ' .
			            '<input id="author" name="author" type="text" placeholder="' . esc_html__('Name *', 'rentals_collective_theme_two') . '" value="' . esc_attr($commenter['comment_author']) . '" size="30" required /></p>',
			'email'  => '<p class="comment-form-email"><label for="email">' . esc_html('') . '</label> ' .
			            '<input id="email" name="email" type="text" placeholder="' . esc_html('Email *') . '" value="' . esc_attr($commenter['comment_author_email']) . '" size="30" required /></p></div>',
		),
		'comment_field' => '<p class="comment-form-comment"><label for="comment">' . esc_html('') . '</label><textarea id="comment" name="comment" cols="45" rows="8" placeholder="' . esc_html__('Write A Comment *', 'rentals_collective_theme_two') . '" required></textarea></p>',
		'comment_notes_before' => '<p>' . esc_html__('Your email address will not be published. Required fields are marked *', 'rentals_collective_theme_two') . '</p>',
		'comment_notes_after' => '',
		'submit_button' => '<button type="submit" class="btn">%4$s</button>',
		'label_submit' => esc_html__('Post a Comment', 'rentals_collective_theme_two'),
	);

	return $comment_args;
}

//function rentals_collective_theme_two_move_comment_field_to_bottom($fields) {
//	$comment_field = $fields['comment'];
//	unset($fields['comment']);
//	$fields['comment'] = $comment_field;
//
//	return $fields;
//}
//
//add_filter('comment_form_fields', 'rentals_collective_theme_two_move_comment_field_to_bottom');
//comment_form(rentals_collective_theme_two_comment_form_args());


