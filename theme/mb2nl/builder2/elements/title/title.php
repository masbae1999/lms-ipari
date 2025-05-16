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

mb2_add_shortcode('mb2pb_title', 'mb2_shortcode_mb2pb_title');

/**
 *
 * Method to define shortcode
 *
 * @return HTML
 */
function mb2_shortcode_mb2pb_title($atts, $content = null) {

    $atts2 = [
        'id' => 'title',
        'tag' => 'h4',
        'align' => 'none',
        'issubtext' => 1,
        'subtext' => 'Subtext here',
        'size' => 'n',
        'sizerem' => 2,
        'fwcls' => 'global',
        'lhcls' => 'global',
        'lspacing' => 0,
        'wspacing' => 0,
        'color' => '',
        'upper' => 0,
        'style' => 1,
        'mt' => 0,
        'mb' => 30,
        'custom_class' => '',
        'template' => '',
    ];

    $a = mb2_shortcode_atts($atts2, $atts);

    $output = '';
    $cstyle = '';
    $tstyle = '';
    $cls = '';
    $tcls = '';

    $cls .= ' title-' . $a['align'];
    $cls .= ' title-' . $a['size'];
    $cls .= ' style-' . $a['style'];
    $cls .= ' issubtext' . $a['issubtext'];
    $cls .= $a['custom_class'] ? ' ' . $a['custom_class'] : '';

    $tcls .= ' upper' . $a['upper'];
    $tcls .= ' fw' . $a['fwcls'];
    $tcls .= ' lh' . $a['lhcls'];
    $tcls .= ' ' . theme_mb2nl_tsize_cls($a['sizerem']);

    $tmplcls = $a['template'] ? ' mb2-pb-template-title' : '';

    if ($a['mt'] || $a['mb']) {
        $cstyle .= ' style="';
        $cstyle .= $a['mt'] ? 'margin-top:' . $a['mt'] . 'px;' : '';
        $cstyle .= $a['mb'] ? 'margin-bottom:' . $a['mb'] . 'px;' : '';
        $cstyle .= '"';
    }

    if ($a['sizerem'] || $a['color'] || $a['lspacing'] != 0 || $a['wspacing'] != 0) {
        $tstyle .= ' style="';
        $tstyle .= 'font-size:' . $a['sizerem'] . 'rem;';
        $tstyle .= $a['lspacing'] != 0 ? 'letter-spacing:' . $a['lspacing'] . 'px;' : '';
        $tstyle .= $a['wspacing'] != 0 ? 'word-spacing:' . $a['wspacing'] . 'px;' : '';
        $tstyle .= $a['color'] ? 'color:' . $a['color'] . ';' : '';
        $tstyle .= '"';
    }

    $content = $content ? $content : 'Heading text here';
    $atts2['content'] = $content;

    $output .= '<div class="mb2-pb-element mb2pb-title' . $tmplcls . '"' . $cstyle .
    theme_mb2nl_page_builder_el_datatts($atts, $atts2) . '>';
    $output .= '<div class="element-helper"></div>';
    $output .= theme_mb2nl_page_builder_el_actions('element', 'title');
    $output .= '<div class="theme-title' . $cls . '">';
    $output .= '<' . $a['tag'] . ' class="title' . $tcls . '"' . $tstyle . '><span>';
    $output .= '<span class="title-text">' . urldecode($content) . '</span>';
    $output .= '</span></' . $a['tag'] . '>';
    $output .= '<span class="title-subtext">' . $a['subtext'] . '</span>';
    $output .= '</div>';
    $output .= '</div>';

    return $output;

}
