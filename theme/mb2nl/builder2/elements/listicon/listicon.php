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

mb2_add_shortcode('mb2pb_listicon', 'mb2pb_shortcode_mb2pb_listicon');
mb2_add_shortcode('mb2pb_listicon_item', 'mb2pb_shortcode_mb2pb_listicon_item');

/**
 *
 * Method to define shortcode
 *
 * @return HTML
 */
function mb2pb_shortcode_mb2pb_listicon($atts, $content = null) {

    global $gl0listicon,
    $gl0listbgcolor,
    $gl0listiconcolor,
    $gl0listtextcolor,
    $gl0listbordercolor,
    $gl0listborderw;

    $atts2 = [
        'id' => 'listicon',
        'style' => 'disc',
        'icon' => 'fa fa-check-square-o',
        'bgcolor' => '',
        'iconcolor' => '',
        'textcolor' => '',
        'border' => 0,
        'borderw' => 2,
        'bordercolor' => '',
        'horizontal' => 0,
        'align' => 'none',
        'fwcls' => 'global',
        'isize' => 2.65,
        'space' => 0.45,
        'iconbg' => 1,
        'fs' => 1,
        'custom_class' => '',
        'mt' => 0,
        'mb' => 30,
        'template' => '',
    ];

    $a = mb2_shortcode_atts($atts2, $atts);

    $gl0listicon = $a['icon'] ? $a['icon'] : 'fa fa-check-square-o';
    $gl0listbgcolor = $a['bgcolor'];
    $gl0listiconcolor = $a['iconcolor'];
    $gl0listtextcolor = $a['textcolor'];
    $gl0listbordercolor = $a['bordercolor'];
    $gl0listborderw = $a['borderw'];

    $styleattr = '';
    $liststyle = '';
    $output = '';
    $cls = '';

    $cls .= ' iconbg' . $a['iconbg'];
    $cls .= ' horizontal' . $a['horizontal'];
    $cls .= ' border' . $a['border'];
    $cls .= ' fw' . $a['fwcls'];
    $cls .= ' align' . $a['align'];
    $cls .= $a['custom_class'] ? ' ' . $a['custom_class'] : '';

    $templatecls = $a['template'] ? ' mb2-pb-template-listicon' : '';

    $styleattr .= ' style="';
    $styleattr .= $a['mt'] ? 'margin-top:' . $a['mt'] . 'px;' : '';
    $styleattr .= $a['mb'] ? 'margin-bottom:' . $a['mb'] . 'px;' : '';
    $styleattr .= '"';

    $liststyle .= ' style="';
    $liststyle .= '--mb2-pb-listicon-fs:' . $a['fs'] . 'rem;';
    $liststyle .= '--mb2-pb-listicon-isize:' . $a['isize'] . 'rem;';
    $liststyle .= '--mb2-pb-listicon-space:' . $a['space'] . 'rem;';
    $liststyle .= '"';

    $content = $content;

    if (! $content) {
        for ($i = 1; $i <= 3; $i++) {
            $content .= '[mb2pb_listicon_item]List content here.[/mb2pb_listicon_item]';
        }
    }

    $output .= '<div class="mb2-pb-element mb2-pb-listicon' . $templatecls . '"' .
    $styleattr . theme_mb2nl_page_builder_el_datatts($atts, $atts2) . '>';
    $output .= '<div class="element-helper"></div>';
    $output .= theme_mb2nl_page_builder_el_actions('element', 'listicon');
    $output .= '<ul class="theme-listicon mb2-pb-sortable-subelements' . $cls . '"' . $liststyle . '>';
    $output .= mb2_do_shortcode($content);
    $output .= '</ul>';
    $output .= '</div>';

    return $output;

}



/**
 *
 * Method to define shortcode
 *
 * @return HTML
 */
function mb2pb_shortcode_mb2pb_listicon_item($atts, $content = null) {

    global $gl0listicon,
    $gl0listbgcolor,
    $gl0listiconcolor,
    $gl0listtextcolor,
    $gl0listbordercolor,
    $gl0listborderw;

    $atts2 = [
        'id' => 'listicon_item',
        'icon' => '',
        'bgcolor' => '',
        'iconcolor' => '',
        'textcolor' => '',
        'bordercolor' => '',
        'link' => '',
        'link_target' => 0,
        'template' => '',
    ];

    $a = mb2_shortcode_atts($atts2, $atts);

    $output = '';
    $iconstyle = '';
    $textstyle = '';
    $a['icon'] = $a['icon'] ? $a['icon'] : $gl0listicon;
    $a['bgcolor'] = $a['bgcolor'] ? $a['bgcolor'] : $gl0listbgcolor;
    $a['iconcolor'] = $a['iconcolor'] ? $a['iconcolor'] : $gl0listiconcolor;
    $a['textcolor'] = $a['textcolor'] ? $a['textcolor'] : $gl0listtextcolor;
    $a['bordercolor'] = $a['bordercolor'] ? $a['bordercolor'] : $gl0listbordercolor;

    $iconstyle .= ' style="';
    $iconstyle .= $a['bgcolor'] ? 'background-color:' . $a['bgcolor'] . ';' : '';
    $iconstyle .= $a['iconcolor'] ? 'color:' . $a['iconcolor'] . ';' : '';
    $iconstyle .= '"';

    $textstyle .= ' style="';
    $textstyle .= 'color:' . $a['textcolor'] . ';';
    $textstyle .= 'border-bottom-width:' . $gl0listborderw . 'px;';
    $textstyle .= 'border-bottom-color:' . $a['bordercolor'] . ';';
    $textstyle .= '"';

    $content = ! $content ? 'List content here.' : $content;
    $atts2['text'] = $content;

    $output .= '<li class="mb2-pb-subelement mb2-pb-listicon_item"' .
    theme_mb2nl_page_builder_el_datatts($atts, $atts2) . '>';
    $output .= theme_mb2nl_page_builder_el_actions('subelement');
    $output .= '<div class="subelement-helper"></div>';
    $output .= '<div class="item-content">';
    $output .= '<span class="iconel' . theme_mb2nl_bsfcls(2, '', 'center', 'center') . '"' .
    $iconstyle . '><i class="' . $a['icon'] . '"></i></span>';
    $output .= '<span class="list-text"' . $textstyle . '>' . urldecode($content) . '</span>';
    $output .= '</div>';
    $output .= '</li>';

    return $output;

}
