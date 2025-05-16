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

$ratingblock = theme_mb2nl_rating_block();
$clpage = theme_mb2nl_is_login(true);
$cfilter = theme_mb2nl_courses_filter_form();
$tgsdb = theme_mb2nl_is_tgsdb();

if ($tgsdb) {
    $sidebar = $cfilter;
} else {
    $sidebar = theme_mb2nl_isblock('side-pre') || $cfilter || theme_mb2nl_is_toc() || $ratingblock;
}

// Blog sidebar.
if (theme_mb2nl_is_blog() && ! theme_mb2nl_theme_setting($PAGE, 'blogsidebar')) {
    $sidebar = false;
} else if (theme_mb2nl_is_blogsingle() && ! theme_mb2nl_theme_setting($PAGE, 'blogsinglesidebar')) {
    $sidebar = false;
}

if (theme_mb2nl_is_login(true)) {
    $sidebar = false;
}

$sidebarpos = theme_mb2nl_sidebarpos();

if ($sidebar) {
    $contentcol = 'col-lg-9';
    $sidecol = 'col-lg-3';

    if ($sidebarpos === 'left' || $sidebarpos === 'classic') {
        $contentcol .= ' order-2';
        $sidecol .= ' order-1';
    }
} else {
    $contentcol = 'col-lg-12';
}

$html = '';
$html .= '';

$html .= theme_mb2nl_notice();

if (!$clpage) {
    $html .= $OUTPUT->theme_part('course_banner');
}

$html .= '<div id="main-content">';
$html .= '<div class="container-fluid">';
$html .= '<div id="theme-main-content" class="row">';
$html .= '<section id="region-main" class="content-col ' . $contentcol . '">';
$html .= theme_mb2nl_show_hide_sidebars(['sidebar' => $sidebar], true);
$html .= '<div id="page-content">';
$html .= $OUTPUT->course_content_header();
$html .= $OUTPUT->theme_part('dashboard');
$html .= theme_mb2nl_check_plugins();

if ($PAGE->pagetype === 'user-profile') {
    $html .= $OUTPUT->context_header();
}

if (theme_mb2nl_isblock('content-top')) {
    $html .= $OUTPUT->blocks('content-top', theme_mb2nl_block_cls('content-top', 'none'));
}

$html .= $OUTPUT->main_content();

if (theme_mb2nl_isblock('content-bottom')) {
    $html .= $OUTPUT->blocks('content-bottom', theme_mb2nl_block_cls('content-bottom', 'none'));
}

$html .= theme_mb2nl_custom_sectionnav();
$html .= $OUTPUT->course_content_footer();
$html .= '</div>';
$html .= '</section>';

if ($sidebar) {

    $html .= '<div class="sidebar-col ' . $sidecol . '">';

    if (!$tgsdb && theme_mb2nl_is_toc()) {
        $html .= theme_mb2nl_module_sections(true);
    }

    $html .= !$tgsdb ? theme_mb2nl_rating_block() : '';
    $html .= $cfilter;
    $html .= !$tgsdb ? $OUTPUT->blocks('side-pre', theme_mb2nl_block_cls('side-pre')) : '';
    $html .= '</div>';

}

$html .= '</div>';
$html .= '</div>';
$html .= '</div>';
$html .= $OUTPUT->standard_after_main_region_html();

if (!$clpage) {
    $html .= $OUTPUT->theme_part('region_bottom');
    $html .= $OUTPUT->theme_part('region_bottom_abcd');
}

$html .= $OUTPUT->theme_part('footer', ['sidebar' => $sidebar]);

echo $html;
