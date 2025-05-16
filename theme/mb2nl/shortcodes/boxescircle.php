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

mb2_add_shortcode('boxescircle', 'mb2_shortcode_boxescircle');
mb2_add_shortcode('boxescircle_item', 'mb2_shortcode_boxescircle_item');

/**
 *
 * Method to define shortcode
 *
 * @return HTML
 */
function mb2_shortcode_boxescircle($atts, $content = null) {

    global $gl0boxescircledesc, $gl0boxescircletfs, $gl0boxescircletfw, $gl0boxescircletlh;

    $atts2 = [
        'type' => 1,
        'desc' => 1,
        'size' => 200,
        'hspace' => 1.4,
        'vspace' => 0.5,

        'tfs' => 1.4,
        'tfw' => 'global',
        'tlh' => 'global',

        'color' => '',
        'hcolor' => '',
        'bgcolor' => '',
        'borcolor' => '',
        'hbgcolor' => '',
        'hborcolor' => '',
        'bors' => 3,
        'mt' => 0,
        'mb' => 10, // 10 because box item has margin bottom 20 pixels
        'custom_class' => '',
    ];

    $a = mb2_shortcode_atts($atts2, $atts);

    $output = '';
    $style = '';
    $cls = '';

    // Global variables.
    $gl0boxescircledesc = $a['desc'];
    $gl0boxescircletfs = $a['tfs'];
    $gl0boxescircletfw = $a['tfw'];
    $gl0boxescircletlh = $a['tlh'];

    $cls .= ' type-' . $a['type'];
    $cls .= ' desc' . $a['desc'];
    $cls .= $a['custom_class'] ? ' ' . $a['custom_class'] : '';

    $style .= ' style="';
    $style .= 'margin-top:' . $a['mt'] . 'px;';
    $style .= 'margin-bottom:' . $a['mb'] . 'px;';
    $style .= '--mb2-pb-bcrcle-s:' . $a['size'] . 'px;';
    $style .= '--mb2-pb-bcrcle-hspace:' . $a['hspace'] . 'rem;';
    $style .= '--mb2-pb-bcrcle-vspace:' . $a['vspace'] . 'rem;';
    $style .= '--mb2-pb-bcrcle-bors:' . $a['bors'] . 'px;';

    $style .= $a['color'] ? '--mb2-pb-bcrcle-color:' . $a['color'] . ';' : '';
    $style .= $a['hcolor'] ? '--mb2-pb-bcrcle-hcolor:' . $a['hcolor'] . ';' : '';
    $style .= $a['bgcolor'] ? '--mb2-pb-bcrcle-bgcolor:' . $a['bgcolor'] . ';' : '';
    $style .= $a['borcolor'] ? '--mb2-pb-bcrcle-borcolor:' . $a['borcolor'] . ';' : '';
    $style .= $a['hbgcolor'] ? '--mb2-pb-bcrcle-hbgcolor:' . $a['hbgcolor'] . ';' : '';
    $style .= $a['hborcolor'] ? '--mb2-pb-bcrcle-hborcolor:' . $a['hborcolor'] . ';' : '';

    $style .= '"';

    $output .= '<div class="mb2-pb-boxescircle' . $cls . '"' . $style . '>';
    $output .= '<div class="boxescircle-list">';
    $output .= mb2_do_shortcode($content);
    $output .= '</div>';
    $output .= '</div>';

    return $output;

}



/**
 *
 * Method to define shortcode
 *
 * @return HTML
 */
function mb2_shortcode_boxescircle_item($atts, $content=null) {

    global $gl0boxescircledesc, $gl0boxescircletfs, $gl0boxescircletfw, $gl0boxescircletlh;

    $atts2 = [
        'image' => theme_mb2nl_dummy_image('1600x944'),
        'title' => 'Box title here',
        'link' => '',
        'color' => '',
        'hcolor' => '',
        'bgcolor' => '',
        'borcolor' => '',
        'hbgcolor' => '',
        'hborcolor' => '',
        'link_target' => 0,
    ];

    $a = mb2_shortcode_atts($atts2, $atts);

    $output = '';
    $style = '';
    $tstyle = '';
    $tcls = '';

    $tcls .= ' ' . theme_mb2nl_tsize_cls($gl0boxescircletfs);
    $tcls .= ' fw' . $gl0boxescircletfw;
    $tcls .= ' lh' . $gl0boxescircletlh;

    $tstyle .= ' style="';
    $tstyle .= 'font-size:' . $gl0boxescircletfs . 'rem;';
    $tstyle .= '"';

    $style .= ' style="';
    $style .= $a['color'] ? '--mb2-pb-bcrcle-color:' . $a['color'] . ';' : '';
    $style .= $a['hcolor'] ? '--mb2-pb-bcrcle-hcolor:' . $a['hcolor'] . ';' : '';
    $style .= $a['bgcolor'] ? '--mb2-pb-bcrcle-bgcolor:' . $a['bgcolor'] . ';' : '';
    $style .= $a['borcolor'] ? '--mb2-pb-bcrcle-borcolor:' . $a['borcolor'] . ';' : '';
    $style .= $a['hbgcolor'] ? '--mb2-pb-bcrcle-hbgcolor:' . $a['hbgcolor'] . ';' : '';
    $style .= $a['hborcolor'] ? '--mb2-pb-bcrcle-hborcolor:' . $a['hborcolor'] . ';' : '';
    $style .= '"';

    $target = $a['link_target'] ? ' target="_blank"' : '';
    $linkstart = $a['link'] ? 'a href="' . $a['link'] . '"' . $target : 'div';
    $linkend = $a['link'] ? 'a' : 'div';

    $output .= '<' . $linkstart . ' class="theme-boxcircle lazy"' . $style . ' data-bg="' . $a['image'] . '">';
    $output .= '<div class="box-content">';
    $output .= '<h4 class="box-title' . $tcls . '"' . $tstyle . '><span class="box-title-text">' .
    theme_mb2nl_format_str($a['title']) . '</span></h4>';
    $output .= $gl0boxescircledesc ? '<div class="box-desc-text">' .
    theme_mb2nl_format_str(urldecode($content)) . '</div>' : '';
    $output .= '</div>'; // ...box-content
    $output .= '</' . $linkend . '>'; // ...theme-boxcircle

    return $output;

}
