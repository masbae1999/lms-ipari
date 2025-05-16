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
 * @package    local_mb2reviews
 * @copyright  2021 Mariusz Boloz (mb2themes.com)
 * @license    PHP and HTML: http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later. Other parts: http://themeforest.net/licenses
 */

defined('MOODLE_INTERNAL') || die();

require_once( __DIR__ . '/lib.php' );
require_once( __DIR__ . '/classes/helper.php' );

if ( $hassiteconfig && has_capability( 'local/mb2reviews:manageitems', context_system::instance() ) )
{

	$ADMIN->add('root', new admin_category('local_mb2reviews', get_string('pluginname', 'local_mb2reviews', null, true)));
    $page = new admin_externalpage('local_mb2reviews_managereviews', get_string('managereviews', 'local_mb2reviews'), new moodle_url('/local/mb2reviews/index.php'));
    $ADMIN->add('local_mb2reviews', $page);

	$page = new admin_settingpage('local_mb2reviews_options', get_string('options', 'local_mb2reviews', null, true));

	$name = 'local_mb2reviews/disablereview';
	$title = get_string('disablereview','local_mb2reviews');
	$setting = new admin_setting_configcheckbox($name, $title, '', 0);
	$page->add($setting);

	$name = 'local_mb2reviews/blockonmod';
	$title = get_string('blockonmod','local_mb2reviews');
	$setting = new admin_setting_configcheckbox($name, $title, '', 0);
	$page->add($setting);

	$name = 'local_mb2reviews/minrated';
	$title = get_string('minrated','local_mb2reviews');
	$setting = new admin_setting_configtext($name, $title, '', 3);
	$page->add($setting);

	$name = 'local_mb2reviews/rolestudent';
	$title = get_string('rolestudent','local_mb2reviews');
	$setting = new admin_setting_configtext( $name, $title, '', 'student' );
	$page->add($setting);

	$name = 'local_mb2reviews/roleteacher';
	$title = get_string('roleteacher','local_mb2reviews');
	$setting = new admin_setting_configtext( $name, $title, '', 'editingteacher' );
	$page->add($setting);

	$name = 'local_mb2reviews/caneditreview';
	$title = get_string('caneditreview','local_mb2reviews');
	$setting = new admin_setting_configcheckbox($name, $title, '', 1);
	$page->add($setting);

	$name = 'local_mb2reviews/autoapprove';
	$title = get_string('autoapprove','local_mb2reviews');
	$setting = new admin_setting_configcheckbox($name, $title, '', 1);
	$page->add($setting);

	$name = 'local_mb2reviews/perpage';
	$title = get_string('perpage','local_mb2reviews');
	$setting = new admin_setting_configtext($name, $title, '', 12);
	$page->add($setting);

	$name = 'local_mb2reviews/reviewusername';
	$title = get_string('reviewusername','local_mb2reviews');
	$setting = new admin_setting_configselect( $name, $title, '', 2, array( 1=>get_string('username'), 2=>get_string('firstname'), 3=>get_string('fullname') ) );
	$page->add($setting);

	// $name = 'local_mb2reviews/textcolor';
	// $title = get_string('textcolor','local_mb2reviews');
	// $setting = new admin_setting_configmb2color($name, $title, '', '');
	// $page->add($setting);

	$ADMIN->add('local_mb2reviews', $page);
}
