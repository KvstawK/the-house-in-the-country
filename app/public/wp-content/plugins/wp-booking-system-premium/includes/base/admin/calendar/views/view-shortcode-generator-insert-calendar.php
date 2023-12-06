<!-- Modal Tab: Calendar Single -->
<div class="wpbs-tab wpbs-modal-tab wpbs-active" data-tab="insert-calendar">
    
    <h3><?php echo __( 'Insert a Calendar', 'wp-booking-system' ); ?></h3>
    <p><?php echo __( 'Select which calendar you wish to insert and use the options to customize it to your needs.', 'wp-booking-system' ); ?></p>

    <h4><?php echo __( 'Calendar Options', 'wp-booking-system' ); ?></h4>
    <hr />

    <!-- Row -->
    <div class="wpbs-row">

        <!-- Column: Calendar -->
        <div class="wpbs-col-1-4">
            
            <label for="modal-add-calendar-shortcode-calendar"><?php echo __( 'Calendar', 'wp-booking-system' ); ?></label>

            <select id="modal-add-calendar-shortcode-calendar" class="wpbs-shortcode-generator-field-calendar" data-attribute="id">
                <?php
                    foreach( $calendars as $calendar )
                        echo '<option value="' . $calendar->get('id') . '">' . $calendar->get('name') . '</option>';
                ?>
            </select>

        </div>

        <!-- Column: Calendar Title -->
        <div class="wpbs-col-1-4">
            
            <label for="modal-add-calendar-shortcode-calendar-title"><?php echo __( 'Display Calendar Title', 'wp-booking-system' ); ?></label>

            <select id="modal-add-calendar-shortcode-calendar-title" class="wpbs-shortcode-generator-field-calendar" data-attribute="title">
                <option value="yes"><?php echo __( 'Yes', 'wp-booking-system' ); ?></option>
                <option value="no"><?php echo __( 'No', 'wp-booking-system' ); ?></option>
            </select>

        </div>

        <!-- Column: Legend -->
        <div class="wpbs-col-1-4">
            
            <label for="modal-add-calendar-shortcode-legend"><?php echo __( 'Display Legend', 'wp-booking-system' ); ?></label>

            <select id="modal-add-calendar-shortcode-legend" class="wpbs-shortcode-generator-field-calendar" data-attribute="legend">
                <option value="yes"><?php echo __( 'Yes', 'wp-booking-system' ); ?></option>
                <option value="no"><?php echo __( 'No', 'wp-booking-system' ); ?></option>
            </select>

        </div>

        <!-- Column: Legend Position -->
        <div class="wpbs-col-1-4">
            
            <label for="modal-add-calendar-shortcode-legend-position"><?php echo __( 'Legend Position', 'wp-booking-system' ); ?></label>

            <select id="modal-add-calendar-shortcode-legend-position" class="wpbs-shortcode-generator-field-calendar" data-attribute="legend_position">
                <option value="side"><?php echo __( 'Side', 'wp-booking-system' ); ?></option>
                <option value="top"><?php echo __( 'Top', 'wp-booking-system' ); ?></option>
                <option value="bottom"><?php echo __( 'Bottom', 'wp-booking-system' ); ?></option>
            </select>

        </div>

    </div><!-- / Row -->

    <!-- Row -->
    <div class="wpbs-row">

        <!-- Column: Months to Display -->
        <div class="wpbs-col-1-4">
            
            <label for="modal-add-calendar-shortcode-months-to-display"><?php echo __( 'Months to Display', 'wp-booking-system' ); ?></label>

            <select id="modal-add-calendar-shortcode-months-to-display" class="wpbs-shortcode-generator-field-calendar" data-attribute="display">
                <?php
                    for( $i = 1; $i <= 12; $i++ )
                        echo '<option value="' . $i . '">' . $i . '</option>';
                ?>
            </select>

        </div>

        <!-- Column: Start Year -->
        <div class="wpbs-col-1-4">
            
            <label for="modal-add-calendar-shortcode-start-year"><?php echo __( 'Start Year', 'wp-booking-system' ); ?></label>

            <select id="modal-add-calendar-shortcode-start-year" class="wpbs-shortcode-generator-field-calendar" data-attribute="year">
                <option value="0"><?php echo __( 'Current Year', 'wp-booking-system' ); ?></option>
                <?php
                    for( $i = date('Y'); $i <= date('Y') + 10; $i++ )
                        echo '<option value="' . $i . '">' . $i . '</option>';
                ?>
            </select>

        </div>

        <!-- Column: Start Month -->
        <div class="wpbs-col-1-4">
            
            <label for="modal-add-calendar-shortcode-start-month"><?php echo __( 'Start Month', 'wp-booking-system' ); ?></label>

            <select id="modal-add-calendar-shortcode-start-month" class="wpbs-shortcode-generator-field-calendar" data-attribute="month">
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

        <!-- Column: Language -->
        <div class="wpbs-col-1-4">

            <label for="modal-add-calendar-shortcode-language"><?php echo __( 'Language', 'wp-booking-system' ); ?></label>

            <select id="modal-add-calendar-shortcode-language" class="wpbs-shortcode-generator-field-calendar" data-attribute="language">
                
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

    </div><!-- / Row -->

    <!-- Row -->
    <div class="wpbs-row">

        <!-- Column: Week Start Day -->
        <div class="wpbs-col-1-4">
            
            <label for="modal-add-calendar-shortcode-week-start-day"><?php echo __( 'Week Start Day', 'wp-booking-system' ); ?></label>

            <select id="modal-add-calendar-shortcode-week-start-day" class="wpbs-shortcode-generator-field-calendar" data-attribute="start">
                <option value="1"><?php echo __( 'Monday', 'wp-booking-system' ); ?></option>
                <option value="2"><?php echo __( 'Tuesday', 'wp-booking-system' ); ?></option>
                <option value="3"><?php echo __( 'Wednesday', 'wp-booking-system' ); ?></option>
                <option value="4"><?php echo __( 'Thursday', 'wp-booking-system' ); ?></option>
                <option value="5"><?php echo __( 'Friday', 'wp-booking-system' ); ?></option>
                <option value="6"><?php echo __( 'Saturday', 'wp-booking-system' ); ?></option>
                <option value="7"><?php echo __( 'Sunday', 'wp-booking-system' ); ?></option>
            </select>

        </div>

        <!-- Column: Dropdown -->
        <div class="wpbs-col-1-4">

            <label for="modal-add-calendar-shortcode-dropdown"><?php echo __( 'Display Selection Dropdown', 'wp-booking-system' ); ?><?php echo wpbs_get_output_tooltip( __( "If set to yes, the calendar will display a month and year drop-down select field as a navigation alternative to the arrows.", 'wp-booking-system' ) ); ?></label>

            <select id="modal-add-calendar-shortcode-dropdown" class="wpbs-shortcode-generator-field-calendar" data-attribute="dropdown">
                <option value="yes"><?php echo __( 'Yes', 'wp-booking-system' ); ?></option>
                <option value="no"><?php echo __( 'No', 'wp-booking-system' ); ?></option>
            </select>

        </div>

        <!-- Column: Jump Switch -->
        <div class="wpbs-col-1-4">

            <label for="modal-add-calendar-shortcode-jump-switch"><?php echo __( 'Use Jump Switch', 'wp-booking-system' ); ?><?php echo wpbs_get_output_tooltip( __( "If set to yes and if the calendar displays multiple months, when the user uses the arrows to navigate the calendar, the calendar will switch the number of months selected, rather than just one month.", 'wp-booking-system' ) ); ?></label>

            <select id="modal-add-calendar-shortcode-jump-switch" class="wpbs-shortcode-generator-field-calendar" data-attribute="jump">
                <option value="yes"><?php echo __( 'Yes', 'wp-booking-system' ); ?></option>
                <option value="no" selected><?php echo __( 'No', 'wp-booking-system' ); ?></option>
            </select>

        </div>

        <!-- Column: Show history -->
        <div class="wpbs-col-1-4">

            <label for="modal-add-calendar-shortcode-history"><?php echo __( 'Show History', 'wp-booking-system' ); ?><?php echo wpbs_get_output_tooltip( __( "This option lets you decide how past dates are being displayed for the user in the front-end.", 'wp-booking-system' ) ); ?></label>

            <select id="modal-add-calendar-shortcode-history" class="wpbs-shortcode-generator-field-calendar" data-attribute="history">
                <option value="1"><?php echo __( 'Display booking history', 'wp-booking-system' ); ?></option>
                <option value="2"><?php echo __( 'Replace booking history with the default legend item', 'wp-booking-system' ); ?></option>
                <option value="3"><?php echo __( 'Use the Booking History Color from the Settings', 'wp-booking-system' ); ?></option>
            </select>

        </div>

    </div><!-- / Row -->

    <!-- Row -->
    <div class="wpbs-row">

        <!-- Column: Display Tooltips -->
        <div class="wpbs-col-1-4">

            <label for="modal-add-calendar-shortcode-tooltip"><?php echo __( 'Display Tooltips', 'wp-booking-system' ); ?></label>

            <select id="modal-add-calendar-shortcode-tooltip" class="wpbs-shortcode-generator-field-calendar" data-attribute="tooltip">
                <option value="1"><?php echo __( 'No', 'wp-booking-system' ); ?></option>
                <option value="2"><?php echo __( 'Yes', 'wp-booking-system' ); ?></option>
                <option value="3"><?php echo __( 'Yes, with red indicator', 'wp-booking-system' ); ?></option>
            </select>

        </div>

        <!-- Column: Highlight Today -->
        <div class="wpbs-col-1-4">

            <label for="modal-add-calendar-shortcode-highlight-today"><?php echo __( 'Highlight Today', 'wp-booking-system' ); ?></label>

            <select id="modal-add-calendar-shortcode-highlight-today" class="wpbs-shortcode-generator-field-calendar" data-attribute="highlighttoday">
                <option value="yes"><?php echo __( 'Yes', 'wp-booking-system' ); ?></option>
                <option value="no" selected><?php echo __( 'No', 'wp-booking-system' ); ?></option>
            </select>

        </div>

        <!-- Column: Show week numbers -->
        <div class="wpbs-col-1-4">

            <label for="modal-add-calendar-shortcode-week-numbers"><?php echo __( 'Show Week Numbers', 'wp-booking-system' ); ?><?php echo wpbs_get_output_tooltip( __( "If set to yes, it will display at the beginning of each week the week's number counted from the beginning of the year.", 'wp-booking-system' ) ); ?></label>

            <select id="modal-add-calendar-shortcode-week-numbers" class="wpbs-shortcode-generator-field-calendar" data-attribute="weeknumbers">
                <option value="yes"><?php echo __( 'Yes', 'wp-booking-system' ); ?></option>
                <option value="no" selected><?php echo __( 'No', 'wp-booking-system' ); ?></option>
            </select>

        </div>

        <div class="wpbs-col-1-4">

            <?php if(wpbs_is_pricing_enabled()): ?>

                <label for="modal-add-calendar-shortcode-show-prices"><?php echo __( 'Show Prices', 'wp-booking-system' ); ?></label>

                <select id="modal-add-calendar-shortcode-show-prices" class="wpbs-shortcode-generator-field-calendar" data-attribute="show_prices">
                    <option value="yes"><?php echo __( 'Yes', 'wp-booking-system' ); ?></option>
                    <option value="no" selected><?php echo __( 'No', 'wp-booking-system' ); ?></option>
                </select>

            <?php endif; ?>

        </div>

    </div><!-- / Row -->

    <h4><?php echo __( 'Form Options', 'wp-booking-system' ); ?></h4>
    <hr />

    

    <!-- Row -->
    <div class="wpbs-row">

        <!-- Column: Form -->
        <div class="wpbs-col-1-4">

            <?php $forms = wpbs_get_forms(array('status' => 'active')); ?>
            
            <label for="modal-add-calendar-shortcode-form"><?php echo __( 'Form', 'wp-booking-system' ); ?></label>

            <select id="modal-add-calendar-shortcode-form" class="wpbs-shortcode-generator-field-calendar" data-attribute="form_id">
                <option value="0"><?php echo __('No Form','wp-booking-system') ?></option>
                <?php
                    foreach( $forms as $form )
                        echo '<option value="' . $form->get('id') . '">' . $form->get('name') . '</option>';
                ?>
            </select>

        </div>

        <!-- Column: Form Position -->
        <div class="wpbs-col-1-4">

            <label for="modal-add-calendar-shortcode-form-position"><?php echo __( 'Form Position', 'wp-booking-system' ); ?></label>

            <select id="modal-add-calendar-shortcode-form-position" class="wpbs-shortcode-generator-field-calendar" data-attribute="form_position">
                <option value="bottom"><?php echo __( 'Bottom', 'wp-booking-system' ); ?></option>
                <option value="side"><?php echo __( 'Side', 'wp-booking-system' ); ?></option>
            </select>

        </div>

        <!-- Column: Auto Accept -->
        <div class="wpbs-col-1-4">

            <label for="modal-add-calendar-shortcode-auto-pending"><?php echo __( 'Auto Accept Bookings', 'wp-booking-system' ); ?> <?php echo wpbs_get_output_tooltip( __( "If set to yes, when a booking is made, the dates in the calendar will automatically be changed to the 'Booked' legend", 'wp-booking-system' ) ); ?></label>

            <select id="modal-add-calendar-shortcode-auto-pending" class="wpbs-shortcode-generator-field-calendar" data-attribute="auto_pending">
                <option value="yes"><?php echo __( 'Yes', 'wp-booking-system' ); ?></option>
                <option value="no"><?php echo __( 'No', 'wp-booking-system' ); ?></option>
            </select>

        </div>

        <!-- Column: Selection Type -->
        <div class="wpbs-col-1-4">

            <label for="modal-add-calendar-shortcode-selection-type"><?php echo __( 'Selection Type', 'wp-booking-system' ); ?> <?php echo wpbs_get_output_tooltip( __( "Change the way the visitor selects dates in the calendar.", 'wp-booking-system' ) ); ?></label>

            <select id="modal-add-calendar-shortcode-selection-type" class="wpbs-shortcode-generator-field-calendar" data-attribute="selection_type">
                <option value="multiple" selected><?php echo __( 'Date Range', 'wp-booking-system' ); ?></option>
                <option value="single"><?php echo __( 'Single Day', 'wp-booking-system' ); ?></option>
            </select>

        </div>

    </div><!-- / Row -->

    <!-- Row -->
    <div class="wpbs-row">

        <!-- Column: Selection Style -->
        <div class="wpbs-col-1-4">

            <label for="modal-add-calendar-shortcode-selection-style"><?php echo __( 'Selection Style', 'wp-booking-system' ); ?> <?php echo wpbs_get_output_tooltip( __( "Change the way the selection of dates looks. Normal will highlight days entirely, while Split will make the first and last day of the selection appear as half days.", 'wp-booking-system' ) ); ?></label>

            <select id="modal-add-calendar-shortcode-selection-style" class="wpbs-shortcode-generator-field-calendar" data-attribute="selection_style">
                <option value="split" selected><?php echo __( 'Split', 'wp-booking-system' ); ?></option>
                <option value="normal"><?php echo __( 'Normal', 'wp-booking-system' ); ?></option>
            </select>

        </div>

        <!-- Column: Minimum Days -->
        <div class="wpbs-col-1-4">

            <label for="modal-add-calendar-shortcode-minimum-days"><?php echo __( 'Minimum Days', 'wp-booking-system' ); ?><?php echo wpbs_get_output_tooltip( __( 'The minimum number of days of a booking. If you are using the "Split" selection type, the number of nights will be counted instead.', 'wp-booking-system' ) ); ?></label>

            <input type="number" value="0" id="modal-add-calendar-shortcode-minimum-days" min="1" class="wpbs-shortcode-generator-field-calendar" data-attribute="minimum_days" />

        </div>

        <!-- Column: Maximum Days -->
        <div class="wpbs-col-1-4">

            <label for="modal-add-calendar-shortcode-maximum-days"><?php echo __( 'Maximum Days', 'wp-booking-system' ); ?><?php echo wpbs_get_output_tooltip( __( 'The maximum number of days of a booking. If you are using the "Split" selection type, the number of nights will be counted instead.', 'wp-booking-system' ) ); ?></label>

            <input type="number" value="0" id="modal-add-calendar-shortcode-maximum-days" min="1" class="wpbs-shortcode-generator-field-calendar" data-attribute="maximum_days" />

        </div>

        

        <!-- Column: Booking Start Day -->
        <div class="wpbs-col-1-4">
            
            <label for="modal-add-calendar-shortcode-booking-start-day"><?php echo __( 'Booking Start Day', 'wp-booking-system' ); ?><?php echo wpbs_get_output_tooltip( __( "Force the booking to start on a specific day.", 'wp-booking-system' ) ); ?></label>

            <select id="modal-add-calendar-shortcode-booking-start-day" class="wpbs-shortcode-generator-field-calendar" data-attribute="booking_start_day">
                <option value="0">-</option>
                <option value="1"><?php echo __( 'Monday', 'wp-booking-system' ); ?></option>
                <option value="2"><?php echo __( 'Tuesday', 'wp-booking-system' ); ?></option>
                <option value="3"><?php echo __( 'Wednesday', 'wp-booking-system' ); ?></option>
                <option value="4"><?php echo __( 'Thursday', 'wp-booking-system' ); ?></option>
                <option value="5"><?php echo __( 'Friday', 'wp-booking-system' ); ?></option>
                <option value="6"><?php echo __( 'Saturday', 'wp-booking-system' ); ?></option>
                <option value="7"><?php echo __( 'Sunday', 'wp-booking-system' ); ?></option>
            </select>

        </div>

    </div><!-- / Row -->

    <!-- Row -->
    <div class="wpbs-row">

        <!-- Column: Booking End Day -->
        <div class="wpbs-col-1-4">
            
            <label for="modal-add-calendar-shortcode-booking-end-day"><?php echo __( 'Booking End Day', 'wp-booking-system' ); ?><?php echo wpbs_get_output_tooltip( __( "Force the booking to end on a specific day.", 'wp-booking-system' ) ); ?></label>

            <select id="modal-add-calendar-shortcode-booking-end-day" class="wpbs-shortcode-generator-field-calendar" data-attribute="booking_end_day">
                <option value="0">-</option>
                <option value="1"><?php echo __( 'Monday', 'wp-booking-system' ); ?></option>
                <option value="2"><?php echo __( 'Tuesday', 'wp-booking-system' ); ?></option>
                <option value="3"><?php echo __( 'Wednesday', 'wp-booking-system' ); ?></option>
                <option value="4"><?php echo __( 'Thursday', 'wp-booking-system' ); ?></option>
                <option value="5"><?php echo __( 'Friday', 'wp-booking-system' ); ?></option>
                <option value="6"><?php echo __( 'Saturday', 'wp-booking-system' ); ?></option>
                <option value="7"><?php echo __( 'Sunday', 'wp-booking-system' ); ?></option>
            </select>

        </div>

        <!-- Column: Show Selected Dates -->
        <div class="wpbs-col-1-4">

            <label for="modal-add-calendar-shortcode-show-date-selection"><?php echo __( 'Show Selected Dates', 'wp-booking-system' ); ?> <?php echo wpbs_get_output_tooltip( __( "If set to yes, the dates selected in the calendar will appear in the form as well. This just provides a visual confirmation of the dates selected.", 'wp-booking-system' ) ); ?></label>

            <select id="modal-add-calendar-shortcode-show-date-selection" class="wpbs-shortcode-generator-field-calendar" data-attribute="show_date_selection">
                <option value="yes"><?php echo __( 'Yes', 'wp-booking-system' ); ?></option>
                <option value="no" selected><?php echo __( 'No', 'wp-booking-system' ); ?></option>
            </select>

        </div>

    </div><!-- / Row -->

    <hr />

    <!-- Shortcode insert -->
    <a href="#" id="wpbs-insert-shortcode-single-calendar" class="button button-primary"><?php echo __( 'Insert Calendar', 'wp-booking-system' ); ?></a>
    <a href="#" class="button button-secondary wpbs-modal-close"><?php echo __( 'Cancel', 'wp-booking-system' ); ?></a>

</div>