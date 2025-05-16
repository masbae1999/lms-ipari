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
 * Contains helper class for membership functionality
 *
 * @package   enrol_duitku
 * @copyright 2025 IPARI <admin@example.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace enrol_duitku;

defined('MOODLE_INTERNAL') || die();

use enrol_duitku\duitku_mathematical_constants;
use enrol_duitku\duitku_status_codes;

/**
 * Helper class for managing membership subscriptions
 *
 * @package   enrol_duitku
 * @copyright 2025 IPARI <admin@example.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class duitku_membership {

    /** @var string The role shortname for membership */
    public const MEMBERSHIP_ROLE = 'penyuluhagama';
    
    /** @var int Default price for yearly membership in IDR */
    public const DEFAULT_MEMBERSHIP_PRICE = 200000;
    
    /**
     * Get membership statistics for dashboard
     *
     * @return array Array with statistics
     */
    public static function get_statistics(): array {
        global $DB;
        
        $currenttime = time();
        $monthago = $currenttime - (duitku_mathematical_constants::ONE_DAY_IN_SECONDS * 30);
        
        // Count active members
        $activesql = "SELECT COUNT(DISTINCT userid) 
                      FROM {enrol_duitku_membership}
                      WHERE payment_type = :payment_type
                      AND expiry_time > :current_time";
        $activeparams = [
            'payment_type' => duitku_status_codes::PAYMENT_TYPE_MEMBERSHIP,
            'current_time' => $currenttime
        ];
        $activecount = $DB->count_records_sql($activesql, $activeparams);
        
        // Count members expiring soon (within 30 days)
        $expiringsql = "SELECT COUNT(DISTINCT userid) 
                        FROM {enrol_duitku_membership}
                        WHERE payment_type = :payment_type
                        AND expiry_time > :current_time
                        AND expiry_time < :thirty_days";
        $expiringparams = [
            'payment_type' => duitku_status_codes::PAYMENT_TYPE_MEMBERSHIP,
            'current_time' => $currenttime,
            'thirty_days' => $currenttime + (duitku_mathematical_constants::ONE_DAY_IN_SECONDS * 30)
        ];
        $expiringcount = $DB->count_records_sql($expiringsql, $expiringparams);
        
        // Count new members this month
        $newsql = "SELECT COUNT(DISTINCT userid) 
                   FROM {enrol_duitku_membership}
                   WHERE payment_type = :payment_type
                   AND purchase_time > :month_ago";
        $newparams = [
            'payment_type' => duitku_status_codes::PAYMENT_TYPE_MEMBERSHIP,
            'month_ago' => $monthago
        ];
        $newcount = $DB->count_records_sql($newsql, $newparams);
        
        // Calculate total revenue
        $revenuesql = "SELECT SUM(amount) 
                       FROM {enrol_duitku_transactions}
                       WHERE payment_type = :payment_type
                       AND payment_status = :payment_status";
        $revenueparams = [
            'payment_type' => duitku_status_codes::PAYMENT_TYPE_MEMBERSHIP,
            'payment_status' => duitku_status_codes::CHECK_STATUS_SUCCESS // Using CHECK_STATUS_SUCCESS for consistency
        ];
        $revenue = $DB->get_field_sql($revenuesql, $revenueparams) ?: 0;
        
        return [
            'active' => $activecount,
            'expiring' => $expiringcount,
            'new' => $newcount,
            'revenue' => $revenue
        ];
    }

    /**
     * Check if a user has an active membership
     *
     * @param int $userid The ID of the user to check
     * @return bool True if the user has an active membership, false otherwise
     */
    public static function has_active_membership(int $userid): bool {
        global $DB;
        
        $currenttime = time();
        
        // First, check if the membership record exists and is not expired
        $sql = "SELECT * FROM {enrol_duitku_membership} 
                WHERE userid = :userid 
                AND payment_type = :payment_type 
                AND payment_status = :payment_status
                AND expiry_time > :current_time 
                ORDER BY expiry_time DESC LIMIT 1";
        
        $params = [
            'userid' => $userid,
            'payment_type' => duitku_status_codes::PAYMENT_TYPE_MEMBERSHIP,
            'payment_status' => duitku_status_codes::CHECK_STATUS_SUCCESS, // Using CHECK_STATUS_SUCCESS for consistency
            'current_time' => $currenttime
        ];
        
        $record = $DB->get_record_sql($sql, $params);
        $has_valid_record = !empty($record);
        
        // Also check if the user has the membership role
        $sql = "SELECT COUNT(ra.id)
                FROM {role_assignments} ra
                JOIN {role} r ON ra.roleid = r.id
                WHERE ra.userid = :userid AND r.shortname = :roleshortname";
                
        $params = [
            'userid' => $userid,
            'roleshortname' => self::MEMBERSHIP_ROLE
        ];
        
        $hasrole = $DB->count_records_sql($sql, $params) > 0;
        
        // Log inconsistencies for debugging
        if ($has_valid_record && !$hasrole) {
            $DB->insert_record('enrol_duitku_log', [
                'timestamp' => $currenttime,
                'log_type' => 'membership_inconsistency',
                'data' => "User $userid has valid membership record but missing role. " .
                          "Will try to fix by assigning the role.",
                'status' => 'warning'
            ]);
            
            // Try to assign the role to fix the inconsistency
            $systemcontext = \context_system::instance();
            self::assign_membership_role($userid, $systemcontext->id);
            
            // Re-check role assignment
            $hasrole = $DB->count_records_sql($sql, [
                'userid' => $userid,
                'roleshortname' => self::MEMBERSHIP_ROLE
            ]) > 0;
        }
        
        // Both should be true for membership to be active
        return $has_valid_record && $hasrole;
    }
    
    /**
     * Get the expiry date of a user's membership
     *
     * @param int $userid The ID of the user
     * @return int|null The expiry timestamp, or null if the user has no membership
     */
    public static function get_membership_expiry(int $userid): ?int {
        global $DB;
        
        $sql = "SELECT expiry_time FROM {enrol_duitku_membership} 
                WHERE userid = :userid 
                AND payment_type = :payment_type 
                ORDER BY expiry_time DESC LIMIT 1";
        
        $params = [
            'userid' => $userid,
            'payment_type' => duitku_status_codes::PAYMENT_TYPE_MEMBERSHIP
        ];
        
        $record = $DB->get_record_sql($sql, $params);
        
        if ($record) {
            return (int)$record->expiry_time;
        }
        
        return null;
    }
    
    /**
     * Assign the membership role to a user
     *
     * @param int $userid The ID of the user
     * @param int $contextid The context ID (usually system context)
     * @return bool True if successful, false otherwise
     */
    public static function assign_membership_role(int $userid, int $contextid): bool {
        global $DB;
        
        // Get the role ID for the membership role
        $roleid = $DB->get_field('role', 'id', ['shortname' => self::MEMBERSHIP_ROLE]);
        
        if (!$roleid) {
            return false;
        }
        
        // Check if the user already has this role
        $exists = $DB->record_exists('role_assignments', [
            'roleid' => $roleid, 
            'contextid' => $contextid, 
            'userid' => $userid
        ]);
        
        if ($exists) {
            return true; // Already assigned
        }
        
        // Assign the role
        $record = new \stdClass();
        $record->roleid = $roleid;
        $record->contextid = $contextid;
        $record->userid = $userid;
        $record->timemodified = time();
        $record->modifierid = $userid; // Self-assigned through payment
        
        $DB->insert_record('role_assignments', $record);
        
        return true;
    }
    
    /**
     * Create or extend a user's membership
     *
     * @param int $userid The ID of the user
     * @param int $expiryperiod The expiry period in days or timestamp for expiry date
     * @return bool True if successful, false otherwise
     */
    public static function create_or_extend_membership(int $userid, int $expiryperiod = 365): bool {
        global $DB;
        
        // Detailed logging of function entry
        $DB->insert_record('enrol_duitku_log', [
            'timestamp' => time(),
            'log_type' => 'membership_function',
            'data' => "Entered create_or_extend_membership for user $userid, expiry period: $expiryperiod days",
            'status' => 'info'
        ]);
        
        // Determine if expiryperiod is a timestamp or number of days
        $currenttime = time();
        if ($expiryperiod > $currenttime) {
            // It's a timestamp for the expiry date
            $newexpirytime = $expiryperiod;
        } else {
            // It's a number of days
            // Get current membership if exists
            $currentexpiry = self::get_membership_expiry($userid);
            
            // Calculate new expiry time
            $expiryperiodinseconds = $expiryperiod * duitku_mathematical_constants::ONE_DAY_IN_SECONDS;
            
            if ($currentexpiry && $currentexpiry > $currenttime) {
                // Extend the existing membership
                $newexpirytime = $currentexpiry + $expiryperiodinseconds;
                
                $DB->insert_record('enrol_duitku_log', [
                    'timestamp' => $currenttime,
                    'log_type' => 'membership_extending',
                    'data' => "Extending existing membership for user $userid from " . 
                             userdate($currentexpiry) . " to " . userdate($newexpirytime),
                    'status' => 'info'
                ]);
            } else {
                // Create a new membership
                $newexpirytime = $currenttime + $expiryperiodinseconds;
                
                $DB->insert_record('enrol_duitku_log', [
                    'timestamp' => $currenttime,
                    'log_type' => 'membership_creating',
                    'data' => "Creating new membership for user $userid until " . userdate($newexpirytime),
                    'status' => 'info'
                ]);
            }
        }
        
        try {
            // Store membership record
            $record = new \stdClass();
            $record->userid = $userid;
            $record->payment_type = duitku_status_codes::PAYMENT_TYPE_MEMBERSHIP;
            $record->payment_status = duitku_status_codes::CHECK_STATUS_SUCCESS; // Using CHECK_STATUS_SUCCESS for consistency
            $record->purchase_time = $currenttime;
            $record->expiry_time = $newexpirytime;
            $record->processed = 0; // Not processed for expiration yet
            
            $membershipid = $DB->insert_record('enrol_duitku_membership', $record, true);
            
            // Make sure the role exists before trying to assign it
            self::create_membership_role();
            
            // Assign membership role if needed
            $systemcontext = \context_system::instance();
            $roleassigned = self::assign_membership_role($userid, $systemcontext->id);
            
            // Log the action with detailed information
            $DB->insert_record('enrol_duitku_log', [
                'timestamp' => $currenttime,
                'log_type' => 'membership_created',
                'data' => "Membership record created (id: $membershipid) for user $userid until " . 
                         userdate($newexpirytime) . ". Role assigned: " . ($roleassigned ? 'yes' : 'no'),
                'status' => 'success'
            ]);
            
            return true;
        } catch (\Exception $e) {
            // Log the detailed error information
            $DB->insert_record('enrol_duitku_log', [
                'timestamp' => time(),
                'log_type' => 'membership_error',
                'data' => "Error creating/extending membership for user $userid: " . $e->getMessage() . 
                          "\nFile: " . $e->getFile() . ":" . $e->getLine() . 
                          "\nTrace: " . $e->getTraceAsString(),
                'status' => 'error'
            ]);
            
            return false;
        }
    }
    
    /**
     * Process membership subscription payment
     *
     * @param int $userid The ID of the user
     * @param string $reference The payment reference
     * @param int $amount The payment amount
     * @return bool True if successful, false otherwise
     */
    public static function process_membership_payment(int $userid, string $reference, int $amount): bool {
        global $DB;
        
        try {
            // Log start of processing
            $DB->insert_record('enrol_duitku_log', [
                'timestamp' => time(),
                'log_type' => 'transaction_processing',
                'data' => "Starting to process membership payment for user $userid, reference: $reference, amount: $amount",
                'status' => 'processing'
            ]);
            
            // Check if transaction already exists
            $existingtransaction = $DB->get_record('enrol_duitku_transactions', ['reference' => $reference]);
            $currenttime = time();
            
            if (!$existingtransaction) {
                // Create membership transaction record
                $transaction = new \stdClass();
                $transaction->userid = $userid;
                $transaction->reference = $reference;
                $transaction->payment_type = duitku_status_codes::PAYMENT_TYPE_MEMBERSHIP;
                $transaction->payment_status = duitku_status_codes::CHECK_STATUS_SUCCESS; // Using CHECK_STATUS_SUCCESS for consistency
                $transaction->amount = $amount;
                $transaction->payment_time = $currenttime;
                
                // Use original merchant order ID format
                $transaction->merchant_order_id = 'MBRS-'.$userid.'-'.$currenttime;
                
                $transactionid = $DB->insert_record('enrol_duitku_transactions', $transaction, true);
                
                // Log transaction creation
                $DB->insert_record('enrol_duitku_log', [
                    'timestamp' => $currenttime,
                    'log_type' => 'transaction_create',
                    'data' => "Created membership transaction (id: $transactionid) for user $userid, reference: $reference",
                    'status' => 'success'
                ]);
            } else {
                // Update existing transaction if needed
                $existingtransaction->payment_status = duitku_status_codes::CHECK_STATUS_SUCCESS; // Using CHECK_STATUS_SUCCESS for consistency
                $existingtransaction->timeupdated = $currenttime;
                $DB->update_record('enrol_duitku_transactions', $existingtransaction);
                $transactionid = $existingtransaction->id;
                
                // Log transaction update
                $DB->insert_record('enrol_duitku_log', [
                    'timestamp' => $currenttime,
                    'log_type' => 'transaction_update',
                    'data' => "Updated membership transaction (id: $transactionid) for user $userid, reference: $reference",
                    'status' => 'success'
                ]);
            }
            
            // Check if membership already exists and is active
            $has_active = self::has_active_membership($userid);
            if ($has_active) {
                $DB->insert_record('enrol_duitku_log', [
                    'timestamp' => $currenttime,
                    'log_type' => 'membership_exists',
                    'data' => "User $userid already has an active membership, will extend it",
                    'status' => 'info'
                ]);
            }
            
            // Create or extend the membership
            $result = self::create_or_extend_membership($userid);
            
            if ($result) {
                // Assign membership role to ensure it's set
                $systemcontext = \context_system::instance();
                self::assign_membership_role($userid, $systemcontext->id);
                
                // Auto-enroll user in paid courses
                $count = self::auto_enroll_member($userid);
                
                $DB->insert_record('enrol_duitku_log', [
                    'timestamp' => $currenttime,
                    'log_type' => 'membership_success',
                    'data' => "Successfully processed membership for user $userid. Auto-enrolled in $count courses.",
                    'status' => 'success'
                ]);
                
                return true;
            } else {
                $DB->insert_record('enrol_duitku_log', [
                    'timestamp' => $currenttime,
                    'log_type' => 'membership_failed',
                    'data' => "Failed to create or extend membership for user $userid despite successful payment",
                    'status' => 'error'
                ]);
                return false;
            }
        } catch (\Exception $e) {
            // Log error with detailed exception information
            $DB->insert_record('enrol_duitku_log', [
                'timestamp' => time(),
                'log_type' => 'transaction_error',
                'data' => "Error processing membership payment for user $userid: " . $e->getMessage() . 
                          "\nFile: " . $e->getFile() . ":" . $e->getLine() . 
                          "\nTrace: " . $e->getTraceAsString(),
                'status' => 'error'
            ]);
            return false;
        }
    }
    
    /**
     * Auto-enroll a member in all paid courses
     *
     * @param int $userid The ID of the user to enroll
     * @return int Number of courses enrolled in
     */
    public static function auto_enroll_member(int $userid): int {
        global $DB;
        
        $count = 0;
        $user = $DB->get_record('user', ['id' => $userid], '*', MUST_EXIST);
        
        // Get all paid courses with Duitku enrollment
        $sql = "SELECT e.*, c.id as courseid, c.fullname 
                FROM {enrol} e 
                JOIN {course} c ON e.courseid = c.id 
                WHERE e.enrol = 'duitku' 
                AND e.cost > 0 
                AND e.status = 0";
        
        $paidcoursesenrolments = $DB->get_records_sql($sql);
        
        // Enroll the user in each paid course
        foreach ($paidcoursesenrolments as $enrol) {
            $plugin = enrol_get_plugin('duitku');
            
            // Check if user is already enrolled
            if (!is_enrolled(\context_course::instance($enrol->courseid), $user)) {
                try {
                    // Get the role ID to assign
                    $roleid = $DB->get_field('role', 'id', ['shortname' => 'student']);
                    if (!$roleid) {
                        $roleid = $plugin->get_config('roleid');
                    }
                    
                    // Enroll the user in the course
                    $plugin->enrol_user($enrol, $userid, $roleid);
                    $count++;
                    
                    // Log the enrollment
                    $DB->insert_record('enrol_duitku_log', [
                        'timestamp' => time(),
                        'log_type' => 'auto_enroll',
                        'data' => "User $userid enrolled in course {$enrol->courseid} via membership",
                        'status' => 'success'
                    ]);
                } catch (\Exception $e) {
                    // Log enrollment error
                    $DB->insert_record('enrol_duitku_log', [
                        'timestamp' => time(),
                        'log_type' => 'auto_enroll_error',
                        'data' => "Error enrolling user $userid in course {$enrol->courseid}: " . $e->getMessage(),
                        'status' => 'error'
                    ]);
                }
            }
        }
        
        return $count;
    }
    
    /**
     * Get all users who have or had a membership
     * 
     * @return array Array of user objects
     */
    public static function get_all_members(): array {
        global $DB;
        
        $sql = "SELECT DISTINCT u.* 
                FROM {user} u
                JOIN {enrol_duitku_membership} m ON m.userid = u.id
                WHERE m.payment_type = :payment_type
                AND u.deleted = 0
                ORDER BY u.lastname, u.firstname";
                
        $params = [
            'payment_type' => duitku_status_codes::PAYMENT_TYPE_MEMBERSHIP
        ];
        
        return $DB->get_records_sql($sql, $params);
    }
    
    /**
     * Get IDs of users with active membership
     * 
     * @return array Array of user IDs
     */
    public static function get_all_active_members_ids(): array {
        global $DB;
        
        $currenttime = time();
        
        $sql = "SELECT DISTINCT m.userid 
                FROM {enrol_duitku_membership} m
                JOIN {user} u ON u.id = m.userid
                WHERE m.payment_type = :payment_type
                AND m.expiry_time > :current_time
                AND u.deleted = 0
                AND u.suspended = 0";
                
        $params = [
            'payment_type' => duitku_status_codes::PAYMENT_TYPE_MEMBERSHIP,
            'current_time' => $currenttime
        ];
        
        return $DB->get_fieldset_sql($sql, $params);
    }
    
    /**
     * Create a new membership directly with expiry timestamp
     * 
     * @param int $userid User ID
     * @param int $expirydate Expiry timestamp
     * @return bool True if successful
     */
    public static function create_new_membership(int $userid, int $expirydate): bool {
        global $DB;
        
        // Create membership record
        $membership = new \stdClass();
        $membership->userid = $userid;
        $membership->payment_type = duitku_status_codes::PAYMENT_TYPE_MEMBERSHIP;
        $membership->payment_status = duitku_status_codes::CHECK_STATUS_SUCCESS; // Using CHECK_STATUS_SUCCESS for consistency
        $membership->purchase_time = time();
        $membership->expiry_time = $expirydate;
        $membership->processed = 0;
        
        try {
            $DB->insert_record('enrol_duitku_membership', $membership);
            
            // Ensure user has the membership role
            self::assign_membership_role_system($userid);
            
            // Log the action
            $DB->insert_record('enrol_duitku_log', [
                'timestamp' => time(),
                'log_type' => 'membership_created',
                'data' => "Membership created/extended for user $userid until " . userdate($expirydate),
                'status' => 'success'
            ]);
            
            return true;
        } catch (Exception $e) {
            // Log error
            $DB->insert_record('enrol_duitku_log', [
                'timestamp' => time(),
                'log_type' => 'membership_creation_error',
                'data' => "Error creating membership for user $userid: " . $e->getMessage(),
                'status' => 'error'
            ]);
            
            return false;
        }
    }
    
    /**
     * Revoke a user's membership
     * 
     * @param int $userid User ID
     * @return bool True if successful
     */
    public static function revoke_membership(int $userid): bool {
        global $DB, $CFG;
        
        // Set all active memberships to expire now
        $currenttime = time();
        $success = $DB->set_field_select(
            'enrol_duitku_membership', 
            'expiry_time', 
            $currenttime,
            "userid = :userid AND expiry_time > :current_time", 
            ['userid' => $userid, 'current_time' => $currenttime]
        );
        
        if ($success) {
            // Remove the role
            require_once($CFG->dirroot . '/enrol/duitku/classes/role_helper.php');
            \enrol_duitku\role_helper::handle_expired_membership($userid);
            
            // Log the action
            $DB->insert_record('enrol_duitku_log', [
                'timestamp' => $currenttime,
                'log_type' => 'membership_revoked',
                'data' => "Membership manually revoked for user $userid",
                'status' => 'success'
            ]);
            
            return true;
        }
        
        return false;
    }
    
    /**
     * Assign the membership role to a user (without context)
     * 
     * @param int $userid User ID
     * @return bool True if successful
     */
    public static function assign_membership_role_system(int $userid): bool {
        global $DB;
        
        // Get role ID
        $roleid = $DB->get_field('role', 'id', ['shortname' => self::MEMBERSHIP_ROLE]);
        
        if (!$roleid) {
            // Role doesn't exist, try to create it
            if (!self::create_membership_role()) {
                return false;
            }
            $roleid = $DB->get_field('role', 'id', ['shortname' => self::MEMBERSHIP_ROLE]);
            if (!$roleid) {
                return false;
            }
        }
        
        // Get system context
        $systemcontext = \context_system::instance();
        
        // Check if role is already assigned
        $params = ['roleid' => $roleid, 'contextid' => $systemcontext->id, 'userid' => $userid];
        if ($DB->record_exists('role_assignments', $params)) {
            return true; // Already assigned
        }
        
        // Assign role
        role_assign($roleid, $userid, $systemcontext->id);
        
        // Log the action
        $DB->insert_record('enrol_duitku_log', [
            'timestamp' => time(),
            'log_type' => 'role_assigned',
            'data' => "Membership role assigned to user $userid",
            'status' => 'success'
        ]);
        
        return true;
    }
    
    /**
     * Create the membership role if it doesn't exist
     * 
     * @return bool True if successful
     */
    public static function create_membership_role(): bool {
        global $DB;
        
        // Check if role already exists
        if ($DB->record_exists('role', ['shortname' => self::MEMBERSHIP_ROLE])) {
            return true;
        }
        
        // Create the role
        $roledata = (object)[
            'name' => 'Penyuluh Agama',
            'shortname' => self::MEMBERSHIP_ROLE,
            'description' => 'Annual membership role with access to all paid courses',
            'archetype' => 'student'
        ];
        
        $roleid = create_role(
            $roledata->name,
            $roledata->shortname,
            $roledata->description,
            $roledata->archetype
        );
        
        if (!$roleid) {
            return false;
        }
        
        // Log creation
        $DB->insert_record('enrol_duitku_log', [
            'timestamp' => time(),
            'log_type' => 'role_created',
            'data' => "Created membership role '{$roledata->shortname}'",
            'status' => 'success'
        ]);
        
        return true;
    }

    /**
     * Check and update expired memberships
     *
     * @return int Number of expired memberships processed
     */
    public static function check_expired_memberships(): int {
        global $DB;
        
        $currenttime = time();
        $count = 0;
        
        // Find users with expired memberships
        $sql = "SELECT DISTINCT userid FROM {enrol_duitku_membership}
                WHERE payment_type = :payment_type
                AND expiry_time < :current_time
                AND processed = 0";
                
        $params = [
            'payment_type' => duitku_status_codes::PAYMENT_TYPE_MEMBERSHIP,
            'current_time' => $currenttime
        ];
        
        $expiredusers = $DB->get_records_sql($sql, $params);
        
        foreach ($expiredusers as $user) {
            // Check if user has a newer valid membership
            if (self::has_active_membership($user->userid)) {
                continue;
            }
            
            // Handle expired membership (remove role)
            require_once($CFG->dirroot . '/enrol/duitku/classes/role_helper.php');
            \enrol_duitku\role_helper::handle_expired_membership($user->userid);
            
            // Mark the expired memberships as processed
            $DB->set_field_select('enrol_duitku_membership', 'processed', 1, 
                    "userid = :userid AND expiry_time < :current_time",
                    ['userid' => $user->userid, 'current_time' => $currenttime]);
            
            // Log the expiration
            $DB->insert_record('enrol_duitku_log', [
                'timestamp' => time(),
                'log_type' => 'membership_expired',
                'data' => "Membership for user {$user->userid} has expired",
                'status' => 'processed'
            ]);
            
            $count++;
        }
        
        return $count;
    }
    
    /**
     * Get membership statistics
     *
     * @return \stdClass Object containing statistics
     */
    public static function get_membership_statistics(): \stdClass {
        global $DB;
        
        $currenttime = time();
        $onemonthago = strtotime('-1 month');
        $thirtydays = strtotime('+30 days');
        
        $stats = new \stdClass();
        
        // Active members count
        $sql = "SELECT COUNT(DISTINCT userid) 
                FROM {enrol_duitku_membership} 
                WHERE payment_type = :payment_type 
                AND expiry_time > :current_time";
                
        $params = [
            'payment_type' => duitku_status_codes::PAYMENT_TYPE_MEMBERSHIP,
            'current_time' => $currenttime
        ];
        
        $stats->active = $DB->count_records_sql($sql, $params);
        
        // Expiring soon (within 30 days)
        $sql = "SELECT COUNT(DISTINCT userid) 
                FROM {enrol_duitku_membership} 
                WHERE payment_type = :payment_type 
                AND expiry_time > :current_time 
                AND expiry_time < :thirty_days";
                
        $params = [
            'payment_type' => duitku_status_codes::PAYMENT_TYPE_MEMBERSHIP,
            'current_time' => $currenttime,
            'thirty_days' => $currenttime + (30 * duitku_mathematical_constants::ONE_DAY_IN_SECONDS)
        ];
        
        $stats->expiring = $DB->count_records_sql($sql, $params);
        
        // New members this month
        $sql = "SELECT COUNT(DISTINCT userid) 
                FROM {enrol_duitku_membership} 
                WHERE payment_type = :payment_type 
                AND purchase_time > :one_month_ago";
                
        $params = [
            'payment_type' => duitku_status_codes::PAYMENT_TYPE_MEMBERSHIP,
            'one_month_ago' => $onemonthago
        ];
        
        $stats->new = $DB->count_records_sql($sql, $params);
        
        // Revenue this month
        $sql = "SELECT SUM(amount) 
                FROM {enrol_duitku_transactions} 
                WHERE payment_type = :payment_type 
                AND payment_status = :payment_status
                AND payment_time > :one_month_ago";
                
        $params = [
            'payment_type' => duitku_status_codes::PAYMENT_TYPE_MEMBERSHIP,
            'payment_status' => duitku_status_codes::CHECK_STATUS_SUCCESS,
            'one_month_ago' => $onemonthago
        ];
        
        $stats->revenue = $DB->get_field_sql($sql, $params) ?: 0;
        
        return $stats;
    }
    
    /**
     * Get transaction data for charting
     *
     * @param int $days Number of days to look back
     * @return array Array of transaction data
     */
    public static function get_transaction_data(int $days): array {
        global $DB;
        
        $result = [];
        $starttime = strtotime("-{$days} days");
        
        // Get data day by day
        for ($i = 0; $i < $days; $i++) {
            $daytime = $starttime + ($i * duitku_mathematical_constants::ONE_DAY_IN_SECONDS);
            $nextday = $daytime + duitku_mathematical_constants::ONE_DAY_IN_SECONDS;
            
            $sql = "SELECT COUNT(*) as count, SUM(amount) as revenue
                    FROM {enrol_duitku_transactions}
                    WHERE payment_type = :payment_type
                    AND payment_status = :payment_status
                    AND payment_time >= :day_time
                    AND payment_time < :next_day";
                    
            $params = [
                'payment_type' => duitku_status_codes::PAYMENT_TYPE_MEMBERSHIP,
                'payment_status' => duitku_status_codes::CHECK_STATUS_SUCCESS,
                'day_time' => $daytime,
                'next_day' => $nextday
            ];
            
            $daydata = $DB->get_record_sql($sql, $params);
            
            $result[] = [
                'date' => date('Y-m-d', $daytime),
                'count' => (int)($daydata->count ?? 0),
                'revenue' => (int)($daydata->revenue ?? 0)
            ];
        }
        
        return $result;
    }
    
    /**
     * Get all members with membership data
     *
     * @return array Array of user objects with membership data
     */
    public static function get_members(): array {
        global $DB;
        
        // Get all users with the membership role
        $sql = "SELECT u.*, m.expiry_time
                FROM {user} u
                JOIN {role_assignments} ra ON ra.userid = u.id
                JOIN {role} r ON r.id = ra.roleid
                JOIN {enrol_duitku_membership} m ON m.userid = u.id
                WHERE r.shortname = :roleshortname
                AND u.deleted = 0
                GROUP BY u.id
                ORDER BY m.expiry_time DESC";
                
        $params = [
            'roleshortname' => self::MEMBERSHIP_ROLE
        ];
        
        $members = $DB->get_records_sql($sql, $params);
        
        // Get enrollment counts for each member
        foreach ($members as $member) {
            $sql = "SELECT COUNT(DISTINCT e.courseid) 
                    FROM {user_enrolments} ue
                    JOIN {enrol} e ON ue.enrolid = e.id
                    WHERE ue.userid = :userid";
                    
            $params = ['userid' => $member->id];
            
            $member->courses_count = $DB->count_records_sql($sql, $params);
        }
        
        return $members;
    }
}
