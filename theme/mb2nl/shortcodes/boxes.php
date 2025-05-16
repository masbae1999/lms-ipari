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

mb2_add_shortcode('boxes', 'mb2_shortcode_boxes');
mb2_add_shortcode('boxesimg', 'mb2_shortcode_boxes');
mb2_add_shortcode('boxes3d', 'mb2_shortcode_boxes');
mb2_add_shortcode('boxesicon', 'mb2_shortcode_boxes');
mb2_add_shortcode('boxescontent', 'mb2_shortcode_boxes');

/**
 *
 * Method to define boxes shortcode
 *
 * @return HTML
 */
function mb2_shortcode_boxes($atts, $content=null, $tag= '') {

    global $gl0bxheight,
    $gl0bxlinkbtn,
    $gl0bxbtntext,
    $gl0bxlinkbtntype,
    $gl0bxlinkbtnsize,
    $gl0bxlinkbtnrounded,
    $gl0bxlinkbtnborder,
    $gl0bxlinkbtnfwcls,
    $gl0bxtype,
    $gl0bxcolor,
    $gl0bxdesc,
    $gl0bxmb,
    $gl0bximgwidth,
    $gl0bxtfs,
    $gl0bxtfw,
    $gl0bxtlh,
    $gl0bxcwidth,
    $gl0bxborder;

    $atts2 = [
        'boxid' => '',
        'columns' => 1, // ...max 5
        'type' => 1,
        'mt' => 0,
        'mb' => 0, // 0 because box item has margin bottom 30 pixels
        'boxmb' => 0,
        'gutter' => 'normal',
        'imgwidth' => 800,

        'tfs' => 1.4,
        'tfw' => 'global',
        'tlh' => 'global',
        'center' => 0,

        'cwidth' => 2000,
        'btnhor' => 0,
        'padding' => 'm',
        'shadow' => 0,
        'border' => 0,

        'linkbtn' => 0,
        'btntype' => 'primary',
        'btnsize' => 'normal',
        'btnfwcls' => 'global',
        'btnrounded' => 0,
        'btnborder' => 0,
        'btntext' => '',

        'itemlink' => 0,

        'smtitle' => 1,
        'wave' => 0,
        'height' => 0,
        'desc' => theme_mb2nl_shortcodes_global_opts('boxes', 'desc', 1),
        'rounded' => 0,

        'color' => 'primary',

        'ccolor' => '',
        'icolor' => '',
        'bgcolor' => '',
        'tcolor' => '',
        'txcolor' => '',
        'bocolor' => '',

        'btncolor' => '',
        'btnbgcolor' => '',
        'btnbghcolor' => '',
        'btnhcolor' => '',
        'btnborcolor' => '',
        'btnborhcolor' => '',

        'tcenter' => 0,

        'custom_class' => '',
    ];

    $a = mb2_shortcode_atts($atts2, $atts);

    $output = '';
    $style = '';
    $cls = '';

    // Global values.
    $gl0bxheight = $a['height'];
    $gl0bxlinkbtn = $a['linkbtn'];
    $gl0bxbtntext = $a['btntext'];
    $gl0bxlinkbtntype = $a['btntype'];
    $gl0bxlinkbtnsize = $a['btnsize'];
    $gl0bxlinkbtnrounded = $a['btnrounded'];
    $gl0bxlinkbtnborder = $a['btnborder'];
    $gl0bxlinkbtnfwcls = $a['btnfwcls'];
    $gl0bxtype = $a['type'];
    $gl0bxcolor = $a['color'];
    $gl0bxdesc = $a['desc'];
    $gl0bxmb = $a['boxmb'];
    $gl0bxheight = $a['height'];
    $gl0bximgwidth = $a['imgwidth'];
    $gl0bxtfs = $a['tfs'];
    $gl0bxtfw = $a['tfw'];
    $gl0bxtlh = $a['tlh'];
    $gl0bxcwidth = $a['cwidth'];
    $gl0bxborder = $a['border'];

    $cls .= ' theme-' . $a['boxid'];
    $cls .= ' type-' . $a['type'];
    $cls .= ' gutter-' . $a['gutter'];
    $cls .= ' theme-col-' . $a['columns'];
    $cls .= ' smtitle' . $a['smtitle'];
    $cls .= ' rounded' . $a['rounded'];
    $cls .= ' wave' . $a['wave'];
    $cls .= ' padding' . $a['padding'];
    $cls .= ' linkbtn' . $a['linkbtn'];
    $cls .= ' btnhor' . $a['btnhor'];
    $cls .= ' theme-' . $tag;
    $cls .= ' shadow' . $a['shadow'];
    $cls .= ' itemlink' . $a['itemlink'];
    $cls .= ' tcenter' . $a['tcenter'];
    $cls .= ' boxcolor-' . $a['color'];
    $cls .= ' center' . $a['center'];
    $cls .= $a['custom_class'] ? ' ' . $a['custom_class'] : '';

    if ($a['mt'] || $a['mb'] || $a['ccolor'] || $a['icolor'] || $a['bgcolor'] || $a['tcolor'] || $a['txcolor'] || $a['bocolor']) {
        $style .= ' style="';
        $style .= $a['mt'] ? 'margin-top:' . $a['mt'] . 'px;' : '';
        $style .= $a['mb'] ? 'margin-bottom:' . $a['mb'] . 'px;' : '';
        $style .= $a['ccolor'] ? '--mb2-pb-bxaccolor:' . $a['ccolor'] . ';' : '';
        $style .= $a['icolor'] ? '--mb2-pb-bxicolor:' . $a['icolor'] . ';' : '';
        $style .= $a['bocolor'] ? '--mb2-pb-bxbocolor:' . $a['bocolor'] . ';' : '';
        $style .= $a['bgcolor'] ? '--mb2-pb-bxbgcolor:' . $a['bgcolor'] . ';' : '';
        $style .= $a['tcolor'] ? '--mb2-pb-bxtcolor:' . $a['tcolor'] . ';' : '';
        $style .= $a['txcolor'] ? '--mb2-pb-bxtxcolor:' . $a['txcolor'] . ';' : '';
        $style .= '"';
    }

    $output .= '<div class="theme-boxes' . $cls . ' clearfix"' . $style . '>';
    $output .= mb2_do_shortcode($content);
    $output .= '</div>';

    return $output;

}




mb2_add_shortcode('boxcontent', 'mb2_shortcode_boxcontent');

/**
 *
 * Method to define boxes content shortcode
 *
 * @return HTML
 */
function mb2_shortcode_boxcontent($atts, $content = null) {

    global $gl0bxheight,
    $gl0bxlinkbtn,
    $gl0bxbtntext,
    $gl0bxlinkbtntype,
    $gl0bxlinkbtnsize,
    $gl0bxlinkbtnrounded,
    $gl0bxlinkbtnborder,
    $gl0bxlinkbtnfwcls,
    $gl0bxtype,
    $gl0bxcolor,
    $gl0bxdesc,
    $gl0bxmb,
    $gl0bximgwidth,
    $gl0bxtfs,
    $gl0bxtfw,
    $gl0bxtlh,
    $gl0bxcwidth,
    $gl0bxborder;

    $atts2 = [
        'icon' => '',
        'type' => '',
        'title' => '',
        'link' => '',
        'linktext' => 'Read more',
        'color' => 'primary',
        'link_target' => '',
        'target' => '',
    ];

    $a = mb2_shortcode_atts($atts2, $atts);

    $output = '';
    $istarget = $a['target'] ? $a['target'] : $a['link_target'];

    if ($a['type'] === '' && isset($gl0bxtype)) {
        $a['type'] = $gl0bxtype;
    }

    $pref = theme_mb2nl_font_icon_prefix($a['icon']);
    $boxcls = $a['icon'] != '' ? ' isicon' : ' noicon';
    $boxcls .= $a['link'] != '' ? ' islink' : '';

    $output .= '<div class="theme-box">';

    $output .= '<div class="theme-boxcontent type-' . $a['type'] . ' cboxcolor-' . $a['color'] . $boxcls . '">';
    $output .= '<div class="theme-boxcontent-content">';
    $output .= $a['icon'] != '' ? '<div class="theme-boxcontent-icon">' : '';
    $output .= $a['icon'] != '' ? '<i class="' . $pref . $a['icon'] . '"></i>' : '';
    $output .= $a['icon'] != '' ? '</div>' : '';
    $output .= $a['title'] != '' ? '<h4>' . theme_mb2nl_format_str($a['title']) . '</h4>' : '';

    // Page builder prevent to use HTML content in boxes content, so We can use the 'theme_mb2nl_format_str' function.
    $output .= mb2_do_shortcode(theme_mb2nl_format_str($content));
    $output .= $a['link'] != '' ? '<div class="theme-boxcontent-readmore"><a class="mb2-pb-btn sizesm" href="' .
    $a['link'] . '" target="' . $istarget . '">' . theme_mb2nl_format_str($a['linktext']) . '</a></div>' : '';
    $output .= '</div>';
    $output .= '</div>';
    $output .= '</div>';

    return $output;

}
