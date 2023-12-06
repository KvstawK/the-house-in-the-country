<?php get_header() ?>

<section class="page-banner">
	<div class="container">
		<?php
		global $post;

		if($post->ID === 1883) :

			echo do_shortcode('[rc_slider id="2035"]');

		elseif ($post->ID === 1884) :

			echo do_shortcode('[rc_slider id="2036"]');

		endif;

		?>
	</div>
</section>

<div class="rooms__rc-rentals-single">
	<div class="container">
		<div class="rooms__rc-rentals-single-container">
			<main role="main" class="rooms__rc-rentals-single-container-info page-section--sm">
				<h1><?php the_title() ?></h1>
				<section class="rooms__rc-rentals-single-container-info-icons">
					<div class="rooms__rc-rentals-single-container-info-icons-item">
						<?php echo wp_get_attachment_image(2016) ?>
						<p><?php echo get_post_meta(get_the_ID(), 'persons', true) ?></p>
					</div>
					<div class="rooms__rc-rentals-single-container-info-icons-item">
						<?php echo wp_get_attachment_image(2017) ?>
						<p><?php echo get_post_meta(get_the_ID(), 'meters', true) ?></p>
					</div>
					<a href="<?php echo esc_url('https://goo.gl/maps/ehVTnyDE1FXHhcW99') ?>" target="_blank"><div class="rooms__rc-rentals-single-container-info-icons-item">
						<?php echo wp_get_attachment_image(2018) ?>
						<p><?php
						$terms = get_term(196, 'rental-location');
						echo $terms->name;
						?>
						</p>
					</div></a>
				</section>
				<section class="rooms__rc-rentals-single-container-info-content">
					<div class="container">
						<p><?php the_content(); ?></p>
					</div>
				</section>
				<section class="rooms__rc-rentals-single-container-related">
					<div class="container">
						<div class="rooms__rc-rentals-single-container-related-container">
							<h2><?php esc_html_e('Related Rooms', 'rentals_collective_theme_two'); ?></h2>

							<?php
							$relatedPostID = $post->ID === 1883 ? 1884 : 1883;

							$singleRoomsQuery = new WP_Query(array(
								'post_type' => 'rc-rentals',
								'p' => $relatedPostID
							));

							if($singleRoomsQuery->have_posts()) : while($singleRoomsQuery->have_posts()) : $singleRoomsQuery->the_post(); ?>
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
			<aside role="complementary" class="rooms__rc-rentals-single-container-calendar page-section--sm">
				<div class="rooms__rc-rentals-single-container-calendar-container">
					<div>

						<?php

						if($post->ID === 1884) :

							echo do_shortcode('[wpbs id="1" title="yes" legend="yes" legend_position="bottom" display="2" year="0" month="0" language="auto" start="1" dropdown="yes" jump="no" history="3" tooltip="1" highlighttoday="yes" weeknumbers="no" show_prices="yes" form_id="1" form_position="bottom" auto_pending="yes" selection_type="multiple" selection_style="split" minimum_days="3" maximum_days="0" booking_start_day="0" booking_end_day="0" show_date_selection="yes"]');

						elseif($post->ID === 1883) :

						echo do_shortcode( '[wpbs id="2" title="yes" legend="yes" legend_position="bottom" display="2" year="0" month="0" language="auto" start="1" dropdown="yes" jump="no" history="3" tooltip="1" highlighttoday="yes" weeknumbers="no" show_prices="yes" form_id="2" form_position="bottom" auto_pending="yes" selection_type="multiple" selection_style="split" minimum_days="3" maximum_days="0" booking_start_day="0" booking_end_day="0" show_date_selection="yes"]');

						endif;

						?>

					</div>
				</div>
			</aside>
		</div>
	</div>
</div>

<?php get_footer() ?>
