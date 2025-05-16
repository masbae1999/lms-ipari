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
 * @package    local_mb2megamenu
 * @copyright  2018 - 2022 Mariusz Boloz (mb2moodle.com)
 * @license    Commercial https://themeforest.net/licenses
 */

defined('MOODLE_INTERNAL') || die();

require_once( $CFG->libdir . '/formslib.php' );

class media_edit_form extends moodleform {

    /**
     * Defines the standard structure of the form.
     *
     * @throws \coding_exception
     */
    protected function definition()
    {
        global $PAGE, $CFG;
        $mform =& $this->_form;
        $maxbytes = $CFG->maxbytes;

        $mform->addElement('filepicker', 'mediafile', '', null, array('maxbytes' => $maxbytes, 'accepted_types' => '*' ) );

    }
}
