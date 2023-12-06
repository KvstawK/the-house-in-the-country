<?php

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

class WPBS_Form_Conditional_Logic
{

    /**
     * The form fields
     *
     * @access protected
     * @var    array
     *
     */
    protected $form_fields = null;

    /**
     * The starting date
     *
     * @access protected
     * @var    int
     *
     */
    protected $start_date = null;

    /**
     * The ending date
     *
     * @access protected
     * @var    int
     *
     */
    protected $end_date = null;

    /**
     * Optional args 
     *
     * @access protected
     * @var    array
     *
     */
    protected $args = null;

    /**
     * Constructor
     *
     * @param array         $form_fields
     * @param int           $start_date
     * @param int           $end_date
     * @param array         $args
     *
     */
    public function __construct($form_fields, $start_date, $end_date, $args)
    {

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
         * Set the args
         *
         */
        $this->args = $args;

    }

    /**
     * Get an array of all the hidden form fields
     * 
     * @return array
     * 
     */
    public function get_hidden_fields(){

        $hidden_fields = array();

        foreach($this->form_fields as $field){

            if(!$this->check_if_required_by_conditional_logic($field)){
                $hidden_fields[$field['id']] = $field;
            }

        }

        return $hidden_fields;

    }


    /**
     * Check if there is a conditional rule applied on a required field
     * 
     * @param array $field
     * 
     * @return bool
     * 
     */
    protected function check_if_required_by_conditional_logic($field)
    {
        // Check if conditional logic is enabled for this field
        if (!isset($field['values']['default']['conditional_logic']) || $field['values']['default']['conditional_logic'] != 'on') {
            return true;
        }

        $rules = $field['values']['default']['conditional_logic_rules'];
        $logic = $field['values']['default']['conditional_logic_logic_type'];
        $action = $field['values']['default']['conditional_logic_action'];

        $total_rules = count($rules);
        $valid_rules = 0;
        $valid = false;

        foreach ($rules as $rule) {
            $compare_value = $this->get_compare_value($rule['field']);
            $rule_value = $this->get_rule_value($rule);
            $this->wpbs_evaluate_conditional_field_rule($compare_value, $rule_value, $rule['condition']) === true ? $valid_rules++ : '';
        }

        if (($logic == 'all' && $valid_rules == $total_rules) || ($logic == 'any' && $valid_rules > 0)) {
            $valid = true;
        }

        if ($valid === true) {
            if ($action == 'show') {
                return true;
            } else {
                return false;
            }
        } else {
            if ($action == 'show') {
                return false;
            } else {
                return true;
            }
        }

        return true;
    }

    /**
     * Get the conditional logic rule value
     * 
     * @param array $rule
     * 
     * @return string
     * 
     */
    protected function get_rule_value($rule){
        if($rule['field'] == 'start_weekday' || $rule['field'] == 'end_weekday'){
            return $rule['select_value'];
        }
        
        if($rule['field'] == 'start_date' || $rule['field'] == 'end_date'){
            $date = DateTime::createFromFormat('Y-m-d H:i:s', $rule['value'] . ' 00:00:00');
            if(!$date){
                return 0;
            }
            return $date->getTimestamp();
        }
        
        return $rule['value'];
    }

    /**
     * Get the conditional logic comparison value
     * 
     * @param string $field
     * 
     * @return string
     * 
     */
    protected function get_compare_value($field){
        if($field == 'start_date'){
            return $this->start_date;
        }

        if($field == 'end_date'){
            return $this->end_date;
        }

        if($field == 'start_weekday'){
            return date('N', $this->start_date);
        }

        if($field == 'end_weekday'){
            return date('N', $this->end_date);
        }

        if($field == 'stay_length'){
            return ($this->args['selection_style'] == 'split' ? 0 : 1) + ($this->end_date - $this->start_date) / DAY_IN_SECONDS;
        }

        if($field == 'calendar_id'){
            return $this->args['calendar_id'];
        }
        return $this->get_field_value($field);
    }

    /**
     * Get a field's value
     * 
     * @param string $field
     * 
     * @return string
     * 
     */
    protected function get_field_value($field_id)
    {
        $value = '';
        $field =  $this->form_fields[array_search($field_id, array_column($this->form_fields, 'id'))];
        $field_value = (isset($field['user_value']) && $field['user_value']) ? $field['user_value'] : '';

        if (is_array($field_value)) {
            foreach ($field_value as $val) {
                if (strpos($val, '|') !== false) {
                    $value .= trim(explode('|', $val)[1]) . ',';

                } else {
                    $value .= trim($val) . ',';
                }

            }
            $value = trim($value, ',');
        } else {
            if (strpos($field_value, '|') !== false) {
                $value = trim(explode('|', $field_value)[1]);

            } else {
                $value = trim($field_value);
            }
        }
        return $value;
    }
    
    /**
     * Evaluate a conditional field rule
     * 
     * @param mixed $a
     * @param mixed $b
     * @param string $operation
     * 
     * @return bool
     * 
     */
    protected function wpbs_evaluate_conditional_field_rule($a, $b, $operation)
    {
        $a = $a ? strtolower($a) : "";
        $b = $b ? strtolower($b) : "";

        switch ($operation) {
            case "is":
                return $a == $b;

            case "isnot":
                return $a != $b;

            case "greater":
                $a = floatval($a);
                $b = floatval($b);

                return is_numeric($a) && is_numeric($b) ? $a > $b : false;

            case "lower":
                $a = floatval($a);
                $b = floatval($b);

                return is_numeric($a) && is_numeric($b) ? $a < $b : false;

            case "contains":
                return strpos($a, $b) !== false;

            case "starts":
                return strpos($a, $b) === 0;

            case "ends":
                $start = strlen($a) - strlen($b);
                if ($start < 0) {
                    return false;
                }

                $tail = substr($a, $start);
                return $b == $tail;
        }
        return false;
    }

}
