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
 * @package    local_mb2coursenotes
 * @copyright  2019 - 2024 Mariusz Boloz (lmsstyle.com)
 * @license    PHP and HTML: http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later. Other parts: http://themeforest.net/licenses
 */

//moodleform is defined in formslib.php
require_once($CFG->libdir . '/formslib.php');
require_once( __DIR__ . '/classes/helper.php' );

class mb2coursenotes_form_filter extends moodleform {
    //Add elements to form
    public function definition() {
        global $CFG;

        $mform = $this->_form; // Don't forget the underscore! 

        $options = array(
            'ajax' => 'core_user/form_user_selector',
            'multiple' => false,
            'valuehtmlcallback' => function( $userid ){

                if (! $userid ) 
                {
                    return get_string('allusers','search');
                }

                $user = core_user::get_user($userid);
                return fullname($user, has_capability('moodle/site:viewfullnames', context_system::instance()));
            }
        ); 

        if ( ! has_capability('local/mb2coursenotes:manageitems', context_system::instance()) ) {
            $mform->addElement('hidden', 'user');
            $mform->setType('user', PARAM_INT);
            $courses = Mb2coursenotesHelper::course_for_select(true);
        }
        else 
        {
            $mform->addElement('autocomplete', 'user', get_string('user'), array(''), $options);
            $mform->setType('user', PARAM_INT);
            $mform->setDefault('user',null);
            $courses = Mb2coursenotesHelper::course_for_select();
        }

        $mform->addElement('autocomplete', 'course', get_string('course'), $courses, array('multiple' => false));
        $mform->setType('course', PARAM_INT);
        $mform->setDefault('course',0);

        $mform->addElement('submit', 'submit', get_string('applyfilters'));
    }
}