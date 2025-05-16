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
 * Utility functions for detailed logging for the Duitku plugin
 *
 * @package    enrol_duitku
 * @copyright  2025 IPARI
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace enrol_duitku;

defined('MOODLE_INTERNAL') || die();

/**
 * Helper class for detailed logging
 */
class duitku_logger {
    /**
     * Log levels
     */
    const LOG_LEVEL_DEBUG = 'debug';
    const LOG_LEVEL_INFO = 'info';
    const LOG_LEVEL_WARNING = 'warning';
    const LOG_LEVEL_ERROR = 'error';
    const LOG_LEVEL_CRITICAL = 'critical';

    /**
     * Log types
     */
    const LOG_TYPE_MEMBERSHIP = 'membership';
    const LOG_TYPE_CALLBACK = 'callback';
    const LOG_TYPE_TRANSACTION = 'transaction';
    const LOG_TYPE_ROLE = 'role';
    const LOG_TYPE_PAYMENT = 'payment';
    
    /**
     * Log a detailed message to the enrol_duitku_log table
     *
     * @param string $type The log type (membership, callback, transaction, etc.)
     * @param string $message The message to log
     * @param string $level The log level (debug, info, warning, error, critical)
     * @param array $data Additional data to log as JSON
     * @return int|false The ID of the inserted log or false on failure
     */
    public static function log($type, $message, $level = self::LOG_LEVEL_INFO, $data = []) {
        global $DB, $USER;
        
        $record = new \stdClass();
        $record->timestamp = time();
        $record->log_type = $type;
        $record->data = $message;
        $record->status = $level;
        
        // Add additional context data
        if (!empty($data)) {
            $jsondata = json_encode($data);
            if (strlen($record->data) + strlen($jsondata) < 1024) { 
                // Prevent exceeding database field size limits
                $record->data .= "\n" . $jsondata;
            } else {
                $record->data .= "\nAdditional data too large to include";
            }
        }
        
        // Add user information if available
        if (!empty($USER->id)) {
            $record->data .= "\nUser: {$USER->id} ({$USER->firstname} {$USER->lastname})";
        }
        
        try {
            return $DB->insert_record('enrol_duitku_log', $record, true);
        } catch (\Exception $e) {
            // If logging itself fails, write to PHP error log as fallback
            error_log('Duitku plugin error logging failed: ' . $e->getMessage() . 
                      ' | Original message: ' . $message);
            return false;
        }
    }
    
    /**
     * Log membership-related activity
     *
     * @param string $message The message to log
     * @param string $level The log level
     * @param array $data Additional data
     * @return int|false The ID of the inserted log or false on failure
     */
    public static function log_membership($message, $level = self::LOG_LEVEL_INFO, $data = []) {
        return self::log(self::LOG_TYPE_MEMBERSHIP, $message, $level, $data);
    }
    
    /**
     * Log callback-related activity
     *
     * @param string $message The message to log
     * @param string $level The log level
     * @param array $data Additional data
     * @return int|false The ID of the inserted log or false on failure
     */
    public static function log_callback($message, $level = self::LOG_LEVEL_INFO, $data = []) {
        return self::log(self::LOG_TYPE_CALLBACK, $message, $level, $data);
    }
    
    /**
     * Log transaction-related activity
     *
     * @param string $message The message to log
     * @param string $level The log level
     * @param array $data Additional data
     * @return int|false The ID of the inserted log or false on failure
     */
    public static function log_transaction($message, $level = self::LOG_LEVEL_INFO, $data = []) {
        return self::log(self::LOG_TYPE_TRANSACTION, $message, $level, $data);
    }
    
    /**
     * Log a detailed exception with stack trace
     *
     * @param string $type The log type
     * @param \Exception $exception The exception to log
     * @param string $context Additional context description
     * @return int|false The ID of the inserted log or false on failure
     */
    public static function log_exception($type, \Exception $exception, $context = '') {
        $message = ($context ? $context . ': ' : '') . 
                   $exception->getMessage() . 
                   "\nFile: " . $exception->getFile() . ":" . $exception->getLine() .
                   "\nTrace: " . $exception->getTraceAsString();
                   
        return self::log($type, $message, self::LOG_LEVEL_ERROR);
    }
    
    /**
     * Get logs for a specific user
     * 
     * @param int $userid The user ID
     * @param string $type Log type (optional)
     * @param int $limit Maximum number of logs to return
     * @return array Array of log records
     */
    public static function get_user_logs($userid, $type = null, $limit = 100) {
        global $DB;
        
        $sql = "SELECT * FROM {enrol_duitku_log} 
                WHERE data LIKE :userpattern 
                " . ($type ? "AND log_type = :log_type " : "") . "
                ORDER BY timestamp DESC";
        
        $params = [
            'userpattern' => "%User: {$userid}%"
        ];
        
        if ($type) {
            $params['log_type'] = $type;
        }
        
        return $DB->get_records_sql($sql, $params, 0, $limit);
    }
    
    /**
     * Get membership logs for a specific user
     * 
     * @param int $userid The user ID
     * @param int $limit Maximum number of logs to return
     * @return array Array of log records
     */
    public static function get_user_membership_logs($userid, $limit = 50) {
        return self::get_user_logs($userid, self::LOG_TYPE_MEMBERSHIP, $limit);
    }
    
    /**
     * Get transaction logs for a specific user
     * 
     * @param int $userid The user ID
     * @param int $limit Maximum number of logs to return
     * @return array Array of log records
     */
    public static function get_user_transaction_logs($userid, $limit = 50) {
        return self::get_user_logs($userid, self::LOG_TYPE_TRANSACTION, $limit);
    }
}
