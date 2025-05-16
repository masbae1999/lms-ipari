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

$html = '';
theme_mb2nl_scripts();

$html .= $OUTPUT->doctype();
$html .= '<html ' . $OUTPUT->htmlattributes() . '>';

$html .= '<head>';
$html .= '<title>' . $OUTPUT->page_title() . '</title>';
$html .= '<link rel="shortcut icon" href="' . $OUTPUT->favicon() . '">';
$html .= theme_mb2nl_google_fonts();
$html .= $OUTPUT->standard_head_html();
$html .= '<meta name="viewport" content="width=device-width, initial-scale=1.0">';
$html .= '</head>';

$html .= '<body ' . $OUTPUT->body_attributes(theme_mb2nl_body_cls()) . '>';
$html .= '<div id="page-outer" class="position-relative">';
$html .= '<div id="page">';
$html .= $OUTPUT->standard_top_of_body_html();
$html .= '<div id="main-content">';
$html .= '<div class="container-fluid">';
$html .= '<div class="row">';
$html .= '<div class="col-md-12">';
$html .= '<div id="page-content">';
$html .= $OUTPUT->main_content();
$html .= '</div>';
$html .= '</div>';
$html .= '</div>';
$html .= '</div>';
$html .= '</div>';

$html .= '<footer id="footer" class="dark1">';
$html .= '<div class="container-fluid">';
$html .= '<div class="row">';
$html .= '<div class="col-md-12">';
$html .= $OUTPUT->standard_footer_html();
$html .= '</div>';
$html .= '</div>';
$html .= '</div>';
$html .= '</footer>';

$html .= '</div><!-- end #page -->';
$html .= '</div><!-- end #page-outer -->';
$html .= theme_mb2nl_scrolltt();
$html .= $OUTPUT->standard_end_of_body_html();
$html .= '</body>';
$html .= '</html>';

echo $html;

$PAGE->requires->js_amd_inline('require([\'theme_boost/loader\']);');
