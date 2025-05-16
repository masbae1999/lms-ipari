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

define('AJAX_SCRIPT', true);

// No login check is expected here bacause all items
// are visible for all site visitors.
// @codingStandardsIgnoreLine
require_once(__DIR__ . '/../../../config.php');
require_once($CFG->libdir . '/adminlib.php');

if ($CFG->forcelogin) {
    require_login();
}

require_sesskey();

if (!confirm_sesskey()) {
    die;
}

$themedir = '/theme';

if (isset($CFG->themedir)) {
    $themedir = $CFG->themedir;
    $themedir = str_replace($CFG->dirroot, '', $CFG->themedir);
}

// Require theme lib files.
require_once($CFG->dirroot . $themedir . '/mb2nl/lib.php');

$context = context_system::instance();
$PAGE->set_url($themedir . '/mb2nl/lib/lib_ajax_items.php');
$PAGE->set_context($context);

$i2load = optional_param('i2load', '', PARAM_RAW);
$options = optional_param('options', '', PARAM_RAW);

// Get options array.
$options = urldecode($options);
$options = unserialize($options);

if ($i2load === 'courses') {
    $courses = theme_mb2nl_get_courses($options);
    echo theme_mb2nl_shortcodes_course_template($courses, $options);
} else if ($i2load === 'blog') {
    $posts = theme_mb2nl_get_blog_posts($options);
    echo theme_mb2nl_blog_template($posts, $options);
} else if ($i2load === 'events') {
    $events = theme_mb2nl_get_events($options);
    echo theme_mb2nl_events_template($events, $options);
} else if ($i2load === 'teachers') {
    $teachers = theme_mb2nl_get_all_teachers($options, 'u.id,u.firstname,u.firstnamephonetic,u.lastnamephonetic,u.middlename,
    u.alternatename,u.email,u.lastname,u.picture,u.imagealt,u.description');
    echo theme_mb2nl_shortcodes_teachers_template($teachers, $options);
} else if ($i2load === 'mycourses') {
    $cids = explode(',', $options['courseids']);
    $cids = is_array($cids) && $cids[0] ? $cids : [];
    $nocids = explode(',', $options['nocourseids']);
    $nocids = is_array($nocids) && $nocids[0] ? $nocids : [];

    $courses = enrol_get_my_courses('*', null, $options['limit'], $cids, false, 0, $nocids);
    echo theme_mb2nl_shortcodes_course_template($courses, $options);
} else if ($i2load === 'announcements') {
    echo theme_mb2nl_get_announcements_tmpl($options);
} else if ($i2load === 'categories') {
    $categories = theme_mb2nl_shortcodes_categories_get_items($options);
    echo theme_mb2nl_shortcodes_content_template($categories, $options);
} else if ($i2load === 'reviews') {
    $reviews = theme_mb2nl_get_featured_reviews($options);
    echo theme_mb2nl_shortcodes_reviews_template($reviews, $options);
} else {
    echo get_string('nothingtodisplay');
}
die;
