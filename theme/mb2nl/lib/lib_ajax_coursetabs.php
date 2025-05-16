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
 *
 */

define('AJAX_SCRIPT', true);

// No login check is expected here bacause the course tabs element
// is visible for all site visitors.
// @codingStandardsIgnoreLine
require_once(__DIR__ . '/../../../config.php');
require_once($CFG->libdir . '/adminlib.php');

if ($CFG->forcelogin) {
    require_login();
}

require_sesskey();

if (!confirm_sesskey()) {
    die;
}

$themedir = '/theme';

if (isset($CFG->themedir)) {
    $themedir = $CFG->themedir;
    $themedir = str_replace($CFG->dirroot, '', $CFG->themedir);
}

// Require theme lib files.
require_once($CFG->dirroot . $themedir . '/mb2nl/lib.php');

// Get url params from ajax call.
$urlprts = parse_url($PAGE->url);
$urlopts = [];

if (isset($urlprts['query'])) {
     parse_str(str_replace('&amp;', '&', $urlprts['query']), $urlopts);
}

$context = context_system::instance();
$PAGE->set_url($themedir . '/mb2nl/lib/lib_ajax_coursetabs.php');
$PAGE->set_context($context);

$urlopts['tags'] = $urlopts['filtertype'] === 'category' ? [] : [$urlopts['categories']];
$urlopts['categories'] = $urlopts['filtertype'] === 'tag' ? [] : [$urlopts['categories']];
$urlopts['lazy'] = 0;

echo theme_mb2nl_coursetabs_tabcontent($urlopts);
die;
