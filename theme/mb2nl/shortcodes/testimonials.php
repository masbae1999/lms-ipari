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

mb2_add_shortcode('testimonials', 'mb2_shortcode_testimonials');
mb2_add_shortcode('testimonials_item', 'mb2_shortcode_testimonials_item');

/**
 *
 * Method to define shortcode
 *
 * @return HTML
 */
function mb2_shortcode_testimonials($atts, $content = null) {

    global $gl0testimonialsisimage,
    $gl0testimonialsiscompany,
    $gl0testimonialsisjob;

    $atts2 = [
        'mt' => 0,
        'mb' => 30,
        'width' => '',
        'custom_class' => '',
        'clayout' => 2, // 1 - columns layout, 2 - creative layout.
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
    ];

    $a = mb2_shortcode_atts($atts2, $atts);

    $output = '';
    $attr = [];
    $sliderid = uniqid('swiper_');
    $sdata = '';
    $style = '';
    $cls = '';
    $gl0testimonialsisimage = $a['isimage'];
    $gl0testimonialsiscompany = $a['iscompany'];
    $gl0testimonialsisjob = $a['isjob'];

    $cls .= ' snav' . $a['snav'];
    $cls .= ' sdots' . $a['sdots'];
    $cls .= ' isimage' . $a['isimage'];
    $cls .= ' iscompany' . $a['iscompany'];
    $cls .= ' isjob' . $a['isjob'];
    $cls .= ' clayout' . $a['clayout'];
     $cls .= $a['custom_class'] ? ' ' . $a['custom_class'] : '';

    if ($a['mt'] || $a['mb']) {
        $style .= ' style="';
        $style .= $a['mt'] ? 'margin-top:' . $a['mt'] . 'px;' : '';
        $style .= $a['mb'] ? 'margin-bottom:' . $a['mb'] . 'px;' : '';
        $style .= '"';
    }

    $opts = theme_mb2nl_page_builder_2arrays($atts, $atts2);
    $sliderdata = theme_mb2nl_shortcodes_slider_data($opts);

    $output .= '<div class="mb2-pb-carousel mb2-pb-testimonials mb2-pb-content' . $cls . '"' .
    $style . $sliderdata . '>';
    $output .= '<div class="mb2-pb-testimonials-inner">';
    $output .= '<div id="' . $sliderid . '" class="mb2-pb-element-inner swiper">';
    $output .= theme_mb2nl_shortcodes_swiper_nav($sliderid);
    $output .= '<div class="swiper-wrapper">';
    $output .= mb2_do_shortcode($content);
    $output .= '</div>'; // ...swiper-wrapper
    $output .= theme_mb2nl_shortcodes_swiper_pagenavnav();
    $output .= '</div>'; // ...swiper
    $output .= '</div>'; // ...mb2-pb-testimonials-inner
    $output .= '</div>'; // ...mb2-pb-testimonials

    return $output;

}



/**
 *
 * Method to define shortcode
 *
 * @return HTML
 */
function mb2_shortcode_testimonials_item($atts, $content = null) {

    global $gl0testimonialsisimage,
    $gl0testimonialsiscompany,
    $gl0testimonialsisjob;

    $atts2 = [
        'name' => 'Full Name',
        'job' => 'Moodel Dev',
        'companyname' => 'Company name',
        'image' => theme_mb2nl_dummy_image('100x100'),
        'rating' => 5,
        'template' => '',
    ];

    $a = mb2_shortcode_atts($atts2, $atts);

    $output = '';

    $islabel = is_numeric($a['rating']) ? get_string('rating', 'theme_mb2nl', ['name' => $a['name'], 'rating' => $a['rating']]) :
    $a['name'];
    $output .= '<div class="mb2-pb-carousel-item mb2-pb-testimonials-item theme-slider-item swiper-slide" data-custom_label="' .
    $islabel . '">';
    $output .= '<div class="testimonials-item-inner">';

    if ($gl0testimonialsisimage) {
        $output .= '<div class="testimonial-image">';
        $output .= '<img class="testimonial-image-src lazy" src="' . theme_mb2nl_lazy_plc() . '" data-src="' .
        $a['image'] . '" alt="' . $a['name'] . '">';
        $output .= '</div>'; // ...testimonial-image
    }

    $output .= '<div class="testimonial-content">';

    $output .= '<div class="testimonial-header">';
    $output .= '<div class="testimonial-meta">';
    $output .= '<span class="testimonial-name">';
    $output .= $a['name'];
    $output .= '</span>'; // ...testimonial-name

    if ($gl0testimonialsisjob) {
        $output .= '<span class="testimonial-job">';
        $output .= $a['job'];
        $output .= '</span>'; // ...testimonial-job
    }

    if ($gl0testimonialsiscompany) {
        $output .= '<div class="testimonial-companyname">';
        $output .= $a['companyname'];
        $output .= '</div>'; // ...testimonial-companyname
    }

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
