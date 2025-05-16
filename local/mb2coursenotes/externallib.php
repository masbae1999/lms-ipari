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
 * Defines forms.
 *
 * @package    theme_mb2nl
 * @copyright  2020 Mariusz Boloz (mb2moodle.com)
 * @license   PHP and HTML: http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later. Other parts: http://themeforest.net/licenses
 */

defined( 'MOODLE_INTERNAL' ) || die;


require_once( $CFG->libdir . '/externallib.php' );
require ( __DIR__ . '/lib.php' );
require_once($CFG->dirroot . '/local/mb2coursenotes/classes/api.php');
require_once($CFG->dirroot . '/local/mb2coursenotes/classes/helper.php');



class local_mb2coursenotes_external extends external_api
{

    public static function get_note( $itemid, $module, $user, $course ) {
        global $PAGE;

        require_login();
        $context = context_system::instance();
        $PAGE->set_context( $context );
        $data = new stdClass();

        $params = self::validate_parameters( self::get_note_parameters(), array(
            'itemid' => $itemid,
            'module' => $module,
            'user' => $user,
            'course' => $course
        ));

        $recordid = $params['itemid'] ? $params['itemid'] : Mb2coursenotesHelper::note_exists($params['module'], $params['user'], $params['course']);

        if ( $recordid ) {
            $data = Mb2coursenotesApi::get_record($recordid);
        }
        else 
        {
            $data->id = $params['itemid'];
            $data->content = '';
        }       

        $results = array(
            'id' => $data->id,
            'content' => $data->content
        );

        return $results;

    }




    public static function add_note( $user, $course, $module, $content ) {
        global $CFG, $PAGE;

        require_login();
        $context = context_system::instance();
        $PAGE->set_context( $context );

        $params = self::validate_parameters( self::add_note_parameters(), array(
            'user' => $user,
            'course' => $course,
            'module' => $module,
            'content' => $content
        ));

        $data = new stdClass();
        $data->user = $params['user'];
        $data->course = $params['course']; 
        $data->module = $params['module'];
        $data->content = $params['content'];
        $data->timecreated = time();        

        // Add a new record
        Mb2coursenotesApi::add_record($data);

        $results = array(
            'message' => Mb2coursenotesHelper::alert_tmpl('success', get_string('notecreated','local_mb2coursenotes') ),
        );

        return $results;

    }



    public static function delete_note( $itemid, $user ) {
        global $CFG, $PAGE, $DB;

        require_login();
        $context = context_system::instance();
        $PAGE->set_context( $context );

        $params = self::validate_parameters( self::delete_note_parameters(), array(
            'itemid' => $itemid,
            'user' => $user
        )); 

        // Delet note
        $DB->delete_records('local_mb2coursenotes_notes', array('id'=>$itemid));

        $results = array(
            'message' => Mb2coursenotesHelper::alert_tmpl('success', get_string('notedeleted','local_mb2coursenotes') ),
        );

        return $results;

    }




     /**
     * Describes the parameters for submit_grading_form webservice.
     * @return external_function_parameters
     * @since  Moodle 3.1
     */
    public static function delete_note_parameters() {
        return new external_function_parameters(
            array(
                'itemid' => new external_value( PARAM_INT, 'Note ID number' ),
                'user' => new external_value( PARAM_INT, 'User ID number' )
            )
        );
    }



    /**
     * Describes the return for submit_grading_form
     * @return external_function_parameters
     * @since  Moodle 3.1
     */
    public static function delete_note_returns() {
        return new external_single_structure(
            array(
                'message' => new external_value( PARAM_RAW, 'Note deleted message' ),
                'warnings' => new external_warnings()
            )
        );
    }



    public static function edit_note( $itemid, $user, $content ) {
        global $CFG, $PAGE;

        require_login();
        $context = context_system::instance();
        $PAGE->set_context( $context );

        $params = self::validate_parameters( self::edit_note_parameters(), array(
            'itemid' => $itemid,
            'user' => $user,
            'content' => $content
        ));

        // Get note to update
        $data = Mb2coursenotesApi::get_record($params['itemid']);
        $data->content = $params['content'];
        $data->timemodified = $data->timecreated < time() ? time() : 0;
        $data->modifiedby = $data->timemodified ? $params['user'] : 0;

        // Add a new record
        Mb2coursenotesApi::update_record_data($data);

        $results = array(
            'message' => Mb2coursenotesHelper::alert_tmpl('success', get_string('noteupdated','local_mb2coursenotes') )
        );

        return $results;

    }








     /**
     * Describes the parameters for submit_grading_form webservice.
     * @return external_function_parameters
     * @since  Moodle 3.1
     */
    public static function get_note_parameters() {
        return new external_function_parameters(
            array(
                'itemid' => new external_value( PARAM_INT, 'Note ID number' ),
                'module' => new external_value( PARAM_INT, 'Module ID number' ),
                'user' => new external_value( PARAM_INT, 'User ID number' ),
                'course' => new external_value( PARAM_INT, 'Course ID number' ),
            )
        );
    }


    /**
     * Describes the parameters for submit_grading_form webservice.
     * @return external_function_parameters
     * @since  Moodle 3.1
     */
    public static function add_note_parameters() {
        return new external_function_parameters(
            array(
                'user' => new external_value( PARAM_INT, 'User ID' ),
                'course' => new external_value( PARAM_INT, 'Course ID' ),
                'module' => new external_value( PARAM_INT, 'Module ID' ),
                'content' => new external_value( PARAM_RAW, 'Note content' )
            )
        );
    }


    /**
     * Describes the parameters for submit_grading_form webservice.
     * @return external_function_parameters
     * @since  Moodle 3.1
     */
    public static function edit_note_parameters() {
        return new external_function_parameters(
            array(
                'itemid' => new external_value( PARAM_INT, 'Note ID number' ),
                'user' => new external_value( PARAM_INT, 'User ID number' ),
                'content' => new external_value( PARAM_RAW, 'Note content' )
            )
        );
    }



    /**
     * Describes the return for submit_grading_form
     * @return external_function_parameters
     * @since  Moodle 3.1
     */
    public static function get_note_returns() {
        return new external_single_structure(
            array(
                'id' => new external_value( PARAM_INT, 'Note ID' ),
                'content' => new external_value( PARAM_RAW, 'Note content' ),
                'warnings' => new external_warnings()
            )
        );
    }


    /**
     * Describes the return for submit_grading_form
     * @return external_function_parameters
     * @since  Moodle 3.1
     */
    public static function add_note_returns() {
        return new external_single_structure(
            array(
                'message' => new external_value( PARAM_RAW, 'Note created message' ),
                'warnings' => new external_warnings()
            )
        );
    }



    /**
     * Describes the return for submit_grading_form
     * @return external_function_parameters
     * @since  Moodle 3.1
     */
    public static function edit_note_returns() {
        return new external_single_structure(
            array(
                'message' => new external_value( PARAM_RAW, 'Note updated message' ),
                'warnings' => new external_warnings()
            )
        );
    }



}
