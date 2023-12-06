<?php
$content = apply_filters('the_content', get_the_content());
$videos = get_media_embedded_in_content($content, array('video', 'object', 'embed', 'iframe'));
?>

<article <?php post_class('blog page-section'); ?>>

    <div class="blog__single">

        <?php if(get_the_post_thumbnail() !== '' && (empty($videos) || is_single())) { ?>
            <div>
            <?php the_post_thumbnail(); ?>
            </div>
        <?php } ?>
        <?php if(!is_single() && !empty($videos)) { ?>
            <div class="c-post__video">
            <?php if(strpos($videos[0], '<iframe') !== false) { ?>
                <div class="u-responsive-video">
            <?php } ?>
            <?php echo $videos[0]; ?>
            <?php if(strpos($videos[0], '<iframe') !== false) { ?>
                </div>
            <?php } ?>
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
