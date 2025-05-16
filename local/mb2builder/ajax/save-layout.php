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

define( 'AJAX_SCRIPT', true );

require_once( __DIR__ . '/../../../config.php' );
require_once( $CFG->libdir . '/adminlib.php' );

// Get builder files.
require_once( __DIR__ . '/../lib.php' );
require_once( __DIR__ . '/../classes/layouts_api.php');

$context = context_system::instance();
$PAGE->set_url('/local/mb2builder/ajax/save-layout.php');
$PAGE->set_context($context);

require_login();
require_sesskey();

$itemid = $_POST['itemid'];
$content = $_POST['content'];
$name = $_POST['name'];
$layoutid = $_POST['layoutid'];

// Set record data.
$data = Mb2builderLayoutsApi::set_record_data(['name' => $name, 'content' => $content]);

if ($layoutid > 0) {
    // We need to update layout data.
    $itemtoupdate = Mb2builderLayoutsApi::get_record($layoutid);
    $itemtoupdate->name = $name ? $name : $itemtoupdate->name;
    $itemtoupdate->content = $content;
    $itemtoupdate->timemodified = $itemtoupdate->timecreated < time() ? time() : 0;
    Mb2builderLayoutsApi::update_record_data($itemtoupdate);
} else {
    // Now we have to add new layout record.
    Mb2builderLayoutsApi::add_record($data);
}

echo $content; // This is because ajax call error: Unexpected end of json input.
die();
