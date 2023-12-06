<?php get_header() ?>

<div role="main" class="blog__single">
    <div class="container">
        <div class="blog__single-thumbnail">
		    <?php
		    if(has_post_thumbnail()) {
			    echo get_the_post_thumbnail();
			    if(get_the_ID() == 2314) {
				    echo '<div class="blog__single-thumbnail-caption">' . esc_html__('Panagia Kera in Sarchos, source:', 'rentals_collective_theme_two') . ' <a href="' . esc_url('https://commons.wikimedia.org/wiki/File:%CE%A0%CE%B1%CE%BD%CE%B1%CE%B3%CE%AF%CE%B1_%CE%9A%CE%B5%CF%81%CE%AC_%CE%A3%CE%AC%CF%81%CF%87%CE%BF%CF%85_3791.jpg') . '" target="_blank">' . esc_html__('Wikimedia Commons', 'rentals_collective_theme_two') . '</a></div>';
			    }
		    } else {
			    echo wp_get_attachment_image(1871, 'full');
		    }
		    ?>
        </div>

        <div class="blog__single-container page-section--sm">

            <main role="main" class="blog__single-container-content">

                <?php

                get_template_part('loop', 'single')

                ?>

            </main>

	        <?php

	        if(is_active_sidebar('primary-sidebar')) {
		        get_sidebar();
	        }

	        ?>

        </div>
    </div>
</div>

<?php get_footer() ?>

