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

mb2_add_shortcode('boxes3d_item', 'mb2_shortcode_boxes3d_item');

/**
 *
 * Method to define boxes 3D shortcode
 *
 * @return HTML
 */
function mb2_shortcode_boxes3d_item($atts, $content = null) {

    $atts2 = [
        'image' => '',
        'link' => '',
        'type' => '',
        'title' => 'Box title here',
        'link_target' => 0,
        'target' => 0,
        'frontcolor' => '',
        'backcolor' => '',
    ];

    $a = mb2_shortcode_atts($atts2, $atts);

    $output = '';
    $cls = '';
    $titlecolorspan = '';

    $a['link_target'] = $a['target'] ? $a['target'] : $a['link_target'];
    $a['target'] = $a['link_target'] ? ' target="_balnk"' : '';

    $stylefront = $a['frontcolor'] !== '' ? ' style="background-color:' . $a['frontcolor'] . ';"' : '';
    $styleback = $a['backcolor'] !== '' ? ' style="background-color:' . $a['backcolor'] . ';"' : '';

    $istitle = theme_mb2nl_format_str($a['title']);

    $output .= '<div class="theme-box">';
    $output .= $a['link'] !== '' ? '<a href="' . $a['link'] . '"' . $a['target'] . '>' : '';
    $output .= '<div class="theme-box3d' . $cls . '">';
    $output .= '<div class="box-scene">';

    $output .= '<div class="box-face box-front">';
    $output .= '<div class="vtable-wrapp">';
    $output .= '<div class="vtable">';
    $output .= '<div class="vtable-cell">';
    $output .= '<h4 class="box-title"><span class="box-title-text">' . $istitle . '</span></h4>';
    $output .= '</div>';
    $output .= '</div>';
    $output .= '</div>';
    $output .= '<img class="theme-box3d-img lazy" aria-hidden="true" src="' .
    theme_mb2nl_lazy_plc() . '" data-src="' . $a['image'] . '" alt="' . strip_tags($istitle) . '">';
    $output .= '<div class="theme-box3d-color"' . $stylefront . '></div>';
    $output .= '</div>'; // ...box-front

    $output .= '<div class="box-face box-back">';
    $output .= '<div class="vtable-wrapp">';
    $output .= '<div class="vtable">';
    $output .= '<div class="vtable-cell">';
    $output .= '<div class="box-desc-text">' . theme_mb2nl_format_str($content) . '</div>';
    $output .= '</div>';
    $output .= '</div>';
    $output .= '</div>';
    $output .= '<div class="theme-box3d-color"' . $styleback . '></div>';
    $output .= '</div>'; // ...box-back

    $output .= '<img class="theme-box3d-img theme-box3d-imagenovisible lazy" src="' .
    theme_mb2nl_lazy_plc() . '" data-src="' . $a['image'] . '" alt="' . strip_tags($istitle) . '">';
    $output .= '</div>'; // ...box-scene
    $output .= '</div>';
    $output .= $a['link'] !== '' ? '</a>' : '';
    $output .= '</div>';

    return $output;

}
