<?php

function rentals_collective_theme_one_register_menus()
{
    register_nav_menus(
        array(
        'main-menu' => esc_html__('Main Menu', 'rentals_collective_theme_one')
        )
    );
}

add_action('init', 'rentals_collective_theme_one_register_menus');


$imgId = 2006;
function rentals_collective_theme_one_submenu_button($imgId, $title)
{
    $button = '<button class="menu-button" aria-expanded="false">';
    $button .= '<span class="screen-reader-text menu-button-show" aria-hidden="false">' . sprintf(esc_html__('Show %s submenu', 'rentals_collective_theme_one'), $title) . '</span>';
    $button .= '<span class="screen-reader-text menu-button-hide" aria-hidden="true">' . sprintf(esc_html__('Hide %s submenu', 'rentals_collective_theme_one'), $title) . '</span>';
    $button .= '<div aria-hidden="true">' . wp_get_attachment_image($imgId, "full") . '</div>';
    $button .= '</button>';
    return $button;
}


function rentals_collective_theme_one_dropdown_icon($title, $item, $args, $depth)
{
    if($args->theme_location == 'main-menu') {
        if(in_array('menu-item-has-children', $item->classes)) {
            if($depth == 0) {
                $title .= rentals_collective_theme_one_submenu_button(2008, $title);
            } else {
                $title .= rentals_collective_theme_one_submenu_button(2007, $title);
            }
        }
    }
    return $title;
}

add_filter('nav_menu_item_title', 'rentals_collective_theme_one_dropdown_icon', 10, 4);


// Aria labels for accessibility
function rentals_collective_theme_one_aria_has_dropdown($atts, $item, $args)
{
    if($args->theme_location == 'main-menu') {
        if(in_array('menu-item-has-children', $item->classes)) {
            $atts['aria-haspopup'] = 'true';
            $atts['aria-expanded'] = 'false';
        }
    }
    return $atts;
}

add_filter('nav_menu_link_attributes', 'rentals_collective_theme_one_aria_has_dropdown', 10, 3);
