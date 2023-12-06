<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

$default_strings = wpbs_form_default_strings();
$strings = wpbs_form_default_string_values();
?>

<h2><?php echo __('Form Strings', 'wp-booking-system') ?></h2>

<div class="wpbs-page-notice notice-info wpbs-form-changed-notice">
    <p><?php echo __("These are the default form strings and translations. They can be overwritten individually for each form on its settings page.", 'wp-booking-system') ?></p>
</div>

<?php

foreach ($strings as $section):?>
    
    <div class="wpbs-settings-field-wrapper wpbs-settings-field-inline wpbs-settings-field-heading wpbs-settings-field-large">
        <label class="wpbs-settings-field-label"><?php echo $section['label'] ?></label>
        <div class="wpbs-settings-field-inner">&nbsp;</div>
    </div>
    <?php foreach ($section['strings'] as $key => $string): ?>
    <!-- Required Field -->
    <div class="wpbs-settings-field-translation-wrapper">
        <div class="wpbs-settings-field-wrapper wpbs-settings-field-inline wpbs-settings-field-large">
            <label class="wpbs-settings-field-label" for="form_strings_<?php echo $key; ?>">
                <?php echo $string['label'] ?>
                <?php if (isset($string['tooltip'])): ?>
                    <?php echo wpbs_get_output_tooltip($string['tooltip']); ?>
                <?php endif?>
            </label>
            <div class="wpbs-settings-field-inner">
                <input name="wpbs_settings[form_strings_<?php echo $key; ?>]" type="text" id="form_strings_<?php echo $key; ?>" value="<?php echo (!empty($settings['form_strings_' . $key])) ? esc_attr($settings['form_strings_' . $key]) : $default_strings[$key]; ?>" class="regular-text" >
                <?php if (wpbs_translations_active()): ?><a href="#" class="wpbs-settings-field-show-translations"><?php echo __('Translations', 'wp-booking-system'); ?> <i class="wpbs-icon-down-arrow"></i></a><?php endif?>
            </div>
        </div>
        <?php if (wpbs_translations_active()): ?>
        <!-- Required Field Translations -->
        <div class="wpbs-settings-field-translations">
            <?php foreach ($active_languages as $language): ?>
                <div class="wpbs-settings-field-wrapper wpbs-settings-field-inline wpbs-settings-field-large">
                    <label class="wpbs-settings-field-label" for="form_strings_<?php echo $key; ?>_translation_<?php echo $language; ?>"><img src="<?php echo WPBS_PLUGIN_DIR_URL; ?>/assets/img/flags/<?php echo $language; ?>.png" /> <?php echo $languages[$language]; ?></label>
                    <div class="wpbs-settings-field-inner">
                        <input name="wpbs_settings[form_strings_<?php echo $key; ?>_translation_<?php echo $language; ?>]" type="text" id="form_strings_<?php echo $key; ?>_translation_<?php echo $language; ?>" value="<?php echo (!empty($settings['form_strings_' . $key . '_translation_' . $language])) ? esc_attr($settings['form_strings_' . $key . '_translation_' . $language]) : ''; ?>" class="regular-text" >
                    </div>
                </div>
            <?php endforeach;?>
        </div>
        <?php endif?>
    </div>
    <?php endforeach;?>
<?php endforeach;?>