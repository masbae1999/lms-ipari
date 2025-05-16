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

// Page builder footer style.
$footer = optional_param('footer', 0, PARAM_INT);
$itemid = optional_param('itemid', 0, PARAM_INT);

$bodycls = theme_mb2nl_body_cls($itemid);

if ($footer == 1) {
    $bodycls[] = 'builderfooter';
    $bodycls[] = 'page-c';
} else {
    $bodycls[] = 'page-b'; // This is require because of the responsive break point class.
}

$bodycls[] = 'page-layout-' . theme_mb2nl_theme_setting($PAGE, 'layout');
$bgimage = theme_mb2nl_pagebg_image(false);
$html = '';

$html .= $OUTPUT->doctype();
$html .= $OUTPUT->theme_part('head');
$html .= '<body ' . $OUTPUT->body_attributes($bodycls) . ' data-headerstyle="' .
theme_mb2nl_theme_setting($PAGE, 'headerstyle') . '">';

if ($bgimage) {
    $html .= '<div class="page-bgimg lazy position-fixed" style="background-image:url(' . $bgimage . ');"></div>';
}

$html .= '<div id="page" class="position-relative">';
$html .= $OUTPUT->standard_top_of_body_html();
$html .= '<div id="main-content">';
$html .= '<div id="page-content">';
$html .= $OUTPUT->main_content();
$html .= '</div>';
$html .= '</div>';
$html .= '</div>';

$html .= $OUTPUT->standard_end_of_body_html();
$html .= '</body>';
$html .= '</html>';

echo $html;

$PAGE->requires->js_amd_inline('require([\'theme_boost/loader\']);');
