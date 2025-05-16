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

mb2_add_shortcode('mb2pb_alert', 'mb2pb_shortcode_alert');

/**
 *
 * Method to define alert shortcode
 *
 * @return HTML
 */
function mb2pb_shortcode_alert($atts, $content=null) {

    $atts2 = [
        'id' => 'alert',
        'type' => 'info',
        'close' => 0,
        'mt' => 0,
        'mb' => 30,
        'class' => '',
        'template' => '',
    ];

    $a = mb2_shortcode_atts($atts2, $atts);

    $output = '';
    $style = '';
    $cls = $a['close'] ? ' alert-dismissible' : '';
    $cls .= $a['class'] != '' ? ' ' . $a['class'] : '';
    $cls .= $a['template'] ? ' mb2-pb-template-alert' : '';
    $cls .= ' closebtn' . $a['close'];

    if ($a['mt'] || $a['mb']) {
        $style .= ' style="';
        $style .= $a['mb'] ? 'margin-top:' . $a['mb'] . 'px;' : '';
        $style .= $a['mb'] ? 'margin-bottom:' . $a['mb'] . 'px;' : '';
        $style .= '"';
    }

    $content = $content ? $content : 'Alert text here.';
    $atts2['text'] = $content;

    $output .= '<div class="alert mb2-pb-element mb2pb-alert alert-' . $a['type'] . $cls . '"' . $style .
    theme_mb2nl_page_builder_el_datatts($atts, $atts2) . '>';
    $output .= '<div class="element-helper"></div>';
    $output .= theme_mb2nl_page_builder_el_actions('element', 'alert');
    $output .= '<button type="button" class="close" aria-label="' .
    get_string('closebuttontitle') . '"><span aria-hidden="true">&times;</span></button>';
    $output .= '<div class="alert-text">' . urldecode($content) . '</div>';
    $output .= '</div>';

    return $output;

}
