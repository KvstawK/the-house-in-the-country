<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

?>


<?php

    /**
     * Hook to add extra fields at the top of the Form Tab
     *
     * @param array $settings
     *
     */
    do_action( 'wpbs_submenu_page_settings_tab_form_top', $settings );

?>

<h2>
    <?php echo __( 'Form Settings', 'wp-booking-system' ); ?>
</h2>

<div class="wpbs-settings-field-wrapper wpbs-settings-field-inline wpbs-settings-field-medium">
    <label class="wpbs-settings-field-label" for="form_styling">
        <?php echo __( 'Styling', 'wp-booking-system' ); ?>
        <?php echo wpbs_get_output_tooltip(__("By default we use our own styling to make sure everything looks fine. You can select 'Theme Styling' to disable the output of our custom CSS.", 'wp-booking-system'));?>
    </label>

    <div class="wpbs-settings-field-inner">
        <select name="wpbs_settings[form_styling]" id="form_styling">
            <option value="default" <?php echo isset($settings['form_styling']) ? selected($settings['form_styling'], 'default', false) : '';?> ><?php echo __('Plugin Styling','wp-booking-system') ?></option>
            <option value="theme" <?php echo isset($settings['form_styling']) ? selected($settings['form_styling'], 'theme', false) : '';?>><?php echo __('Theme Styling','wp-booking-system') ?></option>
        </select>
        
    </div>
</div>


<h2>
    <?php echo __( 'Phone Number Field Stylised UI', 'wp-booking-system' ); ?>
</h2>

<!-- Styled Phone Number Field -->
<div class="wpbs-settings-field-wrapper wpbs-settings-field-inline wpbs-settings-field-large">
	<label class="wpbs-settings-field-label" for="form_styled_phone_input_toggle">
        <?php echo __( 'Enable', 'wp-booking-system' ); ?>
        <?php echo wpbs_get_output_tooltip(__("Make the Phone form field a stylised input with a dropdown for selecting the country code, phone number formatting and live validation.", 'wp-booking-system'));?>
    </label>

	<div class="wpbs-settings-field-inner">
        <label for="form_styled_phone_input_toggle" class="wpbs-checkbox-switch">
            <input type="hidden" name="wpbs_settings[form_styled_phone_input_toggle]" value="0">
            <input data-target="#wpbs-form-styled-phone-input-wrapper" name="wpbs_settings[form_styled_phone_input_toggle]" type="checkbox" id="form_styled_phone_input_toggle"  class="regular-text wpbs-settings-toggle wpbs-settings-wrap-toggle" <?php echo ( ! empty( $settings['form_styled_phone_input_toggle']) && $settings['form_styled_phone_input_toggle'] == 'on' ) ? 'checked' : '';?> >
            <div class="wpbs-checkbox-slider"></div>
        </label>
	</div>
</div>

<div id="wpbs-form-styled-phone-input-wrapper" class="wpbs-user-notification-wrapper wpbs-settings-wrapper <?php echo ( ! empty( $settings['form_styled_phone_input_toggle']) && $settings['form_styled_phone_input_toggle'] == 'on' ) ? 'wpbs-settings-wrapper-show' : '';?>">

    <!-- Default Country -->
    <div class="wpbs-settings-field-wrapper wpbs-settings-field-inline  wpbs-settings-field-large">

        <label class="wpbs-settings-field-label" for="form_styled_phone_input_default_country"><?php echo __( 'Default Country', 'wp-booking-system' ); ?></label>

        <div class="wpbs-settings-field-inner">
            <select name="wpbs_settings[form_styled_phone_input_default_country]" id="form_styled_phone_input_default_country">
                <option value="">-</option>
                <?php foreach( wpbs_intl_tel_input_countries_list() as $country_code => $country_name): ?>
                    <option <?php echo (isset($settings['form_styled_phone_input_default_country']) && $settings['form_styled_phone_input_default_country'] == $country_code) ? 'selected="selected"' : '';?> value="<?php echo $country_code;?>"><?php echo $country_name;?></option>
                <?php endforeach; ?>
            </select>
        </div>
        
    </div>

    <!-- Dynamic Country Lookup -->
    <div class="wpbs-settings-field-wrapper wpbs-settings-field-inline  wpbs-settings-field-large">

        <label class="wpbs-settings-field-label" for="form_styled_phone_input_country_lookup"><?php echo __( 'ipinfo.io Token', 'wp-booking-system' ); ?></label>

        <div class="wpbs-settings-field-inner">
            <input name="wpbs_settings[form_styled_phone_input_country_lookup]" id="form_styled_phone_input_country_lookup" type="text" value="<?php echo ( ! empty( $settings['form_styled_phone_input_country_lookup'] ) ? esc_attr( $settings['form_styled_phone_input_country_lookup'] ) : '' ); ?>" />
        </div>
        
    </div>

    <div class="wpbs-page-notice notice-info wpbs-form-changed-notice"> 
        <p><?php echo __("You can use the automatic country location feature by using looking up the customer's IP address. Sign up for a free API key on <a href='https://ipinfo.io' target='_blank'>https://ipinfo.io</a>.",'wp-booking-system') ?></p>
    </div>


</div>


<!-- reCAPTCHA Keys -->
<h2><?php echo __( 'Google reCAPTCHA', 'wp-booking-system' ); ?></h2>


<div class="wpbs-settings-field-wrapper wpbs-settings-field-inline wpbs-settings-field-medium">
    <label class="wpbs-settings-field-label" for="recaptcha_type">
        <?php echo __( 'reCAPTCHA Type', 'wp-booking-system' ); ?>
    </label>

    <div class="wpbs-settings-field-inner">
        <select name="wpbs_settings[recaptcha_type]" id="recaptcha_type">
            <option value="v2_tickbox" <?php echo isset($settings['recaptcha_type']) ? selected($settings['recaptcha_type'], 'v2_tickbox', false) : '';?> ><?php echo __('reCAPTCHA v2 Tickbox','wp-booking-system') ?></option>
            <option value="v3" <?php echo isset($settings['recaptcha_type']) ? selected($settings['recaptcha_type'], 'v3', false) : '';?>><?php echo __('reCAPTCHA v3','wp-booking-system') ?></option>
        </select>
        
    </div>
</div>

<!-- reCAPTCHA v2 -->
<div class="wpbs-settings-field-wrapper-recapthca wpbs-settings-field-wrapper-recapthca-v2_tickbox wpbs-hide">

    <!-- reCAPTCHA Keys -->
    <div class="wpbs-settings-field-wrapper wpbs-settings-field-inline  wpbs-settings-field-large">

        <label class="wpbs-settings-field-label" for="recaptcha_v2_site_key"><?php echo __( 'v2 Site Key', 'wp-booking-system' ); ?></label>

        <div class="wpbs-settings-field-inner">
            <input name="wpbs_settings[recaptcha_v2_site_key]" id="recaptcha_v2_site_key" type="text" value="<?php echo ( ! empty( $settings['recaptcha_v2_site_key'] ) ? esc_attr( $settings['recaptcha_v2_site_key'] ) : '' ); ?>" />
        </div>
        
    </div>

    <!-- reCAPTCHA Keys -->
    <div class="wpbs-settings-field-wrapper wpbs-settings-field-inline wpbs-settings-field-large">

        <label class="wpbs-settings-field-label" for="recaptcha_v2_secret_key"><?php echo __( 'v2 Secret Key', 'wp-booking-system' ); ?></label>

        <div class="wpbs-settings-field-inner">
            <input name="wpbs_settings[recaptcha_v2_secret_key]" id="recaptcha_v2_secret_key" type="text" value="<?php echo ( ! empty( $settings['recaptcha_v2_secret_key'] ) ? esc_attr( $settings['recaptcha_v2_secret_key'] ) : '' ); ?>" />
        </div>
        
    </div>

</div>

<!-- reCAPTCHA v3 -->
<div class="wpbs-settings-field-wrapper-recapthca wpbs-settings-field-wrapper-recapthca-v3  wpbs-hide">

    <!-- reCAPTCHA Keys -->
    <div class="wpbs-settings-field-wrapper wpbs-settings-field-inline  wpbs-settings-field-large">

        <label class="wpbs-settings-field-label" for="recaptcha_v3_site_key"><?php echo __( 'v3 Site Key', 'wp-booking-system' ); ?></label>

        <div class="wpbs-settings-field-inner">
            <input name="wpbs_settings[recaptcha_v3_site_key]" id="recaptcha_v3_site_key" type="text" value="<?php echo ( ! empty( $settings['recaptcha_v3_site_key'] ) ? esc_attr( $settings['recaptcha_v3_site_key'] ) : '' ); ?>" />
        </div>
        
    </div>

    <!-- reCAPTCHA Keys -->
    <div class="wpbs-settings-field-wrapper wpbs-settings-field-inline wpbs-settings-field-large">

        <label class="wpbs-settings-field-label" for="recaptcha_v3_secret_key"><?php echo __( 'v3 Secret Key', 'wp-booking-system' ); ?></label>

        <div class="wpbs-settings-field-inner">
            <input name="wpbs_settings[recaptcha_v3_secret_key]" id="recaptcha_v3_secret_key" type="text" value="<?php echo ( ! empty( $settings['recaptcha_v3_secret_key'] ) ? esc_attr( $settings['recaptcha_v3_secret_key'] ) : '' ); ?>" />
        </div>
        
    </div>

</div>


<?php

    /**
     * Hook to add extra fields at the bottom of the Form Tab
     *
     * @param array $settings
     *
     */
    do_action( 'wpbs_submenu_page_settings_tab_form_bottom', $settings );

?>

<!-- Submit button -->
<input type="submit" class="button-primary" value="<?php echo __( 'Save Settings', 'wp-booking-system' ); ?>" />