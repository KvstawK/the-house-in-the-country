<?php
$booking_restrictions = wpbs_get_form_meta($form_id, 'booking_restrictions', true);
$booking_restrictions = (is_array($booking_restrictions)) ? $booking_restrictions : array(array(
    'period' => 'all',
    'booking_day_start' => array('any'),
    'booking_day_end' => array('any'),
));

$booking_restrictions = array_values($booking_restrictions);

$weekday = wpbs_get_weekdays();
?>

<!-- Enable Booking Restrictions -->
<div class="wpbs-settings-field-wrapper wpbs-settings-field-inline wpbs-settings-field-xlarge">
	<label class="wpbs-settings-field-label" for="booking_restrictions_enable">
        <?php echo __('Enable Booking Restrictions', 'wp-booking-system-booking-restrictions'); ?>
    </label>

	<div class="wpbs-settings-field-inner">
        <label for="booking_restrictions_enable" class="wpbs-checkbox-switch">
            <input type="hidden" name="booking_restrictions_enable" value="0">
            <input data-target="#wpbs-booking-restrictions-wrapper" name="booking_restrictions_enable" type="checkbox" id="booking_restrictions_enable" class="regular-text wpbs-settings-toggle wpbs-settings-wrap-toggle" <?php echo (!empty($form_meta['booking_restrictions_enable'][0])) ? 'checked' : ''; ?> >
            <div class="wpbs-checkbox-slider"></div>
        </label>
	</div>
</div>

<?php 
/**
 * Validation Notices
 * 
 */
$problems = wpbs_br_validate_restriction_settings($booking_restrictions); ?>
<?php if($problems) foreach($problems as $problem): ?>

    <!-- Validation Notice -->
    <div class="wpbs-page-notice notice-error"> 
        <p><?php echo $problem; ?></p>
    </div>

<?php endforeach; ?>

<div id="wpbs-booking-restrictions-wrapper" class="wpbs-user-notification-wrapper wpbs-settings-wrapper <?php echo (!empty($form_meta['booking_restrictions_enable'][0])) ? 'wpbs-settings-wrapper-show' : ''; ?>">

    <div class="wpbs-booking-restrictions" data-index="<?php echo count($booking_restrictions);?>">

        <?php foreach($booking_restrictions as $id => $booking_restriction): ?>

        <div class="postbox wpbs-booking-restriction">

            <h3 class="hndle" data-custom-period-title="<?php echo __('Restriction Rule - Custom Period', 'wp-booking-system-booking-restrictions');?>">
                <span><?php echo ($id == 0) ? __('Restriction Rule - General', 'wp-booking-system-booking-restrictions') : __('Restriction Rule - Custom Period', 'wp-booking-system-booking-restrictions') ?></span>
                <a href="#" class="wpbs-booking-restriction-remove" title="Remove"><i class="wpbs-icon-close"></i> <?php echo __('Remove', 'wp-booking-system-booking-restrictions') ?></a>
            </h3>

            <div class="inside">

                <!-- Period -->
                <input type="hidden" value="<?php echo ($id==0) ? 'all' : 'custom';?>" name="booking_restrictions[<?php echo $id;?>][period]" data-name="booking_restrictions[id][period]" class="wpbs-booking-restrictions-period" />
                            

                <div class="wpbs-booking-restrictions-custom-period <?php echo (!isset($booking_restriction['period']) || $booking_restriction['period'] == 'all') ? 'wpbs-hide' : '';?>">

                    <!-- Date Range Type -->
                    <div class="wpbs-settings-field-wrapper wpbs-settings-field-inline wpbs-settings-field-xlarge ">
                        <label class="wpbs-settings-field-label">
                            <?php echo __('Date range type', 'wp-booking-system-booking-restrictions') ?>
                        </label>

                        <div class="wpbs-settings-field-inner">
                            <select name="booking_restrictions[<?php echo $id;?>][date_range_type]" data-name="booking_restrictions[id][date_range_type]">
                                <option <?php isset($booking_restriction['date_range_type']) ? selected($booking_restriction['date_range_type'], 'recurring') : '';?> value="recurring"><?php echo __('Recurring', 'wp-booking-system-booking-restrictions') ?></option>
                                <option <?php isset($booking_restriction['date_range_type']) ? selected($booking_restriction['date_range_type'], 'fixed_date') : '';?> value="fixed_date"><?php echo __('Fixed Date', 'wp-booking-system-booking-restrictions') ?></option>
                            </select>
                        </div>
                        
                    </div>

                    <!-- Date Range - Recurring -->
                    <div class="wpbs-settings-field-wrapper wpbs-settings-field-inline wpbs-settings-field-xlarge wpbs-settings-br-date-range-type wpbs-settings-br-date-range-recurring">
                        <label class="wpbs-settings-field-label">
                            <?php echo __('Date range', 'wp-booking-system-booking-restrictions') ?>
                            <?php echo wpbs_get_output_tooltip(__('Set restriction rules for a custom date range. This will override the "General" restriction rules.', 'wp-booking-system-booking-restrictions')); ?>
                        </label>

                        <div class="wpbs-settings-field-inner wpbs-br-field-inner-half wpbs-settings-br-date-range-wrapper">
                            <label><?php echo __('Start date', 'wp-booking-system-booking-restrictions') ?></label>
                            <input type="text" class="wpbs-br-datepicker" value="<?php echo isset($booking_restriction['start_period_display_value']) ? $booking_restriction['start_period_display_value'] : (isset($booking_restriction['start_period']) ? $booking_restriction['start_period'] : '');?>" name="booking_restrictions[<?php echo $id;?>][start_period_display_value]" data-name="booking_restrictions[id][start_period_display_value]">
                            <input type="hidden" class="wpbs-br-datepicker-timestamp" value="<?php echo isset($booking_restriction['start_period']) ? $booking_restriction['start_period'] : '';?>" name="booking_restrictions[<?php echo $id;?>][start_period]" data-name="booking_restrictions[id][start_period]">
                        </div>

                        <div class="wpbs-settings-field-inner wpbs-br-field-inner-half wpbs-br-field-inner-half-last wpbs-settings-br-date-range-wrapper">
                            <label><?php echo __('End date', 'wp-booking-system-booking-restrictions') ?></label>
                            <input type="text" class="wpbs-br-datepicker" value="<?php echo isset($booking_restriction['end_period_display_value']) ? $booking_restriction['end_period_display_value'] : (isset($booking_restriction['end_period']) ? $booking_restriction['end_period'] : '');?>" name="booking_restrictions[<?php echo $id;?>][end_period_display_value]" data-name="booking_restrictions[id][end_period_display_value]">
                            <input type="hidden" class="wpbs-br-datepicker-timestamp" value="<?php echo isset($booking_restriction['end_period']) ? $booking_restriction['end_period'] : '';?>" name="booking_restrictions[<?php echo $id;?>][end_period]" data-name="booking_restrictions[id][end_period]">
                        </div>
                        
                    </div>

                    <!-- Date Range - One Time -->
                    <div class="wpbs-settings-field-wrapper wpbs-settings-field-inline wpbs-settings-field-xlarge wpbs-settings-br-date-range-type wpbs-settings-br-date-range-fixed_date">
                        <label class="wpbs-settings-field-label">
                            <?php echo __('Date range', 'wp-booking-system-booking-restrictions') ?>
                            <?php echo wpbs_get_output_tooltip(__('Set restriction rules for a custom date range. This will override the "General" restriction rules.', 'wp-booking-system-booking-restrictions')); ?>
                        </label>

                        <div class="wpbs-settings-field-inner wpbs-br-field-inner-half wpbs-settings-br-date-range-wrapper">
                            <label><?php echo __('Start date', 'wp-booking-system-booking-restrictions') ?></label>
                            <input type="text" class="wpbs-br-datepicker" value="<?php echo isset($booking_restriction['start_period_fixed_display_value']) ? $booking_restriction['start_period_fixed_display_value'] : (isset($booking_restriction['start_period_fixed']) ? $booking_restriction['start_period_fixed'] : '');?>" name="booking_restrictions[<?php echo $id;?>][start_period_fixed_display_value]" data-name="booking_restrictions[id][start_period_fixed_display_value]">
                            <input type="hidden" class="wpbs-br-datepicker-timestamp" value="<?php echo isset($booking_restriction['start_period_fixed']) ? $booking_restriction['start_period_fixed'] : '';?>" name="booking_restrictions[<?php echo $id;?>][start_period_fixed]" data-name="booking_restrictions[id][start_period_fixed]">
                        </div>

                        <div class="wpbs-settings-field-inner wpbs-br-field-inner-half wpbs-br-field-inner-half-last wpbs-settings-br-date-range-wrapper">
                            <label><?php echo __('End date', 'wp-booking-system-booking-restrictions') ?></label>
                            <input type="text" class="wpbs-br-datepicker" value="<?php echo isset($booking_restriction['end_period_fixed_display_value']) ? $booking_restriction['end_period_fixed_display_value'] : (isset($booking_restriction['end_period_fixed']) ? $booking_restriction['end_period_fixed'] : '');?>" name="booking_restrictions[<?php echo $id;?>][end_period_fixed_display_value]" data-name="booking_restrictions[id][end_period_fixed_display_value]">
                            <input type="hidden" class="wpbs-br-datepicker-timestamp" value="<?php echo isset($booking_restriction['end_period_fixed']) ? $booking_restriction['end_period_fixed'] : '';?>" name="booking_restrictions[<?php echo $id;?>][end_period_fixed]" data-name="booking_restrictions[id][end_period_fixed]">
                        </div>

                    </div>

                </div>

                <!-- Stay Length -->
                <div class="wpbs-settings-field-wrapper wpbs-settings-field-inline wpbs-settings-field-xlarge">
                    <label class="wpbs-settings-field-label">
                        <?php echo __('Stay length', 'wp-booking-system-booking-restrictions') ?>
                        <?php echo wpbs_get_output_tooltip(__('Set the minimum or maximum number of days for a booking. If you are using the "Split" selection type, the number of nights will be counted instead.', 'wp-booking-system-booking-restrictions')); ?>
                    </label>

                    <div class="wpbs-settings-field-inner wpbs-br-field-inner-half">
                        <label for="minimum_stay"><?php echo __('Minimum days', 'wp-booking-system-booking-restrictions') ?></label>
                        <input type="number" min="0" placeholder="0" value="<?php echo isset($booking_restriction['minimum_stay']) ? $booking_restriction['minimum_stay'] : '';?>" class="wpbs-booking-restrictions-minimum-stay" name="booking_restrictions[<?php echo $id;?>][minimum_stay]" data-name="booking_restrictions[id][minimum_stay]">
                    </div>

                    <div class="wpbs-settings-field-inner wpbs-br-field-inner-half wpbs-br-field-inner-half-last">
                        <label for="maximum_stay"><?php echo __('Maximum days', 'wp-booking-system-booking-restrictions') ?></label>
                        <input type="number" min="0" placeholder="&#8734;" value="<?php echo isset($booking_restriction['maximum_stay']) ? $booking_restriction['maximum_stay'] : '';?>" class="wpbs-booking-restrictions-maximum-stay" name="booking_restrictions[<?php echo $id;?>][maximum_stay]" data-name="booking_restrictions[id][maximum_stay]">
                    </div>

                    <div class="wpbs-settings-field-inner">
                        <label for="fixed_days">
                            <?php echo __('Fixed number of days', 'wp-booking-system-booking-restrictions') ?> 
                            <?php echo wpbs_get_output_tooltip(__('Set a fixed number of days for bookings. You can enter multiple days separated by a comma. Eg "7,14,21".', 'wp-booking-system-booking-restrictions')); ?>
                        </label>
                        <input type="text" value="<?php echo isset($booking_restriction['fixed_days']) ? $booking_restriction['fixed_days'] : '';?>" class="wpbs-booking-restrictions-maximum-stay" name="booking_restrictions[<?php echo $id;?>][fixed_days]" data-name="booking_restrictions[id][fixed_days]">
                    </div>
                </div>

                <!-- Stay Length based on Day of the Week -->
                <div class="wpbs-settings-field-wrapper wpbs-settings-field-inline wpbs-settings-field-xlarge">
                    <label class="wpbs-settings-field-label">
                        <?php echo __('Minimum stay based on start day', 'wp-booking-system-booking-restrictions') ?>
                        <?php echo wpbs_get_output_tooltip(__('Set the minimum stay for a specific day of the week. The value cannot be lower or higher than the "Stay length" values.', 'wp-booking-system-booking-restrictions')); ?>
                    </label>

                    <?php for ($i = 1; $i <= 7; $i++): ?>
                    <div class="wpbs-settings-field-inner wpbs-br-field-inner-week-days wpbs-br-field-inner-week-days-<?php echo $i; ?>">
                        <label><?php echo $weekday[$i - 1] ?></label>
                        <input type="number" min="1" placeholder="<?php echo isset($booking_restriction['minimum_stay']) && $booking_restriction['minimum_stay'] ? $booking_restriction['minimum_stay'] : '0';?>" value="<?php echo isset($booking_restriction['minimum_stay_per_day'][$i]) ? $booking_restriction['minimum_stay_per_day'][$i] : '';?>" class="wpbs-booking-restrictions-minimum-stay-per-day" name="booking_restrictions[<?php echo $id;?>][minimum_stay_per_day][<?php echo $i; ?>]" data-name="booking_restrictions[id][minimum_stay_per_day][<?php echo $i; ?>]">
                    </div>
                    <?php endfor;?>

                </div>

                <!-- Enforce Specific Days -->
                <div class="wpbs-settings-field-wrapper wpbs-settings-field-inline wpbs-settings-field-xlarge">
                    <label class="wpbs-settings-field-label">
                        <?php echo __('Enforce specific days', 'wp-booking-system-booking-restrictions') ?>
                        <?php echo wpbs_get_output_tooltip(__('Force the booking to start or end on a specific day of the week.', 'wp-booking-system-booking-restrictions')); ?>
                    </label>

                    <div class="wpbs-enforce-days-fields">
                        <div class="wpbs-enforce-days-field wpbs-enforce-days-field-heading">
                            <div class="wpbs-settings-field-inner wpbs-br-field-inner-half">
                                <label><?php echo __('Start day', 'wp-booking-system-booking-restrictions') ?></label>
                            </div>

                            <div class="wpbs-settings-field-inner wpbs-br-field-inner-half wpbs-br-field-inner-half-last">
                                <label><?php echo __('End day', 'wp-booking-system-booking-restrictions') ?></label>
                            </div>
                        </div>

                        <?php $start_weekday = (!empty($settings['backend_start_day']) ? (int) $settings['backend_start_day'] : 1); ?>

                        <div class="wpbs-enforce-days-field">
                            <div class="wpbs-settings-field-inner wpbs-br-field-inner-half">
                                <?php for( $d = $start_weekday; $d < ( $start_weekday + 7 ); $d++ ):?>

                                    <?php $week_day_letter = wpbs_get_days_first_letters(wpbs_get_locale())[($d + 6) % 7];?>

                                    <?php $week_day = ($d % 7) ? : 7; ?>
                                    
                                    <label class="wpbs-br-enforce-days-week-days">
                                        <input type="checkbox" id="wpbs-br-start-day-<?php echo $id;?>-<?php echo $week_day;?>" name="booking_restrictions[<?php echo $id;?>][booking_day_start][]" data-name="booking_restrictions[id][booking_day_start][]" class="wpbs-br-enforce-days-week-day" value="<?php echo $week_day;?>" <?php echo isset($booking_restriction['booking_day_start']) && in_array($week_day, $booking_restriction['booking_day_start']) ? 'checked' : '';?> />
                                        <span><?php echo $week_day_letter;?></span>

                                    </label>

                                <?php endfor; ?>
                            </div>

                            <div class="wpbs-settings-field-inner wpbs-br-field-inner-half wpbs-br-field-inner-half-last">
                                <?php for( $d = $start_weekday; $d < ( $start_weekday + 7 ); $d++ ):?>

                                    <?php $week_day_letter = wpbs_get_days_first_letters(wpbs_get_locale())[($d + 6) % 7];?>

                                    <?php $week_day = ($d % 7) ? : 7; ?>
                                    
                                    <label class="wpbs-br-enforce-days-week-days">
                                        <input type="checkbox" id="wpbs-br-end-day-<?php echo $id;?>-<?php echo $week_day;?>" name="booking_restrictions[<?php echo $id;?>][booking_day_end][]" data-name="booking_restrictions[id][booking_day_end][]" class="wpbs-br-enforce-days-week-day" value="<?php echo $week_day;?>" <?php echo isset($booking_restriction['booking_day_end']) && in_array($week_day, $booking_restriction['booking_day_end']) ? 'checked' : '';?> />
                                        <span><?php echo $week_day_letter;?></span>

                                    </label>

                                <?php endfor; ?>
                            </div>

                        </div>
                        
                    
                    </div>
                </div>
                
                <!-- Advance Reservation -->
                <div class="wpbs-settings-field-wrapper wpbs-settings-field-inline wpbs-settings-field-xlarge">
                    <label class="wpbs-settings-field-label">
                        <?php echo __('Advance reservation', 'wp-booking-system-booking-restrictions') ?> 
                        <?php echo wpbs_get_output_tooltip(__('The minimum and maximum advance reservation restrictions refer to the minimum or maximum periods for how far in advance guests can book.', 'wp-booking-system-booking-restrictions')); ?>
                    </label>

                    <div class="wpbs-settings-field-inner wpbs-br-field-inner-half">
                        <label><?php echo __('Minimum days', 'wp-booking-system-booking-restrictions') ?></label>
                        <input type="number" value="<?php echo isset($booking_restriction['minimum_advance_reservation']) ? $booking_restriction['minimum_advance_reservation'] : '';?>" placeholder="0" min="0" name="booking_restrictions[<?php echo $id;?>][minimum_advance_reservation]" data-name="booking_restrictions[id][minimum_advance_reservation]">
                    </div>

                    <div class="wpbs-settings-field-inner wpbs-br-field-inner-half wpbs-br-field-inner-half-last">
                        <label><?php echo __('Maximum days', 'wp-booking-system-booking-restrictions') ?></label>
                        <input type="number" value="<?php echo isset($booking_restriction['maximum_advance_reservation']) ? $booking_restriction['maximum_advance_reservation'] : '';?>" placeholder="&#8734;" min="0" name="booking_restrictions[<?php echo $id;?>][maximum_advance_reservation]" data-name="booking_restrictions[id][maximum_advance_reservation]">
                    </div>
                </div>

                <!-- Turnaround time -->
                <div class="wpbs-settings-field-wrapper wpbs-settings-field-inline wpbs-settings-field-xlarge">
                    <label class="wpbs-settings-field-label">
                        <?php echo __('Turnaround time', 'wp-booking-system-booking-restrictions') ?> 
                        <?php echo wpbs_get_output_tooltip(__('Force a number of days between bookings.', 'wp-booking-system-booking-restrictions')); ?>
                    </label>

                    <div class="wpbs-settings-field-inner ">
                        <label><?php echo __('Days', 'wp-booking-system-booking-restrictions') ?></label>
                        <input type="number" value="<?php echo isset($booking_restriction['turnaround_time']) ? $booking_restriction['turnaround_time'] : '';?>" placeholder="0" min="0" name="booking_restrictions[<?php echo $id;?>][turnaround_time]" data-name="booking_restrictions[id][turnaround_time]">
                    </div>

                </div>

                <!-- Priority -->
                <div class="wpbs-settings-field-wrapper wpbs-settings-field-inline wpbs-settings-field-xlarge wpbs-booking-restrictions-priority <?php echo ($id == 0) ? 'wpbs-hide' : '';?>">
                    <label class="wpbs-settings-field-label">
                        <?php echo __('Priority', 'wp-booking-system-booking-restrictions') ?> 
                        <?php echo wpbs_get_output_tooltip(__('Allow a rule to overwrite another rule by setting a higher priority.', 'wp-booking-system-booking-restrictions')); ?>
                    </label>

                    <div class="wpbs-settings-field-inner ">
                        <input type="number" value="<?php echo isset($booking_restriction['priority']) ? $booking_restriction['priority'] : '';?>" placeholder="0" min="0" name="booking_restrictions[<?php echo $id;?>][priority]" data-name="booking_restrictions[id][priority]">
                    </div>

                </div>

            </div>

        </div>

        <?php endforeach; ?>
    </div>

    <button class="wpbs-booking-restrictions-add-new button button-secondary"><?php echo __('Add Custom Period Restriction Rule', 'wp-booking-system-booking-restrictions') ?></button>

</div>