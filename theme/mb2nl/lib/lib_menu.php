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
 * Method to get langauge list
 *
 */
function theme_mb2nl_language_list($pos = 'menu', $shortcode = false, $lazy = true) {

    global $PAGE, $OUTPUT, $CFG;

    $output = '';
    $footer = $pos === 'footer' && (theme_mb2nl_theme_setting($PAGE, 'langfooter') || theme_mb2nl_is_login(true) || $shortcode);
    $menu = $pos === 'menu' && theme_mb2nl_theme_setting($PAGE, 'langmenu');
    $langimg = theme_mb2nl_theme_setting($PAGE, 'langimg');

    // For menu languages always swhow flags.
    if ($pos === 'menu' || $shortcode) {
        $langimg = true;
    }

    if (!$footer && !$menu) {
        return;
    }

    $langs = get_string_manager()->get_list_of_translations();

    if (!count($langs)) {
        return;
    }

    $strlang = get_string('language');
    $currentlang = current_language();
    $listcls = $footer ? theme_mb2nl_bsfcls(1, 'wrap', '', '') : '';
    $linkcls = $footer ? theme_mb2nl_bsfcls(2, '', '', 'center') : '';
    $linkcls2 = '';
    $lazycls = $lazy ? ' lazy' : '';
    $lazyattr = $lazy ? 'src="' . theme_mb2nl_lazy_plc() . '" data-src' : 'src';
    $listcls .= $footer ? ' lang-footer' : '';

    // Get language flags.
    $flagfile = $OUTPUT->image_url('noflag', 'theme');
    $flagimages = theme_mb2nl_filearea('langflags');
    $currentflagurl = array_key_exists($currentlang, $flagimages) ? $flagimages[$currentlang] : $flagfile;

    $currentflagimg = '<img class="lang-flag" src="' . $currentflagurl . '" alt="" />';
    $lantext = isset($langs[$currentlang]) ? $langs[$currentlang] : $strlang;
    $lantext = theme_mb2nl_get_langname($lantext);

    if ($menu) {
        $output .= '<li class="lang-item level-1 isparent onhover">';
        $output .= '<button type="button" class="themereset mb2mm-action" aria-label="' . $lantext . '">';
        $output .= $currentflagimg;
        $output .= '<span class="lang-shortname mb2mm-label" aria-hidden="true">' . str_replace('_', ' ', $currentlang). '</span>';
        $output .= '<span class="lang-fullname mb2mm-label">' . $lantext . '</span>';
        $output .= '<span class="mb2mm-arrow"></span>';
        $output .= '</button>';
        $output .= '<button type="button" class="mb2mm-toggle themereset" aria-label="' .
        get_string('togglemenuitem', 'theme_mb2nl', ['menuitem' => get_string('language')]) . '" aria-expanded="false"></button>';
        $output .= '<div class="mb2mm-ddarrow"></div>';

        $listcls = ' mb2mm-dd';
        $linkcls = 'mb2mm-action';
        $linkcls2 = ' mb2mm-label';
    }

    $output .= '<ul class="lang-list' . $listcls . '">';

    foreach ($langs as $langtype => $langname) {
        if ($langtype !== $currentlang) {
            $langname = theme_mb2nl_get_langname($langname);

            $flagurl = array_key_exists($langtype, $flagimages) ? $flagimages[$langtype] : $flagfile;

            $flafimg = $langimg ? '<img class="lang-flag' . $lazycls . '" ' . $lazyattr . '="' . $flagurl . '" alt="' .
            $langname . '">' : '';

            $output .= '<li class="level-2 ' . $langtype . '">';
            $output .= '<a class="' . $linkcls . '" href="' . new moodle_url($PAGE->url, ['lang' => $langtype]) . '"
            aria-label="' . $langname . '">';
            $output .= $flafimg;
            $output .= '<span class="lang-shortname' . $linkcls2 . '" aria-hidden="true">' . str_replace('_', ' ', $langtype)
            . '</span>';
            $output .= '<span class="lang-fullname' . $linkcls2 . '" aria-hidden="true">' . $langname . '</span>';
            $output .= '</a>';
            $output .= '</li>';
        }
    }

    $output .= '</ul>';
    $output .= $menu ? '</li>' : '';

    return $output;

}





/**
 *
 * Method to get mycourses list
 *
 */
function theme_mb2nl_mycourses_list($single=false) {

    global $PAGE, $CFG;
    $output = '';
    $courses = theme_mb2nl_get_mycourses();
    $limit = theme_mb2nl_theme_setting($PAGE, 'myclimit', 6);

    if (!count($courses) || !isloggedin() || isguestuser() || !theme_mb2nl_theme_setting($PAGE, 'mycinmenu2')) {
        return;
    }

    $output .= $single ? '<div class="mycourses">' : '<li class="mycourses level-1 isparent onhover">';

    $output .= '<a class="mb2mm-action" href="' . new moodle_url('/my/courses.php') . '">';
    $output .= '<span class="mb2mm-label">';
    $output .= get_string('mycourses');
    $output .= '</span>';
    $output .= '<span class="mb2mm-hlabel" aria-hidden="true">' . count($courses) . '</span>';
    $output .= '<span class="mb2mm-arrow"></span>';
    $output .= '</a>';
    $output .= '<button type="button" class="mb2mm-toggle themereset" aria-label="' .
    get_string('togglemenuitem', 'theme_mb2nl', ['menuitem' => get_string('mycourses')]) . '" aria-expanded="false"></button>';

    $output .= '<div class="mb2mm-ddarrow"></div>';

    $output .= '<ul class="mb2mm-dd">';

    foreach ($courses as $c) {
        $courseurl = new moodle_url('/course/view.php', ['id' => $c['id']]);
        $coursename = theme_mb2nl_format_str($c['fullname']);

        $output .= '<li class="level-2 visible' . $c['visible'] . ' ' . $c['roles'] . '">';
        $output .= '<a class="mb2mm-action" href="' . $courseurl . '" aria-label="' . $coursename . '">';
        $output .= '<span class="mb2mm-label">';
        $output .= theme_mb2nl_wordlimit($coursename, $limit);
        $output .= '</span>';
        $output .= '</a>';
        $output .= '</li>';
    }

    $output .= '</ul>';
    $output .= $single ? '</div>' : '</li>';

    return $output;

}




/**
 *
 * Method to check if is my course list
 *
 */
function theme_mb2nl_get_mycourses() {
    global $USER, $PAGE;

    // Get cache.
    $cache = cache::make('theme_mb2nl', 'courses');
    $cacheid = 'my_courses';

    if ($cache->get($cacheid)) {
        return $cache->get($cacheid);
    }

    $mycourses = enrol_get_my_courses();
    $courses = [];

    foreach ($mycourses as $c) {
        $coursecontext = context_course::instance($c->id);
        $viewhidden = has_capability('moodle/course:viewhiddenactivities', $coursecontext);

        // This is required: isset($PAGE->theme->settings->mycexpierd).
        // becuse some user use child theme without 'mycexpierd' setting.
        if (theme_mb2nl_course_passed($c->id) && isset($PAGE->theme->settings->mycexpierd) &&
        !theme_mb2nl_theme_setting($PAGE, 'mycexpierd')) {
            continue;
        }

        // Hide hidden courses for students.
        if (!$c->visible) {
            if (isset($PAGE->theme->settings->mychidden) && ! theme_mb2nl_theme_setting($PAGE, 'mychidden')) {
                continue;
            }

            if (!$viewhidden) {
                continue;
            }
        }

        $courses[$c->id] = ['id' => $c->id, 'fullname' => $c->fullname, 'visible' => $c->visible,
        'roles' => implode(' ', theme_mb2nl_get_user_course_roles($c->id, $USER->id))];
    }

    // Set cache.
    $cache->set($cacheid, $courses);

    return $courses;

}



/**
 *
 * Method to get additional links to usermenu
 *
 */
function theme_mb2nl_menu_addits() {
    global $CFG, $USER;

    $items = [];

    if (!$USER->emailstop) {
        $items[] = [
            'id' => 'notifications',
            'itemtype' => 'link',
            'url' => new moodle_url('/message/output/popup/notifications.php'),
            'title' => get_string('notifications'),
        ];
    }

    if (theme_mb2nl_message_display()) {
        $items[] = [
            'id' => 'messages',
            'itemtype' => 'link',
            'url' => new moodle_url('/message/index.php'),
            'title' => get_string('messages', 'message'),
        ];
    }

    return $items;

}



/**
 *
 * Method to check if course is passed
 *
 */
function theme_mb2nl_course_passed($id) {
    global $DB;

    if (! $id) {
        return false;
    }

    // Get end date from database.
    $csql = 'SELECT * FROM {course} WHERE id=?';
    if (!$DB->record_exists_sql($csql, [$id])) {
        return false;
    }

    $course = $DB->get_record('course', ['id' => $id], 'enddate', MUST_EXIST);

    // Now we have to check date.
    if ($course->enddate > 0 && $course->enddate < theme_mb2nl_get_user_date()) {
        return true;
    }

    return false;

}






/**
 *
 * Method to get user date and time
 *
 */
function theme_mb2nl_get_user_date() {
    $date = new DateTime('now', core_date::get_user_timezone_object());
    $time = $date->getTimestamp();
    return $time;
}






/**
 *
 * Method to get icon navigation
 *
 */
function theme_mb2nl_iconnav($mobile = false) {
    global $PAGE;

    $iconnavs = theme_mb2nl_theme_setting($PAGE, 'navicons');

    if ($iconnavs === '') {
        return;
    }

    $cls = $mobile ? 'theme-iconnav-mobile mt-4 mb-0 mr-auto ml-auto' : 'theme-iconnav';

    return theme_mb2nl_static_content($iconnavs, true, true, ['listcls' => $cls]);

}




/**
 *
 * Method to get language full name without brackets
 *
 */
function theme_mb2nl_get_langname($langname) {
    $newlangname = [];
    $langname = explode(' ', $langname);

    foreach ($langname as $l) {
        if (preg_match('@\(@', $l)) {
            continue;
        }

        $newlangname[] = $l;
    }

    return implode(' ', $newlangname);

}




/**
 *
 * Method to get theme main menu
 *
 */
function theme_mb2nl_main_menu($id = 0, $pos = 1) {
    global $OUTPUT, $PAGE;

    $html = '';
    $navforusers = theme_mb2nl_theme_setting($PAGE, 'navforusers');
    $id = isloggedin() && ! isguestuser() && $navforusers ? $navforusers : $id;

    $buildermenu = theme_mb2nl_builder_menu();
    $megamenu = theme_mb2nl_megamenu($id, $pos);

    if ($buildermenu) {
        $html .= theme_mb2nl_megamenu($buildermenu, $pos);
    } else if ($megamenu) {
        $html .= $megamenu;
    } else {
        $html .= $OUTPUT->custom_menu();
    }

    return $html;

}





/**
 *
 * Method to check if megamenu plugin is installed and enabled
 *
 */
function theme_mb2nl_is_megamenu_plugin() {
    global $CFG, $DB;

    $dbman = $DB->get_manager();
    $table = new xmldb_table('local_mb2megamenu_menus');

    if (is_file($CFG->dirroot . '/local/mb2megamenu/index.php') && $dbman->table_exists($table)) {
        $options = get_config('local_mb2megamenu');

        if (isset($options->enablemenu) && $options->enablemenu) {
            return true;
        }
    }

    return false;

}





/**
 *
 * Method to get megamenu items from plugin
 *
 */
function theme_mb2nl_megamenu($id, $pos) {
    global $CFG;

    if (! theme_mb2nl_is_megamenu_plugin()) {
        return;
    }

    if (! class_exists('Mb2megamenuHelper')) {
        require_once($CFG->dirroot . '/local/mb2megamenu/classes/helper.php');
    }

    $mmhlpr = new Mb2megamenuHelper;

    return $mmhlpr->menu_template($id, $pos);

}




/**
 *
 * Method to get megamenu items from plugin
 *
 */
function theme_mb2nl_main_menu_style($extra = '') {
    global $PAGE;

    $style = '';

    $settings = [
        'navbarbgcolor',
        'navcolor',
        'navhcolor',
        'navsubcolor',
        'navsubhcolor',
        'navhbgcolor',
    ];

    foreach ($settings as $setting) {
        $val = theme_mb2nl_theme_setting($PAGE, $setting);

        $style .= $val ? '--mb2-pb-' . $setting . ':' . $val . ';' : '';
    }

    return $style . $extra;

}




/**
 *
 * Method to get top menu items
 *
 */
function theme_mb2nl_topmenu($cls = 'topmenu') {
    global $PAGE;

    $topmenu = theme_mb2nl_theme_setting($PAGE, 'topmenu');
    $options = ['listcls' => $cls];

    if (!$topmenu) {
        return;
    }

    return theme_mb2nl_static_content($topmenu, true, true, $options);

}




/**
 *
 * Method to get user menu
 *
 */
function theme_mb2nl_usermenu($exclude = ['logout'], $add = false, $hor = false) {
    global $USER, $PAGE;

    $output = '';
    $cls = '';

    $cls .= $hor ? theme_mb2nl_bsfcls(1, '', '', 'center') : '';

    if (!isloggedin() || isguestuser()) {
        return;
    }

    $opts = user_get_user_navigation_info($USER, $PAGE);
    $items = $opts->navitems;
    $addits = theme_mb2nl_menu_addits();

    $output .= '<ul class="theme-usermenu' . $cls . '">';

    if (count($addits) && $add) {
        foreach (theme_mb2nl_menu_addits() as $item) {
            $output .= '<li class="menu-item ' . $item['id'] . '">';
            $output .= '<a href="' . $item['url'] . '">';
            $output .= $item['title'];
            $output .= '</a>';
            $output .= '</li>';
        }

        $output .= '<li class="menu-item divider noid"></li>';
    }

    foreach ($items as $k => $item) {

        if ($item->itemtype === 'invalid') {
            continue;
        }

        $cls = $item->itemtype === 'divider' ? ' divider' : '';
        $id = isset($item->titleidentifier) ? explode(',', strtolower($item->titleidentifier))[0] : 'noid';
        $idcls = ' ' . $id;

        if (in_array($id, $exclude)) {
            continue;
        }

        $output .= '<li class="menu-item' . $cls . $idcls . '">';

        if ($item->itemtype === 'link') {
            $output .= '<a href="' . $item->url . '">';
            $output .= $item->title;
            $output .= '</a>';
        }

        $output .= '</li>';
    }

    $output .= '</ul>';

    return $output;

}
