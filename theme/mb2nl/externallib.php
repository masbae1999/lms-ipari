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
 * @package    theme_mb2nl
 * @copyright  2020 Mariusz Boloz (lmsstyle.com)
 * @license   PHP and HTML: http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later. Other parts: http://themeforest.net/licenses
 */

defined('MOODLE_INTERNAL') || die;

require_once($CFG->libdir . '/externallib.php');
require(__DIR__ . '/lib.php');

/**
 * External theme API
 *
 *
 */
class theme_mb2nl_external extends external_api {


    /**
     *
     * Method to get a list of all services.
     *
     */
    public static function set_course_filters($formdata, $page, $searchstr) {
        global $PAGE;

        $context = context_system::instance();
        $PAGE->set_context($context);

        $params = self::validate_parameters(self::set_course_filters_parameters(), [
            'formdata' => $formdata,
            'page' => $page,
            'searchstr' => $searchstr,
        ]);

        // Convert serialized form string to an array.
        $popt = [];
        parse_str($params['formdata'], $popt);

        $opt = [
            'categories' => $popt['filter_categories'],
            'tags' => $popt['filter_tags'],
            'price' => $popt['filter_price'] > -1 ? $popt['filter_price'] : -1,
            'instructors' => $popt['filter_instructors'],
            'page' => $params['page'],
            'searchstr' => $params['searchstr'],
            'cfields' => $popt['filter_cfields'],
        ];

        // We don't need lazy load here.
        $opt['lazy'] = 0;

        $results = [
            'courses' => theme_mb2nl_course_list($opt),
        ];

        return $results;

    }




    /**
     * Describes the parameters for submit_grading_form webservice.
     * @return external_function_parameters
     * @since  Moodle 3.1
     */
    public static function set_course_filters_parameters() {
        return new external_function_parameters(
            [
                'formdata' => new external_value(PARAM_RAW, 'The data from the grading form, encoded as a json array'),
                'page' => new external_value(PARAM_INT, 'Pagination current page number'),
                'searchstr' => new external_value(PARAM_RAW, 'Search query'),
            ]
        );
    }



    /**
     * Describes the return for submit_grading_form
     * @return external_function_parameters
     * @since  Moodle 3.1
     */
    public static function set_course_filters_returns() {
        return new external_single_structure(
            [
                'courses' => new external_value(PARAM_RAW, 'The data from the grading form, encoded as a json array'),
                'warnings' => new external_warnings(),
            ]
        );
    }



    /**
     *
     * Method to get a list of all services.
     *
     */
    public static function course_quickview($course) {
        global $PAGE;

        $context = context_system::instance();
        $PAGE->set_context($context);

        $params = self::validate_parameters(self::course_quickview_parameters(), [
            'course' => $course,
        ]);

        $course = theme_mb2nl_course_quickview($params['course']);

        $results = [
            'course' => $course,
            'courseid' => $params['course'],
        ];

        return $results;

    }





    /**
     * Describes the parameters for submit_grading_form webservice.
     * @return external_function_parameters
     * @since  Moodle 3.1
     */
    public static function course_quickview_parameters() {
        return new external_function_parameters(
            [
                'course' => new external_value(PARAM_INT, 'Course id'),
            ]
        );
    }



    /**
     * Describes the return for submit_grading_form
     * @return external_function_parameters
     * @since  Moodle 3.1
     */
    public static function course_quickview_returns() {
        return new external_single_structure(
            [
                'course' => new external_value(PARAM_RAW, 'Course'),
                'courseid' => new external_value(PARAM_INT, 'Course ID'),
                'warnings' => new external_warnings(),
            ]
        );
    }



    /**
     *
     * Method to get a list of all services.
     *
     */
    public static function event_details($eventid) {

        global $PAGE;

        $context = context_system::instance();
        $PAGE->set_context($context);

        $params = self::validate_parameters(self::event_details_parameters(), [
            'eventid' => $eventid,
        ]);

        // To check event capability
        // check calendar/externallib.php, line 342.
        $results = [
            'event' => theme_mb2nl_event_details($params['eventid']),
            'eventid' => $params['eventid'],
        ];

        return $results;

    }




    /**
     * Describes the parameters for submit_grading_form webservice.
     * @return external_function_parameters
     * @since  Moodle 3.1
     */
    public static function event_details_parameters() {
        return new external_function_parameters(
            ['eventid' => new external_value(PARAM_INT, 'Event ID')]
        );
    }




    /**
     * Describes the return for submit_grading_form
     * @return external_function_parameters
     * @since  Moodle 3.1
     */
    public static function event_details_returns() {
        return new external_single_structure(
            [
                'event' => new external_value(PARAM_RAW, 'Event details'),
                'eventid' => new external_value(PARAM_INT, 'Event ID'),
                'warnings' => new external_warnings(),
            ]
        );
    }




    /**
     *
     * Method to get a list of all services.
     *
     */
    public static function course_modules($courseid, $modid, $sid, $ptype, $playout, $uparamsection, $uparamid) {
        global $PAGE;

        $context = context_system::instance();
        $PAGE->set_context($context);

        $params = self::validate_parameters(self::course_modules_parameters(), [
            'courseid' => $courseid,
            'modid' => $modid,
            'sid' => $sid,
            'ptype' => $ptype,
            'playout' => $playout,
            'uparamsection' => $uparamsection,
            'uparamid' => $uparamid,
        ]);

        $course = get_course($params['courseid']);
        $uparams = [
            'uparam_section' => $params['uparamsection'],
            'uparam_id' => $params['uparamid'],
        ];

        $results = [
            'sections' => theme_mb2nl_module_section_items($course, $params['modid'], $params['sid'], $params['ptype'],
            $params['playout'], $uparams),
        ];

        return $results;

    }




    /**
     * Describes the parameters for submit_grading_form webservice.
     * @return external_function_parameters
     * @since  Moodle 3.1
     */
    public static function course_modules_parameters() {
        return new external_function_parameters(
            [
                'courseid' => new external_value(PARAM_INT, 'Course ID'),
                'modid' => new external_value(PARAM_INT, 'Current module ID'),
                'sid' => new external_value(PARAM_INT, 'Current section ID'),
                'ptype' => new external_value(PARAM_TEXT, 'Moodle pagetype'),
                'playout' => new external_value(PARAM_TEXT, 'Moodle pagelayout'),
                'uparamsection' => new external_value(PARAM_INT, 'Section id from URL - older versions of Moodle'),
                'uparamid' => new external_value(PARAM_INT, 'Section id from URL'),
            ]
        );
    }



    /**
     * Describes the return for submit_grading_form
     * @return external_function_parameters
     * @since  Moodle 3.1
     */
    public static function course_modules_returns() {
        return new external_single_structure(
            [
                'sections' => new external_value(PARAM_RAW, 'Course scetion list'),
                'warnings' => new external_warnings(),
            ]
        );
    }






    /**
     *
     * Method to get a list of all services.
     *
     */
    public static function course_enrolment_modules($courseid, $viewhidden) {
        global $PAGE;

        $context = context_system::instance();
        $PAGE->set_context($context);

        $params = self::validate_parameters(self::course_enrolment_modules_parameters(), [
            'courseid' => $courseid,
            'viewhidden' => $viewhidden,
        ]);

        $course = get_course($params['courseid']);

        $results = [
            'sections' => theme_mb2nl_module_enrolment_section_items($course, $params['viewhidden']),
        ];

        return $results;

    }




    /**
     * Describes the return for submit_grading_form
     * @return external_function_parameters
     * @since  Moodle 3.1
     */
    public static function course_enrolment_modules_returns() {
        return new external_single_structure(
            [
                'sections' => new external_value(PARAM_RAW, 'Course scetion list'),
                'warnings' => new external_warnings(),
            ]
        );
    }



    /**
     * Describes the parameters for submit_grading_form webservice.
     * @return external_function_parameters
     * @since  Moodle 3.1
     */
    public static function course_enrolment_modules_parameters() {
        return new external_function_parameters(
            [
                'courseid' => new external_value(PARAM_INT, 'Course ID'),
                'viewhidden' => new external_value(PARAM_INT, 'User can view (or not) hidden acativities'),
            ]
        );
    }






    /**
     *
     * Method to get a list of all services.
     *
     */
    public static function course_chome($courseid, $playout, $ptype, $section) {
        global $PAGE, $USER;

        $output = '';
        $context = context_system::instance();
        $PAGE->set_context($context);

        $params = self::validate_parameters(self::course_chome_parameters(), [
            'courseid' => $courseid,
            'playout' => $playout,
            'ptype' => $ptype,
            'section' => $section,
        ]);

        $course = get_course($params['courseid']);

        $progressopts = [
            'circle' => true,
            'fcolor' => 'transparent',
            's' => 42,
            'bs' => 2,
            'bcolor' => theme_mb2nl_theme_setting($PAGE, 'tgsdbdark') ? 'rgba(255,255,255,.15)' : 'rgba(37,161,142,.16)',
            'text' => true,
        ];

        $output .= '<div class="chome-header position-relative">';
        $output .= '<img class="course-image lazy" src="' . theme_mb2nl_course_image_url($course->id, true) . '" alt="' .
        theme_mb2nl_format_str($course->fullname) . '">';
        $output .= '<div class="course-title position-relative' . theme_mb2nl_bsfcls(1, '', 'between', 'center') . '">';
        $output .= '<h2 class="h5 mb-0 mt-0">' . theme_mb2nl_format_str($course->fullname) . '</h2>';
        $output .= theme_mb2nl_course_progressbar($progressopts, $params['courseid']);
        $output .= '</div>'; // ...course-title
        $output .= '</div>'; // ...course-header
        $output .= '<div class="course-fileds mt-5">';
        $output .= theme_mb2nl_course_fields($params['courseid'], false);
        $output .= '</div>'; // ...course-fileds
        $output .= '<div class="course-slogan mt-5">';
        $output .= '<h3 class="h5">' . get_string('coursesummary') . '</h3>';
        $output .= theme_mb2nl_moreless(theme_mb2nl_get_mb2course_description($course));
        $output .= '</div>'; // ...course-slogan
        $output .= '<div class="course-sminstructors mt-5">';
        $output .= '<h3 class="h5">' . get_string('headinginstructors', 'theme_mb2nl'). '</h3>';
        $output .= theme_mb2nl_moreless(theme_mb2nl_contacts_content($course));
        $output .= '</div>'; // ...course-instructors
        $output .= theme_mb2nl_course_tags_block($params['courseid'], true);
        $output .= theme_mb2nl_full_screen_module_backlink(false, false, $course, $params['playout'], $params['ptype'],
        $params['section']);

        $results = [
            'chome' => $output,
        ];

        return $results;

    }




    /**
     * Describes the parameters for submit_grading_form webservice.
     * @return external_function_parameters
     * @since  Moodle 3.1
     */
    public static function course_chome_parameters() {
        return new external_function_parameters(
            [
                'courseid' => new external_value(PARAM_INT, 'Course ID'),
                'playout' => new external_value(PARAM_TEXT, 'Moodle pagelayout'),
                'ptype' => new external_value(PARAM_TEXT, 'Moodle pagetype'),
                'section' => new external_value(PARAM_INT, 'Course section ID (custom course layout)'),
            ]
        );
    }



    /**
     * Describes the return for submit_grading_form
     * @return external_function_parameters
     * @since  Moodle 3.1
     */
    public static function course_chome_returns() {
        return new external_single_structure(
            [
                'chome' => new external_value(PARAM_RAW, 'Course home content'),
                'warnings' => new external_warnings(),
            ]
        );
    }





    /**
     *
     * Method to get a course teacher list.
     *
     */
    public static function course_teacher_list($courseid, $ptype) {
        global $CFG, $PAGE, $USER;

        $output = '';
        $context = context_system::instance();
        $PAGE->set_context($context);

        $params = self::validate_parameters(self::course_teacher_list_parameters(), [
            'courseid' => $courseid,
            'ptype' => $ptype,
        ]);

        $showdetails = $params['ptype'] === 'enrol-index' ? 1 : 0;
        $course = get_course($params['courseid']);
        $teachers = theme_mb2nl_course_teachers_data($params['courseid'], $showdetails);
        $email = theme_mb2nl_email_display($course);
        $teachermessage = theme_mb2nl_message_display();

        // Get teacher rating.
        if ($showdetails && $reviews = theme_mb2nl_is_review_plugin()) {
            if (!class_exists('Mb2reviewsHelper')) {
                require($CFG->dirroot . '/local/mb2reviews/classes/helper.php');
            }

            $tratingobj = theme_mb2nl_review_obj($params['courseid'], true);
        }

        foreach ($teachers as $teacher) {
            $output .= '<li class="instructor-' . $teacher['id'] . theme_mb2nl_bsfcls(1, '', '', 'center') . '">';
            $output .= '<div class="instructor-image details">';
            $output .= $teacher['picture'];

            if ($showdetails) {
                $output .= '<div class="instructor-info mt-3 lhsmall">';

                if ($reviews && $trating = $tratingobj->teachers[$teacher['id']]->rating) {
                    $output .= '<div class="info-rating mt-2">';
                    $output .= '<i class="glyphicon glyphicon-star mr-2"></i>';
                    $output .= $trating;
                    $output .= ' (' . get_string('ratingscount', 'local_mb2reviews', ['ratings' =>
                    $tratingobj->teachers[$teacher['id']]->rating_count]) . ')';
                    $output .= '</div>';

                    $output .= '<div class="info-reviews mt-2">';
                    $output .= '<i class="fa fa-trophy mr-2"></i>';
                    $output .= get_string('reviewscount', 'local_mb2reviews', ['reviews' =>
                    $tratingobj->teachers[$teacher['id']]->reviews_count]);
                    $output .= '</div>';
                }

                $output .= '<div class="info-courses mt-2"><i class="fa fa-book mr-2"></i>' .
                get_string('teachercourses', 'theme_mb2nl', ['courses' => $teacher['coursescount']]) . '</div>';
                $output .= '<div class="info-students mt-2"><i class="fa fa-graduation-cap mr-2"></i>' .
                get_string('teacherstudents', 'theme_mb2nl', ['students' => $teacher['studentscount']]) . '</div>';

                $output .= '</div>'; // ...instructor-info
            }

            $output .= '</div>'; // ...instructor-image

            $output .= '<div class="instructor-details">';

            $output .= '<div class="details-header">';
            $output .= '<h3 class="h5 instructor-name">' . $teacher['firstname'] . ' ' . $teacher['lastname'] . '</h3>';
            $output .= '</div>';

            if ($teacher['description']) {
                $output .= '<div class="instructor-description">';
                $output .= theme_mb2nl_get_user_description($teacher['description'], $teacher['id']);
                $output .= '</div>';
            }

            if ($email || $teachermessage) {
                $output .= '<div class="instructor-meta">';
                $output .= $email ? '<span class="contact"><a href="mailto:' .$teacher['email']. '"><i class="fa fa-envelope"></i>'.
                $teacher['email'] . '</a></span>' : '';

                if ($teachermessage) {
                    $messageurl = new moodle_url('/message/index.php', ['id' => $teacher['id']]);
                    $output .= '<span class="message"><a href="' .  $messageurl . '"><i class="fa fa-comment"></i>' .
                    get_string('sendmessage', 'message') . '</a></span>';
                }

                $output .= '</div>';
            }

            $output .= '</div>'; // ...instructor-details
            $output .= '</li>';
        }

        $results = [
            'teachers' => $output,
        ];

        return $results;

    }





    /**
     * Describes the parameters for submit_grading_form webservice.
     * @return external_function_parameters
     * @since  Moodle 3.1
     */
    public static function course_teacher_list_parameters() {
        return new external_function_parameters(
            [
                'courseid' => new external_value(PARAM_INT, 'Course ID'),
                'ptype' => new external_value(PARAM_TEXT, 'Moodle pagetype'),
            ]
        );
    }





    /**
     * Describes the return for submit_grading_form
     * @return external_function_parameters
     * @since  Moodle 3.1
     */
    public static function course_teacher_list_returns() {
        return new external_single_structure(
            [
                'teachers' => new external_value(PARAM_RAW, 'List of course teachers'),
                'warnings' => new external_warnings(),
            ]
        );
    }




    /**
     *
     * Method to get a list of all services.
     *
     */
    public static function course_filters($categoryid, $tagid, $teacherid) {
        global $PAGE, $USER;

        $output = '';
        $context = context_system::instance();
        $PAGE->set_context($context);

        $params = self::validate_parameters(self::course_filters_parameters(), [
            'categoryid' => $categoryid,
            'tagid' => $tagid,
            'teacherid' => $teacherid,
        ]);

        $output .= theme_mb2nl_courses_filter_categories($params['categoryid']);
        $output .= theme_mb2nl_courses_filter_tags($tagid);
        $output .= theme_mb2nl_courses_filter_instructors($params['teacherid']);
        $output .= theme_mb2nl_courses_filter_price();
        $output .= theme_mb2nl_filter_cfileds();

        $results = [
            'filters' => $output,
        ];

        return $results;

    }




    /**
     * Describes the parameters for submit_grading_form webservice.
     * @return external_function_parameters
     * @since  Moodle 3.1
     */
    public static function course_filters_parameters() {
        return new external_function_parameters(
            [
                'categoryid' => new external_value(PARAM_INT, 'Category ID'),
                'tagid' => new external_value(PARAM_RAW, 'Tag ID or comma-separated tag IDs'),
                'teacherid' => new external_value(PARAM_INT, 'Teacher ID'),
            ]
        );
    }



    /**
     * Describes the return for submit_grading_form
     * @return external_function_parameters
     * @since  Moodle 3.1
     */
    public static function course_filters_returns() {
        return new external_single_structure(
            [
                'filters' => new external_value(PARAM_RAW, 'Course filters html'),
                'warnings' => new external_warnings(),
            ]
        );
    }




    /**
     *
     * Method to get a list of all services.
     *
     */
    public static function admin_dashboard() {
        global $PAGE, $USER;

        $output = '';
        $context = context_system::instance();
        $PAGE->set_context($context);

        require_login();

        if (!is_siteadmin()) {
            return;
            die;
        }

        $params = self::validate_parameters(self::admin_dashboard_parameters(), []);

        $users = theme_mb2nl_dashboard_users();
        $usersactive = theme_mb2nl_dashboard_activeusers();
        $usersonline = theme_mb2nl_dashboard_onlineusers();
        $onlinepct = $users > 0 ? round($usersonline / $users, 2) : 0;
        $activepct = $users > 0 ? round($usersactive / $users, 2) : 0;
        $chartopts = ['color' => theme_mb2nl_theme_setting($PAGE, 'color_info'), 'size' => 180, 'sw' => 4];

        $output .= '<div class="row' . theme_mb2nl_bsfcls(1, '', '', 'center') . '">';
        $output .= '<div class="col-lg-6">';
        $output .= '<div class="chart-wrap-outer">';
        $output .= '<h3 class="chart-wrap-title h5 mb-0">' . get_string('users') . '</h3>';
        $output .= '<div class="chart-wrap' . theme_mb2nl_bsfcls(1, 'wrap', 'center', 'center') . '">';
        $output .= theme_mb2nl_chart_semicircle(1, theme_mb2nl_dashboard_users(), get_string('users'), $chartopts);
        $output .= theme_mb2nl_chart_semicircle($activepct, $usersactive, get_string('activeusers'), $chartopts);
        $output .= theme_mb2nl_chart_semicircle($onlinepct, $usersonline, get_string('onlineusers', 'theme_mb2nl'), $chartopts);
        $output .= '</div>'; // ...chart-wrap
        $output .= '</div>'; // ...chart-wrap-outer
        $output .= '</div>'; // ...col-lg-6

        $output .= '<div class="col-lg-6">';
        $output .= theme_mb2nl_dashboard();
        $output .= '</div>'; // ...col-lg-6
        $output .= '</div>'; // ...row

        $results = [
            'dashboard' => $output,
        ];

        return $results;

    }




    /**
     * Describes the parameters for submit_grading_form webservice.
     * @return external_function_parameters
     * @since  Moodle 3.1
     */
    public static function admin_dashboard_parameters() {
        return new external_function_parameters(
            []
        );
    }



    /**
     * Describes the return for submit_grading_form
     * @return external_function_parameters
     * @since  Moodle 3.1
     */
    public static function admin_dashboard_returns() {
        return new external_single_structure(
            [
                'dashboard' => new external_value(PARAM_RAW, 'Admin dashboard HTML'),
                'warnings' => new external_warnings(),
            ]
        );
    }





    /**
     *
     * Method to get a custom student dashboard.
     *
     */
    public static function student_dashboard() {
        global $CFG, $PAGE, $USER;

        require_once($CFG->dirroot .'/course/lib.php');

        $output = '';
        $context = context_system::instance();
        $PAGE->set_context($context);

        require_login();

        $params = self::validate_parameters(self::student_dashboard_parameters(), []);

        $chartopts = ['color' => theme_mb2nl_theme_setting($PAGE, 'color_info'), 'size' => 180, 'sw' => 4];
        $courses = count(enrol_get_my_courses());
        $cprogress = theme_mb2nl_mycourses_timeline(COURSE_TIMELINE_INPROGRESS);
        $cprogresspct = $courses > 0 ? round($cprogress / $courses, 2) : 0;
        $ccopleted = theme_mb2nl_mycourses_timeline(COURSE_TIMELINE_PAST);
        $ccopletedpct = $courses > 0 ? round($ccopleted / $courses, 2) : 0;

        $output .= '<div class="row' . theme_mb2nl_bsfcls(1, '', '', 'center') . '">';
        $output .= '<div class="col-lg-6">';
        $output .= theme_mb2nl_cdshb_welcomebox();
        $output .= '</div>'; // ...col-lg-6
        $output .= '<div class="col-lg-6">';
        $output .= '<div class="chart-wrap-outer">';
        $output .= '<h3 class="chart-wrap-title h5 mb-0">' . get_string('mycourses') . '</h3>';
        $output .= '<div class="chart-wrap' . theme_mb2nl_bsfcls(1, 'wrap', 'center', 'center') . '">';
        $output .= theme_mb2nl_chart_semicircle(1, $courses, get_string('allcourses', 'search'), $chartopts);
        $output .= theme_mb2nl_chart_semicircle($cprogresspct, $cprogress, get_string('inprogress'), $chartopts);
        $output .= theme_mb2nl_chart_semicircle($ccopletedpct, $ccopleted, get_string('pastcourses', 'theme_mb2nl'), $chartopts);
        $output .= '</div>'; // ...chart-wrap-outer
        $output .= '</div>'; // ...chart-wrap-outer
        $output .= '</div>'; // ...col-lg-6
        $output .= '</div>'; // ...row

        $results = [
            'dashboard' => $output,
        ];

        return $results;

    }




    /**
     * Describes the parameters for custom student dashboard.
     * @return external_function_parameters
     * @since  Moodle 3.1
     */
    public static function student_dashboard_parameters() {
        return new external_function_parameters(
            []
        );
    }



    /**
     * Describes the return for custom student dashboard.
     * @return external_function_parameters
     * @since  Moodle 3.1
     */
    public static function student_dashboard_returns() {
        return new external_single_structure(
            [
                'dashboard' => new external_value(PARAM_RAW, 'Student dashboard HTML'),
                'warnings' => new external_warnings(),
            ]
        );
    }



}
