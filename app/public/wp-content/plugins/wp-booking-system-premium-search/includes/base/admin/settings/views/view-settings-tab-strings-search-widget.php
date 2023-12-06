<?php
$settings = get_option( 'wpbs_settings', array() );
$active_languages = (!empty($settings['active_languages']) ? $settings['active_languages'] : array());
$languages = wpbs_get_languages();

$default_strings = wpbs_s_search_widget_default_strings();
$strings = array(
    'widget_title' => array(
        'label' => __('Search Widget Title', 'wp-booking-system-search')
    ),
    'start_date_label' => array(
        'label' => __('Start Date Label', 'wp-booking-system-search'),
    ),
    'start_date_placeholder' => array(
        'label' => __('Start Date Placeholder', 'wp-booking-system-search'),
    ),
    'end_date_label' => array(
        'label' => __('End Date Label', 'wp-booking-system-search'),
    ),
    'end_date_placeholder' => array(
        'label' => __('End Date Placeholder', 'wp-booking-system-search'),
    ),
    'date_label' => array(
        'label' => __('Date Label', 'wp-booking-system-search'),
    ),
    'search_button_label' => array(
        'label' => __('Search Button Label', 'wp-booking-system-search')
    ),
    'no_start_date' => array(
        'label' => __('No Start Date Error', 'wp-booking-system-search'),
    ),
    'no_end_date' => array(
        'label' => __('No End Date Error', 'wp-booking-system-search'),
    ),
    'invalid_start_date' => array(
        'label' => __('Invalid Start Date Error', 'wp-booking-system-search'),
    ),
    'invalid_end_date' => array(
        'label' => __('Invalid End Date Error', 'wp-booking-system-search'),
    ),
    'results_title' => array(
        'label' => __('Results Title', 'wp-booking-system-search'),
    ),
    'no_results' => array(
        'label' => __('No Results Warning', 'wp-booking-system-search'),
    ),
    'view_button_label' => array(
        'label' => __('Details Button Label', 'wp-booking-system-search'),
    ),
    'starting_from' => array(
        'label' => __('Price', 'wp-booking-system-search'),
    ),
    'label_previous' => array(
        'label' => __('Pagination - Previous', 'wp-booking-system-search'),
    ),
    'label_next' => array(
        'label' => __('Pagination - Next', 'wp-booking-system-search'),
    )
);?>

<h2>Search Widget Strings</h2>
<?php foreach ($strings as $key => $string): ?>
<!-- Required Field -->
<div class="wpbs-settings-field-translation-wrapper">
    <div class="wpbs-settings-field-wrapper wpbs-settings-field-inline wpbs-settings-field-large">
        <label class="wpbs-settings-field-label" for="wpbs_settings_search_addon_<?php echo $key;?>">
            <?php echo $string['label'] ?>
            <?php if(isset($string['tooltip'])): ?>
                <?php echo wpbs_get_output_tooltip($string['tooltip']);?>
            <?php endif ?>
        </label>
        <div class="wpbs-settings-field-inner">
            <input name="wpbs_settings[search_addon][<?php echo $key;?>]" type="text" id="wpbs_settings_search_addon_<?php echo $key;?>" value="<?php echo (!empty($settings['search_addon'][$key])) ? esc_attr($settings['search_addon'][$key]) : $default_strings[$key]; ?>" class="regular-text" >
            <?php if (wpbs_translations_active()): ?><a href="#" class="wpbs-settings-field-show-translations"><?php echo __('Translations', 'wp-booking-system-search'); ?> <i class="wpbs-icon-down-arrow"></i></a><?php endif?>
        </div>
    </div>
    <?php if (wpbs_translations_active()): ?>
    <!-- Required Field Translations -->
    <div class="wpbs-settings-field-translations">
        <?php foreach ($active_languages as $language): ?>
            <div class="wpbs-settings-field-wrapper wpbs-settings-field-inline wpbs-settings-field-large">
                <label class="wpbs-settings-field-label" for="wpbs_settings_search_addon_<?php echo $key;?>_translation_<?php echo $language; ?>"><img src="<?php echo WPBS_PLUGIN_DIR_URL; ?>/assets/img/flags/<?php echo $language; ?>.png" /> <?php echo $languages[$language]; ?></label>
                <div class="wpbs-settings-field-inner">
                    <input name="wpbs_settings[search_addon][<?php echo $key;?>_translation_<?php echo $language; ?>]" type="text" id="wpbs_settings_search_addon_<?php echo $key;?>_translation_<?php echo $language; ?>" value="<?php echo (!empty($settings['search_addon'][$key.'_translation_' . $language])) ? esc_attr($settings['search_addon'][$key.'_translation_' . $language]) : ''; ?>" class="regular-text" >
                </div>
            </div>
        <?php endforeach;?>
    </div>
    <?php endif; ?>
</div>
<?php endforeach;?>
