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

$temp = new admin_settingpage('theme_mb2nlchild_settingsfonts',  get_string('settingsfonts', 'theme_mb2nl'));

$fontsarray = [
    'Arial' => 'Arial',
    'Georgia' => 'Georgia',
    'Tahoma' => 'Tahoma',
    'Lucida Sans Unicode' => 'Lucida Sans Unicode',
    'Palatino Linotype' => 'Palatino Linotype',
    'Trebuchet MS' => 'Trebuchet MS',
];

$setting = new admin_setting_configmb2start2('theme_mb2nlchild/startnfonts', get_string('nfonts', 'theme_mb2nl'));
$temp->add($setting);

for ($i = 1; $i <= 3; $i++) {

    $name = 'theme_mb2nlchild/nfont' . $i;
    $title = get_string('fontname', 'theme_mb2nl') . ' #' . $i;
    $desc = '';
    $setting = new admin_setting_configselect($name, $title, $desc, '', $fontsarray);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $temp->add($setting);

}

$setting = new admin_setting_configmb2end('theme_mb2nlchild/endnfonts');
$temp->add($setting);

$setting = new admin_setting_configmb2start2('theme_mb2nlchild/startgfonts', get_string('gfonts', 'theme_mb2nl'));
$temp->add($setting);

for ($i = 1; $i <= 3; $i++) {

    $name = 'theme_mb2nlchild/gfont' . $i;
    $title = get_string('fontname', 'theme_mb2nl') . ' #' . $i;
    $def = $i == 1 ? 'Roboto' : '';
    $desc = '';
    $setting = new admin_setting_configtext($name, $title, $desc, $def);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $temp->add($setting);

    $name = 'theme_mb2nlchild/gfontstyle' . $i;
    $title = get_string('fontstyle', 'theme_mb2nl') . ' #' . $i;
    $def2 = $i == 1 ? '300,400,500,700' : '';
    $desc = '';
    $setting = new admin_setting_configtext($name, $title, $desc, $def2);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $temp->add($setting);

    $name = 'theme_mb2nlchild/gfontspacer' . $i;
    $setting = new admin_setting_configmb2spacer($name);
    $temp->add($setting);

}

$name = 'theme_mb2nlchild/gfontsubset';
$title = get_string('fontsubset', 'theme_mb2nl');
$setting = new admin_setting_configtext($name, $title, '', '');
$temp->add($setting);

$setting = new admin_setting_configmb2end('theme_mb2nlchild/endgfonts');
$temp->add($setting);

$setting = new admin_setting_configmb2start2('theme_mb2nlchild/startcfonts', get_string('cfont', 'theme_mb2nl'));
$temp->add($setting);

for ($i = 1; $i <= 3; $i++) {
    $name = 'theme_mb2nlchild/cfont' . $i;
    $title = get_string('cfontname', 'theme_mb2nl') . ' #' . $i;
    $setting = new admin_setting_configtext($name, $title, '', '');
    $setting->set_updatedcallback('theme_reset_all_caches');
    $temp->add($setting);

    $name = 'theme_mb2nlchild/cfontfiles' . $i;
    $title = get_string('cfontfiles', 'theme_mb2nl') . ' #' . $i;
    $desc = get_string('cfontfilesdesc', 'theme_mb2nl');
    $setting = new admin_setting_configstoredfile($name, $title, $desc, 'cfontfiles' . $i, 0, [
        'accepted_types' => ['woff2', 'woff', 'ttf'],
        'maxfiles' => 3,
    ]);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $temp->add($setting);

    if ($i < 3) {
        $name = 'theme_mb2nlchild/cfontspacer' . $i;
        $setting = new admin_setting_configmb2spacer($name);
        $temp->add($setting);
    }
}

$setting = new admin_setting_configmb2end('theme_mb2nlchild/endcfonts');
$temp->add($setting);

$nlsettings->add($temp);
