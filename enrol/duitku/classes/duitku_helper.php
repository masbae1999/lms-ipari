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
 *     Stores all the function needed to run the plugin for better readability
 *
 * @package   enrol_duitku
 * @copyright 2022 Michael David <mikedh2612@gmail.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace enrol_duitku;

use curl;

defined('MOODLE_INTERNAL') || die();

/**
 * Stores all reusable functions here.
 *
 * @author  2022 Michael David <mikedh2612@gmail.com>
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class duitku_helper
{

    /**
     * @var string The base API URL
     */
    private $baseurl;

    /**
     * @var string Merchant Code
     */
    private $merchantcode;

    /**
     * @var string Api Key
     */
    private $apikey;

    /**
     * @var string Merchant Order Id
     */
    private $merchantorderid;

    /**
     * @var string Environment
     */
    private $environment;


    /**
     * helper constructor.
     *
     * @param string    $merchantcode       Duitku Merchant Code
     * @param string    $apikey Duitku      API Key.
     * @param string    $merchantorderid    Customly genereted Merchant Order Id at call.php.
     * @param string    $environment        Environment string (sandbox or production).
     */
    public function __construct(string $merchantcode, string $apikey, string $merchantorderid, string $environment)
    {
        $this->merchantcode = $merchantcode;
        $this->apikey = $apikey;
        $this->merchantorderid = $merchantorderid;
        $this->environment = $environment;
        $this->baseurl = $this->environment === 'sandbox' ? 'https://api-sandbox.duitku.com/api/merchant' : 'https://api-prod.duitku.com/api/merchant';
    }

    /**
     * Creates a transaction to Duitku. Logs the request sent to Duitku as well.
     *
     * @param string              $paramsstring     Json encoded parameters array being sent to Duitku
     * @param string              $timestamp        Timestamp in Milliseconds. Not generated in here to synchronize with the time given in the return Url.
     * @param \context            $context          Context needed for request logging (can be context_course or context_system)
     */
    public function create_transaction(string $paramsstring, string $timestamp, \context $context)
    {
        global $USER, $CFG;
        require_once($CFG->libdir . '/filelib.php');

        $url = "{$this->baseurl}/createInvoice";
        $signature = hash('sha256', $this->merchantcode . $timestamp . $this->apikey);

        $curl = new curl();
        $curl->resetopt();
        $url = "{$this->baseurl}/createInvoice";
        $signature = hash('sha256', $this->merchantcode . $timestamp . $this->apikey);

        $curloptheader = [
            'Content-Type: application/json',
            'Content-Length: ' . strlen($paramsstring),
            'x-duitku-signature:' . $signature,
            'x-duitku-timestamp:' . $timestamp,
            'x-duitku-merchantcode:' . $this->merchantcode
        ];
        $curlopt = [
            'CURLOPT_RETURNTRANSFER' => true,
            'CURLOPT_SSL_VERIFYPEER' => false,
            'CURLOPT_HTTPHEADER' => $curloptheader
        ];
        $curl->setopt($curlopt);

        // Log outgoing Request.
        $eventarray = [
            'context' => $context,
            'relateduserid' => $USER->id,
            'other' => [
                'Log Details' => get_string('log_request_transaction', 'enrol_duitku'),
                'sentParams' => $paramsstring,
                'destination' => $url
            ]
        ];
        $this->log_request($eventarray);

        // Execute post.
        $request = $curl->post($url, $paramsstring);
        $httpcode = $curl->info['http_code'];
        $headersize = $curl->info['header_size'];
        $header = substr($request, 0, $headersize);

        // Log incoming response.
        $eventarray = [
            'context' => $context,
            'relateduserid' => $USER->id,
            'other' => [
                'Log Details' => get_string('log_request_transaction_response', 'enrol_duitku'),
                'httpCode' => $httpcode,
                'response' => json_encode($header),
            ]
        ];
        $this->log_request($eventarray);

        // Return data to redirect user to the designated page.
        $returndata = [
            'request' => $request,
            'httpCode' => $httpcode,
        ];
        return $returndata;
    }

    /**
     * Checks the transaction of a user who has just returned from the Duitku Page and logs the request
     * @param \context    $context    Context needed for request logging (can be context_course or context_system)
     */
    public function check_transaction(\context $context)
    {
        global $USER, $CFG;
        require_once($CFG->libdir . '/filelib.php');

        $url = "{$this->baseurl}/transactionStatus";
        $signature = md5($this->merchantcode . $this->merchantorderid . $this->apikey);
        $params = [
            'merchantCode' => $this->merchantcode,
            'merchantOrderId' => $this->merchantorderid,
            'signature' => $signature
        ];
        $paramsstring = json_encode($params);

        // Setup curl.
        $curl = new curl();
        $curl->resetopt();
        $curlopt = [
            'CURLOPT_RETURNTRANSFER' => true,
            'CURLOPT_SSL_VERIFYPEER' => false
        ];
        $curl->setopt($curlopt);
        $curloptheader = [
            'Content-Type: application/json',
            'Content-Length: ' . strlen($paramsstring)
        ];
        $curl->setHeader($curloptheader);

        // Log outgoing request.
        $eventarray = [
            'context' => $context,
            'relateduserid' => $USER->id,
            'other' => [
                'Log Details' => get_string('log_check_transaction', 'enrol_duitku'),
                'sentParams' => $paramsstring,
                'destination' => $url
            ]
        ];
        $this->log_request($eventarray);

        // Execute post.
        $request = $curl->post($url, $paramsstring);
        $httpcode = $curl->info['http_code'];
        $headersize = $curl->info['header_size'];
        $header = substr($request, 0, $headersize);

        // Log incoming response.
        $eventarray = [
            'context' => $context,
            'relateduserid' => $USER->id,
            'other' => [
                'Log Details' => get_string('log_check_transaction_response', 'enrol_duitku'),
                'httpCode' => $httpcode,
                'response' => json_encode($header),
            ]
        ];
        $this->log_request($eventarray);

        $returndata = [
            'request' => $request,
            'httpCode' => $httpcode,
            'url' => $url
        ];
        return $returndata;
    }

    /**
     * Logs any incoming/outgoing requests (including callbacks).
     * @param array    $eventarray    Course context needed for request logging
     */
    public function log_request($eventarray)
    {
        $event = \enrol_duitku\event\duitku_request_log::create($eventarray);
        $event->trigger();
    }
    /**
     * Validates user access to ensure guest users cannot access paid courses
     * Security fix to prevent guest users from accessing paid content
     * 
     * @return void
     */
    public static function validate_access()
    {
        global $USER, $PAGE;

        if (isguestuser() || !isloggedin()) {
            // Redirect to login with session termination
            require_logout();
            redirect(new \moodle_url('/login/index.php'));
        }

        // Only validate course context when we're in a course
        if (isset($PAGE->context) && $PAGE->context->contextlevel == CONTEXT_COURSE) {
            // Additional course-specific validation can be done here if needed
            return true;
        }

        return true;
    }
}
