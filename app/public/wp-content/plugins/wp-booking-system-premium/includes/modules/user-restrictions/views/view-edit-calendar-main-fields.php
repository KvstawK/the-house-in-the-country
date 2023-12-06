<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

$settings = get_option( 'wpbs_settings', array() );

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

$calendar_users = wpbs_get_calendar_meta( $calendar->get('id'), 'user_permission' );

if( empty( $calendar_users ) )
	$calendar_users = array();

?>

<!-- User Permissions -->
<div class="postbox">

	<h3 class="hndle"><?php echo __( 'Users Editing Permissions', 'wp-booking-system' ); ?><?php echo wpbs_get_output_tooltip( __( 'If you wish to allow certain users to edit this calendar, select them from the field below. The selected users will be able to edit only this calendar. If you select the same users in other calendars, they will be able to also edit those calendars.', 'wp-booking-system' ) ); ?></h3>

	<div class="inside">

		<div class="wpbs-settings-field-wrapper wpbs-settings-field-inline wpbs-last">

			<label class="wpbs-settings-field-label"><?php echo __( 'User Assign', 'wp-booking-system' ); ?></label>

			<div class="wpbs-settings-field-inner wpbs-chosen-wrapper">

				<?php if( ! empty( $users ) ): ?>
				<select multiple name="calendar_user_permissions[]" class="wpbs-chosen">
					<?php 
						
						foreach( $users as $user ) {
							$name = (get_user_meta( $user->ID, 'first_name', true ) && get_user_meta( $user->ID, 'last_name', true )) ? get_user_meta( $user->ID, 'first_name', true ) . ' ' . get_user_meta( $user->ID, 'last_name', true ) . ' ('.$user->user_login.')' : $user->user_login;

							echo '<option value="' . esc_attr( $user->ID ) . '" ' . ( in_array( $user->ID, $calendar_users ) ? 'selected' : '' ) . '>' . $name . '</option>';

						}
						
					?>
				</select>
				<?php else: ?>
					<p class="description" style="padding-top: 4px;"><?php echo __( 'There are no users that can be assigned to the calendar.', 'wp-booking-system' ); ?></p>
				<?php endif; ?>

			</div>

		</div>

	</div>
	
</div>

<!-- User Permissions Nonce -->
<?php wp_nonce_field( 'wpbs_token_calendar_user_premissions', 'wpbs_token_calendar_user_premissions', false ); ?>