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

mb2_add_shortcode('mb2pb_animnum', 'mb2_shortcode_mb2pb_animnum');
mb2_add_shortcode('mb2pb_animnum_item', 'mb2_shortcode_mb2pb_animnum_item');

/**
 *
 * Method to define animated number shortcode
 *
 * @return HTML
 */
function mb2_shortcode_mb2pb_animnum ($atts, $content=null) {

    global $PAGE,
    $gl0sizenumber,
    $gl0sizetitle,
    $gl0coloricon,
    $gl0colornumber,
    $gl0colortitle,
    $gl0colorsubtitle,
    $gl0colorbg,
    $gl0nfwcls,
    $gl0tlhcls,
    $gl0tfwcls,
    $gl0height,
    $gl0nfstyle,
    $gl0nmt,
    $gl0nmb;

    $atts2 = [
        'id' => 'animnum',
        'columns' => 4, // ...max 5
        'mt' => 0,
        'mb' => 0, // ...0 because box item has margin bottom 30 pixels
        'gutter' => 'normal',
        'icon' => 0,
        'center' => 1,
        'size_number' => 3,
        'size_icon' => 3,
        'size_title' => 1.4,
        'color_icon' => '',
        'color_number' => '',
        'nmt' => 0,
        'nmb' => 8,
        'nfstyle' => 'normal',
        'nfwcls' => 'global',
        'tfwcls' => 'global',
        'tlhcls' => 'global',
        'color_title' => '',
        'color_subtitle' => '',
        'color_bg' => '',
        'subtitle' => 0,
        'nopadding' => 0,
        'custom_class' => '',
        'aspeed' => 10000,
        'height' => 0,
        'template' => '',
    ];

    $a = mb2_shortcode_atts($atts2, $atts);

    $output = '';
    $cls = '';
    $style = '';
    $gl0sizenumber = $a['size_number'];
    $gl0sizetitle = $a['size_title'];
    $gl0coloricon = $a['color_icon'];
    $gl0colornumber = $a['color_number'];
    $gl0colortitle = $a['color_title'];
    $gl0colorsubtitle = $a['color_subtitle'];
    $gl0colorbg = $a['color_bg'];
    $gl0nfwcls = $a['nfwcls'];
    $gl0tlhcls = $a['tlhcls'];
    $gl0tfwcls = $a['tfwcls'];
    $gl0height = $a['height'];
    $gl0nfstyle = $a['nfstyle'];
    $gl0nmt = $a['nmt'];
    $gl0nmb = $a['nmb'];

    $cls .= ' gutter-' . $a['gutter'];
    $cls .= ' subtitle' . $a['subtitle'];
    $cls .= ' theme-col-' . $a['columns'];
    $cls .= ' center' . $a['center'];
    $cls .= ' nopadding' . $a['nopadding'];
    $cls .= ' icon' . $a['icon'];
    $cls .= $a['custom_class'] ? ' ' . $a['custom_class'] : '';
    $templatecls = $a['template'] ? ' mb2-pb-template-animnum' : '';

    $style .= ' style="';
    $style .= $a['mt'] ? 'margin-top:' . $a['mt'] . 'px;' : '';
    $style .= $a['mb'] ? 'margin-bottom:' . $a['mb'] . 'px;' : '';
    $style .= '--mb2-b-anum-sizei:' . $a['size_icon'] . 'rem';
    $style .= '"';

    $content = $content;

    if (! $content) {
        for ($i = 1; $i <= 4; $i++) {
            $content .= '[mb2pb_animnum_item number="125" ]Box content here.[/mb2pb_animnum_item]';
        }
    }

    $output .= '<div class="mb2-pb-element mb2-pb-animnum' . $templatecls . '"' . $style .
    theme_mb2nl_page_builder_el_datatts($atts, $atts2)  . '>';
    $output .= '<div class="element-helper"></div>';
    $output .= theme_mb2nl_page_builder_el_actions('element', 'animnum');
    $output .= '<div class="mb2-pb-element-inner theme-boxes' . $cls . '">';
    $output .= '<div class="mb2-pb-sortable-subelements">';

    $output .= mb2_do_shortcode($content);

    $output .= '</div>';
    $output .= '</div>';
    $output .= '</div>';

    return $output;

}



/**
 *
 * Method to define animated number item shortcode
 *
 * @return HTML
 */
function mb2_shortcode_mb2pb_animnum_item ($atts, $content=null) {

    global $gl0sizenumber,
    $gl0sizetitle,
    $gl0coloricon,
    $gl0colornumber,
    $gl0colortitle,
    $gl0colorsubtitle,
    $gl0colorbg,
    $gl0nfwcls,
    $gl0tlhcls,
    $gl0tfwcls,
    $gl0height,
    $gl0nfstyle,
    $gl0nmt,
    $gl0nmb;

    $atts2 = [
        'id' => 'animnum_item',
        'number' => 125,
        'icon' => 'fa fa-graduation-cap',
        'title' => 'Title here',
        'color_icon' => '',
        'color_number' => '',
        'color_title' => '',
        'color_subtitle' => '',
        'color_bg' => '',
        'subtitle' => 'Subtitle here',
        'template' => '',
    ];

    $a = mb2_shortcode_atts($atts2, $atts);

    $output = '';
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

    $color = $a['color_bg'] ? $a['color_bg'] : $gl0colorbg;

    $colorbgstyle .= ' style="';
    $colorbgstyle .= $color ? 'background-color:' . $color . ';' : '';
    $colorbgstyle .= 'min-height:' . $gl0height . 'px;';
    $colorbgstyle .= '"';

    $output .= '<div class="mb2-pb-subelement mb2-pb-animnum_item theme-box"' .
    theme_mb2nl_page_builder_el_datatts($atts, $atts2)  . '>';
    $output .= theme_mb2nl_page_builder_el_actions('subelement');
    $output .= '<div class="subelement-helper"></div>';
    $output .= '<div class="mb2-pb-subelement-inner">';
    $output .= '<div class="pbanimnum-item"' . $colorbgstyle . '>';

    $output .= '<div class="pbanimnum-icon" style="' . $coloriconstyle . '">';
    $output .= '<i class="' . $a['icon'] . '"></i>';
    $output .= '</div>';
    $output .= '<div class="pbanimnum-number' . $ncls . '"' . $numberstyle . '>0</div>';

    $output .= '<div class="pbanimnum-text">';
    $output .= '<h4 class="pbanimnum-title' . $tcls . '"' . $colortitlestyle . '>' . $a['title'] . '</h4>';
    $output .= '<span class="pbanimnum-subtitle"' . $colorsubtitlestyle . '>' . $a['subtitle'] . '</span>';
    $output .= '</div>';

    $output .= '</div>';
    $output .= '</div>';
    $output .= '</div>';

    return $output;

}
