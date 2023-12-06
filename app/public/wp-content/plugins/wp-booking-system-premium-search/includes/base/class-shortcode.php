<?php

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

class WPBS_S_Shortcodes
{

    /**
     * Constructor
     *
     */
    public function __construct()
    {

        // Register the single search-widget
        add_shortcode('wpbs-search', array(__CLASS__, 'search_widget'));

    }

    /**
     * The callback for the Search Widget shortcode
     *
     * @param array $atts
     *
     */
    public static function search_widget($atts)
    {

        // Shortcode default attributes
        $default_atts = wpbs_s_get_search_widget_default_args();

        // Shortcode attributes
        $args = shortcode_atts($default_atts, $atts);

        $search_widget_outputter = new WPBS_S_Search_Widget_Outputter($args);

        $output = $search_widget_outputter->get_display();

        return apply_filters('wpbs_shortcode_search_widget_output', $output);

    }

}

// Init shortcodes
new WPBS_S_Shortcodes();
