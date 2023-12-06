<?php

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * The main class for the Calendar
 *
 */
class WPBS_Calendar extends WPBS_Base_Object
{

    /**
     * The Id of the legend item
     *
     * @access protected
     * @var    int
     *
     */
    protected $id;

    /**
     * The legend item name
     *
     * @access protected
     * @var    string
     *
     */
    protected $name;

    /**
     * The date when the calendar was created
     *
     * @access protected
     * @var    string
     *
     */
    protected $date_created;

    /**
     * The date when the calendar was last modified
     *
     * @access protected
     * @var    string
     *
     */
    protected $date_modified;

    /**
     * The status of the calendar
     *
     * @access protected
     * @var    string
     *
     */
    protected $status;

    /**
     * The random ical hash
     *
     * @access protected
     * @var    string
     *
     */
    protected $ical_hash;

    /**
     * Returns the name property for the current object, or the translation for it
     * if the the language code is provided and the translation for that language exists
     *
     * @param string $language_code
     *
     * @return string
     *
     */
    public function get_name($language_code = '')
    {

        if (empty($language_code)) {
            return $this->name;
        }

        if (!wpbs_translations_active($language_code)) {
            return $this->name;
        }

        $translation = wpbs_get_calendar_meta($this->id, 'calendar_name_translation_' . $language_code, true);

        return (!empty($translation) ? $translation : $this->name);

    }

}
