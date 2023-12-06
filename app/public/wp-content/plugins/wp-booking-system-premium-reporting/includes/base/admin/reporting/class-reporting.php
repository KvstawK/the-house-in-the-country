<?php

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

class WPBS_Reporting
{
    /**
     * All the available calendars
     *
     * @access protected
     * @var    array
     *
     */
    protected $calendars;

    /**
     * All the available bookings
     *
     * @access protected
     * @var    array
     *
     */
    protected $bookings;

    /**
     * The period generated for which we show reports
     *
     * @access protected
     * @var    DatePeriod
     *
     */
    protected $period;

    /**
     * Date formats based on the $interval_type value
     *
     * @access protected
     * @var    array
     *
     */
    protected $date_formats;

    /**
     * Holds information necessary for the "Average" amounts calculations
     *
     * @access protected
     * @var    array
     *
     */
    protected $difference;

    /**
     * The number of bookings, grouped per calendar, then per $period interval
     *
     * @access protected
     * @var    array
     *
     */
    protected $bookings_number;

    /**
     * The revenues, grouped per calendar, then per $period interval
     *
     * @access protected
     * @var    array
     *
     */
    protected $revenue;

    /**
     * The number of nights booked, grouped per calendar, then per $period interval
     *
     * @access protected
     * @var    array
     *
     */
    protected $nights_booked;

    /**
     * The number of days booked, grouped per calendar, then per $period interval
     *
     * @access protected
     * @var    array
     *
     */
    protected $days_booked;

    /**
     * Constructor
     *
     * @param string $start_date
     * @var string $interval_type
     *
     */
    public function __construct($start_date, $interval_type = 'day', $end_date = false)
    {

        // Set all the necessary information for the current date range
        $this->set_interval($start_date, $interval_type, $end_date);

        // Get all calendars
        $this->calendars = wpbs_get_calendars(array('status' => 'active'));

        // Get all bookings

        $this->bookings = [];

        foreach($this->calendars as $calendar){
            $bookings = wpbs_get_bookings(array('status' => array('accepted', 'pending'), 'calendar_id' => $calendar->get('id')));
            $this->bookings = array_merge(array_values($this->bookings), array_values($bookings));
        }

        // Calculate the number of total bookings
        $this->bookings_number = $this->get_data('get_bookings_in_time_interval');

        // Calculate the revenue
        $this->revenue = $this->get_data('get_revenue_in_time_interval');

        // Calculate the number of nights booked
        $this->nights_booked = $this->get_data('get_nights_booked_in_time_interval');

        // Calculate the number of days booked
        $this->days_booked = $this->get_data('get_days_booked_in_time_interval');

    }

    /**
     * Set all the necessary information for the current date range
     *
     * @param string $start_date
     * @param string $interval_type
     *
     */
    private function set_interval($start_date, $interval_type, $end_date)
    {
        // Create DateTime start and end dates
        $start_date = DateTime::createFromFormat('Y-m-d', $start_date);

        if ($end_date === false) {
            $end_date = new DateTime('now');
        } else {
            $end_date = DateTime::createFromFormat('Y-m-d', $end_date);
        }
        $end_date->modify('+1 day');

        

        // Create the interval
        $interval = DateInterval::createFromDateString('1 ' . $interval_type);

        // Create the period
        $this->period = new DatePeriod($start_date, $interval, $end_date);

        // Calculate the difference between $start_date and $end_date
        $difference = $start_date->diff($end_date);

        if ($interval_type == 'day') {
            // Set correct date formats
            $this->date_formats = array('dataset_label' => 'd M', 'data_groupping' => 'Ymd', 'end_period' => 'Y-m-d 23:59:59');
            // Set $difference values
            $this->difference = array(
                'label' => 'day',
                'value' => $difference->days,
            );
        } elseif ($interval_type == 'month') {
            // Set correct date formats
            $this->date_formats = array('dataset_label' => 'M Y', 'data_groupping' => 'Ym', 'end_period' => 'Y-m-t 23:59:59');
            // Set $difference values
            $this->difference = array(
                'label' => 'month',
                'value' => $difference->m + ($difference->y * 12) + 1,
            );
        } elseif ($interval_type == 'year') {
            // Set correct date formats
            $this->date_formats = array('dataset_label' => 'Y', 'data_groupping' => 'Y', 'end_period' => 'Y-12-t 23:59:59');
            // Set $difference values
            $this->difference = array(
                'label' => 'year',
                'value' => $difference->y + 1,
            );
        }
    }

    /**
     * Get one of the ten colors used for the chart lines
     *
     * @param int $i
     *
     * @return string
     *
     */
    private function get_color($i)
    {
        $colors = array('#f34135', '#9b27b0', '#02a8f4', '#ff9700', '#019587', '#663ab6', '#4baf4f', '#3e50b4', '#fdc006', '#785446');
        return $colors[$i % count($colors)];
    }

    /**
     * Helper function to count the valus of a multi-dimensional array
     *
     * @param array $array
     *
     * @return int
     *
     */
    private function count_items($array)
    {
        $count = 0;
        foreach ($array as $children) {
            foreach ($children as $value) {
                $count += $value;
            }
        }
        return $count;
    }

    /**
     * Helper function that creates the JSON used to create the chart.
     *
     * @param array $source
     *
     * @return array
     *
     */
    private function get_chart_data($source)
    {
        $data = array();

        // Loop through period
        foreach ($this->period as $period) {

            // Add Labels
            $data['labels'][] = $period->format($this->date_formats['dataset_label']);

            // Loop through calendars
            foreach ($this->calendars as $i => $calendar) {

                $data['datasets'][$calendar->get('id')] = array(
                    // Set the chart data
                    'data' => array_values($source[$calendar->get('id')]),
                    // Set other options
                    'label' => $calendar->get('name'),
                    'fill' => false,
                    'backgroundColor' => $this->get_color($i),
                    'borderColor' => $this->get_color($i),
                    'borderWidth' => 2,
                );
            }
        }

        // Remove array keys
        $data['datasets'] = array_values($data['datasets']);

        return $data;
    }

    /**
     * Loop through the interval, search for matching bookings and add calculate the values
     *
     * @param string $callback - the class method that does the maths for each data type
     *
     */
    private function get_data($callback)
    {
        $data = array();

        // Loop through period
        foreach ($this->period as $period) {

            // Set correct start and end dates
            $start = DateTime::createFromFormat('Y-m-d H:i:s', $period->format('Y-m-d 00:00:00'));
            $end = DateTime::createFromFormat('Y-m-d H:i:s', $period->format($this->date_formats['end_period']));

            // Loop through bookings
            foreach ($this->bookings as $booking) {

                // Get calendar id
                $calendar_id = $booking->get('calendar_id');

                // Get date format
                $date_format = $this->date_formats['data_groupping'];

                // Initialize the current date with 0
                if (!isset($data[$calendar_id][$period->format($date_format)])) {
                    $data[$calendar_id][$period->format($date_format)] = 0;
                }

                // Create the DateTime object for the booking date
                $booking_date = DateTime::createFromFormat('Y-m-d H:i:s', $booking->get('date_created'));

                // Check if it's in range
                if ($start < $booking_date && $booking_date < $end) {

                    // Get the values from the $callback method
                    $value = $this->$callback($booking);

                    // Add it to the total
                    $data[$calendar_id][$period->format($date_format)] += $value;
                }
            }
        }

        foreach ($this->calendars as $calendar) {
            if (isset($data[$calendar->get('id')])) {
                continue;
            }

            foreach ($this->period as $period) {
                $data[$calendar->get('id')][] = 0;
            }
        }

        return $data;
    }

    /**
     * Increment the number of bookings in a time interval with 1
     *
     * @param WPBS_Booking $booking
     *
     * @return int
     *
     */
    private function get_bookings_in_time_interval($booking)
    {
        return 1;
    }

    /**
     * Get the revenue of a booking
     *
     * @param WPBS_Booking $booking
     *
     * @return int
     *
     */
    private function get_revenue_in_time_interval($booking)
    {
        // Get payment for current booking
        $payment = wpbs_get_payment_by_booking_id($booking->get('id'));

        // Check if it exists
        if (!empty($payment)) {

            // Check if it's an online payment and it was paid
            if ($payment->get('order_status') != 'completed' && !in_array($payment->get('gateway'), array('payment_on_arrival', 'bank_transfer'))) {
                return 0;
            }

            // Return the value
            if($payment->has_security_deposit()){
                return $payment->get_total() - wpbs_get_security_deposit_value($payment->get('prices'));
            }

            return $payment->get_total();
        }
    }

    /**
     * Get the number of nights in a booking
     *
     * @param WPBS_Booking $booking
     *
     * @return int
     *
     */
    private function get_nights_booked_in_time_interval($booking)
    {
        // Booking start and end dates
        $booking_start = DateTime::createFromFormat('Y-m-d H:i:s', $booking->get('start_date'));
        $booking_end = DateTime::createFromFormat('Y-m-d H:i:s', $booking->get('end_date'));

        // Day difference
        $difference = $booking_start->diff($booking_end);

        // Return
        return $difference->format('%a');
    }

    /**
     * Get the number of days in a booking
     *
     * @param WPBS_Booking $booking
     *
     * @return int
     *
     */
    private function get_days_booked_in_time_interval($booking)
    {
        // Booking start and end dates
        $booking_start = DateTime::createFromFormat('Y-m-d H:i:s', $booking->get('start_date'));
        $booking_end = DateTime::createFromFormat('Y-m-d H:i:s', $booking->get('end_date'));
        $booking_end->modify('+1 day');

        // Day difference
        $difference = $booking_start->diff($booking_end);

        // Return
        return $difference->format('%a');
    }

    /**
     * Return the interval type (day, month, year)
     *
     * @return string
     *
     */
    public function difference_interval()
    {
        return $this->difference['label'];
    }

    /**
     * Return the number of total bookings
     *
     * @return float
     *
     */
    public function total_bookings()
    {
        return $this->count_items($this->bookings_number);
    }

    /**
     * Return the average number of bookings per time interval
     *
     * @return float
     *
     */
    public function average_bookings()
    {
        return round(($this->count_items($this->bookings_number) / $this->difference['value']), 2);
    }

    /**
     * Return the chart data for number of bookings
     *
     * @return array
     *
     */
    public function chart_data_bookings()
    {
        return $this->get_chart_data($this->bookings_number);
    }

    /**
     * Return the total revenue
     *
     * @return float
     *
     */
    public function total_revenue()
    {
        return wpbs_get_formatted_price($this->count_items($this->revenue), '');
    }

    /**
     * Return the average revenue per time interval
     *
     * @return float
     *
     */
    public function average_revenue_per_interval()
    {
        return wpbs_get_formatted_price(($this->count_items($this->revenue) / $this->difference['value']), '');
    }

    /**
     * Return the revenue per number of bookings
     *
     * @return float
     *
     */
    public function average_revenue_per_booking()
    {
        if ($this->total_bookings() > 0) {
            return wpbs_get_formatted_price(($this->count_items($this->revenue) / $this->total_bookings()), '');
        }
        return 0;
    }

    /**
     * Return the chart data for revenues
     *
     * @return array
     *
     */
    public function chart_data_revenue()
    {
        return $this->get_chart_data($this->revenue);
    }

    /**
     * Return the total number of nights booked
     *
     * @return float
     *
     */
    public function total_nights_booked()
    {
        return $this->count_items($this->nights_booked);
    }

    /**
     * Return the average number of nights booked per time interval
     *
     * @return float
     *
     */
    public function average_nights_booked_per_interval()
    {
        return round(($this->count_items($this->nights_booked) / $this->difference['value']), 2);
    }

    /**
     * Return the average number of nights booked per number of bookings
     *
     * @return float
     *
     */
    public function average_nights_booked_per_booking()
    {
        if ($this->total_bookings() > 0) {
            return round(($this->count_items($this->nights_booked) / $this->total_bookings()), 2);
        }

        return 0;
    }

    /**
     * Return the chart data for nights booked
     *
     * @return array
     *
     */
    public function chart_data_nights_booked()
    {
        return $this->get_chart_data($this->nights_booked);
    }

    /**
     * Return the total number of days booked
     *
     * @return float
     *
     */
    public function total_days_booked()
    {
        return $this->count_items($this->days_booked);
    }

    /**
     * Return the average number of days booked per time interval
     *
     * @return float
     *
     */
    public function average_days_booked_per_interval()
    {
        return round(($this->count_items($this->days_booked) / $this->difference['value']), 2);
    }

    /**
     * Return the average number of days booked per number of bookings
     *
     * @return float
     *
     */
    public function average_days_booked_per_booking()
    {
        if ($this->total_bookings() > 0) {
            return round(($this->count_items($this->days_booked) / $this->total_bookings()), 2);
        }

        return 0;
    }

    /**
     * Return the chart data for days booked
     *
     * @return array
     *
     */
    public function chart_data_days_booked()
    {
        return $this->get_chart_data($this->days_booked);
    }

}
