<?php
if (!defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
if (!defined('WP_UNINSTALL_PLUGIN')) die;

delete_option('atec_WPCA_p_cache_enabled');
delete_option('atec_WPCA_p_minify_enabled');
delete_option('atec_WPCA_p_clear_enabled');
delete_option('atec_WPCA_p_gzip_enabled');
?>