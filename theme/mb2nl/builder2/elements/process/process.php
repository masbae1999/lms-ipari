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

mb2_add_shortcode('mb2pb_process', 'mb2pb_shortcode_process');
mb2_add_shortcode('mb2pb_process_item', 'mb2pb_shortcode_process_item');

/**
 *
 * Method to define shortcode
 *
 * @return HTML
 */
function mb2pb_shortcode_process($atts, $content = null) {

    global $gl0boxmb,
    $gl0height,
    $gl0boxicontitlefs,
    $gl0boxicontitlefw,
    $gl0mb2pbprocessicon;

    $atts2 = [
        'id' => 'process',
        'columns' => 3, // ...lmax 5
        'gutter' => 'normal',
        'type' => 1,
        'rounded' => 0,
        'tfs' => 1.4,
        'tfw' => 'global',
        'labelpos' => 'left',
        'wave' => 0,
        'height' => 0,
        'mt' => 0,
        'mb' => 0, // 0 because box item has margin bottom 30 pixels.
        'boxmb' => 0,
        'desc' => 1,
        'icon' => 'fa fa-arrow-right',
        'isicon' => 0,
        'custom_class' => '',
        'template' => '',
    ];

    $a = mb2_shortcode_atts($atts2, $atts);

    $output = '';
    $cls = '';
    $style = '';

    $gl0boxmb = $a['boxmb'];
    $gl0height = $a['height'];
    $gl0boxicontitlefs = $a['tfs'];
    $gl0boxicontitlefw = $a['tfw'];
    $gl0mb2pbprocessicon = $a['icon'] ? $a['icon'] : 'fa fa-rocket';

    $cls .= ' gutter-' . $a['gutter'];
    $cls .= ' desc' . $a['desc'];
    $cls .= ' theme-col-' . $a['columns'];
    $cls .= ' rounded' . $a['rounded'];
    $cls .= ' isicon' . $a['isicon'];
    $cls .= ' wave' . $a['wave'];
    $cls .= ' type' . $a['type'];
    $cls .= ' labelpos' . $a['labelpos'];
    $cls .= $a['custom_class'] ? ' ' . $a['custom_class'] : '';
    $templatecls = $a['template'] ? ' mb2-pb-template-process' : '';

    $style .= ' style="';
    $style .= $a['mt'] ? 'margin-top:' . $a['mt'] . 'px;' : '';
    $style .= $a['mb'] ? 'margin-bottom:' . $a['mb'] . 'px;' : '';
    $style .= '"';

    $content = $content;

    if (! $content) {
        for ($i = 1; $i <= 3; $i++) {
            $content .= '[mb2pb_process_item title="Box title here" label="' .
            $i . '" ]Box content here.[/mb2pb_process_item]';
        }
    }

    $output .= '<div class="mb2-pb-element mb2-pb-process' . $templatecls . '"' .
    $style . theme_mb2nl_page_builder_el_datatts($atts, $atts2) . '>';
    $output .= '<div class="element-helper"></div>';
    $output .= theme_mb2nl_page_builder_el_actions('element', 'process');
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
 * Method to define shortcode
 *
 * @return HTML
 */
function mb2pb_shortcode_process_item($atts, $content = null) {

    global $gl0boxmb,
    $gl0height,
    $gl0boxicontitlefs,
    $gl0boxicontitlefw,
    $gl0mb2pbprocessicon;

    $atts2 = [
        'id' => 'process_item',
        'icon' => '',
        'title' => 'Box title here',
        'label' => 1,
        'link' => '',
        'color' => '',
        'bgcolor' => '',
        'link_target' => 0,
        'template' => '',
    ];

    $a = mb2_shortcode_atts($atts2, $atts);

    $output = '';
    $fcls = '';
    $boxstyle = '';
    $stylebg = '';
    $stylecolor = '';

    $content = ! $content ? 'Box content here.' : $content;
    $atts2['content'] = $content;

    $a['icon'] = $a['icon'] ? $a['icon'] : $gl0mb2pbprocessicon;

    if ($gl0boxmb || $gl0height) {
        $boxstyle .= ' style="';
        $boxstyle .= $gl0boxmb ? 'margin-bottom:' . $gl0boxmb . 'px;' : '';
        $boxstyle .= $gl0height ? 'min-height:' . $gl0height . 'px;' : '';
        $boxstyle .= '"';
    }

    if ($a['bgcolor']) {
        $stylebg .= ' style="';
        $stylebg .= 'background-color:' . $a['bgcolor'] . ';';
        $stylebg .= '"';
    }

    if ($a['color']) {
        $stylecolor .= ' style="';
        $stylecolor .= 'color:' . $a['color'] . ';';
        $stylecolor .= '"';
    }

    $fcls .= ' fw' . $gl0boxicontitlefw;

    $output .= '<div class="mb2-pb-subelement mb2-pb-process_item theme-box"' .
    theme_mb2nl_page_builder_el_datatts($atts, $atts2) . '>';
    $output .= theme_mb2nl_page_builder_el_actions('subelement');
    $output .= '<div class="subelement-helper"></div>';
    $output .= '<div class="mb2-pb-subelement-inner">';

    $output .= '<div class="boxprocess">';
    $output .= '<div class="boxprocess-inner"' . $boxstyle . '>';
    $output .= '<div class="boxprocess-label">';
    $output .= '<div class="label-content"' . $stylecolor . '>';
    $output .= '<div class="boxprocess-icon"><i class="' . $a['icon'] . '"></i></div>';
    $output .= '<div class="boxprocess-text">' . $a['label'] . '</div>';
    $output .= '<div class="colorel colorel1"' . $stylebg . '></div>';
    $output .= '<div class="colorel colorel2"' . $stylebg . '></div>';
    $output .= '<div class="colorel colorel3"' . $stylebg . '></div>';
    $output .= '</div>'; // ...llabel-content
    $output .= '</div>'; // ...lboxprocess-label
    $output .= '<div class="boxprocess-content">';
    $output .= '<h4 class="boxprocess-title' . $fcls . '" style="font-size:' . $gl0boxicontitlefs . 'rem;">';
    $output .= $a['title'];
    $output .= '</h4>';
    $output .= '<div class="boxprocess-desc">' . urldecode($content) . '</div>';
    $output .= '</div>'; // ...lboxprocess-content
    $output .= '</div>'; // ...lboxprocess-inner
    $output .= '</div>'; // ...lboxprocess
    $output .= '</div>'; // ...lmb2-pb-subelement-inner
    $output .= '</div>'; // ...lmb2-pb-process_item

    return $output;

}
