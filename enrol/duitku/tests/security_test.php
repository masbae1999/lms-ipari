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
 * Security test script for Duitku enrollment plugin
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
require_once($CFG->dirroot . '/enrol/duitku/lib.php');
require_once($CFG->dirroot . '/enrol/duitku/locallib.php');

// Now get CLI options.
list($options, $unrecognized) = cli_get_params(
    [
        'help' => false,
    ],
    [
        'h' => 'help',
    ]
);

if ($options['help']) {
    $help = "Duitku enrollment security testing script.

Options:
-h, --help                 Print this help.

Example:
\$ php enrol/duitku/tests/security_test.php
";
    echo $help;
    exit(0);
}

mtrace("Starting Duitku enrollment security tests...");
mtrace("");

// Test 1: Check for guest access blocking
mtrace("Test 1: Guest access blocking");
try {
    // Create mock instance and test
    $instance = new stdClass();
    $instance->id = 1;
    $instance->courseid = 2;
    $instance->enrol = 'duitku';
    $instance->status = ENROL_INSTANCE_ENABLED;

    // Set up as guest user
    $olduser = $USER;
    $USER = $DB->get_record('user', ['username' => 'guest']);

    // Try to enroll
    $plugin = new enrol_duitku_plugin();
    $result = $plugin->allow_enrol($instance);

    // This should not be reached due to exception
    mtrace("  [FAILED] Guest access not blocked");
} catch (Exception $e) {
    mtrace("  [PASSED] Guest access correctly blocked with message: " . $e->getMessage());
}

// Reset user
$USER = $olduser;

// Test 2: Check course access control
mtrace("Test 2: Course access control");
try {
    // Test as guest user
    $olduser = $USER;
    $USER = $DB->get_record('user', ['username' => 'guest']);

    // Try to access course
    $result = check_course_access(2);

    // Should not reach here
    mtrace("  [FAILED] Guest course access not blocked");
} catch (Exception $e) {
    mtrace("  [PASSED] Guest course access correctly blocked with message: " . $e->getMessage());
}

// Reset user
$USER = $olduser;

// Test 3: Ensure guest users are not in membership table
mtrace("Test 3: Guest users in membership table");
$guestuser = $DB->get_record('user', ['username' => 'guest'], 'id', MUST_EXIST);
$count = $DB->count_records('enrol_duitku_membership', ['userid' => $guestuser->id]);
if ($count > 0) {
    mtrace("  [FAILED] Found $count guest user records in membership table");
} else {
    mtrace("  [PASSED] No guest users found in membership table");
}

// Test 4: Ensure validate_access method is working
mtrace("Test 4: validate_access method");
try {
    // We can't easily mock the $PAGE object in CLI
    // So we'll just check if the method exists and has the right signature
    $reflection = new ReflectionMethod('\\enrol_duitku\\duitku_helper', 'validate_access');
    if ($reflection->isStatic()) {
        mtrace("  [PASSED] validate_access method exists and is static");
    } else {
        mtrace("  [WARNING] validate_access method exists but is not static");
    }
} catch (Exception $e) {
    mtrace("  [FAILED] validate_access method check failed: " . $e->getMessage());
}

mtrace("");
mtrace("Security tests completed!");
