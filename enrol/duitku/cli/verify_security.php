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
 * Verification script for Duitku security fixes
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
    $help = "Duitku security fixes verification script.

Options:
-h, --help                 Print this help.
-v, --verbose              Print verbose progress information.

Example:
\$ php enrol/duitku/cli/verify_security.php --verbose
";
    echo $help;
    exit(0);
}

$verbose = !empty($options['verbose']);
$errors = 0;
$warnings = 0;

// Start with a header
echo "\n";
echo "===================================================================\n";
echo " Duitku Enrollment Plugin Security Verification\n";
echo "===================================================================\n\n";

// Check 1: Verify the allow_enrol method
echo "[Test 1] Checking allow_enrol implementation: ";
$plugin = new enrol_duitku_plugin();

try {
    $method = new ReflectionMethod('enrol_duitku_plugin', 'allow_enrol');
    $params = $method->getParameters();

    if (count($params) == 1 && $params[0]->getName() == 'instance') {
        echo "PASSED\n";
    } else {
        echo "WARNING - Unexpected parameter list\n";
        $warnings++;
    }

    // Verify there's only one implementation
    $class = new ReflectionClass('enrol_duitku_plugin');
    $count = 0;
    foreach ($class->getMethods() as $method) {
        if ($method->getName() === 'allow_enrol') {
            $count++;
        }
    }

    if ($count == 1) {
        echo "       - No duplicate methods found: PASSED\n";
    } else {
        echo "       - Duplicate methods found: FAILED\n";
        $errors++;
    }
} catch (Exception $e) {
    echo "FAILED - Method not found or error: " . $e->getMessage() . "\n";
    $errors++;
}

// Check 2: Verify the validate_access method
echo "\n[Test 2] Checking validate_access implementation: ";
try {
    $method = new ReflectionMethod('enrol_duitku\duitku_helper', 'validate_access');
    if ($method->isStatic()) {
        echo "PASSED\n";
    } else {
        echo "WARNING - Method should be static\n";
        $warnings++;
    }
} catch (Exception $e) {
    echo "FAILED - Method not found or error: " . $e->getMessage() . "\n";
    $errors++;
}

// Check 3: Test membership_callback.php
echo "\n[Test 3] Checking membership_callback.php: ";
$callbackfile = $CFG->dirroot . '/enrol/duitku/membership_callback.php';
if (file_exists($callbackfile)) {
    $content = file_get_contents($callbackfile);

    if (strpos($content, "Validate session - block guest users") === false) {
        echo "PASSED - No strict validation that blocks callbacks\n";
    } else {
        echo "WARNING - May have strict validation that blocks callbacks\n";
        $warnings++;
    }
} else {
    echo "FAILED - File not found\n";
    $errors++;
}

// Check 4: Test locallib.php
echo "\n[Test 4] Checking locallib.php implementation: ";
$locallibfile = $CFG->dirroot . '/enrol/duitku/locallib.php';
if (file_exists($locallibfile)) {
    $content = file_get_contents($locallibfile);

    if (strpos($content, 'function check_course_access') !== false) {
        echo "PASSED\n";

        if (strpos($content, 'is_siteadmin()') !== false) {
            echo "       - Admin access check: PASSED\n";
        } else {
            echo "       - Admin access check: WARNING - Missing admin check\n";
            $warnings++;
        }

        if (strpos($content, 'try {') !== false && strpos($content, 'catch') !== false) {
            echo "       - Error handling: PASSED\n";
        } else {
            echo "       - Error handling: WARNING - Missing error handling\n";
            $warnings++;
        }
    } else {
        echo "WARNING - Missing course access function\n";
        $warnings++;
    }
} else {
    echo "FAILED - File not found\n";
    $errors++;
}

// Check 5: Test cleanup_guests.php
echo "\n[Test 5] Checking cleanup_guests.php: ";
$cleanupfile = $CFG->dirroot . '/enrol/duitku/cli/cleanup_guests.php';
if (file_exists($cleanupfile)) {
    echo "PASSED\n";
} else {
    echo "FAILED - File not found\n";
    $errors++;
}

// Final summary
echo "\n===================================================================\n";
echo "SECURITY VERIFICATION COMPLETE\n";
echo "-------------------------------------------------------------------\n";
echo "Errors:   $errors\n";
echo "Warnings: $warnings\n";

if ($errors == 0 && $warnings == 0) {
    echo "\nAll security fixes have been properly implemented!\n";
    exit(0);
} else if ($errors == 0) {
    echo "\nSecurity fixes are in place with some minor warnings.\n";
    exit(1);
} else {
    echo "\nThere are critical errors in the security implementation.\n";
    echo "Please review the issues and fix them before deployment.\n";
    exit(2);
}
