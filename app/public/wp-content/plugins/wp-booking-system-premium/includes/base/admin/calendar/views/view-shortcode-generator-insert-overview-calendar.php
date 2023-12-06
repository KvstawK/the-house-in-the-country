<!-- Modal Tab: Calendar Overview -->
<div class="wpbs-tab wpbs-modal-tab" data-tab="insert-overview-calendar">
    
    <h3><?php echo __( 'Insert a Multiple Overview Calendar', 'wp-booking-system' ); ?></h3>
    <p><?php echo __( 'Create a calendar overview that displays all selected calendars and their availability', 'wp-booking-system' ); ?></p>


    <h4><?php echo __( 'Calendar Selection', 'wp-booking-system' ); ?></h4>
    <hr />

    <!-- Row -->
    <div class="wpbs-row">

        <!-- Column: Calendars -->
        <div class="wpbs-col-1-4">

            <label for="modal-add-calendar-overview-shortcode-calendars"><?php echo __( 'Display Calendars', 'wp-booking-system' ); ?></label>

            <select id="modal-add-calendar-overview-shortcode-calendars">
                <option value="1"><?php echo __( 'All Calendars', 'wp-booking-system' ); ?></option>
                <option value="2"><?php echo __( 'Selected Calendars', 'wp-booking-system' ); ?></option>
            </select>

        </div>

        <!-- Column: Selected Calendars -->
        <div class="wpbs-col-3-4 wpbs-element-disabled wpbs-chosen-wrapper">

            <label for="modal-add-calendar-overview-shortcode-selected-calendars"><?php echo __( 'Select Calendars', 'wp-booking-system' ); ?></label>

            <select id="modal-add-calendar-overview-shortcode-selected-calendars" multiple class="wpbs-chosen">
                <?php
                    foreach( $calendars as $calendar )
                        echo '<option value="' . $calendar->get('id') . '">' . $calendar->get('name') . '</option>';
                ?>
            </select>

        </div>

    </div><!-- / Row -->

    <h4><?php echo __( 'Basic Options', 'wp-booking-system' ); ?></h4>
    <hr />

    <!-- Row -->
    <div class="wpbs-row">

        <!-- Column: Legend -->
        <div class="wpbs-col-1-4">
            
            <label for="modal-add-calendar-overview-shortcode-legend"><?php echo __( 'Display Legend', 'wp-booking-system' ); ?></label>

            <select id="modal-add-calendar-overview-shortcode-legend" class="wpbs-shortcode-generator-field-calendar-overview" data-attribute="legend">
                <option value="yes"><?php echo __( 'Yes', 'wp-booking-system' ); ?></option>
                <option value="no"><?php echo __( 'No', 'wp-booking-system' ); ?></option>
            </select>

        </div>

        <!-- Column: Legend Position -->
        <div class="wpbs-col-1-4">
            
            <label for="modal-add-calendar-overview-shortcode-legend-position"><?php echo __( 'Legend Position', 'wp-booking-system' ); ?></label>

            <select id="modal-add-calendar-overview-shortcode-legend-position" class="wpbs-shortcode-generator-field-calendar-overview" data-attribute="legend_position">
                <option value="top"><?php echo __( 'Top', 'wp-booking-system' ); ?></option>
                <option value="bottom"><?php echo __( 'Bottom', 'wp-booking-system' ); ?></option>
            </select>

        </div>

        <!-- Column: Start Year -->
        <div class="wpbs-col-1-4">
            
            <label for="modal-add-calendar-overview-shortcode-start-year"><?php echo __( 'Start Year', 'wp-booking-system' ); ?></label>

            <select id="modal-add-calendar-overview-shortcode-start-year" class="wpbs-shortcode-generator-field-calendar-overview" data-attribute="start_year">
                <option value="0"><?php echo __( 'Current Year', 'wp-booking-system' ); ?></option>
                <?php
                    for( $i = date('Y'); $i <= date('Y') + 10; $i++ )
                        echo '<option value="' . $i . '">' . $i . '</option>';
                ?>
            </select>

        </div>

        <!-- Column: Start Month -->
        <div class="wpbs-col-1-4">
            
            <label for="modal-add-calendar-overview-shortcode-start-month"><?php echo __( 'Start Month', 'wp-booking-system' ); ?></label>

            <select id="modal-add-calendar-overview-shortcode-start-month" class="wpbs-shortcode-generator-field-calendar-overview" data-attribute="start_month">
                <option value="0"><?php echo __( 'Current Month', 'wp-booking-system' ); ?></option>
                <option value="1"><?php echo __( 'January', 'wp-booking-system' ); ?></option>
                <option value="2"><?php echo __( 'February', 'wp-booking-system' ); ?></option>
                <option value="3"><?php echo __( 'March', 'wp-booking-system' ); ?></option>
                <option value="4"><?php echo __( 'April', 'wp-booking-system' ); ?></option>
                <option value="5"><?php echo __( 'May', 'wp-booking-system' ); ?></option>
                <option value="6"><?php echo __( 'June', 'wp-booking-system' ); ?></option>
                <option value="7"><?php echo __( 'July', 'wp-booking-system' ); ?></option>
                <option value="8"><?php echo __( 'August', 'wp-booking-system' ); ?></option>
                <option value="9"><?php echo __( 'September', 'wp-booking-system' ); ?></option>
                <option value="10"><?php echo __( 'October', 'wp-booking-system' ); ?></option>
                <option value="11"><?php echo __( 'November', 'wp-booking-system' ); ?></option>
                <option value="12"><?php echo __( 'December', 'wp-booking-system' ); ?></option>   
            </select>

        </div>

    </div><!-- / Row -->

    <h4><?php echo __( 'Advanced Options', 'wp-booking-system' ); ?></h4>
    <hr />

    <!-- Row -->
    <div class="wpbs-row">

        <!-- Column: Show history -->
        <div class="wpbs-col-1-4">

            <label for="modal-add-calendar-overview-shortcode-history"><?php echo __( 'Show History', 'wp-booking-system' ); ?><?php echo wpbs_get_output_tooltip( __( "This option lets you decide how past dates are being displayed for the user in the front-end.", 'wp-booking-system' ) ); ?></label>

            <select id="modal-add-calendar-overview-shortcode-history" class="wpbs-shortcode-generator-field-calendar-overview" data-attribute="history">
                <option value="1"><?php echo __( 'Display booking history', 'wp-booking-system' ); ?></option>
                <option value="2"><?php echo __( 'Replace booking history with the default legend item', 'wp-booking-system' ); ?></option>
                <option value="3"><?php echo __( 'Use the Booking History Color from the Settings', 'wp-booking-system' ); ?></option>
            </select>

        </div>

        <!-- Column: Display Tooltips -->
        <div class="wpbs-col-1-4">

            <label for="modal-add-calendar-overview-shortcode-tooltip"><?php echo __( 'Display Tooltips', 'wp-booking-system' ); ?></label>

            <select id="modal-add-calendar-overview-shortcode-tooltip" class="wpbs-shortcode-generator-field-calendar-overview" data-attribute="tooltip">
                <option value="1"><?php echo __( 'No', 'wp-booking-system' ); ?></option>
                <option value="2"><?php echo __( 'Yes', 'wp-booking-system' ); ?></option>
                <option value="3"><?php echo __( 'Yes, with red indicator', 'wp-booking-system' ); ?></option>
            </select>

        </div>

        <!-- Column: Show week numbers -->
        <div class="wpbs-col-1-4">

            <label for="modal-add-calendar-overview-shortcode-week-numbers"><?php echo __( 'Show Day Abbreviations', 'wp-booking-system' ); ?><?php echo wpbs_get_output_tooltip( __( "If set to yes, it will display a secondary calendar header for the dates (besides the day numbers), containing the first letter of the day's name. For example, Monday will display M.", 'wp-booking-system' ) ); ?></label>

            <select id="modal-add-calendar-overview-shortcode-week-numbers" class="wpbs-shortcode-generator-field-calendar-overview" data-attribute="weeknumbers">
                <option value="yes"><?php echo __( 'Yes', 'wp-booking-system' ); ?></option>
                <option value="no" selected><?php echo __( 'No', 'wp-booking-system' ); ?></option>
            </select>

        </div>

        <div class="wpbs-col-1-4">

            <label for="modal-add-calendar-shortcode-language"><?php echo __( 'Language', 'wp-booking-system' ); ?></label>

            <select id="modal-add-calendar-shortcode-language" class="wpbs-shortcode-generator-field-calendar-overview" data-attribute="language">
                
                <option value="auto"><?php echo __( 'Auto (let WP choose)', 'wp-booking-system' ); ?></option>

                <?php

                    $settings 		  = get_option( 'wpbs_settings', array() );
                    $languages 		  = wpbs_get_languages();
                    $active_languages = ( ! empty( $settings['active_languages'] ) ? $settings['active_languages'] : array() );

                    foreach( $active_languages as $code ) {

                        echo '<option value="' . esc_attr( $code ) . '">' . ( ! empty( $languages[$code] ) ? $languages[$code] : '' ) . '</option>';

                    }

                ?>

            </select>
        </div>
    </div>

    <hr />

    <!-- Shortcode insert -->
    <a href="#" id="wpbs-insert-shortcode-overview-calendar" class="button button-primary"><?php echo __( 'Insert Calendar Overview', 'wp-booking-system' ); ?></a>
    <a href="#" class="button button-secondary wpbs-modal-close"><?php echo __( 'Cancel', 'wp-booking-system' ); ?></a>

</div>