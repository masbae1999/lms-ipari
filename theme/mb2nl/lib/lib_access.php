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


/**
 *
 * Method to define site access
 *
 */
function theme_mb2nl_site_access($iscourse = null) {

    global $PAGE, $COURSE, $USER;

    $curentcat = optional_param('categoryid', 0, PARAM_INT);

    $access = 'none';
    $courseid = $iscourse ? $iscourse->id : $COURSE->id;
    $catid = $iscourse ? $iscourse->category : $COURSE->category;
    $iscatid = $curentcat ? $curentcat : $catid;
    $iscontext = $catid || $curentcat ? context_coursecat::instance($iscatid) : context_course::instance($courseid);
    $context = context_course::instance($courseid);

    $coursecancreate = has_capability('moodle/course:create', $iscontext);
    $coursecanedit = has_capability('moodle/course:update', $context);
    $hiddenactivities = has_capability('moodle/course:viewhiddenactivities', $context);
    $coursecatcanmanage = has_capability('moodle/category:manage', $iscontext);
    $enrolled = is_enrolled($context, $USER->id, '', true);
    $sitecanconfig = has_capability('moodle/site:config', $context);

    $accessadmin = ($sitecanconfig && $coursecatcanmanage && $coursecanedit && $coursecancreate && $hiddenactivities);
    $accessmanager = ($coursecatcanmanage && $coursecanedit && $coursecancreate && $hiddenactivities);
    $accessteacher = ($hiddenactivities && $coursecanedit);
    $accessnoeditingteacher = ($hiddenactivities && !$coursecanedit);
    $accesscreator = (!$coursecanedit && $coursecancreate);
    $accessstudent = ($enrolled && isloggedin() && !isguestuser() && !$hiddenactivities);
    $accessuser = (isloggedin() && !isguestuser());

    if ($accessadmin) {
        $access = 'admin';
    } else if ($accessmanager) {
        $access = 'manager';
    } else if ($accessteacher) {
        $access = 'editingteacher';
    } else if ($accessnoeditingteacher) {
        $access = 'teacher';
    } else if ($accesscreator) {
        $access = 'coursecreator';
    } else if ($accessstudent) {
        $access = 'student';
    } else if ($accessuser) {
        $access = 'user';
    }

    return $access;

}




/**
 *
 * Method to define skiplinks
 *
 */
function theme_mb2nl_skiplinks() {
    global $PAGE, $COURSE;

    if (preg_match('@admin-local-mb2builder@', $PAGE->pagetype) || theme_mb2nl_full_screen_module()) {
        return;
    }

    $cantsee = ['none', 'user'];
    $courseaccess = theme_mb2nl_site_access();
    $canmanage = ['admin', 'manager', 'editingteacher', 'teacher'];
    $coursemanagestring = in_array($courseaccess, $canmanage) ? get_string('coursemanagement', 'theme_mb2nl') :
    get_string('coursedashboard', 'theme_mb2nl');
    $logintext = (isloggedin() && ! isguestuser()) ? get_string('skiptoprofile', 'theme_mb2nl') :
    get_string('skiptologin', 'theme_mb2nl');

    $PAGE->requires->skip_link_to('main-navigation', get_string('skiptonavigation', 'theme_mb2nl'));
    $PAGE->requires->skip_link_to('themeskipto-mobilenav', get_string('skiptonavigation', 'theme_mb2nl'));
    $PAGE->requires->skip_link_to('themeskipto-search', get_string('skiptosearch', 'theme_mb2nl'));
    $PAGE->requires->skip_link_to('themeskipto-login', $logintext);
    $PAGE->requires->skip_link_to('maincontent', get_string('tocontent', 'access'));

    if (theme_mb2nl_theme_setting($PAGE, 'acsboptions')) {
        $PAGE->requires->skip_link_to('acsb-menu_launcher', get_string('skiptoaccessibilitymenu', 'theme_mb2nl'));
    }

    if (theme_mb2nl_theme_setting($PAGE, 'coursepanel') && theme_mb2nl_is_course() && ! in_array($courseaccess, $cantsee)) {
        $PAGE->requires->skip_link_to('themeskipto-coursepanel', $coursemanagestring);
    }

    $PAGE->requires->skip_link_to('footer', get_string('skiptofooter', 'theme_mb2nl'));

}






/**
 *
 * Method to get accessibility block
 *
 */
function theme_mb2nl_acsb_profiles() {

    return [
        [
            'id' => 'visualimpairment',
            'acsb' => 'readablefont,textsizelarge,highsaturation,bigblackcursor',
            'icon' => 'ri-eye-line',
        ],
        [
            'id' => 'seizureandepileptic',
            'acsb' => 'lowsaturation,stopanimations',
            'icon' => 'ri-flashlight-fill',
        ],
        [
            'id' => 'colorvisiondeficiency',
            'acsb' => 'readablefont,highcontrast,highsaturation',
            'icon' => 'ri-contrast-drop-fill',
        ],
        [
            'id' => 'adhd',
            'acsb' => 'lowsaturation,readingmask,stopanimations',
            'icon' => 'ri-focus-2-fill',
        ],
        [
            'id' => 'dyslexia',
            'acsb' => 'dyslexic,readingguide',
            'icon' => 'ri-font-size',
        ],
        [
            'id' => 'learning',
            'acsb' => 'readablefont,textsizenormal,readingguide',
            'icon' => 'ri-book-read-line',
        ],
    ];

}


/**
 *
 * Method to get accessibility block
 *
 */
function theme_mb2nl_acsb() {
    global $PAGE;

    return [
        ['id' => 'sdiv'],
        [
            'id' => 'title',
            'text' => get_string('contentadjustments', 'theme_mb2nl'),
        ],
        [
            'id' => 'readablefont',
            'icon' => 'ri-font-family',
            'arialabel' => 1,
            'disable' => 'dyslexic',
        ],
        [
            'id' => 'dyslexic',
            'icon' => 'ri-font-size',
            'arialabel' => 1,
            'disable' => 'readablefont',
        ],
        [
            'id' => 'highlighttitles',
            'icon' => 'ri-heading',
            'arialabel' => 1,
        ],
        [
            'id' => 'highlightlinks',
            'icon' => 'ri-link',
            'arialabel' => 1,
        ],
        [
            'id' => 'highlightbuttons',
            'icon' => 'ri-mouse-line',
            'arialabel' => 1,
        ],
        [
            'id' => 'hideimages',
            'icon' => 'ri-image-2-line',
            'arialabel' => 1,
        ],
        [
            'id' => 'tooltips',
            'icon' => 'ri-feedback-line',
            'arialabel' => 1,
        ],
        [
            'id' => 'stopanimations',
            'icon' => 'ri-stop-line',
            'arialabel' => 1,
        ],
        [
            'id' => 'acsbtextsize',
            'icon' => 'ri-font-size-2',
            'items' => [
                [
                    'id' => 'textsizenormal',
                    'label' => '&#43;',
                    'arialabel' => get_string('acsbplus', 'theme_mb2nl', ['adj' => get_string('acsbtextsize', 'theme_mb2nl'),
                    'time' => '1']),
                    'disable' => 'textsizelarge,textsizebig',
                ],
                [
                    'id' => 'textsizelarge',
                    'label' => '&#43; &#43;',
                    'arialabel' => get_string('acsbplus', 'theme_mb2nl', ['adj' => get_string('acsbtextsize', 'theme_mb2nl'),
                    'time' => '2']),
                    'disable' => 'textsizenormal,textsizebig',
                ],
                [
                    'id' => 'textsizebig',
                    'label' => '&#43; &#43; &#43;',
                    'arialabel' => get_string('acsbplus', 'theme_mb2nl', ['adj' => get_string('acsbtextsize', 'theme_mb2nl'),
                    'time' => '3']),
                    'disable' => 'textsizenormal,textsizelarge',
                ],
            ],
        ],
        [
            'id' => 'acsblineheight',
            'icon' => 'ri-line-height',
            'items' => [
                [
                    'id' => 'lineheightnormal',
                    'label' => '&#43;',
                    'arialabel' => get_string('acsbplus', 'theme_mb2nl', ['adj' => get_string('acsblineheight', 'theme_mb2nl'),
                    'time' => '1']),
                    'disable' => 'lineheightlarge,lineheightbig',
                ],
                [
                    'id' => 'lineheightlarge',
                    'label' => '&#43; &#43;',
                    'arialabel' => get_string('acsbplus', 'theme_mb2nl', ['adj' => get_string('acsblineheight', 'theme_mb2nl'),
                    'time' => '2']),
                    'disable' => 'lineheightnormal,lineheightbig',
                ],
                [
                    'id' => 'lineheightbig',
                    'label' => '&#43; &#43; &#43;',
                    'arialabel' => get_string('acsbplus', 'theme_mb2nl', ['adj' => get_string('acsblineheight', 'theme_mb2nl'),
                    'time' => '3']),
                    'disable' => 'lineheightnormal,lineheightlarge',
                ],
            ],
        ],
        [
            'id' => 'textspacing',
            'icon' => 'ri-text-spacing',
            'items' => [
                [
                    'id' => 'textspacingnormal',
                    'label' => '&#43;',
                    'arialabel' => get_string('acsbplus', 'theme_mb2nl', ['adj' => get_string('textspacing', 'theme_mb2nl'),
                    'time' => '1']),
                    'disable' => 'textspacinglarge,textspacingbig',
                ],
                [
                    'id' => 'textspacinglarge',
                    'label' => '&#43; &#43;',
                    'arialabel' => get_string('acsbplus', 'theme_mb2nl', ['adj' => get_string('textspacing', 'theme_mb2nl'),
                    'time' => '2']),
                    'disable' => 'textspacingnormal,textspacingbig',
                ],
                [
                    'id' => 'textspacingbig',
                    'label' => '&#43; &#43; &#43;',
                    'arialabel' => get_string('acsbplus', 'theme_mb2nl', ['adj' => get_string('textspacing', 'theme_mb2nl'),
                    'time' => '3']),
                    'disable' => 'textspacingnormal,textspacinglarge',
                ],
            ],
        ],
        ['id' => 'ediv'],
        ['id' => 'sdiv'],
        [
            'id' => 'title',
            'text' => get_string('coloradjustments', 'theme_mb2nl'),
        ],
        [
            'id' => 'contrastdark',
            'icon' => 'ri-moon-fill',
            'disable' => 'contrastlight,invertcolors,changecolors',
            'arialabel' => 1,
        ],
        [
            'id' => 'contrastlight',
            'icon' => 'ri-sun-fill',
            'disable' => 'contrastdark,invertcolors,changecolors',
            'arialabel' => 1,
        ],
        [
            'id' => 'invertcolors',
            'icon' => 'ri-contrast-line',
            'disable' => 'contrastdark,contrastlight,highcontrast,highsaturation,lowsaturation,monochrome,changecolors',
            'arialabel' => 1,
        ],
        [
            'id' => 'changecolors',
            'icon' => 'ri-exchange-2-line',
            'disable' => 'contrastdark,contrastlight,highcontrast,highsaturation,lowsaturation,monochrome,invertcolors',
            'arialabel' => 1,
        ],
        [
            'id' => 'highcontrast',
            'icon' => 'ri-contrast-fill',
            'disable' => 'invertcolors,changecolors,lowsaturation,monochrome,highsaturation',
            'arialabel' => 1,
        ],
        [
            'id' => 'highsaturation',
            'icon' => 'ri-drop-fill',
            'disable' => 'highcontrast,lowsaturation,monochrome,invertcolors,changecolors',
            'arialabel' => 1,
        ],
        [
            'id' => 'lowsaturation',
            'icon' => 'ri-contrast-drop-2-line',
            'disable' => 'highcontrast,highsaturation,monochrome,invertcolors,changecolors',
            'arialabel' => 1,
        ],
        [
            'id' => 'monochrome',
            'icon' => 'ri-contrast-drop-fill',
            'disable' => 'highcontrast,highsaturation,lowsaturation,invertcolors,changecolors',
            'arialabel' => 1,
        ],
        ['id' => 'ediv'],
        ['id' => 'sdiv'],
        [
            'id' => 'title',
            'text' => get_string('orientationadjustments', 'theme_mb2nl'),
        ],
        [
            'id' => 'readingguide',
            'icon' => 'ri-subtract-fill',
            'arialabel' => 1,
        ],
        [
            'id' => 'readingmask',
            'icon' => 'ri-send-backward',
            'arialabel' => 1,
        ],
        [
            'id' => 'bigblackcursor',
            'disable' => 'bigwhitecursor',
            'icon' => 'ri-cursor-fill',
            'arialabel' => 1,
        ],
        [
            'id' => 'bigwhitecursor',
            'disable' => 'bigblackcursor',
            'icon' => 'ri-cursor-line',
            'arialabel' => 1,
        ],
        ['id' => 'ediv'],
    ];

}






/**
 *
 * Method to get accessibility block
 *
 */
function theme_mb2nl_acsb_block() {
    global $CFG, $PAGE;

    if (! theme_mb2nl_theme_setting($PAGE, 'acsboptions')) {
        return;
    }

    $output = '';
    $items = theme_mb2nl_acsb();
    $profiles = theme_mb2nl_acsb_profiles();
    $svg = theme_mb2nl_svg();
    $si = 0;
    $btnicon = theme_mb2nl_theme_setting($PAGE, 'acsbalticon') ? $svg['accessible-icon'] : $svg['universal-access'];

    // Set ajax parameter.
    if ($CFG->version < 2023100900) { // Up to Moodle 4.3.
        user_preference_allow_ajax_update('acsb_trigger', PARAM_INT);
    }

    $triggercls = theme_mb2nl_user_preference('acsb_trigger', 0, PARAM_INT) ? ' active' : '';
    $output .= '<a class="sr-only sr-only-focusable" href="#skip_acsb-menu">' . get_string('skipacsb', 'theme_mb2nl') . '</a>';
    $output .= '<button id="acsb-menu_launcher" type="button" class="acsb-trigger' . $triggercls . '" aria-label="' .
    get_string('acsboptions', 'theme_mb2nl') . '">';
    $output .= '<span class="acsb-icon-main">' . $btnicon . '</span>';
    $output .= '<span class="acsb-icon-check">' . $svg['circle-check'] . '</span>';
    $output .= '</button>';

    $output .= '<div id="acsb-menu" class="acsb-block">';

    $output .= '<div class="acsb-block-header' . theme_mb2nl_bsfcls(1, '', 'between', 'center') . '">';
    $output .= '<button type="button" class="themereset acsb-reset' .
    theme_mb2nl_bsfcls(2, '', 'center', 'center') . '" aria-label="' . get_string('resetsettings', 'theme_mb2nl') . '">';
    $output .= '<span class="acsb-btn-icon"><i class="ri-loop-left-line"></i></span>';
    $output .= '<span class="acsb-btn-text">' . get_string('resetsettings', 'theme_mb2nl') . '</span>';
    $output .= '</button>';
    $output .= '<div class="acsb-block-close">';
    $output .= '<button type="button" class="themereset acsb-close' .
    theme_mb2nl_bsfcls(2, '', 'center', 'center') . '" aria-label="' . get_string('closebuttontitle') . '">';
    $output .= '<span class="acsb-btn-icon"><i class="ri-close-fill"></i></span>';
    $output .= '<span class="acsb-btn-text">' . get_string('closebuttontitle') . '</span>';
    $output .= '</button>';
    $output .= '</div>'; // ...acsb-block-close
    $output .= '</div>'; // ...acsb-block-header

    $output .= '<div class="acsb-block-inner' . theme_mb2nl_bsfcls(1, 'column', '', '') . '">';

    $output .= '<div class="acsb-section acsb-profiles">';

    $output .= '<button type="button" class="themereset acsb-title' .
    theme_mb2nl_bsfcls(1, 'row', 'between', 'center') . '" aria-controls="acsb_section_profiles" aria-expanded="true" aria-label="'.
    get_string('acsbprofiles', 'theme_mb2nl') . '">';
    $output .= '<span class="btntext">' . get_string('acsbprofiles', 'theme_mb2nl') . '</span>';
    $output .= '<span class="btnicon' . theme_mb2nl_bsfcls(2, '', 'center', 'center') . '">';
    $output .= '<i class="ri-add-fill"></i>';
    $output .= '<i class="ri-subtract-fill"></i>';
    $output .= '</span>';
    $output .= '</button>';
    $output .= '<div id="acsb_section_profiles" class="acsb-section-content" >';

    foreach ($profiles as $profile) {

        if ($profile['id'] === 'dyslexia' && ! theme_mb2nl_theme_setting($PAGE, 'dyslexic')) {
            continue;
        }

        // Set ajax parameter.
        if ($CFG->version < 2023100900) { // Up to Moodle 4.3.
            user_preference_allow_ajax_update('acsb_' . $profile['id'], PARAM_INT);
        }

        $enabled = theme_mb2nl_user_preference('acsb_' . $profile['id'], 0, PARAM_INT);
        $cls = $enabled ? ' active' : '';
        $ariachecked = $enabled ? ' aria-checked="true"' : ' aria-checked="false"';

        $output .= '<button type="button" class="acsb-profile-item acsb-btn-css themereset' . $cls .
        theme_mb2nl_bsfcls(1, 'row', '', 'center') . '" data-id="' . $profile['id'] . '" data-acsb="' .
        $profile['acsb'] . '" aria-label="' . get_string($profile['id'], 'theme_mb2nl') . '"' . $ariachecked . ' role="checkbox">';
        $output .= '<span class="acsb-profile-icon"><i class="' . $profile['icon'] . '"></i></span>';
        $output .= '<span class="acsb-profile-title">' . get_string($profile['id'], 'theme_mb2nl') . '</span>';
        $output .= '</button>';
    }

    $output .= '</div>'; // ...acsb-section-content
    $output .= '</div>'; // ...acsb-profiles

    foreach ($items as $item) {
        $si++;

        if ($item['id'] === 'dyslexic' && ! theme_mb2nl_theme_setting($PAGE, 'dyslexic')) {
            continue;
        }

        if ($item['id'] === 'title') {
            $output .= '<button type="button" class="themereset acsb-title' .
            theme_mb2nl_bsfcls(1, 'row', 'between', 'center') . '" aria-controls="acsb_section_' .
            $si . '" aria-expanded="true" aria-label="' . $item['text'] . '">';
            $output .= '<span class="btntext">' . $item['text']. '</span>';
            $output .= '<span class="btnicon' . theme_mb2nl_bsfcls(2, '', 'center', 'center') . '">';
            $output .= '<i class="ri-add-fill"></i>';
            $output .= '<i class="ri-subtract-fill"></i>';
            $output .= '</span>';
            $output .= '</button>';
            $output .= '<div id="acsb_section_' . $si . '" class="acsb-section-content' .
            theme_mb2nl_bsfcls(1, 'wrap', 'between', '') . '">';
        } else if ($item['id'] === 'sdiv') {
            $output .= '<div class="acsb-section">';
        } else if ($item['id'] === 'ediv') {
            $output .= '</div>'; // ...acsb-section-content
            $output .= '</div>'; // ...acsb-section
        } else {
            $items = isset($item['items']) ? $item['items'] : null;
            $itemcls = $items ? ' acsb-item-group' : '';

            $output .= '<div class="acsb-item' . $itemcls . '">';

            if ($items) {
                $output .= '<div class="acsb-group-title">';
                $output .= isset($item['icon']) ? '<i class="' . $item['icon'] . '"></i>' : '';
                $output .= '<span class="acsb-item-group-title">' . get_string($item['id'], 'theme_mb2nl') . '</span>';
                $output .= '</div>'; // ...acsb-group-title

                $output .= '<div class="acsb-group-buttons' . theme_mb2nl_bsfcls(1, 'row', 'between') . '">';

                foreach ($item['items'] as $item) {
                    $output .= theme_mb2nl_acsb_block_item($item);
                }

                $output .= '</div>'; // ...acsb-group-buttons
            } else {
                $output .= theme_mb2nl_acsb_block_item($item);
            }

            $output .= '</div>'; // ...acsb-item
        }
    }

    $output .= '</div>'; // ...acsb-block-inner

    $output .= '<div class="acsb-block-footer">';
    $output .= theme_mb2nl_user_helplink();
    $output .= '</div>';

    $output .= '</div>'; // ...acsb-block

    $output .= '<span id="skip_acsb-menu"></span>';

    $PAGE->requires->data_for_js('jsselectors', theme_mb2nl_acsb_selectors());
    $PAGE->requires->js_call_amd('theme_mb2nl/access', 'acsbTools');

    return $output;

}




/**
 *
 * Method to get accessibility block
 *
 */
function theme_mb2nl_acsb_block_item($item) {
    global $CFG;

    $output = '';
    $arialabel = '';

    // Set ajax parameter.
    if ($CFG->version < 2023100900) { // Up to Moodle 4.3.
        user_preference_allow_ajax_update('acsb_' . $item['id'], PARAM_INT);
    }

    $enabled = theme_mb2nl_user_preference('acsb_' . $item['id'], 0, PARAM_INT);

    $cls = $enabled ? ' active' : '';
    $disable = isset($item['disable']) ? ' data-disable="' . $item['disable'] . '"' : ' data-disable=""';
    $text = isset($item['label']) ? $item['label'] : get_string($item['id'], 'theme_mb2nl');
    $ariachecked = $enabled ? ' aria-checked="true"' : ' aria-checked="false"';

    if (isset($item['arialabel'])) {
        if (is_numeric($item['arialabel'])) {
            $arialabel = ' aria-label="' . $text . '"';
        } else {
            $arialabel = ' aria-label="' . $item['arialabel'] . '"';
        }
    }

    $output .= '<button type="button" data-id="' . $item['id'] . '" class="acsb-button acsb-btn-css themereset' . $cls .
    theme_mb2nl_bsfcls(1, 'column', 'center', 'center') . '"' . $disable . $arialabel . $ariachecked . ' role="checkbox">';
    $output .= isset($item['icon']) ? '<i class="' . $item['icon'] . '"></i>' : '';
    $output .= '<span class="acsb-item-title">' . $text . '</span>';
    $output .= '</button>';

    return $output;

}










/**
 *
 * Method to set accessibility classess
 *
 */
function theme_mb2nl_acsb_cls() {

    global $PAGE;

    $cls = '';
    $items = theme_mb2nl_acsb();

    foreach ($items as $item) {
        if ($item['id'] === 'title' || $item['id'] === 'sdiv' || $item['id'] === 'ediv') {
            continue;
        }

        if (isset($item['items'])) {
            foreach ($item['items'] as $item) {
                if (theme_mb2nl_user_preference('acsb_' . $item['id'], 0, PARAM_INT)) {
                    $cls .= ' acsb_' . $item['id'];
                }
            }
        } else {
            if (theme_mb2nl_user_preference('acsb_' . $item['id'], 0, PARAM_INT)) {
                $cls .= ' acsb_' . $item['id'];
            }
        }
    }

    return $cls;

}





/**
 *
 * Method to set accessibility preferences
 *
 */
function theme_mb2nl_acsb_preferences() {

    $pref = [];
    $items = theme_mb2nl_acsb();
    $prifiles = theme_mb2nl_acsb_profiles();
    $items = array_merge($items, $prifiles);

    $pref['acsb_trigger'] = [
        'type' => PARAM_INT,
        'null' => NULL_NOT_ALLOWED,
        'default' => 0,
    ];

    foreach ($items as $item) {
        if ($item['id'] === 'title' || $item['id'] === 'sdiv' || $item['id'] === 'ediv') {
            continue;
        }

        if (isset($item['items'])) {
            foreach ($item['items'] as $item) {
                $pref['acsb_' . $item['id']] = [
                    'type' => PARAM_INT,
                    'null' => NULL_NOT_ALLOWED,
                    'default' => 0,
                ];
            }
        } else {
            $pref['acsb_' . $item['id']] = [
                'type' => PARAM_INT,
                'null' => NULL_NOT_ALLOWED,
                'default' => 0,
            ];
        }
    }

    return $pref;

}



/**
 *
 * Method to set css selectors for js
 *
 */
function theme_mb2nl_acsb_selectors() {
    return [
        'body', '.fsmod-footer',
        '.page-bgimg', '.theme-scrolltt',

        '.header-inner', '.top-bar', '.master-header-inner',
        '.breadcrumb-item',

        '#region-main', '.page-breadcrumb.breadcrumb_classic', '#page-header', '.theme-footer',
        '.mb2-pb-date', '.page-b',

        '.slide-contentnav-link',

        '.pbanimnum-number', '.pbanimnum-icon',

        '.theme-text-text', '.theme-text-text *',

        '.text', '.text-muted', 'pre', '.theme-table-wrap', '.icon', 'label', '.text-truncate', '.bg-light', '.page-link',
        'dt', 'dd', '.slide-desc', '.tcolorh',

        '.badge',

        '.box-title', '.box-title-text',
        '.box-desc', '.theme-boxicon-icon', '.theme-boxicon-icon i',
        '.boxcontent-desc', '.box-desc-text', '.theme-header-subtitle',

        '.event-duration', '.event-date', '.event-date span', '.eventname', '.footer-link a',

        '.iconel i', '.list-text', '.theme-list li', '.theme-list a', '.social-list a', '.share-list a',

        '.tab-pane', '.tab-pane *',

        '.theme-slide-title', '.theme-slider-desc',

        '.select-items-container', '.mb2-pb-select_item',

        '.mb2-pb-btn', '#page button', '.showmore-container button *', '#page button i', '.mb2-pb-btn i', '.fixed-bar button',
        '.panel-link', '.header-tools-link i', '.mb2-accordion button', '.mb2-accordion button i',

        '.embed-video-bg i',

        '.accimg-item', '.accimg-plus',

        '.block_coursetoc .coursetoc-sectionlist',

        '.enrollment-page #page-content',

        '.card-body', '.day-number',

        '.quicklinks-list', '.item-link', '.static-icon', '.static-icon i', '.menu-extracontent-content', '.quicklinks path',

        '.mb2mm-action', '.mb2mm-mega-action', '.mb2mm-heading', '.mb2mm-icon', '.mb2mm-label', '.mb2mm-sublabel',
        '.mb2mm-mega-action', '.mb2mm-toggle',
        '.mb2mm-arrow', '.link-replace', '.mobile-navto', '.mobile-navbottom', '.menu-extracontent-controls',
        '.menu-extra-controls-btn', '.lang-list a', '.show-menu', '.theme-usermenu', '.theme-usermenu li', '.theme-usermenu a',
        '.logout-link', '.theme-loginform .help-link',

        '.admin-region', '.theme-links a', '.mb2tmpl-acccontent>div', '.mb2megamenu-item-header',
        '.mb2megamenu-item-header .item-label', '.mb2megamenu-builder-footer', '.dashboard-tabs', '.theme-dashboard',
        '.block-item-inner',

        '.action-menu', '.action-menu-item',

        '.mb2pb-editfooter',

        'input', 'textarea', 'select', '.form-label', '.form-control', '.col-form-label *', '.form-description',
        '.mb2-pb-announcements-title', '.mb2-pb-announcements-content', '.mb2-pb-announcements-item',

        '.pagelayout-content', '.pagelayout-a', '.pagelayout-b', '.toggle-sidebar-nav', '.toggle-sidebar-nav button',

        '.footer-tools', '.footer-tools a', '.footer-content',

        '.alert',

        '.filter-content',

        '.box', '.boxlist a', '.boxlist span', '.activityiconcontainer.content', '.activityname', '.activityname a',
        '.instructor-meta *',
        '.course-link-item a', '.course-link-item path', '.toggle-icon', '.progress-value', '.theme-turnediting',
        '.course-nav-list-item-list-container',
        '.course-nav-list-ccontent', '.activity', '.theme-course-teacher-inner', '.info-courses', '.info-students',
        '.teacher-info i', '.theme-courses-topbar',
        '#fsmod-header', '.fsmod-course-sections', '.coursetoc-section-tite', '.fsmod-section-tools', '.fsmod-section',
        '.fsmod-course-content', '.coursenav-link',
        '.fsmod-backlink', '.fsmod-showhide-sidebar path', '.coursenav-link span', '.sidebar-inner', '.course-slogan',
        '.course-categories-tree', '.course-meta1', '.course-meta2', '.price', '.enrol-course-nav', '.enrol-course-nav ul',
        '.course-description-item', '.children-category a', '.cat-image', '.cat-image path',
        '.theme-course-filter .field-container input+i', '.coursetabs-tablist', '.coursetabs-catitem span',
        '.course-custom-fileds', '.course-custom-fileds li', '.activity-header', '.tcolorl', '.tcolorn',
        '.course-categories-tree a', '.filter-heading', '.filter-toggle', '.toggle-list-btn', '.course-info2-inner',
        '.enrol-course-nav-inner', '.availabilityinfo', '.course-section-header', '.activity-item', '.section-collapsemenu',
        '.course-nav-button', '.modified',

        '.nav-buttons', '.coursetoc-tools', '.coursetoc-sinput',

        '.comment-link',

        '.message-app', '.drawer-top', '.drawer-top a', '.drawer-top i',

        '.popover-region-container', '.popover-region-footer-container', '.popover-region-seeall-text',

        '.mb2config-heading', '.mb2config-spacer',

        '.mb2pb-videopopup-icon', '.mb2pb-videopopup-text',

        '.bgcolor',

        '.theme-boxicon',

        '.mb2-pb-ba_content',
     ];
}


/**
 *
 * Method to set scss selectors
 *
 */
function theme_mb2nl_acsb_cssselectors() {

    return [
        '.breadcrumb ul li+li::before', '.breadcrumb ol li+li::before', '.block_tree a',

        '.popover-region-header-container', '.popover-region-toggle::before', '.popover-region-toggle::after',

        '.control-area', '.notification-area',

        '.courseindex-item', '.changenumsections', '.pluscontainer', '.inplaceeditable',

        '.sectionname a',

        '[type="text"]', 'fieldset', 'fieldset>*', '.filepicker-container', '.moreless-toggler', 'select', '.form-control', 'input',
        'textarea', '.fm-empty-container',

        '.sp-replacer', '.sp-container', '.sp-picker-container',

        '.tox-tinymce', '.tox-editor-header', '.tox-menubar', '.tox-toolbar-overlord', '.tox-statusbar', '.tox-mbtn',
        '.tox-toolbar__primary', '.tox-edit-area__iframe', '.tox-tbtn svg', '.tox-toolbar__overflow',
        '.tox-statusbar__text-container *', '.tox-menu', '.tox-collection__item', '.tox-collection__group',
        '.tox .tox-collection__item-caret svg', '.tox-collection__item-accessory', '.tox-pop__dialog', '.tox-tbtn',

        '.CodeMirror', '.CodeMirror-linenumber', '.CodeMirror-gutters',

        '.editor_atto_toolbar', '.atto_group', '.atto_group button', '.atto_group button i',

        '.modal-body', '.modal-content', '.modal-header', '.modal-footer', '.modal-header *', '.modal-footer *',

        '.moodle-dialogue-wrap', '.moodle-dialogue-hd',

        '.badge', '.yui3-calendar-content', '.yui3-calendar-header', '.filepicker-filelist',

        'tr', 'th', 'td', 'th *', 'td *',

        'h1', 'h2', 'h3', 'h4', 'h5', 'h6', '.h1', '.h2', '.h3', '.h4', '.h5', '.h6',

        '.card', '.text-truncate', '.activityiconcontainer', '.btn', '.btn i', '.multiline', '.card-footer', '.progress-text',

        'button:not(#acsb-menu button):not(.acsb-trigger)', 'button i:not(#acsb-menu button i)',

        '.list-group-item', '.bg-white',

        '.linkbtn', '.title', '.course-popover-inner', '.course-popover-inner *',

        '.item-actions button', '.popover-header', '.popover-body',

        'footer button',

        '.day', '.message', '.message *',

        '.dropdown-menu', '.dropdown-menu *', '.dropdown-item', 'nav', '.nav-item', '.nav-link', '.nav-tabs',

        '.border-bottom', '.border-top', '.border-right', '.border-left',

        '.categoryname', '.coursebox', '.info a', '.enrolmenticons', '.categoryname::before', '.teachers',

        'table', '.cell',

        '.full-width-bottom-border',

        // Theme elements.
        '.mb2-pb-testimonials-item', '.mb2-pb-testimonials-item *',

        '.course-quick', '.course-quick *',

        '.mb2-editor', '.mb2-editor-document', '.mb2-editor-document *',

        '.mb2mm-wrap:before',

        '.search-field',

        '.theme-footer a',

        '.theme-course-item-inner',

        '.event-title', '.event-details',

        '.myc-readmore',

        '.tpheader_dark.tpheaderl_modern .breadcrumb a',

        '.tgsdbc_dark .sidebar-content',

        '.cfilter-wrap',

        '.dshb-wbox',

        '.block-name', '.value', '.dshb-block:after', '.suffix',

        '.progress-area',
     ];

}


/**
 *
 * Method to set contrast css
 *
 */
function theme_mb2nl_acsb_csscontrast($dark = true) {

    $eldark = [
        '.mb2-pb-row.light .mb2-pb-row-inner',
        '.tpheader_light #page-header',
        // Moreless button gradient.
        '.toggle-content .content:before',
        '.tgsdb-chome .course-title',
    ];

    $ellight = [
        '.mb2-pb-row.dark .mb2-pb-row-inner',
        '.tpheader_dark #page-header',
    ];

    if ($dark) {
        return $eldark;
    } else {
        return $ellight;
    }

}



/**
 *
 * Method to set css selectors for css filter
 *
 */
function theme_mb2nl_acsb_filterselectors() {
    return [
        'svg image',
        'img.icon',
        '.activityicon',
    ];
}




/**
 *
 * Method to set style tag
 *
 */
function theme_mb2nl_acsb_style() {
    global $PAGE;

    if (! theme_mb2nl_theme_setting($PAGE, 'acsboptions')) {
        return;
    }

    $style = '';
    $selectors = theme_mb2nl_acsb_cssselectors();
    $filters = theme_mb2nl_acsb_filterselectors();
    $selcount = count($selectors);
    $filterscount = count($filters);
    $i = 0;
    $z = 0;

    $style .= '<style id="ascsb_style">';

    // CSS variables.
    $style .= ':root{';
    $style .= '--acsb-bg: #181818;';
    $style .= '--acsb-color: #ffffff;';
    $style .= '--acsb-bocolor: #282828;';
    $style .= '--acsb-filtercolor: brightness(0) saturate(100%) invert(100%) sepia(91%) saturate(0%) hue-rotate(298deg)';
    $style .= ' brightness(105%) contrast(101%);';
    $style .= '}';

    $style .= '.acsb_contrastlight{';
    $style .= '--acsb-bg: #ffffff;';
    $style .= '--acsb-color: #000000;';
    $style .= '--acsb-bocolor: #dddddd;';
    $style .= '--acsb-filtercolor: brightness(0) saturate(100%) invert(0%) sepia(100%) saturate(7494%) hue-rotate(292deg)';
    $style .= ' brightness(70%) contrast(100%);';
    $style .= '}';

    // CSS selectors.
    foreach ($selectors as $selector) {
        $i++;
        $comma = $i == $selcount ? '' : ', ';

        $style .= '.acsb_contrastdark ' . $selector . ', ';
        $style .= '.acsb_contrastlight ' . $selector . $comma;
    }

    $style .= '{';
    $style .= 'background-color: var(--acsb-bg) !important;';
    $style .= 'color: var(--acsb-color) !important;';
    $style .= 'border-color: var(--acsb-bocolor) !important;';
    $style .= '}';

    // Style for light sections.
    $darkel = theme_mb2nl_acsb_csscontrast();
    $dcounter = 0;

    foreach ($darkel as $el) {
        $dcounter++;
        $comma = $dcounter == count($darkel) ? '' : ', ';

        $style .= '.acsb_contrastdark ' . $el . $comma;
    }

    $style .= '{';
    $style .= 'background-color: var(--acsb-bg) !important;';
    $style .= 'border-color: var(--acsb-bocolor) !important;';
    $style .= 'background-image: none !important;';
    $style .= '}';

    // Style for dark sections.
    $lightel = theme_mb2nl_acsb_csscontrast(false);
    $lcounter = 0;

    foreach ($lightel as $el) {
        $lcounter++;
        $comma = $lcounter == count($lightel) ? '' : ', ';

        $style .= '.acsb_contrastlight ' . $el . $comma;
    }

    $style .= '{';
    $style .= 'background-color: var(--acsb-bg) !important;';
    $style .= 'border-color: var(--acsb-bocolor) !important;';
    $style .= 'background-image: none !important;';
    $style .= '}';

    // CSS filter.
    foreach ($filters as $filter) {
        $z++;
        $comma = $z == $filterscount ? '' : ', ';

        $style .= '.acsb_contrastdark ' . $filter . ', ';
        $style .= '.acsb_contrastlight ' . $filter . $comma;
    }

    $style .= '{';
    $style .= 'filter: var(--acsb-filtercolor) !important;';
    $style .= '}';

    $style .= '</style>';

    return $style;

}
