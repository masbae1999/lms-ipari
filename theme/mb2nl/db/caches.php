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

$definitions = [
    // Shared caches.
    'catcolors' => [
        'mode' => cache_store::MODE_APPLICATION,
        'simplekeys' => true,
        'ttl' => 3600,
      ],
    'course' => [
        'mode' => cache_store::MODE_APPLICATION,
        'simplekeys' => true,
        'invalidationevents' => [
            'changesincourse',
        ],
        'ttl' => 3600,
    ],
    'category' => [
        'mode' => cache_store::MODE_APPLICATION,
        'simplekeys' => true,
        'ttl' => 3600,
    ],
    'coursefield' => [
        'mode' => cache_store::MODE_APPLICATION,
        'simplekeys' => true,
        'ttl' => 3600,
    ],
    'features' => [
        'mode' => cache_store::MODE_APPLICATION,
        'simplekeys' => true,
        'ttl' => 86400,  // ...just in case 24 hours
    ],
    'dsahboard' => [
        'mode' => cache_store::MODE_APPLICATION,
        'simplekeys' => true,
        'ttl' => 3600,
    ],

    // User caches.
    'ufeatures' => [
        'mode' => cache_store::MODE_SESSION,
        'simplekeys' => true,
        'ttl' => 3600,
    ],
    'userroles' => [
        'mode' => cache_store::MODE_SESSION,
        'invalidationevents' => [
            'changesincourse',
        ],
        'ttl' => 3600,
    ],
    'categories' => [
        'mode' => cache_store::MODE_SESSION,
        'invalidationevents' => [
            'changesincoursecat',
            'changesincourse',
        ],
        'ttl' => 3600,
    ],
    'courses' => [
        'mode' => cache_store::MODE_SESSION,
        'invalidationevents' => [
            'changesincoursecat',
            'changesincourse',
        ],
        'ttl' => 3600,
    ],
    'coursetags' => [
        'mode' => cache_store::MODE_SESSION,
        'invalidationevents' => [
            'changesincourse',
        ],
        'ttl' => 3600,
    ],
    'coursefields' => [
        'mode' => cache_store::MODE_SESSION,
        'invalidationevents' => [
            'changesincourse',
        ],
        'ttl' => 3600,
    ],
];
