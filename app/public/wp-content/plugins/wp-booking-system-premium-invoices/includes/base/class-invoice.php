<?php

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

class WPBS_Invoice
{

    /**
     * The Booking
     *
     * @access public
     * @var WPBS_Booking
     *
     */
    public $booking;

    /**
     * The Form
     *
     * @access public
     * @var WPBS_Form
     *
     */
    public $form;

    /**
     * The Calendar
     *
     * @access public
     * @var WPBS_Calendar
     *
     */
    public $calendar;

    /**
     * The Payment
     *
     * @access public
     * @var WPBS_Payment
     *
     */
    public $payment;

    /**
     * The plugin settings
     *
     * @access public
     * @var array
     *
     */
    public $plugin_settings;

    /**
     * The pdf generator class
     *
     * @access protected
     *
     */
    protected $pdf;

    /**
     * The pdf output type
     *
     * @access protected
     *
     */
    protected $output;

    /**
     * Constructor
     *
     * @param WPBS_Booking $booking
     * @param string $output
     *
     */
    public function __construct(WPBS_Booking $booking, $output = 'I')
    {

        /**
         * Load the mPDF library
         *
         */
        require_once WPBS_INVC_PLUGIN_DIR . 'libs/mpdf/vendor/autoload.php';

        /**
         * Get the booking
         *
         */
        $this->booking = $booking;

        /**
         * Get the language
         *
         */
        $this->language = wpbs_get_booking_meta($booking->get('id'), 'submitted_language', true);

        // Allow overwriting the language
        if (isset($_GET['language']) && !empty($_GET['language'])) {
            $this->language = sanitize_text_field($_GET['language']);
        }

        /**
         * Get the Form
         *
         */
        $this->form = wpbs_get_form($this->booking->get('form_id'));

        /**
         * Get the Calendar
         *
         */
        $this->calendar = wpbs_get_calendar($this->booking->get('calendar_id'));

        /**
         * Get the Payment
         *
         */
        $this->payment = wpbs_get_payment_by_booking_id($this->booking->get('id'));

        /**
         * Check if the payment exists
         *
         */
        if ($this->payment === false) {
            return false;
        }

        /**
         * Get the plugin settings
         *
         */
        $this->plugin_settings = get_option('wpbs_settings', array());

        /**
         * Set the output type
         *
         * I = Inline
         * F = Write to file
         *
         */
        $this->output = $output;

        /**
         * Initialize the invoice amounts
         * 
         */
        $this->amounts = array('subtotal' => 0, 'vat' => 0, 'total' => 0);

        /**
         * Check if we have VAT enabled or not
         * 
         */
        $this->vat = isset($this->plugin_settings['invoice_vat']) && !empty($this->plugin_settings['invoice_vat']) ? $this->plugin_settings['invoice_vat'] : false;

        /**
         * Generate the invoice
         *
         */
        $this->invoice();
    }

    /**
     * Output the invoice
     *
     */
    protected function invoice()
    {

        $this->pdf = new \Mpdf\Mpdf([

            'fontdata' => [
                'noto' => [
                    'R' => 'ntr.ttf',
                    'B' => 'ntcb.ttf',
                ],
            ],
            'default_font' => 'noto',
            'margin_top' => 8,
            'margin_right' => 0,
            'margin_bottom' => 8,
            'margin_left' => 0,
            'margin_header' => 0,
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

        $this->pdf->AddPage();

        $this->pdf->SetTextColor(0, 0, 0);

        $file_name = $this->get_invoice_file_name();

        $template_file = apply_filters('wpbs_invoice_template_file', WPBS_INVC_PLUGIN_DIR . 'templates/invoice-template.php');

        ob_start();
        require $template_file;

        $html = ob_get_contents();
        ob_end_clean();

        $this->pdf->WriteHTML($html);

        $this->pdf->Output($file_name, $this->output);
    }

    /**
     * Get the invoice file name
     *
     * @return string
     *
     */
    public function get_invoice_file_name()
    {

        // Get the prefix
        $invoice_prefix = $this->get_option('attachment_prefix') ?: 'invoice';

        // Check if we need to write the file to the disk
        if ($this->output == 'F') {
            return WPBS_PLUGIN_DIR . 'temp/' . sanitize_title($invoice_prefix) . '-' . $this->booking->get('id') . '.pdf';
        }

        // Output the file inline
        $invoice_name = apply_filters('wpbs_invoice_name', $this->get_invoice_number() . '.pdf', $this->get_invoice_number());

        return $invoice_name;
    }

    /**
     * Get plugin option in the correct language
     *
     * @param string $option
     *
     * @return string
     *
     */
    protected function get_option($option)
    {

        if (!empty($this->plugin_settings['invoice_' . $option . '_translation_' . $this->language])) {
            return esc_attr($this->plugin_settings['invoice_' . $option . '_translation_' . $this->language]);
        }

        if (!empty($this->plugin_settings['invoice_' . $option])) {
            return esc_attr($this->plugin_settings['invoice_' . $option]);
        }

        return '';
    }

    /**
     * Get plugin string in the correct language
     *
     * @param string $string
     *
     * @return string
     *
     */
    protected function get_string($string)
    {

        if (!empty($this->plugin_settings['invoice_strings'][$string . '_translation_' . $this->language])) {
            return html_entity_decode(esc_attr($this->plugin_settings['invoice_strings'][$string . '_translation_' . $this->language]), ENT_QUOTES | ENT_XML1, 'UTF-8');
        }

        if (!empty($this->plugin_settings['invoice_strings'][$string])) {
            return html_entity_decode(esc_attr($this->plugin_settings['invoice_strings'][$string]), ENT_QUOTES | ENT_XML1, 'UTF-8');
        }

        return html_entity_decode(wpbs_invoice_default_strings()[$string], ENT_QUOTES | ENT_XML1, 'UTF-8');
    }

    /**
     * Get line items
     *
     * @return array
     *
     */
    protected function get_line_items()
    {
        $prices = $this->payment->get('prices');

        $line_items = apply_filters('wpbs_invoice_line_items', $this->payment->get_line_items());

        // Check if we display individual days
        if (isset($line_items['events']) && isset($this->plugin_settings['invoice_individual_items']) && $this->plugin_settings['invoice_individual_items'] == 'on' && $line_items['events']['prices_per_day']) {

            $prices_per_day = array();
            $price_per_day = false;

            // Check if we have any discounts applied to the calendar price if the Individual Days option is active.
            $total_prices_per_day = 0;
            foreach ($line_items['events']['prices_per_day'] as $day => $price) {
                $total_prices_per_day += $price;
            }

            if ($total_prices_per_day != $prices['events']['price_with_vat']) {
                $price_per_day = $prices['events']['price_with_vat'] / count($line_items['events']['prices_per_day']);
            }

            foreach ($line_items['events']['prices_per_day'] as $day => $price) {
                $date = DateTime::createFromFormat('Ymd', $day);
                $prices_per_day[] = array(
                    'label' => $this->payment->get('details')['price']['events']['name'] . ' - ' . wpbs_date_i18n(get_option('date_format'), $date->getTimestamp()),
                    'value' => wpbs_get_formatted_price($price, $this->payment->get_currency()),
                    'quantity' => 1,
                    'individual_price' => $price_per_day ?: $price,
                    'price' => $price_per_day ?: $price,
                    'price_with_vat' => $price_per_day ?: $price,
                    'type' => 'event',
                );
            }

            unset($line_items['events']);

            $line_items = array_merge($prices_per_day, $line_items);
        }

        // Loop through the line items
        foreach ($line_items as $line_item) {

            // Skip some fields
            if (in_array($line_item['type'], array('tax', 'vat', 'part-payment-first-payment', 'part-payment-second-payment', 'total'))) {
                continue;
            }

            if (!$line_item['quantity']) {
                $line_item['quantity'] = 1;
            }

            // backwards compatibility
            if (!isset($line_item['price_with_vat']) || empty($line_item['price_with_vat'])) {
                if (isset($prices['vat_percentage'])) {
                    $line_item['price_with_vat'] = $line_item['price'] * (1 + $prices['vat_percentage'] / 100);
                } else {
                    $line_item['price_with_vat'] = $line_item['price'];
                }
                $line_item['price_with_vat'] = round($line_item['price_with_vat'], 3);
                $line_item['price_with_vat'] = round($line_item['price_with_vat'], 2);
            }

            $line_total = $line_item['price_with_vat'];

            $vat = 0;

            // if VAT is enabled
            if ($this->vat > 0) {
                $vat_percentage = apply_filters('wpbs_invoice_line_item_vat_percentage', $this->vat, $line_item);
                $vat = round($line_total - $line_total / (1 + ($vat_percentage / 100)), 2);
                $this->amounts['vat'] += $vat;
                $line_subtotal = $line_total - $vat;
                $line_item_unit_price = round($line_subtotal / $line_item['quantity'], 4);
            } else {
                $line_item_unit_price = round($line_total / $line_item['quantity'], 4);
            }

            // Hide free line items?
            $hide_free_items = (isset($this->plugin_settings['invoice_free_items']) && $this->plugin_settings['invoice_free_items'] == 'on') ? true : false;

            if ($hide_free_items && $line_total == 0) {
                continue;
            }

            $this->amounts['subtotal'] += $line_item_unit_price * $line_item['quantity'];
            $this->amounts['total'] += $line_total;

            $formatted_line_items[] = array(
                'label' => $this->remove_formatting($line_item['label']),
                'quantity' => $line_item['quantity'],
                'unit_price' => $line_item_unit_price,
                'vat' => $vat,
                'total' => $line_total,
            );
        }

        if ($this->amounts['total'] != $this->amounts['subtotal'] + $this->amounts['vat']) {
            $this->amounts['total'] = $this->amounts['subtotal'] + $this->amounts['vat'];
        }

        return $formatted_line_items;
    }

    /**
     * Remove some unwanted formatting
     *
     * @param string $string
     *
     * @return string
     *
     */
    protected function remove_formatting($string)
    {
        // Remove quantity from labels
        $string = preg_replace('/<span class="wpbs-line-item-quantity\b[^>]*>(.*?)<\/span>/i', '', $string);
        $string = strip_tags($string);
        $string = str_replace('&times;', 'x', $string);
        $string = html_entity_decode($string, ENT_QUOTES);
        return $string;
    }

    /**
     * Get the Accent Color
     *
     * @return string
     *
     */
    public function get_accent_color()
    {
        $accent_color = $this->get_option('color') ? $this->get_option('color') : '999999';
        return apply_filters('wpbs_invoice_accent_color', $accent_color);
    }

    /**
     * Get the Text Color
     *
     * @return string
     *
     */
    public function get_text_color()
    {
        $text_color = '444444';
        return apply_filters('wpbs_invoice_text_color', $text_color);
    }

    /**
     * Get logo type
     *
     * @return string
     *
     */
    public function get_logo_type()
    {
        return $this->get_option('logo_type') == 'image' ? 'image' : 'text';
    }

    /**
     * Get logo image
     *
     * @return string
     *
     */
    public function get_logo_image()
    {
        if ($this->get_option('logo_type') == 'image' && $this->get_option('logo_image')) {

            $image = $this->get_option('logo_image');

            $image_exists = @fopen($image, 'r');

            if (!$image_exists) {
                $image = ABSPATH . str_replace(get_bloginfo('url'), '', $image);
                $image_exists = @fopen($image, 'r');
            }

            if ($image_exists) {

                $path_parts = pathinfo($image);
                if (in_array($path_parts['extension'], array('jpg', 'jpeg', 'png'))) {

                    return $image;
                } else {
                    return 'Error: Logo must be a PNG or a JPG file.';
                }
            } else {
                return 'Error: Logo Image Missing';
            }
        }
    }

    /**
     * Get logo image max-height
     *
     * @return int
     *
     */
    public function get_logo_image_max_height()
    {
        return apply_filters('wpbs_invoice_logo_max_height', 100);
    }

    /**
     * Get logo heading
     *
     * @return string
     *
     */
    public function get_logo_heading()
    {
        return $this->get_option('logo_heading');
    }

    /**
     * Get logo subheading
     *
     * @return string
     *
     */
    public function get_logo_subheading()
    {
        return $this->get_option('logo_subheading');
    }

    /**
     * Get invoice number
     *
     * @return string
     *
     */
    public function get_invoice_number()
    {
        $series = ($this->get_option('series')) ? $this->get_option('series') : '#';
        $number = $this->booking->get('id');

        if ($this->get_option('number_offset')) {
            $number += (int) $this->get_option('number_offset');
        }

        $number = str_pad($number, 6, '0', STR_PAD_LEFT);

        $number = apply_filters('wpbs_invoie_number', $number);

        return $series . $number;
    }

    /**
     * Get the seller details
     *
     */
    public function get_seller_details()
    {
        $seller_details = isset($this->form) ? wpbs_get_translated_form_meta($this->form->get('id'), 'invoice_seller_details', $this->language) : '';
        if (empty($seller_details)) {
            $seller_details = $this->get_option('seller_details');
        }

        $seller_details = html_entity_decode($seller_details, ENT_QUOTES | ENT_XML1, 'UTF-8');

        $email_tags = new WPBS_Email_Tags($this->form, $this->calendar, $this->booking->get('id'), $this->booking->get('fields'), $this->language, strtotime($this->booking->get('start_date')), strtotime($this->booking->get('end_date')));
        $seller_details = $email_tags->parse($seller_details);

        $seller_details = nl2br($seller_details);

        return $seller_details;
    }

    /**
     * Get the buyer details
     *
     */
    public function get_buyer_details()
    {
        $buyer_details = html_entity_decode(wpbs_get_booking_meta($this->booking->get('id'), 'invoice_buyer_details', true), ENT_QUOTES | ENT_XML1, 'UTF-8');

        $buyer_details = nl2br($buyer_details);

        return $buyer_details;
    }

    /**
     * Get the invoice date
     *
     */
    public function get_date()
    {
        return wpbs_date_i18n(get_option('date_format'), strtotime($this->booking->get('date_created')));
    }

    /**
     * Get the invoice due date
     *
     */
    public function get_due_date()
    {
        if (empty($this->get_option('due_date'))) {
            return false;
        }

        $invoice_date = DateTime::createFromFormat('U', strtotime($this->booking->get('date_created')));

        $invoice_date->modify('+' . absint($this->get_option('due_date')) . ' days');

        return wpbs_date_i18n(get_option('date_format'), $invoice_date->getTimestamp());
    }

    /**
     * Get the number of table columns
     *
     * @return int
     *
     */
    public function get_table_column_count()
    {
        return $this->vat ? 5 : 4;
    }

    /**
     * Get the table heading
     *
     * @return array
     *
     */
    public function get_table_heading()
    {
        $columns = [];

        $columns[] = [
            'id' => 'description',
            'class' => 'text-left width-large',
            'label' => $this->get_string('description'),
        ];

        $columns[] = [
            'id' => 'quantity',
            'class' => 'text-center',
            'label' => $this->get_string('quantity'),
        ];

        $columns[] = [
            'id' => 'unit_price',
            'class' => '',
            'label' => $this->get_string('unit_price'),
        ];

        if ($this->vat) {
            $columns[] = [
                'id' => 'vat',
                'class' => '',
                'label' => $this->get_string('vat'),
            ];
        }

        $columns[] = [
            'id' => 'total',
            'class' => 'col-last',
            'label' => $this->get_string('total'),
        ];

        return apply_filters('wpbs_invoice_table_heading', $columns);
    }

    /**
     * Get the table items
     *
     * @return array
     *
     */
    public function get_table_line_items()
    {

        $rows = [];

        $line_items = $this->get_line_items();

        foreach ($line_items as $line_item) {

            $row = [];

            $row[] = [
                'id' => 'description',
                'class' => 'text-left width-large',
                'label' => $line_item['label'],
            ];

            $row[] = [
                'id' => 'quantity',
                'class' => 'text-center',
                'label' => $line_item['quantity'],
            ];

            $row[] = [
                'id' => 'unit_price',
                'class' => '',
                'label' => wpbs_get_formatted_price($line_item['unit_price'], $this->payment->get_currency(), false, apply_filters('wpbs_invoice_decimals', 3)) . apply_filters('wpbs_invoice_line_after_price', '', $line_item['unit_price']),
            ];

            if ($this->vat > 0) {
                $row[] = [
                    'id' => 'vat',
                    'class' => '',
                    'label' => wpbs_get_formatted_price($line_item['vat'], $this->payment->get_currency()) . apply_filters('wpbs_invoice_line_after_price', '', $line_item['vat']),
                ];
            }

            $row[] = [
                'id' => 'total',
                'class' => 'col-last',
                'label' => wpbs_get_formatted_price($line_item['total'], $this->payment->get_currency()) . apply_filters('wpbs_invoice_line_after_price', '', $line_item['total']),
            ];

            $rows[] = $row;
        }

        return apply_filters('wpbs_invoice_table_line_items', $rows);
    }

    /**
     * Get the table items
     *
     * @return array
     *
     */
    public function get_table_footer()
    {

        $rows = [];

        $colspan = $this->get_table_column_count() - 3;

        // Subtotal
        $rows['subtotal'] = [
            [
                'id' => 'pad',
                'colspan' => $colspan,
                'class' => 'empty',
                'label' => '',
            ],
            [
                'id' => 'footer-label',
                'colspan' => '2',
                'class' => 'text-left text-large',
                'label' => $this->get_string('subtotal'),
            ],
            [
                'id' => 'footer-value',
                'colspan' => '1',
                'class' => 'text-large col-last',
                'label' => wpbs_get_formatted_price($this->amounts['subtotal'], $this->payment->get_currency()) . apply_filters('wpbs_invoice_line_after_price', '', $this->amounts['subtotal']),
            ],

        ];

        // VAT
        if ($this->amounts['vat'] > 0) {

            $rows['vat'] = [
                [
                    'id' => 'pad',
                    'colspan' => $colspan,
                    'class' => 'empty',
                    'label' => '',
                ],
                [
                    'id' => 'footer-label',
                    'colspan' => '2',
                    'class' => 'text-left text-large',
                    'label' => $this->get_string('vat'),
                ],
                [
                    'id' => 'footer-value',
                    'colspan' => '1',
                    'class' => 'text-large col-last',
                    'label' => wpbs_get_formatted_price($this->amounts['vat'], $this->payment->get_currency()) . apply_filters('wpbs_invoice_line_after_price', '', $this->amounts['vat']),
                ],

            ];
        }

        // Taxes
        foreach ($this->payment->get_line_items() as $i => $line_item) {

            if (!in_array($line_item['type'], array('tax'))) {
                continue;
            }

            $this->amounts['total'] += $line_item['price'];
            $label = $this->remove_formatting($line_item['label']);
            $value = $this->remove_formatting($line_item['value']);

            $rows['tax_' . $i] = [
                [
                    'id' => 'pad',
                    'colspan' => $colspan,
                    'class' => 'empty',
                    'label' => '',
                ],
                [
                    'id' => 'footer-label',
                    'colspan' => '2',
                    'class' => 'text-left text-large',
                    'label' => $label,
                ],
                [
                    'id' => 'footer-value',
                    'colspan' => '1',
                    'class' => 'text-large col-last',
                    'label' => $value . apply_filters('wpbs_invoice_line_after_price', '', ($line_item['price'])),
                ],

            ];
        }

        // Total
        $rows['total'] = [
            [
                'id' => 'pad',
                'colspan' => $colspan,
                'class' => 'empty',
                'label' => '',
            ],
            [
                'id' => 'footer-label',
                'colspan' => '2',
                'class' => 'text-left text-large text-highlighted',
                'label' => $this->get_string('total'),
            ],
            [
                'id' => 'footer-value',
                'colspan' => '1',
                'class' => 'text-large text-highlighted col-last',
                'label' => wpbs_get_formatted_price($this->amounts['total'], $this->payment->get_currency()) . apply_filters('wpbs_invoice_line_after_price', '', $this->amounts['total']),
            ],

        ];

        return apply_filters('wpbs_invoice_table-footer', $rows);
    }

    /**
     * Get the footer notes heading
     * 
     * @return string
     *
     */
    public function get_footer_notes_heading()
    {
        $footer_notes_heading = isset($this->form) ? wpbs_get_translated_form_meta($this->form->get('id'), 'invoice_footer_notes_heading', $this->language) : '';
        if (empty($footer_notes_heading)) {
            $footer_notes_heading = $this->get_option('footer_notes_heading');
        }

        $footer_notes_heading = html_entity_decode($footer_notes_heading, ENT_QUOTES | ENT_XML1, 'UTF-8');

        return $footer_notes_heading;
    }

    /**
     * Get the footer notes body
     * 
     * @return string
     *
     */
    public function get_footer_notes_body()
    {
        $footer_notes = isset($this->form) ? wpbs_get_translated_form_meta($this->form->get('id'), 'invoice_footer_notes', $this->language) : '';
        if (empty($footer_notes)) {
            $footer_notes = $this->get_option('footer_notes');
        }

        $footer_notes = html_entity_decode($footer_notes, ENT_QUOTES | ENT_XML1, 'UTF-8');

        $email_tags = new WPBS_Email_Tags($this->form, $this->calendar, $this->booking->get('id'), $this->booking->get('fields'), $this->language, strtotime($this->booking->get('start_date')), strtotime($this->booking->get('end_date')));
        $footer_notes = $email_tags->parse($footer_notes);

        $footer_notes = nl2br($footer_notes);

        return $footer_notes;
    }

    /**
     * Get the footer booking details heading
     * 
     * @return string
     *
     */
    public function get_footer_booking_details()
    {

        if (!$this->get_option('booking_details')) {
            return false;
        }

        $rows = [];

        $rows['booking_id'] = [
            'label' => wpbs_get_form_default_string(($this->form ? $this->form->get('id') : ''), 'booking_id', $this->language),
            'value' => '#' . $this->booking->get('id')
        ];

        $rows['start_date'] = [
            'label' => wpbs_get_form_default_string(($this->form ? $this->form->get('id') : ''), 'start_date', $this->language),
            'value' => wpbs_date_i18n(get_option('date_format'), strtotime($this->booking->get('start_date')))
        ];

        $rows['end_date'] = [
            'label' => wpbs_get_form_default_string(($this->form ? $this->form->get('id') : ''), 'end_date', $this->language),
            'value' => wpbs_date_i18n(get_option('date_format'), strtotime($this->booking->get('end_date')))
        ];


        $calendar = wpbs_get_calendar($this->booking->get('calendar_id'));
        $rows['calendar_name'] = [
            'label' => $this->get_string('calendar'),
            'value' => $calendar->get_name($this->language)
        ];

        if ($this->payment->is_part_payment()) {

            $rows['part_payments_deposit'] = [
                'label' => wpbs_get_payment_default_string('part_payments_deposit', $this->language),
                'value' => wpbs_get_formatted_price($this->payment->get_total_first_payment(), $this->payment->get_currency())
            ];

            $rows['part_payments_final_payment'] = [
                'label' => wpbs_get_payment_default_string('part_payments_final_payment_' . $this->payment->get_final_payment_method(), $this->language),
                'value' => wpbs_get_formatted_price($this->payment->get_total_second_payment(), $this->payment->get_currency())
            ];
        }

        return apply_filters('wpbs_invoice_footer-booking-details', $rows);
    }

    /**
     * Add some custom text to the footer
     * 
     * @return string
     *
     */
    public function get_footer_custom_text()
    {
        $custom_text = apply_filters('wpbs_invoice_custom_footer_text', '', $this->booking);

        if (empty($custom_text)) {
            return false;
        }

        return $custom_text;
    }
}
