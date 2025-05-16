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

global $PAGE, $USER;

require_once($CFG->libdir . '/authlib.php');
$loginicon = ! isloggedin() || isguestuser() ? 'ri-lock-line' : 'ri-user-3-line';
$logintext = ! isloggedin() || isguestuser() ? get_string('login') : $USER->firstname;
$btncls = theme_mb2nl_bsfcls(2, 'column', 'center', 'center');
$headercontent = theme_mb2nl_theme_setting($PAGE, 'headercontent') &&
theme_mb2nl_static_content(theme_mb2nl_theme_setting($PAGE, 'headercontent'), false);

$html = '';

$html .= '<div class="mnavtop menu-extracontent">';
$html .= '<div class="menu-extracontent-controls' . theme_mb2nl_bsfcls(1, 'row', 'center', 'center') . '">';

if (theme_mb2nl_is_site_menu() && ! theme_mb2nl_is_tgsdb()) {
    $html .= '<button class="themereset p-0 menu-extra-controls-btn menu-extra-controls-quicklinks' .
    $btncls . '" aria-label="' . get_string('quicklinks', 'theme_mb2nl') . '" aria-controls="menu-quicklinkscontainer"
    aria-expanded="false"><i class="ri-apps-2-line"></i><span class="d-block mt-1 label' . theme_mb2nl_tcls('xxsmall') . '">' .
    get_string('quicklinks', 'theme_mb2nl') . '</span></button>';
}

$html .= '<button class="themereset p-0 menu-extra-controls-btn menu-extra-controls-search' .
$btncls . '" aria-label="' . get_string('togglesearch', 'theme_mb2nl') . '" aria-controls="menu-searchcontainer"
aria-expanded="false"><i class="ri-search-line"></i><span class="d-block mt-1 label' . theme_mb2nl_tcls('xxsmall') . '">' .
get_string('search') . '</span></button>';
$html .= '<button class="themereset p-0 menu-extra-controls-btn menu-extra-controls-login' .
$btncls . '" aria-label="' . get_string('togglelogin', 'theme_mb2nl') . '" aria-controls="menu-logincontainer"
aria-expanded="false"><i class="' . $loginicon  . '"></i><span class="d-block mt-1 label' . theme_mb2nl_tcls('xxsmall') . '">' .
$logintext . '</span></button>';

if ((! isloggedin() || isguestuser()) && (signup_is_enabled() || theme_mb2nl_theme_setting($PAGE, 'signuppage'))) {
    $html .= '<button class="themereset p-0 menu-extra-controls-btn menu-extra-controls-register' . $btncls . '" aria-label="' .
    get_string('register', 'theme_mb2nl') . '" aria-controls="menu-registercontainer" aria-expanded="false">
    <i class="ri-user-3-line"></i><span class="d-block mt-1 label' . theme_mb2nl_tcls('xxsmall') . '">' .
    get_string('register', 'theme_mb2nl') . '</span></button>';
}

if ($headercontent) {
    $html .= '<button class="themereset p-0 menu-extra-controls-btn menu-extra-controls-content' . $btncls . '" aria-label="' .
    get_string('toggleheadercontent', 'theme_mb2nl') . '" aria-controls="menu-staticontentcontainer" aria-expanded="false">
    <i class="ri-information-line"></i><span class="d-block mt-1 label' . theme_mb2nl_tcls('xxsmall') . '">' .
    get_string('info') . '</span></button>';
}

$html .= '</div>';

if (theme_mb2nl_is_site_menu() && ! theme_mb2nl_is_tgsdb()) {
    $html .= '<div id="menu-quicklinkscontainer" class="menu-extracontent-content">';
    $html .= theme_mb2nl_site_menu(true);
    $html .= '</div>';
}

$html .= theme_mb2nl_search_form(true);
$html .= theme_mb2nl_login_form(false, true);
$html .= theme_mb2nl_register_form();

if ($headercontent) {
    $html .= '<div id="menu-staticontentcontainer" class="menu-extracontent-content">';
    $html .= theme_mb2nl_static_content(theme_mb2nl_theme_setting($PAGE, 'headercontent'), true, true,
    ['listcls' => 'mobile-header-list']);
    $html .= '</div>';
}

$html .= '</div>';

echo $html;
