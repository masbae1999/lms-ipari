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
    'id' => 'blog',
    'subid' => '',
    'title' => get_string('blog', 'local_mb2builder'),
    'icon' => 'fa fa-newspaper-o',
    'tabs' => [
        'general' => get_string('generaltab', 'local_mb2builder'),
        'layouttab' => get_string('layouttab', 'local_mb2builder'),
        'carousel' => get_string('carouseltab', 'local_mb2builder'),
        'style' => get_string('styletab', 'local_mb2builder'),
    ],
    'attr' => [
        'extags' => [
            'type' => 'list',
            'section' => 'general',
            'title' => get_string('tags', 'local_mb2builder'),
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
            'title' => get_string('tagids', 'local_mb2builder'),
            'desc' => get_string('tagidsdesc', 'local_mb2builder'),
            'action' => 'ajax',
        ],
        'exposts' => [
            'type' => 'list',
            'section' => 'general',
            'title' => get_string('posts', 'local_mb2builder'),
            'options' => [
                0 => get_string('showall', 'local_mb2builder'),
                'exclude' => get_string('exclude', 'local_mb2builder'),
                'include' => get_string('include', 'local_mb2builder'),
            ],
            'default' => 0,
            'action' => 'ajax',
        ],
        'postids' => [
            'type' => 'text',
            'section' => 'general',
            'showon' => 'exposts:exclude|include',
            'title' => get_string('postids', 'local_mb2builder'),
            'desc' => get_string('postidsdesc', 'local_mb2builder'),
            'action' => 'ajax',
        ],
        'postexternal' => [
            'type' => 'yesno',
            'section' => 'general',
            'title' => get_string('postexternal', 'local_mb2builder'),
            'options' => [
                1 => get_string('yes', 'local_mb2builder'),
                0 => get_string('no', 'local_mb2builder'),
            ],
            'default' => 1,
            'action' => 'ajax',
        ],
        'limit' => [
            'type' => 'number',
            'section' => 'general',
            'min' => 1,
            'max' => 99,
            'title' => get_string('itemsperpage', 'local_mb2builder'),
            'default' => 4,
            'action' => 'ajax',
        ],
        'layout' => [
            'type' => 'list',
            'section' => 'layouttab',
            'title' => get_string('layouttab', 'local_mb2builder'),
            'options' => [
                1 => get_string('list', 'local_mb2builder'),
                2 => get_string('columns', 'local_mb2builder'),
                3 => get_string('carousel', 'local_mb2builder'),
            ],
            'default' => 2,
            'action' => 'ajax',
            'class_remove' => 'layout-1 layout-2',
            'class_prefix' => 'layout-',
            'advlayout' => 1,
        ],
        'columns' => [
            'type' => 'range',
            'section' => 'layouttab',
            'showon' => 'layout:2|3',
            'min' => 1,
            'max' => 5,
            'title' => get_string('columns', 'local_mb2builder'),
            'default' => 4,
            'changemode' => 'input',
            'action' => 'class',
            'class_remove' => 'theme-col-1 theme-col-2 theme-col-3 theme-col-4 theme-col-5',
            'class_prefix' => 'theme-col-',
            'selector' => '.mb2-pb-content-list',
        ],
        'gutter' => [
            'type' => 'buttons',
            'section' => 'layouttab',
            'showon' => 'layout:2|3',
            'title' => get_string('grdwidth', 'local_mb2builder'),
            'options' => [
                'normal' => get_string('normal', 'local_mb2builder'),
                'thin' => get_string('thin', 'local_mb2builder'),
                'none' => get_string('none', 'local_mb2builder'),
            ],
            'default' => 'normal',
            'action' => 'callback',
            'callback' => 'carousel',
            'class_remove' => 'gutter-normal gutter-thin gutter-none',
            'class_prefix' => 'gutter-',
            'selector' => '.mb2-pb-content-list',
            'selector2' => '.superpost',
        ],
        'superpost' => [
            'type' => 'yesno',
            'section' => 'layouttab',
            'title' => get_string('superpost', 'local_mb2builder'),
            'options' => [
                1 => get_string('yes', 'local_mb2builder'),
                0 => get_string('no', 'local_mb2builder'),
            ],
            'default' => 0,
            'action' => 'ajax',
        ],
        'desc' => [
            'type' => 'yesno',
            'section' => 'layouttab',
            'title' => get_string('content', 'local_mb2builder'),
            'options' => [
                1 => get_string('yes', 'local_mb2builder'),
                0 => get_string('no', 'local_mb2builder'),
            ],
            'action' => 'ajax',
            'default' => 1,
        ],
        'date' => [
            'type' => 'yesno',
            'section' => 'layouttab',
            'title' => get_string('date', 'local_mb2builder'),
            'options' => [
                1 => get_string('yes', 'local_mb2builder'),
                0 => get_string('no', 'local_mb2builder'),
            ],
            'default' => 0,
            'action' => 'ajax',
        ],
        'author' => [
            'type' => 'yesno',
            'section' => 'layouttab',
            'title' => get_string('author', 'local_mb2builder'),
            'options' => [
                1 => get_string('yes', 'local_mb2builder'),
                0 => get_string('no', 'local_mb2builder'),
            ],
            'default' => 0,
            'action' => 'ajax',
        ],
        'animtime' => [
            'type' => 'number',
            'section' => 'carousel',
            'min' => 300,
            'max' => 2000,
            'title' => get_string('sanimate', 'local_mb2builder'),
            'default' => 450,
            'action' => 'callback',
            'callback' => 'carousel',
        ],
        'pausetime' => [
            'type' => 'number',
            'section' => 'carousel',
            'min' => 1000,
            'max' => 20000,
            'title' => get_string('spausetime', 'local_mb2builder'),
            'default' => 5000,
            'action' => 'callback',
            'callback' => 'carousel',
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
            'action' => 'callback',
            'callback' => 'carousel',
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
            'default' => 1,
            'action' => 'callback',
            'callback' => 'carousel',
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
            'action' => 'callback',
            'callback' => 'carousel',
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
            'action' => 'callback',
            'callback' => 'carousel',
          ],
        'tfs' => [
            'type' => 'range',
            'section' => 'style',
            'title' => get_string('titlefs', 'local_mb2builder'),
            'min' => 1,
            'max' => 10,
            'step' => 0.01,
            'default' => 1.4,
            'action' => 'style',
            'changemode' => 'input',
            'style_properity' => '--mb2-pb-blog-tfs',
            'style_suffix' => 'rem',
            'numclass' => 1,
            'sizepref' => 'pbtsize',
        ],
        'tcolor' => [
            'type' => 'color',
            'section' => 'style',
            'title' => get_string('titlecolor', 'local_mb2builder'),
            'action' => 'color',
            'style_properity' => '--mb2-pb-blog-tcolor',
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

define('LOCAL_MB2BUILDER_SETTINGS_BLOG', base64_encode(serialize($mb2settings)));
