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

global $PAGE, $COURSE;

$layout = theme_mb2nl_enrol_layout();
$reviews = theme_mb2nl_is_review_plugin();
$mb2promo = theme_mb2nl_mb2fields_filed('mb2promo');
$skills = theme_mb2nl_mb2fields_filed('mb2skills');
$ecinstructor = theme_mb2nl_theme_setting($PAGE, 'ecinstructor');
$requirements = theme_mb2nl_mb2fields_filed('mb2requirements');
$mb2section = theme_mb2nl_mb2fields_filed('mb2section');
$actres = theme_mb2nl_get_course_activities($COURSE, true);
$showvideo = $layout == 1 || $layout == 2;

$reviewlist = '';
$reviewscount = '';
$reviewsummary = '';
$courserating = '';

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

// Start HTML.
$html = '';

$html .= '<div id="course_nav_desc_content" class="enrol-course-navcontent active">';

if ($mb2promo) {
    $html .= '<div class="details-section promo">';
    $html .= '<div class="details-content">';
    $html .= '<div class="content-inner">' . $mb2promo . '</div>';
    $html .= '</div>';
    $html .= '</div>';
}

if ($showvideo) {
    $html .= theme_mb2nl_course_video();
}

$html .= '<div class="details-section aboutcourse">';
$html .= '<h2 class="details-heading h3">' . get_string('headingaboutcourse', 'theme_mb2nl') . '</h2>';
$html .= '<div class="details-content">';
$html .= theme_mb2nl_moreless(theme_mb2nl_get_mb2course_description());
$html .= '</div>';
$html .= '</div>';

if ($skills) {
    $html .= '<div class="details-section skills">';
    $html .= '<h2 class="details-heading h3">' . get_string('headingwhatlearn', 'theme_mb2nl') . '</h2>';
    $html .= '<div class="details-content">';
    $html .= '<div class="content-inner">' . theme_mb2nl_sr_list($skills) . '</div>';
    $html .= '</div>';
    $html .= '</div>';
}

if ($requirements) {
    $html .= '<div class="details-section requirements">';
    $html .= '<h2 class="details-heading h3">' . get_string('headingrequirements', 'theme_mb2nl') . '</h2>';
    $html .= '<div class="details-content">';
    $html .= '<div class="content-inner">' . theme_mb2nl_sr_list($requirements, false, 999, 2) . '</div>';
    $html .= '</div>';
    $html .= '</div>';
}

$html .= '</div>';

if ($mb2section) {
    $html .= '<div id="course_nav_mb2section_content" class="enrol-course-navcontent">';
    $html .= '<div id="course-mb2section" class="details-section mb2section">';
    $html .= '<h2 class="details-heading h3">' . theme_mb2nl_mb2sectionfiledname() . '</h2>';
    $html .= '<div class="details-content">';
    $html .= '<div class="content-inner">' . $mb2section . '</div>';
    $html .= '</div>';
    $html .= '</div>';
    $html .= '</div>';
}

$html .= '<div id="course_nav_sections_content" class="enrol-course-navcontent">';

if (theme_mb2nl_theme_setting($PAGE, 'elrollsections')) {
    $html .= '<div class="details-section sections">';
    $html .= '<h2 class="details-heading h3">' . get_string('headingsections', 'theme_mb2nl') . '</h2>';
    $html .= '<div class="details-content">';
    $html .= '<div class="content-inner">';
    $html .= '<div class="block_coursetoc">';
    $html .= '<div class="' . theme_mb2nl_bsfcls(1, '', 'between', 'center', 'sm') . theme_mb2nl_tcls('small', '', 0, '') . '">';
    $html .= '<div class="course-content-details mb-2' . theme_mb2nl_bsfcls(1, '', '', 'end') . '">';
    $html .= '<div class="details-item">';
    $html .= '<span class="item-label">' . get_string('sections') . ':</span>';
    $html .= '<span class="item-value">' . count(theme_mb2nl_get_course_sections()) . '</span>';
    $html .= '</div>';
    $html .= '<span class="sep">&#8226;</span>';
    $html .= '<div class="details-item">';
    $html .= '<span class="item-label">' . get_string('activities') . ':</span>';
    $html .= '<span class="item-value">' . $actres['activities'] . '</span>';
    $html .= '</div>';
    $html .= '<span class="sep">&#8226;</span>';
    $html .= '<div class="details-item">';
    $html .= '<span class="item-label">' . get_string('resources') . ':</span>';
    $html .= '<span class="item-value">' . $actres['resources'] . '</span>';
    $html .= '</div>';
    $html .= '</div>';
    $html .= '<div class="mb-2' . theme_mb2nl_tcls('', 'bold', 0, '') . '">';
    $html .= '<button type="button" class="themereset coursetoc-toggleall collapsed enrol-toggleall p-0" aria-label="' .
    get_string('expandall') . '" aria-expanded="false">';
    $html .= get_string('expandall');
    $html .= '</button>';
    $html .= '</div>';
    $html .= '</div>';
    $html .= theme_mb2nl_course_sections_accordion();
    $html .= '</div>';
    $html .= '</div>';
    $html .= '</div>';
    $html .= '</div>';
}

$html .= '</div>';

if ($ecinstructor) {
    $html .= '<div id="course_nav_instructors_content" class="enrol-course-navcontent">';
    $html .= '<div id="course-instructors" class="details-section instructors">';
    $html .= '<h2 class="details-heading h3">' . get_string('headinginstructors', 'theme_mb2nl') . '</h2>';
    $html .= '<div class="details-content">';
    $html .= '<div class="content-inner">' . theme_mb2nl_course_teachers_list() . '</div>';
    $html .= '</div>';
    $html .= '</div>';
    $html .= '</div>';
}

if ($reviewsummary || $reviewscount) {
    $html .= '<div id="course_nav_reviews_content" class="enrol-course-navcontent">';

    if ($reviewsummary) {
        $html .= '<div id="course-ratings" class="details-section reviews-summary">';
        $html .= '<h2 class="details-heading h3">' . get_string('courserating', 'local_mb2reviews') . '</h2>';
        $html .= $reviewsummary;
        $html .= '</div>';
    }

    if ($reviewscount) {
        $html .= '<div class="details-section reviews">';
        $html .= '<h2 class="details-heading h3">' . get_string('reviews', 'local_mb2reviews') . '</h2>';
        $html .= Mb2reviewsHelper::review_list($reviewscount);
        $html .= '</div>';
    }

    $html .= '</div>';
}

echo $html;
