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

mb2_add_shortcode('mb2pb_events', 'mb2_shortcode_mb2pb_events');

/**
 *
 * Method to define shortcode
 *
 * @return HTML
 */
function mb2_shortcode_mb2pb_events($atts, $content=null) {

    $atts2 = [
        'id' => 'events',
        'type' => 0, // 0 = all, 1 = site events
        'upcoming' => 0,
        'limit' => 8,
        'image' => 0,

        'color1' => '',
        'color2' => '',

        'columns' => 3,
        'sloop' => 0,
        'snav' => 1,
        'sdots' => 0,
        'autoplay' => 0,
        'pausetime' => 5000,
        'animtime' => 450,

        'layout' => 1, // 1 - list, 2 - columns, 3 - carousel
        'gutter' => 'normal',
        'custom_class' => '',
        'mt' => 0,
        'mb' => 30,
        'template' => '',
    ];

    $a = mb2_shortcode_atts($atts2, $atts);

    $output = '';
    $cls = '';
    $listcls = '';
    $style = '';
    $sliderid = $a['template'] ? '' : uniqid('swiper_');
    $modalid = uniqid();

    $cls .= $a['custom_class'] ? ' ' . $a['custom_class'] : '';

    $options = theme_mb2nl_page_builder_2arrays($atts, $atts2);
    $options['lazy'] = 0;

    // Carousel layout.
    $listcls .= $a['layout'] == 3 ? ' swiper-wrapper' : '';
    $listcls .= $a['layout'] == 2 ? ' theme-boxes theme-col-' . $a['columns'] : '';
    $listcls .= ' gutter-' . $a['gutter'];
    $listcls .= ' layout' . $a['layout'];

    $supercls = ' gutter-' . $a['gutter'];

    $containercls = $a['layout'] == 3 ? ' swiper' : '';

    $cls .= $a['template'] ? ' mb2-pb-template-events' : '';

    $style .= ' style="';
    $style .= 'margin-top:' . $a['mt'] . 'px;';
    $style .= 'margin-bottom:' . $a['mb'] . 'px;';
    $style .= $a['color1'] ? '--mb2-pb-color1:' . $a['color1'] . ';' : '';
    $style .= $a['color2'] ? '--mb2-pb-color2:' . $a['color2'] . ';' : '';
    $style .= '"';

    $output .= '<div class="mb2-pb-content mb2-pb-element mb2-pb-events clearfix' . $cls . '"' .
    $style . theme_mb2nl_page_builder_el_datatts($atts, $atts2) . '>';
    $output .= '<div class="element-helper"></div>';
    $output .= theme_mb2nl_page_builder_el_actions('element', 'events');
    $output .= '<div id="' . $sliderid . '" class="mb2-pb-content-inner mb2-pb-element-inner clearfix' . $containercls . '">';
    $output .= theme_mb2nl_shortcodes_swiper_nav($sliderid);
    $output .= '<div class="mb2-pb-content-list' . $listcls . '"></div>'; // Content loaded via js.
    $output .= theme_mb2nl_shortcodes_swiper_pagenavnav();
    $output .= '</div>'; // ...mb2-pb-content-inner
    $output .= '</div>'; // ...mb2-pb-events

    return $output;

}
