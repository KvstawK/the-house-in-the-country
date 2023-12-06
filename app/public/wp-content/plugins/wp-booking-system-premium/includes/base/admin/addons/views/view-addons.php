<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

?>

<div class="wrap wpbs-wrap">

	<!-- Page Heading -->
	<h1 class="wp-heading-inline"><?php echo __( 'Add-ons', 'wp-booking-system' ); ?></h1>
	<hr class="wp-header-end" />

    <?php if(empty($subscription_type)): ?>
        <!-- No Licence Notice -->
        <div class="wpbs-page-notice notice-error wpbs-form-changed-notice"> 
            <p><?php echo __( 'Add-ons are only available with a valid licence key.', 'wp-booking-system' ); ?></p>
        </div>
    <?php endif ?>

	<div class="wpbs-addons-wrap">

        <?php foreach($addons as $addon): ?>

            <div class="wpbs-addon-wrap">
                <div class="wpbs-addon">
                    <img src="<?php echo $addon['image'] ?>" alt="<?php echo $addon['name'] ?>">

                    <div class="wpbs-addon-inner">
                        <div class="wpbs-addon-inner-text-wrap">
                            <h2><?php echo $addon['name'] ?></h2>
                            <p><?php echo $addon['description'] ?></p>
                        </div>
                        
                        <?php if(!empty($subscription_type)): ?>

                            <?php if(!array_key_exists($subscription_type, $addon['plans'])): ?>
                                <p>Only available with the <?php echo implode(' or ',$addon['plans']);?> licence. <a href="https://www.wpbookingsystem.com/documentation/upgrading-your-license/" target="_blank"><?php echo __('Upgrade your licence', 'wp-booking-system') ?></a></p>
                            <?php elseif(file_exists(ABSPATH . 'wp-content/plugins/' . $addon['slug'] . '/index.php')): ?>
                                <?php if(is_plugin_active($addon['slug'] . '/index.php')): ?>
                                    <a class="button button-primary button-disabled"><?php echo __('Installed', 'wp-booking-system') ?></a>
                                <?php else: ?>
                                    <a class="button button-primary" href="<?php echo wp_nonce_url(add_query_arg(array('page' => 'wpbs-addons', 'wpbs_action' => 'activate_addon', 'addon' => $addon['slug']), admin_url('admin.php')), 'wpbs_activate_addon', 'wpbs_token');?>"><?php echo __('Activate', 'wp-booking-system') ?></a>
                                <?php endif; ?>
                            <?php else: ?>
                                <a class="button button-primary wpbs-addon-button-install" href="<?php echo wp_nonce_url(add_query_arg(array('page' => 'wpbs-addons', 'wpbs_action' => 'install_addon', 'addon' => $addon['slug']), admin_url('admin.php')), 'wpbs_install_addon', 'wpbs_token');?>"><?php echo __('Install', 'wp-booking-system') ?></a>
                            <?php endif; ?>

                        <?php endif; ?>
                    </div>
                </div>
            </div>

        <?php endforeach; ?>

    </div>

</div>