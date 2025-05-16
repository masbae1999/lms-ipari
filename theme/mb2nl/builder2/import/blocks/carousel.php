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
    'id' => 'carousel',
    'title' => get_string('carousel', 'local_mb2builder'),
    'items' => [
        [
            'id' => 'carousel-1',
            'name' => 'carousel-1',
            'thumb' => 'carousel-1',
            'tags' => 'carousel',
            'data' => '',
        ],
        [
            'id' => 'carousel-2',
            'name' => 'carousel-2',
            'thumb' => 'carousel-2',
            'tags' => 'carousel',
            'data' => '',
        ],
        [
            'id' => 'carousel-3',
            'name' => 'carousel-3',
            'thumb' => 'carousel-3',
            'tags' => 'carousel',
            'data' => '',
        ],
        [
            'id' => 'carousel-4',
            'name' => 'carousel-4',
            'thumb' => 'carousel-4',
            'tags' => 'carousel',
            'data' => '',
        ],
    ],
];

define('LOCAL_MB2BUILDER_IMPORT_BLOCKS_CAROUSEL', base64_encode(serialize($mb2settings)));
