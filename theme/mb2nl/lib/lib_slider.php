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
 * @copyright 2018 - 2020 Mariusz Boloz (lmsstyle.com)
 * @license   PHP and HTML: http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later. Other parts: http://themeforest.net/licenses
 *
 */



/**
 *
 * Method to display slider items
 *
 */
function theme_mb2nl_slider($items = []) {
    global $CFG, $PAGE;

    $slider = theme_mb2nl_theme_setting($PAGE, 'slider');
    $opt = get_config('local_mb2slides');
    $uniqid = uniqid();
    $output = '';
    $cls = '';

    // Check if user want to use slider on front page.
    if (!$slider) {
        return;
    }

    // Cehck if local slides plugi is installed.
    if (! file_exists($CFG->dirroot . '/local/mb2slides/index.php')) {
        if (is_siteadmin()) {
            return '<div class="alert alert-warning" style="max-width:90%;margin:1rem auto;" role="alert">' .
            get_string('mb2slides_plugin', 'theme_mb2nl') . '</div>';
        } else {
            return;
        }
    } else {
        // Slides plugin is installed!
        // Get slides api.
        if (! class_exists('Mb2slidesApi')) {
            require_once($CFG->dirroot . '/local/mb2slides/classes/api.php');
        }

        // Get slides.
        $items = Mb2slidesApi::get_sortorder_items();

        // Check if some slides exists.
        if (!count($items)) {
            if (is_siteadmin()) {
                return '<div class="alert alert-success" style="max-width:90%;margin:1rem auto;" role="alert">' .
                get_string('mb2slides_plugin_empty', 'theme_mb2nl', ['link' => $CFG->wwwroot . '/local/mb2slides/index.php'])
                . '</div>';
            } else {
                return;
            }
        }
    }

    // Set slider css classess and styles.
    $cls .= ' navdir' . $opt->navdir;
    $styleslider = $opt->slidermargin ? ' style="padding:' . $opt->slidermargin . '"' : '';

    $output .= '<div id="main-slider" class="mb2slides mb2slides-mainslider mb2slides' . $uniqid . $cls . '"' . $styleslider . '>';
    $output .= '<div class="mb2slides-inner">';
    $output .= '<ul class="mb2slides-slide-list"' . theme_mb2nl_slider_data_attr($uniqid) . '>';

    foreach ($items as $itemid) {
        if (count($items) && ! in_array($itemid , $items)) {
            continue;
        }

        $output .= theme_mb2nl_slider_item($itemid);
    }

    $output .= '</ul>'; // ...mb2slides-slide-list
    $output .= '</div>'; // ...mb2slides-inner
    $output .= '</div>'; // ...mb2slides

    // Set links for tab key navigation.
    foreach ($items as $itemid) {
        if (count($items) && ! in_array($itemid , $items)) {
            continue;
        }

        $item = Mb2slidesApi::get_record($itemid);
        $attribs = json_decode($item->attribs);

        if (! Mb2slidesHelper::can_see($item)) {
            continue;
        }

        if (! $attribs->link) {
            continue;
        }

        $linktarget = $attribs->linktarget ? ' target="_blank"' : '';
        $output .= '<a class="sr-only sr-only-focusable" href="' . $attribs->link . '"' . $linktarget . ' tabindex="0">' .
        $item->title . '</a>';

    }

    return $output;

}



/**
 *
 * Method to display slider item
 *
 */
function theme_mb2nl_slider_item($itemid) {
    global $CFG;

    // Get slider api.
    if (!class_exists('Mb2slidesApi')) {
        require_once($CFG->dirroot . '/local/mb2slides/classes/api.php');
    }

    if (!class_exists('Mb2slidesHelper')) {
        require_once($CFG->dirroot . '/local/mb2slides/classes/helper.php');
    }

    $output = '';
    $cls = '';
    $clscaption = '';
    $stylecaption = '';
    $stylecaption3 = '';
    $clscaptioncontent = '';
    $styletitle = '';
    $item = Mb2slidesApi::get_record($itemid);
    $opt = get_config('local_mb2slides');
    $attribs = json_decode($item->attribs);
    $cstylepre = Mb2slidesHelper::get_param($itemid, 'cstylepre');
    $titlefs = str_replace(',', '.', Mb2slidesHelper::get_param($itemid, 'titlefs'));
    $titlefs = trim($titlefs);
    $linkslide = ($attribs->link && !Mb2slidesHelper::get_param($itemid, 'linkbtn') &&
    !theme_mb2nl_check_for_tags(Mb2slidesHelper::get_item_content($item), 'a'));
    $linktarget = $attribs->linktarget ? ' target="_blank"' : '';
    $linkbtntext = Mb2slidesHelper::get_param($itemid, 'linkbtntext') ? Mb2slidesHelper::get_param($itemid, 'linkbtntext') :
    get_string('readmore', 'local_mb2slides');

    if (! Mb2slidesHelper::can_see($item)) {
        return;
    }

    // Caption classes.
    $clscaption .= ' hor-' . Mb2slidesHelper::get_param($itemid, 'chalign');
    $clscaption .= ' ver-' . Mb2slidesHelper::get_param($itemid, 'cvalign');
    $clscaption .= ' anim' . $opt->captionanim;
    $clscaption .= ($cstylepre === 'custom' && Mb2slidesHelper::get_param($itemid, 'cbgcolor') === '') ? ' nobg' : '';
    $clscaption .= $attribs->showtitle ? ' istitle' : ' notitle';

    // Caption content classes.
    if ($cstylepre === 'circle' && $opt->navdir == 2) {
        $clscaptioncontent .= ' nocircle';
    } else if ($cstylepre === 'border') {
        $clscaptioncontent .= ' csborder';
    } else {
        $clscaptioncontent .= ' ' . $cstylepre;
    }

    $clscaptioncontent .= Mb2slidesHelper::get_param($itemid, 'cshadow') ? ' isshadow' : '';

    // Caption style.
    $stylecaption .= ' style="';

    // Caption style for circle.
    if ($cstylepre === 'circle' && $opt->navdir != 2) {
        $stylecaption .= 'width:' . Mb2slidesHelper::get_param($itemid, 'captionw') . 'px;';
        $stylecaption .= 'height:' . Mb2slidesHelper::get_param($itemid, 'captionw') . 'px;';
    } else if ($cstylepre === 'fullwidth') {
        $stylecaption3 = ' style="max-width:' . Mb2slidesHelper::get_param($itemid, 'captionw') . 'px;"';
    } else {
        $stylecaption .= 'max-width:' . Mb2slidesHelper::get_param($itemid, 'captionw') . 'px;';
    }

    $stylecaption .= Mb2slidesHelper::get_param($itemid, 'cbgcolor') ? 'background-color:' .
    Mb2slidesHelper::get_param($itemid, 'cbgcolor') . ';' : '';
    $stylecaption .= '"';

    // Title style.
    $styletitle .= ' style="';
    $styletitle .= Mb2slidesHelper::get_param($itemid, 'titlecolor') ? 'color:' . Mb2slidesHelper::get_param($itemid, 'titlecolor').
     ';' : '';
    $styletitle .= 'font-size:' . $titlefs . 'rem;';
    $styletitle .= '"';

    // Content width.
    $contentwidthstyle = $opt->contentwidth !== '' ? 'width:' . str_replace('px', '', $opt->contentwidth) . 'px;' : '';

    $output .= '<li class="mb2slides-slide-item caption-' . $cstylepre . '">';
    $output .= theme_mb2nl_slider_actions($item);
    $output .= $linkslide ? '<a class="fillslide-link" href="' . $attribs->link . '"' . $linktarget . ' tabindex="-1">' : '';
    $output .= '<div class="mb2slides-slide-item-inner">';
    $output .= '<div class="mb2slides-slide-media" style="background-image:url(\'' .
    Mb2slidesHelper::get_image_url($item->id) . '\');">';
    $output .= '<img class="mb2slides-slide-img" src="' . Mb2slidesHelper::get_image_url($item->id) . '" alt=""/>';
    $output .= '</div>'; // ...mb2slides-slide-media

    if (Mb2slidesHelper::get_item_content($item) || $attribs->showtitle) {
        $output .= '<div class="mb2slides-caption' . $clscaption . '">';
        $output .= '<div class="mb2slides-caption1" style="' . $contentwidthstyle . 'margin:0 auto;">';
        $output .= '<div class="mb2slides-caption2">';
        $output .= '<div class="mb2slides-caption3">';
        $output .= '<div class="mb2slides-caption-content' . $clscaptioncontent . '"' . $stylecaption . '>';
        $output .= '<div class="mb2slides-caption-content2">';
        $output .= '<div class="mb2slides-caption-content3"' . $stylecaption3 . '>'; // ...caption width in full width style
        $output .= $attribs->showtitle ? '<h4 class="mb2slides-title"' . $styletitle . '>' . $item->title . '</h4>' : '';
        $output .= theme_mb2nl_slider_item_desc($item);

        // Caption 'border' style.
        $cborderstyle = Mb2slidesHelper::get_param($itemid, 'cbordercolor') != '' ? ' style="background-color:' .
        Mb2slidesHelper::get_param($itemid, 'cbordercolor') . ';"' : '';
        $output .= $cstylepre === 'border' ? '<span class="mb2slides-border"' . $cborderstyle . '></span>' : '';

        $output .= '</div>'; // ...mb2slides-caption-content3
        $output .= '</div>'; // ...mb2slides-caption-content2
        $output .= '</div>'; // ...mb2slides-caption-content
        $output .= '</div>'; // ...mb2slides-caption3
        $output .= '</div>'; // ...mb2slides-caption2
        $output .= '</div>'; // ...mb2slides-caption1
        $output .= '</div>'; // ...mb2slides-caption
    }

    $output .= Mb2slidesHelper::get_param($itemid, 'imagecolor') ? '<div class="mb2slides-overlay-bg" style="background-color:' .
    Mb2slidesHelper::get_param($itemid, 'imagecolor') . ';"></div>' : '';
    $output .= '</div>'; // ...mb2slides-slide-item-inner
    $output .= $linkslide ? '</a>' : '';
    $output .= '</li>'; // ...mb2slides-slide-item

    return $output;

}




/**
 *
 * Method to display slider item
 *
 */
function theme_mb2nl_slider_item_desc($item) {
    global $CFG;

    if (!class_exists('Mb2slidesHelper')) {
        require_once($CFG->dirroot . '/local/mb2slides/classes/helper.php');
    }

    $output = '';

    $styledesc = '';
    $clsnav = '';
    $stylebtn = '';
    $itemid = $item->id;
    $opt = get_config('local_mb2slides');
    $cstylepre = Mb2slidesHelper::get_param($itemid, 'cstylepre');
    $attribs = json_decode($item->attribs);
    $descfs = str_replace(',', '.', Mb2slidesHelper::get_param($itemid, 'descfs'));
    $descfs = trim($descfs);

    if (!Mb2slidesHelper::get_item_content($item)) {
        return;
    }

    // Description style.
    $styledesc .= ' style="';
    $styledesc .= Mb2slidesHelper::get_param($itemid, 'desccolor') ? 'color:' .
    Mb2slidesHelper::get_param($itemid, 'desccolor') . ';' : '';
    $styledesc .= 'font-size:' . $descfs . 'rem;line-height:' . round($descfs * 1.65, 10) . 'rem;';
    $styledesc .= '"';

    // Button style.
    if (Mb2slidesHelper::get_param($itemid, 'btncolor')) {
        $stylebtn .= ' style="';
        $stylebtn .= 'background-color:' . Mb2slidesHelper::get_param($itemid, 'btncolor') . ';';
        $stylebtn .= 'border-color:' . Mb2slidesHelper::get_param($itemid, 'btncolor') . ';';
        $stylebtn .= '"';
    }

    // Check link button.
    $linkbtn = ($attribs->link && Mb2slidesHelper::get_param($itemid, 'linkbtn'));
    $linkbtntext = Mb2slidesHelper::get_param($itemid, 'linkbtntext') ? Mb2slidesHelper::get_param($itemid, 'linkbtntext') :
    get_string('readmore', 'local_mb2slides');
    $linkbtntarget = $attribs->linktarget ? ' target="_blank"' : '';
    $linkbtncls = $opt->navdir == 2 ? '' : Mb2slidesHelper::get_param($itemid, 'linkbtncls');

    // Custom navigation css class.
    $clsnav .= $linkbtn ? ' islink' : ' nolink';

    $output .= '<div class="mb2slides-description">';
    $output .= '<div class="mb2slides-text"' . $styledesc . '>';
    $output .= Mb2slidesHelper::get_item_content($item);
    $output .= '</div>'; // ...mb2slides-text

    $output .= $opt->navdir == 2 ? '<div class="mb2slides-captionnav' . $clsnav . '">' : '';
    $output .= $opt->navdir == 2 ? '<span class="mb2slides-prevslide"><i class="fa fa-angle-left"></i></span>' : '';

    if ($linkbtn) {
        $output .= '<a href="' . $attribs->link . '" class="mb2slides-btn ' . $linkbtncls . '"' . $linkbtntarget . $stylebtn
        . ' tabindex="-1">';
        $output .= $linkbtntext;
        $output .= '</a>';
    }

    $output .= $opt->navdir == 2 ? '<span class="mb2slides-nextslide"><i class="fa fa-angle-right"></i></span>' : '';
    $output .= $opt->navdir == 2 ? '</div>' : ''; // ...mb2slides-captionnav

    $output .= '</div>'; // ...mb2slides-description
    $output .= $linkbtn ? '<a href="' . $attribs->link . '" class="mb2slides-btn-mobile"' . $linkbtntarget .
    $stylebtn . ' tabindex="-1">&#43;</a>' : '';

    return $output;

}



/**
 *
 * Method to display slides actions
 *
 */
function theme_mb2nl_slider_actions($item) {
    global $PAGE;
    $output = '';
    $languages = Mb2slidesHelper::get_languages($item);
    $canmanage = has_capability('local/mb2slides:manageitems', context_system::instance());
    $linkedit = new moodle_url('/local/mb2slides/edit.php', ['itemid' => $item->id, 'returnurl' =>
    $PAGE->url->out_as_local_url()]);
    $linkadd = new moodle_url('/local/mb2slides/edit.php');
    $linkdelete = new moodle_url('/local/mb2slides/delete.php', ['deleteid' => $item->id]);
    $linkhideshow = new moodle_url('/local/mb2slides/index.php', ['hideshowid' => $item->id, 'returnurl' =>
    $PAGE->url->out_as_local_url()]);

    if (!$canmanage) {
        return;
    }

    $output .= '<div class="mb2slides-actions" tabindex="-1">';

    $output .= '<a class="mb2slides-action action-edit" href="' . $linkedit . '" data-toggle="tooltip" aria-label="' .
    get_string('editslide', 'local_mb2slides') . '" tabindex="-1">';
    $output .= '<i class="fa fa-pencil"></i>';
    $output .= '</a>';

    $showhideicon = $item->enable ? 'fa-eye' : 'fa-eye-slash';
    $showhidestr = $item->enable ? get_string('disableslide', 'local_mb2slides') : get_string('enableslide', 'local_mb2slides');

    $output .= '<a class="mb2slides-action action-hideshow" href="' . $linkhideshow . '" data-toggle="tooltip" aria-label="' .
    $showhidestr . '" tabindex="-1">';
    $output .= '<i class="fa ' . $showhideicon . '"></i>';
    $output .= '</a>';

    $output .= '<a class="mb2slides-action action-add" href="' . $linkadd . '" data-toggle="tooltip" aria-label="' .
    get_string('addslide', 'local_mb2slides') . '" tabindex="-1">';
    $output .= '<i class="fa fa-plus"></i>';
    $output .= '</a>';

    $output .= '<a class="mb2slides-action action-delete" href="' . $linkdelete . '" data-toggle="tooltip" aria-label="' .
    get_string('deleteslide', 'local_mb2slides') . '" tabindex="-1">';
    $output .= '<i class="fa fa-trash"></i>';
    $output .= '</a>';

    if ($item->access == 1 || $item->access == 2) {
        if ($item->access == 1) {
            $visibletostr = get_string('userscansee', 'local_mb2slides');
            $visibletoicon = 'fa-lock';
        } else if ($item->access == 2) {
            $visibletostr = get_string('guestscansee', 'local_mb2slides');
            $visibletoicon = 'fa-unlock';
        }

        $output .= '<span class="mb2slides-action action-access" data-toggle="tooltip" title="' . $visibletostr . '">';
        $output .= '<i class="fa ' . $visibletoicon . '"></i>';
        $output .= '</span>';
    }

    if (count($languages)) {
        $output .= '<span class="mb2slides-action action-languages" data-toggle="tooltip" title="' .
        get_string('language', 'moodle') . ': ' . implode(',', $languages) . '">';
        $output .= '<i class="fa fa-globe"></i>';
        $output .= '</span>';
    }

    $output .= '</div>'; // ...mb2slides-actions

    return $output;

}



/**
 *
 * Method to display slider attribute
 *
 */
function theme_mb2nl_slider_data_attr($id = 0) {

    $output = '';
    $opt = get_config('local_mb2slides');

    $output .= ' data-mode="' . $opt->animtype . '"';
    $output .= ' data-auto="' . $opt->animauto . '"';
    $output .= ' data-aspeed="' . $opt->animspeed . '"';
    $output .= ' data-apause="' . $opt->animpause . '"';
    $output .= ' data-loop="' . $opt->animloop . '"';
    $output .= ' data-pager="' . $opt->navpager . '"';
    $output .= ' data-control="' . $opt->navdir . '"';
    $output .= ' data-items="1"';
    $output .= ' data-moveitems="1"';
    $output .= ' data-margin=""';
    $output .= ' data-aheight="1"';
    $output .= ' data-kpress="1"';
    $output .= ' data-modid="' . $id . '"';
    $output .= ' data-slidescount="2"';

    return $output;

}
