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
 * Controls the version for the Duitku enrolment plugin
 *
 * @package   enrol_duitku
 * @copyright 2022 Michael David <mikedh2612@gmail.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @var stdClass $plugin
 */

defined('MOODLE_INTERNAL') || die();

// Reference https://docs.moodle.org/dev/version.php

$plugin->version    = 2025051500; // Format: YYYYMMDDXX (XX is an incremental number)
$plugin->requires   = 2022112800; // Moodle Version 4.1 (minimum required for this plugin)
$plugin->maturity   = MATURITY_STABLE;
$plugin->release    = '1.1.0';
$plugin->component  = 'enrol_duitku';
$plugin->cron       = 60;
