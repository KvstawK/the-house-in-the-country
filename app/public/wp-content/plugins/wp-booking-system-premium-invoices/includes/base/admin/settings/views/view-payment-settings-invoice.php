<?php 
$active_languages = (!empty($settings['active_languages']) ? $settings['active_languages'] : array());
$languages = wpbs_get_languages();
?>

<h2><?php echo __('Invoice Settings', 'wp-booking-system-invoices') ?></h2>

<div class="wpbs-settings-field-wrapper wpbs-settings-field-inline wpbs-settings-field-large">
    <label class="wpbs-settings-field-label" for="invoice_logo_type">
        <?php echo __( 'Logo Type', 'wp-booking-system-invoices'); ?>
    </label>

    <div class="wpbs-settings-field-inner">
        <select name="wpbs_settings[invoice_logo_type]" id="invoice_logo_type" >
            <option value="image" <?php echo isset($settings['invoice_logo_type']) ? selected($settings['invoice_logo_type'],'image') : ''; ?>><?php echo __('Image', 'wp-booking-system-invoices'); ?></option>
            <option value="text" <?php echo isset($settings['invoice_logo_type']) ? selected($settings['invoice_logo_type'],'text') : ''; ?>><?php echo __('Text', 'wp-booking-system-invoices'); ?></option>
        </select>
    </div>
</div>

<!-- Logo - Image -->
<div class="wpbs-invoice-settings-logo-type wpbs-invoice-settings-logo-type-image wpbs-hide">
    <div class="wpbs-settings-field-wrapper wpbs-settings-field-inline wpbs-settings-field-large">
        <label class="wpbs-settings-field-label" for="invoice_logo_type">
            <?php echo __( 'Logo', 'wp-booking-system-invoices'); ?>
        </label>

        <div class="wpbs-settings-field-inner">
            
            <img class="wpbs-media-upload-preview" src="<?php echo ( !empty( $settings['invoice_logo_image'] ) ) ? $settings['invoice_logo_image'] : '';?>" />
            <input type="text" name="wpbs_settings[invoice_logo_image]" class="wpbs-media-upload-url regular-text" value="<?php echo ( !empty( $settings['invoice_logo_image'] ) ) ? $settings['invoice_logo_image'] : '';?>" >
            <button name="upload-btn" class="wpbs-media-upload-button button-secondary"><?php echo __( 'Upload Image', 'wp-booking-system-invoices'); ?></button>
            <a href="#" class="wpbs-media-upload-remove <?php echo ( empty( $settings['invoice_logo_image'] ) ) ? 'wpbs-hide' : '';?>"><?php echo __( 'remove image', 'wp-booking-system-invoices'); ?></a>
        </div>
    </div>
</div>

<!-- Logo - Text -->
<div class="wpbs-invoice-settings-logo-type wpbs-invoice-settings-logo-type-text wpbs-hide">
    <!-- Logo - Heading -->
    <div class="wpbs-settings-field-translation-wrapper">
        <div class="wpbs-settings-field-wrapper wpbs-settings-field-inline wpbs-settings-field-large">
            <label class="wpbs-settings-field-label" for="invoice_logo_heading">
                <?php echo __( 'Logo - Heading', 'wp-booking-system-invoices'); ?>
                <?php echo wpbs_get_output_tooltip(__("The payment method name that appears on the booking form.", 'wp-booking-system-invoices'));?>
            </label>

            <div class="wpbs-settings-field-inner">
                <input name="wpbs_settings[invoice_logo_heading]" type="text" id="invoice_logo_heading" class="regular-text" value="<?php echo ( !empty( $settings['invoice_logo_heading'] ) ) ? $settings['invoice_logo_heading'] : '';?>" >
                <?php if (wpbs_translations_active()): ?><a href="#" class="wpbs-settings-field-show-translations"><?php echo __('Translations', 'wp-booking-system-invoices'); ?> <i class="wpbs-icon-down-arrow"></i></a><?php endif?>
            </div>
        </div>
        <?php if (wpbs_translations_active()): ?>
        <!-- Required Field Translations -->
        <div class="wpbs-settings-field-translations">
            <?php foreach ($active_languages as $language): ?>
                <div class="wpbs-settings-field-wrapper wpbs-settings-field-inline wpbs-settings-field-large">
                    <label class="wpbs-settings-field-label" for="invoice_logo_heading_translation_<?php echo $language; ?>"><img src="<?php echo WPBS_PLUGIN_DIR_URL; ?>/assets/img/flags/<?php echo $language; ?>.png" /> <?php echo $languages[$language]; ?></label>
                    <div class="wpbs-settings-field-inner">
                        <input name="wpbs_settings[invoice_logo_heading_translation_<?php echo $language; ?>]" type="text" id="invoice_logo_heading_translation_<?php echo $language; ?>" value="<?php echo (!empty($settings['invoice_logo_heading_translation_'. $language])) ? esc_attr($settings['invoice_logo_heading_translation_'. $language]) : ''; ?>" class="regular-text" >
                    </div>
                </div>
            <?php endforeach;?>
        </div>
        <?php endif; ?>
    </div>

    <!-- Logo - Sub Heading -->
    <div class="wpbs-settings-field-translation-wrapper">
        <div class="wpbs-settings-field-wrapper wpbs-settings-field-inline wpbs-settings-field-large">
            <label class="wpbs-settings-field-label" for="invoice_logo_subheading">
                <?php echo __( 'Logo - Subheading', 'wp-booking-system-invoices'); ?>
                <?php echo wpbs_get_output_tooltip(__("The payment method name that appears on the booking form.", 'wp-booking-system-invoices'));?>
            </label>

            <div class="wpbs-settings-field-inner">
                <input name="wpbs_settings[invoice_logo_subheading]" type="text" id="invoice_logo_subheading" class="regular-text" value="<?php echo ( !empty( $settings['invoice_logo_subheading'] ) ) ? $settings['invoice_logo_subheading'] : '';?>" >
                <?php if (wpbs_translations_active()): ?><a href="#" class="wpbs-settings-field-show-translations"><?php echo __('Translations', 'wp-booking-system-invoices'); ?> <i class="wpbs-icon-down-arrow"></i></a><?php endif?>
            </div>
        </div>
        <?php if (wpbs_translations_active()): ?>
        <!-- Required Field Translations -->
        <div class="wpbs-settings-field-translations">
            <?php foreach ($active_languages as $language): ?>
                <div class="wpbs-settings-field-wrapper wpbs-settings-field-inline wpbs-settings-field-large">
                    <label class="wpbs-settings-field-label" for="invoice_logo_subheading_translation_<?php echo $language; ?>"><img src="<?php echo WPBS_PLUGIN_DIR_URL; ?>/assets/img/flags/<?php echo $language; ?>.png" /> <?php echo $languages[$language]; ?></label>
                    <div class="wpbs-settings-field-inner">
                        <input name="wpbs_settings[invoice_logo_subheading_translation_<?php echo $language; ?>]" type="text" id="invoice_logo_subheading_translation_<?php echo $language; ?>" value="<?php echo (!empty($settings['invoice_logo_subheading_translation_'. $language])) ? esc_attr($settings['invoice_logo_subheading_translation_'. $language]) : ''; ?>" class="regular-text" >
                    </div>
                </div>
            <?php endforeach;?>
        </div>
        <?php endif; ?>
    </div>
</div>

<!-- Accent Color -->
<div class="wpbs-settings-field-wrapper wpbs-settings-field-inline wpbs-settings-field-large">
    <label class="wpbs-settings-field-label" for="invoice_color">
        <?php echo __( 'Accent Color', 'wp-booking-system-invoices'); ?>
    </label>

    <div class="wpbs-settings-field-inner">
        <input name="wpbs_settings[invoice_color]" type="text" id="invoice_color" class="wpbs-colorpicker" value="<?php echo ( !empty( $settings['invoice_color'] ) ) ? $settings['invoice_color'] : '';?>" >
    </div>
</div>

<!-- Series -->
<div class="wpbs-settings-field-wrapper wpbs-settings-field-inline wpbs-settings-field-large">
    <label class="wpbs-settings-field-label" for="invoice_series">
        <?php echo __( 'Invoice Series', 'wp-booking-system-invoices'); ?>
        <?php echo wpbs_get_output_tooltip(__('Optional. A string that will be prepended to the invoice number. For example if you add the series "INV", the invoice number will be displayed as INV1234', 'wp-booking-system-invoices'));?>
    </label>

    <div class="wpbs-settings-field-inner">
        <input name="wpbs_settings[invoice_series]" type="text" id="invoice_series" class="regular-text" value="<?php echo ( !empty( $settings['invoice_series'] ) ) ? $settings['invoice_series'] : '';?>" >
    </div>
</div>

<!-- Number -->
<div class="wpbs-settings-field-wrapper wpbs-settings-field-inline wpbs-settings-field-large">
    <label class="wpbs-settings-field-label" for="invoice_number_offset">
        <?php echo __( 'Invoice Number Offset', 'wp-booking-system-invoices'); ?>
        <?php echo wpbs_get_output_tooltip(__("The invoice number will be the same as the Booking ID, which starts from 1. You can add an offset to this number, eg. if you want the first invoice number to be 101, add an offset of 100.", 'wp-booking-system-invoices'));?>
    </label>

    <div class="wpbs-settings-field-inner">
        <input name="wpbs_settings[invoice_number_offset]" type="number" id="invoice_number_offset" class="regular-text" value="<?php echo ( !empty( $settings['invoice_number_offset'] ) ) ? $settings['invoice_number_offset'] : '0';?>" >
    </div>
</div>

<!-- Due Date -->
<div class="wpbs-settings-field-wrapper wpbs-settings-field-inline wpbs-settings-field-large">
    <label class="wpbs-settings-field-label" for="invoice_due_date">
        <?php echo __( 'Invoice Due Date', 'wp-booking-system-invoices'); ?>
        <?php echo wpbs_get_output_tooltip(__('The due date, in number of days. Leave 0 if you do not want to display the due date on the invoice.', 'wp-booking-system-invoices'));?>
    </label>

    <div class="wpbs-settings-field-inner">
        <input name="wpbs_settings[invoice_due_date]" type="number" id="invoice_due_date" class="regular-text" value="<?php echo ( !empty( $settings['invoice_due_date'] ) ) ? $settings['invoice_due_date'] : '0';?>" > <?php echo __('days', 'wp-booking-system-invoices') ?>
    </div>
</div>



<!-- VAT -->
<div class="wpbs-settings-field-wrapper wpbs-settings-field-inline wpbs-settings-field-large">
    <label class="wpbs-settings-field-label" for="invoice_vat">
        <?php echo __( 'VAT', 'wp-booking-system-invoices'); ?>
        <?php echo wpbs_get_output_tooltip(__('Show the VAT on the invoice. VAT will be calcualted as a percentage from the existing prices. Leave 0 to disable VAT.', 'wp-booking-system-invoices'));?>
    </label>

    <div class="wpbs-settings-field-inner">
        <span class="input-before">
            <span class="before">%</span>
            <input name="wpbs_settings[invoice_vat]" type="number" id="invoice_vat" step="0.01" class="regular-text" value="<?php echo ( !empty( $settings['invoice_vat'] ) ) ? $settings['invoice_vat'] : '0';?>" >
        </span>
    </div>
</div>

<!-- Attachment Prefix -->
<div class="wpbs-settings-field-wrapper wpbs-settings-field-inline wpbs-settings-field-large">
    <label class="wpbs-settings-field-label" for="invoice_attachment_prefix">
        <?php echo __( 'Filename Prefix', 'wp-booking-system-invoices'); ?>
        <?php echo wpbs_get_output_tooltip(__('The prefix of the email attachment PDF filename.', 'wp-booking-system-invoices'));?>
    </label>

    <div class="wpbs-settings-field-inner">
        <input name="wpbs_settings[invoice_attachment_prefix]" type="text" id="invoice_attachment_prefix" class="regular-text" value="<?php echo ( !empty( $settings['invoice_attachment_prefix'] ) ) ? $settings['invoice_attachment_prefix'] : 'invoice';?>" >
    </div>
</div>

<!-- Seller Details -->
<div class="wpbs-settings-field-translation-wrapper">
    <div class="wpbs-settings-field-wrapper wpbs-settings-field-inline wpbs-settings-field-large">
        <label class="wpbs-settings-field-label" for="invoice_seller_details">
            <?php echo __( 'Seller Details', 'wp-booking-system-invoices'); ?>
            <?php echo wpbs_get_output_tooltip(__('Your name, email address, company details, company address...', 'wp-booking-system-invoices'));?>
        </label>
        <div class="wpbs-settings-field-inner">
            <textarea name="wpbs_settings[invoice_seller_details]" id="invoice_seller_details"><?php echo ( !empty( $settings['invoice_seller_details'] ) ) ? $settings['invoice_seller_details'] : '';?></textarea>
        </div>
        <?php if (wpbs_translations_active()): ?><a href="#" class="wpbs-settings-field-show-translations"><?php echo __('Translations', 'wp-booking-system-invoices'); ?> <i class="wpbs-icon-down-arrow"></i></a><?php endif?>
    </div>
    <?php if (wpbs_translations_active()): ?>
    <!-- Required Field Translations -->
    <div class="wpbs-settings-field-translations">
        <?php foreach ($active_languages as $language): ?>
            <div class="wpbs-settings-field-wrapper wpbs-settings-field-inline wpbs-settings-field-large">
                <label class="wpbs-settings-field-label" for="invoice_seller_details_translation_<?php echo $language; ?>"><img src="<?php echo WPBS_PLUGIN_DIR_URL; ?>/assets/img/flags/<?php echo $language; ?>.png" /> <?php echo $languages[$language]; ?></label>
                <div class="wpbs-settings-field-inner">
                    <textarea name="wpbs_settings[invoice_seller_details_translation_<?php echo $language; ?>]" id="invoice_seller_details_translation_<?php echo $language; ?>"><?php echo ( !empty( $settings['invoice_seller_details_translation_'. $language] ) ) ? $settings['invoice_seller_details_translation_'. $language] : '';?></textarea>
                    
                </div>
            </div>
        <?php endforeach;?>
    </div>
    <?php endif; ?>
</div>

<!-- Buyer Details -->
<div class="wpbs-settings-field-wrapper wpbs-settings-field-inline wpbs-settings-field-large">
    <label class="wpbs-settings-field-label" for="invoice_seller_details">
        <?php echo __( 'Buyer Details', 'wp-booking-system-invoices'); ?>
    </label>
    <div class="wpbs-settings-field-inner">
        <div class="wpbs-page-notice notice-info wpbs-form-changed-notice">
            <p><?php echo __('Buyer details are configured from the Invoice tab when editing a Form, as these values need to be directly linked to the form.', 'wp-booking-system-invoices') ?></p>
        </div>
        
    </div>
</div>

<!-- Footer Notes -->
<div class="wpbs-settings-field-translation-wrapper">
    <div class="wpbs-settings-field-wrapper wpbs-settings-field-inline wpbs-settings-field-large">
        <label class="wpbs-settings-field-label" for="invoice_footer_notes">
            <?php echo __( 'Footer Notes', 'wp-booking-system-invoices'); ?>
            <?php echo wpbs_get_output_tooltip(__('Add some text to the bottom of the invoice, like payment instructions or a thank you message.', 'wp-booking-system-invoices'));?>
        </label>

        <div class="wpbs-settings-field-inner">
            <input name="wpbs_settings[invoice_footer_notes_heading]" type="text" id="invoice_footer_notes_heading" class="regular-text" value="<?php echo ( !empty( $settings['invoice_footer_notes_heading'] ) ) ? $settings['invoice_footer_notes_heading'] : '';?>" >
            <small><?php echo __('Heading', 'wp-booking-system-invoices') ?></small>
            <textarea name="wpbs_settings[invoice_footer_notes]" id="invoice_footer_notes"><?php echo ( !empty( $settings['invoice_footer_notes'] ) ) ? $settings['invoice_footer_notes'] : '';?></textarea>
            <small><?php echo __('Content', 'wp-booking-system-invoices') ?></small>
            <?php if (wpbs_translations_active()): ?><a href="#" class="wpbs-settings-field-show-translations"><?php echo __('Translations', 'wp-booking-system-invoices'); ?> <i class="wpbs-icon-down-arrow"></i></a><?php endif?>
        </div>
    </div>
    <?php if (wpbs_translations_active()): ?>
    <!-- Required Field Translations -->
    <div class="wpbs-settings-field-translations">
        <?php foreach ($active_languages as $language): ?>
            <div class="wpbs-settings-field-wrapper wpbs-settings-field-inline wpbs-settings-field-large">
                <label class="wpbs-settings-field-label" for="invoice_footer_notes_heading_translation_<?php echo $language; ?>"><img src="<?php echo WPBS_PLUGIN_DIR_URL; ?>/assets/img/flags/<?php echo $language; ?>.png" /> <?php echo $languages[$language]; ?></label>
                <div class="wpbs-settings-field-inner">
                    <input name="wpbs_settings[invoice_footer_notes_heading_translation_<?php echo $language; ?>]" type="text" id="invoice_footer_notes_heading_translation_<?php echo $language; ?>" class="regular-text" value="<?php echo ( !empty( $settings['invoice_footer_notes_heading_translation_'. $language] ) ) ? $settings['invoice_footer_notes_heading_translation_'. $language] : '';?>" >
                    <small><?php echo __('Heading', 'wp-booking-system-invoices') ?></small>
                    <textarea name="wpbs_settings[invoice_footer_notes_translation_<?php echo $language; ?>]" id="invoice_footer_notes_translation_<?php echo $language; ?>"><?php echo ( !empty( $settings['invoice_footer_notes_translation_'. $language] ) ) ? $settings['invoice_footer_notes_translation_'. $language] : '';?></textarea>
                    <small><?php echo __('Content', 'wp-booking-system-invoices') ?></small>
                </div>
            </div>
        <?php endforeach;?>
    </div>
    <?php endif; ?>
</div>

<!-- Show Booking Details -->
<div class="wpbs-settings-field-wrapper wpbs-settings-field-inline wpbs-settings-field-large">
    <label class="wpbs-settings-field-label" for="invoice_booking_details">
        <?php echo __('Show Booking Details', 'wp-booking-system-invoices'); ?>
        <?php echo wpbs_get_output_tooltip(__('Show the booking details (booking ID, starting date...) at the bottom of the invoice', 'wp-booking-system-invoices'));?>
    </label>

    <div class="wpbs-settings-field-inner">
        <label for="invoice_booking_details" class="wpbs-checkbox-switch">
            <input type="hidden" name="wpbs_settings[invoice_booking_details]" value="0">
            <input name="wpbs_settings[invoice_booking_details]" type="checkbox" id="invoice_booking_details"  class="regular-text wpbs-settings-toggle " <?php echo (!empty($settings['invoice_booking_details'])) ? 'checked' : ''; ?> >
            <div class="wpbs-checkbox-slider"></div>
        </label>
    </div>
</div>

<!-- Show Individual Items -->
<div class="wpbs-settings-field-wrapper wpbs-settings-field-inline wpbs-settings-field-large">
    <label class="wpbs-settings-field-label" for="invoice_individual_items">
        <?php echo __('Individual Items', 'wp-booking-system-invoices'); ?>
        <?php echo wpbs_get_output_tooltip(__('Show each booked day separately on the invoice.', 'wp-booking-system-invoices'));?>
    </label>

    <div class="wpbs-settings-field-inner">
        <label for="invoice_individual_items" class="wpbs-checkbox-switch">
            <input type="hidden" name="wpbs_settings[invoice_individual_items]" value="0">
            <input name="wpbs_settings[invoice_individual_items]" type="checkbox" id="invoice_individual_items"  class="regular-text wpbs-settings-toggle " <?php echo (!empty($settings['invoice_individual_items'])) ? 'checked' : ''; ?> >
            <div class="wpbs-checkbox-slider"></div>
        </label>
    </div>
</div>

<!-- Remove Free Items -->
<div class="wpbs-settings-field-wrapper wpbs-settings-field-inline wpbs-settings-field-large">
    <label class="wpbs-settings-field-label" for="invoice_free_items">
        <?php echo __('Remove Free Items', 'wp-booking-system-invoices'); ?>
        <?php echo wpbs_get_output_tooltip(__('Hide line items with a price of 0. ', 'wp-booking-system-invoices'));?>
    </label>

    <div class="wpbs-settings-field-inner">
        <label for="invoice_free_items" class="wpbs-checkbox-switch">
            <input type="hidden" name="wpbs_settings[invoice_free_items]" value="0">
            <input name="wpbs_settings[invoice_free_items]" type="checkbox" id="invoice_free_items"  class="regular-text wpbs-settings-toggle " <?php echo (!empty($settings['invoice_free_items'])) ? 'checked' : ''; ?> >
            <div class="wpbs-checkbox-slider"></div>
        </label>
    </div>
</div>
