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
 *
 */

defined('MOODLE_INTERNAL') || die();

global $PAGE;

$loginicon = (!isloggedin() || isguestuser()) ? 'lock' : 'user';
$logintitle = (!isloggedin() || isguestuser()) ? get_string('login', 'core') : get_string('profile', 'core');

$html = '';

if (!theme_mb2nl_is_header_tools_modal()) {

    $html .= '<div class="sliding-panel dark1" data-open="false">';
    $html .= '<div class="sliding-panel-inner">';
    $html .= '<div class="container-fluid">';
    $html .= '<div class="row">';
    $html .= '<div class="col-md-12 clearfix">';
    $html .= theme_mb2nl_search_form();
    $html .= theme_mb2nl_login_form();
    $html .= '</div>';
    $html .= '</div>';
    $html .= '</div>';
    $html .= '</div>';
    $html .= '</div>';

    echo $html;

    $PAGE->requires->js_call_amd('theme_mb2nl/slidingpanel', 'init');

}
