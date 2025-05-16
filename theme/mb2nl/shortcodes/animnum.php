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

mb2_add_shortcode('animnum', 'mb2_shortcode_animnum');
mb2_add_shortcode('animnum_item', 'mb2_shortcode_animnum_item');

/**
 *
 * Method to define animated number shortcode
 *
 * @return HTML
 */
function mb2_shortcode_animnum ($atts, $content=null) {

    global $PAGE,
    $gl0sizenumber,
    $gl0coloricon,
    $gl0colornumber,
    $gl0colortitle,
    $gl0colorsubtitle,
    $gl0colorbg,
    $gl0animnumicon,
    $gl0animnumsubtitle,
    $gl0sizetitle,
    $gl0nfwcls,
    $gl0tlhcls,
    $gl0tfwcls,
    $gl0nfstyle,
    $gl0nmt,
    $gl0nmb;

    $atts2 = [
        'columns' => 4, // ...max 5
        'mt' => 0,
        'mb' => 0, // ...0 because box item has margin bottom 30 pixels
        'icon' => 0,
        'subtitle' => 0,
        'gutter' => 'normal',
        'aspeed' => 10000,
        'size_number' => 3,
        'size_title' => 1.4,
        'nmt' => 0,
        'nmb' => 8,
        'nfstyle' => 'normal',
        'nfwcls' => 'global',
        'tfwcls' => 'global',
        'tlhcls' => 'global',
        'nopadding' => 0,
        'center' => 1,
        'size_icon' => 3,
        'color_icon' => '',
        'color_number' => '',
        'color_title' => '',
        'color_subtitle' => '',
        'color_bg' => '',
        'custom_class' => '',
    ];

    $a = mb2_shortcode_atts($atts2, $atts);

    $output = '';
    $cls = '';
    $style = '';

    $gl0sizenumber = $a['size_number'];
    $gl0coloricon = $a['color_icon'];
    $gl0colornumber = $a['color_number'];
    $gl0colortitle = $a['color_title'];
    $gl0colorsubtitle = $a['color_subtitle'];
    $gl0colorbg = $a['color_bg'];
    $gl0animnumicon = $a['icon'];
    $gl0animnumsubtitle = $a['subtitle'];
    $gl0sizetitle = $a['size_title'];
    $gl0nfwcls = $a['nfwcls'];
    $gl0tlhcls = $a['tlhcls'];
    $gl0tfwcls = $a['tfwcls'];
    $gl0nfstyle = $a['nfstyle'];
    $gl0nmt = $a['nmt'];
    $gl0nmb = $a['nmb'];

    $cls .= ' gutter-' . $a['gutter'];
    $cls .= ' theme-col-' . $a['columns'];
    $cls .= ' center' . $a['center'];
    $cls .= ' nopadding' . $a['nopadding'];
    $cls .= $a['custom_class'] ? ' ' . $a['custom_class'] : '';

    $style .= ' style="';
    $style .= $a['mt'] ? 'margin-top:' . $a['mt'] . 'px;' : '';
    $style .= $a['mb'] ? 'margin-bottom:' . $a['mb'] . 'px;' : '';
    $style .= '--mb2-b-anum-sizei:' . $a['size_icon'] . 'rem';
    $style .= '"';

    $output .= '<div class="mb2-pb-animnum theme-boxes' . $cls . ' clearfix" data-aspeed="' . $a['aspeed'] . '"' . $style . '>';
    $output .= mb2_do_shortcode($content);
    $output .= '</div>';

    return $output;

}




/**
 *
 * Method to define animated number item shortcode
 *
 * @return HTML
 */
function mb2_shortcode_animnum_item ($atts, $content = null) {

    global $PAGE,
    $gl0sizenumber,
    $gl0coloricon,
    $gl0colornumber,
    $gl0colortitle,
    $gl0colorsubtitle,
    $gl0colorbg,
    $gl0animnumicon,
    $gl0animnumsubtitle,
    $gl0sizetitle,
    $gl0nfwcls,
    $gl0tlhcls,
    $gl0tfwcls,
    $gl0nfstyle,
    $gl0nmt,
    $gl0nmb;

    $atts2 = [
        'number' => 0,
        'icon' => 'fa fa-graduation-cap',
        'title' => '',
        'color_icon' => '',
        'color_number' => '',
        'color_title' => '',
        'color_subtitle' => '',
        'color_bg' => '',
        'subtitle' => '',
    ];

    $a = mb2_shortcode_atts($atts2, $atts);

    $output = '';
    $conpref = theme_mb2nl_font_icon_prefix($a['icon']);
    $a['size_number'] = isset($gl0sizenumber) ? $gl0sizenumber : 3;
    $coloriconstyle = '';
    $numberstyle = '';
    $colortitlestyle = '';
    $colorsubtitlestyle = '';
    $colorbgstyle = '';
    $tcls = '';
    $ncls = '';

    $ncls .= ' fw' . $gl0nfwcls;
    $ncls .= ' fs' . $gl0nfstyle;
    $tcls .= ' fw' . $gl0tfwcls;
    $tcls .= ' lh' . $gl0tlhcls;

    if ($a['color_icon'] || $gl0coloricon) {
        $color = $a['color_icon'] ? $a['color_icon'] : $gl0coloricon;
        $coloriconstyle = 'color:' . $color . ';';
    }

    $numberstyle .= ' style="';
    $numberstyle .= 'font-size:' . $a['size_number'] . 'rem;';
    $numcolor = $a['color_number'] ? $a['color_number'] : $gl0colornumber;
    $numberstyle .= $numcolor ? 'color:' . $numcolor . ';' : '';
    $numberstyle .= 'margin-top:' . $gl0nmt . 'px;';
    $numberstyle .= 'margin-bottom:' . $gl0nmb . 'px;';
    $numberstyle .= '"';

    if ($a['color_title'] || $gl0colortitle || $gl0sizetitle) {
        $color = $a['color_title'] ? $a['color_title'] : $gl0colortitle;
        $colortitlestyle .= ' style="';
        $colortitlestyle .= 'color:' . $color . ';';
        $colortitlestyle .= 'font-size:' . $gl0sizetitle . 'rem;';
        $colortitlestyle .= '"';
    }

    if ($a['color_subtitle'] || $gl0colorsubtitle) {
        $color = $a['color_subtitle'] ? $a['color_subtitle'] : $gl0colorsubtitle;
        $colorsubtitlestyle = ' style="color:' . $color . ';"';
    }

    if ($a['color_bg'] || $gl0colorbg) {
        $color = $a['color_bg'] ? $a['color_bg'] : $gl0colorbg;

        $colorbgstyle .= ' style="';
        $colorbgstyle .= $color ? 'background-color:' . $color . ';' : '';
        $colorbgstyle .= '"';
    }

    $output .= '<div class="theme-box">';
    $output .= '<div class="pbanimnum-item"' . $colorbgstyle . ' data-number="' . $a['number'] . '">';

    if ($gl0animnumicon) {
        $output .= '<div class="pbanimnum-icon" style="' . $coloriconstyle . '"><i class="' .
        $conpref . $a['icon'] . '"></i></div>';
    }

    $output .= '<div class="pbanimnum-number' . $ncls . '"' . $numberstyle . ' aria-hidden="true">0</div>';
    $output .= '<span class="sr-only">' . $a['number'] . '</span>';

    $output .= '<div class="pbanimnum-text">';
    $output .= $a['title'] ? '<h4 class="pbanimnum-title' . $tcls . '"' . $colortitlestyle . '>' .
    theme_mb2nl_format_str($a['title']) . '</h4>' : '';
    $output .= $gl0animnumsubtitle ? '<span class="pbanimnum-subtitle"' . $colorsubtitlestyle . '>' .
    theme_mb2nl_format_str($a['subtitle']) . '</span>' : '';
    $output .= '</div>';

    $output .= '</div>';
    $output .= '</div>';

    return $output;

}
