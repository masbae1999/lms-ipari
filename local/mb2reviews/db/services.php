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
 * Web service for mod assign
 * @package    mod_assign
 * @subpackage db
 * @since      Moodle 2.4
 * @copyright  2012 Paul Charsley
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

$functions = array(
    
    'local_mb2reviews_review_list' => array(
            'classname'     => 'local_mb2reviews_external',
            'methodname'    => 'review_list',
            'classpath'     => 'local/mb2reviews/externallib.php',
            'description'   => 'Load more reviews via ajax',
            'type'          => 'read',
            'ajax'          => true,
            'loginrequired' => false,
            'capabilities'  => '',
            'services'      => array( MOODLE_OFFICIAL_MOBILE_SERVICE )
    ),
);
