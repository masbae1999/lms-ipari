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
    'id' => 'school',
    'title' => get_string('school', 'local_mb2builder'),
    'items' => [
        [
            'id' => 'programming-school',
            'name' => 'programming-school',
            'thumb' => 'programming-school',
            'tags' => 'school',
            'data' => '',
        ],
        [
            'id' => 'new-school',
            'name' => 'new-school',
            'thumb' => 'new-school',
            'tags' => 'school',
            'data' => '',
        ],
        [
            'id' => 'school-1',
            'name' => 'school-1',
            'thumb' => 'school-1',
            'tags' => 'school',
            'data' => '',
        ],
        [
            'id' => 'school-language',
            'name' => 'school-language',
            'thumb' => 'school-language',
            'tags' => 'school',
            'data' => '',
        ],
        [
            'id' => 'school-2',
            'name' => 'school-2',
            'thumb' => 'school-2',
            'tags' => 'school',
            'data' => '',
        ],
        [
            'id' => 'school-3',
            'name' => 'school-3',
            'thumb' => 'school-3',
            'tags' => 'school',
            'data' => '',
        ],
        [
            'id' => 'school-4',
            'name' => 'school-4',
            'thumb' => 'school-4',
            'tags' => 'school',
            'data' => '',
        ],
        [
            'id' => 'school-5',
            'name' => 'school-5',
            'thumb' => 'school-5',
            'tags' => 'school',
            'data' => '',
        ],
    ],
];

define('LOCAL_MB2BUILDER_IMPORT_LAYOUTS_SCHOOL', base64_encode(serialize($mb2settings)));
