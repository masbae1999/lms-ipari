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
 * Event observer class
 *
 * @package    enrol_duitku
 * @copyright  2025 IPARI
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace enrol_duitku;

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot . '/enrol/duitku/classes/duitku_membership.php');
require_once($CFG->dirroot . '/enrol/duitku/classes/role_helper.php');

/**
 * Event observer for Duitku enrollment
 */
class observer {
    /**
     * Observer for enrol instance created event
     *
     * @param \core\event\enrol_instance_created $event
     * @return void
     */
    public static function enrol_instance_created(\core\event\enrol_instance_created $event) {
        global $DB;
        
        $eventdata = $event->get_data();
        $enrolid = $event->objectid;
        
        // Check if this is a Duitku enrolment instance
        if ($eventdata['other']['enrol'] !== 'duitku') {
            return;
        }
        
        // Get the instance data
        $instance = $DB->get_record('enrol', ['id' => $enrolid], '*', MUST_EXIST);
        
        if ($instance->enrol !== 'duitku' || $instance->status != ENROL_INSTANCE_ENABLED) {
            return;
        }
        
        $course = $DB->get_record('course', ['id' => $instance->courseid]);
        if (!$course) {
            return;
        }
        
        // Log the enrolment instance creation
        $DB->insert_record('enrol_duitku_log', [
            'timestamp' => time(),
            'log_type' => 'enrol_instance_created',
            'data' => "Duitku enrolment instance created for course {$course->id} ({$course->fullname})",
            'status' => 'info'
        ]);
        
        // If this is a paid course, auto-enroll all members
        if ($instance->cost > 0) {
            self::enroll_all_members_in_course($instance->courseid);
        }
    }
    
    /**
     * Observer for enrol instance updated event
     *
     * @param \core\event\enrol_instance_updated $event
     * @return void
     */
    public static function enrol_instance_updated(\core\event\enrol_instance_updated $event) {
        global $DB;
        
        $eventdata = $event->get_data();
        
        // Check if this is a Duitku enrolment instance
        if ($eventdata['other']['enrol'] !== 'duitku') {
            return;
        }
        
        // Get the instance data
        $instance = $DB->get_record('enrol', ['id' => $eventdata['objectid']], '*', MUST_EXIST);
        
        // If this is a paid course that's enabled, auto-enroll all members
        if ($instance->cost > 0 && $instance->status == 0) {
            self::enroll_all_members_in_course($instance->courseid);
        }
    }
    
    /**
     * Observer for course created event
     *
     * @param \core\event\course_created $event
     * @return void
     */
    public static function course_created(\core\event\course_created $event) {
        global $DB;
        
        $courseid = $event->objectid;
        $course = $DB->get_record('course', ['id' => $courseid]);
        
        if (!$course) {
            return;
        }
        
        // Log the course creation event
        $DB->insert_record('enrol_duitku_log', [
            'timestamp' => time(),
            'log_type' => 'course_created',
            'data' => "New course created: {$course->id} - {$course->fullname}",
            'status' => 'info'
        ]);
        
        // Wait a bit to make sure the enrollment method is fully created
        sleep(1);
        
        $hasduitku = $DB->record_exists('enrol', [
            'courseid' => $courseid,
            'enrol' => 'duitku',
            'status' => 0
        ]);
        
        if ($hasduitku) {
            // If it has Duitku enrollment, check if it's paid
            $instance = $DB->get_record('enrol', [
                'courseid' => $courseid,
                'enrol' => 'duitku',
                'status' => 0
            ]);
            
            if ($instance && $instance->cost > 0) {
                self::enroll_all_members_in_course($courseid);
            }
        }
    }
    
    /**
     * Enroll all active members in a specific course
     *
     * @param int $courseid Course ID
     * @return int Number of members enrolled
     */
    private static function enroll_all_members_in_course(int $courseid): int {
        global $DB;
        
        $plugin = \enrol_get_plugin('duitku');
        if (!$plugin) {
            return 0;
        }
        
        // Get instance
        $instance = $DB->get_record('enrol', [
            'courseid' => $courseid,
            'enrol' => 'duitku',
            'status' => 0
        ]);
        
        if (!$instance) {
            return 0;
        }
        
        // Get student role ID
        $studentroleid = $DB->get_field('role', 'id', ['shortname' => 'student']);
        if (!$studentroleid) {
            $studentroleid = $plugin->get_config('roleid');
            if (!$studentroleid) {
                return 0;
            }
        }
        
        $course = $DB->get_record('course', ['id' => $courseid], '*', MUST_EXIST);
        $coursecontext = \context_course::instance($courseid);
        
        // Get all users with active membership
        $activemembers = self::get_active_members();
        $enrollcount = 0;
        
        foreach ($activemembers as $user) {
            // Check if already enrolled
            if (is_enrolled($coursecontext, $user->id)) {
                continue;
            }
            
            try {
                // Enroll in the course
                $plugin->enrol_user($instance, $user->id, $studentroleid);
                $enrollcount++;
                
                // Log the enrollment
                $DB->insert_record('enrol_duitku_log', [
                    'timestamp' => time(),
                    'log_type' => 'course_created_enroll',
                    'data' => "User {$user->id} enrolled in new course {$courseid} ({$course->fullname}) via membership",
                    'status' => 'success'
                ]);
            } catch (\Exception $e) {
                // Log error
                $DB->insert_record('enrol_duitku_log', [
                    'timestamp' => time(),
                    'log_type' => 'course_created_enroll_error',
                    'data' => "Error enrolling user {$user->id} in course {$courseid}: " . $e->getMessage(),
                    'status' => 'error'
                ]);
            }
        }
        
        return $enrollcount;
    }
    
    /**
     * Get all users with active memberships
     *
     * @return array Array of user objects
     */
    private static function get_active_members() {
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
}
