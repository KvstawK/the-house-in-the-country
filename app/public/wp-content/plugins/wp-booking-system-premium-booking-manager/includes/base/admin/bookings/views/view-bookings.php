<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

$view = wpbs_bm_get_dashboard_view();

?>

<div class="wrap wpbs-wrap wpbs-wrap-bookings">

	<!-- Page Heading -->
	<h1 class="wp-heading-inline"><?php echo __( 'Bookings Manager', 'wp-booking-system-booking-manager' ); ?></h1>

	<div class="view-switch media-grid-view-switch wpbs-bm-view-switch">
		<p><?php _e('View', 'wp-booking-system-booking-manager') ?></p>
		<a href="<?php echo add_query_arg(array('page' => 'wpbs-bookings', 'view' => 'list'), admin_url('admin.php'));?>" class="view-list <?php echo $view == 'list' ? 'current' : '';?>" title="<?php _e('List view', 'wp-booking-system-booking-manager') ?>">
			<span class="screen-reader-text"><?php _e('List view', 'wp-booking-system-booking-manager') ?></span>
		</a>
		<a href="<?php echo add_query_arg(array('page' => 'wpbs-bookings', 'view' => 'calendar'), admin_url('admin.php'));?>" class="view-grid <?php echo $view == 'calendar' ? 'current' : '';?>" title="<?php _e('Calendar view', 'wp-booking-system-booking-manager') ?>">
			<span class="screen-reader-text"><?php _e('Calendar view', 'wp-booking-system-booking-manager') ?></span>
		</a>
	</div> 
	
	<hr class="wp-header-end" />

	
	<?php if($view == 'list'): ?>

		<!-- Bookings List Table -->
		<form method="get" autocomplete="off">

			<input type="hidden" name="page" value="wpbs-bookings" />
			<input type="hidden" name="paged" value="1">

			<?php
				$table = new WPBS_WP_List_Table_Bookings();
				$table->views();
				$table->search_box( __( 'Search Bookings', 'wp-booking-system-booking-manager' ), 'wpbs-search-bookings' );
				$table->display();
			?>
		</form>

	<?php else: ?>
		<!-- Bookings Calendar View -->

		<?php
			$calendar = new WPBS_BM_Calendar_View_Bookings();
			$calendar->display();
		?>

	<?php endif; ?>

	


</div>