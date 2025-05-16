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

/**
 *
 * Method to define shortcode
 *
 * @return HTML
 */
function mb2_list_fnc($atts, $content= null) {

    $atts2 = [
        'type' => 1,
        'style' => '',
        'horizontal' => 0,
        'align' => 'none',
        'custom_class' => '',

        'fwcls' => 'global',
        'fsize' => 1,
        'lhcls' => 'global',
        'upper' => 0,

        'color' => '',
        'hcolor' => '',
        'mt' => 0,
        'mb' => 30,

        'gap' => .8,
        'pl' => 0,

        'margin' => '',
    ];

    $a = mb2_shortcode_atts($atts2, $atts);

    $cls = '';
    $styleattr = '';

    // Define list class.
    $cls .= ' horizontal' . $a['horizontal'];
    $cls .= ' list-' . $a['align'];
    $cls .= $a['style'] ? ' list-' . $a['style'] : '';
    $cls .= ' fw' . $a['fwcls'];
    $cls .= ' lh' . $a['lhcls'];
    $cls .= ' upper' . $a['upper'];
    $cls .= $a['custom_class'] ? ' ' . $a['custom_class'] : '';

    $listtag = 'ul';

    if ($a['style'] === 'number') {
        $listtag = 'ol';
    }

    $styleattr .= ' style="';
    $styleattr .= $a['mt'] ? 'margin-top:' . $a['mt'] . 'px;' : '';
    $styleattr .= $a['mb'] ? 'margin-bottom:' . $a['mb'] . 'px;' : '';
    $styleattr .= $a['margin'] ? 'margin:' . $a['margin'] . ';' : '';
    $styleattr .= $a['color'] ? '--mb2-pb-listcolor:' . $a['color'] . ';' : '';
    $styleattr .= $a['hcolor'] ? '--mb2-pb-listhcolor:' . $a['hcolor'] . ';' : '';
    $styleattr .= '--mb2-pb-listgap:' . $a['gap'] .'rem;';
    $styleattr .= '--mb2-pb-listpl:' . $a['pl'] .'rem;';
    $styleattr .= 'font-size:' . $a['fsize'] .'rem;';
    $styleattr .= '"';

    $output = '';
    $output .= '<' . $listtag . ' class="theme-list mb2-pb-list list' . $a['type'] . $cls . '"' . $styleattr . '>';
    $output .= mb2_do_shortcode($content);
    $output .= '</' . $listtag . '>';

    return $output;

}

mb2_add_shortcode('list', 'mb2_list_fnc');






mb2_add_shortcode('list_item', 'mb2_list_item_fnc');

/**
 *
 * Method to define shortcode
 *
 * @return HTML
 */
function mb2_list_item_fnc ($atts, $content= null) {

    $atts2 = [
        'link' => '',
        'link_target' => '',
    ];

    $a = mb2_shortcode_atts($atts2, $atts);

    $cls = '';
    $output = '';

    $target = $a['link_target'] ? ' target="' . $a['link_target'] . '"' : '';

    $output = '';

    $output .= '<li>';
    $output .= $a['link'] != '' ? '<a class="llink" href="' . $a['link'] . '"' . $target . '>' : '';
    $output .= mb2_do_shortcode(theme_mb2nl_format_str($content));
    $output .= $a['link'] != '' ? '</a>' : '';
    $output .= '</li>';

    return $output;

}
