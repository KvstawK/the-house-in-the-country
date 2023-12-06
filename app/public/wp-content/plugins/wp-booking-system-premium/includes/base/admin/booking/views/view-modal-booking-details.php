 <div class="wpbs-booking-details-modal-booking-details">

    <div class="wpbs-booking-details-modal-column wpbs-booking-details-modal-column-left">

        <h3><?php echo __('Booking Data', 'wp-booking-system') ?></h3>

        <form action="#" method="post" class="wpbs-edit-booking-details"  data-type="booking_data">
            <table>
                <?php foreach($this->get_booking_data() as $data): ?>
                    <tr>
                        <td><strong><?php echo $data['label'] ?>:</strong></td>
                        <td class="wpbs-edit-booking-field-<?php echo $data['name'];?> <?php echo ($data['editable']) ? 'wpbs-edit-booking-details-field-editable' : ''; ?>">
                            <span class="wpbs-edit-booking-details-field-view">
                                <p><?php echo $data['value'] ?></p>
                            </span>

                            <?php if($data['editable']): ?>

                                <span class="wpbs-edit-booking-details-field-edit">
                                    <input name="wpbs-edit-booking-field-<?php echo $data['name'];?>" class="wpbs-edit-booking-datepicker" type="text" value="<?php echo wpbs_date_i18n('Y-m-d', $data['time']) ?>">
                                </span>

                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach ?>
            </table>

            <?php wp_nonce_field( 'wpbs_edit_booking', 'wpbs_edit_booking_data_token', false ); ?>
            <input type="hidden" name="booking_id" value="<?php echo $this->booking->get('id');?>">

            <button class="edit-booking-details-open button button-secondary"><?php echo __('Edit', 'wp-booking-system') ?></button>

            <div class="wpbs-page-notice notice-info wpbs-form-changed-notice">
                <p><?php echo __('Please keep in mind that changing the dates will <strong>not</strong> change the availability in the calendar, nor the price of the booking. You will need to adjust these manually.', 'wp-booking-system'); ?></p>
            </div>
            
            <button class="edit-booking-details-save button button-primary"><?php echo __('Save Changes', 'wp-booking-system') ?></button>
            <button class="edit-booking-details-cancel button button-secondary"><?php echo __('Cancel', 'wp-booking-system') ?></button>

        </form>

    </div>

    <div class="wpbs-booking-details-modal-column wpbs-booking-details-modal-column-right">
        
        <h3><?php echo __('Form Data', 'wp-booking-system') ?></h3>

        <form action="#" method="post" class="wpbs-edit-booking-details" data-type="booking_details">
            <table>
                <?php foreach($this->get_form_data() as $data): ?>
                    <tr>
                        <td><strong><?php echo $data['label'] ?>:</strong></td>
                        <td class="<?php echo ($data['editable']) ? 'wpbs-edit-booking-details-field-editable' : ''; ?>">
                            <span class="wpbs-edit-booking-details-field-view">
                                <p><?php echo $data['value'] ?></p>
                            </span>

                            <?php if($data['editable']): ?>

                                <span class="wpbs-edit-booking-details-field-edit">

                                    <?php if($data['field']['type'] == 'textarea'): ?>
                                        <textarea name="wpbs-edit-booking-field-<?php echo $data['field']['id'];?>" class="wpbs-edit-booking-field-<?php echo $data['field']['id'];?>"><?php echo strip_tags($data['value']); ?></textarea>
                                    <?php else: ?>
                                        <input name="wpbs-edit-booking-field-<?php echo $data['field']['id'];?>" class="wpbs-edit-booking-field-<?php echo $data['field']['id'];?>" type="text" value="<?php echo $data['value'] ?>">
                                    <?php endif; ?>

                                </span>

                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach ?>
            </table>

            <?php wp_nonce_field( 'wpbs_edit_booking', 'wpbs_edit_booking_details_token', false ); ?>
            <input type="hidden" name="booking_id" value="<?php echo $this->booking->get('id');?>">

            <button class="edit-booking-details-open button button-secondary"><?php echo __('Edit', 'wp-booking-system') ?></button>
            
            <button class="edit-booking-details-save button button-primary"><?php echo __('Save Changes', 'wp-booking-system') ?></button>
            <button class="edit-booking-details-cancel button button-secondary"><?php echo __('Cancel', 'wp-booking-system') ?></button>

        
        </form>

    </div>

</div>