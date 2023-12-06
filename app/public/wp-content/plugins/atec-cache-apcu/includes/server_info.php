<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

$host = php_uname('n');

if (isset($_SERVER['SERVER_ADDR'])) { $host .= ($host!==''?' | ':'').sanitize_text_field($_SERVER['SERVER_ADDR']); }
$curl = @curl_version();
$connection = @mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
$mysql_version=@mysqli_get_server_info($connection);

echo '
<table class="pure-table" style="width:100%;">
	<thead>
		<tr><td class="ac_b">WP</td><td class="ac_b">PHP</td><td class="ac_b">CURL</td><td class="ac_b">mySQL</td><td class="ac_b">Host</td><td class="ac_b">IP</td><td class="ac_b">Server</td></tr>
	</thead>
	<tbody></tbody>
		<tr>
			<td>Version '.esc_html(get_bloginfo('version')).'</td>
			<td>Version '.esc_html(phpversion().(function_exists( 'php_sapi_name')?' | '.php_sapi_name():'').' | '.ini_get('memory_limit')).'</td>
			<td>Version '.esc_html(function_exists( 'curl_version')?$curl['version'].' | '.$curl['ssl_version']:'n/a').'</td>
			<td>Version '.esc_html(isset($mysql_version)?$mysql_version:'n/a').'</td>
			<td>'.esc_html($_SERVER['SERVER_NAME']).'</td>
			<td>'.esc_html($host).'</td>
			<td>'.esc_html($_SERVER['SERVER_SOFTWARE']).'</td>
		</tr>
	</tbody>
</table>
<br><br>';
?>