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

mb2_add_shortcode('boxesicon_item', 'mb2_shortcode_boxesicon_item');
mb2_add_shortcode('boxicon', 'mb2_shortcode_boxesicon_item'); // This is old shortcode.

/**
 *
 * Method to define boxes icon shortcode
 *
 * @return HTML
 */
function mb2_shortcode_boxesicon_item($atts, $content = null) {

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
        'icon' => 'fa-rocket',
        'title' => '',
        'link' => '',

        'ccolor' => '',
        'icolor' => '',
        'bgcolor' => '',
        'tcolor' => '',
        'txcolor' => '',

        'link_target' => 0,
        'target' => '',
        'readmore' => '',
        'linkbtn' => '',
        'btntext' => '',

        'btncolor' => '',
        'btnbgcolor' => '',
        'btnbghcolor' => '',
        'btnhcolor' => '',
        'btnborcolor' => '',
        'btnborhcolor' => '',
    ];

    $a = mb2_shortcode_atts($atts2, $atts);

    $output = '';
    $cls = '';
    $fcls = '';
    $ccolorstyle = '';
    $boxstyle = '';
    $boxistyle = '';
    $btnstyle = '';

    // Link target.
    $a['target'] = $a['target'] ? $a['target'] : $a['link_target'];
    $a['target'] = $a['target'] ? ' target="_blank"' : '';

    // Get some global values from parent shortcode.
    $a['btntext'] = $a['btntext'] ? $a['btntext'] : $gl0bxbtntext;
    $a['linkbtn'] = $a['linkbtn'] ? $a['linkbtn'] : $gl0bxlinkbtn;

    if ($gl0bxmb || $a['ccolor'] || $a['icolor'] || $a['bgcolor'] || $a['tcolor'] || $a['txcolor']) {
        $boxstyle .= ' style="';
        $boxstyle .= $gl0bxmb ? 'margin-bottom:' . $gl0bxmb . 'px;' : '';
        $boxstyle .= $a['ccolor'] ? '--mb2-pb-bxaccolor:' . $a['ccolor'] . ';' : '';
        $boxstyle .= $a['icolor'] ? '--mb2-pb-bxicolor:' . $a['icolor'] . ';' : '';
        $boxstyle .= $a['bgcolor'] ? '--mb2-pb-bxbgcolor:' . $a['bgcolor'] . ';' : '';
        $boxstyle .= $a['tcolor'] ? '--mb2-pb-bxtcolor:' . $a['tcolor'] . ';' : '';
        $boxstyle .= $a['txcolor'] ? '--mb2-pb-bxtxcolor:' . $a['txcolor'] . ';' : '';
        $boxstyle .= '"';
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

    if ($gl0bxheight) {
        $boxistyle .= ' style="';
        $boxistyle .= $gl0bxheight ? 'min-height:' . $gl0bxheight . 'px;' : '';
        $boxistyle .= '"';
    }

    $fcls .= ' fw'. $gl0bxtfw;
    $fcls .= ' lh'. $gl0bxtlh;
    $fcls .= ' ' . theme_mb2nl_tsize_cls($gl0bxtfs);

    $a['readmore'] = $a['readmore'] ? $a['readmore'] : $a['btntext'];
    $a['readmore'] = $a['readmore'] ? theme_mb2nl_format_str($a['readmore']) : get_string('readmore', 'theme_mb2nl');

    $pref = theme_mb2nl_font_icon_prefix($a['icon']);

    $output .= '<div class="theme-box">';
    $output .= '<div class="theme-boxicon"' . $boxstyle . '>';
    $output .= '<div class="theme-boxicon-inner"' . $boxistyle . '>';
    $output .= '<div class="theme-boxicon-icon">';
    $output .= '<i class="' . $pref . $a['icon'] . '"></i>';
    $output .= '</div>';
    $output .= '<div class="theme-boxicon-content">';

    if ($a['title']) {
        $output .= '<h4 class="box-title m-0' . $fcls . '" style="font-size:' . $gl0bxtfs . 'rem;">';
        $output .= theme_mb2nl_format_str($a['title']);
        $output .= '</h4>';
    }

    if ($gl0bxdesc) {
        $output .= '<div class="box-desc">';
        $output .= theme_mb2nl_format_str($content);
        $output .= '</div>';
    }

    if ($a['linkbtn']) {
        $output .= '<div class="box-readmore">';

        if ($a['linkbtn'] == 2) {
            $output .= '<a class="mb2-pb-btn typeprimary" href="' . $a['link'] . '"' . $a['target'] . ' tabindex="-1"' .
            $btnstyle . '>' . $a['readmore'] . '</a>';
        } else {
            $output .= '<a href="' . $a['link'] . '"' . $a['target'] . ' class="arrowlink" tabindex="-1"' .
            $btnstyle . '>' . $a['readmore'] . '</a>';
        }

        $output .= '</div>'; // ...theme-boxicon-readmore
    }

    $output .= '</div>';
    $output .= '</div>';

    if ($a['ccolor']) {
        $output .= '<div class="box-color"></div>';
    }

    $output .= '<div class="bigicon d-none"><i class="' . $pref . $a['icon'] . '"></i></div>';
    $output .= $a['link'] ? '<a class="linkabs" href="' . $a['link'] . '"' . $a['target'] . ' tabindex="0" aria-label="' .
    theme_mb2nl_format_str($a['title']) . '"></a>' : '';
    $output .= '</div>';
    $output .= '</div>'; // ...theme box

    return $output;

}
