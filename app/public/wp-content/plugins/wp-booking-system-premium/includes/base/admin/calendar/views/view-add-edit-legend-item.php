<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

$page_heading       = ( $_GET['subpage'] == 'add-legend-item' ? __( 'Add New Legend Item', 'wp-booking-system' ) : __( 'Edit Legend Item', 'wp-booking-system' ) );
$page_wrap_class    = ( $_GET['subpage'] == 'add-legend-item' ? 'wpbs-wrap-add-legend-item' : 'wpbs-wrap-edit-legend-item' );
$submit_button_text = ( $_GET['subpage'] == 'add-legend-item' ? __( 'Add Legend Item', 'wp-booking-system' ) : __( 'Save Legend Item', 'wp-booking-system' ) );
$action				= ( $_GET['subpage'] == 'add-legend-item' ? 'add_legend_item' : 'edit_legend_item' );

$name  		= ( ! empty( $_POST['legend_item_name'] ) ? $_POST['legend_item_name'] : '' );
$type  		= ( ! empty( $_POST['legend_item_type'] ) ? $_POST['legend_item_type'] : '' );
$color 		= ( ! empty( $_POST['legend_item_color'] ) ? $_POST['legend_item_color'] : array() );
$color_text = ( ! empty( $_POST['legend_item_color_text'] ) ? $_POST['legend_item_color_text'] : '' );

$settings = get_option( 'wpbs_settings', array() );

// Set values for the edit page
if( $_GET['subpage'] == 'edit-legend-item' ) {

	$legend_item = wpbs_get_legend_item( absint( $_GET['legend_item_id'] ) );

	$name  		= ( ! empty( $name ) ? $name : $legend_item->get('name') );
	$type  		= ( ! empty( $type ) ? $type : $legend_item->get('type') );
	$color 		= ( ! empty( $color ) ? $color : $legend_item->get('color') );
	$color_text = ( ! empty( $color_text ) ? $color_text : $legend_item->get('color_text') );

}

?>

<div class="wrap wpbs-wrap <?php echo $page_wrap_class; ?>">

	<form action="" method="POST">

		<!-- Page Heading -->
		<h1 class="wp-heading-inline"><?php echo $page_heading; ?></h1>

		<!-- Page Heading Actions -->
		<div class="wpbs-heading-actions">

			<!-- Submit -->
		<input type="submit" value="<?php echo $submit_button_text; ?>" class="button button-primary" />

		<!-- Back to Legend -->
		<a class="button button-secondary" href="<?php echo add_query_arg( array( 'subpage' => 'view-legend' ), remove_query_arg( array( 'legend_item_id', 'wpbs_message' ) ) ); ?>"><?php echo __( 'Back to Legend', 'wp-booking-system' ); ?></a>
			
		</div>
		
		<hr class="wp-header-end" />

		<!-- General Settings Panel -->
		<div class="metabox-holder">
			<div class="postbox">

				<h3 class="hndle"><?php echo __( 'General', 'wp-booking-system' ); ?></h3>

				<div class="inside">
					
					<!-- Name -->
					<div class="wpbs-admin-field-wrapper wpbs-field-inline">
						<label for="wpbs-legend-item-name"><?php echo __( 'Name', 'wp-booking-system' ); ?></label>
						<div class="wpbs-admin-field-inner">
							<input id="wpbs-legend-item-name" name="legend_item_name" type="text" value="<?php echo esc_attr( $name ); ?>" class="wpbs-medium" />
						</div>
					</div>

					<!-- Type -->
					<div class="wpbs-admin-field-wrapper wpbs-field-inline">
						<label for="wpbs-legend-item-type"><?php echo __( 'Type', 'wp-booking-system' ); ?></label>
						<div class="wpbs-admin-field-inner">
							<select id="wpbs-legend-item-type" name="legend_item_type" class="wpbs-medium">
								<option value="single" <?php selected( $type, 'single' ); ?>><?php echo __( 'Single', 'wp-booking-system' ); ?></option>
								<option value="split" <?php selected( $type, 'split' ); ?>><?php echo __( 'Split', 'wp-booking-system' ); ?></option>
							</select>
						</div>
					</div>

					<!-- Color -->
					<div class="wpbs-admin-field-wrapper wpbs-field-inline">
						<label for="color-1"><?php echo __( 'Color', 'wp-booking-system' ); ?></label>

						<div class="wpbs-admin-field-inner">
							<input id="wpbs-legend-item-color-1" name="legend_item_color[]" class="wpbs-colorpicker" type="text" value="<?php echo esc_attr( ! empty( $color[0] ) ? $color[0] : '' ); ?>" class="wpbs-medium" />
							<input id="wpbs-legend-item-color-2" name="legend_item_color[]" class="wpbs-colorpicker" type="text" value="<?php echo esc_attr( ! empty( $color[1] ) ? $color[1] : '' ); ?>" class="wpbs-medium" />
						</div>
					</div>

					<!-- Text Color -->
					<div class="wpbs-admin-field-wrapper wpbs-field-inline">
						<label for="color-text"><?php echo __( 'Text Color', 'wp-booking-system' ); ?></label>

						<div class="wpbs-admin-field-inner">
							<input id="wpbs-legend-item-color-text" name="legend_item_color_text" class="wpbs-colorpicker" type="text" value="<?php echo esc_attr( ! empty( $color_text ) ? $color_text : '' ); ?>" class="wpbs-medium" />
						</div>
					</div>

				</div>
			</div>
		</div><!-- / Settings Panel -->

		<!-- Translations Panel -->
		<?php
			$active_languages = ( ! empty( $settings['active_languages'] ) ? $settings['active_languages'] : array() );
			$languages 		  = wpbs_get_languages();
		?>
		<?php if( ! empty( $active_languages ) ): ?>
		<div class="metabox-holder">
			<div class="postbox">

				<h3 class="hndle"><?php echo __( 'Translations', 'wp-booking-system' ); ?></h3>

				<div class="inside">
					
					<?php foreach( $active_languages as $code ): ?>

					<?php 

						if( $_GET['subpage'] == 'edit-legend-item' )
							$value = wpbs_get_legend_item_meta( $legend_item->get('id'), 'translation_' . $code, true );
						else
							$value = '';

					?>

					<div class="wpbs-admin-field-wrapper wpbs-field-inline">

						<label for="wpbs-legend-item-translation-<?php echo esc_attr( $code ); ?>">
							<img src="<?php echo WPBS_PLUGIN_DIR_URL . 'assets/img/flags/' . esc_attr( $code ) . '.png'; ?>" />
							<?php echo ( ! empty( $languages[$code] ) ? $languages[$code] : '' ); ?>
						</label>

						<div class="wpbs-admin-field-inner">
							<input id="wpbs-legend-item-translation-<?php echo esc_attr( $code ); ?>" name="legend_item_translation_<?php echo esc_attr( $code ); ?>" type="text" value="<?php echo esc_attr( $value ); ?>" class="wpbs-medium" />
						</div>

					</div>

					<?php endforeach; ?>

				</div>
			</div>
		</div><!-- / Settings Panel -->
		<?php endif; ?>

		<!-- Extra -->
		<?php do_action( 'wpbs_admin_view_add_edit_legend_item' ); ?>

		<!-- Nonce -->
		<?php wp_nonce_field( 'wpbs_' . $action, 'wpbs_token', false ); ?>
		<input type="hidden" name="wpbs_action" value="<?php echo $action; ?>" />

		<!-- Submit -->
		<input type="submit" value="<?php echo $submit_button_text; ?>" class="button button-primary" />

		<!-- Back to Legend -->
		<a class="button button-secondary" href="<?php echo add_query_arg( array( 'subpage' => 'view-legend' ), remove_query_arg( array( 'legend_item_id', 'wpbs_message' ) ) ); ?>"><?php echo __( 'Back to Legend', 'wp-booking-system' ); ?></a>

	</form>

</div>