<?php 
$active_languages = (!empty($settings['active_languages']) ? $settings['active_languages'] : array());
$languages = wpbs_get_languages();

$default_strings = wpbs_contract_default_strings();

$strings = array(
    'contract' => array(
        'label' => __('Contract', 'wp-booking-system-contracts')
    ),
    
);

$strings = apply_filters('wpbs_contract_default_strings_labels', $strings);
?>

<h2><?php echo __('Contract Settings', 'wp-booking-system-contracts') ?></h2>

<div class="wpbs-settings-field-wrapper wpbs-settings-field-inline wpbs-settings-field-large">
    <label class="wpbs-settings-field-label" for="contract_logo_type">
        <?php echo __( 'Logo Type', 'wp-booking-system-contracts'); ?>
    </label>

    <div class="wpbs-settings-field-inner">
        <select name="wpbs_settings[contract_logo_type]" id="contract_logo_type" >
            <option value="image" <?php echo isset($settings['contract_logo_type']) ? selected($settings['contract_logo_type'],'image') : ''; ?>><?php echo __('Image', 'wp-booking-system-contracts'); ?></option>
            <option value="text" <?php echo isset($settings['contract_logo_type']) ? selected($settings['contract_logo_type'],'text') : ''; ?>><?php echo __('Text', 'wp-booking-system-contracts'); ?></option>
        </select>
    </div>
</div>

<!-- Logo - Image -->
<div class="wpbs-contract-settings-logo-type wpbs-contract-settings-logo-type-image wpbs-hide">
    <div class="wpbs-settings-field-wrapper wpbs-settings-field-inline wpbs-settings-field-large">
        <label class="wpbs-settings-field-label" for="contract_logo_type">
            <?php echo __( 'Logo', 'wp-booking-system-contracts'); ?>
        </label>

        <div class="wpbs-settings-field-inner">            
            <img class="wpbs-media-upload-preview" src="<?php echo ( !empty( $settings['contract_logo_image'] ) ) ? $settings['contract_logo_image'] : '';?>" />
            <input type="text" name="wpbs_settings[contract_logo_image]"  class="wpbs-media-upload-url regular-text" value="<?php echo ( !empty( $settings['contract_logo_image'] ) ) ? $settings['contract_logo_image'] : '';?>" >
            <button name="upload-btn" class="wpbs-media-upload-button button-secondary"><?php echo __( 'Upload Image', 'wp-booking-system-contracts'); ?></button>
            <a href="#" class="wpbs-media-upload-remove <?php echo ( empty( $settings['contract_logo_image'] ) ) ? 'wpbs-hide' : '';?>"><?php echo __( 'remove image', 'wp-booking-system-contracts'); ?></a>
        </div>
    </div>
</div>

<!-- Logo - Text -->
<div class="wpbs-contract-settings-logo-type wpbs-contract-settings-logo-type-text wpbs-hide">
    <!-- Logo - Heading -->
    <div class="wpbs-settings-field-translation-wrapper">
        <div class="wpbs-settings-field-wrapper wpbs-settings-field-inline wpbs-settings-field-large">
            <label class="wpbs-settings-field-label" for="contract_logo_heading">
                <?php echo __( 'Logo - Heading', 'wp-booking-system-contracts'); ?>
                <?php echo wpbs_get_output_tooltip(__("The payment method name that appears on the booking form.", 'wp-booking-system-contracts'));?>
            </label>

            <div class="wpbs-settings-field-inner">
                <input name="wpbs_settings[contract_logo_heading]" type="text" id="contract_logo_heading" class="regular-text" value="<?php echo ( !empty( $settings['contract_logo_heading'] ) ) ? $settings['contract_logo_heading'] : '';?>" >
                <?php if (wpbs_translations_active()): ?><a href="#" class="wpbs-settings-field-show-translations"><?php echo __('Translations', 'wp-booking-system-contracts'); ?> <i class="wpbs-icon-down-arrow"></i></a><?php endif?>
            </div>
        </div>
        <?php if (wpbs_translations_active()): ?>
        <!-- Required Field Translations -->
        <div class="wpbs-settings-field-translations">
            <?php foreach ($active_languages as $language): ?>
                <div class="wpbs-settings-field-wrapper wpbs-settings-field-inline wpbs-settings-field-large">
                    <label class="wpbs-settings-field-label" for="contract_logo_heading_translation_<?php echo $language; ?>"><img src="<?php echo WPBS_PLUGIN_DIR_URL; ?>/assets/img/flags/<?php echo $language; ?>.png" /> <?php echo $languages[$language]; ?></label>
                    <div class="wpbs-settings-field-inner">
                        <input name="wpbs_settings[contract_logo_heading_translation_<?php echo $language; ?>]" type="text" id="contract_logo_heading_translation_<?php echo $language; ?>" value="<?php echo (!empty($settings['contract_logo_heading_translation_'. $language])) ? esc_attr($settings['contract_logo_heading_translation_'. $language]) : ''; ?>" class="regular-text" >
                    </div>
                </div>
            <?php endforeach;?>
        </div>
        <?php endif; ?>
    </div>

    <!-- Logo - Sub Heading -->
    <div class="wpbs-settings-field-translation-wrapper">
        <div class="wpbs-settings-field-wrapper wpbs-settings-field-inline wpbs-settings-field-large">
            <label class="wpbs-settings-field-label" for="contract_logo_subheading">
                <?php echo __( 'Logo - Subheading', 'wp-booking-system-contracts'); ?>
                <?php echo wpbs_get_output_tooltip(__("The payment method name that appears on the booking form.", 'wp-booking-system-contracts'));?>
            </label>

            <div class="wpbs-settings-field-inner">
                <input name="wpbs_settings[contract_logo_subheading]" type="text" id="contract_logo_subheading" class="regular-text" value="<?php echo ( !empty( $settings['contract_logo_subheading'] ) ) ? $settings['contract_logo_subheading'] : '';?>" >
                <?php if (wpbs_translations_active()): ?><a href="#" class="wpbs-settings-field-show-translations"><?php echo __('Translations', 'wp-booking-system-contracts'); ?> <i class="wpbs-icon-down-arrow"></i></a><?php endif?>
            </div>
        </div>
        <?php if (wpbs_translations_active()): ?>
        <!-- Required Field Translations -->
        <div class="wpbs-settings-field-translations">
            <?php foreach ($active_languages as $language): ?>
                <div class="wpbs-settings-field-wrapper wpbs-settings-field-inline wpbs-settings-field-large">
                    <label class="wpbs-settings-field-label" for="contract_logo_subheading_translation_<?php echo $language; ?>"><img src="<?php echo WPBS_PLUGIN_DIR_URL; ?>/assets/img/flags/<?php echo $language; ?>.png" /> <?php echo $languages[$language]; ?></label>
                    <div class="wpbs-settings-field-inner">
                        <input name="wpbs_settings[contract_logo_subheading_translation_<?php echo $language; ?>]" type="text" id="contract_logo_subheading_translation_<?php echo $language; ?>" value="<?php echo (!empty($settings['contract_logo_subheading_translation_'. $language])) ? esc_attr($settings['contract_logo_subheading_translation_'. $language]) : ''; ?>" class="regular-text" >
                    </div>
                </div>
            <?php endforeach;?>
        </div>
        <?php endif; ?>
    </div>
</div>

<!-- Series -->
<div class="wpbs-settings-field-wrapper wpbs-settings-field-inline wpbs-settings-field-large">
    <label class="wpbs-settings-field-label" for="contract_series">
        <?php echo __( 'Contract Series', 'wp-booking-system-contracts'); ?>
        <?php echo wpbs_get_output_tooltip(__('Optional. A string that will be prepended to the contract number. For example if you add the series "INV", the contract number will be displayed as INV1234', 'wp-booking-system-contracts'));?>
    </label>

    <div class="wpbs-settings-field-inner">
        <input name="wpbs_settings[contract_series]" type="text" id="contract_series" class="regular-text" value="<?php echo ( !empty( $settings['contract_series'] ) ) ? $settings['contract_series'] : '';?>" >
    </div>
</div>

<!-- Number -->
<div class="wpbs-settings-field-wrapper wpbs-settings-field-inline wpbs-settings-field-large">
    <label class="wpbs-settings-field-label" for="contract_number_offset">
        <?php echo __( 'Contract Number Offset', 'wp-booking-system-contracts'); ?>
        <?php echo wpbs_get_output_tooltip(__("The contract number will be the same as the Booking ID, which starts from 1. You can add an offset to this number, eg. if you want the first contract number to be 101, add an offset of 100.", 'wp-booking-system-contracts'));?>
    </label>

    <div class="wpbs-settings-field-inner">
        <input name="wpbs_settings[contract_number_offset]" type="number" id="contract_number_offset" class="regular-text" value="<?php echo ( !empty( $settings['contract_number_offset'] ) ) ? $settings['contract_number_offset'] : '0';?>" >
    </div>
</div>

<!-- Attachment Prefix -->
<div class="wpbs-settings-field-wrapper wpbs-settings-field-inline wpbs-settings-field-large">
    <label class="wpbs-settings-field-label" for="contract_attachment_prefix">
        <?php echo __( 'Filename Prefix', 'wp-booking-system-contracts'); ?>
        <?php echo wpbs_get_output_tooltip(__('The prefix of the email attachment PDF filename.', 'wp-booking-system-contracts'));?>
    </label>

    <div class="wpbs-settings-field-inner">
        <input name="wpbs_settings[contract_attachment_prefix]" type="text" id="contract_attachment_prefix" class="regular-text" value="<?php echo ( !empty( $settings['contract_attachment_prefix'] ) ) ? $settings['contract_attachment_prefix'] : 'contract';?>" >
    </div>
</div>

<h2><?php echo __('Contract Strings', 'wp-booking-system-contracts'); ?></h2>

<?php foreach ($strings as $key => $string): ?>
<!-- String Field -->
<div class="wpbs-settings-field-translation-wrapper">
    <div class="wpbs-settings-field-wrapper wpbs-settings-field-inline wpbs-settings-field-large">
        <label class="wpbs-settings-field-label" for="wpbs_contract_string_<?php echo $key;?>">
            <?php echo $string['label'] ?>
            <?php if(isset($string['tooltip'])): ?>
                <?php echo wpbs_get_output_tooltip($string['tooltip']);?>
            <?php endif ?>
        </label>
        <div class="wpbs-settings-field-inner">
            <input name="wpbs_settings[contract_strings][<?php echo $key;?>]" type="text" id="wpbs_contract_string_<?php echo $key;?>" value="<?php echo (!empty($settings['contract_strings'][$key])) ? esc_attr($settings['contract_strings'][$key]) : $default_strings[$key]; ?>" class="regular-text" >
            <?php if (wpbs_translations_active()): ?><a href="#" class="wpbs-settings-field-show-translations"><?php echo __('Translations', 'wp-booking-system-contracts'); ?> <i class="wpbs-icon-down-arrow"></i></a><?php endif?>
        </div>
    </div>
    <?php if (wpbs_translations_active()): ?>
    <!-- String Field Translations -->
    <div class="wpbs-settings-field-translations">
        <?php foreach ($active_languages as $language): ?>
            <div class="wpbs-settings-field-wrapper wpbs-settings-field-inline wpbs-settings-field-large">
                <label class="wpbs-settings-field-label" for="wpbs_contract_string_<?php echo $key;?>_translation_<?php echo $language; ?>"><img src="<?php echo WPBS_PLUGIN_DIR_URL; ?>/assets/img/flags/<?php echo $language; ?>.png" /> <?php echo $languages[$language]; ?></label>
                <div class="wpbs-settings-field-inner">
                    <input name="wpbs_settings[contract_strings][<?php echo $key;?>_translation_<?php echo $language; ?>]" type="text" id="wpbs_contract_string_<?php echo $key;?>_translation_<?php echo $language; ?>" value="<?php echo (!empty($settings['contract_strings'][$key.'_translation_' . $language])) ? esc_attr($settings['contract_strings'][$key.'_translation_' . $language]) : ''; ?>" class="regular-text" >
                </div>
            </div>
        <?php endforeach;?>
    </div>
    <?php endif; ?>
</div>
<?php endforeach;?>

