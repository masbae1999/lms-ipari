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
 * Method to get search form
 *
 */
function theme_mb2nl_search_form($menu = false) {

    global $CFG, $PAGE;

    $output = '';
    $searchaction = new moodle_url('/course/search.php');
    $searchtext = get_string('search');
    $globalsearch = (isset($CFG->enableglobalsearch) && $CFG->enableglobalsearch);
    $inputname = 'search';

    if (preg_match('@admin-@', $PAGE->pagetype)) {
        $searchaction = new moodle_url('/admin/search.php');
        $inputname = 'query';
    } else if ($globalsearch) {
        $searchaction = new moodle_url('/search/index.php');
        $inputname = 'q';
    }

    $cls = $menu ? 'menu-extracontent-content menu-searchcontainer' : 'theme-searchform panel-item panel-search';
    $id1 = $menu ? 'menu-search' : 'theme-search';
    $id2 = $menu ? 'menu-searchbox' : 'theme-searchbox';
    $id3 = $menu ? 'menu-searchcontainer' : uniqid('search_');

    $output .= '<div id="' . $id3 . '" class="' . $cls . '">';
    $output .= '<div class="form-inner">';
    $output .= '<form id="' . $id1 . '" action="' . $searchaction . '" class="' . theme_mb2nl_bsfcls(1, '', 'center', 'center')
    . '">';
    $output .= '<input id="' . $id2 . '" type="text" value="" placeholder="' . $searchtext . '" name="' . $inputname . '">';
    $output .= '<button type="submit" aria-label="' . $searchtext . '"><i class="ri-search-line"></i></button>';
    $output .= '</form>';
    $output .= '</div>'; // ...form-inner
    $output .= theme_mb2nl_search_links();
    $output .= '</div>';

    return $output;

}




/**
 *
 * Method to get search links
 *
 */
function theme_mb2nl_search_links() {
    global $PAGE;

    $searchmenuitems = theme_mb2nl_theme_setting($PAGE, 'searchlinks');

    if ($searchmenuitems) {
        return theme_mb2nl_static_content($searchmenuitems, true, true, ['listcls' => 'theme-searchform-links']);
    }

}



/**
 *
 * Method to get  register link
 *
 */
function theme_mb2nl_register_form() {
    global $CFG, $PAGE;

    require_once($CFG->libdir . '/authlib.php');

    $output = '';

    if (isloggedin() && ! isguestuser()) {
        return;
    }

    if (! signup_is_enabled() && ! theme_mb2nl_theme_setting($PAGE, 'signuppage')) {
        return;
    }

    $signuplink = theme_mb2nl_signup_url();
    $signuptarget = theme_mb2nl_signup_url(true);

    $output .= '<div id="menu-registercontainer" class="menu-extracontent-content menu-registercontainer text-center">';
    $output .= '<a href="' . $signuplink . '"' . $signuptarget . '>' . get_string('startsignup') . '</a>';
    $output .= '</div>';

    return $output;
}



/**
 *
 * Method to get login form
 *
 *
 */
function theme_mb2nl_login_form($modal = false, $menu = false) {

    global $SITE, $PAGE, $OUTPUT, $USER, $CFG;

    $output = '';
    $logintoken = '';
    $loginlink = new moodle_url('/login/forgot_password.php');
    $compact = $modal || $menu;
    $loginurl = theme_mb2nl_login_url();
    $loginlinktopage = theme_mb2nl_theme_setting($PAGE, 'loginlinktopage');

    if (theme_mb2nl_theme_setting($PAGE, 'loginlinktopage') && (! isloggedin() || isguestuser()) && ! $menu) {
        return;
    }

    $cls = $menu ? 'menu-extracontent-content menu-logincontainer theme-loginform' : 'theme-loginform panel-item panel-login';
    $divid = $menu ? 'menu-logincontainer' : uniqid('login_');
    $idform = $menu ? 'menu-form-login' : 'header-form-login';
    $idusername = $menu ? 'menu-login-username' : 'login-username';
    $idpswd = $menu ? 'menu-login-password' : 'login-password';

    if (method_exists('\core\session\manager', 'get_login_token')) {
        $logintoken = '<input type="hidden" name="logintoken" value="' . s(\core\session\manager::get_login_token()) .'">';
    }

    $output .= '<div id="' . $divid . '" class="' . $cls . '">';
    $output .= $menu ? '<div class="form-inner">' : '';

    if (! $loginlinktopage && (! isloggedin() || isguestuser())) {
        $output .= $compact ? '<h2 class="h4">' . get_string('login') . '</h2>' : '';

        $output .= theme_mb2nl_get_login_auth();

        $output .= '<form id="' . $idform . '" method="post" action="' . $loginurl . '">';
        $output .= '<div class="form-field">';
        $output .= '<label for="' . $idusername . '"><i class="ri-user-3-line"></i></label>';
        $output .= '<input id="' . $idusername . '" type="text" name="username" placeholder="' . get_string('username')
        . '" aria-label="' . get_string('username') . '">';
        $output .= '</div>';
        $output .= '<div class="form-field">';
        $output .= '<label for="' . $idpswd . '"><i class="ri-lock-line"></i></label>';
        $output .= '<input id="' . $idpswd . '" type="password" name="password" placeholder="' . get_string('password')
        . '" aria-label="' . get_string('password') . '">';
        $output .= '<span class="themereset pass_show" data-show="' . get_string('show') . '" data-hide="' . get_string('hide')
        . '" aria-hidden="true">' . get_string('show') . '</span>';
        $output .= '</div>';

        if ($compact) {
            $output .= '<span class="login-info"><a href="' . $loginlink . '">' . get_string('forgotten') . '</a></span>';
            $output .= '<input type="submit" value="' . get_string('login') . '">';
        } else {
            $output .= '<button type="submit" class="' . theme_mb2nl_bsfcls(2, '', 'center', 'center') . '" aria-label="' .
            get_string('login') . '"><i class="fa fa-angle-right"></i></button>';
        }

        $output .= $logintoken;
        $output .= '</form>';

        $output .= ! $compact ? '<div class="login-info"><a href="' . $loginlink . '">' .get_string('forgotten'). '</a></div>' : '';

        if ($CFG->registerauth === 'email' || ! empty($CFG->registerauth)) {
            $output .= '<div class="login-info signup-info"><a href="' . new moodle_url('/login/signup.php') . '">' .
            get_string('startsignup') . '</a></div> ';
        }

    } else if ($loginlinktopage && (! isloggedin() || isguestuser())) {
        $output .= '<div class="text-center"><a href="' . $loginurl . '">' . get_string('loginto', 'moodle', $SITE->fullname)
        . '</a></div>';
    } else if (isloggedin() && !isguestuser()) {
        if ($compact) {
            $output .= '<div class="user-picture-container mb-4' . theme_mb2nl_bsfcls(1, '', '', 'center') . '">';
            $output .= $OUTPUT->user_picture($USER, ['size' => 80]);
            $output .= '<div class="user-description ml-4">';
            $output .= theme_mb2nl_user_info_html();
            $output .= '</div>'; // ...user-description
            $output .= '</div>'; // ...user-picture-container
            $output .= theme_mb2nl_usermenu(['logout'], $menu);
            $output .= '<div class="user-footer mt-4' . theme_mb2nl_bsfcls(1, '', 'between', 'center') . '">';
            $output .= theme_mb2nl_user_helplink();
            $output .= theme_mb2nl_user_logoutlink();
            $output .= '</div>'; // ...user-footer
        } else {
            $output .= '<div class="' . theme_mb2nl_bsfcls(1, '', 'between', 'center') . '">';
            $output .= theme_mb2nl_usermenu([], false, true);
            $output .= '<div class="user-picture-container' . theme_mb2nl_bsfcls(1, '', '', 'center') . '">';
            $output .= $OUTPUT->user_picture($USER, ['size' => 80, 'class' => '']);
            $output .= '<div class="user-description ml-2">';
            $output .= theme_mb2nl_user_info_html(false);
            $output .= '</div>'; // ...user-description
            $output .= '</div>'; // ...user-picture-container
            $output .= '</div>';
        }

    }

    $output .= $menu ? '</div>' : '';
    $output .= '</div>';

    return $output;

}




/**
 *
 * Method to get login form
 *
 */
function theme_mb2nl_header_tools($modal = false, $class = '') {

    global $OUTPUT, $PAGE, $USER, $CFG, $COURSE;

    require_once($CFG->libdir . '/authlib.php');

    $output = '';
    $class = $class ? ' ' . $class : '';

    $globalsearch = (isset($CFG->enableglobalsearch) && $CFG->enableglobalsearch);
    $searchtextcore = get_string('search');

    $jslinkcls = !$modal ? ' header-tools-jslink' : '';
    $modalatts = $modal ? ' data-toggle="modal" data-target="#header-modal-search"' : '';

    $output .= '<div class="header-tools' . $class . '">';

    if (theme_mb2nl_theme_setting($PAGE, 'navbarplugin')) {
        $output .= '<div class="theme-plugins">';
        $output .= $OUTPUT->navbar_plugin_output();
        $output .= '</div>';
    }

    $output .= '<div id="themeskipto-search" class="sr-only sr-only-focusable"></div>';
    $output .= '<button id="theme-search-btn" class="header-tools-link' . $jslinkcls . ' tool-search themereset" data-id="search"
    aria-label="' . $searchtextcore . '"' . $modalatts . '>';
    $output .= '<i class="icon1 ri-search-line"></i>';
    $output .= '<span class="text1">' . $searchtextcore . '</span>';
    $output .= '</button>';

    $output .= theme_mb2nl_tool_login($modal);

    $signuppage = theme_mb2nl_theme_setting($PAGE, 'signuppage');

    if ((signup_is_enabled() || $signuppage) && $PAGE->pagetype !== 'login-signup' && (!isloggedin() || isguestuser())) {
        $signuplink = theme_mb2nl_signup_url();
        $signupliktarget = theme_mb2nl_signup_url(true);

        $output .= '<a href="' . $signuplink . '" class="header-tools-link tool-signup" aria-label="' .
        get_string('register', 'theme_mb2nl') . '"' . $signupliktarget . '>';
        $output .= '<i class="icon1 ri-user-3-line"></i>';
        $output .= '<span class="text1">' . get_string('register', 'theme_mb2nl') . '</span>';
        $output .= '</a>';
    }

    $output .= '</div>';

    return $output;

}








/**
 *
 * Method to set login button
 *
 */
function theme_mb2nl_tool_login($modal = false) {
    global $PAGE, $CFG, $USER, $OUTPUT;
    $output = '';
    $toolspos = theme_mb2nl_header_tools_pos();
    $notlogin = (!isloggedin() || isguestuser());
    $loginlinktopage = theme_mb2nl_theme_setting($PAGE, 'loginlinktopage');
    $loginlink = '#';
    $tag = 'button';
    $href = '';
    $infoicon = \core\session\manager::is_loggedinas() ? '<i class="bi bi-info position-absolute lhsmall' .
    theme_mb2nl_bsfcls(2, '', 'center', 'center') . '" title="' . get_string('loggedinas', '', $USER->firstname . ' ' .
    $USER->lastname) . '"></i>' : '';
    $jslinkcls = ! $modal ? ' header-tools-jslink' : '';
    $loginicon = $notlogin ? 'lock' : 'user-3';
    $logintitle = $notlogin ? get_string('login') : $USER->firstname;
    $modalatts = $modal ? ' data-toggle="modal" data-target="#header-modal-login"' : '';
    $userimg = $notlogin || $toolspos == 1 ? '' : '<span class="avatar position-relative' . theme_mb2nl_bsfcls(2) . '">'. $infoicon.
    $OUTPUT->user_picture($USER, ['size' => 40, 'link' => false, 'alttext' => true]).'</span>';
    $textcls = !$notlogin && $toolspos == 2 ? 'sr-only' : 'text1';

    if (theme_mb2nl_is_login()) {
        return;
    }

    if ($notlogin && $loginlinktopage) {
        $jslinkcls = '';
        $tag = 'a';
        $loginlink = theme_mb2nl_login_url();
        $modalatts = '';
        $href = ' href="' . $loginlink . '"';
    }

    $output .= '<div id="themeskipto-login" class="sr-only sr-only-focusable"></div>';
    $output .= '<' . $tag . $href . ' class="header-tools-link themereset' . $jslinkcls . ' tool-login" data-id="login"' .
    $modalatts . ' aria-label="' . $logintitle . '">';
    $output .= ! $userimg ? '<i class="icon1 ri-' . $loginicon . '-line"></i>' : '';
    $output .= $userimg;
    $output .= '<span class="' . $textcls . '">' . $logintitle . '</span>';
    $output .= '</' . $tag . '>';

    return $output;

}





/**
 *
 * Method to set login auth
 *
 */
function theme_mb2nl_get_login_auth() {
    global $PAGE;

    $output = '';
    $authsequence = get_enabled_auth_plugins(true); // Get all auths, in sequence.
    $potentialidps = [];

    foreach ($authsequence as $authname) {
        $authplugin = get_auth_plugin($authname);
        $potentialidps = array_merge($potentialidps, $authplugin->loginpage_idp_list($PAGE->url->out(false)));
    }

    if (! empty($potentialidps)) {
        $output .= '<div class="potentialidps">';
        $output .= '<h4 class="sr-only">' . get_string('potentialidps', 'auth') . '</h4>';
        $output .= '<div class="potentialidplist">';

        foreach ($potentialidps as $idp) {
            $output .= '<div class="potentialidp">';
            $output .= '<a class="btn btn-socimage btn-' . s($idp['name']) . '" href="' . $idp['url']->out() . '">';

            if (! empty($idp['iconurl'])) {
                $output .= '<span class="btn-image" aria-hidden="true">';
                $output .= '<img src="' . s($idp['iconurl']) . '" alt="' . s($idp['name']) . '">';
                $output .= '</span>';
            }

            $output .= '<span class="btn-text">';
            $output .= get_string('continuewith', 'theme_mb2nl', s($idp['name']));
            $output .= '</span>';
            $output .= '</a>';
            $output .= '</div>';
        }

        $output .= '</div>';
        $output .= '</div>';
        $output .= '<div class="text-separator"><div><span>' . get_string('or', 'availability') . '</span></div></div>';

    }

    return $output;

}






/**
 *
 * Method to get moadl window with search and login form
 *
 */
function theme_mb2nl_modal_tmpl($type) {
    global $PAGE;
    $output = '';
    $cls = $type ? ' ' . $type : '';

    if (theme_mb2nl_theme_setting($PAGE, 'loginlinktopage') && $type === 'login' && (!isloggedin() || isguestuser())) {
        return;
    }

    $output .= '<div id="header-modal-' . $type . '" class="modal theme-modal-scale theme-forms' . $cls . '" role="dialog"
    tabindex="0" aria-labelledby="header-modal-' . $type . '" aria-describedby="header-modal-' . $type . '" aria-modal="true">';
    $output .= '<div class="modal-dialog" role="document">';
    $output .= '<div class="modal-content">';
    $output .= '<div class="theme-modal-container">';
    $output .= '<button class="close-container themereset" data-dismiss="modal" aria-label="' .
    get_string('closebuttontitle') . '">&times;</button>';

    if ($type === 'login') {
        $output .= theme_mb2nl_login_form(true);
    } else if ($type === 'search') {
        $output .= theme_mb2nl_search_form();
    }

    $output .= '<button class="themereset themekeynavonly" data-dismiss="modal">' . get_string('closebuttontitle') . '</button>';
    $output .= '</div>';
    $output .= '</div>';
    $output .= '</div>';
    $output .= '</div>';

    return $output;

}
