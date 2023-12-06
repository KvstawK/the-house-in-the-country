<h2><?php echo __('General Settings', 'wp-booking-system'); ?></h2>

<!-- Currency -->
<div class="wpbs-settings-field-wrapper wpbs-settings-field-inline wpbs-settings-field-large">
    <label class="wpbs-settings-field-label" for="payment_currency">
        <?php echo __('Currency', 'wp-booking-system'); ?>
    </label>

    <div class="wpbs-settings-field-inner">
        <select name="wpbs_settings[payment_currency]" id="payment_currency">
            <?php $currencies = wpbs_get_currencies();foreach ($currencies as $currency_code => $currency_name): ?>
                <option <?php selected((isset($settings['payment_currency']) ? $settings['payment_currency'] : 'USD'), $currency_code); ?> value="<?php echo $currency_code; ?>"><?php echo $currency_code ?> - <?php echo $currency_name ?></option>
            <?php endforeach;?>
        </select>
    </div>
</div>

<!-- Currency -->
<div class="wpbs-settings-field-wrapper wpbs-settings-field-inline wpbs-settings-field-large">
    <label class="wpbs-settings-field-label" for="price_format">
        <?php echo __('Price Format', 'wp-booking-system'); ?>
    </label>

    <div class="wpbs-settings-field-inner">
        <select name="wpbs_settings[price_format]" id="price_format">
            <option <?php selected((isset($settings['price_format']) ? $settings['price_format'] : '1'), '1'); ?> value="1">12,345.67</option>
            <option <?php selected((isset($settings['price_format']) ? $settings['price_format'] : '1'), '2'); ?> value="2">12.345,67</option>
            <option <?php selected((isset($settings['price_format']) ? $settings['price_format'] : '1'), '3'); ?> value="3">12 345,67</option>
            <option <?php selected((isset($settings['price_format']) ? $settings['price_format'] : '1'), '4'); ?> value="4">12 345.67</option>
        </select>
    </div>
</div>

<!-- Currency -->
<div class="wpbs-settings-field-wrapper wpbs-settings-field-inline wpbs-settings-field-large">
    <label class="wpbs-settings-field-label" for="currency_format">
        <?php echo __('Currency Format', 'wp-booking-system'); ?>
    </label>

    <div class="wpbs-settings-field-inner">
        <select name="wpbs_settings[currency_format]" id="currency_format">
            <option <?php selected((isset($settings['currency_format']) ? $settings['currency_format'] : 'code'), 'code'); ?> value="code"><?php _e('Code (eg. USD, GBP)', 'wp-booking-system') ?></option>
            <option <?php selected((isset($settings['currency_format']) ? $settings['currency_format'] : 'code'), 'symbol'); ?> value="symbol"><?php _e('Symbol (eg. $, £)', 'wp-booking-system') ?></option>
        </select>
    </div>
</div>

<!-- Currency -->
<div class="wpbs-settings-field-wrapper wpbs-settings-field-inline wpbs-settings-field-large">
    <label class="wpbs-settings-field-label" for="currency_position">
        <?php echo __('Currency Position', 'wp-booking-system'); ?>
    </label>

    <div class="wpbs-settings-field-inner">
        <select name="wpbs_settings[currency_position]" id="currency_position">
            <option <?php selected((isset($settings['currency_position']) ? $settings['currency_position'] : 'right'), 'right'); ?> value="right"><?php _e('Right (eg. 100.00£)', 'wp-booking-system') ?></option>
            <option <?php selected((isset($settings['currency_position']) ? $settings['currency_position'] : 'right'), 'left'); ?> value="left"><?php _e('Left (eg. £100.00)', 'wp-booking-system') ?></option>
        </select>
    </div>
</div>

<!-- Product Name -->
<div class="wpbs-settings-field-translation-wrapper">
    <div class="wpbs-settings-field-wrapper wpbs-settings-field-inline wpbs-settings-field-large">
        <label class="wpbs-settings-field-label" for="payment_product_name">
            <?php echo __('Product Name', 'wp-booking-system'); ?>
            <?php echo wpbs_get_output_tooltip(__('The product name to show in the price calculator for date selections. For eg. "Days". If the users selects 4 days in the calendar, the price calculation will show "Days &times; 4: 100 EUR"', 'wp-booking-system')); ?>
        </label>

        <div class="wpbs-settings-field-inner">
            <input name="wpbs_settings[payment_product_name]" id="payment_product_name" type="text" value="<?php echo (!empty($settings['payment_product_name']) ? esc_attr($settings['payment_product_name']) : ''); ?>" />
            <?php if (wpbs_translations_active()): ?><a href="#" class="wpbs-settings-field-show-translations"><?php echo __('Translations', 'wp-booking-system'); ?> <i class="wpbs-icon-down-arrow"></i></a><?php endif?>
        </div>
    </div>
    <?php if (wpbs_translations_active()): ?>
    <!-- Required Field Translations -->
    <div class="wpbs-settings-field-translations">
        <?php foreach ($active_languages as $language): ?>
            <div class="wpbs-settings-field-wrapper wpbs-settings-field-inline wpbs-settings-field-large">
                <label class="wpbs-settings-field-label" for="payment_product_name_translation_<?php echo $language; ?>"><img src="<?php echo WPBS_PLUGIN_DIR_URL; ?>/assets/img/flags/<?php echo $language; ?>.png" /> <?php echo $languages[$language]; ?></label>
                <div class="wpbs-settings-field-inner">
                    <input name="wpbs_settings[payment_product_name_translation_<?php echo $language; ?>]" type="text" id="payment_product_name_translation_<?php echo $language; ?>" value="<?php echo (!empty($settings['payment_product_name_translation_' . $language])) ? esc_attr($settings['payment_product_name_translation_' . $language]) : ''; ?>" class="regular-text" >
                </div>
            </div>
        <?php endforeach;?>
    </div>
    <?php endif;?>
</div>

<?php

	/**
	 * Hook to add extra fields at the bottom of the Payment General Tab
	 *
	 * @param array $settings
	 *
	 */
	do_action( 'wpbs_submenu_page_settings_tab_payment_general_bottom', $settings );

