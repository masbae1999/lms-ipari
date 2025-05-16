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

mb2_add_shortcode('popupimage', 'mb2_shortcode_popupimage');
mb2_add_shortcode('popupgallery', 'mb2_shortcode_popupgallery');

/**
 *
 * Method to define shortcode
 *
 * @return HTML
 */
function mb2_shortcode_popupimage($atts, $content= null) {
    global $PAGE, $glogallid;

    $atts2 = [
        'image' => '',
        'width' => 200,
        'border' => 1,
        'zoom' => 1,
        'alt' => '',
        'custom_class' => '',
    ];

    $a = mb2_shortcode_atts($atts2, $atts);

    $output = '';
    $style = '';
    $cls = $a['custom_class'] ? ' ' . $a['custom_class'] : '';
    $isimage = $a['image'] ? $a['image'] : theme_mb2nl_dummy_image('600x400');
    $elcls = '';
    $linkcls = '';
    $gid = $glogallid;

    // Image style.
    $style .= ' style="';
    $style .= 'width:' . $a['width'] . 'px;max-width:100%;';
    $style .= '"';

    $elcls .= ' border' . $a['border'];
    $elcls .= ' zoom' . $a['zoom'];
    $elcls .= !$gid ? ' mb-4' : '';

    $linkcls .= $gid ? '_g' : '';

    $output .= '<div class="theme-popup-element' . $elcls . theme_mb2nl_bsfcls(2, '', 'center', 'center') . '">';
    $output .= '<a class="theme-popup-link popup-image' . $linkcls . '" href="' . $isimage . '" title=""' .
    $style . '><img class="lazy" src="' . theme_mb2nl_lazy_plc() . '" data-src="' . $isimage . '" alt="' . $a['alt'] . '"></a>';
    $output .= '</div>';

    return $output;

}




/**
 *
 * Method to define shortcode
 *
 * @return HTML
 */
function mb2_shortcode_popupgallery($atts, $content= null) {

    global $PAGE, $glogallid;

    $a = mb2_shortcode_atts([], $atts);

    $output = '';
    $uniqid = uniqid('gallery_');

    $glogallid = $uniqid;

    $output .= '<div id="' . $uniqid . '" class="theme-popup-gallery mb-4' . theme_mb2nl_bsfcls(1, 'wrap', '', 'center')  . '">';
    $output .= mb2_do_shortcode($content);
    $output .= '</div>';

    $glogallid = 0;

    return $output;

}
