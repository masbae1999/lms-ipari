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
 * Contains all the strings used in the plugin.
 * @package   enrol_duitku
 * @copyright 2022 Michael David <mikedh2612@gmail.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

$string['pluginname'] = 'Duitku Payment';
$string['pluginname_desc'] = 'The Duitku module allows you to set up paid courses.  If the cost for any course is zero, then students are not asked to pay for entry.  There is a site-wide cost that you set here as a default for the whole site and then a course setting that you can set for each course individually. The course cost overrides the site cost.';

// Membership strings
$string['membership'] = 'Yearly Membership';
$string['membership_desc'] = 'Yearly membership allows access to all paid courses on the site.';
$string['membership_price'] = 'Membership price';
$string['membership_price_desc'] = 'The price for yearly membership in IDR';
$string['membership_settings'] = 'Membership Settings';
$string['membership_dashboard'] = 'Membership Dashboard';
$string['membership_dashboard_title'] = 'Membership Dashboard';
$string['annual_membership'] = 'Annual Membership';
$string['membership_role_name'] = 'Penyuluh Agama';
$string['expires_on'] = 'Expires on:';
$string['days_remaining'] = 'days remaining';
$string['renewal_notice'] = 'Your membership is expiring soon. Renew now to maintain access to all courses.';
$string['renew_now'] = 'Renew Membership';
$string['subscribe_now'] = 'Subscribe Now';
$string['active'] = 'Active';
$string['expired'] = 'Expired';
$string['not_subscribed'] = 'Not Subscribed';
$string['membership_active'] = 'Membership Active';
$string['membership_expires'] = 'Expires:';
$string['membership_subscribe'] = 'Annual Membership Subscription';
$string['membership_benefits'] = 'Benefits:';
$string['membership_benefit1'] = 'Access to all paid courses';
$string['membership_benefit2'] = 'New courses automatically added';
$string['membership_benefit3'] = 'Special access to member resources';

// Verification and troubleshooting tools
$string['verify_membership_title'] = 'Verify Membership Status';
$string['verify_membership_heading'] = 'Membership Status Verification Tool';
$string['verify_payment'] = 'Verify Payment Status';
$string['user'] = 'User ID';
$string['fix_membership_issues'] = 'Fix Membership Issues';

// Transaction verification strings
$string['verify_transaction'] = 'Verify Transaction';
$string['verify'] = 'Verify';
$string['reference_code'] = 'Reference Code';
$string['transaction_not_found'] = 'Transaction not found';
$string['back_to_dashboard'] = 'Back to Dashboard';
$string['not_your_transaction'] = 'This transaction does not belong to your account';
$string['transaction_details'] = 'Transaction Details';
$string['merchantorder_id'] = 'Merchant Order ID';
$string['amount'] = 'Amount';
$string['payment_time'] = 'Payment Time';
$string['payment_status'] = 'Payment Status';
$string['payment_type'] = 'Payment Type';
$string['status_success'] = 'Success';
$string['status_pending'] = 'Pending';
$string['status_failed'] = 'Failed';
$string['course_enrollment'] = 'Course Enrollment';
$string['recent_logs'] = 'Recent Transaction Logs';
$string['time'] = 'Time';
$string['type'] = 'Type';
$string['status'] = 'Status';
$string['active_membership'] = 'You have an active membership';
$string['no_active_membership'] = 'You do not have an active membership';
$string['verify_transaction_explanation'] = 'If you\'ve completed payment but your membership is not active, you can check the status again:';
$string['check_status_now'] = 'Check Status Now';
$string['membership_activated'] = 'Your membership has been activated successfully';
$string['membership_check_failed'] = 'Payment verified, but membership activation failed. Please contact support.';
$string['course_enrollment_transaction'] = 'This is a course enrollment transaction, not a membership payment';
$string['transaction_status_error'] = 'Transaction status from Duitku: {$a}';
$string['thank_you_membership'] = 'Thank You for Your Membership';
$string['membership_payment'] = 'Membership Payment';
$string['membership_payment_success'] = 'Your membership payment was successful. You now have access to all paid courses.';
$string['membership_payment_pending'] = 'Your membership payment is being processed. Please wait a few moments or check your status later.';
$string['gotodashboard'] = 'Go to Dashboard';
$string['view_benefits'] = 'View membership details';
$string['per_year'] = 'per year';
$string['membership_extended'] = 'Membership successfully extended';
$string['membership_revoked'] = 'Membership successfully revoked';
$string['membership_created'] = 'Membership successfully created';
$string['error_creating_membership'] = 'Error creating membership';
$string['error_user_mismatch'] = 'User mismatch error';
$string['membership_status'] = 'Membership Status';

// Auto-enrollment strings
$string['auto_enroll_members'] = 'Auto-enroll members';
$string['auto_enrollment_complete'] = 'Auto-enrollment complete for {$a} members';
$string['task_expired_memberships'] = 'Process expired memberships and auto-enrollments';
$string['membership_status'] = 'Membership status';
$string['active'] = 'Active';
$string['expired'] = 'Expired';
$string['membership_none'] = 'No membership';
$string['expires_on'] = 'Expires on: {$a}';
$string['membership_never'] = 'Never purchased';
$string['subscribe'] = 'Subscribe';
$string['renew_membership'] = 'Renew Membership';
$string['membership_benefits_title'] = 'Benefits of Membership';
$string['benefit_all_courses'] = 'Access to all paid courses';
$string['benefit_special_role'] = 'Special "Penyuluh Agama" status';
$string['benefit_auto_enroll'] = 'Automatic enrollment in new courses';
$string['benefit_support_site'] = 'Priority support';
$string['subscribe_now'] = 'Subscribe Now';
$string['thank_you_membership'] = 'Thank you for subscribing to our yearly membership!';
$string['membership_payment_success'] = 'Your membership payment was successful. You now have access to all paid courses.';
$string['membership_payment_pending'] = 'Your membership payment is pending. Once completed, you will have access to all paid courses.';
$string['membership_dashboard_title'] = 'IPARI Membership';
$string['days_remaining'] = 'days remaining';
$string['auto_enroll_members'] = 'Auto-enroll members in paid courses';
$string['membership_payment'] = 'Membership Payment';
$string['membership_return'] = 'Membership Payment Return';
$string['checking_status'] = 'Checking payment status...';
$string['checking_payment'] = 'Checking payment status, page will refresh in:';
$string['gotodashboard'] = 'Go to Dashboard';
$string['renewal_notice'] = 'Your membership is expiring soon. Renew now to maintain uninterrupted access.';
$string['error_already_subscribed'] = 'You already have an active membership.';
$string['error_user_mismatch'] = 'User ID mismatch. Please try again.';
$string['continue'] = 'Continue';
$string['membership_title'] = 'Annual Membership';
$string['membership_heading'] = 'Annual Membership Subscription';
$string['membership_annual'] = 'Annual Membership';
$string['per_year'] = 'per year';
$string['active_membership'] = 'Your membership is active';
$string['no_active_membership'] = 'You don\'t have an active membership';
$string['can_renew_now'] = 'You can renew your membership now to extend it.';
$string['membership_product_name'] = 'Annual Membership - Penyuluh Agama';
$string['payment_error'] = 'An error occurred while processing your payment. Please try again.';
$string['payment_plugin_disabled'] = 'Duitku payment plugin is disabled.';
$string['revoke'] = 'Revoke';
$string['sync'] = 'Sync courses';

// Missing transaction verification strings
$string['verify_transaction'] = 'Verify Transaction';
$string['transaction_not_found'] = 'Transaction not found';
$string['not_your_transaction'] = 'This transaction does not belong to your account';
$string['transaction_details'] = 'Transaction Details';
$string['merchantorder_id'] = 'Merchant Order ID';
$string['status_success'] = 'Success';
$string['status_pending'] = 'Pending';
$string['status_failed'] = 'Failed';
$string['recent_logs'] = 'Recent Logs';
$string['time'] = 'Time';
$string['type'] = 'Type';
$string['verify_transaction_explanation'] = 'If you\'ve completed payment but your status hasn\'t updated, you can check again:';
$string['check_status_now'] = 'Check Status Now';
$string['membership_activated'] = 'Your membership has been activated successfully';
$string['membership_check_failed'] = 'Payment verified, but membership activation failed. Please contact support.';
$string['course_enrollment_transaction'] = 'This is a course enrollment transaction';
$string['transaction_status_error'] = 'Transaction status from Duitku: {$a}';
$string['reference_code'] = 'Reference Code';

// Admin dashboard strings
$string['membership_dashboard'] = 'Membership Dashboard';
$string['active_members'] = 'Active Members';
$string['expiring_soon'] = 'Expiring Within 30 Days';
$string['new_this_month'] = 'New This Month';
$string['revenue_this_month'] = 'Revenue This Month';
$string['extend'] = 'Extend';
$string['sync_all_members'] = 'Sync Members to Courses';
$string['advanced_reports'] = 'Advanced Reports';
$string['members_list'] = 'Members List';
$string['enrolled_courses'] = 'Enrolled Courses';
$string['no_members'] = 'No members found';
$string['extend_membership_for'] = 'Extend Membership for {$a}';
$string['current_expiry_date'] = 'Current expiry date';
$string['extend_days'] = 'Extend by (days)';
$string['membership_extended'] = 'Membership extended successfully';
$string['membership_extend_failed'] = 'Failed to extend membership';
$string['sync_complete'] = 'Member synchronization completed';
$string['plugin_not_available'] = 'Duitku enrollment plugin is not available';
$string['auto_enroll_success'] = 'User was successfully enrolled in all paid courses';
$string['dashboard_overview'] = 'Overview';
$string['dashboard_members'] = 'Members';
$string['dashboard_logs'] = 'Logs';
$string['membership_statistics'] = 'Membership Statistics';
$string['membership_settings'] = 'Membership Settings';
$string['edit_settings'] = 'Edit Settings';
$string['membership_logs'] = 'Membership Logs';
$string['log_type'] = 'Log Type';
$string['details'] = 'Details';
$string['expiry_date'] = 'Expiry Date';
$string['sync_all_enrollments'] = 'Sync All Members\' Enrollments';
$string['sync_complete'] = 'Enrolled in {$a} courses';
$string['sync_all_complete'] = 'Enrolled in {$a} courses total';
$string['confirm_extend_membership'] = 'Are you sure you want to extend {$a->name}\'s membership by {$a->months} months?';
$string['confirm_revoke_membership'] = 'Are you sure you want to revoke {$a}\'s membership?';
$string['confirm_sync_all'] = 'Are you sure you want to synchronize all members with all paid courses? This may take some time.';
$string['membership_revoked'] = 'Membership revoked successfully';
$string['membership_revocation_failed'] = 'Failed to revoke membership';
$string['total_revenue'] = 'Total Revenue';

$string['apikey'] = 'API Key';
$string['apikey_desc'] = 'API Key located in the Project website';
$string['assignrole'] = 'Assign role';
$string['call_error'] = 'An error has occured when requesting transaction. Please try again or contact the site admin';
$string['cost'] = 'Enrol cost';
$string['costerror'] = 'The enrolment cost is not numeric';
$string['costorkey'] = 'Please choose one of the following methods of enrolment.';
$string['course_error'] = 'Course not found';
$string['currency'] = 'Currency';
$string['defaultrole'] = 'Default role assignment';
$string['defaultrole_desc'] = 'Select role which should be assigned to users during Duitku enrolments';
$string['duitku:config'] = 'Configure duitku enrol instances';
$string['duitku:manage'] = 'Manage enrolled users';
$string['duitku:unenrol'] = 'Unenrol users from course';
$string['duitku:unenrolself'] = 'Unenrol self from the course';
$string['duitkuaccepted'] = 'Duitku payments accepted';
$string['enrolenddate'] = 'End date';
$string['enrolenddate_help'] = 'If enabled, users can be enrolled until this date only.';
$string['enrolenddaterror'] = 'Enrolment end date cannot be earlier than start date';
$string['enrolperiod'] = 'Enrolment duration';
$string['enrolperiod_desc'] = 'Default length of time that the enrolment is valid. If set to zero, the enrolment duration will be unlimited by default.';
$string['enrolperiod_help'] = 'Length of time that the enrolment is valid, starting with the moment the user is enrolled. If disabled, the enrolment duration will be unlimited.';
$string['enrolstartdate'] = 'Start date';
$string['enrolstartdate_help'] = 'If enabled, users can be enrolled from this date onward only.';
$string['environment'] = 'Environment';
$string['environment_desc'] = 'Configure Duitku endpoint to be sandbox or production';
$string['errdisabled'] = 'The Duitku enrolment plugin is disabled and does not handle payment notifications.';
$string['expiredaction'] = 'Enrolment expiry action';
$string['expiredaction_help'] = 'Select action to carry out when user enrolment expires. Please note that some user data and settings are purged from course during course unenrolment.';
$string['expiry'] = 'Expiry Period';
$string['expiry_desc'] = 'Expiry period for each transaction. Units set in minutes';
$string['mailadmins'] = 'Notify admin';
$string['mailstudents'] = 'Notify students';
$string['mailteachers'] = 'Notify teachers';
$string['mail_logging'] = 'Duitku Logs the email that is sent';
$string['merchantcode'] = 'Merchant Code';
$string['merchantcode_desc'] = 'Merchant code located in the Project website';
$string['nocost'] = 'There is no cost associated with enrolling in this course!';
$string['payment_expirations'] = 'Duitku checks for expired transaction in database';
$string['payment_not_exist'] = 'Transaction does not exist or has not been saved. Please create a new transaction';
$string['payment_cancelled'] = 'Transaction cancelled. Please create a new transaction';
$string['payment_paid'] = 'Transaction paid succesfully. Please wait a moment and refresh the page again.';
$string['pending_message'] = 'User has not completed payment yet';
$string['sendpaymentbutton'] = 'Pay via Duitku';
$string['status'] = 'Allow Duitku enrolments';
$string['status_desc'] = 'Allow users to use Duitku to enrol into a course by default.';
$string['transactions'] = 'Duitku Transactions';
$string['user_return'] = 'User has returned from redirect page';

$string['duitku_request_log'] = 'Duitku Enrol Plugin Log';
$string['log_request_transaction'] = 'Requesting a transaction to Duitku';
$string['log_request_transaction_response'] = 'Duitku response to Request Transaction';
$string['log_check_transaction'] = 'Checking transaction to Duitku';
$string['log_check_transaction_response'] = 'Duitku respose for Checking Transaction';
$string['log_callback'] = 'Received Callback from Duitku. Affected student should be enrolled';

$string['environment:production'] = 'Production';
$string['environment:sandbox'] = 'Sandbox';

$string['return_header'] = '<h2>Pending Transaction</h2>';
$string['return_sub_header'] = 'Course name : {$a->fullname}<br />';
$string['return_body'] = 'If you have already paid, wait a few moments then check again if you are already enrolled. <br /> We kept your payment <a href="{$a->reference}">here</a> in case you would like to return.';

$string['admin_email'] = 'Email to Admin on Enrolment';
$string['admin_email_desc'] = 'Fill with HTML format. Leave blank for default template. <br /> Use "$courseShortName" to display the enrolled course short name, <br /> "$studentUsername" to display enrolled student username, <br /> "$courseFullName" to display the enrolled course full name, <br /> "$amount" to get the amount payed during enrolment, "$adminUsername" to get the admin username, "$teacherName" to get the teacher username. (All without quotation marks).';
$string['admin_email_template_header'] = '<h1>New Enrolment in {$a->shortname}</h1><br />';
$string['admin_email_template_greeting'] = '<p>Hello {$a->adminUsername}!</p><br />';
$string['admin_email_template_body'] = '<p>{$a->studentUsername} has successfully payed {$a->amount} and enrolled in the {$a->courseFullName} course via Duitku Enrolment Plugin</p>';

$string['teacher_email'] = 'Email to Teacher on Enrolment';
$string['teacher_email_desc'] = 'Fill with HTML format. Leave blank for default template. <br /> Use "$courseShortName" to display the enrolled course short name, <br /> "$studentUsername" to display enrolled student username, <br /> "$courseFullName" to display the enrolled course full name, <br /> "$amount" to get the amount payed during enrolment, "$adminUsername" to get the admin username, "$teacherName" to get the teacher username. (All without quotation marks).';
$string['teacher_email_template_header'] = '<h1>New Enrolment in {$a->shortname}</h1><br />';
$string['teacher_email_template_greeting'] = '<p>Hello {$a->teachername}!</p><br />';
$string['teacher_email_template_body'] = '<p>{$a->studentUsername} has successfully payed {$a->amount} and enrolled in the {$a->courseFullName} course via Duitku Enrolment Plugin</p>';

$string['student_email'] = 'Email to Student on Enrolment';
$string['student_email_desc'] = 'Fill with HTML format. Leave blank for default template. <br /> Use "$courseShortName" to display the enrolled course short name, <br /> "$studentUsername" to display enrolled student username, <br /> "$courseFullName" to display the enrolled course full name, <br /> "$amount" to get the amount payed during enrolment, "$adminUsername" to get the admin username, "$teacherName" to get the teacher username. (All without quotation marks).';
$string['student_email_template_header'] = '<h1>Enrolment Successful</h1>';
$string['student_email_template_greeting'] = '<p>Hello {$a->studentUsername},</p><br /><p>Welcome to {$a->courseFullName}!</p><br />';
$string['student_email_template_body'] = '<p>Your payment of {$a->amount} using Duitku has been successful. Enjoy your course!</p><br/>';

$string['privacy:metadata:enrol_duitku:enrol_duitku'] = 'Transaction data for the Duitku Payment Gateway Plugin.';
$string['privacy:metadata:enrol_duitku:enrol_duitku:userid'] = 'The ID of the user making requesting a transaction';
$string['privacy:metadata:enrol_duitku:enrol_duitku:courseid'] = 'The ID of the course being requested';
$string['privacy:metadata:enrol_duitku:enrol_duitku:instanceid'] = 'The instance ID of the course being requested';
$string['privacy:metadata:enrol_duitku:enrol_duitku:reference'] = 'Reference number received from Duitku.';
$string['privacy:metadata:enrol_duitku:enrol_duitku:timestamp'] = 'Timestamp of when the transaction was requested';
$string['privacy:metadata:enrol_duitku:enrol_duitku:signature'] = 'Signature used to verify the transaction';
$string['privacy:metadata:enrol_duitku:enrol_duitku:merchant_order_id'] = 'The order id used to identify the transaction';
$string['privacy:metadata:enrol_duitku:enrol_duitku:receiver_id'] = 'The receiver user id. Usually the admin';
$string['privacy:metadata:enrol_duitku:enrol_duitku:receiver_email'] = 'The receiver email. Usually the admin';
$string['privacy:metadata:enrol_duitku:enrol_duitku:payment_status'] = 'Transaction Payment Status.';
$string['privacy:metadata:enrol_duitku:enrol_duitku:pending_reason'] = 'The reason for the payment status';
$string['privacy:metadata:enrol_duitku:enrol_duitku:timeupdated'] = 'The time this specific transaction is updated';
$string['privacy:metadata:enrol_duitku:enrol_duitku:expiryperiod'] = 'The expiry period for this specific transaction';
$string['privacy:metadata:enrol_duitku:enrol_duitku:referenceurl'] = 'The reference link for when user wants to go back to a previous transaction.';
$string['privacy:metadata:enrol_duitku:duitku_com'] = 'Duitku Payment Gateway plugin sends user data from Moodle to Duitku.';
$string['privacy:metadata:enrol_duitku:duitku_com:merchantcode'] = 'Duitku Merchant Code';
$string['privacy:metadata:enrol_duitku:duitku_com:apikey'] = 'Duitku API Key';
$string['privacy:metadata:enrol_duitku:duitku_com:signature'] = 'Signature generated to verify a transaction';
$string['privacy:metadata:enrol_duitku:duitku_com:merchant_order_id'] = 'The order ID generated per order';
$string['privacy:metadata:enrol_duitku:duitku_com:paymentAmount'] = 'The cost of the course requested for transaction';
$string['privacy:metadata:enrol_duitku:duitku_com:username'] = 'Username of the user requesting a transaction';
$string['privacy:metadata:enrol_duitku:duitku_com:first_name'] = 'First name of the user requesting a transaction';
$string['privacy:metadata:enrol_duitku:duitku_com:last_name'] = 'Last name of the user requesting a transaction';
$string['privacy:metadata:enrol_duitku:duitku_com:address'] = 'Address of the user requesting a transaction';
$string['privacy:metadata:enrol_duitku:duitku_com:city'] = 'City of the user requesting a transaction';
$string['privacy:metadata:enrol_duitku:duitku_com:email'] = 'Email of the user requesting a transaction';
$string['privacy:metadata:enrol_duitku:duitku_com:country'] = 'Country of the user requesting a transaction';
$string['paymentcompleted'] = 'Payment completed';
