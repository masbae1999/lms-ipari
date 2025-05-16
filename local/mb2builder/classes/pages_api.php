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


if (! class_exists('Mb2builderPagesApi')) {

    class Mb2builderPagesApi
    {


        /**
         *
         * Method to get a list of all services.
         *
         */
        public static function get_list_records($limitfrom=0, $limitnum=0, $count=false, $fields='*', $sort='', $dir='') {
            global $DB;

            if ($count) {
                return $DB->count_records('local_mb2builder_pages');
            }

            $issort = $sort ? $sort . ' ' . $dir : 'id';
            $records = $DB->get_records('local_mb2builder_pages', null, $issort, $fields, $limitfrom, $limitnum);

            return $records;

        }







        /**
         *
         * Method to get sindle record.
         *
         */
        public static function get_record( $itemid = 0, $pageid = false) {
            global $DB;

            if ($pageid) {
                return $DB->get_record_sql( 'SELECT * FROM {local_mb2builder_pages} WHERE pageid = ?', array( $itemid ) );
            }

            return $DB->get_record( 'local_mb2builder_pages', array( 'id' => $itemid ), '*', MUST_EXIST );

        }









        /**
         *
         * Method to fave page during editing
         *
         */
        public static function get_form_democontent($opts=[]) {
            global $CFG, $USER;
            $output = '';
            $ajaxurl = new moodle_url($CFG->wwwroot . '/local/mb2builder/ajax/save-page.php',[]);

            $output .= '<form id="mb2-pb-form-democontent" action="" method="" data-url="' . $ajaxurl . '">';
            $output .= '<input type="hidden" name="sesskey" value="' . $USER->sesskey . '" />';
            $output .= '<input type="hidden" name="itemid" id="democontentitemid" value="' . $opts['itemid'] . '" />';
            $output .= '<input type="hidden" name="pageid" id="democontentpageid" value="' . $opts['pageid'] . '" />';
            $output .= '<textarea name="democontent" id="democontent"></textarea>';
            $output .= '<input type="submit" value="Submit">';
            $output .= '</form>';

            return $output;

        }




        /**
         *
         * Method to check if page with specific pageid exists
         *
         */
        public static function is_pageid($id, $pageid = false) {
            global $DB;

            if (!$id) {
                return false;
            }

            // We have to search by pageid
            if ($pageid) {
                $sql = 'SELECT * FROM {local_mb2builder_pages} WHERE pageid=?';
            }
            // We have to search by id
            else {
                $sql = 'SELECT * FROM {local_mb2builder_pages} WHERE id=?';
            }

            if ($DB->record_exists_sql($sql, array($id))) {
                return true;
            }

            return false;

        }




        /**
         *
         * Method to add new record.
         *
         */
        public static function add_record($data,$setcache=false,$setcachepages=false) {
            global $DB;

            if (!$data) {
                $data = new stdClass();
            }

            $data->id = $DB->insert_record('local_mb2builder_pages', $data);

            self::update_record_data($data,$setcache,$setcachepages);

        }




        /**
         *
         * Method to get page id.
         *
         */
        public static function get_page_id() {
            global $DB, $PAGE;

            $content = '';
            $ismodpage = $PAGE->pagetype==='mod-page-view';
            $isfpage = $PAGE->pagetype==='site-index' && $PAGE->pagelayout==='frontpage';
            $pageid = $isfpage ? -1 : optional_param('id', 0, PARAM_INT);

            if (!$ismodpage && !$isfpage) {
                return 0;
            }

            // Get cache of pages
            $cache = cache::make('local_mb2builder','pages');

            if (!$cache->get('mpages')) {
                // Set cache if doesn't exists
                self::set_cache_moodle_pages();
            }

            if ($pageid!=0 && array_key_exists($pageid,$cache->get('mpages'))) {
                return $cache->get('mpages')[$pageid]['id'];
            }

            return 0;

        }





        /**
         *
         * Method to check if Moodle page has builder page.
         *
         */
        public static function has_builderpage() {
            global $PAGE;

            $ismodpage = $PAGE->pagetype==='mod-page-view';
            $isfpage = $PAGE->pagetype==='site-index' && $PAGE->pagelayout==='frontpage';
            $pageid = $isfpage ? -1 : optional_param('id', 0, PARAM_INT);

            if ((!$ismodpage && !$isfpage) || $pageid==0) {
                return false;
            }

            // Get cache of pages
            $cache = cache::make('local_mb2builder','pages');

            if (!$cache->get('mpages')) {
                // Set cache if doesn't exists
                self::set_cache_moodle_pages();
            }

            return array_key_exists($pageid,$cache->get('mpages'));

        }







        /**
         *
         * Method to set cache Moodle page ids.
         *
         */
        public static function set_cache_moodle_pages() {
            global $DB;

            $cache = cache::make('local_mb2builder', 'pages');

            $mpages = [];
            $pagesql = 'SELECT id,mpage FROM {local_mb2builder_pages}';

            foreach ($DB->get_records_sql($pagesql) as $page) {
                $mpages[$page->mpage] = array('id'=>$page->id,'mpage'=>$page->mpage);
            }

            $cache->set('mpages',$mpages);
            return;

        }







        /**
         *
         * Method to update the record in the database.
         *
         */
        public static function update_record_data($data,$setcache=false,$setcachepages=false) {
            global $DB;

            // Update existing item
            $DB->update_record('local_mb2builder_pages', $data);

            if ($setcache) {
                // Update pagedata cache
                $cacheid = $data->mpage == -1 ? 'fp' : $data->mpage;
                $cache = cache::make('local_mb2builder', 'pagedata');
                $data->democontent=''; // We don't need demo contecnt in the cache file
                $cache->set($cacheid,$data);
            }  

            if ($setcachepages) {
                // Update mpages cache
                self::set_cache_moodle_pages();
            }

        }




        /**
         *
         * Method to check if user can delete item.
         *
         */
        public static function can_delete() {
            return has_capability( 'local/mb2builder:managepages', context_system::instance() );
        }




        /**
         *
         * Method to delete item.
         *
         */
        public static function delete($itemid,$setcache=true) {
            global $DB;

            if (!self::can_delete()) {
                return;
            }

            $item2delete = self::get_record($itemid);

            if ($setcache) {
                // Delete cache
                $cacheid = $item2delete->mpage == -1 ? 'fp' : $item2delete->mpage;
                $cache = cache::make('local_mb2builder', 'pagedata');
                $cache->delete($cacheid);
            }            

            $DB->delete_records('local_mb2builder_pages', array('id'=>$itemid));

            if ($setcache) {
                // Update mpages cache
                self::set_cache_moodle_pages();
            }            

        }






        /**
         *
         * Method to set form data.
         *
         */
        public static function set_record_data($opts = []) {

            global $USER;
            $data = new stdClass();

            // This case should't appears but this is for safety
            if (!$opts['itemid'] && !$opts['pageid']) {
                $data->id = null;
                $data->title = null;
                $data->timecreated = null;
                $data->createdby = null;
            }

            // This is the case when user back to the page
            else if ($opts['itemid']) {
                $data = self::get_record($opts['itemid']);
            }

            // This is the case when user editing page first time
            // itemid doesn't appear in the url, there is only pageid
            // We need to search record by pageid instead itemid
            // This is require because if user cancel settings option iframe will be reloaded and user lost all earlier changes
            else if (!$opts['itemid'] && $opts['pageid']) {
                // We have to check if page exists
                // If yes we will get data from the existing record
                if (Mb2builderPagesApi::is_pageid($opts['pageid'], true)) {
                    $data = self::get_record($opts['pageid'], true);
                }
                // If record doesn't exists we have to create it
                // For this we need define some data parts
                else
                {
                    $data->pageid = $opts['pageid'];
                    $data->mpage = $opts['mpage'];
                    $data->title = $opts['pagename'] ? urldecode($opts['pagename']) : 'Page' . time();
                }
            }

            // Set date created and modified
            $data->timecreated = isset( $data->timecreated ) ? $data->timecreated : time();
            $data->timemodified = $data->timecreated < time() ? time() : 0;

            // Set create and modifier
            $data->createdby = isset( $data->createdby ) ? $data->createdby : $USER->id;
            $data->modifiedby = $data->timecreated == time() ? 0 : $USER->id;

            // Set demo content when page is saved via AJAX request
            // This is used in 'save-page.php' files
            if (isset($opts['democontent'])) {
                $data->democontent = $opts['democontent'];
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
                    $url = new moodle_url('/local/mb2builder/index.php', array('sort'=>$k,'dir'=>$isdir,'page'=>$page,'perpage'=>$perpage));
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