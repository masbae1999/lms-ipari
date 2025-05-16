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

namespace format_topics\output\courseformat;

use core_courseformat\output\local\content as content_base;
use renderer_base;

/**
 * Base class to render a course content.
 *
 */
class content extends content_base {


    /**
     * Export this data so it can be used as the context for a mustache template (core/inplace_editable).
     *
     * @param renderer_base $output typically, the renderer that's calling this function
     * @return stdClass data context for a mustache template
     */
    public function export_for_template(renderer_base $output) {
        global $PAGE, $CFG, $OUTPUT;

        $data = new \stdClass();
        $clayout = theme_mb2nl_course_layout();
        $data->tablist = theme_mb2nl_course_tabs('hor');
        $data->course_sections = $OUTPUT->theme_part('course_sections', ['layout' => $clayout]);

        if ($clayout) {
            return $data;
        }

        return parent::export_for_template($output);
    }



    /**
     * Returns the output class template path.
     *
     * This method redirects the default template when the course content is rendered.
     */
    public function get_template_name(\renderer_base $renderer): string {

        if (theme_mb2nl_course_layout()) {
            return 'format_topics/local/custom_content';
        }

        return 'core_courseformat/local/content';

    }

}
