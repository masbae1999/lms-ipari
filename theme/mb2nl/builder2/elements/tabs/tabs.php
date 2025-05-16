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

mb2_add_shortcode('mb2pb_tabs', 'mb2_shortcode_mb2pb_tabs');
mb2_add_shortcode('mb2pb_tabs_item', 'mb2_shortcode_mb2pb_tabs_item');

/**
 *
 * Method to define shortcode
 *
 * @return HTML
 */
function mb2_shortcode_mb2pb_tabs($atts, $content = null) {

    global $gl0mb2pbtabicon;

    $atts2 = [
        'id' => 'tabs',
        'tabpos' => 'top',
        'height' => 100,
        'isicon' => 0,
        'icon' => 'fa fa-trophy',
        'custom_class' => '',
        'mt' => 0,
        'mb' => 30,
        'template' => '',
    ];

    $a = mb2_shortcode_atts($atts2, $atts);

    $gl0mb2pbtabicon = $a['icon'] ? $a['icon'] : 'fa fa-trophy';
    $unique = uniqid('tab-');
    $output = '';
    $style = '';
    $cls = '';

    if (! $content) {
        $content = '[mb2pb_tabs_item title="Tab" desc="Tab content here." ][/mb2pb_tabs_item]';
    }

    // Get tab content for sortable elements.
    $regex = '\\[(\\[?)(mb2pb_tabs_item)\\b([^\\]\\/]*(?:\\/(?!\\])[^\\]\\/]*)*?)';
    $regex .= '(?:(\\/)\\]|\\](?:([^\\[]*+(?:\\[(?!\\/\\2\\])[^\\[]*+)*+)\\[\\/\\2\\])?)(\\]?)';
    preg_match_all("/$regex/is", $content, $match);
    $content = $match[0];

    if ($a['mt'] || $a['mb']) {
        $style .= ' style="';
        $style .= $a['mt'] ? 'margin-top:' . $a['mt'] . 'px;' : '';
        $style .= $a['mb'] ? 'margin-bottom:' . $a['mb'] . 'px;' : '';
        $style .= '"';
    }

    $a['height'] = $a['height'] ? ' style="min-height:' . $a['height'] . 'px"' : '';

    $cls .= $a['template'] ? ' mb2-pb-template-tabs' : '';
    $cls .= $a['custom_class'] ? ' ' . $a['custom_class'] : '';

    $innercls = $a['tabpos'];
    $innercls .= ' isicon' . $a['isicon'];

    $output .= '<div class="mb2-pb-element mb2-pb-tabs ' . $cls . '"' . $style .
    theme_mb2nl_page_builder_el_datatts($atts, $atts2) . '>';
    $i = -1;
    $output .= '<div class="element-helper"></div>';
    $output .= theme_mb2nl_page_builder_el_actions('element', 'tabs');

    $output .= '<div class="mb2-pb-element-inner theme-tabs tabs ' . $innercls . '">';
    $output .= '<ul class="nav nav-tabs mb2-pb-sortable-subelements" role="tablist">';

    foreach ($content as $c) {
        $i++;
        $uniqueid = $unique . $i;
        $tabselected = $i == 0 ? 'true' : 'false'; // We need word 'true' or 'false' for data attribute.
        $activecls = $i == 0 ? ' active' : '';
        $tabattr = $i == 0 ? ' custom_class="active"' : '';

        // Get attributes of tab items.
        $attributes = shortcode_parse_atts($c);
        $attr['id'] = 'tabs_item';
        $attr['pbid'] = (isset($attributes['pbid']) && $attributes['pbid']) ? $attributes['pbid'] : '';
        $attr['title'] = (isset($attributes['title']) && $attributes['title']) ? $attributes['title'] : 'Tab';
        $attr['icon'] = (isset($attributes['icon']) && $attributes['icon']) ? $attributes['icon'] : '';
        $attr['text'] = theme_mb2nl_page_builder_shortcode_content_attr($c, mb2_get_shortcode_regex());

        // Defaine global icon.
        $a['icon'] = $attr['icon'] ? $attr['icon'] : $a['icon'];

        $output .= '<li class="nav-item mb2-pb-subelement mb2-pb-tabs_item"' .
        theme_mb2nl_page_builder_el_datatts($attr, $attr) . '>';
        $output .= theme_mb2nl_page_builder_el_actions('subelement');
        $output .= '<a class="nav-link d-flex'. $activecls .'" style="gap:.45rem;" href="#' .
        $uniqueid . '" data-toggle="tab" role="tab" aria-controls="' . $uniqueid . '" aria-selected="' . $tabselected . '">';
        $output .= '<i class="'. $a['icon'] . '"></i> <span class="tab-text">' .
        theme_mb2nl_format_str($attr['title']) . '</span>';
        $output .= '</a>';
        $output .= '</li>';

        $content[$i] = str_replace('[mb2pb_tabs_item ', '[mb2pb_tabs_item'. $tabattr . ' tabid="' .
        $uniqueid . '" ', $content[$i]);
    }

    $output .= '</ul>';

    $output .= '<div class="tab-content"' . $a['height'] . '>';

    foreach ($content as $c) {
        $output .= mb2_do_shortcode($c);
    }

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
function mb2_shortcode_mb2pb_tabs_item($atts, $content = null) {

    global $gl0mb2pbtabicon;

    $atts2 = [
        'id' => 'tabs_item',
        'title' => 'Tab',
        'tabid' => '',
        'icon' => '',
        'text' => '',
        'custom_class' => '',
        'template' => '',
    ];

    $a = mb2_shortcode_atts($atts2, $atts);

    $a['icon'] = $a['icon'] ? $a['icon'] : $gl0mb2pbtabicon;

    $cls = '';
    $cls .= $a['template'] ? ' mb2-pb-template-tabs_item' : '';
    $cls .= $a['custom_class'] ? ' ' . $a['custom_class'] : '';

    if (! $content) {
        $content = 'Tab content here.';
    }

    $output = '<div class="tab-pane' . $cls . '" id="' . $a['tabid'] . '" role="tabpanel" aria-labelledby="' .
    $a['tabid'] . '">';
    $output .= urldecode($content);
    $output .= '</div>';

    return $output;
}
