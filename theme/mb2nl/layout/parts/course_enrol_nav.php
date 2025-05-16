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
 * @package   theme_mb2nl
 * @copyright 2017 - 2024 Mariusz Boloz (lmsstyle.com)
 * @license   PHP and HTML: http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later. Other parts: http://themeforest.net/licenses
 *
 */

defined('MOODLE_INTERNAL') || die();

global $CFG, $PAGE, $COURSE;

$layout = theme_mb2nl_enrol_layout();
$reviews = theme_mb2nl_is_review_plugin();
$ecinstructor = theme_mb2nl_theme_setting($PAGE, 'ecinstructor');
$onepagenav = theme_mb2nl_is_eopn();
$mb2section = theme_mb2nl_mb2fields_filed('mb2section');
$tabalt = theme_mb2nl_mb2fields_filed('mb2tabalt') ? theme_mb2nl_mb2fields_filed('mb2tabalt') :
theme_mb2nl_theme_setting($PAGE, 'tabalt');
$tabcls = $tabalt ? ' tabalt' : '';
$activecls = $onepagenav ? '' : ' active';
$reviewlist = '';
$reviewscount = '';
$reviewsummary = '';
$courserating = '';
$navcol = '9 enrol-contentcol';

if ($layout == 1 || $layout == 2) {
    $navcol = 12;
}

if ($reviews) {
    if (!class_exists('Mb2reviewsHelper')) {
        require($CFG->dirroot . '/local/mb2reviews/classes/helper.php');
    }

    $ratingobj = theme_mb2nl_review_obj($COURSE->id);
    $ratingdetailsobj = theme_mb2nl_review_obj($COURSE->id, true);

    $reviewscount = $ratingdetailsobj->reviews_count;
    $reviewsummary = Mb2reviewsHelper::review_summary($ratingobj, $ratingdetailsobj);
    $courserating = $ratingobj->rating;
}

if ($onepagenav) {
    $PAGE->requires->js_call_amd('theme_mb2nl/enrol', 'onePageNav');
} else {
    $PAGE->requires->js_call_amd('theme_mb2nl/enrol', 'contentTabs');
}

$btncls = theme_mb2nl_bsfcls(2, '', 'center', 'center');

// Start HTML.
$html = '';

if ($onepagenav) {
    $html .= '<div class="enrol-course-nav-replace"></div>';
}

$html .= '<div class="enrol-course-nav position-relative' . $tabcls . '">';
$html .= '<div class="enrol-course-nav-inner">';
$html .= '<div class="container-fluid">';
$html .= '<div class="row">';
$html .= '<div class="col-lg-' . $navcol . '">';
$html .= '<ul class="enrol-course-nav-list' . theme_mb2nl_bsfcls(2, 'row', '', 'center') . '">';
$html .= '<li class="enrol-course-navitem' . $activecls . '"><button class="themereset' .
$btncls . '" aria-controls="course_nav_desc_content">' . get_string('overview', 'theme_mb2nl') . '</button></li>';

if ($mb2section) {
    $html .= '<li class="enrol-course-navitem"><button class="themereset' .
    $btncls . '" aria-controls="course_nav_mb2section_content">' . theme_mb2nl_mb2sectionfiledname() . '</button></li>';
}

if (theme_mb2nl_theme_setting($PAGE, 'elrollsections')) {
    $html .= '<li class="enrol-course-navitem"><button class="themereset' .
    $btncls . '" aria-controls="course_nav_sections_content">' . get_string('headingsections', 'theme_mb2nl') . '</button></li>';
}

if ($ecinstructor) {
    $html .= '<li class="enrol-course-navitem"><button class="themereset' .
    $btncls . '" aria-controls="course_nav_instructors_content">'.get_string('headinginstructors', 'theme_mb2nl').'</button></li>';
}

if ($reviewsummary || $reviewscount) {
    $html .= '<li class="enrol-course-navitem"><button class="themereset'.$btncls.'" aria-controls="course_nav_reviews_content">';
    $html .= get_string('reviews', 'theme_mb2nl');

    if ($courserating) {
        $html .= Mb2reviewsHelper::rating_stars($courserating, 'xs') . '<span class="course-rating">' . $courserating . '</span>';
    }

    $html .= '</button></li>';
}

$html .= '</ul>';
$html .= '</div>';
$html .= '</div>';
$html .= '</div>';
$html .= '</div>';
$html .= '</div>';

echo $html;
