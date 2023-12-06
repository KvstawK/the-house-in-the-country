<?php

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

class WPBS_Contract
{

    protected $booking;

    protected $form;

    protected $plugin_settings;

    protected $pdf;

    protected $config;

    protected $output;

    protected $debug = 0;

    protected $amounts = array('subtotal' => 0, 'vat' => 0, 'total' => 0);

    public function __construct(WPBS_Booking $booking, $output = 'I')
    {

        $this->output = $output;
        
        require_once WPBS_CNTRCT_PLUGIN_DIR . 'libs/mfpdf/vendor/autoload.php';

        $defaultConfig = (new Mpdf\Config\ConfigVariables())->getDefaults();
      

        $this->pdf = new \Mpdf\Mpdf([

            'fontdata' => [
                'noto' => [
                    'R' => 'ntr.ttf',
                    'B' => 'ntcb.ttf',
                ],
            ],
            'default_font' => 'noto',
        ]);

        $footer = array(
            'C' => array(
                'content' => '{PAGENO} / {nb}',
                'font-size' => 10,
                'font-style' => 'R',
                'font-family' => 'noto',
                'color' => '#5a5a5a',
            ),
            'line' => 0,
        );

        $this->pdf->SetFooter($footer, 'O');
        $this->pdf->SetFooter($footer, 'E');

        $this->booking = $booking;

        $this->form = wpbs_get_form($this->booking->get('form_id'));

        $this->plugin_settings = get_option('wpbs_settings', array());

        $this->language = wpbs_get_booking_meta($booking->get('id'), 'submitted_language', true);

        // Allow overwriting the language
        if (isset($_GET['language']) && !empty($_GET['language'])) {
            $this->language = sanitize_text_field($_GET['language']);
        }

        $this->contract();

    }

    protected function contract()
    {

        $this->pdf->AddPage();

        $this->add_logo();

        $this->add_contract_number();

        $this->add_content();

        $file_name = $this->get_contract_file_name();

        $this->pdf->Output($file_name, $this->output);

    }

    public function get_contract_file_name()
    {

        $contract_prefix = $this->get_option('attachment_prefix') ?: 'contract';

        if ($this->output == 'F') {
            return WPBS_PLUGIN_DIR . 'temp/' . sanitize_title($contract_prefix) . '-' . $this->booking->get('id') . '.pdf';
        }

        return 'contract-' . $this->booking->get('id') . '.pdf';
    }

    protected function add_logo()
    {

        if ($this->get_option('logo_type') == 'image' && $this->get_option('logo_image')) {

            $logo_height = apply_filters('wpbs_contract_logo_height', 18);

            if ($logo_height > 40) {
                $logo_height = 40;
            }

            $logo_y = (40 - $logo_height) / 2;
            $logo_file = $this->get_option('logo_image');

            $logo_exists = @fopen($logo_file, 'r');

            if (!$logo_exists) {
                $logo_file = ABSPATH . str_replace(get_bloginfo('url'), '', $logo_file);
                $logo_exists = @fopen($logo_file, 'r');
            }

            if ($logo_exists) {
                $this->pdf->Image($logo_file, 10, $logo_y, 0, $logo_height);
            } else {
                $this->pdf->SetFont('Noto', '', 10);
                $this->pdf->MultiCell(190, $logo_height, 'Error: Logo Image Missing');
            }

        } else {

            $heading = $this->get_option('logo_heading');
            $subheading = $this->get_option('logo_subheading');

            if ($heading && $subheading) {
                $this->pdf->setXY(10, 12);
                $this->pdf->SetFont('Noto', 'B', 24);
                $this->pdf->MultiCell(190, 10, $heading, $this->debug, 'L');
                $this->pdf->SetFont('Noto', 'B', 12);
                $this->pdf->SetTextColor(90, 90, 90);
                $this->pdf->setXY(10, 22);
                $this->pdf->MultiCell(190, 6, $subheading, $this->debug, 'L');
            } else {
                $this->pdf->setXY(10, 10);
                $this->pdf->SetFont('Noto', 'B', 24);
                $this->pdf->MultiCell(190, 20, $heading, $this->debug, 'L');
            }
        }

    }

    protected function add_contract_number()
    {

        if(apply_filters('wpbs_contract_show_contract_number', true) === false){
            return false;
        }

        $contract_number = $this->get_string('contract') . " " . $this->get_contract_number();

        // Contract number, 10 padding top
        $this->pdf->SetFont('Noto', 'B', 18);
        $this->pdf->SetTextColor(90, 90, 90);

        $this->pdf->setXY(110, 10);
        $this->pdf->MultiCell(90, 20, $contract_number, $this->debug, 'R');

    }

    protected function add_content()
    {
        $this->pdf->SetTextColor(0, 0, 0);

        $contract = wpbs_get_booking_meta($this->booking->get('id'), 'contract_content', true);
        
        $contract = apply_filters('the_content', $contract);

        $contract = str_replace('{PAGE_BREAK}', '<pagebreak>', $contract);

        $html = '<div class="wpbs-contract-body">';

        $html .= $contract;

        $html .= '<style>
            .wpbs-email-your-order-heading {display:none;}
            .wpbs-contract-body table {width: 100%;background: #fff;margin-bottom: 12px;border-collapse: collapse;}
            .wpbs-contract-body table th,
            .wpbs-contract-body table td {padding: 10px;border:1px solid #ccc;color: #333544;}
            .wpbs-contract-body table th {text-align: left;}
            .wpbs-contract-body table td {text-align: right;}
        </style>';

        $html .= '</div>';

        $signature = $this->get_signature();
        $html = str_replace('{Signature}', $signature, $html);

        $html = apply_filters('wpbs_contract_html', $html);

        $this->pdf->WriteHTML($html);
    }

    protected function get_signature(){
        $fields = $this->booking->get('fields');
        $key = array_search('signature', array_column($fields, 'type'));
        
        if($key === false){
            return '';
        }

        if(!isset($fields[$key]['user_value'])){
            return '';
        }
        
        $signature = $fields[$key]['user_value'];

        if(empty($signature)){
            return '';
        }

        $signature = json_decode(html_entity_decode($signature), true);

        $signature = '<img src="'.$signature['dataURL'].'" />';

        return $signature;

    }

    protected function get_option($option)
    {

        if (!empty($this->plugin_settings['contract_' . $option . '_translation_' . $this->language])) {
            return esc_attr($this->plugin_settings['contract_' . $option . '_translation_' . $this->language]);
        }

        if (!empty($this->plugin_settings['contract_' . $option])) {
            return esc_attr($this->plugin_settings['contract_' . $option]);
        }

        return '';

    }

    protected function get_contract_number()
    {
        $series = ($this->get_option('series')) ? $this->get_option('series') : '#';
        $number = $this->booking->get('id');

        if ($this->get_option('number_offset')) {
            $number += (int) $this->get_option('number_offset');
        }

        $number = str_pad($number, 6, '0', STR_PAD_LEFT);

        return $series . $number;
    }

    protected function get_string($string)
    {

        if (!empty($this->plugin_settings['contract_strings'][$string . '_translation_' . $this->language])) {
            return esc_attr($this->plugin_settings['contract_strings'][$string . '_translation_' . $this->language]);
        }

        if (!empty($this->plugin_settings['contract_strings'][$string])) {
            return esc_attr($this->plugin_settings['contract_strings'][$string]);
        }

        return wpbs_contract_default_strings()[$string];

    }

}
