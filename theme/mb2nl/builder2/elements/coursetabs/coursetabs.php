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

mb2_add_shortcode('mb2pb_coursetabs', 'mb2_shortcode_mb2pb_coursetabs');

/**
 *
 * Method to define shortcode
 *
 * @return HTML
 */
function mb2_shortcode_mb2pb_coursetabs($atts, $content=null) {

    $atts2 = [
        'id' => 'coursetabs',
        'limit' => 12,
        'filtertype' => 'category',
        'catids' => '',
        'excats' => 0,
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

        'template' => '',
    ];

    $a = mb2_shortcode_atts($atts2, $atts);

    global $PAGE, $CFG;

    $output = '';
    $cls = '';
    $style = '';

    $elopts = theme_mb2nl_page_builder_2arrays($atts, $atts2);
    $elopts['lazy'] = 0;
    $categories = theme_mb2nl_get_categories(true);

    // Define builder option.
    // It's required to init carousel by builder or theme script.
    $elopts['builder'] = 1;

    $cls .= ' coursecount' . $a['coursecount'];
    $cls .= $a['template'] ? ' mb2-pb-template-coursetabs' : '';

    if ($a['mt'] || $a['mb']) {
        $style .= ' style="';
        $style .= $a['mt'] ? 'margin-top:' . $a['mt'] . 'px;' : '';
        $style .= $a['mb'] ? 'margin-bottom:' . $a['mb'] . 'px;' : '';
        $style .= '"';
    }

    // Define uniq id.
    $elopts['uniqid'] = uniqid('carousetabs_');

    $output .= '<div class="mb2-pb-content mb2-pb-element mb2-pb-coursetabs clearfix' . $cls . '"' .
    $style . theme_mb2nl_page_builder_el_datatts($atts, $atts2) . '>';
    $output .= '<div class="element-helper"></div>';
    $output .= theme_mb2nl_page_builder_el_actions('element', 'coursetabs');
    $output .= '<div class="mb2-pb-content-inner mb2-pb-element-inner clearfix">';

    $output .= theme_mb2nl_coursetabs_tabs($elopts);
    $output .= theme_mb2nl_coursetabs_courses($elopts);

    $output .= '</div>'; // ...mb2-pb-content-inner
    $output .= '</div>'; // ...mb2-pb-element

    return $output;

}
