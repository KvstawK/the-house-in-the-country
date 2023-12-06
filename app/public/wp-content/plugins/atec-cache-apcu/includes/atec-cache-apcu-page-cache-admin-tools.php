<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

function atec_WPCA_delete_page($key) { apcu_delete($key); apcu_delete($key.'_h'); }
function atec_WPCA_delete_page_cache()
{
	$apcu_it=new APCUIterator('/atec_WPCA_p/');
	if (!empty($apcu_it)) 
	{ 
		foreach ($apcu_it as $entry) atec_WPCA_delete_page($entry['key']); 
		apcu_store('atec_WPCA_debug','PCache cleared.');
	}
};

if (get_option('atec_WPCA_p_clear_enabled')=='yes')
{
	function atec_WPCA_post_update() { atec_WPCA_delete_page('atec_WPCA_p_'.get_the_ID()); }
	add_action( 'save_post', 'atec_WPCA_post_update' );

	add_action( 'after_switch_theme', 'atec_WPCA_delete_page_cache' );
	add_action( 'activated_plugin', 'atec_WPCA_delete_page_cache');
	add_action( 'deactivated_plugin', 'atec_WPCA_delete_page_cache');

	function atec_WPCA_detect_core_update($ver)
	{
		atec_WPCA_delete_page_cache();
		apcu_store('atec_WPCA_debug','WP update: v'.$ver);
		if (!apcu_exists('atec_WPCA_version')) apcu_store('atec_WPCA_version',wp_cache_get('atec_WPCA_version'));
	}
	add_action( '_core_updated_successfully','atec_WPCA_detect_core_update');
};

function atec_WPCA_admin_bar($wp_admin_bar)
{
	$actual_link=sanitize_url($_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);
	$actual_link=substr($actual_link, 0, strpos($actual_link, "wp-admin"));
	$actual_link.='wp-admin/admin.php?page=atec-cache-apcu/includes/wpca_install.php/statistics&flush=APCu_PCache';
          $args = array('id' => 'atec_WPCA_admin_bar', 'title' => '<span class="ab-icon dashicons dashicons-trash"></span> APCu PCache', 'href' => $actual_link );
    	$wp_admin_bar->add_node($args);
}
add_action('admin_bar_menu', 'atec_WPCA_admin_bar', PHP_INT_MAX);
?>