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
    'id' => 'header',
    'subid' => '',
    'title' => get_string('header', 'local_mb2builder'),
    'icon' => 'fa fa-window-maximize',
    'tabs' => [
        'general' => get_string('generaltab', 'local_mb2builder'),
        'button' => get_string('button', 'local_mb2builder'),
        'style' => get_string('styletab', 'local_mb2builder'),
    ],
    'attr' => [
        'type' => [
            'type' => 'list',
            'section' => 'general',
            'title' => get_string('type', 'local_mb2builder'),
            'options' => [
                'dark' => get_string('dark', 'local_mb2builder'),
                'dark-striped' => get_string('dark_striped', 'local_mb2builder'),
                'light' => get_string('light', 'local_mb2builder'),
                'light-striped' => get_string('light_striped', 'local_mb2builder'),
            ],
            'default' => 'dark',
            'action' => 'class',
            'selector' => '.theme-header',
            'class_prefix' => 'type-',
            'class_remove' => 'type-dark type-dark-striped type-light type-light-striped',
        ],
        'title' => [
            'type' => 'text',
            'section' => 'general',
            'title' => get_string('title', 'local_mb2builder'),
            'action' => 'text',
            'selector' => '.theme-header-title',
        ],
        'issubtitle' => [
            'type' => 'yesno',
            'section' => 'general',
            'title' => get_string('subtext', 'local_mb2builder'),
            'options' => [
                1 => get_string('yes', 'local_mb2builder'),
                0 => get_string('no', 'local_mb2builder'),
            ],
            'default' => 1,
            'action' => 'class',
            'selector' => '.theme-header',
            'class_prefix' => 'issubtitle',
            'class_remove' => 'issubtitle0 issubtitle1',
        ],
        'subtitle' => [
            'type' => 'text',
            'section' => 'general',
            'showon' => 'issubtitle:1',
            'title' => get_string('subtext', 'local_mb2builder'),
            'action' => 'text',
            'selector' => '.theme-header-subtitle',
        ],
        'image' => [
            'type' => 'image',
            'section' => 'general',
            'title' => get_string('bgimagelabel', 'local_mb2builder'),
            'action' => 'image',
            'selector' => '.theme-header',
            'style_properity' => 'background-image',
        ],
        'linkbtn' => [
            'type' => 'yesno',
            'section' => 'button',
            'title' => get_string('readmorebtn', 'local_mb2builder'),
            'options' => [
                1 => get_string('yes', 'local_mb2builder'),
                0 => get_string('no', 'local_mb2builder'),
            ],
            'action' => 'class',
            'default' => 0,
            'selector' => '.theme-header',
            'class_remove' => 'linkbtn0 linkbtn1',
            'class_prefix' => 'linkbtn',
        ],
        'linktext' => [
            'type' => 'text',
            'section' => 'button',
            'showon' => 'linkbtn:1',
            'title' => get_string('btntext', 'local_mb2builder'),
            'action' => 'text',
            'selector' => '.btn-intext',
            'default' => get_string('readmorefp', 'local_mb2builder'),
        ],
        'link' => [
            'type' => 'text',
            'section' => 'button',
            'showon' => 'linkbtn:1',
            'title' => get_string('link', 'local_mb2builder'),
            'default' => '#',
        ],
        'link_target' => [
            'type' => 'yesno',
            'section' => 'button',
            'showon' => 'linkbtn:1',
            'title' => get_string('linknewwindow', 'local_mb2builder'),
            'options' => [
                1 => get_string('yes', 'local_mb2builder'),
                0 => get_string('no', 'local_mb2builder'),
            ],
            'action' => 'none',
            'default' => 0,
        ],
        'btntype' => [
            'type' => 'list',
            'section' => 'button',
            'showon' => 'linkbtn:1',
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
        'btnsize' => [
            'type' => 'list',
            'section' => 'button',
            'showon' => 'linkbtn:1',
            'title' => get_string('sizelabel', 'local_mb2builder', ''),
            'options' => [
                'normal' => get_string('default', 'local_mb2builder'),
                'xs' => get_string('xsmall', 'local_mb2builder'),
                'sm' => get_string('small', 'local_mb2builder'),
                'lg' => get_string('large', 'local_mb2builder'),
            ],
            'default' => 'normal',
            'action' => 'class',
            'selector' => '.mb2-pb-btn',
            'class_remove' => 'sizexs sizesm sizelg sizexlg',
            'class_prefix' => 'size',
        ],
        'btnrounded' => [
            'type' => 'yesno',
            'section' => 'button',
            'showon' => 'linkbtn:1',
            'title' => get_string('rounded', 'local_mb2builder'),
            'options' => [
                1 => get_string('yes', 'local_mb2builder'),
                0 => get_string('no', 'local_mb2builder'),
            ],
            'default' => 0,
            'action' => 'class',
            'selector' => '.mb2-pb-btn',
            'class_remove' => 'rounded0 rounded1',
            'class_prefix' => 'rounded',
        ],
        'btnborder' => [
            'type' => 'yesno',
            'section' => 'button',
            'showon' => 'linkbtn:1',
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
        'color' => [
            'type' => 'color',
            'section' => 'style',
            'title' => get_string('color', 'local_mb2builder'),
            'action' => 'color',
            'selector' => '.theme-header-title',
            'selector2' => '.theme-header-subtitle',
            'style_properity' => 'color',
        ],
        'bgcolor' => [
            'type' => 'color',
            'section' => 'style',
            'title' => get_string('bgcolor', 'local_mb2builder'),
            'action' => 'color',
            'selector' => '.theme-header-bg',
            'style_properity' => 'background-color',
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
    ],
];

define('LOCAL_MB2BUILDER_SETTINGS_HEADER', base64_encode(serialize($mb2settings)));
