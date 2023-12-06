<?php

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

class WPBS_Email_Template
{
    /**
     * The email body
     *
     * @access protected
     * @var    string
     *
     */
    public $body;

    /**
     * The email footer_text
     *
     * @access protected
     * @var    string
     *
     */
    public $footer_text;

    /**
     * The email logo
     *
     * @access protected
     * @var    string
     *
     */
    private $logo = false;

    /**
     * The email accent colour
     *
     * @access protected
     * @var    string
     *
     */
    private $accent = '#f7f7f7';

    /**
     * Constructor
     *
     */
    public function __construct($body, $language)
    {
        // Wrap the tables in another table, email templates are hacky
        if (apply_filters('wpbs_email_template_table_wrapping', true)) {
            $body = str_replace('<table>', '<table><tr><td><table>', $body);
            $body = str_replace('</table>', '</table></td></tr></table>', $body);
        }

        // Set the body
        $this->body = $body;

        // Get the settings
        $settings = get_option('wpbs_settings');

        $this->footer_text = isset($settings['email_footer_translation_' . $language]) && $settings['email_footer_translation_' . $language] ? $settings['email_footer_translation_' . $language] : (isset($settings['email_footer']) && $settings['email_footer'] ? $settings['email_footer'] : '');

        $this->logo_height = 0;

        // Set the logo
        if (isset($settings['fancy_emails_logo']) && !empty($settings['fancy_emails_logo'])) {
            $this->logo = $settings['fancy_emails_logo'];
            if (strpos($this->logo, 'http') !== 0) {
                $this->logo = get_bloginfo('url') . $this->logo;
            }
            $this->logo_height = ($settings['fancy_emails_logo_height'] ?: '60') . 'px';
        }

        // Set the accent colour
        if (isset($settings['fancy_emails_accent']) && !empty($settings['fancy_emails_accent'])) {
            $this->accent = $settings['fancy_emails_accent'];
        }
    }

    /**
     * Returns the final email template with all the contents in place.
     * 
     */
    public function get_output()
    {

        $template_file = apply_filters('wpbs_email_template_file', WPBS_PLUGIN_DIR . 'templates/email-template.php');

        ob_start();
        require $template_file;

        $html = ob_get_contents();
        ob_end_clean();

        return $html;
    }

    /**
     * Add the styling
     * 
     */
    public function get_styling()
    {
        echo '<style>
            img {
            border: none;
            -ms-interpolation-mode: bicubic;
            max-width: 100%;
            }

            body {
                padding: 0;
                margin: 0;
                background-color: #eee;
                font-family: -apple-system, system-ui, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
                -webkit-font-smoothing: antialiased;
                font-size: 14px;
                line-height: 1.4;
                -ms-text-size-adjust: 100%;
                -webkit-text-size-adjust: 100%;
            }

            body * {
                padding: 0;
                margin: 0;
            }

            table {
                border-collapse: collapse;
                mso-table-lspace: 0pt;
                mso-table-rspace: 0pt;
            }

            table td {
                font-family: -apple-system, system-ui, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
                font-size: 14px;
                vertical-align: top;
            }

            .body {
                padding: 30px 15px;
                border-collapse: separate;
                background-color: #eee;
                width: 100%;
            }

            .bodywrap {
                width: 600px;
            }

            .container {
                display: block;
                margin: 0 auto !important;
                max-width: 580px;
                padding: 0;
                width: 600px;
            }

            .content {
                box-sizing: border-box;
                display: block;
                margin: 0 auto;
                max-width: 580px;
                padding: 10px;
            }

            .main {
                background: #ffffff;
                border-radius: 10px;
                width: 100%;
                overflow: hidden;
            }

            .wrapper {
                box-sizing: border-box;
                padding: 10px 38px 10px;
            }

            .wrapper table {
                width: 100%;
                background: #fafafa;
                padding: 0px 21px;
                margin-bottom: 12px;
                border-radius: 5px;
                border-collapse: separate;
            }

            .wrapper table table {
                width: 100%;
                padding: 0;
                border-radius: 0;
                border-collapse: collapse;
                margin-bottom: 0;
            }

            .wrapper table table th,
            .wrapper table table td {
                padding: 10px 0;
                border-top: 1px solid #e0e0e0;
                color: #333544;
            }

            .wrapper table table tr.wpbs-table-first-row th,
            .wrapper table table tr.wpbs-table-first-row td {
                border-top: 0;
            }

            .wrapper table th {
                text-align: left;
            }

            .wrapper table td {
                text-align: right;
            }

            .wrapper table td p {
                padding-bottom:10px;
            }

            .content-block {
                padding-bottom: 10px;
                padding-top: 10px;
            }

            .footer {
                clear: both;
                margin-top: 10px;
                margin-bottom: 10px;
                text-align: center;
                width: 100%;
            }

            .footer td,
            .footer p,
            .footer span,
            .footer a {
                color: #999999;
                font-size: 12px;
                text-align: center;
            }

            .footer-text {
                color: #999;
                font-size: 13px;
                padding-top: 10px;
                text-align: center;
            }

            .footer-text a {
                color: #999;
            }

            h1,
            h2,
            h3,
            h4 {
                color: #000000;
                font-family: -apple-system, system-ui, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
                line-height: 1.4;
                margin: 0;
                color: #333544;
            }

            h1 {
                font-size: 35px;
                font-weight: bold;
                text-align: center;
            }

            h1,
            h2,
            h3,
            h4,
            h5,
            h6 {
                margin: 35px 0 20px 0;
            }

            p,
            ul,
            ol {
                font-family: -apple-system, system-ui, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
                font-size: 16px;
                font-weight: normal;
                padding: 0;
                margin: 24px 0 24px 0;
                color: #333544;
            }

            p li,
            ul li,
            ol li {
                list-style-position: inside;
                margin-left: 5px;
            }

            a {
                color: #3498db;
                text-decoration: underline;
            }

            .btn {
                box-sizing: border-box;
                width: 100%;
            }

            .btn>tbody>tr>td {
                padding-bottom: 15px;
            }

            .btn table {
                width: auto;
            }

            .btn table td {
                background-color: #ffffff;
                border-radius: 5px;
                text-align: center;
            }

            .btn a {
                background-color: #ffffff;
                border: solid 1px #3498db;
                border-radius: 5px;
                box-sizing: border-box;
                color: #3498db;
                cursor: pointer;
                display: inline-block;
                font-size: 14px;
                font-weight: bold;
                margin: 0;
                padding: 12px 25px;
                text-decoration: none;
                text-transform: capitalize;
            }

            .btn-primary table td {
                background-color: #3498db;
            }

            .btn-primary a {
                background-color: #3498db;
                border-color: #3498db;
                color: #ffffff;
            }

            .last {
                margin-bottom: 0;
            }

            .first {
                margin-top: 0;
            }

            .align-center {
                text-align: center;
            }

            .align-right {
                text-align: right;
            }

            .align-left {
                text-align: left;
            }

            .clear {
                clear: both;
            }

            .mt0 {
                margin-top: 0;
            }

            .mb0 {
                margin-bottom: 0;
            }

            .preheader {
                color: transparent;
                display: none;
                height: 0;
                max-height: 0;
                max-width: 0;
                opacity: 0;
                overflow: hidden;
                mso-hide: all;
                visibility: hidden;
                width: 0;
            }

            hr {
                border: 0;
                border-bottom: 1px solid #f6f6f6;
                margin: 20px 0;
            }

            .main .header {
                height: 10px;
                background-color: ' . $this->accent . ';
            }

            td.logo-row {
                text-align: center;
            }

            .logo {
                overflow: hidden;
                display: inline-block;
                width: auto;
                margin: 0 auto;
                display: inline-block;
                margin-top: 22px;
            }

            .logo img {
                display: block;
                max-height: ' . $this->logo_height . ';
                max-width: 500px;
                width: auto;
            }

            @media only screen and (max-width: 620px) {
                .bodywrap {
                    width: 100%;
                }

                .wrapper {
                    padding: 10px 15px 25px;
                }
                
                .wrapper table {
                    padding: 11px 15px;
                }

                table[class=body] h1 {
                    font-size: 28px !important;
                    margin-bottom: 10px !important;
                }

                table[class=body] p,
                table[class=body] ul,
                table[class=body] ol,
                table[class=body] td,
                table[class=body] span,
                table[class=body] a {
                    font-size: 16px !important;
                }

                table[class=body] .wrapper,
                table[class=body] .article {
                    padding: 23px !important;
                }

                table[class=body] .content {
                    padding: 0 !important;
                }

                table[class=body] .container {
                    width: auto !important;
                }

                table[class=body] .main {
                    border-left-width: 0 !important;
                    border-radius: 8px !important;
                    border-right-width: 0 !important;
                }

                table[class=body] .btn table {
                    width: 100% !important;
                }

                table[class=body] .btn a {
                    width: 100% !important;
                }

                table[class=body] .img-responsive {
                    height: auto !important;
                    max-width: 100% !important;
                    width: auto !important;
                }

                .logo img {
                    max-width: 220px;
                    max-height: 50px;
                }
            }

            @media all {
                .ExternalClass {
                    width: 100%;
                }

                .ExternalClass,
                .ExternalClass p,
                .ExternalClass span,
                .ExternalClass font,
                .ExternalClass td,
                .ExternalClass div {
                    line-height: 100%;
                }

                .apple-link a {
                    color: inherit !important;
                    font-family: inherit !important;
                    font-size: inherit !important;
                    font-weight: inherit !important;
                    line-height: inherit !important;
                    text-decoration: none !important;
                }

                #MessageViewBody a {
                    color: inherit;
                    text-decoration: none;
                    font-size: inherit;
                    font-family: inherit;
                    font-weight: inherit;
                    line-height: inherit;
                }

                .btn-primary table td:hover {
                    background-color: #34495e !important;
                }

                .btn-primary a:hover {
                    background-color: #34495e !important;
                    border-color: #34495e !important;
                }
            }
        </style>';
    }

    /**
     * Get the body text
     * 
     * @return string
     * 
     */
    public function get_body()
    {
        echo $this->body;
    }

    /**
     * Check if a logo exists
     * 
     * @return bool
     * 
     */
    public function has_logo()
    {
        if (!$this->logo) {
            return false;
        }

        return true;
    }

    /**
     * Get the logo
     * 
     * @return string
     * 
     */
    public function get_logo()
    {
        if (!$this->logo) {
            return '';
        }

        echo $this->logo;
    }

    /**
     * Check if a logo exists
     * 
     * @return bool
     * 
     */
    public function has_footer_text()
    {
        if (!$this->footer_text) {
            return false;
        }

        return true;
    }

    /**
     * Get the footer text
     * 
     * @return string
     * 
     */
    public function get_footer_text()
    {
        echo $this->footer_text;
    }
}
