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
 * Search area for mod_page activities.
 *
 * @package    mod_page
 * @copyright  2015 David Monllao {@link http://www.davidmonllao.com}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */



namespace local_mb2builder\search;

class content extends \core_search\base {


    public function get_document_recordset($modifiedfrom=0, \context $context=null)
    {
        global $DB;
        return $DB->get_recordset_select('local_mb2builder_pages', 'timemodified>=?', array(0));
    }


   /**
    *
    * Method to set search results
    *
    */
   public function get_document($record, $options=[])
   {
        global $SITE;       

        // Get the default implementation.
        $doc = \core_search\document_factory::instance($record->id, $this->componentname, $this->areaname);
        $context = \context_system::instance();

        // Get Moodle page module
        $pagemod = $this->get_page_module($record->id);

        if (isset($pagemod->id)) {
            $doc->set('title', $pagemod->name);
            $doc->set('courseid', $pagemod->course);
            $context = \context_course::instance($pagemod->course);
            $doc->set('contextid', $context->id);
        }
        else
        {
            $doc->set('title', $record->title);
            $doc->set('courseid', $SITE->id);
        }

        // Add the subtitle and additional info fields.
        $doc->set('modified', $record->timemodified);
        $doc->set('content', $record->content);
        $doc->set('contextid', $context->id);
        $doc->set('owneruserid', $record->createdby);

        return $doc;

    }



    /**
     * Returns an icon instance for the document.
     *
     * @param \core_search\document $doc
     * @return \core_search\document_icon
     */
    public function get_doc_icon( \core_search\document $doc):\core_search\document_icon  {
        return new \core_search\document_icon('monologo', 'mod_page');
    }




    /**
     * Link to the page.
     *
     * @param \core_search\document $doc
     * @return \moodle_url
     */
    public function get_doc_url(\core_search\document $doc)
    {
        // Get builder page ID
        $pageid = $this->get_page_instance($doc->get('itemid'));

        // Check if there is a page resource
        if ($pageid) {
            return new \moodle_url( '/mod/page/view.php', array('id'=>$pageid) );  
        }

        // In other cases return front page
        return new \moodle_url( '/', array('redirect'=>0) );

    }



    /**
     * Link to the course.
     *
     * @param \core_search\document $doc
     * @return \moodle_url
     */
    public function get_context_url(\core_search\document $doc)
    {
        return new \moodle_url('/course/view.php', array('id' => $doc->get('courseid')));
    }



    public function check_access($id) {

        global $DB;

        try 
        {
            $record = $DB->get_record('local_mb2builder_pages', array('id'=>$id), '*', MUST_EXIST);
        } catch (\dml_missing_record_exception $ex) {
            // If the record does not exist anymore in Moodle we should return \core_search\manager::ACCESS_DELETED.
            return \core_search\manager::ACCESS_DELETED;
        } catch (\dml_exception $ex) {
            // Skip results if there is any unexpected error.
            return \core_search\manager::ACCESS_DENIED;
        }       

        return \core_search\manager::ACCESS_GRANTED;
    }




    function get_page_module($id)
    {
        global $DB;

        $pageid = 0;

        // Get page module ID
        $pagemodsql = 'SELECT id FROM {modules} WHERE name=:name';
        $pagemodid = $DB->get_record_sql($pagemodsql,array('name'=>'page'))->id;

        // Get mpage ID from builder page record
        $params = array('id'=>$id);
        $sql = 'SELECT id,mpage FROM {local_mb2builder_pages} WHERE id=:id';
        $mpage = $DB->get_record_sql($sql,$params)->mpage;

        // Get Moodle page ID from course_modules table
        $params = array('id'=>$mpage,'module'=>$pagemodid,'deletioninprogress'=>0);
        $sql = 'SELECT id,instance FROM {course_modules} WHERE module=:module AND deletioninprogress=:deletioninprogress AND id=:id';

        if ($DB->record_exists_sql($sql,$params)) {
            $pageid = $DB->get_record_sql($sql,$params)->instance;
        }

        // Get Moodle page record
        $params = array('id'=>$pageid);
        $sql = 'SELECT * FROM {page} WHERE id=:id';

        return $DB->get_record_sql($sql,$params);

    }




    function get_page_instance($id)
    {
       global $DB;

       $page = $this->get_page_module($id);

       if (!isset($page->id))
       {
           return;
       }

       $module = $DB->get_record('modules', array('name'=>'page'), 'id', MUST_EXIST);
       $instance = $DB->get_record('course_modules', array('instance'=>$page->id,'module'=>$module->id), 'id', MUST_EXIST);

       return $instance->id;
   }



}
