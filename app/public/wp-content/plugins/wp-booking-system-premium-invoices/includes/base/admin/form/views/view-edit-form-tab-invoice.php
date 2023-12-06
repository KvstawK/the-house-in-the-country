<?php
$form_id   = absint( ! empty( $_GET['form_id'] ) ? $_GET['form_id'] : 0 );
$form      = wpbs_get_form( $form_id );

if( is_null( $form ) )
    return;

$form_meta = wpbs_get_form_meta($form_id);
$form_data = $form->get('fields');

$settings = get_option( 'wpbs_settings', array() );
$active_languages = (!empty($settings['active_languages']) ? $settings['active_languages'] : array());
$languages = wpbs_get_languages();


$email_fields = wpbs_form_get_email_fields($form_data);

$attachment_email_types = wpbs_invc_get_attachment_email_types();
?>


<!-- Form Changed Notice -->
<div class="wpbs-page-notice notice-info wpbs-form-changed-notice"> 
    <p><?php echo __( 'It appears you made changes to the form. Make sure you save the form before you make any changes on this page to ensure all dynamic tags are up to date.', 'wp-booking-system-invoices'); ?></p>
</div>

<!-- Dynamic Tags -->
<div class="card wpbs-email-tags-wrapper">
    <h2 class="title"><?php echo __( 'Dynamic Tags', 'wp-booking-system-invoices'); ?></h2>
    <p><?php echo __( 'You can use these dynamic tags in the Buyer Details field. They will be replaced with the values submitted in the form.', 'wp-booking-system-invoices'); ?></p>
    
    <?php wpbs_output_email_tags($form_data); ?>
</div>

<div class="wpbs-settings-field-wrapper wpbs-settings-field-inline wpbs-settings-field-heading wpbs-settings-field-large">
    <label class="wpbs-settings-field-label"><?php echo __( 'Invoice Settings', 'wp-booking-system-invoices' ); ?> </label>
    <div class="wpbs-settings-field-inner">&nbsp;</div>
</div>

<!-- Buyer Details -->
<div class="wpbs-settings-field-wrapper wpbs-settings-field-inline wpbs-settings-field-large">
    <label class="wpbs-settings-field-label" for="invoice_buyer_details">
        <?php echo __( 'Buyer Details', 'wp-booking-system-invoices'); ?>
        <?php echo wpbs_get_output_tooltip(__('The details that will appear on the invoice under the "Buyer Details" section. You can add text or Dynamic Tags which will be replaced with the form field values.', 'wp-booking-system-invoices'));?>
    </label>

    <div class="wpbs-settings-field-inner">
        <textarea name="invoice_buyer_details"id="invoice_buyer_details"><?php echo ( !empty($form_meta['invoice_buyer_details'][0]) ) ? esc_textarea($form_meta['invoice_buyer_details'][0]) : '';?></textarea>
    </div>
</div>


<div class="wpbs-settings-field-wrapper wpbs-settings-field-inline wpbs-settings-field-heading wpbs-settings-field-large">
    <label class="wpbs-settings-field-label"><?php echo __( 'Attach Invoice to Emails', 'wp-booking-system-invoices' ); ?> </label>
    <div class="wpbs-settings-field-inner">&nbsp;</div>
</div>

<?php foreach($attachment_email_types as $email_type => $email_name): ?>
    <!-- Send Attachment $email_type -->
    <div class="wpbs-settings-field-wrapper wpbs-settings-field-inline wpbs-settings-field-large wpbs-settings-field-invoice-attachment-<?php echo sanitize_title($email_name);?>">
        <label class="wpbs-settings-field-label" for="invoice_attach_to_<?php echo $email_type;?>_email">
            <?php echo sprintf(__( '%s Email', 'wp-booking-system-invoices' ), $email_name); ?>
            <?php echo wpbs_get_output_tooltip(sprintf(__("Include the invoice as an attachment to the %s Notification email when a booking is made.", 'wp-booking-system-invoices'), $email_name));?>
        </label>

        <div class="wpbs-settings-field-inner">
            <label for="invoice_attach_to_<?php echo $email_type;?>_email" class="wpbs-checkbox-switch">
                <input type="hidden" name="invoice_attach_to_<?php echo $email_type;?>_email" value="0">
                <input  name="invoice_attach_to_<?php echo $email_type;?>_email" type="checkbox" id="invoice_attach_to_<?php echo $email_type;?>_email"  class="regular-text wpbs-settings-toggle" <?php echo ( !empty($form_meta['invoice_attach_to_'.$email_type.'_email'][0]) ) ? 'checked' : '';?> >
                <div class="wpbs-checkbox-slider"></div>
            </label>
        </div>
    </div>
<?php endforeach; ?>



<div class="wpbs-settings-field-wrapper wpbs-settings-field-inline wpbs-settings-field-heading wpbs-settings-field-large">
    <label class="wpbs-settings-field-label">
        <?php echo __( 'Overwrite Default Settings', 'wp-booking-system-invoices' ); ?>
        <?php echo wpbs_get_output_tooltip(__('Allows you to specify custom Seller Details and Footer Notes for bookings that are made with this form. If the fields are empty, the default values from the Invoice Settings page will be used.', 'wp-booking-system-invoices'));?>
    </label>
    <div class="wpbs-settings-field-inner">&nbsp;</div>
</div>

<!-- Footer Notes -->
<div class="wpbs-settings-field-translation-wrapper">
    <div class="wpbs-settings-field-wrapper wpbs-settings-field-inline wpbs-settings-field-large">
        <label class="wpbs-settings-field-label" for="invoice_footer_notes">
            <?php echo __( 'Footer Notes', 'wp-booking-system-invoices'); ?>
            <?php echo wpbs_get_output_tooltip(__('Add some text to the bottom of the invoice, like payment instructions or a thank you message.', 'wp-booking-system-invoices'));?>
        </label>

        <div class="wpbs-settings-field-inner">
            <input name="invoice_footer_notes_heading" type="text" id="invoice_footer_notes_heading" class="regular-text" value="<?php echo ( !empty( $form_meta['invoice_footer_notes_heading'][0] ) ) ? $form_meta['invoice_footer_notes_heading'][0] : '';?>" >
            <small><?php echo __('Heading', 'wp-booking-system-invoices') ?></small>
            <textarea name="invoice_footer_notes" id="invoice_footer_notes"><?php echo ( !empty( $form_meta['invoice_footer_notes'][0] ) ) ? $form_meta['invoice_footer_notes'][0] : '';?></textarea>
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
                    <input name="invoice_footer_notes_heading_translation_<?php echo $language; ?>" type="text" id="invoice_footer_notes_heading_translation_<?php echo $language; ?>" class="regular-text" value="<?php echo ( !empty( $form_meta['invoice_footer_notes_heading_translation_'. $language][0] ) ) ? $form_meta['invoice_footer_notes_heading_translation_'. $language][0]: '';?>" >
                    <small><?php echo __('Heading', 'wp-booking-system-invoices') ?></small>
                    <textarea name="invoice_footer_notes_translation_<?php echo $language; ?>" id="invoice_footer_notes_translation_<?php echo $language; ?>"><?php echo ( !empty( $form_meta['invoice_footer_notes_translation_'. $language][0] ) ) ? $form_meta['invoice_footer_notes_translation_'. $language][0] : '';?></textarea>
                    <small><?php echo __('Content', 'wp-booking-system-invoices') ?></small>
                </div>
            </div>
        <?php endforeach;?>
    </div>
    <?php endif; ?>
</div>

<!-- Seller Details -->
<div class="wpbs-settings-field-translation-wrapper">
    <div class="wpbs-settings-field-wrapper wpbs-settings-field-inline wpbs-settings-field-large">
        <label class="wpbs-settings-field-label" for="invoice_seller_details">
            <?php echo __( 'Seller Details', 'wp-booking-system-invoices'); ?>
            <?php echo wpbs_get_output_tooltip(__('Your name, email address, company details, company address...', 'wp-booking-system-invoices'));?>
        </label>
        <div class="wpbs-settings-field-inner">
            <textarea name="invoice_seller_details" id="invoice_seller_details"><?php echo ( !empty( $form_meta['invoice_seller_details'][0] ) ) ? $form_meta['invoice_seller_details'][0] : '';?></textarea>
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
                    <textarea name="invoice_seller_details_translation_<?php echo $language; ?>" id="invoice_seller_details_translation_<?php echo $language; ?>"><?php echo ( !empty( $form_meta['invoice_seller_details_translation_'. $language][0] ) ) ? $form_meta['invoice_seller_details_translation_'. $language][0] : '';?></textarea>
                    
                </div>
            </div>
        <?php endforeach;?>
    </div>
    <?php endif; ?>
</div>