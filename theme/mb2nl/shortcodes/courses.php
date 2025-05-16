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

mb2_add_shortcode('courses', 'mb2_shortcode_courses');

/**
 *
 * Method to define shortcode
 *
 * @return HTML
 */
function mb2_shortcode_courses($atts, $content= null) {

    global $CFG, $PAGE;

    $atts2 = [
        'limit' => 8,
        'catids' => '',
        'courseids' => '',
        'excourses' => 0,

        'tagids' => '',
        'extags' => 0,

        'instids' => '',
        'exinst' => 0,

        'lazy' => 0,
        'mobcolumns' => 0,
        'excats' => 0,
        'carousel' => 0,
        'columns' => 3,
        'sdots' => 0,
        'sloop' => 0,
        'snav' => 1,
        'sautoplay' => 1,
        'autoplay' => 0,
        'spausetime' => 5000,
        'pausetime' => 5000,
        'sanimate' => 600,
        'animtime' => 600,
        'desclimit' => 25,
        'titlelimit' => 6,
        'gridwidth' => 'normal',
        'gutter' => 'normal',
        'linkbtn' => 0,
        'btntext' => '',
        'prestyle' => 'none',
        'custom_class' => '',
        'colors' => '',

        'cistyle' => 'n',
        'crounded' => 1,
        'mt' => 0,
        'mb' => 30,

        'coursestudentscount' => 1,
        'coursinstructor' => 1,
        'courseprice' => 1,
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

    // Disable touch move for quick view courses.
    if (theme_mb2nl_theme_setting($PAGE, 'quickview')) {
        $courseopts['touchmove'] = 0;
    }

    $sliderdata = theme_mb2nl_shortcodes_slider_data($courseopts);

    // Carousel layout.
    $listcls .= $a['carousel'] ? ' swiper-wrapper' : '';
    $listcls .= !$a['carousel'] ? ' theme-boxes theme-col-' . $a['columns'] : '';
    $listcls .= !$a['carousel'] ? ' gutter-' . $a['gutter'] : '';

    $containercls = $a['carousel'] ? ' swiper' : '';

    $cls .= ' prestyle' . $a['prestyle'];

    if ($a['mt'] || $a['mb']) {
        $style .= ' style="';
        $style .= $a['mt'] ? 'margin-top:' . $a['mt'] . 'px;' : '';
        $style .= $a['mb'] ? 'margin-bottom:' . $a['mb'] . 'px;' : '';
        $style .= '"';
    }

    $output .= '<div class="mb2-pb-content mb2-pb-courses clearfix' . $cls . '"' .
    $style . $sliderdata . ' data-i2load="courses" data-options="' .
    urlencode(serialize($courseopts)) . '" data-sesskey="' . sesskey() . '">';
    $output .= '<div id="' . $sliderid . '" class="mb2-pb-content-inner clearfix' . $containercls . '">';
    $output .= $a['carousel'] ? theme_mb2nl_shortcodes_swiper_nav($sliderid) : '';
    $output .= '<div class="mb2-pb-content-list' . $listcls . '">';
    $output .= theme_mb2nl_preload();
    $output .= '</div>'; // ...mb2-pb-content-list
    $output .= $a['carousel'] ? theme_mb2nl_shortcodes_swiper_pagenavnav() : '';
    $output .= '</div>'; // ...mb2-pb-content-inner
    $output .= '</div>'; // ...mb2-pb-content

    return $output;

}
