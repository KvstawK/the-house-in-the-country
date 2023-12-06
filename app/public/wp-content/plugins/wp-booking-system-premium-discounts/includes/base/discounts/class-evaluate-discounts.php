<?php

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * The Discount Evaluation Class
 *
 */
class WPBS_Evaluate_Discounts
{
    /**
     * The discount rules
     *
     * @access protected
     * @var array
     *
     */
    protected $rules;

    /**
     * The pricing information
     *
     * @access protected
     * @var int
     *
     */
    protected $prices;

    /**
     * The form
     *
     * @access protected
     * @var WPBS_Form
     *
     */
    protected $form;

    /**
     * The form fields containing user data
     *
     * @access protected
     * @var array
     *
     */
    protected $form_fields;

    /**
     * The booking starting date
     *
     * @access public
     * @var string
     *
     */
    public $start_date;

    /**
     * The booking ending date
     *
     * @access public
     * @var string
     *
     */
    public $end_date;

    /**
     * The final evaluation of the discount rules
     *
     * @access protected
     * @var bool
     *
     */
    protected $evaluation = false;

    public function __construct($discount, $prices, $form, $form_fields, $start_date, $end_date)
    {
        /**
         * Set the rules
         *
         */
        $this->rules = $discount['rules'];

        /**
         * Set the pricing details
         *
         */
        $this->prices = $prices;

        /**
         * Set the quantity
         *
         */
        $this->quantity = $prices['quantity'];

        /**
         * Set the subtotal
         *
         */
        $this->subtotal = $prices['subtotal'];

        /**
         * Set the form
         *
         */
        $this->form = $form;

        /**
         * Set the form fields
         *
         */
        $this->form_fields = $form_fields;

        /**
         * Set the start date
         *
         */
        $this->start_date = $start_date;

        /**
         * Set the end date
         *
         */
        $this->end_date = $end_date;

        /**
         * Calculate the average price per day
         * 
         */
        $this->price_per_day = $prices['events']['price'] / $this->quantity;

        /**
         * Evaluate all rule groups
         *
         */
        $this->evaluate_groups();

        /**
         * Evaluate discount
         *
         */
        $this->evaluate_discount();
    }

    /**
     * Returns the evaluation of the discount rules
     *
     * @return bool
     *
     */
    public function get_evaluation()
    {
        return $this->evaluation;
    }

    /**
     * Loop through all rule groups and rules and check if at least one group matches all rules
     *
     */
    protected function evaluate_discount()
    {

        // Loop through groups
        foreach ($this->rules as $group) {

            // Assume group is valid
            $group_valid = true;

            // Loop through rules
            foreach ($group as $rule) {

                // Check if rule is not valid, invalidate group as well.
                if ($rule['evaluation'] !== true) {
                    $group_valid = false;
                    break;
                }

            }

            // If at least one group is valid, set discount evaluation to true
            if ($group_valid == true) {
                $this->evaluation = true;
                break;
            }

        }

    }

    /**
     * Evaluate rule groups
     *
     */
    protected function evaluate_groups()
    {
        foreach ($this->rules as &$group) {
            foreach ($group as &$rule) {
                $this->evaluate_rule($rule);
            }
        }
    }

    /**
     * Evaluate single rule
     *
     * @param array $rule
     *
     */
    protected function evaluate_rule(&$rule)
    {
        // Set operator
        $operator = $rule['comparison'];

        // Set compare value
        $compare = $rule['value'];

        // Switch conditions
        switch ($rule['condition']) {

            case 'total-order-value':
                $rule['evaluation'] = $this->evaluate($this->subtotal, $operator, $compare);
                break;

            case 'number-of-booked-days':
                $rule['evaluation'] = $this->evaluate($this->quantity, $operator, $compare);
                break;
            
            case 'price-per-day':
                $rule['evaluation'] = $this->evaluate($this->price_per_day, $operator, $compare);
                break;

            case 'user':
                $rule['evaluation'] = $this->evaluate(is_user_logged_in(), $operator, true);
                break;

            case 'payment_type':
                $rule['evaluation'] = $this->evaluate_payment_type($rule);
                break;
            
            case 'user_role':
                $rule['evaluation'] = $this->evaluate_user_role($rule);
                break;

            case 'booking-weekday':
                $rule['evaluation'] = $this->evaluate_weekday($rule);
                break;

            case 'booking-period':
                $today = new DateTime();
                $today->setTime(0,0);

                $days_from_today = ($this->start_date - $today->getTimestamp()) / DAY_IN_SECONDS;
                $days_from_today = apply_filters('wpbs_discount_days_from_today_inclusion', $days_from_today, $this);

                $rule['evaluation'] = $this->evaluate($days_from_today, $operator, $compare);
                break;

            case 'form-field':
                $rule['evaluation'] = $this->evaluate_form_field($rule);
                break;
            
            case 'currency':
                $rule['evaluation'] = $this->evaluate_currency($rule);
                break;
        }
    }

    /**
     * Evaluate payment type
     *
     * @param array $rule
     *
     */
    protected function evaluate_payment_type($rule)
    {

        $part_payment = false;
        
        if(isset($this->prices['is_part_payment']) && $this->prices['is_part_payment'] == true){
            $part_payment = true;
        }

        if($rule['comparison'] == 'deposit' && $part_payment == true){
            return true;
        }

        if($rule['comparison'] == 'full' && $part_payment == false){
            return true;
        }

        return false;

    }

    /**
     * Evaluate user role
     *
     * @param array $rule
     *
     */
    protected function evaluate_user_role($rule)
    {

        if(!is_user_logged_in()){
            return false;
        }

        $user = wp_get_current_user();

        $roles = ( array ) $user->roles;

        $role = array_shift($roles);

        return $this->evaluate($role, $rule['comparison'], $rule['value-user-role']);

    }

    /**
     * Evaluate week
     *
     * @param array $rule
     *
     */
    protected function evaluate_weekday($rule)
    {

        switch ($rule['comparison']) {
            case 'starts':
                $comparison = 'equal';
                $value = date('N', $this->start_date);
                break;
            case 'ends':
                $comparison = 'equal';
                $value = date('N', $this->end_date);
                break;
            case 'does-not-start':
                $comparison = 'not-equal';
                $value = date('N', $this->start_date);
                break;
            case 'does-not-end':
                $comparison = 'not-equal';
                $value = date('N', $this->end_date);
                break;
        }

        return $this->evaluate($value, $comparison, $rule['value-weekday']);

    }

    /**
     * Evaluate form field
     *
     * @param array $rule
     *
     */
    protected function evaluate_form_field($rule)
    {
        if(empty($rule['form_field'])){
            return false;
        }
        
        list($form_id, $form_field) = explode('-', $rule['form_field']);

        // Check if the field belogs to the current form
        if ($this->form->get('id') != $form_id) {
            return false;
        }

        $form_value = '';

        // Get current field value
        foreach ($this->form_fields as $field) {
            if ($field['id'] != $form_field) {
                continue;
            }

            // Get value
            $form_value = (isset($field['user_value'])) ? $field['user_value'] : '';

            // Handle Pricing options differently
            if (wpbs_form_field_is_product($field['type'])) {
                $form_value = wpbs_get_form_field_product_values($field);
            }

            if ($field['type'] == 'payment_method' && $form_value != '') {
                $form_value = wpbs_get_payment_methods()[$form_value];
            }

        }

        // Check if it's an array or not
        if (is_array($form_value)) {
            foreach ($form_value as $value) {
                if ($this->evaluate($value, $rule['comparison'], $rule['value']) == true) {
                    return true;
                }
            }
        } else {
            return $this->evaluate($form_value, $rule['comparison'], $rule['value']);
        }

    }

    /**
     * Evaluate the currency
     *
     * @param array $rule
     *
     */
    public function evaluate_currency($rule){
        return $this->evaluate($this->prices['currency'], $rule['comparison'], $rule['value']);
    }

    /**
     * Helper function to compare two values with dynamic operator
     *
     * @param mixed $x
     * @param string $operator
     * @param mixed $y
     *
     * @return bool
     *
     */
    protected function evaluate($x, $operator, $y)
    {
        switch ($operator) {
            case 'lower':
                return ($x < $y);
            case 'greater':
                return ($x > $y);
            case 'equal':
                return ($x == $y);
            case 'not-equal':
                return ($x != $y);
            case 'logged_in':
                return ($x == $y);
            case 'not_logged_in':
                return ($x != $y);
        }
        return false;
    }

}
