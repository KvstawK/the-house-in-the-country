<?php
$form_id   = absint( ! empty( $_GET['form_id'] ) ? $_GET['form_id'] : 0 );
$form      = wpbs_get_form( $form_id );

if( is_null( $form ) )
    return;

$form_meta = wpbs_get_form_meta($form_id);
$form_data = $form->get('fields');
?>

<!-- Form Changed Notice -->
<div class="wpbs-page-notice notice-info wpbs-form-changed-notice"> 
    <p><?php echo __( 'It appears you made changes to the form. Make sure you save the form before you make any changes on this page to ensure all email tags are up to date.', 'wp-booking-system-invoices'); ?></p>
</div>

<div class="wpbs-settings-field-wrapper wpbs-settings-field-inline wpbs-settings-field-heading wpbs-settings-field-large">
    <label class="wpbs-settings-field-label"><?php echo __( 'Bookings Manager Field Mappings', 'wp-booking-system-booking-manager' ); ?></label>
    <div class="wpbs-settings-field-inner">&nbsp;</div>
</div>

<!-- Fields -->
<div class="wpbs-settings-field-wrapper wpbs-settings-field-inline wpbs-settings-field-large">

	<label class="wpbs-settings-field-label" for="booking_manager_fields"><?php echo __( 'Fields', 'wp-booking-system-booking-manager' ); ?>  
        <?php echo wpbs_get_output_tooltip(__("Select which fields will appear in the Bookings List page for bookings made with this form.", 'wp-booking-system-booking-manager'));?>
    </label>
    <?php $selected_fields = isset($form_meta['booking_manager_fields'][0]) ? unserialize($form_meta['booking_manager_fields'][0]) : array(); ?>
	<div class="wpbs-settings-field-inner wpbs-chosen-wrapper">
        <input type="hidden" name="booking_manager_fields" value="" />
        <select name="booking_manager_fields[]" class="wpbs-chosen" id="booking_manager_fields" multiple>
            <?php foreach($form_data as $field): if (in_array($field['type'], wpbs_get_excluded_fields(array('hidden')))) {continue;}  ?>
                <option value="<?php echo $field['id'];?>" <?php echo (in_array($field['id'], $selected_fields)) ? 'selected' : '';?>><?php echo isset($field['values']['default']['label']) ? $field['values']['default']['label'] : '- (' . str_replace('_',' ', $field['type']) . ' field)' ?></option>
            <?php endforeach; ?>
        </select>
	</div>
	
</div>