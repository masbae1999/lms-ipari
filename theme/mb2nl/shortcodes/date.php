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

mb2_add_shortcode('date', 'mb2_shortcode_date');
mb2_add_shortcode('year', 'mb2_shortcode_year');

/**
 *
 * Method to define shortcode
 *
 * @return HTML
 */
function mb2_shortcode_date($atts, $content = null) {

    $atts2 = [
        'textbefore' => '&copy; 2017 - ',
        'textafter' => ' New Learning Theme. All rights reserved.',
        'mt' => 0,
        'mb' => 30,
        'sizerem' => 1,
        'color' => '',
        'custom_class' => '',
    ];

    $a = mb2_shortcode_atts($atts2, $atts);

    $output = '';
    $elstyle = '';
    $cls = '';

    $elstyle .= ' style="';
    $elstyle .= 'margin-top:' .  $a['mt'] . 'px;';
    $elstyle .= 'margin-bottom:' .  $a['mb'] . 'px;';
    $elstyle .= $a['color'] ? 'color:' .  $a['color'] . ';' : '';
    $elstyle .= 'font-size:' .  $a['sizerem'] . 'rem;';
    $elstyle .= '"';

    $cls .= $a['custom_class'] ? ' ' . $a['custom_class'] : '';

    $output .= '<div class="mb2-pb-date' . $cls . '"' . $elstyle . '>';
    $output .= '<span class="before">' . $a['textbefore'] . '</span>';
    $output .= date('Y');
    $output .= '<span class="after">' . $a['textafter'] . '</span>';
    $output .= '</div>';

    return $output;

}


/**
 *
 * Method to define shortcode
 *
 * @return HTML
 */
function mb2_shortcode_year($atts, $content = null) {

    $atts2 = [];

    $a = mb2_shortcode_atts($atts2, $atts);

    return '<span class="cyear">' . date('Y') . '</span>';

}
