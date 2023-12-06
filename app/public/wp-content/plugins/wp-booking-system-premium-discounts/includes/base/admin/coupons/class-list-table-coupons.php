<?php

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * List table class outputter for Coupons
 *
 */
class WPBS_WP_List_Table_Coupons extends WPBS_WP_List_Table
{

    /**
     * The number of coupons that should appear in the table
     *
     * @access private
     * @var int
     *
     */
    private $items_per_page;

    /**
     * The number of the page being displayed by the pagination
     *
     * @access private
     * @var int
     *
     */
    private $paged;

    /**
     * The data of the table
     *
     * @access public
     * @var array
     *
     */
    public $data = array();

    /**
     * Constructor
     *
     */
    public function __construct()
    {

        parent::__construct(array(
            'plural' => 'wpbs_coupons',
            'singular' => 'wpbs_coupon',
            'ajax' => false,
        ));

        /**
         * Filter the number of coupon shown in the table
         *
         * @param int
         *
         */
        $this->items_per_page = apply_filters('wpbs_list_table_coupon_items_per_page', 20);

        $this->paged = (!empty($_GET['paged']) ? (int) $_GET['paged'] : 1);

        $coupon_status = (!empty($_GET['coupon_status']) ? esc_attr($_GET['coupon_status']) : 'active');

        $this->set_pagination_args(array(
            'total_items' => wpbs_get_coupons(array('number' => -1, 'status' => $coupon_status, 'search' => (!empty($_GET['s']) ? esc_attr($_GET['s']) : '')), true),
            'per_page' => $this->items_per_page,
        ));

        // Get and set table data
        $this->set_table_data();

        // Add column headers and table items
        $this->_column_headers = array($this->get_columns(), array(), $this->get_sortable_columns());
        $this->items = $this->data;

    }

    /**
     * Returns all the columns for the table
     *
     */
    public function get_columns()
    {

        $columns = array(
            'name' => __('Name', 'wp-booking-system-coupons-discounts'),
            'id' => __('ID', 'wp-booking-system-coupons-discounts'),
            'coupon_code' => __('Code', 'wp-booking-system-coupons-discounts'),
            'value' => __('Value', 'wp-booking-system-coupons-discounts'),
            'usage_limit' => __('Usage Limit', 'wp-booking-system-coupons-discounts'),
            'validity_period' => __('Validity Period', 'wp-booking-system-coupons-discounts'),
            'date_created' => __('Date Created', 'wp-booking-system-coupons-discounts'),
            'date_modified' => __('Date Modified', 'wp-booking-system-coupons-discounts'),
        );

        /**
         * Filter the columns of the coupons table
         *
         * @param array $columns
         *
         */
        return apply_filters('wpbs_list_table_coupons_columns', $columns);

    }

    /**
     * Overwrites the parent class.
     * Define which columns are sortable
     *
     * @return array
     *
     */
    public function get_sortable_columns()
    {

        return array(
            'name' => array('name', false),
            'id' => array('id', false),
            'date_created' => array('date_created', false),
            'date_modified' => array('date_modified', false),
        );

    }

    /**
     * Returns the possible views for the coupon list table
     *
     */
    protected function get_views()
    {

        $coupon_status = (!empty($_GET['coupon_status']) ? esc_attr($_GET['coupon_status']) : 'active');

        $views = array(
            'active' => '<a href="' . add_query_arg(array('page' => 'wpbs-coupons', 'coupon_status' => 'active', 'paged' => 1, 's' => (!empty($_GET['s']) ? esc_attr($_GET['s']) : '')), admin_url('admin.php')) . '" ' . ($coupon_status == 'active' ? 'class="current"' : '') . '>' . __('Active', 'wp-booking-system-coupons-discounts') . ' <span class="count">(' . wpbs_get_coupons(array('status' => 'active', 'search' => (!empty($_GET['s']) ? esc_attr($_GET['s']) : '')), true) . ')</span></a>',
            'trash' => '<a href="' . add_query_arg(array('page' => 'wpbs-coupons', 'coupon_status' => 'trash', 'paged' => 1, 's' => (!empty($_GET['s']) ? esc_attr($_GET['s']) : '')), admin_url('admin.php')) . '" ' . ($coupon_status == 'trash' ? 'class="current"' : '') . '>' . __('Trash', 'wp-booking-system-coupons-discounts') . ' <span class="count">(' . wpbs_get_coupons(array('status' => 'trash', 'search' => (!empty($_GET['s']) ? esc_attr($_GET['s']) : '')), true) . ')</span></a>',
        );

        /**
         * Filter the views of the coupons table
         *
         * @param array $views
         *
         */
        return apply_filters('wpbs_list_table_coupons_views', $views);

    }

    /**
     * Gets the coupons data and sets it
     *
     */
    private function set_table_data()
    {

        $coupon_args = array(
            'number' => $this->items_per_page,
            'offset' => ($this->paged - 1) * $this->items_per_page,
            'status' => (!empty($_GET['coupon_status']) ? sanitize_text_field($_GET['coupon_status']) : 'active'),
            'orderby' => (!empty($_GET['orderby']) ? sanitize_text_field($_GET['orderby']) : 'id'),
            'order' => (!empty($_GET['order']) ? sanitize_text_field(strtoupper($_GET['order'])) : 'DESC'),
            'search' => (!empty($_GET['s']) ? esc_attr($_GET['s']) : ''),
        );

        $coupons = wpbs_get_coupons($coupon_args);

        if (empty($coupons)) {
            return;
        }

        foreach ($coupons as $coupon) {

            $row_data = $coupon->to_array();

            /**
             * Filter the coupon row data
             *
             * @param array          $row_data
             * @param WPBS_Coupon $coupon
             *
             */
            $row_data = apply_filters('wpbs_list_table_coupons_row_data', $row_data, $coupon);

            $this->data[] = $row_data;

        }

    }

    /**
     * Returns the HTML that will be displayed in each columns
     *
     * @param array $item             - data for the current row
     * @param string $column_name     - name of the current column
     *
     * @return string
     *
     */
    public function column_default($item, $column_name)
    {

        return isset($item[$column_name]) ? $item[$column_name] : '-';

    }

    /**
     * Returns the HTML that will be displayed in the "name" column
     *
     * @param array $item - data for the current row
     *
     * @return string
     *
     */
    public function column_name($item)
    {

        if ($item['status'] == 'active') {

            $output = '<strong><a class="row-title" href="' . add_query_arg(array('page' => 'wpbs-coupons', 'subpage' => 'edit-coupon', 'coupon_id' => $item['id']), admin_url('admin.php')) . '">' . (!empty($item['name']) ? $item['name'] : '') . '</a></strong>';

            $actions = array(
                'edit_coupon' => '<a href="' . add_query_arg(array('page' => 'wpbs-coupons', 'subpage' => 'edit-coupon', 'coupon_id' => $item['id']), admin_url('admin.php')) . '">' . __('Edit coupon', 'wp-booking-system-coupons-discounts') . '</a>',
                'duplicate_coupon' => '<a href="' . wp_nonce_url(add_query_arg(array('page' => 'wpbs-coupons', 'wpbs_action' => 'duplicate_coupon', 'coupon_id' => $item['id']), admin_url('admin.php')), 'wpbs_duplicate_coupon', 'wpbs_token') . '">' . __('Duplicate coupon', 'wp-booking-system-coupons-discounts') . '</a>',
                'trash' => '<span class="trash"><a onclick="return confirm( \'' . __("Are you sure you want to send this coupon to the trash?", 'wp-booking-system-coupons-discounts') . ' \' )" href="' . wp_nonce_url(add_query_arg(array('page' => 'wpbs-coupons', 'wpbs_action' => 'trash_coupon', 'coupon_id' => $item['id']), admin_url('admin.php')), 'wpbs_trash_coupon', 'wpbs_token') . '" class="submitdelete">' . __('Trash', 'wp-booking-system-coupons-discounts') . '</a></span>',
            );

        }

        if ($item['status'] == 'trash') {

            $output = '<strong>' . (!empty($item['name']) ? $item['name'] : '') . '</strong>';

            $actions = array(
                'restore_coupon' => '<a href="' . wp_nonce_url(add_query_arg(array('page' => 'wpbs-coupons', 'wpbs_action' => 'restore_coupon', 'coupon_id' => $item['id']), admin_url('admin.php')), 'wpbs_restore_coupon', 'wpbs_token') . '">' . __('Restore coupon', 'wp-booking-system-coupons-discounts') . '</a>',
                'delete' => '<span class="trash"><a onclick="return confirm( \'' . __("Are you sure you want to delete this coupon?", 'wp-booking-system-coupons-discounts') . ' \' )" href="' . wp_nonce_url(add_query_arg(array('page' => 'wpbs-coupons', 'wpbs_action' => 'delete_coupon', 'coupon_id' => $item['id']), admin_url('admin.php')), 'wpbs_delete_coupon', 'wpbs_token') . '" class="submitdelete">' . __('Delete Permanently', 'wp-booking-system-coupons-discounts') . '</a></span>',
            );

        }

        /**
         * Filter the row actions before adding them to the table
         *
         * @param array $actions
         * @param array $item
         *
         */
        $actions = apply_filters('wpbs_list_table_coupons_row_actions', $actions, $item);

        $output .= $this->row_actions($actions);

        return $output;

    }

    /**
     * Returns the HTML that will be displayed in the "id" column
     *
     * @param array $item - data for the current row
     *
     * @return string
     *
     */
    public function column_id($item)
    {
        return '<span class="wpbs-list-table-id">' . $item['id'] . '</span>';
    }

    /**
     * Returns the HTML that will be displayed in the "code" column
     *
     * @param array $item - data for the current row
     *
     * @return string
     *
     */
    public function column_coupon_code($item)
    {
        return '<span class="wpbs-list-table-code">' . (isset($item['options']['code']) ? $item['options']['code'] : '-')  . '</span>';
    }


    /**
     * Returns the HTML that will be displayed in the "validity_period" column
     *
     * @param array $item - data for the current row
     *
     * @return string
     *
     */
    public function column_validity_period($item)
    {
        if(empty($item['options']['validity_from']) && empty($item['options']['validity_to'])){
            $output = '&infin;';
        }

        if(!empty($item['options']['validity_from']) && empty($item['options']['validity_to'])){
            $output = $item['options']['validity_from'] . ' &rarr; ' . '&infin;';
        }

        if(empty($item['options']['validity_from']) && !empty($item['options']['validity_to'])){
            $output =  '&infin;' . ' &rarr; ' . $item['options']['validity_to'];
        }

        if(!empty($item['options']['validity_from']) && !empty($item['options']['validity_to'])){
            $output = $item['options']['validity_from'] . ' &rarr; ' . $item['options']['validity_to'];
        }

        if(!empty($item['options']['validity_to'])){
            $date = DateTime::createFromFormat('Y-m-d', $item['options']['validity_to']);
            if($date->format('Ymd') < wp_date('Ymd')){
                $output .= '<br><span class="wpbs-discount-expired">'.__('Expired', 'wp-booking-system-discounts').'</span>';
            }
        }

        return $output;
    }

    /**
     * Returns the HTML that will be displayed in the "value" column
     *
     * @param array $item - data for the current row
     *
     * @return string
     *
     */
    public function column_value($item)
    {
        if ($item['options']['type'] == 'fixed_amount') {
            return wpbs_get_formatted_price($item['options']['value'], wpbs_get_currency());
        }
        return $item['options']['value'] . '%';
    }

    /**
     * Returns the HTML that will be displayed in the "value" column
     *
     * @param array $item - data for the current row
     *
     * @return string
     *
     */
    public function column_usage_limit($item)
    {
        if (isset($item['options']['usage_limit'])) {
            $usages = absint(wpbs_get_coupon_meta($item['id'], 'usages', true));
            return $usages . '/' . $item['options']['usage_limit'];
        }
        return '&#8734;';
    }

    /**
     * Returns the HTML that will be displayed in the "date_created" column
     *
     * @param array $item - data for the current row
     *
     * @return string
     *
     */
    public function column_date_created($item)
    {

        $output = date_i18n(get_option('date_format') . ' ' . get_option('time_format'), strtotime($item['date_created']));

        return $output;

    }

    /**
     * Returns the HTML that will be displayed in the "date_created" column
     *
     * @param array $item - data for the current row
     *
     * @return string
     *
     */
    public function column_date_modified($item)
    {

        $output = date_i18n(get_option('date_format') . ' ' . get_option('time_format'), strtotime($item['date_modified']));

        return $output;

    }

    /**
     * HTML display when there are no items in the table
     *
     */
    public function no_items()
    {

        echo __('No coupons found.', 'wp-booking-system-coupons-discounts');

    }

}
