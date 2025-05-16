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

require_once( __DIR__ . '/../../config.php');
require_once( __DIR__ . '/classes/api.php' );
require_once( __DIR__ . '/classes/helper.php' );
require_once( __DIR__ . '/lib.php' );
require_once($CFG->libdir . '/adminlib.php' );

// Optional parameters
$deleteid = optional_param('deleteid', 0, PARAM_INT);
$course = optional_param('course', 0, PARAM_INT);
$user = optional_param('user', 0, PARAM_INT);
$confirm = optional_param('confirm', false, PARAM_BOOL);

// Link generation
$urlparameters = array('deleteid'=>$deleteid, 'user'=>$user, 'course'=>$course);
$baseurl = new moodle_url('/local/mb2coursenotes/delete.php', $urlparameters);
$returnurl = new moodle_url('/local/mb2coursenotes/manage.php');

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

// The page title
$context = context_system::instance();
$titlepage = get_string('deletenote', 'local_mb2coursenotes');
$PAGE->set_url($baseurl);
$PAGE->set_context($context);
$PAGE->navbar->add($titlepage);
$PAGE->set_heading($titlepage);
$PAGE->set_title($titlepage);
echo $OUTPUT->header();

$confirmed = ($confirm && data_submitted() && confirm_sesskey());

if ( ! $confirmed )
{

    $optionsyes = array('action'=>'delete', 'deleteid'=>$deleteid, 'sesskey'=>sesskey(), 'confirm'=>1, 'user'=>$user, 'course'=>$course);
    $optionsno = array('action'=>'cancel', 'deleteid'=>0, 'confirm'=>0, 'user'=>$user, 'course'=>$course);
    $formcontinue = new single_button(new moodle_url($returnurl, $optionsyes), get_string('yes'));
    $formcancel = new single_button(new moodle_url($returnurl, $optionsno), get_string('no'), 'get');
    $note = Mb2coursenotesApi::get_record($deleteid);

    echo $OUTPUT->confirm(get_string('confirmdeletenote', 'local_mb2coursenotes', $note->id), $formcontinue, $formcancel);
}

echo $OUTPUT->footer();
?>
