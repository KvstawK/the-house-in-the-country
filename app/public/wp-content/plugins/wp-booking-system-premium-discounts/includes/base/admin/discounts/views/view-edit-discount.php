<?php

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

$discount_id = absint(!empty($_GET['discount_id']) ? $_GET['discount_id'] : 0);
$discount = wpbs_get_discount($discount_id);

if (is_null($discount)) {
    return;
}

$discount_options = $discount->get('options');

$calendars = wpbs_get_calendars(array('status' => 'active'));

$settings = get_option('wpbs_settings', array());
$active_languages = (!empty($settings['active_languages']) ? $settings['active_languages'] : array());
$languages = wpbs_get_languages();

$removable_query_args = wp_removable_query_args();

$validity_period = isset($discount_options['validity_period']) && !empty($discount_options['validity_period']) ? $discount_options['validity_period'] : [[]];
if(isset($discount_options['validity_from']) || isset($discount_options['validity_to'])){
    $validity_period = array(array(
        'from' => (isset($discount_options['validity_from']) ? $discount_options['validity_from'] : ''),
        'to' => (isset($discount_options['validity_to']) ? $discount_options['validity_to'] : '')
    ));
}

?>



<div class="wrap wpbs-wrap wpbs-wrap-edit-discount">


    <form method="POST" action="" autocomplete="off">

        <!-- Page Heading -->
        <h1 class="wp-heading-inline"><?php echo __('Edit Discount', 'wp-booking-system-coupons-discounts'); ?><span class="wpbs-heading-tag"><?php printf(__('Discount ID: %d', 'wp-booking-system-coupons-discounts'), $discount_id);?></span></h1>

        <!-- Page Heading Actions -->
        <div class="wpbs-heading-actions">

            <!-- Back Button -->
            <a href="<?php echo add_query_arg(array('page' => 'wpbs-discounts'), admin_url('admin.php')); ?>" class="button-secondary"><?php echo __('Back to all discounts', 'wp-booking-system-coupons-discounts') ?></a>

            <!-- Save button -->
            <input type="submit" class="wpbs-save-discount button-primary" value="<?php echo __('Save Discount', 'wp-booking-system-coupons-discounts'); ?>" />

        </div>

        <hr class="wp-header-end" />

        <?php if(wpbs_discounts_check_overlapping_periods($validity_period)):?>
            <!-- Validation Notice -->
            <div class="wpbs-page-notice error"> 
                <p><?php _e('One or more validity periods contain invalid or overlapping dates.', 'wp-booking-system-coupons-discounts') ?></p>
            </div>
        <?php endif; ?>

        <?php if($invalid_date = wpbs_discounts_check_invalid_dates($validity_period)):?>
            <!-- Validation Notice -->
            <div class="wpbs-page-notice error"> 
                <p><?php _e('The date "'. $invalid_date. '" is not a valid date format or date string.', 'wp-booking-system-coupons-discounts') ?></p>
            </div>
        <?php endif; ?>


        <div id="poststuff">
            <!-- Discount Title -->
            <div id="titlediv">
                <div id="titlewrap">
                    <input type="text" name="discount_name" size="30" value="<?php echo esc_attr($discount->get('name')) ?>" id="title">

                    <?php if (isset($settings['active_languages']) && count($settings['active_languages']) > 0): ?>

						<a href="#" class="titlewrap-toggle"><?php echo __('Translate discount title', 'wp-booking-system-coupons-discounts') ?> <svg aria-hidden="true" focusable="false" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512" ><path fill="currentColor" d="M31.3 192h257.3c17.8 0 26.7 21.5 14.1 34.1L174.1 354.8c-7.8 7.8-20.5 7.8-28.3 0L17.2 226.1C4.6 213.5 13.5 192 31.3 192z" class=""></path></svg></a>
						<div class="titlewrap-translations">
							<?php foreach ($settings['active_languages'] as $language): ?>
								<div class="titlewrap-translation">
									<div class="titlewrap-translation-flag"><img src="<?php echo WPBS_PLUGIN_DIR_URL; ?>assets/img/flags/<?php echo $language; ?>.png" /></div>
									<input type="text" name="discount_name_translation_<?php echo $language; ?>" size="30" value="<?php echo esc_attr(wpbs_get_discount_meta($discount->get('id'), 'discount_name_translation_' . $language, true)) ?>" >
								</div>
							<?php endforeach;?>
						</div>

					<?php endif?>
                </div>
            </div>


            <div class="wpbs-discount-form-fields">

                <!-- Discount Description -->
                <div class="wpbs-settings-field-translation-wrapper">
                    <div class="wpbs-settings-field-wrapper wpbs-settings-field-inline wpbs-settings-field-large">
                        <label class="wpbs-settings-field-label" for="discount_description">
                            <?php echo __('Discount Description', 'wp-booking-system-coupons-discounts'); ?>
                            <?php echo wpbs_get_output_tooltip(__("Optional. A description that will appear in the pricing table, under the discount's name.", 'wp-booking-system-coupons-discounts')) ?>
                        </label>

                        <div class="wpbs-settings-field-inner">
                            <input name="discount_description" type="text" id="discount_description" class="regular-text" value="<?php echo isset($discount_options['description']) ? esc_attr($discount_options['description']) : ''; ?>" />
                            <?php if (wpbs_translations_active()): ?><a href="#" class="wpbs-settings-field-show-translations"><?php echo __('Translations', 'wp-booking-system-coupons-discounts'); ?> <i class="wpbs-icon-down-arrow"></i></a><?php endif?>
                        </div>
                    </div>

                    <?php if (wpbs_translations_active()): ?>
                        <!-- Translations -->
                        <div class="wpbs-settings-field-translations">
                            <?php foreach ($active_languages as $language): ?>
                                <div class="wpbs-settings-field-wrapper wpbs-settings-field-inline wpbs-settings-field-xlarge">
                                    <label class="wpbs-settings-field-label" for="discount_description_translation_<?php echo $language; ?>"><img src="<?php echo WPBS_PLUGIN_DIR_URL; ?>/assets/img/flags/<?php echo $language; ?>.png" /> <?php echo $languages[$language]; ?></label>
                                    <div class="wpbs-settings-field-inner">
                                        <input name="discount_description_translation_<?php echo $language; ?>" type="text" id="discount_description_translation_<?php echo $language; ?>" class="regular-text" value="<?php echo isset($discount_options['description_translation_' . $language]) ? esc_attr($discount_options['description_translation_' . $language]) : ''; ?>" />
                                    </div>
                                </div>
                            <?php endforeach;?>
                        </div>
                    <?php endif;?>
                </div>

                <!-- Discount Type -->
                <div class="wpbs-settings-field-wrapper wpbs-settings-field-inline wpbs-settings-field-large">
                    <label class="wpbs-settings-field-label" for="discount_type"><?php echo __('Discount Type', 'wp-booking-system-coupons-discounts'); ?></label>

                    <div class="wpbs-settings-field-inner">
                        <select name="discount_type" id="discount_type">
                            <option <?php isset($discount_options['type']) ? selected($discount_options['type'], 'fixed_amount') : ''; ?> value="fixed_amount"><?php echo __('Fixed Amount', 'wp-booking-system-coupons-discounts') ?></option>
                            <option <?php isset($discount_options['type']) ? selected($discount_options['type'], 'percentage') : ''; ?>value="percentage"><?php echo __('Percentage', 'wp-booking-system-coupons-discounts') ?></option>
                        </select>
                    </div>
                </div>

                <!-- Discount Type -->
                <div class="wpbs-settings-field-wrapper wpbs-settings-field-inline wpbs-settings-field-large">
                    <label class="wpbs-settings-field-label" for="discount_value"><?php echo __('Discount Value', 'wp-booking-system-coupons-discounts'); ?></label>

                    <div class="wpbs-settings-field-inner wpbs-discount-value-field-inner">
                        <span class="input-before">
                            <span class="before">
                                <span class="discount-type discount-type-fixed_amount"><?php echo wpbs_get_currency(); ?></span>
                                <span class="discount-type discount-type-percentage">%</span>
                            </span>
                            <input name="discount_value" type="text" id="discount_value" value="<?php echo isset($discount_options['value']) ? esc_attr($discount_options['value']) : ''; ?>" class="regular-text" >
                        </span>
                    </div>
                </div>

                <!-- Discount Conditions -->
                <div class="wpbs-settings-field-wrapper wpbs-settings-field-inline wpbs-settings-field-discount-conditions">
                    <label class="wpbs-settings-field-label" for=""><?php echo __('Discount Conditions', 'wp-booking-system-coupons-discounts'); ?></label>
                    <div class="wpbs-settings-field-inner wpbs-discount-value-field-inner">

                        <div class="wpbs-discount-rules">

                            <?php if(isset($discount_options['rules'])) foreach($discount_options['rules'] as $group_index => $group):  ?>
                                <div class="wpbs-discount-rule-group" data-index="<?php echo $group_index;?>">
                                    <div class="wpbs-discount-rule-group-inner">
                                        <?php foreach($group as $rule): ?>
                                            <?php discount_rule_row($rule, $group_index); ?>
                                        <?php endforeach; ?>
                                    </div>
                                    <div class="discount-rule-group-separator"><p><?php echo __('or', 'wp-booking-system-coupons-discounts') ?></p></div>
                                </div>
                            <?php endforeach; ?>

                            <div class="discount-rule-group-add-group">
                                <button class="discount-rule-add discount-rule-add-or button-secondary"><?php echo __('add rule group', 'wp-booking-system-coupons-discounts'); ?></button>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <div id="wpbs-discount-rule-template">
                <?php discount_rule_row();?>
            </div>

            <!-- Apply To -->
            <div class="wpbs-settings-field-wrapper wpbs-settings-field-inline wpbs-settings-field-large wpbs-settings-field-discount-apply-to">
                <label class="wpbs-settings-field-label" for="discount_apply_to">
                    <?php echo __('Apply Discount to', 'wp-booking-system-coupons-discounts'); ?>
                    <?php echo wpbs_get_output_tooltip(__('Select how to apply the discount. To all pricing items (calendar price per day and form product fields) or to calendar price only (calendar price per day).', 'wp-booking-system-coupons-discounts')) ?>
                </label>

                <div class="wpbs-settings-field-inner">
                    <select name="discount_apply_to" id="discount_apply_to">
                        <option value="all" <?php echo isset($discount_options['apply_to']) ? selected($discount_options['apply_to'], 'all', false) : '';?>><?php echo __('Calendar and Form Prices', 'wp-booking-system-coupons-discounts'); ?></option>
                        <option value="calendar" <?php echo isset($discount_options['apply_to']) ? selected($discount_options['apply_to'], 'calendar', false) : '';?>><?php echo __('Calendar Price Only', 'wp-booking-system-coupons-discounts'); ?></option>
                    </select>
                </div>
            </div>

            <!-- Application -->
            <div class="wpbs-settings-field-wrapper wpbs-settings-field-inline wpbs-settings-field-large wpbs-settings-field-discount-application">
                <label class="wpbs-settings-field-label" for="discount_application">
                    <?php echo __('Application', 'wp-booking-system-coupons-discounts'); ?>
                    <?php echo wpbs_get_output_tooltip(__('Select how to apply the discount. Once per booking or multiplied by the number of days booked.', 'wp-booking-system-coupons-discounts')) ?>
                </label>

                <div class="wpbs-settings-field-inner">
                    <select name="discount_application" id="discount_application">
                        <option value="per_booking" <?php echo isset($discount_options['application']) ? selected($discount_options['application'], 'per_booking', false) : '';?>><?php echo __('Per Booking - Apply the discount only once', 'wp-booking-system-coupons-discounts'); ?></option>
                        <option value="per_day" <?php echo isset($discount_options['application']) ? selected($discount_options['application'], 'per_day', false) : '';?>><?php echo __('Per Day - Apply the discount multiplied by the number of days booked', 'wp-booking-system-coupons-discounts'); ?></option>
                    </select>
                </div>
            </div>

            <!-- Visibility -->
            <div class="wpbs-settings-field-wrapper wpbs-settings-field-inline wpbs-settings-field-large">
                <label class="wpbs-settings-field-label" for="discount_visibility">
                    <?php echo __('Visibility', 'wp-booking-system-coupons-discounts'); ?>
                    <?php echo wpbs_get_output_tooltip(__('Chose whether to show the discount in the pricing table, or hide the discount while subtracting the discount value from the Calendar Price (the price per day or per night).', 'wp-booking-system-coupons-discounts')) ?>
                </label>

                <div class="wpbs-settings-field-inner">
                    <select name="discount_visibility" id="discount_visibility">
                        <option value="show" <?php echo isset($discount_options['visibility']) ? selected($discount_options['visibility'], 'show', false) : '';?>><?php echo __('Show Discount', 'wp-booking-system-coupons-discounts'); ?></option>
                        <option value="hide" <?php echo isset($discount_options['visibility']) ? selected($discount_options['visibility'], 'hide', false) : '';?>><?php echo __('Hide Discount, subtract amount from Calendar Price', 'wp-booking-system-coupons-discounts'); ?></option>
                    </select>
                </div>
            </div>

            <!-- Calendars -->
            <div class="wpbs-settings-field-wrapper wpbs-settings-field-inline wpbs-settings-field-large">
                <label class="wpbs-settings-field-label" for="discount_calendars">
                    <?php echo __('Calendars', 'wp-booking-system-coupons-discounts'); ?>
                    <?php echo wpbs_get_output_tooltip(__('Select the calendars the discount applies to. If no calendars are selected, the discount will be applied to all calendars.', 'wp-booking-system-coupons-discounts')) ?>
                </label>

                <div class="wpbs-settings-field-inner wpbs-chosen-wrapper">
                    <select name="discount_calendars[]" id="discount_calendars" class="wpbs-chosen" multiple>
                        <?php foreach ($calendars as $calendar): ?>
                        <option value="<?php echo $calendar->get('id'); ?>" <?php echo isset($discount_options['calendars']) && in_array($calendar->get('id'), $discount_options['calendars']) ? 'selected' : ''; ?>><?php echo $calendar->get('name'); ?></option>
                        <?php endforeach?>
                    </select>
                </div>
            </div>

            <!-- Period -->
            <div class="wpbs-settings-field-wrapper wpbs-settings-field-inline wpbs-settings-field-large">
                <label class="wpbs-settings-field-label">
                    <?php echo __( 'Validity Period', 'wp-booking-system-coupons-discounts' ); ?>
                    <?php echo wpbs_get_output_tooltip(__('Optional. The period in which the discount is valid.', 'wp-booking-system-coupons-discounts')) ?>
                </label>

                <div class="wpbs-settings-field-inner wpbs-discount-validity-wrapper">

                    <?php $readonly = apply_filters('wpbs_discounts_validity_period_readonly', true) ? 'readonly' : ''; ?>
                
                    <?php foreach($validity_period as $index => $period): ?>
                        <div class="wpbs-discount-validity-row" data-index="<?php echo $index;?>">
                            <input value="<?php echo isset($period['from']) ? esc_attr($period['from']) : ''; ?>" type="text" <?php echo $readonly;?> class="wpbs-discount-validity-date wpbs-discount-validity-datepicker wpbs-discount-validity-date-from" name="discount_validity_period[<?php echo $index;?>][from]" placeholder="<?php echo __('from', 'wp-booking-system-coupons-discounts');?>">
                            <input value="<?php echo isset($period['to']) ? esc_attr($period['to']) : ''; ?>" type="text" <?php echo $readonly;?> class="wpbs-discount-validity-date wpbs-discount-validity-datepicker wpbs-discount-validity-date-to" name="discount_validity_period[<?php echo $index;?>][to]" placeholder="<?php echo __('to', 'wp-booking-system-coupons-discounts');?>">
                            <a href="#" class="wpbs-discount-remove-validity-period"><i class="wpbs-icon-close"></i></a>
                            
                        </div>
                    <?php endforeach; ?>

                    <a href="#" class="button button-secondary wpbs-discount-add-validity-period"><?php _e('Add Period', 'wp-booking-system-coupons-discounts') ?></a>


                </div>
            </div>

            <!-- Period Inclusion -->
            <div class="wpbs-settings-field-wrapper wpbs-settings-field-inline wpbs-settings-field-large">
                <label class="wpbs-settings-field-label" for="discount_inclusion">
                    <?php echo __('Validity Inclusion', 'wp-booking-system-coupons-discounts'); ?>
                    <?php echo wpbs_get_output_tooltip(__('Choose to apply the discount only if the start date is contained in the Validity Period, or the entire date selection is contained in the Validity Period.', 'wp-booking-system-coupons-discounts')) ?>
                </label>

                <div class="wpbs-settings-field-inner">
                    <select name="discount_inclusion" id="discount_inclusion">
                        <option value="start_date" <?php echo isset($discount_options['inclusion']) ? selected($discount_options['inclusion'], 'start_date', false) : '';?>><?php echo __('Start Date only', 'wp-booking-system-coupons-discounts'); ?></option>
                        <option value="any_date" <?php echo isset($discount_options['inclusion']) ? selected($discount_options['inclusion'], 'any_date', false) : '';?>><?php echo __('Start Date or End Date', 'wp-booking-system-coupons-discounts'); ?></option>
                        <option value="entire_date" <?php echo isset($discount_options['inclusion']) ? selected($discount_options['inclusion'], 'entire_date', false) : '';?>><?php echo __('Start Date and End Date', 'wp-booking-system-coupons-discounts'); ?></option>
                    </select>
                </div>
            </div>

        </div><!-- / #poststuff -->

        <!-- Hidden fields -->
        <input type="hidden" name="discount_id" value="<?php echo $discount_id; ?>" />

        <!-- Nonce -->
        <?php wp_nonce_field('wpbs_edit_discount', 'wpbs_token', false);?>
        <input type="hidden" name="wpbs_action" value="edit_discount" />

        <!-- Save button -->
        <input type="submit" class="wpbs-save-discount button-primary" value="<?php echo __('Save Discount', 'wp-booking-system-coupons-discounts'); ?>" />

        <!-- Save Button Spinner -->
        <div class="wpbs-save-discount-spinner spinner"><!-- --></div>

    </form>

</div>