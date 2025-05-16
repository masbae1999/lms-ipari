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
require_once( __DIR__ . '/form-page.php');
require_once( __DIR__ . '/classes/builder.php' );
require_once( __DIR__ . '/classes/pages_api.php' );
require_once( __DIR__ . '/classes/pages_helper.php' );
require_once( $CFG->libdir . '/adminlib.php' );

// Optional parameters
$itemid = optional_param('itemid', 0, PARAM_INT);
$pagename = optional_param('pagename', '', PARAM_TEXT);
$pageid = optional_param('pageid', '', PARAM_TEXT);
$courseid = optional_param('courseid', 0, PARAM_INT);
$mpage = optional_param('mpage', 0, PARAM_INT);
$returnurl = optional_param('returnurl', '/local/mb2builder/index.php', PARAM_LOCALURL);

// Link generation
$urlparameters = array('itemid'=>$itemid, 'returnurl'=>$returnurl, 'mpage'=>$mpage, 'pagename'=>$pagename, 'pageid'=>$pageid, 'courseid'=>$courseid);
$baseurl = new moodle_url('/local/mb2builder/edit-page.php', $urlparameters);
$returnurl = new moodle_url($returnurl);

// Configure the context of the page
admin_externalpage_setup( 'local_mb2builder_managepages', '', null, $baseurl );
require_capability( 'local/mb2builder:managepages', context_system::instance() );

// Get existing items
$items = Mb2builderPagesApi::get_list_records();

// Create an editing form
$mform = new service_edit_form($PAGE->url);

// Getting the data
$formopts = array('itemid'=>$itemid,'pagename'=>$pagename,'pageid'=>$pageid,'mpage'=>$mpage);
$recordata = Mb2builderPagesApi::set_record_data($formopts);
$data = Mb2builderPagesApi::get_form_data($mform, $recordata);

// Now user build new page
// We have to save it in database
// This is require for saving demo content of a new page (with itemid=0) in ajax
if (!$itemid && $pageid) {
    // Make sure that record doesn't exists
    if (!Mb2builderPagesApi::is_pageid($pageid,true) )
    {
        // Add a fake page
        Mb2builderPagesApi::add_record($recordata);
    }
}

// Cancel processing
if ($mform->is_cancelled()) {
    $message = '';

    // If itemid doesn't exists and user click cancel buttons Ee have to delete a page record
    if (!$itemid && $pageid )
    {
        $recordtodelete = Mb2builderPagesApi::get_record($pageid, true);

        // Delete the record and don't recreate cache
        Mb2builderPagesApi::delete($recordtodelete->id,false);
    }
    // In this case user edit existing page
    // We have to set democontent the same as content
    // This is require becaue in next page editing builder will load democontent
    else if ($itemid)
    {
        $itemtoupdate = Mb2builderPagesApi::get_record($itemid);
        $itemtoupdate->democontent = $itemtoupdate->content;
        Mb2builderPagesApi::update_record_data($itemtoupdate);
    }
}

// Processing of received data
if (!empty($data)) {
    if ($itemid)
    {
        // Update record data and recreate cache: pagedata
        Mb2builderPagesApi::update_record_data($data,true);
        $message = get_string('pageupdated', 'local_mb2builder', array('title'=>$data->title));
    }
    else if (!$itemid && $pageid)
    {
        // Now we need to get record ID for update record in database
        // We don't have item ID in url because user now create a new page
        // Page exists already in database but in URL we don't have the page id
        $recordforid = Mb2builderPagesApi::get_record($pageid,true);
        $data->id = $recordforid->id;

        // Update record and recreate a cache: pagedata and pages
        Mb2builderPagesApi::update_record_data($data,true,true);
        $message = get_string('pageupdated', 'local_mb2builder', array('title'=>$data->title) );
    }
    else
    {   
        // Update record and recreate cache: pagedata and pages
        Mb2builderPagesApi::add_record($data,true,true);
        $message = get_string('pagecreated', 'local_mb2builder');
    }
}

// Then redirect to to the page
if (isset($message)) {
    redirect($returnurl, $message);
}

// The page title
$titlepage = get_string( 'editpage', 'local_mb2builder' );
$PAGE->set_pagelayout( 'mb2builder_form' );
$PAGE->navbar->add($titlepage);

$PAGE->set_title($titlepage);
echo $OUTPUT->header();
echo $OUTPUT->heading($titlepage);

// Displays the form
$mform->display();
echo mb2builderBuilder::get_demo_iframe(array('itemid'=>$itemid,'pageid'=>$pageid));
echo mb2builderBuilder::page_settings_form();
echo $OUTPUT->footer();
?>
