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

// Get builder files.
require_once( __DIR__ . '/../lib.php' );
require_once( __DIR__ . '/../classes/builder_content.php' );

// Get url params from ajax call.
$urlparts = parse_url( $PAGE->url );
$urloptions = [];

if (isset( $urlparts['query'] ) ) {
    parse_str( str_replace( '&amp;', '&', $urlparts['query'] ), $urloptions );
}

$context = context_system::instance();
$PAGE->set_url( '/local/mb2builder/ajax/videoweb.php' );
$PAGE->set_context( $context );

require_login();
require_sesskey();

// Get default settings.
$options = [
    'id' => 'videoweb',
    'width' => 800,
    'videourl' => 'https://youtu.be/wop3FMhoLGs',
    'video_text' => '',
    'ratio' => '16:9',
    'margin' => '',
    'mt' => 0,
    'mb' => 30,
    'custom_class' => '',
    'template' => '',
];

$options = mb2builderBuilderContent::get_options($options, $urloptions);
echo mb2builderBuilderContent::get_videoweb_url($options['videourl'], 'https://youtu.be/wop3FMhoLGs');
die();
