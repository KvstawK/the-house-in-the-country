<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

$calendar_id = absint( ! empty( $_GET['calendar_id'] ) ? $_GET['calendar_id'] : 0 );

?>

<div class="wrap wpbs-wrap wpbs-wrap-legend-items">

	<!-- Page Heading -->
	<h1 class="wp-heading-inline"><?php echo __( 'Calendar Legend', 'wp-booking-system' ); ?><span class="wpbs-heading-tag"><?php printf( __( 'Calendar ID: %d', 'wp-booking-system' ), $calendar_id ); ?></span></h1>
	<a href="<?php echo add_query_arg( array( 'subpage' => 'add-legend-item', 'calendar_id' => $calendar_id ), $this->admin_url ); ?>" class="page-title-action"><?php echo __( 'Add New Legend Item', 'wp-booking-system' ); ?></a>

	<!-- Page Heading Actions -->
	<div class="wpbs-heading-actions">
		<a href="<?php echo add_query_arg( array( 'subpage' => 'edit-calendar' ) ); ?>" class="button-secondary"><?php echo __( 'Back to Calendar', 'wp-booking-system' ); ?></a>
	</div>
	
	<hr class="wp-header-end" />

	<!-- Calendars List Table -->
	<?php 
		$table = new WPBS_WP_List_Table_Legend_Items();
		$table->display();
	?>

	<style>
	<?php 
		/**
         * Legend Items CSS
         *
         */
        foreach (wpbs_get_legend_items(array('calendar_id' => $calendar_id)) as $legend_item):
			// Background colors
            $colors = $legend_item->get('color');
			?>
            
            .wpbs-legend-item-icon-<?php echo esc_attr($legend_item->get('id'));?> div:first-of-type { background-color: <?php echo (!empty($colors[0]) ? esc_attr($colors[0]) : 'transparent');?>; }
            .wpbs-legend-item-icon-<?php echo esc_attr($legend_item->get('id'));?> div:nth-of-type(2) { background-color: <?php echo (!empty($colors[1]) ? esc_attr($colors[1]) : 'transparent');?>; }
            
			.wpbs-legend-item-icon-<?php echo esc_attr( $legend_item->get('id') );?> div:first-of-type svg { fill: <?php echo ( ! empty( $colors[0] ) ? esc_attr( $colors[0] ) : 'transparent' );?> !important; }
			.wpbs-legend-item-icon-<?php echo esc_attr( $legend_item->get('id') );?> div:nth-of-type(2) svg { fill: <?php echo ( ! empty( $colors[1] ) ? esc_attr( $colors[1] ) : 'transparent' );?> !important; }

       <?php endforeach; ?>
	   </style>

</div>