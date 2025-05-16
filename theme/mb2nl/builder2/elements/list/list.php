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

mb2_add_shortcode('mb2pb_list', 'mb2pb_shortcode_list');
mb2_add_shortcode('mb2pb_list_item', 'mb2pb_shortcode_list_item');

/**
 *
 * Method to define shortcode
 *
 * @return HTML
 */
function mb2pb_shortcode_list($atts, $content = null) {

    $atts2 = [
        'id' => 'list',
        'style' => 'disc',
        'horizontal' => 0,
        'align' => 'none',
        'custom_class' => '',

        'fwcls' => 'global',
        'fsize' => 1,
        'lhcls' => 'global',
        'upper' => 0,

        'color' => '',
        'hcolor' => '',
        'mt' => 0,
        'mb' => 30,

        'gap' => .8,
        'pl' => 0,

        'template' => '',
    ];

    $a = mb2_shortcode_atts($atts2, $atts);

    $styleattr = '';
    $liststyle = '';
    $output = '';
    $cls = '';

    $cls .= ' horizontal' . $a['horizontal'];
    $cls .= ' list-' . $a['align'];
    $cls .= ' list-' . $a['style'];
    $cls .= ' fw' . $a['fwcls'];
    $cls .= ' lh' . $a['lhcls'];
    $cls .= ' upper' . $a['upper'];

    $cls .= $a['custom_class'] ? ' ' . $a['custom_class'] : '';

    $templatecls = $a['template'] ? ' mb2-pb-template-list' : '';

    $listtag = $a['style'] === 'number' ? 'ol' : 'ul';

    $styleattr .= ' style="';
    $styleattr .= $a['mt'] ? 'margin-top:' . $a['mt'] . 'px;' : '';
    $styleattr .= $a['mb'] ? 'margin-bottom:' . $a['mb'] . 'px;' : '';
    $styleattr .= '"';

    $liststyle .= ' style="';
    $liststyle .= $a['color'] ? '--mb2-pb-listcolor:' . $a['color'] . ';' : '';
    $liststyle .= $a['hcolor'] ? '--mb2-pb-listhcolor:' . $a['hcolor'] . ';' : '';
    $liststyle .= '--mb2-pb-listgap:' . $a['gap'] .'rem;';
    $liststyle .= '--mb2-pb-listpl:' . $a['pl'] .'rem;';
    $liststyle .= 'font-size:' . $a['fsize'] .'rem;';
    $liststyle .= '"';

    $content = $content;

    if (! $content) {
        for ($i = 1; $i <= 3; $i++) {
            $content .= '[mb2pb_list_item]List content here.[/mb2pb_list_item]';
        }
    }

    $output .= '<div class="mb2-pb-element mb2-pb-list' . $templatecls . '"' . $styleattr .
    theme_mb2nl_page_builder_el_datatts($atts, $atts2) . '>';
    $output .= '<div class="element-helper"></div>';
    $output .= theme_mb2nl_page_builder_el_actions('element', 'list');
    $output .= '<' . $listtag . ' class="theme-list mb2-pb-sortable-subelements' . $cls . '"' . $liststyle . '>';
    $output .= mb2_do_shortcode($content);
    $output .= '</' . $listtag . '>';
    $output .= '</div>';

    return $output;

}



/**
 *
 * Method to define shortcode
 *
 * @return HTML
 */
function mb2pb_shortcode_list_item($atts, $content = null) {

    $atts2 = [
        'id' => 'list_item',
        'link' => '',
        'link_target' => '',
        'template' => '',
    ];

    $a = mb2_shortcode_atts($atts2, $atts);

    $output = '';

    $content = ! $content ? 'List content here.' : $content;
    $atts2['text'] = $content;

    $output .= '<li class="mb2-pb-subelement mb2-pb-list_item"' .
    theme_mb2nl_page_builder_el_datatts($atts, $atts2) . '>';
    $output .= theme_mb2nl_page_builder_el_actions('subelement');
    $output .= '<div class="subelement-helper"></div>';
    $output .= '<a href="#" class="llink list-text">' . urldecode($content) . '</a>';
    $output .= '</li>';

    return $output;

}
