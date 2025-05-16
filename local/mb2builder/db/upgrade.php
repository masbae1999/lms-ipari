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
 * @package    local_mb2builder
 * @copyright  2018 - 2024 Mariusz Boloz (lmsstyle.com)
 * @license    PHP and HTML: http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later. Other parts: http://themeforest.net/licenses
 */

defined( 'MOODLE_INTERNAL' ) || die();

function xmldb_local_mb2builder_upgrade($oldversion) {
    global $DB, $SITE;
    $dbman = $DB->get_manager();
    $pagedata = new stdClass();

    require_once( __DIR__ . '/../lib.php' );
    require_once( __DIR__ . '/../classes/pages_api.php' );

    if ($oldversion < 2020090916 )
    {
        $table_pages = new xmldb_table( 'local_mb2builder_pages' );
        $table_pages->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
        $table_pages->add_field('pageid', XMLDB_TYPE_TEXT, null, null, null, null);
        $table_pages->add_field('timecreated', XMLDB_TYPE_INTEGER, '10', null, null, null, '0');
        $table_pages->add_field('timemodified', XMLDB_TYPE_INTEGER, '10', null, null, null, '0');
        $table_pages->add_field('title', XMLDB_TYPE_TEXT, null, null, null, null);
        $table_pages->add_field('content', XMLDB_TYPE_TEXT, null, null, null, null);
        $table_pages->add_field('democontent', XMLDB_TYPE_TEXT, null, null, null, null);
        $table_pages->add_field('createdby', XMLDB_TYPE_INTEGER, '10', null, null, null, '0');
        $table_pages->add_field('modifiedby', XMLDB_TYPE_INTEGER, '10', null, null, null, '0');
        $table_pages->add_key('primary', XMLDB_KEY_PRIMARY, array('id') );

        if (!$dbman->table_exists($table_pages)) {
            $dbman->create_table($table_pages);
        }

        $table_layouts = new xmldb_table( 'local_mb2builder_layouts' );
        $table_layouts->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
        $table_layouts->add_field('timecreated', XMLDB_TYPE_INTEGER, '10', null, null, null, '0');
        $table_layouts->add_field('timemodified', XMLDB_TYPE_INTEGER, '10', null, null, null, '0');
        $table_layouts->add_field('name', XMLDB_TYPE_TEXT, null, null, null, null);
        $table_layouts->add_field('content', XMLDB_TYPE_TEXT, null, null, null, null);
        $table_layouts->add_field('createdby', XMLDB_TYPE_INTEGER, '10', null, null, null, '0');
        $table_layouts->add_field('modifiedby', XMLDB_TYPE_INTEGER, '10', null, null, null, '0');
        $table_layouts->add_key('primary', XMLDB_KEY_PRIMARY, array('id') );

        if (!$dbman->table_exists($table_layouts)) {
            $dbman->create_table($table_layouts);
        }

        // Now we have to check if user has page built with the old page builder
        // If yes we have to:
        // 1. Add new page
        // 2. Set the 'mpage' filed to -1
        $oldpage = isset(get_config('local_mb2builder')->builderfptext) ? get_config('local_mb2builder')->builderfptext : '';

        if ($oldpage) {
            // Set content and demo content for the new page
            $pagedata->content = $oldpage;
            $pagedata->democontent = $oldpage;
            $pagedata->title = get_string('sitehome');
            $pagedata->mpage = -1;

            // Add new page
            Mb2builderPagesApi::add_record($pagedata);
        }

    }

    if ($oldversion < 2021033025 )
    {
        $table_pages = new xmldb_table( 'local_mb2builder_pages' );
        $headingfield = new xmldb_field( 'heading', XMLDB_TYPE_INTEGER, '10', null, null, null, '0' );

        if (! $dbman->field_exists( $table_pages, $headingfield )) {
            $dbman->add_field( $table_pages, $headingfield );
        }

        upgrade_plugin_savepoint( true, 2021033025, 'local' , 'mb2builder' );
    }

    if ($oldversion < 2022060616 )
    {
        $table_pages = new xmldb_table( 'local_mb2builder_pages' );
        $footerfield = new xmldb_field( 'footer', XMLDB_TYPE_INTEGER, '10', null, null, null, '0' );

        if (! $dbman->field_exists( $table_pages, $footerfield )) {
            $dbman->add_field( $table_pages, $footerfield );
        }

        upgrade_plugin_savepoint( true, 2022060616, 'local' , 'mb2builder' );
    }

    // Add footer table
    if ($oldversion < 2022060616 )
    {
        $table_footers = new xmldb_table( 'local_mb2builder_footers' );
        $table_footers->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
        $table_footers->add_field('footerid', XMLDB_TYPE_TEXT, null, null, null, null);
        $table_footers->add_field('timecreated', XMLDB_TYPE_INTEGER, '10', null, null, null, '0');
        $table_footers->add_field('timemodified', XMLDB_TYPE_INTEGER, '10', null, null, null, '0');
        $table_footers->add_field('name', XMLDB_TYPE_TEXT, null, null, null, null);
        $table_footers->add_field('content', XMLDB_TYPE_TEXT, null, null, null, null);
        $table_footers->add_field('democontent', XMLDB_TYPE_TEXT, null, null, null, null);
        $table_footers->add_field('createdby', XMLDB_TYPE_INTEGER, '10', null, null, null, '0');
        $table_footers->add_field('modifiedby', XMLDB_TYPE_INTEGER, '10', null, null, null, '0');
        $table_footers->add_key('primary', XMLDB_KEY_PRIMARY, array('id') );

        if (! $dbman->table_exists( $table_footers )) {
            $dbman->create_table( $table_footers );
        }
    }


    // Add header style field
    if ($oldversion < 2022111412 )
    {
        $table_pages = new xmldb_table( 'local_mb2builder_pages' );
        $headerstylefield = new xmldb_field( 'headerstyle', XMLDB_TYPE_TEXT, null, null, null, null );

        if (! $dbman->field_exists( $table_pages, $headerstylefield )) {
            $dbman->add_field( $table_pages, $headerstylefield );
        }

        upgrade_plugin_savepoint( true, 2022111412, 'local' , 'mb2builder' );
    }

    // Add page style
    if ($oldversion < 2022121521 )
    {
        $table_pages = new xmldb_table( 'local_mb2builder_pages' );
        $pagecssfield = new xmldb_field( 'pagecss', XMLDB_TYPE_TEXT, null, null, null, null );

        if (! $dbman->field_exists( $table_pages, $pagecssfield )) {
            $dbman->add_field( $table_pages, $pagecssfield );
        }

        upgrade_plugin_savepoint( true, 2022121521, 'local' , 'mb2builder' );
    }

    // Add menu
    if ($oldversion < 2023032919 )
    {
        $table_pages = new xmldb_table( 'local_mb2builder_pages' );
        $menufield = new xmldb_field( 'menu', XMLDB_TYPE_INTEGER, '10', null, null, null, '0' );

        if (! $dbman->field_exists( $table_pages, $menufield )) {
            $dbman->add_field( $table_pages, $menufield );
        }

        upgrade_plugin_savepoint( true, 2023032919, 'local' , 'mb2builder' );
    }   

    // Add toggle sidebar
    if ($oldversion < 2024012514 )
    {
        $table_pages = new xmldb_table( 'local_mb2builder_pages' );
        $tgsdbfield = new xmldb_field( 'tgsdb', XMLDB_TYPE_INTEGER, '10', null, null, null, '0' );

        if (! $dbman->field_exists( $table_pages, $tgsdbfield )) {
            $dbman->add_field( $table_pages, $tgsdbfield );
        }

        upgrade_plugin_savepoint( true, 2024012514, 'local' , 'mb2builder' );
    }


    // Add page ID
    if ($oldversion < 2024090412 )
    {
        // Add mpage
        $table = new xmldb_table('local_mb2builder_pages');
        $field = new xmldb_field('mpage', XMLDB_TYPE_INTEGER, '10', null, null, null, '0');

        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // Set mpage value for existing pages
        $pages = Mb2builderPagesApi::get_list_records();

        // Get page module ID
        $pagemodsql = 'SELECT id FROM {modules} WHERE name=:name';
        $pagemodid = $DB->get_record_sql($pagemodsql,array('name'=>'page'))->id;

        foreach ($pages as $page) {
            // Get page to update
            $itemtoupdate = Mb2builderPagesApi::get_record($page->id);
            $itemtoupdate->mpage = 0;

            // Define search query
            $search = '%[mb2page pageid="'.  $page->id .'"%';
            $params = array('course'=>$SITE->id,'summary'=>$search);

            // Search query for the front page
            $fpsql = 'SELECT id FROM {course_sections} WHERE course=:course AND ' . $DB->sql_like('summary',':summary');

            // Search for the page modules
            $psql = 'SELECT id FROM {page} WHERE ' . $DB->sql_like('content',':summary');

            if ($DB->record_exists_sql($fpsql,$params)) {
                $itemtoupdate->mpage = -1; // -1 = front page
            }
            // Search page modules
            else if ($DB->record_exists_sql($psql,$params)) {
                // Maybe user insert the same shortcode to more than one Moodle pages
                // So We have to get the first Moodle page
                $mpages = $DB->get_records_sql($psql,$params);
                $mpage = array_shift($mpages);

                // Get the URL page ID
                $urlidsql = 'SELECT id FROM {course_modules} WHERE module=' . $pagemodid . ' AND instance=' . $mpage->id;

                if ($DB->record_exists_sql($urlidsql)) {
                    $itemtoupdate->mpage = $DB->get_record_sql($urlidsql)->id; // page module ID from URL: ?id=...
                }               
            }

            Mb2builderPagesApi::update_record_data($itemtoupdate);
        }

        upgrade_plugin_savepoint(true, 2024090412, 'local' , 'mb2builder');
    }

    return true;
}
