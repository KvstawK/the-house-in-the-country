<?php
if ( !defined('ABSPATH') ) { die; }
  /**
  * Plugin Name:  atec Cache APCu
  * Plugin URI: https://atec-systems.com/
  * Description: atec Cache APCu – simple & effective object cache
  * Version: 1.1
  * Requires at least: 5.2
  * Requires PHP: 7.2
  * Author: Chris Ahrweiler
  * Author URI: https://atec-systems.com
  * License: GPL2
  * License URI:  https://www.gnu.org/licenses/gpl-2.0.html
  * Text Domain:  atec-cache-apcu
  */

wp_cache_set('atec_WPCA_version','1.1');
global $atec_WPCA_apcu_enabled;
$atec_WPCA_apcu_enabled=extension_loaded('apcu') && apcu_enabled();
$atec_WPCA_plugin_time_pre=microtime(true);
  
if (is_admin()) 
{
  require_once(__DIR__.'/includes/wpca_install.php');
  
  if ($atec_WPCA_apcu_enabled)
  { 
            include_once(__DIR__.'/includes/atec-cache-apcu-register_settings.php');            
            $atec_wpca_admin_footer=new ATEC_wpca_admin_footer();
            if (get_option('atec_WPCA_p_cache_enabled')=='yes') include_once(__DIR__.'/includes/atec-cache-apcu-page-cache-admin-tools.php');
  }
}
else 
{
   if (get_option('atec_WPCA_p_cache_enabled')=='yes') include_once(__DIR__.'/includes/atec-cache-apcu-page-cache.php');
}
?>
