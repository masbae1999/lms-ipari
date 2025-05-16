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

$clpage = theme_mb2nl_is_login(true);
$showcontentpos = is_siteadmin() ? true : ($PAGE->pagetype !== 'login-index');

$html = '';

$html .= $OUTPUT->doctype();
$html .= $OUTPUT->theme_part('head');
$html .= $OUTPUT->theme_part('header');
$html .= theme_mb2nl_notice();

if (!$clpage) {
    $html .= $OUTPUT->theme_part('course_banner');
}

$html .= '<div id="main-content">';
$html .= '<div class="container-fluid">';
$html .= '<div class="row">';
$html .= '<div class="col-md-12">';
$html .= '<div id="page-content">';
$html .= $OUTPUT->course_content_header();

if (theme_mb2nl_isblock('content-top') && $showcontentpos) {
    $html .= $OUTPUT->blocks('content-top', theme_mb2nl_block_cls('content-top', 'none'));
}

$html .= $OUTPUT->main_content();

if (theme_mb2nl_isblock('content-bottom') && $showcontentpos) {
    $html .= $OUTPUT->blocks('content-bottom', theme_mb2nl_block_cls('content-bottom', 'none'));
}

$html .= $OUTPUT->course_content_footer();
$html .= '</div>';
$html .= '</div>';
$html .= '</div>';
$html .= '</div>';
$html .= '</div>';
$html .= $OUTPUT->standard_after_main_region_html();

if (!$clpage) {
    $html .= $OUTPUT->theme_part('region_bottom');
    $html .= $OUTPUT->theme_part('region_bottom_abcd');
}

$html .= $OUTPUT->theme_part('footer', ['sidebar' => false]);

echo $html;
