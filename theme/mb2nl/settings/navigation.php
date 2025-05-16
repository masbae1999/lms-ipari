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

$temp = new admin_settingpage('theme_mb2nl_settingsnav',  get_string('settingsnav', 'theme_mb2nl'));

$setting = new admin_setting_configmb2start2('theme_mb2nl/startnavgeneral', get_string('mainmenu', 'theme_mb2nl'));
$temp->add($setting);

$name = 'theme_mb2nl/headernav';
$title = get_string('headernav', 'theme_mb2nl');
$setting = new admin_setting_configselect($name, $title, '', 1, [
    0 => get_string('posnavbar', 'theme_mb2nl'),
    1 => get_string('posheader', 'theme_mb2nl'),
    2 => get_string('posheader2', 'theme_mb2nl'),
]);
$temp->add($setting);

$name = 'theme_mb2nl/navalign';
$title = get_string('navalign', 'theme_mb2nl');
$setting = new admin_setting_configselect($name, $title, '', 'right', [
    'left' => get_string('left', 'theme_mb2nl'),
    'center' => get_string('center', 'theme_mb2nl'),
    'justify' => get_string('justify', 'theme_mb2nl'),
    'right' => get_string('right', 'theme_mb2nl'),
]);
$temp->add($setting);

$name = 'theme_mb2nl/stickynav';
$title = get_string('stickynav', 'theme_mb2nl');
$setting = new admin_setting_configcheckbox($name, $title, '', 1);
$temp->add($setting);

$name = 'theme_mb2nl/navddwidth';
$title = get_string('navddwidth', 'theme_mb2nl');
$desc = '';
$setting = new admin_setting_configtext($name, $title, $desc, '200');
$setting->set_updatedcallback('theme_reset_all_caches');
$temp->add($setting);

$temp->add(new admin_setting_configmb2spacer('theme_mb2nl/navspacer_7ea'));

// Get menu from database to the select field.
global $DB;

$dbman = $DB->get_manager();
$tablemenus = new xmldb_table('local_mb2megamenu_menus');
$menus = [0 => get_string('none', 'theme_mb2nl')];

if ($dbman->table_exists($tablemenus)) {

    $sqlquery = 'SELECT id, name FROM {local_mb2megamenu_menus} WHERE enable=1';
    $records = $DB->get_records_sql($sqlquery);

    if (count($records)) {
        foreach ($records as $f) {
            $menus[$f->id] = $f->name;
        }
    }
}

$name = 'theme_mb2nl/navforusers';
$title = get_string('navforusers', 'theme_mb2nl');
$setting = new admin_setting_configselect($name, $title, '', 0, $menus);
$temp->add($setting);

$name = 'theme_mb2nl/mycinmenu2';
$title = get_string('mycinmenu', 'theme_mb2nl');
$setting = new admin_setting_configcheckbox($name, $title, '', 0);
$temp->add($setting);

$name = 'theme_mb2nl/mychidden';
$title = get_string('mychidden', 'theme_mb2nl');
$setting = new admin_setting_configcheckbox($name, $title, '', 0);
$temp->add($setting);

$name = 'theme_mb2nl/mycexpierd';
$title = get_string('expiredcourses', 'theme_mb2nl');
$setting = new admin_setting_configcheckbox($name, $title, '', 0);
$temp->add($setting);

$name = 'theme_mb2nl/myclimit';
$title = get_string('myclimit', 'theme_mb2nl');
$setting = new admin_setting_configtext($name, $title, '', 6);
$temp->add($setting);

$setting = new admin_setting_configmb2end('theme_mb2nl/endnavgeneral');
$temp->add($setting);

$setting = new admin_setting_configmb2start2('theme_mb2nl/startadditmenus', get_string('additmenus', 'theme_mb2nl'));
$temp->add($setting);

$name = 'theme_mb2nl/secnav';
$title = get_string('secnav', 'theme_mb2nl');
$setting = new admin_setting_configcheckbox($name, $title, '', 1);
$temp->add($setting);

$temp->add(new admin_setting_configmb2spacer('theme_mb2nl/additmenussp2'));

$name = 'theme_mb2nl/topmenu';
$title = get_string('topmenu', 'theme_mb2nl');
$setting = new admin_setting_configtextarea($name, $title, get_string('topmenudesc', 'theme_mb2nl'), '');
$temp->add($setting);

$temp->add(new admin_setting_configmb2spacer('theme_mb2nl/additmenussp3'));

$name = 'theme_mb2nl/navicons';
$title = get_string('navicon', 'theme_mb2nl');
$setting = new admin_setting_configtextarea($name, $title, get_string('naviconsdesc', 'theme_mb2nl'), '');
$temp->add($setting);

$setting = new admin_setting_configmb2end('theme_mb2nl/endadditmenus');
$temp->add($setting);

$nlsettings->add($temp);
