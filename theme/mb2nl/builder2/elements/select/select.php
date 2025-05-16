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

mb2_add_shortcode('mb2pb_select', 'mb2pb_shortcode_select');
mb2_add_shortcode('mb2pb_select_item', 'mb2pb_shortcode_select_item');

/**
 *
 * Method to define shortcode
 *
 * @return HTML
 */
function mb2pb_shortcode_select($atts, $content = null) {

    $atts2 = [
        'id' => 'select',
        'custom_class' => '',
        'image' => 1,
        'layout' => 'h',
        'label' => 1,
        'labeltext' => 'Choose an option:',
        'btntext' => 'Submit',
        'btntype' => 'primary',

        'selecth' => 54,
        'selectmh' => 80, // ...%
        'swidth' => 300,
        'center' => 0,

        'fs' => 1,

        'btncolor' => '',
        'btnbgcolor' => '',
        'btnbghcolor' => '',
        'btnhcolor' => '',
        'btnborcolor' => '',
        'btnborhcolor' => '',

        'target' => 0,
        'btnrounded' => 0,
        'btnborder' => 0,
        'btnfwcls' => 'global',
        'mt' => 0,
        'mb' => 30,
        'template' => '',
    ];

    $a = mb2_shortcode_atts($atts2, $atts);

    $styleattr = '';
    $output = '';
    $cls = '';
    $btncls = '';
    $btnstyle = '';

    $cls .= $a['custom_class'] ? ' ' . $a['custom_class'] : '';
    $cls .= ' isimage'. $a['image'];
    $cls .= ' layout' . $a['layout'];
    $cls .= ' label' . $a['label'];
    $cls .= ' center' . $a['center'];
    $templatecls = $a['template'] ? ' mb2-pb-template-select' : '';

    $btncls .= ' rounded' . $a['btnrounded'];
    $btncls .= ' btnborder' . $a['btnborder'];
    $btncls .= ' fw' . $a['btnfwcls'];
    $btncls .= ' type' . $a['btntype'];

    $buttoncls = ' rounded' . $a['btnrounded'];

    $styleattr .= ' style="';
    $styleattr .= 'margin-top:' . $a['mt'] . 'px;';
    $styleattr .= 'margin-bottom:' . $a['mb'] . 'px;';

    $styleattr .= '--mb-pb-selecth:' . $a['selecth'] . 'px;';
    $styleattr .= '--mb-pb-selectmh:' . $a['selectmh'] . ';';
    $styleattr .= '--mb-pb-selectfs:' . $a['fs'] . 'rem;';
    $styleattr .= '--mb-pb-swidth:' . $a['swidth'] . 'px;';
    $styleattr .= '"';

    $btnstyle .= ' style="';
    $btnstyle .= $a['btncolor'] ? '--mb2-pb-btn-color:' . $a['btncolor'] . ';' : '';
    $btnstyle .= $a['btnbgcolor'] ? '--mb2-pb-btn-bgcolor:' . $a['btnbgcolor'] . ';' : '';
    $btnstyle .= $a['btnbghcolor'] ? '--mb2-pb-btn-bghcolor:' . $a['btnbghcolor'] . ';' : '';
    $btnstyle .= $a['btnhcolor'] ? '--mb2-pb-btn-hcolor:' . $a['btnhcolor'] . ';' : '';
    $btnstyle .= $a['btnborcolor'] ? '--mb2-pb-btn-borcolor:' . $a['btnborcolor'] . ';' : '';
    $btnstyle .= $a['btnborhcolor'] ? '--mb2-pb-btn-borhcolor:' . $a['btnborhcolor'] . ';' : '';
    $btnstyle .= '"';

    $content = $content;

    if (! $content) {
        for ($i = 1; $i <= 3; $i++) {
            $content .= '[mb2pb_select_item image="' .
            theme_mb2nl_dummy_image('100x100') . '" link="#" itemtext="Select text" ]Select text[/mb2pb_select_item]';
        }
    }

    // Get first element.
    $regex = '\\[(\\[?)(mb2pb_select_item)\\b([^\\]\\/]*(?:\\/(?!\\])[^\\]\\/]*)*?)';
    $regex .= '(?:(\\/)\\]|\\](?:([^\\[]*+(?:\\[(?!\\/\\2\\])[^\\[]*+)*+)\\[\\/\\2\\])?)(\\]?)';
    preg_match_all("/$regex/is", $content, $match);
    $firstitem = shortcode_parse_atts($match[0][0]);

    $dtext = $firstitem['itemtext'] ? $firstitem['itemtext'] : 'Select text';
    $dlink = $firstitem['link'] ? $firstitem['link'] : '#';
    $dimage = $firstitem['image'] ? $firstitem['image'] : theme_mb2nl_dummy_image('100x100');

    $output .= '<div class="mb2-pb-element mb2-pb-select' . $templatecls . $cls . '"' .
    $styleattr . theme_mb2nl_page_builder_el_datatts($atts, $atts2) . '>';
    $output .= '<div class="element-helper"></div>';
    $output .= theme_mb2nl_page_builder_el_actions('element', 'select');

    $output .= '<div class="select-label"><span class="labeltext">' . $a['labeltext'] . '</span></div>';
    $output .= '<div class="select-container">';
    $output .= '<div class="select-dropdown">';
    $output .= '<button type="button" class="mb2-pb-select-btn' . $buttoncls . theme_mb2nl_bsfcls(2, '', '', 'center') . '">';
    $output .= '<span class="select-btn-image mr-2" aria-hidden="true"><img class="select-image" src="' . $dimage . '"></span>';
    $output .= '<span class="select-btn-text">' . $dtext . '</span>';
    $output .= '<span class="select-btn-arrow ml-auto" aria-hidden="true"></span>';
    $output .= '</button>';
    $output .= '</div>'; // ...select-dropdown
    $output .= '<div class="select-button">';
    $output .= '<a href="#" class="mb2-pb-btn lhsmall' . $btncls . '"' . $btnstyle . '>' . $a['btntext'] . '</a>';
    $output .= '</div>'; // ...select-button
    $output .= '</div>'; // ...select-container
    $output .= '<div class="mb2-pb-sortable-subelements select-items-container">';
    $output .= mb2_do_shortcode($content);
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
function mb2pb_shortcode_select_item($atts, $content = null) {

    $atts2 = [
        'id' => 'select_item',
        'link' => '#',
        'image' => theme_mb2nl_dummy_image('100x100'),
        'itemtext' => 'Select text',
        'template' => '',
    ];

    $a = mb2_shortcode_atts($atts2, $atts);

    $output = '';

    $output .= '<div class="mb2-pb-subelement mb2-pb-select_item"' .
    theme_mb2nl_page_builder_el_datatts($atts, $atts2) . '>';
    $output .= theme_mb2nl_page_builder_el_actions('subelement');
    $output .= '<div class="subelement-helper"></div>';
    $output .= '<div class="select-item-inner">';
    $output .= '<img class="select-image mr-2" src="' . $a['image'] . '">';
    $output .= '<span class="select-text">' . $a['itemtext'] . '</span>';
    $output .= '</div>'; // ...select-item
    $output .= '</div>';

    return $output;

}
