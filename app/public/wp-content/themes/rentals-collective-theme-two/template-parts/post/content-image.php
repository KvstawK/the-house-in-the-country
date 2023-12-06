<article <?php post_class('blog page-section'); ?>>

    <div class="blog__single">

        <div class="blog__single-excerpt">
            <?php the_content(); ?>
        </div>

        <div class="blog__single-meta">
            <?php rentals_collective_theme_post_meta(); ?>
        </div>

        <?php if(is_single()) { ?>
            <?php get_template_part('template-parts/post/footer'); ?>
        <?php } ?>
    </div>
</article>
