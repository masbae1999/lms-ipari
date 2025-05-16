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
 * Method to check if there is custom dashboard
 *
 */
function theme_mb2nl_is_cdshb() {
    global $PAGE, $USER;

    $dashboard = theme_mb2nl_theme_setting($PAGE, 'dashboard');

    if (!isloggedin() || isguestuser() || !theme_mb2nl_is_user_cdshb() || $PAGE->pagelayout !== 'mydashboard' ||
    $PAGE->pagetype !== 'my-index') {
        return false;
    }

    if ($dashboard) {
        return true;
    }

    return false;

}




/**
 *
 * Method to check if there is custom dashboard
 *
 */
function theme_mb2nl_is_user_cdshb() {
    global $PAGE, $USER;

    // For administrators always show dashboard.
    if (is_siteadmin()) {
        return true;
    }

    $dshbdsbl = explode(',', theme_mb2nl_theme_setting($PAGE, 'dshbdsbl'));
    $roles = [];

    foreach (theme_mb2nl_user_roles() as $role) {
        $roles[] = $role['id'];
    }

    $intersect = array_intersect($roles, $dshbdsbl);

    if (!empty($intersect)) {
        return false;
    }

    return true;

}




/**
 *
 * Method to check if there is custom dashboard
 *
 */
function theme_mb2nl_cdshb_welcomebox() {
    global $CFG, $USER;

    require_once($CFG->dirroot . '/course/lib.php');

    $output = '';
    $lastlogin = theme_mb2nl_cdshb_lastlogin();
    $userdate = userdate(time(), get_string('strftimedaydate'));
    $usertmcdate = userdate($USER->timecreated, get_string('strftimedatetime'));
    $profileurl = new moodle_url('/user/profile.php', []);
    $lacourse = current(course_get_recent_courses(null, 1, 0));

    // Messages.
    $welcometxt = !is_null($lastlogin) ? get_string('welcomeback', 'moodle', ['firstname' => $USER->firstname]) :
    get_string('welcometosite', 'moodle', ['firstname' => $USER->firstname]);
    $welcometxt2 = get_string('firstloginmsg', 'theme_mb2nl', ['timecreated' => $usertmcdate, 'profileurl' => $profileurl]);

    if (!is_null($lastlogin) && $lacourse) {
        $welcometxt2 = get_string('lastlogncoursemsg', 'theme_mb2nl', ['loginago' => $lastlogin, 'recentcourse' =>
        $lacourse->fullname]);
    } else if (!is_null($lastlogin)) {
        $welcometxt2 = get_string('lastloginmsg', 'theme_mb2nl', ['loginago' => $lastlogin]);
    }

    $output .= '<div class="dshb-wbox">';
    $output .= '<div class="wbox-title h5 mb-4">' . $welcometxt . '</div>';
    $output .= '<div class="wbox-content">';
    $output .= '<div class="wbox-date">' . get_string('todayis', 'theme_mb2nl', $userdate) . '</div>';
    $output .= '<div class="wbox-text mt-2">' . $welcometxt2 . '</div>';

    if ($lacourse) {
        $output .= '<a class="wbox-button mb2-pb-btn typeprimary mt-4" href="' .
        new moodle_url('/course/view.php', ['id' => $lacourse->id]) . '">' . get_string('continuelearning', 'theme_mb2nl') . '</a>';
    }

    $output .= '</div>'; // ...welcome-content
    $output .= '</div>'; // ...dshb-blocks

    return $output;

}



/**
 *
 * Method to get last login days
 *
 */
function theme_mb2nl_cdshb_lastlogin() {
    global $USER;

    $lastlogin = $USER->lastlogin;

    if (!$lastlogin) {
        return null;
    }

    $datediff = (time() - $lastlogin);
    $days = round($datediff / (60 * 60 * 24));
    $hours = round($datediff / (60 * 60));

    if ($days > 0) {
        return get_string('daysago', 'theme_mb2nl', $days);
    }

    return get_string('hoursago', 'theme_mb2nl', $hours);
}




/**
 *
 * Method to get dashboard
 *
 */
function theme_mb2nl_chart_semicircle($progress = .75, $num = 20, $text = 'Courses',
$opts = ['color' => '#001d62', 'size' => 140, 'sw' => 3]) {

    $output = '';
    $progress = $num == 0 ? 0 : $progress;

    $output .= '<div class="dash-chart chart-semicircle" style="max-width:' . $opts['size'] . 'px;flex:0 0 ' .
    $opts['size'] . 'px;">';
    $output .= '<svg viewbox="0 0 110 115">';
    $output .= '<path class="grey" d="M30,90 A40,40 0 1,1 80,90" fill="none" stroke="rgba(0,0,0,.1)" stroke-width="' .
    $opts['sw'] . '" stroke-linecap="round" />';
    $output .= '<path id="blue" stroke="' . $opts['color'] . '" stroke-dasharray="198" stroke-width="' . $opts['sw']
    . '" stroke-dashoffset="' . round(198 * (1 - $progress), 1) . '" fill="none" class="blue"
    d="M30,90 A40,40 0 1,1 80,90" stroke-linecap="round" />';
    $output .= '<text x="55" y="66" text-anchor="middle" font-size="18" class="fwheadings">' . $num . '</text>';
    $output .= '<text x="55" y="110" text-anchor="middle" font-size="11" class="fwheadings">' . $text . '</text>';
    $output .= '</svg>';
    $output .= '</div>'; // ...progress

    return $output;

}



/**
 *
 * Method to circle progressbar
 *
 */
function theme_mb2nl_chart_circle($progress=43, $opts = []) {
    global $PAGE;

    $output = '';

    $pctchart = ! is_numeric($progress);
    $progresstext = $progress . '%';

    if ($pctchart) {
        $progress = 0;
        $progresstext = '';
    }

    $s = $opts['s'];
    $bs = $opts['bs'];
    $bcolor = isset($opts['bcolor']) ? $opts['bcolor'] : 'rgba(0,0,0,.07)';
    $fcolor = isset($opts['fcolor']) ? $opts['fcolor'] : '#ffffff';
    $color = isset($opts['color']) ? $opts['color'] : theme_mb2nl_theme_setting($PAGE, 'color_success');
    $textcls = isset($opts['textcls']) ? $opts['textcls'] : ' tsizesmall';
    $text = isset($opts['text']) ? '<span class="progress-text position-absolute' . $textcls .
    theme_mb2nl_bsfcls(2, 'column', 'center', 'center') . '" style="width:' . $s . 'px;height:' . $s . 'px;">' .
    $progresstext . '</span>' : '';
    $ttext = isset($opts['ttext']) && $opts['ttext'] ? ' title="' . $opts['ttext'] . '"' : '';

    $cs = round($s / 2);
    $r = round($cs - $bs);
    $darr = round(2 * pi() * $r, 2);
    $offset = round($darr * ((100 - $progress) / 100), 2);

    $output .= '<span class="dash-chart chart-circle position-relative' . theme_mb2nl_bsfcls(2, 'center', 'center')
    . '" style="width:' . $s . 'px;"' . $ttext . '>';
    $output .= '<svg width="' . $s . '" height="' . $s . '" viewBox="0 0 ' . $s . ' ' . $s . '" aria-hidden="true"
    style="transform:rotate(-90deg);">';
    $output .= '<circle r="' . $r . '" cx="' . $cs . '" cy="' . $cs . '" fill="' . $fcolor . '" stroke="' . $bcolor
    . '" stroke-width="' . $bs . 'px"></circle>';
    $output .= '<circle r="' . $r . '" cx="' . $cs . '" cy="' . $cs . '" fill="transparent" stroke="' . $color . '" stroke-width="'.
    $bs . 'px" stroke-dasharray="' . $darr . 'px" stroke-dashoffset="' . $offset . 'px"></circle>';
    $output .= '</svg>';
    $output .= $text;
    $output .= '</span>'; // ...progress

    return $output;

}






/**
 *
 * Method to get dashboard
 *
 */
function theme_mb2nl_mycourses_timeline($timeline=COURSE_TIMELINE_INPROGRESS, $count=1) {
    global $CFG, $USER;

    // Define cache object.
    $cache = cache::make('theme_mb2nl', 'dsahboard');
    $cacheid = 'mycourses_timeline_' . $count . '_' . $timeline . '_' . $USER->id;

    if ($cache->get($cacheid)) {
        return $cache->get($cacheid);
    }

    require_once($CFG->dirroot . '/course/lib.php');

    $courseids = [];
    $courses = enrol_get_my_courses();

    foreach ($courses as $course) {
        $classify = course_classify_for_timeline($course);

        if ($classify != $timeline) {
            continue;
        }

        $courseids[] = $course->id;
    }

    if ($count) {
        $cache->set($cacheid, count($courseids));

        return count($courseids);
    }

    $cache->set($cacheid, $courseids);

    return $courseids;

}







/**
 *
 * Method to get dashboard
 *
 */
function theme_mb2nl_dashboard($col=2) {
    $output = '';

    $output .= '<div class="dshb-blocks dshb-cols-' . $col . theme_mb2nl_bsfcls(1, 'wrap', 'between', '') . '">';
    $output .= theme_mb2nl_dashboard_block('totalcourses');
    $output .= theme_mb2nl_dashboard_block('visiblecourses');
    $output .= theme_mb2nl_dashboard_block('filespace');
    $output .= theme_mb2nl_dashboard_block('performance');
    $output .= '</div>'; // ...theme-dashboard

    return $output;

}





/**
 *
 * Method to get dashboard block
 *
 */
function theme_mb2nl_dashboard_block($id) {
    global $PAGE;
    $output = '';
    $data = theme_mb2nl_dashboard_blocks()[$id];

    $output .= '<div class="dshb-block dshb-block-' . $id . theme_mb2nl_bsfcls(1, 'row', '', 'center')
    . ' position-relative" style="--mb2-dshb-color:' . $data['color'] . '">';

    $output .= '<div class="dshb-block-icon position-relative">';
    $output .= '<i class="' . $data['icon'] . '"></i>';
    $output .= '</div>'; // ...block-icon

    $output .= '<div class="dshb-block-content position-relative' . theme_mb2nl_bsfcls(1, 'column', '', '') . '">';
    $output .= '<span class="block-name lhmedium mb-1">' . $data['name'] . '</span>';
    $output .= '<div class="block-value' . theme_mb2nl_bsfcls(1, 'row', '', 'end') . '">';
    $output .= '<span class="value">' . $data['value'] . '</span>';
    $output .= $data['suffix'] ? '<span class="suffix position-relative">' . $data['suffix'] . '</span>' : '';
    $output .= '</div>'; // ...block-value
    $output .= '</div>'; // ...block-content

    if ($data['chart']) {
        $output .= '<div class="dshb-block-chart ml-auto' . theme_mb2nl_bsfcls(2, '', 'center', 'center') . '">';
        $output .= $data['chart'];
        $output .= '</div>'; // ...dshb-block-chart
    }

    $output .= '</div>'; // ...block-item

    return $output;

}





/**
 *
 * Method to get dashboard
 *
 */
function theme_mb2nl_dashboard_blocks() {
    global $PAGE;

    $tcourses = theme_mb2nl_dashboard_courses();
    $vcourses = theme_mb2nl_dashboard_courses(0);
    $vcoursespct = $tcourses > 0 ? round(($vcourses / $tcourses) * 100, 1) : 0;

    $blocks = [
        'moodledata' => [
            'name' => 'Moodledata',
            'icon' => 'ri-folder-2-line',
            'value' => theme_mb2nl_dashboard_moodledata(),
            'suffix' => 'GB',
            'color' => '#25a18e',
            'chart' => '',
            'link' => '',
        ],
        'filespace' => [
            'name' => get_string('discusage', 'theme_mb2nl'),
            'icon' => 'ri-database-2-line',
            'value' => theme_mb2nl_dashboard_filespace(),
            'suffix' => 'GB',
            'color' => theme_mb2nl_theme_setting($PAGE, 'color_success'),
            'chart' => '',
            'link' => '',
        ],
        'performance' => [
            'name' => get_string('preformanceproblems', 'theme_mb2nl'),
            'icon' => 'ri-rocket-line',
            'value' => theme_mb2nl_dashboard_performance(),
            'suffix' => '',
            'color' => '#EF476F',
            'chart' => '',
            'link' => new moodle_url('/report/performance/index.php'),
        ],
        'totalcourses' => [
            'name' => get_string('fulllistofcourses'),
            'icon' => 'ri-book-2-line',
            'value' => $tcourses,
            'suffix' => '',
            'color' => theme_mb2nl_theme_setting($PAGE, 'color_info'),
            'chart' => '',
            'link' => '',
        ],
        'visiblecourses' => [
            'name' => get_string('visiblecourses', 'theme_mb2nl'),
            'icon' => 'ri-book-open-line',
            'value' => $vcourses,
            'suffix' => '',
            'color' => '#FB8500',
            'chart' => theme_mb2nl_chart_circle($vcoursespct, [
                'circle' => true,
                's' => 52,
                'bs' => 3,
                'textcls' => ' tsizesmall fwmedium',
                'color' => '#FB8500',
                'bcolor' => 'rgba(0,0,0,.08)',
                'fcolor' => 'transparent',
                'text' => true,
                'ttext' => $vcoursespct . '% ' . strtolower(get_string('visiblecourses', 'theme_mb2nl')),
            ]),

            'link' => '',
        ],

    ];

    return $blocks;

}






/**
 *
 * Method to get dashboard
 *
 */
function theme_mb2nl_dashboard_moodledata() {
    global $CFG;

    // Define cache object.
    $cache = cache::make('theme_mb2nl', 'dsahboard');
    $cacheid = 'dashboard_moodledata';

    if ($cache->get($cacheid)) {
        return $cache->get($cacheid);
    }

    $kb = 1024;
    $mb = ($kb * 1024);
    $gb = ($mb * 1024);
    $tb = ($gb * 1024);

    $totalusage = get_directory_size($CFG->dataroot);
    $totalusagereadable = round($totalusage / $gb, 2);

    $cache->set($cacheid, $totalusagereadable);

    return $totalusagereadable;

}



/**
 *
 * Method to get dashboard performance
 *
 */
function theme_mb2nl_dashboard_performance() {
    global $CFG;

    $output = '';
    $issues = 0;
    $preformances = [
        'themedesignermode' => $CFG->themedesignermode ? 1 : 0,
        'debugdev' => debugging('', DEBUG_DEVELOPER) ? 1 : 0,
        'cachejs' => $CFG->cachejs ? 0 : 1,
        'enablestats' => $CFG->enablestats ? 1 : 0,
        'automatedbackups' => get_config('backup', 'backup_auto_active') == 1 ? 1 : 0,
    ];

    foreach ($preformances as $p) {
        if ($p) {
            $issues++;
        }
    }

    return $issues;

}




/**
 *
 * Method to get file space
 *
 */
function theme_mb2nl_dashboard_filespace() {
    global $DB;

    // Define cache object.
    $cache = cache::make('theme_mb2nl', 'dsahboard');
    $cacheid = 'dashboard_filespace';

    if ($cache->get($cacheid)) {
        return $cache->get($cacheid);
    }

    $kb = 1024;
    $mb = ($kb * 1024);
    $gb = ($mb * 1024);
    $tb = ($gb * 1024);

    $count = $DB->get_record_sql('SELECT SUM(filesize) as space FROM {files}');

    $cache->set($cacheid, round($count->space / $gb, 2));

    return round($count->space / $gb, 2);

}





/**
 *
 * Method to get all users
 *
 */
function theme_mb2nl_dashboard_users() {
    global $DB;

    // Define cache object.
    $cache = cache::make('theme_mb2nl', 'dsahboard');
    $cacheid = 'dashboard_users';

    if ($cache->get($cacheid)) {
        return $cache->get($cacheid);
    }

    $sql = 'SELECT count(id) AS num FROM {user} WHERE id>1 AND deleted=0';

    $cache->set($cacheid, $DB->count_records_sql($sql));

    return $DB->count_records_sql($sql);
}





/**
 *
 * Method to get all users
 *
 */
function theme_mb2nl_dashboard_activeusers() {
    global $DB;

    // Define cache object.
    $cache = cache::make('theme_mb2nl', 'dsahboard');
    $cacheid = 'dashboard_activeusers';

    if ($cache->get($cacheid)) {
        return $cache->get($cacheid);
    }

    $accessactive = theme_mb2nl_dashboard_user_active_time();
    $sql = 'SELECT count(id) AS num FROM {user} WHERE id>1 AND deleted=0 AND suspended=0 AND lastaccess>=' . $accessactive;

    $cache->set($cacheid, $DB->count_records_sql($sql));

    return $DB->count_records_sql($sql);
}





/**
 *
 * Method to get courses count
 *
 */
function theme_mb2nl_dashboard_courses($total=1) {
    global $DB, $SITE;

    // Define cache object.
    $cache = cache::make('theme_mb2nl', 'dsahboard');
    $cacheid = 'dashboard_courses_' . $total;

    if ($cache->get($cacheid)) {
        return $cache->get($cacheid);
    }

    $userdate = theme_mb2nl_get_user_date();
    $sql = 'SELECT count(id) AS num FROM {course} WHERE id>'. $SITE->id;

    if (!$total) {
        $sql .= ' AND visible=1 AND (enddate>0 OR enddate<' . $userdate . ')';
    }

    $cache->set($cacheid, $DB->count_records_sql($sql));

    return $DB->count_records_sql($sql);
}




/**
 *
 * Method to get suspended users
 *
 */
function theme_mb2nl_dashboard_chart($options) {
    $output = '';

    $output .= '<div class="dashboard-chart">';
    $output .= '<div class="dashboard-progress">';
    $output .= '<div class="progress-part part1" style="width:' . round(($options['val1'] / $options['mainval']) * 100, 2)
    . '%;"></div>';
    $output .= '<div class="progress-part part2" style="width:' . round(($options['val2'] / $options['mainval']) * 100, 2)
    . '%;"></div>';
    $output .= '</div>'; // ...dashboard-progress
    $output .= '<div class="dashboard-progress-legend">';
    $output .= '<div class="part1">' . $options['str1'] . '<span class="chart-value">(' . $options['val1'] . ')</span></div>';
    $output .= '<div class="part2">' . $options['str2'] . '<span class="chart-value">(' . $options['val2'] . ')</span></div>';
    $output .= '</div>'; // ...dashboard-progress-legend
    $output .= '</div>'; // ...dashboard-chart

    return $output;
}






/**
 *
 * Method to get users active time
 *
 */
function theme_mb2nl_dashboard_user_active_time() {
    global $PAGE;

    $activeuserstime = theme_mb2nl_theme_setting($PAGE, 'activeuserstime');
    $userdate = theme_mb2nl_get_user_date();
    $month = (60 * 60 * 24 * 30 * $activeuserstime);
    $activedate = ($userdate - $month);

    return $activedate;

}



/**
 *
 * Method to get user new time
 *
 */
function theme_mb2nl_dashboard_user_new_time($days) {
    global $PAGE;

    $newuserstime = $days ? $days : theme_mb2nl_theme_setting($PAGE, 'newuserstime');
    $userdate = theme_mb2nl_get_user_date();
    $days = (60 * 60 * 24 * $newuserstime);
    $newdate = ($userdate - $days);

    return $newdate;

}






/**
 *
 * Method to get onlineusers
 *
 */
function theme_mb2nl_dashboard_onlineusers($time=10) {
    global $DB;

    // Define cache object.
    $cache = cache::make('theme_mb2nl', 'dsahboard');
    $cacheid = 'dashboard_onlineusers_' . $time;

    if ($cache->get($cacheid)) {
        return $cache->get($cacheid);
    }

    $onlinestart = strtotime('-' . $time . ' minutes');
    $timefinish = time();

    $sql = 'SELECT COUNT(u.id) FROM {user} u WHERE u.lastaccess BETWEEN';
    $sql .= ' ' . $onlinestart;
    $sql .= ' AND ' .  $timefinish;

    $cache->set($cacheid, $DB->count_records_sql($sql));

    return $DB->count_records_sql($sql);

}
