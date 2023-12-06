<?php 
$email_fields = wpbs_form_get_email_fields($form_data);
?>

<?php if($email_fields === false): ?>

    <div class="wpbs-page-notice notice-error"> 
        <p><?php echo __( 'You must add an Email field to the form in order to configure user notifications.', 'wp-booking-system' ); ?></p>
    </div>

<?php else: ?>

<!-- Form Changed Notice -->
<div class="wpbs-page-notice notice-info wpbs-form-changed-notice"> 
    <p><?php echo __( 'It appears you made changes to the form. Make sure you save the form before you make any changes on this page to ensure all email tags are up to date.', 'wp-booking-system' ); ?></p>
</div>

<div class="wpbs-settings-field-wrapper wpbs-settings-field-inline wpbs-settings-field-heading wpbs-settings-field-large">
    <label class="wpbs-settings-field-label"><?php echo __( 'User Notification', 'wp-booking-system' ); ?> </label>
    <div class="wpbs-settings-field-inner">&nbsp;</div>
</div>

<!-- Enable Notification -->
<div class="wpbs-settings-field-wrapper wpbs-settings-field-inline wpbs-settings-field-large">
	<label class="wpbs-settings-field-label" for="user_notification_enable">
        <?php echo __( 'Enable Notification', 'wp-booking-system' ); ?>
        <?php echo wpbs_get_output_tooltip(__("Send a confirmation email to the person who made the booking.", 'wp-booking-system'));?>
    </label>

	<div class="wpbs-settings-field-inner">
        <label for="user_notification_enable" class="wpbs-checkbox-switch">
            <input type="hidden" name="user_notification_enable" value="0">
            <input data-target="#wpbs-user-notification-wrapper" name="user_notification_enable" type="checkbox" id="user_notification_enable"  class="regular-text wpbs-settings-toggle wpbs-settings-wrap-toggle" <?php echo ( !empty($form_meta['user_notification_enable'][0]) ) ? 'checked' : '';?> >
            <div class="wpbs-checkbox-slider"></div>
        </label>
	</div>
</div>

<div id="wpbs-user-notification-wrapper" class="wpbs-user-notification-wrapper wpbs-settings-wrapper <?php echo ( !empty($form_meta['user_notification_enable'][0]) ) ? 'wpbs-settings-wrapper-show' : '';?>">

    <!-- Email Tags -->
    <div class="card wpbs-email-tags-wrapper">
        <h2 class="title"><?php echo __( 'Dynamic Tags', 'wp-booking-system' ); ?></h2>
        <p><?php echo __( 'You can use these dynamic tags in any of the fields. They will be replaced with the values submitted in the form.', 'wp-booking-system' ); ?></p>
        
        <?php wpbs_output_email_tags($form_data); ?>
    </div>

    <!-- Send To -->
    <div class="wpbs-settings-field-translation-wrapper">
        <div class="wpbs-settings-field-wrapper wpbs-settings-field-inline wpbs-settings-field-large">
            <label class="wpbs-settings-field-label" for="user_notification_send_to"><?php echo __( 'Send To', 'wp-booking-system' ); ?></label>

            <div class="wpbs-settings-field-inner">
                <?php $send_to = ( !empty($form_meta['user_notification_send_to'][0]) ) ? esc_attr($form_meta['user_notification_send_to'][0]) : ''; ?>
                <select name="user_notification_send_to" id="user_notification_send_to">
                    <?php foreach($email_fields as $email_field): ?>
                    <option <?php echo ($send_to == '{' . $email_field['id'] . ':' . $email_field['values']['default']['label'] . '}') ? 'selected' : '';?> value="<?php echo '{' . $email_field['id'] . ':' . $email_field['values']['default']['label'] . '}'; ?>"><?php echo '{' . $email_field['id'] . ':' . $email_field['values']['default']['label'] . '}'; ?></option>
                    <?php endforeach ?>
                </select>
                <a href="#" class="wpbs-settings-field-show-translations"><?php echo __( 'Options', 'wp-booking-system' ); ?> <i class="wpbs-icon-down-arrow"></i></a>
            </div>
        </div>

        <div class="wpbs-settings-field-translations">
            <!-- CC -->
            <div class="wpbs-settings-field-wrapper wpbs-settings-field-inline wpbs-settings-field-large">
                <label class="wpbs-settings-field-label" for="user_notification_send_to_cc"><?php echo __( 'CC', 'wp-booking-system' ); ?></label>
                <div class="wpbs-settings-field-inner">
                    <input name="user_notification_send_to_cc" type="text" id="user_notification_send_to_cc" value="<?php echo ( !empty($form_meta['user_notification_send_to_cc'][0]) ) ? esc_attr($form_meta['user_notification_send_to_cc'][0]) : '';?>" class="regular-text" >
                </div>
            </div>

            <!-- CC -->
            <div class="wpbs-settings-field-wrapper wpbs-settings-field-inline wpbs-settings-field-large">
                <label class="wpbs-settings-field-label" for="user_notification_send_to_bcc"><?php echo __( 'BCC', 'wp-booking-system' ); ?></label>
                <div class="wpbs-settings-field-inner">
                    <input name="user_notification_send_to_bcc" type="text" id="user_notification_send_to_bcc" value="<?php echo ( !empty($form_meta['user_notification_send_to_bcc'][0]) ) ? esc_attr($form_meta['user_notification_send_to_bcc'][0]) : '';?>" class="regular-text" >
                </div>
            </div>
        </div>
    </div>

    <!-- From Name -->
    <div class="wpbs-settings-field-wrapper wpbs-settings-field-inline wpbs-settings-field-large">
        <label class="wpbs-settings-field-label" for="user_notification_from_name"><?php echo __( 'From Name', 'wp-booking-system' ); ?></label>

        <div class="wpbs-settings-field-inner">
            <input name="user_notification_from_name" type="text" id="user_notification_from_name" value="<?php echo ( !empty($form_meta['user_notification_from_name'][0]) ) ? esc_attr($form_meta['user_notification_from_name'][0]) : (isset($settings['default_from_name']) ? esc_attr($settings['default_from_name']) : '');?>" class="regular-text" >
        </div>
    </div>

    <!-- From Email -->
    <?php $from_email = ( !empty($form_meta['user_notification_from_email'][0]) ) ? esc_attr($form_meta['user_notification_from_email'][0]) : (isset($settings['default_from_email']) ? esc_attr($settings['default_from_email']) : ''); ?>
    <div class="wpbs-settings-field-wrapper wpbs-settings-field-inline wpbs-settings-field-large">
        <label class="wpbs-settings-field-label" for="user_notification_from_email"><?php echo __( 'From Email', 'wp-booking-system' ); ?></label>

        <div class="wpbs-settings-field-inner">
            <input name="user_notification_from_email" type="text" id="user_notification_from_email" value="<?php echo $from_email;?>" class="regular-text" >
        </div>
    </div>

    <?php wpbs_same_email_domain_notice($from_email); ?>

    <!-- Reply To -->
    <div class="wpbs-settings-field-wrapper wpbs-settings-field-inline wpbs-settings-field-large">
        <label class="wpbs-settings-field-label" for="user_notification_reply_to"><?php echo __( 'Reply To', 'wp-booking-system' ); ?></label>

        <div class="wpbs-settings-field-inner">
            <input name="user_notification_reply_to" type="text" id="user_notification_reply_to" value="<?php echo ( !empty($form_meta['user_notification_reply_to'][0]) ) ? esc_attr($form_meta['user_notification_reply_to'][0]) : (isset($settings['default_reply_to']) ? esc_attr($settings['default_reply_to']) : '');?>" class="regular-text" >
        </div>
    </div>

    <!-- Subject -->
    <div class="wpbs-settings-field-translation-wrapper">
        
        <div class="wpbs-settings-field-wrapper wpbs-settings-field-inline wpbs-settings-field-large">
            <label class="wpbs-settings-field-label" for="user_notification_subject"><?php echo __( 'Subject', 'wp-booking-system' ); ?></label>

            <div class="wpbs-settings-field-inner">
                <input name="user_notification_subject" type="text" id="user_notification_subject" value="<?php echo ( !empty($form_meta['user_notification_subject'][0]) ) ? esc_attr($form_meta['user_notification_subject'][0]) : '';?>" class="regular-text" >
                <?php if(wpbs_translations_active()): ?><a href="#" class="wpbs-settings-field-show-translations"><?php echo __( 'Translations', 'wp-booking-system' ); ?> <i class="wpbs-icon-down-arrow"></i></a><?php endif ?>
            </div>
        </div>
        <?php if(wpbs_translations_active()): ?>
        <!-- Subject Translations -->
        <div class="wpbs-settings-field-translations">
            <?php foreach($active_languages as $language): ?>
                <!-- Submit Button -->
                <div class="wpbs-settings-field-wrapper wpbs-settings-field-inline wpbs-settings-field-large">

                    <label class="wpbs-settings-field-label" for="user_notification_subject_translation_<?php echo $language;?>"><img src="<?php echo WPBS_PLUGIN_DIR_URL ;?>/assets/img/flags/<?php echo $language;?>.png" /> <?php echo $languages[$language];?></label>

                    <div class="wpbs-settings-field-inner">
                        <input name="user_notification_subject_translation_<?php echo $language;?>" type="text" id="user_notification_subject_translation_<?php echo $language;?>" value="<?php echo ( !empty($form_meta['user_notification_subject_translation_' . $language][0]) ) ? esc_attr($form_meta['user_notification_subject_translation_' . $language][0]) : '';?>" class="regular-text" >
                    </div>
                    
                </div>
            <?php endforeach; ?>
        </div>
        <?php endif ?>
    </div>
    
    <!-- Message -->
    <div class="wpbs-settings-field-translation-wrapper">
        <div class="wpbs-settings-field-wrapper wpbs-settings-field-inline wpbs-settings-field-xlarge">
            <label class="wpbs-settings-field-label" for="user_notification_message"><?php echo __( 'Message', 'wp-booking-system' ); ?></label>

            <div class="wpbs-settings-field-inner">
                <?php wp_editor(( !empty($form_meta['user_notification_message'][0]) ) ? $form_meta['user_notification_message'][0] : '', 'user_notification_message', array('teeny' => true, 'textarea_rows' => 10, 'media_buttons' => false)) ?>
                <?php if(wpbs_translations_active()): ?><a href="#" class="wpbs-settings-field-show-translations"><?php echo __( 'Translations', 'wp-booking-system' ); ?> <i class="wpbs-icon-down-arrow"></i></a><?php endif; ?>
            </div>
        </div>   
        <?php if(wpbs_translations_active()): ?>
        <div class="wpbs-settings-field-translations">
            <?php foreach($active_languages as $language): ?>
                <div class="wpbs-settings-field-wrapper wpbs-settings-field-inline wpbs-settings-field-xlarge">
                    <!-- Submit Button -->
                    <div class="wpbs-settings-field-translation">

                        <label class="wpbs-settings-field-label" for="user_notification_message_translation_<?php echo $language;?>"><img src="<?php echo WPBS_PLUGIN_DIR_URL ;?>/assets/img/flags/<?php echo $language;?>.png" /> <?php echo $languages[$language];?></label>

                        <div class="wpbs-settings-field-inner">
                            <div><?php wp_editor(( !empty($form_meta['user_notification_message_translation_' . $language][0]) ) ? $form_meta['user_notification_message_translation_' . $language][0] : '', 'user_notification_message_translation_' . $language, array('teeny' => true, 'textarea_rows' => 10, 'media_buttons' => false)) ?></div>
                        </div>
                        
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>

    <!-- Enable iCalendar Attachment -->
    <div class="wpbs-settings-field-wrapper wpbs-settings-field-inline wpbs-settings-field-large">
        <label class="wpbs-settings-field-label" for="user_notification_ical_file">
            <?php echo __( 'iCalendar Attachment', 'wp-booking-system' ); ?>
            <?php echo wpbs_get_output_tooltip(__("Attach an iCalendar event file to the email.", 'wp-booking-system'));?>
        </label>

        <div class="wpbs-settings-field-inner">
            <label for="user_notification_ical_file" class="wpbs-checkbox-switch">
                <input type="hidden" name="user_notification_ical_file" value="0">
                <input data-target="#wpbs-user-notification-ical-file" name="user_notification_ical_file" type="checkbox" id="user_notification_ical_file"  class="regular-text wpbs-settings-toggle wpbs-settings-wrap-toggle" <?php echo ( !empty($form_meta['user_notification_ical_file'][0]) ) ? 'checked' : '';?> >
                <div class="wpbs-checkbox-slider"></div>
            </label>
        </div>
    </div>

    <div id="wpbs-user-notification-ical-file" class="wpbs-user-notification-ical-file wpbs-settings-wrapper <?php echo ( !empty($form_meta['user_notification_ical_file'][0]) ) ? 'wpbs-settings-wrapper-show' : '';?>">
    
        <!-- Summary -->
        <div class="wpbs-settings-field-translation-wrapper">
            
            <div class="wpbs-settings-field-wrapper wpbs-settings-field-inline wpbs-settings-field-large">
                <label class="wpbs-settings-field-label" for="user_notification_ical_summary">
                    <?php echo __( 'Summary', 'wp-booking-system' ); ?>
                    <?php echo wpbs_get_output_tooltip(__("The title of the event.", 'wp-booking-system'));?>
                </label>

                <div class="wpbs-settings-field-inner">
                    <input name="user_notification_ical_summary" type="text" id="user_notification_ical_summary" value="<?php echo ( !empty($form_meta['user_notification_ical_summary'][0]) ) ? esc_attr($form_meta['user_notification_ical_summary'][0]) : '';?>" class="regular-text" >
                    <?php if(wpbs_translations_active()): ?><a href="#" class="wpbs-settings-field-show-translations"><?php echo __( 'Translations', 'wp-booking-system' ); ?> <i class="wpbs-icon-down-arrow"></i></a><?php endif ?>
                </div>
            </div>
            <?php if(wpbs_translations_active()): ?>
            <!-- Subject Translations -->
            <div class="wpbs-settings-field-translations">
                <?php foreach($active_languages as $language): ?>
                    <!-- Submit Button -->
                    <div class="wpbs-settings-field-wrapper wpbs-settings-field-inline wpbs-settings-field-large">

                        <label class="wpbs-settings-field-label" for="user_notification_ical_summary_translation_<?php echo $language;?>"><img src="<?php echo WPBS_PLUGIN_DIR_URL ;?>/assets/img/flags/<?php echo $language;?>.png" /> <?php echo $languages[$language];?></label>

                        <div class="wpbs-settings-field-inner">
                            <input name="user_notification_ical_summary_translation_<?php echo $language;?>" type="text" id="user_notification_ical_summary_translation_<?php echo $language;?>" value="<?php echo ( !empty($form_meta['user_notification_ical_summary_translation_' . $language][0]) ) ? esc_attr($form_meta['user_notification_ical_summary_translation_' . $language][0]) : '';?>" class="regular-text" >
                        </div>
                        
                    </div>
                <?php endforeach; ?>
            </div>
            <?php endif ?>
        </div>

        <!-- Description -->
        <div class="wpbs-settings-field-translation-wrapper">
            
            <div class="wpbs-settings-field-wrapper wpbs-settings-field-inline wpbs-settings-field-large">
                <label class="wpbs-settings-field-label" for="user_notification_ical_description">
                    <?php echo __( 'Description', 'wp-booking-system' ); ?>
                    <?php echo wpbs_get_output_tooltip(__("The event description.", 'wp-booking-system'));?>
                </label>

                <div class="wpbs-settings-field-inner">
                    <input name="user_notification_ical_description" type="text" id="user_notification_ical_description" value="<?php echo ( !empty($form_meta['user_notification_ical_description'][0]) ) ? esc_attr($form_meta['user_notification_ical_description'][0]) : '';?>" class="regular-text" >
                    <?php if(wpbs_translations_active()): ?><a href="#" class="wpbs-settings-field-show-translations"><?php echo __( 'Translations', 'wp-booking-system' ); ?> <i class="wpbs-icon-down-arrow"></i></a><?php endif ?>
                </div>
            </div>
            <?php if(wpbs_translations_active()): ?>
            <!-- Subject Translations -->
            <div class="wpbs-settings-field-translations">
                <?php foreach($active_languages as $language): ?>
                    <!-- Submit Button -->
                    <div class="wpbs-settings-field-wrapper wpbs-settings-field-inline wpbs-settings-field-large">

                        <label class="wpbs-settings-field-label" for="user_notification_ical_description_translation_<?php echo $language;?>"><img src="<?php echo WPBS_PLUGIN_DIR_URL ;?>/assets/img/flags/<?php echo $language;?>.png" /> <?php echo $languages[$language];?></label>

                        <div class="wpbs-settings-field-inner">
                            <input name="user_notification_ical_description_translation_<?php echo $language;?>" type="text" id="user_notification_ical_description_translation_<?php echo $language;?>" value="<?php echo ( !empty($form_meta['user_notification_ical_description_translation_' . $language][0]) ) ? esc_attr($form_meta['user_notification_ical_description_translation_' . $language][0]) : '';?>" class="regular-text" >
                        </div>
                        
                    </div>
                <?php endforeach; ?>
            </div>
            <?php endif ?>
        </div>

    </div>

</div>

<?php endif ?>