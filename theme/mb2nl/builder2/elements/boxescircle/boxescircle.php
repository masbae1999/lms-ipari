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

mb2_add_shortcode('mb2pb_boxescircle', 'mb2_shortcode_mb2pb_boxescircle');
mb2_add_shortcode('mb2pb_boxescircle_item', 'mb2_shortcode_mb2pb_boxescircle_item');


/**
 *
 * Method to define shortcode
 *
 * @return HTML
 */
function mb2_shortcode_mb2pb_boxescircle($atts, $content=null) {

    global $gl0boxescircletfs, $gl0boxescircletfw, $gl0boxescircletlh;

    $atts2 = [
        'id' => 'boxescircle',
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
        'mb' => 10, // 10 because box item has margin bottom 20 pixels.
        'custom_class' => '',
        'template' => '',
    ];

    $a = mb2_shortcode_atts($atts2, $atts);

    // Global variables.
    $gl0boxescircletfs = $a['tfs'];
    $gl0boxescircletfw = $a['tfw'];
    $gl0boxescircletlh = $a['tlh'];

    $output = '';
    $style = '';
    $cls = '';

    $cls .= ' type-' . $a['type'];
    $cls .= ' desc' . $a['desc'];
    $cls .= $a['custom_class'] ? ' ' . $a['custom_class'] : '';
    $templatecls = $a['template'] ? ' mb2-pb-template-boxescircle' : '';

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

    $content = $content;

    if (!$content) {

        for ($i = 1; $i <= 3; $i++) {
            $content .= '[mb2pb_boxescircle_item image="' . theme_mb2nl_dummy_image('200x200') .
            '" title="Box title here" ]Box content here[/mb2pb_boxescircle_item]';
        }
    }

    $output .= '<div class="mb2-pb-element mb2-pb-boxescircle' . $templatecls . '"' . $style .
    theme_mb2nl_page_builder_el_datatts($atts, $atts2) . '>';
    $output .= '<div class="element-helper"></div>';
    $output .= theme_mb2nl_page_builder_el_actions('element', 'boxescircle');
    $output .= '<div class="mb2-pb-element-inner' . $cls . '">';
    $output .= '<div class="mb2-pb-sortable-subelements boxescircle-list">';
    $output .= mb2_do_shortcode($content);
    $output .= '</div>';
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
function mb2_shortcode_mb2pb_boxescircle_item($atts, $content = null) {

    global $gl0boxescircletfs, $gl0boxescircletfw, $gl0boxescircletlh;

    $atts2 = [
        'id' => 'boxescircle_item',
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
        'template' => '',
    ];

    $a = mb2_shortcode_atts($atts2, $atts);

    $output = '';
    $style = '';
    $tstyle = '';
    $tcls = '';

    $content = ! $content ? 'Box content here' : $content;
    $atts2['text'] = $content;

    $tcls .= ' ' . theme_mb2nl_tsize_cls($gl0boxescircletfs);
    $tcls .= ' fw' . $gl0boxescircletfw;
    $tcls .= ' lh' . $gl0boxescircletlh;

    $tstyle .= ' style="';
    $tstyle .= 'font-size:' . $gl0boxescircletfs . 'rem;';
    $tstyle .= '"';

    $style .= ' style="';
    $style .= 'background-image:url(\'' . $a['image'] . '\');';
    $style .= $a['color'] ? '--mb2-pb-bcrcle-color:' . $a['color'] . ';' : '';
    $style .= $a['hcolor'] ? '--mb2-pb-bcrcle-hcolor:' . $a['hcolor'] . ';' : '';
    $style .= $a['bgcolor'] ? '--mb2-pb-bcrcle-bgcolor:' . $a['bgcolor'] . ';' : '';
    $style .= $a['borcolor'] ? '--mb2-pb-bcrcle-borcolor:' . $a['borcolor'] . ';' : '';
    $style .= $a['hbgcolor'] ? '--mb2-pb-bcrcle-hbgcolor:' . $a['hbgcolor'] . ';' : '';
    $style .= $a['hborcolor'] ? '--mb2-pb-bcrcle-hborcolor:' . $a['hborcolor'] . ';' : '';
    $style .= '"';

    $output .= '<div class="mb2-pb-subelement mb2-pb-boxescircle_item"' .
    theme_mb2nl_page_builder_el_datatts($atts, $atts2) . '>';
    $output .= theme_mb2nl_page_builder_el_actions('subelement');
    $output .= '<div class="subelement-helper"></div>';
    $output .= '<div class="mb2-pb-subelement-inner">';
    $output .= '<div class="theme-boxcircle"' . $style . '>';
    $output .= '<div class="box-content">';

    $output .= '<h4 class="box-title' . $tcls . '"' . $tstyle . '><span class="box-title-text">' .
    theme_mb2nl_format_str($a['title']) . '</span></h4>';

    $output .= '<div class="box-desc-text">' . urldecode($content) . '</div>';

    $output .= '</div>'; // ...box-content
    $output .= '</div>'; // ...theme-boxcircle

    $output .= '</div>';
    $output .= '</div>';

    return $output;

}
