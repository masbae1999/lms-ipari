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

mb2_add_shortcode('mb2pb_footertools', 'mb2_shortcode_mb2pb_footertools');

/**
 *
 * Method to define shortcode
 *
 * @return HTML
 */
function mb2_shortcode_mb2pb_footertools($atts, $content = null) {
    global $OUTPUT;

    $atts2 = [
        'id' => 'footertools',
        'mt' => 0,
        'mb' => 30,
        'sizerem' => 1,
        'color' => '',
        'custom_class' => '',
        'template' => '',
    ];

    $a = mb2_shortcode_atts($atts2, $atts);

    $output = '';
    $elstyle = '';
    $cls = '';

    $elstyle .= ' style="';
    $elstyle .= 'margin-top:' .  $a['mt'] . 'px;';
    $elstyle .= 'margin-bottom:' .  $a['mb'] . 'px;';
    $elstyle .= $a['color'] ? 'color:' .  $a['color'] . ';' : '';
    $elstyle .= 'font-size:' .  $a['sizerem'] . 'rem;';
    $elstyle .= '"';

    $cls .= $a['custom_class'] ? ' ' . $a['custom_class'] : '';

    $tmplcls = $a['template'] ? ' mb2-pb-template-' . $a['id'] : '';

    $output .= '<div class="mb2-pb-element mb2-pb-footertools' . $tmplcls . $cls . '"' .
    theme_mb2nl_page_builder_el_datatts($atts, $atts2) . $elstyle . '>';
    $output .= '<div class="element-helper"></div>';
    $output .= theme_mb2nl_page_builder_el_actions('element', 'footertools', ['copy' => 0]);

    // TO DO: check what is the reason on PERFORMANCEINFO... on customize page.
    // The code below is only workaround.
    $footercontent = theme_mb2nl_get_footertools();
    $preformancestr = '%%PERFORMANCEINFO-' . sesskey() . '%%';

    $output .= str_replace($preformancestr, '', $footercontent);
    $output .= '</div>';

    return $output;

}
