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
 * Local library functions for Duitku enrollment plugin
 *
 * @package   enrol_duitku
 * @copyright 2025 IPARI
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

/**
 * Check course access for the current user
 * Prevents guest users from accessing course content
 *
 * @param int $courseid Course ID to check access for
 * @return boolean True if access is allowed, false otherwise
 */
function check_course_access($courseid)
{
    global $USER, $DB;

    if (isguestuser() || !isloggedin()) {
        // Block access to course content
        send_forbidden_response();
        return false;
    }

    // First check if this is an admin/manager who should always have access
    if (is_siteadmin() || has_capability('moodle/course:manageactivities', context_course::instance($courseid))) {
        return true;
    }

    // Check membership status - safely with error handling
    try {
        if (class_exists('\\enrol_duitku\\duitku_membership')) {
            $memberships = $DB->get_records('enrol_duitku_membership', ['userid' => $USER->id]);
            if (!empty($memberships)) {
                foreach ($memberships as $membership) {
                    // Simple active check (expiry time in future)
                    if ($membership->expiry_time > time()) {
                        return true;
                    }
                }
            }
        }
    } catch (Exception $e) {
        // Just log the error and continue with normal enrollment checks
        error_log('Error checking membership: ' . $e->getMessage());
    }

    // If no membership, check standard enrollment
    $context = context_course::instance($courseid);
    if (is_enrolled($context)) {
        return true;
    }

    return false;
}

/**
 * Send a 403 Forbidden response
 *
 * @return void
 */
function send_forbidden_response()
{
    header('HTTP/1.1 403 Forbidden');
    header('X-Content-Type-Options: nosniff');
    header('Strict-Transport-Security: max-age=63072000');
    die('Access forbidden - Login required');
}
