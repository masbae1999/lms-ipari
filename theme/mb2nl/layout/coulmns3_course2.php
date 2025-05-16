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

$sidebarposfield = theme_mb2nl_mb2fields_filed('mb2sidebarpos');
$sidebarpos = (!is_null($sidebarposfield) && $sidebarposfield !== '') ? $sidebarposfield : theme_mb2nl_sidebarpos();
$sidepre = true;
$tgsdb = theme_mb2nl_is_tgsdb();
$sidepost = !$tgsdb && theme_mb2nl_isblock('side-post');

$sidebar = ($sidepre || $sidepost);
$contentcol = ' col-lg-12';
$sideprecol = ' col-lg-3';
$sidepostcol = ' col-lg-3';

if ($sidepre && $sidepost) {
    $contentcol = ' col-lg-6';
    $boxcls = 'gutter-thin theme-col-2';

    if ($sidebarpos === 'classic') {
        $contentcol .= ' order-2';
        $sideprecol .= ' order-1';
        $sidepostcol .= ' order-3';
    } else if ($sidebarpos === 'left') {
        $contentcol .= ' order-3';
        $sideprecol .= ' order-1';
        $sidepostcol .= ' order-2';
    }
} else if ($sidepre || $sidepost) {
    $contentcol = ' col-lg-9';

    if ($sidebarpos === 'classic') {
        $contentcol .= ' order-2';
        $sideprecol .= ' order-1';
        $sidepostcol .= ' order-3';
    } else if ($sidebarpos === 'left') {
        $contentcol .= ' order-3';
        $sideprecol .= ' order-1';
        $sidepostcol .= ' order-2';
    }
}

$PAGE->requires->js_call_amd('theme_mb2nl/course', 'sectionsToggle');

$html = '';

$html .= '<div id="main-content" class="course-layout layout2">';
$html .= '<div class="container-fluid">';
$html .= '<div id="theme-main-content" class="row">';
$html .= '<section id="region-main" class="content-col' . $contentcol . '">';
$html .= theme_mb2nl_show_hide_sidebars(['sidebar' => true], true);
$html .= '<div id="page-content">';

if (theme_mb2nl_isblock('content-top')) {
    $html .= $OUTPUT->blocks('content-top', theme_mb2nl_block_cls('content-top', 'none'));
}

$html .= $OUTPUT->main_content();

if (theme_mb2nl_isblock('content-bottom')) {
    $html .= $OUTPUT->blocks('content-bottom', theme_mb2nl_block_cls('content-bottom', 'none'));
}

$html .= theme_mb2nl_custom_sectionnav();
$html .= '</div>';
$html .= '</section>';
$html .= '<div class="course-sidebar sidebar-col' . $sideprecol . '">';
$html .= '<div class="sidebar-inner">';
$html .= theme_mb2nl_course_progressbar();
$html .= theme_mb2nl_course_boxes('circle');
$html .= theme_mb2nl_course_tabs();
$html .= !$tgsdb ? $OUTPUT->blocks('side-pre', theme_mb2nl_block_cls('side-pre')) : '';
$html .= $tgsdb ? $OUTPUT->blocks('side-post', theme_mb2nl_block_cls('side-post')) : '';
$html .= '</div>';
$html .= '</div>';

if ($sidepost) {
    $html .= '<div class="course-sidebar sidebar-col' . $sidepostcol . '">';
    $html .= '<div class="sidebar-inner">';
    $html .= $OUTPUT->blocks('side-post', theme_mb2nl_block_cls('side-post'));
    $html .= '</div>';
    $html .= '</div>';
}

$html .= '</div>';
$html .= '</div>';
$html .= '</div>';

$html .= $OUTPUT->standard_after_main_region_html();
$html .= $OUTPUT->theme_part('region_bottom');
$html .= $OUTPUT->theme_part('region_bottom_abcd');
$html .= $OUTPUT->theme_part('footer', ['sidebar' => true]);

echo $html;
