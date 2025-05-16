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

mb2_add_shortcode('accordionimg', 'mb2_shortcode_accordionimg');
mb2_add_shortcode('accordionimg_item', 'mb2_shortcode_accordionimg_item');

/**
 *
 * Method to define accordion image shortcode
 *
 * @return HTML
 */
function mb2_shortcode_accordionimg($atts, $content=null) {

    global $PAGE, $gl0acctfw;

    $atts2 = [
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
    ];

    $a = mb2_shortcode_atts($atts2, $atts);

    $output = '';
    $style = '';
    $cls = '';

    $gl0acctfw = $a['tfw'];

    $cls .= $a['custom_class'] ? ' ' . $a['custom_class'] : '';

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

    $output .= '<div class="mb2-pb-accordionimg' . theme_mb2nl_bsfcls(1, '', 'between', 'center') . $cls . '"' . $style . '>';
    $output .= '<div class="accimg-nav">';
    $output .= mb2_do_shortcode($content);
    $output .= '</div>';
    $output .= '<div class="accimg-img-preview" aria-hidden="true">';
    $output .= '<div class="accimg-preview-inner">';

    // Get carousel content for sortable elements.
    $regex = '\\[(\\[?)(accordionimg_item)\\b([^\\]\\/]*(?:\\/(?!\\])[^\\]\\/]*)*?)';
    $regex .= '(?:(\\/)\\]|\\](?:([^\\[]*+(?:\\[(?!\\/\\2\\])[^\\[]*+)*+)\\[\\/\\2\\])?)(\\]?)';
    preg_match_all("/$regex/is", $content, $match);
    $accitems = $match[0];
    $i = 0;

    foreach ($accitems as $item) {
        $itematts = shortcode_parse_atts($item);
        $i++;

        $output .= '<div class="accimg-preview accimg-preview-'. $i .'">';
        $output .= '<img src="' . theme_mb2nl_lazy_plc() . '" class="lazy" data-src="' . $itematts['image'] . '" alt="'.
        theme_mb2nl_format_str($itematts['title']) . '">';
        $output .= '</div>';
    }

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
function mb2_shortcode_accordionimg_item($atts, $content=null) {

    global $gl0accitem, $gl0acctfw;

    $atts2 = [
        'title' => 'Accordion title here',
        'image' => theme_mb2nl_dummy_image('800x600'),
    ];

    $a = mb2_shortcode_atts($atts2, $atts);

    $output = '';
    $show = '';
    $tcls = ' fw' . $gl0acctfw;
    $expanded = 'false';
    $style = '';

    // Get accordion ids.
    $accid = uniqid('accitem_');

    // Define accordion number.
    if (isset($gl0accitem)) {
        $gl0accitem++;
    } else {
        $gl0accitem = 1;
    }

    // Check if is active.
    if ($gl0accitem == 1) {
        $show = ' show';
        $expanded = 'true';
    }

    $a['title'] = theme_mb2nl_format_str($a['title']);

    $output .= '<div class="accimg-item' . $show . '" data-num="' . $gl0accitem . '">';
    $output .= '<div class="accimg-header">';
    $output .= '<button type="button" class="themereset accimg-btn'.
    theme_mb2nl_bsfcls(1, '', 'between', 'center') . '" aria-expanded="' . $expanded . '" aria-controls="' . $accid . '">';
    $output .= '<span class="accimg-title mb-0 h4' . $tcls . theme_mb2nl_bsfcls(2) . '">' . $a['title']. '</span>';
    $output .= '<span class="accimg-plus' . theme_mb2nl_bsfcls(2, '', '', 'center') . '"></span>';
    $output .= '</button>'; // ...accimg-header
    $output .= '</div>'; // ...accimg-header
    $output .= '<div id="' . $accid . '" class="accimg-content position-relative">';
    $output .= '<div class="accimg-content-inner">';
    $output .= '<div class="accimg-text">';
    $output .= theme_mb2nl_check_for_tags($content, 'iframe') ? $content :
    mb2_do_shortcode(theme_mb2nl_format_txt($content, FORMAT_HTML));
    $output .= '</div>'; // ...accimg-text
    $output .= '<div class="accimg-image">';
    $output .= '<img src="' . theme_mb2nl_lazy_plc() . '" class="accimg-image-src lazy" data-src="' . $a['image'] . '" alt="'.
    strip_tags($a['title']) . '">';
    $output .= '</div>'; // ...accimg-image
    $output .= '</div>'; // ...accimg-content-inner
    $output .= '</div>'; // ...accimg-content
    $output .= '</div>'; // ...accimg-item

    return $output;
}
