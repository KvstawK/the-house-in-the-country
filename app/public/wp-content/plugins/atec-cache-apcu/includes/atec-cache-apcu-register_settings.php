<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

add_action( 'admin_init',  'atec_WPCA_settings_fields' );
function atec_WPCA_settings_fields()
{
  $page_slug = 'atec_WPCA_cache';
  $option_group = 'atec_WPCA_p_cache_settings';
  add_settings_section('atec_WPCA_section','', '', $page_slug);
  register_setting( $option_group, 'atec_WPCA_p_cache_enabled', 'atec_WPCA_sanitize_checkbox' );
  register_setting( $option_group, 'atec_WPCA_p_minify_enabled', 'atec_WPCA_sanitize_checkbox' );
  register_setting( $option_group, 'atec_WPCA_p_clear_enabled', 'atec_WPCA_sanitize_checkbox' );
  register_setting( $option_group, 'atec_WPCA_p_gzip_enabled', 'atec_WPCA_sanitize_checkbox' );

  $atec_WPCA_middot='&middot;&middot;&middot;> ';
  add_settings_field('atec_WPCA_p_cache_enabled', 'Enable page cache', function () { atec_WPCA_checkbox('atec_WPCA_p_cache_enabled'); }, $page_slug, 'atec_WPCA_section');
  add_settings_field('atec_WPCA_p_minify_enabled', $atec_WPCA_middot.'Minify<br><span style="font-size:80%; color:#999;">Minify HTML Document.</span>', function () { atec_WPCA_checkbox('atec_WPCA_p_minify_enabled'); }, $page_slug, 'atec_WPCA_section');
  add_settings_field('atec_WPCA_p_gzip_enabled', $atec_WPCA_middot.'Gzip<br><span style="font-size:80%; color:#999;">Compress HTML Document.</span>', function () { atec_WPCA_checkbox('atec_WPCA_p_gzip_enabled'); }, $page_slug, 'atec_WPCA_section');
  add_settings_field('atec_WPCA_p_clear_enabled', $atec_WPCA_middot.'Auto clear<br><span style="font-size:80%; color:#999;">Clear cache, after plugin & theme changes, updates and page edits.</span>', function () { atec_WPCA_checkbox('atec_WPCA_p_clear_enabled'); }, $page_slug, 'atec_WPCA_section');
}

function atec_WPCA_checkbox($field) { echo '<input type="checkbox" name="'.esc_html($field).'"'; checked( get_option( esc_html($field) ), 'yes' ); echo '>'; }
function atec_WPCA_sanitize_checkbox( $value ) { return 'on' === $value ? 'yes' : 'no'; }
?>