<?php $settings = get_option('wpbs_settings'); ?>

<div class="wpbs-booking-details-main">

    <h3 class="wpbs-no-margin"><?php echo __('Edit the calendar availability', 'wp-booking-system'); ?></h3>

    <!-- Calendar Editor -->
    <div class="wpbs-booking-details-modal-column wpbs-booking-details-modal-column-left">
        <?php echo $this->calendar_editor(); ?>
    </div>

    <!-- Bulk Date Editor -->
    <div class="wpbs-booking-details-modal-column wpbs-booking-details-modal-column-right">
        <h3><?php echo __('Bulk Edit Dates', 'wp-booking-system'); ?></h3>
        <div id="wpbs-bulk-edit-availability-booking-wrap">
            <p>
                <label for="wpbs-bulk-edit-availability-booking-legend-item"><?php echo __('Legend Item', 'wp-booking-system'); ?></label>
                <select id="wpbs-bulk-edit-availability-booking-legend-item">
                    <?php echo $this->get_legends_as_options(); ?>
                </select>
            </p>

            <?php if(function_exists('wp_booking_system_inventory')): ?>
                <p>
                    <label for="wpbs-bulk-edit-availability-booking-inventory"><?php echo __('Inventory', 'wp-booking-system'); ?></label>
                    <input id="wpbs-bulk-edit-availability-booking-inventory" type="number" />
                </p>
            <?php endif; ?>

            <p>
                <label for="wpbs-bulk-edit-availability-booking-description"><?php echo __('Description', 'wp-booking-system'); ?></label>
                <input id="wpbs-bulk-edit-availability-booking-description" type="text" />
            </p>
            <p>
                <label for="wpbs-bulk-edit-availability-booking-tooltip"><?php echo __('Tooltip', 'wp-booking-system'); ?></label>
                <input id="wpbs-bulk-edit-availability-booking-tooltip" type="text" />
            </p>


            <a id="wpbs-bulk-edit-availability-booking" class="button-secondary" href="#"><?php echo __('Apply Changes', 'wp-booking-system'); ?></a>
        </div>
    </div>

    <div class="wpbs-clear"><!-- --></div>

    <!-- Email Customer -->

    <?php if ($this->get_email_addresses() !== false): ?>



    <div class="wpbs-booking-details-modal-column wpbs-booking-details-modal-column-left">

        <div class="wpbs-booking-details-modal-accept-booking-email">

            <form>

                <h3>
                    <label for="booking_email_accept_booking_enable" class="wpbs-checkbox-switch">
                        <input data-target="#wpbs-booking-details-modal-email" name="booking_email_accept_booking_enable" type="checkbox" id="booking_email_accept_booking_enable" value="on" class="regular-text wpbs-settings-toggle wpbs-settings-wrap-toggle" >
                        <div class="wpbs-checkbox-slider"></div>
                    </label>
                    <label for="booking_email_accept_booking_enable"><?php echo $this->get_email_customer_heading(); ?></label>
                </h3>

                <div id="wpbs-booking-details-modal-email" class="wpbs-booking-details-modal-email-wrapper wpbs-settings-wrapper">

                    <!-- Send To -->
                    <div class="wpbs-settings-field-translation-wrapper">
                        <div class="wpbs-settings-field-wrapper wpbs-settings-field-inline">
                            <label class="wpbs-settings-field-label" for="booking_email_accept_booking_send_to"><?php echo __('Send To', 'wp-booking-system'); ?></label>
                            <div class="wpbs-settings-field-inner">
                                <select name="booking_email_accept_booking_send_to" id="booking_email_accept_booking_send_to">
                                    <?php echo $this->get_email_addresses_as_options(); ?>
                                </select>
                                <a href="#" class="wpbs-settings-field-show-translations"><?php echo __( 'Options', 'wp-booking-system' ); ?> <i class="wpbs-icon-down-arrow"></i></a>
                            </div>
                        </div>

                        <div class="wpbs-settings-field-translations">
                            <!-- CC -->
                            <div class="wpbs-settings-field-wrapper wpbs-settings-field-inline wpbs-settings-field-large">
                                <label class="wpbs-settings-field-label" for="booking_email_accept_booking_send_to_cc"><?php echo __( 'CC', 'wp-booking-system' ); ?></label>
                                <div class="wpbs-settings-field-inner">
                                    <input name="booking_email_accept_booking_send_to_cc" type="text" id="booking_email_accept_booking_send_to_cc" class="regular-text" value="<?php echo (isset($this->plugin_settings['default_cc'])) ? $this->plugin_settings['default_cc'] : '' ;?>">
                                </div>
                            </div>

                            <!-- CC -->
                            <div class="wpbs-settings-field-wrapper wpbs-settings-field-inline wpbs-settings-field-large">
                                <label class="wpbs-settings-field-label" for="booking_email_accept_booking_send_to_bcc"><?php echo __( 'BCC', 'wp-booking-system' ); ?></label>
                                <div class="wpbs-settings-field-inner">
                                    <input name="booking_email_accept_booking_send_to_bcc" type="text" id="booking_email_accept_booking_send_to_bcc" class="regular-text" value="<?php echo (isset($this->plugin_settings['default_bcc'])) ? $this->plugin_settings['default_bcc'] : '' ;?>">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- From Name -->
                    <div class="wpbs-settings-field-wrapper wpbs-settings-field-inline">
                        <label class="wpbs-settings-field-label" for="booking_email_accept_booking_from_name"><?php echo __('From Name', 'wp-booking-system'); ?></label>

                        <div class="wpbs-settings-field-inner">
                            <input name="booking_email_accept_booking_from_name" type="text" id="booking_email_accept_booking_from_name" class="regular-text" value="<?php echo (isset($this->plugin_settings['default_from_name'])) ? $this->plugin_settings['default_from_name'] : ''; ?>" >
                        </div>
                    </div>

                    <!-- From Email -->
                    <div class="wpbs-settings-field-wrapper wpbs-settings-field-inline">
                        <label class="wpbs-settings-field-label" for="booking_email_accept_booking_from_email"><?php echo __('From Email', 'wp-booking-system'); ?></label>

                        <div class="wpbs-settings-field-inner">
                            <input name="booking_email_accept_booking_from_email" type="text" id="booking_email_accept_booking_from_email" class="regular-text" value="<?php echo (isset($this->plugin_settings['default_from_email'])) ? $this->plugin_settings['default_from_email'] : ''; ?>">
                        </div>
                    </div>

                    <!-- Reply To -->
                    <div class="wpbs-settings-field-wrapper wpbs-settings-field-inline">
                        <label class="wpbs-settings-field-label" for="booking_email_accept_booking_reply_to"><?php echo __('Reply To', 'wp-booking-system'); ?></label>

                        <div class="wpbs-settings-field-inner">
                            <input name="booking_email_accept_booking_reply_to" type="text" id="booking_email_accept_booking_reply_to" class="regular-text" value="<?php echo (isset($this->plugin_settings['default_reply_to'])) ? $this->plugin_settings['default_reply_to'] : ''; ?>">
                        </div>
                    </div>

                    <div class="wpbs-settings-field-wrapper wpbs-settings-field-inline ">
                        <label class="wpbs-settings-field-label" for="booking_email_accept_load_template"><?php echo __('Load Template', 'wp-booking-system');?></label>
                        <div class="wpbs-settings-field-inner">
                            <?php if(isset($settings['form_email_template_name']) && $settings['form_email_template_name']): ?>
                            <select name="" class="wpbs-load-tinymce-content" data-auto-load="<?php echo (isset($settings['automatically_load_template']))? $settings['automatically_load_template'] : '0'; ?>" id="booking_email_accept_load_template" data-tinymce="booking_email_accept_booking_message" data-subject="booking_email_accept_booking_subject">
                                <option disabled selected>-</option>
                                <?php foreach($settings['form_email_template_name'] as $i => $template_name): ?>
                                    <option value="<?php echo ($i+1);?>" data-text="<?php echo esc_attr(wpautop($settings['form_email_template_body'][$i]));?>"><?php echo $template_name ?></option>
                                <?php endforeach; ?>
                            </select>
                            <?php endif; ?>
                            <small><?php echo __('You can set up message templates in', 'wp-booking-system') ?> <a target="_blank" href="<?php echo add_query_arg( array('page' => 'wpbs-settings', 'tab' => 'email'),  admin_url( 'admin.php' ) ); ?>"><?php echo __('WP Booking System -> Settings -> Email', 'wp-booking-system') ?></a></small>
                        </div>
                    </div>

                    <div class="wpbs-settings-field-wrapper wpbs-settings-field-inline">
                        <label class="wpbs-settings-field-label" for="booking_email_accept_booking_subject"><?php echo __('Subject', 'wp-booking-system'); ?></label>
                        <div class="wpbs-settings-field-inner">
                            <input name="booking_email_accept_booking_subject" type="text" id="booking_email_accept_booking_subject" value="" class="regular-text">
                        </div>
                    </div>

                    <div class="wpbs-settings-field-wrapper wpbs-settings-field-inline">
                        <label class="wpbs-settings-field-label" for="booking_email_accept_booking_message"><?php echo __('Message', 'wp-booking-system'); ?></label>
                        <div class="wpbs-settings-field-inner">
                            <div class="wpbs-wp-editor-ajax" data-id="booking_email_accept_booking_message">
                                <?php wp_editor('', 'booking_email_accept_booking_message', array('teeny' => true, 'textarea_rows' => 10, 'media_buttons' => false));?>
                            </div>
                        </div>
                    </div>

                    <div class="wpbs-settings-field-wrapper wpbs-settings-field-inline">
                        <label class="wpbs-settings-field-label" for="booking_email_accept_booking_include_booking_details"><?php echo __('Include Booking Details', 'wp-booking-system'); ?></label>
                        <div class="wpbs-settings-field-inner">
                            <label for="booking_email_accept_booking_include_booking_details" class="wpbs-checkbox-switch">
                                <input <?php echo (get_user_meta(get_current_user_id(), 'wpbs_remember_include_booking_details', true)) ? 'checked' : '';?> name="booking_email_accept_booking_include_booking_details" type="checkbox" id="booking_email_accept_booking_include_booking_details"  class="regular-text wpbs-settings-toggle">
                                <div class="wpbs-checkbox-slider"></div>
                            </label>
                        </div>
                    </div>

                    <?php do_action('wpbs_booking_modal_email_accept_booking_after', $this->booking) ?>

                </div>

            </form>

        </div>

    </div>

    <div class="wpbs-booking-details-modal-column wpbs-booking-details-modal-column-right"><!-- --></div>

    <div class="wpbs-clear"><!-- --></div>

    <?php endif;?>

    <div class="wpbs-booking-details-modal-footer-actions">

        <hr>

        <?php if ($this->booking->get('status') == 'trash'): ?>

            <button class="button button-primary wpbs-action-update-booking" data-action="restore" data-booking-id="<?php echo $this->booking->get('id'); ?>">
                <?php echo $this->get_manage_booking_button_label(); ?>
            </button>

            <a href="<?php echo wp_nonce_url(add_query_arg(array('page' => 'wpbs-calendars', 'wpbs_action' => 'permanently_delete_booking', 'booking_id' => $this->booking->get('id'), 'calendar_id' => $this->calendar->get('id')), admin_url('admin.php')), 'wpbs_permanently_delete_booking', 'wpbs_token'); ?>" class="button button-secondary wpbs-permanently-delete-booking"><?php echo __('Permanently delete booking', 'wp-booking-system') ?></a>
        <?php else: ?>

            <button class="button button-primary wpbs-action-update-booking" data-action="accept" data-booking-id="<?php echo $this->booking->get('id'); ?>">
                <?php echo $this->get_manage_booking_button_label(); ?>
            </button>

            <button class="button button-secondary wpbs-action-update-booking wpbs-delete-booking" data-action="delete" data-booking-id="<?php echo $this->booking->get('id'); ?>">
                <?php echo __('Delete Booking', 'wp-booking-system'); ?>
            </button>

            <?php echo wpbs_get_output_tooltip(__('Accepting, Updating or Deleting the booking will also update the calendar availability as per the form above.', 'wp-booking-system')) ?>

            <button class="button button-secondary wpbs-move-booking" >
                <?php echo __('Move Booking', 'wp-booking-system'); ?>
            </button>

        <?php endif;?>

    </div>

</div>

<div class="wpbs-booking-details-modal-move-booking">
    <h3><?php echo __('Move Booking', 'wp-booking-system'); ?></h3>
    
    <form method="post">

        <?php $calendars = wpbs_get_calendars(['status' => 'active']) ?>

        <div class="wpbs-settings-field-translation-wrapper">
            <div class="wpbs-settings-field-wrapper wpbs-settings-field-inline wpbs-settings-field-xlarge">
                <label class="wpbs-settings-field-label" for="new_calendar_id"><?php _e('New Calendar', 'wp-booking-system') ?></label>
                <div class="wpbs-settings-field-inner">
                    <select name="new_calendar_id" id="new_calendar_id">
                        <option value="">-</option>         
                        <?php foreach($calendars as $calendar): if($calendar->get('id') == $this->calendar->get('id')) {continue;} ?>           
                            <option value="<?php echo $calendar->get('id');?>"><?php echo $calendar->get('name');?> (ID: #<?php echo $calendar->get('id');?>)</option>     
                        <?php endforeach; ?>               
                    </select>
                </div>
            </div>
        </div>

        <div class="wpbs-page-notice notice-error wpbs-form-changed-notice">
            <p><?php _e('<strong>WARNING!</strong> Moving a booking will not update the availability in neither the old or the new calendar, nor does it check if the dates are available or not in the new calendar. You will have to update the availability in both calendars manually. Please use with caution.', 'wp-booking-system') ?></p>
        </div>

        <div class="wpbs-booking-details-modal-footer-actions">

            <hr>

            <button type="submit" class="button button-primary wpbs-action-move-booking-accept">
                <?php echo __('Move Booking', 'wp-booking-system'); ?>
            </button>

            <button class="button button-secondary wpbs-action-move-booking-cancel" >
                <?php echo __('Cancel', 'wp-booking-system'); ?>
            </button>

        </div>

        <!-- Action and nonce -->
        
        <input type="hidden" name="booking_id" value="<?php echo $this->booking->get('id'); ?>" />
        <input type="hidden" name="wpbs_action" value="move_booking" />
        <?php wp_nonce_field( 'wpbs_move_booking', 'wpbs_token', false ); ?>

    </form>


</div>
