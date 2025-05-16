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

mb2_add_shortcode('reviews', 'mb2_shortcode_reviews');

/**
 *
 * Method to define shortcode
 *
 * @return HTML
 */
function mb2_shortcode_reviews($atts, $content= null) {

    global $PAGE;

    $atts2 = [
        'limit' => 8,
        'carousel' => 0,
        'columns' => 3,
        'userimg' => 0,
        'sloop' => 0,
        'snav' => 1,
        'sdots' => 0,
        'autoplay' => 0,
        'pausetime' => 5000,
        'animtime' => 450,
        'desclimit' => 25,
        'titlelimit' => 6,
        'gutter' => 'normal',
        'custom_class' => '',
        'mt' => 0,
        'mb' => 30,
    ];

    $a = mb2_shortcode_atts($atts2, $atts);

    $output = '';
    $cls = '';
    $listcls = '';
    $colcls = '';
    $style = '';
    $sliderid = uniqid('swiper_');

    // Set column style.
    $col = 0;
    $colstyle = '';
    $liststyle = '';

    $courseopts = theme_mb2nl_page_builder_2arrays($atts, $atts2);
    $sliderdata = theme_mb2nl_shortcodes_slider_data($courseopts);

    // Carousel layout.
    $listcls .= $a['carousel'] ? ' swiper-wrapper' : '';
    $listcls .= ! $a['carousel'] ? ' theme-boxes theme-col-' . $a['columns'] : '';
    $listcls .= ! $a['carousel'] ? ' gutter-' . $a['gutter'] : '';

    $containercls = $a['carousel'] ? ' swiper' : '';

    if ($a['mt'] || $a['mb']) {
        $style .= ' style="';
        $style .= $a['mt'] ? 'margin-top:' . $a['mt'] . 'px;' : '';
        $style .= $a['mb'] ? 'margin-bottom:' . $a['mb'] . 'px;' : '';
        $style .= '"';
    }

    $output .= '<div class="mb2-pb-content mb2-pb-reviews clearfix' . $cls . '"' .
    $style . $sliderdata . ' data-i2load="reviews" data-options="' .
    urlencode(serialize($courseopts)) . '" data-sesskey="' . sesskey() . '">';
    $output .= '<div id="' . $sliderid . '" class="mb2-pb-content-inner clearfix ' . $containercls . '">';
    $output .= $a['carousel'] ? theme_mb2nl_shortcodes_swiper_nav($sliderid) : '';
    $output .= '<div class="mb2-pb-content-list' . $listcls . '">';
    $output .= theme_mb2nl_preload();
    $output .= '</div>';
    $output .= $a['carousel'] ? theme_mb2nl_shortcodes_swiper_pagenavnav() : '';
    $output .= '</div>';
    $output .= '</div>';

    return $output;

}
