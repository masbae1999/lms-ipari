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
 */

defined('MOODLE_INTERNAL') || die();

mb2_add_shortcode('mb2pb_header', 'mb2_shortcode_mb2pb_header');

/**
 *
 * Method to define shortcode
 *
 * @return HTML
 */
function mb2_shortcode_mb2pb_header ($atts, $content=null) {

    $atts2 = [
        'id' => 'header',
        'title' => 'Title text here',
        'issubtitle' => 1,
        'subtitle' => 'Subtitle text here',
        'bgcolor' => '',
        'linkbtn' => 0,
        'link' => '#',
        'btntype' => 'primary',
        'btnsize' => 'lg',
        'btnrounded' => 0,
        'btnborder' => 0,
        'link_target' => 0,
        'linktext' => get_string('readmorefp', 'local_mb2builder'),
        'color' => '',
        'mt' => 0,
        'mb' => 30,
        'image' => theme_mb2nl_dummy_image('2500x470'),
        'type' => 'dark',
        'custom_class' => '',
        'template' => '',
    ];

    $a = mb2_shortcode_atts($atts2, $atts);

    $output = '';
    $style = '';
    $cls = '';
    $btncls = '';

    $cls .= $a['custom_class'] ? ' ' . $a['custom_class'] : '';
    $cls .= ' type-' . $a['type'];
    $cls .= ' linkbtn' . $a['linkbtn'];
    $cls .= ' issubtitle' . $a['issubtitle'];

    $btncls .= ' type' . $a['btntype'];
    $btncls .= ' size' . $a['btnsize'];
    $btncls .= ' rounded' . $a['btnrounded'];
    $btncls .= ' btnborder' . $a['btnborder'];

    $target = $a['link_target'] ? ' target="_blank"' : '';

    $tmplcls = $a['template'] ? ' mb2-pb-template-header' : '';

    if ($a['mt'] || $a['mb']) {
        $style .= ' style="';
        $style .= $a['mt'] ? 'margin-top:' . $a['mt'] . 'px;' : '';
        $style .= $a['mb'] ? 'margin-bottom:' . $a['mb'] . 'px;' : '';
        $style .= '"';
    }

    $bgstyle = $a['image'] ? ' style="background-image:url(\'' . $a['image'] . '\');"' : '';
    $bgcolorstyle = $a['bgcolor'] ? ' style="background-color:' . $a['bgcolor'] . ';"' : '';
    $colorstyle = $a['color'] ? ' style="color:' . $a['color'] . ';"' : '';

    $output .= '<div class="mb2-pb-element mb2pb-header' . $tmplcls . '"' .
    $style . theme_mb2nl_page_builder_el_datatts($atts, $atts2) . '>';
    $output .= '<div class="element-helper"></div>';
    $output .= theme_mb2nl_page_builder_el_actions('element', 'header');
    $output .= '<div class="theme-header-wrap theme-header' . $cls . '"' . $bgstyle . '>';
    $output .= '<div class="theme-header-content position-relative' . theme_mb2nl_bsfcls(1, '', '', 'center') . '">';
    $output .= '<div class="content-a">';
    $output .= '<h3 class="theme-header-title"' . $colorstyle . '>' . $a['title'] . '</h3>';
    $output .= '<div><div class="theme-header-subtitle"' . $colorstyle . '>' . $a['subtitle'] . '</div></div>';
    $output .= '</div>';
    $output .= '<div class="content-b">';
    $output .= '<a href="#" class="mb2-pb-btn' . $btncls . '"><span class="btn-intext">' . $a['linktext'] . '</span></a>';
    $output .= '</div>';
    $output .= '</div>';
    $output .= '<div class="theme-header-bg"' . $bgcolorstyle . '></div>';
    $output .= '</div>';
    $output .= '</div>';

    return $output;

}
