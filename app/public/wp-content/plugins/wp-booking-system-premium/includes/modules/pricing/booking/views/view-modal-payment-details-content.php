<div class="wpbs-booking-details-modal-column wpbs-booking-details-modal-column-left">

    <h3><?php echo __('Payment Information', 'wp-booking-system') ?></h3>

    <table>
        <?php foreach ($payment_information as $data) : ?>
            <tr class="wpbs-booking-details-<?php echo sanitize_title($data['label']); ?> wpbs-booking-details-<?php echo sanitize_title($data['label']); ?>-<?php echo sanitize_title($data['value']); ?>">
                <td><strong><?php echo $data['label'] ?>:</strong></td>
                <td>
                    <p><?php echo $data['value'] ?></p>
                </td>
            </tr>
        <?php endforeach ?>
    </table>

    <?php if ($payment->has_security_deposit()) : ?>
        <h3><?php echo __('Security Deposit', 'wp-booking-system') ?></h3>
        <div class="wpbs-booking-details-modal-refunds" id="wpbs-booking-details-modal-refunds">
            <?php wpbs_booking_details_modal_security_deposits($payment); ?>
        </div>
    <?php endif; ?>

</div>

<div class="wpbs-booking-details-modal-column wpbs-booking-details-modal-column-right">

    <h3><?php echo __('Order Information', 'wp-booking-system') ?></h3>

    <form action="#" method="post" class="wpbs-edit-booking-details" data-type="payment_details">

        <table>
            <?php $i = -1;
            foreach ($order_information as $id => $data) :
                $current_type = isset($data['type']) ? $data['type'] : '';
                if ($current_type == 'vat') $current_type = 'tax';
                if (isset($type) && $type != $current_type) $i = -1;
                $i++;
                $type = $current_type;

                $editable = (isset($data['editable']) && $data['editable'] == true) ? true : false;
            ?>
                <tr class="wpbs-booking-details-<?php echo sanitize_title($data['label']); ?> wpbs-booking-details-<?php echo sanitize_title($data['label']); ?>-<?php echo sanitize_title($data['value']); ?> wpbs-payment-details-field-<?php echo $type; ?>-<?php echo $i; ?>">
                    <td><strong><?php echo $data['label'] ?>:</strong></td>
                    <td class="<?php echo ($editable) ? 'wpbs-edit-booking-details-field-editable' : ''; ?>">
                        <span class="wpbs-edit-booking-details-field-view">
                            <p><?php echo $data['value'] ?></p>
                        </span>

                        <?php if ($editable) : ?>
                            <span class="wpbs-edit-booking-details-field-edit">
                                <input name="wpbs-edit-booking-pricing-field[<?php echo $type; ?>][]" class="wpbs-edit-booking-field-<?php echo $id; ?>" type="number" step="0.01" value="<?php echo $data['price'] ?>">
                            </span>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach ?>

            <?php wpbs_modal_payment_details_refunds_view($payment); ?>

        </table>

        <?php wp_nonce_field('wpbs_edit_booking', 'wpbs_edit_payment_details_token', false); ?>
        <input type="hidden" name="currency" value="<?php echo $payment->get_currency(); ?>">
        <input type="hidden" name="booking_id" value="<?php echo $booking->get('id'); ?>">

        <button class="edit-booking-details-open button button-secondary"><?php echo __('Edit', 'wp-booking-system') ?></button>

        <div class="wpbs-page-notice notice-error wpbs-form-changed-notice">
            <p><?php echo __('Modifying the prices directly updates the values in the database without performing any calculations or validations. Please use with caution.', 'wp-booking-system') ?></p>
        </div>

        <button class="edit-booking-details-save button button-primary"><?php echo __('Save Changes', 'wp-booking-system') ?></button>
        <button class="edit-booking-details-cancel button button-secondary"><?php echo __('Cancel', 'wp-booking-system') ?></button>



    </form>

</div>