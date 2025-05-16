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
 * Method to get courses
 *
 */
function theme_mb2nl_get_courses($opt=[], $count=false) {
    global $DB, $CFG, $PAGE, $SITE;

    $canviewhiddencats = has_capability('moodle/category:viewhiddencategories', context_system::instance());
    $page = isset($opt['page']) ? $opt['page'] : 1;
    $opt['perpage'] = $CFG->coursesperpage;
    $opt['limitfrom'] = ($page - 1) * $opt['perpage'];

    if (isset($opt['limit'])) {
        $opt['perpage'] = $opt['limit'];
        $opt['limitfrom'] = 0;
    }

    $teacherroleid = theme_mb2nl_user_roleid(true);
    $opt['excludecat'] = theme_mb2nl_course_excats();
    $opt['excludetag'] = theme_mb2nl_course_extags();

    // Use cache.
    $cache = cache::make('theme_mb2nl', 'courses');
    $cachelistid = 'courselist_' . serialize($opt); // Course list id.
    $cachecountid = 'coursecount_' . serialize($opt); // Course count id.

    // Get course list cache.
    if (!$count && $cache->get($cachelistid)) {
        return $cache->get($cachelistid);
    }

    // Get course count cache.
    if ($count && $cache->get($cachecountid)) {
        return $cache->get($cachecountid);
    }

    $params = [];
    $sqlwhere = ' WHERE 1=1';
    $sqlorder = '';
    $sql = '';

    if ($count) {
        // Select courses count.
        $sql .= 'SELECT COUNT(DISTINCT c.id) FROM {course} c';
    } else {
        // Select courses.
        $sql .= 'SELECT DISTINCT c.* FROM {course} c';
    }

    // Check for frontpage course
    // and for hidden courses.
    $sqlwhere .= ' AND c.id!=' . $SITE->id;

    $sqlwhere .= !$canviewhiddencats ? ' AND c.visible=1' : '';

    // Check expired courses.
    if (!theme_mb2nl_theme_setting($PAGE, 'expiredcourses')) {
        $sqlwhere .= ' AND (c.enddate=0 OR c.enddate>' . theme_mb2nl_get_user_date() . ')';
    }

    // Filter exclude categories.
    if ($opt['excludecat'][0]) {
        $isnot = count($opt['excludecat']) > 1 ? 'NOT ' : '!';
        list($excatinsql, $excatparams) = $DB->get_in_or_equal($opt['excludecat']);
        $params = array_merge($params, $excatparams);
        $sqlwhere .= ' AND c.category ' . $isnot . $excatinsql;
    }

    // Filter exclude courses (OLD).
    if (isset($opt['courseids']) &&  $opt['courseids'] && $opt['excourses']) {
        $isnot = '';
        $opt['courseids'] = explode(',', $opt['courseids']);

        if ($opt['excourses'] === 'exclude') {
            $isnot = count($opt['courseids']) > 1 ? 'NOT ' : '!';
        }

        list($coursesnsql, $coursesparams) = $DB->get_in_or_equal($opt['courseids']);
        $params = array_merge($params, $coursesparams);
        $sqlwhere .= ' AND c.id ' . $isnot . $coursesnsql;
    }

    // Filter exclude categories (OLD).
    if (isset($opt['catids']) &&  $opt['catids'] && $opt['excats']) {
        $isnot = '';
        $opt['catids'] = explode(',', $opt['catids']);

        if ($opt['excats'] === 'exclude') {
            $isnot = count($opt['catids']) > 1 ? 'NOT ' : '!';
        }

        list($excatinsql, $excatparams) = $DB->get_in_or_equal($opt['catids']);
        $params = array_merge($params, $excatparams);
        $sqlwhere .= ' AND c.category ' . $isnot . $excatinsql;
    }

    // Filter categories.
    if (!empty($opt['categories'])) {
        list($catinsql, $catparams) = $DB->get_in_or_equal($opt['categories']);
        $params = array_merge($params, $catparams);
        $sqlwhere .= ' AND c.category ' . $catinsql;
    }

    // Filter hidden categories.
    $sqlwhere .= !$canviewhiddencats ?
    ' AND EXISTS(SELECT ca.id, ca.visible FROM {course_categories} ca WHERE ca.id=c.category AND ca.visible=1)' : '';

    // Filter instructors.
    if (! empty($opt['instructors']) || (isset($opt['instids']) && $opt['instids'] && $opt['exinst'])) {
        $isnot = '';

        // Set instructor filter for the course shortcode.
        if (isset($opt['instids'])) {
            $opt['instructors'] = explode(',', $opt['instids']);

            if ($opt['exinst'] === 'exclude') {
                $isnot = 'NOT ';
            }
        }

        list($instructorsinsql, $instructorsparams) = $DB->get_in_or_equal($opt['instructors']);
        $params = array_merge($params, $instructorsparams);
        $sqlwhere .= ' AND ' . $isnot  . 'EXISTS(SELECT ra.id FROM {role_assignments} ra JOIN {context} cx ON ra.contextid = cx.id';
        $sqlwhere .= ' AND cx.contextlevel = ' . CONTEXT_COURSE . ' WHERE cx.instanceid = c.id AND ra.roleid = ' . $teacherroleid
        . ' AND ra.userid ' . $instructorsinsql . ')';
    }

    // Filter price.
    if (isset($opt['price']) && ($opt['price'] == 0 ||  $opt['price'] == 1)) {
        $params[] = ENROL_INSTANCE_ENABLED;
        list($priceinsql, $priceparams) = $DB->get_in_or_equal(theme_mb2nl_pay_enrolements());
        $params = array_merge($params, $priceparams);
        $isnot = '';

        if ($opt['price'] == 0) {
            $isnot = 'NOT ';
        }

        $sqlwhere .= ' AND ' . $isnot . 'EXISTS(SELECT er.id FROM {enrol} er WHERE er.courseid=c.id AND er.status=? AND er.enrol ' .
        $priceinsql . ')';
    }

    // Exlude tags.
    if ($opt['excludetag'][0]) {
        list($extaginsql, $extagparams) = $DB->get_in_or_equal($opt['excludetag']);
        $params = array_merge($params, $extagparams);

        $sqlwhere .= ' AND NOT EXISTS(SELECT t.id FROM {tag} t JOIN {tag_instance} ti ON ti.tagid=t.id JOIN {context} cx';
        $sqlwhere .= ' ON cx.id=ti.contextid WHERE c.id=cx.instanceid';
        $sqlwhere .= ' AND cx.contextlevel = ' . CONTEXT_COURSE;
        $sqlwhere .= ' AND t.id ' . $extaginsql;
        $sqlwhere .= ')';
    }

    // Filter tags.
    if (!empty($opt['tags'])) {
        list($extaginsql, $extagparams) = $DB->get_in_or_equal($opt['tags']);
        $params = array_merge($params, $extagparams);

        $sqlwhere .= ' AND EXISTS(SELECT t.id FROM {tag} t JOIN {tag_instance} ti ON ti.tagid=t.id JOIN {context} cx';
        $sqlwhere .= ' ON cx.id=ti.contextid WHERE c.id=cx.instanceid';
        $sqlwhere .= ' AND cx.contextlevel = ' . CONTEXT_COURSE;
        $sqlwhere .= ' AND t.id ' . $extaginsql;
        $sqlwhere .= ')';
    }

    // Filter tags for shortcodes.
    if (isset($opt['tagids']) && $opt['tagids'] && $opt['extags']) {
        $isnot = '';

        $tagsarr = explode(',', $opt['tagids']);

        if ($opt['extags'] === 'exclude') {
            $isnot = 'NOT ';
        }

        list($extaginsql, $extagparams) = $DB->get_in_or_equal($tagsarr);
        $params = array_merge($params, $extagparams);

        $sqlwhere .= ' AND ' .$isnot. 'EXISTS(SELECT t.id FROM {tag} t JOIN {tag_instance} ti ON ti.tagid=t.id JOIN {context} cx';
        $sqlwhere .= ' ON cx.id=ti.contextid WHERE c.id=cx.instanceid';
        $sqlwhere .= ' AND cx.contextlevel = ' . CONTEXT_COURSE;
        $sqlwhere .= ' AND t.id ' . $extaginsql;
        $sqlwhere .= ')';
    }

    // Filter search.
    // Based on get_courses_search.
    if (isset($opt['searchstr'])  && $opt['searchstr'] !== '') {
        $searchstr = strip_tags($opt['searchstr']);
        $searchstr = trim($searchstr);
        $params[] = '%' . $searchstr . '%';
        $concat = $DB->sql_concat("COALESCE(c.summary, '')", "' '", 'c.fullname', "' '", 'c.idnumber', "' '", 'c.shortname');
        $sqlwhere .= ' AND (' . $DB->sql_like($concat, '?', false, true, false);

        // Search by course custom fileds.
        $params[] = '%' . $searchstr . '%';
        $sqlwhere .= ' OR EXISTS(SELECT cf.id FROM {customfield_data} cf JOIN {context} cx ON cx.id=cf.contextid';
        $sqlwhere .= ' WHERE c.id=cx.instanceid';
        $sqlwhere .= ' AND ' . $DB->sql_like('cf.value', '?', false, true, false);
        $sqlwhere .= '))';
    }

    // Filter by custom fileds.
    if (isset($opt['cfields']) && !empty($opt['cfields'])) {
        foreach ($opt['cfields'] as $fid => $fvals) {
            if (!count($fvals)) {
                continue;
            }

            if ($fvals[0] == 0) {
                continue;
            }

            $sqlwhere .= ' AND EXISTS(SELECT cf.id, cf.fieldid FROM {customfield_data} cf JOIN {context} cx ON cx.id=cf.contextid';
            $sqlwhere .= ' WHERE c.id=cx.instanceid';
            $sqlwhere .= ' AND cx.contextlevel=' . CONTEXT_COURSE;
            $sqlwhere .= ' AND cf.fieldid=' . $fid;
            $sqlwhere .= count($fvals) == 1 ? ' AND cf.value=' . $fvals[0] : ' AND cf.value IN (' . implode(',', $fvals) . ')';
            $sqlwhere .= ')';
        }
    }

    $sqlorder .= ' ORDER BY c.sortorder';

    if ($count) {
        $sqlorder = '';
        $coursecount = $DB->count_records_sql($sql . $sqlwhere . $sqlorder, $params, $opt['limitfrom'] , $opt['perpage']);

        // Set cache.
        $cache->set($cachecountid, $coursecount);
        return $coursecount;
    }

    $courselist = $DB->get_records_sql($sql . $sqlwhere . $sqlorder, $params, $opt['limitfrom'], $opt['perpage']);

    // Set cache.
    $cache->set($cachelistid, $courselist);
    return $courselist;

}








/**
 *
 * Method to get course filter form
 *
 */
function theme_mb2nl_filter_cfileds() {

    global $PAGE;

    $output = '';
    $filterfields = theme_mb2nl_theme_setting($PAGE, 'filterfields');

    if (!$filterfields) {
        return;
    }

    $output .= '<input type="hidden" name="filter_cfields" value="">';

    $fileds = preg_split('/\s*\R\s*/', trim($filterfields));

    foreach ($fileds as $f) {
        $line = explode('|', $f);

        // Check if filed exists.
        $field = theme_mb2nl_is_selectfield(trim($line[0]));

        if (!$field) {
            continue;
        }

        $checkbox = isset($line[1]) && $line[1] !== 'checkbox' ? 0 : 1;

        $output .= theme_mb2nl_selectfield_filterblock($field, $checkbox);

    }

    return $output;

}







/**
 *
 * Method to get course filter form
 *
 */
function theme_mb2nl_selectfield_filterblock($field, $checkbox=1) {

    $output = '';

    $id = $field->id;
    $options = theme_mb2nl_selectfield_options($id);

    if (count($options) <= 1) {
        return;
    }

    $output .= '<div class="filter-block">';
    $output .= theme_mb2nl_courses_filter_heading($field->name);
    $output .= '<div id="cfield_block_' . $id . '" class="filter-content">';
    $output .= '<ul class="filter-cfield_' . $id . '">';

    foreach ($options as $v => $name) {
        if ($checkbox && $v == 0) {
            continue;
        }

        $fieldtype = !$checkbox ? theme_mb2nl_cfiled_radiobox($id, $v, $name) : theme_mb2nl_cfiled_checkbox($id, $v, $name);

        $output .= '<li class="filter-form-field">' . $fieldtype. '</li>';
    }

    $output .= '</ul>';
    $output .= '</div>'; // ...filter-content
    $output .= '</div>'; // ...filter-block

    return $output;

}








/**
 *
 * Method to get filter filed - checkbox
 *
 */
function theme_mb2nl_cfiled_checkbox($field, $v, $name) {

    $output = '';

    $ccount = theme_mb2nl_get_cfileds_courses_count($field, $v);
    $coursescount = ' <span class="info">(' . $ccount . ')</span>';
    $isid = 'cfield_' . $field . '_' . $v;
    $disabled = !$ccount ? ' disabled' : '';

    $output .= '<div class="field-container' . $disabled . '">';
    $output .= '<label for="' . $isid . '">';
    $output .= '<input class="mb2filter-inpt" type="checkbox" id="' . $isid . '" name="filter_cfields[' . $field . '][]" value="' .
    $v . '"' . $disabled . '>';
    $output .= '<i></i>';
    $output .= $name . $coursescount;
    $output .= '</label>';
    $output .= '</div>'; // ...field-container

    return $output;

}








/**
 *
 * Method to get filter filed - radiobox
 *
 */
function theme_mb2nl_cfiled_radiobox($field, $v, $name) {

    $output = '';

    $ccount = theme_mb2nl_get_cfileds_courses_count($field, $v);
    $coursescount = ' <span class="info">(' . $ccount . ')</span>';
    $isid = 'cfield_' . $field . '_' . $v;
    $name = $v == 0 ? get_string('all') : $name;
    $checked = $v == 0 ? ' checked' : '';
    $disabled = !$ccount ? ' disabled' : '';

    $output .= '<div class="field-container' . $disabled . '">';
    $output .= '<label for="' . $isid . '">';
    $output .= '<input class="mb2filter-inpt" type="radio" id="' . $isid . '" name="filter_cfields[' . $field . '][]" value="' .
    $v . '"' . $checked . $disabled . '>';
    $output .= '<i></i>';
    $output .= $name . $coursescount;
    $output .= '</label>';

    $output .= '</div>'; // ...field-container

    return $output;

}






/**
 *
 * Method to get course filter form
 *
 */
function theme_mb2nl_selectfield_options($id = 0) {

    global $DB;

    // Get cache.
    $cache = cache::make('theme_mb2nl', 'coursefield');
    $cacheid = 'coursefield_opts_' . $id;

    if ($cache->get($cacheid)) {
        return $cache->get($cacheid);
    }

    $params = ['id' => $id];

    $recordsql = 'SELECT id, configdata FROM {customfield_field} WHERE id=:id';

    if (!$DB->record_exists_sql($recordsql, $params)) {
        return;
    }

    // Get configdata.
    $data = json_decode($DB->get_record_sql($recordsql, $params)->configdata);

    // Get options array.
    // This is based on a native Moodle solution.
    $options = preg_split('/\s*\n\s*/', trim($data->options));

    // Set cache.
    $cache->set($cacheid, array_merge([''], $options));

    return array_merge([''], $options);

}




/**
 *
 * Method to get course filter form
 *
 */
function theme_mb2nl_is_selectfield($shortname) {

    global $DB;

    // Set cache.
    $cache = cache::make('theme_mb2nl', 'coursefield');
    $cacheid = 'coursefield_is_selected_' . $shortname;

    if ($cache->get($cacheid)) {
        return $cache->get($cacheid);
    }

    $params = ['shortname' => trim($shortname)];

    $recordsql = 'SELECT id, name FROM {customfield_field} WHERE ' . $DB->sql_like('shortname', ':shortname');

    if ($DB->record_exists_sql($recordsql, $params)) {
        $result = $DB->get_record_sql($recordsql, $params);

        // Set cache.
        $cache->set($cacheid, $result);

        return $result;
    }

    return false;

}





/**
 *
 * Method to check if there are course filters
 *
 */
function theme_mb2nl_is_course_filters() {
    global $PAGE;

    $coursegrid = theme_mb2nl_is_course_list();
    $coursepage = ($PAGE->pagetype === 'course-index' || $PAGE->pagetype === 'course-index-category');

    if (!$coursepage || !$coursegrid) {
        return;
    }

    return true;

}







/**
 *
 * Method to get course filter form
 *
 */
function theme_mb2nl_courses_filter_form($sidebar=true) {
    global $PAGE;

    $filterpos = theme_mb2nl_theme_setting($PAGE, 'filterpos');

    if (($sidebar && $filterpos === 'top') || (!$sidebar && $filterpos === 'sidebar')) {
        return;
    }

    $output = '';

    if (!theme_mb2nl_is_course_filters()) {
        return;
    }

    $output .= $filterpos === 'sidebar' ? theme_mb2nl_course_top_bar() : ''; // This is require for mobile filter.

    $output .= '<div id="cfilter_wrap" class="cfilter-wrap position-relative filterpos' . $filterpos . '">';
    $output .= '<button type="button" class="themereset filter-close position-absolute" aria-label="' .
    get_string('closebuttontitle') . '"><i class="bi bi-x-lg"></i></button>';
    $output .= '<form name="theme-course-filter" class="theme-course-filter" action="index.php" method="POST">';
    $output .= '<div class="inner' . theme_mb2nl_bsfcls(1) . '"></div>'; // Filters loaded via js.
    $output .= '<div class="submit-filter">';
    $output .= '<input type="submit" value="' . get_string('filter') . '">';
    $output .= '</div>';
    $output .= '</form>';
    $output .= '</div>';

    return $output;

}






/**
 *
 * Method to get course filter form
 *
 */
function theme_mb2nl_courses_filter_price() {
    global $PAGE;

    $output = '';
    $courseprice = theme_mb2nl_theme_setting($PAGE, 'courseprice');

    if (! $courseprice || ! theme_mb2nl_get_paidfree_courses_count() ||  ! theme_mb2nl_get_paidfree_courses_count(true)) {
        return;
    }

    $output .= '<div class="filter-block">';

    $output .= theme_mb2nl_courses_filter_heading(get_string('price', 'theme_mb2nl'));

    $output .= '<div id="cfilter_price" class="filter-content">';
    $output .= '<ul class="filter-price">';

    $output .= '<li class="filter-form-field">';
    $output .= '<div class="field-container">';
    $output .= '<label for="price_all">';
    $output .= '<input class="mb2filter-inpt" type="radio" id="price_all" name="filter_price" value="-1" checked>';
    $output .= '<i></i>';
    $output .= get_string('all') . ' <span class="info">(' . theme_mb2nl_get_courses([], true) . ')</span></label>';
    $output .= '</div>';
    $output .= '</li>';

    $output .= '<li class="filter-form-field">';
    $output .= '<div class="field-container">';
    $output .= '<label for="price_free">';
    $output .= '<input class="mb2filter-inpt" type="radio" id="price_free" name="filter_price" value="0">';
    $output .= '<i></i>';
    $output .= get_string('noprice', 'theme_mb2nl') . ' <span class="info">(' .
    theme_mb2nl_get_paidfree_courses_count(true) . ')</span></label>';
    $output .= '</div>';
    $output .= '</li>';

    $output .= '<li class="filter-form-field">';
    $output .= '<div class="field-container">';
    $output .= '<label for="price_paid">';
    $output .= '<input class="mb2filter-inpt" type="radio" id="price_paid" name="filter_price" value="1">';
    $output .= '<i></i>';
    $output .= get_string('paid', 'theme_mb2nl') . ' <span class="info">(' .
    theme_mb2nl_get_paidfree_courses_count(false) . ')</span></label>';
    $output .= '</div>';
    $output .= '</li>';

    $output .= '</ul>';
    $output .= '</div>'; // ...filter-content
    $output .= '</div>';

    return $output;

}


/**
 *
 * Method to get course filter form
 *
 */
function theme_mb2nl_courses_filter_instructors($teacherid=0) {
    global $PAGE;

    $output = '';
    $cls = '';
    $clslist = '';

    if (!theme_mb2nl_theme_setting($PAGE, 'coursinstructor')) {
        return;
    }

    $instructors = theme_mb2nl_get_all_teachers();

    if (!count($instructors)) {
        return;
    }

    $output .= '<div class="filter-block">';
    $output .= '<input type="hidden" name="filter_instructors" value="">';

    $output .= theme_mb2nl_courses_filter_heading(get_string('instructors', 'theme_mb2nl'));

    $output .= '<div id="cfilter_instructors" class="filter-content filter-instructors' . $cls . '">';
    $output .= '<ul class="filter-instructors-lis' . $clslist . '">';

    foreach ($instructors as $instructor) {
        $courses = theme_mb2nl_get_instructor_courses_count($instructor->id, true);
        $coursescount = ' <span class="info">(' . $courses . ')</span>';

        if (!$courses) {
            continue;
        }

        $checked = $teacherid == $instructor->id ? ' checked' : '';

        $output .= '<li class="filter-form-field">';
        $output .= '<div class="field-container">';
        $output .= '<label for="instructorid_' . $instructor->id . '">';
        $output .= '<input class="mb2filter-inpt" type="checkbox" id="instructorid_' . $instructor->id
        . '" name="filter_instructors[]" value="' . $instructor->id . '"' . $checked . '>';
        $output .= '<i></i>';
        $output .= $instructor->firstname . ' ' . $instructor->lastname . $coursescount .'</label>';
        $output .= '</div>'; // ...field-container
        $output .= '</li>';
    }

    $output .= '</ul>';

    $output .= '</div>'; // ...filter-instructors
    $output .= '</div>';

    return $output;

}



/**
 *
 * Method to get course filter tags form
 *
 */
function theme_mb2nl_courses_filter_tags($tagid='') {
    global $PAGE;

    $coursetagsset = theme_mb2nl_theme_setting($PAGE, 'coursetags');
    $coursetags = theme_mb2nl_course_tags();
    $cls = '';
    $clslist = '';
    $output = '';

    if (!$coursetagsset || !count($coursetags)) {
        return;
    }

    $output .= '<div class="filter-block">';
    $output .= '<input type="hidden" name="filter_tags" value="">';

    $output .= theme_mb2nl_courses_filter_heading(get_string('tags'));

    $output .= '<div id="cfilter_tags" class="filter-content filter-tags' . $cls . '">';
    $output .= '<ul class="filter-tags-list' . $clslist . '">';

    foreach ($coursetags as $tag) {
        $courses = theme_mb2nl_get_tags_course_count($tag->id);
        $coursescount = ' <span class="info">(' . $courses . ')</span>';

        if (!$courses) {
            continue;
        }

        $checked = in_array($tag->id, explode('|', $tagid)) ? ' checked' : '';

        $output .= '<li class="filter-form-field">';
        $output .= '<div class="field-container">';
        $output .= '<label for="tagid_' . $tag->id . '">';
        $output .= '<input class="mb2filter-inpt" type="checkbox" id="tagid_' . $tag->id . '" name="filter_tags[]" value="' .
        $tag->id . '"' . $checked . '>';
        $output .= '<i></i>';
        $output .= $tag->rawname . $coursescount .'</label>';
        $output .= '</div>'; // ...field-container
        $output .= '</li>';
    }

    $output .= '</ul>';

    $output .= '</div>';
    $output .= '</div>';

    return $output;

}






/**
 *
 * Method to get category record
 *
 */
function theme_mb2nl_category_idbyname($name) {

    global $DB;

    $params = ['name' => trim($name)];

    $recordsql = 'SELECT id FROM {course_categories} WHERE ' . $DB->sql_like('name', ':name');

    if (!$DB->record_exists_sql($recordsql, $params)) {
        return 0;
    }

    return $DB->get_record_sql($recordsql, $params)->id;

}









/**
 *
 * Method to get category record
 *
 */
function theme_mb2nl_get_category_record($categoryid, $fields='*') {
    global $DB;

    // Get cache.
    $cache = cache::make('theme_mb2nl', 'category');
    $cacheid = 'category_' . $categoryid;

    if ($cache->get($cacheid)) {
        return $cache->get($cacheid);
    }

    $params = ['id' => $categoryid];

    $recordsql = 'SELECT ' . $fields . ' FROM {course_categories} WHERE id=:id';

    if (!$DB->record_exists_sql($recordsql, $params)) {
        return;
    }

    $category = $DB->get_record_sql($recordsql, $params);

    // Set cache.
    $cache->set($cacheid, $category);

    return $category;

}






/**
 *
 * Method to get categories tree
 *
 */
function theme_mb2nl_get_categories_tree($catid) {

    $cats = [];
    $category = theme_mb2nl_get_category_record($catid);
    $path = substr($category->path, 1);
    $categories = explode('/', $path);

    foreach ($categories as $c) {
        $cats[] = theme_mb2nl_get_category_record($c);
    }

    return $cats;

}






/**
 *
 * Method to get categories tree
 *
 */
function theme_mb2nl_categories_tree($catid) {
    global $CFG;

    require_once($CFG->libdir . '/navigationlib.php');

    $output = '';
    $homepage = get_home_page();
    $homestr = get_string('myhome');

    if ($homepage == HOMEPAGE_SITE) {
        $homestr = get_string('home');
    } else if ($homepage == HOMEPAGE_MYCOURSES) {
        $homestr = get_string('mycourses');
    }

    $categories = theme_mb2nl_get_categories_tree($catid);

    $output .= '<ul class="course-categories-tree' . theme_mb2nl_bsfcls(2, 'wrap', '', 'center') . '">';

    $output .= '<li class="' .theme_mb2nl_bsfcls(2, '', '', 'center') . '">';
    $output .= '<a class="tree-link home-link mr-1' .theme_mb2nl_bsfcls(2, '', '', 'center') . '" href="' .
    new moodle_url('/'). '"><span class="sr-only">' . $homestr . '</span><i class="ri-home-line"></i></a>';
    $output .= '</li>';

    $output .= '<li class="' .theme_mb2nl_bsfcls(2, '', '', 'center') . '">';
    $output .= '<a class="tree-link" href="' . new moodle_url('/course/'). '">' . get_string('courses') . '</a>';
    $output .= '</li>';

    foreach ($categories as $category) {
        $catlink = new moodle_url('/course/index.php', ['categoryid' => $category->id]);
        $output .= '<li class="' . theme_mb2nl_bsfcls(2, '', '', 'center') . '">';
        $output .= '<a class="tree-link" href="' . $catlink . '">' . theme_mb2nl_format_str($category->name) . '</a>';
        $output .= '</li>';
    }

    $output .= '</ul>';

    return $output;

}







/**
 *
 * Method to get categories list
 *
 */
function theme_mb2nl_get_categories($notempty = false, $opts=[]) {

    global $DB, $PAGE, $USER;

    // Check for cache.
    $cache = cache::make('theme_mb2nl', 'categories');
    $opts['notempty'] = $notempty;
    $cacheid = 'catlist_' . serialize($opts);

    if ($cache->get($cacheid)) {
        return $cache->get($cacheid);
    }

    $params = [];
    $sqlwhere = ' WHERE 1=1';
    $canviewhidden = has_capability('moodle/category:viewhiddencategories', context_system::instance());

    $excludecat = theme_mb2nl_course_excats();

    if ($excludecat[0]) {
        $isnot = count($excludecat) > 1 ? 'NOT ' : '!';

        list($excatinsql, $excatparams) = $DB->get_in_or_equal($excludecat);
        $params = array_merge($params, $excatparams);
        $sqlwhere .= ' AND ca.id ' . $isnot . $excatinsql;
    }

    // Custom exclude categories.
    // Require for coursetabs shortcode.
    if ((isset($opts['excats']) && isset($opts['catids'])) && $opts['excats'] && $opts['catids']) {
        $isnot = '';
        $excludecat2 = explode(',', $opts['catids']);
        $excludecat2 = array_map('trim', $excludecat2);

        if ($opts['excats'] === 'exclude') {
            $isnot = count($excludecat2) > 1 ? 'NOT ' : '!';
        }

        list($excatinsql2, $excatparams2) = $DB->get_in_or_equal($excludecat2);
        $params = array_merge($params, $excatparams2);
        $sqlwhere .= ' AND ca.id ' . $isnot . $excatinsql2;
    }

    if ($notempty) {
        $sqlwhere .= ' AND EXISTS(SELECT c.id FROM {course} c WHERE c.category=ca.id AND c.visible=1)';
    }

    $sqlwhere .= !$canviewhidden ? ' AND ca.visible=1' : '';
    $recordsql = 'SELECT ca.id, ca.name, ca.parent, ca.visible, ca.depth, ca.path FROM {course_categories} ca';
    $orderby = ' ORDER BY sortorder';

    $categories = $DB->get_records_sql($recordsql . $sqlwhere . $orderby, $params);

    // Set cache.
    $cache->set($cacheid, $categories);

    return $categories;

}







/**
 *
 * Method to check if user can see category
 *
 */
function theme_mb2nl_category_canview($category) {
    global $USER;

    if ($category->visible) {
        return true;
    } else if (! $category->visible && has_capability('moodle/category:viewhiddencategories', context_system::instance())) {
        return true;
    }

    return false;

}





/**
 *
 * Method to get categories list
 *
 */
function theme_mb2nl_courses_filter_categories($categoryid=0) {
    global $PAGE;

    $output = '';
    $categories = theme_mb2nl_get_categories();
    $level = 0;
    $cls = '';
    $clslist = '';

    $output .= '<div class="filter-block">';
    $output .= '<input type="hidden" name="filter_categories" value="">';
    $output .= theme_mb2nl_courses_filter_heading(get_string('categories'));
    $output .= '<div id="cfilter_categories" class="filter-content filter-categories' . $cls . '">';
    $output .= '<ul class="filter-categories-list' . $clslist . '">';

    foreach ($categories as $category) {
        $level++;
        $children = theme_mb2nl_get_children($category->id);
        $output .= theme_mb2nl_category_level($category, $children, 1, $categoryid);
    }

    $output .= '</ul>';
    $output .= '</div>'; // ...filter-categories
    $output .= '</div>';

    return $output;

}




/**
 *
 * Method to get categories list
 *
 */
function theme_mb2nl_courses_filter_heading($str) {

    $output = '';

    $output .= '<div class="filter-heading mb-3' . theme_mb2nl_bsfcls(1, '', 'between', 'center') . '">';
    $output .= '<h4 class="filter-title mb-0">' . $str . '</h4>';
    $output .= '</div>'; // ...filter-heading

    return $output;

}




/**
 *
 * Method to get course tags
 *
 */
function theme_mb2nl_course_tags($opts=[], $fields='t.id,t.name,t.rawname') {

    global $DB, $PAGE, $USER;

    // Check for cache.
    $cache = cache::make('theme_mb2nl', 'coursetags');
    $cacheid = 'coursetaglist_' . serialize($opts);

    if ($cache->get($cacheid)) {
        return $cache->get($cacheid);
    }

    $params = [];
    $sqlwhere = ' WHERE 1=1';
    $sqlorder = '';

    $recordsql = 'SELECT DISTINCT ' . $fields . ' FROM {tag} t JOIN {tag_instance} ti ON ti.tagid=t.id JOIN {context} cx';
    $recordsql .= ' ON cx.id=ti.contextid JOIN {course} c ON c.id=cx.instanceid';

    $sqlwhere .= ' AND ti.itemtype=?';
    $params[] = 'course';

    $sqlwhere .= ' AND cx.contextlevel=?';
    $params[] = CONTEXT_COURSE;

    $sqlwhere .= ' AND c.visible=?';
    $params[] = 1;

    // Exclude tags filter.
    $extags = theme_mb2nl_course_extags();

    if ($extags[0]) {
        $isnotags = count($extags) > 1 ? 'NOT ' : '!';
        list($extagnsql, $extagparams) = $DB->get_in_or_equal($extags);
        $params = array_merge($params, $extagparams);

        $sqlwhere .= ' AND t.id ' . $isnotags . $extagnsql;
    }

    // Custom exclude categories.
    // Require for coursetabs shortcode.
    if ((isset($opts['extags']) && isset($opts['tagids'])) && $opts['extags'] && $opts['tagids']) {
        $isnot = '';
        $excludetag2 = explode(',', $opts['tagids']);
        $excludetag2 = array_map('trim', $excludetag2);

        if ($opts['extags'] === 'exclude') {
            $isnot = count($excludetag2) > 1 ? 'NOT ' : '!';
        }

        list($extaginsql2, $extagparams2) = $DB->get_in_or_equal($excludetag2);
        $params = array_merge($params, $extagparams2);
        $sqlwhere .= ' AND t.id ' . $isnot . $extaginsql2;
    }

    // Tgas of expired courses.
    if (!theme_mb2nl_theme_setting($PAGE, 'expiredcourses')) {
        $sqlwhere .= ' AND (c.enddate=? OR c.enddate>?)';
        $params[] = 0;
        $params[] = theme_mb2nl_get_user_date();
    }

    $sqlorder .= ' ORDER BY t.name ASC';

    $tags = $DB->get_records_sql($recordsql . $sqlwhere . $sqlorder, $params);

    // Set cache.
    $cache->set($cacheid, $tags);

    return $tags;

}








/**
 *
 * Method to get courses count by tag
 *
 */
function theme_mb2nl_get_tags_course_count($tagid) {

    global $DB, $PAGE, $USER;

    // Check for cache.
    $cache = cache::make('theme_mb2nl', 'coursetags');
    $cacheid = 'tag_course_count_' . $tagid;

    if ($cache->get($cacheid)) {
        return $cache->get($cacheid);
    }

    $params = [];
    $excats = theme_mb2nl_course_excats();
    $extags = theme_mb2nl_course_extags();

    $sqlquery = 'SELECT COUNT(c.id) FROM {course} c JOIN {context} cx ON cx.instanceid=c.id JOIN {tag_instance} ti';
    $sqlquery .= ' ON ti.contextid=cx.id WHERE cx.contextlevel=' . CONTEXT_COURSE;
    $sqlquery .= ' AND ti.tagid=' . $tagid;

    // Count onlu visible courses.
    $sqlquery .= ' AND c.visible=1';

    // Exclude expired courses.
    if (! theme_mb2nl_theme_setting($PAGE, 'expiredcourses')) {
        $params[] = theme_mb2nl_get_user_date();
        $sqlquery .= ' AND (c.enddate=0 OR c.enddate>?)';
    }

    // Exclude tags.
    if ($extags[0]) {
        $isnotags = count($extags) > 1 ? 'NOT ' : '!';
        list($extaginsql, $extagparams) = $DB->get_in_or_equal($extags);
        $params = array_merge($params, $extagparams);

        $sqlquery .= ' AND NOT EXISTS(SELECT t.id FROM {tag} t JOIN {tag_instance} ti ON ti.tagid=t.id JOIN {context} cx';
        $sqlquery .= ' ON cx.id=ti.contextid WHERE c.id=cx.instanceid';
        $sqlquery .= ' AND cx.contextlevel = ' . CONTEXT_COURSE;
        $sqlquery .= ' AND t.id ' . $extaginsql;
        $sqlquery .= ')';
    }

    // Exclude categories.
    if ($excats[0]) {
        $isnotexcat = count($excats) > 1 ? 'NOT ' : '!';
        list($excatnsql, $excatparams) = $DB->get_in_or_equal($excats);
        $params = array_merge($params, $excatparams);

        $sqlquery .= ' AND c.category ' . $isnotexcat . $excatnsql;
    }

    $count = $DB->count_records_sql($sqlquery, $params);

    // Set cache.
    $cache->set($cacheid, $count);

    return $count;

}





/**
 *
 * Method to get courses exlude tags
 *
 */
function theme_mb2nl_course_extags() {
    global $PAGE;

    $exctags = theme_mb2nl_theme_setting($PAGE, 'exctags');
    return explode(',', $exctags);

}





/**
 *
 * Method to get categories list
 *
 */
function theme_mb2nl_get_children($parentid, $fields = 'id,name') {

    global $DB;

    // Get cache.
    $cache = cache::make('theme_mb2nl', 'categories');
    $cacheid = 'child_categories_' . serialize(['parentid' => $parentid, 'fields' => $fields]);

    if ($cache->get($cacheid)) {
        return $cache->get($cacheid);
    }

    $canviewhidden = has_capability('moodle/category:viewhiddencategories', context_system::instance());
    $params = [
        'parent' => $parentid,
        'visible' => 1,
    ];

    $recordsql = 'SELECT ' . $fields . ' FROM {course_categories} WHERE parent=:parent';
    $recordsql .= !$canviewhidden ? ' AND visible=:visible' : '';
    $recordsql .= ' ORDER BY sortorder';

    $childcategories = $DB->get_records_sql($recordsql, $params);

    // Set cache.
    $cache->set($cacheid, $childcategories);

    return $childcategories;

}









/**
 *
 * Method to get categories list
 *
 */
function theme_mb2nl_category_level($category, $children, $level=1, $categoryid=0) {
    global $PAGE;

    $output = '';
    $excludecat = theme_mb2nl_course_excats();
    $togglecat = theme_mb2nl_theme_setting($PAGE, 'togglecat');

    if ($category->depth == $level && theme_mb2nl_category_canview($category)) {
        $ccount = theme_mb2nl_get_category_course_count($category->id);
        $coursescount = ' <span class="info">(' . $ccount . ')</span>';
        $disabled = !$ccount ? ' disabled' : '';
        $disabledcls = $disabled;
        $hiddenicon = !$category->visible ? ' <span class="hidden-icon"><span class="sr-only">' .
        get_string('hidden', 'theme_mb2nl') . '</span><i class="ri-eye-off-line"></i></span>' : '';

        $checked = ($categoryid > 0 && $categoryid == $category->id) ? ' checked' : '';

        if (count($children) && !$ccount) {
            $coursescount = '';
            $disabledcls = ' disabled1';
        }

        $output .= '<li class="filter-form-field">';
        $output .= '<div class="field-container' . $disabledcls . '">';
        $output .= '<label for="catid_' . $category->id . '">';
        $output .= '<input class="mb2filter-inpt" type="checkbox" id="catid_' . $category->id
        . '" name="filter_categories[]" value="' . $category->id . '"' . $disabled . $checked . '>';
        $output .= '<i></i>';
        $output .= theme_mb2nl_format_str($category->name) . $hiddenicon . $coursescount . '</label>';
        $output .= count($children) && $togglecat ? '<button type="button" class="toggle-list-btn themereset" aria-label="' .
        get_string('togglecategory', 'theme_mb2nl', theme_mb2nl_format_str($category->name))
        . '" aria-expanded="false" aria-controls="childcat-' . $category->id . '"></button>' : '';
        $output .= '</div>'; // ...field-container
        $level++;

        if (count($children)) {
            $togglecls = $togglecat ? ' toggle-list' : '';
            $output .= '<ul id="childcat-' . $category->id . '" class="child-list' . $togglecls . '">';

            foreach ($children as $child) {
                if (in_array($child->id, $excludecat)) {
                    continue;
                }

                $children = theme_mb2nl_get_children($child->id);
                $category = theme_mb2nl_get_category_record($child->id);
                $output .= theme_mb2nl_category_level($category, $children, $level, $categoryid);
            }

            $output .= '</ul>';
        }

        $output .= '</li>';
    }

    return $output;

}



/**
 *
 * Method to get all teachers
 *
 */
function theme_mb2nl_get_all_teachers($opts=[], $fields='u.id,u.firstname,u.lastname') {
    global $DB, $USER;

    $perpage = isset($opts['limit']) ? $opts['limit'] : 0;

    // Check for cache.
    $cache = cache::make('theme_mb2nl', 'course');
    $cacheid = 'teacherlist_' . $perpage . '_' . strlen($fields);

    if ($cache->get($cacheid)) {
        return $cache->get($cacheid);
    }

    $teacherroleid = theme_mb2nl_user_roleid(true);
    $andexcats = '';
    $sqlwhere = '';
    $params = [];

    // Do not include deleted and suspensed users.
    $sqlwhere .= ' AND u.deleted=0';
    $sqlwhere .= ' AND u.suspended=0';

    if (isset($opts['teacherids']) &&  $opts['teacherids'] && $opts['exteachers']) {
        $isnot = '';
        $opts['teacherids'] = explode(',', $opts['teacherids']);

        if ($opts['exteachers'] === 'exclude') {
            $isnot = count($opts['teacherids']) > 1 ? 'NOT ' : '!';
        }

        list($teachersnsql, $teachersparams) = $DB->get_in_or_equal($opts['teacherids']);
        $params = array_merge($params, $teachersparams);
        $sqlwhere .= ' AND u.id ' . $isnot . $teachersnsql;
    }

    // Exclude categories filter.
    $excat = theme_mb2nl_course_excats();
    if ($excat[0]) {
        $isnotexcat = count($excat) > 1 ? 'NOT ' : '!';
        list($excatnsql, $excatparams) = $DB->get_in_or_equal($excat);
        $params = array_merge($params, $excatparams);

        $andexcats = ' AND c.category ' . $isnotexcat . $excatnsql;
    }

    $sqlorederby = ' ORDER BY u.lastname';

    $recordsql = 'SELECT DISTINCT ' . $fields . ' FROM {user} u JOIN {role_assignments} ra ON u.id=ra.userid JOIN {context} cx';
    $recordsql .= ' ON ra.contextid = cx.id JOIN {course} c ON cx.instanceid=c.id' . $andexcats . ' AND cx.contextlevel=' .
    CONTEXT_COURSE . ' WHERE 1=1 AND ra.roleid=' . $teacherroleid;

    $teachers = $DB->get_records_sql($recordsql . $sqlwhere . $sqlorederby, $params, 0, $perpage);

    // Set cache.
    $cache->set($cacheid, $teachers);

    return $teachers;

}








/**
 *
 * Method to get course list.
 *
 */
function theme_mb2nl_course_list($opt=[]) {

    global $CFG;

    $output = '';
    $courses = theme_mb2nl_get_courses($opt);
    $coursesnum = theme_mb2nl_get_courses($opt, true);

    // The 'data-page' attribute is required for pagination.
    $output .= '<div class="theme-courses-list" data-page="' . $opt['page'] . '">';

    if (!count($courses)) {
        $output .= '<div class="theme-course-item nothingtodisplay">' . get_string('nothingtodisplay') . '</div>';
    } else {
        $output .= theme_mb2nl_course_list_courses($courses, false, false, $opt);
    }

    $output .= '</div>'; // ...theme-courses-list

    $output .= theme_mb2nl_course_list_pagin($coursesnum, $CFG->coursesperpage, $opt);

    return $output;

}







/**
 *
 * Method to course list.
 *
 */
function theme_mb2nl_course_list_courses($courses, $box = false, $builder = false, $options = []) {
    global $CFG, $PAGE;
    $output = '';
    $reviewplugin = theme_mb2nl_is_review_plugin();
    $quickview = theme_mb2nl_theme_setting($PAGE, 'quickview');
    $rating = '';
    $carousel = (isset($options['carousel']) && $options['carousel']);
    $carouselcls = $carousel ? ' swiper-slide' : '';

    $lazycls = $options['lazy'] ? ' class="lazy"' : '';
    $lazysrc = $options['lazy'] ? 'src="' . theme_mb2nl_lazy_plc() . '" data-src' : 'src';

    // For better speed use require.
    if ($reviewplugin && !class_exists('Mb2reviewsHelper')) {
        require($CFG->dirroot . '/local/mb2reviews/classes/helper.php');
    }

    // Item css classess.
    $cls = theme_mb2nl_citem_cls($box, $options);

    if (! count($courses)) {
        $output .= '<div class="theme-box">';
        $output .= get_string('nothingtodisplay');
        $output .= '</div>';
        return $output;
    }

    foreach ($courses as $course) {
        $courselink = $builder ? '#' : new moodle_url('/course/view.php', ['id' => $course->id]);
        $price = theme_mb2nl_course_price_html($course->id);

        // Course item style.
        $itemstyle = ' style="';
        $itemstyle .= theme_mb2nl_cat_color_itemattr($course);
        $itemstyle .= '--mb2-crandpct:' . rand(20, 80) . '%;';
        $itemstyle .= '"';

        $hiddenicon = ! $course->visible ? ' <span class="hidden-icon"><span class="sr-only">' . get_string('hidden', 'theme_mb2nl')
        . '</span><i class="ri-eye-off-line"></i></span>' : '';

        $cname = theme_mb2nl_format_str($course->fullname);

        $catcls = ' cat-' . $course->category;
        $pricecls = $price ? ' isprice' : ' noprice';

        $coursecontext = context_course::instance($course->id);
        $bestseller = theme_mb2nl_is_bestseller($coursecontext->id, $course->category);
        $bestcls = $bestseller ? ' bestseller' : '';

        $output .= '<div class="theme-course-item course-' . $course->id . $cls . $catcls . $bestcls . $pricecls . $carouselcls
        . '" data-course="' . $course->id . '" data-custom_label="' . strip_tags($cname) . '" role="presentation">';
        $output .= '<div class="theme-course-item-inner position-relative"' . $itemstyle . '>';

        $output .= '<div class="image-wrap">';

        $output .= '<div class="image">';
        $output .= theme_mb2nl_course_badges($course);
        $output .= '<img' . $lazycls . ' ' . $lazysrc . '="' . theme_mb2nl_course_image_url($course->id, true) . '" alt="' .
        $cname . '">';
        $output .= '</div>'; // ...image

        if (!$quickview) {
            $output .= '<div class="image-content">';
            $output .= '<a href="' . $courselink . '" class="linkbtn" tabindex="-1">' . get_string('view') . '</a>';
            $output .= '</div>'; // ...image-content
        }

        $output .= '</div>'; // ...image-wrap

        $output .= '<div class="content-wrap position-relative">';

        $output .= '<div class="course-content">';

        $output .= '<h4 class="title h5 mb-0">';
        $output .= '<a class="d-block" href="' . $courselink . '" tabindex="-1">' . $cname . $hiddenicon . '</a>';
        $output .= '</h4>';

        $output .= '<div class="course-item-deatils tsizesmall lhsmall mt-2' . theme_mb2nl_bsfcls(1, 'wrap', '', 'center') . '">';
        $output .= theme_mb2nl_course_list_catname($course);
        $output .= theme_mb2nl_course_teachers($course->id);
        $output .= '</div>';

        if ($reviewplugin) {
            $ratingobj = theme_mb2nl_review_obj($course->id);

            if ($ratingobj->rating) {
                $output .= '<div class="course-rating mt-2">';
                $output .= '<span class="ratingnum">' . $ratingobj->rating . '</span>';
                $output .= Mb2reviewsHelper::rating_stars($ratingobj->rating, 'sm');
                $output .= '<span class="ratingcount">(' . $ratingobj->rating_count . ')</span>';
                $output .= '</div>'; // ...course-rating
            }
        }

        if (theme_mb2nl_theme_setting($PAGE, 'cdesc')) {
            $output .= '<div class="course-desc mt-3 pb-1">';
            $output .= theme_mb2nl_course_intro($course);
            $output .= '</div>'; // ...course-desc
        }

        $output .= '</div>'; // ...course-content

        $output .= '<div class="course-footer mt-3 pb-2' . theme_mb2nl_bsfcls(1, 'wrap', '', 'center') . '">';
        $output .= $price;
        $output .= theme_mb2nl_course_list_students($course->id);
        $output .= theme_mb2nl_course_list_date($course);
        $output .= '</div>'; // ...course-content

        $output .= '</div>'; // ...content-wrap
        $output .= $courselink ? '<a class="themekeynavlink" href="' . $courselink . '" tabindex="0" aria-label="' . $cname
        . '"></a>' : '';
        $output .= '</div>'; // ...theme-course-item-inner
        $output .= '</div>'; // ...theme-course-item
    }

    return $output;

}







/**
 *
 * Method to set course list pagination.
 *
 */
function theme_mb2nl_course_list_pagin($items, $limit, $opt) {
    $output = '';

    $numpagesround = round($items / $limit, 1);
    $numpages = ceil($numpagesround);
    $endscount = 1;
    $middlecount = 2;
    $dots = false;

    if ($numpages < 2) {
        return;
    }

    $output .= '<div class="theme-courses-pagin">';
    $output .= '<ul class="theme-courses-pagin-list' . theme_mb2nl_bsfcls(1, '', '', 'center') . '">';

    // Set previous button.
    if ($opt['page'] > 1) {
        $output .= '<li>';
        $output .= '<button type="button" class="theme-courses-paginitem themereset p-0' .
        theme_mb2nl_bsfcls(2, '', 'center', 'center') . '" aria-label="' . get_string('previouspage') . '" data-page="' .
        ($opt['page'] - 1) . '">';
        $output .= '<i class="bi bi-chevron-left"></i>';
        $output .= '</button>';
        $output .= '</li>';
    }

    for ($i = 1; $i <= $numpages; $i++) {
        if ($i == $opt['page']) {
            $output .= '<li>';
            $output .= '<button type="button" class="theme-courses-paginitem active themereset p-0' .
            theme_mb2nl_bsfcls(2, '', 'center', 'center') . '" data-page="' . $i . '" aria-label="' .
            get_string('pagedcontentnavigationactiveitem', 'moodle', $i) . '">' . $i;
            $output .= '</button>';
            $output .= '</li>';
            $dots = true;
        } else {
            if ($i <= $endscount || ($opt['page'] && $i >= ($opt['page'] - $middlecount) && $i <= ($opt['page'] + $middlecount))
            || $i > $numpages - $endscount) {
                $output .= '<li>';
                $output .= '<button type="button" class="theme-courses-paginitem themereset p-0' .
                theme_mb2nl_bsfcls(2, '', 'center', 'center') . '" data-page="' . $i . '" aria-label="' .
                get_string('pagea', 'moodle', $i) . '">' . $i;
                $output .= '</button>';
                $output .= '</li>';
                $dots = true;
            } else if ($dots) {
                $output .= '<li><button type="button" class="dots themereset p-0' . theme_mb2nl_bsfcls(2, '', 'center', 'center')
                . '">&#183;&#183;&#183;</button></li>';
                $dots = false;
            }
        }
    }

    // Set next button.
    if ($opt['page'] < $numpages) {
        $output .= '<li>';
        $output .= '<button type="button" class="theme-courses-paginitem themereset p-0' .
        theme_mb2nl_bsfcls(2, '', 'center', 'center') . '" aria-label="' . get_string('nextpage') . '" data-page="' .
        ($opt['page'] + 1) . '">';
        $output .= '<i class="bi bi-chevron-right"></i>';
        $output .= '</button>';
        $output .= '</li>';
    }

    $output .= '</ul>';
    $output .= '</div>';

    return $output;

}





/**
 *
 * Method to set course top bar.
 *
 */
function theme_mb2nl_course_top_bar() {
    global $PAGE;

    $output = '';
    $cls = ' filterpos' . theme_mb2nl_theme_setting($PAGE, 'filterpos');

    $output .= '<div class="theme-courses-topbar' . $cls . theme_mb2nl_bsfcls(1, '', '', 'center') . '">';
    $output .= theme_mb2nl_course_filtertoggle();
    $output .= theme_mb2nl_course_searchform();
    $output .= '</div>';

    return $output;

}




/**
 *
 * Method to set course search form.
 *
 */
function theme_mb2nl_course_filtertoggle() {
    global $PAGE;

    $output = '';

    if (!theme_mb2nl_is_course_filters()) {
        return;
    }

    $output .= '<button type="button" class="themereset filter-toggle p-0' . theme_mb2nl_bsfcls(2, '', '', 'center')
    . '" aria-controls="cfilter_wrap" aria-expanded="false" aria-label="' . get_string('filters') . '">';
    $output .= '<i class="ri-equalizer-line mr-2' . theme_mb2nl_bsfcls(2, '', '', 'center') . '"></i>';
    $output .= '<span class="text">' . get_string('filters') . '</span>';
    $output .= '</button>';

    return $output;

}





/**
 *
 * Method to set course search form.
 *
 */
function theme_mb2nl_course_searchform() {
    $output = '';
    $uniqid = uniqid('csearch_');

    $output .= '<form id="form_' . $uniqid . '" class="theme-course-search" action="index.php" method="GET">';
    $output .= '<div class="search-field' . theme_mb2nl_bsfcls(2, '', '', 'center') . '">';
    $output .= '<input id="' . $uniqid . '" name="' . $uniqid . '" type="search" value="" placeholder="' .
    get_string('searchcourses') . '">';
    $output .= '<button type="submit" aria-label="' . get_string('searchcourses') . '"><i class="ri-search-line"></i></button>';
    $output .= '</div>';
    $output .= '</form>';

    return $output;

}






/**
 *
 * Method to set course layout switcher.
 *
 */
function theme_mb2nl_course_layout_switcher() {
    global $PAGE;

    $output = '';

    $coursegrid = theme_mb2nl_theme_setting($PAGE, 'coursegrid');

    if (! $coursegrid) {
        return;
    }

    if (! theme_mb2nl_theme_setting($PAGE, 'courseswitchlayout')) {
        return;
    }

    $acticeclsgrid = '';
    $acticeclslist = ' active';

    if ($coursegrid) {
        $acticeclsgrid = ' active';
        $acticeclslist = '';
    }

    $output .= '<div class="course-layout-switcher">';
    $output .= '<a href="#" class="grid-layout' . $acticeclsgrid . '" aria-label="' .
    get_string('layoutgrid', 'theme_mb2nl') . '" data-toggle="tooltip" data-trigger="hover"><i class="fa fa-th-large"></i></a>';
    $output .= '<a href="#" class="list-layout' . $acticeclslist . '" aria-label="' .
    get_string('layoutlist', 'theme_mb2nl') . '" data-toggle="tooltip" data-trigger="hover"><i class="fa fa-th-list"></i></a>';
    $output .= '</div>';

    return $output;

}






/**
 *
 * Method to get count courses by custom fileds
 *
 */
function theme_mb2nl_get_cfileds_courses_count($fid, $v) {
    global $DB, $PAGE, $SITE, $USER;

    $cache = cache::make('theme_mb2nl', 'coursefields');
    $cacheid = 'cfield_course_count_' . $fid . '_' . $v;

    // If ther is cache, return it.
    if ($cache->get($cacheid)) {
        return $cache->get($cacheid);
    }

    $canviewhiddencats = has_capability('moodle/category:viewhiddencategories', context_system::instance());
    $params = [];
    $sqlquery = '';
    $sqlwhere = ' WHERE 1=1';

    $hidden = !$canviewhiddencats ? ' AND c.visible=1' : '';
    $andexcats = '';
    $anddate = '';
    $isnot = $v == 0 ? '!=' : '='; // It is required for the 'All" radio filed type.

    // Check expired courses.
    if (!theme_mb2nl_theme_setting($PAGE, 'expiredcourses')) {
        $anddate = ' AND (c.enddate=0 OR c.enddate>' . theme_mb2nl_get_user_date() . ')';
    }

    // Exclude categories filter.
    $excat = theme_mb2nl_course_excats();

    if ($excat[0]) {
        $isnotexcat = count($excat) > 1 ? 'NOT ' : '!';
        list($excatnsql, $excatparams) = $DB->get_in_or_equal($excat);
        $params = array_merge($params, $excatparams);

        $andexcats = ' AND c.category ' . $isnotexcat . $excatnsql;
    }

    $sqlquery = 'SELECT COUNT(c.id) FROM {course} c';

    $sqlwhere .= ' AND c.id !=' . $SITE->id;
    $sqlwhere .= $andexcats;
    $sqlwhere .= $hidden;
    $sqlwhere .= $anddate;

    $sqlwhere .= ' AND EXISTS(SELECT cf.id, cf.fieldid FROM {customfield_data} cf JOIN {context} cx ON cx.id=cf.contextid';
    $sqlwhere .= ' WHERE c.id=cx.instanceid';
    $sqlwhere .= ' AND cx.contextlevel=' . CONTEXT_COURSE;
    $sqlwhere .= ' AND cf.fieldid=' . $fid;
    $sqlwhere .= ' AND cf.value' . $isnot . $v;
    $sqlwhere .= ')';

    $count = $DB->count_records_sql($sqlquery . $sqlwhere, $params);

    // Set cache.
    $cache->set($cacheid, $count);

    return $count;

}








/**
 *
 * Method to get count courses (paid and free).
 *
 */
function theme_mb2nl_get_paidfree_courses_count($free = false, $hidden = false) {
    global $DB, $PAGE;

    // Get cache.
    $cache = cache::make('theme_mb2nl', 'courses');
    $cachepaidid = 'paid_course_count';
    $cachefreeid = 'free_course_count';

    if (!$free && $cache->get($cachepaidid)) {
        return $cache->get($cachepaidid);
    } else if ($free && $cache->get($cachefreeid)) {
        return $cache->get($cachefreeid);
    }

    $canviewhiddencats = has_capability('moodle/category:viewhiddencategories', context_system::instance());
    $params = [];
    $not = $free ? 'NOT ' : '';
    $andcourses = !$canviewhiddencats ? ' AND c.visible=1' : '';
    $andexcats = '';
    $anddate = '';

    // Payment filter.
    list($priceinsql, $priceparams) = $DB->get_in_or_equal(theme_mb2nl_pay_enrolements());
    $params = array_merge($params, $priceparams);

    // Check expired courses.
    if (! theme_mb2nl_theme_setting($PAGE, 'expiredcourses')) {
        $anddate = ' AND (c.enddate=0 OR c.enddate>' . theme_mb2nl_get_user_date() . ')';
    }

    // Exclude categories filter.
    $excat = theme_mb2nl_course_excats();

    if ($excat[0]) {
        $isnotexcat = count($excat) > 1 ? 'NOT ' : '!';
        list($excatnsql, $excatparams) = $DB->get_in_or_equal($excat);
        $params = array_merge($params, $excatparams);

        $andexcats = ' AND c.category ' . $isnotexcat . $excatnsql;
    }

    $sqlquery = 'SELECT COUNT(c.id) FROM {course} c WHERE ' . $not . 'EXISTS(SELECT er.id FROM {enrol} er WHERE er.courseid = c.id';
    $sqlquery .= ' AND er.status = ' . ENROL_INSTANCE_ENABLED . ' AND er.enrol ' . $priceinsql .') AND c.id > 1' . $andexcats .
    $andcourses . $anddate;

    $count = $DB->count_records_sql($sqlquery, $params);

    // Set cache.
    if (!$free) {
        $cache->set($cachepaidid, $count);
    } else {
        $cache->set($cachefreeid, $count);
    }

    return $count;

}



/**
 *
 * Method to get courses exlude categories
 *
 */
function theme_mb2nl_course_excats() {
    global $PAGE;

    $excludecat = theme_mb2nl_theme_setting($PAGE, 'excludecat');
    return explode(',', $excludecat);

}





/**
 *
 * Method to get courses list layout.
 *
 */
function theme_mb2nl_course_list_layout() {
    global $PAGE;

    $output = '';
    $categoryid = optional_param('categoryid', 0, PARAM_INT);

    if ($categoryid) {
        $output .= theme_mb2nl_children_categories($categoryid);
    }

    $output .= '<div class="courses-container">';
    $output .= theme_mb2nl_course_top_bar();
    $output .= theme_mb2nl_courses_filter_form(false);
    $output .= '<div class="courses-container-inner loading"></div>'; // Content loaded via js.
    $output .= '</div>'; // ...courses-container-inner

    $PAGE->requires->js_call_amd('theme_mb2nl/courselist', 'oursesFilters'); // Init course filters and course list.
    $PAGE->requires->js_call_amd('theme_mb2nl/courselist', 'submitForm');
    $PAGE->requires->js_call_amd('theme_mb2nl/courselist', 'pagination');
    $PAGE->requires->js_call_amd('theme_mb2nl/courselist', 'searchCourses');

    return $output;

}




/**
 *
 * Method to get children categories list layout.
 *
 */
function theme_mb2nl_children_categories($parent) {

    $output = '';

    $categories = theme_mb2nl_get_children($parent);
    $svg = theme_mb2nl_svg();

    if (!count($categories)) {
        return;
    }

    $output .= '<div class="children-categories">';

    foreach ($categories as $category) {
        $catlink = new moodle_url('/course/index.php', ['categoryid' => $category->id]);
        $catimgurl = theme_mb2nl_category_image($category->id);
        $catimg = $catimgurl ?
        '<img class="lazy" data-src="' . $catimgurl . '" alt="' . theme_mb2nl_format_str($category->name) . '">' : $svg['folder'];

        $output .= '<div class="children-category cat-' . $category->id . '">';
        $output .= '<a href="' . $catlink . '">';
        $output .= '<div class="cat-image">';
        $output .= $catimg;
        $output .= '</div>'; // ...cat-image
        $output .= '<div class="cat-content">';
        $output .= '<h4 class="children-cat-title">' . theme_mb2nl_format_str($category->name) . '</h4>';
        $output .= '<div class="children-category-details">';
        $output .= get_string('teachercourses', 'theme_mb2nl',
        ['courses' => theme_mb2nl_get_category_course_count($category->id, true)]);
        $output .= '</div>';
        $output .= '</div>'; // ...cat-content
        $output .= '</a>';
        $output .= '</div>';
    }

    $output .= '</div>';

    return $output;

}







/**
 *
 * Method to check if ajax grid course is enabled
 *
 */
function theme_mb2nl_is_course_list() {
    global $PAGE;

    $coursegrid = theme_mb2nl_theme_setting($PAGE, 'coursegrid');

    if ($PAGE->pagetype !== 'course-index-category') {
        return;
    }

    return $coursegrid;

}



/**
 *
 * Method to get categories tabs
 *
 */
function theme_mb2nl_coursetabs_tabs($opts) {
    $i = 0;
    $output = '';
    $style = '';
    $cls = '';
    $categories = theme_mb2nl_get_categories(true, $opts); // True means without emepty categories.
    $coursetags = theme_mb2nl_course_tags($opts);
    $cls .= isset($opts['tabstyle']) ? ' tabstyle' . $opts['tabstyle'] : '';
    $cls .= isset($opts['tcenter']) ? ' tcenter' . $opts['tcenter'] : '';

    $style .= ' style="';
    $style .= isset($opts['acccolor']) && $opts['acccolor'] ? '--mb2-pb-coursetabsacc:' . $opts['acccolor'] . ';' : '';
    $style .= isset($opts['tcolor']) && $opts['tcolor'] ? '--mb2-pb-coursetabsc:' . $opts['tcolor'] . ';' : '';
    $style .= '"';

    $output .= '<div class="coursetabs-tablist' . $cls . theme_mb2nl_bsfcls(1, 'wrap', '', 'center') . '"' . $style . '>';

    if ($opts['filtertype'] === 'tag') {
        foreach ($coursetags as $tag) {
            $coursecount = theme_mb2nl_get_tags_course_count($tag->id);

            if (!$coursecount) {
                continue;
            }

            $i++;
            $isactive = $i == 1 ? ' active' : '';

            $output .= '<button class="coursetabs-catitem position-relative themereset' . $isactive .
            theme_mb2nl_bsfcls(2, '', 'center', 'center') . '" data-category="' .
            $tag->id . '" aria-controls="' . $opts['uniqid'] . '_category-content-' . $tag->id . '" data-uniqid="' .
            $opts['uniqid'] . '">';
            $output .= '<span class="catname">' . $tag->rawname . '</span>';
            $output .= '<span class="coursecount">(' . $coursecount . ')</span>';
            $output .= '</button>'; // ...coursetabs-catitem
        }
    } else {
        foreach ($categories as $category) {
            $coursecount = theme_mb2nl_get_category_course_count($category->id, true);

            if (! $coursecount) {
                continue;
            }

            $i++;
            $isactive = $i == 1 ? ' active' : '';

            $output .= '<button class="coursetabs-catitem position-relative themereset' . $isactive .
            theme_mb2nl_bsfcls(2, '', 'center', 'center') . '" data-category="' .
            $category->id . '" aria-controls="' . $opts['uniqid'] . '_category-content-' . $category->id . '" data-uniqid="' .
            $opts['uniqid'] . '">';
            $output .= '<span class="catname">' . theme_mb2nl_format_str($category->name) . '</span>';
            $output .= '<span class="coursecount">(' . $coursecount . ')</span>';
            $output .= '</button>'; // ...coursetabs-catitem
        }
    }

    $output .= '</div>'; // ...coursetabs-tablist

    return $output;

}



/**
 *
 * Method to get courses in category tabs
 *
 */
function theme_mb2nl_coursetabs_courses($opts, $content=false) {

    $output = '';
    $i = 0;
    $items = theme_mb2nl_get_categories(true, $opts); // True means without emepty categories.
    $istag = $opts['filtertype'] === 'tag';

    if ($istag) {
        $items = theme_mb2nl_course_tags($opts);
    }

    foreach ($items as $item) {
        $count = $istag ? theme_mb2nl_get_tags_course_count($item->id) : theme_mb2nl_get_category_course_count($item->id, true);

        if (!$count) {
            continue;
        }

        $i++;

        // This is required for page builder element.
        // Edit the element -> change the 'Filter type'.
        $ipbaction = $content && $i == 1;
        $activecls = $ipbaction ? ' active' : '';

        $output .= '<div class="coursetabs-content' . $activecls . '" id="' . $opts['uniqid'] . '_category-content-' .
        $item->id  . '">';
        $output .= $ipbaction ? theme_mb2nl_coursetabs_tabcontent($opts, $item->id) : theme_mb2nl_preload();
        $output .= '</div>';
    }

    return $output;

}




/**
 *
 * Method to get category element in course tab
 *
 */
function theme_mb2nl_coursetabs_tabcontent($opts, $id=0) {
    global $PAGE;

    $output = '';

    // This is required for page builder element.
    // Edit the element -> change the 'Filter type'.
    if ($id) {
        if ($opts['filtertype'] === 'tag') {
            $opts['tags'] = [$id];
        } else {
            $opts['categories'] = [$id];
        }
    }

    $courses = theme_mb2nl_get_courses($opts);
    $sliderid = uniqid('swiper_');

    $listcls = '';
    $listcls .= $opts['carousel'] ? ' swiper-wrapper' : '';
    $listcls .= ! $opts['carousel'] ? ' theme-boxes theme-col-' . $opts['columns'] : '';
    $listcls .= ! $opts['carousel'] ? ' gutter-' . $opts['gutter'] : '';

    $containercls = $opts['carousel'] ? ' swiper' : '';

    $output .= $opts['catdesc'] ? theme_mb2nl_coursetabs_category($opts['categories'][0]) : '';
    $output .= '<div id="' . $sliderid . '" class="mb2-pb-content' . $containercls . '">';
    $output .= theme_mb2nl_shortcodes_swiper_nav($sliderid);
    $output .= '<div class="mb2-pb-content-list' . $listcls . '">';
    $output .= theme_mb2nl_shortcodes_course_template($courses, $opts);
    $output .= '</div>'; // ...mb2-pb-content-list
    $output .= theme_mb2nl_shortcodes_swiper_pagenavnav();
    $output .= '</div>'; // ...mb2-pb-content

    return $output;

}




/**
 *
 * Method to get category element in course tab
 *
 */
function theme_mb2nl_coursetabs_category($catid) {
    global $PAGE, $OUTPUT, $CFG;
    require_once($CFG->libdir . '/filelib.php');

    $output = '';
    $category = theme_mb2nl_get_category_record($catid);
    $context = context_coursecat::instance($category->id);

    // Get category description.
    $description = file_rewrite_pluginfile_urls($category->description, 'pluginfile.php', $context->id,
    'coursecat', 'description', null);
    $description = theme_mb2nl_format_txt($description);

    // Category image.
    $catimage = theme_mb2nl_category_image($category->id);
    $courseplaceholder = theme_mb2nl_theme_setting($PAGE, 'courseplaceholder', '', true);
    $plchimage = $courseplaceholder ? $courseplaceholder : $OUTPUT->image_url('course-default', 'theme');
    $imgurl = $catimage ? $catimage : $plchimage;

    $catlink = new moodle_url('/course/index.php', ['categoryid' => $category->id]);

    $output .= '<div class="coursetabs-category">';
    $output .= '<div class="category-desc">';
    $output .= '<h4 class="category-title">' . get_string('categorydesc', 'theme_mb2nl') . '</h4>';
    $output .= $description ? $description : '<p>To add category description and image you have to edit category: <b>' .
    $category->name . '</b>. To set category image just insert an image into category description.</p>';
    $output .= '<div class="category-readmore">';
    $output .= '<a href="' . $catlink . '" class="btn btn-primary">' . get_string('explorecategory', 'theme_mb2nl',
    ['category' => $category->name]) . '</a>';
    $output .= '</div>'; // ...category-readmore
    $output .= '</div>'; // ...category-desc
    $output .= '<div class="category-image">';
    $output .= '<div class="catimage" style="background-image:url(\'' . $imgurl . '\');"></div>';
    $output .= '</div>'; // ...category-image
    $output .= '</div>'; // ...coursetabs-category

    return $output;

}



/**
 *
 * Method to get category description
 *
 */
function theme_mb2nl_category_desc($catid) {
    global $COURSE, $CFG;
    require_once($CFG->libdir . '/filelib.php');

    $category = theme_mb2nl_get_category_record($catid);

    if (! $category) {
        return;
    }

    $context = context_coursecat::instance($catid);
    $desc = file_rewrite_pluginfile_urls($category->description, 'pluginfile.php', $context->id, 'coursecat', 'description', null);
    $desc = theme_mb2nl_format_txt($desc, FORMAT_HTML);

    return $desc;

}






/**
 *
 * Method to get category element in course tab
 *
 */
function theme_mb2nl_category_image($catid, $name=false) {
    $desc = theme_mb2nl_category_desc($catid);
    return theme_mb2nl_url_from_text($desc, $name);
}







/**
 *
 * Method to get category color
 *
 */
function theme_mb2nl_cat_color($catid) {

    if (!count(theme_mb2nl_cat_color_settings())) {
        return [];
    }

    // Get cached colors.
    $cache = cache::make('theme_mb2nl', 'catcolors');
    $cacheid = 'catcolors';

    if (!$cache->get($cacheid)) {
        // Get colors and set it for cache.
        $colors = theme_mb2nl_assign_cat_colors();
        $cache->set($cacheid, ['colors' => $colors]);
    }

    // Get cache.
    $colors = $cache->get($cacheid)['colors'];

    if (isset($colors[$catid])) {
        return $colors[$catid];
    }

    return [];

}




/**
 *
 * Method to assign color to categories
 *
 */
function theme_mb2nl_assign_cat_colors() {

    $cats = [];
    $categories = theme_mb2nl_get_categories();
    $colors = theme_mb2nl_cat_color_settings();

    foreach ($categories as $cat) {
        $cat->path = substr(trim($cat->path), 1);
        $path = explode('/', $cat->path);
        $color = '';

        // Get the category path colors.
        foreach ($path as $p) {
            $color .= isset($colors[$p]) ? $colors[$p] . '-' : '';
        }

        // Remove the last "-" character.
        $color = $color ? $color[-1] === '-' ? substr($color, 0, -1) : $color : '';

        // Make an array of the path colors
        // and get the last color.
        $color = explode('-', $color);
        $color = end($color);

        // Assign color to the category.
        $cats[$p] = isset($colors[$cat->path]) ? explode(':', $colors[$cat->path]) : explode(':', $color);

    }

    return $cats;

}






/**
 *
 * Method to set category settings color array
 *
 */
function theme_mb2nl_cat_color_settings() {
    global $PAGE;

    $colorss = theme_mb2nl_theme_setting($PAGE, 'catcolors');
    $colorcats = [];

    // Explode new line.
    $linearr = preg_split('/\s*\R\s*/', trim($colorss));

    foreach ($linearr as $line) {
        $linearr = explode('|', $line);
        $catid = theme_mb2nl_category_idbyname($linearr[0]);

        if (!isset($linearr[1]) || !$catid) {
            continue;
        }

        $colorcats[$catid] = $linearr[1];

    }

    return $colorcats;

}




/**
 *
 * Method to get category color
 *
 */
function theme_mb2nl_cat_color_itemattr($course, $catid = 0) {
    $style = '';

    $iscat = $catid ? $catid : $course->category;
    $colors = theme_mb2nl_cat_color($iscat);

    foreach ($colors as $k => $c) {
        if (!$c) {
            continue;
        }

        $style .= '--mb2-catcolor' . $k . ':' . $c .';';
    }

    return $style;

}






/**
 *
 * Method to get category color
 *
 */
function theme_mb2nl_cat_color_attr($on=false) {
    global $COURSE;

    $curentcat = optional_param('categoryid', 0, PARAM_INT);

    if ((!theme_mb2nl_is_course() && !$curentcat) || !$on) {
        return;
    }

    $style = '';

    $isid = $curentcat ? $curentcat : $COURSE->category;
    $colors = theme_mb2nl_cat_color($isid);

    foreach ($colors as $k => $c) {
        if (!$c) {
            continue;
        }

        $style .= '--mb2-pb-pheadergrad' . ($k + 1) . ':' . $c .';';
        $style .= $k == 0 && !isset($colors[$k + 1]) ? '--mb2-pb-pheadergrad2:' . $c .';' : '';
    }

    return $style;

}
