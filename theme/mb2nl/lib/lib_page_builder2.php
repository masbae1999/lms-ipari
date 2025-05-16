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
 * Method to get page builder elements actions
 *
 */
function theme_mb2nl_page_builder_el_actions($element='element', $elstr='', $opts=[]) {

    $output = '';

    $output .= '<div class="mb2-pb-actions">';
    $elstr0 = $elstr;
    $moodlestr = ['sitenews'];

    if ($elstr && $element === 'element') {
        $elstr = in_array($elstr, $moodlestr) ? get_string($elstr) : get_string($elstr, 'local_mb2builder');
    } else {
        $elstr = get_string($element, 'local_mb2builder');
    }

    $output .= $element !== 'subelement' ? '<span>' . $elstr . ':</span>' : '';

    $output .= '<span class="drag-handle ' . $element . '-drag-handle" title="' . get_string('move', 'local_mb2builder') . '">';
    $output .= '<i class="fa fa-arrows"></i>';
    $output .= '</span>';

    $output .= '<a href="#" class="settings-' . $element . '" title="' .get_string('settings', 'local_mb2builder')
    . '" data-modal="#mb2-pb-modal-settings-' . $element . '">';
    $output .= '<i class="fa fa-pencil"></i>';
    $output .= '</a>';

    if ($elstr0 === 'carousel' || $elstr0 === 'pbmainslider' || $elstr0 === 'testimonials') {
        $output .= '<a href="#" class="element-items" title="' . get_string('carouselitems', 'local_mb2builder') . '">';
        $output .= '<i class="fa fa-bars"></i>';
        $output .= '</a>';
    }

    if ($element === 'row') {
        $output .= '<a href="#" class="layout-row" title="' . get_string('columns', 'local_mb2builder')
        . '" data-modal="#mb2-pb-modal-row-layout">';
        $output .= '<i class="fa fa-columns"></i>';
        $output .= '</a>';
    }

    if (! isset($opts['copy']) || $opts['copy'] == 1) {
        $output .= '<a href="#" class="duplicate-' . $element . '" title="' . get_string('duplicate', 'local_mb2builder') . '">';
        $output .= '<i class="fa fa-clone"></i>';
        $output .= '</a>';
    }

    if ($element !== 'column') {
        $output .= '<a href="#" class="remove-' . $element . '" title="' . get_string('remove', 'local_mb2builder') . '">';
        $output .= '<i class="fa fa-trash"></i>';
        $output .= '</a>';
    }

    if ($element === 'column') {
        $output .= '<a href="#" class="mb2-pb-add-element" data-modal="#mb2-pb-modal-elements" title="'
        . get_string('addelement', 'local_mb2builder') . '">';
        $output .= '<i class="fa fa-plus"></i>';
        $output .= '</a>';
    }

    if ($element === 'section' || $element === 'row') {
        $output .= '<span class="visible-off" title="' . get_string('hidden', 'local_mb2builder') . '">';
        $output .= '<i class="fa fa-eye-slash"></i>';
        $output .= '</span>';

        $output .= '<span class="access-lock" title="' . get_string('elaccessusers', 'local_mb2builder') . '">';
        $output .= '<i class="fa fa-lock"></i>';
        $output .= '</span>';

        $output .= '<span class="access-unlock" title="' . get_string('elaccesguests', 'local_mb2builder') . '">';
        $output .= '<i class="fa fa-unlock"></i>';
        $output .= '</span>';

        $output .= '<span class="languages">';

        if ($opts['lang'][0]) {
            $output .= implode(',', $opts['lang']);
        }

        $output .= '</span>';
    }

    $output .= '</div>';

    if ($elstr0 === 'courses' || $elstr0 === 'categories' || $elstr0 === 'coursetabs' || $elstr0 === 'blog' ||
    $elstr0 === 'events' || $elstr0 === 'sitenews') {
        $output .= '<div class="mb2-pb-actions-loading"></div>';
    }

    return $output;

}







/**
 *
 * Method to get data attributes of page builder elements
 *
 */
function theme_mb2nl_page_builder_el_datatts($atts, $atts2=[]) {
    global $CFG;
    $output = '';

    if (!theme_mb2nl_check_builder()) {
        return;
    }

    // Get page api file.
    require_once($CFG->dirroot . '/local/mb2builder/classes/builder.php');

    if (isset($atts2['id'])) {
        $atts2['elname'] = get_string($atts2['id'], 'local_mb2builder');
    }

    foreach ($atts2 as $k => $v) {
        if (isset($atts[$k])) {
            $v = $atts[$k];
        }

        // We have to replace shortcodes.
        if (strpos($v, ']')) {
            $v = mb2builderBuilder::replace_shortcode($v);
        }

        if ($k === 'content' || $k === 'text') {
            // Params comes encoded from page builder.
            // So we need to decode it.
            $v = urldecode($v);

            // In data attribute we need html entities.
            // But we need to prevent to etities twice.
            // So, first we need to remove entities with 'html_entity_decode'.
            // And then convert to entities width 'htmlentities'.
            $v = html_entity_decode($v);
            $v = htmlentities($v);
        }

        $output .= ' data-' . $k . '="' . $v . '"';
    }

    return $output;

}






/**
 *
 * Method to get variables from two arrays
 *
 */
function theme_mb2nl_page_builder_2arrays($atts, $atts2=[]) {

    $attributes = [];

    foreach ($atts2 as $k => $v) {
        $v = $v;

        if (isset($atts[$k])) {
            $v = $atts[$k];
        }

        $attributes[$k] = $v;
    }

    return $attributes;

}




/**
 *
 * Method to get demo image from page builder
 *
 */
function theme_mb2nl_dummy_image($size='1600x1066', $color='e1e5ee/767b91', $ext='jpg') {

    return get_string('demoimage', 'theme_mb2nl', [
        'size' => $size,
        'color' => $color,
        'ext' => $ext,
    ]);

}




/**
 *
 * Method to get shortcode content for attribute [... content="..."
 *
 */
function theme_mb2nl_page_builder_shortcode_content_attr($content, $pattern) {

    $output = '';

    $matches = [];
    preg_match("/$pattern/s", $content, $matches);

    if (isset($matches[5])) {
        if (strip_tags($matches[5]) !== $matches[5]) {
            return htmlentities($matches[5]);
        } else {
            return $matches[5];
        }
    }

}






/**
 *
 * Method to get link to edit page
 *
 */
function theme_mb2nl_builder_pagelink() {
    global $PAGE, $COURSE;

    $output = '';
    $link = '';
    $pageid = optional_param('id', 0, PARAM_INT);

    if (($PAGE->pagetype !== 'site-index' && $PAGE->pagetype !== 'mod-page-view') ||
    $PAGE->user_is_editing() || !has_capability('local/mb2builder:managepages', context_system::instance())) {
        return [];
    }

    $linkparams = [
        'itemid' => theme_mb2nl_builder_pageid(),
        'courseid' => $COURSE->id,
        'mpage' => $PAGE->pagetype === 'site-index' ? -1 : $pageid,
        'returnurl' => $PAGE->url->out_as_local_url(),
        'pagename' => urlencode($PAGE->title),
        'pageid' => uniqid('page_'),
    ];

    return $linkparams;

}




/**
 *
 * Method to check if moodle page has builder page
 *
 * @return bool
 */
function theme_mb2nl_builder_has_page() {
    global $CFG, $PAGE;

    if (!theme_mb2nl_check_builder() || $PAGE->user_is_editing()) {
        return 0;
    }

    if (!class_exists('Mb2builderPagesApi')) {
        // Get page api file.
        require($CFG->dirroot . '/local/mb2builder/classes/pages_api.php');
    }

    return Mb2builderPagesApi::has_builderpage();

}




/**
 *
 * Method to check if moodle page has builder page
 *
 * @return INT|NULL
 */
function theme_mb2nl_builderpage_heading() {

    global $DB;

    if (!theme_mb2nl_check_builder()) {
        return;
    }

    if (!$mpageid = theme_mb2nl_builder_mpageid()) {
        return;
    }

    // Get cache.
    $cache = cache::make('local_mb2builder', 'pagedata');
    $cacheid = $mpageid == -1 ? 'fp' : $mpageid;

    if ($cache->get($cacheid)) {
        return $cache->get($cacheid)->heading;
    }

    if (!$pageid = theme_mb2nl_builder_pageid()) {
        return 0;
    }

    $recordsql = 'SELECT heading FROM {local_mb2builder_pages} WHERE id=' . $pageid;

    if ($DB->record_exists_sql($recordsql)) {
        return $DB->get_record_sql($recordsql)->heading;
    }

    return 0;

}





/**
 *
 * Method to get page ID
 *
 * @return INT
 */
function theme_mb2nl_builder_mpageid() {
    global $PAGE;

    if ($PAGE->pagetype === 'site-index' && $PAGE->pagelayout === 'frontpage') {
        return -1;
    } else if ($PAGE->pagetype === 'mod-page-view') {
        return optional_param('id', 0, PARAM_INT);
    }

    return 0;
}








/**
 *
 * Method to get page ID
 *
 */
function theme_mb2nl_builder_pageid() {

    global $CFG;

    if (!theme_mb2nl_check_builder()) {
        return 0;
    }

    if (!class_exists('Mb2builderPagesApi')) {
        // Get page api file.
        require($CFG->dirroot . '/local/mb2builder/classes/pages_api.php');
    }

    return Mb2builderPagesApi::get_page_id();

}



/**
 *
 * Method to get header builder style
 *
 */
function theme_mb2nl_builder_header($itemid=0) {

    global $DB;

    if (!theme_mb2nl_check_builder()) {
        return;
    }

    $mpageid = theme_mb2nl_builder_mpageid();

    if (!$itemid && !$mpageid) {
        return;
    }

    // Get cache.
    $cache = cache::make('local_mb2builder', 'pagedata');
    $cacheid = $mpageid == -1 ? 'fp' : $mpageid;

    // The 'itemid' is set only whe user edit a page in the page builder.
    // This is require for display the header placeholder.
    if ($cache->get($cacheid)) {
        return $cache->get($cacheid)->headerstyle;
    }

    $pageid = $itemid ? $itemid : theme_mb2nl_builder_pageid();

    if (!$pageid) {
        return 0;
    }

    $recordsql = 'SELECT headerstyle FROM {local_mb2builder_pages} WHERE id= ' . $pageid;

    if ($DB->record_exists_sql($recordsql)) {
        return $DB->get_record_sql($recordsql)->headerstyle;
    }

    return 0;

}





/**
 *
 * Method to get header builder style
 *
 */
function theme_mb2nl_builder_menu() {
    global $DB;

    if (!theme_mb2nl_check_builder()) {
        return;
    }

    if (!$mpageid = theme_mb2nl_builder_mpageid()) {
        return;
    }

    // Get cache.
    $cache = cache::make('local_mb2builder', 'pagedata');
    $cacheid = $mpageid == -1 ? 'fp' : $mpageid;

    if ($cache->get($cacheid)) {
        return $cache->get($cacheid)->menu;
    }

    if (!$pageid = theme_mb2nl_builder_pageid()) {
        return 0;
    }

    $recordsql = 'SELECT menu FROM {local_mb2builder_pages} WHERE id= ' . $pageid;

    if ($DB->record_exists_sql($recordsql)) {
        return $DB->get_record_sql($recordsql)->menu;
    }

    return 0;

}






/**
 *
 * Method to get header builder style
 *
 */
function theme_mb2nl_builder_tgsdb() {

    global $DB;

    if (!theme_mb2nl_check_builder()) {
        return;
    }

    if (!$mpageid = theme_mb2nl_builder_mpageid()) {
        return;
    }

    // Get cache.
    $cache = cache::make('local_mb2builder', 'pagedata');
    $cacheid = $mpageid == -1 ? 'fp' : $mpageid;

    if ($cache->get($cacheid)) {
        return $cache->get($cacheid)->tgsdb;
    }

    if (!$pageid = theme_mb2nl_builder_pageid()) {
        return 0;
    }

    $recordsql = 'SELECT tgsdb FROM {local_mb2builder_pages} WHERE id = ' . $pageid;

    if ($DB->record_exists_sql($recordsql)) {
        return $DB->get_record_sql($recordsql)->tgsdb;
    }

    return 0;

}





/**
 *
 * Method to get header builder style
 *
 */
function theme_mb2nl_builder_css() {

    global $DB, $PAGE;

    if (!theme_mb2nl_check_builder()) {
        return;
    }

    if (!$mpageid = theme_mb2nl_builder_mpageid()) {
        return;
    }

    // Get cache.
    $cache = cache::make('local_mb2builder', 'pagedata');
    $cacheid = $mpageid == -1 ? 'fp' : $mpageid;

    if ($cache->get($cacheid)) {
        return $cache->get($cacheid)->pagecss;
    }

    $pageid = theme_mb2nl_builder_pageid();

    // Page ID from page builder editor.
    if (preg_match('@local-mb2builder-customize@', $PAGE->pagetype)) {
        $pageid = optional_param('itemid', 0, PARAM_INT);
    }

    if (!$pageid) {
        return 0;
    }

    $recordsql = 'SELECT pagecss FROM {local_mb2builder_pages} WHERE id = ' . $pageid;

    if ($DB->record_exists_sql($recordsql)) {
        return $DB->get_record_sql($recordsql)->pagecss;
    }

    return 0;

}




/**
 *
 * Method to get header builder style
 *
 */
function theme_mb2nl_builder_style() {

    global $PAGE;

    $output = '';
    $enrolpage = theme_mb2nl_is_cenrol_page();
    $css = theme_mb2nl_builder_css();

    if ($enrolpage) {
        $css = theme_mb2nl_mb2fields_filed('mb2css');
    }

    if (!$css && !preg_match('@local-mb2builder-customize@', $PAGE->pagetype)) {
        return;
    }

    $output .= '<style id="css-mb2-page-builder">';
    $output .= $css;
    $output .= '</style>';

    return $output;

}



/**
 *
 * Method to get header builder style
 *
 */
function theme_mb2nl_builder_logo($l='') {
    global $CFG;

    require_once($CFG->libdir . '/filelib.php');

    $pageid = theme_mb2nl_builder_pageid();

    if (!$pageid) {
        return;
    }

    $context = context_system::instance();
    $fs = get_file_storage();
    $files = $fs->get_area_files($context->id, 'local_mb2builder', 'images');

    foreach ($files as $f) {
        if (!$f->is_valid_image()) {
            continue;
        }

        // Get image xetsnions name.
        $imgarr = explode('.', $f->get_filename());

        // ...pblogo_logo-light_ID.svg.
        if ($f->get_filename() !== 'pblogo_' . $l . '_' . $pageid . '.' . trim(end($imgarr))) {
            continue;
        }

        return moodle_url::make_pluginfile_url($f->get_contextid(), $f->get_component(), $f->get_filearea(), null,
        $f->get_filepath(), $f->get_filename());
    }

    return false;

}
