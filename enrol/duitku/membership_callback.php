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
 * Duitku membership callback handler
 *
 * @package   enrol_duitku
 * @copyright 2025 IPARI
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

use enrol_duitku\duitku_status_codes;
use enrol_duitku\duitku_helper;
use enrol_duitku\duitku_membership;

// This script does not require login.
require("../../config.php"); // phpcs:ignore
require_once("lib.php");

// Make sure we are enabled in the first place.
if (!enrol_is_enabled('duitku')) {
    http_response_code(503);
    throw new moodle_exception('errdisabled', 'enrol_duitku');
}

// For callback from payment gateway, we can't require login
// as the request comes from the payment gateway, not the user

// Log the callback
$eventlogger = $DB->insert_record('enrol_duitku_log', [
    'timestamp' => time(),
    'log_type' => 'log_callback',
    'data' => json_encode($_REQUEST),
    'status' => 'received'
]);

// Get all response parameters from Duitku callback
$apikey = get_config('enrol_duitku', 'apikey');
$merchantcode = required_param('merchantCode', PARAM_TEXT);
$amount = required_param('amount', PARAM_TEXT);
$merchantorderid = required_param('merchantOrderId', PARAM_TEXT);
$productdetail = required_param('productDetail', PARAM_TEXT);
$additionalparam = required_param('additionalParam', PARAM_TEXT);
$paymentcode = required_param('paymentCode', PARAM_TEXT);
$resultcode = required_param('resultCode', PARAM_TEXT);
$reference = required_param('reference', PARAM_TEXT);
$signature = required_param('signature', PARAM_TEXT);

// Check if this is a membership transaction
if (strpos($merchantorderid, 'MBRS-') === 0) {
    // Parse the user ID from merchant order ID
    // Format is MBRS-{userid}-{timestamp}
    $parts = explode('-', $merchantorderid);
    if (count($parts) >= 3) {
        $userid = (int)$parts[1];
    } else {
        // Invalid merchant order ID format
        http_response_code(400);
        exit('Invalid merchantOrderId format');
    }

    // Calculate signature for validation
    $params = $merchantcode . $amount . $merchantorderid . $apikey;
    $calcsignature = md5($params);

    // Validate the signature
    if ($signature !== $calcsignature) {
        // Invalid signature
        http_response_code(400);
        exit('Invalid signature');
    }

    // Check result code from Duitku
    if ($resultcode === duitku_status_codes::CHECK_STATUS_SUCCESS) {
        // Process the membership payment
        if (duitku_membership::process_membership_payment($userid, $reference, (int)$amount)) {
            // Enroll user in all paid courses
            $autopaymentuser = $DB->get_record('user', ['id' => $userid], '*', MUST_EXIST);

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
                if (!is_enrolled(\context_course::instance($enrol->courseid), $autopaymentuser)) {
                    // Get the role ID to assign
                    $roleid = $DB->get_field('role', 'id', ['shortname' => 'student']);
                    if (!$roleid) {
                        $roleid = $plugin->get_config('roleid');
                    }

                    // Enroll the user in the course
                    $plugin->enrol_user($enrol, $userid, $roleid);

                    // Log the enrollment
                    $DB->insert_record('enrol_duitku_log', [
                        'timestamp' => time(),
                        'log_type' => 'auto_enroll',
                        'data' => "User $userid enrolled in course {$enrol->courseid} via membership",
                        'status' => 'success'
                    ]);
                }
            }

            // Update transaction record
            $DB->set_field(
                'enrol_duitku_transactions',
                'payment_status',
                duitku_status_codes::CHECK_STATUS_SUCCESS,
                ['reference' => $reference]
            );

            // Log successful payment processing
            $DB->set_field('enrol_duitku_log', 'status', 'success', ['id' => $eventlogger]);

            echo "SUCCESS";
            exit;
        } else {
            // Failed to process payment
            http_response_code(500);
            $DB->set_field('enrol_duitku_log', 'status', 'failed', ['id' => $eventlogger]);
            exit('Failed to process membership payment');
        }
    } else {
        // Payment not successful
        $DB->set_field('enrol_duitku_log', 'status', 'payment_failed', ['id' => $eventlogger]);
        echo "FAILED";
        exit;
    }
} else {
    // Not a membership transaction, should be handled by regular callback
    // Pass the control to the normal callback
    $callbackurl = "$CFG->wwwroot/enrol/duitku/callback.php";

    // Redirect to normal callback with all parameters
    $params = $_REQUEST;
    $params['redirected_from'] = 'membership';

    // Use curl to forward the request
    $ch = curl_init($callbackurl);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    http_response_code($httpcode);
    echo $response;
    exit;
}
