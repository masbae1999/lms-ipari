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
 * Filter converting shortcodes [...] to HTML
 *
 * @package     filter_mb2shortcodes
 * @author      Mariusz Boloz (lmsstyle.com)
 * @copyright   2017 - 2024 Mariusz Boloz (lmsstyle.com)
 * @license     PHP and HTML: http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later. Other parts: http://themeforest.net/licenses
 */

namespace filter_mb2shortcodes;

defined( 'MOODLE_INTERNAL' ) || die();

global $CFG;

require_once( __DIR__ . '/../lib/shortcodes.php');

$themename = $CFG->theme;

if ( get_config( 'filter_mb2shortcodes', 'themename' ) ) {
    $themename = get_config( 'filter_mb2shortcodes', 'themename' );
}

$shortcodesdir = '';
$currenttheme = $CFG->dirroot . '/theme/' . $themename . '/shortcodes/';

if ( is_dir($currenttheme) ) {
    $shortcodesdir = $currenttheme;
}

$filter = 'php';

if ( is_dir($shortcodesdir) ) {
    $dircontents = scandir($shortcodesdir);

    foreach ($dircontents as $file) {
        $filetype = pathinfo( $file, PATHINFO_EXTENSION );

        if ( $filetype !== $filter ) {
            continue;
        }

        require_once($shortcodesdir . basename($file));
    }
}

/**
 * Filter class
 *
 */
class text_filter extends \core_filters\text_filter {

    /**
     * Filter text
     *
     */
    public function filter($text, array $options = []) {
        global $PAGE, $DB;
        $output = '';

        $array2 = [
            'GENERIC0' => 'GENERICO',
        ];

        $array1 = [
            // Before and after shortcode tag shortcode.
            '<p>[' => '[',
            '<p> [' => '[',
             ']</p>' => ']',
             '] </p>' => ']',
            ']<br></p>' => ']',
            ']</p><br>' => ']',
            '] </p><br>' => ']',
            ']</p> <br>' => ']',
            '] </p> <br>' => ']',
            '] <br></p>' => ']',
            ']<br> </p>' => ']',
            '] <br> </p>' => ']',
            ']<br>' => ']',
            '] <br>' => ']',
            '"&nbsp;' => '" ',

            // Additional filter.
            '<p></p>' => '',
            '<p> </p>' => '',
            '<p><br>' => '<p>',
            '<p> <br>' => '<p>',
            '<br></p>' => '</p>',
            '<br> </p>' => '</p>',
        ];

        $array = $array1;

        if (!preg_match('@mb2builder@', $PAGE->pagetype)) {
            $array = array_merge($array1, $array2);
        }

        $textfixed = strtr($text, $array);
        return mb2_do_shortcode($textfixed);

    }

}
