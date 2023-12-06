<!doctype html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<a href="#content" class="skip-link"><?php esc_html_e('Skip to content', 'rentals_collective_theme_two'); ?></a>
<header role="banner" id="site-header" class="header">

    <div class="header__container">
        <div class="header__container-left">
            <div class="header__container-left-logo" title="<?php esc_attr_e('Home', 'rentals_collective_theme_two'); ?>">
                <?php if (has_custom_logo()) : the_custom_logo();
                else : ?>
                    <a href="<?php echo esc_url(home_url('/')); ?>"><?php esc_html(bloginfo('name')); ?></a>
                <?php endif; ?>
            </div>
            <button title="<?php esc_attr_e('Menu', 'rentals_collective_theme_two'); ?>" class="header__container-left-menu" aria-controls="<?php esc_attr_e('primary-nav', 'rentals_collective_theme_two'); ?>" aria-expanded="false"><span></span></button>
            <nav id="primary-nav" role="navigation" class="header__container-left-links" data-visible="false" aria-label="<?php esc_attr_e('Main navigation', 'rentals_collective_theme_two'); ?>">
                <?php
                wp_nav_menu(
                    array(
                        'theme_location' => 'main-menu',
                        'after' => '<span></span>'
                    )
                )
                ?>
            </nav>
        </div>
        <div class="header__container-right">
            <a href="<?php echo esc_url(site_url('/rooms')) ?>" class="header__container-right-book"><?php esc_html_e('Book A Room', 'rentals_collective_theme_two'); ?><span></span></a>
            <button title="<?php esc_attr_e('Info', 'rentals_collective_theme_two'); ?>" class="header__container-right-menu" aria-controls="<?php esc_attr_e('info-panel', 'rentals_collective_theme_two'); ?>" aria-expanded="false"><span></span></button>
            <div id="info-panel" class="header__container-right-info" data-visible="false" aria-label="<?php esc_attr_e('House\'s info panel', 'rentals_collective_theme_two'); ?>">
                <p class="headline-3 header__container-right-info-headline"><?php esc_html_e('The House In The Country', 'rentals_collective_theme_two'); ?></p>
                <p class="paragraph paragraph--fs-lg header__container-right-info-text"><?php esc_html_e('An original traditional Crete\'s country house.', 'rentals_collective_theme_two'); ?></p>
                <a href="<?php echo esc_url('mailto:info@thehouseinthecountry.com') ?>" class="header__container-right-info-email"><p class="paragraph paragraph--fs-lg"><?php echo esc_html('info@houseinthecountry.com') ?></p><?php echo wp_get_attachment_image(1852) ?></a>
                <div class="header__container-right-info-contact">
                    <ul>
                        <li><a href="<?php echo esc_url('https://www.google.com/maps?daddr=35.2245333,24.9983580') ?>" target="_blank"><p><?php esc_html_e('A: Sarchos, Heraklion Crete', 'rentals_collective_theme_two'); ?></p></a></li>
                        <li><a href="<?php echo esc_url('tel:+306971582503') ?>"><p><?php esc_html_e('M: +30 6971 58 25 03', 'rentals_collective_theme_two'); ?></p></a></li>
                        <li>
                            <div class="header__container-right-info-contact-app">
                                <p class="header__container-right-info-contact-app-headline"><?php esc_html_e('Apps:', 'rentals_collective_theme_two'); ?></p>
                                <a href="<?php echo esc_url('viber://chat?number=%2B306971582503') ?>"><p><?php echo esc_html('Viber,'); ?></p></a>
                                <a href="<?php echo esc_url('https://t.me/kvstaw4') ?>"><p><?php echo esc_html('Telegram'); ?></p></a>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</header>

<div id="content">
