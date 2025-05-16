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
    'id' => 'pbmainslider',
    'title' => get_string('pbmainslider', 'local_mb2builder'),
    'items' => [
        [
            'id' => 'pbmainslider-1',
            'name' => 'pbmainslider-1',
            'thumb' => 'pbmainslider-1',
            'tags' => 'pbmainslider',
            'data' => '',
        ],
        [
            'id' => 'pbmainslider-2',
            'name' => 'pbmainslider-2',
            'thumb' => 'pbmainslider-2',
            'tags' => 'pbmainslider',
            'data' => '',
        ],
        [
            'id' => 'pbmainslider-3',
            'name' => 'pbmainslider-3',
            'thumb' => 'pbmainslider-3',
            'tags' => 'pbmainslider',
            'data' => '',
        ],
    ],
];

define('LOCAL_MB2BUILDER_IMPORT_BLOCKS_PBMAINSLIDER', base64_encode(serialize($mb2settings)));
