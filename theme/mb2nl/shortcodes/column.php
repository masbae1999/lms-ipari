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

mb2_add_shortcode('pbcolumn', 'mb2_shortcode_pbcolumn');
mb2_add_shortcode('column', 'mb2_shortcode_pbcolumn'); // This is old column shortcode.


/**
 *
 * Method to define shortcode
 *
 * @return HTML
 */
function mb2_shortcode_pbcolumn($atts, $content = null) {

    $atts2 = [
        'col' => 12,
        'size' => 0, // This is for the old column shortcode.
        'pt' => 0,
        'pb' => 30,
        'mobcenter' => 0,
        'moborder' => 0,
        'align' => 'none',
        'alignc' => 'none',
        'height' => 0,
        'width' => 4000,
        'scheme' => 'light',
        'bgcolor' => '',
        'bgimage' => '',
        'isfooter' => 0,
        'custom_class' => '',
    ];

    $a = mb2_shortcode_atts($atts2, $atts);

    $output = '';
    $style = '';
    $cstyle = '';
    $cls = '';
    $a['col'] = $a['size'] ? $a['size'] : $a['col'];

    $cls .= ! $content ? ' empty' : ' noempty';
    $cls .= ' ' . $a['scheme'];
    $cls .= ' align-' . $a['align'];
    $cls .= ' alignc' . $a['alignc'];
    $cls .= ' mobcenter' . $a['mobcenter'];
    $cls .= ' moborder' . $a['moborder'];
    $cls .= $a['bgimage'] ? ' lazy' : '';
    $cls .= $a['custom_class'] ? ' ' . $a['custom_class'] : '';

    if ($a['pt'] || $a['pb'] || $a['width']) {
        $style .= ' style="';
        $style .= $a['pt'] ? 'padding-top:' . $a['pt'] . 'px;' : '';
        $style .= $a['pb'] ? 'padding-bottom:' . $a['pb'] . 'px;' : '';
        $style .= $a['width'] ? 'max-width:' . $a['width'] . 'px;' : '';
        $style .= '"';
    }

    $bgcolorstyle = $a['bgcolor'] ? ' style="background-color:' . $a['bgcolor'] . ';"' : '';

    if ($a['height']) {
        $cstyle .= ' style="';
        $cstyle .= $a['height'] ? 'min-height:' . $a['height'] . 'px;' : '';
        $cstyle .= '"';
    }

    $databgimage = $a['bgimage'] ? ' data-bg="' . $a['bgimage'] . '"' : '';

    $a['bgcolor'] = $a['bgcolor'] ? ' style="background-color:' . $a['bgcolor'] . ';"' : '';

    $colpref = 'lg';

    $output .= '<div class="mb2-pb-column col-' . $colpref . '-' . $a['col'] . $cls . '"' . $cstyle . $databgimage . '>';
    $output .= '<div class="column-inner"' . $style . '>';
    $output .= '<div class="clearfix">';
    $output .= ! $content ? '&nbsp;' : mb2_do_shortcode($content);
    $output .= '</div>';
    $output .= '</div>';
    $output .= $a['bgcolor'] ? '<div class="column-inner-bg"' . $bgcolorstyle . '></div>' : '';
    $output .= '</div>';

    return $output;

}
