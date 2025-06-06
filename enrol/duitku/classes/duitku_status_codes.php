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
 * Contains helper class to work with Duitku Plugin.
 *
 * @package   enrol_duitku
 * @copyright 2022 Michael David <mikedh2612@gmail.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace enrol_duitku;

/**
 * Stores all of the mathematical constants used in the plugin
 *
 * @author  2022 Michael David <mikedh2612@gmail.com>
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class duitku_status_codes {
    /**
     * @var string Duitku has received payment from the user. Could still be waiting for callback
     */
    public const CHECK_STATUS_SUCCESS = '00';

    /**
     * @var string Duitku has not received payment from the user.
     */
    public const CHECK_STATUS_PENDING = '01';

    /**
     * @var string Transaction is canceled. Possibly from being expired.
     */
    public const CHECK_STATUS_CANCELED = '02';
    
    /**
     * @var string Transaction is for regular course enrollment
     */
    public const PAYMENT_TYPE_COURSE = 'course';
    
    /**
     * @var string Transaction is for yearly membership
     */
    public const PAYMENT_TYPE_MEMBERSHIP = 'membership';
    
    /**
     * @var string Payment status success
     */
    public const PAYMENT_STATUS_SUCCESS = '00';
    
    /**
     * @var string Payment status pending
     */
    public const PAYMENT_STATUS_PENDING = '01';
    
    /**
     * @var string Payment status failed
     */
    public const PAYMENT_STATUS_FAILED = '02';
    
    /**
     * @var string Membership is active
     */
    public const MEMBERSHIP_ACTIVE = 'active';
    
    /**
     * @var string Membership has expired
     */
    public const MEMBERSHIP_EXPIRED = 'expired';
}
