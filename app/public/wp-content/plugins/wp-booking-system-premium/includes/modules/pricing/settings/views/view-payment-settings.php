<?php
$settings = get_option( 'wpbs_settings', array() );
$active_languages = (!empty($settings['active_languages']) ? $settings['active_languages'] : array());
$active_section = ( isset($_GET['section']) && ! empty( $_GET['section'] && array_key_exists($_GET['section'], $payment_tabs) ) ? sanitize_text_field( $_GET['section'] ) : 'general_settings' );
$languages = wpbs_get_languages();
?>

<ul class="subsubsub wpbs-sub-tab-navigation">
    <?php 
    if( ! empty( $payment_tabs ) ) {
        $i = 0; foreach( $payment_tabs as $tab_slug => $tab_name ) {

            echo '<li> ' . ($i != 0 ? '&nbsp;|&nbsp;' : '') . '<a href="' . add_query_arg( array( 'page' => 'wpbs-settings', 'tab' => 'payment', 'section' => $tab_slug), admin_url('admin.php') ) . '" data-tab="' . $tab_slug . '" '. ($active_section == $tab_slug  ? ' class="current"' : '').'>' . $tab_name . '</a></li>';
        $i++;
        }
    }
    ?>
</ul>

<div class="wpbs-clear"><!-- --></div>

<div class="wpbs-sub-tabs">

	<?php

		if( ! empty( $payment_tabs ) ) {

			foreach( $payment_tabs as $tab_slug => $tab_name ) {

				echo '<div class="wpbs-tab wpbs-tab-' . $tab_slug . ( $active_section == $tab_slug ? ' wpbs-section-active' : '' ) . ' " data-tab="' . $tab_slug . '">';

				// Handle general tab
				if( $tab_slug == 'general_settings' ) {

					include 'view-payment-settings-general.php';
				
				// Handle general tab
				} else if( $tab_slug == 'taxes' ) {

					include 'view-payment-settings-taxes.php';

				// Handle Payment on Arrival tab
				} else if( $tab_slug == 'payment_on_arrival' ) {

					include 'view-payment-settings-payment-on-arrival.php';
				
				// Handle Payment on Arrival tab
				} else if( $tab_slug == 'bank_transfer' ) {

					include 'view-payment-settings-bank-transfer.php';
				
				// Handle String Translations tab
				} else if( $tab_slug == 'payment_strings' ) {

					include 'view-payment-settings-payment-strings.php';

				// Handle dynamic tabs
				} else {

					/**
					 * Action to dynamically add content for each tab
					 *
					 */
					do_action( 'wpbs_submenu_page_payment_settings_tab_' . $tab_slug );
				}
				echo '</div>';
			}
		}

	?>
		
</div>

<!-- Submit button -->
<input type="submit" class="button-primary" value="<?php echo __( 'Save Settings', 'wp-booking-system' ); ?>" />