<?php
$form_id = absint(!empty($_GET['form_id']) ? $_GET['form_id'] : 0);
$form = wpbs_get_form($form_id);

if (is_null($form)) {
    return;
}

$active_section = ( ! empty( $_GET['section']) && in_array( $_GET['section'], array('restriction-rules', 'fixed-intervals' )) ? sanitize_text_field( $_GET['section'] ) : 'restriction-rules' );

$form_meta = wpbs_get_form_meta($form_id);

?>

<ul class="subsubsub wpbs-form-tab-navigation">
    <li><a href="<?php echo add_query_arg( array( 'page' => 'wpbs-forms', 'subpage' => 'edit-form', 'form_id' => $_GET['form_id'], 'tab' => 'booking_restrictions', 'section' => 'restriction-rules'), admin_url('admin.php') ) ; ?>" data-tab="restriction-rules" <?php if($active_section == 'restriction-rules'):?>class="current"<?php endif;?>><?php echo __('Booking Restrictions', 'wp-booking-system-booking-restrictions'); ?></a></li> &nbsp;|&nbsp;

    <li><a href="<?php echo add_query_arg( array( 'page' => 'wpbs-forms', 'subpage' => 'edit-form', 'form_id' => $_GET['form_id'], 'tab' => 'booking_restrictions', 'section' => 'fixed-intervals'), admin_url('admin.php') ) ; ?>" data-tab="fixed-intervals" <?php if($active_section == 'fixed-intervals'):?>class="current"<?php endif;?>><?php echo __('Fixed Date Intervals', 'wp-booking-system-booking-restrictions'); ?></a></li>
</ul>

<div class="wpbs-clear"><!-- --></div>

<div class="wpbs-form-sections">

    <div class="wpbs-form-section wpbs-form-section-restriction-rules <?php echo ( $active_section == 'restriction-rules' ? ' wpbs-section-active' : '' );?>" data-tab="restriction-rules">
        <?php include 'view-edit-form-tab-booking-restriction-rules.php'; ?>
    </div>

    <div class="wpbs-form-section wpbs-form-section-fixed-intervals <?php echo ( $active_section == 'fixed-intervals' ? ' wpbs-section-active' : '' );?>" data-tab="fixed-intervals">
        <?php include 'view-edit-form-tab-booking-fixed-intervals.php'; ?>
    </div>


</div>