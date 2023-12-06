<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

if(isset($_GET['wpbs-reporting-start-date'])){
	$start_date = esc_attr($_GET['wpbs-reporting-start-date']);
	$end_date = isset($_GET['wpbs-reporting-end-date']) && $_GET['wpbs-reporting-end-date'] ? esc_attr($_GET['wpbs-reporting-end-date']) : false;
	$reporting_interval = isset($_GET['wpbs-reporting-interval']) ? esc_attr($_GET['wpbs-reporting-interval']) : 'day';
} else {
	$date = new DateTime('7 days ago'); 
	$start_date = $date->format('Y-m-d');
	$end_date = false;
	$reporting_interval = 'day';
}

$reports = new WPBS_Reporting($start_date, $reporting_interval, $end_date);



?>

<!-- WPBS Reporting Wrap -->
<div class="wrap wpbs-wrap wpbs-wrap-reporting">

	<!-- WPBS Reporting Date Interval -->
	<div class="wpbs-reporting-date-interval">
		<span><?php echo __('Show reports for', 'wp-booking-system-reporting') ?></span> 
		<select name="wpbs-reporting-date-interval-selector" id="wpbs-reporting-date-interval-selector" data-url="<?php echo add_query_arg( array( 'page' => 'wpbs-reporting' ), admin_url('admin.php') );?>">
			<optgroup label="Daily">
				<option value="<?php $date = new DateTime('6 days ago'); echo $date->format('Y-m-d');?>" <?php echo ($reporting_interval == 'day' && $start_date == $date->format('Y-m-d')) ? 'selected' : '' ?> data-interval="day"><?php echo __('Last 7 days', 'wp-booking-system-reporting') ?></option>
				<option value="<?php $date = new DateTime('13 days ago'); echo $date->format('Y-m-d');?>" <?php echo ($reporting_interval == 'day' && $start_date == $date->format('Y-m-d')) ? 'selected' : '' ?> data-interval="day"><?php echo __('Last 14 days', 'wp-booking-system-reporting') ?></option>
				<option value="<?php $date = new DateTime('20 days ago'); echo $date->format('Y-m-d');?>" <?php echo ($reporting_interval == 'day' && $start_date == $date->format('Y-m-d')) ? 'selected' : '' ?> data-interval="day"><?php echo __('Last 21 days', 'wp-booking-system-reporting') ?></option>
				<option value="<?php $date = new DateTime('27 days ago'); echo $date->format('Y-m-d');?>" <?php echo ($reporting_interval == 'day' && $start_date == $date->format('Y-m-d')) ? 'selected' : '' ?> data-interval="day"><?php echo __('Last 28 days', 'wp-booking-system-reporting') ?></option>
				<option value="<?php $date = new DateTime('1 month ago'); echo $date->format('Y-m-01');?>" <?php echo ($reporting_interval == 'day' && $start_date == $date->format('Y-m-01')) ? 'selected' : '' ?> data-interval="day" data-end-date="<?php echo $date->format('Y-m-t');?>"><?php echo __('Last month', 'wp-booking-system-reporting') ?></option>
				<option value="<?php $date = new DateTime('now'); echo $date->format('Y-m-01');?>" <?php echo ($reporting_interval == 'day' && $start_date == $date->format('Y-m-01')) ? 'selected' : '' ?> data-interval="day" data-end-date="<?php echo $date->format('Y-m-d');?>"><?php echo __('Month to date', 'wp-booking-system-reporting') ?></option>
			</optgroup>
			<optgroup label="Monthly">
				<option value="<?php $date = new DateTime('3 months ago'); echo $date->format('Y-m-01');?>" <?php echo ($reporting_interval == 'month' && $start_date == $date->format('Y-m-01')) ? 'selected' : '' ?> data-interval="month"><?php echo __('Last 3 months', 'wp-booking-system-reporting') ?></option>
				<option value="<?php $date = new DateTime('6 months ago'); echo $date->format('Y-m-01');?>" <?php echo ($reporting_interval == 'month' && $start_date == $date->format('Y-m-01')) ? 'selected' : '' ?> data-interval="month"><?php echo __('Last 6 month', 'wp-booking-system-reporting') ?>s</option>
				<option value="<?php $date = new DateTime('9 months ago'); echo $date->format('Y-m-01');?>" <?php echo ($reporting_interval == 'month' && $start_date == $date->format('Y-m-01')) ? 'selected' : '' ?> data-interval="month"><?php echo __('Last 9 months', 'wp-booking-system-reporting') ?></option>
				<option value="<?php $date = new DateTime('12 months ago'); echo $date->format('Y-m-01');?>" <?php echo ($reporting_interval == 'month' && $start_date == $date->format('Y-m-01')) ? 'selected' : '' ?> data-interval="month"><?php echo __('Last 12 months', 'wp-booking-system-reporting') ?></option>
				<option value="<?php $date = new DateTime('18 months ago'); echo $date->format('Y-m-01');?>" <?php echo ($reporting_interval == 'month' && $start_date == $date->format('Y-m-01')) ? 'selected' : '' ?> data-interval="month"><?php echo __('Last 18 months', 'wp-booking-system-reporting') ?></option>
				<option value="<?php $date = new DateTime('now'); echo $date->format('Y-01-01');?>" <?php echo ($reporting_interval == 'month' && $start_date == $date->format('Y-01-01')) ? 'selected' : '' ?> data-interval="month"><?php echo __('Year to date', 'wp-booking-system-reporting') ?></option>
			</optgroup>
			<optgroup label="Yearly">
				<option value="<?php $date = new DateTime('1 year ago'); echo $date->format('Y-01-01');?>" <?php echo ($reporting_interval == 'year' && $start_date == $date->format('Y-01-01')) ? 'selected' : '' ?> data-interval="year"><?php echo __('Last year', 'wp-booking-system-reporting') ?></option>
				<option value="<?php $date = new DateTime('2 years ago'); echo $date->format('Y-01-01');?>" <?php echo ($reporting_interval == 'year' && $start_date == $date->format('Y-01-01')) ? 'selected' : '' ?> data-interval="year"><?php echo __('Last 2 years', 'wp-booking-system-reporting') ?></option>
				<option value="<?php $date = new DateTime('3 years ago'); echo $date->format('Y-01-01');?>" <?php echo ($reporting_interval == 'year' && $start_date == $date->format('Y-01-01')) ? 'selected' : '' ?> data-interval="year"><?php echo __('Last 3 years', 'wp-booking-system-reporting') ?></option>
				<option value="<?php $date = new DateTime('5 years ago'); echo $date->format('Y-01-01');?>" <?php echo ($reporting_interval == 'year' && $start_date == $date->format('Y-01-01')) ? 'selected' : '' ?> data-interval="year"><?php echo __('Last 5 years', 'wp-booking-system-reporting') ?></option>
			</optgroup>

		</select>
		
	</div>


	<!-- Page Heading -->
	<h1 class="wp-heading-inline">
		<?php echo __( 'Reporting', 'wp-booking-system-reporting'); ?>
	</h1>
	<hr class="wp-header-end" />

	
	<!-- Reporting Container -->
	<div class="wpbs-reporting-container">

		<!-- Reporting Row  -->
		<div class="wpbs-reporting-row">

			<!-- Chart -->
			<div class="wpbs-reporting-chart">
				
				<h2><?php echo __('Number of Bookings', 'wp-booking-system-reporting') ?></h2>

				<!-- Chart Wrap -->
				<div class="wpbs-reporting-chart-wrap">
					<canvas class="wpbs-chart" data-tooltip="bookings" data-chart="<?php echo esc_attr(json_encode($reports->chart_data_bookings()));?>"></canvas>
				</div>

			</div>

			<!-- Chart Stats -->
			<div class="wpbs-reporting-chart-stats">
				
				<!-- Chart Stat Total -->
				<div class="wpbs-reporting-chart-stat">
					<h2><?php echo __('Total', 'wp-booking-system-reporting') ?></h2>
					<h3><?php echo $reports->total_bookings() ?></h3>
					<small><?php echo __('bookings', 'wp-booking-system-reporting') ?></small>
				</div>


				<!-- Chart Stat Average -->
				<div class="wpbs-reporting-chart-stat">
					<h2><?php echo __('Average', 'wp-booking-system-reporting') ?></h2>
					<h3><?php echo $reports->average_bookings() ?></h3>
					<small><?php echo __('bookings per', 'wp-booking-system-reporting') ?> <?php echo $reports->difference_interval() ?></small>
				</div>

			</div>

		</div>


		<!-- Reporting Row  -->
		<div class="wpbs-reporting-row">

			<!-- Chart -->
			<div class="wpbs-reporting-chart">
				
				<h2><?php echo __('Revenue', 'wp-booking-system-reporting') ?></h2>

				<div class="wpbs-reporting-chart-wrap">
					<canvas class="wpbs-chart" data-tooltip="revenue" data-currency="<?php echo wpbs_get_currency();?>" data-chart="<?php echo esc_attr(json_encode($reports->chart_data_revenue()));?>"></canvas>
				</div>

			</div>

			<!-- Chart Stats -->
			<div class="wpbs-reporting-chart-stats">

				<!-- Chart Stat Total -->
				<div class="wpbs-reporting-chart-stat">
					<h2><?php echo __('Total', 'wp-booking-system-reporting') ?></h2>
					<h3><?php echo $reports->total_revenue() ?></h3>
					<small><?php echo wpbs_get_currency();?></small>
				</div>


				<!-- Chart Stat Average -->
				<div class="wpbs-reporting-chart-stat">
					<h2><?php echo __('Average', 'wp-booking-system-reporting') ?></h2>
					<h3><?php echo $reports->average_revenue_per_interval() ?></h3>
					<small><?php echo wpbs_get_currency();?> <?php echo __('per', 'wp-booking-system-reporting') ?> <?php echo $reports->difference_interval() ?></small>
				</div>

				<!-- Chart Stat Average -->
				<div class="wpbs-reporting-chart-stat">
					<h2><?php echo __('Average', 'wp-booking-system-reporting') ?></h2>
					<h3><?php echo $reports->average_revenue_per_booking() ?></h3>
					<small><?php echo wpbs_get_currency();?> <?php echo __('per booking', 'wp-booking-system-reporting') ?></small>
				</div>

			</div>

		</div>


		<!-- Reporting Row  -->
		<div class="wpbs-reporting-row">

			<!-- Chart -->
			<div class="wpbs-reporting-chart">
				
				<h2><?php echo __('Number of Nights Booked', 'wp-booking-system-reporting') ?></h2>

				<div class="wpbs-reporting-chart-wrap">
					<canvas class="wpbs-chart" data-tooltip="nights" data-chart="<?php echo esc_attr(json_encode($reports->chart_data_nights_booked()));?>"></canvas>
				</div>

			</div>

			<!-- Chart Stats -->
			<div class="wpbs-reporting-chart-stats">

				<!-- Chart Stat Total -->
				<div class="wpbs-reporting-chart-stat">
					<h2><?php echo __('Total', 'wp-booking-system-reporting') ?></h2>
					<h3><?php echo $reports->total_nights_booked() ?></h3>
					<small><?php echo __('nights', 'wp-booking-system-reporting') ?></small>
				</div>


				<!-- Chart Stat Average -->
				<div class="wpbs-reporting-chart-stat">
					<h2><?php echo __('Average', 'wp-booking-system-reporting') ?></h2>
					<h3><?php echo $reports->average_nights_booked_per_interval() ?></h3>
					<small><?php echo __('nights per', 'wp-booking-system-reporting') ?> <?php echo $reports->difference_interval() ?></small>
				</div>

				<!-- Chart Stat Average -->
				<div class="wpbs-reporting-chart-stat">
					<h2><?php echo __('Average', 'wp-booking-system-reporting') ?></h2>
					<h3><?php echo $reports->average_nights_booked_per_booking() ?></h3>
					<small><?php echo __('nights per booking', 'wp-booking-system-reporting') ?></small>
				</div>

			</div>

		</div>

		<!-- Reporting Row  -->
		<div class="wpbs-reporting-row">

			<!-- Chart -->
			<div class="wpbs-reporting-chart">
				
				<h2><?php echo __('Number of Days Booked', 'wp-booking-system-reporting') ?></h2>

				<div class="wpbs-reporting-chart-wrap">
					<canvas class="wpbs-chart" data-tooltip="days" data-chart="<?php echo esc_attr(json_encode($reports->chart_data_days_booked()));?>"></canvas>
				</div>

			</div>

			<!-- Chart Stats -->
			<div class="wpbs-reporting-chart-stats">

				<!-- Chart Stat Total -->
				<div class="wpbs-reporting-chart-stat">
					<h2><?php echo __('Total', 'wp-booking-system-reporting') ?></h2>
					<h3><?php echo $reports->total_days_booked() ?></h3>
					<small><?php echo __('days', 'wp-booking-system-reporting') ?></small>
				</div>


				<!-- Chart Stat Average -->
				<div class="wpbs-reporting-chart-stat">
					<h2><?php echo __('Average', 'wp-booking-system-reporting') ?></h2>
					<h3><?php echo $reports->average_days_booked_per_interval() ?></h3>
					<small><?php echo __('days booked per', 'wp-booking-system-reporting') ?> <?php echo $reports->difference_interval() ?></small>
				</div>

				<!-- Chart Stat Average -->
				<div class="wpbs-reporting-chart-stat">
					<h2><?php echo __('Average', 'wp-booking-system-reporting') ?></h2>
					<h3><?php echo $reports->average_days_booked_per_booking() ?></h3>
					<small><?php echo __('days per booking', 'wp-booking-system-reporting') ?></small>
				</div>

			</div>

		</div>

		<?php if(wpbs_is_pricing_enabled()): ?>
		<div class="wpbs-reporting-row">
			<h4><?php _e('Actions', 'wp-booking-system-reporting') ?></h4>
			<a target="_blank" href="<?php echo add_query_arg( array( 
				'page' => 'wpbs-reporting', 
				'wpbs-reports-export' => true, 
				'wpbs-reporting-start-date' => (isset($_GET['wpbs-reporting-start-date']) ? $_GET['wpbs-reporting-start-date'] : ''),
				'wpbs-reporting-end-date' => (isset($_GET['wpbs-reporting-end-date']) ? $_GET['wpbs-reporting-end-date'] : ''),
				), admin_url('admin.php') );?>" class="button button-secondary"><?php _e('Download financial report for selected period', 'wp-booking-system-reporting') ?></a>
		</div>
		<?php endif; ?>

	</div>


</div>

