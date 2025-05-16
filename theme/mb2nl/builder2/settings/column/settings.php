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


$mb2settingscol = [
    'type' => 'general',
    'title' => '',
    'tabs' => [
        'general' => get_string('generaltab', 'local_mb2builder'),
        'style' => get_string('styletab', 'local_mb2builder'),
    ],
    'attr' => [
        'align' => [
            'type' => 'buttons',
            'section' => 'general',
            'title' => get_string('aligntext', 'local_mb2builder'),
            'options' => [
                'none' => get_string('none', 'local_mb2builder'),
                'left' => get_string('left', 'local_mb2builder'),
                'center' => get_string('center', 'local_mb2builder'),
                'right' => get_string('right', 'local_mb2builder'),
            ],
            'default' => 'none',
            'action' => 'class',
            'class_remove' => 'align-none align-left align-right align-center',
            'class_prefix' => 'align-',
        ],
        'alignc' => [
            'type' => 'buttons',
            'section' => 'general',
            'title' => get_string('aligncolumn', 'local_mb2builder'),
            'options' => [
                'none' => get_string('none', 'local_mb2builder'),
                'left' => get_string('left', 'local_mb2builder'),
                'center' => get_string('center', 'local_mb2builder'),
                'right' => get_string('right', 'local_mb2builder'),
            ],
            'default' => 'none',
            'action' => 'class',
            'class_remove' => 'aligncnone aligncleft aligncright alignccenter',
            'class_prefix' => 'alignc',
        ],

        'spacer_col1' => ['type' => 'spacer', 'section' => 'general'],

        'mobcenter' => [
            'type' => 'yesno',
            'section' => 'general',
            'title' => get_string('mobcenter', 'local_mb2builder'),
            'options' => [
                1 => get_string('yes', 'local_mb2builder'),
                0 => get_string('no', 'local_mb2builder'),
            ],
            'default' => 0,
            'action' => 'class',
            'class_remove' => 'mobcenter0 mobcenter1',
            'class_prefix' => 'mobcenter',
        ],
        'moborder' => [
            'type' => 'range',
            'section' => 'general',
            'title' => get_string('moborder', 'local_mb2builder'),
            'min' => 0,
            'max' => 4,
            'default' => 0,
            'action' => 'class',
            'changemode' => 'input',
            'class_remove' => 'moborder0 moborder1 moborder2 moborder3 moborder4',
            'class_prefix' => 'moborder',
        ],
        'spacer_col2' => ['type' => 'spacer', 'section' => 'general'],
        'height' => [
            'type' => 'range',
            'section' => 'general',
            'title' => get_string('height', 'local_mb2builder'),
            'min' => 0,
            'max' => 900,
            'default' => 0,
            'action' => 'style',
            'changemode' => 'input',
            'style_properity' => 'min-height',
        ],
        'width' => [
            'type' => 'range',
            'section' => 'general',
            'title' => get_string('widthlabel', 'local_mb2builder'),
            'min' => 50,
            'max' => 4000,
            'default' => 4000,
            'action' => 'style',
            'changemode' => 'input',
            'selector' => '.column-inner',
            'style_properity' => 'max-width',
        ],
        'pt' => [
            'type' => 'range',
            'section' => 'general',
            'title' => get_string('ptlabel', 'local_mb2builder'),
            'min' => 0,
            'max' => 300,
            'default' => 0,
            'action' => 'style',
            'changemode' => 'input',
            'selector' => '.column-inner',
            'style_properity' => 'padding-top',
        ],
        'pb' => [
            'type' => 'range',
            'section' => 'general',
            'title' => get_string('pblabel', 'local_mb2builder'),
            'min' => 0,
            'max' => 300,
            'default' => 30,
            'action' => 'style',
            'changemode' => 'input',
            'selector' => '.column-inner',
            'style_properity' => 'padding-bottom',
        ],
        'scheme' => [
            'type' => 'buttons',
            'section' => 'style',
            'title' => get_string('scheme', 'local_mb2builder'),
            'options' => [
                'light' => get_string('light', 'local_mb2builder'),
                'dark' => get_string('dark', 'local_mb2builder'),
            ],
            'default' => 'light',
            'action' => 'class',
            'class_remove' => 'light dark',
        ],
        'bgcolor' => [
            'type' => 'color',
            'section' => 'style',
            'title' => get_string('bgcolor', 'local_mb2builder'),
            'action' => 'color',
            'selector' => '.column-inner-bg',
            'style_properity' => 'background-color',
        ],
        'bgimage' => [
            'type' => 'image',
            'section' => 'style',
            'title' => get_string('bgimage', 'local_mb2builder'),
            'action' => 'image',
            'style_properity' => 'background-image',
        ],
        'custom_class' => [
            'type' => 'text',
            'section' => 'style',
            'title' => get_string('customclasslabel', 'local_mb2builder'),
            'desc' => get_string('customclassdesc', 'local_mb2builder'),
            'default' => '',
        ],
    ],
];

define('LOCAL_MB2BUILDER_SETTINGS_COL', base64_encode(serialize($mb2settingscol)));
