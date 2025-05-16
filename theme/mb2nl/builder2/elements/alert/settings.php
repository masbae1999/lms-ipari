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
    'id' => 'alert',
    'subid' => '',
    'title' => get_string('alert', 'local_mb2builder'),
    'icon' => 'fa fa-exclamation-triangle',
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
                'success' => get_string('success', 'local_mb2builder'),
                'warning' => get_string('warning', 'local_mb2builder'),
                'info' => get_string('info', 'local_mb2builder'),
                'danger' => get_string('danger', 'local_mb2builder'),
            ],
            'default' => 'info',
            'action' => 'class',
            'class_prefix' => 'alert-',
            'class_remove' => 'alert-success alert-danger alert-info alert-warning',
        ],
        'close' => [
            'type' => 'yesno',
            'section' => 'general',
            'title' => get_string('closebtn', 'local_mb2builder'),
            'options' => [
                1 => get_string('yes', 'local_mb2builder'),
                0 => get_string('no', 'local_mb2builder'),
            ],
            'default' => 0,
            'action' => 'class',
            'class_prefix' => 'closebtn',
            'class_remove' => 'closebtn0 closebtn1',
        ],
        'text' => [
            'type' => 'editor',
            'section' => 'general',
            'title' => get_string('text', 'local_mb2builder'),
            'selector' => '>.alert-text',
            'default' => 'Alert text here.',
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

define('LOCAL_MB2BUILDER_SETTINGS_ALERT', base64_encode(serialize($mb2settings)));
