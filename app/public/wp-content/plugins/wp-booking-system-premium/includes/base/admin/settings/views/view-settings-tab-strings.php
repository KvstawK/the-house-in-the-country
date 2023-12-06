<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

$active_languages = (!empty($settings['active_languages']) ? $settings['active_languages'] : array());
$languages = wpbs_get_languages();
$strings_tabs = $this->get_string_tabs();
$active_section = ( isset($_GET['section']) && ! empty( $_GET['section'] && array_key_exists($_GET['section'], $strings_tabs) ) ? sanitize_text_field( $_GET['section'] ) : 'form' );
?>

<ul class="subsubsub wpbs-sub-tab-navigation">
    <?php 
    if( ! empty( $strings_tabs ) ) {
        $i = 0; foreach( $strings_tabs as $tab_slug => $tab_name ) {

            echo '<li> ' . ($i != 0 ? '&nbsp;|&nbsp;' : '') . '<a href="' . add_query_arg( array( 'page' => 'wpbs-settings', 'tab' => 'strings', 'section' => $tab_slug), admin_url('admin.php') ) . '" data-tab="' . $tab_slug . '" '. ($active_section == $tab_slug  ? ' class="current"' : '').'>' . $tab_name . '</a></li>';
        $i++;
        }
    }
    ?>
</ul>

<div class="wpbs-clear"><!-- --></div>

<div class="wpbs-sub-tabs">

	<?php

		if( ! empty( $strings_tabs ) ) {

			foreach( $strings_tabs as $tab_slug => $tab_name ) {

				echo '<div class="wpbs-tab wpbs-tab-' . $tab_slug . ( $active_section == $tab_slug ? ' wpbs-section-active' : '' ) . ' " data-tab="' . $tab_slug . '">';
				// Handle general tab
				if( $tab_slug == 'form' ) {

					include 'view-settings-tab-strings-form.php';
				
				// Handle dynamic tabs
				} else {

					/**
					 * Action to dynamically add content for each tab
					 *
					 */
					do_action( 'wpbs_submenu_page_string_settings_tab_' . $tab_slug );
				}
				echo '</div>';
			}
		}

	?>
		
</div>

<!-- Submit button -->
<input type="submit" class="button-primary" value="<?php echo __( 'Save Settings', 'wp-booking-system' ); ?>" />