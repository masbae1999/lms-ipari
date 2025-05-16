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

mb2_add_shortcode('events', 'mb2_shortcode_events');

/**
 *
 * Method to define shortcode
 *
 * @return HTML
 */
function mb2_shortcode_events($atts, $content=null) {

    global $PAGE;

    $atts2 = [
        'type' => 0, // 0 = all, 1 = site events
        'upcoming' => 0,
        'limit' => 8,
        'image' => 1,
        'lazy' => 0,

        'color1' => '',
        'color2' => '',

        'columns' => 3,
        'sloop' => 0,
        'snav' => 1,
        'sdots' => 0,
        'autoplay' => 0,
        'pausetime' => 5000,
        'animtime' => 450,

        'layout' => 2, // 1 - list, 2 - columns, 3 - carousel
        'gutter' => 'normal',
        'custom_class' => '',
        'mt' => 0,
        'mb' => 30,
    ];

    $a = mb2_shortcode_atts($atts2, $atts);

    $output = '';
    $cls = '';
    $listcls = '';
    $style = '';
    $sliderid = uniqid('swiper_');
    $modalid = uniqid();

    $cls .= $a['custom_class'] ? ' ' . $a['custom_class'] : '';

    $options = theme_mb2nl_page_builder_2arrays($atts, $atts2);
    $sliderdata = theme_mb2nl_shortcodes_slider_data($options);

    // Carousel layout.
    $listcls .= $a['layout'] == 3 ? ' swiper-wrapper' : '';
    $listcls .= $a['layout'] == 2 ? ' theme-boxes theme-col-' . $a['columns'] : '';
    $listcls .= ' gutter-' . $a['gutter'];
    $listcls .= ' layout' . $a['layout'];

    $containercls = $a['layout'] == 3 ? ' swiper' : '';
    $supercls = ' gutter-' . $a['gutter'];

    if ($a['mt'] || $a['mb'] || $a['color1']) {
        $style .= ' style="';
        $style .= $a['mt'] ? 'margin-top:' . $a['mt'] . 'px;' : '';
        $style .= $a['mb'] ? 'margin-bottom:' . $a['mb'] . 'px;' : '';
        $style .= $a['color1'] ? '--mb2-pb-color1:' . $a['color1'] . ';' : '';
        $style .= $a['color2'] ? '--mb2-pb-color2:' . $a['color2'] . ';' : '';
        $style .= '"';
    }

    $output .= '<div class="mb2-pb-content mb2-pb-events clearfix' . $cls . '"' .
    $style . $sliderdata . ' data-i2load="events" data-options="' .
    urlencode(serialize($options)) . '" data-sesskey="' . sesskey() . '">';
    $output .= '<div id="' . $sliderid . '" class="mb2-pb-content-inner mb2-pb-element-inner clearfix' . $containercls . '">';
    $output .= $a['layout'] == 3 ? theme_mb2nl_shortcodes_swiper_nav($sliderid) : '';
    $output .= '<div class="mb2-pb-content-list' . $listcls . '">';
    $output .= theme_mb2nl_preload();
    $output .= '</div>'; // ...mb2-pb-content-list
    $output .= $a['layout'] == 3 ? theme_mb2nl_shortcodes_swiper_pagenavnav() : '';
    $output .= '</div>'; // ...mb2-pb-content-inner
    $output .= '<div class="mb2-pb-events-items"></div>';
    $output .= '</div>'; // ...mb2-pb-events

    $PAGE->requires->js_call_amd('theme_mb2nl/events', 'eventDetails');

    return $output;

}
