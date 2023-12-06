<?php

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

class WPBS_Mailer
{
    /**
     * The recepient of the email
     *
     * @access protected
     * @var    string
     *
     */
    protected $send_to;

    /**
     * CC email(s)
     *
     * @access protected
     * @var    string
     *
     */
    protected $send_to_cc;

    /**
     * BCC email(s)
     *
     * @access protected
     * @var    string
     *
     */
    protected $send_to_bcc;

    /**
     * The From Name header
     *
     * @access protected
     * @var    string
     *
     */
    protected $from_name;

    /**
     * The From Email header
     *
     * @access protected
     * @var    string
     *
     */
    protected $from_email;

    /**
     * The Reply To header
     *
     * @access protected
     * @var    string
     *
     */
    protected $reply_to;

    /**
     * The email subject
     *
     * @access protected
     * @var    string
     *
     */
    protected $subject;

    /**
     * The email message
     *
     * @access protected
     * @var    string
     *
     */
    protected $message;

    /**
     * The attachments
     *
     * @access protected
     * @var    string
     *
     */
    protected $attachments = array();

    /**
     * Set the content type of the email to "text/html"
     *
     * @return string
     *
     */
    public function mail_content_type()
    {
        return "text/html";
    }

    public function log_email(){

        if(!isset($this->settings['email_logs']) || $this->settings['email_logs'] != 'on'){
            return false;
        }

        if($this->type == 'admin'){
            return false;
        }

        $log_data = array(
            'send_date' => current_time('timestamp'),
            'email_type' => $this->type,
            'send_to' => $this->send_to,
            'from_name' => $this->from_name,
            'from_email' => $this->from_email,
            'send_to_cc' => $this->send_to_cc,
            'send_to_bcc' => $this->send_to_bcc,
            'message' => $this->message
        );

        wpbs_add_booking_meta($this->booking->get('id'), 'email_log', $log_data);
    }

    /**
     * Sent the email
     *
     */
    public function send()
    {
        // If send_to is empty, exit
        if (empty($this->send_to) || $this->send_to == '-') {
            return false;
        }

        // Add Headers
        $headers = array();
        $headers[] = 'MIME-Version: 1.0';

        if (!empty($this->from_email)) {
            $headers[] = 'From: ' . $this->from_name . ' <' . $this->from_email . '>';
        }

        if (!empty($this->reply_to)) {
            $headers[] = 'Reply-To: ' . $this->reply_to;
        }

        if (!empty($this->send_to_cc)) {
            $headers[] = 'Cc: ' . $this->send_to_cc;
        }

        if (!empty($this->send_to_bcc)) {
            $headers[] = 'Bcc: ' . $this->send_to_bcc;
        }

        $this->message = wpautop($this->message);

        $this->message = stripslashes($this->message);

        $this->settings = get_option('wpbs_settings');

        if (!isset($this->settings['fancy_emails_disable']) || $this->settings['fancy_emails_disable'] != 'on') {

            $email_template = new WPBS_Email_Template($this->message, $this->language);
            $this->message = $email_template->get_output();

        }

        $this->message = apply_filters('wpbs_mailer_field_message', $this->message);

        $this->log_email();

        // Set the content type to HTML
        add_filter('wp_mail_content_type', array($this, 'mail_content_type'));

        // ..and off you go
        $email = wp_mail($this->send_to, $this->subject, $this->message, $headers, $this->attachments);

        // Remove filters after sending the email
        remove_filter('wp_mail_content_type', array($this, 'mail_content_type'));

        if($email === false){
            do_action('wpbs_mailer_email_failed_to_send', $this);
            return false;
        }

        do_action('wpbs_mailer_end', $this);

    }

}
