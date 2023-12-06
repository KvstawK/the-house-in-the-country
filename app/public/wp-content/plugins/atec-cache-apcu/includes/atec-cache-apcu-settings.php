<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class ATEC_wpcu_settings
{
	
private function bsize($s) { foreach (array('','K','M','G') as $i => $k) { if ($s < 1024) break; $s/=1024; } return sprintf("%5.1f %sB",$s,$k); }
private function red($txt) { echo '<font color="red">'.esc_html($txt).'</font>'; }

private function enabled($enabled) { echo '<font color="'.esc_html($enabled?'green':'red').'">'.esc_html($enabled?'enabled':'disabled').'</font>'; }
private function error($cache,$txt) { echo '<font color="red">'.esc_html($cache).' '.esc_html($txt).'</font><script>document.getElementById("'.esc_html($cache).'_trash").style.display = "none";</script>'; }

	
function __construct()
{		
global $wp_object_cache;
global $atec_WPCA_apcu_enabled;
$atec_wpca_key='atec_wpca_key';

echo '<div style="width:99%; display:block;">';	

	echo '<h3 style="text-align: center;"><sub><img src="../wp-content/plugins/atec-cache-apcu/assets/img/atec_wpca_icon.svg" style="height:22px;"></sub> atec Cache APCu <font style="font-size:80%;">v'.wp_cache_get('atec_WPCA_version').'</font> – Settings</h3>';

	include_once('server_info.php');
	
	echo '
	<div class="pure-g"><div class="pure-u-1-1" style="background: #e0e0e0; padding-left:5px; border:solid 1px #cbcbcb;"><h3 style="margin:10px 0;">APCu Object Cache – Extension – ';
	$this->enabled(extension_loaded('apcu'));
	echo '</h3></div></div>
	<div class="pure-g ac_inner-div">

    	    <div class="pure-u"><div class="ac_p">
	        <h4>APCu object cache – ';
	        $this->enabled($atec_WPCA_apcu_enabled);
	        echo '</h4>
	        <hr>';
	    	if ($atec_WPCA_apcu_enabled)
		{    
			$apcu_cache=apcu_cache_info(true);
			if ($apcu_cache)
			{
				$total=$apcu_cache['num_entries'];
				$size=$apcu_cache['mem_size'];
				echo 'Current size is '.esc_html(number_format($total).' item'.($total!==1?'s':'')).', '.esc_html($this->bsize($size)).'.<br>';
			}
			else $this->red('No object cache data available.<br>');
		}
		else $this->red('APCu extension not installed/enabled.<br>');	

	echo '
	</div></div>';
	
	if ($atec_WPCA_apcu_enabled)
	{
	$value = get_option( 'atec_WPCA_p_cache_enabled'); 
	echo '
	<div class="pure-u"><div class="ac_p">
		<h4>APCu Page Cache – ';
		$this->enabled($value=='yes');
		echo '</h4>
		<hr>';
		if ($atec_WPCA_apcu_enabled)
		{    
			$apcu_cache=apcu_cache_info(true);
			if ($apcu_cache)
			{
				$apcu_it=new APCUIterator('/atec_WPCA_p_/');
				if (!empty($apcu_it))
				{
					$c=0; $size=0;
					foreach ($apcu_it as $entry) 
					if (!str_contains($entry['key'],'_h')) { $c++; $size+=$entry['mem_size']; };
					echo 'Current size is '.esc_html(number_format($c)).' item'.($c!==1?'s':'').', '.esc_html($this->bsize($size)).'.<br>';
				}
				else $this->red('No page cache data available.<br>');
				echo '<br><hr>';
				?>	
					<div id="atec_WPCA_settings">
						<style>#atec_WPCA_settings .form-table th, #atec_WPCA_settings .form-table td { padding: 5px; }</style>
				      <form method="post" action="options.php">
					<?php
					  settings_fields( 'atec_WPCA_p_cache_settings' );
					  do_settings_sections( 'atec_WPCA_cache' );
					  submit_button();
					?>
				      </form>
					</div>
				<?php
			}
		};
	echo '
	</div></div>';
	}

echo '</div>';

include_once('atec-cache-footer.php');
echo '
</div>';
}
};
$atec_wpcu_settings=new ATEC_wpcu_settings();
?>