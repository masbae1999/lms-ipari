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
$sidepre = ! theme_mb2nl_is_tgsdb() && (theme_mb2nl_isblock('side-pre') || theme_mb2nl_is_toc() || $ratingblock);
$sidepost = theme_mb2nl_isblock('side-post');
$sidebarpos = theme_mb2nl_sidebarpos();

$sidebar = ($sidepre || $sidepost);
$contentcol = 'col-lg-12';
$sideprecol = 'col-lg-3';
$sidepostcol = 'col-lg-3';

if ($sidepre && $sidepost) {
    $contentcol = 'col-lg-6';

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
    $contentcol = 'col-lg-9';

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

$html = '';

$html .= theme_mb2nl_notice();
$html .= $OUTPUT->theme_part('course_banner');
$html .= '<div id="main-content">';
$html .= '<div class="container-fluid">';
$html .= '<div id="theme-main-content" class="row">';
$html .= '<section id="region-main" class="content-col ' . $contentcol . '">';
$html .= theme_mb2nl_show_hide_sidebars(['sidebar' => $sidebar], true);
$html .= '<div id="page-content">';
$html .= $OUTPUT->course_content_header();
$html .= $OUTPUT->theme_part('dashboard');
$html .= theme_mb2nl_check_plugins();

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

if ($sidepre) {
    $html .= '<div class="sidebar-col ' . $sideprecol . '">';

    if (theme_mb2nl_is_toc()) {
        $html .= theme_mb2nl_module_sections(true);
    }

    $html .= theme_mb2nl_rating_block('', true);
    $html .= $OUTPUT->blocks('side-pre', theme_mb2nl_block_cls('side-pre', 'default'));
    $html .= '</div>';
}

if ($sidepost) {
    $html .= '<div class="sidebar-col ' . $sidepostcol . '">';
    $html .= $OUTPUT->blocks('side-post', theme_mb2nl_block_cls('side-post', 'default'));
    $html .= '</div>';
}

$html .= '</div>';
$html .= '</div>';
$html .= '</div>';

$html .= $OUTPUT->standard_after_main_region_html();
$html .= $OUTPUT->theme_part('region_bottom');
$html .= $OUTPUT->theme_part('region_bottom_abcd');
$html .= $OUTPUT->theme_part('footer', ['sidebar' => $sidebar]);

echo $html;
