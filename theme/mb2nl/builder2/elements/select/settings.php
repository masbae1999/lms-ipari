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
    'id' => 'select',
    'subid' => 'select_item',
    'title' => get_string('select', 'local_mb2builder'),
    'icon' => 'fa fa-ellipsis-v',
    'type' => 'general',
    'tabs' => [
        'general' => get_string('generaltab', 'local_mb2builder'),
        'button' => get_string('button', 'local_mb2builder'),
        'style' => get_string('styletab', 'local_mb2builder'),
    ],
    'attr' => [
        'layout' => [
            'type' => 'buttons',
            'section' => 'general',
            'title' => get_string('layouttab', 'local_mb2builder'),
            'options' => [
                'h' => get_string('horizontal', 'local_mb2builder'),
                'v' => get_string('vertical', 'local_mb2builder'),
            ],
            'default' => 'normal',
            'action' => 'class',
            'class_remove' => 'layouth layoutv',
            'class_prefix' => 'layout',
        ],
        'center' => [
            'type' => 'yesno',
            'section' => 'general',
            'title' => get_string('center', 'local_mb2builder'),
            'options' => [
                1 => get_string('yes', 'local_mb2builder'),
                0 => get_string('no', 'local_mb2builder'),
            ],
            'default' => 0,
            'action' => 'class',
            'class_remove' => 'center0 center1',
            'class_prefix' => 'center',
        ],
        'swidth' => [
            'type' => 'range',
            'section' => 'general',
            'title' => get_string('widthlabel', 'local_mb2builder'),
            'min' => 80,
            'max' => 1000,
            'step' => 1,
            'default' => 300,
            'action' => 'style',
            'changemode' => 'input',
            'style_properity' => '--mb-pb-swidth',
        ],
        'selecth' => [
            'type' => 'range',
            'section' => 'general',
            'title' => get_string('height', 'local_mb2builder'),
            'min' => 24,
            'max' => 200,
            'step' => 1,
            'default' => 54,
            'action' => 'style',
            'changemode' => 'input',
            'style_properity' => '--mb-pb-selecth',
        ],
        'selectmh' => [
            'type' => 'range',
            'section' => 'general',
            'title' => get_string('mheight', 'local_mb2builder'),
            'min' => 20,
            'max' => 100,
            'step' => 0.1,
            'default' => 80,
            'action' => 'style',
            'changemode' => 'input',
            'style_properity' => '--mb-pb-selectmh',
            'style_suffix' => 'none',
        ],
        'fs' => [
            'type' => 'range',
            'section' => 'general',
            'title' => get_string('rowtextsize', 'local_mb2builder', ''),
            'min' => 1,
            'max' => 4,
            'step' => 0.01,
            'default' => 1,
            'action' => 'style',
            'changemode' => 'input',
            'style_properity' => '--mb-pb-selectfs',
            'style_suffix' => 'rem',
        ],
        'label' => [
            'type' => 'yesno',
            'section' => 'general',
            'title' => get_string('label', 'local_mb2builder'),
            'options' => [
                1 => get_string('yes', 'local_mb2builder'),
                0 => get_string('no', 'local_mb2builder'),
            ],
            'default' => 1,
            'action' => 'class',
            'class_remove' => 'label0 label1',
            'class_prefix' => 'label',
        ],
        'labeltext' => [
            'type' => 'text',
            'showon' => 'label:1',
            'section' => 'general',
            'title' => get_string('text', 'local_mb2builder'),
            'action' => 'text',
            'selector' => '.labeltext',
            'default' => 'Choose an option:',
        ],
        'image' => [
            'type' => 'yesno',
            'section' => 'general',
            'title' => get_string('image', 'local_mb2builder'),
            'options' => [
                1 => get_string('yes', 'local_mb2builder'),
                0 => get_string('no', 'local_mb2builder'),
            ],
            'default' => 1,
            'action' => 'class',
            'class_remove' => 'isimage0 isimage1',
            'class_prefix' => 'isimage',
        ],
        'target' => [
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
        'btntext' => [
            'type' => 'text',
            'section' => 'button',
            'title' => get_string('text', 'local_mb2builder'),
            'action' => 'text',
            'selector' => '.mb2-pb-btn',
            'default' => 'Submit',
        ],
        'btntype' => [
            'type' => 'list',
            'section' => 'button',
            'title' => get_string('type', 'local_mb2builder'),
            'options' => [
                'default' => get_string('default', 'local_mb2builder'),
                'primary' => get_string('primary', 'local_mb2builder'),
                'secondary' => get_string('secondary', 'local_mb2builder'),
                'success' => get_string('success', 'local_mb2builder'),
                'warning' => get_string('warning', 'local_mb2builder'),
                'info' => get_string('info', 'local_mb2builder'),
                'danger' => get_string('danger', 'local_mb2builder'),
                'inverse' => get_string('inverse', 'local_mb2builder'),
            ],
            'default' => 'primary',
            'action' => 'class',
            'selector' => '.mb2-pb-btn',
            'class_remove' => 'typeprimary typesecondary typesuccess typewarning typeinfo typedanger typeinverse',
            'class_prefix' => 'type',
        ],
        'btnrounded' => [
            'type' => 'buttons',
            'section' => 'button',
            'title' => get_string('rounded', 'local_mb2builder'),
            'options' => [
                0 => get_string('global', 'local_mb2builder'),
                1 => get_string('yes', 'local_mb2builder'),
                -1 => get_string('no', 'local_mb2builder'),
            ],
            'default' => 0,
            'action' => 'class',
            'selector' => '.mb2-pb-btn,.mb2-pb-select-btn',
            'class_remove' => 'rounded0 rounded1 rounded-1',
            'class_prefix' => 'rounded',
        ],
        'btnborder' => [
            'type' => 'yesno',
            'section' => 'button',
            'title' => get_string('border', 'local_mb2builder'),
            'options' => [
                1 => get_string('yes', 'local_mb2builder'),
                0 => get_string('no', 'local_mb2builder'),
            ],
            'default' => 0,
            'action' => 'class',
            'selector' => '.mb2-pb-btn',
            'class_remove' => 'btnborder0 btnborder1',
            'class_prefix' => 'btnborder',
        ],
        'btnfwcls' => [
            'type' => 'buttons',
            'section' => 'button',
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
            'selector' => '.mb2-pb-btn',
            'class_remove' => 'fwglobal fwlight fwnormal fwmedium fwbold',
            'class_prefix' => 'fw',
        ],

        'group_btn_start_1' => [
            'type' => 'group_start', 'section' => 'button', 'title' => get_string('normal', 'local_mb2builder')], // Group start.
            'btncolor' => [
                'type' => 'color',
                'section' => 'button',
                'title' => get_string('color', 'local_mb2builder'),
                'action' => 'color',
                'selector' => '.mb2-pb-btn',
                'cssvariable' => '--mb2-pb-btn-color',
            ],

            'btnbgcolor' => [
                'type' => 'color',
                'section' => 'button',
                'title' => get_string('bgcolor', 'local_mb2builder'),
                'action' => 'color',
                'selector' => '.mb2-pb-btn',
                'cssvariable' => '--mb2-pb-btn-bgcolor',
            ],

            'btnborcolor' => [
                'type' => 'color',
                'section' => 'button',
                'title' => get_string('bordercolor', 'local_mb2builder'),
                'action' => 'color',
                'selector' => '.mb2-pb-btn',
                'cssvariable' => '--mb2-pb-btn-borcolor',
            ],
            'group_btn_end_1' => ['type' => 'group_end', 'section' => 'button'], // Group end.

            'group_btn_start_2' => [
            'type' => 'group_start', 'section' => 'button',
            'title' => get_string('hover_active', 'local_mb2builder')], // Group start.
            'btnhcolor' => [
                'type' => 'color',
                'section' => 'button',
                'title' => get_string('color', 'local_mb2builder'),
                'action' => 'color',
                'selector' => '.mb2-pb-btn',
                'cssvariable' => '--mb2-pb-btn-hcolor',
            ],
            'btnbghcolor' => [
                'type' => 'color',
                'section' => 'button',
                'title' => get_string('bgcolor', 'local_mb2builder'),
                'action' => 'color',
                'selector' => '.mb2-pb-btn',
                'cssvariable' => '--mb2-pb-btn-bghcolor',
            ],
            'btnborhcolor' => [
                'type' => 'color',
                'section' => 'button',
                'title' => get_string('bordercolor', 'local_mb2builder'),
                'action' => 'color',
                'selector' => '.mb2-pb-btn',
                'cssvariable' => '--mb2-pb-btn-borhcolor',
            ],
            'group_btn_end_2' => ['type' => 'group_end', 'section' => 'button'], // Group end.

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
            'default' => '',
        ],
    ],
    'subelement' => [
        'tabs' => [
            'general' => get_string('generaltab', 'local_mb2builder'),
        ],
        'attr' => [
            'itemtext' => [
                'type' => 'textarea',
                'section' => 'general',
                'title' => get_string('text', 'local_mb2builder'),
                'action' => 'text',
                'default' => 'Select content',
                'selector' => '.select-text',
            ],
            'image' => [
                'type' => 'image',
                'section' => 'general',
                'title' => get_string('image', 'local_mb2builder'),
                'action' => 'image',
                'selector' => '.select-image',
            ],
            'link' => [
                'type' => 'text',
                'section' => 'general',
                'title' => get_string('link', 'local_mb2builder'),
            ],
        ],
    ],
];

define('LOCAL_MB2BUILDER_SETTINGS_SELECT', base64_encode(serialize($mb2settings)));
