<?php

if(!class_exists('LC_Newsletter_Post_Type')) {
	class LC_Newsletter_Post_Type {
		function __construct() {
			add_action('init', array($this, 'create_post_type'));
		}

		public function create_post_type() {
			$labels = array(
				'name'                  => esc_html_x( 'L.C. Newsletter', 'Post type general name', 'lc-newsletter' ),
				'singular_name'         => esc_html_x( 'L.C. Newsletter', 'Post type singular name', 'lc-newsletter' ),
				'menu_name'             => esc_html_x( 'L.C. Newsletter', 'Admin Menu text', 'lc-newsletter' ),
				'name_admin_bar'        => esc_html_x( 'L.C. Newsletter', 'Add New on Toolbar', 'lc-newsletter' ),
				'add_new'               => esc_html__( 'Add New', 'lc-newsletter' ),
				'add_new_item'          => esc_html__( 'Add New L.C. Newsletter', 'lc-newsletter' ),
				'new_item'              => esc_html__( 'New L.C. Newsletter', 'lc-newsletter' ),
				'edit_item'             => esc_html__( 'Edit L.C. Newsletter', 'lc-newsletter' ),
				'view_item'             => esc_html__( 'View L.C. Newsletter', 'lc-newsletter' ),
				'view_items'            => esc_html__( 'View L.C. Newsletter', 'lc-newsletter' ),
				'all_items'             => esc_html__( 'All L.C. Newsletters', 'lc-newsletter' ),
				'search_items'          => esc_html__( 'Search L.C. Newsletter', 'lc-newsletter' ),
				'parent_item_colon'     => esc_html__( 'Parent L.C. Newsletter:', 'lc-newsletter' ),
				'not_found'             => esc_html__( 'No L.C. Newsletter found.', 'lc-newsletter' ),
				'not_found_in_trash'    => esc_html__( 'No L.C. Newsletter found in Trash.', 'lc-newsletter' ),
				'featured_image'        => esc_html_x( 'L.C. Newsletter Image', 'Overrides the “Featured Image” phrase for this post type. Added in 4.3', 'lc-newsletter' ),
				'set_featured_image'    => esc_html_x( 'Set L.C. Newsletter image', 'Overrides the “Set featured image” phrase for this post type. Added in 4.3', 'lc-newsletter' ),
				'remove_featured_image' => esc_html_x( 'Remove L.C. Newsletter image', 'Overrides the “Remove featured image” phrase for this post type. Added in 4.3', 'lc-newsletter' ),
				'use_featured_image'    => esc_html_x( 'Use as L.C. Newsletter image', 'Overrides the “Use as featured image” phrase for this post type. Added in 4.3', 'lc-newsletter' ),
				'archives'              => esc_html_x( 'L.C. Newsletter archives', 'The post type archive label used in nav menus. Default “Post Archives”. Added in 4.4', 'lc-newsletter' ),
				'insert_into_item'      => esc_html_x( 'Insert into L.C. Newsletter item', 'Overrides the “Insert into post”/”Insert into page” phrase (used when inserting media into a post). Added in 4.4', 'lc-newsletter' ),
				'uploaded_to_this_item' => esc_html_x( 'Uploaded to this L.C. Newsletter', 'Overrides the “Uploaded to this post”/”Uploaded to this page” phrase (used when viewing media attached to a post). Added in 4.4', 'lc-newsletter' ),
				'filter_items_list'     => esc_html_x( 'Filter L.C. Newsletter list', 'Screen reader text for the filter links heading on the post type listing screen. Default “Filter posts list”/”Filter pages list”. Added in 4.4', 'lc-newsletter' ),
				'items_list_navigation' => esc_html_x( 'L.C. Newsletter list navigation', 'Screen reader text for the pagination heading on the post type listing screen. Default “Posts list navigation”/”Pages list navigation”. Added in 4.4', 'lc-newsletter' ),
				'items_list'            => esc_html_x( 'L.C. Newsletter list', 'Screen reader text for the list heading on the post type listing screen. Default “Posts list”/”Pages list”. Added in 4.4', 'lc-newsletter' ),
			);

			$args = array(
				'show_in_rest' => true,
				'labels' => $labels,
				'public' => true,
				'has_archive' => true,
				'menu_icon' => 'dashicons-email',
				'supports' => array('title', /*'editor', 'author', 'thumbnail', 'excerpt', 'comments', 'custom-fields'*/),
				'rewrite' => true,
//                'rewrite' => array('slug' => 'Newsletter'),
//                'register_meta_box_cb' => array($this, 'create_rc_newsletter_meta_boxes')
			);
			register_post_type('lc-newsletter', $args);
		}
	}
}