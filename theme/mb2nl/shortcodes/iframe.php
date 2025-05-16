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

mb2_add_shortcode('iframe', 'mb2_shortcode_iframe');

/**
 *
 * Method to define shortcode
 *
 * @return HTML
 */
function mb2_shortcode_iframe($atts, $content = null) {
    $atts2 = [
        'width' => 800,
        'height' => 350,
        'mt' => 0,
        'mb' => 30,
        'url' => '',
        'title' => '',
        'custom_class' => '',
    ];

    $a = mb2_shortcode_atts($atts2, $atts);

    $output = '';
    $style = '';
    $cls = '';

    if ($a['mt'] || $a['mb'] || $a['width']) {
        $style .= ' style="';
        $style .= $a['mt'] ? 'margin-top:' . $a['mt'] . 'px;' : '';
        $style .= $a['mb'] ? 'margin-bottom:' . $a['mb'] . 'px;' : '';
        $style .= $a['width'] ? 'max-width:' . $a['width'] .'px;' : '';
        $style .= '"';
    }

    $output .= '<div class="mb2pb-iframe"' . $style . '>';
    $output .= '<div class="embed-responsive-wrap"><div class="embed-responsive-wrap-inner">';
    $output .= '<iframe class="lazy" data-src="' . $a['url'] . '" title="' .
    $a['title'] . '" height="' . $a['height']  . '"></iframe>';
    $output .= '</div></div>';
    $output .= '</div>';

    return $output;

}
