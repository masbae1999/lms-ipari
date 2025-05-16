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

mb2_add_shortcode('button', 'mb2_shortcode_button');

/**
 *
 * Method to define shortcode
 *
 * @return HTML
 */
function mb2_shortcode_button($atts, $content= null) {

    $atts2 = [
        'type' => 'default',
        'size' => '',
        'link' => '#',
        'target' => 0,
        'isicon' => 0,
        'icon' => 'fa fa-play-circle-o',
        'upper' => 0,
        'fw' => 0,
        'fwcls' => 'medium',
        'lspacing' => 0,
        'wspacing' => 0,
        'rounded' => 0,
        'custom_class' => '',
        'ml' => 0,
        'mr' => 0,
        'mt' => 0,
        'mb' => 15,
        'width' => 0,
        'border' => 0,
        'margin' => '',
        'attribute' => '',
        'center' => 0,

        'iafter' => 0,

        'color' => '',
        'bgcolor' => '',
        'borcolor' => '',
        'bghcolor' => '',
        'hcolor' => '',
        'borhcolor' => '',
    ];

    $a = mb2_shortcode_atts($atts2, $atts);

    $output = '';
    $style = '';
    $btncls = '';

    $iconpref = theme_mb2nl_font_icon_prefix($a['icon']);
    $istarget = $a['target'] ? ' target="_blank"' : '';
    $dirmarginright = theme_mb2nl_isrtl() ? 'left' : 'right';
    $dirmarginleft = theme_mb2nl_isrtl() ? 'right' : 'left';

    // Define some button parameters.
    $iconname = $a['icon'];

    // Button icon and text.
    $btnicon = $a['isicon'] ? '<span class="btn-icon" aria-hidden="true"><i class="' .
    $iconpref . $iconname . '"></i></span>' : '';
    $btntext = '<span class="btn-intext">' . theme_mb2nl_format_str($content) . '</span>';

    // Define button css class.
    $btncls .= ' type' . $a['type'];
    $btncls .= ' size' . $a['size'];
    $btncls .= ' rounded' . $a['rounded'];
    $btncls .= ' btnborder' . $a['border'];
    $btncls .= ' isicon' . $a['isicon'];
    $btncls .= ' upper' . $a['upper'];
    $btncls .= ' fw' . $a['fwcls'];
    $btncls .= ' iafter' . $a['iafter'];
    $btncls .= $a['custom_class'] ? ' ' . $a['custom_class'] : '';

    // Button style.
    if (
        $a['ml'] ||
        $a['mr'] ||
        $a['mt'] ||
        $a['mb'] ||
        $a['lspacing'] != 0 ||
        $a['wspacing'] != 0 ||
        $a['margin'] ||
        $a['color'] ||
        $a['bgcolor'] ||
        $a['bghcolor'] ||
        $a['hcolor'] ||
        $a['borcolor'] ||
        $a['borhcolor'] ||
        $a['width']
    ) {
        $style .= ' style="';
        $style .= $a['margin'] ? 'margin:' . $a['margin'] . ';' : '';
        $style .= $a['mt'] ? 'margin-top:' . $a['mt'] . 'px;' : '';
        $style .= $a['mb'] ? 'margin-bottom:' . $a['mb'] . 'px;' : '';
        $style .= $a['ml'] ? 'margin-' . $dirmarginleft . ':' . $a['ml'] . 'px;' : '';
        $style .= $a['mr'] ? 'margin-' . $dirmarginright . ':' . $a['mr'] . 'px;' : '';
        $style .= $a['lspacing'] != 0 ? 'letter-spacing:' . $a['lspacing'] . 'px;' : '';
        $style .= $a['wspacing'] != 0 ? 'word-spacing:' . $a['wspacing'] . 'px;' : '';

        $style .= $a['color'] ? '--mb2-pb-btn-color:' . $a['color'] . ';' : '';
        $style .= $a['bgcolor'] ? '--mb2-pb-btn-bgcolor:' . $a['bgcolor'] . ';' : '';
        $style .= $a['bghcolor'] ? '--mb2-pb-btn-bghcolor:' . $a['bghcolor'] . ';' : '';
        $style .= $a['hcolor'] ? '--mb2-pb-btn-hcolor:' . $a['hcolor'] . ';' : '';
        $style .= $a['borcolor'] ? '--mb2-pb-btn-borcolor:' . $a['borcolor'] . ';' : '';
        $style .= $a['borhcolor'] ? '--mb2-pb-btn-borhcolor:' . $a['borhcolor'] . ';' : '';
        $style .= $a['width'] ? 'min-width:' . $a['width'] . 'px;' : '';

        $style .= '"';
    }

    $output .= ($a['center'] && ! $a['fw']) ? '<div style="text-align:center;" class="clearfix">' : '';
    $output .= '<a href="' . $a['link'] . '"' . $istarget . ' class="mb2-pb-btn' . $btncls . '"' . $style . '>';
    $output .= $btnicon . $btntext;
    $output .= '</a>';
    $output .= ($a['center'] && ! $a['fw']) ? '</div>' : '';

    return $output;

}
