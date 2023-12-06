<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

$calendar_id = ( ! empty( $_GET['calendar_id'] ) ? absint( $_GET['calendar_id'] ) : 0 );
$calendar    = wpbs_get_calendar( $calendar_id );

$legend_items 		 = wpbs_get_legend_items( array( 'calendar_id' => $calendar_id ) );
$export_legend_items = wpbs_get_calendar_meta( $calendar_id, 'ical_export_legend_items', true );
$group_events_by_description = wpbs_get_calendar_meta( $calendar_id, 'group_events_by_description', true );

if( empty( $export_legend_items ) )
	$export_legend_items = array();

$ical_status = wpbs_get_calendar_meta($calendar_id, 'disable_icalendar_links', true);

?>

<div class="wrap wpbs-wrap">

	<!-- Page Heading -->
	<h1 class="wp-heading-inline"><?php echo __( 'iCal Import/Export', 'wp-booking-system' ); ?><span class="wpbs-heading-tag"><?php printf( __( 'Calendar ID: %d', 'wp-booking-system' ), $calendar_id ); ?></span></h1>
	
	<!-- Page Heading Actions -->
	<div class="wpbs-heading-actions">
		<a href="<?php echo add_query_arg( array( 'subpage' => 'edit-calendar' ) ); ?>" class="button-secondary"><?php echo __( 'Back to Calendar', 'wp-booking-system' ); ?></a>
	</div>

	<hr class="wp-header-end" />

	<!-- Dashboard Widgets Wrapper -->
	<div>

		<div id="dashboard-widgets" class="metabox-holder">

			<!-- Row -->
			<div class="wpbs-row">

				<!-- Col 1-1 -->
				<div class="wpbs-col-1-1">

					<!-- Postbox Export -->
					<div class="postbox" style="margin-bottom: 0;">

						<form method="POST" action="">

							<h2 class="hndle"><span class="dashicons dashicons-upload"></span>&nbsp;&nbsp;<?php echo __( 'Export', 'wp-booking-system' ); ?></h2>

							<!-- Form Fields -->
							<div class="inside">

								<!-- iCal Feed Link -->
								<div class="wpbs-settings-field-wrapper">

									<label class="wpbs-settings-field-label"><?php echo __( 'iCalendar Link', 'wp-booking-system' ); ?></label>

									<div class="wpbs-settings-field-inner">
										<input id="wpbs-settings-field-ical-export" readonly type="text" value="<?php echo add_query_arg( array( 'wpbs-ical' => $calendar->get('ical_hash') ), site_url() . '/' ); ?>.ics" />
									</div>
									
								</div>

								<!-- Export as Booked Legend Items -->
								<div class="wpbs-settings-field-wrapper">

									<label class="wpbs-settings-field-label"><?php echo __( 'Legend Items to Export as Booked', 'wp-booking-system' ); ?></label>

									<div class="wpbs-settings-field-inner wpbs-chosen-wrapper">

										<select multiple class="wpbs-chosen" name="ical_export_legend_items[]" id="ical-export-legend-items">
											<?php 
												foreach( $legend_items as $legend_item ) {
													echo '<option value="' . esc_attr( $legend_item->get('id') ) . '" ' . ( in_array( $legend_item->get('id'), $export_legend_items ) ? 'selected' : '' ) . '>' . $legend_item->get('name') . '</option>';
												}
											?>
										</select>

										<div class="wpbs-warning"><span class="dashicons dashicons-info"></span> <?php echo __('When exporting changeover days, do not include the ending changeover in the list above.', 'wp-booking-system') ?></div>

									</div>

								</div>

								<!-- Group events by description -->
								<div class="wpbs-settings-field-wrapper">

									<label class="wpbs-settings-field-label">
										<?php echo __( 'Group Events by Description', 'wp-booking-system' ); ?>
										<?php echo wpbs_get_output_tooltip( __( 'If selected, events that have the same description will be grouped into one event spaning over multiple days.', 'wp-booking-system' ) ); ?>
									</label>

									<div class="wpbs-settings-field-inner">

										<label for="group_events_by_description">
											<input type="checkbox" id="group_events_by_description" name="group_events_by_description" value="1" <?php checked( '1', $group_events_by_description ); ?>  >
											<?php echo __('Yes', 'wp-booking-system') ?>
										</label>


									</div>

								</div>
							
							</div>

							<!-- Card Bottom -->
							<div class="wpbs-plugin-card-bottom plugin-card-bottom">
								<a class="button-secondary" onclick="return confirm('<?php echo __( 'Are you sure you want to reset the iCalendar feed link for this calendar?', 'wp-booking-system' ); ?>' )" href="<?php echo ( wp_nonce_url( add_query_arg( array( 'wpbs_action' => 'reset_private_link' ), remove_query_arg( 'wpbs_message' ) ), 'wpbs_reset_private_link', 'wpbs_token' ) ); ?>"><?php echo __( 'Reset Private Link', 'wp-booking-system' ); ?></a>
								<input type="submit" class="button-primary wpbs-float-right" value="<?php echo __( 'Save Preferences', 'wp-booking-system' ); ?>" />
							</div>

							<!-- Calendar ID -->
							<input type="hidden" name="calendar_id" value="<?php echo $calendar_id; ?>" />

							<!-- Nonce -->
							<?php wp_nonce_field( 'wpbs_ical_export_save_preferences', 'wpbs_token', false ); ?>
							<input type="hidden" name="wpbs_action" value="ical_export_save_preferences" />

						</form>

					</div>

				</div>

			</div><!-- / Row -->

			<!-- Row -->
			<div class="wpbs-row">

				<!-- Col 1-2 -->
				<div class="wpbs-col-1-2">

					<!-- Postbox Import from File -->
					<div class="postbox">

						<form id="wpbs-ical-file-import" enctype="multipart/form-data" method="POST" action="">

							<h2 class="hndle">
								<span class="dashicons dashicons-download"></span>&nbsp;&nbsp;<?php echo __( 'Import from File', 'wp-booking-system' ); ?>
								<?php echo wpbs_get_output_tooltip( __( 'Importing from a .ics file will insert the iCal events into the calendar. You will then be able to edit the details for each imported date.', 'wp-booking-system' ) ); ?>
							</h2>

							<!-- Form Fields -->
							<div class="inside">

								<!-- iCal File -->
								<div class="wpbs-settings-field-wrapper">

									<label class="wpbs-settings-field-label"><?php echo __( 'iCalendar File', 'wp-booking-system' ); ?> *</label>

									<div class="wpbs-settings-field-inner">
										<input id="wpbs-settings-field-ical-file" name="ical_file_import_file" type="file" value="" />
									</div>
									
								</div>

								<!-- Set Legend Item -->
								<div class="wpbs-settings-field-wrapper">

									<label class="wpbs-settings-field-label">
										<?php echo __( 'Import iCal Events As', 'wp-booking-system' ); ?> *
										<?php echo wpbs_get_output_tooltip( __( 'The dates from the iCal events present in the file will be assigned the legend item that you choose here.', 'wp-booking-system' ) ); ?>
									</label>

									<div class="wpbs-settings-field-inner">

										<select name="ical_file_import_legend_item_default">
											<option value=""><?php echo __( 'Select...', 'wp-booking-system' ); ?></option>
											<?php 
												foreach( $legend_items as $legend_item ) {
													echo '<option value="' . absint( $legend_item->get('id') ) . '">' . $legend_item->get('name') . '</option>';
												}
											?>
										</select>

									</div>

								</div>

								<!-- Overwrite existing -->
								<div class="wpbs-settings-field-wrapper">

									<label class="wpbs-settings-field-label">
										<?php echo __( 'Overwrite Existing Dates', 'wp-booking-system' ); ?> *
										<?php echo wpbs_get_output_tooltip( __( 'Select whether or not to overwrite data for dates in the calendar that already contain information. Selecting "Overwrite" will add all events present in the iCal file, regardless of the existing information for the calendar dates. Selecting "Skip" will add data only for dates that do not have any information set.', 'wp-booking-system' ) ); ?>
									</label>

									<div class="wpbs-settings-field-inner">

										<select name="ical_import_file_overwrite">
											<option value=""><?php echo __( 'Select...', 'wp-booking-system' ); ?></option>
											<option value="overwrite"><?php echo __( 'Overwrite', 'wp-booking-system' ); ?></option>
											<option value="skip"><?php echo __( 'Skip', 'wp-booking-system' ); ?></option>
										</select>

									</div>

								</div>

								<!-- Description Import -->
								<div class="wpbs-settings-field-wrapper">

									<label class="wpbs-settings-field-label">
										<?php echo __( 'Description Import', 'wp-booking-system' ); ?> *
										<?php echo wpbs_get_output_tooltip( __( 'Select how you wish the description of each event from the .ics file to be imported into the plugin.', 'wp-booking-system' ) ); ?>
									</label>

									<div class="wpbs-settings-field-inner">

										<select name="ical_import_file_description">
											<option value=""><?php echo __( 'Select...', 'wp-booking-system' ); ?></option>
											<option value="only_description"><?php echo __( 'Import only in the description field', 'wp-booking-system' ); ?></option>
											<option value="only_tooltip"><?php echo __( 'Import only in the tooltip field', 'wp-booking-system' ); ?></option>
											<option value="both"><?php echo __( 'Import in both description and tooltip fields', 'wp-booking-system' ); ?></option>
											<option value="none"><?php echo __( 'Do not import the description', 'wp-booking-system' ); ?></option>
										</select>

									</div>

								</div>
							
							</div>

							<!-- Card Bottom -->
							<div class="wpbs-plugin-card-bottom plugin-card-bottom">
								<input type="submit" disabled class="button-primary wpbs-float-right" value="<?php echo __( 'Import iCal File', 'wp-booking-system' ); ?>" />
								<div class="clear"><!-- --></div>
							</div>

							<!-- Calendar ID -->
							<input type="hidden" name="calendar_id" value="<?php echo $calendar_id; ?>" />

							<!-- Nonce -->
							<?php wp_nonce_field( 'wpbs_ical_import_file', 'wpbs_token', false ); ?>
							<input type="hidden" name="wpbs_action" value="ical_import_file" />

						</form>

					</div>

				</div><!-- / Col-1-2 -->

				<!-- Col 1-2 -->
				<div class="wpbs-col-1-2">

					<!-- Postbox Import from iCal URL -->
					<div class="postbox">

						<form id="wpbs-ical-url-import" method="POST" action="">

							<?php $ical_feeds = wpbs_get_calendar_meta_ical_feeds( $calendar_id );  ?>

							<h2 class="hndle">
								<span class="dashicons dashicons-download"></span>&nbsp;&nbsp;<?php echo __( 'Import from iCal URL', 'wp-booking-system' ); ?>
								<?php echo wpbs_get_output_tooltip( __( 'Importing from an iCal URL will overlay the events from the URL. You will not be able to edit these events.', 'wp-booking-system' ) ); ?>
								
								<?php if(empty($ical_status) || $ical_status != true): ?>
									<a class="wpbs-ical-import-disable" href="<?php echo wp_nonce_url( add_query_arg( array( 'page' => 'wpbs-calendars', 'wpbs_action' => 'disable_icalendar_links', 'calendar_id' => $calendar_id ) , admin_url( 'admin.php' ) ), 'wpbs_disable_icalendar_links', 'wpbs_token' );?>"><?php echo __( 'Disable iCalendar Links', 'wp-booking-system' ); ?></a>
								<?php else: ?>
									<a class="wpbs-ical-import-disable" href="<?php echo wp_nonce_url( add_query_arg( array( 'page' => 'wpbs-calendars', 'wpbs_action' => 'enable_icalendar_links', 'calendar_id' => $calendar_id ) , admin_url( 'admin.php' ) ), 'wpbs_enable_icalendar_links', 'wpbs_token' );?>"><?php echo __( 'Enable iCalendar Links', 'wp-booking-system' ); ?></a>
								<?php endif; ?>
							</h2>

							<div class="wpbs-ical-url-import-wrapper <?php if(!empty($ical_status) && $ical_status == true): ?>disabled<?php endif;?>">

								<?php if( empty( $ical_feeds ) ): ?>

									<div class="inside" style="border-bottom: 1px solid #eee;">

										<p><?php echo __( "You don't have any iCal URL's included. Use the form below to add an iCal feed URL.", 'wp-booking-system' ); ?></p>

									</div>

								<?php else: ?>

									<?php foreach( $ical_feeds as $ical_feed ): ?>

										<div class="wpbs-ical-feed-calendar">

											<?php 
												$disabled = (isset($ical_feed['disabled']) && $ical_feed['disabled'] == true) ? true : false;
												$legend_item = wpbs_get_legend_item( $ical_feed['legend_item_id'] );
												if($legend_item){
													echo wpbs_get_legend_item_icon( $legend_item->get('id'), $legend_item->get('type'), $legend_item->get('color') );
												}
											?>

											<div class="wpbs-ical-feed-calendar-inner wpbs-ical-feed-calendar-inner-<?php echo $disabled ? 'disabled' : 'enabled';?>">
												<strong><?php echo $ical_feed['name']; ?></strong>
												<p title="<?php echo esc_attr( $ical_feed['url'] ); ?>"><?php echo esc_attr( $ical_feed['url'] ); ?></p>
												<small>
													<?php echo sprintf( __( 'Last updated: %s', 'wp-booking-system' ), wpbs_date_i18n( get_option( 'date_format' ) . ' ' . get_option( 'time_format' ), strtotime( $ical_feed['last_updated'] ) ) ); ?>
													| <?php _e('Split Days', 'wp-booking-system') ?>: <?php echo $ical_feed['split_days'] ? __('Yes', 'wp-booking-system') : __('No', 'wp-booking-system'); ?>
												</small>
												<?php if(!$legend_item): ?>
													<span class="wpbs-ical-error"><?php echo __('The legend item assigned to this iCalendar link no longer exists.', 'wp-booking-system') ?></span>
												<?php endif; ?>
											</div>

											<a href="<?php echo wp_nonce_url( add_query_arg( array( 'wpbs_action' => 'toggle_ical_feed', 'ical_feed_id' => $ical_feed['id'] ) ), 'wpbs_toggle_ical_feed', 'wpbs_token' ); ?>" class="wpbs-disable"><?php echo $disabled ? __( 'Enable', 'wp-simple-booking-calendar' ) : __( 'Disable', 'wp-simple-booking-calendar' ); ?></a>

											<a onclick="return confirm('<?php echo __( 'Are you sure you want to remove this iCal URL?', 'wp-booking-system' ); ?>');" href="<?php echo wp_nonce_url( add_query_arg( array( 'wpbs_action' => 'remove_ical_feed', 'ical_feed_id' => $ical_feed['id'] ), remove_query_arg( 'wpbs_message' ) ), 'wpbs_remove_ical_feed', 'wpbs_token' ); ?>" class="wpbs-trash"><?php echo __( 'Remove', 'wp-booking-system' ); ?></a>

										</div>

									<?php endforeach; ?>


								<?php endif; ?>


								<!-- Add iCal URL form -->
								<div class="inside">

									<!-- Calendar Name -->
									<div class="wpbs-settings-field-wrapper">

										<label class="wpbs-settings-field-label"><?php echo __( 'Calendar Name', 'wp-booking-system' ); ?> *</label>

										<div class="wpbs-settings-field-inner">
											<input name="ical_url_import_name" type="text" value="" />
										</div>
										
									</div>

									<!-- iCal URL -->
									<div class="wpbs-settings-field-wrapper">

										<label class="wpbs-settings-field-label"><?php echo __( 'iCalendar URL', 'wp-booking-system' ); ?> *</label>

										<div class="wpbs-settings-field-inner">
											<input name="ical_url_import_url" type="text" value="" />
										</div>
										
									</div>

									<!-- Set Legend Item -->
									<div class="wpbs-settings-field-wrapper">

										<label class="wpbs-settings-field-label">
											<?php echo __( 'Import iCal Events As', 'wp-booking-system' ); ?> *
										</label>

										<div class="wpbs-settings-field-inner">

											<select name="ical_url_import_legend_item_default">
												<option value=""><?php echo __( 'Select...', 'wp-booking-system' ); ?></option>
												<?php 
													foreach( $legend_items as $legend_item ) {
														echo '<option value="' . absint( $legend_item->get('id') ) . '">' . $legend_item->get('name') . '</option>';
													}
												?>
											</select>

										</div>

									</div>

									<!-- Use split days -->
									<div class="wpbs-settings-field-wrapper">

										<label class="wpbs-settings-field-label">
											<?php echo __( 'Use Split Days', 'wp-booking-system' ); ?>
											<?php echo wpbs_get_output_tooltip(__("If selected, the start and end dates of the iCalendar Events will be displayed as Split Days. You will have to chose the legends to be used below.", 'wp-booking-system'));?>
											
										</label>

										<div class="wpbs-settings-field-inner">

											<label for="ical_url_import_split_days">
												<input type="checkbox" id="ical_url_import_split_days" name="ical_url_import_split_days" value="1">
												Yes
											</label>

										</div>

									</div>

									<!-- Set Legend Item for Starting Split Day  -->
									<div class="wpbs-settings-field-wrapper wpbs-settings-field-wrapper-left wpbs-settings-field-conditional">

										<label class="wpbs-settings-field-label">
											<?php echo __( 'Split Day Start', 'wp-booking-system' ); ?> *
										</label>

										<div class="wpbs-settings-field-inner">

											<select name="ical_url_import_legend_item_split_day_start">
												<option value=""><?php echo __( 'Select...', 'wp-booking-system' ); ?></option>
												<?php 
													foreach( $legend_items as $legend_item ) {
														echo '<option value="' . absint( $legend_item->get('id') ) . '">' . $legend_item->get('name') . '</option>';
													}
												?>
											</select>

										</div>

									</div>

									<!-- Set Legend Item for Ending Split Day  -->
									<div class="wpbs-settings-field-wrapper wpbs-settings-field-wrapper-right wpbs-settings-field-conditional">

										<label class="wpbs-settings-field-label">
											<?php echo __( 'Split Day End', 'wp-booking-system' ); ?> *
										</label>

										<div class="wpbs-settings-field-inner">

											<select name="ical_url_import_legend_item_split_day_end">
												<option value=""><?php echo __( 'Select...', 'wp-booking-system' ); ?></option>
												<?php 
													foreach( $legend_items as $legend_item ) {
														echo '<option value="' . absint( $legend_item->get('id') ) . '">' . $legend_item->get('name') . '</option>';
													}
												?>
											</select>

										</div>

									</div>

									<div class="wpbs-clear"><!-- --></div>

								</div><!-- / Add iCal URL form -->

							</div>

							<!-- Card Bottom -->
							<div class="wpbs-plugin-card-bottom plugin-card-bottom">

								<?php if( ! empty( $ical_feeds ) ): ?>
									<a onclick="return confirm('<?php echo __( 'Are you sure you want to refresh this iCal feeds?', 'wp-booking-system' ); ?>');" href="<?php echo wp_nonce_url( add_query_arg( array( 'wpbs_action' => 'refresh_ical_feeds' ), remove_query_arg( 'wpbs_message' ) ), 'wpbs_refresh_ical_feeds', 'wpbs_token' ); ?>" class="button-secondary"><?php echo __( 'Refresh iCal Data', 'wp-booking-system' );?></a>
								<?php endif; ?>

								<input type="submit" disabled class="button-primary wpbs-float-right" value="<?php echo __( 'Add iCal URL', 'wp-booking-system' ); ?>" />
								
								<div class="clear"><!-- --></div>

							</div>

							<!-- Calendar ID -->
							<input type="hidden" name="calendar_id" value="<?php echo $calendar_id; ?>" />

							<!-- Nonce -->
							<?php wp_nonce_field( 'wpbs_ical_import_url', 'wpbs_token', false ); ?>
							<input type="hidden" name="wpbs_action" value="ical_import_url" />

						</form>

					</div>

				</div><!-- / Col-1-2 -->

			</div><!-- / Row -->

		</div>

	</div>

</div>