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
require_once( __DIR__ . '/../classes/footers_api.php' );

$context = context_system::instance();
$PAGE->set_url( '/local/mb2builder/ajax/save-footer.php' );
$PAGE->set_context( $context );

require_login();
require_sesskey();

$itemid = $_POST['itemid'];
$footerid = $_POST['footerid'];
$democontent = $_POST['democontent'];

// Set record data.
$data = Mb2builderFootersApi::set_record_data(['itemid' => $itemid, 'name' => '', 'footerid' => $footerid,
'democontent' => $democontent]);

// Update database demo content field.
Mb2builderFootersApi::update_record_data($data);
echo $democontent; // This is because ajax call error: Unexpected end of json input.
die();
