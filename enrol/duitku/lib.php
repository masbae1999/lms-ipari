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
 * Contains the functions that override Moodle Enrolment Plugin Libraries.
 * @package   enrol_duitku
 * @copyright 2022 Michael David <mikedh2612@gmail.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

use enrol_duitku\duitku_status_codes;
use enrol_duitku\duitku_mathematical_constants;

defined('MOODLE_INTERNAL') || die();

/**
 * Creates a notification for every non-expired pending payment.
 * Function must be outside of class to be detected by Moodle.
 *
 * @return void
 */
function enrol_duitku_before_footer()
{
    global $USER, $DB;

    if (!enrol_is_enabled('duitku')) {
        return null;
    }
    $params = [
        'userid' => (int)$USER->id,
        'payment_status' => duitku_status_codes::CHECK_STATUS_PENDING
    ];
    $pendingtransactions = $DB->get_records_sql('SELECT * FROM {enrol_duitku} WHERE userid = :userid AND payment_status = :payment_status', $params);

    foreach ($pendingtransactions as $transaction) {
        $referenceurl = $transaction->referenceurl;
        $course = $DB->get_record('course', ['id' => $transaction->courseid]);
        \core\notification::add("You have a pending payment for the '{$course->fullname}' course <a href='{$referenceurl}'>here</a>", \core\output\notification::NOTIFY_WARNING);
    }
}

/**
 * Duitku enrolment plugin implementation.
 * @author  Michael David - based on code by Eugene Venter, Martin Dougiamas and others
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class enrol_duitku_plugin extends enrol_plugin
{

    /**
     * Returns the list of currencies supported by Duitku
     * @return array
     */
    public function get_currencies()
    {
        $code = 'IDR';
        $currencies = [];
        $currencies[$code] = new lang_string($code, 'core_currencies');
        return $currencies;
    }

    /**
     * Returns true if foudn icon for enrol plugin
     * @param array $instances
     * @return boolean
     */
    public function get_info_icons(array $instances)
    {
        $found = false;
        foreach ($instances as $instance) {
            if ($instance->enrolstartdate != 0 && $instance->enrolstartdate > time()) {
                continue;
            }
            if ($instance->enrolenddate != 0 && $instance->enrolenddate < time()) {
                continue;
            }
            $found = true;
            break;
        }
        return ($found) ? [new pix_icon('icon', get_string('pluginname', 'enrol_duitku'), 'enrol_duitku')] : [];
    }

    /**
     * Does this plugin assign protected roles are can they be manually removed?
     * @return false
     */
    public function roles_protected()
    {
        // Users with role assign cap may tweak the roles later.
        return false;
    }

    /**
     * Does this plugin allow manual unenrolment of all users?
     * All plugins allowing this must implement 'enrol/xxx:unenrol' capability
     *
     * @param stdClass $instance course enrol instance
     * @return bool - true means user with 'enrol/xxx:unenrol' may unenrol others freely, false means nobody may touch user_enrolments
     */
    public function allow_unenrol(stdClass $instance)
    {
        // Users with unenrol cap may unenrol other users manually - requires enrol/duitku:unenrol.
        return true;
    }

    /**
     * Does this plugin allow manual changes in user_enrolments table?
     *
     * All plugins allowing this must implement 'enrol/xxx:manage' capability
     *
     * @param stdClass $instance course enrol instance
     * @return bool - true means it is possible to change enrol period and status in user_enrolments table
     */
    public function allow_manage(stdClass $instance)
    {
        // Users with manage cap may tweak period and status - requires enrol/duitku:manage.
        return true;
    }
    /**
     * Does this plugin support some way to user to self enrol?
     *
     * @param stdClass $instance course enrol instance
     *
     * @return bool - true means show "Enrol me in this course" link in course UI
     */
    public function show_enrolme_link(stdClass $instance)
    {
        return ($instance->status == ENROL_INSTANCE_ENABLED);
    }

    /**
     * Returns true if the user can add a new instance in this course.
     * @param int $courseid
     * @return boolean
     */
    public function can_add_instance($courseid)
    {
        $context = \context_course::instance($courseid, MUST_EXIST);

        if (!has_capability('moodle/course:enrolconfig', $context) or !has_capability('enrol/duitku:config', $context)) {
            return false;
        }

        // Multiple instances supported - different cost for different roles.
        return true;
    }

    /**
     * We are a good plugin and don't invent our own UI/validation code path.
     *
     * @return boolean
     */
    public function use_standard_editing_ui()
    {
        return true;
    }

    /**
     * Add new instance of enrol plugin.
     * @param object $course
     * @param array $fields instance fields
     * @return int id of new instance, null if can not be created
     */
    public function add_instance($course, array $fields = null)
    {
        if ($fields && !empty($fields['cost'])) {
            $fields['cost'] = unformat_float($fields['cost']);
        }
        return parent::add_instance($course, $fields);
    }

    /**
     * Update instance of enrol plugin.
     * @param stdClass $instance
     * @param stdClass $data modified instance fields
     * @return boolean
     */
    public function update_instance($instance, $data)
    {
        if ($data) {
            $data->cost = unformat_float($data->cost);
        }
        return parent::update_instance($instance, $data);
    }

    /**
     * Creates course enrol form, checks if form submitted
     * and enrols user if necessary. It can also redirect.
     *
     * @param stdClass $instance
     * @return string html text, usually a form in a text box
     */
    public function enrol_page_hook(stdClass $instance)
    {
        global $CFG, $USER, $OUTPUT, $PAGE, $DB;

        ob_start();

        if ($DB->record_exists('user_enrolments', ['userid' => $USER->id, 'enrolid' => $instance->id])) {
            return ob_get_clean();
        }

        if ($instance->enrolstartdate != 0 && $instance->enrolstartdate > time()) {
            return ob_get_clean();
        }

        if ($instance->enrolenddate != 0 && $instance->enrolenddate < time()) {
            return ob_get_clean();
        }

        $course = $DB->get_record('course', ['id' => $instance->courseid]);
        $context = \context_course::instance($course->id);

        $shortname = format_string($course->shortname, true, ['context' => $context]);
        $strloginto = get_string("loginto", "", $shortname);
        $strcourses = get_string("courses");

        // Pass $view=true to filter hidden caps if the user cannot see them.
        if ($users = get_users_by_capability(
            $context,
            'moodle/course:update',
            'u.*',
            'u.id ASC',
            '',
            '',
            '',
            '',
            false,
            true
        )) {
            $users = sort_by_roleassignment_authority($users, $context);
            $teacher = array_shift($users);
        } else {
            $teacher = false;
        }

        if ((float) $instance->cost <= 0) {
            $cost = (float) $this->get_config('cost');
        } else {
            $cost = (float) $instance->cost;
        }

        if (abs($cost) < 0.01) { // No cost, other enrolment methods (instances) should be used.
            echo '<p>' . get_string('nocost', 'enrol_duitku') . '</p>';
        } else {

            // Calculate localised and "." cost, make sure we send duitku the same value,
            // please note duitku expects amount with 2 decimal places and "." separator.
            $localisedcost = format_float($cost, 2, true);
            $cost = format_float($cost, 2, false);

            if (isguestuser()) { // Force login only for guest user, not real users with guest role.
                $wwwroot = $CFG->wwwroot;
                echo '<div class="mdl-align"><p>' . get_string('paymentrequired') . '</p>';
                echo '<p><b>' . get_string('cost') . ": $instance->currency $localisedcost" . '</b></p>';
                echo '<p><a href="' . $wwwroot . '/login/">' . get_string('loginsite') . '</a></p>';
                echo '</div>';
            } else {
                // Sanitise some fields before building the duitku form.
                $coursefullname  = format_string($course->fullname, true, ['context' => $context]);
                $timestamp       = round(microtime(true) * duitku_mathematical_constants::SECOND_IN_MILLISECONDS);
                $courseshortname = $shortname;
                $userfullname    = fullname($USER);
                $userfirstname   = $USER->firstname;
                $userlastname    = $USER->lastname;
                $useraddress     = $USER->address;
                $usercity        = $USER->city;
                $instancename    = $this->get_instance_name($instance);
                $logo            = $OUTPUT->image_url('duitkuw', 'enrol_duitku');

                include($CFG->dirroot . '/enrol/duitku/enrol.html');
            }
        }

        return $OUTPUT->box(ob_get_clean());
    }

    /**
     * Restore instance and map settings.
     *
     * @param restore_enrolments_structure_step $step
     * @param stdClass $data
     * @param stdClass $course
     * @param int $oldid
     */
    public function restore_instance(restore_enrolments_structure_step $step, stdClass $data, $course, $oldid)
    {
        global $DB;
        if ($step->get_task()->get_target() == backup::TARGET_NEW_COURSE) {
            $merge = false;
        } else {
            $merge = [
                'courseid'   => $data->courseid,
                'enrol'      => $this->get_name(),
                'roleid'     => $data->roleid,
                'cost'       => $data->cost,
                'currency'   => $data->currency,
            ];
        }
        if ($merge and $instances = $DB->get_records('enrol', $merge, 'id')) {
            $instance = reset($instances);
            $instanceid = $instance->id;
        } else {
            $instanceid = $this->add_instance($course, (array)$data);
        }
        $step->set_mapping('enrol', $oldid, $instanceid);
    }

    /**
     * Restore user enrolment.
     *
     * @param restore_enrolments_structure_step $step
     * @param stdClass $data
     * @param stdClass $instance
     * @param int $userid
     * @param int $oldinstancestatus
     */
    public function restore_user_enrolment(restore_enrolments_structure_step $step, $data, $instance, $userid, $oldinstancestatus = null)
    {
        $this->enrol_user($instance, $userid, null, $data->timestart, $data->timeend, $data->status);
    }

    /**
     * Return an array of valid options for the status.
     *
     * @return array
     */
    protected function get_status_options()
    {
        $options = [
            ENROL_INSTANCE_ENABLED  => get_string('yes'),
            ENROL_INSTANCE_DISABLED => get_string('no')
        ];
        return $options;
    }

    /**
     * Return an array of valid options for the roleid.
     *
     * @param stdClass $instance
     * @param context $context
     * @return array
     */
    protected function get_roleid_options($instance, $context)
    {
        if ($instance->id) {
            $roles = get_default_enrol_roles($context, $instance->roleid);
        } else {
            $roles = get_default_enrol_roles($context, $this->get_config('roleid'));
        }
        return $roles;
    }


    /**
     * Add elements to the edit instance form.
     *
     * @param stdClass $instance
     * @param MoodleQuickForm $mform
     * @param context $context
     * @return bool
     */
    public function edit_instance_form($instance, MoodleQuickForm $mform, $context)
    {

        $mform->addElement('text', 'name', get_string('custominstancename', 'enrol'));
        $mform->setType('name', PARAM_TEXT);

        $options = $this->get_status_options();
        $mform->addElement('select', 'status', get_string('status', 'enrol_duitku'), $options);
        $mform->setDefault('status', $this->get_config('status'));

        $mform->addElement('text', 'cost', get_string('cost', 'enrol_duitku'), ['size' => 4]);
        $mform->setType('cost', PARAM_RAW);
        $mform->setDefault('cost', format_float($this->get_config('cost'), 2, true));

        $duitkucurrencies = $this->get_currencies();
        $mform->addElement('select', 'currency', get_string('currency', 'enrol_duitku'), $duitkucurrencies);
        $mform->setDefault('currency', $this->get_config('currency'));

        $roles = $this->get_roleid_options($instance, $context);
        $mform->addElement('select', 'roleid', get_string('assignrole', 'enrol_duitku'), $roles);
        $mform->setDefault('roleid', $this->get_config('roleid'));

        $options = ['optional' => true, 'defaultunit' => duitku_mathematical_constants::ONE_DAY_IN_SECONDS]; // Moodle default enrol is 1 Day (86400 seconds).
        $mform->addElement('duration', 'enrolperiod', get_string('enrolperiod', 'enrol_duitku'), $options);
        $mform->setDefault('enrolperiod', $this->get_config('enrolperiod'));
        $mform->addHelpButton('enrolperiod', 'enrolperiod', 'enrol_duitku');

        $options = ['optional' => true];
        $mform->addElement('date_time_selector', 'enrolstartdate', get_string('enrolstartdate', 'enrol_duitku'), $options);
        $mform->setDefault('enrolstartdate', 0);
        $mform->addHelpButton('enrolstartdate', 'enrolstartdate', 'enrol_duitku');

        $options = ['optional' => true];
        $mform->addElement('date_time_selector', 'enrolenddate', get_string('enrolenddate', 'enrol_duitku'), $options);
        $mform->setDefault('enrolenddate', 0);
        $mform->addHelpButton('enrolenddate', 'enrolenddate', 'enrol_duitku');

        if (enrol_accessing_via_instance($instance)) {
            $warningtext = get_string('instanceeditselfwarningtext', 'core_enrol');
            $mform->addElement('static', 'selfwarn', get_string('instanceeditselfwarning', 'core_enrol'), $warningtext);
        }
    }

    /**
     * Perform custom validation of the data used to edit the instance.
     *
     * @param array $data array of ("fieldname"=>value) of submitted data
     * @param array $files array of uploaded files "element_name"=>tmp_file_path
     * @param object $instance The instance loaded from the DB
     * @param context $context The context of the instance we are editing
     * @return array of "element_name"=>"error_description" if there are errors,
     *         or an empty array if everything is OK.
     * @return void
     */
    public function edit_instance_validation($data, $files, $instance, $context)
    {
        $errors = [];

        if (!empty($data['enrolenddate']) and $data['enrolenddate'] < $data['enrolstartdate']) {
            $errors['enrolenddate'] = get_string('enrolenddaterror', 'enrol_duitku');
        }

        $cost = str_replace(get_string('decsep', 'langconfig'), '.', $data['cost']);
        if (!is_numeric($cost)) {
            $errors['cost'] = get_string('costerror', 'enrol_duitku');
        }

        $validstatus = array_keys($this->get_status_options());
        $validcurrency = array_keys($this->get_currencies());
        $validroles = array_keys($this->get_roleid_options($instance, $context));
        $tovalidate = array(
            'name' => PARAM_TEXT,
            'status' => $validstatus,
            'currency' => $validcurrency,
            'roleid' => $validroles,
            'enrolperiod' => PARAM_INT,
            'enrolstartdate' => PARAM_INT,
            'enrolenddate' => PARAM_INT
        );

        $typeerrors = $this->validate_param_types($data, $tovalidate);
        $errors = array_merge($errors, $typeerrors);

        return $errors;
    }

    /**
     * Execute synchronisation.
     * @param progress_trace $trace
     * @return int exit code, 0 means ok
     */
    public function sync(progress_trace $trace)
    {
        $this->process_expirations($trace);
        return 0;
    }

    /**
     * Is it possible to delete enrol instance via standard UI?
     *
     * @param stdClass $instance
     * @return bool
     */
    public function can_delete_instance($instance)
    {
        $context = \context_course::instance($instance->courseid);
        return has_capability('enrol/duitku:config', $context);
    }

    /**
     * Is it possible to hide/show enrol instance via standard UI?
     *
     * @param stdClass $instance
     * @return bool
     */
    public function can_hide_show_instance($instance)
    {
        $context = \context_course::instance($instance->courseid);
        return has_capability('enrol/duitku:config', $context);
    }
    /**
     * Prevent guest access to paid courses via Duitku enrollment
     * @param stdClass $instance
     * @return bool
     */
    public function allow_enrol(stdClass $instance)
    {
        global $USER;

        // Skip the guest check for Duitku callbacks or CLI scripts
        if (defined('CLI_SCRIPT') && CLI_SCRIPT) {
            return parent::allow_enrol($instance);
        }

        // Check for AJAX requests which might not have full user context
        if (defined('AJAX_SCRIPT') && AJAX_SCRIPT) {
            return parent::allow_enrol($instance);
        }

        // Block total access for guest users
        if (isguestuser() || !isloggedin()) {
            if (
                isset($_SERVER['HTTP_X_DUITKU_CALLBACK']) ||
                strpos($_SERVER['PHP_SELF'], 'membership_callback.php') !== false ||
                strpos($_SERVER['PHP_SELF'], 'verify_callback.php') !== false
            ) {
                // Allow Duitku callbacks to pass through
                return parent::allow_enrol($instance);
            }
            // Normal web access from guest user - block it
            throw new moodle_exception('noguestaccess', 'enrol_duitku');
        }

        // Normal enroll logic for authenticated users
        return parent::allow_enrol($instance);
    }
}
