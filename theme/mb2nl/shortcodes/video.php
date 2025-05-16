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

mb2_add_shortcode('video', 'mb2_shortcode_videoweb');
mb2_add_shortcode('videoweb', 'mb2_shortcode_videoweb');
mb2_add_shortcode('videolocal', 'mb2_shortcode_videolocal');
mb2_add_shortcode('videolightbox', 'mb2_shortcode_videolightbox');

/**
 *
 * Method to define shortcode
 *
 * @return HTML
 */
function mb2_shortcode_videoweb($atts, $content = null) {
    $atts2 = [
        'width' => 800,
        'id' => '',
        'videoid' => '',
        'videourl' => '',
        'video_text' => '',
        'ratio' => '16:9',
        'mt' => 0,
        'mb' => 30,
        'bgimage' => 0,
        'bgimageurl' => theme_mb2nl_dummy_image('1600x1066'),
        'iconcolor' => '',
        'bgcolor' => '',
        'custom_class' => '',
    ];

    $a = mb2_shortcode_atts($atts2, $atts);

    $output = '';
    $style = '';
    $cls = '';

    $cls .= $a['bgimage'] ? ' isimage1' : ' isimage0';
    $cls .= $a['custom_class'] ? ' ' . $a['custom_class'] : '';

    // User use old shortcode with video id.
    if ($a['id'] && ! $a['videourl']) {
        $a['videourl'] = $a['id'];
    }

    // User use updated shortcode in page builder.
    if ($a['videoid']) {
        $a['videourl'] = $a['videoid'];
    }

    $a['videourl'] = theme_mb2nl_get_video_url($a['videourl']);
    $isratio = str_replace(':', 'by', $a['ratio']);

    if ($a['mt'] || $a['mb'] || $a['width'] || $a['bgcolor'] || $a['iconcolor']) {
        $style .= ' style="';
        $style .= $a['mt'] ? 'margin-top:' . $a['mt'] . 'px;' : '';
        $style .= $a['mb'] ? 'margin-bottom:' . $a['mb'] . 'px;' : '';
        $style .= $a['width'] ? 'max-width:' . $a['width'] .'px;' : '';

        $style .= $a['bgcolor'] ? '--mb2-pb-videobg:' . $a['bgcolor'] .';' : '';
        $style .= $a['iconcolor'] ? '--mb2-pb-videoiconcolor:' . $a['iconcolor'] .';' : '';
        $style .= '"';
    }

    $output .= '<div class="embed-responsive-wrap ' . $cls . '"' . $style . '>';
    $output .= '<div class="embed-responsive-wrap-inner">';
    $output .= '<div class="embed-responsive embed-responsive-'. $isratio . '">';
    $output .= $a['bgimage'] ? '<div class="embed-video-bg lazy" data-bg="' .
    $a['bgimageurl'] . '"><button type="button" class="themereset' .
    theme_mb2nl_bsfcls(2, '', 'center', 'center') . '" aria-label="' .
    get_string('playbtn', 'theme_mb2nl') . '"><i class="fa fa-play"></i></button><div class="bgcolor"></div></div>' : '';
    $output .= '<iframe class="videowebiframe lazy" data-src="' .
    $a['videourl'] . '?showinfo=0&rel=0" allowfullscreen></iframe>';
    $output .= '</div>';
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
function mb2_shortcode_videolocal($atts, $content = null) {
    $atts2 = [
        'width' => 800,
        'videofile' => '',
        'video_text' => '',
        'mt' => 0,
        'mb' => 30,
        'custom_class' => '',
    ];

    $a = mb2_shortcode_atts($atts2, $atts);

    $output = '';
    $style = '';
    $cls = '';

    $cls .= $a['custom_class'] ? ' ' . $a['custom_class'] : '';

    if ($a['mt'] || $a['mb'] || $a['width']) {
        $style .= ' style="';
        $style .= $a['mt'] ? 'margin-top:' . $a['mt'] . 'px;' : '';
        $style .= $a['mb'] ? 'margin-bottom:' . $a['mb'] . 'px;' : '';
        $style .= $a['width'] ? 'max-width:' . $a['width'] .'px;' : '';
        $style .= '"';
    }

    $output .= '<div class="theme-videolocal mb2-pb-element mb2pb-videolocal' . $cls . '"' . $style . '>';
    $output .= '<div class="theme-videolocal-inner">';

    if ($a['videofile']) {
        $output .= '<video class="lazy" controls="true">';
        $output .= '<source data-src="' . $a['videofile'] . '">';
        $output .= '</video>';
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
function mb2_shortcode_videolightbox($atts, $content = null) {
    global $PAGE;

    $atts2 = [
        'width' => 800,
        'videourl' => 'https://www.youtube.com/watch?v=3ORsUGVNxGs',
        'text' => 'Open video',
        'mt' => 0,
        'mb' => 0,
        'custom_class' => '',
    ];

    $a = mb2_shortcode_atts($atts2, $atts);

    $output = '';

    if (theme_mb2nl_is_video($a['videourl'])) {
        $output .= '<a class="theme-popup-link popup-html_video" href="'. $a['videourl'] . '" title="' .
        get_string('lightboxvideo', 'theme_mb2nl', ['videourl' => $a['videourl']]) . '"><span>' .
        theme_mb2nl_format_str($text) . '</span></a>';
    } else {
        $a['videourl'] = theme_mb2nl_get_video_url($a['videourl'], true);
        $output .= '<a class="theme-popup-link popup-iframe" href="' . $a['videourl'] . '" title="' .
        get_string('lightboxvideo', 'theme_mb2nl', ['videourl' => $a['videourl']]) . '"><span>' .
        theme_mb2nl_format_str($text) . '</span></a>';
    }

    return $output;

}
