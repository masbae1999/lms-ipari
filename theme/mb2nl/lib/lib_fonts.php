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



/**
 *
 * Method to get Google webfonts
 *
 */
function theme_mb2nl_google_fonts($attribs = []) {
    global $PAGE;

    $output = '';

    $gfontsubset = theme_mb2nl_theme_setting($PAGE, 'gfontsubset');

    for ($i = 1; $i <= 3; $i++) {

        $gfontname = theme_mb2nl_theme_setting($PAGE, 'gfont' . $i);
        $gfontstyle = theme_mb2nl_theme_setting($PAGE, 'gfontstyle' . $i);
        $isstyle = $gfontstyle != '' ? ':' . $gfontstyle : '';

        $issubset = $gfontsubset != '' ? '&amp;subset=' . $gfontsubset : '';

        if ($gfontname !== '') {
            $output .= '<link href="//fonts.googleapis.com/css?family=' . str_replace(' ', '+', $gfontname) . $isstyle .
            $issubset . '" rel="stylesheet">';
        }

    }

    return $output;

}






/**
 *
 * Method to get custom fonts
 *
 */
function theme_mb2nl_custom_fonts() {

    global $PAGE;
    $output = '';

    for ($i = 1; $i <= 3; $i++) {
        $fonts = theme_mb2nl_filearea('cfontfiles' . $i, false);
        $fontname = theme_mb2nl_theme_setting($PAGE, 'cfont' . $i);
        $x = 0;

        if (count($fonts) && $fontname) {
            $output .= '@font-face {';
            $output .= 'font-family:\'' .$fontname . '\';';
            $output .= 'src: ';

            foreach ($fonts as $f) {
                $x++;
                $finfo = pathinfo($f);
                $sep = $x == count($fonts) ? ';' : ', ';
                $format = $finfo['extension'];

                if ($finfo['extension'] === 'ttf') {
                    $format = 'truetype';
                }

                $output .= 'url(\'' . $finfo['dirname'] . '/' . $finfo['basename'] . '\') format(\'' . $format . '\')' . $sep;
            }

            $output .= '}';
        }
    }

    return $output;

}




/**
 *
 * Method to get font icons
 *
 */
function theme_mb2nl_fonticons() {
    global $PAGE;
    $output = '';

    $output .= theme_mb2nl_fontface('Pe-icon-7-stroke');
    $output .= theme_mb2nl_fontface('LineIcons');
    $output .= theme_mb2nl_fontface('Glyphicons-Halflings', true);
    $output .= theme_mb2nl_fontface('remixicon', true);
    $output .= theme_mb2nl_fontface('bootstrap-icons', true);

    if (theme_mb2nl_theme_setting($PAGE, 'acsboptions') && theme_mb2nl_theme_setting($PAGE, 'dyslexic')) {
        $output .= theme_mb2nl_fontface('opendyslexic', true);
    }

    return $output;

}





/**
 *
 * Method to get font icons
 *
 */
function theme_mb2nl_fontface($fontname, $woff2 = false) {
    global $CFG;

    $output = '';
    $assetsur = $CFG->wwwroot . theme_mb2nl_themedir() . '/mb2nl/assets/' . $fontname . '/fonts/';
    $fonfamily = $fontname;
    $svgfontname = $fontname;
    $enbl = true;
    $comma = ', ';
    $comma2 = ', ';

    if ($fontname === 'Glyphicons-Halflings') {
        // ...font family: Glyphicons Halflings
        // ...svg: glyphicons_halflingsregular
        // ...filename: glyphicons-halflings
        $fonfamily = 'Glyphicons Halflings';
        $svgfontname = 'glyphicons_halflingsregular';
        $fontname = 'glyphicons-halflings-regular';
    }

    if ($fontname === 'opendyslexic') {
        $enbl = false;
        $fonfamily = 'OpenDyslexic';
        $comma = ';';
    }

    $woff = $enbl;

    if ($fontname === 'bootstrap-icons') {
        $enbl = false;
        $woff = true;
        $comma2 = ';';
    }

    $output .= '@font-face {';
    $output .= 'font-family:\'' . $fonfamily . '\';';
    $output .= 'src: ';
    $output .= $woff2 ? 'url(\'' . $assetsur . $fontname . '.woff2\') format(\'woff2\')' . $comma : '';// Super Modern Browsers.
    $output .= $woff ? 'url(\'' . $assetsur . $fontname . '.woff\') format(\'woff\')' . $comma2 : ''; // Pretty Modern Browsers.
    $output .= $enbl ? 'url(\'' . $assetsur . $fontname . '.ttf\') format(\'truetype\'),' : '';// Safari, Android, iOS.
    $output .= $enbl ? 'url(\'' . $assetsur . $fontname . '.svg#' . $svgfontname . '\') format(\'svg\');' : '';// Legacy iOS.
    $output .= 'font-weight: normal;';
    $output .= 'font-style: normal;';
    $output .= '}';

    return $output;

}




/**
 *
 * Method to get font family setting
 *
 */
function theme_mb2nl_get_fonf_family($page, $font) {

    return '\'' . theme_mb2nl_theme_setting($page, $font) . '\'';

}






/**
 *
 * Method to get font icons for plugins (page builder and megamenu)
 *
 */
function theme_mb2nl_get_icons4plugins() {

    return [
        'font-awesome' => [
            'name' => 'Font Awesome',
            'folder' => 'font-awesome',
            'css' => 'font-awesome',
            'prefhtml' => 'fa ',
            'tabid' => 'tab-font-icons-fa',
        ],
        'remixicon' => [
            'name' => 'Remix icons',
            'folder' => 'remixicon',
            'css' => 'remixicon',
            'prefhtml' => '',
            'tabid' => 'tab-font-icons-remix',
        ],
        'bootstrap-icons' => [
            'name' => 'Bootstrap icons',
            'folder' => 'bootstrap-icons',
            'css' => 'bootstrap-icons',
            'prefhtml' => 'bi ',
            'tabid' => 'tab-font-bootstrap-icons',
        ],
        'Pe-icon-7-stroke' => [
            'name' => '7 Stroke',
            'folder' => 'Pe-icon-7-stroke',
            'css' => 'Pe-icon-7-stroke',
            'prefhtml' => '',
            'tabid' => 'tab-font-icons-7stroke',
        ],
        'LineIcons' => [
            'name' => 'Line icons',
            'folder' => 'LineIcons',
            'css' => 'LineIcons',
            'prefhtml' => '',
            'tabid' => 'tab-font-icons-lineicons',
        ],
        'glyphicons' => [
            'name' => 'Glyphicons',
            'folder' => 'Glyphicons-Halflings',
            'css' => 'glyphicons',
            'prefhtml' => 'glyphicon ',
            'tabid' => 'tab-font-icons-glyph',
        ],
    ];

}
