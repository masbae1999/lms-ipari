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

mb2_add_shortcode('mb2pb_announcements', 'mb2_shortcode_mb2pb_announcements');

/**
 *
 * Method to define announcements shortcode
 *
 * @return HTML
 */
function mb2_shortcode_mb2pb_announcements($atts, $content=null) {

    global $PAGE;

    $atts2 = [
        'id' => 'announcements',
        'limit' => 8,
        'pinned' => 0,
        'title' => '',
        'custom_class' => '',
        // ...
        'columns' => 1,
        'sloop' => 1,
        'snav' => 1,
        'sdots' => 0,
        'autoplay' => 1,
        'pausetime' => 4000,
        'animtime' => 350,
        'animtype' => 'slide',
        // ...
        'bgcolor' => '',
        'rounded' => 0,
        'cbgcolor' => '',
        'ccolor' => '',
        'border' => 1,
        'height' => 36,
        'twidth' => 180,
        'icon' => 0,
        'mt' => 0,
        'mb' => 30,
        'template' => '',
    ];

    $a = mb2_shortcode_atts($atts2, $atts);

    $output = '';
    $cls = '';
    $style = '';
    $sliderid = $a['template'] ? '' : uniqid('swiper_');
    $titletext = $a['title'] ? $a['title'] : get_string('sitenews');

    $opts = theme_mb2nl_page_builder_2arrays($atts, $atts2);
    $opts['pb'] = 1;

    $cls .= $a['template'] ? ' mb2-pb-template-announcements ' : '';
    $cls .= ' icon' . $a['icon'];
    $cls .= ' border' . $a['border'];
    $cls .= ' rounded' . $a['rounded'];

    $style .= ' style="';
    $style .= 'margin-top:' . $a['mt'] . 'px;';
    $style .= 'margin-bottom:' . $a['mb'] . 'px;';
    $style .= '--mb2-pb-ancts-h:' . $a['height'] . 'px;';
    $style .= '--mb2-pb-ancts-twidth:' . $a['twidth'] . 'px;';
    $style .= $a['bgcolor'] ? '--mb2-pb-ancts-bgcolor:' . $a['bgcolor'] . ';' : '';
    $style .= $a['cbgcolor'] ? '--mb2-pb-ancts-cbgcolor:' . $a['cbgcolor'] . ';' : '';
    $style .= $a['ccolor'] ? '--mb2-pb-ancts-ccolor:' . $a['ccolor'] . ';' : '';
    $style .= '"';

    $output .= '<div class="mb2-pb-element mb2-pb-announcements clearfix' . $cls . '"' . $style .
    theme_mb2nl_page_builder_el_datatts($atts, $atts2) . '>';
    $output .= '<div class="element-helper"></div>';
    $output .= theme_mb2nl_page_builder_el_actions('element', 'sitenews');
    $output .= '<div class="mb2-pb-content-inner mb2-pb-element-inner clearfix">';
    $output .= '<div class="mb2-pb-announcements-title">';
    $output .= '<span class="title-text">' . $titletext . '</span>';
    $output .= '<span class="title-icon"><i class="fa fa-bullhorn"></i></span>';
    $output .= '</div>';
    $output .= '<div class="mb2-pb-announcements-content">';
    $output .= '<div id="' . $sliderid . '" class="swiper">';
    $output .= theme_mb2nl_shortcodes_swiper_nav($sliderid);
    $output .= '<div class="mb2-pb-content-list swiper-wrapper"></div>'; // Content loaded via js.
    $output .= theme_mb2nl_shortcodes_swiper_pagenavnav();
    $output .= '</div>'; // ...swiper
    $output .= '</div>'; // ...mb2-pb-announcements-content
    $output .= '</div>'; // ...mb2-pb-content-inner
    $output .= '</div>'; // ...mb2-pb-content

    return $output;

}
