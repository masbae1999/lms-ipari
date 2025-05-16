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
 * Block for displaying IPARI annual membership information.
 *
 * @package    block_duitku_membership
 * @copyright  2025 IPARI
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();
require_once($CFG->dirroot . '/enrol/duitku/classes/duitku_membership.php');
require_once($CFG->dirroot . '/enrol/duitku/classes/duitku_mathematical_constants.php');

use enrol_duitku\duitku_membership;
use enrol_duitku\duitku_status_codes;
use enrol_duitku\duitku_mathematical_constants;

class block_duitku_membership extends block_base {
    /**
     * Initialize block
     */
    public function init() {
        $this->title = get_string('pluginname', 'block_duitku_membership');
    }

    /**
     * Block can be added multiple times
     */
    public function instance_allow_multiple() {
        return false;
    }

    /**
     * Specify which page formats this block can be displayed on
     */
    public function applicable_formats() {
        return [
            'site-index' => true,
            'my' => true,
            'course-index' => true,
            'course-view' => true
        ];
    }

    /**
     * Set custom title if defined in settings
     */
    public function specialization() {
        if (!empty($this->config->title)) {
            $this->title = format_string($this->config->title, true, ['context' => $this->context]);
        } else {
            $this->title = get_string('membership_dashboard_title', 'enrol_duitku');
        }
    }

    /**
     * Generate block content
     */
    public function get_content() {
        global $USER, $CFG;

        if ($this->content !== null) {
            return $this->content;
        }

        $this->content = new stdClass();
        $this->content->text = '';
        $this->content->footer = '';

        // Check if the user is logged in
        if (!isloggedin() || isguestuser()) {
            $this->content->text = $this->get_login_message();
            return $this->content;
        }

        // Render the membership block using the enhanced template
        $this->content->text = $this->render_membership_block();
        return $this->content;
    }

    /**
     * Generate content for users who are not logged in
     */
    private function get_login_message() {
        $message = '<div class="duitku-membership-block guest-view">';
        $message .= '<div class="membership-icon"><i class="fa fa-users fa-3x"></i></div>';
        $message .= '<p>' . get_string('login_to_view', 'block_duitku_membership') . '</p>';
        $message .= '</div>';
        return $message;
    }

    /**
     * Generate block content using the enhanced template
     */
    private function render_membership_block() {
        global $USER, $CFG, $OUTPUT, $DB;
        
        require_once($CFG->dirroot . '/enrol/duitku/classes/duitku_mathematical_constants.php');
        
        // Check for pending membership transactions first
        $pendingtransaction = $DB->get_record_sql(
            "SELECT * FROM {enrol_duitku_transactions} 
             WHERE userid = :userid 
             AND payment_type = :payment_type 
             AND payment_status = :payment_status
             ORDER BY id DESC LIMIT 1",
            [
                'userid' => $USER->id,
                'payment_type' => duitku_status_codes::PAYMENT_TYPE_MEMBERSHIP,
                'payment_status' => duitku_status_codes::PAYMENT_STATUS_PENDING
            ]
        );
        
        // If there's a pending transaction, show pending status
        if ($pendingtransaction) {
            $this->content->text = $this->render_pending_membership($pendingtransaction);
            return $this->content->text;
        }
        
        // Check if user has active membership
        $hasmembership = duitku_membership::has_active_membership($USER->id);
        
        // Prepare data for template
        $data = [
            'hasmembership' => $hasmembership,
            'membershipurl' => new moodle_url('/enrol/duitku/membership.php'),
        ];
        
        // If user has membership, add expiry info
        if ($hasmembership) {
            $expiry = duitku_membership::get_membership_expiry($USER->id);
            if ($expiry) {
                $now = time();
                $daysremaining = ceil(($expiry - $now) / \enrol_duitku\duitku_mathematical_constants::ONE_DAY_IN_SECONDS);
                
                // Get the start date of the membership (approximately)
                $startdate = $this->get_membership_start_time($USER->id);
                if (!$startdate) {
                    $startdate = $expiry - (365 * \enrol_duitku\duitku_mathematical_constants::ONE_DAY_IN_SECONDS);
                }
                
                // Calculate total membership days and percentage remaining
                $totalmembershipdays = max(1, ceil(($expiry - $startdate) / \enrol_duitku\duitku_mathematical_constants::ONE_DAY_IN_SECONDS));
                $percentremaining = min(100, max(0, round(($daysremaining / $totalmembershipdays) * 100)));
                
                // Determine color class based on days remaining
                $colorclass = 'success';
                if ($daysremaining < 30) {
                    $colorclass = 'danger';
                } else if ($daysremaining < 60) {
                    $colorclass = 'warning';
                }
                
                $data['expirydate'] = userdate($expiry, get_string('strftimedate', 'langconfig'));
                $data['daysremaining'] = $daysremaining;
                $data['percentremaining'] = $percentremaining;
                $data['colorclass'] = $colorclass;
                $data['showrenewalnotice'] = ($daysremaining < 30);
            }
        } else {
            // Get membership price from config or use default
            $membershipprice = get_config('enrol_duitku', 'membership_price');
            if (empty($membershipprice)) {
                $membershipprice = duitku_membership::DEFAULT_MEMBERSHIP_PRICE;
            }
            $data['price'] = number_format($membershipprice, 0, ',', '.');
        }
        
        // Render the template
        return $OUTPUT->render_from_template('block_duitku_membership/membership_info_enhanced', $data);
    }
    
    /**
     * Get the start time of the user's membership
     *
     * @param int $userid The user ID
     * @return int|null Timestamp of membership start or null if not found
     */
    private function get_membership_start_time($userid) {
        global $DB;
        
        $sql = "SELECT purchase_time FROM {enrol_duitku_membership} 
                WHERE userid = :userid 
                AND payment_type = :payment_type 
                ORDER BY purchase_time ASC LIMIT 1";
        
        $params = [
            'userid' => $userid,
            'payment_type' => duitku_status_codes::PAYMENT_TYPE_MEMBERSHIP
        ];
        
        $record = $DB->get_record_sql($sql, $params);
        return $record ? (int)$record->purchase_time : null;
    }

    /**
     * Add custom styles for the block
     */
    private function get_custom_styles() {
        $css = '<style>
            .duitku-membership-block {
                padding: 15px;
                border-radius: 8px;
                background-color: #ffffff;
                box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            }
            .duitku-membership-block .membership-icon {
                text-align: center;
                margin-bottom: 15px;
                color: #e63946;
            }
            .duitku-membership-block .membership-status {
                padding: 8px;
                text-align: center;
                border-radius: 4px;
                font-weight: bold;
                margin-bottom: 15px;
            }
            .duitku-membership-block .membership-status.active {
                background-color: #e6f4ea;
                color: #1e8e3e;
            }
            .duitku-membership-block .membership-info {
                display: flex;
                justify-content: space-between;
                font-size: 0.9em;
                margin-top: 5px;
            }
            .duitku-membership-block .membership-price {
                text-align: center;
                font-size: 1.5em;
                font-weight: bold;
                margin: 15px 0;
                color: #e63946;
            }
            .duitku-membership-block .membership-benefits {
                margin: 15px 0;
            }
            .duitku-membership-block .membership-benefits h6 {
                font-weight: bold;
                margin-bottom: 10px;
            }
            .duitku-membership-block .membership-benefits ul {
                list-style: none;
                padding-left: 5px;
            }
            .duitku-membership-block .membership-benefits li {
                margin-bottom: 5px;
            }
            .duitku-membership-block .membership-benefits li i {
                color: #1e8e3e;
                margin-right: 5px;
            }
            .duitku-membership-block .membership-action {
                text-align: center;
                margin-top: 15px;
            }
            .duitku-membership-block .btn-primary {
                background-color: #e63946;
                border-color: #e63946;
            }
            .duitku-membership-block .btn-primary:hover {
                background-color: #d92638;
                border-color: #d92638;
            }
        </style>';
        
        return $css;
    }

    /**
     * Tell Moodle if this block has a configuration page
     */
    public function has_config() {
        return false;
    }

    /**
     * Tell Moodle if this block has instance configuration
     */
    public function instance_allow_config() {
        return true;
    }
    
    /**
     * Render a pending membership payment status
     * 
     * @param object $transaction The pending transaction record
     * @return string HTML for the pending membership status
     */
    private function render_pending_membership($transaction) {
        global $DB;
        
        // Log that we're displaying a pending transaction
        $DB->insert_record('enrol_duitku_log', [
            'timestamp' => time(),
            'log_type' => 'membership_pending',
            'data' => "Showing pending transaction {$transaction->id} for user {$transaction->userid}",
            'status' => 'info'
        ]);
        
        $output = '<div class="membership-icon"><i class="fa fa-clock fa-3x"></i></div>';
        $output .= '<div class="membership-status pending">' . get_string('membership_pending', 'block_duitku_membership') . '</div>';
        $output .= '<p>' . get_string('membership_pending_info', 'block_duitku_membership') . '</p>';
        
        // Add reference code
        $output .= '<div class="membership-reference">';
        $output .= '<strong>' . get_string('reference_code', 'enrol_duitku') . ':</strong> ';
        $output .= $transaction->reference;
        $output .= '</div>';
        
        // Add verification link
        $verifyurl = new moodle_url('/enrol/duitku/verify_transaction.php', ['ref' => $transaction->reference]);
        $output .= '<div class="membership-action">';
        $output .= '<a href="'.$verifyurl.'" class="btn btn-info btn-sm">';
        $output .= get_string('verify_payment', 'enrol_duitku');
        $output .= '</a>';
        $output .= '</div>';
        
        return $output;
    }
}
