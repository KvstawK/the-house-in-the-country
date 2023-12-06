<?php get_header() ?>

<div class="page-banner">
	<?php echo wp_get_attachment_image(1985, 'full') ?>
	<div class="page-banner__text">
		<h1><?php esc_html_e('The House In The Country', 'rentals_collective_theme_two'); ?></h1>
		<h2><?php esc_html_e('Nearby Area', 'rentals_collective_theme_two'); ?></h2>
	</div>
</div>

<main role="main" class="about">
	<section class="about__excursions page-section--sm">
		<div class="container">
			<div class="about__excursions-container">
				<div class="about__excursions-container-item">
					<div class="about__excursions-container-item-icon">
						<?php echo wp_get_attachment_image(1987, 'large') ?>
					</div>
					<div class="about__excursions-container-item-text">
						<h3><?php esc_html_e('Hiking', 'rentals_collective_theme_two'); ?></h3>
						<p><?php esc_html_e('Visit the trails and the gorge nearby.', 'rentals_collective_theme_two'); ?></p>
					</div>
				</div>
				<div class="about__excursions-container-item">
					<div class="about__excursions-container-item-icon">
						<?php echo wp_get_attachment_image(1986, 'large') ?>
					</div>
					<div class="about__excursions-container-item-text">
						<h3><?php esc_html_e('Climbing', 'rentals_collective_theme_two'); ?></h3>
						<p><?php esc_html_e('Explore the magnificent mountains.', 'rentals_collective_theme_two'); ?></p>
					</div>
				</div>
				<div class="about__excursions-container-item">
					<div class="about__excursions-container-item-icon">
						<?php echo wp_get_attachment_image(1988, 'large') ?>
					</div>
					<div class="about__excursions-container-item-text">
						<h3><?php esc_html_e('Walking', 'rentals_collective_theme_two'); ?></h3>
						<p><?php esc_html_e('Walk around the beautiful nature.', 'rentals_collective_theme_two'); ?></p>
					</div>
				</div>
				<div class="about__excursions-container-item">
					<div class="about__excursions-container-item-icon">
						<?php echo wp_get_attachment_image(1989, 'large') ?>
					</div>
					<div class="about__excursions-container-item-text">
						<h3><?php esc_html_e('Orienteering', 'rentals_collective_theme_two'); ?></h3>
						<p><?php esc_html_e('Walk around exploring the local area.', 'rentals_collective_theme_two'); ?></p>
					</div>
				</div>
			</div>
		</div>
	</section>
	<section class="about__trails page-section--sm">
		<div class="container">
			<div class="about__trails-container">
				<div class="about__trails-container-text">
					<div class="about__trails-container-text-headlines">
						<a href="<?php echo esc_url('https://www.alltrails.com/greece/crete/kroussonas') ?>" target="_blank"><h3><span>*</span><?php esc_html_e(' Hike The Trails Nearby', 'rentals_collective_theme_two'); ?></h3></a>
						<a href="<?php echo esc_url('https://www.cretanbeaches.com/en/religious-monuments-on-crete/inactive-monasteries-and-hermitages/inactive-monasteries-at-malevizi/panagia-kera-church-at-sarchos') ?>" target="_blank"><h3><span>*</span><?php esc_html_e(' Visit Byzantine churches', 'rentals_collective_theme_two'); ?></h3></a>
						<a href="<?php echo esc_url('https://www.incrediblecrete.gr/en/place/sarchos-cave/') ?>" target="_blank"><h3><span>*</span><?php esc_html_e(' Explore Majestic Caves', 'rentals_collective_theme_two'); ?></h3></a>
					</div>
					<div class="about__trails-container-text-info">
						<p><?php esc_html_e('Explore the neighboring area by visiting the three links provided above, which offer insights into lesser-known attractions and landmarks. Discover off-the-beaten-path experiences and the hidden gems that make this region truly unique.', 'rentals_collective_theme_two'); ?></p>
					</div>
				</div>
				<div class="about__trails-container-images">
					<?php echo wp_get_attachment_image(1990, 'large') ?>
					<?php echo wp_get_attachment_image(1991, 'large') ?>
				</div>
			</div>
		</div>
	</section>
	<section class="about__offers page-section--sm">
		<div class="about__offers-container">
			<div class="about__offers-container-item">
				<div class="about__offers-container-item-text">
					<h3><?php esc_html_e('The Folklore Museum of Ano Asites', 'rentals_collective_theme_two'); ?></h3>
					<p><?php esc_html_e('Operating since 1995, the museum in Ano Asites is housed in a traditional 1880 Cretan building. The ground floor features a warehouse and kitchen, while the upper floor contains a bedroom and living room.', 'rentals_collective_theme_two'); ?></p>
					<a href="<?php echo esc_url('https://anoasites.weebly.com/things-to-do.html') ?>" target="_blank"><button class="btn btn--sm"><?php esc_html_e('Read More', 'rentals_collective_theme_two'); ?></button></a>
				</div>
				<div class="about__offers-container-item-image">
					<a href="<?php echo esc_url('https://anoasites.weebly.com/things-to-do.html') ?>" target="_blank"><?php echo wp_get_attachment_image(2003, 'full') ?></a>
				</div>
			</div>
			<div class="about__offers-container-item">
				<div class="about__offers-container-item-text">
					<h3><?php esc_html_e('Thirathen Musical Museum', 'rentals_collective_theme_two'); ?></h3>
					<p><?php esc_html_e('In Kroussonas village, the "THIRATHEN" Museum is situated at Agia Triada square. Housed in a renovated traditional building, it showcases Byzantine Era classical education, known as "thirathen pedia."', 'rentals_collective_theme_two'); ?></p>
					<a href="<?php echo esc_url('https://www.thirathen.com/en') ?>" target="_blank"><button class="btn btn--sm"><?php esc_html_e('Read More', 'rentals_collective_theme_two'); ?></button></a>
				</div>
				<div class="about__offers-container-item-image">
					<a href="<?php echo esc_url('https://www.thirathen.com/en') ?>" target="_blank"><?php echo wp_get_attachment_image(2002, 'full') ?></a>
				</div>
			</div>
		</div>
	</section>
</main>

<?php get_footer() ?>
