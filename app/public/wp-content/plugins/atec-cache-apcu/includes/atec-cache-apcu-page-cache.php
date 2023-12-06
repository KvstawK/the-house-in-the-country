<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

 function atec_WPCA_page_buffer_start() 
 { 
	 global $atec_WPCA_page_time_pre;
	 $atec_WPCA_page_time_pre=microtime(true);
	 $key='atec_WPCA_p_'.get_the_ID();
	 if (apcu_exists($key))
	 {
		 $arr=apcu_fetch($key); $id=$arr[0]; $time=$arr[1];
		 if ($time==get_post_modified_time('U',false,get_the_ID()))
		 {
			apcu_inc($key.'_h');
	         		ob_start();
			ob_end_clean();
		        	@header('X-Cache-Type: APCu v'.apcu_fetch('atec_WPCA_version'));
			@header('Content-Type: text/html');

			if (get_option('atec_WPCA_p_minify_enabled')=='yes' && substr_count($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip'))
			{
				ini_set('zlib.output_compression','Off');
				@header('Content-Encoding: gzip');
				@header('X-Cache: HIT/GZIP');
				@header( 'Content-Length: ' . esc_html($arr[4]) );
				@header('Vary: Accept-Encoding');
				echo $arr[3];
			}
			else
			{
				@header('X-Cache: HIT');
				$buffer=$arr[2];
				$exec_time = intval((microtime(true)-$atec_WPCA_page_time_pre)*1000);
				$buffer.=PHP_EOL.'<span style="display:none;">CACHED: atec Cache APCu v'.esc_html(apcu_fetch('atec_WPCA_version')).' | '.esc_html($exec_time).' ms</span>';
				echo $buffer;
			};
			 ob_end_flush();
			 die();
		}
	 };
	 ob_start('atec_WPCA_page_buffer_callback');
 }

function atec_WPCA_page_buffer_stop() { if (ob_get_length()) ob_end_flush(); }

function atec_WPCA_page_buffer_callback($buffer)
{
	global $atec_WPCA_page_time_pre;
	$key='atec_WPCA_p_'.get_the_ID();
	$time=get_post_modified_time('U',false,get_the_ID());
	if (get_option('atec_WPCA_p_minify_enabled')=='yes')
	{
		include_once('minifyPHP.php');
		$atec_WPCA_minifyHTML=new MinifyHTML();
		$buffer=$atec_WPCA_minifyHTML->minifyHTML($buffer);
	};
	$compress='';
	if (function_exists('ob_gzhandler') && get_option('atec_WPCA_p_gzip_enabled')=='yes') { $compressed = gzencode($buffer); };
	$arr=array(get_the_ID(),$time,$buffer,$compressed,strlen($compressed));
	apcu_store($key,$arr);
	apcu_store($key.'_h',0);     
	$exec_time = intval((microtime(true)-$atec_WPCA_page_time_pre)*1000);
	return $buffer.PHP_EOL.'<span style="display:none;">FRESH: atec Cache APCu v'.esc_html(apcu_fetch('atec_WPCA_version')).' | '.esc_html($exec_time).' ms</span>';
}

add_action('template_redirect', 'atec_WPCA_page_buffer_start', 0);
add_action('shutdown', 'atec_WPCA_page_buffer_stop', PHP_INT_MAX);
?>