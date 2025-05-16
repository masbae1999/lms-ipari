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

//defined('MOODLE_INTERNAL') || die();

require_once( __DIR__ . '/../../config.php' );
require_once( __DIR__ . '/lib.php' );
require_once( __DIR__ . '/classes/footers_api.php' );
require_once( __DIR__ . '/classes/pages_helper.php' );
require_once( $CFG->libdir . '/adminlib.php' );
require_once( $CFG->libdir . '/tablelib.php' );

// Optional parameters
$deleteid = optional_param('deleteid', 0, PARAM_INT);
$moveup = optional_param('moveup', 0, PARAM_INT);
$movedown = optional_param('movedown', 0, PARAM_INT);
$returnurl = optional_param('returnurl', '/local/mb2builder/footers.php', PARAM_LOCALURL);
$page = optional_param('page', 0, PARAM_INT);
$sort = optional_param('sort', 'timecreated', PARAM_ALPHANUMEXT);
$dir = optional_param('dir', 'DESC', PARAM_ALPHA);
$perpage = 20;

// Links
$editpage = '/local/mb2builder/edit-footer.php';
$managefooters = '/local/mb2builder/footers.php';
$deletepage = '/local/mb2builder/delete-footer.php';
$baseurl = new moodle_url($managefooters,array('sort' => $sort, 'dir' => $dir, 'page' => $page, 'perpage' => $perpage));
$returnurl = new moodle_url($returnurl);

// Configure the context of the page
$context = context_system::instance();
$PAGE->set_context( $context );
$PAGE->set_url( '/local/mb2builder/footers.php' );
$PAGE->set_pagelayout('admin'); // This is require for page navigation

require_capability( 'local/mb2builder:managefooters', context_system::instance() );
$canmanage = has_capability( 'local/mb2builder:managefooters', context_system::instance() );

// Get items
$sortorderitems = Mb2builderFootersApi::get_list_records($page * $perpage, $perpage, false, '*', $sort, $dir);
$countitems = Mb2builderFootersApi::get_list_records(0, 0, true, '*', $sort, $dir);

// Delete the page
if ($canmanage && $deleteid) {
    Mb2builderFootersApi::delete($deleteid);
    $message = get_string('footerdeleted', 'local_mb2builder');
}

// Move up
if ($canmanage && $moveup) {
    Mb2builderFootersApi::move_up($moveup);
    $message = get_string('footerupdated', 'local_mb2builder', array('title' => Mb2builderFootersApi::get_record($moveup)->name));
}

// Move down
if ($canmanage && $movedown) {
    Mb2builderFootersApi::move_down($movedown);
    $message = get_string('footerupdated', 'local_mb2builder', array('title' => Mb2builderFootersApi::get_record($movedown)->name));
}

if (isset( $message )) {
    redirect($returnurl, $message);
}

// Page title
$namepage = get_string('pluginname', 'local_mb2builder');
$PAGE->set_heading($namepage);
$PAGE->set_title($namepage);
echo $OUTPUT->header();
echo $OUTPUT->heading( get_string('footers', 'local_mb2builder' ) );
echo $OUTPUT->single_button(new moodle_url($editpage, array( 'footerid' => uniqid( 'footer_' ), 'name' => urlencode( 'Footer ' . time() ) ) ), get_string('addfooter', 'local_mb2builder'), 'get');

// Table declaration
$table = new flexible_table('mb2builder-footers-table');

// Customize the table
$table->define_columns(
    array(
        'name',
        'timecreated',
        'timemodified',
        'actions',
    )
);

$table->define_headers(
    array(
        get_string('name','moodle'),
        get_string('timecreated','local_mb2builder'),
        get_string('timemodified','local_mb2builder'),
        get_string('actions','moodle'),
    )
);

$table->define_baseurl($baseurl);
$table->set_attribute('class','generaltable');
$table->column_class('timecreated', 'text-center align-middle');
$table->column_class('timemodified','text-center align-middle');
$table->column_class('actions', 'text-right align-middle');

$table->headers = Mb2builderFootersApi::get_table_header($table->columns, array('timecreated','timemodified'));
$table->setup();

foreach ($sortorderitems as $item) {

    // Filling of information columns
    $namecallback = html_writer::div( html_writer::link(new moodle_url( $editpage, array('itemid' => $item->id ) ), '<strong>' . $item->name . '</strong>'));

    // Created and modified by
    $createduser = Mb2builderFootersApi::get_user($item->createdby);
    $createduserdate = userdate($item->timecreated,get_string('strftimedatemonthabbr','local_mb2builder'));
    $modifieduserdate = userdate($item->timemodified,get_string('strftimedatemonthabbr','local_mb2builder'));
    $modifieduser = Mb2builderFootersApi::get_user($item->modifiedby);
    $createdbyitem = $createduser ? '<div>' . $createduserdate . '</div><div class="mb2slides-admin-username">' . $createduser->firstname . ' ' . $createduser->lastname .  '</div>' : '&minus;';
    $modifiedbyitem = $modifieduser ? '<div>' . $modifieduserdate . '</div><div class="mb2slides-admin-username">' . $modifieduser->firstname . ' ' . $modifieduser->lastname .  '</div>' : '&minus;';

    // Link for editing
    $editlink = new moodle_url($editpage, array('itemid' => $item->id));
    $edititem = $OUTPUT->action_icon($editlink, new pix_icon('t/edit', get_string('edit', 'moodle')));

    // Link to remove
    $deletelink = new moodle_url($deletepage, array('deleteid' => $item->id));
    $deleteitem = $OUTPUT->action_icon($deletelink, new pix_icon('t/delete', get_string('delete', 'moodle')));

    // Check if user can manage items
    $actions = $canmanage ? $edititem . $deleteitem : '';

    $table->add_data(array($namecallback, $createdbyitem, $modifiedbyitem, $actions));

}

// Display the table
$table->print_html();

echo $OUTPUT->paging_bar( $countitems, $page, $perpage, $baseurl );

echo $OUTPUT->footer();
?>
