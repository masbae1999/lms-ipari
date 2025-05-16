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

mb2_add_shortcode('mb2pb_accordion', 'mb2_shortcode_mb2pb_accordion');
mb2_add_shortcode('mb2pb_accordion_item', 'mb2_shortcode_mb2pb_accordion_item');

/**
 *
 * Method to define accordion shortcode
 *
 * @return HTML
 */
function mb2_shortcode_mb2pb_accordion($atts, $content=null) {

    global $gl0acc,
    $gl0accparent,
    $gl0accactive,
    $gl0accicon,
    $gl0accitfs,
    $gl0accicontfw,
    $gl0acctcolor,
    $gl0accthcolor,
    $gl0acchbgcolor,
    $gl0acchbghcolor,
    $gl0acccbgcolor,
    $gl0accscheme,
    $gl0acciconcolor,
    $gl0acciconhcolor;

    $atts2 = [
        'id' => 'accordion',
        'builder' => 1,
        'type' => 'default',
        'size' => 's',
        'padding' => 0,
        'rounded' => 0,
        'custom_class' => '',
        'tfs' => 1,
        'tfw' => 'global',
        'tcolor' => '',
        'thcolor' => '',
        'hbgcolor' => '',
        'hbghcolor' => '',
        'cbgcolor' => '',
        'iconcolor' => '',
        'iconhcolor' => '',
        'scheme' => 'light',
        'isicon' => 0,
        'icon' => 'fa fa-trophy',
        'accordion_active' => theme_mb2nl_shortcodes_global_opts('accordion', 'accordion_active', 1),
        'mt' => 0,
        'mb' => 30,
        'parent' => 1,
        'template' => '',
    ];

    $a = mb2_shortcode_atts($atts2, $atts);

    $output = '';
    $style = '';
    $cls = '';

    // Get accordion uniq id and send it as global.
    $accid = uniqid('mb2acc_');

    // Global variables.
    $gl0acc = $accid;
    $gl0accparent = $a['parent'];
    $gl0accactive = $a['accordion_active'];
    $gl0accicon = $a['icon'] ? $a['icon'] : 'fa fa-trophy';
    $gl0accitfs = $a['tfs'];
    $gl0accicontfw = $a['tfw'];
    $gl0acctcolor = $a['tcolor'];
    $gl0accthcolor = $a['thcolor'];
    $gl0acchbgcolor = $a['hbgcolor'];
    $gl0acchbghcolor = $a['hbghcolor'];
    $gl0acccbgcolor = $a['cbgcolor'];
    $gl0accscheme = $a['scheme'];
    $gl0acciconcolor = $a['iconcolor'];
    $gl0acciconhcolor = $a['iconhcolor'];

    $cls .= $a['custom_class'] ? ' ' . $a['custom_class'] : '';
    $cls .= ' isicon' . $a['isicon'];
    $cls .= ' style-' . $a['type'];
    $cls .= ' size' . $a['size'];
    $cls .= ' padding' . $a['padding'];
    $cls .= ' rounded' . $a['rounded'];
    $cls .= $a['template'] ? ' mb2-pb-template-accordion' : '';

    if ($a['mt'] || $a['mb']) {
        $style .= ' style="';
        $style .= $a['mt'] ? 'margin-top:' . $a['mt'] . 'px;' : '';
        $style .= $a['mb'] ? 'margin-bottom:' . $a['mb'] . 'px;' : '';
        $style .= '"';
    }

    if (!$content) {
        for ($i = 1; $i <= 3; $i++) {
            $content .= '[mb2pb_accordion_item][/mb2pb_accordion_item]';
        }
    }

    $output .= '<div id="' . $accid . '" class="mb2-pb-element mb2-pb-accordion mb2-accordion accordion' . $cls . '"'.
    $style . theme_mb2nl_page_builder_el_datatts($atts, $atts2) . '>';
    $output .= '<div class="element-helper"></div>';
    $output .= theme_mb2nl_page_builder_el_actions('element', 'accordion');
    $output .= '<div class="mb2-pb-element-inner">';
    $output .= '<div class="mb2-pb-sortable-subelements">';
    $output .= mb2_do_shortcode($content);
    $output .= '</div>';
    $output .= '</div>';
    $output .= '</div>';

    return $output;

}



/**
 *
 * Method to define accordion item shortcode
 *
 * @return HTML
 */
function mb2_shortcode_mb2pb_accordion_item($atts, $content=null) {

    global $gl0accitem,
    $gl0acc,
    $gl0accparent,
    $gl0accactive,
    $gl0accicon,
    $gl0accitfs,
    $gl0accicontfw,
    $gl0acctcolor,
    $gl0accthcolor,
    $gl0acchbgcolor,
    $gl0acchbghcolor,
    $gl0acccbgcolor,
    $gl0accscheme,
    $gl0acciconcolor,
    $gl0acciconhcolor;

    $atts2 = [
        'id' => 'accordion_item',
        'title' => 'Accordion title here',
        'icon' => '',
        'template' => '',
    ];

    $a = mb2_shortcode_atts($atts2, $atts);

    $output = '';
    $a['parent'] = '';
    $show = '';
    $expanded = 'false';
    $colpsed = ' collapsed';
    $tcls = ' fw' . $gl0accicontfw;
    $tstyle = '';
    $cbcls = '';
    $cbstyle = '';
    $istyle = '';

    $a['icon'] = $a['icon'] ? $a['icon'] : $gl0accicon;

    // Get accordion ids.
    $parentid = $gl0acc;
    $accid = uniqid('accitem_');

    // Get parent attribute.
    if ($gl0accparent) {
        $a['parent'] = ' data-parent="#' . $parentid . '"';
    }

    // Define accordion number.
    if (isset($gl0accitem)) {
        $gl0accitem++;
    } else {
        $gl0accitem = 1;
    }

    // Check if is active.
    if ($gl0accactive == $gl0accitem) {
        $show = ' show';
        $expanded = 'true';
        $colpsed = '';
    }

    // Title style.
    if (
        $gl0accitfs ||
        $gl0acctcolor ||
        $gl0accthcolor ||
        $gl0acchbgcolor ||
        $gl0acchbghcolor
    ) {
        $tstyle .= ' style="';
        $tstyle .= 'font-size:' . $gl0accitfs . 'rem;';
        $tstyle .= $gl0acctcolor ? '--mb2-pb-acc-tcolor:' . $gl0acctcolor . ';' : '';
        $tstyle .= $gl0accthcolor ? '--mb2-pb-acc-thcolor:' . $gl0accthcolor . ';' : '';
        $tstyle .= $gl0acchbgcolor ? '--mb2-pb-acc-hbgcolor:' . $gl0acchbgcolor . ';' : '';
        $tstyle .= $gl0acchbghcolor ? '--mb2-pb-acc-hbghcolor:' . $gl0acchbghcolor . ';' : '';
        $tstyle .= '"';
    }

    // Content style.
    $cbcls .= ' ' . $gl0accscheme;

    if ($gl0acccbgcolor) {
        $cbstyle .= ' style="';
        $cbstyle .= '--mb2-pb-acc-cbgcolor:' . $gl0acccbgcolor . ';';
        $cbstyle .= '"';
    }

    // Icon style.
    if ($gl0acciconcolor ||  $gl0acciconhcolor) {
        $istyle .= ' style="';
        $istyle .= '--mb2-pb-acc-iconcolor:' . $gl0acciconcolor . ';';
        $istyle .= '--mb2-pb-acc-iconhcolor:' . $gl0acciconhcolor . ';';
        $istyle .= '"';
    }

    $content = $content ? $content : 'Accordion content here.';
    $atts2['text'] = $content;

    $output .= '<div class="card mb2-pb-subelement mb2-pb-accordion_item"'.
    theme_mb2nl_page_builder_el_datatts($atts, $atts2) . '>';
    $output .= theme_mb2nl_page_builder_el_actions('subelement');
    $output .= '<div class="subelement-helper"></div>';
    $output .= '<div class="mb2-pb-subelement-inner">';

    $output .= '<div class="card-header">';
    $output .= '<h5 class="mb-0">';
    $output .= '<button type="button" data-toggle="collapse" class="themereset' . $colpsed . '" data-target="#'.
    $accid . '" aria-controls="' . $accid . '" aria-expanded="' . $expanded . '"' . $a['parent'] . $tstyle . '>';

    $output .= '<i class="' . $a['icon'] . '"' . $istyle . '></i><span class="acc-text' . $tcls . '">' . $a['title'] . '</span>';
    $output .= '</button>';
    $output .= '</h5>';
    $output .= '</div>';

    $output .= '<div id="' . $accid . '" class="collapse' . $show . '"' . $a['parent'] . '>';
    $output .= '<div class="card-body' . $cbcls . '"' . $cbstyle . '>';
    $output .= '<div class="inner">';
    $output .= urldecode($content);
    $output .= '</div>';
    $output .= '</div>';
    $output .= '</div>';

    $output .= '</div>';
    $output .= '</div>';

    return $output;
}
