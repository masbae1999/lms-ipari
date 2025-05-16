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
    'id' => 'videoweb',
    'subid' => '',
    'title' => get_string('videoweb', 'local_mb2builder'),
    'icon' => 'ri-film-line',
    'type' => 'general',
    'tabs' => [
        'general' => get_string('generaltab', 'local_mb2builder'),
        'videoimage' => get_string('image', 'local_mb2builder'),
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
            'default' => 0,
            'action' => 'style',
            'changemode' => 'input',
            'style_properity' => 'margin-bottom',
        ],
        'custom_class' => [
            'type' => 'text',
            'section' => 'general',
            'title' => get_string('customclasslabel', 'local_mb2builder'),
            'desc' => get_string('customclassdesc', 'local_mb2builder'),
        ],
        'bgimage' => [
            'type' => 'yesno',
            'section' => 'videoimage',
            'title' => get_string('image', 'local_mb2builder'),
            'options' => [
                1 => get_string('yes', 'local_mb2builder'),
                0 => get_string('no', 'local_mb2builder'),
            ],
            'action' => 'class',
            'class_remove' => 'isimage0 isimage1',
            'class_prefix' => 'isimage',
            'default' => 0,
        ],
        'bgimageurl' => [
            'type' => 'image',
            'section' => 'videoimage',
            'showon' => 'bgimage:1',
            'title' => get_string('image', 'local_mb2builder'),
            'action' => 'image',
            'style_properity' => 'background-image',
            'selector' => '.embed-video-bg',
        ],
        'bgcolor' => [
            'type' => 'color',
            'showon' => 'bgimage:1',
            'section' => 'videoimage',
            'title' => get_string('bgcolor', 'local_mb2builder'),
            'action' => 'color',
            'style_properity' => '--mb2-pb-videobg',
        ],
        'iconcolor' => [
            'type' => 'color',
            'showon' => 'bgimage:1',
            'section' => 'videoimage',
            'title' => get_string('color', 'local_mb2builder'),
            'action' => 'color',
            'style_properity' => '--mb2-pb-videoiconcolor',
        ],
    ],
];

define('LOCAL_MB2BUILDER_SETTINGS_VIDEOWEB', base64_encode(serialize($mb2settings)));
