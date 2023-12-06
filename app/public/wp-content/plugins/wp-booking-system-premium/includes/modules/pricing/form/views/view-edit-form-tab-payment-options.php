<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

$form_id   = absint( ! empty( $_GET['form_id'] ) ? $_GET['form_id'] : 0 );
$form      = wpbs_get_form( $form_id );

$form_data = $form->get('fields');
$form_meta = wpbs_get_form_meta($form_id);

$settings = get_option( 'wpbs_settings', array() );
$active_languages = (!empty($settings['active_languages']) ? $settings['active_languages'] : array());
$languages = wpbs_get_languages();
?>

<div class="wpbs-settings-field-wrapper wpbs-settings-field-inline wpbs-settings-field-heading wpbs-settings-field-large">
    <label class="wpbs-settings-field-label"><?php echo __( 'Payment Options', 'wp-booking-system' ); ?> </label>
    <div class="wpbs-settings-field-inner">&nbsp;</div>
</div>

<!-- Product Name -->
<div class="wpbs-settings-field-translation-wrapper">
    <div class="wpbs-settings-field-wrapper wpbs-settings-field-inline wpbs-settings-field-large">
        <label class="wpbs-settings-field-label" for="product_name">
            <?php echo __('Product Name', 'wp-booking-system'); ?>
            <?php echo wpbs_get_output_tooltip(__('The product name to show in the price calculator for date selections. For eg. "Days". If the users selects 4 days in the calendar, the price calculation will show "Days &times; 4: 100 EUR". ', 'wp-booking-system') . '<br><br><strong>' . __('This overwrites the default Product Name specified in the plugin\'s Settings page.', 'wp-booking-system') . '</strong>'); ?>
        </label>

        <div class="wpbs-settings-field-inner">
            <input name="product_name" id="product_name" placeholder="<?php echo isset($settings['payment_product_name']) ? $settings['payment_product_name'] : '';?>" type="text" value="<?php echo (!empty($form_meta['product_name'][0]) ? esc_attr($form_meta['product_name'][0]) : ''); ?>" />
            <?php if (wpbs_translations_active()): ?><a href="#" class="wpbs-settings-field-show-translations"><?php echo __('Translations', 'wp-booking-system'); ?> <i class="wpbs-icon-down-arrow"></i></a><?php endif?>
        </div>
    </div>
    <?php if (wpbs_translations_active()): ?>
    <!-- Required Field Translations -->
    <div class="wpbs-settings-field-translations">
        <?php foreach ($active_languages as $language): ?>
            <div class="wpbs-settings-field-wrapper wpbs-settings-field-inline wpbs-settings-field-large">
                <label class="wpbs-settings-field-label" for="product_name_translation_<?php echo $language; ?>"><img src="<?php echo WPBS_PLUGIN_DIR_URL; ?>/assets/img/flags/<?php echo $language; ?>.png" /> <?php echo $languages[$language]; ?></label>
                <div class="wpbs-settings-field-inner">
                    <input name="product_name_translation_<?php echo $language; ?>" placeholder="<?php echo isset($settings['payment_product_name_translation_' . $language]) ? $settings['payment_product_name_translation_' . $language] : '';?>" type="text" id="product_name_translation_<?php echo $language; ?>" value="<?php echo (!empty($form_meta['product_name_translation_' . $language][0])) ? esc_attr($form_meta['product_name_translation_' . $language][0]) : ''; ?>" class="regular-text" >
                </div>
            </div>
        <?php endforeach;?>
    </div>
    <?php endif;?>
</div>

<!-- Multiplication Field -->
<div class="wpbs-settings-field-wrapper wpbs-settings-field-inline wpbs-settings-field-large">
    <label class="wpbs-settings-field-label" for="multiplication_field">
        <?php echo __( 'Multiplication Field', 'wp-booking-system' ); ?>
        <?php echo wpbs_get_output_tooltip(__("Multiply the calendar events price by the value of a form field (eg. number of people, inventory..).", 'wp-booking-system'));?>
    </label>

    <div class="wpbs-settings-field-inner">
        <select name="multiplication_field" type="text" id="multiplication_field">
            <option value="">-</option>
            <?php foreach($form_data as $field): if(!in_array($field['type'], array('radio', 'dropdown', 'checkbox', 'product_radio', 'product_dropdown', 'product_number', 'product_checkbox', 'inventory', 'number'))) continue; ?>
                <option <?php echo ( !empty($form_meta['multiplication_field'][0]) && $form_meta['multiplication_field'][0] == $field['id']  ) ? 'selected' : '';?> value="<?php echo $field['id'];?>"><?php echo ($field['values']['default']['label'] ? : '(no label - ' . str_replace('_', ' ', $field['type']) . ' field)') ?></option>
            <?php endforeach; ?>
        </select>
    </div>
</div>

<!-- Hide 0 line items -->
<div class="wpbs-settings-field-wrapper wpbs-settings-field-inline wpbs-settings-field-large">
	<label class="wpbs-settings-field-label" for="hide_zero_line_items">
        <?php echo __( 'Hide Free Line Items', 'wp-booking-system' ); ?>
        <?php echo wpbs_get_output_tooltip(__("Hide line items that are equal to 0 from the pricing table.", 'wp-booking-system'));?>
    </label>

	<div class="wpbs-settings-field-inner">
        <label for="hide_zero_line_items" class="wpbs-checkbox-switch">
            <input type="hidden" name="hide_zero_line_items" value="0">
            <input name="hide_zero_line_items" type="checkbox" id="hide_zero_line_items"  class="regular-text wpbs-settings-toggle wpbs-settings-wrap-toggle" <?php echo ( !empty($form_meta['hide_zero_line_items'][0]) ) ? 'checked' : '';?> >
            <div class="wpbs-checkbox-slider"></div>
        </label>
	</div>
</div>

<?php if(isset($settings['payment_bt_enable']) && $settings['payment_bt_enable']): ?>
<!-- Payment Instructions -->
<div class="wpbs-settings-field-translation-wrapper">
    <div class="wpbs-settings-field-wrapper wpbs-settings-field-inline wpbs-settings-field-xlarge">
        <label class="wpbs-settings-field-label" for="bt_instructions">
            <?php echo __('Bank Transfer Payment Instructions', 'wp-booking-system'); ?>
            <?php echo wpbs_get_output_tooltip(__('Instructions on how and where to send the money. Bank name, account number, etc. This information will be included in the form confirmation message and in the email if the {Bank Transfer Instructions} tag is used.', 'wp-booking-system') . '<br><br><strong>' . __('This overwrites the default Payment Instructions added in the plugin\'s Settings page.', 'wp-booking-system') . '</strong>'); ?>
        </label>

        <div class="wpbs-settings-field-inner">
            <?php wp_editor((!empty($form_meta['bt_instructions'][0]) ? html_entity_decode($form_meta['bt_instructions'][0]) : ''), 'bt_instructions', array('teeny' => true, 'textarea_rows' => 10, 'media_buttons' => false, 'textarea_name' => 'bt_instructions'))?>
            <p><?php echo __('You can use the <span class="wpbs-small-print-tag wpbs-select-on-click">{Amount}</span> tag to show the total amount needed to be paid by the customer, or the <span class="wpbs-small-print-tag wpbs-select-on-click">{Booking ID}</span> tag to add a transaction reference number.', 'wp-booking-system') ?></p>
            <?php if (wpbs_translations_active()): ?><a href="#" class="wpbs-settings-field-show-translations"><?php echo __('Translations', 'wp-booking-system'); ?> <i class="wpbs-icon-down-arrow"></i></a><?php endif?>
        </div>
    </div>
    <?php if (wpbs_translations_active()): ?>
    <!-- Required Field Translations -->
    <div class="wpbs-settings-field-translations">
        <?php foreach ($active_languages as $language): ?>
            <div class="wpbs-settings-field-wrapper wpbs-settings-field-inline wpbs-settings-field-xlarge">
                <label class="wpbs-settings-field-label" for="bt_instructions_translation_<?php echo $language; ?>"><img src="<?php echo WPBS_PLUGIN_DIR_URL; ?>/assets/img/flags/<?php echo $language; ?>.png" /> <?php echo $languages[$language]; ?></label>
                <div class="wpbs-settings-field-inner">
                    <?php wp_editor((!empty($form_meta['bt_instructions_translation_' . $language][0]) ? html_entity_decode($form_meta['bt_instructions_translation_' . $language][0]) : ''), 'bt_instructions_translation_' . $language , array('teeny' => true, 'textarea_rows' => 10, 'media_buttons' => false, 'textarea_name' => 'bt_instructions_translation_' . $language ))?>
                </div>
            </div>
        <?php endforeach;?>
    </div>
    <?php endif;?>
    
</div>
<?php endif; ?>