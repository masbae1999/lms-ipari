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

global $OUTPUT, $PAGE, $COURSE;

$secnav = theme_mb2nl_theme_setting($PAGE, 'secnav');
$coursemenu = $OUTPUT->context_header_settings_menu();
$modmenu = $OUTPUT->region_main_settings_menu();

if ($secnav) {
    $coursemenu = '';
    $modmenu = '';
}

$courseurl = new moodle_url('/course/view.php', ['id' => $COURSE->id]);
$headerstyle = theme_mb2nl_headerstyle();
$bgimg = theme_header_bgimage();
$cls = $bgimg ? 'isbg' : 'nobg';
$headingcls = theme_mb2nl_is_course() ? ' iscurse' : ' nocourse';
$headingurl = '';
$headerl = theme_mb2nl_theme_setting($PAGE, 'headerl');

if (theme_mb2nl_is_course()) {
    $headingurl = $courseurl;
} else if (theme_mb2nl_is_blogsingle()) {
    $headingurl = new moodle_url('/blog/index.php');
}

$cpcatcolor = theme_mb2nl_theme_setting($PAGE, 'cpcatcolor');
$catcolor = theme_mb2nl_cat_color_attr($cpcatcolor) ? ' style="' . theme_mb2nl_cat_color_attr($cpcatcolor) . '"' : '';

$html = '';

$html .= '<div id="page-header" class="' . $cls . '"' . $catcolor . '>';

if ($bgimg) {
    $html .= '<div class="page-header-img lazy" data-bg="' . $bgimg . '"></div>';
}

$html .= '<div class="inner">';
$html .= '<div class="container-fluid">';
$html .= '<div class="row">';
$html .= '<div class="col-md-12">';
$html .= '<div class="page-heading flexcols">';
$html .= '<div class="page-header-left">';
$html .= '<h1 class="heding h2' . $headingcls . '">';

if ($headingurl) {
    $html .= '<a href="' . $headingurl . '" tabindex="-1">' . theme_mb2nl_page_title() . '</a>';
} else {
    $html .= theme_mb2nl_page_title();
}

$html .= '</h1>';
$html .= '</div>';
$html .= '<div class="page-header-right">';

if (!theme_mb2nl_theme_setting($PAGE, 'coursepanel') && ($coursemenu || $modmenu || theme_mb2nl_turnediting_button())) {
    $html .= $coursemenu . $modmenu;
    $html .= theme_mb2nl_turnediting_button();
} else {
    $html .= theme_mb2nl_panel_link();
    $html .= theme_mb2nl_turnediting_button();
}

$html .= '</div>';
$html .= '</div>';
$html .= '</div>';
$html .= '</div>';
$html .= '</div>';
$html .= '</div>';
$html .= ($PAGE->pagetype !== 'site-index' && $headerl === 'modern') ? $OUTPUT->theme_part('page_breadcrumb') : '';
$html .= '</div>';

$html .= ($PAGE->pagetype !== 'site-index' && $headerl === 'classic') ? $OUTPUT->theme_part('page_breadcrumb') : '';
$html .= $secnav ? $OUTPUT->theme_part('page_secnav') : '';

echo $html;
