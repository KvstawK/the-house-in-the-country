<?php

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Includes the files needed for the Discounts admin area
 *
 */
function wpbs_d_include_files_admin_discounts()
{

    // Get legend admin dir path
    $dir_path = plugin_dir_path(__FILE__);

    // Include submenu page
    if (file_exists($dir_path . 'class-submenu-page-discounts.php')) {
        include $dir_path . 'class-submenu-page-discounts.php';
    }

    // Include discounts list table
    if (file_exists($dir_path . 'class-list-table-discounts.php')) {
        include $dir_path . 'class-list-table-discounts.php';
    }

    // Include admin actions
    if (file_exists($dir_path . 'functions-actions-discount.php')) {
        include $dir_path . 'functions-actions-discount.php';
    }


}
add_action('wpbs_d_include_files', 'wpbs_d_include_files_admin_discounts');

/**
 * Register the Discounts admin submenu page
 *
 */
function wpbs_d_register_submenu_page_discounts($submenu_pages)
{

    if (!is_array($submenu_pages)) {
        return $submenu_pages;
    }

    $submenu_pages['discounts'] = array(
        'class_name' => 'WPBS_Submenu_Page_Discounts',
        'data' => array(
            'page_title' => __('Discounts', 'wp-booking-system-coupons-discounts'),
            'menu_title' => __('Discounts', 'wp-booking-system-coupons-discounts'),
            'capability' => apply_filters('wpbs_submenu_page_capability_discounts', 'manage_options'),
            'menu_slug' => 'wpbs-discounts',
        ),
    );

    return $submenu_pages;

}
add_filter('wpbs_register_submenu_page', 'wpbs_d_register_submenu_page_discounts', 50);

/**
 * Helper function for outputting a discount rule row.
 * 
 */
function discount_rule_row($rule = false, $group_index = false){

    $forms = wpbs_get_forms(array('status' => 'active'));

    $discount_rules = array(
        'number-of-booked-days' => __('Number of booked days', 'wp-booking-system-coupons-discounts'),
        'total-order-value' => __('Total order value', 'wp-booking-system-coupons-discounts'),
        'booking-weekday' => __('Booking weekday', 'wp-booking-system-coupons-discounts'),
        'booking-period' => __('Number of days from current day', 'wp-booking-system-coupons-discounts'),
        'price-per-day' => __('Price per day', 'wp-booking-system-coupons-discounts'),
        'form-field' => __('Form Field', 'wp-booking-system-coupons-discounts'),
        'payment_type' => __('Payment Type', 'wp-booking-system-coupons-discounts'),
        'user' => __('User', 'wp-booking-system-coupons-discounts'),
        'user_role' => __('User Role', 'wp-booking-system-coupons-discounts'),
    );

    $discount_rules = apply_filters('wpbs_discount_rules', $discount_rules);

    $comparison_values = array(
        'number-of-booked-days' => array("equal" =>  __('is equal to', 'wp-booking-system-coupons-discounts'), "not-equal" => __('is not equal to', 'wp-booking-system-coupons-discounts'), "greater" => __('is greater than', 'wp-booking-system-coupons-discounts'), "lower" => __('is lower than', 'wp-booking-system-coupons-discounts')),
        'total-order-value' => array("equal" =>  __('is equal to', 'wp-booking-system-coupons-discounts'), "not-equal" => __('is not equal to', 'wp-booking-system-coupons-discounts'), "greater" => __('is greater than', 'wp-booking-system-coupons-discounts'), "lower" => __('is lower than', 'wp-booking-system-coupons-discounts')),
        'form-field' => array("equal" =>  __('is equal to', 'wp-booking-system-coupons-discounts'), "not-equal" => __('is not equal to', 'wp-booking-system-coupons-discounts'), "greater" => __('is greater than', 'wp-booking-system-coupons-discounts'), "lower" => __('is lower than', 'wp-booking-system-coupons-discounts')),
        'booking-weekday' => array("starts" =>  __('starts on a', 'wp-booking-system-coupons-discounts'), "does-not-start" => __('does not start on a', 'wp-booking-system-coupons-discounts'), "ends" =>  __('ends on a', 'wp-booking-system-coupons-discounts'), "does-not-end" => __('does not end on a', 'wp-booking-system-coupons-discounts')),
        'booking-period' => array("equal" =>  __('is equal to', 'wp-booking-system-coupons-discounts'), "not-equal" => __('is not equal to', 'wp-booking-system-coupons-discounts'), "greater" => __('is greater than', 'wp-booking-system-coupons-discounts'), "lower" => __('is lower than', 'wp-booking-system-coupons-discounts')),
        'price-per-day' => array("equal" =>  __('is equal to', 'wp-booking-system-coupons-discounts'), "not-equal" => __('is not equal to', 'wp-booking-system-coupons-discounts'), "greater" => __('is greater than', 'wp-booking-system-coupons-discounts'), "lower" => __('is lower than', 'wp-booking-system-coupons-discounts')),
        'user' => array("logged_in" =>  __('is logged in', 'wp-booking-system-coupons-discounts'), "not_logged_in" => __('is not logged in', 'wp-booking-system-coupons-discounts')),
        'payment_type' => array("full" =>  __('is full amount', 'wp-booking-system-coupons-discounts'), "deposit" => __('is deposit', 'wp-booking-system-coupons-discounts')),
        'user_role' => array("equal" =>  __('is equal to', 'wp-booking-system-coupons-discounts'), "not-equal" => __('is not equal to', 'wp-booking-system-coupons-discounts')),
        'currency' => array("equal" =>  __('is equal to', 'wp-booking-system-coupons-discounts'), "not-equal" => __('is not equal to', 'wp-booking-system-coupons-discounts')),
    );

    $comparison_values = apply_filters('wpbs_discount_rules_comparison_values', $comparison_values);

    ?>
    <div class="wpbs-discount-rule <?php if($rule):?>wpbs-discount-rule-<?php echo isset($rule['condition']) ? $rule['condition'] : '';?><?php endif;?>">
        <select class="discount-rule-condition" data-name="discount_rules[index][condition][]" <?php if($group_index !== false):?>name="discount_rules[<?php echo $group_index;?>][condition][]"<?php endif;?>>
            <?php foreach($discount_rules as $rule_key => $rule_name): ?>
            <option <?php isset($rule['condition']) ? selected($rule['condition'], $rule_key) : '';?> value="<?php echo $rule_key;?>" data-comparison-rules='<?php echo json_encode($comparison_values[$rule_key]);?>'><?php echo $rule_name; ?></option>
            <?php endforeach; ?>
            
        </select>

        <select class="discount-rule-form-fields" data-name="discount_rules[index][form_field][]" <?php if($group_index !== false):?>name="discount_rules[<?php echo $group_index;?>][form_field][]"<?php endif;?>>
            <option value=""></option>
            <?php foreach ($forms as $form): ?>
                <optgroup label="<?php echo $form->get('name') ?>">
                    <?php $fields = $form->get('fields');?>
                    <?php foreach ($fields as $field): ?>
                    <?php
                    if (in_array($field['type'], wpbs_get_excluded_fields())) {
                        continue;
                    }
                    ?>
                        <option <?php isset($rule['form_field']) ? selected($rule['form_field'], $form->get('id') . '-' . $field['id'] ) : '';?> value="<?php echo $form->get('id');?>-<?php echo $field['id'];?>"><?php echo $field['values']['default']['label'] ?></option>
                    <?php endforeach;?>
                </optgroup>
            <?php endforeach;?>
        </select>

        <select class="discount-rule-comparison" data-name="discount_rules[index][comparison][]" <?php if($group_index !== false):?>name="discount_rules[<?php echo $group_index;?>][comparison][]"<?php endif;?>>
            <?php if($rule): ?>
                <?php foreach($comparison_values[$rule['condition']] as $comparison_value_key => $comparison_value): ?>
                    <option <?php selected($comparison_value_key, $rule['comparison']) ?> value="<?php echo $comparison_value_key;?>"><?php echo $comparison_value ?></option>
                <?php endforeach; ?>
            <?php endif ?>
        </select>

        <input type="text" class="discount-rule-value discount-rule-value-default" value="<?php if(isset($rule['value'])):?><?php echo $rule['value'];?><?php endif;?>" data-name="discount_rules[index][value][]" <?php if($group_index !== false):?>name="discount_rules[<?php echo $group_index;?>][value][]"<?php endif;?>>

        <select class="discount-rule-value discount-rule-value-weekdays" data-name="discount_rules[index][value-weekday][]" <?php if($group_index !== false):?>name="discount_rules[<?php echo $group_index;?>][value-weekday][]"<?php endif;?>>
            <option value="1" <?php isset($rule['value-weekday']) ? selected($rule['value-weekday'], 1) : ''; ?>><?php echo __('Monday', 'wp-booking-system'); ?></option>
            <option value="2" <?php isset($rule['value-weekday']) ? selected($rule['value-weekday'], 2) : ''; ?>><?php echo __('Tuesday', 'wp-booking-system'); ?></option>
            <option value="3" <?php isset($rule['value-weekday']) ? selected($rule['value-weekday'], 3) : ''; ?>><?php echo __('Wednesday', 'wp-booking-system'); ?></option>
            <option value="4" <?php isset($rule['value-weekday']) ? selected($rule['value-weekday'], 4) : ''; ?>><?php echo __('Thursday', 'wp-booking-system'); ?></option>
            <option value="5" <?php isset($rule['value-weekday']) ? selected($rule['value-weekday'], 5) : ''; ?>><?php echo __('Friday', 'wp-booking-system'); ?></option>
            <option value="6" <?php isset($rule['value-weekday']) ? selected($rule['value-weekday'], 6) : ''; ?>><?php echo __('Saturday', 'wp-booking-system'); ?></option>
            <option value="7" <?php isset($rule['value-weekday']) ? selected($rule['value-weekday'], 7) : ''; ?>><?php echo __('Sunday', 'wp-booking-system'); ?></option>
        </select>

        <?php $roles = get_editable_roles();  ?>

        <select class="discount-rule-user-roles" data-name="discount_rules[index][value-user-role][]" <?php if($group_index !== false):?>name="discount_rules[<?php echo $group_index;?>][value-user-role][]"<?php endif;?>>
            <option value=""></option>
            <?php foreach ($roles as $role_key => $role_data): ?>
                <option <?php isset($rule['value-user-role']) ? selected($rule['value-user-role'], $role_key ) : '';?> value="<?php echo $role_key?>"><?php echo $role_data['name'] ?></option>
            <?php endforeach;?>
        </select>

        <button class="button-secondary discount-rule-add-and"><?php echo __('and', 'wp-booking-system-coupons-discounts'); ?></button>

        <a href="#" class="wpbs-discount-rule-remove" title="<?php echo __('Remove', 'wp-booking-system-coupons-discounts') ?>"><i class="wpbs-icon-close"></i></a>
    </div>
    <?php
}