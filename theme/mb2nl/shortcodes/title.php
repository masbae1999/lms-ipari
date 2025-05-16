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

mb2_add_shortcode('title', 'mb2_shortcode_title');

/**
 *
 * Method to define shortcode
 *
 * @return HTML
 */
function mb2_shortcode_title ($atts, $content= null) {

    $atts2 = [
        'tag' => 'h4',
        'align' => 'none',
        'issubtext' => 0,
        'subtext' => '',
        'size' => 'n',
        'sizerem' => 2,
        'color' => '',
        'fwcls' => 'global',
        'lhcls' => 'global',
        'lspacing' => 0,
        'wspacing' => 0,
        'upper' => 0,
        'style' => 1,
        'mt' => 0,
        'mb' => 30,
        'custom_class' => '',
    ];

    $a = mb2_shortcode_atts($atts2, $atts);

    $output = '';
    $cstyle = '';
    $tstyle = '';
    $cls = '';
    $tcls = '';

    $cls .= $a['custom_class'] ? ' ' . $a['custom_class'] : '';
    $cls .= ' title-' . $a['align'];
    $cls .= ' title-' . $a['size'];
    $cls .= ' style-' . $a['style'];

    $tcls .= ' upper' . $a['upper'];
    $tcls .= ' fw' . $a['fwcls'];
    $tcls .= ' lh' . $a['lhcls'];
    $tcls .= ' ' . theme_mb2nl_tsize_cls($a['sizerem']);

    if ($a['mt'] || $a['mb']) {
        $cstyle .= ' style="';
        $cstyle .= $a['mt'] ? 'margin-top:' . $a['mt'] . 'px;' : '';
        $cstyle .= $a['mb'] ? 'margin-bottom:' . $a['mb'] . 'px;' : '';
        $cstyle .= '"';
    }

    if ($a['sizerem'] || $a['color'] || $a['lspacing'] != 0 || $a['wspacing'] != 0) {
        $tstyle .= ' style="';
        $tstyle .= 'font-size:' . $a['sizerem'] . 'rem;';
        $tstyle .= $a['lspacing'] != 0 ? 'letter-spacing:' . $a['lspacing'] . 'px;' : '';
        $tstyle .= $a['wspacing'] != 0 ? 'word-spacing:' . $a['wspacing'] . 'px;' : '';
        $tstyle .= $a['color'] ? 'color:' . $a['color'] . ';' : '';
        $tstyle .= '"';
    }

    $output .= '<div class="theme-title' . $cls . '"' . $cstyle . '>';
    $output .= '<' . $a['tag'] . ' class="title' . $tcls . '"' . $tstyle . '><span>';
    $output .= theme_mb2nl_format_str($content);
    $output .= '</span></' . $a['tag'] . '>';
    $output .= ($a['issubtext'] && $a['subtext']) ? '<span class="title-subtext">' .
    theme_mb2nl_format_str($a['subtext']) . '</span>' : '';
    $output .= '</div>';

    return $output;

}
