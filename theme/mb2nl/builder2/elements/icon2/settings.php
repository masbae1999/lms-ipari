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
    'id' => 'icon2',
    'subid' => '',
    'title' => get_string('icon', 'local_mb2builder'),
    'icon' => 'fa fa-heart',
    'tabs' => [
        'general' => get_string('generaltab', 'local_mb2builder'),
        'style' => get_string('styletab', 'local_mb2builder'),
    ],
    'attr' => [
        'name' => [
            'type' => 'icon',
            'section' => 'general',
            'title' => get_string('icon', 'local_mb2builder'),
            'default' => 'fa fa-star',
            'action' => 'icon',
            'selector' => '.icon-bg i',
        ],
        'desc' => [
            'type' => 'yesno',
            'section' => 'general',
            'title' => get_string('text', 'local_mb2builder'),
            'options' => [
                1 => get_string('yes', 'local_mb2builder'),
                0 => get_string('no', 'local_mb2builder'),
            ],
            'action' => 'class',
            'default' => 0,
            'class_prefix' => 'desc',
            'class_remove' => 'desc0 desc1',
        ],
        'text' => [
            'type' => 'text',
            'showon' => 'desc:1',
            'section' => 'general',
            'title' => get_string('text', 'local_mb2builder'),
            'default' => 'Icon text here.',
            'action' => 'text',
            'selector' => '.icon-desc',
        ],
        'size' => [
            'type' => 'buttons',
            'section' => 'general',
            'title' => get_string('sizelabel', 'local_mb2builder', ''),
            'options' => [
                'n' => get_string('normal', 'local_mb2builder'),
                'l' => get_string('large', 'local_mb2builder'),
                'xl' => get_string('xlarge', 'local_mb2builder'),
                'xxl' => get_string('xxlarge', 'local_mb2builder'),
            ],
            'default' => 'default',
            'action' => 'class',
            'class_prefix' => 'size',
            'class_remove' => 'sizen sizel sizexl sizexxl',
        ],
        'circle' => [
            'type' => 'yesno',
            'section' => 'general',
            'title' => get_string('circle', 'local_mb2builder'),
            'options' => [
                1 => get_string('yes', 'local_mb2builder'),
                0 => get_string('no', 'local_mb2builder'),
            ],
            'action' => 'class',
            'default' => 1,
            'class_prefix' => 'circle',
            'class_remove' => 'circle0 circle1',
        ],

        'color' => [
            'type' => 'color',
            'section' => 'general',
            'title' => get_string('color', 'local_mb2builder'),
            'action' => 'color',
            'selector' => '.icon-bg',
            'style_properity' => 'color',
        ],
        'bgcolor' => [
            'type' => 'color',
            'section' => 'general',
            'title' => get_string('bgcolor', 'local_mb2builder'),
            'action' => 'color',
            'selector' => '.icon-bg',
            'style_properity' => 'background-color',
        ],
        'mt' => [
            'type' => 'range',
            'section' => 'style',
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
            'section' => 'style',
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

define('LOCAL_MB2BUILDER_SETTINGS_ICON2', base64_encode(serialize($mb2settings)));
