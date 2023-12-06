<?php
if (!defined('ABSPATH')) exit; // Exit if accessed directly

add_action('init', function () {

    if (!class_exists('\Bricks\Elements')) {
        return;
    }

    $element_files = [
        __DIR__ . '/widgets/search-widget.php',
    ];

    foreach ($element_files as $file) {
        \Bricks\Elements::register_element($file);
    }
    
}, 11);
