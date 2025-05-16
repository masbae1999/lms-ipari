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

$contentcol = '';
$sidecol = '';

if ($sidebarpos === 'left' || $sidebarpos === 'classic') {
    $contentcol = ' order-2';
    $sidecol = ' order-1';
}

$PAGE->requires->js_call_amd('theme_mb2nl/course', 'sectionsToggle');

$html = '';

$html .= '<div id="main-content" class="course-layout layout2">';
$html .= '<div class="container-fluid">';
$html .= '<div id="theme-main-content" class="row">';
$html .= '<section id="region-main" class="col-lg-9 content-col' . $contentcol . '">';
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
$html .= '<div class="col-lg-3 sidebar-col course-sidebar' . $sidecol . '">';
$html .= '<div class="sidebar-inner">';
$html .= theme_mb2nl_course_progressbar();
$html .= theme_mb2nl_course_boxes('circle');
$html .= theme_mb2nl_course_tabs();
$html .= !theme_mb2nl_is_tgsdb() ? $OUTPUT->blocks('side-pre', theme_mb2nl_block_cls('side-pre')) : '';
$html .= '</div>';
$html .= '</div>';
$html .= '</div>';
$html .= '</div>';
$html .= '</div>';
$html .= $OUTPUT->standard_after_main_region_html();
$html .= $OUTPUT->theme_part('region_bottom');
$html .= $OUTPUT->theme_part('region_bottom_abcd');
$html .= $OUTPUT->theme_part('footer', ['sidebar' => true]);

echo $html;
