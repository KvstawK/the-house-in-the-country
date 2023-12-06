<?php get_header() ?>

<section class="page-banner">
	<?php echo wp_get_attachment_image(1906, 'full') ?>
	<div class="page-banner__text">
		<h1><?php esc_html_e('The House In The Country', 'rentals_collective_theme_two'); ?></h1>
		<h2><?php esc_html_e('Our Blog', 'rentals_collective_theme_two'); ?></h2>
	</div>
</section>

<div class="blog page-section--sm">
	<div class="container">
		<div class="blog__container">

			<?php

			if(is_active_sidebar('primary-sidebar')) {
				get_sidebar();
			}

			?>

			<main role="main" class="blog__container-posts">

				<?php

				get_template_part('loop');

				?>

			</main>
		</div>
	</div>
</div>

<?php get_footer() ?>
