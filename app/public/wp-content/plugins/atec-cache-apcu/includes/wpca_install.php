<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class ATEC_wpca_admin_footer
{
          function __construct()
          {	                    
                function atec_wpca_admin_footer_function($content) 
                { 
                          global $atec_WPCA_plugin_time_pre;
                          $content.=' | APCu: OCache <span class="dashicons dashicons-yes-alt"></span>'; 
                          $value = get_option( 'atec_WPCA_p_cache_enabled');
                          if ($value=='yes') $content.=' PCache <span class="dashicons dashicons-yes-alt"></span>';
                          $exec_time = intval((microtime(true)-$atec_WPCA_plugin_time_pre)*1000);
                          $content.=' '.esc_html($exec_time).' ms <span class="dashicons dashicons-clock"></span>';                          
                          if (apcu_exists('atec_WPCA_debug')) 
                          { 
                                    $debug=apcu_fetch('atec_WPCA_debug');
                                    $info=apcu_key_info('atec_WPCA_debug');
                                    $diff=$info['access_time']-$info['mtime'];
                                    $content.=' Debug: '.esc_html($debug); 
                                    if ($diff>30) apcu_delete('atec_WPCA_debug'); 
                          }
                          return $content; 
                };
                add_action('admin_footer_text', 'atec_wpca_admin_footer_function');          
          }
};

function atec_wpca_menu() 
{ 
  global $atec_WPCA_apcu_enabled;
  $pluginDir=plugin_dir_path(__DIR__).'assets/img/';
  $svg=file_get_contents($pluginDir.'atec_wpca_icon.svg');
  $svg=str_replace('#000000','#fff',$svg);
  $base64=base64_encode($svg);
  $menu_slug = 'atec_wpca';
  add_menu_page($menu_slug, 'atec Cache APCu', 'manage_options', __FILE__, 'atec_wpca_settings', 'data:image/svg+xml;base64,'.esc_html($base64));
  if ($atec_WPCA_apcu_enabled)
  {
    add_submenu_page(__FILE__, 'Settings', 'Settings', 'manage_options', __FILE__, 'atec_wpca_settings' );
    add_submenu_page(__FILE__, 'Statistics', 'Statistics', 'read', __FILE__.'/statistics', 'atec_wpca_results' );
    add_submenu_page(__FILE__, 'Groups', 'Groups', 'read', __FILE__.'/groups', 'atec_wpca_groups' );
   }
};
add_action('admin_menu', 'atec_wpca_menu');

function atec_wpca_settings() { include_once(plugin_dir_path(__DIR__).'includes/atec-cache-apcu-settings.php'); }
function atec_wpca_results() { include_once(plugin_dir_path(__DIR__).'includes/atec-cache-apcu-results.php'); }
function atec_wpca_groups() { include_once(plugin_dir_path(__DIR__).'includes/atec-cache-apcu-groups.php'); }

function wpca_plugin_activation() 
{       
        $source = plugin_dir_path(__DIR__) . '/includes/object-cache.php';
        $final_location=WP_CONTENT_DIR.'/object-cache.php';
        $time_file=WP_CONTENT_DIR.'/object-cache.atec-cache-apcu.txt';
        $time=time();

        if (@file_exists($final_location)) 
        {
          $to=WP_CONTENT_DIR.'/@object-cache-'.$time.'.php';
          @rename($final_location,$to);
        };
        @file_put_contents($time_file,$time.PHP_EOL.'APCu object cache installed.'.PHP_EOL.'Do not delete this file when atec Cache APCu plugin is installed!');
        $result=@copy($source,$final_location);
        if (!$result) @unlink($time_file);
        else 
        {
          @chmod( $final_location, 0644 );
          apcu_clear_cache();        
          apcu_store('atec_WPCA_version',wp_cache_get('atec_WPCA_version'));
        };
}

function wpca_plugin_deactivation() 
{
      $time_file=WP_CONTENT_DIR.'/object-cache.atec-cache-apcu.txt';
      if (@file_exists($time_file))
      {
              $final_location=WP_CONTENT_DIR.'/object-cache.php';
              $time=intval(@file_get_contents($time_file));
              @unlink($time_file);
              apcu_clear_cache();
              @unlink($final_location);                            
              if ($time!==0)
              {
                  $from=WP_CONTENT_DIR.'/@object-cache-'.$time.'.php';
                  @rename($from,$final_location);
              }
      }
}

register_activation_hook( plugin_dir_path(__DIR__).'/atec-cache-apcu/atec-cache-apcu.php', 'wpca_plugin_activation' ); 
register_deactivation_hook( plugin_dir_path(__DIR__).'/atec-cache-apcu/atec-cache-apcu.php', 'wpca_plugin_deactivation' );

function atec_wpca_styles()
{
  $pluginDir=plugin_dir_url( __DIR__ ).'assets/css/';
  wp_register_style('atec_wpca_pure', $pluginDir.'pure-min.css' ); wp_enqueue_style( 'atec_wpca_pure' );
  wp_register_style('atec_wpca_style', $pluginDir.'atec_wpca_style.min.css' ); wp_enqueue_style( 'atec_wpca_style' );
}
add_action( 'admin_enqueue_scripts', 'atec_wpca_styles' );
?>