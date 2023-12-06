<article <?php post_class('blog'); ?>>
    <div class="blog__container-posts-image">

        <a href="<?php the_permalink(); ?>"><?php echo (has_post_thumbnail()) ? get_the_post_thumbnail() : wp_get_attachment_image(1871, 'full') ?></a>

    </div>
    <div class="blog__container-posts-date"><time datetime="<?php echo esc_attr(get_the_date('d/m/Y')); ?>"><p><?php echo get_the_date('d/m/Y'); ?></p></time></div>
    <div class="blog__container-posts-content">
        <p class="paragraph--uppercase">
            <?php
                $categories = get_the_category();
                if ( ! empty( $categories ) ) {
                $main_category = $categories[0];
                echo esc_html( $main_category->name );
                }
            ?>
        </p>
        <h3 class="headline-3--font-size-sm"><a href="<?php the_permalink(); ?>"><?php the_title() ?></a></h3>
        <?php rentals_collective_theme_two_readmore_link() ?>
    </div>
</article>


