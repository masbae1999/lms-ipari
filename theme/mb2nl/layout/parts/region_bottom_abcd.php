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

$isdark = theme_mb2nl_theme_setting($PAGE, 'footerstyle') === 'dark' ? 'dark1' : '';
$a = theme_mb2nl_isblock('bottom-a');
$b = theme_mb2nl_isblock('bottom-b');
$c = theme_mb2nl_isblock('bottom-c');
$d = theme_mb2nl_isblock('bottom-d');

$col1 = (($a && !$b && !$c && !$d) || (!$a && $b && !$c && !$d) || (!$a && !$b && $c && !$d) || (!$a && !$b && !$c && $d));
$col2 = (($a && $b && !$c && !$d) || ($a && !$b && $c && !$d) || ($a && !$b && !$c && $d) || (!$a && $b && $c && !$d) ||
(!$a && $b && !$c && $d) || (!$a && !$b && $c && $d));
$col3 = (($a && $b && $c && !$d) || ($a && $b && !$c && $d) || ($a && !$b && $c && $d) || (!$a && $b && $c && $d));
$col4 = ($a && $b && $c && $d);

if ($col4) {
    $col = '3';
} else if ($col3) {
    $col = '4';
} else if ($col2) {
    $col = '6';
} else {
    $col = '12';
}

$abcd = ['a', 'b', 'c', 'd'];

$html = '';

if ($a || $b || $c || $d) {
    $html .= '<div id="bottom-abcd" class="' . $isdark . '">';
    $html .= '<div class="container-fluid">';
    $html .= '<div class="row">';

    foreach ($abcd as $block) {
        if (theme_mb2nl_isblock('bottom-' . $block)) {
            $html .= '<div class="col-md-' . $col . '">';
            $html .= $OUTPUT->blocks('bottom-' . $block, theme_mb2nl_block_cls('bottom-' . $block, 'bottom'));
            $html .= '</div>';
        }
    }

    $html .= '</div>';
    $html .= '</div>';
    $html .= '</div>';

    echo $html;
}
