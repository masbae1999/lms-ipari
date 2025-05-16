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
 * Verify Duitku callback URL tool for administrators.
 *
 * @package   enrol_duitku
 * @copyright 2025 IPARI
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require('../../config.php');
require_once('lib.php');
require_once($CFG->libdir . '/adminlib.php');
require_login();
$context = context_system::instance();
require_capability('moodle/site:config', $context);

$PAGE->set_url('/enrol/duitku/verify_callback.php');
$PAGE->set_context($context);
$PAGE->set_pagelayout('admin');
$PAGE->set_title("Duitku Callback Verification Tool");
$PAGE->set_heading("Duitku Callback Verification Tool");

// Set up the form
class callback_form extends moodleform {
    public function definition() {
        $mform = $this->_form;
        
        $mform->addElement('header', 'general', 'Test Duitku Callback');
        
        $mform->addElement('text', 'userid', 'User ID', array('size' => 10));
        $mform->setType('userid', PARAM_INT);
        $mform->addRule('userid', 'User ID is required', 'required', null, 'client');
        
        $mform->addElement('text', 'amount', 'Amount', array('size' => 10));
        $mform->setType('amount', PARAM_INT);
        $mform->setDefault('amount', '200000');
        $mform->addRule('amount', 'Amount is required', 'required', null, 'client');
        
        $types = [
            'course' => 'Course enrollment',
            'membership' => 'Membership payment'
        ];
        $mform->addElement('select', 'type', 'Payment Type', $types);
        
        $mform->addElement('text', 'courseid', 'Course ID (for course payments)', array('size' => 10));
        $mform->setType('courseid', PARAM_INT);
        $mform->disabledIf('courseid', 'type', 'eq', 'membership');
        
        $mform->addElement('text', 'instanceid', 'Instance ID (for course payments)', array('size' => 10));
        $mform->setType('instanceid', PARAM_INT);
        $mform->disabledIf('instanceid', 'type', 'eq', 'membership');
        
        $this->add_action_buttons(true, 'Generate Test Callback');
    }
}

$mform = new callback_form();

echo $OUTPUT->header();
echo $OUTPUT->heading("Verify Duitku Callback URL");

echo '<div class="alert alert-info">
    <p>This tool helps you verify that your Duitku callback URL is correctly set up and working properly. 
    It will generate a test callback request that simulates what Duitku sends after a successful payment.</p>
    <p>Current callback URL: <code>' . $CFG->wwwroot . '/enrol/duitku/callback.php</code></p>
</div>';

if ($data = $mform->get_data()) {
    $userid = $data->userid;
    $amount = $data->amount;
    $type = $data->type;
    
    // Validate the user ID
    $user = $DB->get_record('user', ['id' => $userid], '*', IGNORE_MISSING);
    if (!$user) {
        echo $OUTPUT->notification("User with ID $userid does not exist.", 'error');
        echo $OUTPUT->footer();
        exit;
    }
    
    // Get Duitku settings
    $merchantcode = get_config('enrol_duitku', 'merchantcode');
    $apikey = get_config('enrol_duitku', 'apikey');
    
    if (empty($merchantcode) || empty($apikey)) {
        echo $OUTPUT->notification("Duitku plugin is not configured properly. Please set the Merchant Code and API Key.", 'error');
        echo $OUTPUT->footer();
        exit;
    }
    
    // Generate a unique reference
    $reference = 'TEST'.time().rand(1000, 9999);
    
    // Generate merchant order ID
    if ($type == 'membership') {
        $merchantorderid = 'MBRS-'.$userid.'-'.time();
    } else {
        if (empty($data->courseid) || empty($data->instanceid)) {
            echo $OUTPUT->notification("For course payments, Course ID and Instance ID are required.", 'error');
            echo $OUTPUT->footer();
            exit;
        }
        $merchantorderid = time().'-'.$userid.'-'.$data->courseid.'-'.$data->instanceid;
    }
    
    // Calculate signature
    $signature = md5($merchantcode.$amount.$merchantorderid.$apikey);
    
    // Build callback data
    $callbackdata = [
        'merchantCode' => $merchantcode,
        'amount' => $amount,
        'merchantOrderId' => $merchantorderid,
        'productDetail' => $type == 'membership' ? 'Annual Membership' : 'Course Enrollment',
        'additionalParam' => '',
        'paymentCode' => 'BT123',
        'resultCode' => '00', // Success
        'merchantUserId' => $user->username,
        'reference' => $reference,
        'signature' => $signature
    ];
    
    // Send the request to the callback URL
    $url = $CFG->wwwroot . '/enrol/duitku/callback.php';
    
    // Display the callback details
    echo '<div class="alert alert-secondary">';
    echo '<h3>Callback Request Details:</h3>';
    echo '<pre>' . json_encode($callbackdata, JSON_PRETTY_PRINT) . '</pre>';
    echo '</div>';
    
    // Make curl request
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $callbackdata);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    
    $response = curl_exec($ch);
    $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    // Display the result
    echo '<div class="alert ' . ($httpcode == 200 ? 'alert-success' : 'alert-danger') . '">';
    echo '<h3>Callback Response:</h3>';
    echo '<p>HTTP Status Code: ' . $httpcode . '</p>';
    echo '<p>Response: ' . htmlspecialchars($response) . '</p>';
    echo '</div>';
    
    // Check logs for this transaction
    $logs = $DB->get_records_sql(
        "SELECT * FROM {enrol_duitku_log} 
         WHERE data LIKE :reference
         ORDER BY timestamp DESC
         LIMIT 10",
        ['reference' => '%' . $reference . '%']
    );
    
    if ($logs) {
        echo '<div class="alert alert-info">';
        echo '<h3>Recent Log Entries:</h3>';
        echo '<table class="table table-striped">';
        echo '<thead><tr><th>Timestamp</th><th>Type</th><th>Status</th><th>Data</th></tr></thead>';
        echo '<tbody>';
        
        foreach ($logs as $log) {
            echo '<tr>';
            echo '<td>' . userdate($log->timestamp) . '</td>';
            echo '<td>' . htmlspecialchars($log->log_type) . '</td>';
            echo '<td>' . htmlspecialchars($log->status) . '</td>';
            echo '<td><pre>' . htmlspecialchars($log->data) . '</pre></td>';
            echo '</tr>';
        }
        
        echo '</tbody></table>';
        echo '</div>';
    } else {
        echo '<div class="alert alert-warning">';
        echo '<p>No log entries found for this test callback. This may indicate a problem.</p>';
        echo '</div>';
    }
    
    // If it's a membership test, check if membership was created
    if ($type == 'membership') {
        $ismember = \enrol_duitku\duitku_membership::has_active_membership($userid);
        echo '<div class="alert ' . ($ismember ? 'alert-success' : 'alert-warning') . '">';
        echo '<h3>Membership Status:</h3>';
        echo '<p>User ' . fullname($user) . ' (ID: ' . $userid . ') ' . 
             ($ismember ? 'now has an active membership.' : 'does NOT have an active membership.') . '</p>';
        echo '</div>';
        
        if (!$ismember) {
            echo '<div class="alert alert-danger">';
            echo '<p>Membership was not created successfully. Check the logs for more details.</p>';
            echo '</div>';
        }
    }
} else {
    $mform->display();
}

echo $OUTPUT->footer();
