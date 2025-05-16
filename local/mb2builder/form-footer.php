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

require_once( $CFG->libdir . '/formslib.php' );

class service_edit_form extends moodleform {

    /**
     * Defines the standard structure of the form.
     *
     * @throws \coding_exception
     */
    protected function definition()
    {
        global $PAGE;
        $mform = $this->_form;
        $restools = '';

        $restools .= '<div class="mb2-pb-restool">';
        $restools .= '<button type="button" class="mb2-pb-reslink mb2-pb-desktop" data-device="desktop"><i class="ri-computer-line"></i></button>';
        $restools .= '<button type="button" class="mb2-pb-reslink mb2-pb-tablet" data-device="tablet"><i class="ri-tablet-line"></i></button>';
        $restools .= '<button type="button" class="mb2-pb-reslink mb2-pb-smartphone" data-device="smartphone"><i class="ri-smartphone-line"></i></button>';
        $restools .= '</div>';

        // Hidden fields
        $mform->addElement('hidden', 'id');
        $mform->addElement('hidden', 'timecreated');
        $mform->addElement('hidden', 'timemodified');
        $mform->addElement('hidden', 'createdby');
        $mform->addElement('hidden', 'modifiedby');
        $mform->addElement('hidden', 'footerid');
        $mform->setType('id', PARAM_INT);
        $mform->setType('timecreated', PARAM_INT);
        $mform->setType('timemodified', PARAM_INT);
        $mform->setType('createdby', PARAM_INT);
        $mform->setType('modifiedby', PARAM_INT);
        $mform->setType('footerid', PARAM_TEXT);

        $mform->addElement( 'html', '<div class="mb2builder-form">' );
        $mform->addElement('text', 'name', get_string('name', 'local_mb2builder'), array('size' => 60 ) );
        $mform->addRule('name', null, 'required');
        $mform->setType('name', PARAM_NOTAGS);
        $mform->setDefault('name','footer');        
        $mform->addElement( 'html', $restools );
        $mform->addElement( 'html', '</div>' );
        $mform->addElement( 'textarea', 'content', get_string('content', 'moodle') );
        $mform->setType('content', PARAM_RAW);
        $mform->addElement( 'textarea', 'democontent', 'democontent' );
        $mform->setType( 'democontent', PARAM_RAW);

        $this->add_action_buttons();

    }
}
