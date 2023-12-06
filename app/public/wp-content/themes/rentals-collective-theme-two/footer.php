</div>

    <footer role="contentinfo" class="footer">
	    <div class="container">
		    <section class="footer__container  page-section--sm">
			    <div class="footer__container-item">
				    <a href="<?php echo home_url() ?>"><p class="headline headline-3"><?php esc_html_e('The House In The Country', 'rentals_collective_theme_two'); ?></p></a>
				    <p><?php esc_html_e('Experience the authentic charm of a traditional Cretan village home, enhanced with modern amenities for a comfortable stay.', 'rentals_collective_theme'); ?></p>
			    </div>
			    <div class="footer__container-item">
				    <p class="headline headline-3 headline-3--sm"><?php esc_html_e('Contact', 'rentals_collective_theme'); ?></p>
				    <a href="<?php echo esc_url('https://www.google.com/maps?daddr=35.2245333,24.9983580') ?>" target="_blank"><p><?php esc_html_e('A: Sarchos, Heraklion Crete', 'rentals_collective_theme_two'); ?></p></a>
				    <a href="<?php echo esc_url('tel:+306971582503') ?>"><p><?php esc_html_e('M: +30 6971 58 25 03', 'rentals_collective_theme_two'); ?></p></a>
				    <a href="<?php echo esc_url('mailto:info@thehouseinthecountry.com') ?>"><p><?php esc_html_e('E: info@thehouseinthecountry.com', 'rentals_collective_theme_two'); ?></p></a>
				    <div class="footer__container-item-app">
					    <p class="footer__container-item-app-headline"><?php esc_html_e('Apps:', 'rentals_collective_theme_two'); ?></p>
					    <a href="<?php echo esc_url('viber://chat?number=%2B306971582503') ?>"><p><?php echo esc_html('Viber,'); ?></p></a>
					    <a href="<?php echo esc_url('https://t.me/kvstaw4') ?>"><p><?php echo esc_html('Telegram'); ?></p></a>
				    </div>
			    </div>
			    <div class="footer__container-item">
				    <p class="headline headline-3 headline-3--sm"><?php esc_html_e('Links', 'rentals_collective_theme'); ?></p>
				    <div class="footer__container-item-links">
					    <a href="<?php echo esc_url(site_url('/privacy-policy')) ?>"><p><?php esc_html_e('privacy policy', 'rentals_collective_theme_two'); ?></p></a>
					    <a href="<?php echo esc_url(site_url('/terms-conditions')) ?>"><p><?php esc_html_e('terms & conditions', 'rentals_collective_theme_two'); ?></p></a>
					    <a href="<?php echo esc_url(site_url('/disclaimer')) ?>"><p><?php esc_html_e('disclaimer', 'rentals_collective_theme_two'); ?></p></a>
				    </div>
			    </div>
		    </section>
	    </div>
		    <section class="footer__copyright">
			    <div class="container">
				    <div class="footer__copyright-container">
					    <div class="footer__copyright-container-theme">
						    <a href="<?php echo home_url() ?>"><p class="paragraph"><?php esc_html_e('The House In The Country', 'rentals_collective_theme_two'); ?></p></a>
						    <p class="paragraph"><?php echo esc_html('Copyright © ') ?><?php echo date("Y"); ?></p>
					    </div>
					    <div class="footer__copyright-container-rentals">
						    <p class="paragraph"><?php echo esc_html('Managed By ') ?><a href="<?php echo esc_url('https://lodgingscollective.com') ?>" target="_blank"><?php echo esc_html('Lodgings Collective') ?></a></p>
					    </div>
				    </div>
			    </div>
		    </section>
    </footer>

	<?php wp_footer(); ?>
	</body>
</html>