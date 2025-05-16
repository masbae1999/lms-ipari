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

defined('MOODLE_INTERNAL') || die();

$string['pluginname'] = 'Mb2 Reviews';

// Caches 
$string['cachedef_courserating'] = 'Mb2 Reviews - course rating cache';
$string['cachedef_userrating'] = 'Mb2 Reviews - user session rating cache';
$string['cachedef_coursereviews'] = 'Mb2 Reviews - reviews list cache';

// Roles
$string['mb2reviews:view'] = 'View reviews';
$string['mb2reviews:manageitems'] = 'Manage reviews';
$string['mb2reviews:deleteitems'] = 'Delete reviews';
$string['mb2reviews:emailnotifysubmission'] = 'Get a notification message when an review is submitted';
$string['messageprovider:submission'] = 'Notification of review submissions';
$string['mb2reviews:managecourseitems'] = 'Manage course reviews';

// Global strings
$string['none'] = 'None';
$string['left'] = 'Left';
$string['right'] = 'Right';
$string['center'] = 'Center';
$string['top'] = 'Top';
$string['bottom'] = 'Bottom';
$string['content'] = 'Content';
$string['yes'] = 'Yes';
$string['no'] = 'No';
$string['all'] = 'All';
$string['show'] = 'Show';
$string['hide'] = 'Hide';

// Moodle menu
$string['managereviews'] = 'Manage reviews';
$string['editreview'] = 'Edit review';
$string['enablereview'] = 'Show review';
$string['disablereview'] = 'Hide review';
$string['deletereview'] = 'Delete review';
$string['addreview'] = "Add review";
$string['reviewupdated'] = 'Review <strong>{$a->title}</strong> updated.';
$string['reviewcreated'] = 'Review created.';
$string['reviewdeleted'] = 'Review deleted.';
$string['confirmdeletereview'] = 'Do you really want to delete review: <strong>{$a->title}</strong>?';
$string['access'] = 'Access';


// Reviews list table
$string['strftimedatemonthabbr'] = '%d %b %Y';
$string['strftimedatetimeshort'] = '%d/%m/%y, %H:%M';
$string['rating'] = 'Rating';
$string['timecreated'] = 'Created on';

// Rating form
$string['star1'] = '1 Star';
$string['star2'] = '2 Stars';
$string['star3'] = '3 Stars';
$string['star4'] = '4 Stars';
$string['star5'] = '5 Stars';
$string['star'] = '{$a} star';
$string['comment'] =" Comment";
$string['featured'] = 'Featured review';

// Validation of rating from
$string['nocourse'] = 'No course selected.';
$string['norating'] = 'Rating is required.';
$string['noeditpermission'] = 'You don\'t have permission to edit this review.';
$string['coursealreadyrated'] = 'The course has already been rated';

// Course page
$string['reviewwaitingforapprove'] = 'Your review waiting to be approved';
$string['managecourseitems'] = 'Manage course reviews';
$string['courserating'] = 'Course rating';
$string['addreview'] = 'Write a review';
$string['editreview'] = 'Edit your review';
$string['basedonreviewcount'] = '{$a->rating} average based on {$a->count} ratings (<a href="#mb2reviews-block-more" class="rating-details-toggle">more</a>).';
$string['hratingcount'] = 'Hidden reviews: {$a->h}';
$string['reviews'] = 'Reviews';
$string['ratingscount'] = '{$a->ratings} ratings';
$string['reviewscount'] = '{$a->reviews} reviews';
$string['morereviews'] = 'More reviews';
$string['processing'] = 'Processing...';

// Reviews page
$string['managecoursereviewstitle'] = 'Manage reviews {$a->course}';

// Options
$string['options'] = 'Options';
$string['disablereview'] = 'Disable reviews';
$string['caneditreview'] = 'Users can edit their reviews';
$string['autoapprove'] = 'Automatically approve reviews';
$string['rolestudent'] = 'Student role';
$string['roleteacher'] = 'Teacher role';
$string['perpage'] = 'Reviews per page';
$string['reviewusername'] = 'Display reviewer name';
$string['minrated'] = 'Minimum rated count';
$string['blockonmod'] = 'Rating block on activity/resource page';

// Notification
$string['emailnotifyupdatedsubject'] = 'A rating has been updated for {$a->course}';
$string['emailnotifysubject'] = 'A new rating has been received for {$a->course}';
$string['emailnotifybody'] = 'Rating: {$a->rating} Comment: {$a->comment} User: {$a->user}';
$string['emailnotifysmall'] = 'A new rating for {$a->course} ({$a->rating})';

// Deprecated
$string['feedback'] = 'Deprecated';
$string['reviewhelpful'] = 'Deprecated';
$string['feedbackthankyou'] = 'Deprecated';
$string['helpful'] = 'Deprecated';
