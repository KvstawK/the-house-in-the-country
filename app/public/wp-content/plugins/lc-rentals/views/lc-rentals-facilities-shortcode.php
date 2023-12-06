
<div class="rooms__lc-rentals-single-container-info-content-amenities">
    <div class="rooms__lc-rentals-single-container-info-content-amenities-container">
        <?php
        $essentials = get_post_meta( get_the_ID(), 'essentials', true );
        if ($essentials == 1) : ?>
            <div title="<?php esc_html_e("Essentials (Towels, bed sheets, soap, toilet paper, and pillows)", 'lc-rentals'); ?>" class="rooms__lc-rentals-single-container-info-content-amenities-container-item">
                <?php echo wp_get_attachment_image(2023, 'full'); ?>
                <p class="paragraph--black"><?php esc_html_e('Essentials (Towels, bed sheets, soap, toilet paper, and pillows)', 'lc-rentals'); ?></p>
            </div>
        <?php endif; ?>
        <?php if (get_post_meta( get_the_ID(), 'gym', true )) : ?>
            <div title="<?php esc_html_e("Gym", 'lc-rentals'); ?>" class="rooms__lc-rentals-single-container-info-content-amenities-container-item">
                <?php echo wp_get_attachment_image(1955, 'full'); ?>
                <p class="paragraph--black"><?php esc_html_e('Gym', 'lc-rentals'); ?></p>
            </div>
        <?php endif; ?>
        <?php if (get_post_meta( get_the_ID(), 'pool', true )) : ?>
            <div title="<?php esc_html_e("Swimming-pool", 'lc-rentals'); ?>" class="rooms__lc-rentals-single-container-info-content-amenities-container-item">
                <?php echo wp_get_attachment_image(1938, 'full'); ?>
                <p class="paragraph--black"><?php esc_html_e('Private Swimming-pool', 'lc-rentals'); ?></p>
            </div>
        <?php endif; ?>
        <?php if (get_post_meta( get_the_ID(), 'kids', true )) : ?>
            <div title="<?php esc_html_e("Kids Friendly", 'lc-rentals'); ?>" class="rooms__lc-rentals-single-container-info-content-amenities-container-item">
                <?php echo wp_get_attachment_image(2024, 'full'); ?>
                <p class="paragraph--black"><?php esc_html_e('Kids Friendly', 'lc-rentals'); ?></p>
            </div>
        <?php endif; ?>
        <?php if (get_post_meta( get_the_ID(), 'jacuzzi', true )) : ?>
        <div title="<?php esc_html_e("Jacuzzi", 'lc-rentals'); ?>" class="rooms__lc-rentals-single-container-info-content-amenities-container-item">
            <?php echo wp_get_attachment_image(1956, 'full'); ?>
            <p class="paragraph--black"><?php esc_html_e('Jacuzzi', 'lc-rentals'); ?></p>
        </div>
        <?php endif; ?>
        <?php if (get_post_meta( get_the_ID(), 'ac', true )) : ?>
            <div title="<?php esc_html_e("Air Conditioning", 'lc-rentals'); ?>" class="rooms__lc-rentals-single-container-info-content-amenities-container-item">
                <?php echo wp_get_attachment_image(2007, 'full'); ?>
                <p class="paragraph--black"><?php esc_html_e('Air Conditioning', 'lc-rentals'); ?></p>
            </div>
        <?php endif; ?>
        <?php if (get_post_meta( get_the_ID(), 'cleaning-products', true )) : ?>
            <div title="<?php esc_html_e("Cleaning Products", 'lc-rentals'); ?>" class="rooms__lc-rentals-single-container-info-content-amenities-container-item">
                <?php echo wp_get_attachment_image(2025, 'full'); ?>
                <p class="paragraph--black"><?php esc_html_e('Cleaning Products', 'lc-rentals'); ?></p>
            </div>
        <?php endif; ?>
        <?php if (get_post_meta( get_the_ID(), 'cooking-basics', true )) : ?>
            <div title="<?php esc_html_e("Cooking Basics (Pots and pans, oil, salt and pepper)", 'lc-rentals'); ?>" class="rooms__lc-rentals-single-container-info-content-amenities-container-item">
                <?php echo wp_get_attachment_image(2026, 'full'); ?>
                <p class="paragraph--black"><?php esc_html_e('Cooking Basics (Pots and pans, oil, salt and pepper)', 'lc-rentals'); ?></p>
            </div>
        <?php endif; ?>
        <?php if (get_post_meta( get_the_ID(), 'workspace', true )) : ?>
            <div title="<?php esc_html_e("Dedicated workspace", 'lc-rentals'); ?>" class="rooms__lc-rentals-single-container-info-content-amenities-container-item">
                <?php echo wp_get_attachment_image(1958, 'full'); ?>
                <p class="paragraph--black"><?php esc_html_e('Dedicated workspace', 'lc-rentals'); ?></p>
            </div>
        <?php endif; ?>
        <?php if (get_post_meta( get_the_ID(), 'dishes', true )) : ?>
            <div title="<?php esc_html_e("Dishes & Utensils", 'lc-rentals'); ?>" class="rooms__lc-rentals-single-container-info-content-amenities-container-item">
                <?php echo wp_get_attachment_image(2027, 'full'); ?>
                <p class="paragraph--black"><?php esc_html_e('Dishes & Utensils', 'lc-rentals'); ?></p>
            </div>
        <?php endif; ?>
        <?php if (get_post_meta( get_the_ID(), 'dryer', true )) : ?>
            <div title="<?php esc_html_e("Clothes Dryer", 'lc-rentals'); ?>" class="rooms__lc-rentals-single-container-info-content-amenities-container-item">
                <?php echo wp_get_attachment_image(1976, 'full'); ?>
                <p class="paragraph--black"><?php esc_html_e('Clothes Dryer', 'lc-rentals'); ?></p>
            </div>
        <?php endif; ?>
        <?php if (get_post_meta( get_the_ID(), 'washing-machine', true )) : ?>
            <div title="<?php esc_html_e("Washing Machine", 'lc-rentals'); ?>" class="rooms__lc-rentals-single-container-info-content-amenities-container-item">
                <?php echo wp_get_attachment_image(2013, 'full'); ?>
                <p class="paragraph--black"><?php esc_html_e('Washing Machine', 'lc-rentals'); ?></p>
            </div>
        <?php endif; ?>
        <?php if (get_post_meta( get_the_ID(), 'hair-dryer', true )) : ?>
            <div title="<?php esc_html_e("Hair Dryer", 'lc-rentals'); ?>" class="rooms__lc-rentals-single-container-info-content-amenities-container-item">
                <?php echo wp_get_attachment_image(2011, 'full'); ?>
                <p class="paragraph--black"><?php esc_html_e('Hair Dryer', 'lc-rentals'); ?></p>
            </div>
        <?php endif; ?>
        <?php if (get_post_meta( get_the_ID(), 'heating', true )) : ?>
            <div title="<?php esc_html_e("Heating", 'lc-rentals'); ?>" class="rooms__lc-rentals-single-container-info-content-amenities-container-item">
                <?php echo wp_get_attachment_image(1960, 'full'); ?>
                <p class="paragraph--black"><?php esc_html_e('Heating', 'lc-rentals'); ?></p>
            </div>
        <?php endif; ?>
        <?php if (get_post_meta( get_the_ID(), 'kitchen', true )) : ?>
            <div title="<?php esc_html_e("Kitchen", 'lc-rentals'); ?>" class="rooms__lc-rentals-single-container-info-content-amenities-container-item">
                <?php echo wp_get_attachment_image(2028, 'full'); ?>
                <p class="paragraph--black"><?php esc_html_e('Kitchen', 'lc-rentals'); ?></p>
            </div>
        <?php endif; ?>
        <?php if (get_post_meta( get_the_ID(), 'tv', true )) : ?>
            <div title="<?php esc_html_e("TV", 'lc-rentals'); ?>" class="rooms__lc-rentals-single-container-info-content-amenities-container-item">
                <?php echo wp_get_attachment_image(2015, 'full'); ?>
                <p class="paragraph--black"><?php esc_html_e('TV', 'lc-rentals'); ?></p>
            </div>
        <?php endif; ?>
        <?php if (get_post_meta( get_the_ID(), 'dishwasher', true )) : ?>
            <div title="<?php esc_html_e("Dishwasher", 'lc-rentals'); ?>" class="rooms__lc-rentals-single-container-info-content-amenities-container-item">
                <?php echo wp_get_attachment_image(1979, 'full'); ?>
                <p class="paragraph--black"><?php esc_html_e('Dishwasher', 'lc-rentals'); ?></p>
            </div>
        <?php endif; ?>
        <?php if (get_post_meta( get_the_ID(), 'wifi', true )) : ?>
            <div title="<?php esc_html_e("WiFi", 'lc-rentals'); ?>" class="rooms__lc-rentals-single-container-info-content-amenities-container-item">
                <?php echo wp_get_attachment_image(2014, 'full'); ?>
                <p class="paragraph--black"><?php esc_html_e('WiFi', 'lc-rentals'); ?></p>
            </div>
        <?php endif; ?>
        <?php if (get_post_meta( get_the_ID(), 'bathtub', true )) : ?>
            <div title="<?php esc_html_e("Bathtub", 'lc-rentals'); ?>" class="rooms__lc-rentals-single-container-info-content-amenities-container-item">
                <?php echo wp_get_attachment_image(1903, 'full'); ?>
                <p class="paragraph--black"><?php esc_html_e('Bathtub', 'lc-rentals'); ?></p>
            </div>
        <?php endif; ?>
        <?php if (get_post_meta( get_the_ID(), 'shower', true )) : ?>
            <div title="<?php esc_html_e("Shower", 'lc-rentals'); ?>" class="rooms__lc-rentals-single-container-info-content-amenities-container-item">
                <?php echo wp_get_attachment_image(2012, 'full'); ?>
                <p class="paragraph--black"><?php esc_html_e('Shower', 'lc-rentals'); ?></p>
            </div>
        <?php endif; ?>
        <?php if (get_post_meta( get_the_ID(), 'shampoo', true )) : ?>
            <div title="<?php esc_html_e("Shampoo", 'lc-rentals'); ?>" class="rooms__lc-rentals-single-container-info-content-amenities-container-item">
                <?php echo wp_get_attachment_image(2029, 'full'); ?>
                <p class="paragraph--black"><?php esc_html_e('Shampoo', 'lc-rentals'); ?></p>
            </div>
        <?php endif; ?>
        <?php if (get_post_meta( get_the_ID(), 'hangers', true )) : ?>
            <div title="<?php esc_html_e("Hangers", 'lc-rentals'); ?>" class="rooms__lc-rentals-single-container-info-content-amenities-container-item">
                <?php echo wp_get_attachment_image(2030, 'full'); ?>
                <p class="paragraph--black"><?php esc_html_e('Hangers', 'lc-rentals'); ?></p>
            </div>
        <?php endif; ?>
        <?php if (get_post_meta( get_the_ID(), 'iron', true )) : ?>
            <div title="<?php esc_html_e("Iron", 'lc-rentals'); ?>" class="rooms__lc-rentals-single-container-info-content-amenities-container-item">
                <?php echo wp_get_attachment_image(2031, 'full'); ?>
                <p class="paragraph--black"><?php esc_html_e('Iron', 'lc-rentals'); ?></p>
            </div>
        <?php endif; ?>
        <?php if (get_post_meta( get_the_ID(), 'safe-box', true )) : ?>
            <div title="<?php esc_html_e("Safe Box", 'lc-rentals'); ?>" class="rooms__lc-rentals-single-container-info-content-amenities-container-item">
                <?php echo wp_get_attachment_image(1968, 'full'); ?>
                <p class="paragraph--black"><?php esc_html_e('Safe Box', 'lc-rentals'); ?></p>
            </div>
        <?php endif; ?>
    </div>
</div>