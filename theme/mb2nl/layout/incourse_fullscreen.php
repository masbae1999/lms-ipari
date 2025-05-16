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

$footthemecontent = theme_mb2nl_theme_setting($PAGE, 'foottext');
$footcontent = theme_mb2nl_format_txt($footthemecontent, FORMAT_HTML);
$progressopts = [
    'circle' => true,
    'fcolor' => 'transparent',
    's' => 42,
    'bs' => 2,
    'bcolor' => theme_mb2nl_theme_setting($PAGE, 'fsmoddh') ? 'rgba(255,255,255,.15)' : 'rgba(37,161,142,.16)',
    'text' => true,
];

$cname = theme_mb2nl_format_str($COURSE->fullname);
$courseurl = new moodle_url('/course/view.php', ['id' => $COURSE->id]);

$html = '';

$html .= '<div class="fsmod-course">';
$html .= '<div id="fsmod-header">';
$html .= '<div class="fsmod-header-inner flexcols position-relative">';
$html .= '<div class="fsmod-ctitle h5 mb-0">';
$html .= '<a href="' . $courseurl . '" aria-label="' . $cname . '">' . $cname . '</a>';
$html .= '</div>';
$html .= theme_mb2nl_course_progressbar($progressopts);
$html .= '<div class="fsmod-header-links' . theme_mb2nl_bsfcls(1, 'row', '', 'center') . '">';
$html .= theme_mb2nl_panel_link('content', true, false);
$html .= theme_mb2nl_note_link2form(true, true);
$html .= '</div>';
$html .= theme_mb2nl_full_screen_module_backlink();
$html .= '</div>';
$html .= '</div>';
$html .= '<div id="theme-main-content" class="fsmod-wrap">';
$html .= theme_mb2nl_tgsdb();
$html .= '<div class="fsmod-course-content">';
$html .= '<div id="main-content" class="' . theme_mb2nl_bsfcls(1, 'column') . '">';
$html .= '<section id="region-main" class="w-100 content-col">';

if (theme_mb2nl_theme_setting($PAGE, 'fsmodnav')) {
    $html .= '<div class="breadcrumb fsmod-breadcrumb">' . $OUTPUT->navbar() . '</div>';
}

$html .= '<div id="page-content" class="h-100' . theme_mb2nl_bsfcls(1, 'column') . '">';
$html .= '<div class="content-a bg-white">';
$html .= $OUTPUT->page_heading_button();
$html .= $OUTPUT->course_content_header();
$html .= theme_mb2nl_activityheader(true);

if (theme_mb2nl_isblock('content-top')) {
    $html .= $OUTPUT->blocks('content-top', theme_mb2nl_block_cls('content-top', 'none'));
}

$html .= $OUTPUT->main_content();

if (theme_mb2nl_isblock('content-bottom')) {
    $html .= $OUTPUT->blocks('content-bottom', theme_mb2nl_block_cls('content-bottom', 'none'));
}

$html .= '</div>';
$html .= '<div class="content-b pt-1">';
$html .= theme_mb2nl_theme_setting($PAGE, 'coursenav') ? theme_mb2nl_customnav() : $OUTPUT->activity_navigation();
$html .= $OUTPUT->course_content_footer();
$html .= '</div>';
$html .= '</div>';
$html .= '</section>';

$html .= '<footer class="fsmod-footer main-footer order-2' . theme_mb2nl_tcls('small') . '">';
$html .= '<p class="mb-1">' . $footcontent . '</p>';
$html .= theme_mb2nl_language_list('footer');
$html .= '</footer>';

$html .= $OUTPUT->theme_part('region_adminblock');
$html .= '</div>';
$html .= '</div>';
$html .= '</div>';
$html .= '</div>';
$html .= $OUTPUT->standard_after_main_region_html();
$html .= $OUTPUT->theme_part('footer', ['sidebar' => false]);

echo $html;
