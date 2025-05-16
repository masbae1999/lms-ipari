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


$mb2settingssection = [
    'type' => 'general',
    'title' => '',
    'tabs' => [
        'general' => get_string('generaltab', 'local_mb2builder'),
        'style' => get_string('styletab', 'local_mb2builder'),
        'bgels' => get_string('bgelements', 'local_mb2builder'),
    ],
    'attr' => [
        'sectionlang' => [
            'type' => 'text',
            'section' => 'general',
            'title' => get_string('language', 'core'),
            'desc' => get_string('languagedesc', 'local_mb2builder'),
            'action' => 'text',
            'selector' => '>.mb2-pb-actions .languages',
        ],
        'sectionaccess' => [
            'type' => 'list',
            'section' => 'general',
            'title' => get_string('elaccess', 'local_mb2builder'),
            'options' => [
                0 => get_string('elaccessall', 'local_mb2builder'),
                1 => get_string('elaccessusers', 'local_mb2builder'),
                2 => get_string('elaccesguests', 'local_mb2builder'),
            ],
            'default' => 0,
            'action' => 'class',
            'class_prefix' => 'access',
            'class_remove' => 'access0 access1 access2',
        ],
        'sectionhidden' => [
            'type' => 'list',
            'section' => 'general',
            'title' => get_string('hidden', 'local_mb2builder'),
            'options' => [
                1 => get_string('yes', 'local_mb2builder'),
                0 => get_string('no', 'local_mb2builder'),
            ],
            'default' => 0,
            'action' => 'class',
            'class_prefix' => 'hidden',
            'class_remove' => 'hidden0 hidden1',
        ],
        'pt' => [
            'type' => 'range',
            'section' => 'general',
            'title' => get_string('ptlabel', 'local_mb2builder'),
            'min' => 0,
            'max' => 200,
            'default' => 0,
            'action' => 'style',
            'changemode' => 'input',
            'selector' => '>.section-inner',
            'style_properity' => 'padding-top',
        ],
        'pb' => [
            'type' => 'range',
            'section' => 'general',
            'title' => get_string('pblabel', 'local_mb2builder'),
            'min' => 0,
            'max' => 200,
            'default' => 0,
            'action' => 'style',
            'changemode' => 'input',
            'selector' => '>.section-inner',
            'style_properity' => 'padding-bottom',
        ],
        'group_section_start_1' => ['type' => 'group_start', 'section' => 'bgels',
        'title' => get_string('image', 'local_mb2builder') . ' #1'], // Group start.
        'bgel1' => [
            'type' => 'image',
            'section' => 'bgels',
            'title' => get_string('bgimage', 'local_mb2builder'),
            'action' => 'image',
            'selector' => '.section-bgel.bgel1 img',
        ],

        'bgel1left' => [
            'type' => 'range',
            'section' => 'bgels',
            'title' => get_string('left', 'local_mb2builder'),
            'min' => -99,
            'max' => 99,
            'default' => 0,
            'action' => 'style',
            'changemode' => 'input',
            'selector' => '.section-bgel.bgel1',
            'style_properity' => 'left',
            'style_suffix' => '%',
        ],
        'bgel1s' => [
            'type' => 'range',
            'section' => 'bgels',
            'title' => get_string('widthlabel', 'local_mb2builder'),
            'min' => 10,
            'max' => 2000,
            'default' => 500,
            'action' => 'style',
            'changemode' => 'input',
            'selector' => '.section-bgel.bgel1',
            'style_properity' => 'width',
        ],
        'bgel1top' => [
            'type' => 'range',
            'section' => 'bgels',
            'title' => get_string('top', 'local_mb2builder'),
            'min' => 0,
            'max' => 3000,
            'default' => 200,
            'action' => 'style',
            'changemode' => 'input',
            'selector' => '.section-bgel.bgel1',
            'style_properity' => 'top',
        ],

        'group_section_end_1' => ['type' => 'group_end', 'section' => 'bgels'], // Group end.
        'group_section_start_2' => ['type' => 'group_start', 'section' => 'bgels',
        'title' => get_string('image', 'local_mb2builder') . ' #2'], // Group start.
        'bgel2' => [
            'type' => 'image',
            'section' => 'bgels',
            'title' => get_string('bgimage', 'local_mb2builder'),
            'action' => 'image',
            'selector' => '.section-bgel.bgel2 img',
        ],

        'bgel2left' => [
            'type' => 'range',
            'section' => 'bgels',
            'title' => get_string('left', 'local_mb2builder'),
            'min' => -99,
            'max' => 99,
            'default' => 0,
            'action' => 'style',
            'changemode' => 'input',
            'selector' => '.section-bgel.bgel2',
            'style_properity' => 'left',
            'style_suffix' => '%',
        ],
        'bgel2s' => [
            'type' => 'range',
            'section' => 'bgels',
            'title' => get_string('widthlabel', 'local_mb2builder'),
            'min' => 10,
            'max' => 2000,
            'default' => 500,
            'action' => 'style',
            'changemode' => 'input',
            'selector' => '.section-bgel.bgel2',
            'style_properity' => 'width',
        ],
        'bgel2top' => [
            'type' => 'range',
            'section' => 'bgels',
            'title' => get_string('top', 'local_mb2builder'),
            'min' => 0,
            'max' => 3000,
            'default' => 200,
            'action' => 'style',
            'changemode' => 'input',
            'selector' => '.section-bgel.bgel2',
            'style_properity' => 'top',
        ],
        'group_section_end_2' => ['type' => 'group_end', 'section' => 'bgels'], // Group end.

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
            'selector' => '>.section-inner',
            'style_properity' => 'background-color',
        ],
        'prbg' => [
            'type' => 'list',
            'section' => 'style',
            'title' => get_string('prestyle', 'local_mb2builder'),
            'options' => [
                0 => get_string('none', 'local_mb2builder'),
                'gradient20' => get_string('gradient20', 'local_mb2builder'),
                'gradient40' => get_string('gradient40', 'local_mb2builder'),
                'strip1' => get_string('strip1', 'local_mb2builder'),
                'strip2' => get_string('strip2', 'local_mb2builder'),
                'strip3' => get_string('strip3', 'local_mb2builder'),
                'strip4' => get_string('strip4', 'local_mb2builder'),
            ],
            'default' => 0,
            'action' => 'class',
            'class_prefix' => 'pre-bg',
            'class_remove' => 'pre-bggradient20 pre-bggradient40 pre-bgstrip1 pre-bgstrip2 pre-bgstrip3 pre-bgstrip4',
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

define('LOCAL_MB2BUILDER_SETTINGS_SECTION', base64_encode(serialize($mb2settingssection)));
