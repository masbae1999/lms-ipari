<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 *
 * @package   theme_mb2nl
 * @copyright 2017 - 2024 Mariusz Boloz (lmsstyle.com)
 * @license   PHP and HTML: http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later. Other parts: http://themeforest.net/licenses
 *
 */


/**
 *
 * Method to check if course notes are enable
 *
 */
function theme_mb2nl_note_on($link=false) {
    global $CFG, $DB;

    if (!file_exists($CFG->dirroot . '/local/mb2coursenotes/index.php')) {
        return false;
    }

    if (!isloggedin() || isguestuser()) {
        return false;
    }

    // Check cache.
    $cache = cache::make('theme_mb2nl', 'features');
    $mod = theme_mb2nl_is_module_context() ? 1 : 0;
    $cacheid = 'note_on_' . $mod;

    if ($cache->get($cacheid)) {
        // Cache is set only if function returns true.
        return true;
    }

    $dbman = $DB->get_manager();
    $table = new xmldb_table('local_mb2coursenotes_notes');

    if ($dbman->table_exists($table)) {
        $options = get_config('local_mb2coursenotes');

        if (isset($options->disablenotes) && !$options->disablenotes) {
            if ($link) {
                $cache->set($cacheid, 1);
                return true;
            }

            if ($mod) {
                $cache->set($cacheid, 1);
                return true;
            }
        }
    }

    return false;
}






/**
 *
 * Method to define note manage attribute
 *
 */
function theme_mb2nl_note_linkatts() {

    global $CFG, $PAGE, $USER, $COURSE;

    require_once($CFG->dirroot . '/local/mb2coursenotes/classes/api.php');
    require_once($CFG->dirroot . '/local/mb2coursenotes/classes/helper.php');

    $dataattr = '';

    $dataid = 0;
    $datauser = $USER->id;
    $datacourse = $COURSE->id;
    $datamod = is_object($PAGE->cm) && $PAGE->cm->id ? $PAGE->cm->id : 0;

    // Check if note exists.
    // If yes We have to edit existsing note.
    $noteexists = Mb2coursenotesHelper::note_exists($datamod);

    if ($noteexists) {
        $note = Mb2coursenotesApi::get_record($noteexists);

        $dataid = $note->id;
        $datauser = $note->user;
        $datacourse = $note->course;
        $datamod = $note->module;
    }

    $dataattr .= ' data-itemid="' . $dataid . '"';
    $dataattr .= ' data-user="' . $datauser . '"';
    $dataattr .= ' data-course="' . $datacourse . '"';
    $dataattr .= ' data-module="' . $datamod . '"';

    return $dataattr;

}



/**
 *
 * Method to define link to add or edit note
 *
 */
function theme_mb2nl_note_link2form($icon = false, $fmode = false) {
    global $PAGE;

    if (!theme_mb2nl_note_on()) {
        return;
    }

    if (!theme_mb2nl_is_module_context()) {
        return;
    }

    $output = '';
    $textcls = $icon ? ' sr-only' : '';
    $cls = $fmode ? ' fmode' : ' nmode';

    $output .= '<button type="button" class="themereset mb2coursenotes_openform' . $cls . '" aria-label="' .
    get_string('notes', 'local_mb2coursenotes') . '"' . theme_mb2nl_note_linkatts() . '>';
    $output .= '<span class="btn-icon" aria-hidden="true"><i class="ri-file-text-line"></i></span>';
    $output .= '</button>';

    $PAGE->requires->js_call_amd('local_mb2coursenotes/note', 'openForm');
    $PAGE->requires->js_call_amd('local_mb2coursenotes/note', 'saveNote');
    $PAGE->requires->js_call_amd('local_mb2coursenotes/note', 'deleteNote');

    return $output;

}




/**
 *
 * Method to define note modal form
 *
 */
function theme_mb2nl_note_moadalform() {
    global $CFG;

    $output = '';

    if (!theme_mb2nl_note_on()) {
        return;
    }

    require_once($CFG->dirroot . '/local/mb2coursenotes/classes/helper.php');

    $btnatts = theme_mb2nl_note_linkatts();

    $output .= '<div class="modal fade mb2coursenotes_modal" id="mb2coursenotes_modal_form" aria-modal="true" role="dialog">';
    $output .= '<div class="modal-dialog modal-md">';
    $output .= '<div class="modal-content">';
    $output .= '<div class="modal-header">';
    $output .= '<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
    </button>';
    $output .= '<h4 class="modal-title">' . get_string('notes', 'local_mb2coursenotes') . '</h4>';
    $output .= '</div>'; // ...modal-header
    $output .= '<div class="modal-body">';
    $output .= '<div class="note-content">';
    $output .= '<div class="note-form-field">';
    $output .= '<textarea id="mb2coursenotes_modal_form_content" class="mb2-editor-textarea"></textarea>';
    $output .= '</div>'; // ...note-form-field
    $output .= '<div class="note-footer mt-3' . theme_mb2nl_bsfcls(1, '', 'between', 'center') . '">';
    $output .= '<button type="button" class="mb2-pb-btn sizexs typesuccess mb2coursenotes_modal_btn_save"' . $btnatts . '>' .
    get_string('save') . '</button>';
    $output .= '<button type="button" class="mb2-pb-btn sizexs typedanger mb2coursenotes_modal_btn_delete"' .
    $btnatts . ' data-confirmtext="' . get_string('confirmdeletenotenoid', 'local_mb2coursenotes') . '">' .
    get_string('delete') . '</button>';
    $output .= '</div>'; // ...note-footer
    $output .= '</div>'; // ...note-content
    $output .= '</div>'; // ...modal-body
    $output .= '</div>'; // ...modal-content
    $output .= '</div>'; // ...modal-dialog
    $output .= '</div>'; // ...modal

    return $output;

}




/**
 *
 * Method to define course id for notes quik link
 *
 */
function theme_mb2nl_notes_courseid() {
    global $COURSE, $SITE, $USER;

    if (! theme_mb2nl_is_course()) {
        return 0;
    }

    $coursecontext = context_course::instance($COURSE->id);
    $enroled = is_enrolled($coursecontext, $USER->id);

    if (!has_capability('local/mb2coursenotes:manageitems', context_system::instance()) && !$enroled) {
        return 0;
    }

    return $COURSE->id;

}





/**
 *
 * Method to define course id for notes quik link
 *
 */
function theme_mb2nl_notes_userid() {
    global $USER;

    if (! has_capability('local/mb2coursenotes:manageitems', context_system::instance())) {
        return $USER->id;
    }

    return 0;

}
