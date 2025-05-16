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

mb2_add_shortcode('mb2pb_line', 'mb2_shortcode_mb2pb_line');

/**
 *
 * Method to define shortcode
 *
 * @return HTML
 */
function mb2_shortcode_mb2pb_line($atts, $content = null) {

    $atts2 = [
        'id' => 'line',
        'color' => 'dark',
        'custom_color' => '',
        'size' => 1,
        'double' => 0,
        'style' => 'solid',
        'mt' => 30,
        'mb' => 30,
        'custom_class' => '',
        'template' => '',
    ];

    $a = mb2_shortcode_atts($atts2, $atts);

    $output = '';
    $elstyle = '';
    $cls = '';

    $elstyle .= ' style="';
    $elstyle .= $a['custom_color'] !== '' ? 'border-color:' . $a['custom_color']  . ';' : '';
    $elstyle .= 'border-width:' . $a['size']  . 'px;';
    $elstyle .= 'margin-top:' .  $a['mt'] . 'px;';
    $elstyle .= 'margin-bottom:' .  $a['mb'] . 'px;';
    $elstyle .= '"';

    $cls .= ' ' . $a['color'];
    $cls .= $a['custom_class'] ? ' ' . $a['custom_class'] : '';
    $cls .= ' double' . $a['double'];
    $cls .= ' border-' . $a['style'];

    $tmplcls = $a['template'] ? ' mb2-pb-template-line' : '';

    $output .= '<div class="mb2-pb-element mb2pb-line' . $tmplcls . '"' .
    theme_mb2nl_page_builder_el_datatts($atts, $atts2) . '>';
    $output .= '<div class="element-helper"></div>';
    $output .= theme_mb2nl_page_builder_el_actions('element', 'line');
    $output .= '<div class="border-hor' . $cls . '"' . $elstyle . '></div>';
    $output .= '</div>';

    return $output;

}
