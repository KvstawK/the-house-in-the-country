<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

class WPBS_S_Widget_Calendar_Search extends WP_Widget {

	/**
	 * Constructor
	 *
	 */
	public function __construct() {

		$widget_ops = array( 
			'classname'   => 'wpbs_s_calendar_search',
			'description' => __( 'Insert a WP Booking System Search Widget', 'wp-booking-system-search'),
		);

		parent::__construct( 'wpbs_s_calendar_search', 'WP Booking System Search', $widget_ops );

	}

	/**
	 * Outputs the content of the widget
	 *
	 * @param array $args
	 * @param array $instance
	 *
	 */
	public function widget( $args, $instance ) {

		// Remove the "wpbs" prefix to have a cleaner code
		$instance = ( ! empty( $instance ) && is_array( $instance ) ? $instance : array() );

		foreach( $instance as $key => $value ) {

			$instance[ str_replace( 'wpbs_', '', $key ) ] = $value;
			unset( $instance[$key] );

		}

        if( ! empty( $instance['display_calendars'] ) && $instance['display_calendars'] == 1){
            $calendars = 'all';
        }

        if( ! empty( $instance['display_calendars'] ) && $instance['display_calendars'] == 2){
            if(!empty($instance['calendars'])){
                $calendars = implode(',', $instance['calendars']);
            } else {
                $calendars = 'all';
            }
        }
		
		$args = array(
			'calendars' => $calendars,
			'language'  => ( ! empty( $instance['language'] ) ? ( $instance['language'] == 'auto' ? wpbs_get_locale() : $instance['language'] ) : 'en' ),
			'title'  => ( ! empty( $instance['title'] ) ? $instance['title'] : 'yes' ),
			'start_day'  => ( ! empty( $instance['start_day'] ) ? $instance['start_day'] : '10' ),
			'mark_selection'  => ( ! empty( $instance['mark_selection'] ) ? $instance['mark_selection'] : 'yes' ),
			'selection_type'  => ( ! empty( $instance['selection_type'] ) ? $instance['selection_type'] : 'multiple' ),
			'minimum_stay'  => ( ! empty( $instance['minimum_stay'] ) ? $instance['minimum_stay'] : '0' ),
			'featured_image'  => ( ! empty( $instance['featured_image'] ) ? $instance['featured_image'] : 'no' ),
			'starting_price'  => ( ! empty( $instance['starting_price'] ) ? $instance['starting_price'] : 'no' ),
			'show_results_on_load'  => ( ! empty( $instance['show_results_on_load'] ) ? $instance['show_results_on_load'] : 'no' ),
			'results_layout'  => ( ! empty( $instance['results_layout'] ) ? $instance['results_layout'] : 'list' ),
			'results_per_page'  => ( ! empty( $instance['results_per_page'] ) ? $instance['results_per_page'] : '10' ),
			'redirect'  => ( ! empty( $instance['redirect'] ) ? $instance['redirect'] : '0' ),
		);

        // Shortcode default attributes
        $default_args = wpbs_s_get_search_widget_default_args();

        // Shortcode attributes
        $args = shortcode_atts($default_args, $args);

        $search_widget_outputter = new WPBS_S_Search_Widget_Outputter($args);

        echo $search_widget_outputter->get_display();
	}

	/**
	 * Outputs the options form on admin
	 *
	 * @param array $instance The widget options
	 *
	 */
	public function form( $instance ) {
		
		global $wpdb;
        $calendar_display       = ( ! empty( $instance['wpbs_display_calendars'] ) ? $instance['wpbs_display_calendars'] : 1 );
        $calendar_ids       = ( ! empty( $instance['wpbs_calendars'] ) ? $instance['wpbs_calendars'] : array() );
		$widget_title       = ( ! empty( $instance['wpbs_title'] ) ? $instance['wpbs_title'] : 'yes' );
		$mark_selection       = ( ! empty( $instance['wpbs_mark_selection'] ) ? $instance['wpbs_mark_selection'] : 'yes' );
		$selection_type       = ( ! empty( $instance['wpbs_selection_type'] ) ? $instance['wpbs_selection_type'] : 'multiple' );
		$minimum_stay       = ( ! empty( $instance['wpbs_minimum_stay'] ) ? $instance['wpbs_minimum_stay'] : '0' );
		$featured_image       = ( ! empty( $instance['wpbs_featured_image'] ) ? $instance['wpbs_featured_image'] : 'no' );
		$starting_price       = ( ! empty( $instance['wpbs_starting_price'] ) ? $instance['wpbs_starting_price'] : 'no' );
		$show_results_on_load       = ( ! empty( $instance['wpbs_show_results_on_load'] ) ? $instance['wpbs_show_results_on_load'] : 'no' );
		$results_layout       = ( ! empty( $instance['wpbs_results_layout'] ) ? $instance['wpbs_results_layout'] : 'list' );
		$results_per_page       = ( ! empty( $instance['wpbs_results_per_page'] ) ? $instance['wpbs_results_per_page'] : '10' );
		$redirect       = ( ! empty( $instance['wpbs_redirect'] ) ? $instance['wpbs_redirect'] : '' );
		$booking_start_day = (!empty($instance['wpbs_start_day']) ? $instance['wpbs_start_day'] : 1);
        
        $calendar_language = ( ! empty( $instance['wpbs_language'] ) ? $instance['wpbs_language'] : 'en' );
        
        $calendars = wpbs_get_calendars(array('status' => 'active'));

        ?>

        <!-- Calendar -->
		<p class="wpbs-widget-display-calendars-select">
			<label for="<?php echo $this->get_field_id('wpbs_display_calendars'); ?>"><?php echo __( 'Calendars', 'wp-booking-system-search'); ?></label>

			<select name="<?php echo $this->get_field_name('wpbs_display_calendars'); ?>" id="<?php echo $this->get_field_id('wpbs_display_calendars'); ?>" class="widefat">
				<option value="1" <?php echo ( $calendar_display == 1 ? 'selected="selected"' : '' ); ?>><?php echo __('All Calendars', 'wp-booking-system-search'); ?></option>
                <option value="2" <?php echo ( $calendar_display == 2 ? 'selected="selected"' : '' ); ?>><?php echo __('Selected Calendars', 'wp-booking-system-search'); ?></option>
			</select>
		</p>
        
        <!-- Calendar -->
		<p class="wpbs-chosen-wrap <?php echo (empty($calendar_display) || $calendar_display == 1) ? 'wpbs-element-disabled' : '';?>">
			<label for="<?php echo $this->get_field_id('wpbs_calendars'); ?>"><?php echo __( 'Calendars', 'wp-booking-system-search'); ?></label>

			<select multiple="multiple" name="<?php echo $this->get_field_name('wpbs_calendars'); ?>[]" id="<?php echo $this->get_field_id('wpbs_calendars'); ?>" class="widefat wpbs-chosen">
				<?php foreach( $calendars as $calendar ):?>
					<option <?php echo ( in_array($calendar->get('id'), $calendar_ids) ? 'selected="selected"' : '' );?> value="<?php echo $calendar->get('id'); ?>"><?php echo $calendar->get('name'); ?></option>
				<?php endforeach;?>
			</select>
		</p>


		<!-- Calendar Language -->
		<p>
			<label for="<?php echo $this->get_field_id('wpbs_language'); ?>"><?php echo __( 'Language', 'wp-booking-system-search');?></label>

			<select name="<?php echo $this->get_field_name('wpbs_language'); ?>" id="<?php echo $this->get_field_id('wpbs_language'); ?>" class="widefat">
				<?php
					$settings 		  = get_option( 'wpbs_settings', array() );
					$languages 		  = wpbs_get_languages();
					$active_languages = ( ! empty( $settings['active_languages'] ) ? $settings['active_languages'] : array() );
				?>

				<option value="auto"><?php echo __( 'Auto (let WP choose)', 'wp-booking-system-search');?></option>

				<?php foreach( $active_languages as $code ):?>
					<option value="<?php echo esc_attr( $code ); ?>" <?php echo ( $calendar_language == $code ? 'selected="selected"' : '' ); ?>><?php echo ( ! empty( $languages[$code] ) ? $languages[$code] : '' ); ?></option>
				<?php endforeach; ?>
			</select>
		</p>

		<!-- Booking Start Day -->
		<p>
			<label for="<?php echo $this->get_field_id('wpbs_start_day'); ?>"><?php echo __('Start Day of the Week', 'wp-booking-system-search'); ?></label>

			<select name="<?php echo $this->get_field_name('wpbs_start_day'); ?>" id="<?php echo $this->get_field_id('wpbs_start_day'); ?>" class="widefat">
				<option value="1" <?php echo ($booking_start_day == 1 ? 'selected="selected"' : ''); ?>><?php echo __('Monday', 'wp-booking-system-search'); ?></option>
				<option value="2" <?php echo ($booking_start_day == 2 ? 'selected="selected"' : ''); ?>><?php echo __('Tuesday', 'wp-booking-system-search'); ?></option>
				<option value="3" <?php echo ($booking_start_day == 3 ? 'selected="selected"' : ''); ?>><?php echo __('Wednesday', 'wp-booking-system-search'); ?></option>
				<option value="4" <?php echo ($booking_start_day == 4 ? 'selected="selected"' : ''); ?>><?php echo __('Thursday', 'wp-booking-system-search'); ?></option>
				<option value="5" <?php echo ($booking_start_day == 5 ? 'selected="selected"' : ''); ?>><?php echo __('Friday', 'wp-booking-system-search'); ?></option>
				<option value="6" <?php echo ($booking_start_day == 6 ? 'selected="selected"' : ''); ?>><?php echo __('Saturday', 'wp-booking-system-search'); ?></option>
				<option value="7" <?php echo ($booking_start_day == 7 ? 'selected="selected"' : ''); ?>><?php echo __('Sunday', 'wp-booking-system-search'); ?></option>
			</select>
		</p>

		<!-- Show Widget Title -->
		<p>
			<label for="<?php echo $this->get_field_id('wpbs_title'); ?>"><?php echo __( 'Widget Title', 'wp-booking-system-search');?></label>

			<select name="<?php echo $this->get_field_name('wpbs_title'); ?>" id="<?php echo $this->get_field_id('wpbs_title'); ?>" class="widefat">
				
				<option value="yes" <?php echo ( $widget_title == 'yes' ? 'selected="selected"' : '' ); ?>><?php echo __('Yes', 'wp-booking-system-search') ?></option>
				<option value="no" <?php echo ( $widget_title == 'no' ? 'selected="selected"' : '' ); ?>><?php echo __('No', 'wp-booking-system-search') ?></option>
			</select>
		</p>

		<!-- Mark Selection -->
		<p>
			<label for="<?php echo $this->get_field_id('wpbs_mark_selection'); ?>"><?php echo __( 'Automatically Mark Selection', 'wp-booking-system-search');?></label>

			<select name="<?php echo $this->get_field_name('wpbs_mark_selection'); ?>" id="<?php echo $this->get_field_id('wpbs_mark_selection'); ?>" class="widefat">
				
				<option value="yes" <?php echo ( $mark_selection == 'yes' ? 'selected="selected"' : '' ); ?>><?php echo __('Yes', 'wp-booking-system-search') ?></option>
				<option value="no" <?php echo ( $mark_selection == 'no' ? 'selected="selected"' : '' ); ?>><?php echo __('No', 'wp-booking-system-search') ?></option>
			</select>
		</p>

		<!-- Selection Type -->
		<p>
			<label for="<?php echo $this->get_field_id('wpbs_selection_type'); ?>"><?php echo __( 'Selection Type', 'wp-booking-system-search');?></label>

			<select name="<?php echo $this->get_field_name('wpbs_selection_type'); ?>" id="<?php echo $this->get_field_id('wpbs_selection_type'); ?>" class="widefat">
				
				<option value="multiple" <?php echo ( $selection_type == 'multiple' ? 'selected="selected"' : '' ); ?>><?php echo __('Date Range', 'wp-booking-system-search') ?></option>
				<option value="single" <?php echo ( $selection_type == 'single' ? 'selected="selected"' : '' ); ?>><?php echo __('Single Day', 'wp-booking-system-search') ?></option>
			</select>
		</p>

		<!-- Minimum Stay -->
		<p>
			<label for="<?php echo $this->get_field_id('wpbs_minimum_stay'); ?>"><?php echo __( 'Minimum Stay', 'wp-booking-system-search');?></label>
			<input type="text" name="<?php echo $this->get_field_name('wpbs_minimum_stay'); ?>" id="<?php echo $this->get_field_id('wpbs_minimum_stay'); ?>" class="widefat" value="<?php echo $minimum_stay;?>" />
		</p>

		<!-- Featured Image -->
		<p>
			<label for="<?php echo $this->get_field_id('wpbs_featured_image'); ?>"><?php echo __( 'Show Featured Image', 'wp-booking-system-search');?></label>

			<select name="<?php echo $this->get_field_name('wpbs_featured_image'); ?>" id="<?php echo $this->get_field_id('wpbs_featured_image'); ?>" class="widefat">
				
				<option value="yes" <?php echo ( $featured_image == 'yes' ? 'selected="selected"' : '' ); ?>><?php echo __('Yes', 'wp-booking-system-search') ?></option>
				<option value="no" <?php echo ( $featured_image == 'no' ? 'selected="selected"' : '' ); ?>><?php echo __('No', 'wp-booking-system-search') ?></option>
			</select>
		</p>

		<!-- Starting Price -->
		<p>
			<label for="<?php echo $this->get_field_id('wpbs_starting_price'); ?>"><?php echo __( 'Show Starting Price', 'wp-booking-system-search');?></label>

			<select name="<?php echo $this->get_field_name('wpbs_starting_price'); ?>" id="<?php echo $this->get_field_id('wpbs_starting_price'); ?>" class="widefat">
				
				<option value="yes" <?php echo ( $starting_price == 'yes' ? 'selected="selected"' : '' ); ?>><?php echo __('Yes', 'wp-booking-system-search') ?></option>
				<option value="no" <?php echo ( $starting_price == 'no' ? 'selected="selected"' : '' ); ?>><?php echo __('No', 'wp-booking-system-search') ?></option>
			</select>
		</p>

		<!-- Show results on page load -->
		<p>
			<label for="<?php echo $this->get_field_id('wpbs_show_results_on_load'); ?>"><?php echo __( 'Show Results on load', 'wp-booking-system-search');?></label>

			<select name="<?php echo $this->get_field_name('wpbs_show_results_on_load'); ?>" id="<?php echo $this->get_field_id('wpbs_show_results_on_load'); ?>" class="widefat">
				
				<option value="yes" <?php echo ( $show_results_on_load == 'yes' ? 'selected="selected"' : '' ); ?>><?php echo __('Yes', 'wp-booking-system-search') ?></option>
				<option value="no" <?php echo ( $show_results_on_load == 'no' ? 'selected="selected"' : '' ); ?>><?php echo __('No', 'wp-booking-system-search') ?></option>
			</select>
		</p>

		<!-- Results Layout -->
		<p>
			<label for="<?php echo $this->get_field_id('wpbs_results_layout'); ?>"><?php echo __( 'Results Layout', 'wp-booking-system-search');?></label>

			<select name="<?php echo $this->get_field_name('wpbs_results_layout'); ?>" id="<?php echo $this->get_field_id('wpbs_results_layout'); ?>" class="widefat">
				
				<option value="list" <?php echo ( $results_layout == 'list' ? 'selected="selected"' : '' ); ?>><?php echo __('List', 'wp-booking-system-search') ?></option>
				<option value="grid" <?php echo ( $results_layout == 'grid' ? 'selected="selected"' : '' ); ?>><?php echo __('Grid', 'wp-booking-system-search') ?></option>
			</select>
		</p>

		<!-- Results per Page -->
		<p>
			<label for="<?php echo $this->get_field_id('wpbs_results_per_page'); ?>"><?php echo __( 'Results per Page', 'wp-booking-system-search');?></label>
			<input type="text" name="<?php echo $this->get_field_name('wpbs_results_per_page'); ?>" id="<?php echo $this->get_field_id('wpbs_results_per_page'); ?>" class="widefat" value="<?php echo $results_per_page;?>" />
			
		</p>

		<!-- Redirect -->
		<p>
			<label for="<?php echo $this->get_field_id('wpbs_redirect'); ?>"><?php echo __( 'Redirect', 'wp-booking-system-search');?></label>
			<input type="text" name="<?php echo $this->get_field_name('wpbs_redirect'); ?>" id="<?php echo $this->get_field_id('wpbs_redirect'); ?>" class="widefat" value="<?php echo $redirect;?>" />
			
		</p>

        <?php

    }


	/**
	 * Processing widget options on save
	 *
	 * @param array $new_instance The new options
	 * @param array $old_instance The previous options
	 *
	 * @return array
	 *
	 */
	public function update( $new_instance, $old_instance ) {
		
		return $new_instance;

	}

}

add_action( 'widgets_init', function() {
	register_widget( 'WPBS_S_Widget_Calendar_Search' );
});