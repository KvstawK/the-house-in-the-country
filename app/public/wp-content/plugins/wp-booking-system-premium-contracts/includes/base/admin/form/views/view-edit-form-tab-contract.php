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

$attachment_email_types = wpbs_cntrct_get_attachment_email_types();
?>


<!-- Form Changed Notice -->
<div class="wpbs-page-notice notice-info wpbs-form-changed-notice"> 
    <p><?php echo __( 'It appears you made changes to the form. Make sure you save the form before you make any changes on this page to ensure all dynamic tags are up to date.', 'wp-booking-system-contracts'); ?></p>
</div>

<!-- Dynamic Tags -->
<div class="card wpbs-email-tags-wrapper">
    <h2 class="title"><?php echo __( 'Dynamic Tags', 'wp-booking-system-contracts'); ?></h2>
    <p><?php echo __( 'You can use these dynamic tags in the contract body field. They will be replaced with the values submitted in the form.', 'wp-booking-system-contracts'); ?></p>
    
    <?php wpbs_output_email_tags($form_data); ?>
</div>

<div class="wpbs-settings-field-wrapper wpbs-settings-field-inline wpbs-settings-field-heading wpbs-settings-field-large">
    <label class="wpbs-settings-field-label"><?php echo __( 'Contract Settings', 'wp-booking-system-contracts' ); ?> </label>
    <div class="wpbs-settings-field-inner">&nbsp;</div>
</div>

<!-- Contract Body -->
<div class="wpbs-settings-field-translation-wrapper">
    <div class="wpbs-settings-field-wrapper wpbs-settings-field-inline wpbs-settings-field-xlarge">
        <label class="wpbs-settings-field-label" for="payment_bt_instructions">
            <?php echo __('Content', 'wp-booking-system'); ?>
            <?php echo wpbs_get_output_tooltip(__('The content that will be used to generate the PDF Contract.', 'wp-booking-system')); ?>
        </label>

        <div class="wpbs-settings-field-inner">
            <?php wp_editor((!empty($form_meta['contract_content'][0]) ? html_entity_decode($form_meta['contract_content'][0]) : ''), 'contract_content', array('teeny' => false, 'textarea_rows' => 20, 'media_buttons' => false, 'textarea_name' => 'contract_content'))?>
            <?php if (wpbs_translations_active()): ?><a href="#" class="wpbs-settings-field-show-translations"><?php echo __('Translations', 'wp-booking-system'); ?> <i class="wpbs-icon-down-arrow"></i></a><?php endif?>
        </div>
    </div>
    <?php if (wpbs_translations_active()): ?>
    <!-- Required Field Translations -->
    <div class="wpbs-settings-field-translations">
        <?php foreach ($active_languages as $language): ?>
            <div class="wpbs-settings-field-wrapper wpbs-settings-field-inline wpbs-settings-field-xlarge">
                <label class="wpbs-settings-field-label" for="contract_content_translation_<?php echo $language; ?>"><img src="<?php echo WPBS_PLUGIN_DIR_URL; ?>/assets/img/flags/<?php echo $language; ?>.png" /> <?php echo $languages[$language]; ?></label>
                <div class="wpbs-settings-field-inner">
                    <?php wp_editor((!empty($form_meta['contract_content_translation_' . $language][0]) ? html_entity_decode($form_meta['contract_content_translation_' . $language][0]) : ''), 'contract_content_translation_' . $language , array('teeny' => false, 'textarea_rows' => 20, 'media_buttons' => false, 'textarea_name' => 'contract_content_translation_' . $language . ''))?>
                </div>
            </div>
        <?php endforeach;?>
    </div>
    <?php endif;?>
    
</div>


<div class="wpbs-settings-field-wrapper wpbs-settings-field-inline wpbs-settings-field-heading wpbs-settings-field-large">
    <label class="wpbs-settings-field-label"><?php echo __( 'Attach Contract to Emails', 'wp-booking-system-contracts' ); ?> </label>
    <div class="wpbs-settings-field-inner">&nbsp;</div>
</div>

<?php foreach($attachment_email_types as $email_type => $email_name): ?>
    <!-- Send Attachment $email_type -->
    <div class="wpbs-settings-field-wrapper wpbs-settings-field-inline wpbs-settings-field-large wpbs-settings-field-contract-attachment-<?php echo sanitize_title($email_name);?>">
        <label class="wpbs-settings-field-label" for="contract_attach_to_<?php echo $email_type;?>_email">
            <?php echo sprintf(__( '%s Email', 'wp-booking-system-contracts' ), $email_name); ?>
            <?php echo wpbs_get_output_tooltip(sprintf(__("Include the contract as an attachment to the %s Notification email.", 'wp-booking-system-contracts'), $email_name));?>
        </label>

        <div class="wpbs-settings-field-inner">
            <label for="contract_attach_to_<?php echo $email_type;?>_email" class="wpbs-checkbox-switch">
                <input type="hidden" name="contract_attach_to_<?php echo $email_type;?>_email" value="0">
                <input  name="contract_attach_to_<?php echo $email_type;?>_email" type="checkbox" id="contract_attach_to_<?php echo $email_type;?>_email"  class="regular-text wpbs-settings-toggle" <?php echo ( !empty($form_meta['contract_attach_to_'.$email_type.'_email'][0]) ) ? 'checked' : '';?> >
                <div class="wpbs-checkbox-slider"></div>
            </label>
        </div>
    </div>
<?php endforeach; ?>
