<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

?>

<div class="wrap wpbs-wrap wpbs-wrap-discounts">

	<!-- Page Heading -->
	<h1 class="wp-heading-inline"><?php echo __( 'Discounts', 'wp-booking-system-coupons-discounts'); ?></h1>
	<a href="<?php echo add_query_arg( array( 'subpage' => 'add-discount' ), $this->admin_url ); ?>" class="page-title-action"><?php echo __( 'Add New Discount', 'wp-booking-system-coupons-discounts'); ?></a>
	<hr class="wp-header-end" />

	<!-- Discounts List Table -->
	<form method="get">

        <input type="hidden" name="page" value="wpbs-discounts" />
        <input type="hidden" name="paged" value="1">

		<?php
			$table = new WPBS_WP_List_Table_Discounts();
			$table->views();
			$table->search_box( __( 'Search Discounts' ), 'wpbs-search-discounts' );
			$table->display();
		?>
	</form>

</div>

