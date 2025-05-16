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

$temp = new admin_settingpage('theme_mb2nlchild_settingsstyle',  get_string('settingsstyle', 'theme_mb2nl'));

$bgpredefinedpageopt = [
    '' => get_string('none', 'theme_mb2nl'),
    'default' => get_string('imgdefault', 'theme_mb2nl'),
    'strip1' => get_string('strip1', 'theme_mb2nl'),
    'strip2' => get_string('strip2', 'theme_mb2nl'),
];

$setting = new admin_setting_configmb2start2('theme_mb2nlchild/startcolors', get_string('colors', 'theme_mb2nl'));
$temp->add($setting);

$name = 'theme_mb2nlchild/accent1';
$title = get_string('accentcolor', 'theme_mb2nl') . ' 1';
$setting = new admin_setting_configmb2color($name, $title, $desc, '#b8001c');
$setting->set_updatedcallback('theme_reset_all_caches');
$temp->add($setting);

$name = 'theme_mb2nlchild/accent2';
$title = get_string('accentcolor', 'theme_mb2nl') . ' 2';
$setting = new admin_setting_configmb2color($name, $title, '', '#27323a');
$setting->set_updatedcallback('theme_reset_all_caches');
$temp->add($setting);

$name = 'theme_mb2nlchild/accent3';
$title = get_string('accentcolor', 'theme_mb2nl') . ' 3';
$setting = new admin_setting_configmb2color($name, $title, '', '#ffb400');
$setting->set_updatedcallback('theme_reset_all_caches');
$temp->add($setting);

$setting = new admin_setting_configmb2spacer('theme_mb2nlchild/colorspacer1');
$setting->set_updatedcallback('theme_reset_all_caches');
$temp->add($setting);

$name = 'theme_mb2nlchild/textcolor';
$title = get_string('textcolor', 'theme_mb2nl');
$setting = new admin_setting_configmb2color($name, $title, '', '#4f4c51');
$setting->set_updatedcallback('theme_reset_all_caches');
$temp->add($setting);

$name = 'theme_mb2nlchild/textcolor_lighten';
$title = get_string('textcolor_lighten', 'theme_mb2nl');
$setting = new admin_setting_configmb2color($name, $title, '', '#a6a2a9');
$setting->set_updatedcallback('theme_reset_all_caches');
$temp->add($setting);

$name = 'theme_mb2nlchild/textcolorondark';
$title = get_string('textcolorondark', 'theme_mb2nl');
$setting = new admin_setting_configmb2color($name, $title, '', '#909699');
$setting->set_updatedcallback('theme_reset_all_caches');
$temp->add($setting);

$name = 'theme_mb2nlchild/linkcolor';
$title = get_string('linkcolor', 'theme_mb2nl');
$setting = new admin_setting_configmb2color($name, $title, $desc, '#0083fa');
$setting->set_updatedcallback('theme_reset_all_caches');
$temp->add($setting);

$name = 'theme_mb2nlchild/linkhcolor';
$title = get_string('linkhcolor', 'theme_mb2nl');
$setting = new admin_setting_configmb2color($name, $title, $desc, '#00529e');
$setting->set_updatedcallback('theme_reset_all_caches');
$temp->add($setting);

$name = 'theme_mb2nlchild/headingscolor';
$title = get_string('headingscolor', 'theme_mb2nl');
$setting = new admin_setting_configmb2color($name, $title, $desc, '#242027');
$setting->set_updatedcallback('theme_reset_all_caches');
$temp->add($setting);

$setting = new admin_setting_configmb2spacer('theme_mb2nlchild/colorspacer2');
$temp->add($setting);

$name = 'theme_mb2nlchild/btncolor';
$title = get_string('btncolor', 'theme_mb2nl');
$setting = new admin_setting_configmb2color($name, $title, $desc, '#2c49b6');
$setting->set_updatedcallback('theme_reset_all_caches');
$temp->add($setting);

$name = 'theme_mb2nlchild/btnprimarycolor';
$title = get_string('btnprimarycolor', 'theme_mb2nl');
$setting = new admin_setting_configmb2color($name, $title, $desc, '#b8001c');
$setting->set_updatedcallback('theme_reset_all_caches');
$temp->add($setting);

$setting = new admin_setting_configmb2spacer('theme_mb2nlchild/colorspacer4');
$temp->add($setting);

$name = 'theme_mb2nlchild/color_success';
$title = get_string('color_success', 'theme_mb2nl');
$setting = new admin_setting_configmb2color($name, $title, $desc, '#25a18e');
$setting->set_updatedcallback('theme_reset_all_caches');
$temp->add($setting);

$name = 'theme_mb2nlchild/color_warning';
$title = get_string('color_warning', 'theme_mb2nl');
$setting = new admin_setting_configmb2color($name, $title, $desc, '#ff7000');
$setting->set_updatedcallback('theme_reset_all_caches');
$temp->add($setting);

$name = 'theme_mb2nlchild/color_danger';
$title = get_string('color_danger', 'theme_mb2nl');
$setting = new admin_setting_configmb2color($name, $title, $desc, '#eb455f');
$setting->set_updatedcallback('theme_reset_all_caches');
$temp->add($setting);

$name = 'theme_mb2nlchild/color_info';
$title = get_string('color_info', 'theme_mb2nl');
$setting = new admin_setting_configmb2color($name, $title, $desc, '#2c49b6');
$setting->set_updatedcallback('theme_reset_all_caches');
$temp->add($setting);

$name = 'theme_mb2nlchild/colorspacer5';
$setting = new admin_setting_configmb2spacer($name);
$temp->add($setting);

$name = 'theme_mb2nlchild/catcolors';
$title = get_string('catcolors', 'theme_mb2nl');
$setting = new admin_setting_configtextarea($name, $title, get_string('catcolors_desc', 'theme_mb2nl'), '');
$temp->add($setting);

$setting = new admin_setting_configmb2end('theme_mb2nlchild/endcolors');
$temp->add($setting);

$setting = new admin_setting_configmb2start2('theme_mb2nlchild/startheaderstyle', get_string('headerstyleheading', 'theme_mb2nl'));
$temp->add($setting);

$name = 'theme_mb2nlchild/headerstyle';
$title = get_string('headerstyle', 'theme_mb2nl');
$setting = new admin_setting_configselect($name, $title, '', 'light', [
    'light' => get_string('light', 'theme_mb2nl'),
    'light2' => get_string('light', 'theme_mb2nl') . ' 2',
    'dark' => get_string('dark', 'theme_mb2nl'),
    'transparent' => get_string('transparent', 'theme_mb2nl'),
    'transparent_light' => get_string('transparent_light', 'theme_mb2nl'),
]);
$temp->add($setting);

$name = 'theme_mb2nlchild/headerfw';
$title = get_string('layoutfw', 'theme_mb2nl');
$setting = new admin_setting_configcheckbox($name, $title, '', 0);
$temp->add($setting);

$name = 'theme_mb2nlchild/transparentbg';
$title = get_string('transparentbg', 'theme_mb2nl');
$setting = new admin_setting_configcheckbox($name, $title, '', 0);
$temp->add($setting);

$temp->add(new admin_setting_configmb2spacer('theme_mb2nlchild/hstylespacere1'));
$temp->add(new admin_setting_configmb2heading('theme_mb2nlchild/hstyleheading1',
get_string('darktransparentheading', 'theme_mb2nl')));

$name = 'theme_mb2nlchild/mhbgcolor';
$title = get_string('hbgcolor', 'theme_mb2nl');
$setting = new admin_setting_configmb2color($name, $title, '', '#041336');
$setting->set_updatedcallback('theme_reset_all_caches');
$temp->add($setting);

$name = 'theme_mb2nlchild/tbbgcolor';
$title = get_string('tbbgcolor', 'theme_mb2nl');
$setting = new admin_setting_configmb2color($name, $title, '', '#1f2a44');
$setting->set_updatedcallback('theme_reset_all_caches');
$temp->add($setting);

$temp->add(new admin_setting_configmb2spacer('theme_mb2nlchild/hstylespacere2'));
$temp->add(new admin_setting_configmb2heading('theme_mb2nlchild/hstyleheading2',
get_string('lighttransparentheading', 'theme_mb2nl')));

$name = 'theme_mb2nlchild/mhbgcolorl';
$title = get_string('hbgcolor', 'theme_mb2nl');
$setting = new admin_setting_configmb2color($name, $title, '', '#f0f5f7');
$setting->set_updatedcallback('theme_reset_all_caches');
$temp->add($setting);

$name = 'theme_mb2nlchild/tbbgcolorl';
$title = get_string('tbbgcolor', 'theme_mb2nl');
$setting = new admin_setting_configmb2color($name, $title, '', '#e6ebed');
$setting->set_updatedcallback('theme_reset_all_caches');
$temp->add($setting);

$setting = new admin_setting_configmb2end('theme_mb2nlchild/endheaderstyle');
$temp->add($setting);

$setting = new admin_setting_configmb2start2('theme_mb2nlchild/startptitlestyle', get_string('h_pagetitle', 'theme_mb2nl'));
$temp->add($setting);

$name = 'theme_mb2nlchild/headerl';
$title = get_string('style', 'theme_mb2nl');
$setting = new admin_setting_configselect($name, $title, '', 'modern', [
    'classic' => get_string('classic', 'theme_mb2nl'),
    'modern' => get_string('modern', 'theme_mb2nl'),
 ]);
$temp->add($setting);

$name = 'theme_mb2nlchild/headercolorscheme';
$title = get_string('colorscheme', 'theme_mb2nl');
$setting = new admin_setting_configselect($name, $title, '', 'dark', [
    'dark' => get_string('dark', 'theme_mb2nl'),
    'light' => get_string('light', 'theme_mb2nl'),
]);
$temp->add($setting);

$name = 'theme_mb2nlchild/wavebg';
$title = get_string('pbgpre', 'theme_mb2nl');
$setting = new admin_setting_configselect($name, $title, '', 1, [
    0 => get_string('none', 'theme_mb2nl'),
    1 => get_string('waven', 'theme_mb2nl', 1),
 ]);
$temp->add($setting);

$name = 'theme_mb2nlchild/headergradbg';
$title = get_string('headergradbg', 'theme_mb2nl');
$setting = new admin_setting_configcheckbox($name, $title, '', 0);
$temp->add($setting);

$temp->add(new admin_setting_configmb2spacer('theme_mb2nlchild/hstylespacere3'));
$temp->add(new admin_setting_configmb2heading('theme_mb2nlchild/hstyleheading3', get_string('darkschemeheading', 'theme_mb2nl')));

$name = 'theme_mb2nlchild/headerbgcolor';
$title = get_string('gradcolor', 'theme_mb2nl', '1');
$setting = new admin_setting_configmb2color($name, $title, get_string('gradcolordesc', 'theme_mb2nl'), '#001d62');
$setting->set_updatedcallback('theme_reset_all_caches');
$temp->add($setting);

$name = 'theme_mb2nlchild/headerbgcolor2';
$title = get_string('gradcolor', 'theme_mb2nl', '2');
$setting = new admin_setting_configmb2color($name, $title, '', '#204c96');
$setting->set_updatedcallback('theme_reset_all_caches');
$temp->add($setting);

$temp->add(new admin_setting_configmb2spacer('theme_mb2nlchild/hstylespacere4'));
$temp->add(new admin_setting_configmb2heading('theme_mb2nlchild/hstyleheading4', get_string('lightschemeheading', 'theme_mb2nl')));

$name = 'theme_mb2nlchild/headerlbgcolor';
$title = get_string('gradcolor', 'theme_mb2nl', '1');
$setting = new admin_setting_configmb2color($name, $title, get_string('gradcolordesc', 'theme_mb2nl'), '#f4f7f8');
$setting->set_updatedcallback('theme_reset_all_caches');
$temp->add($setting);

$name = 'theme_mb2nlchild/headerlbgcolor2';
$title = get_string('gradcolor', 'theme_mb2nl', '2');
$setting = new admin_setting_configmb2color($name, $title, '', '#d6e4e5');
$setting->set_updatedcallback('theme_reset_all_caches');
$temp->add($setting);

$temp->add(new admin_setting_configmb2spacer('theme_mb2nlchild/hstylespacere5'));

$name = 'theme_mb2nlchild/headerimg';
$title = get_string('bgimage', 'theme_mb2nl');
$setting = new admin_setting_configstoredfile($name, $title, '', 'headerimg');
$temp->add($setting);

$setting = new admin_setting_configmb2end('theme_mb2nlchild/endptitlestyle');
$temp->add($setting);

$setting = new admin_setting_configmb2start2('theme_mb2nlchild/startstylemenu', get_string('mainmenu', 'theme_mb2nl'));
$temp->add($setting);

$name = 'theme_mb2nlchild/navbarbgcolor';
$title = get_string('navbarbgcolor', 'theme_mb2nl');
$setting = new admin_setting_configmb2color($name, $title, '', '');
$temp->add($setting);

$name = 'theme_mb2nlchild/navcolor';
$title = get_string('navcolor', 'theme_mb2nl');
$setting = new admin_setting_configmb2color($name, $title, '', '');
$temp->add($setting);

$name = 'theme_mb2nlchild/navsubcolor';
$title = get_string('navsubcolor', 'theme_mb2nl');
$setting = new admin_setting_configmb2color($name, $title, '', '');
$temp->add($setting);

$temp->add(new admin_setting_configmb2spacer('theme_mb2nlchild/navspacer2'));

$name = 'theme_mb2nlchild/navhcolor';
$title = get_string('navhcolor', 'theme_mb2nl');
$setting = new admin_setting_configmb2color($name, $title, '', '');
$temp->add($setting);

$name = 'theme_mb2nlchild/navsubhcolor';
$title = get_string('navsubhcolor', 'theme_mb2nl');
$setting = new admin_setting_configmb2color($name, $title, '', '');
$temp->add($setting);

$name = 'theme_mb2nlchild/navhbgcolor';
$title = get_string('navhbgcolor', 'theme_mb2nl');
$setting = new admin_setting_configmb2color($name, $title, '', '');
$temp->add($setting);

$setting = new admin_setting_configmb2end('theme_mb2nlchild/endstylemenu');
$temp->add($setting);

$setting = new admin_setting_configmb2start2('theme_mb2nlchild/starttgsdbstyle', get_string('tgsdb', 'theme_mb2nl'));
$temp->add($setting);

$name = 'theme_mb2nlchild/tgsdbdark';
$title = get_string('darkschemeheading', 'theme_mb2nl');
$setting = new admin_setting_configcheckbox($name, $title, '', 0);
$temp->add($setting);

$name = 'theme_mb2nlchild/tgsdbbg';
$title = get_string('bgcolor', 'theme_mb2nl');
$setting = new admin_setting_configmb2color($name, $title, '', '#212f45');
$temp->add($setting);

$setting = new admin_setting_configmb2end('theme_mb2nlchild/endtgsdbstyle');
$temp->add($setting);

$setting = new admin_setting_configmb2start2('theme_mb2nlchild/startblockstyle', get_string('blockstyleheading', 'theme_mb2nl'));
$temp->add($setting);

$layoutarr = [
    'classic' => get_string('classic', 'theme_mb2nl'),
    'minimal' => get_string('minimal', 'theme_mb2nl'),
];
$name = 'theme_mb2nlchild/blockstyle2';
$title = get_string('blockstyle', 'theme_mb2nl');
$setting = new admin_setting_configselect($name, $title, '', 'minimal', $layoutarr);
$temp->add($setting);

$setting = new admin_setting_configmb2end('theme_mb2nlchild/endblockstyle');
$temp->add($setting);

$setting = new admin_setting_configmb2start2('theme_mb2nlchild/startpagestyle', get_string('pagestyle', 'theme_mb2nl'));
$setting->set_updatedcallback('theme_reset_all_caches');
$temp->add($setting);

$name = 'theme_mb2nlchild/pbgpre';
$title = get_string('pbgpre', 'theme_mb2nl');
$setting = new admin_setting_configselect($name, $title, '', '', $bgpredefinedpageopt);
$setting->set_updatedcallback('theme_reset_all_caches');
$temp->add($setting);

$name = 'theme_mb2nlchild/pbgcolor';
$title = get_string('bgcolor', 'theme_mb2nl');
$setting = new admin_setting_configmb2color($name, $title, get_string('pbgdesc', 'theme_mb2nl'), '');
$setting->set_updatedcallback('theme_reset_all_caches');
$temp->add($setting);

$name = 'theme_mb2nlchild/pbgimage';
$title = get_string('bgimage', 'theme_mb2nl');
$setting = new admin_setting_configstoredfile($name, $title, get_string('pbgdesc', 'theme_mb2nl'), 'pbgimage');
$setting->set_updatedcallback('theme_reset_all_caches');
$temp->add($setting);

$setting = new admin_setting_configmb2end('theme_mb2nlchild/endpagestyle');
$setting->set_updatedcallback('theme_reset_all_caches');
$temp->add($setting);

$setting = new admin_setting_configmb2start2('theme_mb2nlchild/startplugincss', get_string('plugincss', 'theme_mb2nl'));
$temp->add($setting);

$name = 'theme_mb2nlchild/plugincss';
$title = get_string('plugincss', 'theme_mb2nl');
$setting = new admin_setting_configtextarea($name, $title, get_string('plugincssdesc', 'theme_mb2nl'), '');
$setting->set_updatedcallback('theme_reset_all_caches');
$temp->add($setting);

$setting = new admin_setting_configmb2end('theme_mb2nlchild/endplugincss');
$temp->add($setting);

$setting = new admin_setting_configmb2start2('theme_mb2nlchild/startcustomcssstyle',
get_string('scustomcssstyleheading', 'theme_mb2nl'));
$temp->add($setting);

$name = 'theme_mb2nlchild/customcss';
$title = get_string('customcss', 'theme_mb2nl');
$setting = new admin_setting_configtextarea($name, $title, '', '');
$setting->set_updatedcallback('theme_reset_all_caches');
$temp->add($setting);

$name = 'theme_mb2nlchild/cstylefiles';
$title = get_string('cstylefiles', 'theme_mb2nl');
$setting = new admin_setting_configstoredfile($name, $title, '', 'cstylefiles', 0, ['accepted_types' => ['css', 'scss'],
'maxfiles' => -1]);
$setting->set_updatedcallback('theme_reset_all_caches');
$temp->add($setting);

$setting = new admin_setting_configmb2end('theme_mb2nlchild/endcustomcssstyle');
$temp->add($setting);

$nlsettings->add($temp);
