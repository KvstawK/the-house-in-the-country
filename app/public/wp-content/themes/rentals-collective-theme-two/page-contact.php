<?php get_header() ?>

<section class="page-banner">
	<?php echo wp_get_attachment_image(1927, 'full') ?>
	<div class="page-banner__text">
		<h1><?php esc_html_e('The House In The Country', 'rentals_collective_theme_two'); ?></h1>
		<h2><?php esc_html_e('Contact Us', 'rentals_collective_theme_two'); ?></h2>
	</div>
</section>

<main role="main" class="contact">
	<section class="contact__info page-section--sm">
		<div class="container">
			<div class="contact__info-container">
				<div class="contact__info-container-text">
					<h2><?php esc_html_e('Please contact us for everything needed.', 'rentals_collective_theme_two'); ?></h2>
					<p><?php esc_html_e('Contact us through our contact information below or send us a message by filling out our form to the right.', 'rentals_collective_theme_two'); ?></p>
					<h3><?php esc_html_e('Contact Info', 'rentals_collective_theme_two'); ?></h3>
					<a href="<?php echo esc_url('https://www.google.com/maps?daddr=35.2245333,24.9983580') ?>" target="_blank"><p><?php esc_html_e('A: Sarchos, Heraklion Crete', 'rentals_collective_theme_two'); ?></p></a>
					<a href="<?php echo esc_url('tel:+306971582503') ?>"><p><?php esc_html_e('M: +30 6971 58 25 03', 'rentals_collective_theme_two'); ?></p></a>
					<a href="<?php echo esc_url('mailto:info@thehouseinthecountry.com') ?>"><p><?php esc_html_e('E: info@thehouseinthecountry.com', 'rentals_collective_theme_two'); ?></p></a>
					<div class="contact__info-container-text-apps">
						<p class="contact__info-container-text-apps-headline"><?php esc_html_e('Apps:', 'rentals_collective_theme_two'); ?></p>
						<a href="<?php echo esc_url('viber://chat?number=%2B306971582503') ?>"><p><?php echo esc_html('Viber,'); ?></p></a>
						<a href="<?php echo esc_url('https://t.me/kvstaw4') ?>"><p><?php echo esc_html('Telegram'); ?></p></a>
					</div>
				</div>
				<div class="contact__info-container-form">

					<?php echo do_shortcode('[lc_contact_form]') ?>

				</div>
			</div>
		</div>
	</section>
    <section class="contact__map">
        <div class="contact__map-container">
            <iframe class="contact__map-container-frame" width="425" height="350" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="<?php echo esc_url('https://www.openstreetmap.org/export/embed.html?bbox=24.996913969516758%2C35.22366052299608%2C25.000454485416412%2C35.225560197251106&amp;layer=mapnik'); ?>" style="border: 1px solid black"></iframe>
            <small><a href="<?php echo esc_url('https://www.openstreetmap.org/#map=19/35.22461/24.99868&amp;layers=D'); ?>" target="_blank"><?php esc_html_e('View OpenStreetMap', 'rentals_collective_theme_two'); ?></a></small>
            <div class="contact__map-container-navigate">
                <div class="contact__map-container-navigate-button paragraph--uppercase"><?php esc_html_e('Navigate to Place', 'rentals_collective_theme_two'); ?></div>
                <div class="contact__map-container-navigate-options" id="navigationOptions" style="display: none;">
                    <a href="<?php echo esc_url('https://www.google.com/maps?daddr=35.2245333,24.9983580'); ?>" target="_blank"><p><?php esc_html_e('Google Maps', 'rentals_collective_theme_two'); ?></p></a>
                    <a href="<?php echo esc_url('http://maps.apple.com/?daddr=35.2245333,24.9983580'); ?>" target="_blank"><p><?php esc_html_e('Apple Maps', 'rentals_collective_theme_two'); ?></p></a>
                    <a href="<?php echo esc_url('https://waze.com/ul?ll=35.2245333,24.9983580&navigate=yes'); ?>" target="_blank"><p><?php esc_html_e('Waze', 'rentals_collective_theme_two'); ?></p></a>
                    <a href="<?php echo esc_url('https://wego.here.com/directions/mix//35.2245333,24.9983580'); ?>" target="_blank"><p><?php esc_html_e('HERE WeGo', 'rentals_collective_theme_two'); ?></p></a>
                </div>
            </div>
        </div>
    </section>
</main>

<?php get_footer() ?>
