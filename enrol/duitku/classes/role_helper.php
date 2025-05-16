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
 * Role management helper class
 *
 * @package   enrol_duitku
 * @copyright 2025 IPARI
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace enrol_duitku;

defined('MOODLE_INTERNAL') || die();

/**
 * Helper class for role management
 */
class role_helper {
    /**
     * Auto-enroll a member in all paid courses (basic version)
     * 
     * @param int $userid User ID
     * @return int Number of courses enrolled in
     */
    public static function basic_auto_enroll_in_paid_courses(int $userid): int {
        global $DB;
        
        // Get the standard student role ID
        $studentroleid = $DB->get_field('role', 'id', ['shortname' => 'student']);
        if (!$studentroleid) {
            return 0; // Can't enroll without student role
        }
        
        // Find all courses with duitku enrolment method
        $sql = "SELECT DISTINCT e.courseid, e.id as enrolid, c.fullname
                FROM {enrol} e
                JOIN {course} c ON c.id = e.courseid
                WHERE e.enrol = 'duitku'
                AND e.status = :enabled
                AND c.visible = 1";
        
        $params = ['enabled' => ENROL_INSTANCE_ENABLED];
        $courses = $DB->get_records_sql($sql, $params);
        
        if (empty($courses)) {
            return 0; // No paid courses found
        }
        
        $count = 0;
        foreach ($courses as $course) {
            $coursecontext = \context_course::instance($course->courseid);
            
            // Check if user is already enrolled
            if (is_enrolled($coursecontext, $userid)) {
                continue;
            }
            
            // Enroll user into the course
            if ($enrolinstance = $DB->get_record('enrol', ['id' => $course->enrolid])) {
                $enrolplugin = enrol_get_plugin('duitku');
                if ($enrolplugin) {
                    $timestart = time();
                    // Enroll indefinitely
                    $timeend = 0;
                    
                    $enrolplugin->enrol_user($enrolinstance, $userid, $studentroleid, $timestart, $timeend);
                    
                    // Log the enrollment
                    $DB->insert_record('enrol_duitku_log', [
                        'timestamp' => time(),
                        'log_type' => 'auto_enroll',
                        'data' => "User $userid enrolled in course {$course->courseid} ({$course->fullname})",
                        'status' => 'success'
                    ]);
                    
                    $count++;
                }
            }
        }
        
        return $count;
    }

    /**
     * Handle role assignments when membership expires
     * 
     * @param int $userid User ID
     * @return bool True if successful
     */
    public static function handle_expired_membership(int $userid): bool {
        global $DB;
        
        // Get the role ID for the membership role
        $roleid = $DB->get_field('role', 'id', ['shortname' => duitku_membership::MEMBERSHIP_ROLE]);
        
        if (!$roleid) {
            // Role not found
            return false;
        }
        
        // Remove the membership role from the user
        $systemcontext = \context_system::instance();
        role_unassign($roleid, $userid, $systemcontext->id);
        
        // Log the removal
        $DB->insert_record('enrol_duitku_log', [
            'timestamp' => time(),
            'log_type' => 'role_removal',
            'data' => "Removed role " . duitku_membership::MEMBERSHIP_ROLE . " from user $userid due to expired membership",
            'status' => 'success'
        ]);
        
        return true;
    }
    
    /**
     * Find all paid courses and enroll the user in them
     * 
     * @param int $userid User ID
     * @return array Array with count of enrollments and course IDs
     */
    public static function auto_enroll_in_paid_courses(int $userid): array {
        global $DB;
        
        $plugin = \enrol_get_plugin('duitku');
        if (!$plugin) {
            return ['count' => 0, 'courses' => []];
        }
        
        // Get student role ID
        $studentroleid = $DB->get_field('role', 'id', ['shortname' => 'student']);
        if (!$studentroleid) {
            $studentroleid = $plugin->get_config('roleid');
        }
        
        if (!$studentroleid) {
            return ['count' => 0, 'courses' => []];
        }
        
        // Get all paid courses with Duitku enrollment
        $sql = "SELECT e.*, c.id as courseid, c.fullname 
                FROM {enrol} e 
                JOIN {course} c ON e.courseid = c.id 
                WHERE e.enrol = 'duitku' 
                AND e.cost > 0 
                AND e.status = 0
                AND c.visible = 1";
        
        $paidcoursesenrolments = $DB->get_records_sql($sql);
        
        if (empty($paidcoursesenrolments)) {
            return ['count' => 0, 'courses' => []];
        }
        
        $enrolledcourses = [];
        $enrollcount = 0;
        
        // Get the user record
        $user = $DB->get_record('user', ['id' => $userid], '*', MUST_EXIST);
        
        // Enroll the user in each paid course
        foreach ($paidcoursesenrolments as $enrol) {
            $coursecontext = \context_course::instance($enrol->courseid);
            
            // Check if user is already enrolled
            if (is_enrolled($coursecontext, $user)) {
                continue;
            }
            
            // Enroll the user
            try {
                $plugin->enrol_user($enrol, $userid, $studentroleid);
                $enrolledcourses[] = $enrol->courseid;
                $enrollcount++;
                
                // Log the enrollment
                $DB->insert_record('enrol_duitku_log', [
                    'timestamp' => time(),
                    'log_type' => 'auto_enroll',
                    'data' => "User $userid enrolled in course {$enrol->courseid} ({$enrol->fullname}) via membership",
                    'status' => 'success'
                ]);
            } catch (\Exception $e) {
                // Log the error
                $DB->insert_record('enrol_duitku_log', [
                    'timestamp' => time(),
                    'log_type' => 'auto_enroll_error',
                    'data' => "Error enrolling user $userid in course {$enrol->courseid}: " . $e->getMessage(),
                    'status' => 'error'
                ]);
            }
        }
        
        return [
            'count' => $enrollcount,
            'courses' => $enrolledcourses
        ];
    }
    
    /**
     * Ensure user has proper role consistent with membership status
     * 
     * @param int $userid User ID
     * @return bool True if any role changes were made
     */
    public static function sync_user_role(int $userid): bool {
        global $DB;
        
        $hasmembership = duitku_membership::has_active_membership($userid);
        $hasrole = self::has_membership_role($userid);
        
        // Get the role ID for membership
        $roleid = $DB->get_field('role', 'id', ['shortname' => duitku_membership::MEMBERSHIP_ROLE]);
        
        if (!$roleid) {
            return false;
        }
        
        $systemcontext = \context_system::instance();
        $changed = false;
        
        if ($hasmembership && !$hasrole) {
            // Should have role but doesn't - assign it
            role_assign($roleid, $userid, $systemcontext->id);
            
            // Log the action
            $DB->insert_record('enrol_duitku_log', [
                'timestamp' => time(),
                'log_type' => 'role_assignment',
                'data' => "Assigned role " . duitku_membership::MEMBERSHIP_ROLE . " to user $userid during role sync",
                'status' => 'success'
            ]);
            
            $changed = true;
        } else if (!$hasmembership && $hasrole) {
            // Shouldn't have role but does - remove it
            role_unassign($roleid, $userid, $systemcontext->id);
            
            // Log the action
            $DB->insert_record('enrol_duitku_log', [
                'timestamp' => time(),
                'log_type' => 'role_removal',
                'data' => "Removed role " . duitku_membership::MEMBERSHIP_ROLE . " from user $userid during role sync",
                'status' => 'success'
            ]);
            
            $changed = true;
        }
        
        return $changed;
    }
    
    /**
     * Check if user has membership role
     * 
     * @param int $userid User ID
     * @return bool True if user has membership role
     */
    public static function has_membership_role(int $userid): bool {
        global $DB;
        
        $sql = "SELECT COUNT(ra.id)
                FROM {role_assignments} ra
                JOIN {role} r ON ra.roleid = r.id
                WHERE ra.userid = :userid AND r.shortname = :roleshortname";
                
        $params = [
            'userid' => $userid,
            'roleshortname' => duitku_membership::MEMBERSHIP_ROLE
        ];
        
        return $DB->count_records_sql($sql, $params) > 0;
    }
}
