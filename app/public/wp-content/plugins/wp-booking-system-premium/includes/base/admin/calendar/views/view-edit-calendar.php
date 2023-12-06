<?php

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

$calendar_id = absint(!empty($_GET['calendar_id']) ? $_GET['calendar_id'] : 0);
$calendar = wpbs_get_calendar($calendar_id);

if (is_null($calendar)) {
    return;
}

$current_year = (!empty($_GET['year']) ? absint($_GET['year']) : current_time('Y'));
$current_month = (!empty($_GET['month']) ? absint($_GET['month']) : current_time('n'));

$settings = get_option('wpbs_settings', array());

$removable_query_args = wp_removable_query_args();

$legend_items = wpbs_get_legend_items(array('calendar_id' => $calendar_id));

$languages = wpbs_get_languages();
$active_languages = ( ! empty( $settings['active_languages'] ) ? $settings['active_languages'] : array() );

?>

<?php if(isset($_GET['booking_id']) && !empty($_GET['booking_id'])): ?>
	<script>
		jQuery(document).ready(function () {
			wpbs_open_booking_modal(<?php echo $_GET['booking_id'];?>, '<?php echo isset($_GET['tab']) ? esc_attr($_GET['tab']) : ''; ?>');
		});
	</script>
<?php endif; ?>

<?php if(isset($_GET['add_booking'])): ?>
	<script>
		jQuery(document).ready(function () {
			jQuery('#wpbs-add-booking-open-modal').trigger('click');
		});
	</script>
<?php endif; ?>

<div class="wrap wpbs-wrap wpbs-wrap-edit-calendar">

	<form method="POST" action="" autocomplete="off">

		<!-- Page Heading -->
		<h1 class="wp-heading-inline"><?php echo __('Edit Calendar', 'wp-booking-system'); ?><span class="wpbs-heading-tag"><?php printf(__('Calendar ID: %d', 'wp-booking-system'), $calendar_id);?></span></h1>

		<!-- Page Heading Actions -->
		<div class="wpbs-heading-actions">

			<!-- Back Button -->
			<a href="<?php echo add_query_arg(array('page' => 'wpbs-calendars'), admin_url('admin.php')); ?>" class="button-secondary"><?php echo __('Back to all calendars','wp-booking-system') ?></a>

			<!-- Save button -->
			<input type="submit" class="wpbs-save-calendar button-primary" value="<?php echo __('Save Calendar', 'wp-booking-system'); ?>" />

		</div>

		<hr class="wp-header-end" />

		<?php if(wpbs_check_for_disabled_ical_feeds($calendar_id)): ?>
			<div class="notice-warning notice">
				<p><?php echo sprintf(__('One or more of your iCalendar feeds are disabled. %sClick here to fix this.%s', 'wp-booking-system'), '<a href="'.add_query_arg(array('page' => 'wpbs-calendars', 'subpage' => 'ical-import-export', 'calendar_id' => $calendar_id), admin_url('admin.php')).'">', '</a>') ?></p>
			</div>
		<?php endif; ?>

		<div id="poststuff">

			<!-- Calendar Title -->
			<div id="titlediv">
				<div id="titlewrap">
					<input type="text" name="calendar_name" size="30" value="<?php echo esc_attr($calendar->get('name')) ?>" id="title">

					<?php if(isset($settings['active_languages']) && count($settings['active_languages']) > 0): ?>

						<a href="#" class="titlewrap-toggle"><?php echo __('Translate calendar title','wp-booking-system') ?> <svg aria-hidden="true" focusable="false" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512" ><path fill="currentColor" d="M31.3 192h257.3c17.8 0 26.7 21.5 14.1 34.1L174.1 354.8c-7.8 7.8-20.5 7.8-28.3 0L17.2 226.1C4.6 213.5 13.5 192 31.3 192z" class=""></path></svg></a>
						<div class="titlewrap-translations">
							<?php foreach($settings['active_languages'] as $language): ?>
								<div class="titlewrap-translation">
									<div class="titlewrap-translation-flag"><img src="<?php echo WPBS_PLUGIN_DIR_URL; ?>assets/img/flags/<?php echo $language;?>.png" /></div>
									<input type="text" name="calendar_name_translation_<?php echo $language;?>" size="30" value="<?php echo esc_attr( wpbs_get_calendar_meta($calendar->get('id'), 'calendar_name_translation_' . $language, true) ) ?>" >
								</div>
							<?php endforeach; ?>
						</div>

					<?php endif ?>
				</div>
			</div>

			<div id="wpbs-bookings-postbox">
				<!-- Availability -->
				<div class="postbox">

					<h3 class="hndle">
						<?php echo __('Bookings', 'wp-booking-system'); ?>
						
						<a class="wpbs-bookings-export" href="<?php echo wp_nonce_url(add_query_arg(array('page' => 'wpbs-calendars', 'subpage' => 'edit-calendar', 'wpbs_action' => 'export_bookings', 'calendar_id' => $calendar_id), admin_url('admin.php')),'wpbs_export_bookings', 'wpbs_token'); ?>">Export CSV</a>
					</h3>

					<div class="inside">
						
						<?php
							$bookings_outputter = new WPBS_Bookings_Outputter($calendar_id);
							$bookings_outputter->display();
						?>

					</div>
				</div>
			</div>

			<div id="post-body" class="metabox-holder columns-2">

				<!-- Main Post Body Content -->
				<div id="post-body-content">

					<!-- Availability -->
					<div class="postbox">

						<h3 class="hndle"><?php echo __('Edit Dates', 'wp-booking-system'); ?></h3>

						<div class="inside">

							<div id="wpbs-calendar-events">
								<?php
								$calendar_args = array(
									'current_year' => $current_year,
									'current_month' => $current_month,
								);

								$calendar_editor = new WPBS_Calendar_Editor_Outputter($calendar, $calendar_args);
								$calendar_editor->display();

								
								?>
							</div>

						</div>
					</div>

					<!-- Link Calendar -->
					<div class="postbox wpbs-calendar-link-postbox">

						<h3 class="hndle"><?php echo __('Link Calendar', 'wp-booking-system'); ?><?php echo wpbs_get_output_tooltip(__("You can attach a post/page from your WordPress website or an external link to this calendar. If the calendar has a link attached, everywhere the calendar's title is displayed, the link will automatically be added to the title.", 'wp-booking-system')); ?></h3>

						<div class="inside">

							<div class="wpbs-row wpbs-last">

								<!-- Link Type -->
								<div class="wpbs-col-1-4">

									<div class="wpbs-settings-field-wrapper wpbs-last">

										<label class="wpbs-settings-field-label"><?php echo __('Link Type', 'wp-booking-system'); ?></label>

										<div class="wpbs-settings-field-inner">

											<?php $calendar_link_type = wpbs_get_calendar_meta($calendar->get('id'), 'calendar_link_type', true);?>

											<select name="calendar_link_type">
												<option value="internal" <?php selected($calendar_link_type, 'internal', true);?>><?php echo __('Internal', 'wp-booking-system'); ?></option>
												<option value="external" <?php selected($calendar_link_type, 'external', true);?>><?php echo __('External', 'wp-booking-system'); ?></option>
											</select>
										</div>

									</div>

								</div>

								<!-- Actual Link -->
								<div class="wpbs-col-3-4">

									<!-- Internal Link -->
									<div id="wpbs-settings-field-wrapper-calendar-link-internal" class="wpbs-settings-field-wrapper wpbs-settings-field-wrapper-link-calendar wpbs-last">

										<label class="wpbs-settings-field-label"><?php echo __('Select Internal Link', 'wp-booking-system'); ?></label>
										
										<?php $post_types_dropdown = wpbs_get_post_types_as_dropdown();?>

										<div class="wpbs-settings-field-translation-wrapper">

											<div class="wpbs-settings-field-inner">

												<?php $calendar_link_internal = wpbs_get_calendar_meta($calendar->get('id'), 'calendar_link_internal', true); ?>

												<select name="calendar_link_internal">
													<option value=""><?php echo __('Select...', 'wp-booking-system'); ?></option>
													<?php foreach($post_types_dropdown as $post_type => $posts): ?>
														<optgroup label="<?php echo $post_type;?>">
															<?php foreach($posts as $post_id => $post_title): ?>
																<option value="<?php echo $post_id;?>" <?php selected($calendar_link_internal, $post_id);?>><?php echo $post_title;?></option>
															<?php endforeach; ?>
														</optgroup>
													<?php endforeach; ?>
												</select>
												<?php if(wpbs_translations_active()): ?><a href="#" class="wpbs-settings-field-show-translations"><?php echo __( 'Translations', 'wp-booking-system' ); ?> <i class="wpbs-icon-down-arrow"></i></a><?php endif ?>

											</div>

											<?php if(wpbs_translations_active()): ?>
											<!-- Subject Translations -->
											<div class="wpbs-settings-field-translations">
												<?php foreach($active_languages as $language): ?>

													<?php $calendar_link_internal_translation = wpbs_get_calendar_meta($calendar->get('id'), 'calendar_link_internal_translation_'. $language, true); ?>

													<!-- Submit Button -->
													<div class="wpbs-settings-field-wrapper wpbs-settings-field-inline wpbs-settings-field-large">

														<label class="wpbs-settings-field-label" for="calendar_link_internal_translation_<?php echo $language;?>"><img src="<?php echo WPBS_PLUGIN_DIR_URL ;?>/assets/img/flags/<?php echo $language;?>.png" /> <?php echo $languages[$language];?></label>

														<div class="wpbs-settings-field-inner">
															<select name="calendar_link_internal_translation_<?php echo $language;?>" id="calendar_link_internal_translation_<?php echo $language;?>">
																<option value=""><?php echo __('Select...', 'wp-booking-system'); ?></option>
																<?php foreach($post_types_dropdown as $post_type => $posts): ?>
																	<optgroup label="<?php echo $post_type;?>">
																		<?php foreach($posts as $post_id => $post_title): ?>
																			<option value="<?php echo $post_id;?>" <?php selected($calendar_link_internal_translation, $post_id);?>><?php echo $post_title;?></option>
																		<?php endforeach; ?>
																	</optgroup>
																<?php endforeach; ?>
															</select>
														</div>
														
													</div>
												<?php endforeach; ?>
											</div>
											<?php endif ?>

										</div>

									</div>

									<!-- External Link -->
									<div id="wpbs-settings-field-wrapper-calendar-link-external" class="wpbs-settings-field-wrapper wpbs-settings-field-wrapper-link-calendar wpbs-last">

										<label class="wpbs-settings-field-label"><?php echo __('Add External Link', 'wp-booking-system'); ?></label>

										<div class="wpbs-settings-field-translation-wrapper">

											<div class="wpbs-settings-field-inner">

												<?php $calendar_link_external = wpbs_get_calendar_meta($calendar->get('id'), 'calendar_link_external', true);?>

												<input type="text" name="calendar_link_external" value="<?php echo esc_attr($calendar_link_external); ?>" />
												<?php if(wpbs_translations_active()): ?><a href="#" class="wpbs-settings-field-show-translations"><?php echo __( 'Translations', 'wp-booking-system' ); ?> <i class="wpbs-icon-down-arrow"></i></a><?php endif ?>
											</div>

											<?php if(wpbs_translations_active()): ?>
											<!-- Subject Translations -->
											<div class="wpbs-settings-field-translations">
												<?php foreach($active_languages as $language): ?>

													<!-- Submit Button -->
													<div class="wpbs-settings-field-wrapper wpbs-settings-field-inline wpbs-settings-field-large">

														<label class="wpbs-settings-field-label" for="calendar_link_external_translation_<?php echo $language;?>"><img src="<?php echo WPBS_PLUGIN_DIR_URL ;?>/assets/img/flags/<?php echo $language;?>.png" /> <?php echo $languages[$language];?></label>

														<div class="wpbs-settings-field-inner">
															<input name="calendar_link_external_translation_<?php echo $language;?>" type="text" id="calendar_link_external_translation_<?php echo $language;?>" value="<?php echo esc_attr(wpbs_get_calendar_meta($calendar->get('id'), 'calendar_link_external_translation_' . $language, true));?>" class="regular-text" >
														</div>
														
													</div>
												<?php endforeach; ?>
											</div>
											<?php endif ?>
										</div>

									</div>

								</div>

							</div>

						</div>

					</div>

					<!-- Notes -->
					<div class="postbox wpbs-calendar-notes-postbox">

						<h3 class="hndle"><?php echo __( 'Notes', 'wp-booking-system' ); ?></h3>

						<div class="inside">
							
							<div class="wpbs-calendar-notes">
								
								<?php wpbs_output_notes_html($calendar_id);?>
								
							</div>

							<div class="wpbs-calendar-new-note">
								<h4><?php echo __( 'Add new note', 'wp-booking-system' ); ?></h4>
								<textarea name="wpbs-calendar-note-content" id="wpbs-calendar-note-content" placeholder="<?php echo __( 'Start writing your note here...', 'wp-booking-system' ); ?>" cols="30" rows="10"></textarea>								
								<button class="button-secondary" id="wpbs-calendar-add-note"><?php echo __( 'Add Note', 'wp-booking-system' ); ?></button>
							</div>

						</div>
					</div>

					<?php

					/**
					 * Action hook to add extra form fields to the main calendar edit area
					 *
					 * @param WPBS_Calendar $calendar
					 *
					 */
					do_action('wpbs_view_edit_calendar_main', $calendar);

					?>

				</div><!-- / Main Post Body Content -->

				<!-- Sidebar Content -->
				<div id="postbox-container-1" class="postbox-container">

		 			<!-- Calendar -->
		 			<div class="postbox">

						<h3 class="hndle"><?php echo __('Calendar', 'wp-booking-system'); ?></h3>

						<div class="inside">

							<?php
							$calendar_args = array(
								'current_year' => $current_year,
								'current_month' => $current_month,
								'start_weekday' => (!empty($settings['backend_start_day']) ? (int) $settings['backend_start_day'] : 1),
								'show_title' => 0,
								'show_legend' => 0,
								'is_admin' => true
							);

							$calendar_args = apply_filters('wpbs_edit_calendar_outputter_args', $calendar_args, $calendar);

							$calendar_outputter = new WPBS_Calendar_Outputter($calendar, $calendar_args);
							$calendar_outputter->display();
							?>

						</div>
					</div><!-- / Calendar -->

					<!-- Availability Bulk Edit -->
		 			<div id="wpbs-bulk-edit-availability-wrapper" class="postbox">

						<h3 class="hndle"><?php echo __('Bulk Edit Availability', 'wp-booking-system'); ?></h3>

						<div class="inside">

							<!-- Start Date -->
							<p>
								<label for="wpbs-bulk-edit-availability-start-date"><?php echo __('Start Date', 'wp-booking-system'); ?></label>
								<input id="wpbs-bulk-edit-availability-start-date" type="text" class="wpbs-datepicker" placeholder="YYYY-MM-DD" readonly />
							</p>

							<!-- End Date -->
							<p>
								<label for="wpbs-bulk-edit-availability-end-date"><?php echo __('End Date', 'wp-booking-system'); ?></label>
								<input id="wpbs-bulk-edit-availability-end-date" type="text" class="wpbs-datepicker" placeholder="YYYY-MM-DD" readonly />
							</p>

							<!-- Week Days -->
							<p>
								<label for="wpbs-bulk-edit-availability-week-days" >
									<?php echo __('Week Days', 'wp-booking-system'); ?>
									<?php echo wpbs_get_output_tooltip(__('Only select specific week days from the date range chosen above. Leave empty to select all days.', 'wp-booking-system')); ?>
								</label>
								<span class="wpbs-bulk-edit-availability-week-days-wrapper">

									<?php $start_weekday = (!empty($settings['backend_start_day']) ? (int) $settings['backend_start_day'] : 1); ?>

									<?php for( $i = $start_weekday; $i < ( $start_weekday + 7 ); $i++ ):?>

										<?php $week_day_letter = wpbs_get_days_first_letters(wpbs_get_locale())[($i + 6) % 7];?>

										<?php $week_day = $i % 7; ?>
										
										<label for="wpbs-bulk-edit-availability-week-day-<?php echo $week_day;?>" class="wpbs-bulk-edit-availability-week-days">
											<span><?php echo $week_day_letter;?></span>
											<input type="checkbox" id="wpbs-bulk-edit-availability-week-day-<?php echo $week_day;?>" name="wpbs-bulk-edit-availability-week-day" class="wpbs-bulk-edit-availability-week-day" value="<?php echo $week_day;?>" />
										</label>

									<?php endfor; ?>

								</span>
							</p>

							<!-- Legend Item -->
							<p>
								<label for="wpbs-bulk-edit-availability-legend-item"><?php echo __('Legend Item', 'wp-booking-system'); ?></label>
								<select id="wpbs-bulk-edit-availability-legend-item">
									<option value=""></option>
									<?php
									foreach ($legend_items as $legend_item) {
										
										echo '<option value="' . esc_attr($legend_item->get('id')) . '">' . $legend_item->get_name(wpbs_get_locale()) . '</option>';

									}
									?>
								</select>
							</p>

							<?php

							/**
							 * Action hook to add extra form fields to the bulk editor
							 *
							 */
							do_action('wpbs_view_edit_calendar_bulk_editor_before');

							?>

							<!-- Description -->
							<p>
								<label for="wpbs-bulk-edit-availability-description"><?php echo __('Description', 'wp-booking-system'); ?></label>
								<input id="wpbs-bulk-edit-availability-description" type="text" />
							</p>

							<!-- Tooltip -->
							<p>
								<label for="wpbs-bulk-edit-availability-tooltip"><?php echo __('Tooltip', 'wp-booking-system'); ?></label>
								<input id="wpbs-bulk-edit-availability-tooltip" type="text" />
							</p>

							<!-- Overwrite -->
							<p>
								<label for="wpbs-bulk-edit-availability-overwrite" class="wpbs-bulk-edit-availability-overwrite">
									<input id="wpbs-bulk-edit-availability-overwrite" type="checkbox" checked />
									<?php echo __('Ignore empty fields', 'wp-booking-system'); ?>
									<?php echo wpbs_get_output_tooltip(__('If this box is ticked, the empty fields in the bulk editor will be ignored and will not clear existing data from the calendar.', 'wp-booking-system')); ?>
								</label>
								
							</p>

						</div>

						<div class="wpbs-plugin-card-bottom plugin-card-bottom">
							<a id="wpbs-bulk-edit-availability" class="button-secondary wpbs-float-right" href="#"><?php echo __('Bulk Edit', 'wp-booking-system'); ?></a>
							<a id="wpbs-bulk-edit-availability-undo" class="wpbs-inactive" href="#"><?php echo __('Undo', 'wp-booking-system'); ?></a>
							<?php echo wpbs_get_output_tooltip(__('Clicking the "Undo" link will undo the last bulk edit action you have made, just in case you have made a mistake.', 'wp-booking-system')); ?>
						</div>

					</div><!-- / Availability Bulk Edit -->

					<?php

					/**
					 * Action hook to add extra form fields to the main calendar edit area
					 *
					 * @param WPBS_Calendar $calendar
					 *
					 */
					do_action('wpbs_view_edit_calendar_sidebar_before', $calendar);

					?>

					<!-- Calendar Legend -->
		 			<div class="postbox">

						<h3 class="hndle"><?php echo __('Legend', 'wp-booking-system'); ?></h3>

						<div class="inside">

							<?php
							

							foreach ($legend_items as $legend_item) {

								echo '<div class="wpbs-legend-item">';
								echo wpbs_get_legend_item_icon($legend_item->get('id'), $legend_item->get('type'), $legend_item->get('color'));
								echo '<span class="wpbs-legend-item-name">' . $legend_item->get_name(wpbs_get_locale()) . '</span>';
								echo '</div>';

							}
							?>

						</div>

						<div class="wpbs-plugin-card-bottom plugin-card-bottom">
							<a class="button-secondary" href="<?php echo add_query_arg(array('subpage' => 'view-legend'), remove_query_arg($removable_query_args)); ?>"><?php echo __('Edit Legend Items', 'wp-booking-system'); ?></a>
						</div>

					</div><!-- / Calendar Legend -->

					<!-- iCal Export -->
					<div class="postbox">

						<h3 class="hndle"><?php echo __('iCal Import/Export', 'wp-booking-system'); ?></h3>

						<div class="inside">

							<p><?php echo __('To configure the iCal import & export settings and have access to the iCal export link please click the button below.', 'wp-booking-system'); ?></p>

							<a href="<?php echo add_query_arg(array('subpage' => 'ical-import-export'), remove_query_arg($removable_query_args)); ?>" class="button-secondary"><span class="dashicons dashicons-upload"></span> <?php echo __('iCal Import/Export', 'wp-booking-system'); ?></a>

						</div>

					</div><!-- / iCal Export -->

					<!-- CSV Export -->
					<div class="postbox">

						<h3 class="hndle"><?php echo __( 'CSV Export', 'wp-booking-system' ); ?></h3>

						<div class="inside">

							<p><?php echo __( 'To configure the CSV export settings please click the button below. ', 'wp-booking-system' ); ?></p>

							<a href="<?php echo add_query_arg( array( 'subpage' => 'csv-export' ), remove_query_arg( $removable_query_args ) ); ?>" class="button-secondary"><span class="dashicons dashicons-media-spreadsheet"></span> <?php echo __( 'CSV Export', 'wp-booking-system' ); ?></a>

						</div>

					</div><!-- / CSV Export -->

					<?php

					/**
					 * Action hook to add extra form fields to the main calendar edit area
					 *
					 * @param WPBS_Calendar $calendar
					 *
					 */
					do_action('wpbs_view_edit_calendar_sidebar_after', $calendar);

					?>

				</div><!-- / Sidebar Content -->

			</div><!-- / #post-body -->

		</div><!-- / #poststuff -->

		<!-- Hidden fields -->
		<input type="hidden" name="calendar_id" value="<?php echo $calendar_id; ?>" />

		<!-- Save button -->
		<input type="submit" class="wpbs-save-calendar button-primary" value="<?php echo __('Save Calendar', 'wp-booking-system'); ?>" />

		<!-- Save Button Spinner -->
		<div class="wpbs-save-calendar-spinner spinner"><!-- --></div>

		<div id="wpbs-placeholder-editor">
			<?php wp_editor('','wpbs_placeholder_editor', array('teeny' => true, 'textarea_rows' => 10, 'media_buttons' => false)); ?>
		</div>

	</form>

</div>

<!-- Add Booking modal -->
<?php include WPBS_PLUGIN_DIR . 'includes/base/admin/booking/views/view-add-booking.php'; ?>