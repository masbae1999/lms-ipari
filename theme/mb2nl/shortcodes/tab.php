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

mb2_add_shortcode('tabs', 'mb2_shortcode_tabs');
mb2_add_shortcode('tab_item', 'mb2_shortcode_tabs_item');
mb2_add_shortcode('tabs_item', 'mb2_shortcode_tabs_item');

/**
 *
 * Method to define shortcode
 *
 * @return HTML
 */
function mb2_shortcode_tabs($atts, $content = null) {

    global $gl0mb2pbtabicon;

    $atts2 = [
          'tabpos' => 'top',
          'height' => 100,
          'isicon' => 0,
          'icon' => 'fa fa-trophy',
          'custom_class' => '',
          'margin' => '',
          'mt' => 0,
          'mb' => 30,
    ];

    $a = mb2_shortcode_atts($atts2, $atts);

    $gl0mb2pbtabicon = $a['icon'];
    $unique = uniqid('tab-');
    $output = '';
    $icontag = '';
    $style = '';
    $cls = '';
    $i = -1;

    // We have to check if user use old or new tabs shortcode.
    $tabsname = preg_match('@tab_item@', $content) ? 'tab_item' : 'tabs_item';

    // Get tab content for sortable elements.
    $regex = '\\[(\\[?)(' . $tabsname . ')\\b([^\\]\\/]*(?:\\/(?!\\])[^\\]\\/]*)*?)';
    $regex .= '(?:(\\/)\\]|\\](?:([^\\[]*+(?:\\[(?!\\/\\2\\])[^\\[]*+)*+)\\[\\/\\2\\])?)(\\]?)';
    preg_match_all("/$regex/is", $content, $match);
    $content = $match[0];

    if ($a['mt'] || $a['mb'] || $a['margin']) {
        $style .= ' style="';
        $style .= $a['margin'] ? 'margin:' . $a['margin'] . ';' : '';
        $style .= $a['mt'] ? 'margin-top:' . $a['mt'] . 'px;' : '';
        $style .= $a['mb'] ? 'margin-bottom:' . $a['mb'] . 'px;' : '';
        $style .= '"';
    }

    $a['height'] = $a['height'] ? ' style="min-height:' . $a['height'] . 'px"' : '';

    $cls .= $a['tabpos'];
    $cls .= ' isicon' . $a['isicon'];
    $cls .= $a['custom_class'] ? ' ' . $a['custom_class'] : '';

    $output .= '<div class="theme-tabs tabs ' . $cls . '"' . $style . '>';
    $output .= '<ul class="nav nav-tabs" role="tablist">';

    foreach ($content as $c) {
        $i++;
        $uniqueid = $unique . $i;
        $tabselected = $i == 0 ? 'true' : 'false'; // We need word 'true' or 'false' for data attribute.
        $activecls = $i == 0 ? ' active' : '';
        $tabattr = $i == 0 ? ' custom_class="active"' : '';

        // This is required for old shortcodes.
        // To use shortcode_parse_atts.
        $c = str_replace('"]', '" ]', $c);
        $c = str_replace('accordion_item title', 'accordion_item titlex', $c);

        $attributes = shortcode_parse_atts($c);

        $attr['pbid'] = (isset($attributes['pbid']) && $attributes['pbid']) ? $attributes['pbid'] : '';
        $attr['title'] = (isset($attributes['title']) && $attributes['title']) ? $attributes['title'] : 'Tab';
        $attr['icon'] = (isset($attributes['icon']) && $attributes['icon']) ? $attributes['icon'] : '';

        // Defaine global icon.
        $a['icon'] = $attr['icon'] ? $attr['icon'] : $a['icon'];

        if ($a['icon']) {
            $pref = theme_mb2nl_font_icon_prefix($a['icon']);
            $icontag = '<i class="'. $pref . $a['icon'] . '"></i> ';
        }

        $output .= '<li class="nav-item">';
        $output .= '<a class="nav-link'. $activecls .'" href="#' . $uniqueid . '" data-toggle="tab" role="tab" aria-controls="' .
        $uniqueid . '" aria-selected="' . $tabselected . '">';
        $output .= '<span class="tab-text">' . $icontag . theme_mb2nl_format_str($attr['title']) . '</span>';
        $output .= '</a>';
        $output .= '</li>';

        $content[$i] = str_replace('[' . $tabsname . ' ', '[' . $tabsname . $tabattr . ' tabid="' .
        $uniqueid . '" ', $content[$i]);
    }

    $output .= '</ul>';
    $output .= '<div class="tab-content"' . $a['height'] . '>';

    foreach ($content as $c) {
        $output .= mb2_do_shortcode($c);
    }

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
function mb2_shortcode_tabs_item($atts, $content = null) {
    $atts2 = [
        'title' => '',
        'id' => '',
        'tabid' => '',
        'icon' => '',
        'custom_class' => '',
    ];

    $a = mb2_shortcode_atts($atts2, $atts);

    $cls = $a['custom_class'] ? ' ' . $a['custom_class'] : '';
    $a['id'] = $a['tabid'] ? $a['tabid'] : $a['id'];

    $output = '<div class="tab-pane' . $cls . '" id="' . $a['id'] . '" role="tabpanel" aria-labelledby="' .
    $a['id'] . '">';
    $output .= mb2_do_shortcode(theme_mb2nl_format_txt($content, FORMAT_HTML));
    $output .= '</div>';

    return $output;
}
