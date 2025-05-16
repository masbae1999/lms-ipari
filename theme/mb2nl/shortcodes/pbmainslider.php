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

mb2_add_shortcode('pbmainslider', 'mb2_shortcode_pbmainslider');
mb2_add_shortcode('pbmainslider_item', 'mb2_shortcode_pbmainslider_item');

/**
 *
 * Method to define shortcode
 *
 * @return HTML
 */
function mb2_shortcode_pbmainslider($atts, $content= null) {

    global $gl0globalctnav,
    $gl0slideruniqid;

    $atts2 = [
        'mt' => 0,
        'mb' => 0,
        'width' => '',
        'height' => 600,
        'mheight' => 85,
        'imgh' => 0,
        'custom_class' => '',
        'columns' => 1,
        'gutter' => 'none',
        'sloop' => 1,
        'snav' => 1,
        'ctnav' => 0,
        'sdots' => 0,
        'autoplay' => 1,
        'pausetime' => 5000,
        'animtime' => 800,
        'animtype' => 'fade',
    ];

    $a = mb2_shortcode_atts($atts2, $atts);

    $output = '';
    $sdata = '';
    $style = '';
    $cls = '';
    $sliderid = uniqid('swiper_');
    $gl0globalctnav = $a['ctnav'];
    $gl0slideruniqid = $sliderid;

    $cls .= $a['custom_class'] ? ' ' . $a['custom_class'] : '';

    $style .= ' style="';
    $style .= 'margin-top:' . $a['mt'] . 'px;';
    $style .= 'margin-bottom:' . $a['mb'] . 'px;';
    $style .= '--mb2-pb-mainslider-height:' . $a['height'] . 'px;--mb2-pb-mainslider-mheight:' . $a['mheight'] . ';';
    $style .= '"';

    $opts = theme_mb2nl_page_builder_2arrays($atts, $atts2);
    $sliderdata = theme_mb2nl_shortcodes_slider_data($opts);

    $cls .= $a['sdots'] == 1 ? ' isdots' : '';
    $cls .= ' imgh' . $a['imgh'];

    $output .= '<div class="pbmainslider-wrap mb2-pb-carousel mb2-pb-content' . $cls . '"' . $style . $sliderdata . '>';
    $output .= '<div id="' . $sliderid . '" class="mb2-pb-element-inner swiper">';
    $output .= theme_mb2nl_shortcodes_swiper_nav($sliderid);
    $output .= '<div class="swiper-wrapper">';
    $output .= mb2_do_shortcode($content);
    $output .= '</div>'; // ...swiper-wrapper
    $output .= theme_mb2nl_shortcodes_swiper_pagenavnav();
    $output .= '</div>'; // ...mb2-pb-element-inner
    $output .= '</div>'; // ...pbmainslider-wrap

    return $output;

}


/**
 *
 * Method to define shortcode
 *
 * @return HTML
 */
function mb2_shortcode_pbmainslider_item($atts, $content = null) {

    global $gl0globalctnav,
    $gl0slideruniqid;

    $atts2 = [
        'title' => 'Title text',
        'isdesc' => 1,

        'tcolor' => '',
        'istitle' => 1,
        'tlh' => 'global',
        'tsize' => 3,
        'tfweight' => 'global',
        'tlspacing' => 0,
        'twspacing' => 0,
        'tupper' => 0,
        'tthshadow' => 0.05,
        'ttvshadow' => 0.05,
        'ttbshadow' => 0,
        'ttcshadow' => '',

        'aligntext' => 'none',
        'valign' => 'center',
        'halign' => 'left',

        'dcolor' => '',
        'dsize' => 1,
        'dlh' => 'global',
        'dfweight' => 'global',
        'dlspacing' => 0,
        'dwspacing' => 0,
        'dupper' => 0,
        'dmt' => 15,
        'dthshadow' => 0.05,
        'dtvshadow' => 0.05,
        'dtbshadow' => 0,
        'dtcshadow' => '',

        'image' => '',
        'desc' => 'Description text',
        'btntext' => '',

        'bgcolor' => '',
        'prebg' => '',

        'ph' => 15,
        'pv' => 0,
        'cmt' => 0,
        'cmb' => 0,

        'ocolor' => '',
        'cwidth' => 750,

        'linkbtn' => 0,

        'btntype' => 'primary',
        'btnfwcls' => 'global',
        'btnsize' => 'lg',
        'btnrounded' => 0,
        'btnborder' => 0,
        'btnmt' => 15,

        'heroimg' => 0,
        'heroimgurl' => '',
        'herow' => 1200,
        'heroonsmall' => 1,
        'herov' => 'center',
        'heroml' => 0,
        'herohpos' => 'left',
        'heromt' => 0,
        'herogradl' => 0,
        'herogradr' => 0,

        'link' => '',
        'link_target' => 0,
    ];

    $a = mb2_shortcode_atts($atts2, $atts);

    $output = '';
    $cls = '';
    $ccls = '';
    $cstyle = '';
    $tstyle = '';
    $dstyle = '';
    $ctnavcls = '';
    $tcls = '';
    $dcls = '';
    $btncls = '';
    $btnstyle = '';
    $istarget = $a['link_target'] ? ' target="_blank"' : '';
    $slidelink = (! $a['linkbtn'] && $a['link'] !== '');
    $a['btntext'] = $a['btntext'] ? $a['btntext'] : get_string('readmorefp', 'local_mb2builder');

    $ccls .= ' ' . theme_mb2nl_tsize_cls($a['ph'], 'phsize-', false);
    $ccls .= ' ' . theme_mb2nl_tsize_cls($a['pv'], 'pvsize-', false);

    $tcls .= ' ' . theme_mb2nl_tsize_cls($a['tsize']);
    $tcls .= ' fw' . $a['tfweight'];
    $tcls .= ' lh' . $a['tlh'];
    $tcls .= ' upper' . $a['tupper'];

    $dcls .= ' ' . theme_mb2nl_tsize_cls($a['dsize']);
    $dcls .= ' fw' . $a['dfweight'];
    $dcls .= ' lh' . $a['dlh'];
    $dcls .= ' upper' . $a['dupper'];

    $cls .= ' halign' . $a['halign'];
    $cls .= ' valign' . $a['valign'];
    $cls .= ' aligntext' . $a['aligntext'];
    $cls .= ' isdesc' . $a['isdesc'];
    $cls .= ' istitle' . $a['istitle'];
    $cls .= ' linkbtn' . $a['linkbtn'];
    $cls .= ' heroimg' . $a['heroimg'];
    $cls .= ' herov' . $a['herov'];
    $cls .= ' herogradl' . $a['herogradl'];
    $cls .= ' herogradr' . $a['herogradr'];
    $cls .= ' heroonsmall' . $a['heroonsmall'];
    $cls .= ' pre-bg' . $a['prebg'];

    $btncls .= ' type' . $a['btntype'];
    $btncls .= ' size' . $a['btnsize'];
    $btncls .= ' fw' . $a['btnfwcls'];
    $btncls .= ' rounded' . $a['btnrounded'];
    $btncls .= ' btnborder' . $a['btnborder'];

    $ctnavcls .= ' fw' . $a['btnfwcls'];
    $ctnavcls .= ' size' . $a['btnsize'];
    $ctnavcls .= ' type' . $a['btntype'];

    $cstyle .= ' style="';
    $cstyle .= 'width:' . $a['cwidth'] . 'px;max-width:100%;';
    $cstyle .= $a['bgcolor'] ? 'background-color:' . $a['bgcolor'] . ';' : '';
    $cstyle .= '--mb2-pb-mainslider-cph:' . $a['ph'] . 'px;';
    $cstyle .= '--mb2-pb-mainslider-cpv:' . $a['pv'] . 'px;';
    $cstyle .= '--mb2-pb-mainslider-btnmt:' . $a['btnmt'] . 'px;';

    $cstyle .= 'margin-top:' . $a['cmt'] . 'px;';
    $cstyle .= 'margin-bottom:' . $a['cmb'] . 'px;';
    $cstyle .= '"';

    $tstyle .= ' style="';
    $tstyle .= $a['tcolor'] ? 'color:' . $a['tcolor'] . ';' : '';
    $tstyle .= 'font-size:' . $a['tsize'] . 'rem;';
    $tstyle .= 'letter-spacing:' . $a['tlspacing'] . 'px;';
    $tstyle .= 'word-spacing:' . $a['twspacing'] . 'px;';
    $tstyle .= '--mb2-pb-mainslider-thshadow:' . $a['tthshadow'] . 'em;';
    $tstyle .= '--mb2-pb-mainslider-tvshadow:' . $a['ttvshadow'] . 'em;';
    $tstyle .= '--mb2-pb-mainslider-tbshadow:' . $a['ttbshadow'] . 'px;';
    $tstyle .= $a['ttcshadow'] ? '--mb2-pb-mainslider-tcshadow:' . $a['ttcshadow'] . ';' :
    '--mb2-pb-mainslider-tcshadow:transparent;';
    $tstyle .= '"';

    $dstyle .= ' style="';
    $dstyle .= $a['dcolor'] ? 'color:' . $a['dcolor'] . ';' : '';
    $dstyle .= 'font-size:' . $a['dsize'] . 'rem;';
    $dstyle .= 'letter-spacing:' . $a['dlspacing'] . 'px;';
    $dstyle .= 'word-spacing:' . $a['dwspacing'] . 'px;';
    $dstyle .= 'margin-top:' . $a['dmt'] . 'px;';
    $dstyle .= '--mb2-pb-mainslider-thshadow:' . $a['dthshadow'] . 'em;';
    $dstyle .= '--mb2-pb-mainslider-tvshadow:' . $a['dtvshadow'] . 'em;';
    $dstyle .= '--mb2-pb-mainslider-tbshadow:' . $a['dtbshadow'] . 'px;';
    $dstyle .= $a['dtcshadow'] ? '--mb2-pb-mainslider-tcshadow:' . $a['dtcshadow'] . ';' :
    '--mb2-pb-mainslider-tcshadow:transparent;';
    $dstyle .= '"';

    $colorstyle = $a['bgcolor'] ? ' style="background-color:' . $a['bgcolor'] . ';"' : '';

    $innerstyle = ' style="';
    $innerstyle .= $a['ocolor'] ? 'background-color:' . $a['ocolor'] . ';' : '';
    $innerstyle .= '"';

    $istitle = theme_mb2nl_format_str(urldecode($a['title']));
    $isdesc = theme_mb2nl_format_str(urldecode($a['desc'])); // No HTML code.

    $output .= '<div class="mb2-pb-carousel-item swiper-slide" data-custom_label="' . strip_tags($istitle) . '">';
    $output .= $slidelink ? '<a href="' . $a['link'] . '"' . $istarget . '>' : '';
    $output .= '<div class="pbmainslider-item-inner1">';
    $output .= '<div class="pbmainslider-item-inner' . $cls . '"' . $innerstyle . '>';
    $output .= '<div class="slide-content1">';
    $output .= '<div class="slide-content2">';
    $output .= '<div class="slide-content3' . $ccls . '"' . $cstyle . '>';
    $output .= '<div class="slide-content4">';

    if ($a['istitle']) {
        $output .= '<h2 class="slide-title' . $tcls . '"' . $tstyle . '>';
        $output .= $istitle;
        $output .= '</h2>';
    }

    if ($a['isdesc']) {
        $output .= '<div class="slide-desc' . $dcls . '"' . $dstyle . '>';
        $output .= $isdesc;
        $output .= '</div>';
    }

    if ($a['linkbtn'] && $a['link'] !== '' && $gl0globalctnav == 0) {
        $output .= '<div class="slide-readmore">';
        $output .= '<a href="' . $a['link'] . '" class="mb2-pb-btn' . $btncls . '"' .
        $btnstyle . $istarget . '><span class="btn-intext">' . theme_mb2nl_format_str(urldecode($a['btntext'])) . '</span></a>';
        $output .= '</div>';
    }

    $output .= $gl0globalctnav ? theme_mb2nl_shortcodes_swiper_descnav($gl0slideruniqid, ['btnlink' => $a['link'],
    'btntext' => urldecode($a['btntext']), 'link_target' => $istarget, 'cls' => $ctnavcls]) : '';

    $output .= '</div>'; // ...slide-content4
    $output .= '</div>'; // ...slide-content3
    $output .= '</div>'; // ...slide-content2
    $output .= '<div class="slide-descbg"' . $colorstyle . '></div>'; // ...theme-slide-content2
    $output .= '</div>'; // ...slide-content1

    if ($a['heroimgurl']) {
        $output .= '<div class="slidehero-img-wrap">';
        $output .= '<div class="slidehero-img-wrap2">';
        $output .= '<div class="slidehero-img-wrap3" style="width:' . $a['herow'] . 'px;' . $a['herohpos'] . ':' .
        $a['heroml'] . '%;margin-top:' . $a['heromt'] . 'px;">';
        $output .= '<img src="' . theme_mb2nl_lazy_plc() . '" class="slidehero-img lazy" data-src="' .
        $a['heroimgurl'] . '" alt="' . strip_tags($istitle) . '">';
        $output .= '<div class="slidehero-img-grad grad-left" style="background-image:linear-gradient(to right,' .
        $a['ocolor'] . ',rgba(255,255,255,0)); "></div>';
        $output .= '<div class="slidehero-img-grad grad-right"';
        $output .= ' style="background-image:linear-gradient(to right,rgba(255,255,255,0),' . $a['ocolor'] . '); "></div>';
        $output .= '</div>'; // ...hero-img-wrap23
        $output .= '</div>'; // ...hero-img-wrap2
        $output .= '</div>'; // ...hero-img-wrap
    }

    $output .= '</div>'; // ...pbmainslider-item-inner1
    $output .= '</div>'; // ...pbmainslider-item-inner

    // Slider image.
    $isimage = $a['image'];

    if (!$isimage && !$a['heroimg']) {
        $isimage = theme_mb2nl_dummy_image('1900x750');
    }

    if ($isimage) {
        $output .= '<div class="theme-slider-img"><img src="' . theme_mb2nl_lazy_plc() . '" class="lazy" data-src="' .
        $isimage . '" alt="' . strip_tags($istitle) . '"><div class="img-cover lazy" data-bg="' . $isimage . '"></div></div>';
    }

    $output .= $slidelink ? '</a>' : '';
    $output .= '</div>'; // ...mb2-pb-carousel-item

    return $output;

}
