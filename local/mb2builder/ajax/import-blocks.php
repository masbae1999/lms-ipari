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
require_once( __DIR__ . '/../classes/api.php' );
require_once( __DIR__ . '/../classes/builder.php' );

require_login();
require_sesskey();

// Require theme lib files.
require_once( LOCAL_MB2BUILDER_PATH_THEME . '/lib.php' );

// Load shortcode filer if nuction doesn't exists.
if (!function_exists('mb2_add_shortcode')) {
    require($CFG->dirroot . '/filter/mb2shortcodes/lib/shortcodes.php');
}

// Include layout shortcodes.
mb2builderBuilder::get_static_layout_parts();

// Include elements shortcodes.
mb2builderBuilder::get_layout_elements();

// Include blocks settings.
mb2builderApi::include_import_blocks();

$context = context_system::instance();
$PAGE->set_url('/local/mb2builder/ajax/import-blocks.php');
$PAGE->set_context($context);

$part = optional_param('part', '', PARAM_RAW);
$partid = optional_param('partid', '', PARAM_RAW);
$fbuilder = optional_param('fbuilder', '', PARAM_INT);

$partdatascode = '';
$partdata = mb2builderApi::get_import_block_data($part, $partid);

if ($part === 'layouts') {
    $pagedata = json_decode($partdata);
    $partdatascode = mb2builderBuilder::get_builder_section($pagedata[0]->attr);
} else {
    // Some block contains more than one row (for example footers).
    // So we have to get rows in foreach.
    // This is change for the footer builder feature.
    $partdata = json_decode($partdata);

    foreach ($partdata as $data) {
        $partdatascode .= mb2builderBuilder::get_builder_row($data, $fbuilder);
    }
}

echo format_text($partdatascode, FORMAT_HTML);
die();
