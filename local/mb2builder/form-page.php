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
require_once( $CFG->dirroot . '/local/mb2builder/classes/footers_api.php' );

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

        $restools .= '<div id="mb2-pb-page-settings">';
        $restools .= '<button class="themereset" type="button" data-modal="#mb2-pb-modal-page-settings">' . get_string('settings') . '</button>';
        $restools .= '</div>';

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
        $mform->addElement('hidden', 'pageid');
        $mform->addElement('hidden', 'footer');
        $mform->addElement('hidden', 'heading');
        $mform->addElement('hidden', 'menu');
        $mform->addElement('hidden', 'tgsdb'); 
        $mform->addElement('hidden', 'mpage');        
        $mform->addElement('hidden', 'headerstyle');
        $mform->addElement('hidden', 'pagecss');
        $mform->setType('id', PARAM_INT);
        $mform->setType('timecreated', PARAM_INT);
        $mform->setType('timemodified', PARAM_INT);
        $mform->setType('createdby', PARAM_INT);
        $mform->setType('modifiedby', PARAM_INT);
        $mform->setType('pageid', PARAM_TEXT);
        $mform->setType('footer', PARAM_INT);
        $mform->setType('heading', PARAM_INT);
        $mform->setType('headerstyle', PARAM_TEXT);
        $mform->setType('pagecss', PARAM_TEXT);
        $mform->setType('menu', PARAM_INT);
        $mform->setType('tgsdb', PARAM_INT);
        $mform->setType('mpage', PARAM_INT);
        $mform->setDefault('headerstyle','');
        $mform->setDefault('pagecss','');
        $mform->setDefault('footer',0);
        $mform->setDefault('menu',0);
        $mform->setDefault('tgsdb',0);
        $mform->setDefault('mpage',0);
        $mform->setDefault('heading',0);

        $mform->addElement( 'html', '<div class="mb2builder-form">' );
        $mform->addElement('text', 'title', get_string('title', 'local_mb2builder'), array('size' => 60));
        $mform->addRule('title', null, 'required');
        $mform->setType('title', PARAM_NOTAGS);
        $mform->setDefault('title','page');
        $mform->addElement( 'html', $restools );
        $mform->addElement( 'html', '</div>' );
        $mform->addElement( 'textarea', 'content', get_string('content', 'moodle') );
        $mform->setType('content', PARAM_RAW);
        $mform->addElement( 'textarea', 'democontent', 'democontent' );
        $mform->setType( 'democontent', PARAM_RAW);

        $this->add_action_buttons();

    }
}
