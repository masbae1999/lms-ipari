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
 * @package    local_mb2coursenotes
 * @copyright  2019 - 2024 Mariusz Boloz (lmsstyle.com)
 * @license    PHP and HTML: http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later. Other parts: http://themeforest.net/licenses
 */

defined('MOODLE_INTERNAL') || die();

require_once( __DIR__ . '/lib.php' );

if ( $hassiteconfig ) {

    $ADMIN->add('localplugins', new admin_category('local_mb2coursenotes',
    get_string('pluginname', 'local_mb2coursenotes', null, true)));

    $page = new admin_settingpage('local_mb2coursenotes_options', get_string('options', 'local_mb2coursenotes', null, true));

    $name = 'local_mb2coursenotes/disablenotes';
    $title = get_string('disablenotes','local_mb2coursenotes');
    $setting = new admin_setting_configcheckbox($name, $title, '', 0);
    $page->add($setting);

    $ADMIN->add('local_mb2coursenotes', $page);

}
