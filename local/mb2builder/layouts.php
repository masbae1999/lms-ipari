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
 * @package    local_mb2builder
 * @copyright  2018 - 2024 Mariusz Boloz (lmsstyle.com)
 * @license    PHP and HTML: http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later. Other parts: http://themeforest.net/licenses
 */

require_once( __DIR__ . '/../../config.php' );
require_once( __DIR__ . '/lib.php' );
require_once( __DIR__ . '/classes/layouts_api.php' );
require_once( __DIR__ . '/classes/pages_helper.php' );
require_once( $CFG->libdir . '/adminlib.php' );
require_once( $CFG->libdir . '/tablelib.php' );

// Optional parameters.
$deleteid = optional_param('deleteid', 0, PARAM_INT);
$moveup = optional_param('moveup', 0, PARAM_INT);
$movedown = optional_param('movedown', 0, PARAM_INT);
$returnurl = optional_param('returnurl', '/local/mb2builder/layouts.php', PARAM_LOCALURL);
$page = optional_param('page', 0, PARAM_INT);
$sort = optional_param('sort', 'timecreated', PARAM_ALPHANUMEXT);
$dir = optional_param('dir', 'DESC', PARAM_ALPHA);
$perpage = 20;

// Links.
$editpage = '/local/mb2builder/edit-layout.php';
$managelayouts = '/local/mb2builder/layouts.php';
$deletepage = '/local/mb2builder/delete-layout.php';
$baseurl = new moodle_url($managelayouts, ['sort' => $sort, 'dir' => $dir, 'page' => $page, 'perpage' => $perpage]);
$returnurl = new moodle_url($returnurl);

// Configure the context of the page.
$context = context_system::instance();
$PAGE->set_context( $context );
$PAGE->set_url('/local/mb2builder/layouts.php');
$PAGE->set_pagelayout('admin'); // This is require for page navigation.

require_login();
require_capability('local/mb2builder:managelayouts', context_system::instance());
$canmanage = has_capability( 'local/mb2builder:managelayouts', context_system::instance() );

// Get sorted pages.
$sortorderitems = Mb2builderLayoutsApi::get_list_records($page * $perpage, $perpage, false, '*', $sort, $dir);
$countitems = Mb2builderLayoutsApi::get_list_records(0, 0, true, '*', $sort, $dir);

// Delete the page.
if ($canmanage && $deleteid) {
    Mb2builderLayoutsApi::delete($deleteid);
    $message = get_string('pagedeleted', 'local_mb2builder');
}

// Move up.
if ($canmanage && $moveup) {
    Mb2builderLayoutsApi::move_up($moveup);
    $message = get_string('pageupdated', 'local_mb2builder', ['title' => Mb2builderLayoutsApi::get_record($moveup)->name]);
}

// Move down.
if ($canmanage && $movedown) {
    Mb2builderLayoutsApi::move_down($movedown);
    $message = get_string('pageupdated', 'local_mb2builder', ['title' => Mb2builderLayoutsApi::get_record($movedown)->name]);
}

if (isset( $message )) {
    redirect($returnurl, $message);
}

// Page title.
$namepage = get_string('pluginname', 'local_mb2builder');
$PAGE->set_heading($namepage);
$PAGE->set_title($namepage);
echo $OUTPUT->header();
echo $OUTPUT->heading( get_string('layouts', 'local_mb2builder' ) );

// Table declaration.
$table = new flexible_table('mb2builder-pages-table');

// Customize the table.
$table->define_columns(
    [
        'name',
        'timecreated',
        'timemodified',
        'actions',
    ]
);

$table->define_headers(
    [
        get_string('name', 'moodle'),
        get_string('timecreated', 'local_mb2builder'),
        get_string('timemodified', 'local_mb2builder'),
        get_string('actions', 'moodle'),
    ]
);

$table->define_baseurl($baseurl);
$table->set_attribute('class', 'generaltable' );
$table->column_class('timecreated', 'text-center align-middle');
$table->column_class('actions', 'text-right align-middle' );

$table->headers = Mb2builderLayoutsApi::get_table_header($table->columns, ['timecreated', 'timemodified']);
$table->setup();

foreach ($sortorderitems as $item) {

    // Filling of information columns.
    $namecallback = html_writer::div( html_writer::link(new moodle_url( $editpage, ['itemid' => $item->id ] ), '<strong>' .
    $item->name . '</strong>'));

    // Created and modified by.
    $createduser = Mb2builderLayoutsApi::get_user($item->createdby);
    $createduserdate = userdate($item->timecreated, get_string('strftimedatemonthabbr', 'local_mb2builder'));
    $createdbyitem = $createduser ? '<div>' . $createduserdate . '</div><div>' . $createduser->firstname . ' ' .
    $createduser->lastname .  '</div>' : '&minus;';

    // Modified by.
    $modifieduser = \core_user::get_user($item->modifiedby);
    $modifieduserdate = userdate($item->timemodified, get_string('strftimedatemonthabbr', 'local_mb2builder'));
    $modifiedbyitem = $modifieduser ? '<div>' . $modifieduserdate . '</div><div>' . $modifieduser->firstname . ' ' .
    $modifieduser->lastname .  '</div>' : '&minus;';

    // Link for editing.
    $editlink = new moodle_url($editpage, ['itemid' => $item->id]);
    $edititem = $OUTPUT->action_icon($editlink, new pix_icon('t/edit', get_string('edit', 'moodle')));

    // Link to remove.
    $deletelink = new moodle_url($deletepage, ['deleteid' => $item->id]);
    $deleteitem = $OUTPUT->action_icon($deletelink, new pix_icon('t/delete', get_string('delete', 'moodle')));

    // Check if user can manage items.
    $actions = $canmanage ? $edititem . $deleteitem : '';

    $table->add_data( [$namecallback, $createdbyitem, $modifiedbyitem, $actions] );

}

// Display the table.
$table->print_html();

echo $OUTPUT->paging_bar( $countitems, $page, $perpage, $baseurl );

echo $OUTPUT->footer();
