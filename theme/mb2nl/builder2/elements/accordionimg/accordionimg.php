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

mb2_add_shortcode('mb2pb_accordionimg', 'mb2_shortcode_mb2pb_accordionimg');
mb2_add_shortcode('mb2pb_accordionimg_item', 'mb2_shortcode_mb2pb_accordionimg_item');

/**
 *
 * Method to define accordion image shortcode
 *
 * @return HTML
 */
function mb2_shortcode_mb2pb_accordionimg($atts, $content = null) {

    global $PAGE, $gl0acctfw;

    $atts2 = [
        'id' => 'accordionimg',
        'custom_class' => '',
        'navw' => 280,
        'space' => 2,
        'vspace' => 1,
        'tfs' => 1.4,
        'tfw' => 'global',
        'tcolor' => '',
        'bcolor' => '',
        'pluscolor' => '',
        'mt' => 0,
        'mb' => 30,
        'template' => '',
    ];

    $a = mb2_shortcode_atts($atts2, $atts);

    $output = '';
    $style = '';
    $cls = '';

    // Get accordion uniq id and send it as global.
    $gl0acctfw = $a['tfw'];

    $cls .= $a['custom_class'] ? ' ' . $a['custom_class'] : '';
    $cls .= $a['template'] ? ' mb2-pb-template-accordionimg' : '';

    $style .= ' style="';
    $style .= 'margin-top:' . $a['mt'] . 'px;';
    $style .= 'margin-bottom:' . $a['mb'] . 'px;';
    $style .= '--mb2-pb-accimg-navw:' . $a['navw'] . 'px;';
    $style .= '--mb2-pb-accimg-space:' . $a['space'] . 'rem;';
    $style .= '--mb2-pb-accimg-vspace:' . $a['vspace'] . 'rem;';
    $style .= '--mb2-pb-accimg-tfs:' . $a['tfs'] . 'rem;';
    $style .= $a['tcolor'] ? '--mb2-pb-accimg-tcolor:' . $a['tcolor'] . ';' : '';
    $style .= $a['bcolor'] ? '--mb2-pb-accimg-bcolor:' . $a['bcolor'] . ';' : '';
    $style .= $a['pluscolor'] ? '--mb2-pb-accimg-pluscolor:' . $a['pluscolor'] . ';' : '';

    $style .= '"';

    if (! $content) {
        for ($i = 1; $i <= 3; $i++) {
            $content .= '[mb2pb_accordionimg_item][/mb2pb_accordionimg_item]';
        }
    }

    $output .= '<div class="mb2-pb-element mb2-pb-accordionimg' . $cls . '"' . $style.
    theme_mb2nl_page_builder_el_datatts($atts, $atts2) . '>';
    $output .= '<div class="element-helper"></div>';
    $output .= theme_mb2nl_page_builder_el_actions('element', 'accordionimg');
    $output .= '<div class="mb2-pb-element-inner mb2-pb-accordionimg_inner">';
    $output .= '<div class="mb2-pb-sortable-subelements accimg-nav">';
    $output .= mb2_do_shortcode($content);
    $output .= '</div>';
    $output .= '<div class="accimg-img-preview">';
    $output .= '<div class="accimg-preview-inner">';
    $output .= '<img src="' . theme_mb2nl_dummy_image('800x600') . '" alt="">';
    $output .= '</div>';
    $output .= '</div>';
    $output .= '</div>';
    $output .= '</div>';

    return $output;

}


/**
 *
 * Method to define accordion image item shortcode
 *
 * @return HTML
 */
function mb2_shortcode_mb2pb_accordionimg_item($atts, $content=null) {

    global $gl0accitem, $gl0acctfw;

    $atts2 = [
        'id' => 'accordionimg_item',
        'title' => 'Accordion title here',
        'image' => theme_mb2nl_dummy_image('800x600'),
        'template' => '',
    ];

    $a = mb2_shortcode_atts($atts2, $atts);

    $output = '';
    $show = '';
    $tcls = ' fw' . $gl0acctfw;
    $style = '';

    // Define accordion number.
    if (isset($gl0accitem)) {
        $gl0accitem++;
    } else {
        $gl0accitem = 1;
    }

    // Activate the first item.
    if ($gl0accitem == 1) {
        $show = ' show';
    }

    $content = $content ? $content : 'Accordion content here.';
    $atts2['text'] = $content;

    $output .= '<div class="mb2-pb-subelement mb2-pb-accordionimg_item"' . theme_mb2nl_page_builder_el_datatts($atts, $atts2) . '>';
    $output .= theme_mb2nl_page_builder_el_actions('subelement');
    $output .= '<div class="subelement-helper"></div>';
    $output .= '<div class="mb2-pb-subelement-inner">';

    $output .= '<div class="accimg-item' . $show . '">';
    $output .= '<div class="accimg-header">';
    $output .= '<button type="button" class="themereset accimg-btn' . theme_mb2nl_bsfcls(1, '', 'between', 'center') . '">';
    $output .= '<span class="accimg-title mb-0 h4' . $tcls . theme_mb2nl_bsfcls(2) . '">' . $a['title'] . '</span>';
    $output .= '<span class="accimg-plus' . theme_mb2nl_bsfcls(2, '', '', 'center') . '"></span>';
    $output .= '</button>';
    $output .= '</div>';

    $output .= '<div class="accimg-content position-relative">';
    $output .= '<div class="accimg-content-inner">';
    $output .= '<div class="accimg-text">' . urldecode($content) . '</div>';
    $output .= '<div class="accimg-image">';
    $output .= '<img class="accimg-image-src" src="" alt="">';
    $output .= '</div>'; // ...accimg-image
    $output .= '</div>';
    $output .= '</div>';
    $output .= '</div>'; // ...accimg-item

    $output .= '</div>';
    $output .= '</div>';

    return $output;
}
