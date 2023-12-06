<?php

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Class that handles database queries for the Payments
 *
 */
class WPBS_Object_DB_Payments extends WPBS_Object_DB
{

    /**
     * Construct
     *
     */
    public function __construct()
    {

        global $wpdb;

        $this->table_name = $wpdb->prefix . 'wpbs_payments';
        $this->primary_key = 'id';
        $this->context = 'booking';
        $this->query_object_type = 'WPBS_Payment';

    }

    /**
     * Return the table columns
     *
     */
    public function get_columns()
    {

        return array(
            'id' => '%d',
            'booking_id' => '%s',
            'gateway' => '%s',
            'order_id' => '%s',
            'order_status' => '%s',
            'details' => '%s',
            'date_created' => '%s',
        );

    }

    /**
     * Returns an array of WPBS_Payment objects from the database
     *
     * @param array $args
     * @param bool  $count - whether to return just the count for the query or not
     *
     * @return mixed array|int
     *
     */
    public function get_payments($args = array(), $count = false)
    {

        $defaults = array(
            'number' => -1,
            'offset' => 0,
            'orderby' => 'id',
            'order' => 'DESC',
            'include' => array(),
            'booking_id' => false,
            'order_id' => false,
            'order_status' => false
        );

        $args = wp_parse_args($args, $defaults);

        /**
         * Filter the query arguments just before making the db call
         *
         * @param array $args
         *
         */
        $args = apply_filters('wpbs_get_payments_args', $args);

        // Number args
        if ($args['number'] < 1) {
            $args['number'] = 999999;
        }

        // Where clause
        $where = "WHERE 1=1";

        // Include where clause
        if (!empty($args['include'])) {

            $include = implode(',', $args['include']);
            $where .= " AND id IN({$include})";

        }

        // Include search
        if (!empty($args['booking_id'])) {

            $booking_id = absint($args['booking_id']);
            $where .= " AND booking_id = {$booking_id}";

        }

        // Include search
        if (!empty($args['order_id'])) {

            $order_id = esc_sql($args['order_id']);
            $where .= " AND order_id = '" . $order_id . "'";

        }

        if (!empty($args['order_status'])) {

            $order_status = esc_sql($args['order_status']);
            $where .= " AND order_status = '" . $order_status . "'";

        }

        // Orderby
        $orderby = esc_sql($args['orderby']);

        // Order
        $order = ('DESC' === strtoupper($args['order']) ? 'DESC' : 'ASC');

        $clauses = compact('where', 'orderby', 'order', 'count');

        $results = $this->get_results($clauses, $args, 'wpbs_get_payment');

        return $results;

    }

    /**
     * Creates and updates the database table for the payments
     *
     */
    public function create_table()
    {

        global $wpdb;

        $table_name = $this->table_name;
        $charset_collate = $wpdb->get_charset_collate();

        $query = "CREATE TABLE {$table_name} (
			id bigint(10) NOT NULL AUTO_INCREMENT,
			booking_id bigint(10) NOT NULL,
			gateway varchar(100) NOT NULL,
			order_id varchar(100) NOT NULL,
			order_status varchar(100) NOT NULL,
			details longtext NOT NULL,
			date_created datetime NOT NULL,
			PRIMARY KEY  id (id)
		) {$charset_collate};";

        require_once ABSPATH . 'wp-admin/includes/upgrade.php';
        dbDelta($query);

    }

}
