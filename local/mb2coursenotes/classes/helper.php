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

defined('MOODLE_INTERNAL') || die();

require_once(__DIR__ . '/api.php');

if ( ! class_exists( 'Mb2coursenotesHelper' ) ) {
    class Mb2coursenotesHelper {



        /**
         *
         * Method to get table sort headers
         *
         */
        public static function get_table_header($columns, $tosort = [] ) {
            global $OUTPUT;

            $headers = []; 
            $sort = optional_param('sort', 'timecreated', PARAM_ALPHANUMEXT);
            $dir = optional_param('dir', 'DESC', PARAM_ALPHA);
            $page = optional_param('page', 0, PARAM_INT );
            $course = optional_param('course', 0, PARAM_INT );
            $perpage = 20;     

            foreach( $columns as $k => $column ) {                
                if ( in_array($k, $tosort) ) {
                    $isdir = $dir === 'DESC' ? 'ASC' : 'DESC';
                    $url = new moodle_url('/local/mb2coursenotes/manage.php', ['course' => $course, 'sort' => $k, 'dir' => $isdir,
                    'page' => $page, 'perpage' => $perpage]);
                    $iconname = $isdir === 'ASC' ? "sort_desc" : "sort_asc";
                    $colicon = $OUTPUT->pix_icon('t/' . $iconname, get_string(strtolower($isdir)), 'core', ['class' => 'iconsort']);

                    $headers[] = '<a href="' . $url . '">' . get_string($k) .  $colicon . '</a>';
                } else {
                    $headers[] = get_string($k);
                }
            }

            return $headers;
        }




        /**
         *
         * Method to get course sort filed
         *
         */
        public static function course_for_select($my = false) {
            global $SITE;
            $scourses = [];
            $courses = $my  ? enrol_get_my_courses() : get_courses();

            $scourses[0] = get_string('allcourses', 'search');

            foreach($courses as $course) {
                if ( $course->id == $SITE->id ) {
                    continue;
                }

                $scourses[$course->id] = $course->fullname;
            }

            return $scourses;

        }




        /**
         *
         * Method to get user
         *
         */
        public static function note_exists($module, $user = 0, $course = 0) {
            global $DB, $USER, $COURSE;

            $isuser = $user ? $user : $USER->id;
            $iscourse = $course ? $course : $COURSE->id;

            $params = ['user' => $isuser, 'course' => $iscourse, 'module' => $module];
            $sqlquery = 'SELECT id FROM {local_mb2coursenotes_notes} WHERE user=:user AND course=:course AND module=:module';

            if ( $DB->record_exists_sql($sqlquery, $params) ) {
                return $DB->get_record_sql($sqlquery, $params)->id;
            }

            return 0;
        }




        /**
         *
         * Method to get user
         *
         */
        public static function get_module($course, $id) {

            $modinfo = get_fast_modinfo($course);

            foreach ( $modinfo->cms as $cm ) {

                if ( $cm->id != $id ) {
                   continue;
                }

                return $cm;               
            }

            return;

        }





        /**
         *
         * Method to get course section
         *
         */
        public static function get_section($courseobj, $moduleid) {

            global $DB;

            $records = [];            
            $params = [$courseobj->id];
            $sqlquery = 'SELECT * FROM {course_sections} WHERE course=?';

            if ( $DB->record_exists_sql($sqlquery, $params) ) {
                $records = $DB->get_records_sql($sqlquery, $params);
            }

            if ( empty( $records ) ) {
                return 0;
            }

            $modinfo = get_fast_modinfo( $courseobj );
            $sections = $modinfo->get_section_info_all();

            foreach( $records as $record ) {
                $modules = explode(',', $record->sequence);

                if ( in_array($moduleid, $modules) ) {                
                    return get_section_name( $courseobj, $sections[$record->section] );
                }
            }

            return 0;

        }







        /**
         *
         * Method to get user
         *
         */
        public static function get_user($id) {
            global $DB;

            if ( ! $id ) {
                return;
            }

            return $DB->get_record('user', ['id' => $id] );
        }





        /**
         *
         * Method to get item datetime.
         *
         */
        public static function get_datetime($time) {

            if (!$time) {
                return;
            }

            $time_bool = date('I', $time);

            // Check if is daylight savings time.
            // If yes add one hour to the base time.
            if ($time_bool) {
                $time = $time+60*60;
            }

            return $time;

        }





        /**
         *
         * Method to get item datetime.
         *
         */
        public static function can_manage() {
            global $SITE, $USER;

            $courseid = optional_param('course', 0, PARAM_INT);

            if ( ! $courseid || $courseid == $SITE->id ) {
                return false;
            }

            $systemcontext = context_system::instance();
            $coursecontext = context_course::instance($courseid);
            $enroled = is_enrolled( $coursecontext, $USER->id );
            $canmanage = has_capability('local/mb2coursenotes:manageitems', $systemcontext);

            if ( $enroled || $canmanage ) {
                return true;
            }

            return false;

        }



        /**
         *
         * Method to get item datetime.
         *
         */
        public static function alert_tmpl($type ='info', $content = '') {

            $output = '';

            $output .= '<div class="alert alert-' . $type . ' alert-block fade in alert-dismissible" role="alert"
            data-aria-autofocus="true">';
            $output .= $content;
            $output .= '<button type="button" class="close" data-dismiss="alert">';
            $output .= '<span aria-hidden="true">&times;</span>';
            $output .= '<span class="sr-only">Dismiss this notification</span>';
            $output .= '</button>';
            $output .= '</div>';

            return $output;

        }






        /**
         *
         * Method to get item datetime.
         *
         */
        public static function editor_html() {
            global $PAGE;

            $output = '';

            $output .= '<div class="mb2-editor">';
            $output .= '<div class="mb2-editor-toolbar">';
            $output .= '<select title="Format" data-action="formatBlock">';
            $output .= '<option selected="selected" disabled="disabled">' . get_string('formatblock','editor') . '</option>';
            $output .= '<option value="h1">' . get_string('heading','editor') . ' 1</option>';
            $output .= '<option value="h2">' . get_string('heading','editor') . ' 2</option>';
            $output .= '<option value="h3">' . get_string('heading','editor') . ' 3</option>';
            $output .= '<option value="h4">' . get_string('heading','editor') . ' 4</option>';
            $output .= '<option value="h5">' . get_string('heading','editor') . ' 5</option>';
            $output .= '<option value="h6">' . get_string('heading','editor') . ' 6</option>';
            $output .= '<option value="p">' . get_string('paragraph','local_mb2builder') . '</option>';
            $output .= '</select>';
            $output .= '<div class="divider"></div>';
            $output .= '<button data-action="bold" title="' . get_string('bold','editor') . '"><i class="fa fa-bold"></i></button>';
            $output .= '<button data-action="' . get_string('italic','editor') . '" title="Italic"><i class="fa fa-italic"></i>
            </button>';
            $output .= '<button data-action="underline" title="' . get_string('underline','editor') . '">
            <i class="fa fa-underline"></i></button>';
            $output .= '<div class="divider"></div>';
            $output .= '<button data-action="justifyLeft" title="' . get_string('justifyleft','editor') . '"><
            i class="fa fa-align-left"></i></button>';
            $output .= '<button data-action="justifyCenter" title="' . get_string('justifycenter','editor') . '">
            <i class="fa fa-align-center"></i></button>';
            $output .= '<button data-action="justifyRight" title="' . get_string('justifyright','editor') . '">
            <i class="fa fa-align-right"></i></button>';
            $output .= '<button data-action="justifyFull" title="' . get_string('justifyfull','editor') . '">
            <i class="fa fa-align-justify"></i></button>';
            $output .= '<div class="divider"></div>';
            $output .= '<button data-action="insertUnorderedList" title="' . get_string('unorderedlist','editor') . '">
            <i class="fa fa-list-ul"></i></button>';
            $output .= '<button data-action="insertOrderedList" title="' . get_string('orderedlist','editor') . '">
            <i class="fa fa-list-ol"></i></button>';

            $output .= '<div class="divider"></div>';
            $output .= '<button class="mb2-editor-helper-link" title="' . get_string('createlink','editor') . '">
            <i class="fa fa-link"></i></button>';
            $output .= '<button data-action="unlink" title="' . get_string('createlink','editor') . '"><i class="fa fa-unlink"></i>
            </button>';
            $output .= '<div class="divider"></div>';
            $output .= '<button data-action="undo" title="' . get_string('undo','editor') . '"><i class="fa fa-undo"></i></button>';
            $output .= '<button data-action="redo" title="' . get_string('redo','editor') . '"><i class="fa fa-repeat"></i>
            </button>';
            $output .= '<button data-action="removeFormat" title="' . get_string('removeformat','local_mb2builder') . '">
            <i class="fa fa-eraser"></i></button>';
            $output .= '<button data-action="delete" title="' . get_string('delete','editor') . '"><i class="fa fa-trash"></i>
            </button>';
            $output .= '<div class="divider"></div>';
            $output .= '<button class="mb2-editor-htmlmode" title="' . get_string('htmlmode','editor') . '">
            <i class="fa fa-code"></i></button>';
            $output .= '</div>'; // ...mb2-editor-toolbar

            $output .= '<div class="mb2-editor-helper">';
            $output .= self::editor_helper_link();
            $output .= '</div>';

            $output .= '</div>'; // ...end editor

            $output .= '<div class="mb2-editor-document"></div>';

            return $output;

        }





        /**
         *
         * Method to get item datetime.
         *
         */
        public static function editor_helper_link() {

            $output = '';

            $output .= '<div class="mb2-editor-helper-element element-link">';
            $output .= '<div class="d-flex flex-column">';
            $output .= '<div class="d-flex align-items-center mb-2">';
            $output .= '<label class="mb-0 mr-1">Url</label> ';
            $output .= '<input type="text" name="mb2_editor_link_url">';
            $output .= '</div>';
            $output .= '<div class="d-flex align-items-center mb-2">'; 
            $output .= '<label class="mb-0 mr-1">Open in a new window</label>';           
            $output .= '<input type="checkbox" name="mb2_editor_link_target">';            
            $output .= '</div>';
            $output .= '</div>';
            $output .= '<div class="element-image-buttons">';
            $output .= '<button type="button" class="mb2-editor-link-save themereset">' .
            get_string('save', 'admin') . '</button> | ';
            $output .= '<button type="button" class="mb2-editor-link-cancel themereset">' . get_string('cancel') . '</button>';
            $output .= '</div>';
            $output .= '</div>';

            return $output;



        }




        /**
         *
         * Method to get item datetime.
         *
         */
        public static function download_btn() {

            $output = '';
            $course = optional_param('course', 0, PARAM_INT);
            $user = optional_param('user', 0, PARAM_INT);

            if ( ! $course || ! $user ) {
                return;
            }

            $notes = Mb2coursenotesApi::get_list_records(0, 0, 'id', '', '', $user, $course);

            if ( ! count( $notes ) ) {
                return;
            }

            $downloadlink = new moodle_url('/local/mb2coursenotes/note2pdf.php', ['user' => $user, 'course' => $course]);

            $output .= '<a href="' . $downloadlink . '" class="mb2-pb-btn sizesm rounded1">';
            $output .= '<span class="btn-icon"><i class="fa fa-file-pdf"></i></span>';
            $output .= '<span class="btn-text">' . get_string('downloadnotes','local_mb2coursenotes') . '</span>';
            $output .= '</a>'; 

            return $output;

        }



        /**
         *
         * Method to set safe file name.
         *
         */
        public static function safe_file_name($string, $lower = false) {

            // ...remove any html tags
            $output = strip_tags( $string );

            // Remove any '-' from the string since they will be used as concatenaters.
            $output = str_replace( '-', ' ', $string );

            // Remove any duplicate whitespace, and ensure all characters are alphanumeric.
            $output = preg_replace( '#[^\w\d_\-\.]#iu', '-', $output );

            if ( $lower ) {
                $output = strtolower($output);
            }

            // Trim dashes at beginning and end of alias.
            $output = trim( $output );

            return $output;

        }


    }

}