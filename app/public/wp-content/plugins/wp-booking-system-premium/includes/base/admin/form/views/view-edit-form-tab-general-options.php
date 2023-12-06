<!-- Email Tags -->
<div class="card wpbs-email-tags-wrapper">
    <h2 class="title"><?php echo __( 'Dynamic Tags', 'wp-booking-system' ); ?></h2>
    <p><?php echo __( 'You can use these dynamic tags in the Event Description and Event Tooltip fields. They will be replaced with the values submitted in the form.', 'wp-booking-system' ); ?></p>
    
    <?php wpbs_output_email_tags($form_data); ?>
</div>

<div class="wpbs-settings-field-wrapper wpbs-settings-field-inline wpbs-settings-field-heading wpbs-settings-field-large">
    <label class="wpbs-settings-field-label"><?php echo __( 'General Options', 'wp-booking-system' ); ?> </label>
    <div class="wpbs-settings-field-inner">&nbsp;</div>
</div>

<!-- Submit Button -->
<div class="wpbs-settings-field-translation-wrapper">
    <div class="wpbs-settings-field-wrapper wpbs-settings-field-inline wpbs-settings-field-large">
        <label class="wpbs-settings-field-label" for="submit_button_label"><?php echo __( 'Submit Button Label', 'wp-booking-system' ); ?></label>

        <div class="wpbs-settings-field-inner">
            <input name="submit_button_label" type="text" id="submit_button_label" value="<?php echo ( !empty($form_meta['submit_button_label'][0]) ) ? esc_attr($form_meta['submit_button_label'][0]) : '';?>" class="regular-text" >
            <?php if(wpbs_translations_active()): ?><a href="#" class="wpbs-settings-field-show-translations"><?php echo __( 'Translations', 'wp-booking-system' ); ?> <i class="wpbs-icon-down-arrow"></i></a><?php endif; ?>

        </div>
    </div>
    <?php if(wpbs_translations_active()): ?>
    <div class="wpbs-settings-field-translations">
        <?php foreach($active_languages as $language): ?>
            <!-- Submit Button -->
            <div class="wpbs-settings-field-wrapper wpbs-settings-field-inline wpbs-settings-field-large">

                <label class="wpbs-settings-field-label" for="submit_button_label_translation_<?php echo $language;?>"><img src="<?php echo WPBS_PLUGIN_DIR_URL ;?>/assets/img/flags/<?php echo $language;?>.png" /> <?php echo $languages[$language];?></label>

                <div class="wpbs-settings-field-inner">
                    <input name="submit_button_label_translation_<?php echo $language;?>" type="text" id="submit_button_label_translation_<?php echo $language;?>" value="<?php echo ( !empty($form_meta['submit_button_label_translation_' . $language][0]) ) ? esc_attr($form_meta['submit_button_label_translation_' . $language][0]) : '';?>" class="regular-text" >
                </div>
                
            </div>
        <?php endforeach; ?>
    </div>
    <?php endif ?>
</div>


<!-- Tracking Script -->
<div class="wpbs-settings-field-wrapper wpbs-settings-field-inline wpbs-settings-field-large">

	<label class="wpbs-settings-field-label" for="tracking_script"><?php echo __( 'Tracking Script', 'wp-booking-system' ); ?>  
        <?php echo wpbs_get_output_tooltip(__("Tracking script code (eg. Google Analytics) that's executed after the form was successfully submitted.", 'wp-booking-system'));?>
    </label>

	<div class="wpbs-settings-field-inner">
        <textarea name="tracking_script" id="tracking_script" rows="3" class="regular-text" ><?php echo ( !empty($form_meta['tracking_script'][0]) ) ? esc_textarea($form_meta['tracking_script'][0]) : '';?></textarea>
        <small><?php echo __( 'Do not include the &lt;script&gt; tags.', 'wp-booking-system' ); ?></small>
	</div>
	
</div>

<!-- Auto-Complete Event Description -->
<div class="wpbs-settings-field-wrapper wpbs-settings-field-inline wpbs-settings-field-large">
    <label class="wpbs-settings-field-label" for="autofill_event_description">
        <?php echo __( 'Event Description', 'wp-booking-system' ); ?>
        <?php echo wpbs_get_output_tooltip(__("Automatically fill in the event description field when a booking is made. You can use Email Tags to populate with dynamic form values, eg. '{1:Your Name}'. This only works if the Auto Accept option is enabled.", 'wp-booking-system'));?>
    </label>

    <div class="wpbs-settings-field-inner">
        <input name="autofill_event_description" type="text" id="autofill_event_description" value="<?php echo ( !empty($form_meta['autofill_event_description'][0]) ) ? esc_attr($form_meta['autofill_event_description'][0]) : '';?>" class="regular-text" >

    </div>
</div>

<!-- Auto-Complete Event Tooltip -->
<div class="wpbs-settings-field-wrapper wpbs-settings-field-inline wpbs-settings-field-large">
    <label class="wpbs-settings-field-label" for="autofill_event_tooltip">
        <?php echo __( 'Event Tooltip', 'wp-booking-system' ); ?>
        <?php echo wpbs_get_output_tooltip(__("Automatically fill in the event tooltip field when a booking is made. You can use Email Tags to populate with dynamic form values, eg. '{1:Your Name}'. This only works if the Auto Accept option is enabled.", 'wp-booking-system'));?>
    </label>

    <div class="wpbs-settings-field-inner">
        <input name="autofill_event_tooltip" type="text" id="autofill_event_tooltip" value="<?php echo ( !empty($form_meta['autofill_event_tooltip'][0]) ) ? esc_attr($form_meta['autofill_event_tooltip'][0]) : '';?>" class="regular-text" >

    </div>
</div>

<!-- Confirmation Type -->
<?php $default_status = ( !empty($form_meta['form_default_booking_status'][0]) ) ? esc_attr($form_meta['form_default_booking_status'][0]) : 'pending'; ?>
<div class="wpbs-settings-field-wrapper wpbs-settings-field-inline wpbs-settings-field-large">
    <label class="wpbs-settings-field-label" for="form_default_booking_status"><?php echo __( 'Default Booking Status', 'wp-booking-system' ); ?></label>

    <div class="wpbs-settings-field-inner">
        <select name="form_default_booking_status" id="form_default_booking_status">
            <option <?php echo ($default_status == 'pending') ? 'selected' : '';?> value="pending"><?php echo __( 'Pending', 'wp-booking-system' ); ?></option>
            <option <?php echo ($default_status == 'accepted') ? 'selected' : '';?> value="accepted"><?php echo __( 'Accepted', 'wp-booking-system' ); ?></option>
        </select>
    </div>
</div>

<?php

	/**
	 * Hook to add extra fields at the bottom of the General Tab
	 *
	 * @param array $settings
	 *
	 */
	do_action( 'wpbs_submenu_page_edit_form_tab_general_bottom', $settings );

?>