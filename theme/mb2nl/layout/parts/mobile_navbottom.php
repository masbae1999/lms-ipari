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

$iconnav = theme_mb2nl_iconnav(true);
$topmenu = theme_mb2nl_topmenu();
$socialtt = theme_mb2nl_theme_setting($PAGE, 'socialtt') == 1 ? 'top' : '';

$html = '';

if (theme_mb2nl_theme_setting($PAGE, 'socialheader') || $iconnav || theme_mb2nl_header_buttons() || $topmenu) {
    $html .= '<div class="mobile-navbottom extra-content" id="mobilemenu_extra-content">';
    $html .= $topmenu;
    $html .= $iconnav;
    $html .= theme_mb2nl_header_buttons(3, true);

    if (theme_mb2nl_theme_setting($PAGE, 'socialheader')) {
        $html .= theme_mb2nl_social_icons(['tt' => $socialtt, 'pos' => 'mobile']);
    }

    $html .= '</div>';
}

echo $html;
