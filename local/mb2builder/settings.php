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

defined('MOODLE_INTERNAL') || die();

global $CFG, $PAGE;

require_once( __DIR__ . '/lib.php' );

$context = context_system::instance();

$managepages = has_capability('local/mb2builder:managepages', $context);
$managelayouts = has_capability('local/mb2builder:managelayouts', $context);
$managefooters = has_capability('local/mb2builder:managefooters', $context);

if ($managepages || $managelayouts || $managefooters) {

    $ADMIN->add( 'root', new admin_category( 'local_mb2builder', get_string('pluginname', 'local_mb2builder', null, true) ) );

    if ($managepages) {
        $page = new admin_externalpage( 'local_mb2builder_managepages', get_string('managepages', 'local_mb2builder'),
        new moodle_url( '/local/mb2builder/index.php' ), 'local/mb2builder:managepages' );
        $ADMIN->add('local_mb2builder', $page);
    }

    if ($managelayouts) {
        $page = new admin_externalpage( 'local_mb2builder_managelayouts', get_string( 'layoutscustom', 'local_mb2builder' ),
        new moodle_url( '/local/mb2builder/layouts.php' ), 'local/mb2builder:managelayouts' );
        $ADMIN->add('local_mb2builder', $page);
    }

    if ($managefooters) {
        $page = new admin_externalpage( 'local_mb2builder_managefooters', get_string('managefooters', 'local_mb2builder'),
        new moodle_url( '/local/mb2builder/footers.php' ), 'local/mb2builder:managefooters' );
        $ADMIN->add('local_mb2builder', $page);
    }

    $page = new admin_settingpage('local_mb2builder_images', get_string('images', 'local_mb2builder', null, true));
    $page->add (new admin_setting_configstoredfile('local_mb2builder/images','','','images', 0, ['maxfiles' => -1,
    'subdirs' => 0, 'accepted_types' => [
        '.jpg',
        '.png',
        '.gif',
        // Video.
        '.webm',
        '.mpg',
        '.mp2',
        '.mpeg',
        '.mpe',
        '.mpv',
        '.mp4',
        '.m4p',
        '.m4v',
        '.avi',
        '.mov',
    ]]));

    $ADMIN->add('local_mb2builder', $page);

    $page = new admin_settingpage( 'local_mb2builder_options', get_string('options', 'local_mb2builder', null, true) );

    $name = 'local_mb2builder/theme';
    $title = get_string('theme','local_mb2builder');
    $setting = new admin_setting_configtext($name, $title, '', '');
    $page->add( $setting );

    $ADMIN->add('local_mb2builder', $page);

}
