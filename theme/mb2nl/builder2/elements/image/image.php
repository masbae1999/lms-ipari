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

mb2_add_shortcode('mb2pb_image', 'mb2_shortcode_mb2pb_image');

/**
 *
 * Method to define shortcode
 *
 * @return HTML
 */
function mb2_shortcode_mb2pb_image($atts, $content = null) {

    $atts2 = [
        'id' => 'image',
        'align' => 'none',
        'center' => 1,
        'width' => 450,
        'alt' => '',
        'mt' => 0,
        'mb' => 30,
        'caption' => 0,
        'captiontext' => 'Caption text here',
        'link' => '',
        'link_target' => 0,
        'custom_class' => '',
        'template' => '',
    ];

    $a = mb2_shortcode_atts($atts2, $atts);

    $output = '';
    $style = '';
    $cls = '';

    $cls .= ' align-' . $a['align'];
    $cls .= ' center' . $a['center'];
    $cls .= ' caption' . $a['caption'];
    $cls .= $a['custom_class'] ? ' ' . $a['custom_class'] : '';
    $cls .= $a['template'] ? ' mb2-pb-template-image' : '';

    if ($a['mt'] || $a['mb'] || $a['width']) {
        $style .= ' style="';
        $style .= $a['mt'] ? 'margin-top:' . $a['mt'] . 'px;' : '';
        $style .= $a['mb'] ? 'margin-bottom:' . $a['mb'] . 'px;' : '';
        $style .= $a['width'] ? 'width:' . $a['width'] . 'px;max-width:100%;' : '';
        $style .= '"';
    }

    $content = $content ? $content : theme_mb2nl_dummy_image('1600x1066');
    $atts2['text'] = $content;

    $output .= '<div class="mb2-pb-element mb2pb-image mb2-image' . $cls . '"' . $style .
    theme_mb2nl_page_builder_el_datatts($atts, $atts2) . '>';
    $output .= '<div class="element-helper"></div>';
    $output .= theme_mb2nl_page_builder_el_actions('element', 'image');
    $output .= '<img class="mb2-image-src" src="' . urldecode($content) . '" alt="' . $a['alt'] . '" />';
    $output .= '<div class="caption">' . $a['captiontext'] . '</div>';
    $output .= '</div>';

    return $output;

}
