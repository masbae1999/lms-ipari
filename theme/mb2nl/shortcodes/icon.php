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


mb2_add_shortcode('icon', 'mb2_shortcode_icon');
mb2_add_shortcode('icon2', 'mb2_shortcode_icon2');

/**
 *
 * Method to define shortcode
 *
 * @return HTML
 */
function mb2_shortcode_icon($atts, $content= null) {
    $atts2 = [
        'name' => 'fa-star',
        'color' => '',
        'size' => 'default',
        'spin' => 0,
        'rotate' => 0,
        'margin' => '',
        'sizebg' => '',
        'rounded' => '',
        'bgcolor' => '',
        'icon_text_pos' => 'after',
        'custom_class' => '',
        'nline' => 0,
    ];

    $a = mb2_shortcode_atts($atts2, $atts);

    $cls = '';
    $output = '';
    $pref = theme_mb2nl_font_icon_prefix($a['name']);
    $is7stroke = preg_match('@pe-7s@', $a['name']);

    $cls .= $a['spin'] ? $is7stroke ? ' pe-spin' : ' fa-spin' : '';
    $cls .= $a['rotate'] ? $is7stroke ? ' pe-' . $a['rotate'] : ' fa-' . $a['rotate'] : '';
    $cls .= ' ' . $pref . $a['name'];
    $cls .= ' icon-size-' . $a['size'];
    $cls .= $a['custom_class'] ? ' ' . $a['custom_class'] : '';

    // Wrap class.
    $wcls = $a['bgcolor'] != '' ? ' iconbg' : '';
    $wcls .= ' icon-size-' . $a['size'];
    $wcls .= $a['rounded'] == 1 ? ' iconrounded' : '';

    // Wrap style.
    $sstyle = ' style="';
    $sstyle .= $a['nline'] == 1 ? 'display:block;' : '';
    $sstyle .= $a['margin'] != '' ? 'margin:' . $a['margin'] . ';' : '';
    $sstyle .= '"';

    // Set icon style.
    $style = ' style="';
    $style .= $a['color'] != '' ? 'color:' . $a['color'] . ';' : '';
    $style .= '"';

    // Wrap style.
    $wstyle = ' style="';
    $wstyle .= $a['sizebg'] > 0 ? 'width:' . $a['sizebg'] . 'px;text-align:center;height:' .
    $a['sizebg'] . 'px;line-height:' . $a['sizebg'] . 'px;' : '';
    $wstyle .= $a['bgcolor'] != '' ? 'background-color:' . $a['bgcolor'] . ';' : '';
    $wstyle .= '"';

    $iscontent = $content ? ' <span class="tmpl-icon-content">' . mb2_do_shortcode($content) . '</span>' : '';

    $output .= '<span class="tmpl-icon-wrap' . $wcls . '"' . $sstyle . '>';
    $output .= $a['icon_text_pos'] === 'before' ? $iscontent : '';
    $output .= $a['bgcolor'] ? '<span class="tmpl-icon-bg"' . $wstyle . '>' : '';
    $output .= '<i class="tmpl-icon' . $cls . '"' . $style . '></i>';
    $output .= $a['bgcolor'] ? '</span>' : '';
    $output .= $a['icon_text_pos'] === 'after' ? $iscontent : '';
    $output .= '</span>';

    return $output;

}



/**
 *
 * Method to define shortcode
 *
 * @return HTML
 */
function mb2_shortcode_icon2($atts, $content= null) {
    $atts2 = [
        'name' => 'fa fa-star',
        'color' => '',
        'size' => 'n',
        'circle' => 1,
        'desc' => 0,
        'spin' => 0,
        'rotate' => 0,
        'mt' => 0,
        'mb' => 30,
        'sizebg' => '',
        'rounded' => '',
        'bgcolor' => '',
        'custom_class' => '',
    ];

    $a = mb2_shortcode_atts($atts2, $atts);

    $output = '';
    $cls = '';
    $style = '';
    $estyle = '';

    $cls .= ' size' . $a['size'];
    $cls .= ' desc' . $a['desc'];
    $cls .= ' circle' . $a['circle'];

    if ($a['mt'] || $a['mb']) {
        $style .= ' style="';
        $style .= $a['mt'] ? 'margin-top:' . $a['mt'] . 'px;' : '';
        $style .= $a['mb'] ? 'margin-bottom:' . $a['mb'] . 'px;' : '';
        $style .= '"';
    }

    // Define icon text.
    $content = $content ? $content : 'Icon text here.';
    $atts2['text'] = $content;

    if ($a['color'] || $a['bgcolor']) {
        $estyle .= ' style="';
        $estyle .= $a['color'] ? 'color:' . $a['color'] . ';' : '';
        $estyle .= $a['bgcolor'] ? 'background-color:' . $a['bgcolor'] . ';' : '';
        $estyle .= '"';
    }

    $output .= '<div class="theme-icon2' . $cls . '"' . $style . '>';
    $output .= '<span class="icon-bg"' . $estyle . '>';
    $output .= '<i class="' . $a['name'] . '"></i>';
    $output .= '</span>';
    $output .= '<span class="icon-desc">';
    $output .= mb2_do_shortcode($content);
    $output .= '</span>';
    $output .= '</div>';

    return $output;

}
