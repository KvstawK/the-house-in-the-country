<?php get_header() ?>

<main role="main" class="home">
	<section class="home__slider">
		<div class="home__slider-container">

			<?php echo do_shortcode('[rc_slider id="1867"]') ?>

		</div>
	</section>
	<section class="home__map">
		<div class="home__map-image">

			<?php echo wp_get_attachment_image(1880, 'full') ?>

		</div>
		<div class="container">
			<div class="home__map-text">
				<div class="home__map-text-image">

					<?php echo wp_get_attachment_image(1878, 'full') ?>

				</div>
				<div class="home__map-text-content">
					<h2><?php esc_html_e('Experience nature, enjoy outdoor activities, quality accommodation and excellent food! Book & visit our traditional Cretan house.', 'rentals_collective_theme_two'); ?></h2>
					<a href="<?php echo esc_url(site_url('/rooms')) ?>"><button class="btn"><?php esc_html_e('Make a booking', 'rentals_collective_theme_two'); ?></button></a>
				</div>
			</div>
		</div>
	</section>
	<section class="home__rooms page-section--sm">
		<div class="container">
			<h2><?php esc_html_e('Ideal For A Getaway', 'rentals_collective_theme_two'); ?></h2>
			<div class="home__rooms-container">

				<?php
				$homepageRentals = new WP_Query(array(
					'post_type' => 'rc-rentals'
				));

				if($homepageRentals->have_posts()) : while ($homepageRentals->have_posts()) : $homepageRentals->the_post();
				?>

				<div class="home__rooms-container-item">
					<a href="<?php the_permalink(); ?>"><div class="home__rooms-container-item-image">
						<?php the_post_thumbnail(); ?>
					</div></a>
					<div class="home__rooms-container-item-content">
						<div title="<?php esc_attr_e('Number Of Persons', 'rentals_collective_theme_two'); ?>" aria-label="<?php esc_attr_e('Number Of Persons', 'rentals_collective_theme_two'); ?>" class="home__rooms-container-item-content-persons">
							<?php echo wp_get_attachment_image(1885) ?>
							<?php
							if ( get_post_meta( get_the_ID(), 'persons', true ) ) :
								echo get_post_meta( get_the_ID(), 'persons', true );
							endif;
							?>
						</div>
						<div class="home__rooms-container-item-content-info">
							<a href="<?php the_permalink(); ?>"><h3><?php the_title() ?></h3></a>

							<?php
							global $post;

							if($post->ID === 1884) : ?>

							<p><?php esc_html_e('The Stream View Room offers a cozy 40 m² space with a private kitchen, bathroom, and picturesque mountain & stream views.', 'rentals_collective_theme_two') ?></p>

							<?php elseif ($post->ID === 1883) : ?>

							<p><?php esc_html_e('The spacious 80 m² Garden View Room offers a comfortable retreat with a private kitchen, bathroom, and beautiful views.', 'rentals_collective_theme_two') ?></p>

							<?php endif; ?>

							<div class="home__rooms-container-item-content-info-amenities">
								<div class="home__rooms-container-item-content-info-amenities-icons">
									<?php echo wp_get_attachment_image(1888) ?>
									<?php echo wp_get_attachment_image(1889) ?>
									<?php echo wp_get_attachment_image(1890) ?>
									<?php echo wp_get_attachment_image(1891) ?>
									<?php echo wp_get_attachment_image(1892) ?>
								</div>
								<a href="<?php the_permalink(); ?>"><button class="home__rooms-container-item-content-info-amenities-book btn btn--book">
										<?php esc_html_e('Book the room', 'rentals_collective_theme_two'); ?>
										<?php echo wp_get_attachment_image(1893) ?></button></a>
							</div>
						</div>
					</div>
				</div>

				<?php endwhile; endif; ?>

			</div>
		</div>
	</section>
	<section class="home__booking page-section--sm">
		<div class="container">
			<h3><?php esc_html_e('Make A Booking', 'rentals_collective_theme_two'); ?></h3>
			<div class="home__booking-container">
				<div class="home__booking-container-search">
					<?php echo do_shortcode('[wpbs-search calendars="all" language="auto" start_day="1" title="yes" mark_selection="yes" selection_type="multiple" minimum_stay="0" featured_image="yes" starting_price="yes" results_layout="list" results_per_page="10" redirect=""]') ?>
				</div>
				<div class="home__booking-container-images">
					<?php echo wp_get_attachment_image(1895, 'large') ?>
					<?php echo wp_get_attachment_image(1899, 'large') ?>
					<?php echo wp_get_attachment_image(1896, 'large') ?>
					<?php echo wp_get_attachment_image(1900, 'large') ?>
					<?php echo wp_get_attachment_image(1897, 'large') ?>
					<?php echo wp_get_attachment_image(1898, 'large') ?>
				</div>
			</div>
		</div>
	</section>
	<section class="home__house">
		<div class="home__house-photo">
			<?php echo wp_get_attachment_image(1871, 'full') ?>
		</div>
		<div class="home__house-info">
			<h2><?php esc_html_e('Traditional Cretan House', 'rentals_collective_theme_two'); ?></h2>
			<p><?php esc_html_e('Immerse yourself in the authentic atmosphere of a Cretan traditional home, nestled within a charming mountain village. Embrace the quintessential Cretan lifestyle as you reside in our beautifully preserved heritage abode.', 'rentals_collective_theme_two'); ?></p>
			<div class="home__house-info-list">
				<h3>* <?php esc_html_e('Zoo, showcasing indigenous species.', 'rentals_collective_theme_two'); ?></h3>
				<h3>* <?php esc_html_e('Garden, with native trees & flowers.', 'rentals_collective_theme_two'); ?></h3>
			</div>
			<div class="home__house-info-amenities">
				<?php echo wp_get_attachment_image(1901) ?>
				<?php echo wp_get_attachment_image(1888) ?>
				<?php echo wp_get_attachment_image(1891) ?>
				<?php echo wp_get_attachment_image(1902) ?>
				<?php echo wp_get_attachment_image(1903) ?>
				<?php echo wp_get_attachment_image(1890) ?>
				<?php echo wp_get_attachment_image(1904) ?>
			</div>
		</div>
	</section>

	<section class="home__gallery">
		<div class="home__gallery-title">
			<h3><?php esc_html_e('Our Gallery', 'rentals_collective_theme_two'); ?></h3>
		</div>
		<div class="home__gallery-shortcode">

			<?php echo do_shortcode('[rc_slider id="1905"]') ?>
			<button class="home__gallery-shortcode-button btn"><?php esc_html_e('See More Images', 'rentals_collective_theme_two'); ?></button>

		</div>
	</section>
	<section class="home__reviews">

		<?php echo do_shortcode('[rc_slider id="1965"]') ?>

	</section>
	<section class="home__parallax">
		<div class="home__parallax-bg"></div>
	</section>
	<section class="home__newsletter page-section--sm">
		<div class="container">
			<div class="home__newsletter-container">
				<div class="home__newsletter-container-images">
					<?php echo wp_get_attachment_image(1970, 'large') ?>
					<?php echo wp_get_attachment_image(1972, 'large') ?>
				</div>
				<div class="home__newsletter-container-text">
					<h2><?php esc_html_e('Our Newsletter', 'rentals_collective_theme_two'); ?></h2>
					<p><?php esc_html_e('Sign up for our newsletter to stay informed about our latest news and updates!'); ?></p>
					<?php echo do_shortcode('[lc_newsletter_shortcode]') ?>
				</div>
			</div>
		</div>
	</section>
</main>

<?php get_footer() ?>
