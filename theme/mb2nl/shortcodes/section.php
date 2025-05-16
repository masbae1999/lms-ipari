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


mb2_add_shortcode('section', 'mb2_shortcode_section');

/**
 *
 * Method to define shortcode
 *
 * @return HTML
 */
function mb2_shortcode_section ($atts, $content= null) {

    $atts2 = [
        'size' => '4',
        'margin' => '',
        'bgcolor' => '',
        'prbg' => 0,
        'scheme' => 'light',
        'bgimage' => '',
        'pt' => 0,

        'bgel1' => '',
        'bgel2' => '',
        'bgel1s' => 500,
        'bgel2s' => 500,
        'bgel1top' => 200,
        'bgel2top' => 200,
        'bgel1left' => 0,
        'bgel2left' => 0,

        'sectionhidden' => 0,
        'sectionlang' => '',
        'pb' => 0,
        'sectionaccess' => 0,
        'custom_class' => '',
    ];

    $a = mb2_shortcode_atts($atts2, $atts);

    $output = '';

    $cls = $a['custom_class'] ? ' ' . $a['custom_class'] : '';
    $cls .= ' pre-bg' . $a['prbg'];
    $cls .= ' hidden' . $a['sectionhidden'];
    $cls .= ' ' . $a['scheme'];
    $cls .= $a['bgimage'] ? ' lazy' : '';

    $langarr = explode(',', $a['sectionlang']);
    $trimmedlangarr = array_map('trim', $langarr);

    if ($a['sectionlang'] && !in_array(current_language(), $trimmedlangarr)) {
        return;
    }

    if ($a['sectionhidden'] && ! is_siteadmin()) {
        return;
    }

    if ($a['sectionaccess'] == 1) {
        if (! isloggedin() || isguestuser()) {
            return;
        }
    } else if ($a['sectionaccess'] == 2) {
        if (isloggedin() && ! isguestuser()) {
            return;
        }
    }

    $sectionstyle = ' style="';
    $sectionstyle .= 'padding-top:' . $a['pt'] . 'px;';
    $sectionstyle .= 'padding-bottom:' . $a['pb'] . 'px;';
    $sectionstyle .= $a['bgcolor'] ? 'background-color:' . $a['bgcolor'] . ';' : '';
    $sectionstyle .= '"';

    $databgimage = $a['bgimage'] ? ' data-bg="' . $a['bgimage'] . '"' : '';

    $output .= '<div class="mb2-pb-fpsection position-relative' . $cls . '"' . $databgimage . '>';
    $output .= '<div class="section-inner"' . $sectionstyle . '>';
    $output .= mb2_do_shortcode($content);

    if ($a['bgel1'] || $a['bgel2']) {
        $output .= '<div class="section-bgel-wrap">';
        $output .= '<div class="section-bgel-wrap2">';
        $output .= $a['bgel1'] ?
        '<div class="section-bgel bgel1" style="width:' . $a['bgel1s'] . 'px;top:' . $a['bgel1top'] . 'px;left:' .
        $a['bgel1left'] . '%;"><img class="lazy" src="' .
        theme_mb2nl_lazy_plc() . '" data-src="' . $a['bgel1'] . '" alt="" aria-hidden="true"></div>' : '';
        $output .= $a['bgel2'] ?
        '<div class="section-bgel bgel2" style="width:' . $a['bgel2s'] . 'px;top:' . $a['bgel2top'] . 'px;left:' .
        $a['bgel2left'] . '%;"><img class="lazy" src="' .
        theme_mb2nl_lazy_plc() . '" data-src="' . $a['bgel2'] . '" alt="" aria-hidden="true"></div>' : '';
        $output .= '</div>'; // ...section-bgel-wrap2
        $output .= '</div>'; // ...section-bgel-wrap
    }

    $output .= '</div>';
    $output .= '</div>';

    return $output;

}
