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
require_once( __DIR__ . '/form-layout.php');
require_once( $CFG->libdir . '/adminlib.php' );

// Optional parameters
$itemid = optional_param('itemid', 0, PARAM_INT);
$returnurl = optional_param('returnurl', '/local/mb2builder/layouts.php', PARAM_LOCALURL);

// Link generation
$urlparameters = array( 'itemid' => $itemid, 'returnurl' => $returnurl );
$baseurl = new moodle_url( '/local/mb2builder/edit-layout.php', $urlparameters );
$returnurl = new moodle_url( $returnurl );

// Configure the context of the page
admin_externalpage_setup( 'local_mb2builder_managelayouts', '', null, $baseurl );
require_capability( 'local/mb2builder:managelayouts', context_system::instance() );

// Get existing items
$items = Mb2builderLayoutsApi::get_list_records();

// Create an editing form
$mform = new service_edit_form( $PAGE->url );

// Getting the data
$formopts = array('itemid'=>$itemid);
$recordata = Mb2builderLayoutsApi::set_record_data($formopts);
$data = Mb2builderLayoutsApi::get_form_data($mform, $recordata);

// Cancel processing
if ($mform->is_cancelled()) {
    $message = '';
}

// Processing of received data
if (!empty($data)) {
    if ($itemid)
    {
        Mb2builderLayoutsApi::update_record_data($data);
        $message = get_string('layoutupdated', 'local_mb2builder', array('title'=>$data->name));
    }
    else
    {
        Mb2builderLayoutsApi::add_record($data);
        $message = get_string('layoutcreated', 'local_mb2builder');
    }
}

// Then redirect to to the page
if (isset($message)) {
    redirect($returnurl, $message);
}

// The page title
$titlepage = get_string('editlayout', 'local_mb2builder');
$PAGE->navbar->add($titlepage);
$PAGE->set_heading($titlepage);
$PAGE->set_title($titlepage);
echo $OUTPUT->header();
echo $OUTPUT->heading($titlepage);

// Displays the form
$mform->display();

echo $OUTPUT->footer();
?>
