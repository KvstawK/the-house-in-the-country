<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

?>

<div class="wrap wpbs-wrap wpbs-wrap-calendars">

	<!-- Page Heading -->
	<h1 class="wp-heading-inline"><?php echo __( 'Calendars', 'wp-booking-system' ); ?></h1>
	<a href="<?php echo add_query_arg( array( 'subpage' => 'add-calendar' ), $this->admin_url ); ?>" class="page-title-action"><?php echo __( 'Add New Calendar', 'wp-booking-system' ); ?></a>
	<hr class="wp-header-end" />

	<!-- Calendars List Table -->
	<form method="get">

        <input type="hidden" name="page" value="wpbs-calendars" />
        <input type="hidden" name="paged" value="1">

		<?php
			$table = new WPBS_WP_List_Table_Calendars();
			$table->views();
			$table->search_box( __( 'Search Calendars', 'wp-booking-system' ), 'wpbs-search-calendars' );
			$table->display();
		?>
	</form>

</div>