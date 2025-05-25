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
 * Scheduled task to auto-enroll members in paid courses
 *
 * @package   enrol_duitku
 * @copyright 2025 IPARI
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace enrol_duitku\task;

defined('MOODLE_INTERNAL') || die();

use enrol_duitku\duitku_membership;
use enrol_duitku\duitku_status_codes;
use enrol_duitku\role_helper;

/**
 * Scheduled task to check membership status and auto-enroll members in paid courses.
 * Also handles expired memberships.
 */
class auto_enroll_members extends \core\task\scheduled_task
{

    /**
     * Get name for this task.
     *
     * @return string
     */
    public function get_name()
    {
        return get_string('auto_enroll_members', 'enrol_duitku');
    }

    /**
     * Run task for processing auto-enrollments and membership expirations.
     */
    public function execute()
    {
        global $DB, $CFG;

        mtrace('Starting Duitku membership auto-enrollment task');

        // Process expired memberships first
        $expiredcount = duitku_membership::check_expired_memberships();
        mtrace("Processed $expiredcount expired memberships");

        // Find users with active memberships
        $activemembers = $this->get_active_members();
        $totalenrollments = 0;
        $syncedusers = 0;

        if (empty($activemembers)) {
            mtrace('No active members found');
            return;
        }

        mtrace('Found ' . count($activemembers) . ' active members');

        // Process each active member
        foreach ($activemembers as $member) {
            $userid = $member->userid;
            $user = $DB->get_record('user', ['id' => $userid]);
            if (!$user) {
                mtrace("User ID $userid not found, skipping");
                continue;
            }
            // Skip guest users
            if (isguestuser($userid) || $user->username === 'guest') {
                mtrace("User $userid is a guest user, skipping auto-enrollment");
                continue;
            }

            // Sync role status first
            $rolechanged = role_helper::sync_user_role($userid);
            if ($rolechanged) {
                mtrace("User $userid role status synchronized");
                $syncedusers++;
            }

            // Auto-enroll in paid courses
            mtrace("Processing auto-enrollment for user $userid ({$user->username})");
            $result = role_helper::auto_enroll_in_paid_courses($userid);

            if ($result['count'] > 0) {
                mtrace("Enrolled user $userid in {$result['count']} courses: " . implode(', ', $result['courses']));
                $totalenrollments += $result['count'];
            } else {
                mtrace("No new enrollments created for user $userid");
            }
        }

        // Final summary
        mtrace("Auto-enrollment completed. Created $totalenrollments enrollments and synchronized $syncedusers users");
    }

    /**
     * Get all users with active memberships
     *
     * @return array Array of user objects with active memberships
     */
    private function get_active_members()
    {
        global $DB;

        $currenttime = time();

        // Find all users with the membership role who have active memberships
        $sql = "SELECT DISTINCT u.* 
                FROM {user} u
                JOIN {role_assignments} ra ON ra.userid = u.id
                JOIN {role} r ON r.id = ra.roleid
                JOIN {enrol_duitku_membership} m ON m.userid = u.id
                WHERE r.shortname = :roleshortname
                AND m.payment_type = :payment_type
                AND m.expiry_time > :current_time
                AND u.deleted = 0
                AND u.suspended = 0";

        $params = [
            'roleshortname' => duitku_membership::MEMBERSHIP_ROLE,
            'payment_type' => duitku_status_codes::PAYMENT_TYPE_MEMBERSHIP,
            'current_time' => $currenttime
        ];

        return $DB->get_records_sql($sql, $params);
    }

    /**
     * Get all paid courses with Duitku enrollment
     *
     * @return array Array of course objects
     */
    private function get_paid_courses()
    {
        global $DB;

        // Find all courses with Duitku enrollment and cost > 0
        $sql = "SELECT DISTINCT c.* 
                FROM {course} c
                JOIN {enrol} e ON e.courseid = c.id
                WHERE e.enrol = 'duitku'
                AND e.cost > 0
                AND e.status = 0
                AND c.visible = 1";

        return $DB->get_records_sql($sql);
    }
}
