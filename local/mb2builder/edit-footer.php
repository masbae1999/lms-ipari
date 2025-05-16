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
require_once( __DIR__ . '/form-footer.php');
require_once( __DIR__ . '/classes/builder.php' );
require_once( __DIR__ . '/classes/footers_api.php' );
require_once( $CFG->libdir . '/adminlib.php' );

// Optional parameters
$itemid = optional_param('itemid', 0, PARAM_INT);
$name = optional_param('name', '', PARAM_TEXT);
$footer = optional_param('footer', 1, PARAM_INT);
$footerid = optional_param('footerid', '', PARAM_TEXT);
$returnurl = optional_param('returnurl', '/local/mb2builder/footers.php', PARAM_LOCALURL);

// Link generation
$urlparameters = array('itemid'=>$itemid, 'returnurl'=>$returnurl, 'name'=>$name, 'footerid'=>$footerid, 'footer'=>$footer);
$baseurl = new moodle_url('/local/mb2builder/edit-footer.php', $urlparameters);
$returnurl = new moodle_url($returnurl);

// Configure the context of the footer
admin_externalpage_setup( 'local_mb2builder_managefooters', '', null, $baseurl );
require_capability( 'local/mb2builder:managefooters', context_system::instance() );

// Get existing items
$items = Mb2builderFootersApi::get_list_records();

// Create an editing form
$mform = new service_edit_form( $PAGE->url );

// Getting the data
$formopts = array( 'itemid' => $itemid, 'name' => $name, 'footerid' => $footerid );
$recordata = Mb2builderFootersApi::set_record_data( $formopts );
$data = Mb2builderFootersApi::get_form_data( $mform, $recordata );

// Now user build a new footer
// We have to save it in database
// This is require for saving demo content of new footer (with itemid=0) in ajax
if (!$itemid && $footerid) {
    // Make sure that record doesn't exists
    if (!Mb2builderFootersApi::is_footerid($footerid,true) )
    {
        Mb2builderFootersApi::add_record($recordata);
    }
}

// Cancel processing
if ($mform->is_cancelled()) {
    $message = '';

    // If itemid doesn't exists and user click cancel buttons
    // So we have to delete footer record
    if (!$itemid && $footerid )
    {
        $recordtodelete = Mb2builderFootersApi::get_record($footerid, true);

        // Delete a footer record and don't recreate cache
        Mb2builderFootersApi::delete($recordtodelete->id,false);
    }
    // In this case user edit existing footer
    // We have to set democontent the same as content
    // This is require becaue in next footer editing builder will load democontent
    else if ($itemid)
    {
        $itemtoupdate = Mb2builderFootersApi::get_record($itemid);
        $itemtoupdate->democontent = $itemtoupdate->content;
        Mb2builderFootersApi::update_record_data($itemtoupdate);
    }
}

// Processing of received data
if (!empty($data)) {
    if ($itemid )
    {
        // Update the footer record and recreate cache
        Mb2builderFootersApi::update_record_data($data,true);
        $message = get_string('footerupdated', 'local_mb2builder', array( 'title' => $data->name ) );
    }
    else if (!$itemid && $footerid )
    {
        // Now we need to get record ID for update record in database
        // We don't have item ID in url because user now create new footer
        // Footer exists already in database but in UREL we don't have the footer id
        $recordforid = Mb2builderFootersApi::get_record($footerid,true);
        $data->id = $recordforid->id;

        // Update the footer record and recreate cache
        Mb2builderFootersApi::update_record_data($data,true);
        $message = get_string('footerupdated', 'local_mb2builder', array('title'=>$data->name));
    }
    else
    {
        // Update the footer record and recreate cache
        Mb2builderFootersApi::add_record($data,true);
        $message = get_string('footercreated', 'local_mb2builder');
    }
}

// Then redirect to to the footer
if (isset( $message )) {
    redirect( $returnurl, $message );
}

// The footer title
$titlefooter = get_string( 'editfooter', 'local_mb2builder' );
$PAGE->set_pagelayout( 'mb2builder_form' );
$PAGE->navbar->add($titlefooter);

$PAGE->set_title($titlefooter);
echo $OUTPUT->header();
echo $OUTPUT->heading($titlefooter);

// Displays the form
$mform->display();
echo mb2builderBuilder::get_demo_iframe(array('itemid'=>$itemid, 'footerid'=>$footerid, 'footer'=>$footer));
echo $OUTPUT->footer();
?>