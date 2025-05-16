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

$temp = new admin_settingpage('theme_mb2nl_settingsgeneral',  get_string('settingsgeneral', 'theme_mb2nl'));

$setting = new admin_setting_configmb2start2('theme_mb2nl/startlogo', get_string('logo', 'theme_mb2nl'));
$setting->set_updatedcallback('theme_reset_all_caches');
$temp->add($setting);

$name = 'theme_mb2nl/logo';
$title = get_string('logoimg', 'theme_mb2nl');
$setting = new admin_setting_configstoredfile($name, $title, '', 'logo');
$temp->add($setting);

$name = 'theme_mb2nl/logodark';
$title = get_string('logodarkimg', 'theme_mb2nl');
$setting = new admin_setting_configstoredfile($name, $title, '', 'logodark');
$temp->add($setting);

$name = 'theme_mb2nl/logoh';
$title = get_string('logoh', 'theme_mb2nl');
$desc = get_string('logohdesc', 'theme_mb2nl');
$setting = new admin_setting_configtext($name, $title, $desc, '48');
$setting->set_updatedcallback('theme_reset_all_caches');
$temp->add($setting);

$name = 'theme_mb2nl/logohsm';
$title = get_string('logohsm', 'theme_mb2nl');
$desc = get_string('logohdesc', 'theme_mb2nl');
$setting = new admin_setting_configtext($name, $title, $desc, '38');
$setting->set_updatedcallback('theme_reset_all_caches');
$temp->add($setting);

$name = 'theme_mb2nl/logospacer1';
$setting = new admin_setting_configmb2spacer($name);
$temp->add($setting);

$name = 'theme_mb2nl/favicon';
$title = get_string('favicon', 'theme_mb2nl');
$desc = get_string('favicondesc', 'theme_mb2nl');
$setting = new admin_setting_configstoredfile($name, $title, $desc, 'favicon', 0, ['accepted_types' => ['.ico']]);
$temp->add($setting);

$setting = new admin_setting_configmb2end('theme_mb2nl/endlogo');
$setting->set_updatedcallback('theme_reset_all_caches');
$temp->add($setting);

$setting = new admin_setting_configmb2start2('theme_mb2nl/startlayout', get_string('layout', 'theme_mb2nl'));
$temp->add($setting);

$name = 'theme_mb2nl/pagewidth';
$title = get_string('pagewidth', 'theme_mb2nl');
$setting = new admin_setting_configtext($name, $title, '', 1270);
$setting->set_updatedcallback('theme_reset_all_caches');
$temp->add($setting);

$layoutarr = [
    'fw' => get_string('layoutfw', 'theme_mb2nl'),
    'fx' => get_string('layoutfx', 'theme_mb2nl'),
];
$name = 'theme_mb2nl/layout';
$title = get_string('layout', 'theme_mb2nl');
$desc = '';
$setting = new admin_setting_configselect($name, $title, $desc, 'fw', $layoutarr);
$temp->add($setting);

$name = 'theme_mb2nl/sidebarpos';
$title = get_string('sidebarpos', 'theme_mb2nl');
$setting = new admin_setting_configselect($name, $title, '', 'right', [
    'classic' => get_string('classic', 'theme_mb2nl'),
    'left' => get_string('left', 'theme_mb2nl'),
    'right' => get_string('right', 'theme_mb2nl'),
]);
$temp->add($setting);

$sidebarbtarr = [
    '0' => get_string('none', 'theme_mb2nl'),
    '1' => get_string('sidebaryesshow', 'theme_mb2nl'),
    '2' => get_string('sidebaryeshide', 'theme_mb2nl'),
];

$name = 'theme_mb2nl/sidebarbtn';
$title = get_string('sidebarbtn', 'theme_mb2nl');
$setting = new admin_setting_configselect($name, $title, '', '1', $sidebarbtarr);
$temp->add($setting);

$name = 'theme_mb2nl/sidebarbtntext';
$title = get_string('sidebarbtntext', 'theme_mb2nl');
$setting = new admin_setting_configcheckbox($name, $title, '', 1);
$temp->add($setting);

$name = 'theme_mb2nl/editingfw2';
$title = get_string('editingfw', 'theme_mb2nl');
$setting = new admin_setting_configcheckbox($name, $title, '', 0);
$temp->add($setting);

$setting = new admin_setting_configmb2spacer('theme_mb2nl/logospacer3');
$temp->add($setting);

$name = 'theme_mb2nl/tgsdb';
$title = get_string('tgsdb', 'theme_mb2nl');
$setting = new admin_setting_configcheckbox($name, $title, '', 1);
$temp->add($setting);

$name = 'theme_mb2nl/tgsdbdef';
$title = get_string('tgsdbdef', 'theme_mb2nl');
$setting = new admin_setting_configcheckbox($name, $title, '', 0);
$temp->add($setting);

$setting = new admin_setting_configmb2end('theme_mb2nl/endlayout');
$temp->add($setting);

$setting = new admin_setting_configmb2start2('theme_mb2nl/startheader', get_string('headerstyleheading', 'theme_mb2nl'));
$temp->add($setting);

$name = 'theme_mb2nl/navbarplugin';
$title = get_string('navbarplugin', 'theme_mb2nl');
$setting = new admin_setting_configcheckbox($name, $title, '', 1);
$temp->add($setting);

$name = 'theme_mb2nl/headerspacer1';
$setting = new admin_setting_configmb2spacer($name);
$temp->add($setting);

$name = 'theme_mb2nl/headercontent';
$title = get_string('headercontent', 'theme_mb2nl');
$setting = new admin_setting_configtextarea($name, $title, get_string('headercontentdesc', 'theme_mb2nl'), '');
$temp->add($setting);

$name = 'theme_mb2nl/headerspacer3';
$setting = new admin_setting_configmb2spacer($name);
$temp->add($setting);

$name = 'theme_mb2nl/headerbtn';
$title = get_string('headerbtn', 'theme_mb2nl');
$setting = new admin_setting_configtextarea($name, $title, get_string('headerbtndesc', 'theme_mb2nl'), '');
$temp->add($setting);

$setting = new admin_setting_configmb2end('theme_mb2nl/endheader');
$temp->add($setting);

$setting = new admin_setting_configmb2start2('theme_mb2nl/startfrontpage', get_string('frontpage', 'theme_mb2nl'));
$temp->add($setting);

$name = 'theme_mb2nl/slider';
$title = get_string('slider', 'theme_mb2nl');
$setting = new admin_setting_configcheckbox($name, $title, '', 0);
$setting->set_updatedcallback('theme_reset_all_caches'); // This is require for load slides css style.
$temp->add($setting);

$name = 'theme_mb2nl/fp2course';
$title = get_string('fp2course', 'theme_mb2nl');
$setting = new admin_setting_configcheckbox($name, $title, '', 0);
$temp->add($setting);

$setting = new admin_setting_configmb2end('theme_mb2nl/endfrontpage');
$temp->add($setting);

$setting = new admin_setting_configmb2start2('theme_mb2nl/startfooter', get_string('footer', 'theme_mb2nl'));
$temp->add($setting);

// Get footer database to select footer.

global $DB;

$dbman = $DB->get_manager();
$tablefooters = new xmldb_table('local_mb2builder_footers');
$footers = [0 => get_string('none', 'theme_mb2nl')];

if ($dbman->table_exists($tablefooters)) {
    $sqlquery = 'SELECT id, name FROM {local_mb2builder_footers}';
    $records = $DB->get_records_sql($sqlquery);

    if (count($records)) {
        foreach ($records as $f) {
            $footers[$f->id] = $f->name;
        }
    }
}

$name = 'theme_mb2nl/footer';
$title = get_string('customfooter', 'theme_mb2nl');
$setting = new admin_setting_configselect($name, $title, get_string('footer_desc', 'theme_mb2nl'), 0, $footers);
$temp->add($setting);

$name = 'theme_mb2nl/footerspacer1';
$setting = new admin_setting_configmb2spacer($name);
$temp->add($setting);

$footerstyleoptions = [
    'dark' => get_string('dark', 'theme_mb2nl'),
    'light' => get_string('light', 'theme_mb2nl'),
];
$name = 'theme_mb2nl/footerstyle';
$title = get_string('style', 'theme_mb2nl');
$setting = new admin_setting_configselect($name, $title, '', 'dark', $footerstyleoptions);
$setting->set_updatedcallback('theme_reset_all_caches');
$temp->add($setting);

$name = 'theme_mb2nl/foottext';
$title = get_string('foottext', 'theme_mb2nl');
$setting = new admin_setting_configtextarea($name, $title, '',
'Copyright (c) New Learning Theme 2017 - [year]. All rights reserved.');
$temp->add($setting);

$name = 'theme_mb2nl/footerspacer2';
$setting = new admin_setting_configmb2spacer($name);
$temp->add($setting);

$name = 'theme_mb2nl/partnerlogos';
$title = get_string('partnerlogos', 'theme_mb2nl');
$opts = ['accepted_types' => ['.png', '.jpg', '.gif', '.webp', '.tiff', '.svg'], 'maxfiles' => 99];
$setting = new admin_setting_configstoredfile($name, $title, '', 'partnerlogos', 0, $opts);
$temp->add($setting);

$name = 'theme_mb2nl/partnerlogoh';
$title = get_string('logoh', 'theme_mb2nl');
$desc = get_string('logohdesc', 'theme_mb2nl');
$setting = new admin_setting_configtext($name, $title, $desc, '46');
$setting->set_updatedcallback('theme_reset_all_caches');
$temp->add($setting);

$name = 'theme_mb2nl/partnerslinks';
$title = get_string('partnerslinks', 'theme_mb2nl');
$setting = new admin_setting_configtextarea($name, $title, get_string('partnerslinksdesc', 'theme_mb2nl'), '');
$temp->add($setting);

$setting = new admin_setting_configmb2end('theme_mb2nl/endfooter');
$temp->add($setting);

$nlsettings->add($temp);
