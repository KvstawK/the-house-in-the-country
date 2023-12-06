<?php

function rentals_collective_theme_two_assets()
{
	wp_enqueue_style('rentals_collective_theme_two_stylesheet', get_template_directory_uri() . '/assets/dist/css/styles.css', array(), '1.0.0', 'all');

    wp_enqueue_script('rentals_collective_theme_two_script', get_template_directory_uri() . '/assets/dist/js/app.js', array(), '1.0.0', true);

	if(is_singular() && comments_open() && get_option('thread_comments')) {
        wp_enqueue_script('comment-reply');
    }
}

add_action('wp_enqueue_scripts', 'rentals_collective_theme_two_assets');


function rentals_collective_theme_two_admin_assets()
{

    wp_enqueue_style('rentals_collective_theme_two_admin_stylesheet', get_template_directory_uri() . '/assets/dist/css/admin.css', array(), '1.0.0', 'all');

    wp_enqueue_script('rentals_collective_theme_two_admin_script', get_template_directory_uri() . '/assets/dist/js/admin.js', array('jquery'), '1.0.0', true);

    wp_enqueue_media();
}

add_action('admin_enqueue_scripts', 'rentals_collective_theme_two_admin_assets');
