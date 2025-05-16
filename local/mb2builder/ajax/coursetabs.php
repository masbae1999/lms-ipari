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
 * @package    local_mb2builder
 * @copyright  2018 - 2024 Mariusz Boloz (lmsstyle.com)
 * @license    PHP and HTML: http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later. Other parts: http://themeforest.net/licenses
 */

define('AJAX_SCRIPT', true);

require_once( __DIR__ . '/../../../config.php' );
require_once( $CFG->libdir . '/adminlib.php' );
require_once( $CFG->libdir . '/filelib.php' );

// Get builder files.
require_once( __DIR__ . '/../lib.php' );
require_once( __DIR__ . '/../classes/builder_content.php' );

// Require theme lib files.
require_once( LOCAL_MB2BUILDER_PATH_THEME . '/lib.php' );

// Get url params from ajax call.
$urlparts = parse_url( $PAGE->url );
$urloptions = [];

if (isset( $urlparts['query'] ) ) {
    parse_str( str_replace( '&amp;', '&', $urlparts['query'] ), $urloptions );
}

$context = context_system::instance();
$PAGE->set_url( '/local/mb2builder/ajax/coursetabs.php' );
$PAGE->set_context( $context );

require_login();
require_sesskey();

// Get default settings.
$options = [
    'id' => 'coursetabs',
    'limit' => 12,
    'filtertype' => 'category',
    'catids' => '',
    'excats' => 0,
    'tagids' => '',
    'extags' => 0,
    'columns' => 4,
    'gutter' => 'normal',
    'custom_class' => '',
    'mt' => 0,
    'mb' => 30,

    'cistyle' => 'n',
    'crounded' => 1,

    'catdesc' => 0,
    'coursecount' => 0,

    'carousel' => 0,
    'sloop' => 0,
    'snav' => 1,
    'sdots' => 0,
    'autoplay' => 0,
    'pausetime' => 5000,
    'animtime' => 450,

    'tabstyle' => 1,
    'acccolor' => '',
    'tcolor' => '',
    'tcenter' => 0,

    'template' => '',
];

$options = mb2builderBuilderContent::get_options($options, $urloptions);
$options['lazy'] = 0;
$options['uniqid'] = uniqid('carousetabs_');

if (local_mb2builder_get_theme_name() === 'mb2nl' ) {
    echo theme_mb2nl_coursetabs_tabs($options);
    echo theme_mb2nl_coursetabs_courses($options, true);
}
die();
