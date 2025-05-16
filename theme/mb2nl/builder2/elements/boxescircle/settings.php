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
    'id' => 'boxescircle',
    'subid' => 'boxescircle_item',
    'title' => get_string('boxescircle', 'local_mb2builder'),
    'icon' => 'fa fa-circle-o',
    'type' => 'general',
    'tabs' => [
        'general' => get_string('generaltab', 'local_mb2builder'),
        'typo' => get_string('typotab', 'local_mb2builder'),
        'colors' => get_string('colorstab', 'local_mb2builder'),
        'style' => get_string('styletab', 'local_mb2builder'),
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
        'size' => [
            'type' => 'range',
            'section' => 'general',
            'title' => get_string('sizelabel', 'local_mb2builder', ' (px)'),
            'min' => 100,
            'max' => 700,
            'default' => 200,
            'action' => 'style',
            'changemode' => 'input',
            'style_properity' => '--mb2-pb-bcrcle-s',
        ],
        'hspace' => [
            'type' => 'range',
            'section' => 'general',
            'title' => get_string('hspace', 'local_mb2builder'),
            'min' => 0,
            'max' => 10,
            'step' => 0.01,
            'default' => 1.4,
            'action' => 'style',
            'style_suffix' => 'rem',
            'changemode' => 'input',
            'style_properity' => '--mb2-pb-bcrcle-hspace',
        ],
        'vspace' => [
            'type' => 'range',
            'section' => 'general',
            'title' => get_string('vspace', 'local_mb2builder'),
            'min' => 0,
            'max' => 10,
            'step' => 0.01,
            'default' => 0.5,
            'action' => 'style',
            'style_suffix' => 'rem',
            'changemode' => 'input',
            'style_properity' => '--mb2-pb-bcrcle-vspace',
        ],
        'bors' => [
            'type' => 'range',
            'section' => 'general',
            'title' => get_string('borderw', 'local_mb2builder'),
            'min' => 0,
            'max' => 20,
            'default' => 3,
            'action' => 'style',
            'changemode' => 'input',
            'style_properity' => '--mb2-pb-bcrcle-bors',
        ],
        'desc' => [
            'type' => 'yesno',
            'section' => 'general',
            'title' => get_string('content', 'local_mb2builder'),
            'options' => [
                1 => get_string('yes', 'local_mb2builder'),
                0 => get_string('no', 'local_mb2builder'),
            ],
            'action' => 'class',
            'selector' => '.mb2-pb-element-inner',
            'class_remove' => 'desc0 desc1',
            'class_prefix' => 'desc',
            'default' => 1,
        ],
        'tfs' => [
            'type' => 'range',
            'section' => 'typo',
            'title' => get_string('titlefs', 'local_mb2builder'),
            'min' => 1,
            'max' => 10,
            'step' => 0.01,
            'default' => 1.4,
            'action' => 'style',
            'changemode' => 'input',
            'selector' => '.box-title',
            'style_properity' => 'font-size',
            'style_suffix' => 'rem',
            'numclass' => 1,
            'sizepref' => 'pbtsize',
        ],
        'tfw' => [
            'type' => 'buttons',
            'section' => 'typo',
            'title' => get_string('fweight', 'local_mb2builder'),
            'options' => [
                'global' => get_string('global', 'local_mb2builder'),
                'light' => get_string('fwlight', 'local_mb2builder'),
                'normal' => get_string('normal', 'local_mb2builder'),
                'medium' => get_string('wmedium', 'local_mb2builder'),
                'bold' => get_string('fwbold', 'local_mb2builder'),
            ],
            'default' => 'global',
            'action' => 'class',
            'selector' => '.box-title',
            'class_remove' => 'fwglobal fwlight fwnormal fwmedium fwbold',
            'class_prefix' => 'fw',
        ],
        'tlh' => [
            'type' => 'buttons',
            'section' => 'typo',
            'title' => get_string('lh', 'local_mb2builder'),
            'options' => [
                'global' => get_string('global', 'local_mb2builder'),
                'small' => get_string('wsmall', 'local_mb2builder'),
                'normal' => get_string('normal', 'local_mb2builder'),
            ],
            'default' => 'global',
            'action' => 'class',
            'selector' => '.box-title',
            'class_remove' => 'lhglobal lhsmall lhnormal',
            'class_prefix' => 'lh',
        ],
        'group_boxcircle_start1' => [
            'type' => 'group_start',
            'section' => 'colors',
            'title' => get_string('normal', 'local_mb2builder')], // Group start.
        'color' => [
            'type' => 'color',
            'section' => 'colors',
            'title' => get_string('color', 'local_mb2builder'),
            'action' => 'color',
            'changemode' => 'input',
            'style_properity' => '--mb2-pb-bcrcle-color',
        ],
        'bgcolor' => [
            'type' => 'color',
            'section' => 'colors',
            'title' => get_string('bgcolor', 'local_mb2builder'),
            'action' => 'color',
            'changemode' => 'input',
            'style_properity' => '--mb2-pb-bcrcle-bgcolor',
        ],
        'borcolor' => [
            'type' => 'color',
            'section' => 'colors',
            'title' => get_string('bordercolor', 'local_mb2builder'),
            'action' => 'color',
            'changemode' => 'input',
            'style_properity' => '--mb2-pb-bcrcle-borcolor',
        ],
        'group_boxcircle_end1' => ['type' => 'group_end', 'section' => 'colors'], // Group end.
        'group_boxcircle_start2' => [
            'type' => 'group_start',
            'section' => 'colors',
            'title' => get_string('hover_active', 'local_mb2builder')], // Group start.
        'hcolor' => [
            'type' => 'color',
            'section' => 'colors',
            'title' => get_string('color', 'local_mb2builder'),
            'action' => 'color',
            'changemode' => 'input',
            'style_properity' => '--mb2-pb-bcrcle-hcolor',
        ],
        'hbgcolor' => [
            'type' => 'color',
            'section' => 'colors',
            'title' => get_string('bgcolor', 'local_mb2builder'),
            'action' => 'color',
            'changemode' => 'input',
            'style_properity' => '--mb2-pb-bcrcle-hbgcolor',
        ],
        'hborcolor' => [
            'type' => 'color',
            'section' => 'colors',
            'title' => get_string('bordercolor', 'local_mb2builder'),
            'action' => 'color',
            'changemode' => 'input',
            'style_properity' => '--mb2-pb-bcrcle-hborcolor',
        ],
        'group_boxcircle_end2' => ['type' => 'group_end', 'section' => 'colors'], // Group end.
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
            'default' => 10,
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
    'subelement' => [
        'tabs' => [
            'general' => get_string('generaltab', 'local_mb2builder'),
            'colors' => get_string('colorstab', 'local_mb2builder'),
        ],
        'attr' => [
            'image' => [
                'type' => 'image',
                'section' => 'general',
                'title' => get_string('image', 'local_mb2builder'),
                'action' => 'image',
                'selector' => '.theme-boxcircle',
                'style_properity' => 'background-image',
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
            'group_boxcircle_start1' => [
                'type' => 'group_start',
                'section' => 'colors',
                'title' => get_string('normal', 'local_mb2builder')], // Group start.
            'color' => [
                'type' => 'color',
                'section' => 'colors',
                'title' => get_string('color', 'local_mb2builder'),
                'action' => 'color',
                'changemode' => 'input',
                'selector' => '.theme-boxcircle',
                'style_properity' => '--mb2-pb-bcrcle-color',
            ],
            'bgcolor' => [
                'type' => 'color',
                'section' => 'colors',
                'title' => get_string('bgcolor', 'local_mb2builder'),
                'action' => 'color',
                'changemode' => 'input',
                'selector' => '.theme-boxcircle',
                'style_properity' => '--mb2-pb-bcrcle-bgcolor',
            ],
            'borcolor' => [
                'type' => 'color',
                'section' => 'colors',
                'title' => get_string('bordercolor', 'local_mb2builder'),
                'action' => 'color',
                'changemode' => 'input',
                'selector' => '.theme-boxcircle',
                'style_properity' => '--mb2-pb-bcrcle-borcolor',
            ],
            'group_boxcircle_end1' => ['type' => 'group_end', 'section' => 'colors'], // Group end.
            'group_boxcircle_start2' => [
                'type' => 'group_start',
                'section' => 'colors',
                'title' => get_string('hover_active', 'local_mb2builder')], // Group start.
            'hcolor' => [
                'type' => 'color',
                'section' => 'colors',
                'title' => get_string('color', 'local_mb2builder'),
                'action' => 'color',
                'changemode' => 'input',
                'selector' => '.theme-boxcircle',
                'style_properity' => '--mb2-pb-bcrcle-hcolor',
            ],
            'hbgcolor' => [
                'type' => 'color',
                'section' => 'colors',
                'title' => get_string('bgcolor', 'local_mb2builder'),
                'action' => 'color',
                'changemode' => 'input',
                'selector' => '.theme-boxcircle',
                'style_properity' => '--mb2-pb-bcrcle-hbgcolor',
            ],
            'hborcolor' => [
                'type' => 'color',
                'section' => 'colors',
                'title' => get_string('bordercolor', 'local_mb2builder'),
                'action' => 'color',
                'changemode' => 'input',
                'selector' => '.theme-boxcircle',
                'style_properity' => '--mb2-pb-bcrcle-hborcolor',
            ],
            'group_boxcircle_end2' => ['type' => 'group_end', 'section' => 'colors'], // Group end.
        ],
    ],
];

define('LOCAL_MB2BUILDER_SETTINGS_BOXESCIRCLE', base64_encode(serialize($mb2settings)));
