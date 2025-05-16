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

mb2_add_shortcode('mb2pb_html', 'mb2pb_shortcode_html');

/**
 *
 * Method to define shortcode
 *
 * @return HTML
 */
function mb2pb_shortcode_html($atts, $content = null) {

    global $PAGE;

    $atts2 = [
        'id' => 'html',
        'el_onmobile' => 1,
        'text' => '',
        'template' => '',
    ];

    $a = mb2_shortcode_atts($atts2, $atts);

    $cls = '';
    $output = '';
    $cls .= $a['template'] ? ' mb2-pb-template-html' : '';

    $cls .= ' el_onmobile' . $a['el_onmobile'];

    $sampltetext = '<h4 style="font-size:38px;border:dashed 1px #ccff66;padding: 20px;text-align:center;">';
    $sampltetext .= '<span style="color:#40ff00;">This</span>';
    $sampltetext .= ' <span style="color:#0040ff;">is</span>';
    $sampltetext .= ' <span style="color:#ffbf00;">a</span>';
    $sampltetext .= ' <span style="color:#ffff00;">custom</span>';
    $sampltetext .= ' <span style="color:#ff00ff;">HTML</span>';
    $sampltetext .= '</h4>';

    $a['text'] = $a['text'] ? $a['text'] : $sampltetext;

    $output .= '<div class="mb2-pb-element mb2pb-html' . $cls . '"' .
    theme_mb2nl_page_builder_el_datatts($atts, $atts2) . '>';
    $output .= '<div class="element-helper"></div>';
    $output .= theme_mb2nl_page_builder_el_actions('element', 'html');
    $output .= '<div class="html-content">' . urldecode($a['text']) . '</div>';
    $output .= '</div>'; // ...mb2-pb-element

    return $output;

}
