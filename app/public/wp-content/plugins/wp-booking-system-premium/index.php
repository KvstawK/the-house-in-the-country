<?php
/**
 * Plugin Name: WP Booking System
 * Plugin URI: https://www.wpbookingsystem.com/
 * Description: A set-and-forget booking calendar for your rental business.
 * Version: 5.8.12
 * Author: Veribo, Roland Murg
 * Author URI: https://www.wpbookingsystem.com/
 * Text Domain: wp-booking-system
 * Tested up to: 6.1
 * License: GPL2
 *
 * == Copyright ==
 * Copyright 2019 WP Booking System (www.wpbookingsystem.com)
 *
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Main plugin class
 *
 */
class WP_Booking_System
{

    /**
     * The current instance of the object
     *
     * @access private
     * @var    WP_Booking_System
     *
     */
    private static $instance;

    /**
     * A list with the objects that handle database requests
     *
     * @access public
     * @var    array
     *
     */
    public $db = array();

    /**
     * A list with the objects that handle submenu pages
     *
     * @access public
     * @var    array
     *
     */
    public $submenu_pages = array();

    /**
     * Constructor
     *
     */
    public function __construct()
    {

        // Defining constants
        define('WPBS_VERSION', '5.8.12');
        define('WPBS_FILE', __FILE__);
        define('WPBS_BASENAME', plugin_basename(__FILE__));
        define('WPBS_PLUGIN_DIR', plugin_dir_path(__FILE__));
        define('WPBS_PLUGIN_DIR_URL', plugin_dir_url(__FILE__));

        $this->include_files();
        $this->load_db_layer();

        define('WPBS_TRANSLATION_TEXTDOMAIN', 'wp-booking-system');

        // Check if we have the required version of the main plugin.
        add_action('admin_init', array($this, 'requirement_check'), 10);

        // Check if just updated
        add_action('plugins_loaded', array($this, 'update_check'), 20);

        // Load the textdomain and the translation folders
        add_action('plugins_loaded', array($this, 'load_text_domain'), 30);

        // Update the database tables
        add_action('wpbs_update_check', array($this, 'update_database_tables'));

        // Add and remove main plugin page
        add_action('admin_menu', array($this, 'add_main_menu_page'), 10);
        add_action('admin_menu', array($this, 'remove_main_menu_page'), 11);

        // Add submenu pages
        add_action('wp_loaded', array($this, 'load_admin_submenu_pages'), 11);

        // Admin scripts
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_scripts'));

        // Front-end scripts
        add_action('wp_enqueue_scripts', array($this, 'enqueue_front_end_scripts'));

        // Remove plugin query args from the URL
        add_filter('removable_query_args', array($this, 'removable_query_args'));

        // Add a 5 star review call to action to admin footer text
        add_filter('admin_footer_text', array($this, 'admin_footer_text'));

        // Add body class for WP versions greater than 5.3
        add_filter('admin_body_class', array($this, 'admin_body_class'));

        register_activation_hook(__FILE__, array($this, 'set_cron_jobs'));
        register_deactivation_hook(__FILE__, array($this, 'unset_cron_jobs'));

        /**
         * Plugin initialized
         *
         */
        do_action('wpbs_initialized');

    }

    /**
     * Returns an instance of the plugin object
     *
     * @return WP_Booking_System
     *
     */
    public static function instance()
    {

        if (!isset(self::$instance) && !(self::$instance instanceof WP_Booking_System)) {
            self::$instance = new WP_Booking_System;
        }

        return self::$instance;

    }
    public function requirement_check()
    {

        $addons = wpbs_get_addons_list();

        foreach ($addons as $addon) {

            if (!in_array($addon['slug'] . '/index.php', apply_filters('active_plugins', get_option('active_plugins')))) {
                continue;
            }

            $plugin_data = get_plugin_data(plugin_dir_path(__DIR__) . $addon['slug'] . '/index.php');

            // Websites hosted on WordPress.com don't return any plugin data :(
            if (empty($plugin_data['Version'])) {
                continue;
            }

            if (version_compare($addon['minimum_required_version'], $plugin_data['Version']) > 0) {
                wpbs_admin_notices()->register_notice($addon['slug'] . '_minimum_version', '<p>' . sprintf(__('The minimum required version for the <strong>%s</strong> Add-on is %s or greater. Please <a href="%s">update</a> to the latest version.', 'wp-booking-system'), $addon['name'], $addon['minimum_required_version'], admin_url('plugins.php')) . '</p>', 'error');
                wpbs_admin_notices()->display_notice($addon['slug'] . '_minimum_version');
            }

        }

    }

    /**
     * Add the main menu page
     *
     */
    public function add_main_menu_page()
    {

        add_menu_page(__('WP Booking System', 'wp-booking-system'), 'WP Booking System', apply_filters('wpbs_menu_page_capability', 'manage_options'), 'wp-booking-system', '', 'dashicons-calendar-alt');

    }

    /**
     * Remove the main menu page as we will rely only on submenu pages
     *
     */
    public function remove_main_menu_page()
    {

        remove_submenu_page('wp-booking-system', 'wp-booking-system');

    }

    /**
     * Checks to see if the current version of the plugin matches the version
     * saved in the database
     *
     * @return void
     *
     */
    public function update_check()
    {

        $db_version = get_option('wpbs_version', '');
        $do_update = false;

        // If current version number differs from saved version number
        if ($db_version != WPBS_VERSION) {

            $do_update = true;

            // Update the version number in the db
            update_option('wpbs_version', WPBS_VERSION);

            // Add first activation time
            if (get_option('wpbs_first_activation', '') == '') {
                update_option('wpbs_first_activation', time());
            }

        }

        if ($do_update) {

            // Hook for fresh update
            do_action('wpbs_update_check', $db_version);

            // Trigger set cron jobs
            $this->set_cron_jobs();

        }

    }

    /**
     * Creates and updates the database tables
     *
     * @return void
     *
     */
    public function update_database_tables()
    {

        foreach ($this->db as $db_class) {

            $db_class->create_table();

        }

    }

    /**
     * Loads plugin text domain
     *
     */
    public function load_text_domain()
    {

        $locale = apply_filters('plugin_locale', get_locale(), WPBS_TRANSLATION_TEXTDOMAIN);

        // Search for Translation in /wp-content/languages/plugin/
        if (file_exists(trailingslashit(WP_LANG_DIR) . 'plugins' . WPBS_TRANSLATION_TEXTDOMAIN . '-' . $locale . '.mo')) {
            load_plugin_textdomain(WPBS_TRANSLATION_TEXTDOMAIN, false, trailingslashit(WP_LANG_DIR));
        }
        // Search for Translation in /wp-content/languages/
        elseif (file_exists(trailingslashit(WP_LANG_DIR) . WPBS_TRANSLATION_TEXTDOMAIN . '-' . $locale . '.mo')) {
            load_textdomain(WPBS_TRANSLATION_TEXTDOMAIN, trailingslashit(WP_LANG_DIR) . WPBS_TRANSLATION_TEXTDOMAIN . '-' . $locale . '.mo');
            // Search for Translation in /wp-content/plugins/wp-booking-system-premium/languages/
        } else {
            load_plugin_textdomain(WPBS_TRANSLATION_TEXTDOMAIN, false, plugin_basename(dirname(__FILE__)) . '/languages');
        }

    }

    /**
     * Sets an action hook for modules to add custom schedules
     *
     */
    public function set_cron_jobs()
    {

        do_action('wpbs_set_cron_jobs');

    }

    /**
     * Sets an action hook for modules to remove custom schedules
     *
     */
    public function unset_cron_jobs()
    {

        do_action('wpbs_unset_cron_jobs');

    }

    /**
     * Include files
     *
     * @return void
     *
     */
    public function include_files()
    {

        /**
         * Include abstract classes
         *
         */

        require_once WPBS_PLUGIN_DIR . 'includes/abstracts/abstract-class-db.php';
        require_once WPBS_PLUGIN_DIR . 'includes/abstracts/abstract-class-list-table.php';
        require_once WPBS_PLUGIN_DIR . 'includes/abstracts/abstract-class-object-db.php';
        require_once WPBS_PLUGIN_DIR . 'includes/abstracts/abstract-class-object-meta-db.php';
        require_once WPBS_PLUGIN_DIR . 'includes/abstracts/abstract-class-object.php';
        require_once WPBS_PLUGIN_DIR . 'includes/abstracts/abstract-class-submenu-page.php';
        require_once WPBS_PLUGIN_DIR . 'includes/abstracts/abstract-class-admin-dashboard-card.php';

        /**
         * Include all functions.php files from all plugin folders
         *
         */
        $this->_recursively_include_files(WPBS_PLUGIN_DIR . 'includes');

        /**
         * Helper hook to include files early
         *
         */
        do_action('wpbs_include_files');

    }

    /**
     * Recursively includes all functions.php files from the given directory path
     *
     * @param string $dir_path
     *
     */
    protected function _recursively_include_files($dir_path)
    {

        $folders = array_filter(glob($dir_path . '/*'), 'is_dir');

        foreach ($folders as $folder_path) {

            if (file_exists($folder_path . '/functions.php')) {
                include $folder_path . '/functions.php';
            }

            $this->_recursively_include_files($folder_path);

        }

    }

    /**
     * Sets up all objects that handle database related requests and adds them to the
     * $db property of the app
     *
     */
    public function load_db_layer()
    {

        /**
         * Hook to register db class handlers
         * The array element should be 'class_slug' => 'class_name'
         *
         * @param array
         *
         */
        $db_classes = apply_filters('wpbs_register_database_classes', array());

        if (empty($db_classes)) {
            return;
        }

        foreach ($db_classes as $db_class_slug => $db_class_name) {

            $this->db[$db_class_slug] = new $db_class_name;

        }

    }

    /**
     * Sets up all objects that handle submenu pages and adds them to the
     * $submenu_pages property of the app
     *
     */
    public function load_admin_submenu_pages()
    {

        /**
         * Hook to register submenu_pages class handlers
         * The array element should be 'submenu_page_slug' => array( 'class_name' => array(), 'data' => array() )
         *
         * @param array
         *
         */
        $submenu_pages = apply_filters('wpbs_register_submenu_page', array());

        if (empty($submenu_pages)) {
            return;
        }

        foreach ($submenu_pages as $submenu_page_slug => $submenu_page) {

            if (empty($submenu_page['data'])) {
                continue;
            }

            if (empty($submenu_page['data']['page_title']) || empty($submenu_page['data']['menu_title']) || empty($submenu_page['data']['capability']) || empty($submenu_page['data']['menu_slug'])) {
                continue;
            }

            $this->submenu_pages[$submenu_page['data']['menu_slug']] = new $submenu_page['class_name']($submenu_page['data']['page_title'], $submenu_page['data']['menu_title'], $submenu_page['data']['capability'], $submenu_page['data']['menu_slug']);

        }

    }

    /**
     * Enqueue the scripts and style for the admin area
     *
     */
    public function enqueue_admin_scripts($hook)
    {

        if (strpos($hook, 'wp-booking-system') !== false || strpos($hook, 'widgets') !== false || in_array(get_post_type(), array('post', 'page'))) {

            if (!wp_script_is('chosen')) {

                wp_enqueue_script('wpbs-chosen', WPBS_PLUGIN_DIR_URL . 'assets/libs/chosen/chosen.jquery.min.js', array('jquery'), WPBS_VERSION);
                wp_enqueue_style('wpbs-chosen', WPBS_PLUGIN_DIR_URL . 'assets/libs/chosen/chosen.css', array(), WPBS_VERSION);

            }

        }

        if (strpos($hook, 'wp-booking-system') !== false) {

            $settings = get_option('wpbs_settings', array());

            // Edit calendar scripts
            wp_register_script('wpbs-script-edit-calendar', WPBS_PLUGIN_DIR_URL . 'assets/js/script-admin-edit-calendar.js', array('jquery', 'jquery-ui-sortable', 'wp-color-picker', 'jquery-ui-datepicker'), WPBS_VERSION);
            wp_localize_script('wpbs-script-edit-calendar', 'wpbs_plugin_settings', $settings);
            wp_enqueue_script('wpbs-script-edit-calendar');

            // Edit form scripts
            wp_register_script('wpbs-script-edit-form', WPBS_PLUGIN_DIR_URL . 'assets/js/script-admin-edit-form.js', array('jquery', 'jquery-ui-sortable', 'wp-color-picker', 'jquery-ui-datepicker'), WPBS_VERSION);
            wp_enqueue_script('wpbs-script-edit-form');
            wp_localize_script('wpbs-script-edit-form', 'wpbs_localized_data', array('wpbs_plugins_dir_url' => WPBS_PLUGIN_DIR_URL));

            // Edit booking scripts
            wp_register_script('wpbs-script-edit-booking', WPBS_PLUGIN_DIR_URL . 'assets/js/script-admin-edit-booking.js', array('jquery', 'jquery-ui-sortable', 'wp-color-picker', 'jquery-ui-datepicker'), WPBS_VERSION);
            wp_localize_script('wpbs-script-edit-booking', 'wpbs_localized_data_booking', array(
                'wpbs_plugins_dir_url' => WPBS_PLUGIN_DIR_URL,
                'open_bookings_token' => wp_create_nonce('wpbs_open_booking_details'),
                'email_customer_token' => wp_create_nonce('wpbs_booking_email_customer'),
                'change_payment_status' => wp_create_nonce('wpbs_change_payment_status'),
                'booking_notes' => wp_create_nonce('wpbs_booking_notes'),
                'remember_hide_past_option' => wp_create_nonce('wpbs_remember_hide_past_option'),
                'remember_order_option' => wp_create_nonce('wpbs_remember_order_option'),
                'remember_orderby_option' => wp_create_nonce('wpbs_remember_orderby_option'),
                'bookings_per_page' => apply_filters('wpbs_dashboard_bookings_per_page', 5),
            ));
            wp_enqueue_script('wpbs-script-edit-booking');

            // Color picker
            wp_enqueue_style('jquery-style', WPBS_PLUGIN_DIR_URL . 'assets/css/jquery-ui.css', array(), WPBS_VERSION);
            wp_enqueue_style('wp-color-picker');

        }

        if (!empty($_GET['page']) && $_GET['page'] == 'wpbs-upgrader') {

            wp_register_script('wpbs-script-upgrader', WPBS_PLUGIN_DIR_URL . 'assets/js/script-admin-upgrader.js', array('jquery'), WPBS_VERSION);
            wp_enqueue_script('wpbs-script-upgrader');

        }

        if (!empty($_GET['page']) && $_GET['page'] == 'wpbs-settings') {

            wp_register_script('wpbs-script-tools', WPBS_PLUGIN_DIR_URL . 'assets/js/script-admin-tools.js', array('jquery'), WPBS_VERSION);
            wp_enqueue_script('wpbs-script-tools');

        }

        /**
         * Add front-end scripts for Add Booking feature
         */
        if (!empty($_GET['page']) && $_GET['page'] == 'wpbs-calendars') {

            // moment.js
            wp_register_script('wpbs-momentjs', WPBS_PLUGIN_DIR_URL . 'assets/js/moment.min.js', array(), WPBS_VERSION, true);
            wp_enqueue_script('wpbs-momentjs');

            wp_register_script('wpbs-script', WPBS_PLUGIN_DIR_URL . 'assets/js/script-front-end.min.js', array('jquery', 'wpbs-momentjs', 'jquery-ui-datepicker'), WPBS_VERSION, true);
            wp_localize_script('wpbs-script', 'wpbs_ajax', array(
                'ajax_url' => admin_url('admin-ajax.php'),
                'token' => wp_create_nonce('wpbs_form_ajax'),
                'time_format' => wpbs_convert_php_to_moment_format(get_option('date_format')),
            ));
            wp_enqueue_script('wpbs-script');

            wp_register_style('wpbs-style-form', WPBS_PLUGIN_DIR_URL . 'assets/css/style-front-end-form.min.css', array(), WPBS_VERSION);
            wp_enqueue_style('wpbs-style-form');

            do_action('wpbs_enqueue_front_end_scripts');

        }

        wp_enqueue_script( 'postbox' );

        // Plugin styles
        wp_register_style('wpbs-admin-style', WPBS_PLUGIN_DIR_URL . 'assets/css/style-admin.css', array(), WPBS_VERSION);
        wp_enqueue_style('wpbs-admin-style');

        // Plugin script
        wp_register_script('wpbs-admin-script', WPBS_PLUGIN_DIR_URL . 'assets/js/script-admin.js', array('jquery', 'jquery-ui-sortable', 'wp-color-picker', 'jquery-ui-datepicker'), WPBS_VERSION);
        wp_localize_script('wpbs-admin-script', 'wpbs_admin', array(
            'wpbs_enhanced_admin_ui' => wpbs_enhanced_admin_ui(),
        ));
        wp_enqueue_script('wpbs-admin-script');

        // Plugin styles from the front-end. Needed for the actual calendar
        wp_register_style('wpbs-front-end-style', WPBS_PLUGIN_DIR_URL . 'assets/css/style-front-end.min.css', array(), WPBS_VERSION);
        wp_enqueue_style('wpbs-front-end-style');

        // Icon Font
        wp_register_style('wpbs-icons-font', WPBS_PLUGIN_DIR_URL . 'assets/css/icons-font.css', array(), WPBS_VERSION);
        wp_enqueue_style('wpbs-icons-font');

        // Upload logo button
        wp_enqueue_media();

        /**
         * Hook to enqueue scripts immediately after the plugin's scripts
         *
         */
        do_action('wpbs_enqueue_admin_scripts');

    }

    /**
     * Enqueue the scripts and style for the front-end part
     *
     */
    public function enqueue_front_end_scripts()
    {

        $settings = get_option('wpbs_settings', array());

        /**
         * Check if we load scripts & styles on all pages.
         *
         */
        if (isset($settings['custom_enqueue_scripts']) && $settings['custom_enqueue_scripts'] == 'on') {
            global $post;

            $pages = isset($settings['custom_enqueue_scripts_pages']) ? $settings['custom_enqueue_scripts_pages'] : array();
            if (isset($post->ID) && !in_array($post->ID, $pages)) {

                if (apply_filters('wpbs_scripts_not_enqueued_message', true) == false) {
                    return false;
                }

                add_filter('wpbs_shortcode_single_calendar_output', array($this, 'scripts_and_styles_not_enqueued_notification'));
                add_filter('wpbs_shortcode_overview_calendar_output', array($this, 'scripts_and_styles_not_enqueued_notification'));
                add_filter('wpbs_shortcode_search_widget_output', array($this, 'scripts_and_styles_not_enqueued_notification'));
                add_filter('wpbs_shortcode_final_payment_page_output', array($this, 'scripts_and_styles_not_enqueued_notification'));

                return false;
            }

        }

        // Plugin styles
        wp_register_style('wpbs-style', WPBS_PLUGIN_DIR_URL . 'assets/css/style-front-end.min.css', array(), WPBS_VERSION);
        wp_enqueue_style('wpbs-style');

        if (is_rtl()) {
            wp_register_style('wpbs-style-rtl', WPBS_PLUGIN_DIR_URL . 'assets/css/style-front-end-rtl.min.css', array(), WPBS_VERSION);
            wp_enqueue_style('wpbs-style-rtl');
        }

        // Plugin styles
        if (!isset($settings['form_styling']) || $settings['form_styling'] == 'default') {
            wp_register_style('wpbs-style-form', WPBS_PLUGIN_DIR_URL . 'assets/css/style-front-end-form.min.css', array(), WPBS_VERSION);
            wp_enqueue_style('wpbs-style-form');
        }

        if (isset($settings['form_styled_phone_input_toggle']) && $settings['form_styled_phone_input_toggle'] == 'on') {
            wp_register_script('wpbs-intl-tel-input', WPBS_PLUGIN_DIR_URL . 'assets/libs/intl-tel-input/js/intlTelInput.min.js', array('jquery'), WPBS_VERSION, true);
            wp_enqueue_script('wpbs-intl-tel-input');

            wp_enqueue_style('wpbs-intl-tel-input', WPBS_PLUGIN_DIR_URL . 'assets/libs/intl-tel-input/css/intlTelInput.min.css', array(), WPBS_VERSION);
        }

        // moment.js
        wp_register_script('wpbs-momentjs', WPBS_PLUGIN_DIR_URL . 'assets/js/moment.min.js', array(), WPBS_VERSION, true);
        wp_enqueue_script('wpbs-momentjs');

        // Plugin script
        wp_register_script('wpbs-script', WPBS_PLUGIN_DIR_URL . 'assets/js/script-front-end.min.js', array('jquery', 'wpbs-momentjs', 'jquery-ui-datepicker'), WPBS_VERSION, true);
        wp_localize_script('wpbs-script', 'wpbs_ajax', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'plugins_dir' => WPBS_PLUGIN_DIR_URL,
            'token' => wp_create_nonce('wpbs_form_ajax'),
            'time_format' => wpbs_convert_php_to_moment_format(get_option('date_format')),
            'permalink' => get_permalink(),
            'wpbs_iti' => (isset($settings['form_styled_phone_input_toggle']) && $settings['form_styled_phone_input_toggle'] == 'on' ? true : false),
            'wpbs_iti_default_country' => (isset($settings['form_styled_phone_input_default_country']) && !empty($settings['form_styled_phone_input_default_country']) ? $settings['form_styled_phone_input_default_country'] : ''),
            'wpbs_iti_country_lookup' => (isset($settings['form_styled_phone_input_country_lookup']) && !empty($settings['form_styled_phone_input_country_lookup']) ? $settings['form_styled_phone_input_country_lookup'] : ''),
            'scroll_animation_offset' => apply_filters('wpbs_form_scroll_animation_offset', 0),
        ));

        wp_enqueue_script('wpbs-script');

        // Google reCaptcha V2
        if (wpbs_get_recaptcha_type() == 'v2' && wpbs_get_recaptcha_keys() !== false) {
            wp_register_script('google-recaptcha', 'https://www.google.com/recaptcha/api.js?hl=' . wpbs_get_locale(), array(), null, false);
        }

        if (wpbs_get_recaptcha_type() == 'v3' && wpbs_get_recaptcha_keys() !== false) {
            $recaptcha_keys = wpbs_get_recaptcha_keys();
            wp_register_script('google-recaptcha', 'https://www.google.com/recaptcha/api.js?render=' . $recaptcha_keys['site_key'], array(), null, false);
            wp_enqueue_script('google-recaptcha');
        }

        /**
         * Hook to enqueue scripts immediately after the plugin's scripts
         *
         */
        do_action('wpbs_enqueue_front_end_scripts');

    }

    /**
     * Notification if scripts and styles are not enqueued on a page
     *
     */
    public function scripts_and_styles_not_enqueued_notification()
    {
        return '<div style="display:inline-block; margin:10px 0; background-color:#d63638; color:#fff; padding: 10px;">WP Booking System: Scripts and Styles are not enqueued on this page. <a style="color:#fff;" href="https://www.wpbookingsystem.com/documentation/scripts-and-styles-are-not-enqueued-on-this-page/" target="_blank">Click here to find out how to fix this.</a></div>';
    }

    /**
     * Removes the query variables from the URL upon page load
     *
     */
    public function removable_query_args($args = array())
    {

        $args[] = 'wpbs_message';

        return $args;

    }

    /**
     * Add custom class to the <body> tag in WP Admin
     *
     * @param string $text
     *
     */
    public function admin_body_class($classes)
    {
        if (version_compare(get_bloginfo('version'), '5.3', '>=')) {
            $classes .= ' wpbs-greater-5-3';
        }
        return $classes;
    }

    /**
     * Replace admin footer text with a rate plugin message
     *
     * @param string $text
     *
     */
    public function admin_footer_text($text)
    {

        return $text;

    }

}

/**
 * Returns the WP Booking System instanced object
 *
 */
function wp_booking_system()
{

    return WP_Booking_System::instance();

}

// Let's get the party started
wp_booking_system();
