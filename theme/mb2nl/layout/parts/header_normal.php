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

global $OUTPUT, $PAGE;

$stickynav = theme_mb2nl_is_stycky();
$socialheader = theme_mb2nl_theme_setting($PAGE, 'socialheader');
$socialtt = theme_mb2nl_theme_setting($PAGE, 'socialtt') == 1 ? 'top' : '';
$headernav = theme_mb2nl_theme_setting($PAGE, 'headernav');
$headercontent = theme_mb2nl_theme_setting($PAGE, 'headercontent') &&
theme_mb2nl_static_content(theme_mb2nl_theme_setting($PAGE, 'headercontent'), false);
$modaltools = theme_mb2nl_is_header_tools_modal();
$headerlistopt = ['listcls' => 'main-header-list'];
$enrolmentpage = theme_mb2nl_is_cenrol_page();
$bgimage = theme_mb2nl_pagebg_image();

// Start HTML.
$html = '';

$html .= '<body ' . $OUTPUT->body_attributes(theme_mb2nl_body_cls()) . '>';

if ($bgimage) {
    $html .= '<div class="page-bgimg lazy position-fixed" data-bg="' . $bgimage . '"></div>';
}

$html .= $OUTPUT->standard_top_of_body_html();
$html .= theme_mb2nl_acsb_block();
$html .= theme_mb2nl_loading_screen();
$html .= $OUTPUT->theme_part('sliding_panel');
$html .= '<div class="page-outer position-relative' . theme_mb2nl_bsfcls(1, 'column') . '" id="page"' .
theme_mb2nl_ajax_data_atts() . '>';
$html .= '<header id="main-header" style="' . theme_mb2nl_main_menu_style() . '">';
$html .= theme_mb2nl_notice('top');

if (theme_mb2nl_header_content_pos() == 2) {

    $html .= '<div class="top-bar">';
    $html .= '<div class="container-fluid">';
    $html .= '<div class="row">';
    $html .= '<div class="col-md-12">';
    $html .= '<div class="flexcols">';

    if ($headercontent) {
        $html .= '<div class="header-content">' .
        theme_mb2nl_static_content(theme_mb2nl_theme_setting($PAGE, 'headercontent'), true, true, $headerlistopt) . '</div>';
    }

    if (theme_mb2nl_topmenu()) {
        $html .= theme_mb2nl_topmenu();
    }

    if ($socialheader) {
        $html .= '<div>' . theme_mb2nl_social_icons(['tt' => $socialtt, 'pos' => 'topbar']) . '</div>';
    }

    $html .= '</div>';
    $html .= '</div>';
    $html .= '</div>';
    $html .= '</div>';
    $html .= '</div>';
}

$html .= '<div class="header-innner">';
$html .= '<div class="header-inner2">';

if ($stickynav == 3) {
    $html .= '<div class="sticky-replace-el"></div>';
}

$html .= '<div id="master-header">';
$html .= '<div class="master-header-inner">';
$html .= '<div class="container-fluid">';
$html .= '<div class="row">';
$html .= '<div class="col-md-12">';
$html .= '<div class="flexcols">';
$html .= $OUTPUT->theme_part('logo');

if (! theme_mb2nl_is_tgsdb()) {
    $html .= theme_mb2nl_site_menu();
}

if ($headernav) {
    $html .= $OUTPUT->theme_part('mobile_button');

    if ($headernav == 2) {
        $html .= '<div class="hnav2-wrap' . theme_mb2nl_bsfcls(1, 'column', '', 'end') . '">';
    }

    $html .= theme_mb2nl_main_menu();
}

if (theme_mb2nl_header_tools_pos() == 1) {
    $html .= theme_mb2nl_header_buttons(1);
}

if (theme_mb2nl_header_content_pos() == 1) {
    if (theme_mb2nl_topmenu()) {
        $html .= theme_mb2nl_topmenu('topmenu inheader');
    }

    if ($headercontent) {
        $html .= '<div class="header-content">' .
        theme_mb2nl_static_content(theme_mb2nl_theme_setting($PAGE, 'headercontent'), true, true, $headerlistopt) . '</div>';
    }

    if ($socialheader || theme_mb2nl_header_tools_pos() == 1) {
        $html .= '<div class="header-tools-wrap">';

        if (theme_mb2nl_header_tools_pos() == 1) {
            $html .= theme_mb2nl_header_tools($modaltools, 'tools-pos1');
        }

        if ($socialheader) {
            $html .= theme_mb2nl_social_icons(['tt' => $socialtt, 'pos' => 'header']);
        }

        $html .= '</div>';
    }
}

if ($headernav == 2) {
    $html .= '<div class="hnav2-tools' . theme_mb2nl_bsfcls(1, '', '', 'center') . '">';
}

if (theme_mb2nl_header_tools_pos() == 2) {
    $html .= theme_mb2nl_header_tools($modaltools, 'tools-pos2 hnavtype' .$headernav);
    $html .= theme_mb2nl_header_buttons();
}

if ($headernav == 2) {
    $html .= '</div>';
    $html .= '</div>';
}

if (!$headernav) {
    $html .= $OUTPUT->theme_part('mobile_button');
}

$html .= '</div>';
$html .= '</div>';
$html .= '</div>';
$html .= '</div>';
$html .= '</div>';
$html .= '</div>';

if ($stickynav == 1) {
    $html .= '<div class="sticky-replace-el"></div>';
}

if (!$headernav) {
    $html .= theme_mb2nl_main_menu(0, 2);
}

$html .= '</div><!-- end .header-inner2 -->';
$html .= '</div><!-- end .header-innner -->';
$html .= '</header><!-- end #main-header -->';
$html .= '<div class="pagelayout' . theme_mb2nl_bsfcls(1, 'row') . '">';
$html .= theme_mb2nl_tgsdb();
$html .= '<div class="pagelayout-b' . theme_mb2nl_bsfcls(1, 'column') . '">';
$html .= '<div class="pagelayout-content' . theme_mb2nl_bsfcls(1, 'column') . '">';
$html .= !$enrolmentpage ? $OUTPUT->theme_part('page_header') : '';
$html .= '<div class="page-b">';

echo $html;
