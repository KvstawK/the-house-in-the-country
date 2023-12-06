<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;


/**
 * The main class for the Discount
 *
 */
class WPBS_Discount extends WPBS_Base_Object {

	/**
	 * The Id of the discount
	 *
	 * @access protected
	 * @var    int
	 *
	 */
	protected $id;

	/**
	 * The discount name
	 *
	 * @access protected
	 * @var    string
	 *
	 */
	protected $name;

	/**
	 * The date when the discount was created
	 *
	 * @access protected
	 * @var    string
	 *
	 */
	protected $date_created;

	/**
	 * The date when the discount was last modified
	 *
	 * @access protected
	 * @var    string
	 *
	 */
	protected $date_modified;

	/**
	 * The options of the discount
	 *
	 * @access protected
	 * @var    string
	 *
	 */
	protected $options;


}