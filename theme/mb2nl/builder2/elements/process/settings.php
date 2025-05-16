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
    'id' => 'process',
    'subid' => 'process_item',
    'title' => get_string('process', 'local_mb2builder'),
    'icon' => 'fa fa-list-ol',
    'type' => 'general',
    'tabs' => [
        'general' => get_string('generaltab', 'local_mb2builder'),
        'tabtitle' => get_string('title', 'local_mb2builder'),
        'style' => get_string('styletab', 'local_mb2builder'),
    ],
    'attr' => [
        'type' => [
            'type' => 'list',
            'section' => 'general',
            'title' => get_string('type', 'local_mb2builder'),
            'options' => [
                1 => get_string('typen', 'local_mb2builder', ['type' => 1]),
            ],
            'default' => 1,
            'action' => 'class',
            'class_remove' => 'type-1',
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
        'labelpos' => [
            'type' => 'buttons',
            'section' => 'general',
            'showon' => 'columns:1',
            'title' => get_string('labelpos', 'local_mb2builder'),
            'options' => [
                'left' => get_string('left', 'local_mb2builder'),
                'right' => get_string('right', 'local_mb2builder'),
            ],
            'default' => 'left',
            'action' => 'class',
            'selector' => '.mb2-pb-element-inner',
            'class_remove' => 'labelposleft labelposright',
            'class_prefix' => 'labelpos',
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
        'isicon' => [
            'type' => 'yesno',
            'section' => 'general',
            'title' => get_string('icon', 'local_mb2builder'),
            'options' => [
                1 => get_string('yes', 'local_mb2builder'),
                0 => get_string('no', 'local_mb2builder'),
            ],
            'action' => 'class',
            'selector' => '.mb2-pb-element-inner',
            'class_remove' => 'isicon0 isicon1',
            'class_prefix' => 'isicon',
            'default' => 0,
        ],
        'icon' => [
            'type' => 'icon',
            'showon' => 'isicon:1',
            'section' => 'general',
            'title' => get_string('icon', 'local_mb2builder'),
            'action' => 'icon',
            'default' => 'fa fa-arrow-right',
            'selector' => '.boxprocess-label .boxprocess-icon i',
            'globalparent' => 1,
        ],
        'tfs' => [
            'type' => 'range',
            'section' => 'tabtitle',
            'title' => get_string('rowtextsize', 'local_mb2builder'),
            'min' => 1,
            'max' => 10,
            'step' => 0.01,
            'default' => 1.4,
            'action' => 'style',
            'changemode' => 'input',
            'selector' => '.boxprocess-title',
            'style_properity' => 'font-size',
            'style_suffix' => 'rem',
            'numclass' => 1,
            'sizepref' => 'pbtsize',
        ],
        'tfw' => [
            'type' => 'buttons',
            'section' => 'tabtitle',
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
            'selector' => '.boxprocess-title',
            'class_remove' => 'fwglobal fwlight fwnormal fwmedium fwbold',
            'class_prefix' => 'fw',
        ],
        'rounded' => [
            'type' => 'yesno',
            'section' => 'style',
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
        'height' => [
            'type' => 'range',
            'section' => 'style',
            'title' => get_string('height', 'local_mb2builder'),
            'min' => 0,
            'max' => 500,
            'default' => 0,
            'action' => 'style',
            'changemode' => 'input',
            'selector' => '.boxprocess-inner',
            'style_properity' => 'min-height',
        ],
        'boxmb' => [
            'type' => 'range',
            'section' => 'style',
            'title' => get_string('boxmb', 'local_mb2builder'),
            'min' => 0,
            'max' => 300,
            'default' => 0,
            'action' => 'style',
            'changemode' => 'input',
            'selector' => '.boxprocess-inner',
            'style_properity' => 'margin-bottom',
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
        ],
        'attr' => [
            'label' => [
                'type' => 'text',
                'section' => 'general',
                'title' => get_string('label', 'local_mb2builder'),
                'action' => 'text',
                'selector' => '.boxprocess-label .boxprocess-text',
                'default' => '1',
            ],
            'icon' => [
                'type' => 'icon',
                'section' => 'general',
                'title' => get_string('icon', 'local_mb2builder'),
                'action' => 'icon',
                'default' => 'fa fa-arrow-right',
                'selector' => '.boxprocess-label .boxprocess-icon i',
                'globalchild' => 1,
            ],
            'title' => [
                'type' => 'text',
                'section' => 'general',
                'title' => get_string('title', 'local_mb2builder'),
                'action' => 'text',
                'selector' => '.boxprocess-title',
                'default' => 'Box title here',
            ],
            'content' => [
                'type' => 'textarea',
                'section' => 'general',
                'title' => get_string('text', 'local_mb2builder'),
                'action' => 'text',
                'selector' => '.boxprocess-desc',
                'default' => 'Box content here.',
            ],
            'color' => [
                'type' => 'color',
                'section' => 'general',
                'title' => get_string('color', 'local_mb2builder'),
                'action' => 'color',
                'selector' => '.label-content',
                'style_properity' => 'color',
            ],
            'bgcolor' => [
                'type' => 'color',
                'section' => 'general',
                'title' => get_string('bgcolor', 'local_mb2builder'),
                'action' => 'color',
                'selector' => '.colorel1, .colorel2, .colorel3',
                'style_properity' => 'background-color',
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

define('LOCAL_MB2BUILDER_SETTINGS_PROCESS', base64_encode(serialize($mb2settings)));
