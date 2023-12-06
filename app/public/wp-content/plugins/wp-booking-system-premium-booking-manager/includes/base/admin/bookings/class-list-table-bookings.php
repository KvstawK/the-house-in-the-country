<?php

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * List table class outputter for Bookings
 *
 */
class WPBS_WP_List_Table_Bookings extends WPBS_WP_List_Table
{

    /**
     * The number of bookings that should appear in the table
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
    public $status;

    protected $dynamic_columns;

    /**
     * Constructor
     *
     */
    public function __construct()
    {

        parent::__construct(array(
            'plural' => 'wpbs_bookings',
            'singular' => 'wpbs_booking',
            'ajax' => false,
        ));

        /**
         * Filter the number of bookings shown in the table
         *
         * @param int
         *
         */
        $this->items_per_page = apply_filters('wpbs_list_table_bookings_items_per_page', 100);

        $this->paged = (!empty($_GET['paged']) ? (int) $_GET['paged'] : 1);

        $this->status = isset($_GET['booking_status']) && in_array($_GET['booking_status'], array('accepted', 'pending', 'trash')) ? [$_GET['booking_status']] : '';

        $this->set_pagination_args(array(
            'total_items' => wpbs_bm_get_bookings(array('status' => $this->status, 'search' => (!empty($_GET['s']) ? sanitize_text_field($_GET['s']) : ''), 'start_date' => (!empty($_GET['wpbs_bm_start_date']) ? sanitize_text_field($_GET['wpbs_bm_start_date']) : ''), 'end_date' => (!empty($_GET['wpbs_bm_end_date']) ? sanitize_text_field($_GET['wpbs_bm_end_date']) : '')), true),
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
            'id' => __('ID', 'wp-booking-system-booking-manager'),
            'calendar' => __('Calendar', 'wp-booking-system-booking-manager'),
            'booking_start_date' => __('Start Date', 'wp-booking-system-booking-manager'),
            'booking_end_date' => __('End Date', 'wp-booking-system-booking-manager'),
            'stay_length' => __('Stay Length', 'wp-booking-system-booking-manager'),
            'date_created' => __('Date Created', 'wp-booking-system-booking-manager'),
            'status' => __('Status', 'wp-booking-system-booking-manager'),
            'view' => '',
        );

        if (wpbs_is_pricing_enabled()) {
            $columns = array_merge(
                array_slice($columns, 0, 5),
                array('payment_status' => __('Payment Status', 'wp-booking-system-booking-manager')),
                array_slice($columns, 5)
            );

        }

        $forms = wpbs_get_forms();

        foreach ($forms as $form) {
            $fields = wpbs_get_form_meta($form->get('id'), 'booking_manager_fields', true);
            if (is_array($fields) && !empty($fields)) {
                foreach ($fields as $field) {
                    $this->dynamic_columns[$form->get('id')][$field] = $field;
                }
            }

        }

        $count = 0;
        if ($this->dynamic_columns) {
            foreach ($this->dynamic_columns as $field) {
                if (count($field) > $count) {
                    $count = count($field);

                }
            }
        }

        $dynamic_columns = array();
        for ($i = 1; $i <= $count; $i++) {
            $dynamic_columns['column_' . $i] = 'Column #' . $i;
        }

        $columns = array_merge(
            array_slice($columns, 0, 2),
            $dynamic_columns,
            array_slice($columns, 2)
        );

        /**
         * Filter the columns of the bookings table
         *
         * @param array $columns
         *
         */
        return apply_filters('wpbs_list_table_bookings_columns', $columns);

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
            'id' => array('id', false),
            'date_created' => array('date_created', false),
            'booking_start_date' => array('start_date', false),
            'booking_end_date' => array('end_date', false),
            'calendar' => array('calendar_id', false),
        );

    }

    /**
     * Returns the possible views for the form list table
     *
     */
    protected function get_views()
    {

        $booking_status = (!empty($_GET['booking_status']) ? sanitize_text_field($_GET['booking_status']) : 'all');

        $views = array(
            'all' => '<a href="' . add_query_arg(array('page' => 'wpbs-bookings', 'booking_status' => '', 'paged' => 1, 's' => (!empty($_GET['s']) ? sanitize_text_field($_GET['s']) : ''), 'wpbs_bm_start_date' => (!empty($_GET['wpbs_bm_start_date']) ? sanitize_text_field($_GET['wpbs_bm_start_date']) : ''), 'wpbs_bm_end_date' => (!empty($_GET['wpbs_bm_end_date']) ? sanitize_text_field($_GET['wpbs_bm_end_date']) : '')), admin_url('admin.php')) . '" ' . ($booking_status == 'all' ? 'class="current"' : '') . '>' . __('All', 'wp-booking-system-booking-manager') . ' <span class="count">(' . wpbs_bm_get_bookings(array('search' => (!empty($_GET['s']) ? sanitize_text_field($_GET['s']) : ''), 'start_date' => (!empty($_GET['wpbs_bm_start_date']) ? sanitize_text_field($_GET['wpbs_bm_start_date']) : ''), 'end_date' => (!empty($_GET['wpbs_bm_end_date']) ? sanitize_text_field($_GET['wpbs_bm_end_date']) : '')), true) . ')</span></a>',

            'pending' => '<a href="' . add_query_arg(array('page' => 'wpbs-bookings', 'booking_status' => 'pending', 'paged' => 1, 's' => (!empty($_GET['s']) ? sanitize_text_field($_GET['s']) : ''), 'wpbs_bm_start_date' => (!empty($_GET['wpbs_bm_start_date']) ? sanitize_text_field($_GET['wpbs_bm_start_date']) : ''), 'wpbs_bm_end_date' => (!empty($_GET['wpbs_bm_end_date']) ? sanitize_text_field($_GET['wpbs_bm_end_date']) : '')), admin_url('admin.php')) . '" ' . ($booking_status == 'pending' ? 'class="current"' : '') . '>' . __('Pending', 'wp-booking-system-booking-manager') . ' <span class="count">(' . wpbs_bm_get_bookings(array('status' => array('pending'), 'search' => (!empty($_GET['s']) ? sanitize_text_field($_GET['s']) : ''), 'start_date' => (!empty($_GET['wpbs_bm_start_date']) ? sanitize_text_field($_GET['wpbs_bm_start_date']) : ''), 'end_date' => (!empty($_GET['wpbs_bm_end_date']) ? sanitize_text_field($_GET['wpbs_bm_end_date']) : '')), true) . ')</span></a>',

            'accepted' => '<a href="' . add_query_arg(array('page' => 'wpbs-bookings', 'booking_status' => 'accepted', 'paged' => 1, 's' => (!empty($_GET['s']) ? sanitize_text_field($_GET['s']) : ''), 'wpbs_bm_start_date' => (!empty($_GET['wpbs_bm_start_date']) ? sanitize_text_field($_GET['wpbs_bm_start_date']) : ''), 'wpbs_bm_end_date' => (!empty($_GET['wpbs_bm_end_date']) ? sanitize_text_field($_GET['wpbs_bm_end_date']) : '')), admin_url('admin.php')) . '" ' . ($booking_status == 'accepted' ? 'class="current"' : '') . '>' . __('Accepted', 'wp-booking-system-booking-manager') . ' <span class="count">(' . wpbs_bm_get_bookings(array('status' => array('accepted'), 'search' => (!empty($_GET['s']) ? sanitize_text_field($_GET['s']) : ''), 'start_date' => (!empty($_GET['wpbs_bm_start_date']) ? sanitize_text_field($_GET['wpbs_bm_start_date']) : ''), 'end_date' => (!empty($_GET['wpbs_bm_end_date']) ? sanitize_text_field($_GET['wpbs_bm_end_date']) : '')), true) . ')</span></a>',

            'trash' => '<a href="' . add_query_arg(array('page' => 'wpbs-bookings', 'booking_status' => 'trash', 'paged' => 1, 's' => (!empty($_GET['s']) ? sanitize_text_field($_GET['s']) : ''), 'wpbs_bm_start_date' => (!empty($_GET['wpbs_bm_start_date']) ? sanitize_text_field($_GET['wpbs_bm_start_date']) : ''), 'wpbs_bm_end_date' => (!empty($_GET['wpbs_bm_end_date']) ? sanitize_text_field($_GET['wpbs_bm_end_date']) : '')), admin_url('admin.php')) . '" ' . ($booking_status == 'trash' ? 'class="current"' : '') . '>' . __('Trash', 'wp-booking-system-booking-manager') . ' <span class="count">(' . wpbs_bm_get_bookings(array('status' => array('trash'), 'search' => (!empty($_GET['s']) ? sanitize_text_field($_GET['s']) : ''), 'start_date' => (!empty($_GET['wpbs_bm_start_date']) ? sanitize_text_field($_GET['wpbs_bm_start_date']) : ''), 'end_date' => (!empty($_GET['wpbs_bm_end_date']) ? sanitize_text_field($_GET['wpbs_bm_end_date']) : '')), true) . ')</span></a>',
        );

        /**
         * Filter the views of the forms table
         *
         * @param array $views
         *
         */
        return apply_filters('wpbs_list_table_forms_views', $views);

    }

    /**
     * Gets the bookings data and sets it
     *
     */
    private function set_table_data()
    {

        $args = array(
            'number' => $this->items_per_page,
            'offset' => ($this->paged - 1) * $this->items_per_page,
            'orderby' => (!empty($_GET['orderby']) ? sanitize_text_field($_GET['orderby']) : 'id'),
            'order' => (!empty($_GET['order']) ? sanitize_text_field(strtoupper($_GET['order'])) : 'DESC'),
            'search' => (!empty($_GET['s']) ? sanitize_text_field($_GET['s']) : ''),
            'start_date' => (!empty($_GET['wpbs_bm_start_date']) ? sanitize_text_field($_GET['wpbs_bm_start_date']) : ''),
            'end_date' => (!empty($_GET['wpbs_bm_end_date']) ? sanitize_text_field($_GET['wpbs_bm_end_date']) : ''),
            'status' => (!empty($_GET['booking_status']) ? array(sanitize_text_field($_GET['booking_status'])) : array()),
        );

        $bookings = wpbs_bm_get_bookings($args);

        if (empty($bookings)) {
            return;
        }

        foreach ($bookings as $booking) {

            $row_data = $booking;

            /**
             * Filter the calendar row data
             *
             * @param array          $row_data
             * @param WPBS_Booking $calendar
             *
             */
            $row_data = apply_filters('wpbs_list_table_bookings_row_data', $row_data, $booking);

            $this->data[] = $row_data;

        }

    }

    protected function display_tablenav($which)
    {
        ?>
        <div class="tablenav <?php echo esc_attr($which); ?>">

            <?php if ($which == 'top'): ?>
                <input type="text" name="wpbs_bm_start_date" class="wpbs-datepicker" id="wpbs-bm-start-date" placeholder="<?php echo __('Start date', 'wp-booking-system-booking-manager'); ?>" value="<?php echo isset($_GET['wpbs_bm_start_date']) ? $_GET['wpbs_bm_start_date'] : ''; ?>" />
                <input type="text" name="wpbs_bm_end_date" class="wpbs-datepicker" id="wpbs-bm-end-date" placeholder="<?php echo __('End date', 'wp-booking-system-booking-manager'); ?>" value="<?php echo isset($_GET['wpbs_bm_end_date']) ? $_GET['wpbs_bm_end_date'] : ''; ?>" />
                <input type="submit" id="filter-submit" class="button" value="<?php echo __('Filter', 'wp-booking-system-booking-manager'); ?>">
                <a class="button" href="<?php echo add_query_arg(array('page' => 'wpbs-bookings', 'booking_status' => (isset($_GET['booking_status']) && in_array($_GET['booking_status'], array('accepted', 'pending', 'trash')) ? $_GET['booking_status'] : ''), 'paged' => 1), admin_url('admin.php')); ?>"><?php echo __('Clear Filters', 'wp-booking-system-booking-manager'); ?></a>

                <label class="hide-past-bookings">
                    <input type="checkbox" name="hide-past-bookings" <?php echo (wpbs_bm_get_past_bookings_filter() == 'on') ? 'checked="true"' : ''; ?> > <span><?php _e('Hide past bookings', 'wp-booking-system-booking-manager')?></span>
                </label>

            <?php endif;?>

            <?php $this->pagination($which);?>
            <a class="button wpbs-bm-export-button" href="<?php echo wp_nonce_url(add_query_arg(array('page' => 'wpbs-bookings', 'wpbs_action' => 'bm_export_bookings', 'booking_status' => (!empty($_GET['booking_status']) ? sanitize_text_field($_GET['booking_status']) : ''), 'paged' => 1, 's' => (!empty($_GET['s']) ? sanitize_text_field($_GET['s']) : ''), 'wpbs_bm_start_date' => (!empty($_GET['wpbs_bm_start_date']) ? sanitize_text_field($_GET['wpbs_bm_start_date']) : ''), 'wpbs_bm_end_date' => (!empty($_GET['wpbs_bm_end_date']) ? sanitize_text_field($_GET['wpbs_bm_end_date']) : '')), admin_url('admin.php')), 'wpbs_bm_export_bookings', 'wpbs_token'); ?>"><?php echo __('Export Selection as CSV', 'wp-booking-system-booking-manager'); ?></a>

            <br class="clear" />
        </div>
    <?php
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

        $column_index = ((int) str_replace('column_', '', $column_name)) - 1;

        if (!isset($this->dynamic_columns[$item->get('form_id')])) {
            return '-';
        }

        $form_fields = array_keys($this->dynamic_columns[$item->get('form_id')]);

        $field_id = isset($form_fields[$column_index]) ? $form_fields[$column_index] : false;

        foreach ($item->get('fields') as $field) {
            if ($field['id'] != $field_id) {
                continue;
            }

            $form_value = isset($field['user_value']) ? $field['user_value'] : '-';

            $value = '';
            if (is_array($form_value)) {
                foreach ($form_value as $val) {
                    if (strpos($val, '|') !== false) {
                        $val = explode('|', $val);
                        $val = $val[1];
                    }
                    $value .= $val . ', ';
                }

                $value = trim($value, ', ');
            } else {
                if (!empty($form_value) && strpos($form_value, '|') !== false) {
                    $value = explode('|', $form_value);
                    $value = $value[1];
                } else {
                    $value = $form_value;
                }
            }

            return $value;
        }

        return property_exists($item, $column_name) ? $item->get($column_name) : '-';

    }

    /**
     * Returns the HTML that will be displayed in the "name" column
     *
     * @param array $item - data for the current row
     *
     * @return string
     *
     */
    public function column_id($item)
    {

        $output = '<a target="_blank" href="' . add_query_arg(array('page' => 'wpbs-calendars', 'subpage' => 'edit-calendar', 'calendar_id' => $item->get('calendar_id'), 'booking_id' => $item->get('id')), admin_url('admin.php')) . '"><span class="wpbs-list-table-id">' . $item->get('id') . '</span></a>';

        return $output;

    }

    public function column_view($item)
    {
        return '<a target="_blank" href="' . add_query_arg(array('page' => 'wpbs-calendars', 'subpage' => 'edit-calendar', 'calendar_id' => $item->get('calendar_id'), 'booking_id' => $item->get('id')), admin_url('admin.php')) . '" class="button button-secondary">' . __('View Booking', 'wp-booking-system-booking-manager') . '</a>';
    }

    public function column_calendar($item)
    {

        $calendar = wpbs_get_calendar($item->get('calendar_id'));

        return '<a target="_blank" href="' . add_query_arg(array('page' => 'wpbs-calendars', 'subpage' => 'edit-calendar', 'calendar_id' => $item->get('calendar_id')), admin_url('admin.php')) . '">' . $calendar->get_name() . '</a>';

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

        $output = wpbs_date_i18n(get_option('date_format'), strtotime($item->get('date_created')));

        return $output;

    }

    public function column_booking_start_date($item)
    {

        $output = wpbs_date_i18n(get_option('date_format'), strtotime($item->get('start_date')));

        return $output;

    }

    public function column_booking_end_date($item)
    {

        $output = wpbs_date_i18n(get_option('date_format'), strtotime($item->get('end_date')));

        return $output;

    }

    public function column_stay_length($item)
    {
        $difference = (strtotime($item->get('end_date')) - strtotime($item->get('start_date'))) / DAY_IN_SECONDS;

        $abbr = '';
        $abbr .= ($difference + 1) . ' ' . (($difference + 1) == 1 ? __('day', 'wp-booking-system-booking-manager') : __('days', 'wp-booking-system-booking-manager'));
        $abbr .= ' / ';
        $abbr .= $difference . ' ' . ($difference == 1 ? __('night', 'wp-booking-system-booking-manager') : __('nights', 'wp-booking-system-booking-manager'));

        $output = '<abbr title="' . $abbr . '">';
        if ($difference == 0) {
            $output .= '1 ' . __('day', 'wp-booking-system-booking-manager');
        } elseif (wpbs_get_booking_meta($item->get('id'), 'selection_style', true) == 'normal') {
            $output .= ($difference + 1) . ' ' . (($difference + 1) == 1 ? __('day', 'wp-booking-system-booking-manager') : __('days', 'wp-booking-system-booking-manager'));
        } else {
            $output .= $difference . ' ' . ($difference == 1 ? __('night', 'wp-booking-system-booking-manager') : __('nights', 'wp-booking-system-booking-manager'));
        }
        $output .= '</abbr>';

        return $output;

    }

    public function column_payment_status($item)
    {
        return WPBS_Bookings_Outputter::payment_status($item);
    }
    public function column_status($item)
    {

        if ($item->get('status') == 'pending') {
            return '<div class="wpbs-bm-status wpbs-bm-status-pending"><span class="dashicons dashicons-marker"></span> ' . __('Pending', 'wp-booking-system-booking-manager') . '</div>';
        }
        if ($item->get('status') == 'accepted') {
            return '<div class="wpbs-bm-status wpbs-bm-status-accepted"><span class="dashicons dashicons-yes-alt"></span> ' . __('Accepted', 'wp-booking-system-booking-manager') . '</div>';
        }
        if ($item->get('status') == 'trash') {
            return '<div class="wpbs-bm-status wpbs-bm-status-trash"><span class="dashicons dashicons-dismiss"></span> ' . __('Trash', 'wp-booking-system-booking-manager') . '</div>';
        }

    }

    /**
     * HTML display when there are no items in the table
     *
     */
    public function no_items()
    {

        echo __('No bookings found.', 'wp-booking-system-booking-manager');

    }

}
