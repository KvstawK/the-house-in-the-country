<?php

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Output the notes from a given calendar id
 * 
 * @param int $calendar_id
 * 
 * @return string
 * 
 */
function wpbs_output_notes_html($calendar_id)
{
    $notes = wpbs_get_calendar_meta($calendar_id, 'notes', true);

    if (empty($notes)) {
        return false;
    }

    krsort($notes);

    foreach ($notes as $note_id => $note_data) {
        wpbs_output_note_html($note_data, $note_id);
    }

}

/**
 * Output the HTML for a note
 * 
 * @param array $data
 * @param int $id
 * 
 * @return string
 * 
 */
function wpbs_output_note_html($data, $id)
{

    $note = nl2br($data['note']);
    $note = preg_replace('!(((f|ht)tp(s)?://)[-a-zA-Zа-яА-Я()0-9@:%_+.~#?&;//=]+)!i', '<a href="$1" target="_blank">$1</a>', $note);

    echo '
    <div class="wpbs-calendar-note">
        <div class="wpbs-calendar-note-body">
            <p>' . $note . '</p>
        </div>
        <div class="wpbs-calendar-note-footer">
            <a class="wpbs-calendar-note-remove" href="#" data-note-id="' . $id . '">' . __('remove', 'wp-booking-system') . '</a>
            <p>' . __('Added on', 'wp-booking-system') . ': ' . date(get_option('date_format') . ' ' . get_option('time_format'), $data['date']) . '</p>
        </div>
    </div>
    ';
}

/**
 * AJAX Callback function for adding notes
 * 
 */
function wpbs_action_ajax_calendar_add_note()
{

    if (!isset($_POST['calendar_id'])) {
        return false;
    }

    $calendar_id = absint($_POST['calendar_id']);

    $note = sanitize_textarea_field($_POST['note']);

    if (empty($note)) {
        return false;
    }

    $notes = wpbs_get_calendar_meta($calendar_id, 'notes', true);

    if (empty($notes)) {
        $notes = array();
    }

    $date = current_time('timestamp');

    $notes[] = array(
        'date' => $date,
        'note' => $note,
    );

    wpbs_update_calendar_meta($calendar_id, 'notes', $notes);

    wpbs_output_note_html(end($notes), end(array_keys($notes)));

    wp_die();
}
add_action('wp_ajax_wpbs_calendar_add_note', 'wpbs_action_ajax_calendar_add_note');

/**
 * AJAX Callback function for removing notes
 * 
 */
function wpbs_action_ajax_calendar_remove_note()
{
    if (!isset($_POST['calendar_id'])) {
        return false;
    }

    $calendar_id = absint($_POST['calendar_id']);

    $note_id = absint($_POST['note_id']);

    $notes = wpbs_get_calendar_meta($calendar_id, 'notes', true);

    unset($notes[$note_id]);

    wpbs_update_calendar_meta($calendar_id, 'notes', $notes);

    wp_die();
}
add_action('wp_ajax_wpbs_calendar_remove_note', 'wpbs_action_ajax_calendar_remove_note');
