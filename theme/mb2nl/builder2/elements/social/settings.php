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
    'id' => 'social',
    'subid' => '',
    'title' => get_string('social', 'local_mb2builder'),
    'icon' => 'fa fa-users',
    'footer' => 1,
    'tabs' => [
        'general' => get_string('generaltab', 'local_mb2builder'),
        'style' => get_string('styletab', 'local_mb2builder'),
    ],
    'attr' => [
        'type' => [
            'type' => 'list',
            'section' => 'general',
            'title' => get_string('type', 'local_mb2builder'),
            'options' => [
                1 => get_string('typen', 'local_mb2builder', ['type' => 1]),
                2 => get_string('typen', 'local_mb2builder', ['type' => 2]),
            ],
            'default' => 1,
            'action' => 'class',
            'class_remove' => 'type1 type2',
            'class_prefix' => 'type',
        ],
        'size' => [
            'type' => 'buttons',
            'section' => 'general',
            'title' => get_string('sizelabel', 'local_mb2builder', ''),
            'options' => [
                'normal' => get_string('medium', 'local_mb2builder'),
                'l' => get_string('large', 'local_mb2builder'),
                'xl' => get_string('xlarge', 'local_mb2builder'),
                'xxl' => get_string('xxlarge', 'local_mb2builder'),
            ],
            'default' => 'normal',
            'action' => 'class',
            'class_remove' => 'sizenormal sizel sizexl sizexxl',
            'class_prefix' => 'size',
        ],
        'space' => [
            'type' => 'range',
            'section' => 'general',
            'title' => get_string('elpacing', 'local_mb2builder'),
            'min' => 0,
            'max' => 60,
            'default' => 6,
            'action' => 'style',
            'changemode' => 'input',
            'style_properity' => '--mb2-social-space',
        ],
        'rounded' => [
            'type' => 'buttons',
            'section' => 'general',
            'title' => get_string('rounded', 'local_mb2builder'),
            'options' => [
                0 => get_string('global', 'local_mb2builder'),
                1 => get_string('yes', 'local_mb2builder'),
                -1 => get_string('no', 'local_mb2builder'),
            ],
            'default' => 0,
            'action' => 'class',
            'class_remove' => 'rounded0 rounded1 rounded-1',
            'class_prefix' => 'rounded',
        ],

        'group_social_start_1' => [
        'type' => 'group_start', 'section' => 'style', 'title' => get_string('normal', 'local_mb2builder')], // Group start.

        'color' => [
            'type' => 'color',
            'section' => 'style',
            'title' => get_string('color', 'local_mb2builder'),
            'action' => 'color',
            'cssvariable' => '--mb2-social-color',
        ],
        'bgcolor' => [
            'type' => 'color',
            'section' => 'style',
            'title' => get_string('bgcolor', 'local_mb2builder'),
            'action' => 'color',
            'cssvariable' => '--mb2-social-bgcolor',
        ],
        'borcolor' => [
            'type' => 'color',
            'section' => 'style',
            'title' => get_string('bordercolor', 'local_mb2builder'),
            'action' => 'color',
            'cssvariable' => '--mb2-social-borcolor',
        ],

        'group_social_end_1' => ['type' => 'group_end', 'section' => 'style'], // Group end.

        'group_social_start_2' => [
        'type' => 'group_start', 'section' => 'style', 'title' => get_string('hover_active', 'local_mb2builder')], // Group start.

        'hcolor' => [
            'type' => 'color',
            'section' => 'style',
            'title' => get_string('color', 'local_mb2builder'),
            'action' => 'color',
            'cssvariable' => '--mb2-social-hcolor',
        ],
        'hbgcolor' => [
            'type' => 'color',
            'section' => 'style',
            'title' => get_string('bgcolor', 'local_mb2builder'),
            'action' => 'color',
            'cssvariable' => '--mb2-social-hbgcolor',
        ],
        'hborcolor' => [
            'type' => 'color',
            'section' => 'style',
            'title' => get_string('bordercolor', 'local_mb2builder'),
            'action' => 'color',
            'cssvariable' => '--mb2-social-hborcolor',
        ],

        'group_social_end_2' => ['type' => 'group_end', 'section' => 'style'], // Group end.

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

define('LOCAL_MB2BUILDER_SETTINGS_SOCIAL', base64_encode(serialize($mb2settings)));
