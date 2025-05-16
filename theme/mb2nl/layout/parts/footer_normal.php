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

global $OUTPUT, $PAGE;

$footerid = theme_mb2nl_footerid();
$socilatt = theme_mb2nl_theme_setting($PAGE, 'socialtt') == 1 ? 'top' : '';
$footthemecontent = theme_mb2nl_theme_setting($PAGE, 'foottext');
$footcontent = theme_mb2nl_format_txt($footthemecontent, FORMAT_HTML);
$isdark = theme_mb2nl_theme_setting($PAGE, 'footerstyle') === 'dark' && ! $footerid ? ' dark1' : '';
$partnerlogos = theme_mb2nl_get_footer_images();
$parnerlinks = theme_mb2nl_line_content(theme_mb2nl_theme_setting($PAGE, 'partnerslinks'));
$quickview = theme_mb2nl_theme_setting($PAGE, 'quickview');
$footercss = $footerid ? 'custom-footer' : 'theme-footer';
$footercss .= $isdark;

$html = '';

$html .= '</div><!-- //end #page-b -->';
$html .= '</div><!--  end .pagelayout-content -->';
$html .= '<div class="page-c">';

if (count($partnerlogos) && ! $footerid) {
    $html .= '<div class="partners">';
    $html .= '<div class="container-fluid">';
    $html .= '<div class="row">';
    $html .= '<div class="col-md-12">';
    $html .= '<div class="partner-images">';

    foreach ($partnerlogos as $k => $logo) {
        $isk = $k + 1;
        $alttext = isset($parnerlinks[$isk]['text']) ? $parnerlinks[$isk]['text'] : '';
        $link = isset($parnerlinks[$isk]['url']) ? $parnerlinks[$isk]['url'] : '';
        $target = (isset($parnerlinks[$isk]['url_target']) && $parnerlinks[$isk]['url_target']) ? ' target="_blank"' : '';

        if ($link) {
            $html .= '<a href="' . $link . '"' . $target . '>';
        }

            $html .= '<img src="' . $logo . '" alt="' . $alttext . '">';

        if ($link) {
            $html .= '</a>';
        }
    }

    $html .= '</div>';
    $html .= '</div>';
    $html .= '</div>';
    $html .= '</div>';
    $html .= '</div>';
}

$html .= '<footer id="footer" class="' . $footercss . ' main-footer">';

if ($footerid) {
    $html .= theme_mb2nl_format_txt('[mb2footer footerid="' . $footerid . '"]', FORMAT_HTML);
} else {
    $html .= '<div class="container-fluid">';
    $html .= '<div class="row">';
    $html .= '<div class="col-md-12">';
    $html .= '<div class="footer-content flexcols">';
    $html .= '<div class="footer-text">';
    $html .= '<p>' . $footcontent . '</p>';
    $html .= theme_mb2nl_language_list('footer');
    $html .= '</div>';

    if (theme_mb2nl_theme_setting($PAGE, 'socialfooter') == 1) {
        $html .= '<div class="footer-social">';
        $html .= theme_mb2nl_social_icons(['tt' => $socilatt, 'pos' => 'footer']);
        $html .= '</div>';
    }

    $html .= '</div>';
    $html .= $OUTPUT->theme_part('footer_tools');

    $html .= '</div>';
    $html .= '</div>';
    $html .= '</div>';
}

$html .= '</footer>';
$html .= '</div><!-- //end #page-c -->';
$html .= $OUTPUT->theme_part('region_adminblock');
$html .= '</div><!--  end pagelayout-b -->';
$html .= '</div><!--  end .pagelayout -->';
$html .= '</div><!-- end #page-outer -->';
$html .= theme_mb2nl_iconnav();

if (theme_mb2nl_panel_link('fixedbar') || theme_mb2nl_show_hide_sidebars($vars)) {
    $html .= '<div class="fixed-bar">';
    $html .= theme_mb2nl_panel_link('fixedbar');
    $html .= theme_mb2nl_show_hide_sidebars($vars);
    $html .= '</div>';
}

$html .= theme_mb2nl_scrolltt();
$html .= '<a href="#page" class="sr-only sr-only-focusable">' . get_string('scrolltt', 'theme_mb2nl') . '</a>';
$html .= $OUTPUT->theme_part('course_panel');

if (theme_mb2nl_theme_setting($PAGE, 'bookmarks') && isloggedin() && !isguestuser()) {
    $html .= theme_mb2nl_user_bookmarks_modal();
}

if (theme_mb2nl_is_header_tools_modal()) {
    $html .= theme_mb2nl_modal_tmpl('login');
    $html .= theme_mb2nl_modal_tmpl('search');
}

$html .= theme_mb2nl_note_moadalform();
$html .= $OUTPUT->standard_end_of_body_html();
$html .= '</body>';
$html .= '</html>';

echo $html;

if ($quickview) {
    $inlinejs = 'require([\'theme_mb2nl/quickview\'], function(QuickView) {';
    $inlinejs .= 'new QuickView();';
    $inlinejs .= '});';
    $PAGE->requires->js_amd_inline($inlinejs);
}

$PAGE->requires->js_amd_inline('require([\'theme_boost/loader\']);');
