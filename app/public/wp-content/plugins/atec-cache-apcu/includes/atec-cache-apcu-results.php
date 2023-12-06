<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class ATEC_wpcu_results
{
private function bsize($s) { foreach (array('','K','M','G') as $i => $k) { if ($s < 1024) break; $s/=1024; } return sprintf("%5.1f %sB",$s,$k); }
private function isPara() { if (isset($_GET['flush'])) return $_GET['flush']; else return false; }
private function enabled($enabled) { echo '<font color="'.($enabled?'green':'red').'">'.($enabled?'enabled':'disabled').'</font>'; }
private function error($cache,$txt) { echo '<font color="red">'.esc_html($cache).' '.esc_html($txt).'</font><script>document.getElementById("'.esc_html($cache).'_trash").style.display = "none";</script>'; }

function __construct()
{	
global $wp_object_cache;
$atec_wpca_key='atec_wpca_key';

echo '<script>
function ac_reload() { const url = new URL(window.location.href); url.searchParams.delete("flush"); window.location.href=url; return false; };
function countDown(c) { ti=setTimeout(function() { countDown(c+1); }, 100); obj=document.getElementById("reload"); obj.innerHTML=obj.innerHTML+" "+(5-c)+" "; if (c>=4) { clearTimeout(ti); ac_reload(); }};
</script>';

echo '<style>.ac_pie { display:inline-block; width: 16px; height: 16px; border-radius: 50%; border: solid 1px #ccc; margin-left:5px; vertical-align: bottom; }</style>';

echo '<div style="width:99%; display:block;">';	

	echo '<h3 style="text-align: center;"><sub><img src="../wp-content/plugins/atec-cache-apcu/assets/img/atec_wpca_icon.svg" style="height:22px;"></sub> atec Cache APCu <font style="font-size:80%;">v'.wp_cache_get('atec_WPCA_version').' – Statistics</font></h3>';
	
	echo '<div class="pure-g"><div class="pure-u-1-1" style="background: #e0e0e0; padding-left:5px; border:solid 1px #cbcbcb;"><h3 style="margin:10px 0;">APCu & WP Object Cache</h3></div></div>';

	if ($flush=$this->isPara())
	{
		echo '<br><br><center>Flushing '.esc_html($flush).' cache ... ';
		$result=false;
		switch ($flush) 
		{
			case 'OPcache': $result=opcache_reset(); break;
	    		case 'APCu': if (function_exists('apcu_clear_cache')) $result=apcu_clear_cache(); apcu_store('atec_WPCA_version',wp_cache_get('atec_WPCA_version')); break;
	    		case 'WP_ocache': $result=$wp_object_cache->flush(); break;
			case 'APCu_PCache': atec_WPCA_delete_page_cache(); $result=true; break;	    
		}
		if ($result==1) echo '<font color="green">successful</font>.';
		else echo '<font color="green">failed</font>.';
		echo '<br><br><a style="text-decoration:none;" href="#" onclick="return ac_reload();">Please reload</a>.<br><br>... <span id="reload"></span>';
		echo '</center><script>countDown(0)</script>';
		return;
	};
	
	global $atec_WPCA_apcu_enabled;
	$wp_enabled=is_object($wp_object_cache);
	$opcache_enabled=function_exists('opcache_get_status') && is_array(opcache_get_status());
	$requestUri=sanitize_url($_SERVER['REQUEST_URI']);

	echo '		
	<div class="pure-g ac_inner-div">
	
    		<div class="pure-u"><div class="ac_p">
		    		<h4>OPcache – ';
				    $this->enabled($opcache_enabled);
				    echo ($opcache_enabled?'<div id="pie_1" style="display:none;" class="ac_pie"></div> <a href="'.$requestUri.'&flush=OPcache'.'" style="text-decoration:none; margin-left:5px;"><span class="dashicons dashicons-trash"></span></a>':'').'</h4>
		    		<hr>';
			
			    		if ($opcache_enabled)
			    		{
				    		$op_status=opcache_get_status();
				    		$op_conf=opcache_get_configuration();
				    		$total=$op_status['opcache_statistics']['hits']+$op_status['opcache_statistics']['misses']+0.001;
				    		$hits=$op_status['opcache_statistics']['hits']*100/$total;
				    		$misses=$op_status['opcache_statistics']['misses']*100/$total;
				    		$percent=$op_status['memory_usage']['used_memory']*100/$op_conf['directives']['opcache.memory_consumption'];
				    		$deg=round(360*$percent/100);
				    		echo '<style>#pie_1 { display:inline-block !important; background: conic-gradient( #ff7721 0deg '.esc_html($deg).'deg, white '.esc_html($deg).'deg 360deg ); }</style>';
				    		echo'
				    		<table class="pure-table">
				    		<tbody>
					    		<tr><td class="ac_b">Version:</td><td>'.esc_html($op_conf['version']['version']).'</td></tr>
					    		<tr><td class="ac_b">Memory:</td><td>'.esc_html($this->bsize($op_conf['directives']['opcache.memory_consumption'])).'</td></tr>
					    		<tr><td class="ac_b">Used:</td><td>'.esc_html($this->bsize($op_status['memory_usage']['used_memory']).' '.sprintf(" (%.1f%%)",$percent)).'</td></tr>
								<tr><td class="ac_b">Items:</td><td>'.esc_html(number_format($op_status['opcache_statistics']['num_cached_scripts']+$op_status['opcache_statistics']['num_cached_keys'])).'</td></tr>
					    		<tr><td class="ac_b">Hits:</td><td>'.esc_html(number_format($op_status['opcache_statistics']['hits']).sprintf(" (%.1f%%)",$hits)).'</td></tr>
					    		<tr><td class="ac_b">Misses:</td><td>'.esc_html(number_format($op_status['opcache_statistics']['misses']).sprintf(" (%.1f%%)",$misses)).'</td></tr>
					    		<tr><td colspan="2"><center style="font-size:60%; margin:0;">Hitrate</center><div class="ac_percent_div"><span class="ac_percent" style="width:'.esc_html(round($hits)).'%; background-color:green;"></span><span class="ac_percent" style="width:'.esc_html(round($misses)).'%; background-color:red;"></span></div></td></tr>
							</tbody>
				    		</table>';
						}
						else echo 'Make sure opcache extension is installed/enabled.';
			    		
	    		echo '
	    	</div></div>	

    	    	<div class="pure-u"><div class="ac_p">
	    	    <h4>APCu – ';
		        $this->enabled($atec_WPCA_apcu_enabled);
		        echo ($atec_WPCA_apcu_enabled?'<div id="pie_2" style="display:none;" class="ac_pie"></div> <a id="APCu_trash" href="'.$requestUri.'&flush=APCu'.'" style="text-decoration:none; margin-left:5px;"><span class="dashicons dashicons-trash"></span></a>':'').'</h4>
	    	    <hr>';
	    	 if ($atec_WPCA_apcu_enabled)
		{    
			$apcu_cache=apcu_cache_info(true);
			if ($apcu_cache)
			{
				$apcu_mem=apcu_sma_info();
				$total=$apcu_cache['num_hits']+$apcu_cache['num_misses']+0.001;
				$hits=$apcu_cache['num_hits']*100/$total;
				$misses=$apcu_cache['num_misses']*100/$total;
				$percent=$apcu_cache['mem_size']*100/($apcu_mem['num_seg']*$apcu_mem['seg_size']);
				$deg=round(360*$percent/100);	
				echo'
				<table class="pure-table">
				<tbody>
				<tr><td class="ac_b">Version:</td><td>'.esc_html(phpversion('apcu')).'</td></tr>
				<tr><td class="ac_b">Type:</td><td>'.esc_html($apcu_cache['memory_type']).'</td></tr>
				<tr><td class="ac_b">Memory:</td><td>'.esc_html($this->bsize($apcu_mem['num_seg']*$apcu_mem['seg_size'])).'</td></tr>';
				if ($percent>0)
				{
				    echo '
				    <tr><td class="ac_b">Used:</td><td>'.esc_html($this->bsize($apcu_cache['mem_size'])).' <font color="#ff7721">'.esc_html(sprintf(" (%.1f%%)",$percent)).'</font></td></tr>
				    <tr><td class="ac_b">Items:</td><td>'.esc_html(number_format($apcu_cache['num_entries'])).'</td></tr>
				    <tr><td class="ac_b">Hits:</td><td>'.esc_html(number_format($apcu_cache['num_hits']).sprintf(" (%.1f%%)",$hits)).'</td></tr>
				    <tr><td class="ac_b">Misses:</td><td>'.esc_html(number_format($apcu_cache['num_misses']).sprintf(" (%.1f%%)",$misses)).'</td></tr>
				    <tr><td colspan="2"><center style="font-size:60%; margin:0;">Hitrate</center><div class="ac_percent_div"><span class="ac_percent" style="width:'.esc_html(round($hits)).'%; background-color:green;"></span><span class="ac_percent" style="width:'.esc_html(round($misses)).'%; background-color:red;"></span></div></td></tr>';
				    echo '</tbody></table><br>';
				    echo '<style>
				    #pie_2 { display:inline-block !important; background: conic-gradient( #ff7721 0deg '.esc_html($deg).'deg, #fff '.esc_html($deg).'deg 360deg ); }
				    </style>';
				}
				else
				{
					echo '</tbody></table><br>';
					echo 'Not in use.<br>';
					echo '<script>document.getElementById("APCu_trash").style.display = "none";</script>';
				}
			}
			else echo $this->error('APCu','cache data could not be retrieved.').'<br>';
		}
		else echo 'APCu extension not installed/enabled.<br>';	
		
	        echo '
		</div></div>

    	    	<div class="pure-u"><div class="ac_p">
	    	    <h4>WP Object Cache – ';
		        $this->enabled($wp_enabled);
		        echo ($wp_enabled?' <a id="WP_ocache_trash" href="'.$requestUri.'&flush=WP_ocache'.'" style="text-decoration:none; margin-left:5px;"><span class="dashicons dashicons-trash"></span></a>':'').'</h4>	
	    	    <hr>';
		if ($wp_enabled)
		{    
			if (method_exists('WP_Object_Cache', 'stats')) 
			{
				echo '<div id="stats" style="border:solid 1px #ccc; display:inline-block; padding:10px;">';
				$wp_object_cache->stats();
				echo '</div><br><br>';			
			}
			else
			{
		    		if (isset($wp_object_cache->cache_hits))
		    		{
			    		$total=$wp_object_cache->cache_hits+$wp_object_cache->cache_misses+0.001;
			    		$hits=$wp_object_cache->cache_hits*100/$total;
			    		$misses=$wp_object_cache->cache_misses*100/$total;
			    		echo'
			    		<table class="pure-table">
			    		<tbody>
				    		<tr><td class="ac_b">Hits:</td><td>'.esc_html(number_format($wp_object_cache->cache_hits).sprintf(" (%.1f%%)",$hits)).'</td></tr>
					    		<tr><td class="ac_b">Misses:</td><td>'.esc_html(number_format($wp_object_cache->cache_misses).sprintf(" (%.1f%%)",$misses)).'</td></tr>
					    		<tr><td colspan="2"><center style="font-size:60%; margin:0;">Hitrate</center><div class="ac_percent_div"><span class="ac_percent" style="width:'.esc_html(round($hits)).'%; background-color:green;"></span><span class="ac_percent" style="width:'.esc_html(round($misses)).'%; background-color:red;"></span></div></td></tr>
							</tbody>
			    		</table>';
			    		echo '<br>';
		    		};
				wp_cache_set('$atec_wpca_key','hello');
				$temp=wp_cache_get('$atec_wpca_key');
				if ($temp=='hello')
				{
					wp_cache_delete('$atec_wpca_key');
					echo 'WP object cache is writeable.<br>';
				}
				else echo $this->error('WP_ocache','not available.');
			}
		}
		else echo 'Check your wp-config.php for define("ENABLE_CACHE",TRUE).';
    
    	echo '
	    </div></div>';
	    
	$value = get_option( 'atec_WPCA_p_cache_enabled'); 

	echo '    
	<div class="pure-u"><div class="ac_p">
		    <h4>APCu Page Cache – ';
		    $this->enabled($value=='yes');
		    echo ($value=='yes'?' <a id="APCu_PCache_trash" href="'.$requestUri.'&flush=APCu_PCache'.'" style="text-decoration:none; margin-left:5px;"><span class="dashicons dashicons-trash"></span></a>':'').'</h4>	
		    <hr>';
		    if ($atec_WPCA_apcu_enabled)
		    {    
			    $apcu_cache=apcu_cache_info(true);
			    if ($apcu_cache)
			    {
				    $apcu_it=new APCUIterator();
				    if (!empty($apcu_it))
				    {
					    echo '
					    <table class="pure-table">
					    	<thead>
						    <tr><td>Type</td><td>ID</td><td>Hits</td><td>Size</td><td>Title</td></tr>
					        <tbody>';					    
					    	$c=0; $size=0;
					    	foreach ($apcu_it as $entry) 
					    	{
						    	if (str_contains($entry['key'],'atec_WPCA_p') && !str_contains($entry['key'],'_h')) 
						    	{ 
							    	$c++; $size+=$entry['mem_size']; 
							    	$pageID=(int) str_replace('atec_WPCA_p_','', $entry['key']);
							    	echo '<tr><td class="ac_b">'.esc_html(ucfirst(get_post_type($pageID))).'</td><td>'.esc_html($pageID).'</td><td>'.esc_html(apcu_fetch($entry['key'].'_h')).'</td><td>'.esc_html($this->bsize($entry['mem_size'])).'</td><td>'.esc_html(get_the_title($pageID)).'</td></tr>';						    
						    	}
					    	};						
						if ($c==0) echo '<tr><td colspan="3">n/a</td></tr>';
					    echo '
					    </tbody>
						  </table>';

					    echo '<br>';
					    echo 'Current size is '.esc_html(number_format($c)).' item'.($c!==1?'s':'').', '.esc_html($this->bsize($size)).'.<br>';
				    }
				    else echo red('No page cache data available.');
			    }
		    };
	    echo '
	    </div></div>  	    
	    
    </div>';
    
include_once('atec-cache-footer.php');
    
echo '</div>';
}
};
$atec_wpcu_results=new ATEC_wpcu_results();
?>