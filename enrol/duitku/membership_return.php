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
 * Duitku membership return page handler
 *
 * @package   enrol_duitku
 * @copyright 2025 IPARI
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

use enrol_duitku\duitku_status_codes;
use enrol_duitku\duitku_helper;
use enrol_duitku\duitku_membership;
use enrol_duitku\duitku_mathematical_constants;

require("../../config.php");
require_once("{$CFG->dirroot}/enrol/duitku/lib.php");

require_login();

// Parameters from Duitku return response
$merchantorderid = required_param('merchantOrderId', PARAM_TEXT);
$userid = required_param('userid', PARAM_INT);
$reference = optional_param('reference', '', PARAM_TEXT);
$resultcode = optional_param('resultCode', '', PARAM_TEXT);

// Verify that the user ID in the URL matches the logged-in user
if ($userid != $USER->id) {
    redirect(new moodle_url('/'), get_string('error_user_mismatch', 'enrol_duitku'), null, \core\output\notification::NOTIFY_ERROR);
    exit;
}

// Get configuration
$merchantcode = get_config('enrol_duitku', 'merchantcode');
$apikey = get_config('enrol_duitku', 'apikey');
$environment = get_config('enrol_duitku', 'environment');

// Prepare return URL
$returnurl = new moodle_url('/');
$dashboardurl = new moodle_url('/my/');

// Page setup
$PAGE->set_context(context_system::instance());
$PAGE->set_url('/enrol/duitku/membership_return.php');
$PAGE->set_pagelayout('standard');
$PAGE->set_title(get_string('membership_payment', 'enrol_duitku'));
$PAGE->set_heading(get_string('membership_payment', 'enrol_duitku'));

// Check transaction status
if (!empty($reference)) {
    // We have a reference, check transaction status directly
    $duitkuhelper = new duitku_helper($merchantcode, $apikey, $merchantorderid, $environment);
    $requestdata = $duitkuhelper->check_transaction(context_system::instance());
    $request = json_decode($requestdata['request']);
    
    if ($request->statusCode === duitku_status_codes::CHECK_STATUS_SUCCESS) {
        // Payment successful
        echo $OUTPUT->header();
        
        echo '<div class="membership-return success">';
        echo '<div class="return-icon"><i class="fa fa-check-circle fa-5x"></i></div>';
        echo '<h2>' . get_string('thank_you_membership', 'enrol_duitku') . '</h2>';
        echo '<p>' . get_string('membership_payment_success', 'enrol_duitku') . '</p>';
        
        // Get membership expiry date
        $expirydate = duitku_membership::get_membership_expiry($USER->id);
        if ($expirydate) {
            $expirydateformatted = userdate($expirydate, get_string('strftimedate', 'langconfig'));
            echo '<p>' . get_string('membership_expires', 'enrol_duitku') . ' <strong>' . $expirydateformatted . '</strong></p>';
        }
        
        echo '<div class="return-actions">';
        echo '<a href="' . $dashboardurl . '" class="btn btn-primary">' . get_string('gotodashboard', 'enrol_duitku') . '</a>';
        echo '</div>';
        echo '</div>';
        
        echo $OUTPUT->footer();
        exit;
    } else if ($request->statusCode === duitku_status_codes::CHECK_STATUS_PENDING) {
        // Payment pending
        echo $OUTPUT->header();
        
        echo '<div class="membership-return pending">';
        echo '<div class="return-icon"><i class="fa fa-clock fa-5x"></i></div>';
        echo '<h2>' . get_string('payment_pending', 'enrol_duitku') . '</h2>';
        echo '<p>' . get_string('membership_payment_pending', 'enrol_duitku') . '</p>';
        
        // Add countdown for auto-refresh
        echo '<div class="countdown-container">';
        echo '<p>' . get_string('checking_payment', 'enrol_duitku') . ' <span id="countdown">00:30</span></p>';
        echo '</div>';
        
        // JavaScript for countdown and auto-refresh
        $PAGE->requires->js_init_code("
            document.addEventListener('DOMContentLoaded', function() {
                var display = document.querySelector('#countdown');
                startTimer(30, display);
            });
            
            function startTimer(duration, display) {
                var timer = duration, minutes, seconds;
                var countdown = setInterval(function () {
                    minutes = parseInt(timer / 60, 10);
                    seconds = parseInt(timer % 60, 10);
                    
                    minutes = minutes < 10 ? '0' + minutes : minutes;
                    seconds = seconds < 10 ? '0' + seconds : seconds;
                    
                    display.textContent = minutes + ':' + seconds;
                    
                    if (--timer < 0) {
                        clearInterval(countdown);
                        window.location.reload();
                    }
                }, 1000);
            }
        ");
        
        echo '<div class="return-actions">';
        echo '<a href="' . $returnurl . '" class="btn btn-secondary">' . get_string('continue', 'enrol_duitku') . '</a>';
        echo '</div>';
        echo '</div>';
        
        echo $OUTPUT->footer();
        exit;
    } else if ($request->statusCode === duitku_status_codes::CHECK_STATUS_CANCELED) {
        // Payment canceled
        redirect($returnurl, get_string('payment_cancelled', 'enrol_duitku'), null, \core\output\notification::NOTIFY_WARNING);
        exit;
    }
}

// If we reach here, check if there's a pending transaction in the DB
$transaction = $DB->get_record('enrol_duitku_transactions', [
    'merchant_order_id' => $merchantorderid, 
    'userid' => $USER->id
]);

if ($transaction) {
    // Transaction exists, check status
    if ($transaction->payment_status === duitku_status_codes::CHECK_STATUS_SUCCESS) {
        // Payment successful
        redirect($dashboardurl, get_string('membership_payment_success', 'enrol_duitku'), null, \core\output\notification::NOTIFY_SUCCESS);
    } else if ($transaction->payment_status === duitku_status_codes::CHECK_STATUS_PENDING) {
        // Payment pending, redirect to check status
        $referenceurl = new moodle_url('/enrol/duitku/membership_return.php', [
            'merchantOrderId' => $merchantorderid,
            'userid' => $USER->id
        ]);
        redirect($referenceurl);
    } else {
        // Payment failed/canceled
        redirect($returnurl, get_string('payment_cancelled', 'enrol_duitku'), null, \core\output\notification::NOTIFY_WARNING);
    }
} else {
    // No transaction found, show general message
    echo $OUTPUT->header();
    
    echo '<div class="membership-return">';
    echo '<h2>' . get_string('membership_return', 'enrol_duitku') . '</h2>';
    echo '<p>' . get_string('checking_status', 'enrol_duitku') . '</p>';
    
    echo '<div class="return-actions">';
    echo '<a href="' . $dashboardurl . '" class="btn btn-primary">' . get_string('gotodashboard', 'enrol_duitku') . '</a>';
    echo '</div>';
    echo '</div>';
    
    echo $OUTPUT->footer();
}

// Custom styles
echo '<style>
    .membership-return {
        max-width: 600px;
        margin: 0 auto;
        padding: 20px;
        text-align: center;
        background-color: #fff;
        border-radius: 8px;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }
    .return-icon {
        margin: 20px 0;
        color: #e63946;
    }
    .membership-return.success .return-icon {
        color: #1e8e3e;
    }
    .membership-return.pending .return-icon {
        color: #f6c23e;
    }
    .return-actions {
        margin-top: 30px;
    }
    .countdown-container {
        margin: 20px 0;
        padding: 10px;
        background-color: #f8f9fa;
        border-radius: 4px;
    }
    #countdown {
        font-weight: bold;
        font-family: monospace;
    }
    .btn-primary {
        background-color: #e63946;
        border-color: #e63946;
    }
    .btn-primary:hover {
        background-color: #d92638;
        border-color: #d92638;
    }
</style>';
