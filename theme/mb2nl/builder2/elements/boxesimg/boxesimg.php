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

mb2_add_shortcode('mb2pb_boxesimg', 'mb2_shortcode_mb2pb_boxesimg');
mb2_add_shortcode('mb2pb_boxesimg_item', 'mb2_shortcode_mb2pb_boxesimg_item');

/**
 *
 * Method to define boxes image shortcode
 *
 * @return HTML
 */
function mb2_shortcode_mb2pb_boxesimg($atts, $content=null) {

    global $gl0bximgbtntext,
    $gl0bximgbtntype,
    $gl0bximgbtnsize,
    $gl0bximgbtnfwcls,
    $gl0bximgbtnborder,
    $gl0bximgbtnrounded,
    $gl0bximgimgwidth,
    $gl0bximgimgtfs,
    $gl0bximgimgtfw,
    $gl0bximgimgtlh,
    $gl0bximgimgborder;

    $atts2 = [
        'id' => 'boxesimg',
        'boxid' => 'boxesimg',
        'columns' => 2, // ...max 5
        'type' => 1,
        'mt' => 0,
        'mb' => 0, // ...0 because box item has margin bottom 30 pixels
        'custom_class' => '',
        'desc' => 0,
        'rounded' => 0,
        // ...
        'tfs' => 1.4,
        'tfw' => 'global',
        'tlh' => 'global',
        'center' => 0,
        // ...
        'linkbtn' => 0,
        'btntext' => '',
        'btntype' => 'primary',
        'btnsize' => 'normal',
        'btnfwcls' => 'global',
        'btnrounded' => 0,
        'btnborder' => 0,
        // ...
        'imgwidth' => 800,
        // ...
        'ccolor' => '',
        'bgcolor' => '',
        'bocolor' => '',
        'tcolor' => '',
        'txcolor' => '',
        // ...
        'shadow' => 0,
        'border' => 0,
        // ...
        'gutter' => 'normal',
        'template' => '',
    ];

    $a = mb2_shortcode_atts($atts2, $atts);

    $output = '';
    $style = '';
    $cls = '';
    $bxstyle = '';

    $cls .= ' theme-' . $a['boxid'];
    $cls .= ' type-' . $a['type'];
    $cls .= ' gutter-' . $a['gutter'];
    $cls .= ' desc' . $a['desc'];
    $cls .= ' rounded' . $a['rounded'];
    $cls .= ' linkbtn' . $a['linkbtn'];
    $cls .= ' theme-col-' . $a['columns'];
    $cls .= ' shadow' . $a['shadow'];
    $cls .= ' center' . $a['center'];
    $cls .= $a['custom_class'] ? ' ' . $a['custom_class'] : '';

    $templatecls = $a['template'] ? ' mb2-pb-template-boxesimg' : '';

    $gl0bximgbtntext = $a['btntext'];
    $gl0bximgbtntype = $a['btntype'];
    $gl0bximgbtnsize = $a['btnsize'];
    $gl0bximgbtnfwcls = $a['btnfwcls'];
    $gl0bximgbtnborder = $a['btnborder'];
    $gl0bximgbtnrounded = $a['btnrounded'];
    $gl0bximgimgwidth = $a['imgwidth'];
    $gl0bximgimgtfs = $a['tfs'];
    $gl0bximgimgtfw = $a['tfw'];
    $gl0bximgimgtlh = $a['tlh'];
    $gl0bximgimgborder = $a['border'];

    $style .= ' style="';
    $style .= $a['mt'] ? 'margin-top:' . $a['mt'] . 'px;' : '';
    $style .= $a['mb'] ? 'margin-bottom:' . $a['mb'] . 'px;' : '';
    $style .= '"';

    $bxstyle .= ' style="';
    $bxstyle .= $a['ccolor'] ? '--mb2-pb-bxaccolor:' . $a['ccolor'] . ';' : '';
    $bxstyle .= $a['bgcolor'] ? '--mb2-pb-bxbgcolor:' . $a['bgcolor'] . ';' : '';
    $bxstyle .= $a['bocolor'] ? '--mb2-pb-bxbocolor:' . $a['bocolor'] . ';' : '';
    $bxstyle .= $a['tcolor'] ? '--mb2-pb-bxtcolor:' . $a['tcolor'] . ';' : '';
    $bxstyle .= $a['txcolor'] ? '--mb2-pb-bxtxcolor:' . $a['txcolor'] . ';' : '';
    $bxstyle .= '"';

    $content = $content;

    if (! $content) {
        $demoimage = theme_mb2nl_dummy_image('1600x944');

        for ($i = 1; $i <= 2; $i++) {
            $content .= '[mb2pb_boxesimg_item image="' . $demoimage . '" ]Box title here[/mb2pb_boxesimg_item]';
        }
    }

    $output .= '<div class="mb2-pb-element mb2-pb-boxesimg' . $templatecls . '"' . $style .
    theme_mb2nl_page_builder_el_datatts($atts, $atts2) . '>';
    $output .= '<div class="element-helper"></div>';
    $output .= theme_mb2nl_page_builder_el_actions('element', 'boxesimg');
    $output .= '<div class="mb2-pb-element-inner theme-boxes' . $cls . '"' . $bxstyle . '>';
    $output .= '<div class="mb2-pb-sortable-subelements">';
    $output .= mb2_do_shortcode($content);
    $output .= '</div>';
    $output .= '</div>';
    $output .= '</div>';

    return $output;

}


/**
 *
 * Method to define boxes image item shortcode
 *
 * @return HTML
 */
function mb2_shortcode_mb2pb_boxesimg_item($atts, $content = null) {

    global $gl0bximgbtntext,
    $gl0bximgbtntype,
    $gl0bximgbtnsize,
    $gl0bximgbtnfwcls,
    $gl0bximgbtnborder,
    $gl0bximgbtnrounded,
    $gl0bximgimgwidth,
    $gl0bximgimgtfs,
    $gl0bximgimgtfw,
    $gl0bximgimgtlh,
    $gl0bximgimgborder;

    $atts2 = [
        'id' => 'boxesimg_item',
        'image' => theme_mb2nl_dummy_image('1600x944'),
        'link' => '',
        'description' => 'Box description here...',
        'link_target' => 0,
        'el_onmobile' => 1,

        'btncolor' => '',
        'btnbgcolor' => '',
        'btnbghcolor' => '',
        'btnhcolor' => '',
        'btnborcolor' => '',
        'btnborhcolor' => '',

        'ccolor' => '',
        'color' => '',
        'bocolor' => '',
        'tcolor' => '',
        'txcolor' => '',

        'template' => '',
    ];

    $a = mb2_shortcode_atts($atts2, $atts);

    $output = '';
    $cls = '';
    $tcls = '';
    $wrapcls = '';
    $titlecolorspan = '';
    $btnstyle = '';
    $style = '';

    $wrapcls .= ' el_onmobile' . $a['el_onmobile'];

    // Title classess.
    $tcls .= ' ' . theme_mb2nl_tsize_cls($gl0bximgimgtfs);
    $tcls .= ' fw' . $gl0bximgimgtfw;
    $tcls .= ' lh' . $gl0bximgimgtlh;

    $style .= ' style="';
    $style .= $a['color'] ? '--mb2-pb-bxbgcolor:' . $a['color'] .';' : '';
    $style .= $a['bocolor'] ? '--mb2-pb-bxbocolor:' . $a['bocolor'] .';' : '';
    $style .= $gl0bximgimgborder ? '--mb2-pb-bxborder:' . $gl0bximgimgborder .'px;' : '';
    $style .= $a['ccolor'] ? '--mb2-pb-bxaccolor:' . $a['ccolor'] . ';' : '';
    $style .= $a['tcolor'] ? '--mb2-pb-bxtcolor:' . $a['tcolor'] . ';' : '';
    $style .= $a['txcolor'] ? '--mb2-pb-bxtxcolor:' . $a['txcolor'] . ';' : '';
    $style .= '"';

    $content = ! $content ? 'Box title here' : $content;
    $atts2['text'] = $content;

    if ($a['btncolor'] || $a['btnbgcolor'] || $a['btnbghcolor'] || $a['btnhcolor'] || $a['btnborcolor'] || $a['btnborhcolor']) {
        $btnstyle .= ' style="';
        $btnstyle .= $a['btncolor'] ? '--mb2-pb-btn-color:' . $a['btncolor'] . ';' : '';
        $btnstyle .= $a['btnbgcolor'] ? '--mb2-pb-btn-bgcolor:' . $a['btnbgcolor'] . ';' : '';
        $btnstyle .= $a['btnbghcolor'] ? '--mb2-pb-btn-bghcolor:' . $a['btnbghcolor'] . ';' : '';
        $btnstyle .= $a['btnhcolor'] ? '--mb2-pb-btn-hcolor:' . $a['btnhcolor'] . ';' : '';
        $btnstyle .= $a['btnborcolor'] ? '--mb2-pb-btn-borcolor:' . $a['btnborcolor'] . ';' : '';
        $btnstyle .= $a['btnborhcolor'] ? '--mb2-pb-btn-borhcolor:' . $a['btnborhcolor'] . ';' : '';
        $btnstyle .= '"';
    }

    $output .= '<div class="mb2-pb-subelement mb2-pb-boxesimg_item theme-box' . $wrapcls . '"' .
    theme_mb2nl_page_builder_el_datatts($atts, $atts2) . '>';
    $output .= theme_mb2nl_page_builder_el_actions('subelement');
    $output .= '<div class="subelement-helper"></div>';
    $output .= '<div class="mb2-pb-subelement-inner">';
    $output .= '<div class="theme-boximg' . $cls . '"' . $style . '>';

    $output .= '<div class="box-allcontent">';
    $output .= '<div class="box-image">';
    $output .= '<img class="theme-boximg-img" src="' . $a['image'] . '" alt="" style="max-width:' . $gl0bximgimgwidth . 'px;">';
    $output .= '</div>'; // ...box-image
    $output .= '<div class="vtable-wrapp">';
    $output .= '<div class="vtable">';
    $output .= '<div class="vtable-cell">';
    $output .= '<div class="box-content">';
    $output .= '<h4 class="box-title' . $tcls . '" style="font-size:' .
    $gl0bximgimgtfs . 'rem;"><span class="box-title-text">' . urldecode($content) . '</span></h4>';
    $output .= '<div class="box-desc">' . $a['description'] . '</div>';
    $output .= '<span class="theme-boximg-color"></span>';

    $a['btntext'] = $gl0bximgbtntext ? $gl0bximgbtntext : get_string('readmorefp', 'local_mb2builder');
    $output .= '<div class="box-readmore">';
    $output .= '<a href="#" class="arrowlink"' . $btnstyle . '>' . $a['btntext'] . '</a>';
    $output .= '<a href="#" class="mb2-pb-btn type' . $gl0bximgbtntype . ' size' . $gl0bximgbtnsize . ' rounded' .
    $gl0bximgbtnrounded . ' btnborder' . $gl0bximgbtnborder . ' fw' . $gl0bximgbtnfwcls . '"' .
    $btnstyle . '>' . $a['btntext'] . '</a>';
    $output .= '</div>'; // ...theme-boxicon-readmore
    $output .= '</div>'; // ...box-content
    $output .= '</div>'; // ...vtable-cell
    $output .= '</div>'; // ...vtable
    $output .= '</div>'; // ...vtable-wrapp
    $output .= '</div>'; // ...box-allcontent
    $output .= '<div class="theme-boximg-color"></div>';

    $output .= '<div class="theme-boximg-imgel" style="background-image:url(\'' . $a['image'] . '\');">';
    $output .= '<div class="gradient-el gradient-left" style="background-image: linear-gradient(to right,' .
    $a['color'] . ',rgba(255,255,255,0));"></div></div>';

    $output .= '</div>';
    $output .= '</div>';
    $output .= '</div>';

    return $output;

}
