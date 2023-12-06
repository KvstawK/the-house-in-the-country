<?php

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

class WPBS_Widget_Calendar extends WP_Widget
{

    /**
     * Constructor
     *
     */
    public function __construct()
    {

        $widget_ops = array(
            'classname' => 'wpbs_calendar',
            'description' => __('Insert a WP Booking System Calendar', 'wp-booking-system'),
        );

        parent::__construct('wpbs_calendar', 'WP Booking System Calendar', $widget_ops);

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

		if(!isset($instance['select_calendar'])){
			return false;
		}

        $calendar = wpbs_get_calendar(absint($instance['select_calendar']));

        if (is_null($calendar)) {
            return false;
        }

        $calendar_args = array(
            'show_title' => (!empty($instance['show_title']) && $instance['show_title'] == 'yes' ? 1 : 0),
            'show_legend' => (!empty($instance['show_legend']) && $instance['show_legend'] == 'yes' ? 1 : 0),
            'legend_position' => (!empty($instance['legend_position']) ? $instance['legend_position'] : 'top'),
            'start_weekday' => (!empty($instance['calendar_start']) ? absint($instance['calendar_start']) : 1),
            'months_to_show' => (!empty($instance['calendar_view']) ? absint($instance['calendar_view']) : 1),
            'language' => (!empty($instance['calendar_language']) ? ($instance['calendar_language'] == 'auto' ? wpbs_get_locale() : $instance['calendar_language']) : 'en'),
            'current_year' => (!empty($instance['calendar_year']) ? $instance['calendar_year'] : date('Y')),
            'current_month' => (!empty($instance['calendar_month']) ? $instance['calendar_month'] : date('n')),
            'history' => (!empty($instance['calendar_history']) ? absint($instance['calendar_history']) : 1),
            'show_tooltip' => (!empty($instance['calendar_tooltip']) ? absint($instance['calendar_tooltip']) : 1),
            'jump_months' => (!empty($instance['calendar_jump']) && $instance['calendar_jump'] == 'yes' ? 1 : 0),
            'highlight_today' => (!empty($instance['calendar_highlighttoday']) && $instance['calendar_highlighttoday'] == 'yes' ? 1 : 0),
            'show_week_numbers' => (!empty($instance['calendar_weeknumbers']) && $instance['calendar_weeknumbers'] == 'yes' ? 1 : 0),
            'show_selector_navigation' => (!empty($instance['show_dropdown']) && $instance['show_dropdown'] == 'yes' ? 1 : 0),
            'show_prices' => (!empty($instance['calendar_show_prices']) && $instance['calendar_show_prices'] == 'yes' ? 1 : 0),
			'form_position' => (!empty($instance['form_form_position']) && $instance['form_form_position'] ? $instance['form_form_position'] : 'bottom'),
        );

        $form_id = (!empty($instance['select_form']) ? absint($instance['select_form']) : 0);

        $output = '<div class="wpbs-main-wrapper wpbs-main-wrapper-calendar-' . $instance['select_calendar'] . ' wpbs-main-wrapper-form-' . $instance['select_form'] . '">';

		$calendar_args = apply_filters('wpbs_calendar_shortcode_args', $calendar_args, $calendar->get('id'));

        // Initialize the calendar outputter
        $calendar_outputter = new WPBS_Calendar_Outputter($calendar, $calendar_args);

        $output .= $calendar_outputter->get_display();

        // Initialize the form outputter
        if ($form_id !== 0) {

            $form = wpbs_get_form($form_id);

            $form_args = array(
                'minimum_days' => (!empty($instance['form_minimum_days']) ? absint($instance['form_minimum_days']) : 0),
                'maximum_days' => (!empty($instance['form_maximum_days']) ? absint($instance['form_maximum_days']) : 0),
                'booking_start_day' => (!empty($instance['form_booking_start_day']) ? absint($instance['form_booking_start_day']) : 0),
                'booking_end_day' => (!empty($instance['form_booking_end_day']) ? absint($instance['form_booking_end_day']) : 0),
                'selection_type' => (!empty($instance['form_selection_type']) ? $instance['form_selection_type'] : 'multiple'),
                'selection_style' => (!empty($instance['form_selection_style']) ? $instance['form_selection_style'] : 'split'),
                'auto_pending' => (!empty($instance['form_auto_pending']) && $instance['form_auto_pending'] == 'yes' ? 1 : 0),
                'show_date_selection' => (!empty($instance['form_show_date_selection']) && $instance['form_show_date_selection'] == 'yes' ? 1 : 0),
                'language' => (!empty($instance['calendar_language']) ? ($instance['calendar_language'] == 'auto' ? wpbs_get_locale() : $instance['calendar_language']) : 'en'),
            );

            $form_outputter = new WPBS_Form_Outputter($form, $form_args, array(), $calendar->get('id'));
            $output .= $form_outputter->get_display();

        }

        $output .= '</div>';

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

        $calendar_id = (!empty($instance['wpbs_select_calendar']) ? $instance['wpbs_select_calendar'] : 0);
        $show_title = (!empty($instance['wpbs_show_title']) ? $instance['wpbs_show_title'] : 'yes');
        $show_legend = (!empty($instance['wpbs_show_legend']) ? $instance['wpbs_show_legend'] : 'yes');
        $legend_position = (!empty($instance['wpbs_legend_position']) ? $instance['wpbs_legend_position'] : 'top');
        $show_dropdown = (!empty($instance['wpbs_show_dropdown']) ? $instance['wpbs_show_dropdown'] : 'yes');
        $calendar_view = (!empty($instance['wpbs_calendar_view']) ? $instance['wpbs_calendar_view'] : '1');
        $calendar_start = (!empty($instance['wpbs_calendar_start']) ? $instance['wpbs_calendar_start'] : '1');
        $calendar_language = (!empty($instance['wpbs_calendar_language']) ? $instance['wpbs_calendar_language'] : 'en');
        $calendar_month = (!empty($instance['wpbs_calendar_month']) ? $instance['wpbs_calendar_month'] : '0');
        $calendar_year = (!empty($instance['wpbs_calendar_year']) ? $instance['wpbs_calendar_year'] : '0');
        $calendar_history = (!empty($instance['wpbs_calendar_history']) ? $instance['wpbs_calendar_history'] : '1');
        $calendar_tooltip = (!empty($instance['wpbs_calendar_tooltip']) ? $instance['wpbs_calendar_tooltip'] : 'no');
        $calendar_weeknrs = (!empty($instance['wpbs_calendar_weeknumbers']) ? $instance['wpbs_calendar_weeknumbers'] : 'no');
        $calendar_jump = (!empty($instance['wpbs_calendar_jump']) ? $instance['wpbs_calendar_jump'] : 'no');
        $calendar_today = (!empty($instance['wpbs_calendar_highlighttoday']) ? $instance['wpbs_calendar_highlighttoday'] : 'no');
        $show_prices = (!empty($instance['wpbs_calendar_show_prices']) ? $instance['wpbs_calendar_show_prices'] : 'no');

        $form_id = (!empty($instance['wpbs_select_form']) ? $instance['wpbs_select_form'] : 0);
        $auto_pending = (!empty($instance['wpbs_form_auto_pending']) ? $instance['wpbs_form_auto_pending'] : 'yes');
        $selection_type = (!empty($instance['wpbs_form_selection_type']) ? $instance['wpbs_form_selection_type'] : 'multiple');
        $selection_style = (!empty($instance['wpbs_form_selection_style']) ? $instance['wpbs_form_selection_style'] : 'split');
        $minimum_days = (!empty($instance['wpbs_form_minimum_days']) ? $instance['wpbs_form_minimum_days'] : 0);
        $show_date_selection = (!empty($instance['wpbs_form_show_date_selection']) ? $instance['wpbs_form_show_date_selection'] : 'no');
        $form_position = (!empty($instance['wpbs_form_form_position']) ? $instance['wpbs_form_form_position'] : 'bottom');
        $maximum_days = (!empty($instance['wpbs_form_maximum_days']) ? $instance['wpbs_form_maximum_days'] : 0);
        $booking_start_day = (!empty($instance['wpbs_form_booking_start_day']) ? $instance['wpbs_form_booking_start_day'] : 0);
        $booking_end_day = (!empty($instance['wpbs_form_booking_end_day']) ? $instance['wpbs_form_booking_end_day'] : 0);

        $calendars = wpbs_get_calendars();

        $forms = wpbs_get_forms();

        ?>

        <!-- Calendar -->
		<p>
			<label for="<?php echo $this->get_field_id('wpbs_select_calendar'); ?>"><?php echo __('Calendar', 'wp-booking-system'); ?></label>

			<select name="<?php echo $this->get_field_name('wpbs_select_calendar'); ?>" id="<?php echo $this->get_field_id('wpbs_select_calendar'); ?>" class="widefat">
				<?php foreach ($calendars as $calendar): ?>
					<option <?php echo ($calendar->get('id') == $calendar_id ? 'selected="selected"' : ''); ?> value="<?php echo $calendar->get('id'); ?>"><?php echo $calendar->get('name'); ?></option>
				<?php endforeach;?>
			</select>
		</p>

		<!-- Show Title -->
		<p>
			<label for="<?php echo $this->get_field_id('wpbs_show_title'); ?>"><?php echo __('Display title', 'wp-booking-system'); ?></label>

			<select name="<?php echo $this->get_field_name('wpbs_show_title'); ?>" id="<?php echo $this->get_field_id('wpbs_show_title'); ?>" class="widefat">
				<option value="yes"><?php echo __('Yes', 'wp-booking-system'); ?></option>
				<option value="no" <?php echo ($show_title == 'no' ? 'selected="selected"' : ''); ?>><?php echo __('No', 'wp-booking-system'); ?></option>
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
				<option <?php echo ($legend_position == 'side' ? 'selected="selected"' : ''); ?> value="side"><?php echo __('Side', 'wp-booking-system'); ?></option>
				<option <?php echo ($legend_position == 'top' ? 'selected="selected"' : ''); ?> value="top"><?php echo __('Top', 'wp-booking-system'); ?></option>
				<option <?php echo ($legend_position == 'bottom' ? 'selected="selected"' : ''); ?> value="bottom"><?php echo __('Bottom', 'wp-booking-system'); ?></option>
			</select>
		</p>

		<!-- Show Dropdown -->
		<p>
			<label for="<?php echo $this->get_field_id('wpbs_show_dropdown'); ?>"><?php echo __('Display dropdown', 'wp-booking-system'); ?></label>

			<select name="<?php echo $this->get_field_name('wpbs_show_dropdown'); ?>" id="<?php echo $this->get_field_id('wpbs_show_dropdown'); ?>" class="widefat">
				<option value="yes"><?php echo __('Yes', 'wp-booking-system'); ?></option>
				<option value="no" <?php echo ($show_dropdown == 'no' ? 'selected="selected"' : ''); ?>><?php echo __('No', 'wp-booking-system'); ?></option>
			</select>
		</p>

		<!-- Calendar Start -->
		<p>
			<label for="<?php echo $this->get_field_id('wpbs_calendar_start'); ?>"><?php echo __('Week starts on', 'wp-booking-system'); ?></label>

			<select name="<?php echo $this->get_field_name('wpbs_calendar_start'); ?>" id="<?php echo $this->get_field_id('wpbs_calendar_start'); ?>" class="widefat">
				<option value="1" <?php echo ($calendar_start == 1 ? 'selected="selected"' : ''); ?>><?php echo __('Monday', 'wp-booking-system'); ?></option>
				<option value="2" <?php echo ($calendar_start == 2 ? 'selected="selected"' : ''); ?>><?php echo __('Tuesday', 'wp-booking-system'); ?></option>
				<option value="3" <?php echo ($calendar_start == 3 ? 'selected="selected"' : ''); ?>><?php echo __('Wednesday', 'wp-booking-system'); ?></option>
				<option value="4" <?php echo ($calendar_start == 4 ? 'selected="selected"' : ''); ?>><?php echo __('Thursday', 'wp-booking-system'); ?></option>
				<option value="5" <?php echo ($calendar_start == 5 ? 'selected="selected"' : ''); ?>><?php echo __('Friday', 'wp-booking-system'); ?></option>
				<option value="6" <?php echo ($calendar_start == 6 ? 'selected="selected"' : ''); ?>><?php echo __('Saturday', 'wp-booking-system'); ?></option>
				<option value="7" <?php echo ($calendar_start == 7 ? 'selected="selected"' : ''); ?>><?php echo __('Sunday', 'wp-booking-system'); ?></option>
			</select>
		</p>

		<!-- Months to Display -->
		<p>
			<label for="<?php echo $this->get_field_id('wpbs_calendar_view'); ?>"><?php echo __('Months to display', 'wp-booking-system'); ?></label>

			<select name="<?php echo $this->get_field_name('wpbs_calendar_view'); ?>" id="<?php echo $this->get_field_id('wpbs_calendar_view'); ?>" class="widefat">
				<?php for ($i = 1; $i <= 24; $i++): ?>
					<option value="<?php echo $i; ?>" <?php echo ($calendar_view == $i ? 'selected="selected"' : ''); ?>><?php echo $i; ?></option>
				<?php endfor;?>
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
			<label for="<?php echo $this->get_field_id('wpbs_calendar_weeknumbers'); ?>"><?php echo __('Show Week Numbers', 'wp-booking-system'); ?></label>

			<select name="<?php echo $this->get_field_name('wpbs_calendar_weeknumbers'); ?>" id="<?php echo $this->get_field_id('wpbs_calendar_weeknumbers'); ?>" class="widefat">
				<option <?php echo ($calendar_weeknrs == 'no' ? 'selected="selected"' : ''); ?> value="no"><?php echo __('No', 'wp-booking-system'); ?></option>
				<option <?php echo ($calendar_weeknrs == 'yes' ? 'selected="selected"' : ''); ?> value="yes"><?php echo __('Yes', 'wp-booking-system'); ?></option>
			</select>
		</p>

		<!-- Calendar Jump -->
		<p>
			<label for="<?php echo $this->get_field_id('wpbs_calendar_jump'); ?>"><?php echo __('Jump Switch', 'wp-booking-system'); ?></label>

			<select name="<?php echo $this->get_field_name('wpbs_calendar_jump'); ?>" id="<?php echo $this->get_field_id('wpbs_calendar_jump'); ?>" class="widefat">
				<option <?php echo ($calendar_jump == 'no' ? 'selected="selected"' : ''); ?> value="no"><?php echo __('No', 'wp-booking-system'); ?></option>
				<option <?php echo ($calendar_jump == 'yes' ? 'selected="selected"' : ''); ?> value="yes"><?php echo __('Yes', 'wp-booking-system'); ?></option>
			</select>
		</p>

		<!-- Highlight Today -->
		<p>
			<label for="<?php echo $this->get_field_id('wpbs_calendar_highlighttoday'); ?>"><?php echo __('Highlight Today', 'wp-booking-system'); ?></label>

			<select name="<?php echo $this->get_field_name('wpbs_calendar_highlighttoday'); ?>" id="<?php echo $this->get_field_id('wpbs_calendar_highlighttoday'); ?>" class="widefat">
				<option <?php echo ($calendar_today == 'no' ? 'selected="selected"' : ''); ?> value="no"><?php echo __('No', 'wp-booking-system'); ?></option>
				<option <?php echo ($calendar_today == 'yes' ? 'selected="selected"' : ''); ?> value="yes"><?php echo __('Yes', 'wp-booking-system'); ?></option>
			</select>
		</p>

		<?php if(wpbs_is_pricing_enabled()): ?>
			<!-- Show Prices -->
			<p>
				<label for="<?php echo $this->get_field_id('wpbs_calendar_show_prices'); ?>"><?php echo __('Show Prices', 'wp-booking-system'); ?></label>

				<select name="<?php echo $this->get_field_name('wpbs_calendar_show_prices'); ?>" id="<?php echo $this->get_field_id('wpbs_calendar_show_prices'); ?>" class="widefat">
					<option <?php echo ($show_prices == 'no' ? 'selected="selected"' : ''); ?> value="no"><?php echo __('No', 'wp-booking-system'); ?></option>
					<option <?php echo ($show_prices == 'yes' ? 'selected="selected"' : ''); ?> value="yes"><?php echo __('Yes', 'wp-booking-system'); ?></option>
				</select>
			</p>
		<?php endif; ?>

		<!-- Form -->
		<p>
			<label for="<?php echo $this->get_field_id('wpbs_select_form'); ?>"><?php echo __('Form', 'wp-booking-system'); ?></label>

			<select name="<?php echo $this->get_field_name('wpbs_select_form'); ?>" id="<?php echo $this->get_field_id('wpbs_select_form'); ?>" class="widefat">
				<option value="0"><?php echo __('No Form', 'wp-booking-system') ?></option>
				<?php foreach ($forms as $form): ?>
					<option <?php echo ($form->get('id') == $form_id ? 'selected="selected"' : ''); ?> value="<?php echo $form->get('id'); ?>"><?php echo $form->get('name'); ?></option>
				<?php endforeach;?>
			</select>
		</p>

		<!-- Form Position -->
		<p>
			<label for="<?php echo $this->get_field_id('wpbs_form_form_position'); ?>"><?php echo __('Form Position', 'wp-booking-system'); ?></label>

			<select name="<?php echo $this->get_field_name('wpbs_form_form_position'); ?>" id="<?php echo $this->get_field_id('wpbs_form_form_position'); ?>" class="widefat">
				<option <?php echo (empty($form_position) || $form_position == 'bottom' ? 'selected="selected"' : ''); ?> value="bottom"><?php echo __('Bottom', 'wp-booking-system'); ?></option>
				<option <?php echo ($form_position == 'side' ? 'selected="selected"' : ''); ?> value="side"><?php echo __('Side', 'wp-booking-system'); ?></option>
			</select>
		</p>

		<!-- Auto Pending -->
		<p>
			<label for="<?php echo $this->get_field_id('wpbs_form_auto_pending'); ?>"><?php echo __('Auto Accept Bookings', 'wp-booking-system'); ?></label>

			<select name="<?php echo $this->get_field_name('wpbs_form_auto_pending'); ?>" id="<?php echo $this->get_field_id('wpbs_form_auto_pending'); ?>" class="widefat">
				<option <?php echo (empty($auto_pending) || $auto_pending == 'yes' ? 'selected="selected"' : ''); ?> value="yes"><?php echo __('Yes', 'wp-booking-system'); ?></option>
				<option <?php echo ($auto_pending == 'no' ? 'selected="selected"' : ''); ?> value="no"><?php echo __('No', 'wp-booking-system'); ?></option>
			</select>
		</p>

		<!-- Selection Type -->
		<p>
			<label for="<?php echo $this->get_field_id('wpbs_form_selection_type'); ?>"><?php echo __('Selection Type', 'wp-booking-system'); ?></label>

			<select name="<?php echo $this->get_field_name('wpbs_form_selection_type'); ?>" id="<?php echo $this->get_field_id('wpbs_form_selection_type'); ?>" class="widefat">
				<option <?php echo (empty($selection_type) || $selection_type == 'multiple' ? 'selected="selected"' : ''); ?> value="multiple"><?php echo __('Date Range', 'wp-booking-system'); ?></option>
				<option <?php echo ($selection_type == 'single' ? 'selected="selected"' : ''); ?> value="single"><?php echo __('Single Day', 'wp-booking-system'); ?></option>
			</select>
		</p>

		<!-- Selection Style -->
		<p>
			<label for="<?php echo $this->get_field_id('wpbs_form_selection_style'); ?>"><?php echo __('Selection Style', 'wp-booking-system'); ?></label>

			<select name="<?php echo $this->get_field_name('wpbs_form_selection_style'); ?>" id="<?php echo $this->get_field_id('wpbs_form_selection_style'); ?>" class="widefat">
				<option <?php echo (empty($selection_style) || $selection_style == 'split' ? 'selected="selected"' : ''); ?> value="split"><?php echo __('Split', 'wp-booking-system'); ?></option>
				<option <?php echo ($selection_style == 'normal' ? 'selected="selected"' : ''); ?> value="normal"><?php echo __('Normal', 'wp-booking-system'); ?></option>
			</select>
		</p>

		<!-- Minimum Days -->
		<p>
			<label for="<?php echo $this->get_field_id('wpbs_form_minimum_days'); ?>"><?php echo __('Minimum Days', 'wp-booking-system'); ?></label>

			<input type="number" value="<?php echo $minimum_days; ?>" name="<?php echo $this->get_field_name('wpbs_form_minimum_days'); ?>" id="<?php echo $this->get_field_id('wpbs_form_minimum_days'); ?>" class="widefat" />

		</p>

		<!-- Maximum Days -->
		<p>
			<label for="<?php echo $this->get_field_id('wpbs_form_maximum_days'); ?>"><?php echo __('Maximum Days', 'wp-booking-system'); ?></label>

			<input type="number" value="<?php echo $maximum_days; ?>" name="<?php echo $this->get_field_name('wpbs_form_maximum_days'); ?>" id="<?php echo $this->get_field_id('wpbs_form_maximum_days'); ?>" class="widefat" />

		</p>

		<!-- Booking Start Day -->
		<p>
			<label for="<?php echo $this->get_field_id('wpbs_form_booking_start_day'); ?>"><?php echo __('Booking Start Day', 'wp-booking-system'); ?></label>

			<select name="<?php echo $this->get_field_name('wpbs_form_booking_start_day'); ?>" id="<?php echo $this->get_field_id('wpbs_form_booking_start_day'); ?>" class="widefat">
				<option value="0" <?php echo (empty($booking_start_day) ? 'selected="selected"' : ''); ?>>-</option>
				<option value="1" <?php echo ($booking_start_day == 1 ? 'selected="selected"' : ''); ?>><?php echo __('Monday', 'wp-booking-system'); ?></option>
				<option value="2" <?php echo ($booking_start_day == 2 ? 'selected="selected"' : ''); ?>><?php echo __('Tuesday', 'wp-booking-system'); ?></option>
				<option value="3" <?php echo ($booking_start_day == 3 ? 'selected="selected"' : ''); ?>><?php echo __('Wednesday', 'wp-booking-system'); ?></option>
				<option value="4" <?php echo ($booking_start_day == 4 ? 'selected="selected"' : ''); ?>><?php echo __('Thursday', 'wp-booking-system'); ?></option>
				<option value="5" <?php echo ($booking_start_day == 5 ? 'selected="selected"' : ''); ?>><?php echo __('Friday', 'wp-booking-system'); ?></option>
				<option value="6" <?php echo ($booking_start_day == 6 ? 'selected="selected"' : ''); ?>><?php echo __('Saturday', 'wp-booking-system'); ?></option>
				<option value="7" <?php echo ($booking_start_day == 7 ? 'selected="selected"' : ''); ?>><?php echo __('Sunday', 'wp-booking-system'); ?></option>
			</select>
		</p>

		<!-- Booking End Day -->
		<p>
			<label for="<?php echo $this->get_field_id('wpbs_form_booking_end_day'); ?>"><?php echo __('Booking End Day', 'wp-booking-system'); ?></label>

			<select name="<?php echo $this->get_field_name('wpbs_form_booking_end_day'); ?>" id="<?php echo $this->get_field_id('wpbs_form_booking_end_day'); ?>" class="widefat">
				<option value="0" <?php echo (empty($booking_end_day) ? 'selected="selected"' : ''); ?>>-</option>
				<option value="1" <?php echo ($booking_end_day == 1 ? 'selected="selected"' : ''); ?>><?php echo __('Monday', 'wp-booking-system'); ?></option>
				<option value="2" <?php echo ($booking_end_day == 2 ? 'selected="selected"' : ''); ?>><?php echo __('Tuesday', 'wp-booking-system'); ?></option>
				<option value="3" <?php echo ($booking_end_day == 3 ? 'selected="selected"' : ''); ?>><?php echo __('Wednesday', 'wp-booking-system'); ?></option>
				<option value="4" <?php echo ($booking_end_day == 4 ? 'selected="selected"' : ''); ?>><?php echo __('Thursday', 'wp-booking-system'); ?></option>
				<option value="5" <?php echo ($booking_end_day == 5 ? 'selected="selected"' : ''); ?>><?php echo __('Friday', 'wp-booking-system'); ?></option>
				<option value="6" <?php echo ($booking_end_day == 6 ? 'selected="selected"' : ''); ?>><?php echo __('Saturday', 'wp-booking-system'); ?></option>
				<option value="7" <?php echo ($booking_end_day == 7 ? 'selected="selected"' : ''); ?>><?php echo __('Sunday', 'wp-booking-system'); ?></option>
			</select>
		</p>

		<!-- Show Date Selection -->
		<p>
			<label for="<?php echo $this->get_field_id('wpbs_form_show_date_selection'); ?>"><?php echo __('Show Date Selection', 'wp-booking-system'); ?></label>

			<select name="<?php echo $this->get_field_name('wpbs_form_show_date_selection'); ?>" id="<?php echo $this->get_field_id('wpbs_form_show_date_selection'); ?>" class="widefat">
				<option <?php echo (empty($show_date_selection) || $show_date_selection == 'no' ? 'selected="selected"' : ''); ?> value="no"><?php echo __('No', 'wp-booking-system'); ?></option>
				<option <?php echo ($show_date_selection == 'yes' ? 'selected="selected"' : ''); ?> value="yes"><?php echo __('Yes', 'wp-booking-system'); ?></option>
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
    register_widget('WPBS_Widget_Calendar');
});