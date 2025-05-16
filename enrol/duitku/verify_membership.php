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
 * Admin tool to verify and fix membership status for a user
 *
 * @package    enrol_duitku
 * @copyright  2025 IPARI
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once('../../config.php');
require_once($CFG->libdir . '/adminlib.php');
require_once($CFG->dirroot . '/enrol/duitku/classes/duitku_membership.php');

use enrol_duitku\duitku_membership;
use enrol_duitku\duitku_status_codes;

// Restrict to admin
admin_externalpage_setup('enrol_duitku_verify_membership');
require_capability('moodle/site:config', context_system::instance());

// Parameters
$userid = optional_param('userid', 0, PARAM_INT);
$action = optional_param('action', '', PARAM_ALPHA);
$confirm = optional_param('confirm', 0, PARAM_BOOL);

// Setup page
$PAGE->set_context(context_system::instance());
$PAGE->set_url('/enrol/duitku/verify_membership.php');
$PAGE->set_title(get_string('verify_membership_title', 'enrol_duitku', ''));
$PAGE->set_heading(get_string('verify_membership_heading', 'enrol_duitku', ''));

// Start output
echo $OUTPUT->header();
echo $OUTPUT->heading(get_string('verify_membership_heading', 'enrol_duitku'));

// Show form to select user if none selected
if (!$userid) {
    $userform = new simple_form(new moodle_url('/enrol/duitku/verify_membership.php'), 'get');
    $userform->add_field('userid', get_string('user'), 'text', PARAM_INT, 'size="10"');
    $userform->display();
    echo $OUTPUT->footer();
    exit;
}

// Get the user
$user = $DB->get_record('user', ['id' => $userid], '*', MUST_EXIST);
echo $OUTPUT->heading(fullname($user), 3);

// Process actions
if ($action == 'fix' && $confirm) {
    // Fix membership issues by reassigning roles and checking status
    $systemcontext = context_system::instance();
    
    // First log the current status
    $current_status = duitku_membership::has_active_membership($userid);
    $active_record_exists = $DB->record_exists_sql(
        "SELECT id FROM {enrol_duitku_membership}
         WHERE userid = :userid
         AND payment_type = :payment_type
         AND expiry_time > :now",
        [
            'userid' => $userid,
            'payment_type' => duitku_status_codes::PAYMENT_TYPE_MEMBERSHIP,
            'now' => time()
        ]
    );
    
    // Check if user has the membership role
    $roleid = $DB->get_field('role', 'id', ['shortname' => duitku_membership::MEMBERSHIP_ROLE]);
    $has_role = false;
    if ($roleid) {
        $has_role = $DB->record_exists('role_assignments', [
            'roleid' => $roleid,
            'contextid' => $systemcontext->id,
            'userid' => $userid
        ]);
    }
    
    echo '<div class="alert alert-info">';
    echo '<h4>Current status:</h4>';
    echo '<ul>';
    echo '<li>Active membership according to has_active_membership(): ' . ($current_status ? 'Yes' : 'No') . '</li>';
    echo '<li>Active record in enrol_duitku_membership: ' . ($active_record_exists ? 'Yes' : 'No') . '</li>';
    echo '<li>Has membership role: ' . ($has_role ? 'Yes' : 'No') . '</li>';
    echo '</ul>';
    
    // Apply fixes
    if ($active_record_exists && !$has_role) {
        // Membership record exists but role is missing
        $result = duitku_membership::assign_membership_role_system($userid);
        echo '<div class="alert alert-' . ($result ? 'success' : 'danger') . '">';
        echo 'Role assignment: ' . ($result ? 'Successful' : 'Failed');
        echo '</div>';
    } else if (!$active_record_exists && $has_role) {
        // No active membership record but has role - remove role
        $result = \enrol_duitku\role_helper::handle_expired_membership($userid);
        echo '<div class="alert alert-' . ($result ? 'success' : 'danger') . '">';
        echo 'Role removal: ' . ($result ? 'Successful' : 'Failed');
        echo '</div>';
    } else {
        echo '<div class="alert alert-warning">';
        echo 'No issues detected that require fixing.';
        echo '</div>';
    }
    
    // Final check
    $final_status = duitku_membership::has_active_membership($userid);
    
    echo '<h4>Status after fixes:</h4>';
    echo '<p>Membership active: ' . ($final_status ? 'Yes' : 'No') . '</p>';
    echo '</div>';
    
    echo '<a href="' . new moodle_url('/enrol/duitku/verify_membership.php', ['userid' => $userid]) . '" class="btn btn-primary">Refresh status</a>';

} else {
    // Show membership status and available actions
    $has_membership = duitku_membership::has_active_membership($userid);
    
    if ($has_membership) {
        $expiry = duitku_membership::get_membership_expiry($userid);
        echo '<div class="alert alert-success">';
        echo '<p><strong>Membership status:</strong> Active</p>';
        echo '<p><strong>Expires:</strong> ' . userdate($expiry) . '</p>';
        echo '</div>';
    } else {
        echo '<div class="alert alert-warning">';
        echo '<p><strong>Membership status:</strong> Not active</p>';
        echo '</div>';
    }
    
    // Show transactions
    $transactions = $DB->get_records_sql(
        "SELECT * FROM {enrol_duitku_transactions}
         WHERE userid = :userid
         AND payment_type = :payment_type
         ORDER BY id DESC",
        [
            'userid' => $userid,
            'payment_type' => duitku_status_codes::PAYMENT_TYPE_MEMBERSHIP
        ]
    );
    
    if ($transactions) {
        echo '<h3>Membership Transactions</h3>';
        echo '<table class="table table-striped">';
        echo '<thead><tr><th>ID</th><th>Reference</th><th>Status</th><th>Amount</th><th>Date</th></tr></thead>';
        echo '<tbody>';
        
        foreach ($transactions as $t) {
            echo '<tr>';
            echo '<td>' . $t->id . '</td>';
            echo '<td>' . $t->reference . '</td>';
            echo '<td>' . $t->payment_status . '</td>';
            echo '<td>' . number_format($t->amount, 0, ',', '.') . '</td>';
            echo '<td>' . userdate($t->payment_time) . '</td>';
            echo '</tr>';
        }
        
        echo '</tbody></table>';
    } else {
        echo '<div class="alert alert-info">No membership transactions found.</div>';
    }
    
    // Show membership records
    $records = $DB->get_records_sql(
        "SELECT * FROM {enrol_duitku_membership}
         WHERE userid = :userid
         AND payment_type = :payment_type
         ORDER BY id DESC",
        [
            'userid' => $userid,
            'payment_type' => duitku_status_codes::PAYMENT_TYPE_MEMBERSHIP
        ]
    );
    
    if ($records) {
        echo '<h3>Membership Records</h3>';
        echo '<table class="table table-striped">';
        echo '<thead><tr><th>ID</th><th>Status</th><th>Purchase Date</th><th>Expiry Date</th><th>Processed</th></tr></thead>';
        echo '<tbody>';
        
        foreach ($records as $r) {
            echo '<tr>';
            echo '<td>' . $r->id . '</td>';
            echo '<td>' . $r->payment_status . '</td>';
            echo '<td>' . userdate($r->purchase_time) . '</td>';
            echo '<td>' . userdate($r->expiry_time) . '</td>';
            echo '<td>' . ($r->processed ? 'Yes' : 'No') . '</td>';
            echo '</tr>';
        }
        
        echo '</tbody></table>';
    } else {
        echo '<div class="alert alert-info">No membership records found.</div>';
    }
    
    // Show form for actions
    echo '<h3>Actions</h3>';
    echo '<p>';
    echo '<a href="' . new moodle_url('/enrol/duitku/verify_membership.php', ['userid' => $userid, 'action' => 'fix', 'confirm' => 1]) . '" class="btn btn-warning">Fix membership issues</a> ';
    echo '</p>';
}

// Display footer
echo $OUTPUT->footer();

/**
 * Simple form class
 */
class simple_form {
    protected $url;
    protected $method;
    protected $fields;
    
    public function __construct($url, $method = 'get') {
        $this->url = $url;
        $this->method = $method;
        $this->fields = array();
    }
    
    public function add_field($name, $label, $type, $cleantype, $extras = '') {
        $this->fields[] = array(
            'name' => $name,
            'label' => $label,
            'type' => $type,
            'cleantype' => $cleantype,
            'extras' => $extras
        );
    }
    
    public function display() {
        echo '<form method="' . $this->method . '" action="' . $this->url . '">';
        echo '<div class="form-group">';
        
        foreach ($this->fields as $field) {
            echo '<label for="' . $field['name'] . '">' . $field['label'] . '</label> ';
            echo '<input type="' . $field['type'] . '" name="' . $field['name'] . '" id="' . $field['name'] . '" ' . $field['extras'] . ' class="form-control">';
        }
        
        echo '</div>';
        echo '<div class="form-group">';
        echo '<input type="submit" class="btn btn-primary" value="Submit">';
        echo '</div>';
        echo '</form>';
    }
}
