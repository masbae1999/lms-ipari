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

mb2_add_shortcode('mb2pb_column', 'mb2_shortcode_mb2pb_column');

/**
 *
 * Method to define shortcode
 *
 * @return HTML
 */
function mb2_shortcode_mb2pb_column ($atts, $content = null) {

    $atts2 = [
        'id' => 'column',
        'col' => 12,
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
    $colid = uniqid('mb2-pb-col_');

    $cls .= $content === '' ? ' empty' : ' noempty';
    $cls .= ' ' . $a['scheme'];
    $cls .= ' align-' . $a['align'];
    $cls .= ' alignc' . $a['alignc'];
    $cls .= ' mobcenter' . $a['mobcenter'];
    $cls .= ' moborder' . $a['moborder'];
    $cls .= $a['custom_class'] ? ' ' . $a['custom_class'] : '';

    if ($a['pt'] || $a['pb'] || $a['width']) {
        $style .= ' style="';
        $style .= $a['pt'] ? 'padding-top:' . $a['pt'] . 'px;' : '';
        $style .= $a['pb'] ? 'padding-bottom:' . $a['pb'] . 'px;' : '';
        $style .= $a['width'] ? 'max-width:' . $a['width'] . 'px;' : '';
        $style .= '"';
    }

    $bgcolorstyle = $a['bgcolor'] ? ' style="background-color:' . $a['bgcolor'] . ';"' : '';

    if ($a['bgimage'] || $a['height']) {
        $cstyle .= ' style="';
        $cstyle .= $a['bgimage'] ? 'background-image:url(\'' . $a['bgimage'] . '\');' : '';
        $cstyle .= $a['height'] ? 'min-height:' . $a['height'] . 'px;' : '';
        $cstyle .= '"';
    }

    $colpref = 'lg';

    $output .= '<div class="mb2-pb-column col-' . $colpref . '-' . $a['col'] . $cls . '"' . $cstyle .
    theme_mb2nl_page_builder_el_datatts($atts, $atts2) . '>';
    $output .= '<div class="column-helper"></div>';
    $output .= theme_mb2nl_page_builder_el_actions('column', '', ['copy' => 0]);
    $output .= '<div class="column-inner"' . $style . '>';
    $output .= '<div class="mb2-pb-sortable-elements clearfix">';
    $output .= mb2_do_shortcode($content);
    $output .= '</div>';
    $output .= '</div>';

    $output .= '<div class="mb2-pb-column-footer">';
    $output .= '<a href="#" class="mb2-pb-add-element" title="' .
    get_string('addelement', 'local_mb2builder') . '" data-modal="#mb2-pb-modal-elements">&plus; ' .
    get_string('addelement', 'local_mb2builder') . '</a>';
    $output .= '</div>';
    $output .= '<div class="column-inner-bg"' . $bgcolorstyle . '></div>';
    $output .= '</div>';

    return $output;

}
