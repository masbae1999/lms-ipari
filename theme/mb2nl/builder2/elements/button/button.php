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

mb2_add_shortcode('mb2pb_button', 'mb2_shortcode_mb2pb_button');

/**
 *
 * Method to define shortcode
 *
 * @return HTML
 */
function mb2_shortcode_mb2pb_button($atts, $content=null) {

    $atts2 = [
        'id' => 'button',
        'type' => 'primary',
        'size' => 'normal',
        'link' => '#',
        'target' => 0,
        'isicon' => 0,
        'icon' => 'fa fa-play-circle-o',
        'fw' => 0,
        'fwcls' => 'global',
        'lspacing' => 0,
        'wspacing' => 0,
        'rounded' => 0,
        'upper' => 0,
        'custom_class' => '',
        'ml' => 0,
        'mr' => 0,
        'mt' => 0,
        'mb' => 15,
        'width' => 0,
        'border' => 0,
        'center' => 0,

        'iafter' => 0,

        'color' => '',
        'bgcolor' => '',
        'borcolor' => '',
        'bghcolor' => '',
        'hcolor' => '',
        'borhcolor' => '',

        'template' => '',
    ];

    $a = mb2_shortcode_atts($atts2, $atts);

    $output = '';
    $cls = '';
    $style = '';
    $elcls = '';
    $btnstyle = '';

    // Button icon.
    $btnicon = '<span class="btn-icon"><i class="' . $a['icon'] . '"></i></span>';

    // Define button css class.
    $cls .= ' type' . $a['type'];
    $cls .= ' size' . $a['size'];
    $cls .= ' upper' . $a['upper'];
    $cls .= ' rounded' . $a['rounded'];
    $cls .= ' btnborder' . $a['border'];
    $cls .= ' isicon' . $a['isicon'];
    $cls .= ' fw' . $a['fw'];
    $cls .= ' fw' . $a['fwcls'];
    $cls .= ' iafter' . $a['iafter'];
    $cls .= $a['custom_class'] ? ' ' . $a['custom_class'] : '';

    $elcls .= ' fw' . $a['fw'];
    $elcls .= ' center' . $a['center'];
    $elcls .= $a['template'] ? ' mb2-pb-template-button' : '';

    $content = $content ? $content : get_string('readmorefp', 'local_mb2builder');
    $atts2['text'] = $content;
    $btntext = '<span class="btn-intext">' . urldecode($content) . '</span>';

    // Button style.
    if ($a['ml'] || $a['mr'] || $a['mt'] || $a['mb']) {
        $style .= ' style="';
        $style .= $a['mt'] ? 'margin-top:' . $a['mt'] . 'px;' : '';
        $style .= $a['mb'] ? 'margin-bottom:' . $a['mb'] . 'px;' : '';
        $style .= $a['ml'] ? 'margin-left:' . $a['ml'] . 'px;' : '';
        $style .= $a['mr'] ? 'margin-right:' . $a['mr'] . 'px;' : '';
        $style .= '"';
    }

    // Button style.
    if (
        $a['lspacing'] != 0 ||
        $a['wspacing'] != 0 ||
        $a['color'] ||
        $a['bgcolor'] ||
        $a['borcolor'] ||
        $a['bghcolor'] ||
        $a['hcolor'] ||
        $a['borhcolor'] ||
        $a['width']) {

        $btnstyle .= ' style="';
        $btnstyle .= $a['lspacing'] != 0 ? 'letter-spacing:' . $a['lspacing'] . 'px;' : '';
        $btnstyle .= $a['wspacing'] != 0 ? 'word-spacing:' . $a['wspacing'] . 'px;' : '';
        $btnstyle .= $a['color'] ? '--mb2-pb-btn-color:' . $a['color'] . ';' : '';
        $btnstyle .= $a['bgcolor'] ? '--mb2-pb-btn-bgcolor:' . $a['bgcolor'] . ';' : '';
        $btnstyle .= $a['bghcolor'] ? '--mb2-pb-btn-bghcolor:' . $a['bghcolor'] . ';' : '';
        $btnstyle .= $a['hcolor'] ? '--mb2-pb-btn-hcolor:' . $a['hcolor'] . ';' : '';
        $btnstyle .= $a['borcolor'] ? '--mb2-pb-btn-borcolor:' . $a['borcolor'] . ';' : '';
        $btnstyle .= $a['borhcolor'] ? '--mb2-pb-btn-borhcolor:' . $a['borhcolor'] . ';' : '';
        $btnstyle .= $a['width'] ? 'min-width:' . $a['width'] . 'px;' : '';
        $btnstyle .= '"';

    }

    $output .= '<div class="mb2-pb-element mb2-pb-button' . $elcls . '"' . $style .
    theme_mb2nl_page_builder_el_datatts($atts, $atts2) . '>';
    $output .= '<div class="element-helper"></div>';
    $output .= theme_mb2nl_page_builder_el_actions('element', 'button');
    $output .= '<a href="#" class="mb2-pb-btn' . $cls . '"' . $btnstyle . '>';
    $output .= $btnicon . $btntext;
    $output .= '</a>';
    $output .= '</div>';

    return $output;

}
