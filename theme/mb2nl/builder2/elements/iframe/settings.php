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
    'id' => 'iframe',
    'subid' => '',
    'title' => get_string('iframe', 'local_mb2builder'),
    'icon' => 'fa fa-code',
    'tabs' => [
        'general' => get_string('generaltab', 'local_mb2builder'),
    ],
    'attr' => [
        'url' => [
            'type' => 'text',
            'section' => 'general',
            'title' => get_string('url', 'local_mb2builder'),
            'action' => 'attribute',
            'selector' => 'iframe',
            'attribute' => 'src',
            'changemode' => 'input',
            'default' => 'https://example.com',
        ],
        'width' => [
            'type' => 'range',
            'section' => 'general',
            'title' => get_string('widthlabel', 'local_mb2builder'),
            'min' => 20,
            'max' => 2000,
            'default' => 800,
            'action' => 'style',
            'changemode' => 'input',
            'style_properity' => 'max-width',
        ],
        'height' => [
            'type' => 'range',
            'section' => 'general',
            'title' => get_string('height', 'local_mb2builder'),
            'min' => 100,
            'max' => 2000,
            'default' => 350,
            'action' => 'attribute',
            'changemode' => 'input',
            'attribute' => 'height',
            'selector' => 'iframe',
        ],
        'mt' => [
            'type' => 'range',
            'section' => 'general',
            'title' => get_string('mt', 'local_mb2builder'),
            'min' => 0,
            'max' => 300,
            'default' => 0,
            'action' => 'style',
            'changemode' => 'input',
            'style_properity' => 'margin-top',
        ],
        'mb' => [
            'type' => 'range',
            'section' => 'general',
            'title' => get_string('mb', 'local_mb2builder'),
            'min' => 0,
            'max' => 300,
            'default' => 30,
            'action' => 'style',
            'changemode' => 'input',
            'style_properity' => 'margin-bottom',
        ],
        'custom_class' => [
            'type' => 'text',
            'section' => 'style',
            'title' => get_string('customclasslabel', 'local_mb2builder'),
            'desc' => get_string('customclassdesc', 'local_mb2builder'),
        ],
    ],
];

define('LOCAL_MB2BUILDER_SETTINGS_IFRAME', base64_encode(serialize($mb2settings)));
