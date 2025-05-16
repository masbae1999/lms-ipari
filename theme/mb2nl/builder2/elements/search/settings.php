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
    'id' => 'search',
    'subid' => '',
    'title' => get_string('search', 'local_mb2builder'),
    'icon' => 'fa fa-search',
    'tabs' => [
        'general' => get_string('generaltab', 'local_mb2builder'),
        'style' => get_string('styletab', 'local_mb2builder'),
    ],
    'attr' => [

        'global' => [
            'type' => 'yesno',
            'section' => 'general',
            'title' => get_string('globalsearch', 'local_mb2builder'),
            'options' => [
                1 => get_string('yes', 'local_mb2builder'),
                0 => get_string('no', 'local_mb2builder'),
            ],
            'default' => 0,
            'action' => 'none',
        ],
        'size' => [
            'type' => 'buttons',
            'section' => 'general',
            'title' => get_string('sizelabel', 'local_mb2builder', ''),
            'options' => [
                's' => get_string('small', 'local_mb2builder'),
                'n' => get_string('medium', 'local_mb2builder'),
                'l' => get_string('large', 'local_mb2builder'),
            ],
            'default' => 'n',
            'action' => 'class',
            'class_remove' => 'sizen sizel sizexl sizes',
            'class_prefix' => 'size',
        ],
        'placeholder' => [
            'type' => 'text',
            'section' => 'general',
            'title' => get_string('placeholder', 'local_mb2builder'),
            'default' => get_string('searchcourses'),
            'action' => 'attribute',
            'attribute' => 'placeholder',
            'selector' => 'input',
        ],
        'rounded' => [
            'type' => 'yesno',
            'section' => 'style',
            'title' => get_string('rounded', 'local_mb2builder'),
            'options' => [
                1 => get_string('yes', 'local_mb2builder'),
                0 => get_string('no', 'local_mb2builder'),
            ],
            'default' => 0,
            'action' => 'class',
            'class_remove' => 'rounded0 rounded1',
            'class_prefix' => 'rounded',
        ],
        'border' => [
            'type' => 'yesno',
            'section' => 'style',
            'title' => get_string('border', 'local_mb2builder'),
            'options' => [
                1 => get_string('yes', 'local_mb2builder'),
                0 => get_string('no', 'local_mb2builder'),
            ],
            'default' => 0,
            'action' => 'class',
            'class_remove' => 'border0 border1',
            'class_prefix' => 'border',
        ],
        'width' => [
            'type' => 'range',
            'section' => 'style',
            'title' => get_string('widthlabel', 'local_mb2builder'),
            'min' => 200,
            'max' => 1500,
            'default' => 750,
            'action' => 'style',
            'changemode' => 'input',
            'style_properity' => 'max-width',
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

define('LOCAL_MB2BUILDER_SETTINGS_SEARCH', base64_encode(serialize($mb2settings)));
