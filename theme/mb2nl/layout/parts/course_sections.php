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

$mb2section = theme_mb2nl_mb2fields_filed('mb2section') && theme_mb2nl_theme_setting($PAGE, 'csection');
$skills = theme_mb2nl_mb2fields_filed('mb2skills');
$requirements = theme_mb2nl_mb2fields_filed('mb2requirements');
$reviews = theme_mb2nl_is_review_plugin();
$reviewlist = '';
$reviewscount = '';
$reviewsummary = '';
$startslink = '';
$canrate = '';
$ratealready = '';
$urlctab = optional_param('ctab', '', PARAM_ALPHANUMEXT);

if ($reviews) {
    if (!class_exists('Mb2reviewsHelper')) {
        require($CFG->dirroot . '/local/mb2reviews/classes/helper.php');
    }

    $ratingobj = theme_mb2nl_review_obj($COURSE->id);
    $ratingdetailsobj = theme_mb2nl_review_obj($COURSE->id, true);

    $canrate = Mb2reviewsHelper::can_rate($COURSE->id);
    $ratealready = Mb2reviewsHelper::rate_already($COURSE->id);
    $reviewscount = $ratingdetailsobj->reviews_count;
    $reviewsummary = Mb2reviewsHelper::review_summary($ratingobj, $ratingdetailsobj);
    $courserating = $ratingobj->rating;
    $startslink = Mb2reviewsHelper::rating_stars_link();
}

// Start HTML.
$html = '';

$html .= theme_mb2nl_tabcontent_topics();

if ($mb2section && $urlctab === 'csection') {
    $html .= '<div class="course-nav-section csection_csection">';
    $html .= '<div id="course-mb2section" class="details-section mb2section">';
    $html .= '<h2 class="section-heading h3">' . theme_mb2nl_mb2sectionfiledname() . '</h2>';
    $html .= '<div class="details-content">';
    $html .= '<div class="content-inner">' . theme_mb2nl_mb2fields_filed('mb2section') . '</div>';
    $html .= '</div>';
    $html .= '</div>';
    $html .= '</div>';
}

if (($reviewsummary || $reviewscount || $canrate || $ratealready) && $urlctab === 'reviews') {
    $html .= '<div class="course-nav-section csection_reviews">';
    $html .= '<div id="course-ratings" class="details-section reviews-starslink">';
    $html .= $startslink;
    $html .= '</div>';

    if ($reviewsummary) {
        $html .= '<div id="course-ratings" class="details-section reviews-summary">';
        $html .= '<h2 class="section-heading h3">' . get_string('courserating', 'local_mb2reviews') . '</h2>';
        $html .= $reviewsummary;
        $html .= '</div>';
    }

    if ($reviewscount) {
        $html .= '<div class="details-section reviews">';
        $html .= '<h2 class="section-heading h3">' . get_string('reviews', 'local_mb2reviews') . '</h2>';
        $html .= Mb2reviewsHelper::review_list($reviewscount);
        $html .= '</div>';
    }

    $html .= '</div>';
}

if ($urlctab === 'courseinfo') {
    $html .= '<div class="course-nav-section csection_courseinfo">';
    $html .= '<div id="course-mb2section" class="details-section mb2section">';
    $html .= '<div class="section-content">';
    $html .= '<div class="content-inner">';
    $html .= '<div class="course-summary course-section-part">';
    $html .= '<h2 class="section-heading h3">' . get_string('coursesummary') . '</h2>';
    $html .= theme_mb2nl_moreless(theme_mb2nl_get_mb2course_description());
    $html .= '</div>';

    if ($skills) {
        $html .= '<div class="skills course-skills course-section-part">';
        $html .= '<h2 class="section-heading h3">' . get_string('headingwhatlearn', 'theme_mb2nl') . '</h2>';
        $html .= '<div class="content-inner">' . theme_mb2nl_sr_list($skills) . '</div>';
        $html .= '</div>';
    }

    if ($requirements) {
        $html .= '<div class="course-requirements course-section-part">';
        $html .= '<h2 class="section-heading h3">' . get_string('headingrequirements', 'theme_mb2nl') . '</h2>';
        $html .= '<div class="content-inner">' . theme_mb2nl_sr_list($requirements, false, 999, 2) . '</div>';
        $html .= '</div>';
    }

    $html .= '<div class="course-section-part">';
    $html .= '<h2 class="section-heading h3">' . get_string('headinginstructors', 'theme_mb2nl') . '</h2>';
    $html .= theme_mb2nl_course_teachers_list();
    $html .= '</div>';
    $html .= theme_mb2nl_course_info_table();
    $html .= '</div>';
    $html .= '</div>';
    $html .= '</div>';
    $html .= '</div>';
}

echo $html;
