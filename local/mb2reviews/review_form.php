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
 * @package    local_mb2reviews
 * @copyright  2021 Mariusz Boloz (mb2themes.com)
 * @license    PHP and HTML: http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later. Other parts: http://themeforest.net/licenses
 */

defined('MOODLE_INTERNAL') || die();

require_once( $CFG->libdir . '/formslib.php' );
require_once( __DIR__ . '/classes/api.php' );

class mb2reviews_item_edit_form extends moodleform {

    /**
     * Defines the standard structure of the form.
     *
     * @throws \coding_exception
     */
    protected function definition()
    {

        $mform =& $this->_form;
        $sepAttr = ' class="mb2form-separator" style="height:1px;border-top:solid 1px #e5e5e5;margin:46px 0;"';
        $size = array('size' => 60 );
        $context = context_system::instance();
        $courseid = optional_param('course', 0, PARAM_INT );
        $coursecontext = context_course::instance( $courseid );

        // Hidden fields
        $mform->addElement('hidden', 'course');
        $mform->addElement('hidden', 'enable');
        $mform->addElement('hidden', 'timecreated');
        $mform->addElement('hidden', 'timemodified');
        $mform->addElement('hidden', 'createdby');
        $mform->addElement('hidden', 'modifiedby');

        $mform->setType('course', PARAM_INT);
        $mform->setType('enable', PARAM_INT);
        $mform->setType('timecreated', PARAM_INT);
        $mform->setType('timemodified', PARAM_INT);
        $mform->setType('createdby', PARAM_INT);
        $mform->setType('modifiedby', PARAM_INT);

        if ( has_capability( 'local/mb2reviews:managecourseitems', $coursecontext ) )
        {
            $mform->addElement('advcheckbox', 'featured', get_string('featured', 'local_mb2reviews'));
            $mform->setType('featured', PARAM_BOOL);
        }
        else
        {
            $mform->addElement('hidden', 'featured');
            $mform->setType('featured', PARAM_INT);
        }

        $mform->addElement('text', 'id', 'ID', array( 'readonly' ) );
        $mform->setType('id', PARAM_INT);

        $mform->addElement('select', 'rating', get_string('rating', 'local_mb2reviews'), array(
            0 => get_string('none', 'local_mb2reviews'),
            1 => get_string('star1', 'local_mb2reviews'),
            2 => get_string('star2', 'local_mb2reviews'),
            3 => get_string('star3', 'local_mb2reviews'),
            4 => get_string('star4', 'local_mb2reviews'),
            5 => get_string('star5', 'local_mb2reviews')
        ));
        $mform->setType('rating', PARAM_INT);

        $mform->addElement('textarea', 'content', get_string('comment', 'local_mb2reviews') );
        $mform->setType('content', PARAM_TEXT);

        $this->add_action_buttons( true, get_string('submit') );
    }





    /**
     * Validation.
     *
     * @param array $data
     * @param array $files
     * @return array the errors that were found
     */
    function validation( $data, $files)
    {
        global $DB, $USER;

        $errors = parent::validation( $data, $files );

        if ( ! $data['course'] )
        {
            $errors['id'] = get_string( 'nocourse', 'local_mb2reviews' );
        }        

        if ( ! $data['rating']  )
        {
            $errors['rating'] = get_string( 'norating', 'local_mb2reviews' );
        }

        return $errors;
    }
}
