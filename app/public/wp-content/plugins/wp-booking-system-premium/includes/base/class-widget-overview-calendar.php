<?php

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

class WPBS_Widget_Overview_Calendar extends WP_Widget
{

    /**
     * Constructor
     *
     */
    public function __construct()
    {

        $widget_ops = array(
            'classname' => 'wpbs_overview_calendar',
            'description' => __('Insert a WP Booking System Overview Calendar', 'wp-booking-system'),
        );

        parent::__construct('wpbs_overview_calendar', 'WP Booking System Overview Calendar', $widget_ops);

    }

    /**
     * Outputs the content of the widget
     *
     * @param array $args
     * @param array $instance
     *
     */
    public function widget($args, $instance)
    {

        // Remove the "wpbs" prefix to have a cleaner code
        $instance = (!empty($instance) && is_array($instance) ? $instance : array());

        foreach ($instance as $key => $value) {

            $instance[str_replace('wpbs_', '', $key)] = $value;
            unset($instance[$key]);

        }

        // Calendar outputter default arguments
        $default_args = wpbs_get_calendar_overview_output_default_args();

        $tooltip = (isset($instance['calendar_tooltip']) && $instance['calendar_tooltip'] !== null) ? $instance['calendar_tooltip'] : '1';

        // Translating values from the shortcode attributes to the calendar arguments
        $args = array(
            'show_legend' => (!empty($instance['show_legend']) && $instance['show_legend'] == 'yes' ? 1 : 0),
            'legend_position' => $instance['legend_position'],
            'show_day_abbreviation' => (!empty($instance['calendar_weeknumbers']) && $instance['calendar_weeknumbers'] == 'yes' ? 1 : 0),
            'current_month' => (!empty($instance['calendar_month']) ? (int) $instance['calendar_month'] : date('n')),
            'current_year' => (!empty($instance['calendar_year']) ? (int) $instance['calendar_year'] : date('Y')),
            'history' => (int) $instance['calendar_history'],
            'show_tooltip' => (int) $tooltip,
            'language' => ($instance['calendar_language'] == 'auto' ? wpbs_get_locale() : $instance['calendar_language']),
        );


        // Calendar arguments
        $calendar_args = wp_parse_args($args, $default_args);

        // Calendars
        if (empty($instance['display_calendars']) || $instance['display_calendars'] == '1') {

            $args = apply_filters('wpbs_calendar_overview_shortcode_all_calendars_args', array('status' => 'active'));

            $calendars = wpbs_get_calendars($args);

        } elseif(isset($instance['selected_calendars']) && is_array($instance['selected_calendars'])) {

            $calendar_ids = array_filter(array_map('trim', $instance['selected_calendars']));

            $args = array(
                'include' => $calendar_ids,
                'orderby' => 'FIELD( id, ' . implode(',', $calendar_ids) . ')',
                'order' => '',
            );

            $calendars = wpbs_get_calendars($args);

        }

        if (empty($calendars)) {

            $output = '<p>' . __('No calendars found.', 'wp-booking-system') . '</p>';

        } else {

            // Initialize the calendar overview outputter
            $calendar_overview_outputter = new WPBS_Calendar_Overview_Outputter($calendars, $calendar_args);

            $output = $calendar_overview_outputter->get_display();

        }

        echo $output;

    }

    /**
     * Outputs the options form on admin
     *
     * @param array $instance The widget options
     *
     */
    public function form($instance)
    {

        global $wpdb;

        $display_calendars = (!empty($instance['wpbs_display_calendars']) ? $instance['wpbs_display_calendars'] : '1');
        $selected_calendars = (!empty($instance['wpbs_selected_calendars']) ? (array) $instance['wpbs_selected_calendars'] : array());
        $show_legend = (!empty($instance['wpbs_show_legend']) ? $instance['wpbs_show_legend'] : 'yes');
        $legend_position = (!empty($instance['wpbs_legend_position']) ? $instance['wpbs_legend_position'] : 'top');
        $calendar_language = (!empty($instance['wpbs_calendar_language']) ? $instance['wpbs_calendar_language'] : 'en');
        $calendar_month = (!empty($instance['wpbs_calendar_month']) ? $instance['wpbs_calendar_month'] : '0');
        $calendar_year = (!empty($instance['wpbs_calendar_year']) ? $instance['wpbs_calendar_year'] : '0');
        $calendar_history = (!empty($instance['wpbs_calendar_history']) ? $instance['wpbs_calendar_history'] : '1');
        $calendar_tooltip = (!empty($instance['wpbs_calendar_tooltip']) ? $instance['wpbs_calendar_tooltip'] : 'no');
        $calendar_weeknrs = (!empty($instance['wpbs_calendar_weeknumbers']) ? $instance['wpbs_calendar_weeknumbers'] : 'no');

        $calendars = wpbs_get_calendars();

        ?>

		<!-- Calendar -->
		<p class="wpbs-display-calendars">
			<label for="<?php echo $this->get_field_id('wpbs_display_calendars'); ?>"><?php echo __('Display Calendars', 'wp-booking-system'); ?></label>

			<select class="wpbs-widget-option-display-calendars widefat" name="<?php echo $this->get_field_name('wpbs_display_calendars'); ?>" id="<?php echo $this->get_field_id('wpbs_display_calendars'); ?>">
				<option value="1" <?php echo ('1' == $display_calendars ? 'selected="selected"' : ''); ?>><?php echo __('All Calendars', 'wp-booking-system'); ?></option>
                <option value="2" <?php echo ('2' == $display_calendars ? 'selected="selected"' : ''); ?>><?php echo __('Selected Calendars', 'wp-booking-system'); ?></option>
			</select>
		</p>

		<!-- Calendar -->
		<p class="wpbs-selected-months <?php echo ($display_calendars == 1) ? 'wpbs-selected-months-hidden' : '';?>">
			<label for="<?php echo $this->get_field_id('wpbs_selected_calendars'); ?>"><?php echo __('Selected Calendars', 'wp-booking-system'); ?></label>

			<select style="height:150px;" name="<?php echo $this->get_field_name('wpbs_selected_calendars'); ?>[]" id="<?php echo $this->get_field_id('wpbs_selected_calendars'); ?>" class="widefat" multiple>
				<?php foreach ($calendars as $calendar): ?>
					<option <?php echo (in_array($calendar->get('id'), $selected_calendars) ? 'selected="selected"' : ''); ?> value="<?php echo $calendar->get('id'); ?>"><?php echo $calendar->get('name'); ?></option>
				<?php endforeach;?>
			</select>
		</p>


		<!-- Show Legend -->
		<p>
			<label for="<?php echo $this->get_field_id('wpbs_show_legend'); ?>"><?php echo __('Display legend', 'wp-booking-system'); ?></label>

			<select name="<?php echo $this->get_field_name('wpbs_show_legend'); ?>" id="<?php echo $this->get_field_id('wpbs_show_legend'); ?>" class="widefat">
				<option value="yes"><?php echo __('Yes', 'wp-booking-system'); ?></option>
				<option value="no" <?php echo ($show_legend == 'no' ? 'selected="selected"' : ''); ?>><?php echo __('No', 'wp-booking-system'); ?></option>
			</select>
		</p>

		<!-- Legend Position -->
		<p>
			<label for="<?php echo $this->get_field_id('wpbs_legend_position'); ?>"><?php echo __('Legend Position', 'wp-booking-system'); ?></label>

			<select name="<?php echo $this->get_field_name('wpbs_legend_position'); ?>" id="<?php echo $this->get_field_id('wpbs_legend_position'); ?>" class="widefat">
				<option <?php echo ($legend_position == 'top' ? 'selected="selected"' : ''); ?> value="top"><?php echo __('Top', 'wp-booking-system'); ?></option>
				<option <?php echo ($legend_position == 'bottom' ? 'selected="selected"' : ''); ?> value="bottom"><?php echo __('Bottom', 'wp-booking-system'); ?></option>
			</select>
		</p>


		<!-- Calendar Month -->
		<p>
			<label for="<?php echo $this->get_field_id('wpbs_calendar_month'); ?>"><?php echo __('Start Month', 'wp-booking-system'); ?></label>

			<select name="<?php echo $this->get_field_name('wpbs_calendar_month'); ?>" id="<?php echo $this->get_field_id('wpbs_calendar_month'); ?>" class="widefat">
				<option <?php echo ($calendar_month == 0 ? 'selected="selected"' : ''); ?> value="0"><?php echo __('Current Month', 'wp-booking-system'); ?></option>
				<option <?php echo ($calendar_month == 1 ? 'selected="selected"' : ''); ?> value="1"><?php echo __('January', 'wp-booking-system'); ?></option>
				<option <?php echo ($calendar_month == 2 ? 'selected="selected"' : ''); ?> value="2"><?php echo __('February', 'wp-booking-system'); ?></option>
				<option <?php echo ($calendar_month == 3 ? 'selected="selected"' : ''); ?> value="3"><?php echo __('March', 'wp-booking-system'); ?></option>
				<option <?php echo ($calendar_month == 4 ? 'selected="selected"' : ''); ?> value="4"><?php echo __('April', 'wp-booking-system'); ?></option>
				<option <?php echo ($calendar_month == 5 ? 'selected="selected"' : ''); ?> value="5"><?php echo __('May', 'wp-booking-system'); ?></option>
				<option <?php echo ($calendar_month == 6 ? 'selected="selected"' : ''); ?> value="6"><?php echo __('June', 'wp-booking-system'); ?></option>
				<option <?php echo ($calendar_month == 7 ? 'selected="selected"' : ''); ?> value="7"><?php echo __('July', 'wp-booking-system'); ?></option>
				<option <?php echo ($calendar_month == 8 ? 'selected="selected"' : ''); ?> value="8"><?php echo __('August', 'wp-booking-system'); ?></option>
				<option <?php echo ($calendar_month == 9 ? 'selected="selected"' : ''); ?> value="9"><?php echo __('September', 'wp-booking-system'); ?></option>
				<option <?php echo ($calendar_month == 10 ? 'selected="selected"' : ''); ?> value="10"><?php echo __('October', 'wp-booking-system'); ?></option>
				<option <?php echo ($calendar_month == 11 ? 'selected="selected"' : ''); ?> value="11"><?php echo __('November', 'wp-booking-system'); ?></option>
				<option <?php echo ($calendar_month == 12 ? 'selected="selected"' : ''); ?> value="12"><?php echo __('December', 'wp-booking-system'); ?></option>
			</select>
		</p>

		<!-- Calendar Year -->
		<p>
			<label for="<?php echo $this->get_field_id('wpbs_calendar_year'); ?>"><?php echo __('Start Year', 'wp-booking-system'); ?></label>

			<select name="<?php echo $this->get_field_name('wpbs_calendar_year'); ?>" id="<?php echo $this->get_field_id('wpbs_calendar_year'); ?>" class="widefat">
				<option value="0"><?php echo __('Current Year', 'wp-booking-system'); ?></option>

				<?php for ($i = date('Y'); $i <= date('Y') + 10; $i++): ?>
					<option <?php echo ($calendar_year == $i ? 'selected="selected"' : ''); ?> value="<?php echo $i; ?>"><?php echo $i; ?></option>
				<?php endfor;?>
			</select>
		</p>

		<!-- Calendar History -->
		<p>
			<label for="<?php echo $this->get_field_id('wpbs_calendar_history'); ?>"><?php echo __('Show history', 'wp-booking-system'); ?></label>

			<select name="<?php echo $this->get_field_name('wpbs_calendar_history'); ?>" id="<?php echo $this->get_field_id('wpbs_calendar_history'); ?>" class="widefat">
				<option <?php echo ($calendar_history == 1 ? 'selected="selected"' : ''); ?> value="1"><?php echo __('Display booking history', 'wp-booking-system'); ?></option>
				<option <?php echo ($calendar_history == 2 ? 'selected="selected"' : ''); ?> value="2"><?php echo __('Replace booking history with the default legend item', 'wp-booking-system'); ?></option>
				<option <?php echo ($calendar_history == 3 ? 'selected="selected"' : ''); ?> value="3"><?php echo __('Use the Booking History Color from the Settings', 'wp-booking-system'); ?></option>
			</select>
		</p>

		<!-- Calendar Tooltip -->
		<p>
			<label for="<?php echo $this->get_field_id('wpbs_calendar_tooltip'); ?>"><?php echo __('Show Tooltip', 'wp-booking-system'); ?></label>

			<select name="<?php echo $this->get_field_name('wpbs_calendar_tooltip'); ?>" id="<?php echo $this->get_field_id('wpbs_calendar_tooltip'); ?>" class="widefat">
				<option <?php echo ($calendar_tooltip == 1 ? 'selected="selected"' : ''); ?> value="1"><?php echo __('No', 'wp-booking-system'); ?></option>
				<option <?php echo ($calendar_tooltip == 2 ? 'selected="selected"' : ''); ?> value="2"><?php echo __('Yes', 'wp-booking-system'); ?></option>
				<option <?php echo ($calendar_tooltip == 3 ? 'selected="selected"' : ''); ?> value="3"><?php echo __('Yes, with red indicator', 'wp-booking-system'); ?></option>
			</select>
        </p>

        <!-- Calendar Weeknumbers -->
		<p>
			<label for="<?php echo $this->get_field_id('wpbs_calendar_weeknumbers'); ?>"><?php echo __('Show Day Abbreviations', 'wp-booking-system'); ?></label>

			<select name="<?php echo $this->get_field_name('wpbs_calendar_weeknumbers'); ?>" id="<?php echo $this->get_field_id('wpbs_calendar_weeknumbers'); ?>" class="widefat">
				<option <?php echo ($calendar_weeknrs == 'no' ? 'selected="selected"' : ''); ?> value="no"><?php echo __('No', 'wp-booking-system'); ?></option>
				<option <?php echo ($calendar_weeknrs == 'yes' ? 'selected="selected"' : ''); ?> value="yes"><?php echo __('Yes', 'wp-booking-system'); ?></option>
			</select>
		</p>


		<!-- Calendar Language -->
		<p>
			<label for="<?php echo $this->get_field_id('wpbs_calendar_language'); ?>"><?php echo __('Language', 'wp-booking-system'); ?></label>

			<select name="<?php echo $this->get_field_name('wpbs_calendar_language'); ?>" id="<?php echo $this->get_field_id('wpbs_calendar_language'); ?>" class="widefat">
				<?php
		$settings = get_option('wpbs_settings', array());
        $languages = wpbs_get_languages();
        $active_languages = (!empty($settings['active_languages']) ? $settings['active_languages'] : array());
        ?>

				<option value="auto"><?php echo __('Auto (let WP choose)', 'wp-booking-system'); ?></option>

				<?php foreach ($active_languages as $code): ?>
					<option value="<?php echo esc_attr($code); ?>" <?php echo ($calendar_language == $code ? 'selected="selected"' : ''); ?>><?php echo (!empty($languages[$code]) ? $languages[$code] : ''); ?></option>
				<?php endforeach;?>
			</select>
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
    public function update($new_instance, $old_instance)
    {

        return $new_instance;

    }

}

add_action('widgets_init', function () {
    register_widget('WPBS_Widget_Overview_Calendar');
});