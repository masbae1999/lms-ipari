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
 * Membership management dashboard for administrators
 *
 * @package   enrol_duitku
 * @copyright 2025 IPARI
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require('../../config.php');
require_once($CFG->libdir.'/adminlib.php');
require_once($CFG->dirroot.'/enrol/duitku/classes/duitku_membership.php');

use enrol_duitku\duitku_membership;
use enrol_duitku\duitku_status_codes;

// Only admins can access this page
admin_externalpage_setup('membershipdashboard');

// Process actions
$action = optional_param('action', '', PARAM_ALPHA);
$userid = optional_param('userid', 0, PARAM_INT);
$confirm = optional_param('confirm', 0, PARAM_BOOL);
$extenddays = optional_param('extenddays', 365, PARAM_INT);

if ($action === 'extend' && $userid > 0) {
    // Extend a user's membership
    if ($confirm) {
        if (duitku_membership::create_or_extend_membership($userid, $extenddays)) {
            redirect(
                new moodle_url('/enrol/duitku/membership_dashboard.php'),
                get_string('membership_extended', 'enrol_duitku'),
                null,
                \core\output\notification::NOTIFY_SUCCESS
            );
        } else {
            redirect(
                new moodle_url('/enrol/duitku/membership_dashboard.php'),
                get_string('membership_extend_failed', 'enrol_duitku'),
                null,
                \core\output\notification::NOTIFY_ERROR
            );
        }
    } else {
        // Show confirmation form
        $user = $DB->get_record('user', array('id' => $userid), '*', MUST_EXIST);
        $membershipexpiry = duitku_membership::get_membership_expiry($userid);
        
        echo $OUTPUT->header();
        echo $OUTPUT->heading(get_string('extend_membership_for', 'enrol_duitku', fullname($user)));
        
        if ($membershipexpiry) {
            $expirydateformatted = userdate($membershipexpiry, get_string('strftimedate', 'langconfig'));
            echo '<p>' . get_string('current_expiry_date', 'enrol_duitku') . ': ' . $expirydateformatted . '</p>';
        }
        
        // Show confirmation form
        echo '<div class="extend-form">';
        echo '<form action="membership_dashboard.php" method="post">';
        echo '<input type="hidden" name="action" value="extend">';
        echo '<input type="hidden" name="userid" value="' . $userid . '">';
        echo '<input type="hidden" name="confirm" value="1">';
        echo '<div class="form-group">';
        echo '<label for="extenddays">' . get_string('extend_days', 'enrol_duitku') . '</label>';
        echo '<input type="number" id="extenddays" name="extenddays" value="365" min="1" max="3650" class="form-control">';
        echo '</div>';
        echo '<div class="form-submit">';
        echo '<input type="submit" value="' . get_string('confirm') . '" class="btn btn-primary">';
        echo '<a href="membership_dashboard.php" class="btn btn-secondary">' . get_string('cancel') . '</a>';
        echo '</div>';
        echo '</form>';
        echo '</div>';
        
        echo $OUTPUT->footer();
        exit;
    }
} else if ($action === 'syncmembers') {
    // Manually sync all members to courses
    $plugin = enrol_get_plugin('duitku');
    
    if ($plugin) {
        $task = new \enrol_duitku\task\auto_enroll_members();
        $task->execute();
        
        redirect(
            new moodle_url('/enrol/duitku/membership_dashboard.php'),
            get_string('sync_complete', 'enrol_duitku'),
            null,
            \core\output\notification::NOTIFY_SUCCESS
        );
    } else {
        redirect(
            new moodle_url('/enrol/duitku/membership_dashboard.php'),
            get_string('plugin_not_available', 'enrol_duitku'),
            null,
            \core\output\notification::NOTIFY_ERROR
        );
    }
}

// Get statistics
$membercounts = duitku_membership::get_membership_statistics();

// Get transaction data for chart
$transactions = duitku_membership::get_transaction_data(30); // Last 30 days

// Get members list
$members = duitku_membership::get_members();

// Page output
echo $OUTPUT->header();
echo $OUTPUT->heading(get_string('membership_dashboard', 'enrol_duitku'));

// Display membership statistics
echo '<div class="membership-stats">';
echo '<div class="row">';

// Active members
echo '<div class="col-md-3">';
echo '<div class="info-box bg-success">';
echo '<span class="info-box-icon"><i class="fa fa-users"></i></span>';
echo '<div class="info-box-content">';
echo '<span class="info-box-text">' . get_string('active_members', 'enrol_duitku') . '</span>';
echo '<span class="info-box-number">' . $membercounts->active . '</span>';
echo '</div>';
echo '</div>';
echo '</div>';

// Expiring soon
echo '<div class="col-md-3">';
echo '<div class="info-box bg-warning">';
echo '<span class="info-box-icon"><i class="fa fa-clock"></i></span>';
echo '<div class="info-box-content">';
echo '<span class="info-box-text">' . get_string('expiring_soon', 'enrol_duitku') . '</span>';
echo '<span class="info-box-number">' . $membercounts->expiring . '</span>';
echo '</div>';
echo '</div>';
echo '</div>';

// New this month
echo '<div class="col-md-3">';
echo '<div class="info-box bg-info">';
echo '<span class="info-box-icon"><i class="fa fa-plus-circle"></i></span>';
echo '<div class="info-box-content">';
echo '<span class="info-box-text">' . get_string('new_this_month', 'enrol_duitku') . '</span>';
echo '<span class="info-box-number">' . $membercounts->new . '</span>';
echo '</div>';
echo '</div>';
echo '</div>';

// Revenue this month
echo '<div class="col-md-3">';
echo '<div class="info-box bg-primary">';
echo '<span class="info-box-icon"><i class="fa fa-money-bill"></i></span>';
echo '<div class="info-box-content">';
echo '<span class="info-box-text">' . get_string('revenue_this_month', 'enrol_duitku') . '</span>';
echo '<span class="info-box-number">Rp ' . number_format($membercounts->revenue, 0, ',', '.') . '</span>';
echo '</div>';
echo '</div>';
echo '</div>';

echo '</div>'; // .row
echo '</div>'; // .membership-stats

// Add admin actions
echo '<div class="admin-actions">';
echo '<a href="membership_dashboard.php?action=syncmembers" class="btn btn-info mr-2">';
echo '<i class="fa fa-sync"></i> ' . get_string('sync_all_members', 'enrol_duitku');
echo '</a>';
echo '<a href="' . new moodle_url('/report/customsql/view.php') . '" class="btn btn-secondary ml-2">';
echo '<i class="fa fa-chart-bar"></i> ' . get_string('advanced_reports', 'enrol_duitku');
echo '</a>';
echo '</div>';

// Display members list
echo '<div class="members-list mt-4">';
echo '<h3>' . get_string('members_list', 'enrol_duitku') . '</h3>';

// Create table
$table = new html_table();
$table->head = [
    get_string('name'),
    get_string('email'),
    get_string('membership_status', 'enrol_duitku'),
    get_string('membership_expires', 'enrol_duitku'),
    get_string('enrolled_courses', 'enrol_duitku'),
    get_string('actions')
];
$table->id = 'membership-users-table';
$table->attributes['class'] = 'table table-striped';
$table->data = [];

// Add users to table
foreach ($members as $member) {
    $userurl = new moodle_url('/user/profile.php', ['id' => $member->id]);
    $namelink = html_writer::link($userurl, fullname($member));
    
    if ($member->expiry_time > time()) {
        $status = html_writer::span(
            get_string('membership_active', 'enrol_duitku'),
            'badge badge-success'
        );
        
        // Calculate days remaining
        $daysremaining = ceil(($member->expiry_time - time()) / (60 * 60 * 24));
        if ($daysremaining < 30) {
            $status .= ' ' . html_writer::span(
                $daysremaining . ' ' . get_string('days_remaining', 'enrol_duitku'),
                'badge badge-warning'
            );
        }
    } else {
        $status = html_writer::span(
            get_string('membership_expired', 'enrol_duitku'),
            'badge badge-danger'
        );
    }
    
    $expirydateformatted = userdate($member->expiry_time, get_string('strftimedate', 'langconfig'));
    
    // Actions
    $actions = html_writer::link(
        new moodle_url('/enrol/duitku/membership_dashboard.php', ['action' => 'extend', 'userid' => $member->id]),
        html_writer::span('<i class="fa fa-clock"></i> ' . get_string('extend', 'enrol_duitku'), 'btn btn-sm btn-primary')
    );
    
    $table->data[] = [
        $namelink,
        $member->email,
        $status,
        $expirydateformatted,
        $member->courses_count,
        $actions
    ];
}

if (empty($members)) {
    $cell = new html_table_cell(get_string('no_members', 'enrol_duitku'));
    $cell->colspan = count($table->head);
    $row = new html_table_row([$cell]);
    $table->data[] = $row;
}

echo html_writer::table($table);
echo '</div>'; // .members-list

// Add custom styles
echo '<style>
    .membership-stats {
        margin-bottom: 20px;
    }
    .info-box {
        min-height: 100px;
        padding: 15px;
        border-radius: 8px;
        margin-bottom: 20px;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        display: flex;
        align-items: center;
    }
    .info-box-icon {
        width: 70px;
        height: 70px;
        border-radius: 8px;
        font-size: 36px;
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: rgba(255,255,255,0.2);
        margin-right: 15px;
    }
    .info-box-content {
        display: flex;
        flex-direction: column;
    }
    .info-box-text {
        font-size: 16px;
        margin-bottom: 5px;
    }
    .info-box-number {
        font-size: 24px;
        font-weight: bold;
    }
    .bg-success {
        background-color: #1e8e3e;
        color: white;
    }
    .bg-warning {
        background-color: #f6c23e;
        color: white;
    }
    .bg-info {
        background-color: #4e73df;
        color: white;
    }
    .bg-primary {
        background-color: #e63946;
        color: white;
    }
    .admin-actions {
        margin: 20px 0;
        text-align: right;
    }
    .btn-info {
        background-color: #4e73df;
        border-color: #4e73df;
    }
    .badge-success {
        background-color: #1e8e3e;
    }
    .badge-warning {
        background-color: #f6c23e;
    }
    .badge-danger {
        background-color: #e74a3b;
    }
    .extend-form {
        max-width: 500px;
        margin: 20px 0;
        background: #f8f9fa;
        padding: 20px;
        border-radius: 8px;
    }
    .form-submit {
        margin-top: 15px;
    }
</style>';

echo $OUTPUT->footer();
