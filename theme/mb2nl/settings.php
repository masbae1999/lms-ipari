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

unset($settings);
$settings = null;

// Reguire heleper class.
require(__DIR__ . '/classes/fields.php');

$ADMIN->add('appearance', new admin_category('theme_mb2nl', get_string('configtitle', 'theme_mb2nl')));
$nlsettings = new theme_mb2nl_admin_settingspage_tabs('themesettingmb2nl', get_string('configtabtitle', 'theme_mb2nl'));

if ($ADMIN->fulltree) {
    require(__DIR__ . '/settings/general.php');
    require(__DIR__ . '/settings/courses.php');
    require(__DIR__ . '/settings/features.php');
    require(__DIR__ . '/settings/fonts.php');
    require(__DIR__ . '/settings/navigation.php');
    require(__DIR__ . '/settings/social.php');
    require(__DIR__ . '/settings/style.php');
    require(__DIR__ . '/settings/typography.php');
}

$ADMIN->add('theme_mb2nl', $nlsettings);
