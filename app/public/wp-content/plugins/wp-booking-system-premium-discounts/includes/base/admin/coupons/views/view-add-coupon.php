<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

?>

<div class="wrap wpbs-wrap wpbs-wrap-add-coupon">

	<form action="" method="POST">
		
		<!-- Icon -->
		<div id="wpbs-add-new-coupon-icon">
			<div class="wpbs-icon-wrap">
				<span class="dashicons dashicons-tickets-alt"></span>
				<span class="dashicons dashicons-plus"></span>
			</div>
		</div>

		<!-- Heading -->
		<h1 id="wpbs-add-new-coupon-heading"><?php echo __( 'Add New Coupon', 'wp-booking-system-coupons-discounts'); ?></h1>

		<!-- Postbox -->
		<div id="wpbs-add-new-coupon-postbox" class="postbox">

			<!-- Form Fields -->
			<div class="inside">

				<!-- Add coupon Name -->
				<label for="wpbs-new-coupon-name"><?php echo __( 'Coupon Name', 'wp-booking-system-coupons-discounts'); ?> *</label>
				<input id="wpbs-new-coupon-name" name="coupon_name" type="text" value="<?php echo ( ! empty( $_POST['coupon_name'] ) ? esc_attr( $_POST['coupon_name'] ) : '' ); ?>" />
			
			</div>

			<!-- Form Submit button -->
			<div id="major-publishing-actions">
				<a href="<?php echo admin_url( $this->admin_url ); ?>"><?php echo __( 'Cancel', 'wp-booking-system-coupons-discounts'); ?></a>
				<input type="submit" class="button-primary wpbs-button-large" value="<?php echo __( 'Add Coupon', 'wp-booking-system-coupons-discounts'); ?>" />
			</div>

			<!-- Action and nonce -->
			<input type="hidden" name="wpbs_action" value="add_coupon" />
			<?php wp_nonce_field( 'wpbs_add_coupon', 'wpbs_token', false ); ?>

		</div>

	</form>

</div>