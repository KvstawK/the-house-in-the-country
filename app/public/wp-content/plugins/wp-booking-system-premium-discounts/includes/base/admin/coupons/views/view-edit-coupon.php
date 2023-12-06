<?php

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

$coupon_id = absint(!empty($_GET['coupon_id']) ? $_GET['coupon_id'] : 0);
$coupon = wpbs_get_coupon($coupon_id);

if (is_null($coupon)) {
    return;
}

$coupon_options = $coupon->get('options');

$calendars = wpbs_get_calendars(array('status' => 'active'));

$settings = get_option('wpbs_settings', array());
$active_languages = (!empty($settings['active_languages']) ? $settings['active_languages'] : array());
$languages = wpbs_get_languages();

$removable_query_args = wp_removable_query_args();

?>



<div class="wrap wpbs-wrap wpbs-wrap-edit-coupon">

    <form method="POST" action="" autocomplete="off">

        <!-- Page Heading -->
        <h1 class="wp-heading-inline"><?php echo __('Edit Coupon', 'wp-booking-system-coupons-discounts'); ?><span class="wpbs-heading-tag"><?php printf(__('Coupon ID: %d', 'wp-booking-system-coupons-discounts'), $coupon_id);?></span></h1>

        <!-- Page Heading Actions -->
        <div class="wpbs-heading-actions">

            <!-- Back Button -->
            <a href="<?php echo add_query_arg(array('page' => 'wpbs-coupons'), admin_url('admin.php')); ?>" class="button-secondary"><?php echo __('Back to all Coupons', 'wp-booking-system-coupons-discounts') ?></a>

            <!-- Save button -->
            <input type="submit" class="wpbs-save-coupon button-primary" value="<?php echo __('Save Coupon', 'wp-booking-system-coupons-discounts'); ?>" />

        </div>

        <hr class="wp-header-end" />

        <div id="poststuff">
            <!-- Coupon Title -->
            <div id="titlediv">
                <div id="titlewrap">
                    <input type="text" name="coupon_name" size="30" value="<?php echo esc_attr($coupon->get('name')) ?>" id="title">

                    <?php if (isset($settings['active_languages']) && count($settings['active_languages']) > 0): ?>

						<a href="#" class="titlewrap-toggle"><?php echo __('Translate coupon title', 'wp-booking-system-coupons-discounts') ?> <svg aria-hidden="true" focusable="false" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512" ><path fill="currentColor" d="M31.3 192h257.3c17.8 0 26.7 21.5 14.1 34.1L174.1 354.8c-7.8 7.8-20.5 7.8-28.3 0L17.2 226.1C4.6 213.5 13.5 192 31.3 192z" class=""></path></svg></a>
						<div class="titlewrap-translations">
							<?php foreach ($settings['active_languages'] as $language): ?>
								<div class="titlewrap-translation">
									<div class="titlewrap-translation-flag"><img src="<?php echo WPBS_PLUGIN_DIR_URL; ?>assets/img/flags/<?php echo $language; ?>.png" /></div>
									<input type="text" name="coupon_name_translation_<?php echo $language; ?>" size="30" value="<?php echo esc_attr(wpbs_get_coupon_meta($coupon->get('id'), 'coupon_name_translation_' . $language, true)) ?>" >
								</div>
							<?php endforeach;?>
						</div>

					<?php endif?>
                </div>
            </div>


            <div class="wpbs-coupon-form-fields">

                <!-- Coupon Code -->
                <div class="wpbs-settings-field-wrapper wpbs-settings-field-inline wpbs-settings-field-large">
                    <label class="wpbs-settings-field-label" for="coupon_code"><?php echo __('Coupon Code', 'wp-booking-system-coupons-discounts'); ?></label>

                    <div class="wpbs-settings-field-inner">
                        <input name="coupon_code" type="text" id="coupon_code" value="<?php echo isset($coupon_options['code']) ? esc_attr($coupon_options['code']) : ''; ?>" class="regular-text" >
                    </div>
                </div>

                <!-- Coupon Description -->
                <div class="wpbs-settings-field-translation-wrapper">
                    <div class="wpbs-settings-field-wrapper wpbs-settings-field-inline wpbs-settings-field-large">
                        <label class="wpbs-settings-field-label" for="coupon_description">
                            <?php echo __('Coupon Description', 'wp-booking-system-coupons-discounts'); ?>
                            <?php echo wpbs_get_output_tooltip(__("Optional. A description that will appear in the pricing table, under the coupon's name.", 'wp-booking-system-coupons-discounts')) ?>
                        </label>

                        <div class="wpbs-settings-field-inner">
                            <input name="coupon_description" type="text" id="coupon_description" class="regular-text" value="<?php echo isset($coupon_options['description']) ? esc_attr($coupon_options['description']) : ''; ?>" />
                            <?php if (wpbs_translations_active()): ?><a href="#" class="wpbs-settings-field-show-translations"><?php echo __('Translations', 'wp-booking-system-coupons-discounts'); ?> <i class="wpbs-icon-down-arrow"></i></a><?php endif?>
                        </div>
                    </div>

                    <?php if (wpbs_translations_active()): ?>
                        <!-- Translations -->
                        <div class="wpbs-settings-field-translations">
                            <?php foreach ($active_languages as $language): ?>
                                <div class="wpbs-settings-field-wrapper wpbs-settings-field-inline wpbs-settings-field-xlarge">
                                    <label class="wpbs-settings-field-label" for="coupon_description_translation_<?php echo $language; ?>"><img src="<?php echo WPBS_PLUGIN_DIR_URL; ?>/assets/img/flags/<?php echo $language; ?>.png" /> <?php echo $languages[$language]; ?></label>
                                    <div class="wpbs-settings-field-inner">
                                        <input name="coupon_description_translation_<?php echo $language; ?>" type="text" id="coupon_description_translation_<?php echo $language; ?>" class="regular-text" value="<?php echo isset($coupon_options['description_translation_' . $language]) ? esc_attr($coupon_options['description_translation_' . $language]) : ''; ?>" />
                                    </div>
                                </div>
                            <?php endforeach;?>
                        </div>
                    <?php endif;?>
                </div>

                <!-- Coupon Type -->
                <div class="wpbs-settings-field-wrapper wpbs-settings-field-inline wpbs-settings-field-large">
                    <label class="wpbs-settings-field-label" for="coupon_type"><?php echo __('Coupon Discount Type', 'wp-booking-system-coupons-discounts'); ?></label>

                    <div class="wpbs-settings-field-inner">
                        <select name="coupon_type" id="coupon_type">
                            <option <?php isset($coupon_options['type']) ? selected($coupon_options['type'], 'fixed_amount') : ''; ?> value="fixed_amount"><?php echo __('Fixed Amount', 'wp-booking-system-coupons-discounts') ?></option>
                            <option <?php isset($coupon_options['type']) ? selected($coupon_options['type'], 'percentage') : ''; ?>value="percentage"><?php echo __('Percentage', 'wp-booking-system-coupons-discounts') ?></option>
                        </select>
                    </div>
                </div>

                <!-- Coupon Value -->
                <div class="wpbs-settings-field-wrapper wpbs-settings-field-inline wpbs-settings-field-large">
                    <label class="wpbs-settings-field-label" for="coupon_value"><?php echo __('Coupon Value', 'wp-booking-system-coupons-discounts'); ?></label>

                    <div class="wpbs-settings-field-inner wpbs-coupon-value-field-inner">
                        <span class="input-before">
                            <span class="before">
                                <span class="coupon-type coupon-type-fixed_amount"><?php echo wpbs_get_currency(); ?></span>
                                <span class="coupon-type coupon-type-percentage">%</span>
                            </span>
                            <input name="coupon_value" type="text" id="coupon_value" value="<?php echo isset($coupon_options['value']) ? esc_attr($coupon_options['value']) : ''; ?>" class="regular-text" >
                        </span>
                    </div>
                </div>

            </div>

            <!-- Apply To -->
            <div class="wpbs-settings-field-wrapper wpbs-settings-field-inline wpbs-settings-field-large wpbs-settings-field-coupon-apply-to">
                <label class="wpbs-settings-field-label" for="coupon_apply_to">
                    <?php echo __('Apply Coupon to', 'wp-booking-system-coupons-discounts'); ?>
                    <?php echo wpbs_get_output_tooltip(__('Select how to apply the discount. To all pricing items (calendar price per day and form product fields) or to calendar price only (calendar price per day).', 'wp-booking-system-coupons-discounts')) ?>
                </label>

                <div class="wpbs-settings-field-inner">
                    <select name="coupon_apply_to" id="coupon_apply_to">
                        <option value="all" <?php echo isset($coupon_options['apply_to']) ? selected($coupon_options['apply_to'], 'all', false) : '';?>><?php echo __('Calendar and Form Prices', 'wp-booking-system-coupons-discounts'); ?></option>
                        <option value="calendar" <?php echo isset($coupon_options['apply_to']) ? selected($coupon_options['apply_to'], 'calendar', false) : '';?>><?php echo __('Calendar Price Only', 'wp-booking-system-coupons-discounts'); ?></option>
                    </select>
                </div>
            </div>

            <!-- Application Order  -->
            <div class="wpbs-settings-field-wrapper wpbs-settings-field-inline wpbs-settings-field-large wpbs-settings-field-coupon-apply-to">
                <label class="wpbs-settings-field-label" for="coupon_application_order">
                    <?php echo __('Application Order', 'wp-booking-system-coupons-discounts'); ?>
                    <?php echo wpbs_get_output_tooltip(__('Calculate the coupon value before or after applying other discounts.', 'wp-booking-system-coupons-discounts')) ?>
                </label>

                <div class="wpbs-settings-field-inner">
                    <select name="coupon_application_order" id="coupon_application_order">
                        <option value="before" <?php echo isset($coupon_options['application_order']) ? selected($coupon_options['application_order'], 'before', false) : '';?>><?php echo __('Before Discounts', 'wp-booking-system-coupons-discounts'); ?></option>
                        <option value="after" <?php echo isset($coupon_options['application_order']) ? selected($coupon_options['application_order'], 'after', false) : '';?>><?php echo __('After Discounts', 'wp-booking-system-coupons-discounts'); ?></option>
                    </select>
                </div>
            </div>

            <!-- Calendars -->
            <div class="wpbs-settings-field-wrapper wpbs-settings-field-inline wpbs-settings-field-large">
                <label class="wpbs-settings-field-label" for="coupon_calendars">
                    <?php echo __('Calendars', 'wp-booking-system-coupons-discounts'); ?>
                    <?php echo wpbs_get_output_tooltip(__('Select the calendars the coupon applies to. If no calendars are selected, the coupon will be applied to all calendars.', 'wp-booking-system-coupons-discounts')) ?>
                </label>

                <div class="wpbs-settings-field-inner wpbs-chosen-wrapper">
                    <select name="coupon_calendars[]" id="coupon_calendars" class="wpbs-chosen" multiple>
                        <?php foreach ($calendars as $calendar): ?>
                        <option value="<?php echo $calendar->get('id'); ?>" <?php echo isset($coupon_options['calendars']) && in_array($calendar->get('id'), $coupon_options['calendars']) ? 'selected' : ''; ?>><?php echo $calendar->get('name'); ?></option>
                        <?php endforeach?>
                    </select>
                </div>
            </div>

            <!-- Usage Limit -->
            <div class="wpbs-settings-field-wrapper wpbs-settings-field-inline wpbs-settings-field-large">
                <label class="wpbs-settings-field-label" for="coupon_usage_limit">
                    <?php echo __( 'Usage Limit', 'wp-booking-system-coupons-discounts' ); ?>
                    <?php echo wpbs_get_output_tooltip(__('Optional. The number of times the coupon code can be used.', 'wp-booking-system-coupons-discounts')) ?>
                </label>
                <div class="wpbs-settings-field-inner">
                    <input value="<?php echo isset($coupon_options['usage_limit']) ? esc_attr($coupon_options['usage_limit']) : ''; ?>" type="text" name="coupon_usage_limit" id="coupon_usage_limit" placeholder="<?php echo __( 'unlimited', 'wp-booking-system-coupons-discounts' ); ?>">

                    <?php if (isset($coupon_options['usage_limit'])): ?>
                        <?php $usages = absint(wpbs_get_coupon_meta($coupon->get('id'), 'usages', true)); ?>
                        <small><?php echo sprintf( __('Used %d times so far (%d usages left)', 'wp-booking-system-coupons-discounts'), $usages, $coupon_options['usage_limit'] - $usages); ?></small>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Minimum Stay -->
            <div class="wpbs-settings-field-wrapper wpbs-settings-field-inline wpbs-settings-field-large">
                <label class="wpbs-settings-field-label" for="coupon_minimum_stay">
                    <?php echo __( 'Minimum Stay', 'wp-booking-system-coupons-discounts' ); ?>
                    <?php echo wpbs_get_output_tooltip(__('Optional. The number of days the client has to book for the coupon to be valid. Nights will be counted if selection style is set to split.', 'wp-booking-system-coupons-discounts')) ?>
                </label>
                <div class="wpbs-settings-field-inner">
                    <input value="<?php echo isset($coupon_options['minimum_stay']) ? esc_attr($coupon_options['minimum_stay']) : ''; ?>" type="number" name="coupon_minimum_stay" id="coupon_minimum_stay" min="0" step="1" placeholder="<?php echo __( 'no minimum', 'wp-booking-system-coupons-discounts' ); ?>">
                </div>
            </div>

            <!-- Maximum Stay -->
            <div class="wpbs-settings-field-wrapper wpbs-settings-field-inline wpbs-settings-field-large">
                <label class="wpbs-settings-field-label" for="coupon_maximum_stay">
                    <?php echo __( 'Maximum Stay', 'wp-booking-system-coupons-discounts' ); ?>
                    <?php echo wpbs_get_output_tooltip(__('Optional. The maximum number of days the client has to book for the coupon to be valid. Nights will be counted if selection style is set to split.', 'wp-booking-system-coupons-discounts')) ?>
                </label>
                <div class="wpbs-settings-field-inner">
                    <input value="<?php echo isset($coupon_options['maximum_stay']) ? esc_attr($coupon_options['maximum_stay']) : ''; ?>" type="number" name="coupon_maximum_stay" id="coupon_maximum_stay" min="0" step="1" placeholder="<?php echo __( 'no maximum', 'wp-booking-system-coupons-discounts' ); ?>">
                </div>
            </div>

            <!-- Weekdays -->
            <div class="wpbs-settings-field-wrapper wpbs-settings-field-inline wpbs-settings-field-large">
                <label class="wpbs-settings-field-label" for="coupon_weekdays">
                    <?php echo __( 'Applicable Weekdays', 'wp-booking-system-coupons-discounts' ); ?>
                    <?php echo wpbs_get_output_tooltip(__('Optional. The coupon will be valid only if the booking starts on a certain weekday.', 'wp-booking-system-coupons-discounts')) ?>
                </label>
                <div class="wpbs-settings-field-inner">
                    
                    <?php $start_weekday = (!empty($settings['backend_start_day']) ? (int) $settings['backend_start_day'] : 1); ?>

                    <?php for( $i = $start_weekday; $i < ( $start_weekday + 7 ); $i++ ):?>

                        <?php $week_day_letter = wpbs_get_days_first_letters(wpbs_get_locale())[($i + 6) % 7];?>

                        <?php $week_day = 1 + ($i + 6) % 7; ?>
                        
                        <label for="wpbs-coupon-week-day-<?php echo $week_day;?>" class="wpbs-coupon-week-days">
                            <span><?php echo $week_day_letter;?></span>
                            <input type="checkbox" <?php if(isset($coupon_options['weekdays']) && in_array($week_day, $coupon_options['weekdays'])):?>checked="checked"<?php endif;?> id="wpbs-coupon-week-day-<?php echo $week_day;?>" name="coupon_weekdays[]" class="wpbs-coupon-week-day" value="<?php echo $week_day?>" />
                        </label>

                    <?php endfor; ?>

                </div>
            </div>

            <!-- Period -->
            <div class="wpbs-settings-field-wrapper wpbs-settings-field-inline wpbs-settings-field-large">
                <label class="wpbs-settings-field-label">
                    <?php echo __( 'Validity Period', 'wp-booking-system-coupons-discounts' ); ?>
                    <?php echo wpbs_get_output_tooltip(__('Optional. The period in which the coupon is valid.', 'wp-booking-system-coupons-discounts')) ?>
                    
                </label>
                <div class="wpbs-settings-field-inner wpbs-coupon-validity-wrapper">
                    <input value="<?php echo isset($coupon_options['validity_from']) ? esc_attr($coupon_options['validity_from']) : ''; ?>" readonly type="text" class="wpbs-coupon-validity-date" name="coupon_validity_from" id="wpbs-coupon-validity-from" placeholder="<?php echo __('from', 'wp-booking-system-coupons-discounts');?>">
                    <input value="<?php echo isset($coupon_options['validity_to']) ? esc_attr($coupon_options['validity_to']) : ''; ?>" readonly type="text" class="wpbs-coupon-validity-date" name="coupon_validity_to" id="wpbs-coupon-validity-to" placeholder="<?php echo __('to', 'wp-booking-system-coupons-discounts');?>">
                </div>
            </div>

            <!-- Period Inclusion -->
            <div class="wpbs-settings-field-wrapper wpbs-settings-field-inline wpbs-settings-field-large">
                <label class="wpbs-settings-field-label" for="coupon_inclusion">
                    <?php echo __('Validity Inclusion', 'wp-booking-system-coupons-discounts'); ?>
                    <?php echo wpbs_get_output_tooltip(__('Choose to apply the coupon only if the start date is contained in the Validity Period, or the entire date selection is contained in the Validity Period.', 'wp-booking-system-coupons-discounts')) ?>
                </label>

                <div class="wpbs-settings-field-inner">
                    <select name="coupon_inclusion" id="coupon_inclusion">
                        <option value="start_date" <?php echo isset($coupon_options['inclusion']) ? selected($coupon_options['inclusion'], 'start_date', false) : '';?>><?php echo __('Start Date only', 'wp-booking-system-coupons-discounts'); ?></option>
                        <option value="entire_date" <?php echo isset($coupon_options['inclusion']) ? selected($coupon_options['inclusion'], 'entire_date', false) : '';?>><?php echo __('Start Date or End Date', 'wp-booking-system-coupons-discounts'); ?></option>
                    </select>
                </div>
            </div>

        </div><!-- / #poststuff -->

        <!-- Hidden fields -->
        <input type="hidden" name="coupon_id" value="<?php echo $coupon_id; ?>" />

        <!-- Nonce -->
        <?php wp_nonce_field('wpbs_edit_coupon', 'wpbs_token', false);?>
        <input type="hidden" name="wpbs_action" value="edit_coupon" />

        <!-- Save button -->
        <input type="submit" class="wpbs-save-coupon button-primary" value="<?php echo __('Save Coupon', 'wp-booking-system-coupons-discounts'); ?>" />

        <!-- Save Button Spinner -->
        <div class="wpbs-save-coupon-spinner spinner"><!-- --></div>

    </form>

</div>