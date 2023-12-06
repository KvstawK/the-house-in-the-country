<?php 
$booking_options = wpbs_get_calendar_meta($calendar_id, 'manual_booking_options', true);
$settings_configured = wpbs_is_add_booking_settings_configured($calendar_id);
$settings = get_option('wpbs_settings', array());
?>

<div id="wpbs-add-booking-modal-overlay">
    <div id="wpbs-add-booking-modal-inner">
        <a href="#" id="wpbs-add-booking-modal-close"><i class="wpbs-icon-close"></i></a>
        <h1>
            <svg aria-hidden="true"  xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" ><path fill="currentColor" d="M336 292v24c0 6.6-5.4 12-12 12h-76v76c0 6.6-5.4 12-12 12h-24c-6.6 0-12-5.4-12-12v-76h-76c-6.6 0-12-5.4-12-12v-24c0-6.6 5.4-12 12-12h76v-76c0-6.6 5.4-12 12-12h24c6.6 0 12 5.4 12 12v76h76c6.6 0 12 5.4 12 12zm112-180v352c0 26.5-21.5 48-48 48H48c-26.5 0-48-21.5-48-48V112c0-26.5 21.5-48 48-48h48V12c0-6.6 5.4-12 12-12h40c6.6 0 12 5.4 12 12v52h128V12c0-6.6 5.4-12 12-12h40c6.6 0 12 5.4 12 12v52h48c26.5 0 48 21.5 48 48zm-48 346V160H48v298c0 3.3 2.7 6 6 6h340c3.3 0 6-2.7 6-6z"></path></svg> 
            <?php echo __('Add Booking', 'wp-booking-system') ?>
            <button id="wpbs-add-booking-edit-options" class="button-secondary <?php echo !$settings_configured ? 'wpbs-hidden' : '';?>"><?php echo __('Edit Calendar Options', 'wp-booking-system') ?></button>
        </h1>

        <div class="wpbs-add-booking-options <?php echo $settings_configured ? 'wpbs-hidden' : '';?>">

            <h2><?php echo __('Calendar Options', 'wp-booking-system') ?></h2>

            <form class="wpbs-add-booking-options-form">

                <input type="hidden" name="calendar_id" value="<?php echo $calendar_id;?>">

                <div class="wpbs-settings-field-wrapper wpbs-settings-field-inline wpbs-settings-field-medium">
                    <label class="wpbs-settings-field-label" for="wpbs-add-booking-option-form">
                        <?php echo __('Form', 'wp-booking-system') ?>
                        <?php echo wpbs_get_output_tooltip(__('Select the Form you want to use to create the booking. Should be the same one as you use on the front-end.', 'wp-booking-system')) ?>
                    </label>

                    <div class="wpbs-settings-field-inner">
                        <select name="form_id" id="wpbs-add-booking-option-form">
                            <option value="">-</option>
                            <?php foreach(wpbs_get_forms(array('status' => 'active')) as $form): ?>
                                <option <?php echo isset($booking_options['form_id']) && $booking_options['form_id'] == $form->get('id') ? 'selected' : '';?> value="<?php echo $form->get('id');?>"><?php echo $form->get('name');?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="wpbs-settings-field-wrapper wpbs-settings-field-inline wpbs-settings-field-medium">
                    <label class="wpbs-settings-field-label" for="wpbs-add-booking-option-language">
                        <?php echo __('Language', 'wp-booking-system') ?>
                    </label>

                    <div class="wpbs-settings-field-inner">
                        <select name="language" id="wpbs-add-booking-option-language">
                            <option value="auto"><?php echo __( 'Default', 'wp-booking-system' ); ?></option>
                            <?php
                                $languages = wpbs_get_languages();
                                $active_languages = ( ! empty( $settings['active_languages'] ) ? $settings['active_languages'] : array() );

                                foreach( $active_languages as $code ) {
                                    echo '<option '.(isset($booking_options['language']) && $booking_options['language'] == $code ? 'selected' : '').' value="' . esc_attr( $code ) . '">' . ( ! empty( $languages[$code] ) ? $languages[$code] : '' ) . '</option>';
                                }
                            ?>
                        </select>
                    </div>
                </div>

                <div class="wpbs-settings-field-wrapper wpbs-settings-field-inline wpbs-settings-field-small">
                    <label class="wpbs-settings-field-label" for="wpbs-add-booking-option-selection_style">
                        <?php echo __('Calendar Selection Style', 'wp-booking-system') ?>
                        <?php echo wpbs_get_output_tooltip(__('Select the what Selection Style you want to use when create the booking. Should be the same as you use on the front-end.', 'wp-booking-system')) ?>
                    </label>

                    <div class="wpbs-settings-field-inner">
                        <select name="selection_style" id="wpbs-add-booking-option-selection_style">
                            <option value="">-</option>
                            <option <?php echo isset($booking_options['selection_style']) && $booking_options['selection_style'] == 'normal' ? 'selected' : '';?> value="normal"><?php echo __('Normal', 'wp-booking-system') ?></option>
                            <option <?php echo isset($booking_options['selection_style']) && $booking_options['selection_style'] == 'split' ? 'selected' : '';?> value="split"><?php echo __('Split Days', 'wp-booking-system') ?></option>
                        </select>
                    </div>
                </div>

                <div class="wpbs-settings-field-wrapper wpbs-settings-field-inline wpbs-settings-field-small">
                    <label class="wpbs-settings-field-label" for="wpbs-add-booking-option-auto_accept">
                        <?php echo __('Auto Accept Booking', 'wp-booking-system') ?>
                        <?php echo wpbs_get_output_tooltip(__('Select if you want to use the Auto Accept opton when creating the booking. If enabled, the dates will automatically become blocked in the calendar.', 'wp-booking-system')) ?>
                    </label>

                    <div class="wpbs-settings-field-inner">
                        <select name="auto_pending" id="wpbs-add-booking-option-auto_accept">
                            <option value="">-</option>
                            <option <?php echo isset($booking_options['auto_pending']) && $booking_options['auto_pending'] == 'yes' ? 'selected' : '';?> value="yes"><?php echo __('Yes', 'wp-booking-system') ?></option>
                            <option <?php echo isset($booking_options['auto_pending']) && $booking_options['auto_pending'] == 'no' ? 'selected' : '';?> value="no"><?php echo __('No', 'wp-booking-system') ?></option>
                        </select>
                    </div>
                </div>

                <div class="wpbs-settings-field-wrapper wpbs-settings-field-inline wpbs-settings-field-large">
                    <label class="wpbs-settings-field-label" for="wpbs-add-booking-option-send_emails">
                        <?php echo __( 'Send Emails', 'wp-booking-system' ); ?>
                        <?php echo wpbs_get_output_tooltip(__('Select whether or not to send the Admin and User notification emails when creating the booking. Regardless of this option, if email reminders are enabled, they will be scheduled as configured.', 'wp-booking-system')) ?>

                    </label>

                    <div class="wpbs-settings-field-inner">
                        <label for="wpbs-add-booking-option-send_emails" class="wpbs-checkbox-switch">
                            <input name="send_emails" type="checkbox" id="wpbs-add-booking-option-send_emails" class="regular-text wpbs-settings-toggle" <?php echo (!empty($booking_options['send_emails'])) ? 'checked' : ''; ?>>
                            <div class="wpbs-checkbox-slider"></div>
                        </label>
                    </div>
                </div>

                <div class="wpbs-settings-field-wrapper wpbs-settings-field-inline wpbs-settings-field-large">
                    <label class="wpbs-settings-field-label" for="wpbs-add-booking-option-ignore_validation">
                        <?php echo __( 'Ignore Validation Rules', 'wp-booking-system' ); ?>
                        <?php echo wpbs_get_output_tooltip(__('Allows you to create bookings on any dates, even if you have validation rules set up.', 'wp-booking-system')) ?>
                    </label>

                    <div class="wpbs-settings-field-inner">
                        <label for="wpbs-add-booking-option-ignore_validation" class="wpbs-checkbox-switch">
                            <input name="ignore_validation" type="checkbox" id="wpbs-add-booking-option-ignore_validation" class="regular-text wpbs-settings-toggle" <?php echo (!empty($booking_options['ignore_validation'])) ? 'checked' : ''; ?>>
                            <div class="wpbs-checkbox-slider"></div>
                        </label>
                    </div>
                </div>

                <div class="wpbs-page-notice notice-info wpbs-form-changed-notice">
                    <p><?php echo __('When creating a manual booking, the Payment on Arrival and Bank Transfer payment methods are always enabled by default. ', 'wp-booking-system') ?></p>
                </div>

                <div class="wpbs-page-notice notice-info wpbs-form-changed-notice">
                    <p><?php echo __('Using an online payment gateway will create a payment link that you can send to the customer.', 'wp-booking-system') ?></p>
                </div>

                <?php if(!isset($settings['payment_part_payments_page']) || empty($settings['payment_part_payments_page'])): ?>
                    <div class="wpbs-page-notice notice-error wpbs-form-changed-notice">
                        <p><?php echo sprintf(__("To enable online payments, please set up a secondary payment page in the %splugin's settings page%s.", 'wp-booking-system'), '<a href="'.add_query_arg(array('page' => 'wpbs-settings', 'tab' => 'payment'), admin_url('admin.php')) .'">', '</a>') ?></p>
                    </div>
                <?php endif; ?>

                <button class="button-primary" id="wpbs-add-booking-options-save"><?php echo __('Save Changes', 'wp-booking-system') ?></button>
                
            </form>

            <hr>
            
        </div>

        <div id="wpbs-add-booking"></div>
        
    </div>
</div>