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
    'id' => 'iconboxes',
    'title' => get_string('iconboxes', 'local_mb2builder'),
    'items' => [
        [
            'id' => 'iconboxes-1',
            'name' => 'iconboxes-1',
            'thumb' => 'iconboxes-1',
            'tags' => 'iconboxes',
            'data' => '',
        ],
        [
            'id' => 'iconboxes-2',
            'name' => 'iconboxes-2',
            'thumb' => 'iconboxes-2',
            'tags' => 'iconboxes',
            'data' => '',
        ],
        [
            'id' => 'iconboxes-3',
            'name' => 'iconboxes-3',
            'thumb' => 'iconboxes-3',
            'tags' => 'iconboxes',
            'data' => '',
        ],
        [
            'id' => 'iconboxes-4',
            'name' => 'iconboxes-4',
            'thumb' => 'iconboxes-4',
            'tags' => 'iconboxes',
            'data' => '',
        ],
    ],
];

define('LOCAL_MB2BUILDER_IMPORT_BLOCKS_ICONBOXES', base64_encode(serialize($mb2settings)));
