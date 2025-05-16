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

mb2_add_shortcode('text', 'mb2_shortcode_text');

/**
 *
 * Method to define shortcode
 *
 * @return HTML
 */
function mb2_shortcode_text ($atts, $content = null) {

    $atts2 = [
        'align' => 'none',
        'size' => 'n',
        'sizerem' => 1,

        'fwcls' => 'global',
        'lhcls' => 'global',
        'lspacing' => 0,
        'wspacing' => 0,

        'tupper' => 0,

        'tfwcls' => 'global',
        'tlhcls' => 'global',
        'tlspacing' => 0,
        'twspacing' => 0,
        'tsizerem' => 1.4,
        'tcolor' => '',

        'color' => '',
        'showtitle' => 0,
        'upper' => 0,

        'title' => '',
        'width' => 2000,
        'rounded' => 0,
        'mt' => 0,
        'mb' => 30,
        'pv' => 0,
        'ph' => 0,
        'tmb' => 30,
        'gradient' => 0,

        'button' => 0,
        'btype' => 'primary',
        'bsize' => 'normal',
        'link' => '#',
        'target' => 0,
        'brounded' => 0,
        'bmt' => 0,
        'bborder' => 0,
        'btext' => "Read more",
        'bfwcls' => 'global',

        'bgcolor' => '',
        'scheme' => 'light',
        'custom_class' => '',
    ];

    $a = mb2_shortcode_atts($atts2, $atts);

    $output = '';
    $style = '';
    $typostyle = '';
    $fontcls = '';
    $cls = '';
    $typosttyle = '';
    $titlecls = '';
    $btncls = '';
    $styleinner = '';

    $linktarget = $a['target'] ? ' target="_blank"' : '';

    $cls .= $a['custom_class'] ? ' ' . $a['custom_class'] : '';
    $cls .= ' align-' . $a['align'];
    $cls .= ' text-' . $a['color'];
    $cls .= ' rounded' . $a['rounded'];
    $cls .= ' gradient' . $a['gradient'];
    $cls .= ' ' . $a['scheme'];

    $fontcls .= ' ' . theme_mb2nl_tsize_cls($a['sizerem']);
    $fontcls .= ' upper' . $a['upper'];
    $fontcls .= ' fw' . $a['fwcls'];
    $fontcls .= ' lh' . $a['lhcls'];

    $titlecls .= ' ' . theme_mb2nl_tsize_cls($a['sizerem']);
    $titlecls .= ' upper' . $a['tupper'];
    $titlecls .= ' fw' . $a['tfwcls'];
    $titlecls .= ' lh' . $a['tlhcls'];

    // Text container style.
    $style .= ' style="';
    $style .= $a['mt'] ? 'margin-top:' . $a['mt'] . 'px;' : '';
    $style .= $a['mb'] ? 'margin-bottom:' . $a['mb'] . 'px;' : '';
    $style .= 'max-width:' . $a['width'] . 'px;margin-left:auto;margin-right:auto;';
    $style .= '"';

    $styleinner .= ' style="';
    $styleinner .= $a['pv'] ? 'padding-top:' . $a['pv'] . 'px;' : '';
    $styleinner .= $a['pv'] ? 'padding-bottom:' . $a['pv'] . 'px;' : '';
    $styleinner .= $a['ph'] ? 'padding-left:' . $a['ph'] . 'px;' : '';
    $styleinner .= $a['ph'] ? 'padding-right:' . $a['ph'] . 'px;' : '';
    $styleinner .= $a['bgcolor'] ? 'background-color:' . $a['bgcolor'] . ';' : '';
    $styleinner .= '"';

    $btncls .= ' type' . $a['btype'];
    $btncls .= ' size' . $a['bsize'];
    $btncls .= ' rounded' . $a['brounded'];
    $btncls .= ' btnborder' . $a['bborder'];
    $btncls .= ' fw' . $a['bfwcls'];

    $typostyle .= ' style="';
    $typostyle .= $a['color'] ? 'color:' . $a['color'] . ';' : '';
    $typostyle .= $a['lspacing'] != 0 ? 'letter-spacing:' . $a['lspacing'] . 'px;' : '';
    $typostyle .= $a['wspacing'] != 0 ? 'word-spacing:' . $a['wspacing'] . 'px;' : '';
    $typostyle .= $a['sizerem'] ? 'font-size:' . $a['sizerem'] . 'rem;' : '';
    $typostyle .= '"';

    $typosttyle .= ' style="';
    $typosttyle .= $a['tcolor'] ? 'color:' . $a['tcolor'] . ';' : '';
    $typosttyle .= 'letter-spacing:' . $a['tlspacing'] . 'px;';
    $typosttyle .= 'word-spacing:' . $a['twspacing'] . 'px;';
    $typosttyle .= 'font-size:' . $a['tsizerem'] . 'rem;';
    $typosttyle .= 'margin-bottom:' . $a['tmb'] . 'px;';
    $typosttyle .= '"';

    $output .= '<div class="theme-text"' . $style . '>';
    $output .= '<div class="theme-text-inner' . $cls . '"' . $styleinner . '>';
    $output .= ($a['showtitle'] && $a['title']) ? '<h4 class="theme-text-title' . $titlecls . '"' . $typosttyle . '>' .
    theme_mb2nl_format_str($a['title']) . '</h4>' : '';
    $output .= '<div class="theme-text-text' . $fontcls . '"' . $typostyle . '>';
    $output .= theme_mb2nl_format_txt($content, FORMAT_HTML);
    $output .= '</div>';

    if ($a['button']) {
        $output .= '<div class="theme-text-button" style="padding-top:' . $a['bmt'] . 'px;">';
        $output .= '<a href="' . $a['link'] . '" class="mb2-pb-btn' . $btncls . '"' .
        $linktarget . '>' . $a['btext'] . '</a>';
        $output .= '</div>';
    }

    $output .= '</div>';
    $output .= '</div>';

    return $output;

}
