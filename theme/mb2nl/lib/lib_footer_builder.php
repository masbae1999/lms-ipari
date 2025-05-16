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
 * Method to check footer type
 *
 */
function theme_mb2nl_footerid() {
    global $PAGE;

    if (theme_mb2nl_is_login(true)) {
        return false;
    }

    $themefooter = theme_mb2nl_theme_setting($PAGE, 'footer');
    $builderfooter = theme_mb2nl_builderpage_footer();

    if ($builderfooter) {
        return $builderfooter;
    } else if ($themefooter) {
        return $themefooter;
    }

    return false;

}






/**
 *
 * Method to check if builder page has custom footer
 *
 */
function theme_mb2nl_builderpage_footer() {

    global $PAGE, $DB, $CFG;

    $content = '';

    $pageid = theme_mb2nl_builder_pageid();

    if (!$pageid) {
        return 0;
    }

    // Get footer databse.
    // Check for old vearion of page builder if 'footer' filed exist.
    $dbman = $DB->get_manager();
    $tablepages = new xmldb_table('local_mb2builder_pages');
    $footerfield = new xmldb_field('footer', XMLDB_TYPE_INTEGER, '10', null, null, null, '0');

    if (!$dbman->field_exists($tablepages, $footerfield)) {
        return 0;
    }

    // Get cache.
    $cache = cache::make('local_mb2builder', 'pagedata');

    if ($cache->get($pageid)) {
        return $cache->get($pageid)->footer;
    }

    $recordsql = 'SELECT footer FROM {local_mb2builder_pages} WHERE id=' . $pageid;

    if ($DB->record_exists_sql($recordsql)) {
        return $DB->get_record_sql($recordsql)->footer;
    }

    return 0;

}
