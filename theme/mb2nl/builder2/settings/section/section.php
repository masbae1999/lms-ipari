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

mb2_add_shortcode('mb2pb_section', 'mb2_shortcode_mb2pb_section');

/**
 *
 * Method to define shortcode
 *
 * @return HTML
 */
function mb2_shortcode_mb2pb_section ($atts, $content = null) {

    $atts2 = [
        'id' => 'section',
        'size' => '4',
        'margin' => '',
        'bgcolor' => '',
        'prbg' => 0,
        'scheme' => 'light',
        'bgimage' => '',

        'bgel1' => '',
        'bgel2' => '',
        'bgel1s' => 500,
        'bgel2s' => 500,
        'bgel1top' => 200,
        'bgel2top' => 200,
        'bgel1left' => 0,
        'bgel2left' => 0,

        'pt' => 0,
        'sectionhidden' => 0,
        'sectionlang' => '',
        'pb' => 0,
        'sectionaccess' => 0,
        'custom_class' => '',
        'template' => '',
    ];

    $a = mb2_shortcode_atts($atts2, $atts);

    $output = '';
    $bgimagestyle = $a['bgimage'] ? ' style="background-image:url(\'' . $a['bgimage'] . '\');"' : '';
    $cls = $a['custom_class'] ? ' ' . $a['custom_class'] : '';
    $cls .= ' pre-bg' . $a['prbg'];
    $cls .= ' hidden' . $a['sectionhidden'];
    $cls .= ' access' . $a['sectionaccess'];
    $cls .= ' ' . $a['scheme'];
    $cls .= $a['template'] ? ' mb2-pb-template-row' : '';

    $langarr = explode(',', $a['sectionlang']);
    $trimmedlangarr = array_map('trim', $langarr);

    $sectionstyle = ' style="';
    $sectionstyle .= 'padding-top:' . $a['pt'] . 'px;';
    $sectionstyle .= 'padding-bottom:' . $a['pb'] . 'px;';
    $sectionstyle .= $a['bgcolor'] ? 'background-color:' . $a['bgcolor'] . ';' : '';
    $sectionstyle .= '"';

    $output .= '<div class="mb2-pb-section mb2-pb-fpsection' . $cls . '"' . $bgimagestyle .
    theme_mb2nl_page_builder_el_datatts($atts, $atts2) . '>';
    $output .= theme_mb2nl_page_builder_el_actions('section', 'section', ['lang' => $trimmedlangarr]);
    $output .= '<div class="section-inner mb2-pb-section-inner"' . $sectionstyle . '>';
    $output .= '<div class="mb2-pb-sortable-rows">';
    $output .= mb2_do_shortcode($content);
    $output .= '</div>'; // ...mb2-pb-sortable-rows

    $output .= '<div class="section-bgel-wrap">';
    $output .= '<div class="section-bgel-wrap2">';
    $output .= '<div class="section-bgel bgel1" style="width:' . $a['bgel1s'] . 'px;top:' . $a['bgel1top'] . 'px;left:' .
    $a['bgel1left'] . '%;"><img src="' . $a['bgel1'] . '" alt=""></div>';
    $output .= '<div class="section-bgel bgel2" style="width:' . $a['bgel2s'] . 'px;top:' . $a['bgel2top'] . 'px;left:' .
    $a['bgel2left'] . '%;"><img src="' . $a['bgel2'] . '" alt=""></div>';
    $output .= '</div>'; // ...section-bgel-wrap2
    $output .= '</div>'; // ...section-bgel-wrap

    $output .= '</div>'; // ...mb2-pb-section-inner
    $output .= '<div class="mb2-pb-addrow">';
    $output .= '<a href="#" class="mb2-pb-row-toggle" data-modal="#mb2-pb-modal-row-layout">&plus; ' .
    get_string('addrow', 'local_mb2builder') . '</a>';
    $output .= '</div>';
    $output .= '</div>';

    return $output;

}
