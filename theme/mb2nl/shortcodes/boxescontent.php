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

mb2_add_shortcode('boxescontent_item', 'shortcode_boxescontent_item');

/**
 *
 * Method to define boxes content shortcode
 *
 * @return HTML
 */
function shortcode_boxescontent_item($atts, $content = null) {

    global $gl0bxheight,
    $gl0bxlinkbtn,
    $gl0bxbtntext,
    $gl0bxlinkbtntype,
    $gl0bxlinkbtnsize,
    $gl0bxlinkbtnrounded,
    $gl0bxlinkbtnborder,
    $gl0bxlinkbtnfwcls,
    $gl0bxtype,
    $gl0bxcolor,
    $gl0bxdesc,
    $gl0bxmb,
    $gl0bximgwidth,
    $gl0bxtfs,
    $gl0bxtfw,
    $gl0bxtlh,
    $gl0bxcwidth,
    $gl0bxborder;

    $atts2 = [
        'id' => 'boxescontent_item',
        'icon' => '',
        'title' => 'Box title here',
        'link' => '',
        'bgimage' => '',
        'link_target' => 0,

        'btncolor' => '',
        'btnbgcolor' => '',
        'btnbghcolor' => '',
        'btnhcolor' => '',
        'btnborcolor' => '',
        'btnborhcolor' => '',

        'ccolor' => '',
        'bgcolor' => '',
        'bocolor' => '',
        'tcolor' => '',
        'txcolor' => '',

    ];

    $a = mb2_shortcode_atts($atts2, $atts);

    $output = '';
    $fcls = '';
    $boxstyle = '';
    $stylebgimg = '';
    $boxscttyle = '';
    $heightstyle = '';
    $cls = '';
    $btnstyle = '';

    $cls .= $a['bgimage'] ? ' lazy' : '';

    $a['btntext'] = $gl0bxbtntext ? theme_mb2nl_format_str($gl0bxbtntext) : get_string('readmorefp', 'local_mb2builder');
    $a['target'] = $a['link_target'] ? ' target="_blank"' : '';

    if ($gl0bxcwidth) {
        $boxscttyle .= ' style="';
        $boxscttyle .= 'max-width:' . $gl0bxcwidth . 'px;';
        $boxscttyle .= '"';
    }

    $lazybg = $a['bgimage'] ? ' data-bg="'. $a['bgimage'] . '"' : '';

    if ($gl0bxmb || $a['bgcolor'] || $gl0bxborder || $a['bocolor'] || $a['ccolor'] || $a['tcolor'] || $a['txcolor']) {
        $boxstyle .= ' style="';
        $boxstyle .= $gl0bxmb ? 'margin-bottom:' . $gl0bxmb . 'px;' : '';
        $boxstyle .= $gl0bxborder ? '--mb2-pb-bxborder:' . $gl0bxborder .'px;' : '';
        $boxstyle .= $a['bgcolor'] ? '--mb2-pb-bxbgcolor:' . $a['bgcolor'] .';' : '';
        $boxstyle .= $a['bocolor'] ? '--mb2-pb-bxbocolor:' . $a['bocolor'] .';' : '';
        $boxstyle .= $a['ccolor'] ? '--mb2-pb-bxaccolor:' . $a['ccolor'] . ';' : '';
        $boxstyle .= $a['tcolor'] ? '--mb2-pb-bxtcolor:' . $a['tcolor'] . ';' : '';
        $boxstyle .= $a['txcolor'] ? '--mb2-pb-bxtxcolor:' . $a['txcolor'] . ';' : '';
        $boxstyle .= '"';
    }

    if ($gl0bxheight) {
        $heightstyle .= ' style="';
        $heightstyle .= 'min-height:' . $gl0bxheight . 'px;';
        $heightstyle .= '"';
    }

    if ($a['btncolor'] || $a['btnbgcolor'] || $a['btnbghcolor'] || $a['btnhcolor'] || $a['btnborcolor'] || $a['btnborhcolor']) {
        $btnstyle .= ' style="';
        $btnstyle .= $a['btncolor'] ? '--mb2-pb-btn-color:' . $a['btncolor'] . ';' : '';
        $btnstyle .= $a['btnbgcolor'] ? '--mb2-pb-btn-bgcolor:' . $a['btnbgcolor'] . ';' : '';
        $btnstyle .= $a['btnbghcolor'] ? '--mb2-pb-btn-bghcolor:' . $a['btnbghcolor'] . ';' : '';
        $btnstyle .= $a['btnhcolor'] ? '--mb2-pb-btn-hcolor:' . $a['btnhcolor'] . ';' : '';
        $btnstyle .= $a['btnborcolor'] ? '--mb2-pb-btn-borcolor:' . $a['btnborcolor'] . ';' : '';
        $btnstyle .= $a['btnborhcolor'] ? '--mb2-pb-btn-borhcolor:' . $a['btnborhcolor'] . ';' : '';
        $btnstyle .= '"';
    }

    $fcls .= ' fw' . $gl0bxtfw;

    $output .= '<div class="mb2-pb-subelement mb2-pb-boxescontent_item theme-box">';
    $output .= '<div class="mb2-pb-subelement-inner">';
    $output .= '<div class="boxcontent position-relative' . $cls . '"' . $boxstyle . $lazybg . '>';
    $output .= '<div class="boxcontent-inner"' . $heightstyle . '>';
    $output .= '<div class="boxcontent-content"' . $boxscttyle . '>';
    $output .= '<h4 class="boxcontent-title' . $fcls . '" style="font-size:' . $gl0bxtfs . 'rem;">';
    $output .= $a['link'] ? '<a href="' . $a['link'] . '"' . $a['target'] . '>' : '';
    $output .= theme_mb2nl_format_str($a['title']);
    $output .= $a['link'] ? '</a>' : '';
    $output .= '</h4>';
    $output .= '<div class="boxcontent-desc">' . theme_mb2nl_format_str(urldecode($content)) . '</div>';
    $output .= '</div>'; // ...boxcontent-content

    if ($a['link']) {
        $output .= '<div class="boxcontent-readmore">';
        $output .= $gl0bxlinkbtn == 1 ? '<a href="' . $a['link'] . '"' .
        $a['target'] . ' class="arrowlink"' . $btnstyle . '>' . $a['btntext'] . '</a>' : '';
        $output .= $gl0bxlinkbtn == 2 ? '<a href="' . $a['link'] . '"' .
        $a['target'] . ' class="mb2-pb-btn type' . $gl0bxlinkbtntype . ' size' .
        $gl0bxlinkbtnsize . ' rounded' . $gl0bxlinkbtnrounded . ' btnborder' . $gl0bxlinkbtnborder . ' fw' .
        $gl0bxlinkbtnfwcls . '"' . $btnstyle . '>' . $a['btntext'] . '</a>' : '';
        $output .= '</div>'; // ...theme-boxicon-readmore
    }

    $output .= '</div>'; // ...boxcontent-inner
    $output .= $a['bgcolor'] ? '<div class="bgcolor"></div>' : '';
    $output .= '<div class="elcolor-el">';
    $output .= '<div class="elcolor1"></div>';
    $output .= '<div class="elcolor2"></div>';
    $output .= '</div>'; // ...elcolor-el
    $output .= '</div>'; // ...boxcontent
    $output .= $a['link'] ? '<a class="linkabs" href="' . $a['link'] . '"' . $a['target'] . ' tabindex="0" aria-label="' .
    theme_mb2nl_format_str($a['title']) . '"></a>' : '';
    $output .= '</div>'; // ...mb2-pb-subelement-inner
    $output .= '</div>'; // ...mb2-pb-boxescontent_item

    return $output;

}
