<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

?>

<div class="wrap wpbs-wrap wpbs-wrap-add-discount">

	<form action="" method="POST">
		
		<!-- Icon -->
		<div id="wpbs-add-new-discount-icon">
			<div class="wpbs-icon-wrap">
				<span class="dashicons dashicons-tickets-alt"></span>
				<span class="dashicons dashicons-plus"></span>
			</div>
		</div>

		<!-- Heading -->
		<h1 id="wpbs-add-new-discount-heading"><?php echo __( 'Add New Discount', 'wp-booking-system-coupons-discounts'); ?></h1>

		<!-- Postbox -->
		<div id="wpbs-add-new-discount-postbox" class="postbox">

			<!-- Form Fields -->
			<div class="inside">

				<!-- Add discount Name -->
				<label for="wpbs-new-discount-name"><?php echo __( 'Discount Name', 'wp-booking-system-coupons-discounts'); ?> *</label>
				<input id="wpbs-new-discount-name" name="discount_name" type="text" value="<?php echo ( ! empty( $_POST['discount_name'] ) ? esc_attr( $_POST['discount_name'] ) : '' ); ?>" />
			
			</div>

			<!-- Form Submit button -->
			<div id="major-publishing-actions">
				<a href="<?php echo admin_url( $this->admin_url ); ?>"><?php echo __( 'Cancel', 'wp-booking-system-coupons-discounts'); ?></a>
				<input type="submit" class="button-primary wpbs-button-large" value="<?php echo __( 'Add Discount', 'wp-booking-system-coupons-discounts'); ?>" />
			</div>

			<!-- Action and nonce -->
			<input type="hidden" name="wpbs_action" value="add_discount" />
			<?php wp_nonce_field( 'wpbs_add_discount', 'wpbs_token', false ); ?>

		</div>

	</form>

</div>