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

$temp = new admin_settingpage('theme_mb2nlchild_settingsfeatures',  get_string('settingsfeatures', 'theme_mb2nl'));
$yesnooptions = ['1' => get_string('yes', 'theme_mb2nl'), '0' => get_string('no', 'theme_mb2nl')];

    $bgpredefinedopt = [
        '' => get_string('none', 'theme_mb2nl'),
        'strip1' => get_string('strip1', 'theme_mb2nl'),
        'strip2' => get_string('strip2', 'theme_mb2nl'),
    ];

    $setting = new admin_setting_configmb2start2('theme_mb2nlchild/startaccessibility', get_string('accessibility', 'theme_mb2nl'));
    $temp->add($setting);

    $name = 'theme_mb2nlchild/acsboptions';
    $title = get_string('acsboptions', 'theme_mb2nl');
    $setting = new admin_setting_configcheckbox($name, $title, '', 1);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $temp->add($setting);

    $name = 'theme_mb2nlchild/acsbalticon';
    $title = get_string('acsbalticon', 'theme_mb2nl');
    $setting = new admin_setting_configcheckbox($name, $title, '', 0);
    $temp->add($setting);

    $name = 'theme_mb2nlchild/dyslexic';
    $title = get_string('dyslexic', 'theme_mb2nl');
    $setting = new admin_setting_configcheckbox($name, $title, '', 1);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $temp->add($setting);

    $temp->add(new admin_setting_configmb2spacer('theme_mb2nlchild/acsbspacer1'));

    $name = 'theme_mb2nlchild/acsb_color1';
    $title = get_string('colorn', 'theme_mb2nl', '1');
    $setting = new admin_setting_configmb2color($name, $title, '', '#033860');
    $setting->set_updatedcallback('theme_reset_all_caches');
    $temp->add($setting);

    $name = 'theme_mb2nlchild/acsb_color2';
    $title = get_string('colorn', 'theme_mb2nl', '2');
    $setting = new admin_setting_configmb2color($name, $title, '', '#004ba8');
    $setting->set_updatedcallback('theme_reset_all_caches');
    $temp->add($setting);

    $name = 'theme_mb2nlchild/acsb_color3';
    $title = get_string('colorn', 'theme_mb2nl', '3');
    $setting = new admin_setting_configmb2color($name, $title, '', '#d9ecf2');
    $setting->set_updatedcallback('theme_reset_all_caches');
    $temp->add($setting);

    $setting = new admin_setting_configmb2end('theme_mb2nlchild/endaccessibility');
    $temp->add($setting);

    $setting = new admin_setting_configmb2start2('theme_mb2nlchild/startblog', get_string('blogsettings', 'theme_mb2nl'));
    $temp->add($setting);

    $name = 'theme_mb2nlchild/blogplaceholder';
    $title = get_string('blogplaceholder', 'theme_mb2nl');
    $setting = new admin_setting_configstoredfile($name, $title, '', 'blogplaceholder');
    $temp->add($setting);

    $temp->add(new admin_setting_configmb2spacer('theme_mb2nlchild/blogspacer1'));
    $temp->add(new admin_setting_configmb2heading('theme_mb2nlchild/blogheading1', get_string('blogpage', 'theme_mb2nl')));

    $name = 'theme_mb2nlchild/bloglayout';
    $title = get_string('layout', 'theme_mb2nl');
    $setting = new admin_setting_configselect($name, $title, '', 'col3', [
        'list' => get_string('layoutlist', 'theme_mb2nl'),
        'col2' => get_string('xcolumns', 'theme_mb2nl', 2),
        'col3' => get_string('xcolumns', 'theme_mb2nl', 3),
    ]);
    $temp->add($setting);

    $name = 'theme_mb2nlchild/blogsidebar';
    $title = get_string('sidebar', 'theme_mb2nl');
    $setting = new admin_setting_configcheckbox($name, $title, '', 0);
    $temp->add($setting);

    $name = 'theme_mb2nlchild/blogdateformat';
    $title = get_string('dateformat', 'theme_mb2nl');
    $setting = new admin_setting_configtext($name, $title, '', 'M d, Y');
    $temp->add($setting);

    $name = 'theme_mb2nlchild/blogpageintro';
    $title = get_string('blogintro', 'theme_mb2nl');
    $setting = new admin_setting_configcheckbox($name, $title, '', 1);
    $temp->add($setting);

    $name = 'theme_mb2nlchild/blogmore';
    $title = get_string('blogmore', 'theme_mb2nl');
    $setting = new admin_setting_configcheckbox($name, $title, '', 1);
    $temp->add($setting);

    $temp->add(new admin_setting_configmb2spacer('theme_mb2nlchild/blogspacer2'));
    $temp->add(new admin_setting_configmb2heading('theme_mb2nlchild/blogheading2', get_string('blogsinglepage', 'theme_mb2nl')));

    $name = 'theme_mb2nlchild/blogsinglesidebar';
    $title = get_string('sidebar', 'theme_mb2nl');
    $setting = new admin_setting_configcheckbox($name, $title, '', 0);
    $temp->add($setting);

    $name = 'theme_mb2nlchild/blogsingledateformat';
    $title = get_string('dateformat', 'theme_mb2nl');
    $setting = new admin_setting_configtext($name, $title, '', 'M d, Y, H:i A');
    $temp->add($setting);

    $name = 'theme_mb2nlchild/blogfeaturedmedia';
    $title = get_string('blogfeaturedmedia', 'theme_mb2nl');
    $setting = new admin_setting_configcheckbox($name, $title, '', 1);
    $temp->add($setting);

    $name = 'theme_mb2nlchild/blogsingleintro';
    $title = get_string('blogintro', 'theme_mb2nl');
    $setting = new admin_setting_configcheckbox($name, $title, '', 1);
    $temp->add($setting);

    $name = 'theme_mb2nlchild/blogmodify';
    $title = get_string('blogmodify', 'theme_mb2nl');
    $setting = new admin_setting_configcheckbox($name, $title, '', 0);
    $temp->add($setting);

    $name = 'theme_mb2nlchild/blogshareicons';
    $title = get_string('shareicons', 'theme_mb2nl');
    $setting = new admin_setting_configcheckbox($name, $title, '', 1);
    $temp->add($setting);

    $setting = new admin_setting_configmb2end('theme_mb2nlchild/endblog');
    $temp->add($setting);

    $setting = new admin_setting_configmb2start2('theme_mb2nlchild/startbookmarks', get_string('bookmarks', 'theme_mb2nl'));
    $temp->add($setting);

    $name = 'theme_mb2nlchild/bookmarks';
    $title = get_string('bookmarks', 'theme_mb2nl');
    $setting = new admin_setting_configcheckbox($name, $title, '', 0);
    $temp->add($setting);

    $name = 'theme_mb2nlchild/bookmarkslimit';
    $title = get_string('bookmarkslimit', 'theme_mb2nl');
    $setting = new admin_setting_configtext($name, $title, '', 15);
    $temp->add($setting);

    $setting = new admin_setting_configmb2end('theme_mb2nlchild/endbookmarks');
    $temp->add($setting);

    $setting = new admin_setting_configmb2start2('theme_mb2nlchild/startcoursepanel', get_string('coursepanel', 'theme_mb2nl'));
    $temp->add($setting);

    $name = 'theme_mb2nlchild/coursepanel';
    $title = get_string('coursepanel', 'theme_mb2nl');
    $setting = new admin_setting_configcheckbox($name, $title, '', 1);
    $temp->add($setting);

    $setting = new admin_setting_configmb2end('theme_mb2nlchild/endcoursepanel');
    $temp->add($setting);

    $setting = new admin_setting_configmb2start2('theme_mb2nlchild/startevents', get_string('events', 'calendar'));
    $temp->add($setting);

    $name = 'theme_mb2nlchild/eventsplaceholder';
    $title = get_string('eventsplaceholder', 'theme_mb2nl');
    $setting = new admin_setting_configstoredfile($name, $title, '', 'eventsplaceholder');
    $temp->add($setting);

    $setting = new admin_setting_configmb2end('theme_mb2nlchild/endevent');
    $temp->add($setting);

    $setting = new admin_setting_configmb2start2('theme_mb2nlchild/startdashboard', get_string('myhome'));
    $temp->add($setting);

    $rolestoselect = [];

    if (function_exists('role_fix_names') && function_exists('get_all_roles')) {
        foreach (role_fix_names(get_all_roles()) as $role) {
            $rolestoselect[$role->id] = $role->localname;
        }
    }

    $name = 'theme_mb2nlchild/d2cols';
    $title = get_string('c2cols', 'theme_mb2nl');
    $setting = new admin_setting_configcheckbox($name, $title, '', 1);
    $temp->add($setting);

    $temp->add(new admin_setting_configmb2spacer('theme_mb2nlchild/dshbspacer1'));
    $temp->add(new admin_setting_configmb2heading('theme_mb2nlchild/dshbheading1', get_string('customdshbheading', 'theme_mb2nl')));

    $name = 'theme_mb2nlchild/dashboard';
    $title = get_string('customdshbheading', 'theme_mb2nl');
    $setting = new admin_setting_configcheckbox($name, $title, '', 0);
    $temp->add($setting);

    $name = 'theme_mb2nlchild/dshbdsbl';
    $title = get_string('dshbdsbl', 'theme_mb2nl');
    $setting = new admin_setting_configmultiselect($name, $title, '', [], $rolestoselect);
    $temp->add($setting);

    $name = 'theme_mb2nlchild/activeuserstime';
    $title = get_string('activeuserstime', 'theme_mb2nl');
    $setting = new admin_setting_configtext($name, $title, '', 6);
    $temp->add($setting);

    $setting = new admin_setting_configmb2end('theme_mb2nlchild/enddashboard');
    $temp->add($setting);

    $setting = new admin_setting_configmb2start2('theme_mb2nlchild/startlang', get_string('language', 'theme_mb2nl'));
    $temp->add($setting);

    $name = 'theme_mb2nlchild/langmenu';
    $title = get_string('langmenu', 'theme_mb2nl');
    $setting = new admin_setting_configcheckbox($name, $title, '', 0);
    $temp->add($setting);

    $name = 'theme_mb2nlchild/langfooter';
    $title = get_string('langfooter', 'theme_mb2nl');
    $setting = new admin_setting_configcheckbox($name, $title, '', 1);
    $temp->add($setting);

    $name = 'theme_mb2nlchild/langimg';
    $title = get_string('langimg', 'theme_mb2nl');
    $setting = new admin_setting_configcheckbox($name, $title, '', 0);
    $temp->add($setting);

    $name = 'theme_mb2nlchild/langflags';
    $title = get_string('langflags', 'theme_mb2nl');
    $desc = '';
    $setting = new admin_setting_configstoredfile($name, $title, $desc, 'langflags', 0, ['accepted_types' => ['.png', '.jpg'],
    'maxfiles' => -1]);
    $setting->set_updatedcallback('theme_cache_features_purge');
    $temp->add($setting);

    $setting = new admin_setting_configmb2end('theme_mb2nlchild/endlang');
    $temp->add($setting);

    $setting = new admin_setting_configmb2start2('theme_mb2nlchild/startlogin', get_string('cloginpage', 'theme_mb2nl'));
    $temp->add($setting);

    $name = 'theme_mb2nlchild/cloginpage';
    $title = get_string('cloginpage', 'theme_mb2nl');
    $setting = new admin_setting_configselect($name, $title, '', 1, [
        0 => get_string('none', 'theme_mb2nl'),
        1 => get_string('layoutn', 'theme_mb2nl', ['layout' => 1]),
        2 => get_string('layoutn', 'theme_mb2nl', ['layout' => 2]),
        3 => get_string('layoutn', 'theme_mb2nl', ['layout' => 3]),
    ]);
    $temp->add($setting);

    $name = 'theme_mb2nlchild/loginbgcolor';
    $title = get_string('bgcolor', 'theme_mb2nl');
    $setting = new admin_setting_configmb2color($name, $title, '', '');
    $setting->set_updatedcallback('theme_reset_all_caches');
    $temp->add($setting);

    $name = 'theme_mb2nlchild/loginbgpre';
    $title = get_string('pbgpre', 'theme_mb2nl');
    $setting = new admin_setting_configselect($name, $title, '', '', $bgpredefinedopt);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $temp->add($setting);

    $name = 'theme_mb2nlchild/loginbgimage';
    $title = get_string('bgimage', 'theme_mb2nl');
    $setting = new admin_setting_configstoredfile($name, $title, get_string('pbgdesc', 'theme_mb2nl'), 'loginbgimage');
    $setting->set_updatedcallback('theme_reset_all_caches');
    $temp->add($setting);

    $setting = new admin_setting_configmb2end('theme_mb2nlchild/endlogin');
    $setting->set_updatedcallback('theme_reset_all_caches');
    $temp->add($setting);

    $setting = new admin_setting_configmb2start2('theme_mb2nlchild/startloading', get_string('loadingscreen', 'theme_mb2nl'));
    $setting->set_updatedcallback('theme_reset_all_caches');
    $temp->add($setting);

    $name = 'theme_mb2nlchild/loadingscr';
    $title = get_string('loadingscreen', 'theme_mb2nl');
    $setting = new admin_setting_configcheckbox($name, $title, get_string('loadingscrdesc', 'theme_mb2nl'), 0);
    $temp->add($setting);

    $name = 'theme_mb2nlchild/loadinghide';
    $title = get_string('loadinghide', 'theme_mb2nl');
    $setting = new admin_setting_configtext($name, $title, '', 1000);
    $temp->add($setting);

    $name = 'theme_mb2nlchild/spinnerw';
    $title = get_string('spinnerw', 'theme_mb2nl');
    $setting = new admin_setting_configtext($name, $title, '', 50);
    $temp->add($setting);

    $name = 'theme_mb2nlchild/lbgcolor';
    $title = get_string('bgcolor', 'theme_mb2nl');
    $setting = new admin_setting_configmb2color($name, $title, '', '');
    $setting->set_updatedcallback('theme_reset_all_caches');
    $temp->add($setting);

    $name = 'theme_mb2nlchild/loadinglogo';
    $title = get_string('logoimg', 'theme_mb2nl');
    $setting = new admin_setting_configstoredfile($name, $title, get_string('loadinglogodesc', 'theme_mb2nl'), 'loadinglogo');
    $temp->add($setting);

    $setting = new admin_setting_configmb2end('theme_mb2nlchild/endloading');
    $setting->set_updatedcallback('theme_reset_all_caches');
    $temp->add($setting);

    $setting = new admin_setting_configmb2start2('theme_mb2nlchild/startloginform', get_string('loginsearchform', 'theme_mb2nl'));
    $temp->add($setting);

    $name = 'theme_mb2nlchild/modaltools';
    $title = get_string('modaltools', 'theme_mb2nl');
    $setting = new admin_setting_configcheckbox($name, $title, '', 1);
    $temp->add($setting);

    $name = 'theme_mb2nlchild/loginlinktopage';
    $title = get_string('loginlinktopage', 'theme_mb2nl');
    $setting = new admin_setting_configcheckbox($name, $title, '', 0);
    $temp->add($setting);

    $layoutarr = [
        '1' => get_string('loginpage', 'theme_mb2nl'),
        '2' => get_string('forgotpage', 'theme_mb2nl'),
    ];

    $name = 'theme_mb2nlchild/loginsearchspacer1';
    $setting = new admin_setting_configmb2spacer($name);
    $temp->add($setting);

    $name = 'theme_mb2nlchild/signuppage';
    $title = get_string('signuppage', 'theme_mb2nl');
    $setting = new admin_setting_configtext($name, $title, '', '');
    $temp->add($setting);

    $name = 'theme_mb2nlchild/loginsearchspacer2';
    $setting = new admin_setting_configmb2spacer($name);
    $temp->add($setting);

    $name = 'theme_mb2nlchild/searchlinks';
    $title = get_string('searchlinks', 'theme_mb2nl');
    $setting = new admin_setting_configtextarea($name, $title, get_string('searchlinksdesc', 'theme_mb2nl'), '');
    $temp->add($setting);

    $setting = new admin_setting_configmb2end('theme_mb2nlchild/endloginform');
    $setting->set_updatedcallback('theme_reset_all_caches');
    $temp->add($setting);

    $setting = new admin_setting_configmb2start2('theme_mb2nlchild/startscrolltt', get_string('scrolltt', 'theme_mb2nl'));
    $temp->add($setting);

    $name = 'theme_mb2nlchild/scrolltt';
    $title = get_string('scrolltt', 'theme_mb2nl');
    $setting = new admin_setting_configcheckbox($name, $title, '', 0);
    $temp->add($setting);

    $name = 'theme_mb2nlchild/scrollspeed';
    $title = get_string('scrollspeed', 'theme_mb2nl');
    $setting = new admin_setting_configtext($name, $title, '', 400);
    $temp->add($setting);

    $setting = new admin_setting_configmb2end('theme_mb2nlchild/endscrolltt');
    $temp->add($setting);

    $setting = new admin_setting_configmb2start2('theme_mb2nlchild/startsitemenu', get_string('quicklinks', 'theme_mb2nl'));
    $temp->add($setting);

    $name = 'theme_mb2nlchild/excludedlinks';
    $title = get_string('excludedlinks', 'theme_mb2nl');
    $setting = new admin_setting_configtextarea($name, $title, get_string('excludedlinksdesc', 'theme_mb2nl'),
    'addcourse,addcategory,editcategory');
    $temp->add($setting);

    $name = 'theme_mb2nlchild/customsitemnuitems';
    $title = get_string('customquicklinkitems', 'theme_mb2nl');
    $setting = new admin_setting_configtextarea($name, $title, get_string('customquicklinkitemsdesc', 'theme_mb2nl'), '');
    $temp->add($setting);

    $setting = new admin_setting_configmb2end('theme_mb2nlchild/endsitemenu');
    $temp->add($setting);

    $setting = new admin_setting_configmb2start2('theme_mb2nlchild/startganalitycs', get_string('ganatitle', 'theme_mb2nl'));
    $temp->add($setting);

    $name = 'theme_mb2nlchild/ganaidga4';
    $title = get_string('ganaidga4', 'theme_mb2nl');
    $setting = new admin_setting_configtext($name, $title, $title = get_string('ganaiddesc_ga4', 'theme_mb2nl'), '');
    $temp->add($setting);

    $name = 'theme_mb2nlchild/ganaid';
    $title = get_string('ganaid', 'theme_mb2nl');
    $setting = new admin_setting_configtext($name, $title, $title = get_string('ganaiddesc', 'theme_mb2nl'), '');
    $temp->add($setting);

    $name = 'theme_mb2nlchild/ganaasync';
    $title = get_string('ganaasync', 'theme_mb2nl');
    $setting = new admin_setting_configcheckbox($name, $title, '', 0);
    $temp->add($setting);

    $setting = new admin_setting_configmb2end('theme_mb2nlchild/endganalitycs');
    $temp->add($setting);

    $nlsettings->add($temp);
