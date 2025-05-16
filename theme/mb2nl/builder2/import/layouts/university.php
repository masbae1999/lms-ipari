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
    'id' => 'university',
    'title' => get_string('university', 'local_mb2builder'),
    'items' => [
        [
            'id' => 'university-1',
            'name' => 'university-1',
            'thumb' => 'university-1',
            'tags' => 'university',
            'data' => '',
        ],
        [
            'id' => 'university-2',
            'name' => 'university-2',
            'thumb' => 'university-2',
            'tags' => 'university',
            'data' => '',
        ],
        [
            'id' => 'university-3',
            'name' => 'university-3',
            'thumb' => 'university-3',
            'tags' => 'university',
            'data' => '',
        ],
    ],
];

define('LOCAL_MB2BUILDER_IMPORT_LAYOUTS_UNIVERSITY', base64_encode(serialize($mb2settings)));
