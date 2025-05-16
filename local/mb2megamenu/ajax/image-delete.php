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
 * @package    local_mb2megamenu
 * @copyright  2018 - 2022 Mariusz Boloz (mb2moodle.com)
 * @license    Commercial https://themeforest.net/licenses
 */

define( 'AJAX_SCRIPT', true );

require_once( __DIR__ . '/../../../config.php' );
require_once( $CFG->libdir . '/adminlib.php' );

// Get builder files
require_once( __DIR__ . '/../lib.php' );
require_once( __DIR__ . '/../classes/api.php' );
require_once( __DIR__ . '/../classes/settings.php' );

// Get url params from ajax call
$urlparts = parse_url( $PAGE->url );
$urloptions = array();

if ( isset( $urlparts['query'] ) )
{
    parse_str( str_replace( '&amp;', '&', $urlparts['query'] ), $urloptions );
}

$context = context_system::instance();
$PAGE->set_url( '/local/mb2megamenu/ajax/image-delete.php' );
$PAGE->set_context( $context );

require_login();
require_sesskey();

$imgname = $urloptions['imgname'];

Mb2megamenuApi::delete_file( $imgname );
echo Mb2megamenuSettings::image_preview();
