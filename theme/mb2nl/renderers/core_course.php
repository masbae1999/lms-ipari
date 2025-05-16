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

require_once($CFG->dirroot . '/course/renderer.php');

/**
 * Course renderer
 */
class theme_mb2nl_core_course_renderer extends core_course_renderer {




    /**
     * Returns HTML to display a tree of subcategories and courses in the given category
     *
     * @param coursecat_helper $chelper various display options
     * @param core_course_category $coursecat top category (this category's name and description will NOT be added to the tree)
     * @return string
     */
    protected function coursecat_tree(coursecat_helper $chelper, $coursecat) {

        // Start content generation.
        $content = '';
        $attributes = $chelper->get_and_erase_attributes('course_category_tree clearfix');

        if (theme_mb2nl_is_course_list()) {
            return theme_mb2nl_course_list_layout();
        }

        // Reset the category expanded flag for this course category tree first.
        $this->categoryexpandedonload = false;
        $categorycontent = $this->coursecat_category_content($chelper, $coursecat, 0);
        if (empty($categorycontent)) {
            return '';
        }

        $content .= html_writer::start_tag('div', $attributes);

        if ($coursecat->get_children_count()) {
            $classes = [
                'collapseexpand',
                'aabtn',
            ];

            // Check if the category content contains subcategories with children's content loaded.
            if ($this->categoryexpandedonload) {
                $classes[] = 'collapse-all';
                $linkname = get_string('collapseall');
            } else {
                $linkname = get_string('expandall');
            }

            // Only show the collapse/expand if there are children to expand.
            $content .= html_writer::start_tag('div', ['class' => 'collapsible-actions']);
            $content .= html_writer::link('#', $linkname, ['class' => implode(' ', $classes)]);
            $content .= html_writer::end_tag('div');
            $this->page->requires->strings_for_js(['collapseall', 'expandall'], 'moodle');
        }

        $content .= html_writer::tag('div', $categorycontent, ['class' => 'content']);

        $content .= html_writer::end_tag('div'); // ...course_category_tree

        return $content;
    }


    /**
     * Returns HTML to display a course category as a part of a tree
     *
     * This is an internal function, to display a particular category and all its contents
     * use {@link core_course_renderer::course_category()}
     *
     * @param coursecat_helper $chelper various display options
     * @param core_course_category $coursecat
     * @param int $depth depth of this category in the current tree
     * @return string
     */
    protected function coursecat_category(coursecat_helper $chelper, $coursecat, $depth) {

        // ...open category tag
        $classes = ['category'];
        if (empty($coursecat->visible)) {
            $classes[] = 'dimmed_category';
        }
        if ($chelper->get_subcat_depth() > 0 && $depth >= $chelper->get_subcat_depth()) {
            // ...do not load content
            $categorycontent = '';
            $classes[] = 'notloaded';
            if ($coursecat->get_children_count() ||
                    ($chelper->get_show_courses() >= self::COURSECAT_SHOW_COURSES_COLLAPSED && $coursecat->get_courses_count())) {
                $classes[] = 'with_children';
                $classes[] = 'collapsed';
            }
        } else {
            // ...load category content
            $categorycontent = $this->coursecat_category_content($chelper, $coursecat, $depth);
            $classes[] = 'loaded';
            if (!empty($categorycontent)) {
                $classes[] = 'with_children';
                // Category content loaded with children.
                $this->categoryexpandedonload = true;
            }
        }

        // Make sure JS file to expand category content is included.
        $this->coursecat_include_js();

        $content = html_writer::start_tag('div', [
            'class' => join(' ', $classes),
            'data-categoryid' => $coursecat->id,
            'data-depth' => $depth,
            'data-showcourses' => $chelper->get_show_courses(),
            'data-type' => self::COURSECAT_TYPE_CATEGORY,
          ]);

        // ...category name
        $categoryname = $coursecat->get_formatted_name();
        $categoryname = html_writer::link(new moodle_url('/course/index.php',
                ['categoryid' => $coursecat->id]),
                $categoryname);
        if ($chelper->get_show_courses() == self::COURSECAT_SHOW_COURSES_COUNT
                && ($coursescount = $coursecat->get_courses_count())) {
            $categoryname .= html_writer::tag('span', ' ('. $coursescount.')',
                    ['title' => get_string('numberofcourses'), 'class' => 'numberofcourse']);
        }
        $content .= html_writer::start_tag('div', ['class' => 'info']);

        $content .= html_writer::tag(($depth > 1) ? 'h4' : 'h3', $categoryname, [
            'class' => 'categoryname position-relative aabtn',
            'style' => theme_mb2nl_cat_color_itemattr(null, $coursecat->id)]);
        $content .= html_writer::end_tag('div'); // ...info

        // ...add category content to the output
        $content .= html_writer::tag('div', $categorycontent, ['class' => 'content']);

        $content .= html_writer::end_tag('div'); // ...category

        // Return the course category tree HTML.
        return $content;
    }



    /**
     *
     * Method to render course box.
     *
     */
    protected function coursecat_coursebox(coursecat_helper $chelper, $course, $additionalclasses = '') {

        global $CFG, $USER;

        if (!isset($this->strings->summary)) {
            $this->strings->summary = get_string('summary');
        }

        if ($chelper->get_show_courses() <= self::COURSECAT_SHOW_COURSES_COUNT) {
            return '';
        }

        if ($course instanceof stdClass) {
            $course = new core_course_list_element($course);
        }

        $content = '';
        $showinfobox = ($chelper->get_show_courses() >= self::COURSECAT_SHOW_COURSES_EXPANDED);
        $summarycontent = $chelper->get_course_formatted_summary($course, ['overflowdiv' => true, 'noclean' => true,
        'para' => false]);
        $summaryhasp = ($course->has_summary() && theme_mb2nl_check_for_tags($summarycontent, 'p'));
        $infocls = $summaryhasp ? ' summaryisp' : ' summarynop';
        $infocls .= !$course->has_summary() ? ' nosummary' : '';
        $infocls .= $showinfobox ? ' noinfobox' : ' collapsed isinfobox';
        $infocls .= (isset($course->visible) && !$course->visible) ? ' course-hidden' : '';
        $infocls .= is_enrolled(context_course::instance($course->id), $USER->id, '', true) ? ' mycourse' : ' notmycourse';

        $objfitcls = theme_mb2nl_theme_setting($this->page, 'ccimgs') ? 'objfit ' : '';
         $classes = trim('coursebox clearfix position-relative '. $objfitcls . $additionalclasses . $infocls);

        // Category colors.
        $catcolors = theme_mb2nl_cat_color_itemattr($course);

        // ...coursebox
        $content .= html_writer::start_tag('div', [
            'class' => $classes,
            'data-courseid' => $course->id,
            'data-type' => self::COURSECAT_TYPE_COURSE,
            'style' => theme_mb2nl_cat_color_itemattr($course) . '--mb2-crandpct:' . rand(20, 80) . '%;',
        ]);

        // Collapsed course list.
        if (! $showinfobox) {
            $nametag = 'div';

            $content .= html_writer::start_tag('div', ['class' => 'info']);

            // Course name.
            $coursename = theme_mb2nl_is_mlang($chelper->get_course_formatted_name($course)) ?
                theme_mb2nl_format_txt($chelper->get_course_formatted_name($course)) : $chelper->get_course_formatted_name($course);

            if (theme_mb2nl_theme_setting($this->page, 'shortnamecourse')) {
                $coursename .= ' <span class="cshortname">' . $course->shortname . '</span>';
            }

            $coursenamelink = html_writer::link(new moodle_url('/course/view.php', ['id' => $course->id]),
            $coursename, ['class' => $course->visible ? '' : 'dimmed']);
            $content .= html_writer::tag($nametag, $coursenamelink, ['class' => 'coursename']);

            // If we display course in collapsed form but the course has summary or course contacts,
            // display the link to the info page.
            $content .= html_writer::start_tag('div', ['class' => 'moreinfo']);

            if ($chelper->get_show_courses() < self::COURSECAT_SHOW_COURSES_EXPANDED) {
                if ($course->has_summary() || $course->has_course_contacts() || $course->has_course_overviewfiles()) {
                    $url = new moodle_url('/course/info.php', ['id' => $course->id]);
                    $image = $this->output->pix_icon('i/info', $this->strings->summary);
                    $content .= html_writer::link($url, $image, ['title' => $this->strings->summary]);

                    // Make sure JS file to expand course content is included.
                    $this->coursecat_include_js();
                }
            }

            $content .= html_writer::end_tag('div'); // ...moreinfo

            // Print enrolmenticons.
            $content .= $this->theme_mb2nl_coursecat_coursebox_enroll_icons($course);
            $content .= html_writer::end_tag('div'); // ...info

        }

        $content .= html_writer::start_tag('div', ['class' => 'content']);
        $content .= $this->coursecat_coursebox_content($chelper, $course);
        $content .= html_writer::end_tag('div'); // ...content
        $content .= html_writer::end_tag('div'); // ...coursebox

        return $content;

    }




    /**
     *
     * Method to render enrol icons.
     *
     */
    protected function theme_mb2nl_coursecat_coursebox_enroll_icons($course) {
        $content = '';

        // Print enrolmenticons.
        if ($icons = enrol_get_course_info_icons($course)) {

            $content .= html_writer::start_tag('div', ['class' => 'enrolmenticons']);
            foreach ($icons as $pixicon) {
                $content .= $this->render($pixicon);
            }

            $content .= html_writer::end_tag('div'); // ...enrolmenticons
        }

        return $content;
    }




    /**
     *
     * Method to render content image.
     *
     */
    protected function theme_mb2nl_coursecat_coursebox_content_image($chelper, $url, $course) {
        $img = html_writer::empty_tag('img', ['src' => $url, 'alt' => $chelper->get_course_formatted_name($course)]);
        $output = html_writer::link(new moodle_url('/course/view.php', ['id' => $course->id]), $img);
        return $output;
    }




    /**
     *
     * Method to render course box content.
     *
     */
    protected function coursecat_coursebox_content(coursecat_helper $chelper, $course) {

        global $CFG, $USER;

        if ($chelper->get_show_courses() < self::COURSECAT_SHOW_COURSES_EXPANDED) {
            return;
        }

        if ($course instanceof stdClass) {
            $course = new core_course_list_element($course);
        }

        $content = '';
        $countfiles = count($course->get_course_overviewfiles());
        $plcimg = 1;
        $isfile = ($countfiles > 0 || $plcimg);
        $iscontacts = ($course->has_course_contacts() > 0);
        $iscategory = ($chelper->get_show_courses() == self::COURSECAT_SHOW_COURSES_EXPANDED_WITH_CAT);
        $iscoursecontent = true;

        $contentcls = ($isfile && $iscoursecontent) ? ' fileandcontent' : '';
        $content .= html_writer::start_tag('div', ['class' => 'content-inner' . $contentcls . theme_mb2nl_bsfcls(1)]);

        // ...display course overview files
        $contentimages = $contentfiles = '';

        if ($isfile) {

            $content .= html_writer::start_tag('div', ['class' => 'course-media']);

            foreach ($course->get_course_overviewfiles() as $file) {

                $isimage = $file->is_valid_image();

                $url = file_encode_url("$CFG->wwwroot/pluginfile.php",
                '/'. $file->get_contextid(). '/'. $file->get_component(). '/'.
                $file->get_filearea(). $file->get_filepath() . $file->get_filename(), !$isimage);

                if ($isimage) {
                    $contentimages .= $this->theme_mb2nl_coursecat_coursebox_content_image($chelper, $url, $course);
                } else {
                    $image = $this->output->pix_icon(file_file_icon($file, 24), $file->get_filename(), 'moodle');
                    $filename = html_writer::tag('span', $image, ['class' => 'fp-icon']) .
                    html_writer::tag('span', $file->get_filename(), ['class' => 'fp-filename']);
                    $contentfiles .= html_writer::tag('span', html_writer::link($url, $filename),
                    ['class' => 'coursefile fp-filename-icon']);
                }
            }

            // Course placeholder image.
            if ($contentimages === '' && $plcimg) {
                $courseplaceholder = theme_mb2nl_theme_setting($this->page, 'courseplaceholder', '', true);
                $isplcimg = $courseplaceholder ? $courseplaceholder : $this->output->image_url('course-default', 'theme');
                $contentimages .= $this->theme_mb2nl_coursecat_coursebox_content_image($chelper, $isplcimg, $course);
            }

            $content .= $contentimages . $contentfiles;

            $content .= html_writer::end_tag('div'); // ...course-media

        }

          // ...display course summary
        if ($iscoursecontent) {

            $content .= html_writer::start_tag('div', ['class' => 'course-content']);

            // Course heading.
            $coursename = theme_mb2nl_is_mlang($chelper->get_course_formatted_name($course)) ?
            theme_mb2nl_format_txt($chelper->get_course_formatted_name($course)) : $chelper->get_course_formatted_name($course);

            if (theme_mb2nl_theme_setting($this->page, 'shortnamecourse')) {
                $coursename .= ' <span class="cshortname">' . $course->shortname . '</span>';
            }

            $coursenamelink = html_writer::link(new moodle_url('/course/view.php', ['id' => $course->id]), $coursename);
            $content .= html_writer::start_tag('div', ['class' => 'course-heading']);
            $content .= html_writer::tag('h3', $coursenamelink, ['class' => 'coursename']);
            $coursedate = theme_mb2nl_theme_setting($this->page, 'cupdatedate');
            $iscdate = theme_mb2nl_course_date($course);
            $datelblstr = $iscdate > $course->startdate ? get_string('coursemodifieddate', 'theme_mb2nl') :
                get_string('coursestartdate', 'theme_mb2nl');

            if ($coursedate) {
                $content .= '<div class="coursedate"><span>' . $datelblstr . ':</span> ' . userdate($iscdate,
                get_string('strdatecourse', 'theme_mb2nl')) . '</div>';
            }

            if (theme_mb2nl_theme_setting($this->page, 'coursecustomfields')) {
                $content .= theme_mb2nl_course_fields($course->id);
            }

            $content .= $this->theme_mb2nl_coursecat_coursebox_enroll_icons($course);
            $content .= html_writer::end_tag('div');

            if ($course->has_summary()) {
                $content .= html_writer::start_tag('div', ['class' => 'summary']);
                $content .= $chelper->get_course_formatted_summary($course, ['overflowdiv' => true, 'noclean' => true, 'para' =>
                false]);
                $content .= html_writer::end_tag('div'); // ...summary
            }

            $context = context_course::instance($course->id);
            $students = get_role_users(theme_mb2nl_user_roleid(), $context);
            $studentscount = count($students);
            $isstudent = theme_mb2nl_is_user_role($course->id, theme_mb2nl_user_roleid(), $USER->id);

            if ($iscontacts || theme_mb2nl_theme_setting($this->page, 'coursestudentscount')) {

                $content .= html_writer::start_tag('ul', ['class' => 'teachers']);

                if ($iscontacts) {
                    foreach ($course->get_course_contacts() as $userid => $coursecontact) {
                        $name = '<strong>' .$coursecontact['rolename'].':</strong> ' .
                        html_writer::link(new moodle_url('/user/view.php', ['id' => $userid, 'course' => SITEID]),
                        $coursecontact['username']);
                        $content .= html_writer::tag('li', $name);
                    }
                }

                if (theme_mb2nl_theme_setting($this->page, 'coursestudentscount')) {

                    $studentscounttext = $studentscount ? $studentscount : get_string('nostudentsyet');

                    if ($isstudent) {
                        $studentscount = $studentscount - 1;
                        $studentscounttext = get_string('studentsandyou', 'theme_mb2nl', ['students' => $studentscount]);
                    }

                    $content .= html_writer::tag('li', '<strong>' . get_string('existingstudents') . ':</strong> ' .
                    $studentscounttext);
                }

                $content .= html_writer::end_tag('ul'); // ...teachers

            }

            // ...display course category if necessary (for example in search results)
            if ($iscategory) {

                $cat = core_course_category::get($course->category, IGNORE_MISSING);

                if ($cat) {
                    $content .= html_writer::start_tag('div', ['class' => 'coursecat']);

                    $content .= '<strong>' . get_string('category').':</strong> ' .
                    html_writer::link(new moodle_url('/course/index.php', ['categoryid' => $cat->id]),
                    $cat->get_formatted_name(), ['class' => $cat->visible ? '' : 'dimmed']);

                    $content .= html_writer::end_tag('div'); // ...coursecat
                }
            }

            // Red more button.
            if ($this->page->pagetype !== 'enrol-index') {
                $coursenamebtnlink = html_writer::link(new moodle_url('/course/view.php', ['id' => $course->id]),
                get_string('entercourse', 'theme_mb2nl'), ['class' => 'btn btn-primary']);
                $content .= html_writer::tag('div', $coursenamebtnlink, ['class' => 'course-readmore']);
            }

            $content .= html_writer::end_tag('div'); // ...course-content

        }

        $content .= html_writer::end_tag('div'); // ...content-inner

        return $content;

    }

}
