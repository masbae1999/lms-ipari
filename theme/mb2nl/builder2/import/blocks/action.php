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
    'id' => 'action',
    'title' => get_string('action', 'local_mb2builder'),
    'items' => [
        [
            'id' => 'action-1',
            'name' => 'action-1',
            'thumb' => 'action-1',
            'tags' => 'action',
            'data' => '',
        ],
        [
            'id' => 'action-2',
            'name' => 'action-2',
            'thumb' => 'action-2',
            'tags' => 'action',
            'data' => '',
        ],
    ],
];

define('LOCAL_MB2BUILDER_IMPORT_BLOCKS_ACTION', base64_encode(serialize($mb2settings)));
