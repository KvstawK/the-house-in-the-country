<?php $current_tab = 'form-options'; ?>
<?php $sub_tabs = $this->get_sub_tabs($current_tab); ?>
<?php $active_section = ( ! empty( $_GET['section'] ) && array_key_exists($_GET['section'], $sub_tabs) ? sanitize_text_field( $_GET['section'] ) : key($sub_tabs) ); ?>

<ul class="subsubsub wpbs-form-tab-navigation">
    <?php 
        $i = 0; foreach( $sub_tabs as $tab_slug => $tab_name ) {

            echo '<li> ' . ($i != 0 ? '&nbsp;|&nbsp;' : '') . '<a href="' . add_query_arg( array( 'page' => 'wpbs-forms', 'subpage' => 'edit-form', 'form_id' => $_GET['form_id'], 'tab' => $current_tab, 'section' => $tab_slug), admin_url('admin.php') ) . '" data-tab="' . $tab_slug . '" '. ($active_section == $tab_slug  ? ' class="current"' : '').'>' . $tab_name . '</a></li>';
        $i++;
        }
    ?>
</ul>

<div class="wpbs-clear"><!-- --></div>

<div class="wpbs-form-sections">

    <?php foreach( $sub_tabs as $tab_slug => $tab_name ) {

		echo '<div class="wpbs-form-section wpbs-form-section-' . $tab_slug . ( $active_section == $tab_slug ? ' wpbs-section-active' : '' ) . ' " data-tab="' . $tab_slug . '">';

		// Handle general tab
		if( $tab_slug == 'general-options' ) {

			include 'view-edit-form-tab-general-options.php';
		
		// Handle general tab
		} else if( $tab_slug == 'form-confirmation' ) {

			include 'view-edit-form-tab-form-confirmation.php';

		// Handle dynamic tabs
		} else {

			/**
			 * Action to dynamically add content for each tab
			 *
			 */
			do_action( 'wpbs_submenu_page_edit_form_tabs_form_options_' . $tab_slug );
		}
		echo '</div>';
	}

	?>
		

</div>
