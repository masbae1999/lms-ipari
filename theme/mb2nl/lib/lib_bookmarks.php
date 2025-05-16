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
 * Method to display custom user bookmarks
 *
 */
function theme_mb2nl_user_bookmarks() {
    global $CFG, $PAGE;

    if (! theme_mb2nl_theme_setting($PAGE, 'bookmarks') || ! isloggedin() || isguestuser()) {
        return;
    }

    $output = '';
    $bookmarkaddremove = '';
    $urlarr = [];

    $bookmarkurl = htmlspecialchars_decode(str_replace($CFG->wwwroot, '', $PAGE->url));
    $tempbookmarks = ! is_null(get_user_preferences('user_bookmarks')) ? explode(',', get_user_preferences('user_bookmarks')) : [];
    $bookmarkmanageattr = ' data-toggle="modal" data-target="#theme-bookmarks-modal"';

    $output .= '<li class="bookmarks-item dropdown level-1 isparent onhover">';
    $output .= '<button type="button" class="themereset mb2mm-action">';
    $output .= '<span class="text mb2mm-label">' . get_string('mybookmarks', 'theme_mb2nl') . '</span>';
    $output .= '<span class="mb2mm-arrow"></span>';
    $output .= '</button>';
    $output .= '<button type="button" class="mb2mm-toggle themereset" aria-label="' .
    get_string('togglemenuitem', 'theme_mb2nl', ['menuitem' =>
    get_string('mybookmarks', 'theme_mb2nl')]) . '" aria-expanded="false"></button>';

    $output .= '<div class="mb2mm-ddarrow"></div>';

    $output .= '<ul class="theme-bookmarks dropdown-list mb2mm-dd">';

    foreach ($tempbookmarks as $b) {
        $barr = explode(';', $b);
        $barr0 = isset($barr[0]) ? $barr[0] : '';
        $blink = new moodle_url($barr0, []);
        $title = isset($barr[1]) ? $barr[1] : '';

        if ($barr0) {
            $output .= '<li class="level-2" data-url="' . $barr0 . '">';
            $output .= '<a class="bookmark-link mb2mm-action" href="' . $blink . '">';
            $output .= '<span class="mb2mm-label">' . trim($title) . '</span>';
            $output .= '</a>';
            $output .= '<div class="theme-bookmarks-action">';
            $output .= '<button type="button" class="theme-bookmarks-form bookmark-edit themereset" data-url="' .
            $barr0 . '" data-mb2bktitle="' . trim($title) . '"' . $bookmarkmanageattr . ' aria-label="' .
            get_string('edit') . '"><i class="fa fa-pencil"></i></button>';
            $output .= '<button type="button" class="theme-bookmarks-form bookmark-delete themereset" data-url="' .
            $barr0 . '" data-mb2bktitle="' . trim($title) . '" aria-label="' .
            get_string('remove') . '"><i class="fa fa-times"></i></button>';
            $output .= '</div>';
            $output .= '</li>';

            $urlarr[] = $barr0;
        }
    }

    $isbokmarked = in_array(trim($bookmarkurl), $urlarr);

    if ($isbokmarked) {
        $bookmarkmanageattr = '';
        $bookmarkaddremove = ' bookmark-delete';
    }

    $output .= '<li class="theme-bookmarks-add">';
    $output .= '<button type="button" class="theme-bookmarks-form' . $bookmarkaddremove . ' themereset mb2mm-action" data-url="' .
    trim($bookmarkurl) . '" data-mb2bktitle="' . theme_mb2nl_page_title(true) . '"' . $bookmarkmanageattr . '>';
    $output .= '<span class="mb2mm-label">';
    $output .= $isbokmarked ? get_string('unbookmarkthispage', 'admin') : get_string('bookmarkthispage', 'admin');
    $output .= '</span>';
    $output .= '</button>';
    $output .= '</li>';

    $output .= '</ul>';
    $output .= '</li>';
    return $output;

}



/**
 *
 * Method to get moadl window with search form
 *
 */
function theme_mb2nl_user_bookmarks_modal () {
    global $PAGE, $CFG, $OUTPUT;
    $output = '';

    $createurl = new moodle_url(theme_mb2nl_themedir() . '/mb2nl/lib/lib_ajax_bookmarks_manage.php', []);
    $bookmarkurl = htmlspecialchars_decode(str_replace($CFG->wwwroot, '', $PAGE->url));
    $bklimit = theme_mb2nl_theme_setting($PAGE, 'bookmarkslimit', 15);

    $output .= '<div id="theme-bookmarks-modal" class="modal fade" role="dialog" tabindex="0">';
    $output .= '<div class="modal-dialog modal-sm" role="document">';
    $output .= '<div class="modal-content">';
    $output .= '<div class="modal-header">';
    $output .= '<button type="button" class="close" data-dismiss="modal" aria-label="' . get_string('closebuttontitle')
    . '">&times;</button>';
    $output .= '<h4 class="modal-title">' . get_string('mybookmarks', 'theme_mb2nl') . '</h4>';
    $output .= '</div>';
    $output .= '<div class="modal-body">';
    $output .= '<form method="get" id="theme-bookmarks-form" action="index.php" data-rooturl="'.$CFG->wwwroot.'" data-pageurl="' .
    $bookmarkurl . '" data-bookmarkthispage="' .
    get_string('bookmarkthispage', 'admin') . '" data-unbookmarkthispage="' . get_string('unbookmarkthispage', 'admin')
    . '" data-pagetitle="' . theme_mb2nl_page_title(true) . '" data-wentwrong="'. get_string('wentwrong', 'theme_mb2nl') . '">';

    $output .= '<div class="form-group  mb2-pb-form-group">';
    $output .= '<label for="mb2bktitle">' . get_string('title', 'backup') . '</label>';
    $output .= '<input type="text" id="mb2bktitle" class="form-control" name="mb2bktitle" style="margin:0;width:100%;" value="">';
    $output .= '</div>';

    $output .= '<div class="form-group  mb2-pb-form-group">';
    $output .= '<label for="mb2bkurl">' . get_string('url', 'core') . '</label>';
    $output .= '<input type="text" id="mb2bkurl" class="form-control" name="mb2bkurl" value="" style="margin:0;width:100%;"
    readonly>';
    $output .= '</div>';

    $output .= '<input type="hidden" id="mb2bkcreateurl" name="mb2bkcreateurl" value="' . $createurl . '">';
    $output .= '<input type="hidden" id="bkdelete" name="bkdelete" value="">';
    $output .= '<input type="hidden" id="bkupdate" name="bkupdate" value="">';
    $output .= '<input type="hidden" id="bklimit" name="bklimit" value="' . $bklimit . '">';
    $output .= '<input type="hidden" id="sesskey" name="sesskey" value="' . sesskey() . '">';
    $output .= '<input style="display:none;" class="btn btn-default" type="submit" name="submit" value="' .
    get_string('save', 'admin') . '">';

    $loadingimg = $CFG->version >= 2017051500 ? $OUTPUT->image_url('spinners/spinner-default', 'theme') :
    $OUTPUT->pix_url('spinners/spinner-default', 'theme');
    $output .= '<div class="loading-bg"><img src="' . $loadingimg . '" alt="" /></div>';
    $output .= '</form>';
    $output .= '</div>';

    $output .= '<div class="modal-footer">';
    $output .= '<button type="button" class="btn btn-sm btn-success theme-bookmarks-save">' .
    get_string('save', 'admin') . '</button>';
    $output .= '<button type="button" class="btn btn-sm btn-danger" data-dismiss="modal">' .
    get_string('close', 'form') . '</button>';
    $output .= '</div>';

    $output .= '</div>';
    $output .= '</div>';
    $output .= '</div>';

    return $output;

}
