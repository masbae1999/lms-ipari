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

require_once( __DIR__ . '/../../config.php' );
require_once( __DIR__ . '/review_form.php');
require_once( __DIR__ . '/classes/api.php' );
require_once( __DIR__ . '/classes/helper.php' );
require_once( __DIR__ . '/lib.php' );
require_once( $CFG->libdir . '/adminlib.php' );

$options = get_config('local_mb2reviews');

// Optional parameters
$itemid = optional_param('itemid', 0, PARAM_INT);
$course = optional_param('course', 0, PARAM_INT);
$rating = optional_param('rating', 0, PARAM_INT);

$coursecontext = context_course::instance( $course );
$defreturnurl = has_capability( 'local/mb2reviews:managecourseitems', $coursecontext ) ? '/local/mb2reviews/index.php?course=' . $course : '/my';
$returnurl = optional_param( 'returnurl', $defreturnurl, PARAM_LOCALURL );

// Link generation
$urlparameters = array('itemid'=>$itemid, 'course'=>$course, 'rating'=>$rating, 'returnurl'=>$returnurl);
$baseurl = new moodle_url('/local/mb2reviews/edit.php', $urlparameters);
$returnurl = new moodle_url($returnurl);

$context = context_system::instance();
$PAGE->set_url( $baseurl );
$PAGE->set_context( $context );

// Create an editing form
$mform = new mb2reviews_item_edit_form($PAGE->url);

// Cancel processing
if ($mform->is_cancelled())
{
    $message = '';
}

// Getting the data
$data = Mb2reviewsApi::get_form_data($mform, $itemid);

// Processing of received data
if ( ! empty( $data ) )
{
    // Make sure item without content is not featured
    if ( ! $data->content )
    {
        $data->featured = 0;
    }

    if ( $itemid )
    {
        if ( ! has_capability( 'local/mb2reviews:managecourseitems', $coursecontext ) )
        {
            $isenable = $options->autoapprove ? 1 : 0;
            $data->enable = $isenable;
        }

        Mb2reviewsApi::update_record_data($data);

        if ( ! has_capability( 'local/mb2reviews:managecourseitems', $coursecontext ) )
        {
            Mb2reviewsHelper::notify_users_message( $data, true );
        }

        $message = get_string('reviewupdated', 'local_mb2reviews', array('title' => Mb2reviewsApi::get_record($itemid)->id));
    }
    else
    {
        Mb2reviewsApi::add_record($data);
        Mb2reviewsHelper::notify_users_message( $data );
        $message = get_string('reviewcreated', 'local_mb2reviews');
    }
}

if ( isset( $message ) )
{
    redirect( $returnurl, $message );
}

// The page title
$titlepage = get_string('editreview', 'local_mb2reviews');
$PAGE->navbar->add($titlepage);
$PAGE->set_heading($titlepage);
$PAGE->set_title($titlepage);
echo $OUTPUT->header();
echo $OUTPUT->heading($titlepage);

// Disable review
if ( $options->disablereview || ! isloggedin() || isguestuser() )
{
    echo get_string( 'noeditpermission', 'local_mb2reviews' );
}
// Course parameter isn't set
else if ( !$course || $course==1 )
{
    echo get_string( 'nocourse', 'local_mb2reviews' );
}
// Course is set but itemid is not and user already rate the course
else if ( $course && ! $itemid && Mb2reviewsHelper::rate_already($course) )
{
    echo get_string( 'coursealreadyrated', 'local_mb2reviews' );
}
else
{


    if ( $itemid )
    {
        // Now we have to check if user edit thier own review
        // This is studnt's case
        if ( $options->caneditreview && Mb2reviewsHelper::own_review( $itemid ) )
        {
            $mform->display();
        }
        // Now check if user can manage coursereview
        // this is teachr's case
        else if ( has_capability( 'local/mb2reviews:managecourseitems', $coursecontext ) )
        {
            $mform->display();
        }
        else
        {
            echo get_string( 'noeditpermission', 'local_mb2reviews' );
        }
    }
    else if ( is_enrolled( $coursecontext, $USER->id ) )
    {
        $mform->display();
    }
}

echo $OUTPUT->footer();
?>
