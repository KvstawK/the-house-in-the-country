<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class ATEC_wpcu_groups
{
private function bsize($s) { foreach (array('','K','M','G') as $i => $k) { if ($s < 1024) break; $s/=1024; } return sprintf("%5.1f %sB",$s,$k); }
private function createArray($array) { $newArray=[]; foreach($array as $arr) $newArray[$arr]=array(0,0); return $newArray; }
private function enabled($enabled) { echo '<font color="'.esc_html($enabled?'green':'red').'">'.esc_html($enabled?'enabled':'disabled').'</font>'; }
private function error($cache,$txt) { echo '<font color="red">'.esc_html($cache).' '.esc_html($txt).'</font><script>document.getElementById("'.esc_html($cache).'_trash").style.display = "none";</script>'; }

function __construct()
{	

global $atec_WPCA_apcu_enabled;

echo '<div style="width:99%; display:block;">';	

	echo '<h3 style="text-align: center;"><sub><img src="../wp-content/plugins/atec-cache-apcu/assets/img/atec_wpca_icon.svg" style="height:22px;"></sub> atec Cache APCu <font style="font-size:80%;">v'.esc_html(wp_cache_get('atec_WPCA_version')).' – Groups list</font></h3>';

	echo '
	<div class="pure-g"><div class="pure-u-1-1" style="background: #e0e0e0; padding-left:5px; border:solid 1px #cbcbcb;"><h3 style="margin:10px 0;">APCu Object Cache</h3></div></div>

	<div class="pure-g ac_inner-div">

        	<div class="pure-u"><div class="ac_p">
	    	<h4>APCu – ';
		    $this->enabled($atec_WPCA_apcu_enabled);
	    	echo '</h4><hr>';
	    	if ($atec_WPCA_apcu_enabled)
			{    
				$apcu_cache=apcu_cache_info(true);
				if ($apcu_cache)
				{
					$c=0; $total=0;
					$apcu_it=new APCUIterator();
					if (!empty($apcu_it))
					{
						$keys=array('wp:posts:wp_query','wp:terms:get_terms','wp:posts:get_pages','wp:comment:get_comments');
						$groups=$this->createArray($keys);						
						echo'
						<table class="pure-table">
						<thead>
							<tr><td>#</td><td>Key</td><td>Hits</td><td>Size</td><td>Value</td></tr>
						</thead>
						<tbody>';
						foreach ($apcu_it as $entry) 
						{
							$c++;
							$key=explode(':',$entry['key']);
							$temp=(isset($key[3]) && isset($key[4]))?$key[0].':'.$key[3].':'.$key[4]:$entry['key'];
							if (isset($groups[$temp]))
							{
								$groups[$temp][0]+=$entry['num_hits'];
								$groups[$temp][1]+=$entry['mem_size'];								
							}
							else
							{
								echo '<tr><td>'.esc_html($c).'</td>';
								echo '<td>';
								if (str_contains($temp,'atec_WPCA')) echo '<font color="green"><b>';
								else echo '<font>';
								echo esc_html($temp);
								echo '</b></font>';
								echo '</td>';
								echo '<td>'.esc_html($entry['num_hits']).'</td>';
								echo '<td>'.esc_html($this->bsize($entry['mem_size'])).'</td>';
								echo '<td>'.esc_html(htmlentities(substr(serialize($entry['value']),0,50))).'</td></tr>';
							};
							$total+=$entry['mem_size'];
						};
						
						foreach($keys as $key)
						echo '<tr><td></td><td>'.esc_html($key).'</td><td>'.esc_html($groups[$key][0]).'</td><td>'.esc_html($this->bsize($groups[$key][1])).'</td><td><i>grouped</i></td></tr>';
						
						echo '
						<thead>
							<tr><td>'.esc_html($c).'</td><td></td><td></td><td>'.esc_html($this->bsize($total)).'</td><td></td></tr>
						</thead>';
						echo '</tbody></table><br>';
					}
					else echo 'Not in use.<br>';
				}
				else error('APCu','cache data could not be retrieved.').'<br>';
			}
			else echo 'Make sure apcu extension is installed and enabled in php.ini.<br>';	
			
    		echo '
		</div></div>
	</div>';

	include_once('atec-cache-footer.php');

echo '
</div>';
}
};
$atec_wpcu_groups=new ATEC_wpcu_groups();
?>