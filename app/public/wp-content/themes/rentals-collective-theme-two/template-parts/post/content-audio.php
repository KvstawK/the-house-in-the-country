
<?php
$content = apply_filters('the_content', get_the_content());
$audios = get_media_embedded_in_content($content, array('audio', 'iframe'));
?>

<article <?php post_class('blog page-section'); ?>>

    <div class="blog__single">

        <?php if(get_the_post_thumbnail() !== '' && (empty($audios) || is_single())) { ?>
            <div>
            <?php the_post_thumbnail(); ?>
            </div>
        <?php } ?>
        <?php if(!is_single() && !empty($audios)) { ?>
            <div class="blog__single-audio">
            <?php echo $audios[0]; ?>
            </div>
        <?php } ?>

        <?php get_template_part('template-parts/post/header'); ?>

        <?php get_template_part('template-parts/post/footer'); ?>

    </div>
</article>
