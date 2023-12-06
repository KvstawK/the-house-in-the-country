<?php
$values =  get_post_meta( $post->ID );

 multi_media_uploader_field($name = '', $value = '')
?>
<div class="rc-slider__options">
    <input type="hidden" name="rc_slider_nonce" value="<?php echo wp_create_nonce('rc_slider_nonce') ?>">
    <div class="rc-slider__options-images">
        <div class="rc-slider__options-images-text"><?php esc_html_e('Slider\'s Images', 'rc-slider'); ?></div>
        <div class="rc-slider__options-images-attachments">
            <?php echo multi_media_uploader_field( 'images', get_post_meta($post->ID,'images',true) ); ?>
        </div>
    </div>
    <div class="rc-slider__options-checkboxes">
    <div class="rc-slider__options-checkboxes-item">
            <div><?php esc_html_e('Check to change image sizes:', 'rc-slider'); ?></div>
            <div><label for="thumbnail"><?php esc_html_e('Thumbnail:', 'rc-slider'); ?></label></div>
            <div>
                <input type="checkbox" name="thumbnail" id="thumbnail" class="checkbox checkbox-sizes" value="true" <?php if ( isset ( $values['thumbnail'] ) ) checked( $values['thumbnail'][0], 'true' ); ?>>
            </div>
            <div><label for="medium"><?php esc_html_e('Medium:', 'rc-slider'); ?></label></div>
            <div>
                <input type="checkbox" name="medium" id="medium" class="checkbox checkbox-sizes" value="true" <?php if ( isset ( $values['medium'] ) ) checked( $values['medium'][0], 'true' ); ?>>
            </div>
            <div><label for="large"><?php esc_html_e('Large:', 'rc-slider'); ?></label></div>
            <div>
                <input type="checkbox" name="large" id="large" class="checkbox checkbox-sizes" value="true" <?php if ( isset ( $values['large'] ) ) checked( $values['large'][0], 'true' ); ?>>
            </div>
            <div><label for="full"><?php esc_html_e('Full:', 'rc-slider'); ?></label></div>
            <div>
                <input type="checkbox" name="full" id="full" class="checkbox checkbox-sizes" value="true" <?php if ( isset ( $values['full'] ) ) checked( $values['full'][0], 'true' ); ?>>
            </div>
        </div>
        <div class="rc-slider__options-checkboxes-item">
            <div><label for="image-link"><?php esc_html_e('Check if you would like the image to be the link from the "Attachments Details" in Media Library:', 'rc-slider'); ?></label></div>
            <div>
                <input type="checkbox" name="image-link" id="image-link" class="checkbox" value="true" <?php if ( isset ( $values['image-link'] ) ) checked( $values['image-link'][0], 'true' ); ?>>
            </div>
        </div>
        <div class="rc-slider__options-checkboxes-item">
            <div><label for="arrows"><?php esc_html_e('Check if you would like the arrows to appear:', 'rc-slider'); ?></label></div>
            <div>
                <input type="checkbox" name="arrows" id="arrows" class="checkbox" value="true" <?php if ( isset ( $values['arrows'] ) ) checked( $values['arrows'][0], 'true' ); ?>>
            </div>
        </div>
        <div class="rc-slider__options-checkboxes-item">
            <div><label for="auto-play"><?php esc_html_e('Check if you would like auto-play:', 'rc-slider'); ?></label></div>
            <div>
                <input type="checkbox" name="auto-play" id="auto-play" class="checkbox" value="true" <?php if ( isset ( $values['auto-play'] ) ) checked( $values['auto-play'][0], 'true' ); ?>>
            </div>
        </div>
        <div class="rc-slider__options-checkboxes-item">
            <div><label for="image-appear-disappear-slider"><?php esc_html_e('Check if you want the "image appearing-disappearing" type of slider:', 'rc-slider'); ?></label></div>
            <div>
                <input type="checkbox" name="image-appear-disappear-slider" id="image-appear-disappear-slider" class="checkbox" value="true" <?php if ( isset ( $values['image-appear-disappear-slider'] ) ) checked( $values['image-appear-disappear-slider'][0], 'true' ); ?>>
            </div>
        </div>
        <div class="rc-slider__options-checkboxes-item">
        <div><label for="dots"><?php esc_html_e('Check if you would like to display bullets:', 'rc-slider'); ?></label></div>
            <div>
                <input type="checkbox" name="dots" id="dots" class="checkbox" value="true" <?php if ( isset ( $values['dots'] ) ) checked( $values['dots'][0], 'true' ); ?>>
            </div>
        </div>
        <div class="rc-slider__options-checkboxes-item">
            <div><label for="dots-carousel"><?php esc_html_e('Check if you would like to display a second slider like bullets:', 'rc-slider'); ?></label></div>
            <div>
                <input type="checkbox" name="dots-carousel" id="dots-carousel" class="checkbox" value="true" <?php if ( isset ( $values['dots-carousel'] ) ) checked( $values['dots-carousel'][0], 'true' ); ?>>
            </div>
        </div>
        <div class="rc-slider__options-checkboxes-item">
            <div><label for="content"><?php esc_html_e('Check if you would like to display the content:', 'rc-slider'); ?></label></div>
            <div>
                <input type="checkbox" name="content" id="content" class="checkbox" value="true" <?php if ( isset ( $values['content'] ) ) checked( $values['content'][0], 'true' ); ?>>
            </div>
        </div>
        <div class="rc-slider__options-checkboxes-item">
            <div><label for="2-slide-carousel"><?php esc_html_e('Check if you would like to display 2 slides in 1 in desktop:', 'rc-slider'); ?></label></div>
            <div>
                <input type="checkbox" name="2-slide-carousel" id="2-slide-carousel" class="checkbox" value="true" <?php if ( isset ( $values['2-slide-carousel'] ) ) checked( $values['2-slide-carousel'][0], 'true' ); ?>>
            </div>
        </div>
        <div class="rc-slider__options-checkboxes-item">
            <div><label for="3-slide-carousel"><?php esc_html_e('Check if you would like to display 3 slides in 1 in desktop:', 'rc-slider'); ?></label></div>
            <div>
                <input type="checkbox" name="3-slide-carousel" id="3-slide-carousel" class="checkbox" value="true" <?php if ( isset ( $values['3-slide-carousel'] ) ) checked( $values['3-slide-carousel'][0], 'true' ); ?>>
            </div>
        </div>
        <div class="rc-slider__options-checkboxes-item">
            <div><label for="gallery"><?php esc_html_e('Check if you would like to have a gallery:', 'rc-slider'); ?></label></div>
            <div>
                <input type="checkbox" name="gallery" id="gallery" class="checkbox" value="true" <?php if ( isset ( $values['gallery'] ) ) checked( $values['gallery'][0], 'true' ); ?>>
            </div>
        </div>
        <div class="rc-slider__options-checkboxes-item">
            <div><label for="lightbox"><?php esc_html_e('Check if you would like to have a lightbox:', 'rc-slider'); ?></label></div>
            <div>
                <input type="checkbox" name="lightbox" id="lightbox" class="checkbox" value="true" <?php if ( isset ( $values['lightbox'] ) ) checked( $values['lightbox'][0], 'true' ); ?>>
            </div>
        </div>
    </div>
</div>
