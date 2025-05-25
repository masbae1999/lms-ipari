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
 * Database cleanup script for Duitku enrollments
 * Removes guest users from the membership table
 *
 * @package   enrol_duitku
 * @copyright 2025 IPARI
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

define('CLI_SCRIPT', true);

// Use proper path resolution to find config.php
$dirroot = dirname(dirname(dirname(__DIR__)));
require($dirroot . '/config.php');
require_once($CFG->libdir . '/clilib.php');
require_once($CFG->libdir . '/adminlib.php');

// Now get CLI options.
list($options, $unrecognized) = cli_get_params(
    [
        'help' => false,
        'verbose' => false,
    ],
    [
        'h' => 'help',
        'v' => 'verbose',
    ]
);

if ($options['help']) {
    $help = "Duitku enrollment security cleanup script.

Options:
-h, --help                 Print this help.
-v, --verbose              Print verbose progress information.

Example:
\$ php enrol/duitku/cli/cleanup_guests.php --verbose
";
    echo $help;
    exit(0);
}

$verbose = !empty($options['verbose']);

if ($verbose) {
    mtrace("Starting Duitku enrollment security cleanup...");
    mtrace("");
}

// Find the guest user ID
$guestuser = $DB->get_record('user', ['username' => 'guest'], 'id', MUST_EXIST);
if ($verbose) {
    mtrace("Found guest user with ID: " . $guestuser->id);
}

// 1. Delete guest user from enrol_duitku_membership
$count = $DB->count_records('enrol_duitku_membership', ['userid' => $guestuser->id]);
if ($count > 0) {
    $DB->delete_records('enrol_duitku_membership', ['userid' => $guestuser->id]);
    mtrace("Removed $count guest user record(s) from enrol_duitku_membership table");
} else {
    mtrace("No guest user records found in enrol_duitku_membership table");
}

// 2. Delete any enrollment records for guest users in Duitku courses
$sql = "DELETE FROM {user_enrolments} 
        WHERE enrolid IN (
            SELECT id FROM {enrol} WHERE enrol = 'duitku'
        ) 
        AND userid = :guestuserid";
$params = ['guestuserid' => $guestuser->id];

$DB->execute($sql, $params);
if ($verbose) {
    mtrace("Removed any guest user enrollments from Duitku courses");
}

// 3. Find and remove any role assignments for guest users in Duitku courses
$sql = "DELETE FROM {role_assignments}
        WHERE userid = :guestuserid
        AND contextid IN (
            SELECT ctx.id 
            FROM {context} ctx
            JOIN {course} c ON c.id = ctx.instanceid AND ctx.contextlevel = :contextlevel
            JOIN {enrol} e ON e.courseid = c.id AND e.enrol = 'duitku'
        )";
$params = [
    'guestuserid' => $guestuser->id,
    'contextlevel' => CONTEXT_COURSE
];

$DB->execute($sql, $params);
if ($verbose) {
    mtrace("Removed any role assignments for guest users in Duitku courses");
}

mtrace("");
mtrace("Duitku enrollment security cleanup completed successfully!");
