<!-- Modal Tab: Search Widget -->
<div class="wpbs-tab wpbs-modal-tab" data-tab="insert-search-widget">

    <h3><?php echo __('Insert a Calendar Search Widget', 'wp-booking-system-search'); ?></h3>
    <p><?php echo __('Create a search widget that allows the visitor to search for available dates in your calendars', 'wp-booking-system-search'); ?></p>


    <h4><?php echo __('Search Widget', 'wp-booking-system-search'); ?></h4>
    <hr />

    <!-- Row -->
    <div class="wpbs-row">

        <!-- Column: Calendars -->
        <div class="wpbs-col-1-4">

            <label for="modal-add-search-widget-shortcode-calendars"><?php echo __('Calendars', 'wp-booking-system-search'); ?></label>

            <select id="modal-add-search-widget-shortcode-calendars">
                <option value="1"><?php echo __('All Calendars', 'wp-booking-system-search'); ?></option>
                <option value="2"><?php echo __('Selected Calendars', 'wp-booking-system-search'); ?></option>
            </select>

        </div>

        <!-- Column: Selected Calendars -->
        <div class="wpbs-col-3-4 wpbs-element-disabled">

            <label for="modal-add-search-widget-shortcode-selected-calendars"><?php echo __('Select Calendars', 'wp-booking-system-search'); ?></label>

            <select id="modal-add-search-widget-shortcode-selected-calendars" multiple class="wpbs-chosen">
                <?php $calendars = wpbs_get_calendars(array('status' => 'active'));?>
                <?php
                    foreach ($calendars as $calendar) {
                        echo '<option value="' . $calendar->get('id') . '">' . $calendar->get('name') . '</option>';
                    }

                    ?>
            </select>

        </div>

    </div><!-- / Row -->

    <h4><?php echo __('Basic Options', 'wp-booking-system-search'); ?></h4>
    <hr />

    <!-- Row -->
    <div class="wpbs-row">

        <!-- Column: Language -->
        <div class="wpbs-col-1-4">

            <label for="modal-add-search-widget-shortcode-language"><?php echo __('Language', 'wp-booking-system-search'); ?></label>
            <select id="modal-add-search-widget-shortcode-language" class="wpbs-shortcode-generator-field-search-widget" data-attribute="language">
                <option value="auto"><?php echo __('Auto (let WP choose)', 'wp-booking-system-search'); ?></option>

                <?php

                $settings = get_option('wpbs_settings', array());
                $languages = wpbs_get_languages();
                $active_languages = (!empty($settings['active_languages']) ? $settings['active_languages'] : array());

                foreach ($active_languages as $code) {

                    echo '<option value="' . esc_attr($code) . '">' . (!empty($languages[$code]) ? $languages[$code] : '') . '</option>';

                }

                ?>
            </select>
        </div>

        <!-- Column: Week Start Day -->
        <div class="wpbs-col-1-4">
            
            <label for="modal-add-calendar-shortcode-week-start-day"><?php echo __( 'Week Start Day', 'wp-booking-system-search' ); ?></label>

            <select id="modal-add-calendar-shortcode-week-start-day" class="wpbs-shortcode-generator-field-search-widget" data-attribute="start_day">
                <option value="1"><?php echo __( 'Monday', 'wp-booking-system-search' ); ?></option>
                <option value="2"><?php echo __( 'Tuesday', 'wp-booking-system-search' ); ?></option>
                <option value="3"><?php echo __( 'Wednesday', 'wp-booking-system-search' ); ?></option>
                <option value="4"><?php echo __( 'Thursday', 'wp-booking-system-search' ); ?></option>
                <option value="5"><?php echo __( 'Friday', 'wp-booking-system-search' ); ?></option>
                <option value="6"><?php echo __( 'Saturday', 'wp-booking-system-search' ); ?></option>
                <option value="7"><?php echo __( 'Sunday', 'wp-booking-system-search' ); ?></option>
            </select>

        </div>

        <!-- Column: Widget Title -->
        <div class="wpbs-col-1-4">

            <label for="modal-add-search-widget-shortcode-title"><?php echo __('Widget Title', 'wp-booking-system-search'); ?></label>
            <select id="modal-add-search-widget-shortcode-title" class="wpbs-shortcode-generator-field-search-widget" data-attribute="title">
                <option value="yes"><?php echo __('Yes', 'wp-booking-system-search'); ?></option>
                <option value="no"><?php echo __('No', 'wp-booking-system-search'); ?></option>
            </select>
        </div>

        <!-- Column: Mark Selection -->
        <div class="wpbs-col-1-4">

            <label for="modal-add-search-widget-shortcode-selection"><?php echo __('Automatically Mark Selection', 'wp-booking-system-search'); ?></label>
            <select id="modal-add-search-widget-shortcode-selection" class="wpbs-shortcode-generator-field-search-widget" data-attribute="mark_selection">
                <option value="yes"><?php echo __('Yes', 'wp-booking-system-search'); ?></option>
                <option value="no"><?php echo __('No', 'wp-booking-system-search'); ?></option>
            </select>
        </div>

        


    </div><!-- / Row -->

    <div class="wpbs-row">

        <!-- Column: Selection Type -->
        <div class="wpbs-col-1-4">

            <label for="modal-add-search-widget-shortcode-type"><?php echo __('Selection Type', 'wp-booking-system-search'); ?></label>
            <select id="modal-add-search-widget-shortcode-type" class="wpbs-shortcode-generator-field-search-widget" data-attribute="selection_type">
                <option value="multiple"><?php echo __('Date Range', 'wp-booking-system-search'); ?></option>
                <option value="single"><?php echo __('Single Day', 'wp-booking-system-search'); ?></option>
            </select>
        </div>

        <!-- Column: Minimum Stay -->
        <div class="wpbs-col-1-4">

            <label for="modal-add-search-widget-shortcode-minimum_stay"><?php echo __('Minimum Stay', 'wp-booking-system-search'); ?></label>
            <input type="number" id="modal-add-search-widget-shortcode-minimum_stay" class="wpbs-shortcode-generator-field-search-widget" data-attribute="minimum_stay" value="0" />
        </div>

        <!-- Column: Featured Image -->
        <div class="wpbs-col-1-4">

            <label for="modal-add-search-widget-shortcode-featured-image"><?php echo __('Show Featured Image', 'wp-booking-system-search'); ?></label>
            <select id="modal-add-search-widget-shortcode-featured-image" class="wpbs-shortcode-generator-field-search-widget" data-attribute="featured_image">
                <option value="yes"><?php echo __('Yes', 'wp-booking-system-search'); ?></option>
                <option value="no"><?php echo __('No', 'wp-booking-system-search'); ?></option>
            </select>
        </div>

        <!-- Column: Starting Price -->
        <div class="wpbs-col-1-4">

            <label for="modal-add-search-widget-shortcode-starting-price"><?php echo __('Show Starting Price', 'wp-booking-system-search'); ?></label>
            <select id="modal-add-search-widget-shortcode-starting-price" class="wpbs-shortcode-generator-field-search-widget" data-attribute="starting_price">
                <option value="yes"><?php echo __('Yes', 'wp-booking-system-search'); ?></option>
                <option value="no"><?php echo __('No', 'wp-booking-system-search'); ?></option>
            </select>
        </div>

    </div><!-- / Row -->

    <div class="wpbs-row">

        <!-- Column: Starting Price -->
        <div class="wpbs-col-1-4">

            <label for="modal-add-search-widget-shortcode-show-results-on-load"><?php echo __('Show Results on Load', 'wp-booking-system-search'); ?></label>
            <select id="modal-add-search-widget-shortcode-show-results-on-load" class="wpbs-shortcode-generator-field-search-widget" data-attribute="show_results_on_load">
                <option value="yes"><?php echo __('Yes', 'wp-booking-system-search'); ?></option>
                <option value="no"><?php echo __('No', 'wp-booking-system-search'); ?></option>
            </select>
        </div>

        <!-- Column: Results Layout -->
        <div class="wpbs-col-1-4">

            <label for="modal-add-search-widget-shortcode-results-layout"><?php echo __('Results Layout', 'wp-booking-system-search'); ?></label>
            <select id="modal-add-search-widget-shortcode-results-layout" class="wpbs-shortcode-generator-field-search-widget" data-attribute="results_layout">
                <option value="list"><?php echo __('List', 'wp-booking-system-search'); ?></option>
                <option value="grid"><?php echo __('Grid', 'wp-booking-system-search'); ?></option>
            </select>
        </div>

        <!-- Column: Results per Page -->
        <div class="wpbs-col-1-4">

            <label for="modal-add-search-widget-shortcode-results-per-page"><?php echo __('Results per Page', 'wp-booking-system-search'); ?></label>
            <input type="number" id="modal-add-search-widget-shortcode-results-per-page" class="wpbs-shortcode-generator-field-search-widget" data-attribute="results_per_page" value="10" />
        </div>

        <!-- Column: Featured Image -->
        <div class="wpbs-col-1-4">

            <label for="modal-add-search-widget-shortcode-redirect"><?php echo __('Redirect', 'wp-booking-system-search'); ?></label>
            <input type="text" id="modal-add-search-widget-shortcode-redirect" class="widefat wpbs-shortcode-generator-field-search-widget" data-attribute="redirect" />
        </div>

    </div>

    <h4><?php echo __('Strings', 'wp-booking-system-search'); ?></h4>
    <hr />

    <!-- Row -->
    <div class="wpbs-row">
        <p><?php echo sprintf(__('You can configure strings like labels and error messages from the <a target="_blank" href="%s">settings page</a>.', 'wp-booking-system-search'), esc_url(add_query_arg(array('page' => 'wpbs-settings', 'tab' => 'search_addon'), admin_url( 'admin.php' ) ) ));?></p>

    </div>

    <hr />

    <!-- Shortcode insert -->
    <a href="#" id="wpbs-insert-shortcode-search-widget" class="button button-primary"><?php echo __('Insert Search Widget', 'wp-booking-system-search'); ?></a>
    <a href="#" class="button button-secondary wpbs-modal-close"><?php echo __('Cancel', 'wp-booking-system-search'); ?></a>

</div>