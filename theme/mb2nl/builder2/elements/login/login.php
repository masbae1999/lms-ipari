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

mb2_add_shortcode('mb2pb_login', 'mb2pb_shortcode_mb2pb_login');

/**
 *
 * Method to define shortcode
 *
 * @return HTML
 */
function mb2pb_shortcode_mb2pb_login($atts, $content = null) {

    $atts2 = [
        'id' => 'login',
        'istitle' => 1,
        'title' => 'Title text here',
        'titletag' => 'h4',
        'width' => 400,
        'mt' => 0,
        'mb' => 30,
        'custom_class' => '',
        'template' => '',
    ];

    $a = mb2_shortcode_atts($atts2, $atts);

    $output = '';
    $style = '';
    $inputstyle = '';
    $cls = '';
    $tcls = '';

    $formid = uniqid('loginform_');

    $cls .= $a['custom_class'] ? ' ' . $a['custom_class'] : '';
    $cls .= ' istitle' . $a['istitle'];
    $cls .= $a['template'] ? ' mb2-pb-template-login' : '';

    $tcls .= ' ' . $a['titletag'];

    if ($a['mt'] || $a['mb'] || $a['width']) {
        $style .= ' style="';
        $style .= $a['mt'] ? 'margin-top:' . $a['mt'] . 'px;' : '';
        $style .= $a['mb'] ? 'margin-bottom:' . $a['mb'] . 'px;' : '';
        $style .= $a['width'] ? 'max-width:' . $a['width'] . 'px;' : '';
        $style .= '"';
    }

    $output .= '<div class="mb2-pb-element mb2-pb-login' . $cls . '"' . $style .
    theme_mb2nl_page_builder_el_datatts($atts, $atts2) . '>';
    $output .= '<div class="element-helper"></div>';
    $output .= theme_mb2nl_page_builder_el_actions('element', 'login');
    $output .= '<form id="' . $formid . '" method="post" action="">';
    $output .= '<h2 class="form-title' . $tcls . '">' . $a['title'] . '</h2>';
    $output .= '<div class="form-content">';
    $output .= '<div class="form-field">';
    $output .= '<label id="user"><i class="ri-user-3-line"></i></label>';
    $output .= '<input id="' . $formid . '_username" type="text" name="username" placeholder="' .
    get_string('username') . '">';
    $output .= '</div>';
    $output .= '<div class="form-field">';
    $output .= '<label id="pass"><i class="ri-lock-line"></i></label>';
    $output .= '<input id="' . $formid . '_password" type="password" name="password" placeholder="' .
    get_string('password') . '">';
    $output .= '</div>';
    $output .= '<div class="form-button"><input type="submit" id="' . $formid . '_submit" name="submit" value="' .
    get_string('login') . '"></div>';
    $output .= '</form>';
    $output .= '<div class="logininfo"><a href="#">' . get_string('forgotten') . '</a></div>';
    $output .= '</div>';
    $output .= '</div>';

    return $output;

}
