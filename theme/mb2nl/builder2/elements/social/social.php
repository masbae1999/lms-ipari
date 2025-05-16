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

mb2_add_shortcode('mb2pb_social', 'mb2_shortcode_mb2pb_social');

/**
 *
 * Method to define shortcode
 *
 * @return HTML
 */
function mb2_shortcode_mb2pb_social($atts, $content = null) {
    global $OUTPUT, $PAGE;

    $atts2 = [
        'id' => 'social',
        'type' => 1,
        'mt' => 0,
        'mb' => 30,
        'size' => 'normal',
        'space' => 6,
        'rounded' => 'normal',

        'color' => '',
        'bgcolor' => '',
        'borcolor' => '',
        'hcolor' => '',
        'hbgcolor' => '',
        'hborcolor' => '',

        'custom_class' => '',
        'template' => '',
    ];

    $a = mb2_shortcode_atts($atts2, $atts);

    $output = '';
    $elstyle = '';
    $cls = '';

    $cls .= ' size' . $a['size'];
    $cls .= ' rounded' . $a['rounded'];

    $elstyle .= ' style="';
    $elstyle .= 'margin-top:' .  $a['mt'] . 'px;';
    $elstyle .= 'margin-bottom:' .  $a['mb'] . 'px;';
    $elstyle .= '--mb2-social-space:' .  $a['space'] . 'px;';
    $elstyle .= $a['color'] ? '--mb2-social-color:' .  $a['color'] . ';' : '';
    $elstyle .= $a['bgcolor'] ? '--mb2-social-bgcolor:' .  $a['bgcolor'] . ';' : '';
    $elstyle .= $a['borcolor'] ? '--mb2-social-borcolor:' .  $a['borcolor'] . ';' : '';
    $elstyle .= $a['hcolor'] ? '--mb2-social-hcolor:' .  $a['hcolor'] . ';' : '';
    $elstyle .= $a['hbgcolor'] ? '--mb2-social-hbgcolor:' .  $a['hbgcolor'] . ';' : '';
    $elstyle .= $a['hborcolor'] ? '--mb2-social-hborcolor:' .  $a['hborcolor'] . ';' : '';
    $elstyle .= '"';

    $socialtt = theme_mb2nl_theme_setting($PAGE, 'socialtt') == 1 ? 'top' : '';

    $cls .= ' type' . $a['type'];
    $cls .= $a['custom_class'] ? ' ' . $a['custom_class'] : '';

    $tmplcls = $a['template'] ? ' mb2-pb-template-' . $a['id'] : '';

    $output .= '<div class="mb2-pb-element mb2-pb-social' . $tmplcls . $cls . '"' .
    theme_mb2nl_page_builder_el_datatts($atts, $atts2) . $elstyle . '>';
    $output .= '<div class="element-helper"></div>';
    $output .= theme_mb2nl_page_builder_el_actions('element', 'social', ['copy' => 0]);
    $output .= theme_mb2nl_social_icons(['pos' => 'footer', 'tt' => $socialtt]);
    $output .= '</div>';

    return $output;

}
