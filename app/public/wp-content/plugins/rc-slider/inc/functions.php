<?php

//function wp_get_attachment($attachment_id)
//{
//
//    $attachment = get_post($attachment_id);
//    return array(
//        'alt' => get_post_meta($attachment->ID, 'images', true),
//        'caption' => $attachment->post_excerpt,
//        'description' => $attachment->post_content,
//        'href' => get_permalink($attachment->ID),
//        'src' => $attachment->guid,
//        'title' => $attachment->post_title
//    );
//}





//
//
//// Add custom text/textarea attachment field
//function add_custom_text_field_to_attachment_fields_to_edit( $form_fields, $post ) {
//    $text_field = get_post_meta($post->ID, 'text_field', true);
//    $form_fields['text_field'] = array(
//        'label' => esc_html__('Add a link URL', 'rc-slider'),
//        'input' => 'text', // you may also use 'textarea' field
//        'value' => $text_field,
//        'helps' => esc_html__('Add a link to redirect from this attachment (example: https://eadjustments.com)', 'rc-slider')
//    );
//    return $form_fields;
//}
//add_filter('attachment_fields_to_edit', 'add_custom_text_field_to_attachment_fields_to_edit', null, 2);
//
//// Save custom text/textarea attachment field
//function save_custom_text_attachment_field($post, $attachment) {
//    if( isset($attachment['text_field']) ){
//        update_post_meta($post['ID'], 'text_field', sanitize_text_field( $attachment['text_field'] ) );
//    }else{
//        delete_post_meta($post['ID'], 'text_field' );
//    }
//    return $post;
//}
//add_filter('attachment_fields_to_save', 'save_custom_text_attachment_field', null, 2);
