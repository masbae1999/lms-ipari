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

require_once(__DIR__ . '/api.php');

if (! class_exists('Mb2builderPagesHelper')) {
    class Mb2builderPagesHelper
    {


        public static function get_image_url($itemid) {
            global $CFG;

            require_once($CFG->libdir . '/filelib.php');
            $context = context_system::instance();
            $url = '';
            $fs = get_file_storage();
            $files = $fs->get_area_files($context->id, 'local_mb2builder', 'attachment', $itemid);

            foreach ($files as $f) {
                $url = moodle_url::make_pluginfile_url($f->get_contextid(), $f->get_component(), $f->get_filearea(), $f->get_itemid(), $f->get_filepath(), $f->get_filename(), false);
            }

            return $url;

        }






        /**
         *
         * Method to update get settings.
         *
         */
        public static function get_param($itemid, $param) {

            $opt = get_config('local_mb2builder');
            $paramname = $param;
            $item = Mb2builderPagesApi::get_record($itemid);
            $attribsobj = json_decode($item->attribs);
            $attribs = json_decode($item->attribs, true);
            $isattribs = array_key_exists($param, $attribs);

            if ($isattribs) {
                $item = $attribsobj;
            }

            if (isset($item->$param)) {

                // Intiger values
                // '999' is a global value
                if (is_numeric($item->$param) && $item->$param != 999) {
                    return $item->$param;
                }
                // String values
                else if (!is_numeric($item->$param) && $item->$param !== '') {
                    return $item->$param;
                }
                else
                {
                    // Return global option
                    return $opt->$param;
                }
            } else {
               return $opt->$param;
            }

        }





        /**
         *
         * Method to update get settings.
         *
         */
        public static function get_item_content( $item) {
            $context = context_system::instance();

            if (strip_tags( $item->content ) === '') {
                return;
            }

            $text = file_rewrite_pluginfile_urls( $item->content, 'pluginfile.php', $context->id, 'local_mb2builder', 'content', $item->id );
            $text = format_text( $text, FORMAT_HTML );

            return $text;

        }





        /**
         *
         * Method to get user roles
         *
         */
        public static function get_roles() {

            return role_fix_names(get_all_roles());

        }





        /**
         *
         * Method to get user roles to selct from field
         *
         */
        public static function get_roles_to_select() {
            $select_roles = [];
            $roles = self::get_roles();

            foreach ($roles as $role) {
                $select_roles[$role->shortname] = $role->localname;
            }

            return $select_roles;

        }




        /**
         *
         * Method to get user
         *
         */
        public static function get_user($id) {
            global $DB;

            if (!$id) {
                return;
            }

            return $DB->get_record('user', array('id'=>$id));
        }





        /**
         *
         * Method to get item datetime.
         *
         */
        public static function get_datetime ($time) {

        	if (!$time)
        	{
        		return;
        	}

        	$time_bool = date('I',$time);

        	// Check if is daylight savings time
        	// If yes add one hour to the base time
        	if ($time_bool)
        	{
        		$time = $time+60*60;
        	}

        	return $time;

        }




        /**
         *
         * Method to get item languages array
         *
         */
        public static function get_languages($item) {

            if (!$item) {
                return [];
            }

            // Get json languages
            $languages = json_decode($item->language, true);

            // Remove empty values
            $languages = array_filter($languages);

            return $languages;

        }




        /**
         *
         * Method to check if user can see item
         *
         */
        public static function can_see($item) {

            // Check if item is published
            if (!$item->enable) {
                return false;
            }

            // Check item access
            if ($item->access == 1) {
                if (! self::user_access_status($item)) {
                    return false;
                }
            } else if ($item->access == 2 && isloggedin() && ! isguestuser()) {
                return false;
            }

            // Check language
            $itemlanguages = self::get_languages($item);

            if (count($itemlanguages) && !in_array(current_language(), $itemlanguages)) {
                return false;
            }

            return true;

        }




        /**
         *
         * Method to check who can see notice.
         *
         */
        public static function user_access_status( $item) {

            global $USER;
            $attribs = json_decode( $item->attribs );

            // Check if user is logged in
            if (! isloggedin() || isguestuser()) {
                return false;
            }

            // Check if userids param exists
            if (! isset( $attribs->userids )) {
                return true;
            }

            // Get user IDs array
            $userids = explode( ',', $attribs->userids );
            $userids = array_map( 'trim', $userids );

            if ($userids[0]) {
                // Include course IDs
                if (self::array_positive( $userids ) && ! in_array( $USER->id, $userids )) {
                    return false;
                }

                // Exclude course IDs
                else if (! self::array_positive( $userids ) && in_array( $USER->id * -1, $userids )) {
                    return false;
                }
            }


            return true;

        }



        /**
         *
         * Method to check array value (positive or negative).
         *
         */
        public static function array_positive( $array = []) {

            if (count( $array ) == 0) {
                return;
            }

            foreach ($array as $v) {
                if ($v < 0) {
                    return false;
                }
            }

            return true;

        }



        /**
         *
         * Method to get url params array
         *
         */
        public static function url_params( $url) {

            $urlparts = parse_url( $url );

            if (isset( $urlparts['query'] )) {
                parse_str( str_replace( '&amp;', '&', $urlparts['query'] ), $parts );

                return $parts;
            }

            return;

        }






        /**
         *
         * Method to get safe url string
         *
         */
        public static function url_safe_str( $string) {

        	// Remove any '-' from the string since they will be used as concatenaters
        	$output = str_replace( '-', ' ', $string );

        	// Trim white spaces at beginning and end of alias and make lowercase
        	$output = trim( mb_strtolower( $output ) );

            // Remove any duplicate whitespace, and ensure all characters are alphanumeric
            $output = preg_replace( '#[^\w\d_\-\.]#iu', '-', $output );

        	// Trim dashes at beginning and end of alias
        	$output = trim( $output, '-' );

        	return $output;

        }




    }

}
