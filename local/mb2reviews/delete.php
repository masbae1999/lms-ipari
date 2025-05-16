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
 * @package    local_mb2reviews
 * @copyright  2021 Mariusz Boloz (mb2themes.com)
 * @license    PHP and HTML: http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later. Other parts: http://themeforest.net/licenses
 */

require_once( __DIR__ . '/../../config.php');
require_once( __DIR__ . '/classes/api.php' );
require_once( __DIR__ . '/classes/helper.php' );
require_once( __DIR__ . '/lib.php' );
require_once($CFG->libdir . '/adminlib.php' );

// Optional parameters
$deleteid = optional_param('deleteid', 0, PARAM_INT);
$confirm = optional_param('confirm', false, PARAM_BOOL);

// Link generation
$urlparameters = array('deleteid' => $deleteid);
$baseurl = new moodle_url( '/local/mb2reviews/delete.php', $urlparameters );
$managereviews = new moodle_url('/local/mb2reviews/index.php');

// Configure the context of the page
//admin_externalpage_setup( 'local_mb2reviews_managereviews', '', null, $baseurl );
require_capability( 'local/mb2reviews:deleteitems', context_system::instance() );

$PAGE->set_url( $baseurl );
$PAGE->set_context( context_system::instance());

// The page title
$titlepage = get_string('deletereview', 'local_mb2reviews');
$PAGE->navbar->add($titlepage);
$PAGE->set_heading($titlepage);
$PAGE->set_title($titlepage);
echo $OUTPUT->header();

$confirmed = ( $confirm && data_submitted() && confirm_sesskey() );

if ( ! $confirmed )
{
    $optionsyes = array('action'=>'delete', 'deleteid'=>$deleteid, 'sesskey'=>sesskey(), 'confirm'=>1);
    $formcontinue = new single_button(new moodle_url($managereviews, $optionsyes), get_string('yes'));
    $formcancel = new single_button(new moodle_url($managereviews), get_string('no'), 'get');
    $callback = Mb2reviewsApi::get_record($deleteid);

    echo $OUTPUT->confirm( get_string('confirmdeletereview', 'local_mb2reviews', array( 'title'=>$callback->id ) ), $formcontinue, $formcancel );
}

echo $OUTPUT->footer();
?>
