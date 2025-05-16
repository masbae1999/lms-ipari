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
 * @package   local_mb2builder
 * @copyright 2017 Mariusz Boloz (http://marbol2.com)
 * @license   PHP and HTML: http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later. Other parts: http://themeforest.net/licenses
 *
 */

defined('MOODLE_INTERNAL') || die();

require_once( $CFG->dirroot . '/local/mb2builder/lib.php' );
require_once( $CFG->dirroot . '/local/mb2builder/classes/api.php' );

if (! class_exists( 'mb2builderBuilder' )) {

    class mb2builderBuilder
    {



        /**
         *
         * Method to get builder layout
         *
         */
        public static function get_builder_layout( $data, $footer = 0) {

            // TO DO: Add some condition if user hasn't installed and activated schortcode filter plugin
            $output = '';

            // Get static elements shortcodes
            self::get_static_layout_parts();

            // Get elements shortcodes
            self::get_layout_elements();

            // Get demo content in customizer
            // We always get content from 'democontent' item field
            $data = isset( $data->democontent) ? json_decode( $data->democontent ) : '';

            $output .= '<div class="mb2-pb-overlay main-overlay"></div>';
            $output .= '<div class="mb2-pb-container">';
            $output .= '<div class="mb2-pb-sortable-sections clearfix">';

            // TO DO: add some conditions and content when json code doesn't exists
            if (! is_array( $data ) || empty( $data ) || ( isset( $data[0]->attr ) && empty( $data[0]->attr ) )) {
                $output .= '[mb2pb_section][/mb2pb_section]';
            } else {
                $output .= self::get_builder_section( $data[0]->attr, $footer );
            }

            $output .= '</div>';
            $output .= '</div>';

            return $output;

        }


        /**
         *
         * Method to get builder section
         *
         */
        public static function get_builder_section( $page, $footer = false) {

            $output = '';

            foreach ($page as $section) {
                $output .= '[mb2pb_section' . self::get_el_settings( $section->settings, array( 'template' ) ) . ']';

                if (isset( $section->attr )) {
                    foreach ($section->attr as $row) {
                        $output .= self::get_builder_row( $row, $footer );
                    }
                }
                $output .= '[/mb2pb_section]';
            }

            return $output;

        }



        /**
         *
         * Method to get builder layout
         *
         */
        public static function get_builder_row( $row, $footer = false) {
            $output = '';

            $output .= '[mb2pb_row' . self::get_el_settings( $row->settings, array( 'template' ) ) . ']';

            if (isset( $row->attr )) {
                foreach ($row->attr as $col) {
                    $isfooter = $footer ? ' isfooter="1"' : ' isfooter="0"';
                    $output .= '[mb2pb_column' . self::get_el_settings( $col->settings, array( 'template' ) ) . $isfooter . ']';

                    if (isset( $col->attr )) {
                        foreach ($col->attr as $el) {
                            // Check for old video shortcode
                            // TO DO: Remove this condition after a few months
                            // We believe that all users imported already old builder content
                            $elid = $el->settings->id === 'video' ? 'videoweb' : $el->settings->id;

                            // Don't use gap elements
                            // New elements has margin top and bottom settings
                            // TO DO: Remove this condition after a few months
                            if ($el->settings->id === 'gap' ||  $el->settings->id === 'code') {
                                continue;
                            }

                            $output .= '[mb2pb_' . $elid . self::get_el_settings( $el->settings, array( 'template' ) ) . ']';
                            $output .= self::get_el_content( $el );
                            $output .= '[/mb2pb_' . $elid . ']';
                        }
                    }
                    $output .= '[/mb2pb_column]';
                }
            }

            $output .= '[/mb2pb_row]';

            return $output;

        }






        /**
         *
         * Method to get builder element settings
         *
         */
        public static function get_el_settings( $item, $exclude = []) {
            global $CFG;
            $output = '';

            // Load shortcode filer if fuction doesn't exists
            if (! function_exists( 'mb2_add_shortcode' )) {
                require_once( $CFG->dirroot . '/filter/mb2shortcodes/lib/shortcodes.php' );
            }

            foreach ($item as $k => $v) {
                if (! in_array( $k, $exclude )) {
                    // We have to replace shortcodes
                    $v = self::replace_shortcode($v);

                    // Check for GENERICO
                    $v = self::check_for_generico($v);

                    // Check for sample data images
                    $v = mb2builderApi::get_sample_image($v);

                    // Check for dummy image
                    $v = mb2builderApi::get_dummy_image($v);

                    if ($k === 'content' || $k === 'text') {
                        // Encode text to url for safety shotcode parameters
                        // In front end 'content' or 'text' parameters are excluded from shortcode tag
                        // In page builde we have to include content and text parameters
                        // This is why we have to make it safety, because fo HTML tags

                        // Now we need url encode parameters for shortcode attributes value [shortname content="..."]
                        // This is because html content as a parameter
                        // Without this html attributes (for example image width attribute)
                        // can change element attribute (for example element with changed by an image width attribute)
                        $v = html_entity_decode($v);
                        $v = urlencode($v);
                    }

                    $output .= ' ' . $k . '="' . $v . '"';
                }
            }

             return $output;

        }




        /**
         *
         * Method to check for generico filter plugin
         *
         */
        public static function check_for_generico( $str) {
            global $DB;

            $sql = 'SELECT * FROM {filter_active} WHERE ' . $DB->sql_like( 'filter', '?' ) . ' AND active = ?';
            if (! $DB->record_exists_sql( $sql, array( 'generico', 1 ) )) {
                return $str;
            }

            return str_replace( 'GENERICO', 'GENERIC0', $str );
        }






        /**
         *
         * Method to get builder element content
         *
         */
        public static function get_el_content( $element) {

            $output = '';

             foreach ($element->settings as $id => $value) {
                if ($id === 'text' || $id === 'content') {
                    // Check for generico filter plugin
                    $value = self::check_for_generico($value);

                    // Javascript code prevert to do this but not hurts to do it twice
                    $value = self::replace_shortcode($value);

                    // Check for sample data images
                    $value = mb2builderApi::get_sample_image($value);

                    // Check for dummy image
                    $value = mb2builderApi::get_dummy_image($value);

                    // Encode text to url for safety shotcode parameters
                    // In front end 'content' or 'text' parameters are excluded from shortcode tag
                    // In page builde we have to include content and text parameters
                    // This is why we have to make it safety, because fo HTML tags
                    $value = html_entity_decode($value);
                    $value = urlencode($value);

                    $output .= $value;
                }
             }

             if (isset( $element->attr )) {
                 foreach ($element->attr as $subelement) {
                    // Leave empty space at the and of the shortcode ...attribute="value" ]
                    // This is because 'shortcode_parse_atts' function in some shortcodes, for example carousel item.
                     $output .= '[mb2pb_' . $element->settings->id . '_item' . self::get_el_settings( $subelement->settings, array( 'template' ) ) . ' ]';

                     foreach ($subelement->settings as $id => $value) {
                        if ($id === 'text' || $id === 'content') {
                            // Check for generico filter plugin
                            $value = self::check_for_generico($value);

                            // Javascript code prevert to do this but not hurts to do it twice
                            $value = self::replace_shortcode($value);

                            // Check for sample data images
                            $value = mb2builderApi::get_sample_image($value);

                            // Check for dummy image
                            $value = mb2builderApi::get_dummy_image($value);

                            // Encode text to url for safety shotcode parameters
                            // In front end 'content' or 'text' parameters are excluded from shortcode tag
                            // In page builder we have to include content and text parameters
                            // This is why we have to make it safety, because of HTML tags
                            $value = html_entity_decode($value);
                            $value = urlencode($value);

                            $output .= $value;

                        }
                     }

                     $output .= '[/mb2pb_' . $element->settings->id . '_item]';
                 }
             }

             return $output;

        }







        /**
         *
         * Method to get staic elements layout
         *
         */
        public static function get_static_layout_parts() {

            $elements = array( 'section', 'row', 'column' );

            foreach ($elements as $e) {
                if (file_exists( LOCAL_MB2BUILDER_PATH_THEME_SETTINGS . $e . '/' . $e . '.php' )) {
                    require_once( LOCAL_MB2BUILDER_PATH_THEME_SETTINGS . $e . '/' . $e . '.php' );
                }
            }

        }






        /**
         *
         * Method to get elements layout
         *
         */
        public static function get_layout_elements() {

            $elements = mb2builderApi::get_elements();

            foreach ($elements as $e) {
                if (file_exists( LOCAL_MB2BUILDER_PATH_THEME_ELEMENTS . $e . '/' . $e . '.php' )) {
                    require_once( LOCAL_MB2BUILDER_PATH_THEME_ELEMENTS . $e . '/' . $e . '.php' );
                }
            }

        }





        /**
         *
         * Method to get demo page iframe
         *
         */
        public static function get_demo_iframe( $params = []) {
            $output = '';

            if (isset( $params['footer'] )) {
                $urlparams = array('itemid'=>$params['itemid'], 'footerid'=>$params['footerid'], 'footer'=>$params['footer']);
            } else {
                $urlparams = array('itemid'=>$params['itemid'], 'pageid'=>$params['pageid']);
            }

            $output .= '<div id="mb2-pb-demo-iframe-wrap">';
            $output .= '<iframe id="mb2-pb-demo-iframe" src="' . new moodle_url( '/local/mb2builder/customize.php', $urlparams ) . '" ></iframe>';
            $output .= '</div>';

            return $output;
        }




        /**
         *
         * Method to get layout links
         *
         */
        public static function manage_layouts() {

            $output = '';

            $output .= '<button type="button" class="mb2-pb-importexportbtn" data-modal="#mb2-pb-modal-import-export" title="' . get_string('importexport','local_mb2builder') . '">';
            $output .= '<i class="fa fa-exchange fa-rotate-90"></i>';
            $output .= '</button>';

            return $output;

        }




        /**
         *
         * Method to replce shortcode
         *
         */
        public static function replace_shortcode( $content) {
            if (! strpos( $content, ']' )) {
                return $content;
            }

            $patterg = '#\[.+\]#';
            return preg_replace( $patterg, get_string( 'shortcodereplaced', 'local_mb2builder'), $content );

        }




        /**
         *
         * Method to check if urltolink filter plugin is active and above shortcodes filter
         *
         */
        public static function check_urltolink_filter() {
            global $DB;

            // Chect if urltolink filter plugin is active
            $sql = 'SELECT * FROM {filter_active} WHERE ' . $DB->sql_like('filter', '?') . ' AND active=?';

            // Make sure that urltolink filter is enabled
            // If not - return true
            if (! $DB->record_exists_sql( $sql, array( 'urltolink', 1 ) )) {
                return true;
            }

            // Urltolink filter is enabled, so we have to check oreding of the filters
            $mb2shortcodes = $DB->get_record_sql( $sql, array( 'mb2shortcodes', 1 ) );
            $urltolink = $DB->get_record_sql( $sql, array( 'urltolink', 1 ) );

            // In this case shortcodes filter is above urltolink filter
            // This is ok, so we returns true
            if ($mb2shortcodes->sortorder < $urltolink->sortorder) {
                return true;
            }

            return false;

        }





        /**
         *
         * Method to check if mb2shortcode filter is installed and activated
         *
         */
        public static function check_shortcodes_filter() {
            global $DB;

            $sql = 'SELECT * FROM {filter_active} WHERE ' . $DB->sql_like('filter', '?') . ' AND active=?';
            return $DB->record_exists_sql( $sql, ['mb2shortcodes', 1] );
        }


        /**
         *
         * Method to get page settings modal window
         *
         */
        public static function check_menu() {

            global $CFG, $DB;

            $dbman = $DB->get_manager();
            $table = new xmldb_table('local_mb2megamenu_menus');

            // In this case user use old version of page builder.
            if (is_file( $CFG->dirroot . '/local/mb2megamenu/index.php' ) && $dbman->table_exists($table)) {
                $options = get_config('local_mb2megamenu');

                if (isset($options->enablemenu) && $options->enablemenu) {
                    return true;
                }
            }

            return false;

        }



        /**
         *
         * Method to get page settings modal window
         *
         */
        public static function page_settings_form() {
            global $CFG;

            $output = '';
            $footers = Mb2builderFootersApi::get_footers_for_select();

            $output .= '<div id="mb2-pb-modal-page-settings" class="modal fade">';

            $output .= '<div class="modal-dialog modal-lg">';
            $output .= '<div class="modal-content">';

            $output .= '<div class="modal-header">';
            $output .= '<button type="button" class="close" data-dismiss="modal">&times;</button>';
            $output .= '<h4 class="modal-title">' . get_string('settings') . '</h4>';
            $output .= '</div>'; // modal-header

            $output .= '<div class="modal-body">';

            $output .= '<div class="form-group mb2-pb-form-group">';
            $output .= '<div><label for="mb2pb-pagesettingsheading">' . get_string('heading', 'local_mb2builder') . '</label></div>';
            $output .= '<select name="mb2pb-pagesettingsheading" id="mb2pb-pagesettingsheading">';
            $output .= '<option value="1">' . get_string('yes') . '</option>';
            $output .= '<option value="0">' . get_string('no') . '</option>';
            $output .= '</select>';
            $output .= '</div>'; // form-group

            $output .= '<div class="form-group mb2-pb-form-group">';
            $output .= '<div><label for="mb2pb-pagesettingstgsdb">' . get_string('tgsdb', 'local_mb2builder') . '</label></div>';
            $output .= '<select name="mb2pb-pagesettingstgsdb" id="mb2pb-pagesettingstgsdb">';
            $output .= '<option value="0">' . get_string('themesettings','local_mb2builder') . '</option>';
            $output .= '<option value="1">' . get_string('yes') . '</option>';
            $output .= '<option value="-1">' . get_string('no') . '</option>';
            $output .= '</select>';
            $output .= '</div>'; // form-group

            $output .= '<div class="form-group mb2-pb-form-group">';
            $output .= '<div><label for="mb2pb-pagesettingsheaderstyle">' . get_string('headerstyle', 'local_mb2builder') . '</label></div>';
            $output .= '<select name="mb2pb-pagesettingsheaderstyle" id="mb2pb-pagesettingsheaderstyle">';
            $output .= '<option value="">' . get_string('selectheaderstyle', 'local_mb2builder') . '</option>';
            $output .= '<option value="light">Light</option>';
            $output .= '<option value="light2">Light 2</option>';
            $output .= '<option value="dark">Dark</option>';
            $output .= '<option value="transparent">Transparent</option>';
            $output .= '<option value="transparent_light">Transparent light</option>';
            $output .= '</select>';
            $output .= '</div>'; // form-group

            if (self::check_menu()) {
                if (! class_exists( 'Mb2megamenuHelper' )) {
                    require_once( $CFG->dirroot . '/local/mb2megamenu/classes/helper.php' );
                }

                $mmHlpr = new Mb2megamenuHelper;

                $output .= '<div class="form-group mb2-pb-form-group">';
                $output .= '<div><label for="mb2pb-pagesettingsmenu">' . get_string('menu', 'local_mb2builder') . '</label></div>';
                $output .= '<select name="mb2pb-pagesettingsmenu" id="mb2pb-pagesettingsmenu">';
                foreach ($mmHlpr->get_menus_for_select() as $k => $v) {
                    $output .= '<option value="' . $k . '">' . $v . '</option>';
                }
                $output .= '</select>';
                $output .= '</div>'; // form-group
            }			

            $output .= '<div class="form-group mb2-pb-form-group">';
            $output .= '<div><label for="mb2pb-pagesettingsfooter">' . get_string('footer', 'local_mb2builder') . '</label></div>';
            $output .= '<select name="mb2pb-pagesettingsfooter" id="mb2pb-pagesettingsfooter">';
            foreach ($footers as $k => $v) {
                $output .= '<option value="' . $k . '">' . $v . '</option>';
            }
            $output .= '</select>';
            $output .= '</div>'; // form-group

            $output .= '<div class="form-group mb2-pb-form-group">';
            $output .= '<div><label for="mb2pb-pagesettingspagecss">' . get_string('pagecss', 'local_mb2builder') . '</label></div>';
            $output .= '<textarea name="mb2pb-pagesettingspagecss" id="mb2pb-pagesettingspagecss" style="width:100%;"></textarea>';
            $output .= '</div>'; // form-group

            $output .= '</div>'; // modal-body

            $output .= '<div class="modal-footer">';
            $output .= '<button type="button" class="btn btn-sm btn-success" data-modal="#mb2-pb-modal-page-settings">' . get_string('save') . '</button>';
            $output .= '<button type="button" class="btn btn-sm btn-danger" data-modal="#mb2-pb-modal-page-settings">' . get_string('cancel') . '</button>';
            $output .= '</div>'; // modal-footer

            $output .= '</div>'; // modal-content
            $output .= '</div>'; // modal-dialog
            $output .= '</div>'; // modal fade mb2-pb-modal

            return $output;

        }



    }


}
