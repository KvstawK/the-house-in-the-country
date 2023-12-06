<?php 
$defaults = wpbs_settings_bank_transfer_defaults();
?>

<h2><?php echo __('Bank Transfer', 'wp-booking-system'); ?><?php echo wpbs_get_output_tooltip(__("Give the customer the option to transfer money directly in your bank account.", 'wp-booking-system'));?></h2>

<!-- Enable Bank Transfer -->
<div class="wpbs-settings-field-wrapper wpbs-settings-field-inline wpbs-settings-field-large">
	<label class="wpbs-settings-field-label" for="payment_bt_enable">
        <?php echo __( 'Active', 'wp-booking-system' ); ?>
    </label>

	<div class="wpbs-settings-field-inner">
        <label for="payment_bt_enable" class="wpbs-checkbox-switch">
            <input type="hidden" name="wpbs_settings[payment_bt_enable]" value="0">
            <input data-target="#wpbs-bank-transfer" name="wpbs_settings[payment_bt_enable]" type="checkbox" id="payment_bt_enable" class="regular-text wpbs-settings-toggle wpbs-settings-wrap-toggle" <?php echo ( !empty( $settings['payment_bt_enable'] ) ) ? 'checked' : '';?> >
            <div class="wpbs-checkbox-slider"></div>
        </label>
	</div>
</div>

<div id="wpbs-bank-transfer" class="wpbs-bank-transfer-wrapper wpbs-settings-wrapper <?php echo ( !empty($settings['payment_bt_enable']) ) ? 'wpbs-settings-wrapper-show' : '';?>">

    <!-- Payment Method Name -->
    <div class="wpbs-settings-field-translation-wrapper">
        <div class="wpbs-settings-field-wrapper wpbs-settings-field-inline wpbs-settings-field-large">
            <label class="wpbs-settings-field-label" for="payment_bt_name">
                <?php echo __( 'Display name', 'wp-booking-system' ); ?>
                <?php echo wpbs_get_output_tooltip(__("The payment method name that appears on the booking form.", 'wp-booking-system'));?>
            </label>

            <div class="wpbs-settings-field-inner">
                <input name="wpbs_settings[payment_bt_name]" type="text" id="payment_bt_name"  class="regular-text " value="<?php echo ( !empty( $settings['payment_bt_name'] ) ) ? $settings['payment_bt_name'] : $defaults['display_name'];?>" >
                <?php if (wpbs_translations_active()): ?><a href="#" class="wpbs-settings-field-show-translations"><?php echo __('Translations', 'wp-booking-system'); ?> <i class="wpbs-icon-down-arrow"></i></a><?php endif?>
            </div>
        </div>
        <?php if (wpbs_translations_active()): ?>
        <!-- Required Field Translations -->
        <div class="wpbs-settings-field-translations">
            <?php foreach ($active_languages as $language): ?>
                <div class="wpbs-settings-field-wrapper wpbs-settings-field-inline wpbs-settings-field-large">
                    <label class="wpbs-settings-field-label" for="payment_bt_name_translation_<?php echo $language; ?>"><img src="<?php echo WPBS_PLUGIN_DIR_URL; ?>/assets/img/flags/<?php echo $language; ?>.png" /> <?php echo $languages[$language]; ?></label>
                    <div class="wpbs-settings-field-inner">
                        <input name="wpbs_settings[payment_bt_name_translation_<?php echo $language; ?>]" type="text" id="payment_bt_name_translation_<?php echo $language; ?>" value="<?php echo (!empty($settings['payment_bt_name_translation_'. $language])) ? esc_attr($settings['payment_bt_name_translation_'. $language]) : ''; ?>" class="regular-text" >
                    </div>
                </div>
            <?php endforeach;?>
        </div>
        <?php endif; ?>
    </div>

    <!-- Payment Method Description -->
    <div class="wpbs-settings-field-translation-wrapper">
        <div class="wpbs-settings-field-wrapper wpbs-settings-field-inline wpbs-settings-field-large">
            <label class="wpbs-settings-field-label" for="payment_bt_description">
                <?php echo __( 'Description', 'wp-booking-system' ); ?>
                <?php echo wpbs_get_output_tooltip(__("The payment method description that appears on the booking form.", 'wp-booking-system'));?>
            </label>

            <div class="wpbs-settings-field-inner">
                <input name="wpbs_settings[payment_bt_description]" type="text" id="payment_bt_description"  class="regular-text " value="<?php echo ( !empty( $settings['payment_bt_description'] ) ) ? $settings['payment_bt_description'] : $defaults['description'];?>" >
                <?php if (wpbs_translations_active()): ?><a href="#" class="wpbs-settings-field-show-translations"><?php echo __('Translations', 'wp-booking-system'); ?> <i class="wpbs-icon-down-arrow"></i></a><?php endif?>
            </div>
        </div>
        <?php if (wpbs_translations_active()): ?>
        <!-- Required Field Translations -->
        <div class="wpbs-settings-field-translations">
            <?php foreach ($active_languages as $language): ?>
                <div class="wpbs-settings-field-wrapper wpbs-settings-field-inline wpbs-settings-field-large">
                    <label class="wpbs-settings-field-label" for="payment_bt_description_translation_<?php echo $language; ?>"><img src="<?php echo WPBS_PLUGIN_DIR_URL; ?>/assets/img/flags/<?php echo $language; ?>.png" /> <?php echo $languages[$language]; ?></label>
                    <div class="wpbs-settings-field-inner">
                        <input name="wpbs_settings[payment_bt_description_translation_<?php echo $language; ?>]" type="text" id="payment_bt_description_translation_<?php echo $language; ?>" value="<?php echo (!empty($settings['payment_bt_description_translation_'. $language])) ? esc_attr($settings['payment_bt_description_translation_'. $language]) : ''; ?>" class="regular-text" >
                    </div>
                </div>
            <?php endforeach;?>
        </div>
        <?php endif; ?>
    </div>

    <!-- Payment Instructions -->
    <div class="wpbs-settings-field-translation-wrapper">
        <div class="wpbs-settings-field-wrapper wpbs-settings-field-inline wpbs-settings-field-xlarge">
            <label class="wpbs-settings-field-label" for="payment_bt_instructions">
                <?php echo __('Payment Instructions', 'wp-booking-system'); ?>
                <?php echo wpbs_get_output_tooltip(__('Instructions on how and where to send the money. Bank name, account number, etc. This information will be included in the form confirmation message and in the email if the {Bank Transfer Instructions} tag is used.', 'wp-booking-system')); ?>
            </label>

            <div class="wpbs-settings-field-inner">
                <?php wp_editor((!empty($settings['payment_bt_instructions']) ? html_entity_decode($settings['payment_bt_instructions']) : ''), 'payment_bt_instructions', array('teeny' => true, 'textarea_rows' => 10, 'media_buttons' => false, 'textarea_name' => 'wpbs_settings[payment_bt_instructions]'))?>
                <p><?php echo __('You can use the <span class="wpbs-small-print-tag wpbs-select-on-click">{Amount}</span> tag to show the total amount needed to be paid by the customer, or the <span class="wpbs-small-print-tag wpbs-select-on-click">{Booking ID}</span> tag to add a transaction reference number.', 'wp-booking-system') ?></p>
                <?php if (wpbs_translations_active()): ?><a href="#" class="wpbs-settings-field-show-translations"><?php echo __('Translations', 'wp-booking-system'); ?> <i class="wpbs-icon-down-arrow"></i></a><?php endif?>
            </div>
        </div>
        <?php if (wpbs_translations_active()): ?>
        <!-- Required Field Translations -->
        <div class="wpbs-settings-field-translations">
            <?php foreach ($active_languages as $language): ?>
                <div class="wpbs-settings-field-wrapper wpbs-settings-field-inline wpbs-settings-field-xlarge">
                    <label class="wpbs-settings-field-label" for="payment_bt_instructions_translation_<?php echo $language; ?>"><img src="<?php echo WPBS_PLUGIN_DIR_URL; ?>/assets/img/flags/<?php echo $language; ?>.png" /> <?php echo $languages[$language]; ?></label>
                    <div class="wpbs-settings-field-inner">
                        <?php wp_editor((!empty($settings['payment_bt_instructions_translation_' . $language]) ? html_entity_decode($settings['payment_bt_instructions_translation_' . $language]) : ''), 'payment_bt_instructions_translation_' . $language , array('teeny' => true, 'textarea_rows' => 10, 'media_buttons' => false, 'textarea_name' => 'wpbs_settings[payment_bt_instructions_translation_' . $language . ']'))?>
                    </div>
                </div>
            <?php endforeach;?>
        </div>
        <?php endif;?>
        
    </div>

</div>
