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
 *
 * @package   theme_mb2nl
 * @copyright 2017 - 2024 Mariusz Boloz (lmsstyle.com)
 * @license   PHP and HTML: http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later. Other parts: http://themeforest.net/licenses
 *
 */

defined('MOODLE_INTERNAL') || die();

$functions = [
    'theme_mb2nl_course_list' => [
            'classname' => 'theme_mb2nl_external',
            'methodname' => 'set_course_filters',
            'classpath' => 'theme/mb2nl/externallib.php',
            'description' => 'Submit the course list filter form data via ajax',
            'type' => 'read',
            'ajax' => true,
            'loginrequired' => false,
            'capabilities' => '',
            'services' => [MOODLE_OFFICIAL_MOBILE_SERVICE],
    ],
    'theme_mb2nl_course_quickview' => [
            'classname' => 'theme_mb2nl_external',
            'methodname' => 'course_quickview',
            'classpath' => 'theme/mb2nl/externallib.php',
            'description' => 'Get course details in modal window',
            'type' => 'read',
            'ajax' => true,
            'loginrequired' => false,
            'capabilities' => '',
            'services' => [MOODLE_OFFICIAL_MOBILE_SERVICE],
    ],
    'theme_mb2nl_event_details' => [
        'classname' => 'theme_mb2nl_external',
        'methodname' => 'event_details',
        'classpath' => 'theme/mb2nl/externallib.php',
        'description' => 'Submit event details via ajax',
        'type' => 'read',
        'ajax' => true,
        'loginrequired' => false,
        'capabilities' => '',
        'services' => [MOODLE_OFFICIAL_MOBILE_SERVICE],
    ],
    'theme_mb2nl_course_modules' => [
        'classname' => 'theme_mb2nl_external',
        'methodname' => 'course_modules',
        'classpath' => 'theme/mb2nl/externallib.php',
        'description' => 'Get course modules for TOC',
        'type' => 'read',
        'ajax' => true,
        'loginrequired' => false,
        'capabilities' => '',
        'services' => [MOODLE_OFFICIAL_MOBILE_SERVICE],
    ],
    'theme_mb2nl_course_enrolment_modules' => [
        'classname' => 'theme_mb2nl_external',
        'methodname' => 'course_enrolment_modules',
        'classpath' => 'theme/mb2nl/externallib.php',
        'description' => 'Get course modules for TOC on enrolment page',
        'type' => 'read',
        'ajax' => true,
        'loginrequired' => false,
        'capabilities' => '',
        'services' => [MOODLE_OFFICIAL_MOBILE_SERVICE],
    ],
    'theme_mb2nl_course_chome' => [
        'classname' => 'theme_mb2nl_external',
        'methodname' => 'course_chome',
        'classpath' => 'theme/mb2nl/externallib.php',
        'description' => 'Get course chome sidebar',
        'type' => 'read',
        'ajax' => true,
        'loginrequired' => false,
        'capabilities' => '',
        'services' => [MOODLE_OFFICIAL_MOBILE_SERVICE],
    ],
    'theme_mb2nl_course_teacher_list' => [
        'classname' => 'theme_mb2nl_external',
        'methodname' => 'course_teacher_list',
        'classpath' => 'theme/mb2nl/externallib.php',
        'description' => 'Get course teacher list',
        'type' => 'read',
        'ajax' => true,
        'loginrequired' => false,
        'capabilities' => '',
        'services' => [MOODLE_OFFICIAL_MOBILE_SERVICE],
    ],
    'theme_mb2nl_course_filters' => [
        'classname' => 'theme_mb2nl_external',
        'methodname' => 'course_filters',
        'classpath' => 'theme/mb2nl/externallib.php',
        'description' => 'Course filters',
        'type' => 'read',
        'ajax' => true,
        'loginrequired' => false,
        'capabilities' => '',
        'services' => [MOODLE_OFFICIAL_MOBILE_SERVICE],
    ],
    'theme_mb2nl_admin_dashboard' => [
        'classname' => 'theme_mb2nl_external',
        'methodname' => 'admin_dashboard',
        'classpath' => 'theme/mb2nl/externallib.php',
        'description' => 'Custom dashboard for administrators',
        'type' => 'read',
        'ajax' => true,
        'loginrequired' => true,
        'capabilities' => '',
        'services' => [MOODLE_OFFICIAL_MOBILE_SERVICE],
    ],
    'theme_mb2nl_student_dashboard' => [
        'classname' => 'theme_mb2nl_external',
        'methodname' => 'student_dashboard',
        'classpath' => 'theme/mb2nl/externallib.php',
        'description' => 'Custom dashboard for students',
        'type' => 'read',
        'ajax' => true,
        'loginrequired' => true,
        'capabilities' => '',
        'services' => [MOODLE_OFFICIAL_MOBILE_SERVICE],
    ],
];
