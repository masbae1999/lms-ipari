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
 * Annual membership subscription handler
 *
 * @package   enrol_duitku
 * @copyright 2025 IPARI
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

use enrol_duitku\duitku_helper;
use enrol_duitku\duitku_membership;
use enrol_duitku\duitku_status_codes;
use enrol_duitku\duitku_mathematical_constants;

require("../../config.php");
require_login(null, false);
require_once("{$CFG->dirroot}/enrol/duitku/lib.php");
if ($USER->id == 1) {
     redirect(new moodle_url('/login/index.php'));
}

// Get parameters
$action = optional_param('action', '', PARAM_ALPHA);
$returnurl = new moodle_url('/');

// Page setup
$PAGE->set_context(context_system::instance());
$PAGE->set_url('/enrol/duitku/membership.php');
$PAGE->set_pagelayout('standard');
$PAGE->set_title(get_string('membership', 'enrol_duitku'));
$PAGE->set_heading(get_string('membership', 'enrol_duitku'));

// JavaScript for countdown timer
$PAGE->requires->js_init_code("
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

// Handle actions
switch ($action) {
    case 'subscribe': // Process membership subscription
        // Check if user already has active membership
        if (duitku_membership::has_active_membership($USER->id)) {
            redirect(
                $returnurl,
                get_string('error_already_subscribed', 'enrol_duitku'),
                null,
                \core\output\notification::NOTIFY_INFO
            );
            exit;
        }
        
        $price = get_config('enrol_duitku', 'membership_price');
        if (empty($price)) {
            $price = duitku_membership::DEFAULT_MEMBERSHIP_PRICE;
        }
        
        // Create Duitku payment parameters
        $merchantcode = get_config('enrol_duitku', 'merchantcode');
        $apikey = get_config('enrol_duitku', 'apikey');
        $environment = get_config('enrol_duitku', 'environment');
        $expiryperiod = get_config('enrol_duitku', 'expiry');
        
        // Create merchant order ID (unique per transaction)
        $timestamp = time();
        $merchantorderid = "MBRS-{$USER->id}-{$timestamp}";
        
        // Set up return and callback URLs
        $callbackurl = "$CFG->wwwroot/enrol/duitku/membership_callback.php";
        $returnurl = "$CFG->wwwroot/enrol/duitku/membership_return.php?merchantOrderId={$merchantorderid}&userid={$USER->id}";
        
        // Set product details
        $productdetails = get_string('membership', 'enrol_duitku');
        
        // Prepare parameters for Duitku API
        $params = [
            'paymentAmount' => (int)$price,
            'merchantOrderId' => $merchantorderid,
            'productDetails' => $productdetails,
            'customerVaName' => $USER->username,
            'merchantUserInfo' => $USER->username,
            'email' => $USER->email,
            'itemDetails' => [
                [
                    'name' => $productdetails,
                    'price' => (int)$price,
                    'quantity' => 1
                ]
            ],
            'customerDetail' => [
                'firstName' => $USER->firstname,
                'lastName' => $USER->lastname,
                'email' => $USER->email,
            ],
            'callbackUrl' => $callbackurl,
            'returnUrl' => $returnurl,
            'expiryPeriod' => (int)$expiryperiod
        ];
        
        $paramsstring = json_encode($params);
        $currenttimestamp = round(microtime(true) * duitku_mathematical_constants::SECOND_IN_MILLISECONDS);
        
        $duitkuhelper = new duitku_helper($merchantcode, $apikey, $merchantorderid, $environment);
        $requestdata = $duitkuhelper->create_transaction($paramsstring, $currenttimestamp, context_system::instance());
        $request = json_decode($requestdata['request']);
        $httpcode = $requestdata['httpCode'];
        
        if ($httpcode == 200) {
            // Store transaction data for reference
            $transaction = new \stdClass();
            $transaction->userid = $USER->id;
            $transaction->reference = $request->reference;
            $transaction->payment_type = duitku_status_codes::PAYMENT_TYPE_MEMBERSHIP;
            $transaction->payment_status = duitku_status_codes::CHECK_STATUS_PENDING;
            $transaction->amount = $price;
            $transaction->payment_time = $currenttimestamp;
            $transaction->merchant_order_id = $merchantorderid;
            
            $DB->insert_record('enrol_duitku_transactions', $transaction);
            
            // Redirect to Duitku payment page
            redirect($request->paymentUrl);
            exit;
        } else {
            // Handle error
            redirect(
                $returnurl,
                get_string('call_error', 'enrol_duitku'),
                null,
                \core\output\notification::NOTIFY_ERROR
            );
            exit;
        }
        break;
        
    case 'renew': // Process membership renewal
        // Similar to subscribe but for renewal - redirect to subscription process
        redirect(new moodle_url('/enrol/duitku/membership.php', ['action' => 'subscribe']));
        break;
        
    default: // Display membership information page
        echo $OUTPUT->header();
        
        $hasmembership = duitku_membership::has_active_membership($USER->id);
        $membershipexpiry = duitku_membership::get_membership_expiry($USER->id);
        
        $price = get_config('enrol_duitku', 'membership_price');
        if (empty($price)) {
            $price = duitku_membership::DEFAULT_MEMBERSHIP_PRICE;
        }
        
        // Format price as Indonesian Rupiah
        $formattedprice = 'Rp ' . number_format($price, 0, ',', '.');
        
        // Display membership information
        echo '<div class="membership-page">';
        echo '<div class="membership-info">';
        
        if ($hasmembership) {
            // Show active membership details
            $now = time();
            $daysremaining = ceil(($membershipexpiry - $now) / (60 * 60 * 24));
            $expirydateformatted = userdate($membershipexpiry, get_string('strftimedate', 'langconfig'));
            
            echo '<div class="alert alert-success">';
            echo '<h4><i class="fa fa-check-circle"></i> ' . get_string('membership_active', 'enrol_duitku') . '</h4>';
            echo '<p>' . get_string('membership_expires', 'enrol_duitku') . ' ' . $expirydateformatted;
            echo ' (' . $daysremaining . ' ' . get_string('days_remaining', 'enrol_duitku') . ')</p>';
            echo '</div>';
            
            if ($daysremaining < 30) {
                // Show renewal button if less than 30 days remaining
                echo '<div class="membership-renewal">';
                echo '<p>' . get_string('renewal_notice', 'enrol_duitku') . '</p>';
                $renewurl = new moodle_url('/enrol/duitku/membership.php', ['action' => 'renew']);
                echo '<a href="' . $renewurl . '" class="btn btn-primary">' . get_string('renew_now', 'enrol_duitku') . '</a>';
                echo '</div>';
            }
            
        } else {
            // Show subscription info
            echo '<h3>' . get_string('membership_subscribe', 'enrol_duitku') . '</h3>';
            echo '<div class="membership-price-info">';
            echo '<div class="price-tag">' . $formattedprice . ' / ' . get_string('year') . '</div>';
            echo '</div>';
            
            echo '<div class="membership-benefits">';
            echo '<h4>' . get_string('membership_benefits', 'enrol_duitku') . '</h4>';
            echo '<ul class="fa-ul">';
            echo '<li><span class="fa-li"><i class="fa fa-check"></i></span>' . get_string('membership_benefit1', 'enrol_duitku') . '</li>';
            echo '<li><span class="fa-li"><i class="fa fa-check"></i></span>' . get_string('membership_benefit2', 'enrol_duitku') . '</li>';
            echo '<li><span class="fa-li"><i class="fa fa-check"></i></span>' . get_string('membership_benefit3', 'enrol_duitku') . '</li>';
            echo '</ul>';
            echo '</div>';
            
            // Subscription button
            $subscribeurl = new moodle_url('/enrol/duitku/membership.php', ['action' => 'subscribe']);
            echo '<div class="membership-action">';
            echo '<a href="' . $subscribeurl . '" class="btn btn-lg btn-primary">' . get_string('subscribe_now', 'enrol_duitku') . '</a>';
            echo '</div>';
        }
        
        echo '</div>'; // .membership-info
        
        // Custom CSS
        echo '<style>
            .membership-page {
                max-width: 800px;
                margin: 0 auto;
                padding: 20px;
            }
            .membership-info {
                background-color: #fff;
                border-radius: 8px;
                box-shadow: 0 2px 5px rgba(0,0,0,0.1);
                padding: 25px;
            }
            .membership-benefits {
                margin: 25px 0;
            }
            .membership-benefits ul {
                margin-top: 15px;
            }
            .membership-benefits li {
                margin-bottom: 10px;
                font-size: 16px;
            }
            .price-tag {
                font-size: 32px;
                font-weight: bold;
                color: #e63946;
                text-align: center;
                margin: 20px 0;
            }
            .membership-action {
                text-align: center;
                margin: 30px 0 15px;
            }
            .btn-primary {
                background-color: #e63946;
                border-color: #e63946;
            }
            .btn-primary:hover {
                background-color: #d92638;
                border-color: #d92638;
            }
            .membership-renewal {
                margin-top: 20px;
                padding-top: 20px;
                border-top: 1px solid #eee;
            }
            .fa-check-circle {
                color: #1e8e3e;
                margin-right: 5px;
            }
        </style>';
        
        echo '</div>'; // .membership-page
        
        echo $OUTPUT->footer();
?>
<script>
    console.log("USER ID: <?php echo $USER->id; ?>");
    console.log("USERNAME: '<?php echo $USER->username; ?>'");
    console.log("FULLNAME: '<?php echo fullname($USER); ?>'");
    console.log("IS LOGGED IN: <?php echo isloggedin() ? 'true' : 'false'; ?>");
</script>
<?php
}
