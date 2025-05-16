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
    'id' => 'boxes3d',
    'subid' => 'boxes3d_item',
    'title' => get_string('elboxes3d', 'local_mb2builder'),
    'icon' => 'ri-swap-box-line',
    'type' => 'general',
    'tabs' => [
        'general' => get_string('generaltab', 'local_mb2builder'),
    ],
    'attr' => [
        'type' => [
            'type' => 'buttons',
            'section' => 'general',
            'title' => get_string('type', 'local_mb2builder'),
            'options' => [
                1 => get_string('typen', 'local_mb2builder', ['type' => 1]),
            ],
            'default' => 1,
            'action' => 'class',
            'selector' => '.mb2-pb-element-inner',
            'class_remove' => 'type-1 type-2 type-3',
            'class_prefix' => 'type-',
        ],
        'columns' => [
            'type' => 'range',
            'min' => 1,
            'max' => 5,
            'section' => 'general',
            'title' => get_string('columns', 'local_mb2builder'),
            'default' => 4,
            'action' => 'class',
            'changemode' => 'input',
            'selector' => '.mb2-pb-element-inner',
            'class_remove' => 'theme-col-1 theme-col-2 theme-col-3 theme-col-4 theme-col-5',
            'class_prefix' => 'theme-col-',
        ],
        'rounded' => [
            'type' => 'yesno',
            'section' => 'general',
            'title' => get_string('rounded', 'local_mb2builder'),
            'options' => [
                1 => get_string('yes', 'local_mb2builder'),
                0 => get_string('no', 'local_mb2builder'),
            ],
            'action' => 'class',
            'selector' => '.mb2-pb-element-inner',
            'class_remove' => 'rounded0 rounded1',
            'class_prefix' => 'rounded',
            'default' => 0,
        ],
        'gutter' => [
            'type' => 'buttons',
            'section' => 'general',
            'title' => get_string('grdwidth', 'local_mb2builder'),
            'options' => [
                'normal' => get_string('normal', 'local_mb2builder'),
                'thin' => get_string('thin', 'local_mb2builder'),
                'none' => get_string('none', 'local_mb2builder'),
            ],
            'default' => 'normal',
            'action' => 'class',
            'selector' => '.mb2-pb-element-inner',
            'class_remove' => 'gutter-thin gutter-normal gutter-none',
            'class_prefix' => 'gutter-',
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
      ],
      'subelement' => [
        'tabs' => [
            'general' => get_string('generaltab', 'local_mb2builder'),
        ],
        'attr' => [
            'image' => [
                'type' => 'image',
                'section' => 'general',
                'title' => get_string('image', 'local_mb2builder'),
                'action' => 'image',
                'selector' => '.theme-box3d-img',
            ],
            'title' => [
                'type' => 'text',
                'section' => 'general',
                'title' => get_string('title', 'local_mb2builder'),
                'action' => 'text',
                'selector' => '.box-title-text',
            ],
            'text' => [
                'type' => 'textarea',
                'section' => 'general',
                'title' => get_string('text', 'local_mb2builder'),
                'action' => 'text',
                'selector' => '.box-desc-text',
            ],
            'frontcolor' => [
                'type' => 'color',
                'section' => 'general',
                'title' => get_string('color', 'local_mb2builder'),
                'action' => 'color',
                'changemode' => 'input',
                'selector' => '.box-front .theme-box3d-color',
                'style_properity' => 'background-color',
            ],
            'backcolor' => [
                'type' => 'color',
                'section' => 'general',
                'title' => get_string('color', 'local_mb2builder'),
                'action' => 'color',
                'changemode' => 'input',
                'selector' => '.box-back .theme-box3d-color',
                'style_properity' => 'background-color',
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
                'action' => 'none',
                'default' => 0,
            ],
        ],
    ],
];

define('LOCAL_MB2BUILDER_SETTINGS_BOXES3D', base64_encode(serialize($mb2settings)));
