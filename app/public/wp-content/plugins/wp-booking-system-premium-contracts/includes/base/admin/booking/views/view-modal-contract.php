<div class="wpbs-booking-details-modal-booking-details">
    <div class="wpbs-booking-details-modal-column">

        <h3><?php echo __('Edit Contract', 'wp-booking-system-contracts') ?></h3>
        <!-- Enable Contracts -->
            <div class="wpbs-settings-field-wrapper wpbs-settings-field-wrapper-edit-contract">
                

                <div class="wpbs-settings-field-inner">
                    <div class="wpbs-wp-editor-ajax" data-id="contract_content">
                        <?php wp_editor(wpbs_get_booking_meta($booking->get('id'),'contract_content', true), 'contract_content', array('teeny' => false, 'textarea_rows' => 30, 'media_buttons' => false, 'textarea_name' => 'contract_content'))?>
                    </div>
                </div>
            </div>
        
        <button class="button-primary" data-label="<?php echo __('Update Contract', 'wp-booking-system-contracts') ?>" data-label-wait="<?php echo __('Please wait...', 'wp-booking-system-contracts') ?>" data-label-done="<?php echo __('Contract Updated', 'wp-booking-system-contracts') ?>" data-booking-id="<?php echo $booking->get('id');?>" id="wpbs-update-contract-details"><?php echo __('Update Contract', 'wp-booking-system-contracts') ?></button>

        <button class="button-secondary" data-label="<?php echo __('Regenerate Contract', 'wp-booking-system-contracts') ?>" data-label-wait="<?php echo __('Please wait...', 'wp-booking-system-contracts') ?>" data-label-done="<?php echo __('Contract Updated', 'wp-booking-system-contracts') ?>" data-booking-id="<?php echo $booking->get('id');?>" id="wpbs-regenerate-contract-details"><?php echo __('Regenerate Contract', 'wp-booking-system-contracts') ?></button>
        
        <div class="wpbs-booking-details-modal-footer-actions wpbs-booking-details-modal-footer-actions-contract">
            <hr>
            <a href="<?php echo wpbs_get_contract_link($booking->get('invoice_hash')) ?>" class="button-secondary" target="_blank" title="<?php echo __('View Contract', 'wp-booking-system-contracts') ?>"><span class="dashicons dashicons-visibility"></span></span> <?php echo __('View Contract', 'wp-booking-system-contracts') ?></a>
            <a href="<?php echo wpbs_get_contract_link($booking->get('invoice_hash'), true) ?>" class="button-secondary" target="_blank" title="<?php echo __('Download Contract', 'wp-booking-system-contracts') ?>"><span class="dashicons dashicons-download"></span> <?php echo __('Download Contract', 'wp-booking-system-contracts') ?></a>
        </div>
    </div>
     
</div>