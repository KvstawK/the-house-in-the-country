<?php
if(is_single()) : ?>
    <div title="<?php esc_attr_e('Back To Top', 'lodgings_collective_theme'); ?>" class="blog__single-container-content-arrow"><a href="#contents">
            <?php echo wp_get_attachment_image(2300, 'full'); ?>
        </a></div>
    <div class="blog__single-container-content-headline">
        <p class="paragraph--uppercase">
            <?php
            $categories = get_the_category();
            if ( ! empty( $categories ) ) {
                $main_category = $categories[0];
                echo esc_html( $main_category->name );
            }
            ?>
        </p>
        <h1 class="headline-3--font-size-sm"><?php the_title() ?></h1>
    </div>

    <article>

        <div class="blog__single-container-content-article">
            <?php
            the_content();
            wp_link_pages();
            ?>
        </div>

    </article>

<?php
else :
    get_template_part('template-parts/page/content');
endif;
?>
