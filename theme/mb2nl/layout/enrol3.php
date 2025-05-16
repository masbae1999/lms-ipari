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

$reviews = theme_mb2nl_is_review_plugin();
$updatedate = theme_mb2nl_course_updatedate();
$ecinstructor = theme_mb2nl_theme_setting($PAGE, 'ecinstructor');
$coursecontext = context_course::instance($COURSE->id);
$courserating = '';
$imgcls = ' noimg';
$herostyle = '';

$slogan = theme_mb2nl_course_intro();
$headerstyle = theme_mb2nl_headerstyle();
$headercolorscheme = theme_mb2nl_mb2fields_filed('mb2scheme') ? theme_mb2nl_mb2fields_filed('mb2scheme') :
theme_mb2nl_theme_setting($PAGE, 'headercolorscheme');
$shemecls = $headerstyle === 'transparent_light' || ($headercolorscheme === 'light' && $headerstyle !== 'transparent') ?
' light' : ' dark';
$metadcls = theme_mb2nl_bsfcls(2, '', '', 'center');

if (theme_mb2nl_get_enroll_hero_url()) {
    $herostyle = ' data-bg="' . theme_mb2nl_get_enroll_hero_url() . '"';
    $imgcls = ' isimg lazy';
}

if ($reviews) {
    if (!class_exists('Mb2reviewsHelper')) {
        require($CFG->dirroot . '/local/mb2reviews/classes/helper.php');
    }

    $ratingobj = theme_mb2nl_review_obj($COURSE->id);
    $courserating = $ratingobj->rating;
}

$cpcatcolor = theme_mb2nl_theme_setting($PAGE, 'cecatcolor');
$catcolor = theme_mb2nl_cat_color_attr($cpcatcolor) ? ' style="' . theme_mb2nl_cat_color_attr($cpcatcolor) . '"' : '';

// Start HTML.
$html = '';

$html .= '<div class="course-header' . $shemecls . $imgcls . '"' . $herostyle . $catcolor . '>';
$html .= '<div class="inner">';
$html .= '<div class="row-topgap w-100"></div>';
$html .= '<div class="header-content">';
$html .= '<div class="container-fluid">';
$html .= '<div class="row">';
$html .= '<div class="col-lg-6">';
$html .= '<div class="course-info1">';
$html .= theme_mb2nl_categories_tree($COURSE->category);
$html .= '<h1 class="course-heading">' . theme_mb2nl_format_str($COURSE->fullname) . theme_mb2nl_course_edit_link() . '</h1>';

if ($slogan) {
    $html .= '<div class="course-slogan">' . $slogan . '</div>';
}

$html .= '<div class="course-meta1">';
$html .= theme_mb2nl_course_badges($COURSE);

if ($courserating) {
    $html .= '<a href="#course-ratings" aria-controls="course_nav_reviews_content" class="out-navitem' . $metadcls . '">';
    $html .= '<div class="course-rating">';
    $html .= '<span class="ratingnum">' . $courserating . '</span>';
    $html .= Mb2reviewsHelper::rating_stars($courserating, 'sm');
    $html .= '<span class="ratingcount">(' .get_string('ratingscount', 'local_mb2reviews', ['ratings' =>
    $ratingobj->rating_count]) . ')</span>';
    $html .= '</div>';
    $html .= '</a>';
}

if (theme_mb2nl_theme_setting($PAGE, 'coursestudentscount')) {
    $html .= '<span class="course-students' . $metadcls . '">' . get_string('teacherstudents', 'theme_mb2nl', ['students' =>
    theme_mb2nl_get_sudents_count()]) . '</span>';
}

if ($updatedate) {
    $html .= '<span class="course-updated' . $metadcls . '">';
    $html .= $updatedate;
    $html .= '</span>';
}

$html .= '</div>';

if ($ecinstructor) {
    $html .= '<div class="course-meta2 lhsmall">';
    $html .= '<a href="#course-instructors" aria-controls="course_nav_instructors_content" class="out-navitem' . $metadcls . '">';
    $html .= theme_mb2nl_course_teachers($COURSE->id, ['image' => 1]);
    $html .= '</a>';
    $html .= '</div>';
}

$html .= '<div class="course-mobile-info">';
$html .= theme_mb2nl_block_enrol(true);
$html .= '</div>';
$html .= '</div>';
$html .= '</div>';
$html .= '</div>';
$html .= '</div>';
$html .= $OUTPUT->theme_part('course_enrol_nav');
$html .= '</div>';
$html .= '</div>';
$html .= '</div>';
$html .= '<div class="course-details">';
$html .= '<div class="container-fluid">';
$html .= '<div id="theme-main-content" class="row">';
$html .= '<div class="col-lg-9 enrol-contentcol">';
$html .= $OUTPUT->theme_part('course_enrol_content');
$html .= '<div id="main-content">';
$html .= '<section id="region-main" class="content-col">';
$html .= '<div id="page-content">';
$html .= $OUTPUT->main_content();
$html .= '</div>';
$html .= '</section>';
$html .= '</div>';
$html .= '</div>';
$html .= '<div class="col-lg-3 enrol-sidebar">';
$html .= '<div class="sidebar-inner">';
$html .= '<div class="fake-block block-video">';
$html .= '<div class="video-banner lazy" data-bg="' . theme_mb2nl_course_image_url($COURSE->id, true) . '">';
$html .= theme_mb2nl_course_video_lightbox();
$html .= '</div>';
$html .= '</div>';
$html .= '<div class="fake-block block-enrol">';
$html .= theme_mb2nl_block_enrol();
$html .= '</div>';
$html .= '<div class="fake-block block-custom-fields">';
$html .= theme_mb2nl_course_fields($COURSE->id, false);
$html .= '</div>';

$html .= '<div class="fake-block block_activities">';
$html .= '<h4 class="block-heading h5">' . get_string('headingactivities', 'theme_mb2nl') . '</h4>';
$html .= theme_mb2nl_activities_list();
$html .= '</div>';
$html .= theme_mb2nl_course_tags_block($COURSE->id);

if (theme_mb2nl_theme_setting($PAGE, 'shareicons')) {
    $html .= '<div class="fake-block block-shares">';
    $html .= '<h4 class="block-heading h5">' .get_string('headingsocial', 'theme_mb2nl') . '</h4>';
    $html .= theme_mb2nl_course_share_list($COURSE->id, theme_mb2nl_format_str($COURSE->fullname));
    $html .= '</div>';
}

if (!theme_mb2nl_is_tgsdb()) {
    $html .= $OUTPUT->blocks('side-pre', theme_mb2nl_block_cls('side-pre'));
}

$html .= '</div>';
$html .= '</div>';
$html .= '</div>';
$html .= '</div>';
$html .= '</div>';

$html .= $OUTPUT->standard_after_main_region_html();
$html .= $OUTPUT->theme_part('region_bottom');
$html .= $OUTPUT->theme_part('region_bottom_abcd');
$html .= $OUTPUT->theme_part('footer', ['sidebar' => 0]);

echo $html;
