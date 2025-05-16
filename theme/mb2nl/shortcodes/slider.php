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

mb2_add_shortcode('slider', 'mb2_shortcode_slider');
mb2_add_shortcode('carousel', 'mb2_shortcode_slider');
mb2_add_shortcode('slider_item', 'mb2_shortcode_slider_item');
mb2_add_shortcode('carousel_item', 'mb2_shortcode_slider_item');

/**
 *
 * Method to define shortcode
 *
 * @return HTML
 */
function mb2_shortcode_slider($atts, $content=null) {

    global $PAGE,
    $gl0carreadmoretext,
    $gl0cartitle,
    $gl0cardesc,
    $gl0carimgwidth,
    $gl0cartitlefs,
    $gl0cartitlefw,
    $gl0cartitlelh,
    $gl0carlinkbtn;

    $atts2 = [
        'mt' => 0,
        'mb' => 30,
        'width' => '',
        'custom_class' => '',
        'prestyle' => '',
        'columns' => 1,
        'imgwidth' => 800,
        'sdots' => 0,
        'sloop' => 0,
        'snav' => 1,
        'sautoplay' => 1,
        'autoplay' => 0,
        'spausetime' => 5000,
        'pausetime' => 5000,
        'sanimate' => 450,
        'animtime' => 450,
        'gridwidth' => 'normal',
        'mobcolumns' => 0,
        'gutter' => 'normal',
        'btntext' => '',

        'title' => 1,
        'titlefs' => 1.4,
        'titlefw' => 'global',
        'titlelh' => 'global',

        'desc' => 1,
        'linkbtn' => 0,
        'link_target' => '',
        'readmoretext' => '',
    ];

    $a = mb2_shortcode_atts($atts2, $atts);

    $output = '';
    $sdata = '';
    $style = '';
    $cls = '';

    $gl0carreadmoretext = $a['readmoretext'] ? $a['readmoretext'] : $a['btntext'];
    $gl0cartitle = $a['title'];
    $gl0cardesc = $a['desc'];
    $gl0carimgwidth = $a['imgwidth'];
    $gl0cartitlefs = $a['titlefs'];
    $gl0cartitlefw = $a['titlefw'];
    $gl0cartitlelh = $a['titlelh'];
    $gl0carlinkbtn = $a['linkbtn'];

    $sliderid = uniqid('swiper_');

    $cls .= ' prestyle' . $a['prestyle'];
    $cls .= ' linkbtn' . $a['linkbtn'];
    $cls .= ' gridwidth' . $a['gridwidth'];
    $cls .= $a['custom_class'] ? ' ' . $a['custom_class'] : '';

    if ($a['mt'] || $a['mb'] || $a['width']) {
        $style .= ' style="';
        $style .= $a['mt'] ? 'margin-top:' . $a['mt'] . 'px;' : '';
        $style .= $a['mb'] ? 'margin-bottom:' . $a['mb'] . 'px;' : '';
        $style .= $a['width'] ? 'max-width:' . $a['width'] . 'px;margin-left:auto;margin-right:auto;' : '';
        $style .= '"';
    }

    $opts = theme_mb2nl_page_builder_2arrays($atts, $atts2);
    $sliderdata = theme_mb2nl_shortcodes_slider_data($opts);

    $cls .= $a['sdots'] == 1 ? ' isdots' : '';
    $cls .= $a['columns'] > 1 ? ' carousel-mode' : ' slider-mode';

    $output .= '<div class="theme-slider-wrap mb2-pb-content mb2-pb-carousel' . $cls . '"' . $style . $sliderdata  . '>';
    $output .= '<div id="' . $sliderid . '" class="swiper theme-slider">';
    $output .= theme_mb2nl_shortcodes_swiper_nav($sliderid);
    $output .= '<div class="swiper-wrapper">';
    $output .= mb2_do_shortcode($content);
    $output .= '</div>'; // ...swiper-wrapper
    $output .= theme_mb2nl_shortcodes_swiper_pagenavnav();
    $output .= '</div>'; // ...theme-slider
    $output .= '</div>'; // ...theme-slider-wrap

    return $output;

}


/**
 *
 * Method to define shortcode
 *
 * @return HTML
 */
function mb2_shortcode_slider_item($atts, $content = null) {

    global $gl0carreadmoretext,
    $gl0cartitle,
    $gl0cardesc,
    $gl0carimgwidth,
    $gl0cartitlefs,
    $gl0cartitlefw,
    $gl0cartitlelh,
    $gl0carlinkbtn;

    $atts2 = [
        'title' => 'Title here...',
        'desc' => '',
        'image' => '',
        'color' => '',
        'link' => '',
        'target' => '',
        'link_target' => 0,
    ];

    $a = mb2_shortcode_atts($atts2, $atts);

    $output = '';
    $istarget = '';
    $tcls = '';
    $colorcls = $a['color'] ? ' color1' : '';
    $imgstyle = ' style="width:' . $gl0carimgwidth . 'px;"';
    $formattitle = theme_mb2nl_format_str($a['title']);

    $tcls .= ' ' . theme_mb2nl_tsize_cls($gl0cartitlefs);
    $tcls .= ' fw' . $gl0cartitlefw;
    $tcls .= ' lh' . $gl0cartitlelh;

    $a['target'] = $a['target'] ? $a['target'] : $a['link_target'];
    $istarget = $a['target'] ? ' target="_blank"' : '';

    $colorstyle = $a['color'] ? ' style="background-color:' . $a['color'] . ';"' : '';

    $output .= '<div class="theme-slider-item swiper-slide" data-custom_label="' . strip_tags($formattitle) . '">';
    $output .= '<div class="theme-slider-item-inner">';

    $output .= '<div class="theme-slider-img">';
    $output .= $a['link'] ? '<a href="' . $a['link'] . '"' . $istarget . ' tabindex="-1">' : '';
    $output .= '<img class="lazy" src="' . theme_mb2nl_lazy_plc() . '" data-src="' . $a['image'] . '" alt="' .
    strip_tags($formattitle) . '"' . $imgstyle . '>';
    $output .= $a['link'] ? '</a>' : '';
    $output .= '</div>';

    if ($content || ($a['desc'] && $gl0cardesc) || ($a['title'] && $gl0cartitle) || ($a['link'] && $gl0carlinkbtn)) {
        $output .= '<div class="theme-slide-content1' . $colorcls . '"' . $colorstyle . '>';
        $output .= '<div class="theme-slide-content2">';
        $output .= '<div class="theme-slide-content3">';
        $output .= '<div class="theme-slide-content4">';

        if ($a['title'] && $gl0cartitle) {
            $output .= '<h4 class="theme-slide-title' . $tcls . '" style="font-size:' . $gl0cartitlefs . 'rem;">';
            $output .= $a['link'] ? '<a href="' . $a['link'] . '"' . $istarget . ' tabindex="-1">' : '';
            $output .= $formattitle;
            $output .= $a['link'] ? '</a>' : '';
            $output .= '</h4>';
        }

        if (($a['desc'] && $gl0cardesc) || ($content && $gl0cardesc) || ($a['link'] && $gl0carlinkbtn)) {
            $output .= '<div class="theme-slider-item-details">';

            $a['desc'] = $content ? $content : $a['desc'];

            if ($a['desc'] && $gl0cardesc) {
                $output .= '<div class="theme-slider-desc">';
                $output .= theme_mb2nl_format_txt($a['desc'], FORMAT_HTML);
                $output .= '</div>';
            }

            if ($a['link']) {
                $a['readmoretext'] = $gl0carreadmoretext ? $gl0carreadmoretext : get_string('readmore', 'theme_mb2nl');

                $output .= '<div class="theme-slider-readmore">';
                $output .= '<a class="mb2-pb-btn typeprimary" href="' . $a['link'] . '"' .
                $istarget . ' tabindex="-1">' . $a['readmoretext'] . '</a>';
                $output .= '</div>';
            }

            $output .= '</div>';
        }

        $output .= '</div>';
        $output .= '</div>';
        $output .= '</div>';
        $output .= '</div>';
    }

    $output .= $a['link'] ? '<a class="themekeynavlink" href="' . $a['link'] . '"' .
    $istarget . ' tabindex="0" aria-label="' . $formattitle . '"></a>' : '';
    $output .= '</div>';
    $output .= '</div>';

    return $output;

}
