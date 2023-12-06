<?php
global $post;
$args = array(
	'post_type' => 'rc-slider',
	'post_status' => 'publish',
	'post__in' => $id,
	'orderby' => 'orderby'
);

$rc_slider_query = new WP_Query($args);

if ($rc_slider_query->have_posts()) : while ($rc_slider_query->have_posts()) :
	$rc_slider_query->the_post();

	?>

<div tabindex="0" id="rc-carousel-<?php echo esc_attr($post->post_name); ?>" aria-roledescription="region" aria-label="<?php echo esc_attr__('Highlighted images of the slider', 'rc-slider'); ?>" class="rc-carousel rc-carousel-<?php echo esc_attr($post->post_name); ?> <?php echo (get_post_meta(get_the_ID(), 'image-appear-disappear-slider', true) ? esc_attr('rc-carousel-image-appear-disappear-type') : (get_post_meta(get_the_ID(), 'gallery', true) ? esc_attr('rc-carousel-gallery') : esc_attr('rc-slider-horizontal-scroll-type'))) ?>
      <?php echo ((get_post_meta(get_the_ID(), 'gallery', true)) ? '' : (get_post_meta(get_the_ID(), '2-slide-carousel', true) ? esc_attr('rc-2-slide-carousel') : (get_post_meta(get_the_ID(), '3-slide-carousel', true) ? esc_attr('rc-3-slide-carousel') : esc_attr('1-slide-carousel')))) ?>
     <?php echo (get_post_meta(get_the_ID(), 'gallery', true) ? esc_attr('rc-gallery') : esc_attr('no-gallery')) ?> <?php echo (get_post_meta(get_the_ID(), 'lightbox', true) ? esc_attr('rc-lightbox') : esc_attr('no-lightbox')) ?> <?php echo (get_post_meta(get_the_ID(), 'arrows', true) ? esc_attr('with-arrows') : esc_attr('no-arrows')) ?> <?php echo (get_post_meta(get_the_ID(), 'auto-play', true) ? esc_attr('with-auto-play') : esc_attr('no-auto-play')) ?>">

	<?php if(get_post_meta( get_the_ID(), 'dots', true ) || get_post_meta( get_the_ID(), 'dots-carousel', true ) || get_post_meta( get_the_ID(), '', true ) || get_post_meta( get_the_ID(), '2-slide-carousel', true ) || get_post_meta( get_the_ID(), '3-slide-carousel', true )) : ?>

	<?php if (get_post_meta( get_the_ID(), 'dots', true )) : ?>

        <div tabindex="0" id="<?php echo $post->post_name; ?>-dots" role="tablist" class="rc-carousel__nav rc-carousel__nav-dots" aria-label="Slides">

			<?php
			$id = get_the_ID();
			$banner_img = get_post_meta($id, 'images', true);
			$banner_img = explode(',', $banner_img);

			if(!empty($banner_img)) :
				for ($i = 0; $i < count($banner_img); $i += (get_post_meta(get_the_ID(), '2-slide-carousel', true) ? 2 : 1)) : ?>

                    <div tabindex="0" role="tab" class="rc-carousel__nav-dot" aria-selected="false"></div>

				<?php endfor; endif; ?>

        </div>

	<?php endif; ?>


	<?php if (get_post_meta( get_the_ID(), 'dots-carousel', true )) : ?>

        <button type="button" class="dots-slider-btn dots-slider-btn-left low-opacity" aria-label="<?php esc_html_e('previous slides button', 'rc-slider'); ?>" aria-controls="<?php echo $post->post_name; ?>-dots-carousel"><?php echo wp_get_attachment_image(169, 'full') ?></button><span class="overflow-left"></span>
        <div tabindex="0" id="<?php echo $post->post_name; ?>-dots-carousel" role="tablist" class="rc-carousel__nav rc-carousel__nav-dots dots-slider" aria-label="Slides">

			<?php

			$id = get_the_ID();
			$banner_img = get_post_meta($id, 'images', true);
			$banner_img = explode(',', $banner_img);

			if(!empty($banner_img)) :
				foreach ($banner_img as $attachment_id) : ?>

                    <div tabindex="0" role="tab" class="rc-carousel__nav-dot dot-carousel" aria-selected="false"><?php echo wp_get_attachment_image( $attachment_id );?></div>

				<?php endforeach; endif; ?>

        </div>
        <button type="button" class="dots-slider-btn dots-slider-btn-right" aria-label="<?php esc_html_e('next slides button', 'rc-slider'); ?>" aria-controls="<?php echo $post->post_name; ?>-dots-carousel"><?php echo wp_get_attachment_image(1962, 'full') ?></button><span class="overflow-right"></span>

	<?php endif; ?>

	<?php if (get_post_meta( get_the_ID(), 'arrows', true )) : ?>

        <button type="button" class="rc-carousel__btn rc-carousel__btn-left hide" aria-label="<?php esc_html_e('previous slide button', 'rc-slider'); ?>" aria-controls="<?php echo $post->post_name; ?>-carousel-items"><?php echo wp_get_attachment_image(1962, 'full') ?></button>

	<?php endif; ?>

    <div class="rc-carousel__container" >
<div id="<?php echo $post->post_name; ?>-carousel-items" class="rc-carousel__container-sliders" aria-live="polite">

	<?php
	// Use below code to show metabox values from anywhere

	$id = get_the_ID();
	$banner_img = get_post_meta($id, 'images', true);
	$banner_img = explode(',', $banner_img);

	if(!empty($banner_img) && empty(get_post_meta( get_the_ID(), '2-slide-carousel', true )) && empty(get_post_meta( get_the_ID(), '3-slide-carousel', true ))) :
		foreach ($banner_img as $attachment_id) :
			$alt_text = get_post_meta($attachment_id, '_wp_attachment_image_alt', true);
			?>

            <div role="tabpanel" class="rc-carousel__container-sliders-slide" aria-roledescription="slide"  >

            <div class="rc-carousel__container-sliders-slide-image">

				<?php
				$attr = array(
					'data-thumbnail' => wp_get_attachment_image_url($attachment_id, 'thumbnail'),
					'data-medium' => wp_get_attachment_image_url($attachment_id, 'medium'),
					'data-large' => wp_get_attachment_image_url($attachment_id, 'large'),
					'data-full' => wp_get_attachment_image_url($attachment_id, 'full'),
				);
				?>

				<?php if (get_post_meta( get_the_ID(), 'image-link', true )) : ?>

                    <a href="<?php echo get_post_meta($attachment_id, 'text_field', true) ?>">
						<?php
						$thumbnail = get_post_meta(get_the_ID(), 'thumbnail', true);
						$medium = get_post_meta(get_the_ID(), 'medium', true);
						$large = get_post_meta(get_the_ID(), 'large', true);
						$full = get_post_meta(get_the_ID(), 'full', true);

						$size = ($thumbnail ? 'thumbnail' : ($medium ? 'medium' : ($large ? 'large' : ($full ? 'full' : ''))));

						echo ($size ? wp_get_attachment_image($attachment_id, $size, false, $attr) : wp_get_attachment_image($attachment_id, 'full', false, $attr));
						?>
                    </a>

				<?php else: ?>

					<?php
					$thumbnail = get_post_meta(get_the_ID(), 'thumbnail', true);
					$medium = get_post_meta(get_the_ID(), 'medium', true);
					$large = get_post_meta(get_the_ID(), 'large', true);
					$full = get_post_meta(get_the_ID(), 'full', true);

					$size = ($thumbnail ? 'thumbnail' : ($medium ? 'medium' : ($large ? 'large' : ($full ? 'full' : ''))));

					echo ($size ? wp_get_attachment_image($attachment_id, $size, false, $attr) : wp_get_attachment_image($attachment_id, 'full', false, $attr));
					?>

				<?php endif; ?>

            </div>

			<?php if (get_post_meta( get_the_ID(), 'content', true )) : ?>

            <div tabindex="0" class="rc-carousel__container-sliders-slide-content">
            <span></span>
			<?php
			$title_tag = get_post_meta($attachment_id, 'title_tag', true);
			$subtitle_tag = get_post_meta($attachment_id, 'subtitle_tag', true);
			$content_tag = get_post_meta($attachment_id, 'content_tag', true);
			$button_text_tag = get_post_meta($attachment_id, 'button_text_tag', true);

			$title_text = get_post_meta($attachment_id, 'images_title', true);
			$subtitle_text = get_post_meta($attachment_id, 'images_subtitle', true);
			$content_text = get_post_meta($attachment_id, 'images_content', true);
			$button_url = get_post_meta($attachment_id, 'images_button_url', true);
			$button_text = get_post_meta($attachment_id, 'images_button_text', true);
			?>

            <<?php echo $title_tag; ?> class="rc-carousel__container-sliders-slide-content-title"><?php echo $title_text; ?></<?php echo $title_tag; ?>>
            <<?php echo $subtitle_tag; ?> class="rc-carousel__container-sliders-slide-content-caption"><?php echo $subtitle_text; ?></<?php echo $subtitle_tag; ?>>
            <<?php echo $content_tag; ?> class="rc-carousel__container-sliders-slide-content-description"><?php echo $content_text; ?></<?php echo $content_tag; ?>>
        <a class="rc-carousel__container-sliders-slide-content-link" href="<?php echo $button_url ?>"><<?php echo $button_text_tag; ?>><?php echo $button_text ?></<?php echo $button_text_tag; ?>></a>
            </div>

		<?php endif; ?>

            </div>

		<?php endforeach;

    elseif (get_post_meta( get_the_ID(), '2-slide-carousel', true ) && get_post_meta( get_the_ID(), 'content', true )) :
		foreach (array_chunk($banner_img, 2) as $group) :
			?>

        <div role="tabpanel" class="rc-carousel__container-sliders-slide  <?php echo (get_post_meta( get_the_ID(), '2-slide-carousel', true ) ? 'two-slide-carousel' : '') ?>" aria-roledescription="slide">

            <div class="rc-carousel__container-sliders-slide-item two-slide-carousel-first-item">
            <div class="rc-carousel__container-sliders-slide-item-image two-slide-carousel-first-image">

				<?php if (get_post_meta( get_the_ID(), 'image-link', true )) : ?>

                    <a href="<?php echo get_post_meta($group[0], 'text_field', true) ?>">

						<?php
						$thumbnail = get_post_meta(get_the_ID(), 'thumbnail', true);
						$medium = get_post_meta(get_the_ID(), 'medium', true);
						$large = get_post_meta(get_the_ID(), 'large', true);
						$full = get_post_meta(get_the_ID(), 'full', true);

						$size = ($thumbnail ? 'thumbnail' : ($medium ? 'medium' : ($large ? 'large' : ($full ? 'full' : ''))));

						echo ($size ? wp_get_attachment_image((int)$group[0], $size) : wp_get_attachment_image((int)$group[0], 'full')); ?>

                    </a>

				<?php else: ?>

					<?php
					$thumbnail = get_post_meta(get_the_ID(), 'thumbnail', true);
					$medium = get_post_meta(get_the_ID(), 'medium', true);
					$large = get_post_meta(get_the_ID(), 'large', true);
					$full = get_post_meta(get_the_ID(), 'full', true);

					$size = ($thumbnail ? 'thumbnail' : ($medium ? 'medium' : ($large ? 'large' : ($full ? 'full' : ''))));

					echo ($size ? wp_get_attachment_image((int)$group[0], $size) : wp_get_attachment_image((int)$group[0], 'full')); ?>

				<?php endif; ?>

            </div>

			<?php if (get_post_meta( get_the_ID(), 'content', true )) : ?>

            <div tabindex="0" class="rc-carousel__container-sliders-slide-item-content two-slide-carousel-first-image-content">
            <span></span>
			<?php
			$attachment_id = $group[0];
			$title_tag = get_post_meta($attachment_id, 'title_tag', true);
			$subtitle_tag = get_post_meta($attachment_id, 'subtitle_tag', true);
			$content_tag = get_post_meta($attachment_id, 'content_tag', true);
			$button_text_tag = get_post_meta($attachment_id, 'button_text_tag', true);

			$title_text = get_post_meta($attachment_id, 'images_title', true);
			$subtitle_text = get_post_meta($attachment_id, 'images_subtitle', true);
			$content_text = get_post_meta($attachment_id, 'images_content', true);
			$button_url = get_post_meta($attachment_id, 'images_button_url', true);
			$button_text = get_post_meta($attachment_id, 'images_button_text', true);
			?>

            <<?php echo $title_tag; ?> class="rc-carousel__container-sliders-slide-content-title"><?php echo $title_text; ?></<?php echo $title_tag; ?>>
            <<?php echo $subtitle_tag; ?> class="rc-carousel__container-sliders-slide-content-caption"><?php echo $subtitle_text; ?></<?php echo $subtitle_tag; ?>>
            <<?php echo $content_tag; ?> class="rc-carousel__container-sliders-slide-content-description"><?php echo $content_text; ?></<?php echo $content_tag; ?>>
        <a class="rc-carousel__container-sliders-slide-content-link" href="<?php echo $button_url ?>"><<?php echo $button_text_tag; ?>><?php echo $button_text ?></<?php echo $button_text_tag; ?>></a>
            </div>

		<?php endif; ?>

            </div>
            <div class="rc-carousel__container-sliders-slide-item two-slide-carousel-second-item">

                <div class="rc-carousel__container-sliders-slide-item-image two-slide-carousel-second-image">

					<?php if (get_post_meta( get_the_ID(), 'image-link', true )) : ?>

                        <a href="<?php echo get_post_meta($group[1], 'text_field', true) ?>">

							<?php
							$thumbnail = get_post_meta(get_the_ID(), 'thumbnail', true);
							$medium = get_post_meta(get_the_ID(), 'medium', true);
							$large = get_post_meta(get_the_ID(), 'large', true);
							$full = get_post_meta(get_the_ID(), 'full', true);

							$size = ($thumbnail ? 'thumbnail' : ($medium ? 'medium' : ($large ? 'large' : ($full ? 'full' : ''))));

							echo ($size ? wp_get_attachment_image((int)$group[1], $size) : wp_get_attachment_image((int)$group[1], 'full')); ?>

                        </a>

					<?php else: ?>

						<?php
						$thumbnail = get_post_meta(get_the_ID(), 'thumbnail', true);
						$medium = get_post_meta(get_the_ID(), 'medium', true);
						$large = get_post_meta(get_the_ID(), 'large', true);
						$full = get_post_meta(get_the_ID(), 'full', true);

						$size = ($thumbnail ? 'thumbnail' : ($medium ? 'medium' : ($large ? 'large' : ($full ? 'full' : ''))));

						echo ($size ? wp_get_attachment_image((int)$group[1], $size) : wp_get_attachment_image((int)$group[1], 'full')); ?>

					<?php endif; ?>

                </div>

				<?php if (get_post_meta( get_the_ID(), 'content', true )) : ?>

                    <div tabindex="0" class="rc-carousel__container-sliders-slide-item-content two-slide-carousel-second-image-content">
                        <span></span>
						<?php
						$attachment_id = $group[1];
						$title_tag = get_post_meta($attachment_id, 'title_tag', true);
						$subtitle_tag = get_post_meta($attachment_id, 'subtitle_tag', true);
						$content_tag = get_post_meta($attachment_id, 'content_tag', true);
						$button_text_tag = get_post_meta($attachment_id, 'button_text_tag', true);

						$title_text = get_post_meta($attachment_id, 'images_title', true);
						$subtitle_text = get_post_meta($attachment_id, 'images_subtitle', true);
						$content_text = get_post_meta($attachment_id, 'images_content', true);
						$button_url = get_post_meta($attachment_id, 'images_button_url', true);
						$button_text = get_post_meta($attachment_id, 'images_button_text', true);
						?>

						<?php echo '<' . $title_tag . ' class="rc-carousel__container-sliders-slide-content-title">'; ?><?php echo $title_text; ?><?php echo '</' . $title_tag . '>'; ?>
						<?php echo '<' . $subtitle_tag . ' class="rc-carousel__container-sliders-slide-content-caption">'; ?><?php echo $subtitle_text; ?><?php echo '</' . $subtitle_tag . '>'; ?>
						<?php echo '<' . $content_tag . ' class="rc-carousel__container-sliders-slide-content-description">'; ?><?php echo $content_text; ?><?php echo '</' . $content_tag . '>'; ?>
                        <a class="rc-carousel__container-sliders-slide-content-link" href="<?php echo $button_url ?>"><<?php echo $button_text_tag; ?>><?php echo $button_text ?></<?php echo $button_text_tag; ?>></a>
                    </div>

				<?php endif; ?>

            </div>

            </div>

		<?php endforeach; ?>

	<?php elseif (get_post_meta( get_the_ID(), '2-slide-carousel', true )) :
		foreach (array_chunk($banner_img, 2) as $group) :
			?>

            <div role="tabpanel" class="rc-carousel__container-sliders-slide  <?php echo (get_post_meta( get_the_ID(), '2-slide-carousel', true ) ? 'two-slide-carousel' : '') ?>" aria-roledescription="slide">

                <div class="rc-carousel__container-sliders-slide-item two-slide-carousel-first-item">
                    <div class="rc-carousel__container-sliders-slide-item-image two-slide-carousel-first-image">

						<?php if (get_post_meta( get_the_ID(), 'image-link', true )) : ?>

                            <a href="<?php echo get_post_meta($group[0], 'text_field', true) ?>">

								<?php
								$thumbnail = get_post_meta(get_the_ID(), 'thumbnail', true);
								$medium = get_post_meta(get_the_ID(), 'medium', true);
								$large = get_post_meta(get_the_ID(), 'large', true);
								$full = get_post_meta(get_the_ID(), 'full', true);

								$size = ($thumbnail ? 'thumbnail' : ($medium ? 'medium' : ($large ? 'large' : ($full ? 'full' : ''))));

								echo ($size ? wp_get_attachment_image((int)$group[0], $size) : wp_get_attachment_image((int)$group[0], 'full')); ?>

                            </a>

						<?php else: ?>

							<?php
							$thumbnail = get_post_meta(get_the_ID(), 'thumbnail', true);
							$medium = get_post_meta(get_the_ID(), 'medium', true);
							$large = get_post_meta(get_the_ID(), 'large', true);
							$full = get_post_meta(get_the_ID(), 'full', true);

							$size = ($thumbnail ? 'thumbnail' : ($medium ? 'medium' : ($large ? 'large' : ($full ? 'full' : ''))));

							echo ($size ? wp_get_attachment_image((int)$group[0], $size) : wp_get_attachment_image((int)$group[0], 'full')); ?>

						<?php endif; ?>

                    </div>

                </div>
                <div class="rc-carousel__container-sliders-slide-item two-slide-carousel-second-item">

                    <div class="rc-carousel__container-sliders-slide-item-image two-slide-carousel-second-image">

						<?php if (get_post_meta( get_the_ID(), 'image-link', true )) : ?>

                            <a href="<?php echo get_post_meta($group[1], 'text_field', true) ?>">

								<?php
								$thumbnail = get_post_meta(get_the_ID(), 'thumbnail', true);
								$medium = get_post_meta(get_the_ID(), 'medium', true);
								$large = get_post_meta(get_the_ID(), 'large', true);
								$full = get_post_meta(get_the_ID(), 'full', true);

								$size = ($thumbnail ? 'thumbnail' : ($medium ? 'medium' : ($large ? 'large' : ($full ? 'full' : ''))));

								echo ($size ? wp_get_attachment_image((int)$group[1], $size) : wp_get_attachment_image((int)$group[1], 'full')); ?>

                            </a>

						<?php else: ?>

							<?php
							$thumbnail = get_post_meta(get_the_ID(), 'thumbnail', true);
							$medium = get_post_meta(get_the_ID(), 'medium', true);
							$large = get_post_meta(get_the_ID(), 'large', true);
							$full = get_post_meta(get_the_ID(), 'full', true);

							$size = ($thumbnail ? 'thumbnail' : ($medium ? 'medium' : ($large ? 'large' : ($full ? 'full' : ''))));

							echo ($size ? wp_get_attachment_image((int)$group[1], $size) : wp_get_attachment_image((int)$group[1], 'full')); ?>

						<?php endif; ?>

                    </div>

                </div>

            </div>

		<?php endforeach; ?>

	<?php elseif (get_post_meta( get_the_ID(), '3-slide-carousel', true ) && get_post_meta( get_the_ID(), 'content', true )) :
		foreach (array_chunk($banner_img, 3) as $group) :
			?>

            <div role="tabpanel" class="rc-carousel__container-sliders-slide" aria-roledescription="slide">

                <div class="rc-carousel__container-sliders-slide-image <?php echo (get_post_meta( get_the_ID(), '3-slide-carousel', true ) ? 'three-slide-carousel' : '') ?>">

					<?php
					$thumbnail = get_post_meta(get_the_ID(), 'thumbnail', true);
					$medium = get_post_meta(get_the_ID(), 'medium', true);
					$large = get_post_meta(get_the_ID(), 'large', true);
					$full = get_post_meta(get_the_ID(), 'full', true);

					$size = ($thumbnail ? 'thumbnail' : ($medium ? 'medium' : ($large ? 'large' : ($full ? 'full' : ''))));

					echo ($size ? wp_get_attachment_image((int)$group[0], $size) : wp_get_attachment_image((int)$group[0], 'full')); ?>
					<?php
					$thumbnail = get_post_meta(get_the_ID(), 'thumbnail', true);
					$medium = get_post_meta(get_the_ID(), 'medium', true);
					$large = get_post_meta(get_the_ID(), 'large', true);
					$full = get_post_meta(get_the_ID(), 'full', true);

					$size = ($thumbnail ? 'thumbnail' : ($medium ? 'medium' : ($large ? 'large' : ($full ? 'full' : ''))));

					echo ($size ? wp_get_attachment_image((int)$group[1], $size) : wp_get_attachment_image((int)$group[1], 'full')); ?>
					<?php
					$thumbnail = get_post_meta(get_the_ID(), 'thumbnail', true);
					$medium = get_post_meta(get_the_ID(), 'medium', true);
					$large = get_post_meta(get_the_ID(), 'large', true);
					$full = get_post_meta(get_the_ID(), 'full', true);

					$size = ($thumbnail ? 'thumbnail' : ($medium ? 'medium' : ($large ? 'large' : ($full ? 'full' : ''))));

					echo ($size ? wp_get_attachment_image((int)$group[2], $size) : wp_get_attachment_image((int)$group[2], 'full')); ?>

                </div>

                <div tabindex="0" class="rc-carousel__container-sliders-slide-content three-slide-carousel-first-image-content">
                    <span></span>
                    <p class="rc-carousel__container-sliders-slide-content-title"><?php echo get_the_title($group[0]) ?></p>
                    <p class="rc-carousel__container-sliders-slide-content-caption"><?php echo wp_get_attachment_caption($group[0]); ?></p>
                    <p class="rc-carousel__container-sliders-slide-content-description"><?php echo wp_get_attachment($group[0])['description']  ?></p>
                    <a class="rc-carousel__container-sliders-slide-content-link" href="<?php echo get_post_meta($group[0], 'text_field', true) ?>"><p><?php echo get_the_title($group[0]); ?></p></a>
                </div>
                <div tabindex="0" class="rc-carousel__container-sliders-slide-content three-slide-carousel-second-image-content">
                    <span></span>
                    <p class="rc-carousel__container-sliders-slide-content-title"><?php echo get_the_title($group[1]) ?></p>
                    <p class="rc-carousel__container-sliders-slide-content-caption"><?php echo wp_get_attachment_caption($group[1]); ?></p>
                    <p class="rc-carousel__container-sliders-slide-content-description"><?php echo wp_get_attachment($group[1])['description']  ?></p>
                    <a class="rc-carousel__container-sliders-slide-content-link" href="<?php echo get_post_meta($group[1], 'text_field', true) ?>"><p><?php echo get_the_title($group[1]); ?></p></a>
                </div>
                <div tabindex="0" class="rc-carousel__container-sliders-slide-content three-slide-carousel-third-image-content">
                    <span></span>
                    <p class="rc-carousel__container-sliders-slide-content-title"><?php echo get_the_title($group[2]) ?></p>
                    <p class="rc-carousel__container-sliders-slide-content-caption"><?php echo wp_get_attachment_caption($group[2]); ?></p>
                    <p class="rc-carousel__container-sliders-slide-content-description"><?php echo wp_get_attachment($group[2])['description']  ?></p>
                    <a class="rc-carousel__container-sliders-slide-content-link" href="<?php echo get_post_meta($group[2], 'text_field', true) ?>"><p><?php echo get_the_title($group[2]); ?></p></a>
                </div>

            </div>

		<?php endforeach; ?>

	<?php elseif (get_post_meta( get_the_ID(), '3-slide-carousel', true )) :
		foreach (array_chunk($banner_img, 3) as $group) :
			?>

            <div role="tabpanel" class="rc-carousel__container-sliders-slide" aria-roledescription="slide">

                <div class="rc-carousel__container-sliders-slide-image <?php echo (get_post_meta( get_the_ID(), '3-slide-carousel', true ) ? 'three-slide-carousel' : '') ?>">

					<?php
					$thumbnail = get_post_meta(get_the_ID(), 'thumbnail', true);
					$medium = get_post_meta(get_the_ID(), 'medium', true);
					$large = get_post_meta(get_the_ID(), 'large', true);
					$full = get_post_meta(get_the_ID(), 'full', true);

					$size = ($thumbnail ? 'thumbnail' : ($medium ? 'medium' : ($large ? 'large' : ($full ? 'full' : ''))));

					echo ($size ? wp_get_attachment_image((int)$group[0], $size) : wp_get_attachment_image((int)$group[0], 'full')); ?>
					<?php
					$thumbnail = get_post_meta(get_the_ID(), 'thumbnail', true);
					$medium = get_post_meta(get_the_ID(), 'medium', true);
					$large = get_post_meta(get_the_ID(), 'large', true);
					$full = get_post_meta(get_the_ID(), 'full', true);

					$size = ($thumbnail ? 'thumbnail' : ($medium ? 'medium' : ($large ? 'large' : ($full ? 'full' : ''))));

					echo ($size ? wp_get_attachment_image((int)$group[1], $size) : wp_get_attachment_image((int)$group[1], 'full')); ?>
					<?php
					$thumbnail = get_post_meta(get_the_ID(), 'thumbnail', true);
					$medium = get_post_meta(get_the_ID(), 'medium', true);
					$large = get_post_meta(get_the_ID(), 'large', true);
					$full = get_post_meta(get_the_ID(), 'full', true);

					$size = ($thumbnail ? 'thumbnail' : ($medium ? 'medium' : ($large ? 'large' : ($full ? 'full' : ''))));

					echo ($size ? wp_get_attachment_image((int)$group[2], $size) : wp_get_attachment_image((int)$group[2], 'full')); ?>

                </div>

            </div>

		<?php endforeach; endif; ?>

    </div>

    </div>

	<?php if (get_post_meta( get_the_ID(), 'arrows', true )) : ?>

        <button type="button" class="rc-carousel__btn rc-carousel__btn-right" aria-label="<?php esc_html_e('next slide button', 'rc-slider'); ?>" aria-controls="<?php echo $post->post_name; ?>-carousel-items"><?php echo wp_get_attachment_image(1963, 'full') ?></button>

	<?php endif; endif; ?>

    </div>

<?php endwhile; endif; wp_reset_postdata(); ?>
