<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

$calendar_id = ( ! empty( $_GET['calendar_id'] ) ? absint( $_GET['calendar_id'] ) : 0 );
$calendar    = wpbs_get_calendar( $calendar_id );

$legend_items 		 = wpbs_get_legend_items( array( 'calendar_id' => $calendar_id ) );

?>

<div class="wrap wpbs-wrap wpbs-wrap-export-csv">

	<!-- Page Heading -->
	<h1 class="wp-heading-inline"><?php echo __( 'CSV Export', 'wp-booking-system' ); ?><span class="wpbs-heading-tag"><?php printf( __( 'Calendar ID: %d', 'wp-booking-system' ), $calendar_id ); ?></span></h1>
	
	<!-- Page Heading Actions -->
	<div class="wpbs-heading-actions">
		<a href="<?php echo add_query_arg( array( 'subpage' => 'edit-calendar' ) ); ?>" class="button-secondary"><?php echo __( 'Back to Calendar', 'wp-booking-system' ); ?></a>
	</div>

	<hr class="wp-header-end" />

	<!-- Dashboard Widgets Wrapper -->
	<div>

		<div id="dashboard-widgets" class="metabox-holder ">

			<!-- Row -->
			<div class="wpbs-row">

				<!-- Col 1-1 -->
				<div class="wpbs-col-1-1">

					<!-- Postbox Export -->
					<div class="postbox" style="margin-bottom: 0;">

						<form method="POST" action="">

							<h2 class="hndle"><span class="dashicons dashicons-media-spreadsheet"></span>&nbsp;&nbsp;<?php echo __( 'Export', 'wp-booking-system' ); ?></h2>

							<!-- Form Fields -->
							<div class="inside">

								<!-- Export Legend Items -->
								<div class="wpbs-settings-field-wrapper">

									<label class="wpbs-settings-field-label" for="csv-export-legend-items"><?php echo __( 'Legend Items to Export', 'wp-booking-system' ); ?></label>

									<div class="wpbs-settings-field-inner wpbs-chosen-wrapper">

										<select multiple class="wpbs-chosen" name="csv-export-legend-items[]" id="csv-export-legend-items">
											<?php 
												foreach( $legend_items as $legend_item ) {
													echo '<option ' . ( isset($_POST['csv-export-legend-items']) && in_array($legend_item->get('id'), $_POST['csv-export-legend-items']) ? 'selected' : '' ) . ' value="' . esc_attr( $legend_item->get('id') ) . '">' . $legend_item->get('name') . '</option>';
												}
											?>
										</select>

										<small><em><?php echo __('Leave empty if you wish to export all legend items.', 'wp-booking-system') ?></em></small>

									</div>

								</div>

								<!-- Export Format -->
								<div class="wpbs-settings-field-wrapper">

									<label class="wpbs-settings-field-label" for="csv-date-format"><?php echo __( 'Date Format', 'wp-booking-system' ); ?></label>

									<div class="wpbs-settings-field-inner">

										<select name="csv-date-format" id="csv-date-format">
											<option <?php echo (isset($_POST['csv-date-format']) && !empty($_POST['csv-date-format'])) ? selected($_POST['csv-date-format'], 'groupped_date', false) : ''; ?> value="groupped_date"><?php echo __('as a single field', 'wp-booking-system') ?></option>
											<option <?php echo (isset($_POST['csv-date-format']) && !empty($_POST['csv-date-format'])) ? selected($_POST['csv-date-format'], 'individual_dates', false) : ''; ?> value="individual_dates"><?php echo __('separate year, month and day fields', 'wp-booking-system') ?></option>
										</select>

									</div>

								</div>

								<!-- Period -->
								<div class="wpbs-settings-field-wrapper">

									<label class="wpbs-settings-field-label" for="csv-export-period"><?php echo __( 'Export Period', 'wp-booking-system' ); ?></label>

									<div class="wpbs-settings-field-inner">

										<input value="<?php echo (isset($_POST['wpbs-export-csv-start-date']) && !empty($_POST['wpbs-export-csv-start-date'])) ? esc_attr($_POST['wpbs-export-csv-start-date']) : ''; ?>" type="text" class="wpbs-export-csv-date" name="wpbs-export-csv-start-date" id="wpbs-export-csv-start-date" placeholder="from" readonly>

										<input value="<?php echo (isset($_POST['wpbs-export-csv-end-date']) && !empty($_POST['wpbs-export-csv-end-date'])) ? esc_attr($_POST['wpbs-export-csv-end-date']) : ''; ?>" type="text" class="wpbs-export-csv-date" name="wpbs-export-csv-end-date" id="wpbs-export-csv-end-date" placeholder="to" readonly>
										
									</div>

								</div>

								<!-- iCalendar Events -->
								<div class="wpbs-settings-field-wrapper">

									<label class="wpbs-settings-field-label" for="csv-icalendar-events"><?php echo __( 'Include iCalendar Events?', 'wp-booking-system' ); ?></label>

									<div class="wpbs-settings-field-inner">

										<select name="csv-icalendar-events" id="csv-icalendar-events">
											<option <?php echo (isset($_POST['csv-icalendar-events']) && !empty($_POST['csv-icalendar-events'])) ? selected($_POST['csv-icalendar-events'], 'no', false) : ''; ?> value="no">No</option>
											<option <?php echo (isset($_POST['csv-icalendar-events']) && !empty($_POST['csv-icalendar-events'])) ? selected($_POST['csv-icalendar-events'], 'yes', false) : ''; ?> value="yes">Yes</option>
										</select>

									</div>

								</div>

							</div>

							<!-- Card Bottom -->
							<div class="wpbs-plugin-card-bottom plugin-card-bottom">
								
								<input type="submit" class="button-primary" value="<?php echo __( 'Export CSV', 'wp-booking-system' ); ?>" />
							</div>

							<!-- Calendar ID -->
							<input type="hidden" name="calendar_id" value="<?php echo $calendar_id; ?>" />

							<!-- Nonce -->
							<?php wp_nonce_field( 'wpbs_csv_export', 'wpbs_token', false ); ?>
							<input type="hidden" name="wpbs_action" value="csv_export" />

						</form>

					</div>

				</div>

			</div><!-- / Row -->


		</div>

	</div>

</div>