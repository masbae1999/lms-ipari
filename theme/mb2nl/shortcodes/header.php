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


mb2_add_shortcode('header', 'mb2_shortcode_header');

/**
 *
 * Method to define shortcode
 *
 * @return HTML
 */
function mb2_shortcode_header ($atts, $content= null) {

    $atts2 = [
        'title' => '',
        'subtitle' => '',
        'issubtitle' => 1,
        'mt' => 0,
        'mb' => 30,
        'bgcolor' => '',
        'linkbtn' => 0,
        'link' => '#',
        'btntype' => 'primary',
        'btnsize' => 'lg',
        'btnrounded' => 0,
        'btnborder' => 0,
        'link_target' => 0,
        'linktext' => get_string('readmore', 'theme_mb2nl'),
        'color' => '',
        'image' => '',
        'type' => 1,
        'custom_class' => '',
    ];

    $a = mb2_shortcode_atts($atts2, $atts);

    $output = '';
    $style = '';
    $btncls = '';
    $cls = '';

    $cls .= $a['custom_class'] ? ' ' . $a['custom_class'] : '';
    $cls .= ' type-' . $a['type'];
    $cls .= ' linkbtn' . $a['linkbtn'];
    $cls .= ' issubtitle' . $a['issubtitle'];
    $cls .= $a['image'] !== '' ? ' lazy' : '';

    $target = $a['link_target'] ? ' target="_blank"' : '';

    $btncls .= ' type' . $a['btntype'];
    $btncls .= ' size' . $a['btnsize'];
    $btncls .= ' rounded' . $a['btnrounded'];
    $btncls .= ' btnborder' . $a['btnborder'];

    if ($a['mt'] || $a['mb']) {
        $style .= ' style="';
        $style .= $a['mt'] ? 'margin-top:' . $a['mt'] . 'px;' : '';
        $style .= $a['mb'] ? 'margin-bottom:' . $a['mb'] . 'px;' : '';
        $style .= '"';
    }

    $bgdata = $a['image'] !== '' ? ' data-bg="' . $a['image'] . '"' : '';
    $bgcolorstyle = $a['bgcolor'] ? ' style="background-color:' . $a['bgcolor'] . ';"' : '';
    $colorstyle = $a['color'] ? ' style="color:' . $a['color'] . ';"' : '';

    $output .= '<div class="theme-header-wrap"' . $style . '>';
    $output .= '<div class="theme-header' . $cls . '"' . $bgdata . '>';
    $output .= '<div class="theme-header-content position-relative' . theme_mb2nl_bsfcls(1, '', '', 'center') . '">';
    $output .= '<div class="content-a">';
    $output .= $a['title'] ? '<h3 class="theme-header-title"' . $colorstyle . '>' .
    theme_mb2nl_format_str($a['title']) . '</h3>' : '';
    $output .= $a['subtitle'] ? '<div><div class="theme-header-subtitle"' . $colorstyle . '>' .
    theme_mb2nl_format_str($a['subtitle']) . '</div></div>' : '';
    $output .= '</div>';

    if ($a['linkbtn']) {
        $output .= '<div class="content-b">';
        $output .= '<a href="' . $a['link']. '" class="mb2-pb-btn' . $btncls . '"' . $target . '><span class="btn-intext">' .
        theme_mb2nl_format_str($a['linktext']) . '</span></a>';
        $output .= '</div>';
    }

    $output .= '</div>';
    $output .= '<div class="theme-header-bg"' . $bgcolorstyle . '></div>';
    $output .= '</div>';
    $output .= '</div>';

    return $output;

}
