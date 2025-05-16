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

mb2_add_shortcode('mb2pb_boxes3d', 'mb2_shortcode_mb2pb_boxes3d');
mb2_add_shortcode('mb2pb_boxes3d_item', 'mb2_shortcode_mb2pb_boxes3d_item');

/**
 *
 * Method to define boxes 3D shortcode
 *
 * @return HTML
 */
function mb2_shortcode_mb2pb_boxes3d($atts, $content=null) {

    $atts2 = [
        'id' => 'boxes3d',
        'columns' => 2, // ...max 5
        'type' => 1,
        'mt' => 0,
        'mb' => 0, // 0 because box item has margin bottom 30 pixels
        'custom_class' => '',
        'rounded' => 0,
        'gutter' => 'normal',
        'template' => '',
    ];

    $a = mb2_shortcode_atts($atts2, $atts);

    $output = '';
    $style = '';
    $cls = '';

    $cls .= ' type-' . $a['type'];
    $cls .= ' gutter-' . $a['gutter'];
    $cls .= ' theme-col-' . $a['columns'];
    $cls .= ' rounded' . $a['rounded'];
    $cls .= $a['custom_class'] ? ' ' . $a['custom_class'] : '';
    $templatecls = $a['template'] ? ' mb2-pb-template-boxes3d' : '';

    if ($a['mt'] || $a['mb']) {
        $style .= ' style="';
        $style .= $a['mt'] ? 'margin-top:' . $a['mt'] . 'px;' : '';
        $style .= $a['mb'] ? 'margin-bottom:' . $a['mb'] . 'px;' : '';
        $style .= '"';
    }

    $content = $content;

    if (! $content) {
        $demoimage = theme_mb2nl_dummy_image('1600x944');

        for ($i = 1; $i <= 2; $i++) {
            $content .= '[mb2pb_boxes3d_item image="' .
            $demoimage . '" title="Box title here" ]Box content here[/mb2pb_boxes3d_item]';
        }
    }

    $output .= '<div class="mb2-pb-element mb2-pb-boxes3d' . $templatecls . '"' . $style .
    theme_mb2nl_page_builder_el_datatts($atts, $atts2) . '>';
    $output .= '<div class="element-helper"></div>';
    $output .= theme_mb2nl_page_builder_el_actions('element', 'boxes3d');
    $output .= '<div class="mb2-pb-element-inner theme-boxes' . $cls . '">';
    $output .= '<div class="mb2-pb-sortable-subelements">';
    $output .= mb2_do_shortcode($content);
    $output .= '</div>';
    $output .= '</div>';
    $output .= '</div>';

    return $output;

}


/**
 *
 * Method to define boxes 3D item shortcode
 *
 * @return HTML
 */
function mb2_shortcode_mb2pb_boxes3d_item($atts, $content = null) {

    $atts2 = [
        'id' => 'boxes3d_item',
        'image' => theme_mb2nl_dummy_image('1600x944'),
        'title' => 'Box title here',
        'link' => '',
        'link_target' => 0,
        'frontcolor' => '',
        'backcolor' => '',
        'template' => '',
    ];

    $a = mb2_shortcode_atts($atts2, $atts);

    $output = '';
    $titlecolorspan = '';

    $stylefront = $a['frontcolor'] !== '' ? ' style="background-color:' . $a['frontcolor'] . ';"' : '';
    $styleback = $a['backcolor'] !== '' ? ' style="background-color:' . $a['backcolor'] . ';"' : '';

    $content = ! $content ? 'Box content here' : $content;
    $atts2['text'] = $content;

    $output .= '<div class="mb2-pb-subelement mb2-pb-boxes3d_item theme-box"' .
    theme_mb2nl_page_builder_el_datatts($atts, $atts2) . '>';
    $output .= theme_mb2nl_page_builder_el_actions('subelement');
    $output .= '<div class="subelement-helper"></div>';
    $output .= '<div class="mb2-pb-subelement-inner">';
    $output .= '<div class="theme-box3d">';

    $output .= '<div class="box-scene">';

    $output .= '<div class="box-face box-front">';
    $output .= '<div class="vtable-wrapp">';
    $output .= '<div class="vtable">';
    $output .= '<div class="vtable-cell">';
    $output .= '<h4 class="box-title"><span class="box-title-text">' . theme_mb2nl_format_str($a['title']) . '</span></h4>';
    $output .= '</div>';
    $output .= '</div>';
    $output .= '</div>';
    $output .= '<img class="theme-box3d-img" src="' . $a['image'] . '" alt="">';
    $output .= '<div class="theme-box3d-color"' . $stylefront . '></div>';
    $output .= '</div>';

    $output .= '<div class="box-face box-back">';
    $output .= '<div class="vtable-wrapp">';
    $output .= '<div class="vtable">';
    $output .= '<div class="vtable-cell">';
    $output .= '<div class="box-desc-text">' . urldecode($content) . '</div>';
    $output .= '</div>';
    $output .= '</div>';
    $output .= '</div>';
    $output .= '<div class="theme-box3d-color"' . $styleback . '></div>';
    $output .= '</div>';

    $output .= '<img class="theme-box3d-img theme-box3d-imagenovisible" src="' . $a['image'] . '" alt="">';
    $output .= '</div>';

    $output .= '</div>';

    $output .= '</div>';
    $output .= '</div>';

    return $output;

}
