
<div class="rooms__rc-rentals-single-container-info-content-amenities">
    <div class="rooms__rc-rentals-single-container-info-content-amenities-container">
        <?php
        $essentials = get_post_meta( get_the_ID(), 'essentials', true );
        if ($essentials == 1) : ?>
            <div title="<?php esc_html_e("Essentials (Towels, bed sheets, soap, toilet paper, and pillows)", 'rc-rentals'); ?>" class="rooms__rc-rentals-single-container-info-content-amenities-container-item">
                <?php echo wp_get_attachment_image(2023, 'full'); ?>
                <p class="paragraph--black"><?php esc_html_e('Essentials (Towels, bed sheets, soap, toilet paper, and pillows)', 'rc-rentals'); ?></p>
            </div>
        <?php endif; ?>
        <?php if (get_post_meta( get_the_ID(), 'gym', true )) : ?>
            <div title="<?php esc_html_e("Gym", 'rc-rentals'); ?>" class="rooms__rc-rentals-single-container-info-content-amenities-container-item">
                <?php echo wp_get_attachment_image(1955, 'full'); ?>
                <p class="paragraph--black"><?php esc_html_e('Gym', 'rc-rentals'); ?></p>
            </div>
        <?php endif; ?>
        <?php if (get_post_meta( get_the_ID(), 'pool', true )) : ?>
            <div title="<?php esc_html_e("Swimming-pool", 'rc-rentals'); ?>" class="rooms__rc-rentals-single-container-info-content-amenities-container-item">
                <?php echo wp_get_attachment_image(1938, 'full'); ?>
                <p class="paragraph--black"><?php esc_html_e('Private Swimming-pool', 'rc-rentals'); ?></p>
            </div>
        <?php endif; ?>
        <?php if (get_post_meta( get_the_ID(), 'kids', true )) : ?>
            <div title="<?php esc_html_e("Kids Friendly", 'rc-rentals'); ?>" class="rooms__rc-rentals-single-container-info-content-amenities-container-item">
                <?php echo wp_get_attachment_image(2024, 'full'); ?>
                <p class="paragraph--black"><?php esc_html_e('Kids Friendly', 'rc-rentals'); ?></p>
            </div>
        <?php endif; ?>
        <?php if (get_post_meta( get_the_ID(), 'jacuzzi', true )) : ?>
        <div title="<?php esc_html_e("Jacuzzi", 'rc-rentals'); ?>" class="rooms__rc-rentals-single-container-info-content-amenities-container-item">
            <?php echo wp_get_attachment_image(1956, 'full'); ?>
            <p class="paragraph--black"><?php esc_html_e('Jacuzzi', 'rc-rentals'); ?></p>
        </div>
        <?php endif; ?>
        <?php if (get_post_meta( get_the_ID(), 'ac', true )) : ?>
            <div title="<?php esc_html_e("Air Conditioning", 'rc-rentals'); ?>" class="rooms__rc-rentals-single-container-info-content-amenities-container-item">
                <?php echo wp_get_attachment_image(2007, 'full'); ?>
                <p class="paragraph--black"><?php esc_html_e('Air Conditioning', 'rc-rentals'); ?></p>
            </div>
        <?php endif; ?>
        <?php if (get_post_meta( get_the_ID(), 'cleaning-products', true )) : ?>
            <div title="<?php esc_html_e("Cleaning Products", 'rc-rentals'); ?>" class="rooms__rc-rentals-single-container-info-content-amenities-container-item">
                <?php echo wp_get_attachment_image(2025, 'full'); ?>
                <p class="paragraph--black"><?php esc_html_e('Cleaning Products', 'rc-rentals'); ?></p>
            </div>
        <?php endif; ?>
        <?php if (get_post_meta( get_the_ID(), 'cooking-basics', true )) : ?>
            <div title="<?php esc_html_e("Cooking Basics (Pots and pans, oil, salt and pepper)", 'rc-rentals'); ?>" class="rooms__rc-rentals-single-container-info-content-amenities-container-item">
                <?php echo wp_get_attachment_image(2026, 'full'); ?>
                <p class="paragraph--black"><?php esc_html_e('Cooking Basics (Pots and pans, oil, salt and pepper)', 'rc-rentals'); ?></p>
            </div>
        <?php endif; ?>
        <?php if (get_post_meta( get_the_ID(), 'workspace', true )) : ?>
            <div title="<?php esc_html_e("Dedicated workspace", 'rc-rentals'); ?>" class="rooms__rc-rentals-single-container-info-content-amenities-container-item">
                <?php echo wp_get_attachment_image(1958, 'full'); ?>
                <p class="paragraph--black"><?php esc_html_e('Dedicated workspace', 'rc-rentals'); ?></p>
            </div>
        <?php endif; ?>
        <?php if (get_post_meta( get_the_ID(), 'dishes', true )) : ?>
            <div title="<?php esc_html_e("Dishes & Utensils", 'rc-rentals'); ?>" class="rooms__rc-rentals-single-container-info-content-amenities-container-item">
                <?php echo wp_get_attachment_image(2027, 'full'); ?>
                <p class="paragraph--black"><?php esc_html_e('Dishes & Utensils', 'rc-rentals'); ?></p>
            </div>
        <?php endif; ?>
        <?php if (get_post_meta( get_the_ID(), 'dryer', true )) : ?>
            <div title="<?php esc_html_e("Clothes Dryer", 'rc-rentals'); ?>" class="rooms__rc-rentals-single-container-info-content-amenities-container-item">
                <?php echo wp_get_attachment_image(1976, 'full'); ?>
                <p class="paragraph--black"><?php esc_html_e('Clothes Dryer', 'rc-rentals'); ?></p>
            </div>
        <?php endif; ?>
        <?php if (get_post_meta( get_the_ID(), 'washing-machine', true )) : ?>
            <div title="<?php esc_html_e("Washing Machine", 'rc-rentals'); ?>" class="rooms__rc-rentals-single-container-info-content-amenities-container-item">
                <?php echo wp_get_attachment_image(2013, 'full'); ?>
                <p class="paragraph--black"><?php esc_html_e('Washing Machine', 'rc-rentals'); ?></p>
            </div>
        <?php endif; ?>
        <?php if (get_post_meta( get_the_ID(), 'hair-dryer', true )) : ?>
            <div title="<?php esc_html_e("Hair Dryer", 'rc-rentals'); ?>" class="rooms__rc-rentals-single-container-info-content-amenities-container-item">
                <?php echo wp_get_attachment_image(2011, 'full'); ?>
                <p class="paragraph--black"><?php esc_html_e('Hair Dryer', 'rc-rentals'); ?></p>
            </div>
        <?php endif; ?>
        <?php if (get_post_meta( get_the_ID(), 'heating', true )) : ?>
            <div title="<?php esc_html_e("Heating", 'rc-rentals'); ?>" class="rooms__rc-rentals-single-container-info-content-amenities-container-item">
                <?php echo wp_get_attachment_image(1960, 'full'); ?>
                <p class="paragraph--black"><?php esc_html_e('Heating', 'rc-rentals'); ?></p>
            </div>
        <?php endif; ?>
        <?php if (get_post_meta( get_the_ID(), 'kitchen', true )) : ?>
            <div title="<?php esc_html_e("Kitchen", 'rc-rentals'); ?>" class="rooms__rc-rentals-single-container-info-content-amenities-container-item">
                <?php echo wp_get_attachment_image(2028, 'full'); ?>
                <p class="paragraph--black"><?php esc_html_e('Kitchen', 'rc-rentals'); ?></p>
            </div>
        <?php endif; ?>
        <?php if (get_post_meta( get_the_ID(), 'tv', true )) : ?>
            <div title="<?php esc_html_e("TV", 'rc-rentals'); ?>" class="rooms__rc-rentals-single-container-info-content-amenities-container-item">
                <?php echo wp_get_attachment_image(2015, 'full'); ?>
                <p class="paragraph--black"><?php esc_html_e('TV', 'rc-rentals'); ?></p>
            </div>
        <?php endif; ?>
        <?php if (get_post_meta( get_the_ID(), 'dishwasher', true )) : ?>
            <div title="<?php esc_html_e("Dishwasher", 'rc-rentals'); ?>" class="rooms__rc-rentals-single-container-info-content-amenities-container-item">
                <?php echo wp_get_attachment_image(1979, 'full'); ?>
                <p class="paragraph--black"><?php esc_html_e('Dishwasher', 'rc-rentals'); ?></p>
            </div>
        <?php endif; ?>
        <?php if (get_post_meta( get_the_ID(), 'wifi', true )) : ?>
            <div title="<?php esc_html_e("WiFi", 'rc-rentals'); ?>" class="rooms__rc-rentals-single-container-info-content-amenities-container-item">
                <?php echo wp_get_attachment_image(2014, 'full'); ?>
                <p class="paragraph--black"><?php esc_html_e('WiFi', 'rc-rentals'); ?></p>
            </div>
        <?php endif; ?>
        <?php if (get_post_meta( get_the_ID(), 'bathtub', true )) : ?>
            <div title="<?php esc_html_e("Bathtub", 'rc-rentals'); ?>" class="rooms__rc-rentals-single-container-info-content-amenities-container-item">
                <?php echo wp_get_attachment_image(1903, 'full'); ?>
                <p class="paragraph--black"><?php esc_html_e('Bathtub', 'rc-rentals'); ?></p>
            </div>
        <?php endif; ?>
        <?php if (get_post_meta( get_the_ID(), 'shower', true )) : ?>
            <div title="<?php esc_html_e("Shower", 'rc-rentals'); ?>" class="rooms__rc-rentals-single-container-info-content-amenities-container-item">
                <?php echo wp_get_attachment_image(2012, 'full'); ?>
                <p class="paragraph--black"><?php esc_html_e('Shower', 'rc-rentals'); ?></p>
            </div>
        <?php endif; ?>
        <?php if (get_post_meta( get_the_ID(), 'shampoo', true )) : ?>
            <div title="<?php esc_html_e("Shampoo", 'rc-rentals'); ?>" class="rooms__rc-rentals-single-container-info-content-amenities-container-item">
                <?php echo wp_get_attachment_image(2029, 'full'); ?>
                <p class="paragraph--black"><?php esc_html_e('Shampoo', 'rc-rentals'); ?></p>
            </div>
        <?php endif; ?>
        <?php if (get_post_meta( get_the_ID(), 'hangers', true )) : ?>
            <div title="<?php esc_html_e("Hangers", 'rc-rentals'); ?>" class="rooms__rc-rentals-single-container-info-content-amenities-container-item">
                <?php echo wp_get_attachment_image(2030, 'full'); ?>
                <p class="paragraph--black"><?php esc_html_e('Hangers', 'rc-rentals'); ?></p>
            </div>
        <?php endif; ?>
        <?php if (get_post_meta( get_the_ID(), 'iron', true )) : ?>
            <div title="<?php esc_html_e("Iron", 'rc-rentals'); ?>" class="rooms__rc-rentals-single-container-info-content-amenities-container-item">
                <?php echo wp_get_attachment_image(2031, 'full'); ?>
                <p class="paragraph--black"><?php esc_html_e('Iron', 'rc-rentals'); ?></p>
            </div>
        <?php endif; ?>
        <?php if (get_post_meta( get_the_ID(), 'safe-box', true )) : ?>
            <div title="<?php esc_html_e("Safe Box", 'rc-rentals'); ?>" class="rooms__rc-rentals-single-container-info-content-amenities-container-item">
                <?php echo wp_get_attachment_image(1968, 'full'); ?>
                <p class="paragraph--black"><?php esc_html_e('Safe Box', 'rc-rentals'); ?></p>
            </div>
        <?php endif; ?>
    </div>
</div>