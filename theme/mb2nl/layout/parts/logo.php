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

global $SITE;

$customlogin = theme_mb2nl_is_login(true);
$logos = ['logo-light', 'logo-dark'];

$html = '';

$html .= '<div class="logo-wrap">';
$html .= '<div class="main-logo">';
$html .= '<a href="' . new moodle_url('/') . '" aria-label="' . $SITE->fullname . '">';

foreach ($logos as $l) {
    $pblogo = theme_mb2nl_builder_logo($l);
    $src = $l === 'logo-light' ? theme_mb2nl_logo_url() : theme_mb2nl_logo_url(false, $l);
    $src = $pblogo ? $pblogo : $src;

    $svgcls = theme_mb2nl_is_svg($src) ? ' is_svg' : ' no_svg';
    $html .= '<img class="' . $l . $svgcls . '" src="' . $src . '" alt="' . $SITE->fullname . '">';
}

$html .= '</a>';
$html .= '</div>';
$html .= '</div>';

echo $html;
