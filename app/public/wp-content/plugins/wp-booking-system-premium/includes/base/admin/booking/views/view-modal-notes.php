 <div class="wpbs-booking-details-modal-booking-details">

    <div class="wpbs-booking-details-modal-column wpbs-booking-details-modal-notes">
        
        <h3><?php echo __('Notes', 'wp-booking-system') ?> <?php echo wpbs_get_output_tooltip(__('Notes are only visible to website administrators, they are not sent to the client.', 'wp-booking-system')) ?></h3>

        <div class="wpbs-booking-details-modal-notes-wrap">

            <?php if($this->get_notes()): ?>

                <?php foreach($this->get_notes() as $i => $note): ?>

                    <div class="wpbs-booking-details-modal-note">

                        <p><?php echo nl2br($note['note']); ?></p>

                        <div class="wpbs-booking-details-modal-note-footer">

                            <span class="wpbs-booking-details-modal-note-date-added">
                                <strong><?php echo __('Added on', 'wp-booking-system') ?>:</strong> 
                                <?php echo wpbs_date_i18n(get_option('date_format') . ' ' . get_option('time_format'), $note['timestamp']); ?>
                            </span>
                            
                            <a href="#" data-booking-note="<?php echo $i;?>" data-booking-id="<?php echo $this->booking->get('id');?>" class="wpbs-booking-details-modal-note-remove"><?php echo __('delete note', 'wp-booking-system') ?></a>
                        </div>

                    </div>

                <?php endforeach; ?>

            <?php else: ?>

                <p class="wpbs-booking-details-modal-note-no-results"><?php echo __('No notes found for this booking.', 'wp-booking-system') ?></p>

            <?php endif; ?>

        </div>
        
        <h3><?php echo __('Add New Note', 'wp-booking-system') ?></h3>

        <form class="wpbs-booking-details-modal-notes-add-new">
        
            <textarea id="wpbs_modal_booking_note" rows="5"></textarea>

            <button class="button button-primary" id="wpbs_modal_add_booking_note" data-booking-id="<?php echo $this->booking->get('id');?>"><?php echo __('Add Note', 'wp-booking-system') ?></button>

        </form>

    </div>
    
</div>