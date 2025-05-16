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

mb2_add_shortcode('mb2pb_videolocal', 'mb2_shortcode_mb2pb_videolocal');

/**
 *
 * Method to define shortcode
 *
 * @return HTML
 */
function mb2_shortcode_mb2pb_videolocal($atts, $content = null) {
    $atts2 = [
        'id' => 'videolocal',
        'width' => 800,
        'videofile' => '',
        'video_text' => '',
        'mt' => 0,
        'mb' => 30,
        'custom_class' => '',
        'template' => '',
    ];

    $a = mb2_shortcode_atts($atts2, $atts);

    $output = '';
    $style = '';
    $cls = '';

    $cls .= $a['template'] ? ' mb2-pb-template-videolocal' : '';
    $cls .= $a['custom_class'] ? ' ' . $a['custom_class'] : '';

    $imgplccls = $a['videofile'] ? ' hidden' : '';

    if ($a['mt'] || $a['mb'] || $a['width']) {
        $style .= ' style="';
        $style .= $a['mt'] ? 'margin-top:' . $a['mt'] . 'px;' : '';
        $style .= $a['mb'] ? 'margin-bottom:' . $a['mb'] . 'px;' : '';
        $style .= $a['width'] ? 'max-width:' . $a['width'] .'px;' : '';
        $style .= '"';
    }

    $output .= '<div class="theme-videolocal mb2-pb-element mb2pb-videolocal' . $cls . '"' .
    $style . theme_mb2nl_page_builder_el_datatts($atts, $atts2) . '>';
    $output .= '<div class="element-helper"></div>';
    $output .= theme_mb2nl_page_builder_el_actions('element', 'videolocal');
    $output .= '<div class="theme-videolocal-inner">';

    if ($a['videofile']) {
        $output .= '<video controls="true">';
        $output .= '<source src="' . $a['videofile'] . '">';
        $output .= '</video>';
    }

    $output .= '</div>';
    $output .= '<img class="videolocal-placeholder' . $imgplccls . '" src="' .
    theme_mb2nl_dummy_image('1600x1066') . '" alt" />';
    $output .= '</div>';

    return $output;

}
