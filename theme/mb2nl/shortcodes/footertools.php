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

mb2_add_shortcode('footertools', 'mb2_shortcode_footertools');

/**
 *
 * Method to define shortcode
 *
 * @return HTML
 */
function mb2_shortcode_footertools($atts, $content = null) {

    global $OUTPUT;

    $atts2 = [
        'id' => 'footertools',
        'mt' => 0,
        'mb' => 0,
        'sizerem' => 1,
        'color' => '',
        'custom_class' => '',
        'template' => '',
    ];

    $a = mb2_shortcode_atts($atts2, $atts);

    $output = '';
    $style = '';
    $cls = '';

    $style .= ' style="';
    $style .= 'margin-top:' .  $a['mt'] . 'px;';
    $style .= 'margin-bottom:' .  $a['mb'] . 'px;';
    $style .= $a['color'] ? 'color:' .  $a['color'] . ';' : '';
    $style .= 'font-size:' .  $a['sizerem'] . 'rem;';
    $style .= '"';

    $cls .= $a['custom_class'] ? ' ' . $a['custom_class'] : '';

    $output .= '<div class="mb2-pb-footertools' . $cls . '"' . $style . '>';
    $output .= $OUTPUT->theme_part('footer_tools');
    $output .= '</div>';

    return $output;

}
