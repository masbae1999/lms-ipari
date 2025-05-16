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
 * Transaction verification page for payment callback troubleshooting.
 *
 * @package   enrol_duitku
 * @copyright 2025 IPARI
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

use enrol_duitku\duitku_helper;
use enrol_duitku\duitku_membership;
use enrol_duitku\duitku_status_codes;

require('../../config.php');
require_once('lib.php');

require_login();

// Get reference from param
$reference = optional_param('ref', '', PARAM_TEXT);
$action = optional_param('action', '', PARAM_TEXT);

// Set up page
$PAGE->set_url('/enrol/duitku/verify_transaction.php', ['ref' => $reference]);
$PAGE->set_context(\context_system::instance());
$PAGE->set_pagelayout('standard');
$PAGE->set_title(get_string('verify_payment', 'enrol_duitku'));
$PAGE->set_heading(get_string('verify_payment', 'enrol_duitku'));

echo $OUTPUT->header();

// Display verification form if no reference provided
if (empty($reference)) {
    echo $OUTPUT->heading(get_string('verify_transaction', 'enrol_duitku'));
    
    echo '<div class="verify-transaction-form">';
    echo '<form action="' . $PAGE->url . '" method="get">';
    echo '<div class="form-group">';
    echo '<label for="ref">' . get_string('reference_code', 'enrol_duitku') . '</label>';
    echo '<input type="text" class="form-control" id="ref" name="ref" required>';
    echo '</div>';
    echo '<button type="submit" class="btn btn-primary">' . get_string('verify', 'enrol_duitku') . '</button>';
    echo '</form>';
    echo '</div>';
    
    echo $OUTPUT->footer();
    die();
}

// Get transaction details
$transaction = $DB->get_record('enrol_duitku_transactions', ['reference' => $reference], '*', IGNORE_MISSING);

if (!$transaction) {
    echo $OUTPUT->notification(get_string('transaction_not_found', 'enrol_duitku'), 'error');
    echo '<div class="mt-3"><a href="' . new moodle_url('/my/') . '" class="btn btn-secondary">' . get_string('back_to_dashboard', 'enrol_duitku') . '</a></div>';
    echo $OUTPUT->footer();
    die();
}

// Check if this is the user's transaction
if ($transaction->userid != $USER->id && !is_siteadmin()) {
    echo $OUTPUT->notification(get_string('not_your_transaction', 'enrol_duitku'), 'error');
    echo '<div class="mt-3"><a href="' . new moodle_url('/my/') . '" class="btn btn-secondary">' . get_string('back_to_dashboard', 'enrol_duitku') . '</a></div>';
    echo $OUTPUT->footer();
    die();
}

// Get configuration
$merchantcode = get_config('enrol_duitku', 'merchantcode');
$apikey = get_config('enrol_duitku', 'apikey');
$environment = get_config('enrol_duitku', 'environment');

// Process manual check action
if ($action === 'check' && confirm_sesskey()) {
    // Log the check attempt
    $DB->insert_record('enrol_duitku_log', [
        'timestamp' => time(),
        'log_type' => 'manual_transaction_check',
        'data' => "User {$USER->id} manually checking transaction with reference: $reference",
        'status' => 'info'
    ]);
    
    try {
        // Check transaction status with Duitku
        $duitkuhelper = new duitku_helper($merchantcode, $apikey, $transaction->merchant_order_id, $environment);
        $requestdata = $duitkuhelper->check_transaction(\context_system::instance());
        $response = json_decode($requestdata['request']);
        
        if ($response && $response->statusCode === duitku_status_codes::CHECK_STATUS_SUCCESS) {
            // Transaction is successful in Duitku, but might not be processed in our system
            // Check if it's a membership transaction
            if ($transaction->payment_type === duitku_status_codes::PAYMENT_TYPE_MEMBERSHIP) {
                // Process the membership payment
                duitku_membership::process_membership_payment($transaction->userid, $reference, $transaction->amount);
                
                // Check if membership is active
                $hasmembership = duitku_membership::has_active_membership($transaction->userid);
                
                if ($hasmembership) {
                    echo $OUTPUT->notification(get_string('membership_activated', 'enrol_duitku'), 'success');
                } else {
                    echo $OUTPUT->notification(get_string('membership_check_failed', 'enrol_duitku'), 'warning');
                }
            } else {
                // Course enrollment transaction
                echo $OUTPUT->notification(get_string('course_enrollment_transaction', 'enrol_duitku'), 'info');
            }
        } else {
            // Failed or pending status from Duitku
            $status = $response ? $response->statusCode : 'unknown';
            echo $OUTPUT->notification(get_string('transaction_status_error', 'enrol_duitku', $status), 'warning');
        }
    } catch (Exception $e) {
        // Log the error
        $DB->insert_record('enrol_duitku_log', [
            'timestamp' => time(),
            'log_type' => 'manual_check_error',
            'data' => "Error checking transaction $reference: " . $e->getMessage(),
            'status' => 'error'
        ]);
        
        echo $OUTPUT->notification($e->getMessage(), 'error');
    }
}

// Display transaction details
echo '<div class="transaction-details card">';
echo '<div class="card-body">';
echo '<h3 class="card-title">' . get_string('transaction_details', 'enrol_duitku') . '</h3>';

echo '<div class="detail-row">';
echo '<strong>' . get_string('reference_code', 'enrol_duitku') . ':</strong> ' . $transaction->reference;
echo '</div>';

echo '<div class="detail-row">';
echo '<strong>' . get_string('merchantorder_id', 'enrol_duitku') . ':</strong> ' . $transaction->merchant_order_id;
echo '</div>';

echo '<div class="detail-row">';
echo '<strong>' . get_string('amount', 'enrol_duitku') . ':</strong> Rp ' . number_format($transaction->amount, 0, ',', '.');
echo '</div>';

echo '<div class="detail-row">';
// Format payment time with proper date format
echo '<strong>' . get_string('payment_time', 'enrol_duitku') . ':</strong> ' . userdate($transaction->payment_time, get_string('strftimedatetimeshort', 'core_langconfig'));
echo '</div>';

echo '<div class="detail-row">';
echo '<strong>' . get_string('payment_status', 'enrol_duitku') . ':</strong> ';
if ($transaction->payment_status === duitku_status_codes::CHECK_STATUS_SUCCESS) { // Using CHECK_STATUS_SUCCESS for consistency
    echo '<span class="badge badge-success">' . get_string('status_success', 'enrol_duitku') . '</span>';
} else if ($transaction->payment_status === duitku_status_codes::CHECK_STATUS_PENDING) { // Using CHECK_STATUS_PENDING for consistency
    echo '<span class="badge badge-warning">' . get_string('status_pending', 'enrol_duitku') . '</span>';
} else {
    echo '<span class="badge badge-danger">' . get_string('status_failed', 'enrol_duitku') . '</span>';
}
echo '</div>';

echo '<div class="detail-row">';
echo '<strong>' . get_string('payment_type', 'enrol_duitku') . ':</strong> ';
if ($transaction->payment_type === duitku_status_codes::PAYMENT_TYPE_MEMBERSHIP) {
    echo get_string('membership', 'enrol_duitku');
} else {
    echo get_string('course_enrollment', 'enrol_duitku');
}
echo '</div>';

// Display related logs
$logs = $DB->get_records_select(
    'enrol_duitku_log', 
    "data LIKE :reference", 
    ['reference' => '%'.$reference.'%'],
    'timestamp DESC', 
    '*', 
    0, 
    5
);

if ($logs) {
    echo '<h4 class="mt-4">' . get_string('recent_logs', 'enrol_duitku') . '</h4>';
    echo '<table class="table table-striped">';
    echo '<thead><tr><th>' . get_string('time', 'enrol_duitku') . '</th><th>' . get_string('type', 'enrol_duitku') . '</th><th>' . get_string('status', 'enrol_duitku') . '</th></tr></thead>';
    echo '<tbody>';
    
    foreach ($logs as $log) {
        echo '<tr>';
        echo '<td>' . userdate($log->timestamp) . '</td>';
        echo '<td>' . $log->log_type . '</td>';
        echo '<td>' . $log->status . '</td>';
        echo '</tr>';
    }
    
    echo '</tbody></table>';
}

// Membership specific information
if ($transaction->payment_type === duitku_status_codes::PAYMENT_TYPE_MEMBERSHIP) {
    $hasmembership = duitku_membership::has_active_membership($transaction->userid);
    $expirydate = duitku_membership::get_membership_expiry($transaction->userid);
    
    echo '<h4 class="mt-4">' . get_string('membership_status', 'enrol_duitku') . '</h4>';
    echo '<div class="membership-info">';
    
    if ($hasmembership) {
        echo '<div class="alert alert-success">' . get_string('active_membership', 'enrol_duitku') . '</div>';
        if ($expirydate) {
            echo '<div>' . get_string('expires_on', 'enrol_duitku') . ': ' . userdate($expirydate) . '</div>';
        }
    } else {
        echo '<div class="alert alert-warning">' . get_string('no_active_membership', 'enrol_duitku') . '</div>';
    }
    
    echo '</div>';
}

// Offer manual check option if transaction is not success
if ($transaction->payment_status !== duitku_status_codes::CHECK_STATUS_SUCCESS) { // Using CHECK_STATUS_SUCCESS for consistency
    echo '<div class="manual-check mt-4">';
    echo '<p>' . get_string('verify_transaction_explanation', 'enrol_duitku') . '</p>';
    echo '<a href="' . new moodle_url('/enrol/duitku/verify_transaction.php', ['ref' => $reference, 'action' => 'check', 'sesskey' => sesskey()]) . '" class="btn btn-primary">' . get_string('check_status_now', 'enrol_duitku') . '</a>';
    echo '</div>';
}

echo '</div>'; // card-body
echo '</div>'; // card

echo '<div class="mt-3">';
echo '<a href="' . new moodle_url('/my/') . '" class="btn btn-secondary">' . get_string('back_to_dashboard', 'enrol_duitku') . '</a>';
echo '</div>';

echo $OUTPUT->footer();
