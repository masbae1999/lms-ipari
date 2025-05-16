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

mb2_add_shortcode('mb2pb_carousel', 'mb2_shortcode_mb2pb_carousel');
mb2_add_shortcode('mb2pb_carousel_item', 'mb2_shortcode_mb2pb_carousel_item');

/**
 *
 * Method to define shortcode
 *
 * @return HTML
 */
function mb2_shortcode_mb2pb_carousel($atts, $content = null) {

    global $PAGE,
    $gl0carouselbtntext,
    $gl0carouseluniqid,
    $gl0carouselitem,
    $gl0carimgwidth,
    $gl0carouseltitlefs,
    $gl0carouseltitlefw,
    $gl0carouseltitlelh;

    $atts2 = [
        'id' => 'carousel',
        'mt' => 0,
        'mb' => 30,
        'width' => '',
        'custom_class' => '',
        'prestyle' => 'nlearning',
        'columns' => 4,
        'gutter' => 'normal',
        'linkbtn' => 0,

        'title' => 1,
        'titlefs' => 1.4,
        'titlefw' => 'global',
        'titlelh' => 'global',

        'imgwidth' => 700,
        'mobcolumns' => 1,
        'desc' => 1,
        'btntext' => '',
        'sloop' => 0,
        'snav' => 1,
        'sdots' => 0,
        'autoplay' => 0,
        'pausetime' => 5000,
        'animtime' => 450,
        'template' => '',
    ];

    $a = mb2_shortcode_atts($atts2, $atts);

    $output = '';
    $attr = [];
    $uniqid = uniqid('carouselitem_');
    $sliderid = $a['template'] ? '' : uniqid('swiper_');
    $sdata = '';
    $style = '';
    $cls = '';

    $gl0carouselbtntext = $a['btntext'];
    $gl0carouseluniqid = $uniqid;
    $gl0carouselitem = 0;
    $gl0carimgwidth = $a['imgwidth'];
    $gl0carouseltitlefs = $a['titlefs'];
    $gl0carouseltitlefw = $a['titlefw'];
    $gl0carouseltitlelh = $a['titlelh'];

    $cls .= ' prestyle' . $a['prestyle'];
    $cls .= ' linkbtn' . $a['linkbtn'];
    $cls .= ' title' . $a['title'];
    $cls .= ' desc' . $a['desc'];
    $cls .= ' snav' . $a['snav'];
    $cls .= ' sdots' . $a['sdots'];
    $cls .= $a['custom_class'] ? ' ' . $a['custom_class'] : '';
    $cls .= $a['template'] ? ' mb2-pb-template-carousel' : '';

    if ($a['mt'] || $a['mb']) {
        $style .= ' style="';
        $style .= $a['mt'] ? 'margin-top:' . $a['mt'] . 'px;' : '';
        $style .= $a['mb'] ? 'margin-bottom:' . $a['mb'] . 'px;' : '';
        $style .= '"';
    }

    // Define default content.
    if (! $content) {
        $demoimage = theme_mb2nl_dummy_image('1600x1066');

        for ($i = 1; $i <= 5; $i++) {
            $content .= '[mb2pb_carousel_item pbid="" image="' . $demoimage . '" ][/mb2pb_carousel_item]';
        }
    }

    // Get carousel content for sortable elements.
    $regex = '\\[(\\[?)(mb2pb_carousel_item)\\b([^\\]\\/]*(?:\\/(?!\\])[^\\]\\/]*)*?)';
    $regex .= '(?:(\\/)\\]|\\](?:([^\\[]*+(?:\\[(?!\\/\\2\\])[^\\[]*+)*+)\\[\\/\\2\\])?)(\\]?)';
    preg_match_all("/$regex/is", $content, $match);
    $content = $match[0];

    $output .= '<div class="mb2-pb-element mb2-pb-carousel' . $cls . '"' . $style .
    theme_mb2nl_page_builder_el_datatts($atts, $atts2) . '>';
    $output .= '<div class="element-helper"></div>';
    $output .= theme_mb2nl_page_builder_el_actions('element', 'carousel');
    $output .= '<div id="' . $sliderid . '" class="mb2-pb-element-inner swiper">';
    $output .= theme_mb2nl_shortcodes_swiper_nav($sliderid);
    $output .= '<div class="swiper-wrapper">';
    foreach ($content as $c) {
        $output .= mb2_do_shortcode($c);
    }
    $output .= '</div>'; // ...swiper-wrapper
    $output .= theme_mb2nl_shortcodes_swiper_pagenavnav();
    $output .= '</div>'; // ...swiper

    $output .= '<div class="mb2-pb-sortable-subelements">';
    $output .= '<a href="#" class="element-items">&#x2715;</a>';
    $z = 0;

    foreach ($content as $c) {

        // Get attributes of carousel items.
        $attributes = shortcode_parse_atts($c);
        $z++;
        $attr['id'] = 'carousel_item';
        $attr['pbid'] = (isset($attributes['pbid']) && $attributes['pbid']) ? $attributes['pbid'] : $uniqid . $z;
        $attr['image'] = $attributes['image'];
        $attr['title'] = (isset($attributes['title']) && $attributes['title']) ? $attributes['title'] : 'Title text';
        $attr['desc'] = (isset($attributes['desc']) && $attributes['desc']) ? $attributes['desc'] : 'Description text';
        $attr['color'] = isset($attributes['color']) ? $attributes['color'] : '';
        $attr['link'] = isset($attributes['link']) ? $attributes['link'] : '';
        $attr['link_target'] = isset($attributes['link_target']) ? $attributes['link_target'] : '';

        $output .= '<div class="mb2-pb-subelement mb2-pb-carousel_item" style="background-image:url(\'' . $attr['image'] . '\');"' .
        theme_mb2nl_page_builder_el_datatts($attr, $attr) . '>';
        $output .= theme_mb2nl_page_builder_el_actions('subelement');
        $output .= '<div class="mb2-pb-subelement-inner">';
        $output .= '<img src="' . $attr['image'] . '" class="theme-slider-img-src" alt="" />';
        $output .= '</div>';
        $output .= '</div>';
    }

    $output .= '</div>';
    $output .= '</div>';

    return $output;

}


/**
 *
 * Method to define shortcode
 *
 * @return HTML
 */
function mb2_shortcode_mb2pb_carousel_item($atts, $content = null) {

    global $gl0carouselbtntext,
    $gl0carouseluniqid,
    $gl0carouselitem,
    $gl0carimgwidth,
    $gl0carouseltitlefs,
    $gl0carouseltitlefw,
    $gl0carouseltitlelh;

    $atts2 = [
        'id' => 'carousel_item',
        'pbid' => '', // ...it's require for sorting elements below carousel items
        'title' => 'Title text',
        'image' => theme_mb2nl_dummy_image('1600x1066'),
        'desc' => 'Description text',
        'color' => '',
        'link' => '',
        'target' => '',
        'link_target' => 0,
        'template' => '',
    ];

    $a = mb2_shortcode_atts($atts2, $atts);

    $output = '';
    $imgstyle = ' style="width:' . $gl0carimgwidth . 'px;"';
    $tcls = '';

    $tcls .= ' fw' . $gl0carouseltitlefw;
    $tcls .= ' lh' . $gl0carouseltitlelh;
    $tcls .= ' ' . theme_mb2nl_tsize_cls($gl0carouseltitlefs);

    if (isset($gl0carouselitem)) {
        $gl0carouselitem++;
    } else {
        $gl0carouselitem = 0;
    }

    $a['pbid'] = $a['pbid'] ? $a['pbid'] : $gl0carouseluniqid . $gl0carouselitem;

    $colorcls = $a['color'] ? ' color1' : '';

    $colorstyle = $a['color'] ? ' style="background-color:' . $a['color'] . ';"' : '';

    $output .= '<div class="mb2-pb-carousel-item theme-slider-item swiper-slide" data-pbid="' . $a['pbid'] . '">';
    $output .= '<div class="theme-slider-item-inner">';

    $output .= '<div class="theme-slider-img">';
    $output .= '<img class="theme-slider-img-src" src="' . $a['image'] . '" alt="' . $a['title'] . '"' . $imgstyle . '>';
    $output .= '</div>';

    $output .= '<div class="theme-slide-content1' . $colorcls . '"' . $colorstyle . '>';
    $output .= '<div class="theme-slide-content2">';
    $output .= '<div class="theme-slide-content3">';
    $output .= '<div class="theme-slide-content4">';
    $output .= '<h4 class="theme-slide-title' . $tcls . '" style="font-size:' . $gl0carouseltitlefs . 'rem;">';
    $output .= $a['title'];
    $output .= '</h4>';
    $output .= '<div class="theme-slider-desc">';
    $output .= $a['desc'];
    $output .= '</div>';
    $a['btntext'] = $gl0carouselbtntext ? $gl0carouselbtntext : get_string('btntext', 'local_mb2builder');
    $output .= '<div class="theme-slider-readmore">';
    $output .= '<a class="mb2-pb-btn typeprimary" href="#">' . $a['btntext'] . '</a>';
    $output .= '</div>';

    $output .= '</div>';
    $output .= '</div>';
    $output .= '</div>';
    $output .= '</div>';

    $output .= '</div>';
    $output .= '</div>';

    return $output;

}
