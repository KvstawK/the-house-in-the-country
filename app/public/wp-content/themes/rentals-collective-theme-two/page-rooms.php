<?php get_header() ?>

<section class="page-banner">
	<?php echo wp_get_attachment_image(1926, 'full') ?>
	<div class="page-banner__text">
		<h1><?php esc_html_e('The House In The Country', 'rentals_collective_theme_two'); ?></h1>
		<h2><?php esc_html_e('Our Rooms', 'rentals_collective_theme_two'); ?></h2>
	</div>
</section>

<main role="main" class="rooms">
	<section class="rooms__search page-section--sm">
		<div class="container">
			<div class="rooms__search-shortcode">

				<?php echo do_shortcode('[wpbs-search calendars="all" language="auto" start_day="1" title="yes" mark_selection="yes" selection_type="multiple" minimum_stay="0" featured_image="yes" starting_price="yes" results_layout="list" results_per_page="10" redirect=""]') ?>

			</div>
		</div>
	</section>
	<section class="rooms__single page-section--sm">
		<div class="container">
			<div class="rooms__single-container">

				<?php
				$roomsQuery = new WP_Query(array(
					'post_type' => 'rc-rentals'
				));
				if($roomsQuery->have_posts()) : while($roomsQuery->have_posts()) : $roomsQuery->the_post(); ?>

					<div class="rooms__single-container-item">
						<div class="rooms__single-container-item-image">
							<a href="<?php the_permalink(); ?>"><?php the_post_thumbnail(); ?></a>
						</div>
						<div class="rooms__single-container-item-text">
							<p class="paragraph--fs-lg"><?php esc_html_e('From €' ,'rentals_collective_theme'); ?><?php echo get_post_meta( $post->ID, 'price', true ); ?></p>
							<h3><a href="<?php the_permalink(); ?>"><?php the_title() ?></a></h3>
							<p><?php the_excerpt(); ?></p>
							<div class="rooms__single-container-item-text-amenities">
								<?php echo wp_get_attachment_image(2007) ?>
								<?php echo wp_get_attachment_image(2008) ?>
								<?php echo wp_get_attachment_image(2009) ?>
								<?php echo wp_get_attachment_image(2015) ?>
								<?php echo wp_get_attachment_image(2011) ?>
								<?php echo wp_get_attachment_image(2012) ?>
								<?php echo wp_get_attachment_image(2013) ?>
								<?php echo wp_get_attachment_image(2014) ?>
							</div>
							<a href="<?php the_permalink(); ?>"><button class="btn"><?php esc_html_e('Make A Booking', 'rentals_collective_theme'); ?></button></a>
						</div>
					</div>

				<?php endwhile; endif; wp_reset_postdata(); ?>

			</div>
		</div>
	</section>
</main>

<?php get_footer() ?>
