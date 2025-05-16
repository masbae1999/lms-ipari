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
    'id' => 'animnum',
    'subid' => 'animnum_item',
    'title' => get_string('animnum', 'local_mb2builder'),
    'icon' => 'fa fa-bar-chart',
    'type' => 'general',
    'tabs' => [
        'general' => get_string('generaltab', 'local_mb2builder'),
        'style' => get_string('styletab', 'local_mb2builder'),
    ],
    'attr' => [
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
        'icon' => [
            'type' => 'yesno',
            'section' => 'general',
            'title' => get_string('icon', 'local_mb2builder'),
            'options' => [
                1 => get_string('yes', 'local_mb2builder'),
                0 => get_string('no', 'local_mb2builder'),
            ],
            'default' => 0,
            'action' => 'class',
            'selector' => '.mb2-pb-element-inner',
            'class_prefix' => 'icon',
            'class_remove' => 'icon0 icon1',
        ],
        'size_icon' => [
            'type' => 'range',
            'min' => 1,
            'max' => 10,
            'step' => 0.01,
            'showon' => 'icon:1',
            'section' => 'general',
            'title' => get_string('iconsize', 'local_mb2builder'),
            'default' => 3,
            'action' => 'style',
            'changemode' => 'input',
            'style_properity' => '--mb2-b-anum-sizei',
            'style_suffix' => 'rem',
        ],
        'color_icon' => [
            'type' => 'color',
            'showon' => 'icon:1',
            'section' => 'general',
            'title' => get_string('iconcolor', 'local_mb2builder'),
            'action' => 'color',
            'selector' => '.pbanimnum-icon',
            'style_properity' => 'color',
            'globalparent' => 1,
        ],
        'subtitle' => [
            'type' => 'yesno',
            'section' => 'general',
            'title' => get_string('subtitle', 'local_mb2builder'),
            'options' => [
                1 => get_string('yes', 'local_mb2builder'),
                0 => get_string('no', 'local_mb2builder'),
            ],
            'default' => 0,
            'action' => 'class',
            'selector' => '.mb2-pb-element-inner',
            'class_prefix' => 'subtitle',
            'class_remove' => 'subtitle0 subtitle1',
        ],
        'group_animnum_start_3' => [
          'type' => 'group_start',
          'section' => 'style',
          'title' => get_string('box', 'local_mb2builder'),
        ], // Group start.

        'height' => [
            'type' => 'range',
            'section' => 'style',
            'title' => get_string('height', 'local_mb2builder'),
            'min' => 0,
            'max' => 500,
            'default' => 0,
            'changemode' => 'input',
            'action' => 'style',
            'selector' => '.pbanimnum-item',
            'style_properity' => 'min-height',
        ],
        'center' => [
            'type' => 'yesno',
            'section' => 'style',
            'title' => get_string('center', 'local_mb2builder'),
            'options' => [
                1 => get_string('yes', 'local_mb2builder'),
                0 => get_string('no', 'local_mb2builder'),
            ],
            'default' => 1,
            'action' => 'class',
            'selector' => '.mb2-pb-element-inner',
            'class_prefix' => 'center',
            'class_remove' => 'center0 center1',
        ],
        'nopadding' => [
            'type' => 'yesno',
            'section' => 'style',
            'title' => get_string('nopadding', 'local_mb2builder'),
            'options' => [
                1 => get_string('yes', 'local_mb2builder'),
                0 => get_string('no', 'local_mb2builder'),
            ],
            'default' => 0,
            'action' => 'class',
            'selector' => '.mb2-pb-element-inner',
            'class_prefix' => 'nopadding',
            'class_remove' => 'nopadding0 nopadding1',
        ],
        'color_bg' => [
            'type' => 'color',
            'section' => 'style',
            'title' => get_string('bgcolor', 'local_mb2builder'),
            'action' => 'color',
            'selector' => '.pbanimnum-item',
            'style_properity' => 'background-color',
            'globalparent' => 1,
        ],

        'group_animnum_end_3' => ['type' => 'group_end', 'section' => 'style'], // Group end.

        'group_animnum_start_1' => [
          'type' => 'group_start',
          'section' => 'style',
          'title' => get_string('number', 'local_mb2builder'),
        ], // Group start.
        'size_number' => [
            'type' => 'range',
            'min' => 1,
            'max' => 10,
            'step' => 0.1,
            'section' => 'style',
            'title' => get_string('numsize', 'local_mb2builder'),
            'default' => 3,
            'action' => 'style',
            'changemode' => 'input',
            'selector' => '.pbanimnum-number',
            'style_properity' => 'font-size',
            'style_suffix' => 'rem',
        ],
        'nfwcls' => [
            'type' => 'buttons',
            'section' => 'style',
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
            'selector' => '.pbanimnum-number',
            'class_remove' => 'fwglobal fwlight fwnormal fwmedium fwbold',
            'class_prefix' => 'fw',
        ],
        'nfstyle' => [
            'type' => 'buttons',
            'section' => 'style',
            'title' => get_string('fstyle', 'local_mb2builder'),
            'options' => [
                'global' => get_string('global', 'local_mb2builder'),
                'normal' => get_string('normal', 'local_mb2builder'),
                'italic' => get_string('fsitalic', 'local_mb2builder'),
            ],
            'default' => 'global',
            'action' => 'class',
            'selector' => '.pbanimnum-number',
            'class_remove' => 'fsglobal fsnormal fsitalic',
            'class_prefix' => 'fs',
        ],
        'color_number' => [
            'type' => 'color',
            'section' => 'style',
            'title' => get_string('numcolor', 'local_mb2builder'),
            'action' => 'color',
            'selector' => '.pbanimnum-number',
            'style_properity' => 'color',
            'globalparent' => 1,
        ],
        'nmt' => [
            'type' => 'range',
            'section' => 'style',
            'title' => get_string('mt', 'local_mb2builder'),
            'min' => -30,
            'max' => 300,
            'default' => 0,
            'action' => 'style',
            'changemode' => 'input',
            'selector' => '.pbanimnum-number',
            'style_properity' => 'margin-top',
        ],
        'nmb' => [
            'type' => 'range',
            'section' => 'style',
            'title' => get_string('mb', 'local_mb2builder'),
            'min' => -30,
            'max' => 300,
            'default' => 0,
            'action' => 'style',
            'changemode' => 'input',
            'selector' => '.pbanimnum-number',
            'style_properity' => 'margin-bottom',
        ],
        'group_animnum_end_1' => ['type' => 'group_end', 'section' => 'style'], // Group end.
        'group_animnum_start_2' => [
          'type' => 'group_start',
          'section' => 'style',
          'title' => get_string('title', 'local_mb2builder'),
        ], // Group start.
        'size_title' => [
            'type' => 'range',
            'min' => 1,
            'max' => 10,
            'step' => 0.01,
            'section' => 'style',
            'title' => get_string('titlesize', 'local_mb2builder'),
            'default' => 1.4,
            'action' => 'style',
            'changemode' => 'input',
            'selector' => '.pbanimnum-title',
            'style_properity' => 'font-size',
            'style_suffix' => 'rem',
        ],
        'tfwcls' => [
            'type' => 'buttons',
            'section' => 'style',
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
            'selector' => '.pbanimnum-title',
            'class_remove' => 'fwglobal fwlight fwnormal fwmedium fwbold',
            'class_prefix' => 'fw',
        ],
        'tlhcls' => [
            'type' => 'buttons',
            'section' => 'style',
            'title' => get_string('lh', 'local_mb2builder'),
            'options' => [
                'global' => get_string('global', 'local_mb2builder'),
                'small' => get_string('wsmall', 'local_mb2builder'),
                'medium' => get_string('wmedium', 'local_mb2builder'),
                'normal' => get_string('normal', 'local_mb2builder'),
            ],
            'default' => 'global',
            'action' => 'class',
            'selector' => '.pbanimnum-title',
            'class_remove' => 'lhglobal lhsmall lhmedium lhnormal',
            'class_prefix' => 'lh',
        ],
        'color_title' => [
            'type' => 'color',
            'section' => 'style',
            'title' => get_string('titlecolor', 'local_mb2builder'),
            'action' => 'color',
            'selector' => '.pbanimnum-title',
            'style_properity' => 'color',
            'globalparent' => 1,
        ],
        'group_animnum_end_2' => ['type' => 'group_end', 'section' => 'style'], // Group end.

        'group_animnum_start_0' => [
          'type' => 'group_start',
          'section' => 'style',
          'title' => get_string('subtitle', 'local_mb2builder'),
        ], // Group start.

        'color_subtitle' => [
            'type' => 'color',
            'section' => 'style',
            'title' => get_string('subtitlecolor', 'local_mb2builder'),
            'action' => 'color',
            'selector' => '.pbanimnum-subtitle',
            'style_properity' => 'color',
            'globalparent' => 1,
        ],

        'group_animnum_end_0' => ['type' => 'group_end', 'section' => 'style'], // Group end.

        'aspeed' => [
            'type' => 'number',
            'section' => 'general',
            'min' => 100,
            'max' => 1000000,
            'title' => get_string('aspeed', 'local_mb2builder'),
            'default' => 20000,
            'action' => 'data',
        ],
        'runbutton' => [
            'type' => 'html',
            'section' => 'general',
            'html' => '<a href="#" class="mb2-pb-btn sizesm typesuccess fw1 mb2-pb-animnum-run" style="margin-top:18px;">'.
            get_string('runanimation', 'local_mb2builder') . '</a>',
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
            'default' => 0,
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
            'style' => get_string('styletab', 'local_mb2builder'),
        ],
        'attr' => [
            'number' => [
                'type' => 'number',
                'section' => 'general',
                'title' => get_string('number', 'local_mb2builder'),
                'default' => 0,
                'action' => 'text',
                'selector' => '.pbanimnum-number',
            ],
            'title' => [
                'type' => 'text',
                'section' => 'general',
                'title' => get_string('title', 'local_mb2builder'),
                'action' => 'text',
                'selector' => '.pbanimnum-title',
            ],
            'subtitle' => [
                'type' => 'text',
                'section' => 'general',
                'title' => get_string('subtitle', 'local_mb2builder'),
                'action' => 'text',
                'selector' => '.pbanimnum-subtitle',
            ],
            'icon' => [
                'type' => 'icon',
                'section' => 'general',
                'title' => get_string('icon', 'local_mb2builder'),
                'default' => 'fa fa-graduation-cap',
                'action' => 'icon',
                'selector' => '.pbanimnum-icon i',
            ],
            'color_number' => [
                'type' => 'color',
                'section' => 'style',
                'title' => get_string('numcolor', 'local_mb2builder'),
                'action' => 'color',
                'selector' => '.pbanimnum-number',
                'style_properity' => 'color',
                'globalchild' => 1,
            ],
            'color_icon' => [
                'type' => 'color',
                'section' => 'style',
                'title' => get_string('iconcolor', 'local_mb2builder'),
                'action' => 'color',
                'selector' => '.pbanimnum-icon',
                'style_properity' => 'color',
                'globalchild' => 1,
            ],
            'color_title' => [
                'type' => 'color',
                'section' => 'style',
                'title' => get_string('titlecolor', 'local_mb2builder'),
                'action' => 'color',
                'selector' => '.pbanimnum-title',
                'style_properity' => 'color',
                'globalchild' => 1,
            ],
            'color_subtitle' => [
                'type' => 'color',
                'section' => 'style',
                'title' => get_string('subtitlecolor', 'local_mb2builder'),
                'action' => 'color',
                'selector' => '.pbanimnum-subtitle',
                'style_properity' => 'color',
                'globalchild' => 1,
            ],
            'color_bg' => [
                'type' => 'color',
                'section' => 'style',
                'title' => get_string('bgcolor', 'local_mb2builder'),
                'action' => 'color',
                'selector' => '.pbanimnum-item',
                'style_properity' => 'background-color',
                'globalchild' => 1,
            ],
        ],
    ],
];

define('LOCAL_MB2BUILDER_SETTINGS_ANIMNUM', base64_encode(serialize($mb2settings)));
