<?php 
$active_languages = (!empty($settings['active_languages']) ? $settings['active_languages'] : array());
$languages = wpbs_get_languages();
?>

<h2><?php echo __('Stripe', 'wp-booking-system-stripe') ?><?php echo wpbs_get_output_tooltip(__("Give the customer the option to pay with a credit card using Stripe.", 'wp-booking-system-stripe'));?></h2>

<!-- Enable Stripe -->
<div class="wpbs-settings-field-wrapper wpbs-settings-field-inline wpbs-settings-field-large">
	<label class="wpbs-settings-field-label" for="payment_stripe_enable">
        <?php echo __( 'Active', 'wp-booking-system-stripe'); ?>
    </label>

	<div class="wpbs-settings-field-inner">
        <label for="payment_stripe_enable" class="wpbs-checkbox-switch">
            <input type="hidden" name="wpbs_settings[payment_stripe_enable]" value="0">
            <input data-target="#wpbs-payment-stripe" name="wpbs_settings[payment_stripe_enable]" type="checkbox" id="payment_stripe_enable"  class="regular-text wpbs-settings-toggle wpbs-settings-wrap-toggle" <?php echo ( !empty( $settings['payment_stripe_enable'] ) ) ? 'checked' : '';?> >
            <div class="wpbs-checkbox-slider"></div>
        </label>
	</div>
</div>

<div id="wpbs-payment-stripe" class="wpbs-payment-on-arrival-wrapper wpbs-settings-wrapper <?php echo ( !empty($settings['payment_stripe_enable']) ) ? 'wpbs-settings-wrapper-show' : '';?>">

    <!-- Payment Method Name -->
    <div class="wpbs-settings-field-translation-wrapper">
        <div class="wpbs-settings-field-wrapper wpbs-settings-field-inline wpbs-settings-field-large">
            <label class="wpbs-settings-field-label" for="payment_stripe_name">
                <?php echo __( 'Display name', 'wp-booking-system-stripe'); ?>
                <?php echo wpbs_get_output_tooltip(__("The payment method name that appears on the booking form.", 'wp-booking-system-stripe'));?>
            </label>

            <div class="wpbs-settings-field-inner">
                <input name="wpbs_settings[payment_stripe_name]" type="text" id="payment_stripe_name"  class="regular-text" value="<?php echo ( !empty( $settings['payment_stripe_name'] ) ) ? $settings['payment_stripe_name'] : $defaults['display_name'];?>" >
                <?php if (wpbs_translations_active()): ?><a href="#" class="wpbs-settings-field-show-translations"><?php echo __('Translations', 'wp-booking-system-stripe'); ?> <i class="wpbs-icon-down-arrow"></i></a><?php endif?>
            </div>
        </div>
        <?php if (wpbs_translations_active()): ?>
        <!-- Required Field Translations -->
        <div class="wpbs-settings-field-translations">
            <?php foreach ($active_languages as $language): ?>
                <div class="wpbs-settings-field-wrapper wpbs-settings-field-inline wpbs-settings-field-large">
                    <label class="wpbs-settings-field-label" for="payment_stripe_name_translation_<?php echo $language; ?>"><img src="<?php echo WPBS_PLUGIN_DIR_URL; ?>/assets/img/flags/<?php echo $language; ?>.png"> <?php echo $languages[$language]; ?></label>
                    <div class="wpbs-settings-field-inner">
                        <input name="wpbs_settings[payment_stripe_name_translation_<?php echo $language; ?>]" type="text" id="payment_stripe_name_translation_<?php echo $language; ?>" value="<?php echo (!empty($settings['payment_stripe_name_translation_'. $language])) ? esc_attr($settings['payment_stripe_name_translation_'. $language]) : ''; ?>" class="regular-text" >
                    </div>
                </div>
            <?php endforeach;?>
        </div>
        <?php endif; ?>
    </div>

    <!-- Payment Method Name -->
    <div class="wpbs-settings-field-translation-wrapper">
        <div class="wpbs-settings-field-wrapper wpbs-settings-field-inline wpbs-settings-field-large">
            <label class="wpbs-settings-field-label" for="payment_stripe_description">
                <?php echo __( 'Description', 'wp-booking-system-stripe'); ?>
                <?php echo wpbs_get_output_tooltip(__("The payment method description that appears on the booking form.", 'wp-booking-system-stripe'));?>
            </label>

            <div class="wpbs-settings-field-inner">
                <input name="wpbs_settings[payment_stripe_description]" type="text" id="payment_stripe_description"  class="regular-text" value="<?php echo ( !empty( $settings['payment_stripe_description'] ) ) ? $settings['payment_stripe_description'] : $defaults['description'];?>" >
                <?php if (wpbs_translations_active()): ?><a href="#" class="wpbs-settings-field-show-translations"><?php echo __('Translations', 'wp-booking-system-stripe'); ?> <i class="wpbs-icon-down-arrow"></i></a><?php endif?>
            </div>
        </div>
        <?php if (wpbs_translations_active()): ?>
        <!-- Required Field Translations -->
        <div class="wpbs-settings-field-translations">
            <?php foreach ($active_languages as $language): ?>
                <div class="wpbs-settings-field-wrapper wpbs-settings-field-inline wpbs-settings-field-large">
                    <label class="wpbs-settings-field-label" for="payment_stripe_description_translation_<?php echo $language; ?>"><img src="<?php echo WPBS_PLUGIN_DIR_URL; ?>/assets/img/flags/<?php echo $language; ?>.png"> <?php echo $languages[$language]; ?></label>
                    <div class="wpbs-settings-field-inner">
                        <input name="wpbs_settings[payment_stripe_description_translation_<?php echo $language; ?>]" type="text" id="payment_stripe_description_translation_<?php echo $language; ?>" value="<?php echo (!empty($settings['payment_stripe_description_translation_'. $language])) ? esc_attr($settings['payment_stripe_description_translation_'. $language]) : ''; ?>" class="regular-text" >
                    </div>
                </div>
            <?php endforeach;?>
        </div>
        <?php endif; ?>
    </div>

    <!-- Invoice Item Name -->
    <div class="wpbs-settings-field-translation-wrapper">
        <div class="wpbs-settings-field-wrapper wpbs-settings-field-inline wpbs-settings-field-large">
            <label class="wpbs-settings-field-label" for="payment_stripe_invoice_name">
                <?php echo __( 'Invoice Item Name', 'wp-booking-system-stripe'); ?>
                <?php echo wpbs_get_output_tooltip(__('The name of the product that appears on the Stripe invoice. Eg. "Booking at Anne\'s house."', 'wp-booking-system-stripe'));?>
            </label>

            <div class="wpbs-settings-field-inner">
                <input name="wpbs_settings[payment_stripe_invoice_name]" type="text" id="payment_stripe_invoice_name"  class="regular-text" value="<?php echo ( !empty( $settings['payment_stripe_invoice_name'] ) ) ? $settings['payment_stripe_invoice_name'] : '';?>" >
                <?php if (wpbs_translations_active()): ?><a href="#" class="wpbs-settings-field-show-translations"><?php echo __('Translations', 'wp-booking-system-stripe'); ?> <i class="wpbs-icon-down-arrow"></i></a><?php endif?>
            </div>
        </div>
        <?php if (wpbs_translations_active()): ?>
        <!-- Required Field Translations -->
        <div class="wpbs-settings-field-translations">
            <?php foreach ($active_languages as $language): ?>
                <div class="wpbs-settings-field-wrapper wpbs-settings-field-inline wpbs-settings-field-large">
                    <label class="wpbs-settings-field-label" for="payment_stripe_invoice_name_translation_<?php echo $language; ?>"><img src="<?php echo WPBS_PLUGIN_DIR_URL; ?>/assets/img/flags/<?php echo $language; ?>.png"> <?php echo $languages[$language]; ?></label>
                    <div class="wpbs-settings-field-inner">
                        <input name="wpbs_settings[payment_stripe_invoice_name_translation_<?php echo $language; ?>]" type="text" id="payment_stripe_invoice_name_translation_<?php echo $language; ?>" value="<?php echo (!empty($settings['payment_stripe_invoice_name_translation_'. $language])) ? esc_attr($settings['payment_stripe_invoice_name_translation_'. $language]) : ''; ?>" class="regular-text" >
                    </div>
                </div>
            <?php endforeach;?>
        </div>
        <?php endif; ?>
    </div>

    <!-- Authorize now, capture later -->
    <div class="wpbs-settings-field-wrapper wpbs-settings-field-inline wpbs-settings-field-large">
        <label class="wpbs-settings-field-label" for="payment_stripe_delayed_capture">
            <?php echo __( 'Capture payment when accepting booking', 'wp-booking-system-stripe'); ?>
            <?php echo wpbs_get_output_tooltip(__('If enabled, when the client makes a payment, his credit card will only be Authorized (the money will be put on hold for 7 days) and the payment will be Captured (money transfered in your account) only when you Accept the booking. Accepting the booking after 7 days will result in a failed payment.', 'wp-booking-system-stripe'));?>
        </label>

        <div class="wpbs-settings-field-inner">
            <label for="payment_stripe_delayed_capture" class="wpbs-checkbox-switch">
                <input type="hidden" name="wpbs_settings[payment_stripe_delayed_capture]" value="0">
                <input name="wpbs_settings[payment_stripe_delayed_capture]" type="checkbox" id="payment_stripe_delayed_capture"  class="regular-text wpbs-settings-toggle" <?php echo ( !empty( $settings['payment_stripe_delayed_capture'] ) ) ? 'checked' : '';?> >
                <div class="wpbs-checkbox-slider"></div>
            </label>
        </div>

        
    </div>

    <!-- Enable Apple/Google/Browser Pay  -->
    <div class="wpbs-settings-field-wrapper wpbs-settings-field-inline wpbs-settings-field-large">
        <label class="wpbs-settings-field-label" for="payment_stripe_apple_pay">
            <?php echo __( 'Enable Apple Pay and Google Pay button', 'wp-booking-system-stripe'); ?>
            <?php echo wpbs_get_output_tooltip(__('Easily collect payment from customers who use Apple Pay, Google Pay, and browser-saved cards.', 'wp-booking-system-stripe'));?>
        </label>

        <div class="wpbs-settings-field-inner">
            <label for="payment_stripe_apple_pay" class="wpbs-checkbox-switch">
                <input type="hidden" name="wpbs_settings[payment_stripe_apple_pay]" value="0">
                <input name="wpbs_settings[payment_stripe_apple_pay]" type="checkbox" id="payment_stripe_apple_pay"  class="regular-text wpbs-settings-toggle" <?php echo ( !empty( $settings['payment_stripe_apple_pay'] ) ) ? 'checked' : '';?> >
                <div class="wpbs-checkbox-slider"></div>
            </label>
        </div>

        
    </div>


    <!-- API Settings -->
    <div class="wpbs-settings-field-wrapper wpbs-settings-field-inline wpbs-settings-field-heading wpbs-settings-field-large">
        <label class="wpbs-settings-field-label"><?php echo __('API Credentials','wp-booking-system-stripe') ?></label>
        <div class="wpbs-settings-field-inner">&nbsp;</div>
    </div>

    <!-- Documentation -->
    <div class="wpbs-page-notice notice-info wpbs-form-changed-notice"> 
        <p><?php echo __( 'If you need help getting your API Keys, <a target="_blank" href="https://www.wpbookingsystem.com/documentation/stripe-integration/">check out our guide</a> which offers step by step instructions.', 'wp-booking-system-stripe'); ?></p>
    </div>

    <!-- Environment -->
    <div class="wpbs-settings-field-wrapper wpbs-settings-field-inline wpbs-settings-field-large">
        <div class="wpbs-settings-field-inner">
            <label class="wpbs-settings-field-label" for="user_notification_enable">
                <?php echo __( 'Enable Test Mode', 'wp-booking-system-stripe'); ?>
                <?php echo wpbs_get_output_tooltip(__("We recommend enabling test mode and testing the payment integration before going live.", 'wp-booking-system-stripe'));?>
            </label>
            <label for="payment_stripe_test" class="wpbs-checkbox-switch">
                <input type="hidden" name="wpbs_settings[payment_stripe_test]" value="0">
                <input name="wpbs_settings[payment_stripe_test]" type="checkbox" id="payment_stripe_test"  class="regular-text wpbs-settings-toggle" <?php echo ( !empty( $settings['payment_stripe_test'] ) ) ? 'checked' : '';?> >
                <div class="wpbs-checkbox-slider"></div>
            </label>
        </div>
    </div>

    <!-- Test Client ID -->
    <div class="wpbs-settings-field-wrapper wpbs-settings-field-inline wpbs-settings-field-large">
        <label class="wpbs-settings-field-label" for="payment_stripe_test_api_publishable_key">
            <?php echo __( 'Test Publishable Key', 'wp-booking-system-stripe'); ?>
        </label>

        <div class="wpbs-settings-field-inner">
            <input name="wpbs_stripe_api[payment_stripe_test_api_publishable_key]" type="text" id="payment_stripe_test_api_publishable_key"  class="regular-text " value="<?php echo ( !empty( $stripe_api['payment_stripe_test_api_publishable_key'] ) ) ? $stripe_api['payment_stripe_test_api_publishable_key'] : '';?>" >
        </div>
    </div>
    
    <!-- Test Client Secret -->
    <div class="wpbs-settings-field-wrapper wpbs-settings-field-inline wpbs-settings-field-large">
        <label class="wpbs-settings-field-label" for="payment_stripe_test_api_secret_key">
            <?php echo __( 'Test Secret Key', 'wp-booking-system-stripe'); ?>
        </label>

        <div class="wpbs-settings-field-inner">
            <input name="wpbs_stripe_api[payment_stripe_test_api_secret_key]" type="text" id="payment_stripe_test_api_secret_key"  class="regular-text " value="<?php echo ( !empty( $stripe_api['payment_stripe_test_api_secret_key'] ) ) ? $stripe_api['payment_stripe_test_api_secret_key'] : '';?>" >
        </div>
    </div>

    <!-- Live Client ID -->
    <div class="wpbs-settings-field-wrapper wpbs-settings-field-inline wpbs-settings-field-large">
        <label class="wpbs-settings-field-label" for="payment_stripe_live_api_publishable_key">
            <?php echo __( 'Live Publishable Key', 'wp-booking-system-stripe'); ?>
        </label>

        <div class="wpbs-settings-field-inner">
            <input name="wpbs_stripe_api[payment_stripe_live_api_publishable_key]" type="text" id="payment_stripe_live_api_publishable_key"  class="regular-text " value="<?php echo ( !empty( $stripe_api['payment_stripe_live_api_publishable_key'] ) ) ? $stripe_api['payment_stripe_live_api_publishable_key'] : '';?>" >
        </div>
    </div>
    
    <!-- Live Client Secret -->
    <div class="wpbs-settings-field-wrapper wpbs-settings-field-inline wpbs-settings-field-large">
        <label class="wpbs-settings-field-label" for="payment_stripe_live_api_secret_key">
            <?php echo __( 'Live Secret Key', 'wp-booking-system-stripe'); ?>
        </label>

        <div class="wpbs-settings-field-inner">
            <input name="wpbs_stripe_api[payment_stripe_live_api_secret_key]" type="text" id="payment_stripe_live_api_secret_key"  class="regular-text " value="<?php echo ( !empty( $stripe_api['payment_stripe_live_api_secret_key'] ) ) ? $stripe_api['payment_stripe_live_api_secret_key'] : '';?>" >
        </div>
    </div>


</div>