<?php
$default_strings = wpbs_form_default_strings();
$strings = wpbs_form_default_string_values();
?>

<!-- Enable Notification -->
<div class="wpbs-settings-field-wrapper wpbs-settings-field-inline wpbs-settings-field-large">
	<label class="wpbs-settings-field-label" for="overwrite_strings_and_translations">
        <?php echo __( 'Use Custom Strings', 'wp-booking-system' ); ?>
        <?php echo wpbs_get_output_tooltip(__("Use custom Strings and Translations for this form instead of the default Strings and Translations from the Settings page.", 'wp-booking-system'));?>
    </label>

	<div class="wpbs-settings-field-inner">
        <label for="overwrite_strings_and_translations" class="wpbs-checkbox-switch">
            <input type="hidden" name="overwrite_strings_and_translations" value="0">
            <input data-target="#wpbs-overwrite-strings-and-translations" name="overwrite_strings_and_translations" type="checkbox" id="overwrite_strings_and_translations"  class="regular-text wpbs-settings-toggle wpbs-settings-wrap-toggle" <?php echo ( !empty($form_meta['overwrite_strings_and_translations'][0]) ) ? 'checked' : '';?> >
            <div class="wpbs-checkbox-slider"></div>
        </label>
	</div>
</div>

<div id="wpbs-overwrite-strings-and-translations" class="wpbs-user-notification-wrapper wpbs-settings-wrapper <?php echo ( !empty($form_meta['overwrite_strings_and_translations'][0]) ) ? 'wpbs-settings-wrapper-show' : '';?>">

<?php foreach ($strings as $section):?>
    
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
                <input name="form_strings_<?php echo $key; ?>" type="text" id="form_strings_<?php echo $key; ?>" value="<?php echo (!empty($form_meta['form_strings_' . $key][0])) ? esc_attr($form_meta['form_strings_' . $key][0]) : $default_strings[$key]; ?>" class="regular-text" >
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
                        <input name="form_strings_<?php echo $key; ?>_translation_<?php echo $language; ?>" type="text" id="form_strings_<?php echo $key; ?>_translation_<?php echo $language; ?>" value="<?php echo (!empty($form_meta['form_strings_' . $key . '_translation_' . $language][0])) ? esc_attr($form_meta['form_strings_' . $key . '_translation_' . $language][0]) : ''; ?>" class="regular-text" >
                    </div>
                </div>
            <?php endforeach;?>
        </div>
        <?php endif?>
    </div>
    <?php endforeach;?>
<?php endforeach;?>

</div>