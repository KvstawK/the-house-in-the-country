<?php

function rentals_collective_theme_one_sidebar()
{
    register_sidebar(
        array(
        'id' => 'primary-sidebar',
        'name' => esc_html__('Primary Sidebar', 'rentals_collective_theme_one'),
        'description' => esc_html__('The sidebar for the blog page', 'rentals_collective_theme_one'),
        'before_widget' => '<section id="%1$s" class="sidebar-item %2$s">',
        'after_widget' => '</section>'
        )
    );
}

add_action('widgets_init', 'rentals_collective_theme_one_sidebar');
