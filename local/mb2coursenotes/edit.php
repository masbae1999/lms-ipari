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
require_once( __DIR__ . '/form_note.php');
require_once( __DIR__ . '/classes/api.php' );
require_once( __DIR__ . '/classes/helper.php' );
require_once( __DIR__ . '/lib.php' );
require_once( $CFG->libdir . '/adminlib.php' );

// Optional parameters
$itemid = optional_param('itemid', 0, PARAM_INT);
$course = optional_param('course', 0, PARAM_INT);
$user = optional_param('user', 0, PARAM_INT);
$returnurl = optional_param( 'returnurl', '/local/mb2coursenotes/manage.php', PARAM_LOCALURL );
$enroled = false;

require_login();

// Get course context
if ( $course )
{
    $coursecontext = context_course::instance($course);
    $enroled = is_enrolled($coursecontext, $USER->id);
}

// Check for user ID
if ( ! $user || $user != $USER->id )
{
    require_capability('local/mb2coursenotes:manageitems', context_system::instance());
}
// Check for course
else if ( $course && ! $enroled )
{
    require_capability('local/mb2coursenotes:manageitems', context_system::instance());
}

// Link generation
$urlparameters = array('itemid'=>$itemid, 'returnurl'=>$returnurl, 'course'=>$course);
$baseurl = new moodle_url( '/local/mb2coursenotes/edit.php', $urlparameters );
$returnurl = new moodle_url($returnurl);

$context = context_system::instance();
$PAGE->set_url( $baseurl );
$PAGE->set_context( $context );

// Create an editing form
$mform = new service_edit_form($PAGE->url);

// Cancel processing
if ($mform->is_cancelled())
{
    $message = '';
}

// Getting the data
$menurecord = new stdClass();
$data = Mb2coursenotesApi::get_form_data($mform, $itemid);

// Processing of received data
if ( ! empty( $data ) )
{    
    if ( $itemid ) {
        Mb2coursenotesApi::update_record_data($data, true);
        $message = get_string('noteupdated','local_mb2coursenotes');
    }
    else {
        Mb2coursenotesApi::add_record($data);
        $message = get_string('notecreated', 'local_mb2coursenotes' );
    }
}

if (isset($message))
{
    redirect($returnurl, $message);
}

// The page title
$titlepage = get_string('editnote', 'local_mb2coursenotes');
$PAGE->navbar->add($titlepage);
$PAGE->set_heading($titlepage);
$PAGE->set_title($titlepage);
echo $OUTPUT->header();


echo $OUTPUT->heading($titlepage);

if ( Mb2coursenotesHelper::can_manage() )
{
    // Displays the form
    $mform->display();
}
else 
{
    echo get_string( 'noeditpermission', 'local_mb2coursenotes' );
}

echo $OUTPUT->footer();
?>