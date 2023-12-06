<?php
$prev = get_previous_post();
$next = get_next_post();
?>

<?php if($prev || $next) : ?>
    <nav class="blog__single-container-content-navigation page-section" role="navigation">
        <h2 class="screen-reader-text"><?php esc_html_e('Post Navigation', 'rentals_collective_theme'); ?></h2>
        <div class="blog__single-container-content-navigation-links <?php echo (!$next || !$prev) ? 'single' : '' ?>">
    <?php if($prev) : ?>
    <div class="blog__single-container-content-navigation-links-post blog__single-container-content-navigation-links-post--prev">
        <a href="<?php the_permalink($prev->ID) ?>">
            <p class="paragraph--uppercase"><?php echo wp_get_attachment_image(2045) ?><?php esc_html_e('Previous Post', 'rentals_collective_theme_two'); ?></p><span></span>
        </a>
    </div>
    <?php endif; ?>
    <?php if($next) : ?>
                <a class="blog__single-container-content-navigation-links-post blog__single-container-content-navigation-links-post--next" href="<?php the_permalink($next->ID) ?>">
                    <p class="paragraph--uppercase"><?php esc_html_e('Next Post', 'rentals_collective_theme_two'); ?><?php echo wp_get_attachment_image(1851) ?></p><span></span>
                </a>
    <?php endif; ?>
        </div>
    </nav>
<?php endif; ?>
