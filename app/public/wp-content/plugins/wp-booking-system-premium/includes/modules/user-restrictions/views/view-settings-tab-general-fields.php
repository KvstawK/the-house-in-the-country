<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

$user_roles = get_editable_roles();

?>

<!-- User Role Permissions -->
<div class="wpbs-settings-field-wrapper wpbs-settings-field-inline">

    <label class="wpbs-settings-field-label">
        <?php echo __( 'User Role Permissions', 'wp-booking-system' ); ?>
        <?php echo wpbs_get_output_tooltip( __( 'Select the user roles you wish to have calendar editing capabilities. All users that have the selected user roles will be able to edit all calendars.', 'wp-booking-system' ) ); ?>
    </label>

    <div class="wpbs-settings-field-inner wpbs-chosen-wrapper">

        <select multiple name="wpbs_settings[user_role_permissions][]" class="wpbs-chosen">
            <?php 
                foreach( $user_roles as $user_role_slug => $user_role ) {

                    if( $user_role_slug == 'administrator' )
                        continue;

                    echo '<option value="' . esc_attr( $user_role_slug ) . '" ' . ( ! empty( $settings['user_role_permissions'] ) && in_array( $user_role_slug, $settings['user_role_permissions'] ) ? 'selected' : '' ) . '>' . $user_role['name'] . '</option>';

                }
            ?>
        </select>

    </div>
    
</div>