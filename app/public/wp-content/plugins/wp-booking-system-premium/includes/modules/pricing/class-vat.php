<?php

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * The main class for the Payment
 *
 */
class WPBS_VAT
{

    private $plugin_settings;

    private $enabled;

    private $percentage;

    private $vat_amount = 0;

    public $percentage_calculation = 1;


    public function __construct()
    {

        $this->plugin_settings = get_option('wpbs_settings');

        $this->enabled = $this->check_if_enabled();

        if($this->enabled === false){
            return false;
        }

        $this->percentage =  floatval($this->plugin_settings['payment_vat_percentage']);

        $this->percentage_calculation = 1 + $this->percentage / 100;

    }

    public function check_if_enabled(){
        if (!isset($this->plugin_settings['payment_vat_enable']) || $this->plugin_settings['payment_vat_enable'] != 'on') {
            return false;
        }

        if(!isset($this->plugin_settings['payment_vat_percentage'])){
            return false;
        }

        if(absint($this->plugin_settings['payment_vat_percentage']) == 0){
            return false;
        }

        return true;
    }

    public function is_enabled()
    {
        return $this->enabled;
    }

    public function deduct_vat($price, $add = true)
    {
        if (!$this->is_enabled()) {
            return $price;
        }

        $price_without_vat = $price / $this->percentage_calculation;

        if($add){
            $this->add_vat_amount($price - $price_without_vat);
        }

        if(isset($this->plugin_settings['payment_vat_display_only']) && $this->plugin_settings['payment_vat_display_only'] == 'on' ){
            return $price;
        }
        
        return round($price_without_vat, 2);

    }

    public function add_vat_amount($amount)
    {
        $this->vat_amount += round($amount, 2);
    }

    public function get_vat_amount(){
        return $this->vat_amount;
    }

    public function get_name($language){
        if(isset($this->plugin_settings['payment_vat_name_translation_' . $language]) && !empty($this->plugin_settings['payment_vat_name_translation_' . $language])){
            return $this->plugin_settings['payment_vat_name_translation_' . $language];
        } 

        if(isset($this->plugin_settings['payment_vat_name']) && !empty($this->plugin_settings['payment_vat_name'])){
            return $this->plugin_settings['payment_vat_name'];
        } 

        return __('VAT','wp-booking-system');
    }

    public function get_percentage(){
        return $this->percentage;
    }
}
