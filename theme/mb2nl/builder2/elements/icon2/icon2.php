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

mb2_add_shortcode('mb2pb_icon2', 'mb2_shortcode_mb2pb_icon2');

/**
 *
 * Method to define shortcode
 *
 * @return HTML
 */
function mb2_shortcode_mb2pb_icon2($atts, $content = null) {

    $atts2 = [
        'id' => 'icon2',
        'name' => 'fa fa-star',
        'color' => '',
        'size' => 'n',
        'circle' => 1,
        'desc' => 0,
        'spin' => 0,
        'rotate' => 0,
        'mt' => 0,
        'mb' => 30,
        'sizebg' => '',
        'rounded' => '',
        'bgcolor' => '',
        'custom_class' => '',
        'template' => '',
    ];

    $a = mb2_shortcode_atts($atts2, $atts);

    $output = '';
    $cls = '';
    $style = '';
    $estyle = '';

    $cls .= ' size' . $a['size'];
    $cls .= ' desc' . $a['desc'];
    $cls .= ' circle' . $a['circle'];
    $cls .= $a['template'] ? ' mb2-pb-template-icon2' : '';

    if ($a['mt'] || $a['mb']) {
        $style .= ' style="';
        $style .= $a['mt'] ? 'margin-top:' . $a['mt'] . 'px;' : '';
        $style .= $a['mb'] ? 'margin-bottom:' . $a['mb'] . 'px;' : '';
        $style .= '"';
    }

    // Define icon text.
    $content = $content ? $content : 'Icon text here.';
    $atts2['text'] = $content;

    if ($a['color'] || $a['bgcolor']) {
        $estyle .= ' style="';
        $estyle .= $a['color'] ? 'color:' . $a['color'] . ';' : '';
        $estyle .= $a['bgcolor'] ? 'background-color:' . $a['bgcolor'] . ';' : '';
        $estyle .= '"';
    }

    $output .= '<div class="theme-icon2 mb2-pb-element mb2pb-icon2' . $cls . '"' . $style .
    theme_mb2nl_page_builder_el_datatts($atts, $atts2) . '>';
    $output .= '<div class="element-helper"></div>';
    $output .= theme_mb2nl_page_builder_el_actions('element', 'icon2');
    $output .= '<span class="icon-bg"' . $estyle . '>';
    $output .= '<i class="' . $a['name'] . '"></i>';
    $output .= '</span>';
    $output .= '<span class="icon-desc">';
    $output .= urldecode($content);
    $output .= '</span>';
    $output .= '</div>';

    return $output;

}
