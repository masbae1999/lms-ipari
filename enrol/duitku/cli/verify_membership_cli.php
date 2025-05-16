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
 * CLI script to verify and repair all membership status records
 *
 * @package    enrol_duitku
 * @copyright  2025 IPARI
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

define('CLI_SCRIPT', true);

require(__DIR__ . '/../../../config.php');
require_once($CFG->libdir . '/clilib.php');
require_once($CFG->dirroot . '/enrol/duitku/classes/duitku_membership.php');
require_once($CFG->dirroot . '/enrol/duitku/classes/duitku_logger.php');

use enrol_duitku\duitku_membership;
use enrol_duitku\duitku_status_codes;
use enrol_duitku\duitku_logger;

// Define CLI options
list($options, $unrecognized) = cli_get_params([
    'help' => false,
    'list' => false,
    'fix' => false,
    'verify' => false,
    'reports' => false,
    'user' => 0,
    'verbose' => false
], [
    'h' => 'help',
    'l' => 'list',
    'f' => 'fix',
    'v' => 'verify',
    'r' => 'reports',
    'u' => 'user',
    'V' => 'verbose'
]);

// Display help
if ($options['help']) {
    echo "CLI script to verify and repair membership status.

Options:
    -h, --help          Print this help
    -l, --list          List all members and their status
    -v, --verify        Verify membership status without fixing
    -f, --fix           Fix inconsistencies in membership status
    -r, --reports       Display membership reports
    -u, --user=INT      Target specific user ID
    -V, --verbose       Verbose output

Example:
\$ php verify_membership_cli.php --verify --verbose
\$ php verify_membership_cli.php --fix --user=123
\$ php verify_membership_cli.php --reports

";
    exit(0);
}

// Intro message
cli_heading('Duitku Membership Verification Tool');
cli_writeln('');

// Function to get all users with membership records
function get_membership_users() {
    global $DB;
    
    $sql = "SELECT DISTINCT m.userid, u.firstname, u.lastname, u.email
            FROM {enrol_duitku_membership} m
            JOIN {user} u ON m.userid = u.id
            WHERE m.payment_type = :payment_type
            AND u.deleted = 0
            ORDER BY u.lastname, u.firstname";
            
    return $DB->get_records_sql($sql, ['payment_type' => duitku_status_codes::PAYMENT_TYPE_MEMBERSHIP]);
}

// Function to check a user's membership status and fix if needed
function check_user_membership($userid, $fix = false, $verbose = false) {
    global $DB;
    
    $user = $DB->get_record('user', ['id' => $userid], 'id, firstname, lastname, email', MUST_EXIST);
    
    if ($verbose) {
        cli_writeln("[User $userid] Checking " . fullname($user));
    }
    
    // Get the system context
    $systemcontext = \context_system::instance();
    
    // Get membership role ID
    $roleid = $DB->get_field('role', 'id', ['shortname' => duitku_membership::MEMBERSHIP_ROLE]);
    if (!$roleid) {
        if ($verbose) {
            cli_writeln("[User $userid] Membership role doesn't exist");
        }
        if ($fix) {
            // Create role
            duitku_membership::create_membership_role();
            $roleid = $DB->get_field('role', 'id', ['shortname' => duitku_membership::MEMBERSHIP_ROLE]);
            if (!$roleid) {
                cli_writeln("[User $userid] ERROR: Failed to create membership role");
                return false;
            }
            if ($verbose) {
                cli_writeln("[User $userid] Created membership role");
            }
        } else {
            return false;
        }
    }
    
    // Check for active membership record
    $currenttime = time();
    $active_record = $DB->get_record_sql(
        "SELECT * FROM {enrol_duitku_membership}
         WHERE userid = :userid
         AND payment_type = :payment_type
         AND payment_status = :payment_status
         AND expiry_time > :current_time
         ORDER BY expiry_time DESC
         LIMIT 1",
        [
            'userid' => $userid,
            'payment_type' => duitku_status_codes::PAYMENT_TYPE_MEMBERSHIP,
            'payment_status' => duitku_status_codes::CHECK_STATUS_SUCCESS,
            'current_time' => $currenttime
        ]
    );
    
    // Check for role assignment
    $has_role = $DB->record_exists('role_assignments', [
        'roleid' => $roleid,
        'contextid' => $systemcontext->id,
        'userid' => $userid
    ]);
    
    $status = [
        'active_record' => !empty($active_record),
        'has_role' => $has_role,
        'consistent' => (!empty($active_record) && $has_role) || (empty($active_record) && !$has_role),
        'messages' => []
    ];
    
    if ($active_record && !$has_role) {
        $status['messages'][] = "User has active membership record but missing role assignment";
        if ($fix) {
            $result = duitku_membership::assign_membership_role_system($userid);
            $status['fixed_role'] = $result;
            if ($result) {
                $status['messages'][] = "Fixed: Assigned membership role";
                duitku_logger::log_membership(
                    "CLI tool assigned missing membership role to user {$userid}",
                    duitku_logger::LOG_LEVEL_INFO,
                    ['user_id' => $userid]
                );
            } else {
                $status['messages'][] = "Failed to assign membership role";
            }
        }
    } else if (!$active_record && $has_role) {
        $status['messages'][] = "User has role assignment but no active membership record";
        if ($fix) {
            $result = \enrol_duitku\role_helper::handle_expired_membership($userid);
            $status['fixed_role'] = $result;
            if ($result) {
                $status['messages'][] = "Fixed: Removed membership role";
                duitku_logger::log_membership(
                    "CLI tool removed outdated membership role from user {$userid}",
                    duitku_logger::LOG_LEVEL_INFO,
                    ['user_id' => $userid]
                );
            } else {
                $status['messages'][] = "Failed to remove membership role";
            }
        }
    } else {
        $status['messages'][] = $active_record ? 
            "Active membership until " . userdate($active_record->expiry_time) : 
            "No active membership";
    }
    
    return $status;
}

// Function to display membership reports
function display_membership_reports() {
    global $DB;
    
    $currenttime = time();
    
    // Count active members
    $active_count = $DB->count_records_sql(
        "SELECT COUNT(DISTINCT userid) 
         FROM {enrol_duitku_membership} 
         WHERE payment_type = :payment_type
         AND payment_status = :payment_status
         AND expiry_time > :current_time",
        [
            'payment_type' => duitku_status_codes::PAYMENT_TYPE_MEMBERSHIP,
            'payment_status' => duitku_status_codes::CHECK_STATUS_SUCCESS,
            'current_time' => $currenttime
        ]
    );
    
    // Count expiring soon (within 30 days)
    $thirty_days = $currenttime + (30 * 24 * 60 * 60);
    $expiring_count = $DB->count_records_sql(
        "SELECT COUNT(DISTINCT userid) 
         FROM {enrol_duitku_membership} 
         WHERE payment_type = :payment_type
         AND payment_status = :payment_status
         AND expiry_time > :current_time
         AND expiry_time < :thirty_days",
        [
            'payment_type' => duitku_status_codes::PAYMENT_TYPE_MEMBERSHIP,
            'payment_status' => duitku_status_codes::CHECK_STATUS_SUCCESS,
            'current_time' => $currenttime,
            'thirty_days' => $thirty_days
        ]
    );
    
    // Get transactions in the last 30 days
    $thirty_days_ago = $currenttime - (30 * 24 * 60 * 60);
    $recent_transactions = $DB->count_records_sql(
        "SELECT COUNT(*) 
         FROM {enrol_duitku_transactions} 
         WHERE payment_type = :payment_type
         AND payment_time > :thirty_days_ago",
        [
            'payment_type' => duitku_status_codes::PAYMENT_TYPE_MEMBERSHIP,
            'thirty_days_ago' => $thirty_days_ago
        ]
    );
    
    // Get revenue in the last 30 days
    $recent_revenue = $DB->get_field_sql(
        "SELECT SUM(amount) 
         FROM {enrol_duitku_transactions} 
         WHERE payment_type = :payment_type
         AND payment_status = :payment_status
         AND payment_time > :thirty_days_ago",
        [
            'payment_type' => duitku_status_codes::PAYMENT_TYPE_MEMBERSHIP,
            'payment_status' => duitku_status_codes::CHECK_STATUS_SUCCESS,
            'thirty_days_ago' => $thirty_days_ago
        ]
    );
    $recent_revenue = $recent_revenue ?: 0;
    
    cli_heading("Membership Reports");
    cli_writeln("Active memberships: $active_count");
    cli_writeln("Expiring within 30 days: $expiring_count");
    cli_writeln("Transactions in last 30 days: $recent_transactions");
    cli_writeln("Revenue in last 30 days: Rp " . number_format($recent_revenue, 0, ',', '.'));
    cli_writeln('');
}

// Main script execution logic
try {
    if ($options['reports']) {
        display_membership_reports();
        exit(0);
    }
    
    $users = [];
    if ($options['user']) {
        $userid = (int)$options['user'];
        $user = $DB->get_record('user', ['id' => $userid], '*', MUST_EXIST);
        $users[$userid] = $user;
        cli_writeln("Checking single user: $userid (" . fullname($user) . ")");
    } else {
        $users = get_membership_users();
        cli_writeln("Found " . count($users) . " users with membership records");
    }
    
    if ($options['list']) {
        cli_heading("Membership Users");
        foreach ($users as $user) {
            $status = check_user_membership($user->userid, false, false);
            cli_writeln(
                sprintf("%-6s %-30s %-10s", 
                    $user->userid, 
                    fullname($user), 
                    $status['active_record'] ? 'ACTIVE' : 'INACTIVE'
                )
            );
        }
        exit(0);
    }
    
    $fix = $options['fix'];
    $verbose = $options['verbose'];
    $inconsistent = [];
    $fixed = [];
    
    cli_heading($fix ? "Verifying and Fixing Membership Status" : "Verifying Membership Status");
    foreach ($users as $user) {
        $status = check_user_membership($user->userid, $fix, $verbose);
        
        if (!$status['consistent']) {
            $inconsistent[$user->userid] = $status;
            if ($fix && isset($status['fixed_role']) && $status['fixed_role']) {
                $fixed[$user->userid] = true;
            }
        }
        
        if ($verbose) {
            foreach ($status['messages'] as $message) {
                cli_writeln("  [User {$user->userid}] $message");
            }
            cli_writeln("");
        }
    }
    
    // Summary
    cli_heading("Summary");
    cli_writeln("Total users checked: " . count($users));
    cli_writeln("Users with inconsistencies: " . count($inconsistent));
    if ($fix) {
        cli_writeln("Users fixed: " . count($fixed));
    }
    
    if (!$verbose && count($inconsistent) > 0) {
        cli_heading("Inconsistencies Found");
        foreach ($inconsistent as $userid => $status) {
            $user = $users[$userid];
            cli_writeln("User $userid (" . fullname($user) . "):");
            foreach ($status['messages'] as $message) {
                cli_writeln("  - $message");
            }
            cli_writeln("");
        }
    }
    
} catch (Exception $e) {
    cli_writeln("ERROR: " . $e->getMessage());
    cli_writeln($e->getTraceAsString());
    exit(1);
}

exit(0);
