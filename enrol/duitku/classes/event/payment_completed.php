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
 * This event is triggered whenever a payment is completed via Duitku
 * @package   enrol_duitku
 * @copyright 2023 Custom Developer
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace enrol_duitku\event;

defined('MOODLE_INTERNAL') || die();

/**
 * This class will be instantiated whenever a payment is completed
 * @copyright 2023 Custom Developer
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class payment_completed extends \core\event\base {

    /**
     * Overrides the baseclass.
     *
     * Set all required data properties:
     *  1/ crud - letter [crud]
     *  2/ edulevel - using a constant self::LEVEL_*.
     *  3/ objecttable - name of database table IF objectid specified
     *
     * Optionally it can set:
     * a/ fixed system context
     *
     * @return void
     */
    protected function init() {
        $this->data['crud'] = 'c';
        $this->data['edulevel'] = self::LEVEL_OTHER;
        $this->data['objecttable'] = 'enrol_duitku';
    }

    /**
     * Returns localised general event name.
     *
     * @return string
     */
    public static function get_name() {
        return get_string('paymentcompleted', 'enrol_duitku');
    }

    /**
     * Returns description of what happened.
     *
     * @return string
     */
    public function get_description() {
        return "The user with id '$this->userid' has completed payment for course with id '$this->courseid'.";
    }

    /**
     * Get URL related to the action.
     *
     * @return \moodle_url
     */
    public function get_url() {
        return new \moodle_url('/course/view.php', array('id' => $this->courseid));
    }

    /**
     * Custom validation.
     *
     * @throws \coding_exception
     * @return void
     */
    protected function validate_data() {
        parent::validate_data();
        
        if (!isset($this->other['reference'])) {
            throw new \coding_exception('The \'reference\' value must be set in other.');
        }
    }
}
