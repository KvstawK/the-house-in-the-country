<?php

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * List table class outputter for Calendars
 *
 */
class WPBS_WP_List_Table_Legend_Items extends WPBS_WP_List_Table
{

    /**
     * The number of calendars that should appear in the table
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
            'plural' => 'wpbs_legend_items',
            'singular' => 'wpbs_legend_item',
            'ajax' => false,
        ));

        $this->items_per_page = 500;
        $this->paged = (!empty($_GET['paged']) ? (int) $_GET['paged'] : 1);

        // Get and set table data
        $this->set_table_data();

        // Add column headers and table items
        $this->_column_headers = array($this->get_columns(), array(), array());
        $this->items = $this->data;

    }

    /**
     * Returns all the columns for the table
     *
     */
    public function get_columns()
    {

        $columns = array(
            'sort' => __('Sort', 'wp-booking-system'),
            'color' => __('Color', 'wp-booking-system'),
            'name' => __('Name', 'wp-booking-system'),
            'is_default' => __('Default', 'wp-booking-system'),
            'is_visible' => __('Visible', 'wp-booking-system'),
            'is_bookable' => __('Bookable', 'wp-booking-system') . ' ' . wpbs_get_output_tooltip(__('Controls whether or not the visitor can book the date using this legend item.', 'wp-booking-system')),
            'auto_pending' => __('Auto Accept as', 'wp-booking-system') . ' ' . wpbs_get_output_tooltip(__('These legend items will be used to automatically book the calendar dates if "Auto Accept" parameter in the shortcode is set to "Yes".', 'wp-booking-system'), 'left'),
        );

        /**
         * Filter the columns of the legend items table
         *
         * @param array $columns
         *
         */
        return apply_filters('wpbs_list_table_calendars_columns', $columns);

    }

    /**
     * Gets the legend items data and sets it
     *
     */
    private function set_table_data()
    {

        $legend_items_args = array(
            'calendar_id' => (!empty($_GET['calendar_id']) ? absint($_GET['calendar_id']) : 0),
        );

        $legend_items = wpbs_get_legend_items($legend_items_args);

        if (empty($legend_items)) {
            return;
        }

        foreach ($legend_items as $legend_item) {

            $row_data = $legend_item->to_array();

            /**
             * Filter the legend item row data
             *
             * @param array             $row_data
             * @param WPBS_Legend_Item $legend_item
             *
             */
            $row_data = apply_filters('wpbs_list_table_legend_items_row_data', $row_data, $legend_item);

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
     * Returns the HTML that will be displayed in the "sort" column
     *
     * @param array $item - data for the current row
     *
     * @return string
     *
     */
    public function column_sort($item)
    {

        return '<span data-id="' . $item['id'] . '" class="wpbs-move-legend-item"><span class="wpbs-inner"></span></span>';

    }

    /**
     * Returns the HTML that will be displayed in the "color" column
     *
     * @param array $item - data for the current row
     *
     * @return string
     *
     */
    public function column_color($item)
    {

        $output = wpbs_get_legend_item_icon($item['id'], $item['type'], $item['color']);

        return $output;

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

        $output = '<strong><a class="row-title" href="' . add_query_arg(array('page' => 'wpbs-calendars', 'subpage' => 'edit-legend-item', 'legend_item_id' => $item['id'], 'calendar_id' => $item['calendar_id']), admin_url('admin.php')) . '">' . (!empty($item['name']) ? $item['name'] : '') . '</a></strong>';

        $actions = array();

        $actions['edit'] = '<a href="' . add_query_arg(array('page' => 'wpbs-calendars', 'subpage' => 'edit-legend-item', 'legend_item_id' => $item['id'], 'calendar_id' => $item['calendar_id']), admin_url('admin.php')) . '">' . __('Edit', 'wp-booking-system') . '</a>';

        if (empty($item['is_default'])) {
            $actions['delete'] = '<span class="trash"><a onclick="return confirm( \'' . __("Are you sure you want to delete this legend item?", "wp-booking-system") . ' \' )" href="' . wp_nonce_url(add_query_arg(array('page' => 'wpbs-calendars', 'subpage' => 'view-legend', 'wpbs_action' => 'delete_legend_item', 'legend_item_id' => $item['id']), admin_url('admin.php')), 'wpbs_delete_legend_item', 'wpbs_token') . '" class="submitdelete">' . __('Delete', 'wp-booking-system') . '</a></span>';
        }

        $output .= $this->row_actions($actions);

        return $output;

    }

    /**
     * Returns the HTML that will be displayed in the "is_default" column
     *
     * @param array $item - data for the current row
     *
     * @return string
     *
     */
    public function column_is_default($item)
    {

        $output = '<span class="wpbs-list-table-icon wpbs-list-table-icon-star ' . (!empty($item['is_default']) ? 'wpbs-list-table-icon-active' : 'wpbs-list-table-icon-inactive') . '">';

        if (!empty($item['is_default'])) {

            $output .= '<span class="dashicons dashicons-star-filled"></span>';

        } else {

            $output .= '<a href="' . (wp_nonce_url(add_query_arg(array('wpbs_action' => 'make_default_legend_item', 'legend_item_id' => $item['id']), remove_query_arg('wpbs_message')), 'wpbs_make_default_legend_item', 'wpbs_token')) . '"><span class="dashicons dashicons-star-filled"></span></a>';

        }

        $output .= '</span>';

        return $output;

    }

    /**
     * Returns the HTML that will be displayed in the "is_visible" column
     *
     * @param array $item - data for the current row
     *
     * @return string
     *
     */
    public function column_is_visible($item)
    {

        $output = '<span class="wpbs-list-table-icon wpbs-list-table-icon-yes ' . (!empty($item['is_visible']) ? 'wpbs-list-table-icon-active' : 'wpbs-list-table-icon-inactive') . '">';

        if (!empty($item['is_visible'])) {

            $output .= '<a href="' . (wp_nonce_url(add_query_arg(array('wpbs_action' => 'make_invisible_legend_item', 'legend_item_id' => $item['id']), remove_query_arg('wpbs_message')), 'wpbs_make_invisible_legend_item', 'wpbs_token')) . '"><span class="dashicons dashicons-yes"></span></a>';

        } else {

            $output .= '<a href="' . (wp_nonce_url(add_query_arg(array('wpbs_action' => 'make_visible_legend_item', 'legend_item_id' => $item['id']), remove_query_arg('wpbs_message')), 'wpbs_make_visible_legend_item', 'wpbs_token')) . '"><span class="dashicons dashicons-yes"></span></a>';

        }

        $output .= '</span>';

        return $output;

    }

    /**
     * Returns the HTML that will be displayed in the "is_bookable" column
     *
     * @param array $item - data for the current row
     *
     * @return string
     *
     */
    public function column_is_bookable($item)
    {

        $output = '<span class="wpbs-list-table-icon wpbs-list-table-icon-yes ' . (!empty($item['is_bookable']) ? 'wpbs-list-table-icon-active' : 'wpbs-list-table-icon-inactive') . '">';

        if (!empty($item['is_bookable'])) {

            $output .= '<a href="' . (wp_nonce_url(add_query_arg(array('wpbs_action' => 'make_unbookable_legend_item', 'legend_item_id' => $item['id']), remove_query_arg('wpbs_message')), 'wpbs_make_unbookable_legend_item', 'wpbs_token')) . '"><span class="dashicons dashicons-yes"></span></a>';

        } else {

            $output .= '<a href="' . (wp_nonce_url(add_query_arg(array('wpbs_action' => 'make_bookable_legend_item', 'legend_item_id' => $item['id']), remove_query_arg('wpbs_message')), 'wpbs_make_bookable_legend_item', 'wpbs_token')) . '"><span class="dashicons dashicons-yes"></span></a>';

        }

        $output .= '</span>';

        return $output;

    }

    /**
     * Returns the HTML that will be displayed in the "auto_pending" column
     *
     * @param array $item - data for the current row
     *
     * @return string
     *
     */
    public function column_auto_pending($item)
    {

        $output = '<span class="wpbs-list-table-select">';

        $output .= '<form method="post" action="' . add_query_arg(array('wpbs_action' => 'change_legend_auto_pending'), remove_query_arg('wpbs_message')) . '">';

            $output .= '<input type="hidden" name="legend_item_id" value="' . $item['id'] . '" />';

            $output .= '<select class="wpbs-auto-accept-booking-as" name="auto_pending">';

                $output .= '<option>-</option>';
                $output .= '<option ' . selected($item['auto_pending'], 'booked', false) . ' value="booked">' . __('Full Day', 'wp-booking-system') . '</option>';
                $output .= '<option ' . selected($item['auto_pending'], 'changeover_start', false) . ' value="changeover_start">' . __('Starting Changeover', 'wp-booking-system') . '</option>';
                $output .= '<option ' . selected($item['auto_pending'], 'changeover_end', false) . ' value="changeover_end">' . __('Ending Changeover', 'wp-booking-system') . '</option>';
           
            $output .= '</select>';

            $output .= wp_nonce_field('wpbs_change_legend_auto_pending', 'wpbs_token', false, false);

        $output .= '</form>';

        $output .= '</span>';

        return $output;

    }

    /**
     * Add needed hidden fields
     *
     * @param string $which
     *
     */
    protected function extra_tablenav($which)
    {

        if ($which == 'top') {
            return;
        }

        // Add calendar id as a hidden field
        echo '<input type="hidden" name="calendar_id" value="' . (!empty($_GET['calendar_id']) ? absint($_GET['calendar_id']) : 0) . '" />';

        // Add a custom nonce for the list table
        wp_nonce_field('wpbs_list_table_legend_items', 'wpbs_token', false);

    }

    /**
     * HTML display when there are no items in the table
     *
     */
    public function no_items()
    {

        echo '<div class="wpbs-list-table-no-items">';
        echo '<p>' . __('Oops... it seems there are no legend items', 'wp-booking-system') . '</p>';
        echo '<a class="button-primary wpbs-button-large" href="' . add_query_arg(array('subpage' => 'add-legend-item')) . '">' . __('Set Up A Legend Item', 'wp-booking-system') . '</a>';
        echo '</div>';

    }

}
