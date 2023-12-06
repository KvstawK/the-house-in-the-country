<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

$settings = get_option( 'wpbs_settings', array() );

$form_id   = absint( ! empty( $_GET['form_id'] ) ? $_GET['form_id'] : 0 );
$form      = wpbs_get_form( $form_id );

if( is_null( $form ) )
    return;

$form_meta = wpbs_get_form_meta($form_id);

/**
 * Exclude from users query roles that are already set in the Settings page
 *
 */
$exclude_roles  = array( 'Administrator' );
$editable_roles = get_editable_roles();
$saved_roles    = ( ! empty( $settings['user_role_permissions'] ) ? $settings['user_role_permissions'] : array() );

foreach( $saved_roles as $role_slug ) {

	if( ! empty( $editable_roles[$role_slug]['name'] ) )
		$exclude_roles[] = $editable_roles[$role_slug]['name'];

}

/**
 * User query
 *
 */
$args = array(
	'number'	   => apply_filters('wpbs_get_users_numberposts', 1000),
	'role__not_in' => $exclude_roles
);

$users = get_users( $args );

$form_users = wpbs_get_form_meta( $form->get('id'), 'user_permission' );

if( empty( $form_users ) )
	$form_users = array();

?>


<!-- User Permissions -->
<div class="wpbs-settings-field-wrapper wpbs-settings-field-inline wpbs-settings-field-large">
    <label class="wpbs-settings-field-label" for="user_permission">
        <?php echo __( 'User Editing Permissions', 'wp-booking-system' ); ?>
        <?php echo wpbs_get_output_tooltip( __( 'If you wish to allow certain users to edit this form, select them from the field below. The selected users will be able to edit only this form. If you select the same users in other forms, they will be able to also edit those forms.', 'wp-booking-system' ) ); ?>
    </label>

    <div class="wpbs-settings-field-inner">
        <div class="wpbs-settings-field-inner wpbs-chosen-wrapper">

            <?php if( ! empty( $users ) ): ?>
            <select multiple name="user_permission[]" class="wpbs-chosen">
                <?php 
                    
                    foreach( $users as $user ) {
                        echo '<option value="' . esc_attr( $user->ID ) . '" ' . ( in_array( $user->ID, $form_users ) ? 'selected' : '' ) . '>' . $user->display_name . '</option>';
                    }
                    
                ?>
            </select>
            <?php else: ?>
                <p class="description" style="padding-top: 4px;"><?php echo __( 'There are no users that can be assigned to the form.', 'wp-booking-system' ); ?></p>
            <?php endif; ?>

        </div>
      
    </div>
</div>
