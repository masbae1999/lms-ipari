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
 * @copyright 2019 - 2020 Mariusz Boloz (lmsstyle.com)
 * @license   PHP and HTML: http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later. Other parts: http://themeforest.net/licenses
 *
 */



/**
 *
 * Method to display slider items
 *
 */
function theme_mb2nl_is_notice_plugin() {

    global $CFG, $DB;

    $dbman = $DB->get_manager();
    $table = new xmldb_table('local_mb2notices_items');

    if (is_file($CFG->dirroot . '/local/mb2notices/index.php') && $dbman->table_exists($table)) {
        return true;
    }

    return false;

}



/**
 *
 * Method to display slider items
 *
 */
function theme_mb2nl_notice($position = 'content') {
    global $CFG, $PAGE, $COURSE;

    $output = '';

    // Cehck if local slides plugi is installed.
    if (!theme_mb2nl_is_notice_plugin()) {
            return;
    }

    // Get slides api.
    if (!class_exists('Mb2noticesApi')) {
        require_once($CFG->dirroot . '/local/mb2notices/classes/api.php');
    }

    // Get slides.
    $items = Mb2noticesApi::get_sortorder_items();

    $output .= '<div class="mb2notices">';

    foreach ($items as $itemid) {
        $output .= theme_mb2nl_notice_item($itemid, $position);
    }

    $output .= '</div>'; // ...mb2notices

    return $output;

}



/**
 *
 * Method to display notice item
 *
 */
function theme_mb2nl_notice_item($itemid, $position) {
    global $CFG, $PAGE;

    // Get slider api.
    if (! class_exists('Mb2noticesApi')) {
        require_once($CFG->dirroot . '/local/mb2notices/classes/api.php');
    }

    if (! class_exists('Mb2noticesHelper')) {
        require_once($CFG->dirroot . '/local/mb2notices/classes/helper.php');
    }

    $output = '';
    $cls = '';
    $noticestyle = '';
    $theader = theme_mb2nl_headerstyle();
    $item = Mb2noticesApi::get_record($itemid);
    $opt = get_config('local_mb2notices');
    $attribs = json_decode($item->attribs);
    $canmanage = has_capability('local/mb2notices:manageitems', context_system::instance());
    $linkedit = new moodle_url('/local/mb2notices/edit.php',
    ['itemid' => $item->id, 'returnurl' => $PAGE->url->out_as_local_url()]);
    $canclose = Mb2noticesHelper::get_param($itemid, 'canclose');
    $noticepos = Mb2noticesHelper::get_param($itemid, 'position');

    // Move content notice to top in transparent header.
    if (preg_match('@transparent@', $theader)) {
        $noticepos = 'top';
    }

    if (! Mb2noticesHelper::can_see($item)) {
        return;
    }

    if ($position !== $noticepos) {
        return;
    }

    $cls .= ' mb2notices-item-' . $item->id;
    $cls .= ' type-' . Mb2noticesHelper::get_param($itemid, 'noticetype');
    $cls .= $canclose ? ' canclose' : ' cantclose';

    // Define custom styles.
    $textcolor = Mb2noticesHelper::get_param($itemid, 'textcolor');
    $bgcolor = Mb2noticesHelper::get_param($itemid, 'bgcolor');

    if ($textcolor || $bgcolor) {
        $noticestyle .= ' style="';
        $noticestyle .= $textcolor ? 'color:' . $textcolor .';' : '';
        $noticestyle .= $bgcolor ? 'background-color:' . $bgcolor .';' : '';
        $noticestyle .= '"';
    }

    $output .= '<div class="mb2notices-item' . $cls . '" data-itemid="' . $item->id . '" data-cookieexpiry="' .
    $opt->cookieexpiry . '"' . $noticestyle . '>';
    $output .= '<div class="mb2notices-content">';
    $output .= $canclose ? '<a href="#" class="mb2notices-item-close">&#10005;</a>' : '';
    $output .= $canmanage ? '<a class="mb2notices-action-edit" href="' . $linkedit . '"><i class="fa fa-pencil"></i></a>' : '';
    $output .= Mb2noticesHelper::get_param($itemid, 'showtitle') ? '<h4 class="mb2notices-title">' . $item->title . '</h4>' : '';
    $output .= Mb2noticesHelper::get_item_content($item);
    $output .= '</div>';
    $output .= '</div>';

    return $output;

}
