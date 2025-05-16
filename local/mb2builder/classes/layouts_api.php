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
 * @package    local_mb2builder
 * @copyright  2018 - 2024 Mariusz Boloz (lmsstyle.com)
 * @license    PHP and HTML: http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later. Other parts: http://themeforest.net/licenses
 */

defined('MOODLE_INTERNAL') || die();


if (! class_exists( 'Mb2builderLayoutsApi' )) {

    class Mb2builderLayoutsApi
    {



        /**
         *
         * Method to get a list of all services.
         *
         */
        public static function get_list_records($limitfrom=0, $limitnum=0, $count=false, $fields='*', $sort='', $dir='') {
            global $DB;

            if ($count) {
                return $DB->count_records('local_mb2builder_layouts');
            }

            $issort = $sort ? $sort . ' ' . $dir : 'id';
            $records = $DB->get_records('local_mb2builder_layouts', null, $issort, $fields, $limitfrom, $limitnum);

            return $records;

        }






        /**
         *
         * Method to get sindle record.
         *
         */
        public static function get_record( $itemid = 0) {
            global $DB;

            return $DB->get_record( 'local_mb2builder_layouts', array( 'id' => $itemid ), '*', MUST_EXIST );

        }





        /**
         *
         * Method to add new record.
         *
         */
        public static function add_record( $data) {
            global $DB;

            if (! $data) {
                $data = new stdClass();
            }

            $data->id = $DB->insert_record( 'local_mb2builder_layouts', $data );

            self::update_record_data( $data );

        }







        /**
         *
         * Method to update the record in the database.
         *
         */
        public static function update_record_data( $data) {
            global $DB;

            // Update existing item
            $DB->update_record( 'local_mb2builder_layouts', $data );

        }




        /**
         *
         * Method to check if user can delete item.
         *
         */
        public static function can_delete() {
            return has_capability( 'local/mb2builder:managelayouts', context_system::instance() );
        }




        /**
         *
         * Method to delete item.
         *
         */
        public static function delete( $itemid) {
            global $DB;

            if (! self::can_delete()) {
                return;
            }

            $DB->delete_records( 'local_mb2builder_layouts', array( 'id' => $itemid ) );

        }












        /**
         *
         * Method to set form data.
         *
         */
        public static function set_record_data( $opts = []) {

            global $USER;
            $data = new stdClass();

            // This case should't appears but this is for safety
            if (! $opts['itemid']) {
                $data->id = null;
                $data->name = null;
                $data->timecreated = null;
                $data->createdby = null;
            }

            // This is the case when user back to the page
            if ($opts['itemid']) {
                $data = self::get_record( $opts['itemid'] );
            }

            // Set date created and modified
            $data->timecreated = isset( $data->timecreated ) ? $data->timecreated : time();
            $data->timemodified = $data->timecreated < time() ? time() : 0;

            // Set create and modifier
            $data->createdby = isset( $data->createdby ) ? $data->createdby : $USER->id;
            $data->modifiedby = $data->timecreated == time() ? 0 : $USER->id;

            // Set content when page is saved via AJAX request
            // This is used in 'layout_save.php' file
            if (isset( $opts['content'] )) {
                $data->content = $opts['content'];
            }

            if (isset( $opts['name'] )) {
                $data->name = $opts['name'];
            }

            return $data;

        }





        /**
         *
         * Method to get form data.
         *
         */
        public static function get_form_data( $form, $data) {
            global $CFG;
            require_once( $CFG->libdir . '/formslib.php' );

            $form->set_data( $data );

            return $form->get_data();

        }





        /**
         *
         * Method to get user
         *
         */
        public static function get_user( $id) {
            global $DB;

            if (! $id) {
                return;
            }

            return $DB->get_record( 'user', array( 'id'=> $id ) );
        }




         /**
         *
         * Method to get table sort headers
         *
         */
        public static function get_table_header($columns,$tosort=[]) {
            global $OUTPUT;

            $headers = []; 
            $sort = optional_param('sort','timecreated',PARAM_ALPHANUMEXT);
            $dir = optional_param('dir','DESC',PARAM_ALPHA);
            $page = optional_param('page',0,PARAM_INT );
            $perpage = 20; 

            foreach ($columns as $k=>$column) {                
                if (in_array($k,$tosort)) {   
                    $isdir = $dir == 'DESC' ? 'ASC' : 'DESC';
                    $url = new moodle_url('/local/mb2builder/layouts.php', array('sort'=>$k,'dir'=>$isdir,'page'=>$page,'perpage'=>$perpage));
                    $icon_name = $isdir==='ASC' ? "sort_desc" : "sort_asc";
                    $colicon = $OUTPUT->pix_icon('t/' . $icon_name, get_string(strtolower($isdir)), 'core', array('class'=>'iconsort'));

                    $headers[] = '<a href="' . $url . '">' . get_string($k,'local_mb2builder') .  $colicon . '</a>';
                }
                else
                {
                    $component = $k==='actions' ? '' : 'local_mb2builder';
                    $headers[] = get_string($k,$component);
                }
            }

            return $headers;
        }


    }
}
