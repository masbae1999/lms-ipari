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
    'id' => 'image',
    'subid' => '',
    'title' => get_string('image', 'local_mb2builder'),
    'icon' => 'fa fa-picture-o',
    'tabs' => [
        'general' => get_string('generaltab', 'local_mb2builder'),
        'style' => get_string('styletab', 'local_mb2builder'),
    ],
    'attr' => [
        'text' => [
            'type' => 'image',
            'section' => 'general',
            'title' => get_string('image', 'local_mb2builder'),
            'action' => 'image',
            'selector' => '.mb2-image-src',
        ],
        'alt' => [
            'type' => 'text',
            'section' => 'general',
            'title' => get_string('alttext', 'local_mb2builder'),
        ],
        'caption' => [
            'type' => 'yesno',
            'section' => 'general',
            'title' => get_string('caption', 'local_mb2builder'),
            'options' => [
                1 => get_string('yes', 'local_mb2builder'),
                0 => get_string('no', 'local_mb2builder'),
            ],
            'default' => 0,
            'action' => 'class',
            'class_remove' => 'caption0 caption1',
            'class_prefix' => 'caption',
        ],
        'captiontext' => [
            'type' => 'text',
            'section' => 'general',
            'showon' => 'caption:1',
            'title' => get_string('caption', 'local_mb2builder'),
            'action' => 'text',
            'selector' => '.caption',
            'default' => 'Caption text here',
        ],
        'width' => [
            'type' => 'range',
            'section' => 'general',
            'title' => get_string('widthlabel', 'local_mb2builder'),
            'min' => 20,
            'max' => 2000,
            'default' => 450,
            'action' => 'style',
            'changemode' => 'input',
            'style_properity' => 'width',
        ],
        'center' => [
            'type' => 'yesno',
            'section' => 'general',
            'title' => get_string('center', 'local_mb2builder'),
            'options' => [
                1 => get_string('yes', 'local_mb2builder'),
                0 => get_string('no', 'local_mb2builder'),
            ],
            'default' => 1,
            'action' => 'class',
            'class_remove' => 'center0 center1',
            'class_prefix' => 'center',
        ],
        'link' => [
            'type' => 'text',
            'section' => 'general',
            'title' => get_string('link', 'local_mb2builder'),
        ],
        'link_target' => [
            'type' => 'yesno',
            'section' => 'general',
            'title' => get_string('linknewwindow', 'local_mb2builder'),
            'options' => [
                1 => get_string('yes', 'local_mb2builder'),
                0 => get_string('no', 'local_mb2builder'),
            ],
            'default' => 0,
            'action' => 'none',
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

define('LOCAL_MB2BUILDER_SETTINGS_IMAGE', base64_encode(serialize($mb2settings)));
