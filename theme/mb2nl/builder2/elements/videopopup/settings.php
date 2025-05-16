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
    'id' => 'videopopup',
    'subid' => '',
    'title' => get_string('videopopup', 'local_mb2builder'),
    'icon' => 'ri-movie-line',
    'type' => 'general',
    'tabs' => [
        'general' => get_string('generaltab', 'local_mb2builder'),
        'icon' => get_string('icon', 'local_mb2builder'),
        'text' => get_string('text', 'local_mb2builder'),
        'style' => get_string('styletab', 'local_mb2builder'),
    ],
    'attr' => [
        'videourl' => [
            'type' => 'text',
            'section' => 'general',
            'title' => get_string('videoidlabel', 'local_mb2builder'),
            'desc' => get_string('videoiddesc', 'local_mb2builder'),
            'default' => 'https://youtu.be/3ORsUGVNxGs',
            'action' => 'ajax',
            'changemode' => 'input',
        ],
        'localvideo' => [
            'type' => 'image',
            'section' => 'general',
            'title' => get_string('videofile', 'local_mb2builder'),
            'action' => 'none',
        ],
        'text' => [
            'type' => 'text',
            'section' => 'general',
            'title' => get_string('text', 'local_mb2builder'),
            'default' => 'Play video',
            'action' => 'text',
            'changemode' => 'input',
            'selector' => '.mb2pb-videopopup-text',
        ],
        'size' => [
            'type' => 'range',
            'section' => 'general',
            'title' => get_string('iconsize', 'local_mb2builder'),
            'min' => 1,
            'max' => 8,
            'step' => 0.01,
            'default' => 4,
            'action' => 'style',
            'changemode' => 'input',
            'style_properity' => '--mb2-vpopups',
            'style_suffix' => 'rem',
        ],
        'msize' => [
            'type' => 'range',
            'section' => 'general',
            'title' => get_string('msize', 'local_mb2builder'),
            'min' => 20,
            'max' => 100,
            'step' => 0.1,
            'default' => 67,
            'action' => 'style',
            'changemode' => 'input',
            'style_properity' => '--mb2-vpopupms',
            'style_suffix' => 'none',
        ],
        'rounded' => [
            'type' => 'buttons',
            'section' => 'icon',
            'title' => get_string('rounded', 'local_mb2builder'),
            'options' => [
                1 => get_string('yes', 'local_mb2builder'),
                0 => get_string('no', 'local_mb2builder'),
            ],
            'default' => 1,
            'action' => 'class',
            'selector' => '.mb2pb-videopopup-link',
            'class_remove' => 'rounded0 rounded1',
            'class_prefix' => 'rounded',
        ],
        'iconcolor' => [
            'type' => 'color',
            'section' => 'icon',
            'title' => get_string('color', 'local_mb2builder'),
            'action' => 'color',
            'cssvariable' => '--mb2-iconcolor',
        ],
        'iconbgcolor' => [
            'type' => 'color',
            'section' => 'icon',
            'title' => get_string('bgcolor', 'local_mb2builder'),
            'action' => 'color',
            'cssvariable' => '--mb2-iconbgcolor',
        ],
        'iconbocolor' => [
            'type' => 'color',
            'section' => 'icon',
            'title' => get_string('bordercolor', 'local_mb2builder'),
            'action' => 'color',
            'cssvariable' => '--mb2-iconbocolor',
        ],
        'fs' => [
            'type' => 'range',
            'section' => 'text',
            'title' => get_string('sizelabel', 'local_mb2builder', ''),
            'min' => 1,
            'max' => 5,
            'step' => 0.01,
            'default' => 1.3,
            'action' => 'style',
            'changemode' => 'input',
            'style_properity' => '--mb2-vpopupfs',
            'style_suffix' => 'rem',
        ],
        'fw' => [
            'type' => 'buttons',
            'section' => 'text',
            'title' => get_string('fweight', 'local_mb2builder'),
            'options' => [
                'global' => get_string('global', 'local_mb2builder'),
                'light' => get_string('fwlight', 'local_mb2builder'),
                'normal' => get_string('normal', 'local_mb2builder'),
                'medium' => get_string('wmedium', 'local_mb2builder'),
                'bold' => get_string('fwbold', 'local_mb2builder'),
            ],
            'default' => 'medium',
            'action' => 'class',
            'selector' => '.mb2pb-videopopup-link',
            'class_remove' => 'fwglobal fwlight fwnormal fwmedium fwbold',
            'class_prefix' => 'fw',
        ],
        'color' => [
            'type' => 'color',
            'section' => 'text',
            'title' => get_string('color', 'local_mb2builder'),
            'action' => 'color',
            'cssvariable' => '--mb2-color',
        ],
        'ml' => [
            'type' => 'range',
            'section' => 'style',
            'title' => get_string('ml', 'local_mb2builder'),
            'min' => 0,
            'max' => 300,
            'default' => 0,
            'action' => 'style',
            'changemode' => 'input',
            'style_properity' => 'margin-left',
        ],
        'mr' => [
            'type' => 'range',
            'section' => 'style',
            'title' => get_string('mr', 'local_mb2builder'),
            'min' => 0,
            'max' => 300,
            'default' => 0,
            'action' => 'style',
            'changemode' => 'input',
            'style_properity' => 'margin-right',
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
            'default' => 15,
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

define('LOCAL_MB2BUILDER_SETTINGS_VIDEOPOPUP', base64_encode(serialize($mb2settings)));
