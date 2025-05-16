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

global $PAGE, $OUTPUT;

$socilatt = theme_mb2nl_theme_setting($PAGE, 'socialtt') == 1 ? 'top' : '';
$footct = theme_mb2nl_format_txt(theme_mb2nl_theme_setting($PAGE, 'foottext'), FORMAT_HTML);
$html = '';

$html .= '<footer class="login-footer">';
$html .= '<div class="container-fluid">';
$html .= '<div class="row">';
$html .= '<div class="col-md-12">';
$html .= '<div class="footer-inner">';
$html .= '<p class="mb-2">' . $footct . '</p>';
$html .= theme_mb2nl_language_list('footer');

if (theme_mb2nl_theme_setting($PAGE, 'socialfooter') == 1) {
    $html .= '<div class="footer-social">';
    $html .= theme_mb2nl_social_icons(['tt' => $socilatt, 'pos' => 'footer']);
    $html .= '</div>';
}

$html .= '</div>';
$html .= '</div>';
$html .= '</div>';
$html .= '</div>';
$html .= '</footer>';
$html .= '</div> <!--  end pagelayout-content -->';
$html .= '</div> <!--  end pagelayout-b -->';
$html .= '</div> <!--  end .pagelayout -->';
$html .= $OUTPUT->theme_part('region_adminblock');
$html .= '</div><!-- end #page-outer -->';
$html .= $OUTPUT->theme_part('course_panel');
$html .= theme_mb2nl_note_moadalform();
$html .= $OUTPUT->standard_end_of_body_html();
$html .= '</body>';
$html .= '</html>';

$PAGE->requires->js_amd_inline('require([\'theme_boost/loader\']);');

echo $html;
