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

global $OUTPUT, $PAGE;

if (theme_mb2nl_isblock('adminblock') && is_siteadmin()) {

    if ($CFG->version < 2023100900) { // Up to Moodle 4.3.
        user_preference_allow_ajax_update('mb2_adminregion', PARAM_INT);
    }

    $cls = 'pb-3';
    $rcls = '';

    if (theme_mb2nl_full_screen_module()) {
        $cls = 'pt-3';
        $rcls = ' mt-3';
    }

    $pref = theme_mb2nl_user_preference('mb2_adminregion', 0);
    $activecls = $pref || $PAGE->user_is_editing() ? ' open' : ' close';
    $PAGE->requires->data_for_js('admbltgltext', [
        'opentxt' => get_string('showadminblocks', 'theme_mb2nl'),
        'hidetxt' => get_string('hideadminblocks', 'theme_mb2nl'),
        'btnhtml' => theme_mb2nl_adminblock_toggle($cls),
    ]);

    $PAGE->requires->js_call_amd('theme_mb2nl/adminregion', 'toggleBtn');

    $html = '';

    $html .= '<div id="adminblock-region" class="admin-region order-3' . $rcls . $activecls . '">';
    $html .= '<div class="container-fluid">';
    $html .= '<div class="alert alert-info">' . get_string('adminblockinfo', 'theme_mb2nl') . '</div>';
    $html .= $OUTPUT->blocks('adminblock', theme_mb2nl_block_cls('adminblock'));
    $html .= '</div>';
    $html .= '</div>';

    echo $html;

}
