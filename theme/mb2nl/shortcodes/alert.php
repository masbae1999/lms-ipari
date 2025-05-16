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

/**
 *
 * Method to define alert shortcode
 *
 * @return HTML
 */
function mb2_shortcode_alert($atts, $content= null) {
    $default = [
        'type' => 'info',
        'close' => 0,
        'mt' => 0,
        'mb' => 30,
        'class' => '',
    ];

    $a = mb2_shortcode_atts($default, $atts);

    $style = '';
    $cls = '';

    $cls .= $a['close'] ? ' alert-dismissible' : '';
    $cls .= $a['class'] != '' ? ' ' . $a['class'] : '';

    if ($a['mt'] || $a['mb']) {
        $style .= ' style="';
        $style .= $a['mt'] ? 'margin-top:' . $a['mt'] . 'px;' : '';
        $style .= $a['mb'] ? 'margin-bottom:' .$a['mb'] . 'px;' : '';
        $style .= '"';
    }

    $cbutton = $a['close'] ? '<button type="button" class="close" data-dismiss="alert" title="' .
    get_string('closebuttontitle') . '"><span aria-hidden="true">&times;</span></button>' : '';
    return '<div class="alert mb2pb-alert alert-' . $a['type'] . $cls . '"' . $style . '>' .
    $cbutton . mb2_do_shortcode($content) .'</div>';

}

mb2_add_shortcode('alert', 'mb2_shortcode_alert');
