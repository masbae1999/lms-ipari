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
 * @package    local_mb2coursenotes
 * @copyright  2019 - 2024 Mariusz Boloz (lmsstyle.com)
 * @license    PHP and HTML: http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later. Other parts: http://themeforest.net/licenses
 */

require_once( __DIR__ . '/../../config.php' );
require_once( __DIR__ . '/classes/api.php' );
require_once( __DIR__ . '/classes/helper.php' );
require_once( __DIR__ . '/lib.php' );
require_once( __DIR__ . '/form_filter.php' );
require_once( $CFG->libdir . '/adminlib.php' );
require_once( $CFG->libdir . '/tablelib.php' );

// Optional parameters.
$deleteid = optional_param('deleteid', 0, PARAM_INT);
$sort = optional_param('sort', 'timecreated', PARAM_ALPHANUMEXT);
$dir = optional_param('dir', 'DESC', PARAM_ALPHA);
$page = optional_param('page', 0, PARAM_INT );
$course = optional_param('course', 0, PARAM_INT );
$user = optional_param('user', 0, PARAM_INT );
$enroled = false;
$perpage = 20;

require_login();

// Links.
$editnote = '/local/mb2coursenotes/edit.php';
$managenotes = '/local/mb2coursenotes/manage.php';
$deletenote = '/local/mb2coursenotes/delete.php';
$baseurl = new moodle_url($managenotes, ['course' => $course, 'user' => $user, 'sort' => $sort, 'dir' => $dir, 'page' => $page,
'perpage' => $perpage]);

// Set page content.
$context = context_system::instance();
$PAGE->set_url( $baseurl );
$PAGE->set_context( $context );

// Get course context.
if ( $course ) {
    $coursecontext = context_course::instance($course);
    $enroled = is_enrolled($coursecontext, $USER->id);
}

// Check for user ID.
if ( ! $user || $user != $USER->id ) {
    require_capability('local/mb2coursenotes:manageitems', context_system::instance());
} else if ( $course && ! $enroled ) { // Check for course.
    require_capability('local/mb2coursenotes:manageitems', context_system::instance());
}

// Get note items.
$items = Mb2coursenotesApi::get_list_records($page * $perpage, $perpage, '*', $sort, $dir, $user, $course);
$countitems = Mb2coursenotesApi::get_list_records(0, 0, '*', $sort, $dir, $user, $course);

// Delete the note.
if ($deleteid && Mb2coursenotesApi::can_delete()) {
    Mb2coursenotesApi::delete($deleteid);
    $message = get_string('notedeleted', 'local_mb2coursenotes');
}

if ( isset( $message ) ) {
    redirect($baseurl, $message);
}

// Page title.
$titlepage = get_string('pluginname', 'local_mb2coursenotes');
$PAGE->set_heading($titlepage);
$PAGE->navbar->add($titlepage);
$PAGE->set_title($titlepage);
echo $OUTPUT->header();
echo $OUTPUT->heading(get_string('managenotes', 'local_mb2coursenotes'));

echo '<div class="mb2coursenotes-filter-notes">';
echo '<div class="filter-header d-flex justify-content-between align-items-center">';
echo '<button type="button" class="mb2-pb-btn sizesm rounded1 mb2coursenotes_toggle_filter"><span class="btn-icon">
<i class="fa fa-sliders"></i></span><span class="btn-text">' . get_string('filters') . '</span></button>';
echo Mb2coursenotesHelper::download_btn();
echo '</div>';
echo '<div class="filter-content">';

// Set filter form.
$mform = new mb2coursenotes_form_filter();
$data = new stdClass();
$data->user = $user;
$data->course = $course;
$mform->set_data($data);
$mform->display();
echo '</div>'; // ...filter-content
echo '</div>'; // ...mb2coursenotes-note-filte

$PAGE->requires->js_call_amd('local_mb2coursenotes/admin', 'toggleFilter');

// Table declaration.
$table = new flexible_table('mb2coursenotes-notes-table');

// Customize the table.
$table->define_columns(
    [
        'course',
        'user',
        'timecreated',
        'actions',
    ]
);

$table->define_headers(
    [
        get_string('course'),
        get_string('user'),
        get_string('lastmodified'),
        get_string('actions'),
    ]
);

$table->define_baseurl($baseurl);
$table->set_attribute('class', 'generaltable');
$table->column_class('actions', 'text-right align-middle');
$table->column_class('user', 'align-middle');
$table->column_class('timecreated', 'align-middle');

$table->headers = Mb2coursenotesHelper::get_table_header($table->columns, ['user', 'timecreated']);

$table->setup();

foreach ($items as $item) {

    // Check if course exists,
    // if not, delete note.
    $sql = 'SELECT id FROM {course} WHERE id=' . $item->course;
    if (!$DB->record_exists_sql($sql)) {
        Mb2coursenotesApi::delete($item->id, true);
        continue;
    }

    $editlink = new moodle_url($editnote, ['itemid' => $item->id, 'user' => $item->user, 'course' => $item->course, 'module' =>
    $item->module, 'returnurl' => $PAGE->url->out_as_local_url()]);

    // Created and modified by.
    $user = Mb2coursenotesHelper::get_user($item->user);
    $createduserdate = userdate($item->timecreated, get_string('strftimedatemonthabbr', 'local_mb2coursenotes'));
    $username = $user ? '<div class="mb2coursenotes-admin-username">' . $user->firstname . ' ' . $user->lastname .  '</div>' :
    '&minus;';

    // Defining note status.
    $hideshowicon = 't/show';
    $hideshowstring = get_string('show');

    $copyicon = 't/copy';
    $copystring = get_string('duplicate', 'moodle');

    // Link for editing.
    $edititem = $OUTPUT->action_icon($editlink, new pix_icon('t/edit', get_string('edit')));

    // Link to remove.
    $deletelink = new moodle_url($deletenote, ['deleteid' => $item->id, 'user' => $item->user, 'course' => $item->course]);
    $deleteitem = $OUTPUT->action_icon($deletelink, new pix_icon('t/delete', get_string('delete')));

    // Course name.
    $course = get_course($item->course);
    $modulename = $item->module ? Mb2coursenotesHelper::get_module($course, $item->module)->name : get_string('all');
    $iscourse = '<div class="mb2coursenotes-admin-note-title">';
    $iscourse .= '<div class="mb2coursenotes-admin-course" title="' . $course->fullname . '">' . $course->fullname . '</div>';
    $iscourse .= '<div class="mb2coursenotes-admin-module" title="' . $modulename . '"><b>' . $modulename . '</b></div>';
    $iscourse .= '</div>';

    // Check if user can manage items.
    $actions = $edititem . $deleteitem;

    $table->add_data([$iscourse, $username, $createduserdate, $actions]);

}

$table->pageable(true);
$table->currpage = $page;
$table->pagesize = 5;

// Display the table.
$table->print_html();

echo $OUTPUT->paging_bar(count($countitems), $page, $perpage, $baseurl);

echo $OUTPUT->footer();
