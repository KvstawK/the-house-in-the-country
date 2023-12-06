<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

$calendars = wpbs_get_calendars( array( 'status' => 'active' ) );

?>

<div class="wrap wpbs-wrap wpbs-wrap-add-calendar">

	<form action="" method="POST">
		
		<!-- Icon -->
		<div id="wpbs-add-new-calendar-icon">
			<div class="wpbs-icon-wrap">
				<span class="dashicons dashicons-calendar-alt"></span>
				<span class="dashicons dashicons-plus"></span>
			</div>
		</div>

		<!-- Heading -->
		<h1 id="wpbs-add-new-calendar-heading"><?php echo __( 'Add New Calendar', 'wp-booking-system' ); ?></h1>

		<!-- Postbox -->
		<div id="wpbs-add-new-calendar-postbox" class="postbox">

			<!-- Form Fields -->
			<div class="inside">

				<!-- Add Calendar Name -->
				<label for="wpbs-new-calendar-name"><?php echo __( 'Calendar Name', 'wp-booking-system' ); ?> *</label>
				<input id="wpbs-new-calendar-name" name="calendar_name" type="text" value="<?php echo ( ! empty( $_POST['calendar_name'] ) ? esc_attr( $_POST['calendar_name'] ) : '' ); ?>" />

				<?php if(wpbs_is_pricing_enabled()): ?>
					<!-- Add Default Price -->
					<label for="wpbs-new-calendar-price"><?php echo __( 'Default price', 'wp-booking-system' ); ?></label>
					<span class="input-before">
						<span class="before"><?php echo wpbs_get_currency() ?></span>
						<input id="wpbs-new-calendar-price" name="calendar_price" type="number" value="<?php echo ( ! empty( $_POST['calendar_price'] ) ? esc_attr( $_POST['calendar_price'] ) : '0' ); ?>" />
					</span>
				<?php endif; ?>

				<!-- Select Calendar Legend -->
				<?php if( ! empty( $calendars ) ): ?>

					<label for="wpbs-new-calendar-legend"><?php echo __( 'Copy Legend', 'wp-booking-system' ); ?><?php echo wpbs_get_output_tooltip( __( "If you wish to copy the legend items from one calendar to this new one, select from which calendar from the drop-down below.", 'wp-booking-system' ) ); ?></label>
					<select id="wpbs-new-calendar-legend" name="calendar_legend">
						<option value=""><?php echo __( 'Select calendar...', 'wp-booking-system' ); ?></option>
						<?php if( ! empty( $calendars ) ): ?>
							<?php foreach( $calendars as $calendar ): ?>
								<option value="<?php echo $calendar->get('id'); ?>"><?php printf( __( 'Copy from calendar #%d (%s)', 'wp-booking-system' ), $calendar->get('id'), $calendar->get('name') ); ?></option>
							<?php endforeach; ?>
						<?php endif; ?>
					</select>

				<?php endif; ?>
			
			</div>

			<!-- Form Submit button -->
			<div id="major-publishing-actions">
				<a href="<?php echo admin_url( $this->admin_url ); ?>"><?php echo __( 'Cancel', 'wp-booking-system' ); ?></a>
				<input type="submit" class="button-primary wpbs-button-large" value="<?php echo __( 'Add Calendar', 'wp-booking-system' ); ?>" />
			</div>

			<!-- Action and nonce -->
			<input type="hidden" name="wpbs_action" value="add_calendar" />
			<?php wp_nonce_field( 'wpbs_add_calendar', 'wpbs_token', false ); ?>

		</div>

	</form>

</div>