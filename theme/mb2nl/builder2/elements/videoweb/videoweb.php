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

mb2_add_shortcode('mb2pb_videoweb', 'mb2_shortcode_mb2pb_videoweb');

/**
 *
 * Method to define shortcode
 *
 * @return HTML
 */
function mb2_shortcode_mb2pb_videoweb($atts, $content = null) {
    global $PAGE;

    $atts2 = [
        'id' => 'videoweb',
        'width' => 800,
        'videourl' => 'https://youtu.be/3ORsUGVNxGs',
        'video_text' => '',
        'ratio' => '16:9',
        'mt' => 0,
        'mb' => 30,
        'custom_class' => '',
        'bgimage' => 0,
        'bgimageurl' => theme_mb2nl_dummy_image('1600x1066'),
        'iconcolor' => '',
        'bgcolor' => '',
        'template' => '',
    ];

    $atts['id'] = $atts2['id'];

    $a = mb2_shortcode_atts($atts2, $atts);

    $output = '';
    $style = '';
    $bgstyle = '';
    $cls = '';

    $cls .= $a['template'] ? ' mb2-pb-template-videoweb' : '';
    $cls .= $a['bgimage'] ? ' isimage1' : ' isimage0';
    $cls .= $a['custom_class'] ? ' ' . $a['custom_class'] : '';

    $a['videourl'] = theme_mb2nl_get_video_url($a['videourl']);

    $isratio = str_replace(':', 'by', $a['ratio']);

    $style .= ' style="';
    $style .= $a['mt'] ? 'margin-top:' . $a['mt'] . 'px;' : '';
    $style .= $a['mb'] ? 'margin-bottom:' . $a['mb'] . 'px;' : '';
    $style .= $a['width'] ? 'max-width:' . $a['width'] .'px;' : '';

    $style .= $a['bgcolor'] ? '--mb2-pb-videobg:' . $a['bgcolor'] .';' : '';
    $style .= $a['iconcolor'] ? '--mb2-pb-videoiconcolor:' . $a['iconcolor'] .';' : '';
    $style .= '"';

    $bgstyle .= ' style="';
    $bgstyle .= 'background-image:url(\'' . $a['bgimageurl'] . '\');';
    $bgstyle .= '"';

    $output .= '<div class="embed-responsive-wrap mb2-pb-element mb2pb-videoweb' . $cls . '"' .
    $style . theme_mb2nl_page_builder_el_datatts($atts, $atts2) . '>';
    $output .= '<div class="element-helper"></div>';
    $output .= theme_mb2nl_page_builder_el_actions('element', 'videoweb');
    $output .= '<div class="embed-responsive-wrap-inner">';
    $output .= '<div class="embed-responsive embed-responsive-'. $isratio . '">';
    $output .= '<div class="embed-video-bg"' . $bgstyle . '><button type="button" class="themereset' .
    theme_mb2nl_bsfcls(2, '', 'center', 'center') . '"><i class="fa fa-play"></i></button><div class="bgcolor"></div></div>';
    $output .= '<iframe class="videowebiframe" src="' . $a['videourl'] . '?showinfo=0&rel=0" allowfullscreen></iframe>';
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
function mb2_shortcode_mb2pb_video_modal() {
    $output = '';

    $output .= '<div id="header-modal-video" class="modal theme-modal-scale" role="dialog">';
    $output .= '<div class="modal-dialog" role="document">';
    $output .= '<div class="modal-content">';
    $output .= '<div class="theme-modal-container">';
    $output .= '<span class="close-container" data-dismiss="modal">&times;</span>';

    $output .= '<div class="embed-responsive-wrap-inner">';
    $output .= '<div class="embed-responsive embed-responsive-16by9">';
    $output .= '<iframe class="videowebiframe" src="" allowfullscreen></iframe>';
    $output .= '</div>'; // ...embed-responsive-wrap-inner
    $output .= '</div>'; // ...embed-responsive

    $output .= '</div>'; // ...theme-modal-container
    $output .= '</div>'; // ...modal-content
    $output .= '</div>'; // ...modal-dialog
    $output .= '</div>'; // ...theme-modal-scale

    return $output;

}
