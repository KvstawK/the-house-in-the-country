<article <?php post_class('blog page-section'); ?>>

    <div class="blog__single">

        <?php if(get_the_post_thumbnail() !== '' && (!get_post_gallery() || is_single())) { ?>
            <div>
            <?php the_post_thumbnail('large'); ?>
            </div>
        <?php } ?>
        <?php if(!is_single() && get_post_gallery()) { ?>
            <div>
            <?php
            $gallery = get_post_gallery(get_the_ID(), false);
            $gallery = explode(',', $gallery['ids']);
            foreach( $gallery as $id) {
                echo wp_get_attachment_image($id, 'large');
            }
            ?>
            </div>
        <?php } ?>

        <?php get_template_part('template-parts/post/header'); ?>

        <?php if(is_single()) { ?>
            <div class="blog__single-content">
            <?php the_content();
            wp_link_pages();
            ?>
            </div>
        <?php } else { ?>
            <div class="blog__single-excerpt">
            <?php the_excerpt(); ?>
            </div>
        <?php } ?>

        <?php if(is_single()) { ?>
            <?php get_template_part('template-parts/post/footer'); ?>
        <?php } ?>

    </div>
</article>
