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
    'id' => 'hero',
    'title' => get_string('hero', 'local_mb2builder'),
    'items' => [
        [
            'id' => 'hero-1',
            'name' => 'hero-1',
            'thumb' => 'hero-1',
            'tags' => 'hero video',
            'data' => '',
        ],
        [
            'id' => 'hero-2',
            'name' => 'hero-2',
            'thumb' => 'hero-2',
            'tags' => 'hero',
            'data' => '',
        ],
        [
            'id' => 'hero-3',
            'name' => 'hero-3',
            'thumb' => 'hero-3',
            'tags' => 'hero search',
            'data' => '',
        ],
        [
            'id' => 'hero-4',
            'name' => 'hero-4',
            'thumb' => 'hero-4',
            'tags' => 'hero',
            'data' => '',
        ],
    ],
];

define('LOCAL_MB2BUILDER_IMPORT_BLOCKS_HERO', base64_encode(serialize($mb2settings)));
