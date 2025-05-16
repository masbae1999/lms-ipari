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

mb2_add_shortcode('mb2pb_testimonials', 'mb2_shortcode_mb2pb_testimonials');
mb2_add_shortcode('mb2pb_testimonials_item', 'mb2_shortcode_mb2pb_testimonials_item');

/**
 *
 * Method to define shortcode
 *
 * @return HTML
 */
function mb2_shortcode_mb2pb_testimonials($atts, $content = null) {

    global $gl0carouseluniqid,
    $gl0carouselitem;

    $atts2 = [
        'id' => 'testimonials',
        'mt' => 0,
        'mb' => 30,
        'width' => '',
        'custom_class' => '',
        'clayout' => 2, // 1 - columns layout, 2 - creative layout
        'columns' => 4,
        'gutter' => 'normal',
        'isimage' => 1,
        'iscompany' => 1,
        'isjob' => 1,
        'mobcolumns' => 1,
        'sloop' => 0,
        'snav' => 0,
        'sdots' => 1,
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
    $gl0carouseluniqid = $uniqid;
    $gl0carouselitem = 0;

    $cls .= ' snav' . $a['snav'];
    $cls .= ' sdots' . $a['sdots'];
    $cls .= ' isimage' . $a['isimage'];
    $cls .= ' iscompany' . $a['iscompany'];
    $cls .= ' isjob' . $a['isjob'];
     $cls .= $a['custom_class'] ? ' ' . $a['custom_class'] : '';
    $cls .= $a['template'] ? ' mb2-pb-template-testimonials' : '';

    if ($a['mt'] || $a['mb']) {
        $style .= ' style="';
        $style .= $a['mt'] ? 'margin-top:' . $a['mt'] . 'px;' : '';
        $style .= $a['mb'] ? 'margin-bottom:' . $a['mb'] . 'px;' : '';
        $style .= '"';
    }

    // Define default content.
    if (! $content) {
        $demoimage = theme_mb2nl_dummy_image('100x100');

        for ($i = 1; $i <= 5; $i++) {
            $content .= '[mb2pb_testimonials_item pbid="" image="' . $demoimage . '" ][/mb2pb_testimonials_item]';
        }
    }

    // Get carousel content for sortable elements.
    $regex = '\\[(\\[?)(mb2pb_testimonials_item)\\b([^\\]\\/]*(?:\\/(?!\\])[^\\]\\/]*)*?)';
    $regex .= '(?:(\\/)\\]|\\](?:([^\\[]*+(?:\\[(?!\\/\\2\\])[^\\[]*+)*+)\\[\\/\\2\\])?)(\\]?)';
    preg_match_all("/$regex/is", $content, $match);
    $content = $match[0];

    $output .= '<div class="mb2-pb-element mb2-pb-carousel mb2-pb-testimonials' . $cls . '"' .
    $style . theme_mb2nl_page_builder_el_datatts($atts, $atts2) . '>';
    $output .= '<div class="element-helper"></div>';
    $output .= theme_mb2nl_page_builder_el_actions('element', 'testimonials');
    $output .= '<div class="mb2-pb-testimonials-inner">';
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
        $attr['id'] = 'testimonials_item';
        $attr['pbid'] = (isset($attributes['pbid']) && $attributes['pbid']) ? $attributes['pbid'] : $uniqid . $z;
        $attr['image'] = $attributes['image'];
        $attr['name'] = (isset($attributes['name']) && $attributes['name']) ? $attributes['name'] : 'Full Name';
        $attr['job'] = (isset($attributes['job']) && $attributes['job']) ? $attributes['job'] : 'Moodle Dev';
        $attr['rating'] = (isset($attributes['rating']) && $attributes['rating']) ? $attributes['rating'] : 5;
        $attr['companyname'] = (isset($attributes['companyname']) && $attributes['companyname']) ?
        $attributes['companyname'] : 'Company name';
        $attr['text'] = theme_mb2nl_page_builder_shortcode_content_attr($c, mb2_get_shortcode_regex());

        $output .= '<div class="mb2-pb-subelement mb2-pb-carousel_item mb2-pb-testimonials_item"';
        $output .= ' style="background-image:url(\'' . $attr['image'] . '\');"' .
        theme_mb2nl_page_builder_el_datatts($attr, $attr) . '>';
        $output .= theme_mb2nl_page_builder_el_actions('subelement');
        $output .= '<div class="mb2-pb-subelement-inner">';
        $output .= '<img src="' . $attr['image'] . '" class="theme-slider-img-src" alt="" />';
        $output .= '</div>';
        $output .= '</div>';
    }

    $output .= '</div>';
    $output .= '</div>'; // ...mb2-pb-testimonials-inner
    $output .= '</div>';

    return $output;

}


/**
 *
 * Method to define shortcode
 *
 * @return HTML
 */
function mb2_shortcode_mb2pb_testimonials_item($atts, $content = null) {

    global $gl0carouseluniqid,
    $gl0carouselitem;

    $atts2 = [
        'id' => 'testimonials_item',
        'pbid' => '', // ...it's require for sorting elements below carousel items
        'name' => 'Full Name',
        'job' => 'Moodel Dev',
        'companyname' => 'Company name',
        'image' => theme_mb2nl_dummy_image('100x100'),
        'rating' => '5',
        'template' => '',
    ];

    $a = mb2_shortcode_atts($atts2, $atts);

    $output = '';

    if (isset($gl0carouselitem)) {
        $gl0carouselitem++;
    } else {
        $gl0carouselitem = 0;
    }

    $a['pbid'] = $a['pbid'] ? $a['pbid'] : $gl0carouseluniqid . $gl0carouselitem;
    $content = $content ? $content : 'Testimonial content here.';

    $output .= '<div class="mb2-pb-carousel-item mb2-pb-testimonials-item theme-slider-item swiper-slide" data-pbid="' .
    $a['pbid'] . '">';
    $output .= '<div class="testimonials-item-inner">';

    $output .= '<div class="testimonial-image">';
    $output .= '<img class="testimonial-image-src" src="' . $a['image'] . '" alt="' . $a['name'] . '">';
    $output .= '</div>'; // ...testimonial-image

    $output .= '<div class="testimonial-content">';

    $output .= '<div class="testimonial-header">';
    $output .= '<div class="testimonial-meta">';
    $output .= '<span class="testimonial-name">';
    $output .= $a['name'];
    $output .= '</span>';
    $output .= '<span class="testimonial-job">';
    $output .= $a['job'];
    $output .= '</span>';
    $output .= '<div class="testimonial-companyname">';
    $output .= $a['companyname'];
    $output .= '</div>';
    $output .= '</div>'; // ...testimonial-meta
    $output .= theme_mb2nl_stars($a['rating']);
    $output .= '</div>'; // ...testimonial-header

    $output .= '<div class="testimonial-text">';
    $output .= urldecode($content);
    $output .= '</div>'; // ...testimonial-text

    $output .= '</div>'; // ...testimonial-content

    $output .= '</div>'; // ...testimonials-item-inner
    $output .= '</div>'; // ...mb2-pb-testimonials-item

    return $output;

}
