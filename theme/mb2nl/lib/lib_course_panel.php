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
 * @package   theme_mb2nl
 * @copyright 2017 - 2024 Mariusz Boloz (lmsstyle.com)
 * @license   PHP and HTML: http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later. Other parts: http://themeforest.net/licenses
 *
 */

/**
 *
 * Method to display course activities
 *
 *
 */
function theme_mb2nl_course_panel($name = '') {

    global $PAGE, $COURSE, $USER;

    $output = '';
    $i = 0;
    $boxes = theme_mb2nl_teacher_boxes();
    $iscourse = theme_mb2nl_is_course();
    $courseaccess = theme_mb2nl_site_access();
    $cls = '';
    $data = '';

    foreach ($boxes as $k => $box) {
        $show = false;
        $i++;

        if (in_array($courseaccess, $box['access'])) {
            $show = true;
        }

        if (isset($box['shown']) && !$box['shown']) {
            $show = false;
        }

        if ($show && $iscourse && $k === $name) {

            $output .= $box['title'] ? '<a href="#panebox-' . $i . '" class="sr-only sr-only-focusable">' .
            get_string('skipel', 'theme_mb2nl', ['skipel' => $box['title']]) . '</a>' : '';
            $output .= '<div class="box box-' . $k . '">';

            if ($box['title']) {
                $output .= '<h3>';
                $output .= '<i class="' . $box['icon'] . '"></i>';
                $output .= $box['title'];
                $output .= '</h3>';
            }

            if (isset($box['content'])) {
                $output .= $box['content'];
            }

            if (is_array($box['links'])) {

                $output .= '<ul class="boxlist">';

                foreach ($box['links'] as $link) {

                    $allowedit = isset($link['edit']) ? $link['edit'] : true;

                    if (isset($link['showif'])) {
                        $allowedit = $link['showif'];
                    }

                    $details = isset($link['details']) ? '<span class="details">' . $link['details'] . '</span>' : '';
                    $icon = isset($link['icon']) ? '<img src="' . $link['icon'] . '" alt="">' : '';

                    // Check custom class.
                    if (isset($link['class'])) {
                        $cls = ' class="' . $link['class'] . '"';
                    }

                    // ...data-scrollpos
                    if (isset($link['data-scrollpos'])) {
                        $data = ' data-scrollpos="' . $link['data-scrollpos'] . '"';
                    }

                    if ($allowedit) {
                        if (! $link['url']) {
                            $output .= '<li>' . $icon . '<span class="nolink-item">' . $link['title'] . '</span>'.$details.'</li>';
                        } else {
                            $output .= '<li><a href="' . $link['url'] . '"' . $cls . $data . '>' . $icon . $link['title'] .
                            $details . '</a></li>';
                        }
                    }
                }

                $output .= '</ul>';
            }

            $output .= '</div>';
            $output .= $box['title'] ? '<span id="panebox-' . $i . '"></span>' : '';

        }

    }

    return $output;

}







/**
 *
 * Method to get teacher links
 *
 *
 */
function theme_mb2nl_teacher_boxes() {
    global $CFG;

    $boxes = [
        'activities' => [
            'access' => ['admin', 'manager', 'editingteacher', 'teacher', 'coursecreator', 'student'],
            'title' => get_string('activities'),
            'desc' => '',
            'icon' => 'fa fa-list-ul',
            'links' => theme_mb2nl_get_activities(),
        ],
        'qbank' => [
            'access' => ['admin', 'manager', 'editingteacher', 'teacher'],
            'title' => get_string('questionbank', 'question'),
            'desc' => '',
            'icon' => 'fa fa-question',
            'links' => theme_mb2nl_links_qbank(),
        ],
        'badges' => [
            'access' => ['admin', 'manager', 'editingteacher'],
            'title' => get_string('coursebadges', 'badges'),
            'desc' => '',
            'shown' => $CFG->enablebadges,
            'icon' => 'fa fa-certificate',
            'links' => theme_mb2nl_links_badges(),
        ],
        'badges2' => [
            'access' => ['teacher'],
            'title' => get_string('coursebadges', 'badges'),
            'desc' => '',
            'shown' => $CFG->enablebadges,
            'icon' => 'fa fa-certificate',
            'links' => theme_mb2nl_links_badges(false),
        ],
        'course_settings' => [
            'access' => ['admin', 'manager', 'editingteacher'],
            'title' => get_string('course'),
            'desc' => '',
            'icon' => 'fa fa-book',
            'links' => theme_mb2nl_links_course(),
        ],
        'students' => [
            'access' => ['admin', 'manager', 'editingteacher'],
            'title' => get_string('defaultcoursestudents'),
            'desc' => '',
            'icon' => 'fa fa-graduation-cap',
            'links' => theme_mb2nl_links_students(),
        ],
        'students2' => [
            'access' => ['teacher'],
            'title' => get_string('defaultcoursestudents'),
            'desc' => '',
            'icon' => 'fa fa-graduation-cap',
            'links' => theme_mb2nl_links_students(false),
        ],
        'modulenav' => [
            'access' => ['admin', 'manager', 'editingteacher'],
            'title' => theme_mb2nl_module_nav(true),
            'desc' => '',
            'show' => theme_mb2nl_is_module_context(),
            'icon' => 'fa fa-cogs',
            'links' => '',
            'content' => theme_mb2nl_module_nav(),
        ],

    ];

    return $boxes;

}








/**
 *
 * Method to get course contacts
 *
 *
 */
function theme_mb2nl_email_display($ccourse=null) {
    global $COURSE, $CFG, $USER;

    $cobj = !is_null($ccourse) ? $ccourse : $COURSE;
    $context = context_course::instance($cobj->id);
    $enrolled = is_enrolled($context, $USER->id);

    if ($CFG->defaultpreference_maildisplay == 2 && $enrolled) {
        return true;
    } else if ($CFG->defaultpreference_maildisplay == 1) {
        return true;
    }

    return false;

}





/**
 *
 * Method to get course contacts
 *
 *
 */
function theme_mb2nl_message_display() {
    global $CFG;

    if (isloggedin() && has_capability('moodle/site:sendmessage', context_system::instance()) && $CFG->messaging &&
    !isguestuser()) {
        return true;
    }

    return false;

}


/**
 *
 * Method to get course contacts
 *
 *
 */
function theme_mb2nl_contacts_content($ccourse=null) {

    global $CFG;

    $output = '';
    $teacheremail = theme_mb2nl_email_display($ccourse);
    $teachermessage = theme_mb2nl_message_display();

    $cid = !is_null($ccourse) ? $ccourse->id : 0;
    $teachers = theme_mb2nl_course_teachers_data($cid);

    if (!count($teachers)) {
        return get_string('nothingtodisplay');
    }

    $output .= '<ul class="course-contacts sm-instructors m-0 px-0">';

    foreach ($teachers as $teacher) {
        $picture = $teacher['picture'];
        $ispicture = $picture ? $picture : '';
        $messageurl = new moodle_url('/message/index.php', ['id' => $teacher['id']]);

        $output .= '<li class="contact-item lhsmall py-2' . theme_mb2nl_bsfcls(1, '', '', 'center') . '">';
        $output .= '<div class="user-picture mr-3">' . $ispicture . '</div>';
        $output .= '<div class="user-details">';
        $output .= '<span class="name fwheadings tcolorh' . theme_mb2nl_bsfcls(2, '', '', 'center') . '">' .
        $teacher['firstname'] . ' ' . $teacher['lastname'] . '</span>';

        if ($teacheremail || $teachermessage) {
            $output .= '<div class="user-contacts tcolorn mt-2' . theme_mb2nl_bsfcls(1, 'column', '', '') . '">';
            $output .= $teacheremail ? '<span class="contact d-block"><a class="' . theme_mb2nl_bsfcls(2, '', '', 'center')
            . '" href="mailto:' .$teacher['email'] . '"><i class="ri-mail-send-line mr-2"></i><span class="text-break tsizesmall">'.
            $teacher['email'] . '</span></a></span>' : '';
            $output .= $teachermessage ? '<span class="message d-block"><a class="' . theme_mb2nl_bsfcls(2, '', '', 'center')
            . '" href="' .  $messageurl . '"><i class="ri-chat-2-line mr-2"></i><span class="text-break tsizesmall">' .
            get_string('sendmessage', 'message') . '</span></a></span>' : '';
            $output .= '</div>';
        }

        $output .= '</div>';
        $output .= '</li>';
    }

    $output .= '</ul>';

    return $output;

}






/**
 *
 * Method to get links of course
 *
 *
 */
function theme_mb2nl_links_students($edit=true) {

    global $CFG, $COURSE;
    require_once($CFG->libdir. '/enrollib.php');
    $context = context_course::instance($COURSE->id);

    return [
        ['url' => new moodle_url('/grade/report/index.php', ['id' => $COURSE->id]), 'title' => get_string('grades', 'grades')],
        ['url' => new moodle_url('/user/index.php', ['id' => $COURSE->id]), 'title' => get_string('participants')],
        ['edit' => $edit, 'url' => new moodle_url('/group/index.php', ['id' => $COURSE->id]), 'title' => get_string('groups')],
        ['edit' => $edit, 'url' => new moodle_url('/enrol/instances.php', ['id' => $COURSE->id]), 'title' =>
        get_string('enrolmentinstances', 'enrol')],
        ['url' => '', 'title' => get_string('reports')],
        ['url' => new moodle_url('/report/progress/index.php', ['course' => $COURSE->id]), 'title' =>
        get_string('activitiescompleted', 'completion')],
        ['url' => new moodle_url('/report/completion/index.php', ['course' => $COURSE->id]), 'title' =>
        get_string('coursecompletion', 'completion')],
        ['url' => new moodle_url('/report/log/index.php', ['id' => $COURSE->id]), 'title' => get_string('logs')],
        ['url' => new moodle_url('/report/loglive/index.php', ['id' => $COURSE->id]), 'title' =>
        get_string('livelogs', 'theme_mb2nl')],
        ['url' => new moodle_url('/report/participation/index.php', ['id' => $COURSE->id]), 'title' =>
        get_string('courseparticipation', 'theme_mb2nl')],
        ['url' => new moodle_url('/report/outline/index.php', ['id' => $COURSE->id]), 'title' => get_string('activities')],
    ];

}





/**
 *
 * Method to get links of course
 *
 *
 */
function theme_mb2nl_links_course() {

    global $CFG, $COURSE, $PAGE;

    if ($CFG->version < 2023100900) { // Up to Moodle 4.3.
        user_preference_allow_ajax_update('mb2_scrollpos', PARAM_INT);
    }

    $scrollpos = theme_mb2nl_user_preference('mb2_scrollpos', 0);

    return [
        ['url' => theme_mb2nl_editmode_link(), 'title' => $PAGE->user_is_editing() ? get_string('turneditingoff') :
        get_string('turneditingon'), 'class' => 'save-location', 'data-scrollpos' => $scrollpos],
        ['url' => new moodle_url('/course/edit.php', ['id' => $COURSE->id]), 'title' => get_string('editcoursesettings')],
        ['url' => new moodle_url('/course/completion.php', ['id' => $COURSE->id]), 'title' => get_string('coursecompletion')],
        ['url' => new moodle_url('/admin/tool/lp/coursecompetencies.php', ['courseid' => $COURSE->id]), 'title' =>
        get_string('competencies', 'competency')],
        ['url' => new moodle_url('/course/admin.php', ['courseid' => $COURSE->id]), 'title' => get_string('courseadministration')],
        ['url' => new moodle_url('/course/reset.php', ['id' => $COURSE->id]), 'title' => get_string('reset')],
        ['url' => new moodle_url('/backup/backup.php', ['id' => $COURSE->id]), 'title' => get_string('backup')],
        ['url' => new moodle_url('/backup/restorefile.php', ['contextid' => $PAGE->context->id]), 'title' => get_string('restore')],
        ['url' => new moodle_url('/backup/import.php', ['id' => $COURSE->id]), 'title' => get_string('import')],
        ['url' => new moodle_url('/admin/tool/recyclebin/index.php', ['contextid' => $PAGE->context->id]), 'title' =>
        get_string('recyclebin', 'theme_mb2nl')],
        ['url' => new moodle_url('/filter/manage.php', ['contextid' => $PAGE->context->id]), 'title' =>
        get_string('filters', 'admin')],
        ['url' => new moodle_url('/admin/tool/monitor/managerules.php', ['courseid' => $COURSE->id]), 'title' =>
        get_string('eventmonitoring', 'theme_mb2nl')],
        ['url' => new moodle_url('/course/admin.php', ['courseid' => $COURSE->id]), 'title' => get_string('morenavigationlinks')],
    ];

}





/**
 *
 * Method to get links of course badges
 *
 *
 */
function theme_mb2nl_links_badges($edit=true) {

    global $COURSE;

    return [
        ['url' => new moodle_url('/badges/index.php', ['type' => 2, 'id' => $COURSE->id]), 'title' =>
        get_string('managebadges', 'badges')],
        ['edit' => $edit, 'url' => new moodle_url('/badges/newbadge.php', ['type' => 2, 'id' => $COURSE->id]), 'title' =>
        get_string('newbadge', 'badges')],
    ];

}






/**
 *
 * Method to get links of questions bank
 *
 *
 */
function theme_mb2nl_links_qbank() {

    global $COURSE;

    return [
        ['url' => new moodle_url('/question/edit.php', ['courseid' => $COURSE->id]), 'title' =>
        get_string('questionbank', 'question')],
        ['url' => new moodle_url('/question/bank/managecategories/category.php', ['courseid' => $COURSE->id]), 'title' =>
        get_string('questioncategory', 'question')],
        ['url' => new moodle_url('/question/bank/importquestions/import.php', ['courseid' => $COURSE->id]), 'title' =>
        get_string('import')],
        ['url' => new moodle_url('/question/bank/exportquestions/export.php', ['courseid' => $COURSE->id]), 'title' =>
        get_string('export', 'calendar')], // The calendar is because older version of Moodle.
    ];

}








/**
 *
 * Method to display module navigation list
 *
 */
function theme_mb2nl_module_nav($title=false) {

    global $PAGE;
    $output = '';

    if (!theme_mb2nl_is_module_context()) {
        return null;
    }

    $output .= '<ul class="boxlist">';

    foreach ($PAGE->settingsnav->children as $item1) {
        if ($item1->key === 'modulesettings') {
            // Set title.
            if ($title) {
                return $item1->text;
            }

            foreach ($item1->children as $item2) {
                $output .= '<li>';
                $output .= theme_mb2nl_module_nav_item($item2);

                if (count($item2->children) > 0) {
                    $output .= '<ul>';

                    foreach ($item2->children as $item3) {
                        $output .= '<li>';
                        $output .= theme_mb2nl_module_nav_item($item3);
                        $output .= '</li>';
                    }

                    $output .= '</ul>';
                }

                $output .= '</li>';
            }
        }
    }

    $output .= '</ul>';

    return $output;

}





/**
 *
 * Method to display module navigation
 *
 */
function theme_mb2nl_module_nav_item($item) {

    global $OUTPUT;
    $output = '';

    // Hide icons.
    $item->hideicon = true;

    $output .= $OUTPUT->render($item);

    return $output;

}





/**
 *
 * Method to display course panel link
 *
 */
function theme_mb2nl_panel_link($pos='content', $fs=false, $tt=true) {
    global $PAGE, $COURSE, $OUTPUT;

    $output = '';
    $courseaccess = theme_mb2nl_site_access();
    $canmanage = ['admin', 'manager', 'editingteacher', 'teacher'];
    $cantsee = ['none', 'user'];

    if (!in_array($courseaccess, $canmanage)) {
        return;
    }

    $coursemanagestring = get_string('coursemanagement', 'theme_mb2nl');
    $coursepanel = theme_mb2nl_theme_setting($PAGE, 'coursepanel');
    $btntext = theme_mb2nl_theme_setting($PAGE, 'cbtntext');
    $ttipattr = (!$btntext || $fs) && $tt ? ' data-toggle="tooltip"' : '';

    if ($fs && !$coursepanel) {
        return $OUTPUT->region_main_settings_menu();
    }

    if (!$coursepanel || !theme_mb2nl_is_course() || in_array($courseaccess, $cantsee)) {
        return;
    }

    if ($pos === 'content') {
        $output .= '<div id="themeskipto-coursepanel" class="sr-only sr-only-focusable"></div>';
        $output .= '<button class="manage-link panel-link themereset' . theme_mb2nl_bsfcls(2, '', 'center', 'center') . '"
        data-toggle="modal" data-target="#course-panel" title="' . $coursemanagestring . '">';
        $output .= '<span class="panel-icon"' . $ttipattr . ' title="' . $coursemanagestring . '">';
        $output .= $btntext && ! $fs ? '<span class="text">' . $coursemanagestring . '</span>' : '';
        $output .= in_array($courseaccess, $canmanage) ? '<i class="fa fa-cog"></i>' : '<i class="fa fa-dashboard"></i>';
        $output .= '</span>';
        $output .= '</button>';
    } else if ($pos === 'fixedbar' && !theme_mb2nl_is_tgsdb()) {
        $output .= '<button class="fixed-panel-link" data-toggle="modal" data-target="#course-panel" aria-hidden="true">';
        $output .= '<span>' . $coursemanagestring . '</span>';
        $output .= '</button>';
    }

    return $output;

}
