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

// Set up debugging log function
function duitku_debug_log($message, $level = 'info') {
    global $DB, $CFG;
    
    // Always log to database
    $DB->insert_record('enrol_duitku_log', [
        'timestamp' => time(),
        'log_type' => 'debug_' . $level,
        'data' => $message,
        'status' => $level
    ]);
    
    // If debugging is enabled, also write to error log
    if (!empty($CFG->debugdisplay) || !empty($CFG->debug) && $CFG->debug >= DEBUG_NORMAL) {
        error_log('[DUITKU_CALLBACK] ' . $message);
    }
}http://moodle.org/
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
 * Listens to any callbacks from Duitku.
 *
 * @package   enrol_duitku
 * @copyright 2022 Michael David <mikedh2612@gmail.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

use enrol_duitku\duitku_mathematical_constants;
use enrol_duitku\duitku_status_codes;
use enrol_duitku\duitku_helper;

// This script does not require login.
require("../../config.php"); // phpcs:ignore
require_once("lib.php");
require_once("{$CFG->libdir}/enrollib.php");
require_once("{$CFG->libdir}/filelib.php");

// Make sure we are enabled in the first place.
if (!enrol_is_enabled('duitku')) {
    http_response_code(503);
    throw new moodle_exception('errdisabled', 'enrol_duitku');
}

// Gets all response parameter from Duitku callback.
$apikey = get_config('enrol_duitku', 'apikey');
$merchantcode = required_param('merchantCode', PARAM_TEXT);
$amount = required_param('amount', PARAM_TEXT);
$merchantorderid = required_param('merchantOrderId', PARAM_TEXT);
$productdetail = required_param('productDetail', PARAM_TEXT);
$additionalparam = required_param('additionalParam', PARAM_TEXT);
$paymentcode = required_param('paymentCode', PARAM_TEXT);
$resultcode = required_param('resultCode', PARAM_TEXT);
$merchantuserid = required_param('merchantUserId', PARAM_TEXT);
$reference = required_param('reference', PARAM_TEXT);
$signature = required_param('signature', PARAM_TEXT);

// First, let's log the incoming callback request for debugging
$DB->insert_record('enrol_duitku_log', [
    'timestamp' => time(),
    'log_type' => 'callback_received',
    'data' => json_encode([
        'merchantCode' => $merchantcode,
        'amount' => $amount,
        'merchantOrderId' => $merchantorderid,
        'reference' => $reference
    ]),
    'status' => 'received'
]);

// Check if this is a membership transaction (starts with MBRS-)
$ismembershiptransaction = (strpos($merchantorderid, 'MBRS-') === 0);

// Parse merchant order ID based on transaction type
$custom = explode('-', $merchantorderid);

// For membership transactions, format is MBRS-{userid}-{timestamp}
// For course transactions, format is {timestamp}-{userid}-{courseid}-{instanceid}
if (empty($custom) || 
    ($ismembershiptransaction && count($custom) < 3) || 
    (!$ismembershiptransaction && count($custom) < 4)) {
    $DB->insert_record('enrol_duitku_log', [
        'timestamp' => time(),
        'log_type' => 'callback_error',
        'data' => "Invalid merchantOrderId format: $merchantorderid",
        'status' => 'error'
    ]);
    throw new moodle_exception('invalidrequest', 'core_error', '', null, 'Invalid value of the request param: merchantOrderId');
}

if (empty($merchantcode) || empty($amount) || empty($merchantorderid) || empty($signature)) {
    $DB->insert_record('enrol_duitku_log', [
        'timestamp' => time(),
        'log_type' => 'callback_error',
        'data' => "Missing required parameters",
        'status' => 'error'
    ]);
    throw new moodle_exception('invalidrequest', 'core_error', '', null, 'Bad Parameter');
}

$params = $merchantcode . $amount . $merchantorderid . $apikey;
$calcsignature = md5($params);
if ($signature != $calcsignature) {
    throw new moodle_exception('invalidrequest', 'core_error', '', null, 'Bad Signature');
}

// Make sure it is not a failed payment.
if (($resultcode !== duitku_status_codes::CHECK_STATUS_SUCCESS)) {
    $DB->insert_record('enrol_duitku_log', [
        'timestamp' => time(),
        'log_type' => 'callback_error',
        'data' => "Payment failed with resultCode: $resultcode",
        'status' => 'error'
    ]);
    throw new moodle_exception('invalidrequest', 'core_error', '', null, 'Payment Failed');
}

$data = new stdClass();
$data->userid = (int)$custom[1];

// Check if this is a membership transaction
if ($ismembershiptransaction) {
    // For membership transactions, userid is in position 1
    $data->userid = (int)$custom[1];
    
    // Get user
    $user = $DB->get_record("user", ["id" => $data->userid], "*", MUST_EXIST);
    
    // Use system context for membership
    $context = context_system::instance();
    $PAGE->set_context($context);
    
    // Log detailed information for debugging
    $log_data = [
        'userid' => $data->userid,
        'reference' => $reference,
        'amount' => $amount,
        'merchantorderid' => $merchantorderid,
        'timestamp' => time()
    ];
    
    $DB->insert_record('enrol_duitku_log', [
        'timestamp' => time(),
        'log_type' => 'membership_payment',
        'data' => "Processing membership payment: " . json_encode($log_data),
        'status' => 'processing'
    ]);
    
    // Process membership payment
    require_once($CFG->dirroot . '/enrol/duitku/classes/duitku_membership.php');
    if (duitku_membership::process_membership_payment($data->userid, $reference, (int)$amount)) {
        // Double-check that membership was actually created
        $has_membership = duitku_membership::has_active_membership($data->userid);
        
        // Log detailed result
        $DB->insert_record('enrol_duitku_log', [
            'timestamp' => time(),
            'log_type' => 'membership_payment',
            'data' => "Membership payment successful for user {$data->userid}, membership active: " . 
                     ($has_membership ? 'yes' : 'no'),
            'status' => 'success'
        ]);
        
        if (!$has_membership) {
            // Try to force role assignment if membership exists but role isn't assigned
            duitku_membership::assign_membership_role_system($data->userid);
        }
        
        // Return success header and exit
        header('HTTP/1.1 200 OK');
        echo "MEMBERSHIP_SUCCESS";
        exit;
    } else {
        // Log failed payment processing with detailed error
        $DB->insert_record('enrol_duitku_log', [
            'timestamp' => time(),
            'log_type' => 'membership_payment',
            'data' => "Failed to process membership payment for user {$data->userid}. " .
                     "Merchant order ID: {$merchantorderid}, Reference: {$reference}",
            'status' => 'error'
        ]);
        
        http_response_code(500);
        echo "MEMBERSHIP_PROCESSING_ERROR";
        exit;
    }
} else {
    // Regular course enrollment process
    $data->courseid = (int)$custom[2];
    $user = $DB->get_record("user", ["id" => $data->userid], "*", MUST_EXIST);
    $course = $DB->get_record("course", ["id" => $data->courseid], "*", MUST_EXIST);
    $context = \context_course::instance($course->id, MUST_EXIST);
    $PAGE->set_context($context);

// Set enrolment duration (default from Moodle).
// Only accessible if all required parameters are available.
$data->instanceid = (int)$custom[3];
$plugininstance = $DB->get_record("enrol", ["id" => $data->instanceid, "enrol" => "duitku", "status" => 0], "*", MUST_EXIST);
$plugin = enrol_get_plugin('duitku');
if ($plugininstance->enrolperiod) {
    $timestart = time();
    $timeend = $timestart + $plugininstance->enrolperiod;
} else {
    $timestart = 0;
    $timeend = 0;
}

// Double check on transaction before continuing.
$environment = get_config('enrol_duitku', 'environment');
$duitkuhelper = new duitku_helper($merchantcode, $apikey, $merchantorderid, $environment);
$requestdata = $duitkuhelper->check_transaction($context);
$response = json_decode($requestdata['request']);
$httpode = $requestdata['httpCode'];
if (($response->statusCode !== duitku_status_codes::CHECK_STATUS_SUCCESS)) {
    throw new moodle_exception('invalidrequest', 'core_error', '', null, 'Payment Failed');
}

// Enrol user and update database.
$plugin->enrol_user($plugininstance, $user->id, $plugininstance->roleid, $timestart, $timeend);

// Add to log that callback has been received and student enrolled.
$eventarray = [
    'context' => $context,
    'relateduserid' => (int)$custom[1],
    'other' => [
        'Log Details' => get_string('log_callback', 'enrol_duitku'),
        'merchantOrderId' => $merchantorderid,
        'reference' => $reference
    ]
];
$duitkuhelper->log_request($eventarray);

// Trigger payment_completed event
$completeevent = \enrol_duitku\event\payment_completed::create([
    'context' => $context,
    'userid' => (int)$custom[1],
    'relateduserid' => (int)$custom[1],
    'courseid' => (int)$custom[2],
    'other' => [
        'reference' => $reference,
        'instanceid' => (int)$custom[3],
        'merchantorderid' => $merchantorderid,
    ]
]);
$completeevent->trigger();

$params = [
    'userid' => (int)$custom[1],
    'courseid' => (int)$custom[2],
    'instanceid' => (int)$custom[3],
    'reference' => $reference
];
$admin = get_admin(); // Only 1 MAIN admin can exist at a time.
$existingdata = $DB->get_record('enrol_duitku', $params);
$data->id = $existingdata->id;
$data->payment_status = $resultcode;
$data->pending_reason = get_string('log_callback', 'enrol_duitku');
$data->timeupdated = round(microtime(true) * \enrol_duitku\duitku_mathematical_constants::SECOND_IN_MILLISECONDS);

$DB->update_record('enrol_duitku', $data);

// Standard mail sending by Moodle to notify users if there are enrolments.
// Pass $view=true to filter hidden caps if the user cannot see them.
if ($users = get_users_by_capability($context, 'moodle/course:update', 'u.*', 'u.id ASC', '', '', '', '', false, true)) {
    $users = sort_by_roleassignment_authority($users, $context);
    $teacher = array_shift($users);
} else {
    $teacher = false;
}

$mailstudents = $plugin->get_config('mailstudents');
$mailteachers = $plugin->get_config('mailteachers');
$mailadmins = $plugin->get_config('mailadmins');
$shortname = format_string($course->shortname, true, ['context' => $context]);

} // End of regular course enrollment process

// Setup the array that will be replace the variables in the custom email html.
$maildata = [
    '$courseFullName' => format_string($course->fullname, true, array('context' => $context)),
    '$amount' => $amount,
    '$courseShortName' => $shortname,
    '$studentUsername' => fullname($user),
    '$courseFullName' => format_string($course->fullname, true, array('context' => $context)),
    '$teacherName' => empty($teacher) ? core_user::get_support_user() : $teacher->username,
    '$adminUsername' => $admin->username

];

// Setup the array that will be replace the variables in the email template.
$a = new stdClass();
$a->shortname = $shortname;
$a->adminUsername = $admin->username;
$a->studentUsername = fullname($user);
$a->amount = $amount;
$a->courseFullName = format_string($course->fullname, true, array('context' => $context));
$a->teachername = empty($teacher) ? core_user::get_support_user() : $teacher->username;
$templatedata = new stdClass();

if (!empty($mailstudents)) {
    $userfrom = empty($teacher) ? core_user::get_support_user() : $teacher;
    $subject = get_string("enrolmentnew", 'enrol', $shortname);
    $templatedata->student_email_template_header = format_text(get_string('student_email_template_header', 'enrol_duitku'), FORMAT_MOODLE);
    $templatedata->student_email_template_greeting = format_text(get_string('student_email_template_greeting', 'enrol_duitku', $a), FORMAT_MOODLE);
    $templatedata->student_email_template_body = format_text(get_string('student_email_template_body', 'enrol_duitku', $a), FORMAT_MOODLE);
    $studentemail = $plugin->get_config('student_email');
    $studentemail = html_entity_decode($studentemail);
    $fullmessage = empty($studentemail) === true ? $OUTPUT->render_from_template('enrol_duitku/duitku_mail_for_students', $templatedata) : strtr($studentemail, $maildata);

    // Send test email.
    ob_start();
    $success = email_to_user($user, $userfrom, $subject, $fullmessage);
    $smtplog = ob_get_contents();
    ob_end_clean();
}

if (!empty($mailteachers) && !empty($teacher)) {
    $subject = get_string("enrolmentnew", 'enrol', $shortname);
    $teacheremail = $plugin->get_config('teacher_email');
    $templatedata->teacher_email_template_header = format_text(get_string('teacher_email_template_header', 'enrol_duitku', $a), FORMAT_MOODLE);
    $templatedata->teacher_email_template_greeting = format_text(get_string('teacher_email_template_greeting', 'enrol_duitku', $a), FORMAT_MOODLE);
    $templatedata->teacher_email_template_body = format_text(get_string('teacher_email_template_body', 'enrol_duitku', $a), FORMAT_MOODLE);
    $fullmessage = empty($teacheremail) === true ? $OUTPUT->render_from_template('enrol_duitku/duitku_mail_for_teachers', $templatedata) : strtr($teacheremail, $maildata);

    // Send test email.
    ob_start();
    $success = email_to_user($teacher, $user, $subject, $fullmessage, $fullmessagehtml);
    $smtplog = ob_get_contents();
    ob_end_clean();
}

if (!empty($mailadmins)) {
    $adminemail = $plugin->get_config('admin_email');
    $admins = get_admins();
    foreach ($admins as $admin) {
        $subject = get_string("enrolmentnew", 'enrol', $shortname);
        $maildata['$adminUsername'] = $admin->username;
        $templatedata->admin_email_template_header = format_text(get_string('admin_email_template_header', 'enrol_duitku', $a), FORMAT_MOODLE);
        $templatedata->admin_email_template_greeting = format_text(get_string('admin_email_template_greeting', 'enrol_duitku', $a), FORMAT_MOODLE);
        $templatedata->admin_email_template_body = format_text(get_string('admin_email_template_body', 'enrol_duitku', $a), FORMAT_MOODLE);
        $templatedata->adminUsername = $admin->username;
        $fullmessage = empty($adminemail) === true ? $OUTPUT->render_from_template('enrol_duitku/duitku_mail_for_admins', $templatedata) : strtr($adminemail, $maildata);
        // Send test email.
        ob_start();
        echo($fullmessagehtml . '<br />');
        $success = email_to_user($admin, $user, $subject, $fullmessage, $fullmessagehtml);
        $smtplog = ob_get_contents();
        ob_end_clean();
    }
}
