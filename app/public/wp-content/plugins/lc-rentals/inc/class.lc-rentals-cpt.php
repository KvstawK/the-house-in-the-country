<?php

if(!class_exists('LC_Rentals_Post_Type')) {
    class LC_Rentals_Post_Type {
        function __construct() {
            add_action('init', array($this, 'create_post_type'));
            add_action('init', array($this, 'lc_rentals_category_taxonomy'));
            add_action('init', array($this, 'lc_rentals_location_taxonomy'));
            add_action( 'save_post', array($this, 'lc_rentals_save_post_data'));
        }

        public function create_post_type() {
            $labels = array(
                'name'                  => esc_html_x( 'LC Rentals', 'Post type general name', 'lc-rentals' ),
                'singular_name'         => esc_html_x( 'LC Rental', 'Post type singular name', 'lc-rentals' ),
                'menu_name'             => esc_html_x( 'LC Rentals', 'Admin Menu text', 'lc-rentals' ),
                'name_admin_bar'        => esc_html_x( 'LC Rentals', 'Add New on Toolbar', 'lc-rentals' ),
                'add_new'               => esc_html__( 'Add New', 'lc-rentals' ),
                'add_new_item'          => esc_html__( 'Add New LC Rental', 'lc-rentals' ),
                'new_item'              => esc_html__( 'New LC Rental', 'lc-rentals' ),
                'new_item_name'              => esc_html__( 'New LC Rental Name', 'lc-rentals' ),
                'edit_item'             => esc_html__( 'Edit LC Rental', 'lc-rentals' ),
                'update_item'             => esc_html__( 'Update LC Rental', 'lc-rentals' ),
                'view_item'             => esc_html__( 'View LC Rental', 'lc-rentals' ),
                'view_items'            => esc_html__( 'View LC Rental', 'lc-rentals' ),
                'all_items'             => esc_html__( 'All LC Rentals', 'lc-rentals' ),
                'search_items'          => esc_html__( 'Search LC Rentals', 'lc-rentals' ),
                'parent_item'     => esc_html__( 'Parent LC Rental', 'lc-rentals' ),
                'parent_item_colon'     => esc_html__( 'Parent LC Rental:', 'lc-rentals' ),
                'not_found'             => esc_html__( 'No LC Rental found.', 'lc-rentals' ),
                'not_found_in_trash'    => esc_html__( 'No LC Rental found in Trash.', 'lc-rentals' ),
                'featured_image'        => esc_html_x( 'LC Rental Image', 'Overrides the “Featured Image” phrase for this post type. Added in 4.3', 'lc-rentals' ),
                'set_featured_image'    => esc_html_x( 'Set LC Rental image', 'Overrides the “Set featured image” phrase for this post type. Added in 4.3', 'lc-rentals' ),
                'remove_featured_image' => esc_html_x( 'Remove LC Rental image', 'Overrides the “Remove featured image” phrase for this post type. Added in 4.3', 'lc-rentals' ),
                'use_featured_image'    => esc_html_x( 'Use as LC Rental image', 'Overrides the “Use as featured image” phrase for this post type. Added in 4.3', 'lc-rentals' ),
                'archives'              => esc_html_x( 'LC Rental archives', 'The post type archive label used in nav menus. Default “Post Archives”. Added in 4.4', 'lc-rentals' ),
                'insert_into_item'      => esc_html_x( 'Insert into LC Rental item', 'Overrides the “Insert into post”/”Insert into page” phrase (used when inserting media into a post). Added in 4.4', 'lc-rentals' ),
                'uploaded_to_this_item' => esc_html_x( 'Uploaded to this LC Rental', 'Overrides the “Uploaded to this post”/”Uploaded to this page” phrase (used when viewing media attached to a post). Added in 4.4', 'lc-rentals' ),
                'filter_items_list'     => esc_html_x( 'Filter LC Rentals list', 'Screen reader text for the filter links heading on the post type listing screen. Default “Filter posts list”/”Filter pages list”. Added in 4.4', 'lc-rentals' ),
                'items_list_navigation' => esc_html_x( 'LC Rentals list navigation', 'Screen reader text for the pagination heading on the post type listing screen. Default “Posts list navigation”/”Pages list navigation”. Added in 4.4', 'lc-rentals' ),
                'items_list'            => esc_html_x( 'LC Rentals list', 'Screen reader text for the list heading on the post type listing screen. Default “Posts list”/”Pages list”. Added in 4.4', 'lc-rentals' ),
            );

            $args = array(
                'show_in_rest' => true,
                'labels' => $labels,
                'public' => true,
                'has_archive' => true,
                'menu_icon' => 'dashicons-admin-home',
                'supports' => array('title', 'editor', 'thumbnail', 'excerpt', 'comments', 'custom-fields'),
//                'rewrite' => array('slug' => 'lc-rental'),
                'rewrite' => true,
                'register_meta_box_cb' => array($this, 'create_lc_rentals_meta_boxes')
            );
            register_post_type('lc-rentals', $args);
        }

        public function lc_rentals_category_taxonomy() {
            $labels = [
                'name'                  => esc_html_x( 'LC Rentals Category', 'Post type general name', 'lc-rentals' ),
                'singular_name'         => esc_html_x( 'LC Rental Category', 'Post type singular name', 'lc-rentals' ),
                'all_items'             => esc_html__( 'All LC Rentals Categories', 'lc-rentals' ),
                'search_items'          => esc_html__( 'Search LC Rentals Categories', 'lc-rentals' ),
                'parent_item'     => esc_html__( 'Parent LC Rental Category', 'lc-rentals' ),
                'parent_item_colon'     => esc_html__( 'Parent LC Rental Category:', 'lc-rentals' ),
                'edit_item'             => esc_html__( 'Edit LC Rentals Category', 'lc-rentals' ),
                'update_item'             => esc_html__( 'Update LC Rentals Category', 'lc-rentals' ),
                'add_new_item'               => esc_html__( 'Add New LC Rentals Category', 'lc-rentals' ),
                'new_item_name'              => esc_html__( 'New LC Rentals Category Name', 'lc-rentals' ),
                'menu_name'             => esc_html__( 'LC Rentals Categories', 'lc-rentals' )
            ];
            $args = array(
                'labels' => $labels,
                'show_in_rest' => true,
                'hierarchical' => true,
                'show_admin_column' => true,
                'supports' => array('thumbnail')
            );

            register_taxonomy('rental-category', ['lc-rentals'], $args);
        }

        public function lc_rentals_location_taxonomy() {
            $labels = [
                'name'                  => esc_html_x( 'LC Rentals Location', 'Post type general name', 'lc-rentals' ),
                'singular_name'         => esc_html_x( 'LC Rental Location', 'Post type singular name', 'lc-rentals' ),
                'all_items'             => esc_html__( 'All LC Rentals Locations', 'lc-rentals' ),
                'search_items'          => esc_html__( 'Search LC Rentals Locations', 'lc-rentals' ),
                'parent_item'     => esc_html__( 'Parent LC Rental Location', 'lc-rentals' ),
                'parent_item_colon'     => esc_html__( 'Parent LC Rental Location:', 'lc-rentals' ),
                'edit_item'             => esc_html__( 'Edit LC Rentals Location', 'lc-rentals' ),
                'update_item'             => esc_html__( 'Update LC Rentals Location', 'lc-rentals' ),
                'add_new_item'               => esc_html__( 'Add New LC Rentals Location', 'lc-rentals' ),
                'new_item_name'              => esc_html__( 'New LC Rentals Location Name', 'lc-rentals' ),
                'menu_name'             => esc_html__( 'LC Rentals Locations', 'lc-rentals' )
            ];
            $args = array(
                'labels' => $labels,
                'show_in_rest' => true,
                'hierarchical' => true,
                'show_admin_column' => true,
                'supports' => array('thumbnail', 'comments')
            );

            register_taxonomy('rental-location', ['lc-rentals'], $args);
        }

        public function create_lc_rentals_meta_boxes() {
            $screens = ['lc-rentals'];
            foreach ( $screens as $screen ) {
                add_meta_box(
                    'lc_rentals_meta_box',
                    esc_html__('LC Rental Info', 'lc-rentals'),
                    array($this, 'add_rentals_inner_meta_box'),
                    $screen,
                    'normal',
                    'high'
                );
            }
        }

        function add_rentals_inner_meta_box( $post ) {
            $values = get_post_meta( $post->ID, 'lc_rentals_meta_key', true );
            ?>
            <div class="lc-rentals__field-container">
                <input type="hidden" name="lc_rentals_nonce" value="<?php echo wp_create_nonce('lc_rentals_nonce') ?>">
                <div class="lc-rentals__field-container-inputs">
                    <div class="lc-rentals__field-container-inputs-item">
                        <label for="persons"><?php esc_html_e('Number Of Guests: ', 'lc-rentals'); ?></label>
                        <input type="text" name="persons" id="persons" class="postbox" value="<?php echo esc_attr( get_post_meta( get_the_ID(), 'persons', true ) ); ?>" placeholder="<?php esc_html_e("Enter a number", 'lc-rentals'); ?>">
                    </div>
                    <div class="lc-rentals__field-container-inputs-item">
                        <label for="double"><?php esc_html_e('Number Of Double Beds: ', 'lc-rentals'); ?></label>
                        <input type="text" name="double" id="double" class="postbox" value="<?php echo esc_attr( get_post_meta( get_the_ID(), 'double', true ) ); ?>" placeholder="<?php esc_html_e("Enter a number", 'lc-rentals'); ?>">
                    </div>
                    <div class="lc-rentals__field-container-inputs-item">
                        <label for="single"><?php esc_html_e('Number Of Single Beds: ', 'lc-rentals'); ?></label>
                        <input type="text" name="single" id="single" class="postbox" value="<?php echo esc_attr( get_post_meta( get_the_ID(), 'single', true ) ); ?>" placeholder="<?php esc_html_e("Enter a number", 'lc-rentals'); ?>">
                    </div>
                    <div class="lc-rentals__field-container-inputs-item">
                        <label for="bathrooms"><?php esc_html_e('Number Of Bathrooms: ', 'lc-rentals'); ?></label>
                        <input type="text" name="bathrooms" id="bathrooms" class="postbox" value="<?php echo esc_attr( get_post_meta( get_the_ID(), 'bathrooms', true ) ); ?>" placeholder="<?php esc_html_e("Enter a number", 'lc-rentals'); ?>">
                    </div>
                    <div class="lc-rentals__field-container-inputs-item">
                        <label for="meters"><?php esc_html_e('Square meters: ', 'lc-rentals'); ?></label>
                        <input type="text" name="meters" id="meters" class="postbox" value="<?php echo esc_attr( get_post_meta( get_the_ID(), 'meters', true ) ); ?>" placeholder="<?php esc_html_e("Enter a number", 'lc-rentals'); ?>">
                    </div>
                    <div class="lc-rentals__field-container-inputs-item">
                        <label for="price"><?php esc_html_e('Price: ', 'lc-rentals'); ?></label>
                        <input type="text" name="price" id="price" class="postbox" value="<?php echo esc_attr( get_post_meta( get_the_ID(), 'price', true ) ); ?>" placeholder="<?php esc_html_e("Enter a number", 'lc-rentals'); ?>">
                    </div>
                </div>
                <div class="lc-rentals__field-container-checkboxes">
                    <div class="lc-rentals__field-container-checkboxes-item">
                        <label for="essentials"><?php esc_html_e('Essentials: ', 'lc-rentals'); ?></label>
                        <?php
                        $checked = get_post_meta( $post->ID, 'essentials', true ) == '1' ? 'checked' : '';
                        echo '<input type="checkbox" class="checkbox" id="essentials" name="essentials" ' . $checked . ' />';
                        ?>
                    </div>
                    <div class="lc-rentals__field-container-checkboxes-item">
                        <label for="pool"><?php esc_html_e('Swimming-pool: ', 'lc-rentals'); ?></label>
	                    <?php
	                    $checked = get_post_meta( $post->ID, 'pool', true ) == '1' ? 'checked' : '';
	                    echo '<input type="checkbox" class="checkbox" id="pool" name="pool" ' . $checked . ' />';
	                    ?>
                    </div>
                    <div class="lc-rentals__field-container-checkboxes-item">
                        <label for="gym"><?php esc_html_e('Gym: ', 'lc-rentals'); ?></label>
	                    <?php
	                    $checked = get_post_meta( $post->ID, 'gym', true ) == '1' ? 'checked' : '';
	                    echo '<input type="checkbox" class="checkbox" id="gym" name="gym" ' . $checked . ' />';
	                    ?>
                    </div>
                    <div class="lc-rentals__field-container-checkboxes-item">
                        <label for="kids"><?php esc_html_e('Kids friendly: ', 'lc-rentals'); ?></label>
	                    <?php
	                    $checked = get_post_meta( $post->ID, 'kids', true ) == '1' ? 'checked' : '';
	                    echo '<input type="checkbox" class="checkbox" id="kids" name="kids" ' . $checked . ' />';
	                    ?>
                    </div>
                    <div class="lc-rentals__field-container-checkboxes-item">
                        <label for="jacuzzi"><?php esc_html_e('Jacuzzi: ', 'lc-rentals'); ?></label>
	                    <?php
	                    $checked = get_post_meta( $post->ID, 'jacuzzi', true ) == '1' ? 'checked' : '';
	                    echo '<input type="checkbox" class="checkbox" id="jacuzzi" name="jacuzzi" ' . $checked . ' />';
	                    ?>
                    </div>
                    <div class="lc-rentals__field-container-checkboxes-item">
                        <label for="ac"><?php esc_html_e('Air Conditioning: ', 'lc-rentals'); ?></label>
	                    <?php
	                    $checked = get_post_meta( $post->ID, 'ac', true ) == '1' ? 'checked' : '';
	                    echo '<input type="checkbox" class="checkbox" id="ac" name="ac" ' . $checked . ' />';
	                    ?>
                    </div>
                    <div class="lc-rentals__field-container-checkboxes-item">
                        <label for="cleaning-products"><?php esc_html_e('Cleaning Products: ', 'lc-rentals'); ?></label>
	                    <?php
	                    $checked = get_post_meta( $post->ID, 'cleaning-products', true ) == '1' ? 'checked' : '';
	                    echo '<input type="checkbox" class="checkbox" id="cleaning-products" name="cleaning-products" ' . $checked . ' />';
	                    ?>
                    </div>
                    <div class="lc-rentals__field-container-checkboxes-item">
                        <label for="cooking-basics"><?php esc_html_e('Cooking Basics: ', 'lc-rentals'); ?></label>
	                    <?php
	                    $checked = get_post_meta( $post->ID, 'cooking-basics', true ) == '1' ? 'checked' : '';
	                    echo '<input type="checkbox" class="checkbox" id="cooking-basics" name="cooking-basics" ' . $checked . ' />';
	                    ?>
                    </div>
                    <div class="lc-rentals__field-container-checkboxes-item">
                        <label for="workspace"><?php esc_html_e('Dedicated Workspace: ', 'lc-rentals'); ?></label>
	                    <?php
	                    $checked = get_post_meta( $post->ID, 'workspace', true ) == '1' ? 'checked' : '';
	                    echo '<input type="checkbox" class="checkbox" id="workspace" name="workspace" ' . $checked . ' />';
	                    ?>
                    </div>
                    <div class="lc-rentals__field-container-checkboxes-item">
                        <label for="dishes"><?php esc_html_e('Dishes And Utensils: ', 'lc-rentals'); ?></label>
	                    <?php
	                    $checked = get_post_meta( $post->ID, 'dishes', true ) == '1' ? 'checked' : '';
	                    echo '<input type="checkbox" class="checkbox" id="dishes" name="dishes" ' . $checked . ' />';
	                    ?>
                    </div>
                    <div class="lc-rentals__field-container-checkboxes-item">
                        <label for="dryer"><?php esc_html_e('Clothes Dryer: ', 'lc-rentals'); ?></label>
	                    <?php
	                    $checked = get_post_meta( $post->ID, 'dryer', true ) == '1' ? 'checked' : '';
	                    echo '<input type="checkbox" class="checkbox" id="dryer" name="dryer" ' . $checked . ' />';
	                    ?>
                    </div>
                    <div class="lc-rentals__field-container-checkboxes-item">
                        <label for="washing-machine"><?php esc_html_e('Washing Machine: ', 'lc-rentals'); ?></label>
	                    <?php
	                    $checked = get_post_meta( $post->ID, 'washing-machine', true ) == '1' ? 'checked' : '';
	                    echo '<input type="checkbox" class="checkbox" id="washing-machine" name="washing-machine" ' . $checked . ' />';
	                    ?>
                    </div>
                    <div class="lc-rentals__field-container-checkboxes-item">
                        <label for="hair-dryer"><?php esc_html_e('Hair Dryer: ', 'lc-rentals'); ?></label>
	                    <?php
	                    $checked = get_post_meta( $post->ID, 'hair-dryer', true ) == '1' ? 'checked' : '';
	                    echo '<input type="checkbox" class="checkbox" id="hair-dryer" name="hair-dryer" ' . $checked . ' />';
	                    ?>
                    </div>
                    <div class="lc-rentals__field-container-checkboxes-item">
                        <label for="heating"><?php esc_html_e('Heater: ', 'lc-rentals'); ?></label>
	                    <?php
	                    $checked = get_post_meta( $post->ID, 'heating', true ) == '1' ? 'checked' : '';
	                    echo '<input type="checkbox" class="checkbox" id="heating" name="heating" ' . $checked . ' />';
	                    ?>
                    </div>
                    <div class="lc-rentals__field-container-checkboxes-item">
                        <label for="kitchen"><?php esc_html_e('Kitchen: ', 'lc-rentals'); ?></label>
	                    <?php
	                    $checked = get_post_meta( $post->ID, 'kitchen', true ) == '1' ? 'checked' : '';
	                    echo '<input type="checkbox" class="checkbox" id="kitchen" name="kitchen" ' . $checked . ' />';
	                    ?>
                    </div>
                    <div class="lc-rentals__field-container-checkboxes-item">
                        <label for="tv"><?php esc_html_e('TV: ', 'lc-rentals'); ?></label>
	                    <?php
	                    $checked = get_post_meta( $post->ID, 'tv', true ) == '1' ? 'checked' : '';
	                    echo '<input type="checkbox" class="checkbox" id="tv" name="tv" ' . $checked . ' />';
	                    ?>
                    </div>
                    <div class="lc-rentals__field-container-checkboxes-item">
                        <label for="dishwasher"><?php esc_html_e('Dishwasher: ', 'lc-rentals'); ?></label>
	                    <?php
	                    $checked = get_post_meta( $post->ID, 'dishwasher', true ) == '1' ? 'checked' : '';
	                    echo '<input type="checkbox" class="checkbox" id="dishwasher" name="dishwasher" ' . $checked . ' />';
	                    ?>
                    </div>
                    <div class="lc-rentals__field-container-checkboxes-item">
                        <label for="wifi"><?php esc_html_e('WiFi: ', 'lc-rentals'); ?></label>
	                    <?php
	                    $checked = get_post_meta( $post->ID, 'wifi', true ) == '1' ? 'checked' : '';
	                    echo '<input type="checkbox" class="checkbox" id="wifi" name="wifi" ' . $checked . ' />';
	                    ?>
                    </div>
                    <div class="lc-rentals__field-container-checkboxes-item">
                        <label for="bathtub"><?php esc_html_e('Bathtub: ', 'lc-rentals'); ?></label>
	                    <?php
	                    $checked = get_post_meta( $post->ID, 'bathtub', true ) == '1' ? 'checked' : '';
	                    echo '<input type="checkbox" class="checkbox" id="bathtub" name="bathtub" ' . $checked . ' />';
	                    ?>
                    </div>
                    <div class="lc-rentals__field-container-checkboxes-item">
                        <label for="shower"><?php esc_html_e('Shower: ', 'lc-rentals'); ?></label>
	                    <?php
	                    $checked = get_post_meta( $post->ID, 'shower', true ) == '1' ? 'checked' : '';
	                    echo '<input type="checkbox" class="checkbox" id="shower" name="shower" ' . $checked . ' />';
	                    ?>
                    </div>
                    <div class="lc-rentals__field-container-checkboxes-item">
                        <label for="shampoo"><?php esc_html_e('Shampoo: ', 'lc-rentals'); ?></label>
	                    <?php
	                    $checked = get_post_meta( $post->ID, 'shampoo', true ) == '1' ? 'checked' : '';
	                    echo '<input type="checkbox" class="checkbox" id="shampoo" name="shampoo" ' . $checked . ' />';
	                    ?>
                    </div>
                    <div class="lc-rentals__field-container-checkboxes-item">
                        <label for="hangers"><?php esc_html_e('Hangers: ', 'lc-rentals'); ?></label>
	                    <?php
	                    $checked = get_post_meta( $post->ID, 'hangers', true ) == '1' ? 'checked' : '';
	                    echo '<input type="checkbox" class="checkbox" id="hangers" name="hangers" ' . $checked . ' />';
	                    ?>
                    </div>
                    <div class="lc-rentals__field-container-checkboxes-item">
                        <label for="iron"><?php esc_html_e('Iron: ', 'lc-rentals'); ?></label>
	                    <?php
	                    $checked = get_post_meta( $post->ID, 'iron', true ) == '1' ? 'checked' : '';
	                    echo '<input type="checkbox" class="checkbox" id="iron" name="iron" ' . $checked . ' />';
	                    ?>
                    </div>
                    <div class="lc-rentals__field-container-checkboxes-item">
                        <label for="safe-box"><?php esc_html_e('Safe Box: ', 'lc-rentals'); ?></label>
	                    <?php
	                    $checked = get_post_meta( $post->ID, 'safe-box', true ) == '1' ? 'checked' : '';
	                    echo '<input type="checkbox" class="checkbox" id="safe-box" name="safe-box" ' . $checked . ' />';
	                    ?>
                    </div>
                </div>
            </div>
            <?php
        }

	    function lc_rentals_save_post_data( $post_id ) {
		    if ( isset( $_POST['lc_rentals_nonce'] ) ) {
			    if ( ! wp_verify_nonce( $_POST['lc_rentals_nonce'], 'lc_rentals_nonce' ) ) {
				    return;
			    }
		    }

		    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			    return;
		    }

		    if ( $parent_id = wp_is_post_revision( $post_id ) ) {
			    $post_id = $parent_id;
		    }

		    $fields = ['persons', 'double', 'single', 'bathrooms', 'price', 'meters'];
		    $checkbox_fields = ['jacuzzi', 'pool', 'gym', 'kids', 'ac', 'essentials', 'cleaning-products', 'cooking-basics', 'workspace', 'dishes', 'dryer', 'washing-machine', 'hair-dryer', 'heating', 'kitchen', 'tv', 'dishwasher', 'wifi', 'bathtub', 'shower', 'shampoo', 'hangers', 'iron', 'safe-box'];

		    // Save regular fields
		    foreach ( $fields as $field ) {
			    if ( array_key_exists( $field, $_POST ) ) {
				    update_post_meta( $post_id, $field, sanitize_text_field( $_POST[ $field ] ) );
			    } else {
				    update_post_meta( $post_id, $field, '' );
			    }
		    }

		    // Save checkbox fields
		    foreach ( $checkbox_fields as $field ) {
			    $value = isset( $_POST[ $field ] ) ? '1' : '0';
			    update_post_meta( $post_id, $field, $value );
		    }
	    }
    }
}