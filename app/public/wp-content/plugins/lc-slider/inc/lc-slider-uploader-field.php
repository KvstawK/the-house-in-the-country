<?php

function multi_media_uploader_field($name, $value = '', $tag_options = array('title' => 'h3', 'subtitle' => 'h4', 'content' => 'p')) {
	$image      = '">' . esc_html__( 'Add Images', 'lc-slider' ) . '';
	$image_str  = '';
	$image_size = 'full';
	$display    = 'none';
	$value      = explode( ',', $value );

	if (!empty($value)) {
		foreach ($value as $values) {
			$image_attributes = wp_get_attachment_image_src($values, $image_size);
			$image_id = $values;
			$title_tag = get_post_meta($image_id, 'title_tag', true);
			$subtitle_tag = get_post_meta($image_id, 'subtitle_tag', true);
			$content_tag = get_post_meta($image_id, 'content_tag', true);
			$button_text_tag = get_post_meta($image_id, 'button_text_tag', true);

			if ($image_attributes) {
				$image_str .= '<li data-attachment-id="' . $values . '">';
				$image_str .= '<a href="' . $image_attributes[0] . '" target="_blank"><img src="' . $image_attributes[0] . '" /></a>';
				$image_str .= '<div class="image-details">';

				// Title
				$image_str .= '<label for="' . $name . '_title_tag_' . $values . '">Title tag: </label>';
				$image_str .= '<select name="' . $name . '_title_tag[]" id="' . $name . '_title_tag_' . $values . '">';
				foreach (array('h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'p') as $tag) {
					$selected = $tag == $title_tag ? ' selected' : '';
					$image_str .= '<option value="' . $tag . '"' . $selected . '>' . $tag . '</option>';
				}
				$image_str .= '</select>';

				$image_str .= '<input type="text" name="' . $name . '_title[]" value="' . esc_attr(get_post_meta($values, $name . '_title', true)) . '" placeholder="' . esc_attr__('Enter title', 'text-domain') . '" />';

				// Subtitle
				$image_str .= '<label for="' . $name . '_subtitle_tag_' . $values . '">Subtitle tag: </label>';
				$image_str .= '<select name="' . $name . '_subtitle_tag[]" id="' . $name . '_subtitle_tag_' . $values . '">';
				foreach (array('h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'p') as $tag) {
					$selected = $tag == $subtitle_tag ? ' selected' : '';
					$image_str .= '<option value="' . $tag . '"' . $selected . '>' . $tag . '</option>';
				}
				$image_str .= '</select>';

				$image_str .= '<input type="text" name="' . $name . '_subtitle[]" value="' . esc_attr(get_post_meta($values, $name . '_subtitle', true)) . '" placeholder="' . esc_attr__('Enter subtitle', 'text-domain') . '" />';

				// Content
				$image_str .= '<label for="' . $name . '_content_tag_' . $values . '">Content tag: </label>';
				$image_str .= '<select name="' . $name . '_content_tag[]" id="' . $name . '_content_tag_' . $values . '">';
				foreach (array('h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'p') as $tag) {
					$selected = $tag == $content_tag ? ' selected' : '';
					$image_str .= '<option value="' . $tag . '"' . $selected . '>' . $tag . '</option>';
				}
				$image_str .= '</select>';

				$image_str .= '<textarea name="' . $name . '_content[]" placeholder="' . esc_attr__('Enter content', 'text-domain') . '">' . esc_html(get_post_meta($values, $name . '_content', true)) . '</textarea>';

				$image_str .= '<input type="text" name="' . $name . '_button_url[]" value="' . esc_attr(get_post_meta($values, $name . '_button_url', true)) . '" placeholder="' . esc_attr__('Enter button URL', 'text-domain') . '" />';

				// Button Text
				$image_str .= '<label for="' . $name . '_button_text_' . $values . '">Button text tag: </label>';
				$image_str .= '<select name="' . $name . '_button_text_tag[]" id="' . $name . '_button_text_' . $values . '">';
				foreach (array('h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'p') as $tag) {
					$selected = $tag == $button_text_tag ? ' selected' : '';
					$image_str .= '<option value="' . $tag . '"' . $selected . '>' . $tag . '</option>';
				}
				$image_str .= '</select>';

				$image_str .= '<input type="text" name="' . $name . '_button_text[]" value="' . esc_attr(get_post_meta($values, $name . '_button_text', true)) . '" placeholder="' . esc_attr__('Enter button text', 'text-domain') . '" />';

				$image_str .= '</div>'; // Close image-details div
				$image_str .= '<i class="dashicons dashicons-no delete-img"></i>';
				$image_str .= '</li>';
			}
		}
	}

	if ( $image_str ) {
		$display = 'inline-block';
	}

	$html = '<div class="multi-upload-medias">';
	$html .= '<ul>' . $image_str . '</ul>';
	$html .= '<a href="#" class="wc_multi_upload_image_button button' . $image . '">' . esc_html__('Add Images', 'lc-slider') . '</a>';
	$html .= '<input type="hidden" class="attachments-ids ' . $name . '" name="' . $name . '" id="' . $name . '" value="' . esc_attr(implode(',', $value)) . '" />';
	$html .= '<a href="#" class="wc_multi_remove_image_button button" style="display:inline-block;display:' . $display . '">' . esc_html__('Remove All Images', 'lc-slider') . '</a>';

	$html .= '</div>'; // Close multi-upload-medias div

	return $html;
}