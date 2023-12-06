<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;


/**
 * The main class for the coupon
 *
 */
class WPBS_Coupon extends WPBS_Base_Object {

	/**
	 * The Id of the coupon
	 *
	 * @access protected
	 * @var    int
	 *
	 */
	protected $id;

	/**
	 * The coupon name
	 *
	 * @access protected
	 * @var    string
	 *
	 */
	protected $name;

	/**
	 * The date when the coupon was created
	 *
	 * @access protected
	 * @var    string
	 *
	 */
	protected $date_created;

	/**
	 * The date when the coupon was last modified
	 *
	 * @access protected
	 * @var    string
	 *
	 */
	protected $date_modified;

	/**
	 * The options of the coupon
	 *
	 * @access protected
	 * @var    string
	 *
	 */
	protected $options;


}