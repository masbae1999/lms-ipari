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

require_once($CFG->libdir . '/formslib.php');
require_once(__DIR__ . '/classes/api.php');
require_once(__DIR__ . '/classes/helper.php');

class service_edit_form extends moodleform {

    /**
     * Defines the standard structure of the form.
     *
     * @throws \coding_exception
     */
    protected function definition() {
        $mform =& $this->_form;
        global $CFG, $USER;

        $size = array('size' => 60 );
        $context = context_system::instance();
        $itemid = optional_param('itemid', 0, PARAM_INT);
        $record = Mb2coursenotesApi::get_record($itemid);
        $courseobj = get_course($record->course);      

        $modulename = $record->module ? Mb2coursenotesHelper::get_module($courseobj, $record->module)->name : get_string('all');
        $sectionname = $record->module ? Mb2coursenotesHelper::get_section($courseobj,$record->module) : get_string('all');

        // Hidden fields
        $mform->addElement('hidden', 'id');
        $mform->addElement('hidden', 'timecreated');
        $mform->addElement('hidden', 'timemodified');
        $mform->addElement('hidden', 'user');
        $mform->addElement('hidden', 'course');
        $mform->addElement('hidden', 'module');
        $mform->addElement('hidden', 'attribs');
        $mform->setType('attribs', PARAM_RAW);
        $mform->setType('id', PARAM_INT);
        $mform->setType('timecreated', PARAM_INT);
        $mform->setType('timemodified', PARAM_INT);
        $mform->setType('user', PARAM_INT);
        $mform->setType('course', PARAM_INT);
        $mform->setType('module', PARAM_INT);

        $mform->addElement('html', '<div class="row">');
        $mform->addElement('html', '<div class="col-md-3"></div>');
        $mform->addElement('html', '<div class="col-md-9">');
        $mform->addElement('html', '<div class="mb-5 note-details">');
        $mform->addElement('html', '<div>' . get_string('coursetitle','local_mb2coursenotes', $courseobj->fullname) . '</div>');
        $mform->addElement('html', '<div>' . get_string('sectiontitle','local_mb2coursenotes', $sectionname) . '</div>');
        $mform->addElement('html', '<div>' . get_string('activityresource','local_mb2coursenotes', $modulename) . '</div>');
        $mform->addElement('html', '</div>');
        $mform->addElement('html', '</div>');
        $mform->addElement('html', '</div>');

        $mform->addElement('textarea', 'content', get_string('content'));
        $mform->addRule('content', null, 'required');
        $mform->setType('content', PARAM_TEXT);

        $this->add_action_buttons();
    }
}
