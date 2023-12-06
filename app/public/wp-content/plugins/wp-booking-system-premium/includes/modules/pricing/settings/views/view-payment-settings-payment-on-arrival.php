<?php 
$defaults = wpbs_settings_payment_on_arrival_defaults();
?>

<h2><?php echo __('Payment on Arrival', 'wp-booking-system'); ?><?php echo wpbs_get_output_tooltip(__("Give the customer the option to pay with cash on arrival.", 'wp-booking-system'));?></h2>

<!-- Enable Payment on Arrival -->
<div class="wpbs-settings-field-wrapper wpbs-settings-field-inline wpbs-settings-field-large">
	<label class="wpbs-settings-field-label" for="payment_poa_enable">
        <?php echo __( 'Active', 'wp-booking-system' ); ?>
    </label>

	<div class="wpbs-settings-field-inner">
        <label for="payment_poa_enable" class="wpbs-checkbox-switch">
            <input type="hidden" name="wpbs_settings[payment_poa_enable]" value="0">
            <input data-target="#wpbs-payment-on-arrival" name="wpbs_settings[payment_poa_enable]" type="checkbox" id="payment_poa_enable" class="regular-text wpbs-settings-toggle wpbs-settings-wrap-toggle" <?php echo ( !empty( $settings['payment_poa_enable'] ) ) ? 'checked' : '';?> >
            <div class="wpbs-checkbox-slider"></div>
        </label>
	</div>
</div>

<div id="wpbs-payment-on-arrival" class="wpbs-payment-on-arrival-wrapper wpbs-settings-wrapper <?php echo ( !empty($settings['payment_poa_enable']) ) ? 'wpbs-settings-wrapper-show' : '';?>">

    <!-- Payment Method Name -->
    <div class="wpbs-settings-field-translation-wrapper">
        <div class="wpbs-settings-field-wrapper wpbs-settings-field-inline wpbs-settings-field-large">
            <label class="wpbs-settings-field-label" for="payment_poa_name">
                <?php echo __( 'Display name', 'wp-booking-system' ); ?>
                <?php echo wpbs_get_output_tooltip(__("The payment method name that appears on the booking form.", 'wp-booking-system'));?>
            </label>

            <div class="wpbs-settings-field-inner">
                <input name="wpbs_settings[payment_poa_name]" type="text" id="payment_poa_name"  class="regular-text " value="<?php echo ( !empty( $settings['payment_poa_name'] ) ) ? $settings['payment_poa_name'] : $defaults['display_name'];?>" >
                <?php if (wpbs_translations_active()): ?><a href="#" class="wpbs-settings-field-show-translations"><?php echo __('Translations', 'wp-booking-system'); ?> <i class="wpbs-icon-down-arrow"></i></a><?php endif?>
            </div>
        </div>
        <?php if (wpbs_translations_active()): ?>
        <!-- Required Field Translations -->
        <div class="wpbs-settings-field-translations">
            <?php foreach ($active_languages as $language): ?>
                <div class="wpbs-settings-field-wrapper wpbs-settings-field-inline wpbs-settings-field-large">
                    <label class="wpbs-settings-field-label" for="payment_poa_name_translation_<?php echo $language; ?>"><img src="<?php echo WPBS_PLUGIN_DIR_URL; ?>/assets/img/flags/<?php echo $language; ?>.png" /> <?php echo $languages[$language]; ?></label>
                    <div class="wpbs-settings-field-inner">
                        <input name="wpbs_settings[payment_poa_name_translation_<?php echo $language; ?>]" type="text" id="payment_poa_name_translation_<?php echo $language; ?>" value="<?php echo (!empty($settings['payment_poa_name_translation_'. $language])) ? esc_attr($settings['payment_poa_name_translation_'. $language]) : ''; ?>" class="regular-text" >
                    </div>
                </div>
            <?php endforeach;?>
        </div>
        <?php endif; ?>
    </div>

    <!-- Payment Method Description -->
    <div class="wpbs-settings-field-translation-wrapper">
        <div class="wpbs-settings-field-wrapper wpbs-settings-field-inline wpbs-settings-field-large">
            <label class="wpbs-settings-field-label" for="payment_poa_description">
                <?php echo __( 'Description', 'wp-booking-system' ); ?>
                <?php echo wpbs_get_output_tooltip(__("The payment method description that appears on the booking form.", 'wp-booking-system'));?>
            </label>

            <div class="wpbs-settings-field-inner">
                <input name="wpbs_settings[payment_poa_description]" type="text" id="payment_poa_description"  class="regular-text " value="<?php echo ( !empty( $settings['payment_poa_description'] ) ) ? $settings['payment_poa_description'] : $defaults['description'];?>" >
                <?php if (wpbs_translations_active()): ?><a href="#" class="wpbs-settings-field-show-translations"><?php echo __('Translations', 'wp-booking-system'); ?> <i class="wpbs-icon-down-arrow"></i></a><?php endif?>
            </div>
        </div>
        <?php if (wpbs_translations_active()): ?>
        <!-- Required Field Translations -->
        <div class="wpbs-settings-field-translations">
            <?php foreach ($active_languages as $language): ?>
                <div class="wpbs-settings-field-wrapper wpbs-settings-field-inline wpbs-settings-field-large">
                    <label class="wpbs-settings-field-label" for="payment_poa_description_translation_<?php echo $language; ?>"><img src="<?php echo WPBS_PLUGIN_DIR_URL; ?>/assets/img/flags/<?php echo $language; ?>.png" /> <?php echo $languages[$language]; ?></label>
                    <div class="wpbs-settings-field-inner">
                        <input name="wpbs_settings[payment_poa_description_translation_<?php echo $language; ?>]" type="text" id="payment_poa_description_translation_<?php echo $language; ?>" value="<?php echo (!empty($settings['payment_poa_description_translation_'. $language])) ? esc_attr($settings['payment_poa_description_translation_'. $language]) : ''; ?>" class="regular-text" >
                    </div>
                </div>
            <?php endforeach;?>
        </div>
        <?php endif; ?>
    </div>

</div>