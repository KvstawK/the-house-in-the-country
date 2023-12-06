<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

?>

<div class="wrap wpbs-wrap wpbs-wrap-coupons">

	<!-- Page Heading -->
	<h1 class="wp-heading-inline"><?php echo __( 'Coupons', 'wp-booking-system-coupons-discounts'); ?></h1>
	<a href="<?php echo add_query_arg( array( 'subpage' => 'add-coupon' ), $this->admin_url ); ?>" class="page-title-action"><?php echo __( 'Add New Coupon', 'wp-booking-system-coupons-discounts'); ?></a>
	<hr class="wp-header-end" />

	<!-- coupons List Table -->
	<form method="get">

        <input type="hidden" name="page" value="wpbs-coupons" />
        <input type="hidden" name="paged" value="1">

		<?php
			$table = new WPBS_WP_List_Table_Coupons();
			$table->views();
			$table->search_box( __( 'Search Couponss' ), 'wpbs-search-coupons' );
			$table->display();
		?>
	</form>

</div>

