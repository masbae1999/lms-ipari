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

$mb2settings = [
    'id' => 'footer',
    'title' => get_string('footer', 'local_mb2builder'),
    'items' => [
        [
            'id' => 'footer-2',
            'name' => 'footer-2',
            'thumb' => 'footer-2',
            'tags' => 'footer',
            'data' => '',
        ],
        [
            'id' => 'footer-3',
            'name' => 'footer-3',
            'thumb' => 'footer-3',
            'tags' => 'footer',
            'data' => '',
        ],
        [
            'id' => 'footer-4',
            'name' => 'footer-4',
            'thumb' => 'footer-4',
            'tags' => 'footer',
            'data' => '',
        ],
        [
            'id' => 'footer-5',
            'name' => 'footer-5',
            'thumb' => 'footer-5',
            'tags' => 'footer',
            'data' => '',
        ],
        [
            'id' => 'footer-6',
            'name' => 'footer-6',
            'thumb' => 'footer-6',
            'tags' => 'footer',
            'data' => '',
        ],
        [
            'id' => 'footer-7',
            'name' => 'footer-7',
            'thumb' => 'footer-7',
            'tags' => 'footer',
            'data' => '',
        ],
    ],
];

define('LOCAL_MB2BUILDER_IMPORT_BLOCKS_FOOTER', base64_encode(serialize($mb2settings)));
