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

$temp = new admin_settingpage('theme_mb2nl_settingsfeatures',  get_string('settingsfeatures', 'theme_mb2nl'));
$yesnooptions = ['1' => get_string('yes', 'theme_mb2nl'), '0' => get_string('no', 'theme_mb2nl')];

    $bgpredefinedopt = [
        '' => get_string('none', 'theme_mb2nl'),
        'strip1' => get_string('strip1', 'theme_mb2nl'),
        'strip2' => get_string('strip2', 'theme_mb2nl'),
    ];

    $setting = new admin_setting_configmb2start2('theme_mb2nl/startaccessibility', get_string('accessibility', 'theme_mb2nl'));
    $temp->add($setting);

    $name = 'theme_mb2nl/acsboptions';
    $title = get_string('acsboptions', 'theme_mb2nl');
    $setting = new admin_setting_configcheckbox($name, $title, '', 1);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $temp->add($setting);

    $name = 'theme_mb2nl/acsbalticon';
    $title = get_string('acsbalticon', 'theme_mb2nl');
    $setting = new admin_setting_configcheckbox($name, $title, '', 0);
    $temp->add($setting);

    $name = 'theme_mb2nl/dyslexic';
    $title = get_string('dyslexic', 'theme_mb2nl');
    $setting = new admin_setting_configcheckbox($name, $title, '', 1);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $temp->add($setting);

    $temp->add(new admin_setting_configmb2spacer('theme_mb2nl/acsbspacer1'));

    $name = 'theme_mb2nl/acsb_color1';
    $title = get_string('colorn', 'theme_mb2nl', '1');
    $setting = new admin_setting_configmb2color($name, $title, '', '#033860');
    $setting->set_updatedcallback('theme_reset_all_caches');
    $temp->add($setting);

    $name = 'theme_mb2nl/acsb_color2';
    $title = get_string('colorn', 'theme_mb2nl', '2');
    $setting = new admin_setting_configmb2color($name, $title, '', '#004ba8');
    $setting->set_updatedcallback('theme_reset_all_caches');
    $temp->add($setting);

    $name = 'theme_mb2nl/acsb_color3';
    $title = get_string('colorn', 'theme_mb2nl', '3');
    $setting = new admin_setting_configmb2color($name, $title, '', '#d9ecf2');
    $setting->set_updatedcallback('theme_reset_all_caches');
    $temp->add($setting);

    $setting = new admin_setting_configmb2end('theme_mb2nl/endaccessibility');
    $temp->add($setting);

    $setting = new admin_setting_configmb2start2('theme_mb2nl/startblog', get_string('blogsettings', 'theme_mb2nl'));
    $temp->add($setting);

    $name = 'theme_mb2nl/blogplaceholder';
    $title = get_string('blogplaceholder', 'theme_mb2nl');
    $setting = new admin_setting_configstoredfile($name, $title, '', 'blogplaceholder');
    $temp->add($setting);

    $temp->add(new admin_setting_configmb2spacer('theme_mb2nl/blogspacer1'));
    $temp->add(new admin_setting_configmb2heading('theme_mb2nl/blogheading1', get_string('blogpage', 'theme_mb2nl')));

    $name = 'theme_mb2nl/bloglayout';
    $title = get_string('layout', 'theme_mb2nl');
    $setting = new admin_setting_configselect($name, $title, '', 'col3', [
        'list' => get_string('layoutlist', 'theme_mb2nl'),
        'col2' => get_string('xcolumns', 'theme_mb2nl', 2),
        'col3' => get_string('xcolumns', 'theme_mb2nl', 3),
    ]);
    $temp->add($setting);

    $name = 'theme_mb2nl/blogsidebar';
    $title = get_string('sidebar', 'theme_mb2nl');
    $setting = new admin_setting_configcheckbox($name, $title, '', 0);
    $temp->add($setting);

    $name = 'theme_mb2nl/blogdateformat';
    $title = get_string('dateformat', 'theme_mb2nl');
    $setting = new admin_setting_configtext($name, $title, '', 'M d, Y');
    $temp->add($setting);

    $name = 'theme_mb2nl/blogpageintro';
    $title = get_string('blogintro', 'theme_mb2nl');
    $setting = new admin_setting_configcheckbox($name, $title, '', 1);
    $temp->add($setting);

    $name = 'theme_mb2nl/blogmore';
    $title = get_string('blogmore', 'theme_mb2nl');
    $setting = new admin_setting_configcheckbox($name, $title, '', 1);
    $temp->add($setting);

    $temp->add(new admin_setting_configmb2spacer('theme_mb2nl/blogspacer2'));
    $temp->add(new admin_setting_configmb2heading('theme_mb2nl/blogheading2', get_string('blogsinglepage', 'theme_mb2nl')));

    $name = 'theme_mb2nl/blogsinglesidebar';
    $title = get_string('sidebar', 'theme_mb2nl');
    $setting = new admin_setting_configcheckbox($name, $title, '', 0);
    $temp->add($setting);

    $name = 'theme_mb2nl/blogsingledateformat';
    $title = get_string('dateformat', 'theme_mb2nl');
    $setting = new admin_setting_configtext($name, $title, '', 'M d, Y, H:i A');
    $temp->add($setting);

    $name = 'theme_mb2nl/blogfeaturedmedia';
    $title = get_string('blogfeaturedmedia', 'theme_mb2nl');
    $setting = new admin_setting_configcheckbox($name, $title, '', 1);
    $temp->add($setting);

    $name = 'theme_mb2nl/blogsingleintro';
    $title = get_string('blogintro', 'theme_mb2nl');
    $setting = new admin_setting_configcheckbox($name, $title, '', 1);
    $temp->add($setting);

    $name = 'theme_mb2nl/blogmodify';
    $title = get_string('blogmodify', 'theme_mb2nl');
    $setting = new admin_setting_configcheckbox($name, $title, '', 0);
    $temp->add($setting);

    $name = 'theme_mb2nl/blogshareicons';
    $title = get_string('shareicons', 'theme_mb2nl');
    $setting = new admin_setting_configcheckbox($name, $title, '', 1);
    $temp->add($setting);

    $setting = new admin_setting_configmb2end('theme_mb2nl/endblog');
    $temp->add($setting);

    $setting = new admin_setting_configmb2start2('theme_mb2nl/startbookmarks', get_string('bookmarks', 'theme_mb2nl'));
    $temp->add($setting);

    $name = 'theme_mb2nl/bookmarks';
    $title = get_string('bookmarks', 'theme_mb2nl');
    $setting = new admin_setting_configcheckbox($name, $title, '', 0);
    $temp->add($setting);

    $name = 'theme_mb2nl/bookmarkslimit';
    $title = get_string('bookmarkslimit', 'theme_mb2nl');
    $setting = new admin_setting_configtext($name, $title, '', 15);
    $temp->add($setting);

    $setting = new admin_setting_configmb2end('theme_mb2nl/endbookmarks');
    $temp->add($setting);

    $setting = new admin_setting_configmb2start2('theme_mb2nl/startcoursepanel', get_string('coursepanel', 'theme_mb2nl'));
    $temp->add($setting);

    $name = 'theme_mb2nl/coursepanel';
    $title = get_string('coursepanel', 'theme_mb2nl');
    $setting = new admin_setting_configcheckbox($name, $title, '', 1);
    $temp->add($setting);

    $setting = new admin_setting_configmb2end('theme_mb2nl/endcoursepanel');
    $temp->add($setting);

    $setting = new admin_setting_configmb2start2('theme_mb2nl/startevents', get_string('events', 'calendar'));
    $temp->add($setting);

    $name = 'theme_mb2nl/eventsplaceholder';
    $title = get_string('eventsplaceholder', 'theme_mb2nl');
    $setting = new admin_setting_configstoredfile($name, $title, '', 'eventsplaceholder');
    $temp->add($setting);

    $setting = new admin_setting_configmb2end('theme_mb2nl/endevent');
    $temp->add($setting);

    $setting = new admin_setting_configmb2start2('theme_mb2nl/startdashboard', get_string('myhome'));
    $temp->add($setting);

    $rolestoselect = [];

    if (function_exists('role_fix_names') && function_exists('get_all_roles')) {
        foreach (role_fix_names(get_all_roles()) as $role) {
            $rolestoselect[$role->id] = $role->localname;
        }
    }

    $name = 'theme_mb2nl/d2cols';
    $title = get_string('c2cols', 'theme_mb2nl');
    $setting = new admin_setting_configcheckbox($name, $title, '', 1);
    $temp->add($setting);

    $temp->add(new admin_setting_configmb2spacer('theme_mb2nl/dshbspacer1'));
    $temp->add(new admin_setting_configmb2heading('theme_mb2nl/dshbheading1', get_string('customdshbheading', 'theme_mb2nl')));

    $name = 'theme_mb2nl/dashboard';
    $title = get_string('customdshbheading', 'theme_mb2nl');
    $setting = new admin_setting_configcheckbox($name, $title, '', 0);
    $temp->add($setting);

    $name = 'theme_mb2nl/dshbdsbl';
    $title = get_string('dshbdsbl', 'theme_mb2nl');
    $setting = new admin_setting_configmultiselect($name, $title, '', [], $rolestoselect);
    $temp->add($setting);

    $name = 'theme_mb2nl/activeuserstime';
    $title = get_string('activeuserstime', 'theme_mb2nl');
    $setting = new admin_setting_configtext($name, $title, '', 6);
    $temp->add($setting);

    $setting = new admin_setting_configmb2end('theme_mb2nl/enddashboard');
    $temp->add($setting);

    $setting = new admin_setting_configmb2start2('theme_mb2nl/startlang', get_string('language', 'theme_mb2nl'));
    $temp->add($setting);

    $name = 'theme_mb2nl/langmenu';
    $title = get_string('langmenu', 'theme_mb2nl');
    $setting = new admin_setting_configcheckbox($name, $title, '', 0);
    $temp->add($setting);

    $name = 'theme_mb2nl/langfooter';
    $title = get_string('langfooter', 'theme_mb2nl');
    $setting = new admin_setting_configcheckbox($name, $title, '', 1);
    $temp->add($setting);

    $name = 'theme_mb2nl/langimg';
    $title = get_string('langimg', 'theme_mb2nl');
    $setting = new admin_setting_configcheckbox($name, $title, '', 0);
    $temp->add($setting);

    $name = 'theme_mb2nl/langflags';
    $title = get_string('langflags', 'theme_mb2nl');
    $desc = '';
    $setting = new admin_setting_configstoredfile($name, $title, $desc, 'langflags', 0, ['accepted_types' => ['.png', '.jpg'],
    'maxfiles' => -1]);
    $setting->set_updatedcallback('theme_cache_features_purge');
    $temp->add($setting);

    $setting = new admin_setting_configmb2end('theme_mb2nl/endlang');
    $temp->add($setting);

    $setting = new admin_setting_configmb2start2('theme_mb2nl/startlogin', get_string('cloginpage', 'theme_mb2nl'));
    $temp->add($setting);

    $name = 'theme_mb2nl/cloginpage';
    $title = get_string('cloginpage', 'theme_mb2nl');
    $setting = new admin_setting_configselect($name, $title, '', 1, [
        0 => get_string('none', 'theme_mb2nl'),
        1 => get_string('layoutn', 'theme_mb2nl', ['layout' => 1]),
        2 => get_string('layoutn', 'theme_mb2nl', ['layout' => 2]),
        3 => get_string('layoutn', 'theme_mb2nl', ['layout' => 3]),
    ]);
    $temp->add($setting);

    $name = 'theme_mb2nl/loginbgcolor';
    $title = get_string('bgcolor', 'theme_mb2nl');
    $setting = new admin_setting_configmb2color($name, $title, '', '');
    $setting->set_updatedcallback('theme_reset_all_caches');
    $temp->add($setting);

    $name = 'theme_mb2nl/loginbgpre';
    $title = get_string('pbgpre', 'theme_mb2nl');
    $setting = new admin_setting_configselect($name, $title, '', '', $bgpredefinedopt);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $temp->add($setting);

    $name = 'theme_mb2nl/loginbgimage';
    $title = get_string('bgimage', 'theme_mb2nl');
    $setting = new admin_setting_configstoredfile($name, $title, get_string('pbgdesc', 'theme_mb2nl'), 'loginbgimage');
    $setting->set_updatedcallback('theme_reset_all_caches');
    $temp->add($setting);

    $setting = new admin_setting_configmb2end('theme_mb2nl/endlogin');
    $setting->set_updatedcallback('theme_reset_all_caches');
    $temp->add($setting);

    $setting = new admin_setting_configmb2start2('theme_mb2nl/startloading', get_string('loadingscreen', 'theme_mb2nl'));
    $setting->set_updatedcallback('theme_reset_all_caches');
    $temp->add($setting);

    $name = 'theme_mb2nl/loadingscr';
    $title = get_string('loadingscreen', 'theme_mb2nl');
    $setting = new admin_setting_configcheckbox($name, $title, get_string('loadingscrdesc', 'theme_mb2nl'), 0);
    $temp->add($setting);

    $name = 'theme_mb2nl/loadinghide';
    $title = get_string('loadinghide', 'theme_mb2nl');
    $setting = new admin_setting_configtext($name, $title, '', 1000);
    $temp->add($setting);

    $name = 'theme_mb2nl/spinnerw';
    $title = get_string('spinnerw', 'theme_mb2nl');
    $setting = new admin_setting_configtext($name, $title, '', 50);
    $temp->add($setting);

    $name = 'theme_mb2nl/lbgcolor';
    $title = get_string('bgcolor', 'theme_mb2nl');
    $setting = new admin_setting_configmb2color($name, $title, '', '');
    $setting->set_updatedcallback('theme_reset_all_caches');
    $temp->add($setting);

    $name = 'theme_mb2nl/loadinglogo';
    $title = get_string('logoimg', 'theme_mb2nl');
    $setting = new admin_setting_configstoredfile($name, $title, get_string('loadinglogodesc', 'theme_mb2nl'), 'loadinglogo');
    $temp->add($setting);

    $setting = new admin_setting_configmb2end('theme_mb2nl/endloading');
    $setting->set_updatedcallback('theme_reset_all_caches');
    $temp->add($setting);

    $setting = new admin_setting_configmb2start2('theme_mb2nl/startloginform', get_string('loginsearchform', 'theme_mb2nl'));
    $temp->add($setting);

    $name = 'theme_mb2nl/modaltools';
    $title = get_string('modaltools', 'theme_mb2nl');
    $setting = new admin_setting_configcheckbox($name, $title, '', 1);
    $temp->add($setting);

    $name = 'theme_mb2nl/loginlinktopage';
    $title = get_string('loginlinktopage', 'theme_mb2nl');
    $setting = new admin_setting_configcheckbox($name, $title, '', 0);
    $temp->add($setting);

    $layoutarr = [
        '1' => get_string('loginpage', 'theme_mb2nl'),
        '2' => get_string('forgotpage', 'theme_mb2nl'),
    ];

    $name = 'theme_mb2nl/loginsearchspacer1';
    $setting = new admin_setting_configmb2spacer($name);
    $temp->add($setting);

    $name = 'theme_mb2nl/signuppage';
    $title = get_string('signuppage', 'theme_mb2nl');
    $setting = new admin_setting_configtext($name, $title, '', '');
    $temp->add($setting);

    $name = 'theme_mb2nl/loginsearchspacer2';
    $setting = new admin_setting_configmb2spacer($name);
    $temp->add($setting);

    $name = 'theme_mb2nl/searchlinks';
    $title = get_string('searchlinks', 'theme_mb2nl');
    $setting = new admin_setting_configtextarea($name, $title, get_string('searchlinksdesc', 'theme_mb2nl'), '');
    $temp->add($setting);

    $setting = new admin_setting_configmb2end('theme_mb2nl/endloginform');
    $setting->set_updatedcallback('theme_reset_all_caches');
    $temp->add($setting);

    $setting = new admin_setting_configmb2start2('theme_mb2nl/startscrolltt', get_string('scrolltt', 'theme_mb2nl'));
    $temp->add($setting);

    $name = 'theme_mb2nl/scrolltt';
    $title = get_string('scrolltt', 'theme_mb2nl');
    $setting = new admin_setting_configcheckbox($name, $title, '', 0);
    $temp->add($setting);

    $name = 'theme_mb2nl/scrollspeed';
    $title = get_string('scrollspeed', 'theme_mb2nl');
    $setting = new admin_setting_configtext($name, $title, '', 400);
    $temp->add($setting);

    $setting = new admin_setting_configmb2end('theme_mb2nl/endscrolltt');
    $temp->add($setting);

    $setting = new admin_setting_configmb2start2('theme_mb2nl/startsitemenu', get_string('quicklinks', 'theme_mb2nl'));
    $temp->add($setting);

    $name = 'theme_mb2nl/excludedlinks';
    $title = get_string('excludedlinks', 'theme_mb2nl');
    $setting = new admin_setting_configtextarea($name, $title, get_string('excludedlinksdesc', 'theme_mb2nl'),
    'addcourse,addcategory,editcategory');
    $temp->add($setting);

    $name = 'theme_mb2nl/customsitemnuitems';
    $title = get_string('customquicklinkitems', 'theme_mb2nl');
    $setting = new admin_setting_configtextarea($name, $title, get_string('customquicklinkitemsdesc', 'theme_mb2nl'), '');
    $temp->add($setting);

    $setting = new admin_setting_configmb2end('theme_mb2nl/endsitemenu');
    $temp->add($setting);

    $setting = new admin_setting_configmb2start2('theme_mb2nl/startganalitycs', get_string('ganatitle', 'theme_mb2nl'));
    $temp->add($setting);

    $name = 'theme_mb2nl/ganaidga4';
    $title = get_string('ganaidga4', 'theme_mb2nl');
    $setting = new admin_setting_configtext($name, $title, $title = get_string('ganaiddesc_ga4', 'theme_mb2nl'), '');
    $temp->add($setting);

    $name = 'theme_mb2nl/ganaid';
    $title = get_string('ganaid', 'theme_mb2nl');
    $setting = new admin_setting_configtext($name, $title, $title = get_string('ganaiddesc', 'theme_mb2nl'), '');
    $temp->add($setting);

    $name = 'theme_mb2nl/ganaasync';
    $title = get_string('ganaasync', 'theme_mb2nl');
    $setting = new admin_setting_configcheckbox($name, $title, '', 0);
    $temp->add($setting);

    $setting = new admin_setting_configmb2end('theme_mb2nl/endganalitycs');
    $temp->add($setting);

    $nlsettings->add($temp);
