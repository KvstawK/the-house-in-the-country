<?php

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Class that handles database queries for the Notifications
 *
 */
class WPBS_Object_DB_Notifications extends WPBS_Object_DB
{

    /**
     * Construct
     *
     */
    public function __construct()
    {

        global $wpdb;

        $this->table_name = $wpdb->prefix . 'wpbs_notifications';
        $this->primary_key = 'id';
        $this->context = 'notification';
        $this->query_object_type = 'WPBS_Notification';
    }

    /**
     * Return the table columns
     *
     */
    public function get_columns()
    {

        return array(
            'id' => '%d',
            'booking_id' => '%d',
            'notification_type' => '%s',
            'notification_group' => '%s',
            'notification_status' => '%s',
            'date_created' => '%s',
        );
    }

    /**
     * Returns an array of WPBS_Notification objects from the database
     *
     * @param array $args
     * @param bool  $count - whether to return just the count for the query or not
     *
     * @return mixed array|int
     *
     */
    public function get_notifications($args = array(), $count = false)
    {

        $defaults = array(
            'number' => -1,
            'offset' => 0,
            'orderby' => 'id',
            'order' => 'DESC',
            'type' => false,
            'group' => false,
            'status' => false,
            'booking_id' => false,
        );

        $args = wp_parse_args($args, $defaults);

        /**
         * Filter the query arguments just before making the db call
         *
         * @param array $args
         *
         */
        $args = apply_filters('wpbs_get_notifications_args', $args);

        // Number args
        if ($args['number'] < 1) {
            $args['number'] = 999999;
        }

        // Where clause
        $where = "WHERE 1=1";

        // Include search
        if (!empty($args['booking_id'])) {

            $booking_id = absint($args['booking_id']);
            $where .= " AND booking_id = {$booking_id}";
        }

        // Include type
        if (!empty($args['type'])) {

            $type = esc_sql($args['type']);
            $where .= " AND notification_type = '" . $type . "'";
        }

        // Include group
        if (!empty($args['group'])) {

            $group = esc_sql($args['group']);
            $where .= " AND notification_group = '" . $group . "'";
        }


        // Include status
        if (!empty($args['status'])) {

            $status = esc_sql($args['status']);
            $where .= " AND notification_status = '" . $status . "'";
        }

        // Orderby
        $orderby = esc_sql($args['orderby']);

        // Order
        $order = ('DESC' === strtoupper($args['order']) ? 'DESC' : 'ASC');

        $clauses = compact('where', 'orderby', 'order', 'count');

        $results = $this->get_results($clauses, $args, 'wpbs_get_notification');

        return $results;
    }

    /**
     * Creates and updates the database table for the notifications
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
			notification_type varchar(100) NOT NULL,
			notification_status varchar(100) NOT NULL,
			notification_group varchar(100) NOT NULL,
			date_created datetime NOT NULL,
			PRIMARY KEY  id (id)
		) {$charset_collate};";


        require_once ABSPATH . 'wp-admin/includes/upgrade.php';
        dbDelta($query);
    }
}
