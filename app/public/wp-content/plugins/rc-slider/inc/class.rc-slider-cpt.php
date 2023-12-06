<?php

if(!class_exists('RC_Slider_Post_Type')) {
    class RC_Slider_Post_Type {
        function __construct() {
            add_action('init', array($this, 'create_post_type'));
            add_action('save_post', array($this, 'save_post'), 10, 2);
            add_filter('manage_rc-slider_posts_columns', array($this, 'rc_slider_cpt_columns'));
            add_action('manage_rc-slider_posts_custom_column', array($this, 'rc_slider_custom_columns'), 10, 2);
            add_filter('manage_edit-rc-slider_sortable_columns', array($this, 'rc_slider_sortable_columns'));
//            add_action('add_meta_boxes', array($this, 'create_meta_boxes'));
        }

        public function create_post_type() {
            $labels = array(
                'name'                  => esc_html_x( 'RC Slider', 'Post type general name', 'rc-slider' ),
                'singular_name'         => esc_html_x( 'RC Slider Item', 'Post type singular name', 'rc-slider' ),
                'menu_name'             => esc_html_x( 'RC Slider', 'Admin Menu text', 'rc-slider' ),
                'name_admin_bar'        => esc_html_x( 'RC Slider Item', 'Add New on Toolbar', 'rc-slider' ),
                'add_new'               => esc_html__( 'Add New Slider', 'rc-slider' ),
                'add_new_item'          => esc_html__( 'Add New RC Slider Item', 'rc-slider' ),
                'new_item'              => esc_html__( 'New RC Slider Item', 'rc-slider' ),
                'edit_item'             => esc_html__( 'Edit RC Slider Item', 'rc-slider' ),
                'view_item'             => esc_html__( 'View RC Slider Item', 'rc-slider' ),
                'view_items'            => esc_html__( 'View RC Slider Items', 'rc-slider' ),
                'all_items'             => esc_html__( 'All RC Slider Items', 'rc-slider' ),
                'search_items'          => esc_html__( 'Search RC Slider Items', 'rc-slider' ),
                'parent_item_colon'     => esc_html__( 'Parent RC Slider Items:', 'rc-slider' ),
                'not_found'             => esc_html__( 'No RC Slider Items found.', 'rc-slider' ),
                'not_found_in_trash'    => esc_html__( 'No RC Slider Items found in Trash.', 'rc-slider' ),
                'featured_image'        => esc_html_x( 'RC Slider Item Image', 'Overrides the “Featured Image” phrase for this post type. Added in 4.3', 'rc-slider' ),
                'set_featured_image'    => esc_html_x( 'Set RC Slider item image', 'Overrides the “Set featured image” phrase for this post type. Added in 4.3', 'rc-slider' ),
                'remove_featured_image' => esc_html_x( 'Remove RC Slider item image', 'Overrides the “Remove featured image” phrase for this post type. Added in 4.3', 'rc-slider' ),
                'use_featured_image'    => esc_html_x( 'Use as RC Slider item image', 'Overrides the “Use as featured image” phrase for this post type. Added in 4.3', 'rc-slider' ),
                'archives'              => esc_html_x( 'RC Slider archives', 'The post type archive label used in nav menus. Default “Post Archives”. Added in 4.4', 'rc-slider' ),
                'insert_into_item'      => esc_html_x( 'Insert into RC Slider item', 'Overrides the “Insert into post”/”Insert into page” phrase (used when inserting media into a post). Added in 4.4', 'rc-slider' ),
                'uploaded_to_this_item' => esc_html_x( 'Uploaded to this RC Slider item', 'Overrides the “Uploaded to this post”/”Uploaded to this page” phrase (used when viewing media attached to a post). Added in 4.4', 'rc-slider' ),
                'filter_items_list'     => esc_html_x( 'Filter RC Slider items list', 'Screen reader text for the filter links heading on the post type listing screen. Default “Filter posts list”/”Filter pages list”. Added in 4.4', 'rc-slider' ),
                'items_list_navigation' => esc_html_x( 'RC Slider items list navigation', 'Screen reader text for the pagination heading on the post type listing screen. Default “Posts list navigation”/”Pages list navigation”. Added in 4.4', 'rc-slider' ),
                'items_list'            => esc_html_x( 'RC Slider items list', 'Screen reader text for the items list heading on the post type listing screen. Default “Posts list”/”Pages list”. Added in 4.4', 'rc-slider' ),
            );

            $args = array(
                'labels' => $labels,
                'slug' => 'rc-slider',
                'singular' => 'RC Slider',
                'plural'  => 'RC Sliders',
                'menu_position' => 30,
                'text_domain' => 'rc-slider',
                'description' => 'RC Slider Custom Post Type',
                'publicly_queryable' => true,
                'show_ui' => true,
                'show_in_menu' => true,
                'query_var' => true,
                'capability_type' => 'post',
                'hierarchical' => false,
                'exclude_from_search' => false,
                'show_in_rest' => false,
                'public' => true,
                'show_in_nav_menus' => false,
                'show_in_admin_bar' => true,
                'can_export' => true,
                'has_archive' => false,
                'menu_icon' => 'dashicons-format-gallery',
                'supports' => array('title'/*, 'editor', 'author', 'thumbnail', 'excerpt', 'comments'*/),
//                'rewrite' => array('slug' => 'rc-slider'),
                'rewrite' => true,
                'register_meta_box_cb' => array($this, 'create_rc_slider_meta_boxes')
            );
            register_post_type('rc-slider', $args);
        }

        public function rc_slider_cpt_columns($columns) {
            unset($columns['date']);

            $columns['rc_slider_shortcode'] = esc_html__('Slider Shortcode', 'rc-slider');
            $columns['date'] = 'Date';
            return $columns;
        }

        public function rc_slider_custom_columns($column, $post_id) {
            switch ($column) {
                case 'rc_slider_shortcode';
                    echo esc_html('[rc_slider id="' . $post_id . '"]');
                break;
            }
        }

        public function rc_slider_sortable_columns($columns) {
            $columns['rc_slider_shortcode'] = 'rc_Slider_shortcode';
            return $columns;
        }

        public function create_rc_slider_meta_boxes() {
            add_meta_box(
                'rc_slider_meta_box',
                esc_html__('Slides', 'rc-slider'),
                array($this, 'add_slider_inner_meta_box'),
                'rc-slider',
                'normal',
                'high'
            );
        }

        public function add_slider_inner_meta_box($post) {
            require_once (RC_SLIDER_PATH . 'views/rc-slider_meta_box.php');
        }

	    public function save_post($post_id) {

		    if(isset($_POST['rc_slider_nonce'])) {
			    if(!wp_verify_nonce($_POST['rc_slider_nonce'], 'rc_slider_nonce')) {
				    return;
			    }
		    }

		    if(defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
			    return;
		    }

		    if(isset($_POST['post_type']) && $_POST['post_type'] === 'rc-slider' ) {
			    if(!current_user_can('edit_page', $post_id)) {
				    return;
			    } elseif (!current_user_can('edit_post', $post_id)) {
				    return;
			    }
		    }

		    $fields = [
			    'images',
			    'thumbnail',
			    'medium',
			    'large',
			    'full',
			    'arrows',
			    'title_tag',
			    'subtitle_tag',
			    'content_tag',
			    'button_text_tag',
			    'image-link',
			    'image-appear-disappear-slider',
			    'dots',
			    'dots-carousel',
			    'content',
			    'auto-play',
			    '2-slide-carousel',
			    '3-slide-carousel',
			    'gallery',
			    'lightbox',
		    ];

		    foreach ( $fields as $field ) {
			    if ( array_key_exists( $field, $_POST ) ) {
				    update_post_meta( $post_id, $field, sanitize_text_field( $_POST[$field] ) );
			    }  else {
				    update_post_meta( $post_id, $field, '' );
			    }
		    }

		    if ( isset( $_POST['images'] ) ) {
			    $attachment_ids = explode( ',', $_POST['images'] );

			    foreach ( $attachment_ids as $index => $attachment_id ) {
				    if ( isset( $_POST['images_title'][$index] ) ) {
					    update_post_meta( $attachment_id, 'images_title', sanitize_text_field( $_POST['images_title'][$index] ) );
				    }

				    if ( isset( $_POST['images_subtitle'][$index] ) ) {
					    update_post_meta( $attachment_id, 'images_subtitle', sanitize_text_field( $_POST['images_subtitle'][$index] ) );
				    }

				    if ( isset( $_POST['images_content'][$index] ) ) {
					    update_post_meta( $attachment_id, 'images_content', sanitize_text_field( $_POST['images_content'][$index] ) );
				    }

				    if ( isset( $_POST['images_button_url'][$index] ) ) {
					    update_post_meta( $attachment_id, 'images_button_url', sanitize_text_field( $_POST['images_button_url'][$index] ) );
				    }

				    if ( isset( $_POST['images_button_text'][$index] ) ) {
					    update_post_meta( $attachment_id, 'images_button_text', sanitize_text_field( $_POST['images_button_text'][$index] ) );
				    }

				    $title_tag = isset( $_POST['images_title_tag'][$index] ) ? sanitize_text_field( $_POST['images_title_tag'][$index] ) : 'h1';
				    update_post_meta( $attachment_id, 'title_tag', $title_tag );

				    $subtitle_tag = isset( $_POST['images_subtitle_tag'][$index] ) ? sanitize_text_field( $_POST['images_subtitle_tag'][$index] ) : 'h1';
				    update_post_meta( $attachment_id, 'subtitle_tag', $subtitle_tag );

				    $content_tag = isset( $_POST['images_content_tag'][$index] ) ? sanitize_text_field( $_POST['images_content_tag'][$index] ) : 'p';
				    update_post_meta( $attachment_id, 'content_tag', $content_tag );

				    $button_text_tag = isset( $_POST['images_button_text_tag'][$index] ) ? sanitize_text_field( $_POST['images_button_text_tag'][$index] ) : 'p';
				    update_post_meta( $attachment_id, 'button_text_tag', $button_text_tag );
			    }
		    }
	    }

    }
}
