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

mb2_add_shortcode('select', 'shortcode_select');
mb2_add_shortcode('select_item', 'shortcode_select_item');

/**
 *
 * Method to define shortcode
 *
 * @return HTML
 */
function shortcode_select($atts, $content = null) {

    global $PAGE, $gl0selectimage;

    $atts2 = [
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
    $btnstyle = '';
    $output = '';
    $cls = '';
    $btncls = '';
    $btnid = uniqid('select_');
    $istarget = $a['target'] ? ' target="_blank"' : '';

    $gl0selectimage = $a['image'];

    $cls .= $a['custom_class'] ? ' ' . $a['custom_class'] : '';
    $cls .= ' isimage'. $a['image'];
    $cls .= ' layout' . $a['layout'];
    $cls .= ' label' . $a['label'];
    $cls .= ' center' . $a['center'];

    $buttoncls = ' rounded' . $a['btnrounded'];

    $btncls .= ' rounded' . $a['btnrounded'];
    $btncls .= ' btnborder' . $a['btnborder'];
    $btncls .= ' fw' . $a['btnfwcls'];
    $btncls .= ' type' . $a['btntype'];

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
            $content .= '[select_item link="#" itemtext="Select text" ]Select text[/select_item]';
        }
    }

    // Get first element.
    $regex = '\\[(\\[?)(select_item)\\b([^\\]\\/]*(?:\\/(?!\\])[^\\]\\/]*)*?)';
    $regex .= '(?:(\\/)\\]|\\](?:([^\\[]*+(?:\\[(?!\\/\\2\\])[^\\[]*+)*+)\\[\\/\\2\\])?)(\\]?)';
    preg_match_all("/$regex/is", $content, $match);
    $ismatch = str_replace('"]', '" ]', $match[0][0]);
    $firstitem = shortcode_parse_atts($ismatch);

    $dtext = $firstitem['itemtext'] ? $firstitem['itemtext'] : 'Select text';
    $dlink = $firstitem['link'] ? $firstitem['link'] : '#';
    $dimage = isset($firstitem['image']) ? $firstitem['image'] : '';

    $output .= '<div id="' . $btnid . '" class="mb2-pb-select' . $cls . '"' .
    $styleattr . ' data-target="' . $a['target'] . '">';

    $output .= $a['label'] ? '<div class="select-label"><span class="labeltext">' .
    theme_mb2nl_format_str($a['labeltext']) . '</span></div>' : '';
    $output .= '<div class="select-container">';
    $output .= '<div class="select-dropdown">';
    $output .= '<button type="button" id="' . $btnid . '_btn" class="mb2-pb-select-btn' .
    $buttoncls . theme_mb2nl_bsfcls(2, '', '', 'center') . '" tabindex="-1">';
    $output .= $a['image'] && $dimage ? '<span class="select-btn-image mr-2' .
    theme_mb2nl_bsfcls(2, '', '', 'center') . '" aria-hidden="true"><img class="select-image lazy" src="' .
    theme_mb2nl_lazy_plc() . '" data-src="' . $dimage . '" alt="' . $dtext . '"></span>' : '';
    $output .= '<span class="select-btn-text">' . $dtext . '</span>';
    $output .= '<span class="select-btn-arrow ml-auto" aria-hidden="true"></span>';
    $output .= '</button>';
    $output .= '<div id="' . $btnid . '_items" class="select-items-container" data-id="' . $btnid . '" tabindex="-1">';
    $output .= '<ul>';
    $output .= mb2_do_shortcode($content);
    $output .= '</ul>';
    $output .= '</div>';
    $output .= '</div>'; // ...select-dropdown

    $output .= '<div class="select-button">';
    $output .= '<a href="' . $dlink . '" class="mb2-pb-btn lhsmall' . $btncls . '"' . $istarget . $btnstyle . '>' .
    theme_mb2nl_format_str($a['btntext']) . '</a>';
    $output .= '</div>'; // ...select-button
    $output .= '</div>'; // ...select-container
    $output .= '</div>';

    $PAGE->requires->js_call_amd('theme_mb2nl/select', 'selectInit', [$btnid]);

    return $output;

}



/**
 *
 * Method to define shortcode
 *
 * @return HTML
 */
function shortcode_select_item($atts, $content = null) {

    global $gl0selectimage;

    $atts2 = [
        'link' => '#',
        'image' => '',
        'itemtext' => 'Select text',
    ];

    $a = mb2_shortcode_atts($atts2, $atts);

    $output = '';

    $output .= '<li class="mb2-pb-select_item position-relative' .
    theme_mb2nl_bsfcls(1, '', '', 'center') . '" data-link="' . $a['link'] . '" tabindex="-1">';
    $output .= '<div class="select-item-inner lhsmall' . theme_mb2nl_bsfcls(2, '', '', 'center') . '">';
    $output .= $gl0selectimage && $a['image'] ? '<img class="select-image lazy mr-2" src="' .
    theme_mb2nl_lazy_plc() . '" data-src="' . $a['image'] . '" alt="' . $a['itemtext'] . '">' : '';
    $output .= '<span class="select-text">' . theme_mb2nl_format_str($a['itemtext']) . '</span>';
    $output .= '</div>'; // ...select-item-inner
    $output .= '</li>';

    return $output;

}
