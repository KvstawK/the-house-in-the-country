<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

$settings = get_option('wpbs_settings', array());

$default_strings = wpbs_payment_default_strings();

$active_languages = (!empty($settings['active_languages']) ? $settings['active_languages'] : array());
$languages = wpbs_get_languages();

$strings = array(
    'payment_confirmation' => array(
        'label' => __('"Payment Confirmation" Heading', 'wp-booking-system'),
        'tooltip'  => __('Appears on the payment screen.', 'wp-booking-system'),
    ),
    'your_order' => array(
        'label' => __('"Your Order" Heading', 'wp-booking-system'),
        'tooltip'  => __('Appears above the pricing table.', 'wp-booking-system'),
    ),
    'edit_order' => array(
        'label' => __('"Edit Order" Link', 'wp-booking-system'),
        'tooltip'  => __('Appears above the pricing table as a link which takes you back to the booking form.', 'wp-booking-system'),
    ),
    'item' => array(
        'label' => __('"Item" Table Column', 'wp-booking-system'),
        'tooltip'  => __('Appears in the pricing table.', 'wp-booking-system'),
    ),
    'total' => array(
        'label' => __('"Total" Table Column', 'wp-booking-system'),
        'tooltip'  => __('Appears in the pricing table.', 'wp-booking-system'),
    ),
    'subtotal' => array(
        'label' => __('"Subtotal" Table Column', 'wp-booking-system'),
        'tooltip'  => __('Appears in the pricing table.', 'wp-booking-system'),
    ),
    'processing_payment' => array(
        'label' => __('Processing Payment Message', 'wp-booking-system'),
        'tooltip'  => __('The loading message while the payment is being processed.', 'wp-booking-system'),
    ),
    'select_dates' => array(
        'label' => __('No Dates Selected', 'wp-booking-system'),
        'tooltip'  => __('The message that appears in the pricing table if no dates are selected.', 'wp-booking-system'),
    ),
);

$strings = apply_filters('wpbs_payment_default_strings_labels', $strings);
?>

<h2><?php echo __('Payment Strings', 'wp-booking-system') ?></h2>
<?php foreach ($strings as $key => $string): ?>
<!-- Required Field -->
<div class="wpbs-settings-field-translation-wrapper">
    <div class="wpbs-settings-field-wrapper wpbs-settings-field-inline wpbs-settings-field-large wpbs-settings-large-label">
        <label class="wpbs-settings-field-label" for="wpbs_payment_string_<?php echo $key;?>">
            <?php echo $string['label'] ?>
            <?php if(isset($string['tooltip'])): ?>
                <?php echo wpbs_get_output_tooltip($string['tooltip']);?>
            <?php endif ?>
        </label>
        <div class="wpbs-settings-field-inner">
            <input name="wpbs_settings[payment_strings][<?php echo $key;?>]" type="text" id="wpbs_payment_string_<?php echo $key;?>" value="<?php echo (!empty($settings['payment_strings'][$key])) ? esc_attr($settings['payment_strings'][$key]) : $default_strings[$key]; ?>" class="regular-text" >
            <?php if (wpbs_translations_active()): ?><a href="#" class="wpbs-settings-field-show-translations"><?php echo __('Translations', 'wp-booking-system'); ?> <i class="wpbs-icon-down-arrow"></i></a><?php endif?>
        </div>
    </div>
    <?php if (wpbs_translations_active()): ?>
    <!-- Required Field Translations -->
    <div class="wpbs-settings-field-translations">
        <?php foreach ($active_languages as $language): ?>
            <div class="wpbs-settings-field-wrapper wpbs-settings-field-inline wpbs-settings-field-large wpbs-settings-field-large-label">
                <label class="wpbs-settings-field-label" for="wpbs_payment_string_<?php echo $key;?>_translation_<?php echo $language; ?>"><img src="<?php echo WPBS_PLUGIN_DIR_URL; ?>/assets/img/flags/<?php echo $language; ?>.png" /> <?php echo $languages[$language]; ?></label>
                <div class="wpbs-settings-field-inner">
                    <input name="wpbs_settings[payment_strings][<?php echo $key;?>_translation_<?php echo $language; ?>]" type="text" id="wpbs_payment_string_<?php echo $key;?>_translation_<?php echo $language; ?>" value="<?php echo (!empty($settings['payment_strings'][$key.'_translation_' . $language])) ? esc_attr($settings['payment_strings'][$key.'_translation_' . $language]) : ''; ?>" class="regular-text" >
                </div>
            </div>
        <?php endforeach;?>
    </div>
    <?php endif; ?>
</div>
<?php endforeach;?>
