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
    'id' => 'coursetabs',
    'subid' => '',
    'title' => get_string('coursetabs', 'local_mb2builder'),
    'icon' => 'fa fa-book',
    'tabs' => [
        'general' => get_string('generaltab', 'local_mb2builder'),
        'layouttab' => get_string('layouttab', 'local_mb2builder'),
        'carousel' => get_string('carouseltab', 'local_mb2builder'),
        'style' => get_string('styletab', 'local_mb2builder'),
    ],
    'attr' => [
        'filtertype' => [
            'type' => 'list',
            'section' => 'general',
            'title' => get_string('filtertype', 'local_mb2builder'),
            'options' => [
                'category' => get_string('category'),
                'tag' => get_string('tags'),
            ],
            'default' => 'category',
            'action' => 'ajax',
        ],
        'excats' => [
            'type' => 'list',
            'section' => 'general',
            'title' => get_string('categories', 'local_mb2builder'),
            'options' => [
                0 => get_string('showall', 'local_mb2builder'),
                'exclude' => get_string('exclude', 'local_mb2builder'),
                'include' => get_string('include', 'local_mb2builder'),
            ],
            'default' => 0,
            'action' => 'ajax',
        ],
        'catids' => [
            'type' => 'text',
            'section' => 'general',
            'showon' => 'excats:exclude|include',
            'title' => get_string('catidslabel', 'local_mb2builder'),
            'desc' => get_string('catidsdesc', 'local_mb2builder'),
            'action' => 'ajax',
        ],
        'extags' => [
            'type' => 'list',
            'section' => 'general',
            'title' => get_string('tags'),
            'options' => [
                0 => get_string('showall', 'local_mb2builder'),
                'exclude' => get_string('exclude', 'local_mb2builder'),
                'include' => get_string('include', 'local_mb2builder'),
            ],
            'default' => 0,
            'action' => 'ajax',
        ],
        'tagids' => [
            'type' => 'text',
            'section' => 'general',
            'showon' => 'extags:exclude|include',
            'title' => get_string('tagidslabel', 'local_mb2builder'),
            'desc' => get_string('tagidsdesc', 'local_mb2builder'),
            'action' => 'ajax',
        ],
        'coursecount' => [
            'type' => 'yesno',
            'section' => 'general',
            'title' => get_string('coursecount', 'local_mb2builder'),
            'options' => [
                1 => get_string('yes', 'local_mb2builder'),
                0 => get_string('no', 'local_mb2builder'),
            ],
            'default' => 0,
            'action' => 'class',
            'class_remove' => 'coursecount0 coursecount1',
            'class_prefix' => 'coursecount',
        ],
        'catdesc' => [
            'type' => 'yesno',
            'section' => 'general',
            'showon' => 'filtertype:category',
            'title' => get_string('catdesc', 'local_mb2builder'),
            'options' => [
                1 => get_string('yes', 'local_mb2builder'),
                0 => get_string('no', 'local_mb2builder'),
            ],
            'default' => 0,
            'action' => 'ajax',
        ],
        'limit' => [
            'type' => 'number',
            'section' => 'general',
            'min' => 1,
            'max' => 99,
            'title' => get_string('coursesperpage', 'local_mb2builder'),
            'default' => 12,
            'action' => 'ajax',
        ],
        'carousel' => [
            'type' => 'yesno',
            'section' => 'layouttab',
            'title' => get_string('carousel', 'local_mb2builder'),
            'options' => [
                1 => get_string('yes', 'local_mb2builder'),
                0 => get_string('no', 'local_mb2builder'),
            ],
            'default' => 0,
            'action' => 'ajax',
        ],
        'columns' => [
            'type' => 'range',
            'section' => 'layouttab',
            'min' => 1,
            'max' => 5,
            'title' => get_string('columns', 'local_mb2builder'),
            'default' => 4,
            'changemode' => 'input',
            'action' => 'ajax',
        ],
        'gutter' => [
            'type' => 'buttons',
            'section' => 'layouttab',
            'title' => get_string('grdwidth', 'local_mb2builder'),
            'options' => [
                'normal' => get_string('normal', 'local_mb2builder'),
                'thin' => get_string('thin', 'local_mb2builder'),
                'none' => get_string('none', 'local_mb2builder'),
            ],
            'default' => 'normal',
            'action' => 'ajax',
        ],
        'tcenter' => [
            'type' => 'yesno',
            'section' => 'layouttab',
            'title' => get_string('tabscenter', 'local_mb2builder'),
            'options' => [
                1 => get_string('yes', 'local_mb2builder'),
                0 => get_string('no', 'local_mb2builder'),
            ],
            'default' => 0,
            'action' => 'class',
            'selector' => '.coursetabs-tablist',
            'class_remove' => 'tcenter0 tcenter1',
            'class_prefix' => 'tcenter',
        ],
        'animtime' => [
            'type' => 'number',
            'section' => 'carousel',
            'min' => 300,
            'max' => 2000,
            'title' => get_string('sanimate', 'local_mb2builder'),
            'default' => 450,
            'action' => 'ajax',
        ],
        'pausetime' => [
            'type' => 'number',
            'section' => 'carousel',
            'min' => 1000,
            'max' => 20000,
            'title' => get_string('spausetime', 'local_mb2builder'),
            'default' => 5000,
            'action' => 'ajax',
        ],
        'sloop' => [
            'type' => 'yesno',
            'section' => 'carousel',
            'title' => get_string('loop', 'local_mb2builder'),
            'options' => [
                1 => get_string('yes', 'local_mb2builder'),
                0 => get_string('no', 'local_mb2builder'),
            ],
            'default' => 0,
            'action' => 'ajax',
        ],
        'autoplay' => [
            'type' => 'yesno',
            'showon' => 'sloop:1',
            'section' => 'carousel',
            'title' => get_string('autoplay', 'local_mb2builder'),
            'options' => [
                1 => get_string('yes', 'local_mb2builder'),
                0 => get_string('no', 'local_mb2builder'),
            ],
            'default' => 0,
            'action' => 'ajax',
        ],
        'sdots' => [
            'type' => 'yesno',
            'section' => 'carousel',
            'title' => get_string('pagernav', 'local_mb2builder'),
            'options' => [
                1 => get_string('yes', 'local_mb2builder'),
                0 => get_string('no', 'local_mb2builder'),
            ],
            'default' => 0,
            'action' => 'ajax',
        ],
        'snav' => [
            'type' => 'yesno',
            'section' => 'carousel',
            'title' => get_string('dirnav', 'local_mb2builder'),
            'options' => [
                1 => get_string('yes', 'local_mb2builder'),
                0 => get_string('no', 'local_mb2builder'),
            ],
            'default' => 1,
            'action' => 'ajax',
        ],
        'tabstyle' => [
            'type' => 'list',
            'section' => 'style',
            'title' => get_string('prestyle', 'local_mb2builder'),
            'options' => [
                1 => get_string('stylen', 'local_mb2builder', ['style' => 1]),
                2 => get_string('stylen', 'local_mb2builder', ['style' => 2]),
            ],
            'default' => 1,
            'action' => 'class',
            'selector' => '.coursetabs-tablist',
            'class_remove' => 'tabstyle1 tabstyle2',
            'class_prefix' => 'tabstyle',
        ],
        'acccolor' => [
            'type' => 'color',
            'section' => 'style',
            'title' => get_string('accentcolor', 'local_mb2builder'),
            'action' => 'color',
            'changemode' => 'input',
            'selector' => '.coursetabs-tablist',
            'style_properity' => '--mb2-pb-coursetabsacc',
        ],
        'tcolor' => [
            'type' => 'color',
            'section' => 'style',
            'title' => get_string('textcolor', 'local_mb2builder'),
            'action' => 'color',
            'changemode' => 'input',
            'selector' => '.coursetabs-tablist',
            'style_properity' => '--mb2-pb-coursetabsc',
        ],

        'cistyle' => [
            'type' => 'buttons',
            'section' => 'style',
            'title' => get_string('cstyle', 'local_mb2builder'),
            'options' => [
                'n' => get_string('default', 'theme_mb2nl'),
                'la' => get_string('cistylela', 'theme_mb2nl'),
                'd' => get_string('dark', 'theme_mb2nl'),
            ],
            'default' => 'n',
            'action' => 'ajax',
        ],
        'crounded' => [
            'type' => 'yesno',
            'section' => 'style',
            'title' => get_string('crounded', 'theme_mb2nl'),
            'options' => [
                1 => get_string('yes', 'local_mb2builder'),
                0 => get_string('no', 'local_mb2builder'),
            ],
            'default' => 1,
            'action' => 'ajax',
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

define('LOCAL_MB2BUILDER_SETTINGS_COURSETABS', base64_encode(serialize($mb2settings)));
