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

$temp = new admin_settingpage('theme_mb2nlchild_settingssocial',  get_string('settingssocial', 'theme_mb2nl'));

$setting = new admin_setting_configmb2start2('theme_mb2nlchild/socialstart1', get_string('options', 'theme_mb2nl'));
$temp->add($setting);

$name = 'theme_mb2nlchild/socialheader';
$title = get_string('socialheader', 'theme_mb2nl');
$desc = '';
$setting = new admin_setting_configcheckbox($name, $title, $desc, 0);
$temp->add($setting);

$name = 'theme_mb2nlchild/socialfooter';
$title = get_string('socialfooter', 'theme_mb2nl');
$desc = '';
$setting = new admin_setting_configcheckbox($name, $title, $desc, 0);
$temp->add($setting);

$name = 'theme_mb2nlchild/socialmargin';
$title = get_string('socialmargin', 'theme_mb2nl');
$setting = new admin_setting_configtext($name, $title, '', 31);
$temp->add($setting);

$name = 'theme_mb2nlchild/sociallinknw';
$title = get_string('sociallinknw', 'theme_mb2nl');
$desc = '';
$setting = new admin_setting_configcheckbox($name, $title, $desc, 0);
$temp->add($setting);

$name = 'theme_mb2nlchild/socialtt';
$title = get_string('socialtt', 'theme_mb2nl');
$desc = '';
$setting = new admin_setting_configcheckbox($name, $title, $desc, 1);
$temp->add($setting);

$setting = new admin_setting_configmb2end('theme_mb2nlchild/socialend');
$temp->add($setting);

$socialnamechoices = [
    '' => get_string('none', 'theme_mb2nl'),
    'android,Android' => 'Android',
    'apple,App Store' => 'App Store',
    'dribbble,Dribbble' => 'Dribbble',
    'dropbox,Dropbox' => 'Dropbox',
    'envelope,Email' => 'Email',
    'facebook,Facebook' => 'Facebook',
    'flickr,Flickr' => 'Flickr',
    'github,Github' => 'Github',
    'google-plus,Google Plus' => 'Google Plus',
    'instagram,Instagram' => 'Instagram',
    'linkedin,Linkedin' => 'Linkedin',
    'pinterest,Pinterest' => 'Pinterest',
    'rss,Rss' => 'Rss',
    'skype,Skype' => 'Skype',
    'spotify,Spotify' => 'Spotify',
    'telegram,Telegram' => 'Telegram',
    'tumblr,Tumblr' => 'Tumblr',
    'twitter,Twitter' => 'Twitter',
    'globe,Web' => 'Web',
    'tiktok,TikTok' => 'TikTok',
    'whatsapp,WhatsApp' => 'WhatsApp',
    'vimeo,Vimeo' => 'Vimeo',
    'vk,VKontakte' => 'VKontakte',
    'xing,Xing' => 'Xing',
    'youtube-play,Youtube' => 'Youtube',
];

$setting = new admin_setting_configmb2start2('theme_mb2nlchild/socialliststart', get_string('socillist', 'theme_mb2nl'));
$temp->add($setting);

for ($i = 1; $i <= 7; $i++) {
    $name = 'theme_mb2nlchild/socialname' . $i;
    $title = get_string('name', 'theme_mb2nl') . ' #' . $i;
    $desc = '';
    $setting = new admin_setting_configselect($name, $title, $desc, '', $socialnamechoices);
    $temp->add($setting);

    $name = 'theme_mb2nlchild/sociallink' . $i;
    $title = get_string('link', 'theme_mb2nl') . ' #' . $i;
    $desc = '';
    $setting = new admin_setting_configtext($name, $title, $desc, '');
    $temp->add($setting);

    if ($i < 7) {
        $setting = new admin_setting_configmb2spacer('theme_mb2nlchild/socialspacer' . $i);
        $setting->set_updatedcallback('theme_reset_all_caches');
        $temp->add($setting);
    }
}

$setting = new admin_setting_configmb2end('theme_mb2nlchild/sociallistend');
$temp->add($setting);

$setting = new admin_setting_configmb2start2('theme_mb2nlchild/shareiconstart', get_string('shareicons', 'theme_mb2nl'));
$temp->add($setting);

$name = 'theme_mb2nlchild/sharetwitter';
$title = get_string('sharetwitter', 'theme_mb2nl');
$setting = new admin_setting_configcheckbox($name, $title, '', 1);
$temp->add($setting);

$name = 'theme_mb2nlchild/sharefacebook';
$title = get_string('sharefacebook', 'theme_mb2nl');
$setting = new admin_setting_configcheckbox($name, $title, '', 1);
$temp->add($setting);

$name = 'theme_mb2nlchild/sharelinkedin';
$title = get_string('sharelinkedin', 'theme_mb2nl');
$setting = new admin_setting_configcheckbox($name, $title, '', 1);
$temp->add($setting);

$name = 'theme_mb2nlchild/shareemail';
$title = get_string('shareemail', 'theme_mb2nl');
$setting = new admin_setting_configcheckbox($name, $title, '', 1);
$temp->add($setting);

$name = 'theme_mb2nlchild/shareurl';
$title = get_string('shareurl', 'theme_mb2nl');
$setting = new admin_setting_configcheckbox($name, $title, '', 1);
$temp->add($setting);

$setting = new admin_setting_configmb2end('theme_mb2nlchild/shareiconsend');
$temp->add($setting);

$nlsettings->add($temp);
