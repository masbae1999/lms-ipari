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

mb2_add_shortcode('toc', 'mb2_shortcode_toc');

/**
 *
 * Method to define shortcode
 *
 * @return HTML
 */
function mb2_shortcode_toc($atts, $content=null) {

    global $PAGE, $DB;

    $a = mb2_shortcode_atts([], $atts);

    $output = '';

    if ($PAGE->pagetype !== 'mod-page-view') {
        return;
    }

    // Get page ID.
    $pageid = $DB->get_record('course_modules', ['id' => $PAGE->cm->id], 'instance', MUST_EXIST);
    $pageid = $pageid->instance;

    // Get page content.
    $pcontent = $DB->get_record('page', ['id' => $pageid], '*', MUST_EXIST);
    $pcontent = theme_mb2nl_toc_get_headlines($pcontent->content);

    if (! count($pcontent)) {
        return;
    }

    // Get table of content HTML.
    $output .= '<div class="theme-toc">';
    $output .= '<p>' . get_string('onthispage', 'theme_mb2nl') . ':</p>';
    $output .= theme_mb2nl_toc_get_html($pcontent);
    $output .= '</div>';

    $PAGE->requires->js_call_amd('theme_mb2nl/toc', 'shortcodeToc');

    return $output;

}




/**
 *
 * Method to get toc html
 *
 * @return HTML
 */
function theme_mb2nl_toc_get_html($toc, $class=true) {

    $output = '';
    $cls = $class ? 'toc-list' : '';
    $output .= '<ol class="' . $cls . '">';

    foreach ($toc as $t) {
        $output .= '<li>';
        $output .= '<a href="#' . theme_mb2nl_string_url_safe($t['text']) . '" aria-describedby="' .
        theme_mb2nl_string_url_safe($t['text']) . '">' . $t['text'] . '</a>';

        if (count($t['sub_toc'])) {
            $output .= theme_mb2nl_toc_get_html($t['sub_toc'], false);
        }

        $output .= '</li>';
    }

    $output .= '</ol>';

    return $output;

}


/**
 *
 * Method to get toc headlines
 *
 * @return array
 */
function theme_mb2nl_toc_get_headlines($html, $depth=3) {

    if ($depth > 7) {
        return [];
    }

    $headlines = explode('<h' . $depth, $html);
    unset($headlines[0]); // ...contains only text before the first headline

    if (count($headlines) == 0) {
        return [];
    }

    $toc = []; // ...will contain the (sub-) toc

    foreach ($headlines as $headline) {

        list($hlinfo, $temp) = explode('>', $headline, 2);

        // ...$hlinfo contains attributes of <hi ... > like the id.
        list($hltext, $subcontent) = explode('</h' . $depth . '>', $temp, 2);

        // ...$hl contains the headline
        // ...$subcontent contains maybe other <hi>-tags
        $id = '';

        if (strlen($hlinfo) > 0 && ($idtagpos = stripos($hlinfo, 'id')) !== false) {
            $idstartpos = stripos($hlinfo, '"', $idtagpos);
            $idendpos = stripos($hlinfo, '"', $idstartpos);
            $id = substr($hlinfo, $idstartpos, $idendpos - $idstartpos);
        }

        $toc[] = [
            'id' => $id,
            'text' => $hltext,
            'sub_toc' => theme_mb2nl_toc_get_headlines($subcontent, $depth + 1),
        ];
    }

    return $toc;

}
