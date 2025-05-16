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

mb2_add_shortcode('coursetabs', 'mb2_shortcode_coursetabs');


/**
 *
 * Method to define shortcode
 *
 * @return HTML
 */
function mb2_shortcode_coursetabs($atts, $content= null) {

    global $PAGE, $CFG;

    $atts2 = [
        'limit' => 12,
        'catids' => '',
        'excats' => 0,
        'filtertype' => 'category',
        'tagids' => '',
        'extags' => 0,
        'columns' => 4,
        'gutter' => 'normal',
        'custom_class' => '',
        'mt' => 0,
        'mb' => 30,

        'cistyle' => 'n',
        'crounded' => 1,

        'catdesc' => 0,
        'coursecount' => 0,
        'lazy' => 1,

        'carousel' => 0,
        'sloop' => 0,
        'snav' => 1,
        'sdots' => 0,
        'autoplay' => 0,
        'pausetime' => 5000,
        'animtime' => 450,

        'tabstyle' => 1,
        'acccolor' => '',
        'tcolor' => '',
        'tcenter' => 0,
    ];

    $a = mb2_shortcode_atts($atts2, $atts);

    $output = '';
    $cls = '';
    $style = '';

    $cls .= ' coursecount' . $a['coursecount'];

    $elopts = theme_mb2nl_page_builder_2arrays($atts, $atts2);

    // Disable touch move for quick view courses.
    if (theme_mb2nl_theme_setting($PAGE, 'quickview')) {
        $elopts['touchmove'] = 0;
    }

    $carouseldata = theme_mb2nl_shortcodes_slider_data($elopts);
    $categories = theme_mb2nl_get_categories(true);

    // Define uniq id.
    $elopts['uniqid'] = uniqid('carousetabs_');

    if ($a['mt'] || $a['mb']) {
        $style .= ' style="';
        $style .= $a['mt'] ? 'margin-top:' . $a['mt'] . 'px;' : '';
        $style .= $a['mb'] ? 'margin-bottom:' . $a['mb'] . 'px;' : '';
        $style .= '"';
    }

    $output .= '<div class="mb2-pb-content mb2-pb-coursetabs clearfix' . $cls . '"' .
    $style . $carouseldata . ' data-carousel="' . $a['carousel'] . '" data-limit="' .
    $a['limit'] . '" data-catdesc="' . $a['catdesc'] . '" data-cistyle="' .
    $a['cistyle'] . '" data-crounded="' . $a['crounded'] . '" data-filtertype="' .$a['filtertype'] . '" data-rooturl="' .
    $CFG->wwwroot . theme_mb2nl_themedir() . '" data-sesskey="' . sesskey() . '">';
    $output .= '<div class="mb2-pb-content-inner clearfix">';
    $output .= theme_mb2nl_coursetabs_tabs($elopts);
    $output .= theme_mb2nl_coursetabs_courses($elopts);
    $output .= '</div>'; // ...mb2-pb-content-inner
    $output .= '</div>'; // ...mb2-pb-coursetabs

    return $output;

}
