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
 * @package    local_mb2coursenotes
 * @copyright  2019 - 2024 Mariusz Boloz (lmsstyle.com)
 * @license    PHP and HTML: http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later. Other parts: http://themeforest.net/licenses
 */


if ( ! class_exists( 'Mb2coursenotesApi' ) ) {

    /**
     * Notes api
     *
     */
    class Mb2coursenotesApi {


        /**
         *
         * Method to get a list of all services.
         *
         */
        public static function get_list_records($limitfrom = 0, $limitnum = 0, $fields = '*', $sort = '', $dir = '', $user = 0,
        $course = 0 ) {
            global $DB;

            $params = [];

            if ( $user ) {
                $params['user'] = $user;
            }

            if ( $course ) {
                $params['course'] = $course;
            }

            $issort = $sort ? $sort . ' ' . $dir : 'id';
            $records = $DB->get_records('local_mb2coursenotes_notes', $params, $issort, $fields, $limitfrom, $limitnum);

            return $records;

        }




        /**
         *
         * Method to get sindle record.
         *
         */
        public static function get_record($itemid = 0) {
            global $DB;

            $record = $DB->get_record('local_mb2coursenotes_notes', ['id' => $itemid], '*', MUST_EXIST);

            return $record;

        }









        /**
         *
         * Method to add new record.
         *
         */
        public static function add_record($data) {
            global $DB;

            // Add record.
            $data->id = $DB->insert_record('local_mb2coursenotes_notes', ['attribs' => '']);

            return self::update_record_data($data, true);

        }






        /**
         *
         * Method to set editor options.
         *
         */
        public static function file_area_options() {
            global $CFG;
            require_once($CFG->libdir.'/formslib.php');
            $options = [];

            $options['subdirs'] = false;
            $options['maxfiles'] = 1;
            $options['context'] = context_system::instance();

            return $options;

        }




        /**
         *
         * Method to update the record in the database.
         *
         */
        public static function update_record_data($data, $editpage = false) {
            global $DB;

            // Update existing item.
            if ( $editpage ) {
                $data->attribs = json_encode($data->attribs);
            }

            $DB->update_record('local_mb2coursenotes_notes', $data);

        }




        /**
         *
         * Method to check if user can delete item.
         *
         */
        public static function can_delete() {
            global $USER;

            $user = optional_param('user', 0, PARAM_INT );

            if ( $user == $USER->id ) {
                return true;
            }

            return has_capability('local/mb2coursenotes:manageitems', context_system::instance());
        }




        /**
         *
         * Method to delete item.
         *
         */
        public static function delete($itemid, $force=false) {
            global $DB;

            if ( !$force && !self::can_delete() ) {
                return;
            }

            $DB->delete_records('local_mb2coursenotes_notes', ['id' => $itemid]);

        }








        /**
         *
         * Method to get form data.
         *
         */
        public static function get_form_data($form, $itemid) {
            global $CFG, $USER;
            require_once($CFG->libdir . '/formslib.php');
            $data = new stdClass();

            if ( empty( $itemid ) ) {
                $data->id = null;
                $data->timecreated = null;
                $data->user = optional_param('user', 0, PARAM_INT);
                $data->course = optional_param('course', 0, PARAM_INT);
                $data->attribs = [];
            } else {
                $data = self::get_record( $itemid );
                $data->attribs = json_decode( $data->attribs, true );
            }

            // Set date created and modified.
            $data->timecreated = $data->timecreated ? $data->timecreated : time();
            $data->timemodified = $data->timecreated < time() ? time() : 0;

            $form->set_data($data);

            return $form->get_data();

        }

    }
}
