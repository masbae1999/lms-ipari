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
 * @package    local_mb2megamenu
 * @copyright  2019 - 2020 Mariusz Boloz (mb2themes.com)
 * @license    Commercial https://themeforest.net/licenses
 */

//defined('MOODLE_INTERNAL') || die();

require_once( __DIR__ . '/../../config.php' );
require_once( __DIR__ . '/classes/api.php' );
require_once( __DIR__ . '/classes/helper.php' );
require_once( __DIR__ . '/lib.php' );
require_once( $CFG->libdir . '/adminlib.php' );
require_once( $CFG->libdir . '/tablelib.php' );

// Optional parameters
$deleteid = optional_param( 'deleteid', 0, PARAM_INT );
$hideshowid = optional_param( 'hideshowid', 0, PARAM_INT );
$moveup = optional_param( 'moveup', 0, PARAM_INT );
$movedown = optional_param( 'movedown', 0, PARAM_INT );
$duplicate = optional_param( 'duplicate', 0, PARAM_INT );
$page = optional_param( 'page', 0, PARAM_INT );
$perpage = 20;
$returnurl = optional_param('returnurl', '/local/mb2megamenu/index.php', PARAM_LOCALURL);

// Links
$editmenu = '/local/mb2megamenu/edit.php';
$managemenus = '/local/mb2megamenu/index.php';
$deletemenu = '/local/mb2megamenu/delete.php';
$baseurl = new moodle_url($managemenus, array( 'sort' => 'location','perpage' => $perpage ) );
$returnurl = new moodle_url($returnurl);

// Configure the context of the page
admin_externalpage_setup('local_mb2megamenu_managemenus', '', null, $baseurl);
require_capability('local/mb2megamenu:manageitems', context_system::instance());
$can_manage = has_capability('local/mb2megamenu:manageitems', context_system::instance());

// Get sorted menus
$sortorder_items = Mb2megamenuApi::get_sortorder_items();

// Set items limit for pagination
$sortorder_items = array_slice( $sortorder_items, $page * $perpage, $perpage );

// Delete the menu
if ($can_manage && $deleteid)
{
    Mb2megamenuApi::delete($deleteid);
    $message = get_string('menudeleted', 'local_mb2megamenu');
}

// Switching the status of the menu
if ($can_manage && $hideshowid)
{
    Mb2megamenuApi::switch_status($hideshowid);
    $message = get_string( 'updated', 'core', Mb2megamenuApi::get_record($hideshowid)->name );
}

// Move up
if ($can_manage && $moveup)
{
    Mb2megamenuApi::move_up($moveup);
    $message = get_string( 'updated', 'core', Mb2megamenuApi::get_record($moveup)->name );
}

// Move down
if ($can_manage && $movedown)
{
    Mb2megamenuApi::move_down($movedown);
    $message = get_string( 'updated', 'core', Mb2megamenuApi::get_record($movedown)->name );
}

// Duplicate menu
if ($can_manage && $duplicate)
{
    Mb2megamenuApi::duplicate($duplicate);
    $message = get_string( 'copied', 'local_mb2megamenu', Mb2megamenuApi::get_record($duplicate)->name );
}

if ( isset( $message ) )
{
    redirect($returnurl, $message);
}

// Page title
$titlepage = get_string('pluginname', 'local_mb2megamenu');
$PAGE->set_heading($titlepage);
$PAGE->set_title($titlepage);
echo $OUTPUT->header();
echo $OUTPUT->heading(get_string('managemenus', 'local_mb2megamenu'));
echo $OUTPUT->single_button(new moodle_url($editmenu), get_string('addmenu', 'local_mb2megamenu'), 'get');

// Table declaration
$table = new flexible_table('mb2megamenu-menus-table');

// Customize the table
$table->define_columns(
    array(
        'name',
        'createdby',
        'modifiedby',
        'actions',
    )
);

$table->define_headers(
    array(
        get_string('name', 'moodle'),
        get_string('createdby', 'local_mb2megamenu'),
        get_string('modifiedby', 'local_mb2megamenu'),
        get_string('actions', 'moodle'),
    )
);

$table->define_baseurl($baseurl);
$table->set_attribute('class', 'generaltable');
$table->column_class('createdby', 'text-center align-middle');
$table->column_class('modifiedby', 'text-center align-middle');
$table->column_class('actions', 'text-right align-middle');
$table->column_class('name', 'align-middle');

$table->setup();

foreach ( $sortorder_items as $item )
{

    $callback = Mb2megamenuApi::get_record($item);

    $actiebadge = Mb2megamenuHelper::active_menu() == $callback->id ? '<span class="badge badge-success">' . get_string('activemenu', 'local_mb2megamenu') . '</span>'  : '';

	// Filling of information columns
    $titlecallback = html_writer::div(html_writer::link(new moodle_url($editmenu, array('itemid' => $callback->id)), '<strong>' . $callback->name . '</strong> ' . $actiebadge), 'mb2megamenu-admin-menu-title');

    // Created and modified by
    $createduser = Mb2megamenuHelper::get_user($callback->createdby);
    $createduserdate = userdate($callback->timecreated, get_string('strftimedatemonthabbr', 'local_mb2megamenu'));
    $modifieduserdate = userdate($callback->timemodified, get_string('strftimedatemonthabbr', 'local_mb2megamenu'));
    $modifieduser = Mb2megamenuHelper::get_user($callback->modifiedby);
    $createdbyitem = $createduser ? '<div class="mb2megamenu-admin-username">' .
    $createduser->firstname . ' ' . $createduser->lastname .  '</div><div>' . $createduserdate . '</div>' : '&minus;';
    $modifiedbyitem = $modifieduser ? '<div class="mb2megamenu-admin-username">' .
    $modifieduser->firstname . ' ' . $modifieduser->lastname .  '</div><div>' . $modifieduserdate . '</div>' : '&minus;';

	// Defining menu status
    $hideshowicon = 't/show';
    $hideshowstring = get_string('show');

    $copyicon = 't/copy';
    $copystring = get_string('duplicate', 'moodle');

    $moveupicon = 't/up';
    $movedownicon = 't/down';
    $moveupstring = get_string('moveup', 'moodle');
    $strmovedown = get_string('movedown', 'moodle');
    $previtem = Mb2megamenuApi::get_record_near($callback->id, 'prev');
    $nextitem = Mb2megamenuApi::get_record_near($callback->id, 'next');

    if ((bool) $callback->enable)
    {
        $hideshowicon = 't/hide';
        $hideshowstring = get_string('hide');
    }

    // Link to enable / disable the menu
    $hideshowlink = new moodle_url($managemenus, array('hideshowid' => $callback->id));
    $hideshowitem = $OUTPUT->action_icon($hideshowlink, new pix_icon($hideshowicon, $hideshowstring));

    // Link to move up
    $moveuplink = new moodle_url($managemenus, array('moveup' => $callback->id));
    $moveupitem = $previtem ? $OUTPUT->action_icon($moveuplink, new pix_icon($moveupicon, $moveupstring)) : '';

    // Link to move down
    $movedownlink = new moodle_url($managemenus, array('movedown' => $callback->id));
    $movedownitem = $nextitem ? $OUTPUT->action_icon($movedownlink, new pix_icon($movedownicon, $strmovedown)) : '';

    // Link to duplicate
    $duplicatelink = new moodle_url($managemenus, array('duplicate' => $callback->id));
    $duplicateitem = $OUTPUT->action_icon($duplicatelink, new pix_icon('t/copy', get_string('duplicate')) );

    // Link for editing
    $editlink = new moodle_url($editmenu, array('itemid' => $callback->id));
    $edititem = $OUTPUT->action_icon($editlink, new pix_icon('t/edit', get_string('edit')));

    // Link to remove
    $deletelink = new moodle_url($deletemenu, array('deleteid' => $callback->id));
    $deleteitem = $OUTPUT->action_icon($deletelink, new pix_icon('t/delete', get_string('delete')));

    // Check if user can manage items
    $actions = $can_manage ? $hideshowitem . $moveupitem . $movedownitem . $duplicateitem . $edititem . $deleteitem : '';

	$table->add_data(array($titlecallback, $createdbyitem, $modifiedbyitem, $actions));

}
$table->pageable(true);
$table->currpage = $page;
$table->pagesize = 5;
// Display the table
$table->print_html();

echo Mb2megamenuApi::menu_importer();

//$baseurl = new moodle_url('/course/format/classroom/manage_location.php', array('sort' => 'location', 'dir' => 'ASC','perpage' => $perpage));
echo $OUTPUT->paging_bar( count( Mb2megamenuApi::get_sortorder_items(0,999) ), $page, $perpage, $baseurl );

echo $OUTPUT->footer();
?>
