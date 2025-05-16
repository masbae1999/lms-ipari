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

mb2_add_shortcode('mb2pb_boxesicon', 'mb2pb_shortcode_boxesicon');
mb2_add_shortcode('mb2pb_boxesicon_item', 'mb2pb_shortcode_boxesicon_item');

/**
 *
 * Method to define boxes icon shortcode
 *
 * @return HTML
 */
function mb2pb_shortcode_boxesicon($atts, $content = null) {

    global $gl0bxbtntext,
    $gl0bxiconbtntype,
    $gl0bxiconbtnsize,
    $gl0bxiconbtnfwcls,
    $gl0bxiconbtnborder,
    $gl0bxiconbtnrounded,
    $gl0bxmb,
    $gl0bxheight,
    $gl0bxicontitlefs,
    $gl0bxicontitlefw,
    $gl0bxicontitlelh;

    $atts2 = [
        'id' => 'boxesicon',
        'boxid' => 'boxesicon',
        'columns' => 3, // ...max 5
        'gutter' => 'normal',
        'type' => 1,

        'color' => 'primary',
        'ccolor' => '',
        'icolor' => '',
        'bgcolor' => '',
        'tcolor' => '',
        'txcolor' => '',

        'rounded' => 0,

        'tfs' => 1.4,
        'tfw' => 'global',
        'tlh' => 'global',
        'wave' => 0,
        'height' => 0,
        'mt' => 0,
        'mb' => 0, // 0 because box item has margin bottom 30 pixels
        'boxmb' => 0,

        'linkbtn' => 0,
        'btntext' => '',
        'btntype' => 'primary',
        'btnsize' => 'normal',
        'btnfwcls' => 'global',
        'btnrounded' => 0,
        'btnborder' => 0,

        'desc' => 1,
        'custom_class' => '',
        'template' => '',
    ];

    $a = mb2_shortcode_atts($atts2, $atts);

    $output = '';
    $cls = '';
    $style = '';
    $bxstyle = '';

    $gl0bxbtntext = $a['btntext'];
    $gl0bxiconbtntype = $a['btntype'];
    $gl0bxiconbtnsize = $a['btnsize'];
    $gl0bxiconbtnfwcls = $a['btnfwcls'];
    $gl0bxiconbtnborder = $a['btnborder'];
    $gl0bxiconbtnrounded = $a['btnrounded'];
    $gl0bxmb = $a['boxmb'];
    $gl0bxheight = $a['height'];
    $gl0bxicontitlefs = $a['tfs'];
    $gl0bxicontitlefw = $a['tfw'];
    $gl0bxicontitlelh = $a['tlh'];

    $cls .= ' theme-' . $a['boxid'];
    $cls .= ' gutter-' . $a['gutter'];
    $cls .= ' linkbtn' . $a['linkbtn'];
    $cls .= ' desc' . $a['desc'];
    $cls .= ' theme-col-' . $a['columns'];
    $cls .= ' rounded' . $a['rounded'];
    $cls .= ' boxcolor-' . $a['color'];
    $cls .= ' wave' . $a['wave'];
    $cls .= ' type-' . $a['type'];
    $cls .= $a['custom_class'] ? ' ' . $a['custom_class'] : '';
    $templatecls = $a['template'] ? ' mb2-pb-template-boxesicon' : '';

    $style .= ' style="';
    $style .= $a['mt'] ? 'margin-top:' . $a['mt'] . 'px;' : '';
    $style .= $a['mb'] ? 'margin-bottom:' . $a['mb'] . 'px;' : '';
    $style .= '"';

    $bxstyle .= ' style="';
    $bxstyle .= $a['ccolor'] ? '--mb2-pb-bxaccolor:' . $a['ccolor'] . ';' : '';
    $bxstyle .= $a['icolor'] ? '--mb2-pb-bxicolor:' . $a['icolor'] . ';' : '';
    $bxstyle .= $a['bgcolor'] ? '--mb2-pb-bxbgcolor:' . $a['bgcolor'] . ';' : '';
    $bxstyle .= $a['tcolor'] ? '--mb2-pb-bxtcolor:' . $a['tcolor'] . ';' : '';
    $bxstyle .= $a['txcolor'] ? '--mb2-pb-bxtxcolor:' . $a['txcolor'] . ';' : '';
    $bxstyle .= '"';

    $content = $content;

    if (! $content) {
        $demoimage = theme_mb2nl_dummy_image('1600x1066');

        for ($i = 1; $i <= 3; $i++) {
            $content .= '[mb2pb_boxesicon_item title="Box title here" icon="fa fa-rocket" ]';
            $content .= 'Box content here.[/mb2pb_boxesicon_item]';
        }
    }

    $output .= '<div class="mb2-pb-element mb2-pb-boxesicon' . $templatecls . '"' . $style .
    theme_mb2nl_page_builder_el_datatts($atts, $atts2) . '>';
    $output .= '<div class="element-helper"></div>';
    $output .= theme_mb2nl_page_builder_el_actions('element', 'boxesicon');
    $output .= '<div class="mb2-pb-element-inner theme-boxes' . $cls . '"' . $bxstyle  . '>';
    $output .= '<div class="mb2-pb-sortable-subelements">';
    $output .= mb2_do_shortcode($content);
    $output .= '</div>';
    $output .= '</div>';
    $output .= '</div>';

    return $output;

}

/**
 *
 * Method to define boxes icon item shortcode
 *
 * @return HTML
 */
function mb2pb_shortcode_boxesicon_item($atts, $content = null) {

    global $gl0bxbtntext,
    $gl0bxiconbtntype,
    $gl0bxiconbtnsize,
    $gl0bxiconbtnfwcls,
    $gl0bxiconbtnborder,
    $gl0bxiconbtnrounded,
    $gl0bxmb,
    $gl0bxheight,
    $gl0bxicontitlefs,
    $gl0bxicontitlefw,
    $gl0bxicontitlelh;

    $atts2 = [
        'id' => 'boxesicon_item',
        'icon' => 'fa fa-rocket',
        'title' => 'Box title here',
        'link' => '',

        'ccolor' => '',
        'icolor' => '',
        'bgcolor' => '',
        'tcolor' => '',
        'txcolor' => '',

        'btncolor' => '',
        'btnbgcolor' => '',
        'btnbghcolor' => '',
        'btnhcolor' => '',
        'btnborcolor' => '',
        'btnborhcolor' => '',

        'link_target' => 0,
        'target' => '',
        'btntext' => '',
        'template' => '',
    ];

    $a = mb2_shortcode_atts($atts2, $atts);

    $output = '';
    $pref = '';
    $fcls = '';
    $boxstyle = '';
    $boxistyle = '';
    $btnstyle = '';

    $content = ! $content ? 'Box content here.' : $content;
    $atts2['content'] = $content;

    $boxstyle .= ' style="';
    $boxstyle .= $gl0bxmb ? 'margin-bottom:' . $gl0bxmb . 'px;' : '';
    $boxstyle .= $a['ccolor'] ? '--mb2-pb-bxaccolor:' . $a['ccolor'] . ';' : '';
    $boxstyle .= $a['icolor'] ? '--mb2-pb-bxicolor:' . $a['icolor'] . ';' : '';
    $boxstyle .= $a['bgcolor'] ? '--mb2-pb-bxbgcolor:' . $a['bgcolor'] . ';' : '';
    $boxstyle .= $a['tcolor'] ? '--mb2-pb-bxtcolor:' . $a['tcolor'] . ';' : '';
    $boxstyle .= $a['txcolor'] ? '--mb2-pb-bxtxcolor:' . $a['txcolor'] . ';' : '';
    $boxstyle .= '"';

    if ($a['btncolor'] || $a['btnbgcolor'] || $a['btnbghcolor'] || $a['btnhcolor'] || $a['btnborcolor'] || $a['btnborhcolor']) {
        $btnstyle .= ' style="';
        $btnstyle .= $a['btncolor'] ? '--mb2-pb-btn-color:' . $a['btncolor'] . ';' : '';
        $btnstyle .= $a['btnbgcolor'] ? '--mb2-pb-btn-bgcolor:' . $a['btnbgcolor'] . ';' : '';
        $btnstyle .= $a['btnbghcolor'] ? '--mb2-pb-btn-bghcolor:' . $a['btnbghcolor'] . ';' : '';
        $btnstyle .= $a['btnhcolor'] ? '--mb2-pb-btn-hcolor:' . $a['btnhcolor'] . ';' : '';
        $btnstyle .= $a['btnborcolor'] ? '--mb2-pb-btn-borcolor:' . $a['btnborcolor'] . ';' : '';
        $btnstyle .= $a['btnborhcolor'] ? '--mb2-pb-btn-borhcolor:' . $a['btnborhcolor'] . ';' : '';
        $btnstyle .= '"';
    }

    $boxistyle .= ' style="';
    $boxistyle .= $gl0bxheight ? 'min-height:' . $gl0bxheight . 'px;' : '';
    $boxistyle .= '"';

    $fcls .= ' fw' . $gl0bxicontitlefw;
    $fcls .= ' lh' . $gl0bxicontitlelh;

    $fcls .= ' ' . theme_mb2nl_tsize_cls($gl0bxicontitlefs);

    $output .= '<div class="mb2-pb-subelement mb2-pb-boxesicon_item theme-box"' .
    theme_mb2nl_page_builder_el_datatts($atts, $atts2) . '>';
    $output .= theme_mb2nl_page_builder_el_actions('subelement');
    $output .= '<div class="subelement-helper"></div>';
    $output .= '<div class="mb2-pb-subelement-inner">';

    $output .= '<div class="theme-boxicon"' . $boxstyle . '>';
    $output .= '<div class="theme-boxicon-inner"' . $boxistyle . '>';
    $output .= '<div class="theme-boxicon-icon">';

    $output .= '<i class="' . $pref . $a['icon'] . '"></i>';
    $output .= '</div>';

    $output .= '<div class="theme-boxicon-content">';

    $output .= '<h4 class="box-title m-0' . $fcls . '" style="font-size:' . $gl0bxicontitlefs . 'rem;">';
    $output .= $a['title'];
    $output .= '</h4>';

    $output .= '<div class="box-desc">' . urldecode($content) . '</div>';

    if ($a['btntext']) {
        $btntext = $a['btntext'];
    } else if ($gl0bxbtntext) {
        $btntext = $gl0bxbtntext;
    } else {
        $btntext = get_string('readmorefp', 'local_mb2builder');
    }

    $output .= '<div class="box-readmore">';
    $output .= '<a href="#" class="arrowlink"' . $btnstyle . '>' . $a['btntext'] . '</a>';
    $output .= '<a class="mb2-pb-btn type' . $gl0bxiconbtntype . ' size' . $gl0bxiconbtnsize . ' rounded' .
    $gl0bxiconbtnrounded . ' btnborder' . $gl0bxiconbtnborder . ' fw' . $gl0bxiconbtnfwcls . '" href="#" ' .
    $btnstyle . '>' . $btntext . '</a>';
    $output .= '</div>';

    $output .= '</div>';
    $output .= '</div>';
    $output .= '<div class="bigicon"><i class="' . $pref . $a['icon'] . '"></i></div>';
    $output .= '<div class="box-color"></div>';
    $output .= '</div>';
    $output .= '</div>';
    $output .= '</div>';

    return $output;

}
