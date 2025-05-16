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
 * Method to get predefined less variables
 *
 */
function theme_mb2nl_get_pre_scss($theme) {
    global $CFG;
    $scss = '';
    $vars = theme_mb2nl_get_style_vars();
    $cssvars = theme_mb2nl_get_style_vars(true);

    foreach ($vars as $k => $v) {
        switch ($k) {
            case ('ffgeneral') :
                $isv = theme_mb2nl_scss_fvalue('ffgeneral', $theme);
                break;
            case ('ffheadings') :
                $isv = theme_mb2nl_scss_fvalue('ffheadings', $theme);
                break;

            case ('ffmenu') :
                $isv = theme_mb2nl_scss_fvalue('ffmenu', $theme);
                break;
            case ('ffddmenu') :
                $isv = theme_mb2nl_scss_fvalue('ffddmenu', $theme);
                break;
            case ('fwgeneral3') :
                $isv = theme_mb2nl_scss_fvalue('fwgeneral3', $theme, false);
                break;
            case ('fwheadings3') :
                $isv = theme_mb2nl_scss_fvalue('fwheadings3', $theme, false);
                break;
            case ('fwmenu3') :
                $isv = theme_mb2nl_scss_fvalue('fwmenu3', $theme, false);
                break;
            case ('fwddmenu3') :
                $isv = theme_mb2nl_scss_fvalue('fwddmenu3', $theme, false);
                break;
            default :
                $isv = (isset($theme->settings->$k) && $theme->settings->$k !== '') ? $theme->settings->$k : null;
        }

        if (empty($isv)) {
            continue;
        }

        $issuffix = isset($v[1]) ? $v[1] : '';
        $scss .= '$' . $v[0] . ':' . $isv . $issuffix . ';';
    }

    $scss .= ':root {';

    foreach ($cssvars as $k => $v) {
        $isv = (isset($theme->settings->$k) && $theme->settings->$k !== '') ? $theme->settings->$k : null;

        if (empty($isv)) {
            continue;
        }

        $issuffix = isset($v[1]) ? $v[1] : '';
        $scss .= $v[0] . ':' . $isv . $issuffix . ';';
    }

    $scss .= '}';

    return $scss;

}






/**
 *
 * Method to get predefined less variables
 *
 */
function theme_mb2nl_scss_fvalue($name, $theme, $quote = true) {

    $output = '';

    // Get settings.
    $sname1 = $theme->settings->$name;

    if (isset($theme->settings->$sname1) && $theme->settings->$sname1 !== '') {
        $output .= $quote ? '\'' : '';
        $output .= $theme->settings->$sname1;
        $output .= $quote ? '\'' : '';
        return $output;
    } else {
        return null;
    }

}







/**
 *
 * Method to set inline styles
 *
 */
function theme_mb2nl_get_pre_scss_raw($theme) {

    global $PAGE;
    $output = '';

    $output .= theme_mb2nl_fonticons();
    $output .= theme_mb2nl_custom_fonts();
    $output .= theme_mb2nl_admin_regions_hide_options();
    $output .= theme_mb2nl_theme_setting($PAGE, 'customcss', '', false, $theme);

    return $output;

}





/**
 *
 * Method to get theme settings for scss and less file
 *
 */
function theme_mb2nl_get_style_vars($css = false) {

    $vars = [
        // Theme setting => scss/less variable.
        // General settings.
        'navddwidth' => ['ddwidth', 'px'],
        'pagewidth' => ['pagewidth', 'px'],
        'logoh' => ['logoh', 'px'],
        'logohsm' => ['logohsm', 'px'],

        // Footer.
        'partnerlogoh' => ['partnerlogoh', 'px'],

        // Colors.
        'accent1' => ['accent1'],
        'accent2' => ['accent2'],
        'accent3' => ['accent3'],
        'textcolor' => ['textcolor'],
        'textcolor_lighten' => ['textcolor_lighten'],
        'textcolorondark' => ['textcolorondark'],
        'linkcolor' => ['linkcolor'],
        'linkhcolor' => ['linkhcolor'],
        'headingscolor' => ['headingscolor'],
        'btncolor' => ['btncolor'],
        'btnprimarycolor' => ['btnprimarycolor'],

        // Helper colors.
        'color_success' => ['color_success'],
        'color_warning' => ['color_warning'],
        'color_danger' => ['color_danger'],
        'color_info' => ['color_info'],

        // Mian header color.
        'mhbgcolor' => ['mhbgcolor'],
        'tbbgcolor' => ['tbbgcolor'],
        'mhbgcolorl' => ['mhbgcolorl'],
        'tbbgcolorl' => ['tbbgcolorl'],

        // Transparent header.
        'headerbgcolor' => ['headerbgcolor'],
        'headerbgcolor2' => ['headerbgcolor2'],
        'headerlbgcolor' => ['headerlbgcolor'],
        'headerlbgcolor2' => ['headerlbgcolor2'],

        // Page background.
        'pbgcolor' => ['pbgcolor'],

        // Login page background.
        'loginbgcolor' => ['loginbgcolor'],

        // Fonts family.
        'ffgeneral' => ['ffgeneral'],
        'ffheadings' => ['ffheadings'],
        'ffmenu' => ['ffmenu'],
        'ffddmenu' => ['ffddmenu'],

        // Font weight.
        'fwlight' => ['fwlight'],
        'fwnormal' => ['fwnormal'],
        'fwmedium' => ['fwmedium'],
        'fwbold' => ['fwbold'],

        // Font size.
        'fsbase' => ['fsbase', 'px'],
        'fsheading1' => ['fsheading1', 'rem'],
        'fsheading2' => ['fsheading2', 'rem'],
        'fsheading3' => ['fsheading3', 'rem'],
        'fsheading4' => ['fsheading4', 'rem'],
        'fsheading5' => ['fsheading5', 'rem'],
        'fsheading6' => ['fsheading6', 'rem'],
        'fsmenu' => ['fsmenu', 'rem'],
        'fsddmenu2' => ['fsddmenu2', 'rem'],

        // Font weight.
        'fwgeneral3' => ['fwgeneral3'],
        'fwheadings3' => ['fwheadings3'],
        'fwmenu3' => ['fwmenu3'],
        'fwddmenu3' => ['fwddmenu3'],

        // Text transform.
        'ttmenu' => ['ttmenu'],
        'ttddmenu' => ['ttddmenu'],
    ];

    $cssvars = [
        'fsbase' => ['--mb2-pb-fsbase', 'px'],

        // Typography colors.
        'textcolor' => ['--mb2-pb-textcolor'],
        'textcolor_lighten' => ['--mb2-pb-textcolor_lighten'],
        'linkcolor' => ['--mb2-pb-linkcolor'],
        'headingscolor' => ['--mb2-pb-headingscolor'],

        // Accent colors.
        'accent1' => ['--mb2-pb-accent1'],
        'accent2' => ['--mb2-pb-accent2'],
        'accent3' => ['--mb2-pb-accent3'],

        // Main header colors.
        'mhbgcolor' => ['--mb2-pb-mhbgcolor'],
        'tbbgcolor' => ['--mb2-pb-tbbgcolor'],
        'mhbgcolorl' => ['--mb2-pb-mhbgcolorl'],
        'tbbgcolorl' => ['--mb2-pb-tbbgcolorl'],

        // Header colors.
        'headerbgcolor' => ['--mb2-pb-headerbgcolor'],
        'headerbgcolor2' => ['--mb2-pb-headerbgcolor2'],
        'headerlbgcolor' => ['--mb2-pb-headerlbgcolor'],
        'headerlbgcolor2' => ['--mb2-pb-headerlbgcolor2'],

        // Helper colors.
        'color_success' => ['--mb2-pb-color_success'],
        'color_warning' => ['--mb2-pb-color_warning'],
        'color_danger' => ['--mb2-pb-color_danger'],
        'color_info' => ['--mb2-pb-color_info'],

        // Buttons.
        'btncolor' => ['--mb2-pb-btn-bgcolor'],
        'btnprimarycolor' => ['--mb2-pb-btn-primarybgcolor'],

        // Font weight.
        'fwlight' => ['--mb2-pb-fwlight'],
        'fwnormal' => ['--mb2-pb-fwnormal'],
        'fwmedium' => ['--mb2-pb-fwmedium'],
        'fwbold' => ['--mb2-pb-fwbold'],

        // Accessibility widget.
        'acsb_color1' => ['--mb2-acsb_color1'],
        'acsb_color2' => ['--mb2-acsb_color2'],
        'acsb_color3' => ['--mb2-acsb_color3'],
    ];

    if ($css) {
        return $cssvars;
    } else {
        return $vars;
    }

}




/**
 *
 * Method to get main scss content
 *
 */
function theme_mb2nl_get_scss_content($theme) {
    global $CFG, $PAGE;

    $scss = '';

    $footerstyle = $theme->settings->footerstyle;

    // Get main scss file.
    $scss .= file_get_contents($CFG->dirroot . '/theme/mb2nl/scss/style.scss');

    // Footer style.
    $scss .= file_get_contents($CFG->dirroot . '/theme/mb2nl/scss/theme/theme-footer-' . $footerstyle . '.scss');

    // Plugin styles.
    $plugins = theme_mb2nl_add_scss($theme);

    if (count($plugins)) {
        foreach ($plugins as $plugin) {
            $fileurl = $CFG->dirroot . '/theme/mb2nl/scss/plugins/' . $plugin . '.scss';

            if (! file_exists($fileurl)) {
                continue;
            }

            $scss .= file_get_contents($fileurl);
        }
    }

    // Course view style.
    if (
        in_array('plugin-buttons', $plugins) ||
        in_array('plugin-grid', $plugins) ||
        in_array('plugin-topcoll', $plugins) ||
        in_array('plugin-tiles', $plugins) ||
        in_array('plugin-mb2sections', $plugins)
      ) {
        $scss .= file_get_contents($CFG->dirroot . '/theme/mb2nl/scss/moodle-custom/moodle-course-view-all.scss');
    } else {
        $scss .= file_get_contents($CFG->dirroot . '/theme/mb2nl/scss/moodle-custom/moodle-course-view.scss');
    }

    // Acsb block.
    if ($theme->settings->acsboptions) {
        $scss .= file_get_contents($CFG->dirroot . '/theme/mb2nl/scss/theme/theme-acsb_block.scss');
    }

    // Custom style.
    $cstylefiles = theme_mb2nl_filearea('cstylefiles', false, $theme->name);

    if (count($cstylefiles)) {
        foreach ($cstylefiles as $f) {
            $finfo = pathinfo($f);
            $format = $finfo['extension'];

            if (!in_array($format, ['css', 'scss'])) {
                continue;
            }

            $scss .= file_get_contents($f);
        }
    }

    return $scss;

}





/**
 *
 * Method to get additional scss
 *
 */
function theme_mb2nl_add_scss($theme) {
    global $PAGE, $CFG;

    $files = [];
    $plugincss = $theme->settings->plugincss;
    $slider = $theme->settings->slider;

    if ($plugincss !== '') {
        $files = preg_split('/\r\n|\n|\r/', trim($plugincss));
    }

    if ($slider && file_exists($CFG->dirroot . '/local/mb2slides/index.php')) {
        $files[] = 'plugin-lightslider';
        $files[] = 'plugin-mb2slides-admin';
        $files[] = 'plugin-mb2slides-nav';
        $files[] = 'plugin-mb2slides-slider';
        $files[] = 'plugin-mb2slides-mobile';
    }

    return $files;

}


/**
 *
 * Method to set theme css inline style
 *
 */
function theme_mb2nl_style() {
    $output = '';

    $output .= '<style id="theme_custom_style">';

    // This is require for older Moodle versons with toggle sidebar.
    // In Moodle 4.0 and older scss compiller lose 'px" after zero and this cause problem with toggle sidebar left position.
    $output .= ':root{--mb2-htmlscl:0px;}';

    $output .= '</style>';

    return $output;

}
