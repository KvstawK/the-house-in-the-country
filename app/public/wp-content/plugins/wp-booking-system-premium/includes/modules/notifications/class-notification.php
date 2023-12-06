<?php

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * The main class for the Notification
 *
 */
class WPBS_Notification extends WPBS_Base_Object
{

    /**
     * The Id of the notification
     *
     * @access protected
     * @var    int
     *
     */
    protected $id;

    /**
     * The booking id of the notification
     *
     * @access protected
     * @var    int
     *
     */
    protected $booking_id;

    /**
     * Notification type
     *
     * @access protected
     * @var    string
     *
     */
    protected $type;

    /**
     * Notification group
     *
     * @access protected
     * @var    string
     *
     */
    protected $group;

    /**
     * The status of the notification
     *
     * @access protected
     * @var    string
     *
     */
    protected $status;


    /**
     * The date when the notification was created
     *
     * @access protected
     * @var    string
     *
     */
    protected $date_created;

    /**
     * Whetehr or not the notification is dismissable
     *
     * @access protected
     * @var    bool
     *
     */
    protected $dismissable = true;

    /**
     * The constructor
     *
     */
    public function __construct($object = false)
    {

        if ($object === false) {
            $object = new stdClass();
        }

        // Make them pretty
        $object->type = $object->notification_type;
        $object->group = $object->notification_group;
        $object->status = $object->notification_status;

        unset($object->notification_type);
        unset($object->notification_group);
        unset($object->notification_status);

        parent::__construct($object);
    }
    
}
