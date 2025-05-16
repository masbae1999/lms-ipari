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

mb2_add_shortcode('mb2pb_search', 'mb2pb_shortcode_mb2pb_search');

/**
 *
 * Method to define shortcode
 *
 * @return HTML
 */
function mb2pb_shortcode_mb2pb_search($atts, $content = null) {

    $atts2 = [
        'id' => 'search',
        'placeholder' => get_string('searchcourses'),
        'global' => 0,
        'rounded' => 0,
        'width' => 750,
        'border' => 1,
        'size' => 'n',
        'mt' => 0,
        'mb' => 30,
        'custom_class' => '',
        'template' => '',
    ];

    $a = mb2_shortcode_atts($atts2, $atts);

    $output = '';
    $style = '';
    $inputstyle = '';
    $cls = '';

    $formid = uniqid('searchform_');
    $inputname = $a['global'] ? 'q' : 'search';

    $cls .= $a['custom_class'] ? ' ' . $a['custom_class'] : '';
    $cls .= ' size' . $a['size'];
    $cls .= ' rounded' . $a['rounded'];
    $cls .= ' border' . $a['border'];
    $cls .= $a['template'] ? ' mb2-pb-template-search' : '';

    if ($a['mt'] || $a['mb'] || $a['width']) {
        $style .= ' style="';
        $style .= $a['mt'] ? 'margin-top:' . $a['mt'] . 'px;' : '';
        $style .= $a['mb'] ? 'margin-bottom:' . $a['mb'] . 'px;' : '';
        $style .= $a['width'] ? 'max-width:' . $a['width'] . 'px;' : '';
        $style .= '"';
    }

    $output .= '<div class="mb2-pb-element mb2-pb-search' . $cls . '"' . $style .
    theme_mb2nl_page_builder_el_datatts($atts, $atts2) . '>';
    $output .= '<div class="element-helper"></div>';
    $output .= theme_mb2nl_page_builder_el_actions('element', 'search');
    $output .= '<form id="' . $formid . '" action="">';
    $output .= '<input id="' . $formid . '_search" type="text" value="" placeholder="' .
    $a['placeholder'] . '" name="' . $inputname . '">';
    $output .= '<button type="submit"><i class="fa fa-search"></i></button>';
    $output .= '</form>';
    $output .= '</div>';

    return $output;

}
