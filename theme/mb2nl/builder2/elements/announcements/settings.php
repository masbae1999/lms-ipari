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
 * @copyright  2018 Mariusz Boloz, marbol2 <mariuszboloz@gmail.com>
 * @license   PHP and HTML: http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later. Other parts: http://themeforest.net/licenses
 */

defined('MOODLE_INTERNAL') || die();

$mb2settings = [
    'id' => 'announcements',
    'subid' => '',
    'title' => get_string('sitenews'),
    'icon' => 'fa fa-bullhorn',
    'tabs' => [
        'general' => get_string('generaltab', 'local_mb2builder'),
        'style' => get_string('styletab', 'local_mb2builder'),
    ],
    'attr' => [
        'limit' => [
            'type' => 'number',
            'section' => 'general',
            'min' => 1,
            'max' => 99,
            'title' => get_string('itemsperpage', 'local_mb2builder'),
            'default' => 8,
            'action' => 'ajax',
        ],
        'pinned' => [
            'type' => 'yesno',
            'section' => 'general',
            'title' => get_string('pinned', 'local_mb2builder'),
            'options' => [
                1 => get_string('yes', 'local_mb2builder'),
                0 => get_string('no', 'local_mb2builder'),
            ],
            'default' => 0,
            'action' => 'ajax',
        ],
        'animtime' => [
            'type' => 'number',
            'section' => 'general',
            'min' => 300,
            'max' => 2000,
            'title' => get_string('sanimate', 'local_mb2builder'),
            'default' => 350,
            'action' => 'callback',
            'callback' => 'carousel',
        ],
        'pausetime' => [
            'type' => 'number',
            'section' => 'general',
            'min' => 1000,
            'max' => 20000,
            'title' => get_string('spausetime', 'local_mb2builder'),
            'default' => 4000,
            'action' => 'callback',
            'callback' => 'carousel',
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
            'class_prefix' => 'icon',
            'class_remove' => 'icon0 icon1',
        ],
        'title' => [
            'type' => 'text',
            'section' => 'general',
            'title' => get_string('title', 'local_mb2builder'),
            'action' => 'text',
            'selector' => '.title-text',
            'default' => get_string('sitenews'),
        ],
        'twidth' => [
            'type' => 'range',
            'section' => 'general',
            'title' => get_string('twidth', 'local_mb2builder'),
            'min' => 40,
            'max' => 400,
            'default' => 180,
            'action' => 'style',
            'changemode' => 'input',
            'style_properity' => '--mb2-pb-ancts-twidth',
        ],
        'height' => [
            'type' => 'range',
            'section' => 'style',
            'title' => get_string('height', 'local_mb2builder'),
            'min' => 30,
            'max' => 80,
            'default' => 36,
            'action' => 'style',
            'changemode' => 'input',
            'style_properity' => '--mb2-pb-ancts-h',
        ],
        'bgcolor' => [
            'type' => 'color',
            'section' => 'style',
            'title' => get_string('bgcolor', 'local_mb2builder'),
            'action' => 'color',
            'cssvariable' => '--mb2-pb-ancts-bgcolor',
        ],
        'cbgcolor' => [
            'type' => 'color',
            'section' => 'style',
            'title' => get_string('bgcolor', 'local_mb2builder'),
            'action' => 'color',
            'cssvariable' => '--mb2-pb-ancts-cbgcolor',
        ],
        'ccolor' => [
            'type' => 'color',
            'section' => 'style',
            'title' => get_string('color', 'local_mb2builder'),
            'action' => 'color',
            'cssvariable' => '--mb2-pb-ancts-ccolor',
        ],
        'border' => [
            'type' => 'yesno',
            'section' => 'style',
            'title' => get_string('border', 'local_mb2builder'),
            'options' => [
                1 => get_string('yes', 'local_mb2builder'),
                0 => get_string('no', 'local_mb2builder'),
            ],
            'default' => 1,
            'action' => 'class',
            'class_remove' => 'border0, border1',
            'class_prefix' => 'border',
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
            'class_remove' => 'rounded0, rounded1',
            'class_prefix' => 'rounded',
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

define('LOCAL_MB2BUILDER_SETTINGS_ANNOUNCEMENTS', base64_encode(serialize($mb2settings)));
