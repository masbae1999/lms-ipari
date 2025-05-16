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
    'local_mb2coursenotes_get_note' => array(
        'classname'     => 'local_mb2coursenotes_external',
        'methodname'    => 'get_note',
        'classpath'     => 'local/mb2coursenotes/externallib.php',
        'description'   => 'Get note data by note ID',
        'type'          => 'read',
        'ajax'          => true,
        'loginrequired' => true,
        'capabilities'  => '',
        'services'      => array( MOODLE_OFFICIAL_MOBILE_SERVICE )
    ),
    'local_mb2coursenotes_add_note' => array(
        'classname'     => 'local_mb2coursenotes_external',
        'methodname'    => 'add_note',
        'classpath'     => 'local/mb2coursenotes/externallib.php',
        'description'   => 'Add a new note',
        'type'          => 'write',
        'ajax'          => true,
        'loginrequired' => true,
        'capabilities'  => '',
        'services'      => array( MOODLE_OFFICIAL_MOBILE_SERVICE )
    ),
    'local_mb2coursenotes_edit_note' => array(
        'classname'     => 'local_mb2coursenotes_external',
        'methodname'    => 'edit_note',
        'classpath'     => 'local/mb2coursenotes/externallib.php',
        'description'   => 'Edit note',
        'type'          => 'write',
        'ajax'          => true,
        'loginrequired' => true,
        'capabilities'  => '',
        'services'      => array( MOODLE_OFFICIAL_MOBILE_SERVICE )
    ),
    'local_mb2coursenotes_delete_note' => array(
        'classname'     => 'local_mb2coursenotes_external',
        'methodname'    => 'delete_note',
        'classpath'     => 'local/mb2coursenotes/externallib.php',
        'description'   => 'Delete a note',
        'type'          => 'write',
        'ajax'          => true,
        'loginrequired' => true,
        'capabilities'  => '',
        'services'      => array( MOODLE_OFFICIAL_MOBILE_SERVICE )
    ), 
);
