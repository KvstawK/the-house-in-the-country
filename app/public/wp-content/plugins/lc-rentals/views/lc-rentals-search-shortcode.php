<form aria-labelledby="search_rentals">
    <fieldset>
        <div class="lc-rentals__search-form-legend">
            <legend id="search_rentals" class="headline-2"><?php esc_html_e('Search The Availability Of Our Rentals', 'lc-rentals'); ?></legend>
        </div>
        <div class="lc-rentals__search-form-info">
            <div class="lc-rentals__search-form-info-items">
                <div title="<?php esc_html_e('Rental Location', 'lc-rentals'); ?>" class="lc-rentals__search-form-info-items-field">
                    <label for="location" class="paragraph-first-line"><?php esc_html_e('Location', 'lc-rentals'); ?></label>
                    <input id="location" name="location" type="text" autocomplete="off" required>
                    <?php
                    $locations = get_terms([
                        'taxonomy' => 'rental-location',
//                        Uncomment if you would like only the first parent to appear
//                        'parent' => 0,
                        'hide_empty' => false
                    ]);
                    foreach ($locations as $location) : ?>
                        <div class="lc-rentals__search-form-info-items-field-menu">
                            <?php echo $location->name ?>
                        </div>
                    <?php endforeach;
                    ?>
                </div>
                <div title="<?php esc_html_e('Rental Type', 'lc-rentals'); ?>" class="lc-rentals__search-form-info-items-field">
                    <label for="type" class="paragraph-first-line"><?php esc_html_e('Type', 'lc-rentals'); ?></label>
                    <input id="type" name="type" type="text">
                    <?php
                    $types = get_terms([
                        'taxonomy' => 'rental-category',
                        'parent' => 0,
                        'hide_empty' => false
                    ]);
                    foreach ($types as $type) : ?>
                        <div class="lc-rentals__search-form-info-items-field-menu">
                            <?php echo $type->name ?>
                        </div>
                    <?php endforeach;
                    ?>
                </div>
                <div title="<?php esc_html_e('Check-in Date', 'lc-rentals'); ?>"  class="lc-rentals__search-form-info-items-field">

	                <?php include ( LC_RENTALS_PATH . 'datepicker/rc-datepicker-check-in.php' ); ?>

                </div>
                <div title="<?php esc_html_e('Check-out Date', 'lc-rentals'); ?>" class="lc-rentals__search-form-info-items-field">

	                <?php include ( LC_RENTALS_PATH . 'datepicker/rc-datepicker-check-out.php' ); ?>

                </div>
                <div title="<?php esc_html_e('Number Of Persons', 'lc-rentals'); ?>" class="lc-rentals__search-form-info-items-field">
                    <label for="persons" class="paragraph-first-line"><?php esc_html_e('persons', 'lc-rentals'); ?></label>
                    <input id="persons" name="persons" type="text" required>
                </div>
            </div>
            <div title="<?php esc_html_e('Search', 'lc-rentals'); ?>" class="lc-rentals__search-form-info-search">
                <div aria-hidden="true"><?php echo wp_get_attachment_image(2216, 'full') ?></div>
                <input type="submit" value="<?php esc_html_e('Search', 'lc-rentals'); ?>">
            </div>
        </div>
    </fieldset>
</form>