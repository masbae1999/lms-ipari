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

defined('MOODLE_INTERNAL') || die();

global $PAGE, $COURSE;

$iscourse = (isset($COURSE->id) && $COURSE->id > 1);
$courseaccess = theme_mb2nl_site_access();
$canmanage = ['admin', 'manager', 'editingteacher', 'teacher'];
$coursemanagestring = in_array($courseaccess, $canmanage) ? get_string('coursemanagement', 'theme_mb2nl') :
get_string('coursedashboard', 'theme_mb2nl');
$cname = theme_mb2nl_format_str($COURSE->fullname);

// Start HTML.
$html = '';

$html .= '<div class="modal fade" id="course-panel" role="dialog" tabindex="0" aria-labelledby="course-panel"
aria-describedby="course-panel" aria-modal="true">';
$html .= '<div class="modal-dialog modal-lg" role="document">';
$html .= '<div class="modal-content">';
$html .= '<div class="modal-header">';
$html .= '<button type="button" class="close" data-dismiss="modal" title="' .
get_string('closebuttontitle') . '"><span aria-hidden="true">&times;</span></button>';
$html .= '<h4 class="modal-title" id="course-panel-label">'.$coursemanagestring.': '.theme_mb2nl_wordlimit($cname, 6) . '</h4>';
$html .= '</div>';
$html .= '<div class="course-panel-content">';

if (in_array($courseaccess, $canmanage)) {
    $html .= '<div class="teacher-panel">';
    $html .= '<div class="container-fluid">';
    $html .= '<div class="row">';

    if (theme_mb2nl_is_module_context()) {
        $html .= '<div class="col-md-3">' . theme_mb2nl_course_panel('activities') . theme_mb2nl_course_panel('badges') . '</div>';
        $html .= '<div class="col-md-3">' . theme_mb2nl_course_panel('modulenav') . '</div>';
        $html .= '<div class="col-md-3">'.theme_mb2nl_course_panel('course_settings').theme_mb2nl_course_panel('badges2').'</div>';
        $html .= '<div class="col-md-3">' . theme_mb2nl_course_panel('students') . theme_mb2nl_course_panel('students2') . '</div>';
    } else {
        $html .= '<div class="col-md-3">' . theme_mb2nl_course_panel('activities') . '</div>';
        $html .= '<div class="col-md-3">' . theme_mb2nl_course_panel('qbank') . theme_mb2nl_course_panel('badges') . '</div>';
        $html .= '<div class="col-md-3">'.theme_mb2nl_course_panel('course_settings').theme_mb2nl_course_panel('badges2').'</div>';
        $html .= '<div class="col-md-3">' . theme_mb2nl_course_panel('students') . theme_mb2nl_course_panel('students2') . '</div>';
    }

    $html .= '</div>';
    $html .= '</div>';
    $html .= '</div>';
}

$html .= '</div>';
$html .= '<button class="themereset themekeynavonly" data-dismiss="modal">' . get_string('closebuttontitle') . '</button>';
$html .= '</div>';
$html .= '</div>';
$html .= '</div>';

if (theme_mb2nl_theme_setting($PAGE, 'coursepanel') && $iscourse && $courseaccess !== 'none' && $courseaccess !== 'user') {
    echo $html;
}
