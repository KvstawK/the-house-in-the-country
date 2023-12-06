<div class="wpbs-booking-details-modal-booking-details">
    <div class="wpbs-booking-details-modal-column">

        <h3><?php echo __('Edit Invoice Details', 'wp-booking-system-invoices') ?></h3>
        <!-- Enable Invoices -->
            <div class="wpbs-settings-field-wrapper wpbs-settings-field-inline wpbs-settings-field-medium">
                <label class="wpbs-settings-field-label" for="wpbs-invoice-details">
                    <?php echo __( 'Buyer Details', 'wp-booking-system-invoices'); ?>
                </label>

                <div class="wpbs-settings-field-inner">
                    <textarea name="wpbs-invoice-details" id="wpbs-invoice-details" cols="30" rows="10"><?php echo esc_textarea(wpbs_get_booking_meta($booking->get('id'),'invoice_buyer_details', true)) ?></textarea>
                </div>
            </div>
        
        <button class="button-primary" data-label="<?php echo __('Update Invoice Details', 'wp-booking-system-invoices') ?>" data-label-wait="<?php echo __('Please wait...', 'wp-booking-system-invoices') ?>" data-label-done="<?php echo __('Details Updated', 'wp-booking-system-invoices') ?>" data-booking-id="<?php echo $booking->get('id');?>" id="wpbs-update-invoice-details"><?php echo __('Update Invoice Details', 'wp-booking-system-invoices') ?></button>
        
        <div class="wpbs-booking-details-modal-footer-actions wpbs-booking-details-modal-footer-actions-invoice">
            <hr>
            <a href="<?php echo wpbs_get_invoice_link($booking->get('invoice_hash')) ?>" class="button-secondary" target="_blank" title="<?php echo __('View Invoice', 'wp-booking-system-invoices') ?>"><span class="dashicons dashicons-visibility"></span></span> <?php echo __('View Invoice', 'wp-booking-system-invoices') ?></a>
            <a href="<?php echo wpbs_get_invoice_link($booking->get('invoice_hash'), true) ?>" class="button-secondary" target="_blank" title="<?php echo __('Download Invoice', 'wp-booking-system-invoices') ?>"><span class="dashicons dashicons-download"></span> <?php echo __('Download Invoice', 'wp-booking-system-invoices') ?></a>
        </div>
    </div>
     
</div>