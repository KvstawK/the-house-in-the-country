<?php
$fixed_intervals = wpbs_get_form_meta($form_id, 'fixed_intervals', true);
$fixed_intervals = (is_array($fixed_intervals)) ? $fixed_intervals : array(1);

$fixed_intervals = array_values($fixed_intervals);

?>

<!-- Enable Fixed Intervals -->
<div class="wpbs-settings-field-wrapper wpbs-settings-field-inline wpbs-settings-field-xlarge">
	<label class="wpbs-settings-field-label" for="fixed_intervals_enable">
        <?php echo __('Enable Fixed Date Intervals', 'wp-booking-system-booking-restrictions'); ?>
    </label>

	<div class="wpbs-settings-field-inner">
        <label for="fixed_intervals_enable" class="wpbs-checkbox-switch">
            <input type="hidden" name="fixed_intervals_enable" value="0">
            <input data-target="#wpbs-fixed-intervals-wrapper" name="fixed_intervals_enable" type="checkbox" id="fixed_intervals_enable" class="regular-text wpbs-settings-toggle wpbs-settings-wrap-toggle" <?php echo (!empty($form_meta['fixed_intervals_enable'][0])) ? 'checked' : ''; ?> >
            <div class="wpbs-checkbox-slider"></div>
        </label>
	</div>
</div>


<div id="wpbs-fixed-intervals-wrapper" class="wpbs-user-notification-wrapper wpbs-settings-wrapper <?php echo (!empty($form_meta['fixed_intervals_enable'][0])) ? 'wpbs-settings-wrapper-show' : ''; ?>">

    <?php 
    /**
     * Validation Notices
     * 
     */
    $problems = wpbs_br_validate_fixed_intervals_settings($fixed_intervals); ?>
    <?php if($problems) foreach($problems as $problem): ?>

        <!-- Validation Notice -->
        <div class="wpbs-page-notice notice-error"> 
            <p><?php echo $problem; ?></p>
        </div>

    <?php endforeach; ?>

    <div class="postbox wpbs-fixed-intervals" data-index="<?php echo count($fixed_intervals);?>">

        <h3 class="hndle">
                <span><?php echo  __('Fixed Date Intervals', 'wp-booking-system-booking-restrictions') ?></span>
            </h3>

            <div class="inside">
        
        <?php foreach($fixed_intervals as $id => $fixed_interval): ?>

            <div class="wpbs-fixed-interval">


                    <div class="wpbs-settings-field-inner wpbs-br-field-inner-third wpbs-settings-br-date-range-wrapper">
                        <label><?php echo __('Start date', 'wp-booking-system-booking-restrictions') ?></label>
                        <input type="text" class="wpbs-br-datepicker" value="<?php echo isset($fixed_interval['start_period_fixed_display_value']) ? $fixed_interval['start_period_fixed_display_value'] : (isset($fixed_interval['start_period_fixed']) ? $fixed_interval['start_period_fixed'] : '');?>" name="fixed_intervals[<?php echo $id;?>][start_period_fixed_display_value]" data-name="fixed_intervals[id][start_period_fixed_display_value]">
                        <input type="hidden" class="wpbs-br-datepicker-timestamp" value="<?php echo isset($fixed_interval['start_period_fixed']) ? $fixed_interval['start_period_fixed'] : '';?>" name="fixed_intervals[<?php echo $id;?>][start_period_fixed]" data-name="fixed_intervals[id][start_period_fixed]">
                    </div>

                    <div class="wpbs-settings-field-inner wpbs-br-field-inner-third  wpbs-settings-br-date-range-wrapper">
                        <label><?php echo __('End date', 'wp-booking-system-booking-restrictions') ?></label>
                        <input type="text" class="wpbs-br-datepicker" value="<?php echo isset($fixed_interval['end_period_fixed_display_value']) ? $fixed_interval['end_period_fixed_display_value'] : (isset($fixed_interval['end_period_fixed']) ? $fixed_interval['end_period_fixed'] : '');?>" name="fixed_intervals[<?php echo $id;?>][end_period_fixed_display_value]" data-name="fixed_intervals[id][end_period_fixed_display_value]">
                        <input type="hidden" class="wpbs-br-datepicker-timestamp" value="<?php echo isset($fixed_interval['end_period_fixed']) ? $fixed_interval['end_period_fixed'] : '';?>" name="fixed_intervals[<?php echo $id;?>][end_period_fixed]" data-name="fixed_intervals[id][end_period_fixed]">
                    </div>

                    <div class="wpbs-settings-field-inner wpbs-br-field-inner-third wpbs-br-field-inner-third-last">
                        <label><?php echo __('Price Override', 'wp-booking-system-booking-restrictions') ?></label>
                        <input type="number" value="<?php echo isset($fixed_interval['override_price']) ? $fixed_interval['override_price'] : '';?>" min="0" step="0.01" name="fixed_intervals[<?php echo $id;?>][override_price]" data-name="fixed_intervals[id][override_price]">
                    </div>

                    <a href="#" class="wpbs-fixed-interval-remove" title="Remove"><i class="wpbs-icon-close"></i></a>


            </div>

        <?php endforeach; ?>

        </div>

    </div>

    <button class="wpbs-fixed-intervals-add-new button button-secondary"><?php echo __('Add Fixed Date Interval', 'wp-booking-system-booking-restrictions') ?></button>

</div>