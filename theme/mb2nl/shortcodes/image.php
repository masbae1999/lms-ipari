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

mb2_add_shortcode('image', 'mb2_image');

/**
 *
 * Method to define shortcode
 *
 * @return HTML
 */
function mb2_image($atts, $content) {

    $atts2 = [
        'align' => 'none',
        'center' => 1,
        'width' => 0,
        'alt' => '',
        'mt' => 0,
        'mb' => 30,
        'caption' => 0,
        'captiontext' => '',
        'link' => '',
        'link_target' => 0,
        'custom_class' => '',
    ];

    $a = mb2_shortcode_atts($atts2, $atts);

    $output = '';
    $style = '';
    $cls = '';

    $cls .= ' align-' . $a['align'];
    $cls .= ' center' . $a['center'];
    $cls .= $a['custom_class'] ? ' ' . $a['custom_class'] : '';

    if ($a['mt'] || $a['mb'] || $a['width']) {
        $style .= ' style="';
        $style .= $a['mt'] ? 'margin-top:' . $a['mt'] . 'px;' : '';
        $style .= $a['mb'] ? 'margin-bottom:' . $a['mb'] . 'px;' : '';
        $style .= $a['width'] ? 'width:' . $a['width'] . 'px;max-width:100%;' : '';
        $style .= '"';
    }

    $islinktarget = $a['link_target'] ? ' target="' . $a['link_target'] . '"' : '';
    $cpttext = $a['captiontext'] ? $a['captiontext'] : $a['alt'];

    $output .= '<div class="mb2-image' . $cls . '"' . $style . '>';
    $output .= $a['link'] ? '<a href="' . $a['link'] . '"' . $islinktarget . '>' : '';
    $output .= '<img class="lazy" src="' . theme_mb2nl_lazy_plc() . '" data-src="' .
    $content . '" alt="' . $a['alt'] . '" />';
    $output .= $a['link'] ? '</a>' : '';
    $output .= $a['caption'] ? '<span class="caption d-block">' . theme_mb2nl_format_str($cpttext) . '</span>' : '';
    $output .= '</div>';

    return $output;

}
