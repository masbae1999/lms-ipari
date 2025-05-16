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

mb2_add_shortcode('boxesimg_item', 'mb2_shortcode_boxesimg_item');
mb2_add_shortcode('boximg', 'mb2_shortcode_boxesimg_item'); // This is old shortcode.

/**
 *
 * Method to define boxes image shortcode
 *
 * @return HTML
 */
function mb2_shortcode_boxesimg_item($atts, $content = null) {

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
        'image' => '',
        'link' => '',
        'type' => '',
        'target' => 0,
        'description' => 'Box description here...',
        'link_target' => 0,
        'el_onmobile' => 1,

        'ccolor' => '',
        'color' => '',
        'bocolor' => '',
        'tcolor' => '',
        'txcolor' => '',

        'btncolor' => '',
        'btnbgcolor' => '',
        'btnbghcolor' => '',
        'btnhcolor' => '',
        'btnborcolor' => '',
        'btnborhcolor' => '',

        'useimg' => 1,
    ];

    $a = mb2_shortcode_atts($atts2, $atts);

    $output = '';
    $cls = '';
    $tcls = '';
    $wrapcls = '';
    $btncls = '';
    $titlecolorspan = '';
    $style = '';
    $btnstyle = '';

    $cls .= preg_match('@#@', $a['color']) ? ' opcolor' : '';

    // Title classess.
    $tcls .= ' ' . theme_mb2nl_tsize_cls($gl0bxtfs);
    $tcls .= ' fw' . $gl0bxtfw;
    $tcls .= ' lh' . $gl0bxtlh;

    $gradleft = $a['color'] ? '<div class="gradient-el gradient-left" style="background-image:linear-gradient(to right,' .
    $a['color'] . ',rgba(255,255,255,0));"></div>' : '';
    $gradright = $a['color'] ?
    '<div class="gradient-el gradient-right" style="background-image:linear-gradient(to right,rgba(255,255,255,0),' .
    $a['color'] . ');"></div>' : '';

    $wrapcls .= ' el_onmobile' . $a['el_onmobile'];

    $btncls .= ' type' . $gl0bxlinkbtntype;
    $btncls .= ' size' . $gl0bxlinkbtnsize;
    $btncls .= ' rounded' . $gl0bxlinkbtnrounded;
    $btncls .= ' btnborder' . $gl0bxlinkbtnborder;
    $btncls .= ' fw' . $gl0bxlinkbtnfwcls;

    if ($a['color'] || $a['bocolor'] || $gl0bxborder|| $a['ccolor'] || $a['tcolor'] || $a['txcolor']) {
        $style .= ' style="';
        $style .= $a['color'] ? '--mb2-pb-bxbgcolor:' . $a['color'] .';' : '';
        $style .= $a['bocolor'] ? '--mb2-pb-bxbocolor:' . $a['bocolor'] .';' : '';
        $style .= $gl0bxborder ? '--mb2-pb-bxborder:' . $gl0bxborder .'px;' : '';
        $style .= $a['ccolor'] ? '--mb2-pb-bxaccolor:' . $a['ccolor'] . ';' : '';
        $style .= $a['tcolor'] ? '--mb2-pb-bxtcolor:' . $a['tcolor'] . ';' : '';
        $style .= $a['txcolor'] ? '--mb2-pb-bxtxcolor:' . $a['txcolor'] . ';' : '';
        $style .= '"';
    }

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

    $istitle = theme_mb2nl_format_str($content);

    $a['link_target'] = $a['target'] ? $a['target'] : $a['link_target'];
    $a['target'] = $a['link_target'] ? ' target="_blank"' : '';

    $boxcls = $a['useimg'] == 1 ? ' useimg' : '';

    $output .= '<div class="theme-box' . $wrapcls . '">';
    $output .= '<div class="theme-boximg' . $cls . '"' . $style . '>';

    $output .= '<div class="box-allcontent">';
    $output .= '<div class="box-image">';
    $output .= $a['link'] !== '' ? '<a class="theme-boximg-img-link" href="' .
    $a['link'] . '"' . $a['target'] . ' tabindex="-1">' : '';
    $output .= '<img class="theme-boximg-img lazy" src="' . theme_mb2nl_lazy_plc() . '" data-src="' . $a['image'] . '" alt="' .
    strip_tags($istitle) . '" style="max-width:' . $gl0bximgwidth . 'px;">';
    $output .= $a['link'] !== '' ? '</a>' : '';
    $output .= '</div>'; // Box image.

    $output .= '<div class="vtable-wrapp">';
    $output .= '<div class="vtable">';
    $output .= '<div class="vtable-cell">';
    $output .= '<div class="box-content">';
    $output .= '<h4 class="box-title' . $tcls . '" style="font-size:' . $gl0bxtfs . 'rem;">';
    $output .= $a['link'] !== '' ? '<a href="' . $a['link'] . '"' . $a['target'] . ' tabindex="-1">' : '';
    $output .= '<span class="box-title-text">' . $istitle . '</span>';
    $output .= $a['link'] !== '' ? '</a>' : '';
    $output .= '</h4>';

    $output .= $gl0bxdesc ? '<div class="box-desc">' . theme_mb2nl_format_str($a['description']) . '</div>' : '';
    $output .= '<span class="theme-boximg-color"></span>';

    if ($gl0bxlinkbtn) {
        $a['btntext'] = $gl0bxbtntext ? theme_mb2nl_format_str($gl0bxbtntext) :
            get_string('readmorefp', 'local_mb2builder');
        $output .= '<div class="box-readmore">';

        if ($gl0bxlinkbtn == 2) {
            $output .= '<a href="' . $a['link'] . '"' . $a['target'] . ' class="mb2-pb-btn' .
            $btncls . '" tabindex="-1"' . $btnstyle . '>' . $a['btntext'] . '</a>';
        } else {
            $output .= '<a class="arrowlink" href="' . $a['link'] . '"' . $a['target'] . ' tabindex="-1"' .
            $btnstyle . '>' . $a['btntext'] . '</a>';
        }

        $output .= '</div>'; // ...theme-boxicon-readmore
    }

    $output .= '</div>'; // ...box-content
    $output .= '</div>'; // ...vtable-cell
    $output .= '</div>'; // ...vtable
    $output .= '</div>'; // ...vtable-wrapp
    $output .= '</div>'; // ...box all content
    $output .= '<div class="theme-boximg-color"></div>';
    $output .= '<div class="theme-boximg-imgel lazy" data-bg="' . $a['image'] . '">' . $gradleft . $gradright . '</div>';
    $output .= $a['link'] ? '<a class="linkabs" href="' . $a['link'] . '"' .
    $a['target'] . ' tabindex="0" aria-label="' . strip_tags($istitle) . '"></a>' : '';
    $output .= '</div>';
    $output .= '</div>';

    return $output;

}
