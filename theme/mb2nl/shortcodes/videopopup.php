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

mb2_add_shortcode('videopopup', 'mb2_shortcode_videopopup');

/**
 *
 * Method to define shortcode
 *
 * @return HTML
 */
function mb2_shortcode_videopopup($atts, $content = null) {
    global $PAGE;

    $atts2 = [
        'videourl' => 'https://youtu.be/3ORsUGVNxGs',
        'localvideo' => '',
        'ml' => 0,
        'mr' => 0,
        'mt' => 0,
        'mb' => 15,
        'text' => 'Play video',
        'size' => 4,
        'msize' => 67,
        'custom_class' => '',

        'fs' => 1.3,
        'fw' => 'medium',

        'rounded' => 1,

        'color' => '',

        'iconcolor' => '',
        'iconbgcolor' => '',
        'iconbocolor' => '',
    ];

    $a = mb2_shortcode_atts($atts2, $atts);

    $output = '';
    $style = '';
    $cls = '';
    $cls .= ' rounded' . $a['rounded'];
    $cls .= ' fw' . $a['fw'];
    $cls .= $a['custom_class'] ? ' ' . $a['custom_class'] : '';

    $style .= ' style="';
    $style .= 'margin-left:' . $a['ml'] . 'px;';
    $style .= 'margin-right:' . $a['mr'] . 'px;';
    $style .= 'margin-top:' . $a['mt'] . 'px;';
    $style .= 'margin-bottom:' . $a['mb'] . 'px;';
    $style .= '--mb2-vpopups:' . $a['size'] . 'rem;';
    $style .= '--mb2-vpopupms:' . $a['msize'] . ';';
    $style .= '--mb2-vpopupfs:' . $a['fs'] . 'rem;';
    $style .= $a['iconcolor'] ? '--mb2-iconcolor:' . $a['iconcolor'] . ';' : '';
    $style .= $a['iconbgcolor'] ? '--mb2-iconbgcolor:' . $a['iconbgcolor'] . ';' : '';
    $style .= $a['iconbocolor'] ? '--mb2-iconbocolor:' . $a['iconbocolor'] . ';' : '';
    $style .= $a['color'] ? '--mb2-color:' . $a['color'] . ';' : '';
    $style .= '"';

    $popupcls = $a['localvideo'] ? ' popup-html_video' : ' popup-iframe';
    $popupurl = $a['localvideo'] ? $a['localvideo'] : theme_mb2nl_get_video_url($a['videourl'], true);

    $output .= '<a class="mb2pb-videopopup-link lhsmall align-middle' .
    theme_mb2nl_bsfcls(2, '', 'center', 'center') . $cls . $popupcls . '" href="' . $popupurl . '"' . $style . '>';
    $output .= '<span class="mb2pb-videopopup-icon lhsmall' .
    theme_mb2nl_bsfcls(2, '', 'center', 'center') . '"><i class="ri-play-fill"></i></span>';
    $output .= '<span class="mb2pb-videopopup-text lhsmall' .
    theme_mb2nl_bsfcls(2, '', '', 'center') . '">' . theme_mb2nl_format_str(urldecode($a['text'])) . '</span>';
    $output .= '</a>';

    return $output;

}
