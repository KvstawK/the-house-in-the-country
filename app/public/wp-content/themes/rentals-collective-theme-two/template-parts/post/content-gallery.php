<?php
$blocks =  parse_blocks(get_the_content());
$gallery = false;
foreach ($blocks as $block) {
    if($block['blockName'] === 'core/gallery') {
        $gallery = $block;
        break;
    }
}
?>

<article <?php post_class('blog page-section'); ?>>

    <div class="blog__single">

        <?php if(get_the_post_thumbnail() !== '' && (!$gallery || is_single())) { ?>
            <div>
            <?php the_post_thumbnail(); ?>
            </div>
        <?php } ?>
        <?php if(!is_single() && $gallery) { ?>
            <div class="blog__single-gutenberg">
            <?php
            echo $gallery['innerHTML'];
            ?>
            </div>
        <?php } ?>

        <?php get_template_part('template-parts/post/header'); ?>

        <div class="blog__single-content"><?php the_content(); wp_link_pages(); ?></div>

        <?php get_template_part('template-parts/post/footer'); ?>

    </div>
</article>
