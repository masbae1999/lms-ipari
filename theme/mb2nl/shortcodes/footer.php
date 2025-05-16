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
 */

defined('MOODLE_INTERNAL') || die();

mb2_add_shortcode('mb2footer', 'mb2_shortcode_mb2footer');

/**
 *
 * Method to define shortcode
 *
 * @return HTML
 */
function mb2_shortcode_mb2footer($atts, $content = null) {

    global $PAGE, $CFG, $DB;

    $atts2 = [
        'footerid' => '',
    ];

    $a = mb2_shortcode_atts($atts2, $atts);

    $output = '';

    if (!theme_mb2nl_check_builder()) {
        return;
    }

    // If user editing page we don't want to show builder code.
    if ($PAGE->user_is_editing()) {
        return is_siteadmin() ? '<span class="d-block align-center pt-5 pb-5">[mb2footer footerid="' .
        $a['footerid'] . '"]</span>' : '';
    }

    // Get page api file.
    if (!class_exists('Mb2builderFootersApi')) {
        require($CFG->dirroot . '/local/mb2builder/classes/footers_api.php');
    }

    if (!Mb2builderFootersApi::is_footerid($a['footerid'])) {
        return '<span class="d-block align-center pt-5 pb-5">' . get_string('footernoexists', 'local_mb2builder',
        ['id' => $a['footerid']]) . '</span>';
    }

    if (has_capability('local/mb2builder:managefooters', context_system::instance()) && !$PAGE->user_is_editing()) {
        $linkparams = [
            'itemid' => $a['footerid'],
            'returnurl' => $PAGE->url->out_as_local_url(),
        ];
        $output .= '<div class="builder-links">';
        $output .= '<a class="mb2pb-editfooter" href="' . new moodle_url('/local/mb2builder/edit-footer.php', $linkparams) . '">';
        $output .= get_string('editfooter', 'local_mb2builder');
        $output .= '</a>';
        $output .= '</div>';
    }

    // Get cached footer content.
    $cache = cache::make('local_mb2builder', 'footerdata');

    if (!$cache->get($a['footerid'])) {
        // Get footer record and set cache content.
        $footer = Mb2builderFootersApi::get_record($a['footerid']);
        $footer->democontent = ''; // We don't need demo content in the cache file.
        $cache->set($footer->id, $footer);
    }

    $output .= theme_mb2nl_builder_content(json_decode($cache->get($a['footerid'])->content), true);

    return $output;

}
