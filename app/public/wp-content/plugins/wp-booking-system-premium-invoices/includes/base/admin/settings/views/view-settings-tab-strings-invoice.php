<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

$settings = get_option('wpbs_settings', array());
$active_languages = (!empty($settings['active_languages']) ? $settings['active_languages'] : array());
$languages = wpbs_get_languages();

$default_strings = wpbs_invoice_default_strings();

$strings = array(
    'invoice' => array(
        'label' => __('Invoice', 'wp-booking-system-invoices')
    ),
    'seller' => array(
        'label' => __('Seller', 'wp-booking-system-invoices')
    ),
    'buyer' => array(
        'label' => __('Buyer', 'wp-booking-system-invoices')
    ),
    'details' => array(
        'label' => __('Details', 'wp-booking-system-invoices'),
    ),
    'booking_details' => array(
        'label' => __('Booking Details', 'wp-booking-system-invoices'),
    ),
    'calendar' => array(
        'label' => __('Calendar', 'wp-booking-system-invoices'),
    ),
    'invoice_number' => array(
        'label' => __('Invoice Number', 'wp-booking-system-invoices'),
    ),
    'invoice_date' => array(
        'label' => __('Invoice Date', 'wp-booking-system-invoices'),
    ),
    'due_date' => array(
        'label' => __('Due Date', 'wp-booking-system-invoices'),
    ),
    'description' => array(
        'label' => __('Description', 'wp-booking-system-invoices'),
    ),
    'quantity' => array(
        'label' => __('Quantity', 'wp-booking-system-invoices'),
    ),
    'unit_price' => array(
        'label' => __('Unit Price', 'wp-booking-system-invoices'),
    ),
    'vat' => array(
        'label' => __('VAT', 'wp-booking-system-invoices'),
    ),
    'subtotal' => array(
        'label' => __('Subtotal', 'wp-booking-system-invoices'),
    ),
    'total' => array(
        'label' => __('Total', 'wp-booking-system-invoices'),
    ),
    
);

$strings = apply_filters('wpbs_invoice_default_strings_labels', $strings);
?>

<h2><?php echo __('Invoice Strings', 'wp-booking-system-invoices'); ?></h2>

<?php foreach ($strings as $key => $string): ?>
<!-- Required Field -->
<div class="wpbs-settings-field-translation-wrapper">
    <div class="wpbs-settings-field-wrapper wpbs-settings-field-inline wpbs-settings-field-large">
        <label class="wpbs-settings-field-label" for="wpbs_invoice_string_<?php echo $key;?>">
            <?php echo $string['label'] ?>
            <?php if(isset($string['tooltip'])): ?>
                <?php echo wpbs_get_output_tooltip($string['tooltip']);?>
            <?php endif ?>
        </label>
        <div class="wpbs-settings-field-inner">
            <input name="wpbs_settings[invoice_strings][<?php echo $key;?>]" type="text" id="wpbs_invoice_string_<?php echo $key;?>" value="<?php echo (!empty($settings['invoice_strings'][$key])) ? esc_attr($settings['invoice_strings'][$key]) : $default_strings[$key]; ?>" class="regular-text" >
            <?php if (wpbs_translations_active()): ?><a href="#" class="wpbs-settings-field-show-translations"><?php echo __('Translations', 'wp-booking-system-invoices'); ?> <i class="wpbs-icon-down-arrow"></i></a><?php endif?>
        </div>
    </div>
    <?php if (wpbs_translations_active()): ?>
    <!-- Required Field Translations -->
    <div class="wpbs-settings-field-translations">
        <?php foreach ($active_languages as $language): ?>
            <div class="wpbs-settings-field-wrapper wpbs-settings-field-inline wpbs-settings-field-large">
                <label class="wpbs-settings-field-label" for="wpbs_invoice_string_<?php echo $key;?>_translation_<?php echo $language; ?>"><img src="<?php echo WPBS_PLUGIN_DIR_URL; ?>/assets/img/flags/<?php echo $language; ?>.png" /> <?php echo $languages[$language]; ?></label>
                <div class="wpbs-settings-field-inner">
                    <input name="wpbs_settings[invoice_strings][<?php echo $key;?>_translation_<?php echo $language; ?>]" type="text" id="wpbs_invoice_string_<?php echo $key;?>_translation_<?php echo $language; ?>" value="<?php echo (!empty($settings['invoice_strings'][$key.'_translation_' . $language])) ? esc_attr($settings['invoice_strings'][$key.'_translation_' . $language]) : ''; ?>" class="regular-text" >
                </div>
            </div>
        <?php endforeach;?>
    </div>
    <?php endif; ?>
</div>
<?php endforeach;?>

