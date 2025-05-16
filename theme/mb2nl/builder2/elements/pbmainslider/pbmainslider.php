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

mb2_add_shortcode('mb2pb_pbmainslider', 'mb2_shortcode_mb2pb_pbmainslider');
mb2_add_shortcode('mb2pb_pbmainslider_item', 'mb2_shortcode_mb2pb_pbmainslider_item');

/**
 *
 * Method to define shortcode
 *
 * @return HTML
 */
function mb2_shortcode_mb2pb_pbmainslider($atts, $content = null) {

    global $gl0pbmainslideruniqid,
    $gl0pbmainslideritem,
    $gl0pbmainsliderid;

    $atts2 = [
        'id' => 'pbmainslider',
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
        'sdots' => 1,
        'autoplay' => 1,
        'pausetime' => 5000,
        'animtime' => 800,
        'animtype' => 'slide',
        'template' => '',
    ];

    $a = mb2_shortcode_atts($atts2, $atts);

    $output = '';
    $attr = [];
    $uniqid = uniqid('pbmainslideritem_');
    $sliderid = uniqid('swiper_');
    $sdata = '';
    $style = '';
    $cls = '';
    $gl0pbmainslideruniqid = $uniqid;
    $gl0pbmainslideritem = 0;
    $gl0pbmainsliderid = $sliderid;

    $cls .= $a['custom_class'] ? ' ' . $a['custom_class'] : '';
    $cls .= $a['sdots'] == 1 ? ' isdots' : '';
    $cls .= $a['template'] ? ' mb2-pb-template-pbmainslider' : '';
    $cls .= ' ctnav' . $a['ctnav'];
    $cls .= ' imgh' . $a['imgh'];

    $style .= ' style="';
    $style .= 'margin-top:' . $a['mt'] . 'px;';
    $style .= 'margin-bottom:' . $a['mb'] . 'px;';
    $style .= '--mb2-pb-mainslider-height:' . $a['height'] . 'px;--mb2-pb-mainslider-mheight:' . $a['mheight'] . ';';
    $style .= '"';

    // Define default content.
    if (! $content) {
        $demoimage = theme_mb2nl_dummy_image('1900x750');

        for ($i = 1; $i <= 2; $i++) {
            $content .= '[mb2pb_pbmainslider_item pbid="" image="' . $demoimage . '" ][/mb2pb_pbmainslider_item]';
        }
    }

    // Get carousel content for sortable elements.
    $regex = '\\[(\\[?)(mb2pb_pbmainslider_item)\\b([^\\]\\/]*(?:\\/(?!\\])[^\\]\\/]*)*?)';
    $regex .= '(?:(\\/)\\]|\\](?:([^\\[]*+(?:\\[(?!\\/\\2\\])[^\\[]*+)*+)\\[\\/\\2\\])?)(\\]?)';
    preg_match_all("/$regex/is", $content, $match);
    $content = $match[0];

    $output .= '<div class="mb2-pb-element pbmainslider-wrap mb2-pb-carousel' . $cls . '"' .
    $style . theme_mb2nl_page_builder_el_datatts($atts, $atts2) . '>';
    $output .= '<div class="element-helper"></div>';
    $output .= theme_mb2nl_page_builder_el_actions('element', 'pbmainslider');
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
        $attr['id'] = 'pbmainslider_item';
        $attr['pbid'] = (isset($attributes['pbid']) && $attributes['pbid']) ? $attributes['pbid'] : $uniqid . $z;
        $attr['image'] = $attributes['image'];
        $attr['title'] = (isset($attributes['title']) && $attributes['title']) ? $attributes['title'] : 'Title text';
        $attr['desc'] = (isset($attributes['desc']) && $attributes['desc']) ? $attributes['desc'] : 'Description text';

        $attr['prebg'] = isset($attributes['prebg']) ? $attributes['prebg'] : '';
        $attr['bgcolor'] = isset($attributes['bgcolor']) ? $attributes['bgcolor'] : '';

        $attr['aligntext'] = isset($attributes['aligntext']) ? $attributes['aligntext'] : 'none';
        $attr['valign'] = isset($attributes['valign']) ? $attributes['valign'] : 'center';
        $attr['halign'] = isset($attributes['halign']) ? $attributes['halign'] : 'left';

        $attr['istitle'] = isset($attributes['istitle']) ? $attributes['istitle'] : 1;
        $attr['isdesc'] = isset($attributes['isdesc']) ? $attributes['isdesc'] : 1;
        $attr['ocolor'] = isset($attributes['ocolor']) ? $attributes['ocolor'] : '';

        $attr['linkbtn'] = isset($attributes['linkbtn']) ? $attributes['linkbtn'] : 0;
        $attr['link_target'] = isset($attributes['link_target']) ? $attributes['link_target'] : 0;
        $attr['link'] = isset($attributes['link']) ? $attributes['link'] : '';

        $attr['btntype'] = isset($attributes['btntype']) ? $attributes['btntype'] : 'primary';
        $attr['btnfwcls'] = isset($attributes['btnfwcls']) ? $attributes['btnfwcls'] : 'global';
        $attr['btnsize'] = isset($attributes['btnsize']) ? $attributes['btnsize'] : 'lg';
        $attr['btnrounded'] = isset($attributes['btnrounded']) ? $attributes['btnrounded'] : 0;
        $attr['btnborder'] = isset($attributes['btnborder']) ? $attributes['btnborder'] : 0;
        $attr['btnmt'] = isset($attributes['btnmt']) ? $attributes['btnmt'] : 15;
        $attr['btntext'] = isset($attributes['btntext']) ? $attributes['btntext'] : '';

        $attr['tsize'] = isset($attributes['tsize']) ? $attributes['tsize'] : 3;
        $attr['tlh'] = isset($attributes['tlh']) ? $attributes['tlh'] : 'global';
        $attr['tfweight'] = isset($attributes['tfweight']) ? $attributes['tfweight'] : 'global';
        $attr['tlspacing'] = isset($attributes['tlspacing']) ? $attributes['tlspacing'] : 0;
        $attr['twspacing'] = isset($attributes['twspacing']) ? $attributes['twspacing'] : 0;
        $attr['tupper'] = isset($attributes['tupper']) ? $attributes['tupper'] : 0;
        $attr['tcolor'] = isset($attributes['tcolor']) ? $attributes['tcolor'] : '';
        $attr['tthshadow'] = isset($attributes['tthshadow']) ? $attributes['tthshadow'] : 3;
        $attr['ttvshadow'] = isset($attributes['ttvshadow']) ? $attributes['ttvshadow'] : 3;
        $attr['ttbshadow'] = isset($attributes['ttbshadow']) ? $attributes['ttbshadow'] : 0;
        $attr['ttcshadow'] = isset($attributes['ttcshadow']) ? $attributes['ttcshadow'] : '';

        $attr['dsize'] = isset($attributes['dsize']) ? $attributes['dsize'] : 1;
        $attr['dlh'] = isset($attributes['dlh']) ? $attributes['dlh'] : 'global';
        $attr['dfweight'] = isset($attributes['dfweight']) ? $attributes['dfweight'] : 'global';
        $attr['dlspacing'] = isset($attributes['dlspacing']) ? $attributes['dlspacing'] : 0;
        $attr['dwspacing'] = isset($attributes['dwspacing']) ? $attributes['dwspacing'] : 0;
        $attr['dupper'] = isset($attributes['dupper']) ? $attributes['dupper'] : 0;
        $attr['dmt'] = isset($attributes['dmt']) ? $attributes['dmt'] : 15;
        $attr['dcolor'] = isset($attributes['dcolor']) ? $attributes['dcolor'] : '';
        $attr['dthshadow'] = isset($attributes['dthshadow']) ? $attributes['dthshadow'] : 3;
        $attr['dtvshadow'] = isset($attributes['dtvshadow']) ? $attributes['dtvshadow'] : 3;
        $attr['dtbshadow'] = isset($attributes['dtbshadow']) ? $attributes['dtbshadow'] : 0;
        $attr['dtcshadow'] = isset($attributes['dtcshadow']) ? $attributes['dtcshadow'] : '';

        $attr['cwidth'] = isset($attributes['cwidth']) ? $attributes['cwidth'] : 750;
        $attr['ph'] = isset($attributes['ph']) ? $attributes['ph'] : 0;
        $attr['pv'] = isset($attributes['pv']) ? $attributes['pv'] : 0;
        $attr['cmt'] = isset($attributes['cmt']) ? $attributes['cmt'] : 0;
        $attr['cmb'] = isset($attributes['cmb']) ? $attributes['cmb'] : 0;

        $attr['heroimg'] = isset($attributes['heroimg']) ? $attributes['heroimg'] : 0;
        $attr['heroimgurl'] = isset($attributes['heroimgurl']) ? $attributes['heroimgurl'] : '';
        $attr['herov'] = isset($attributes['herov']) ? $attributes['herov'] : 'center';
        $attr['herow'] = isset($attributes['herow']) ? $attributes['herow'] : 1200;
        $attr['heroonsmall'] = isset($attributes['heroonsmall']) ? $attributes['heroonsmall'] : 1;
        $attr['heroml'] = isset($attributes['heroml']) ? $attributes['heroml'] : 0;
        $attr['herohpos'] = isset($attributes['herohpos']) ? $attributes['herohpos'] : 'left';
        $attr['heromt'] = isset($attributes['heromt']) ? $attributes['heromt'] : 0;
        $attr['herogradl'] = isset($attributes['herogradl']) ? $attributes['herogradl'] : 0;
        $attr['herogradr'] = isset($attributes['herogradr']) ? $attributes['herogradr'] : 0;

        $attr['link'] = isset($attributes['link']) ? $attributes['link'] : '';
        $attr['link_target'] = isset($attributes['link_target']) ? $attributes['link_target'] : '';

        $isimage = $attr['heroimgurl'] ? $attr['heroimgurl'] : $attr['image'];

        $output .= '<div class="mb2-pb-subelement mb2-pb-carousel_item" style="background-image:url(\'' . $isimage . '\');"' .
        theme_mb2nl_page_builder_el_datatts($attr, $attr) . '>';
        $output .= theme_mb2nl_page_builder_el_actions('subelement');
        $output .= '<div class="mb2-pb-subelement-inner">';
        $output .= '<img src="' . $isimage . '" class="theme-slider-img-src" alt="">';
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
function mb2_shortcode_mb2pb_pbmainslider_item($atts, $content = null) {

    global $gl0pbmainslideruniqid,
    $gl0pbmainslideritem,
    $gl0pbmainsliderid;

    $atts2 = [
        'id' => 'pbmainslider_item',
        'pbid' => '', // It's require for sorting elements below carousel items.
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
        'tthshadow' => 0.06,
        'ttvshadow' => 0.04,
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
        'dthshadow' => 0.06,
        'dtvshadow' => 0.04,
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
        'template' => '',
    ];

    $a = mb2_shortcode_atts($atts2, $atts);

    $output = '';
    $cls = '';
    $ccls = '';
    $cstyle = '';
    $tstyle = '';
    $dstyle = '';
    $tcls = '';
    $dcls = '';
    $btncls = '';
    $ctnavcls = '';
    $btnstyle = '';
    $rtl = theme_mb2nl_isrtl();
    $nexttext = $rtl ? get_string('prev') : get_string('next');
    $prevtext = $rtl ? get_string('next') : get_string('prev');
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
    $tstyle .= 'color:' . $a['tcolor'] . ';';
    $tstyle .= 'font-size:' . $a['tsize'] . 'rem;';
    $tstyle .= 'letter-spacing:' . $a['tlspacing'] . 'px;';
    $tstyle .= 'word-spacing:' . $a['twspacing'] . 'px;';
    $tstyle .= '--mb2-pb-mainslider-thshadow:' . $a['tthshadow'] . 'em;';
    $tstyle .= '--mb2-pb-mainslider-tvshadow:' . $a['ttvshadow'] . 'em;';
    $tstyle .= '--mb2-pb-mainslider-tbshadow:' . $a['ttbshadow'] . 'px;';
    $tstyle .= $a['ttcshadow'] ? '--mb2-pb-mainslider-tcshadow:' .
    $a['ttcshadow'] . ';' : '--mb2-pb-mainslider-tcshadow:transparent;';
    $tstyle .= '"';

    $dstyle .= ' style="';
    $dstyle .= 'color:' . $a['dcolor'] . ';';
    $dstyle .= 'font-size:' . $a['dsize'] . 'rem;';
    $dstyle .= 'letter-spacing:' . $a['dlspacing'] . 'px;';
    $dstyle .= 'word-spacing:' . $a['dwspacing'] . 'px;';
    $dstyle .= 'margin-top:' . $a['dmt'] . 'px;';
    $dstyle .= '--mb2-pb-mainslider-thshadow:' . $a['dthshadow'] . 'em;';
    $dstyle .= '--mb2-pb-mainslider-tvshadow:' . $a['dtvshadow'] . 'em;';
    $dstyle .= '--mb2-pb-mainslider-tbshadow:' . $a['dtbshadow'] . 'px;';
    $dstyle .= $a['dtcshadow'] ? '--mb2-pb-mainslider-tcshadow:' .
    $a['dtcshadow'] . ';' : '--mb2-pb-mainslider-tcshadow:transparent;';
    $dstyle .= '"';

    $colorstyle = $a['bgcolor'] ? ' style="background-color:' . $a['bgcolor'] . ';"' : '';

    $innerstyle = ' style="';
    $innerstyle .= $a['ocolor'] ? 'background-color:' . $a['ocolor'] . ';' : '';
    $innerstyle .= '"';

    if (isset($gl0pbmainslideritem)) {
        $gl0pbmainslideritem++;
    } else {
        $gl0pbmainslideritem = 0;
    }

    $a['pbid'] = $a['pbid'] ? $a['pbid'] : $gl0pbmainslideruniqid . $gl0pbmainslideritem;

    $output .= '<div class="mb2-pb-carousel-item swiper-slide" data-pbid="' . $a['pbid'] . '">';
    $output .= '<div class="pbmainslider-item-inner1">';
    $output .= '<div class="pbmainslider-item-inner' . $cls . '"' . $innerstyle . '>';
    $output .= '<div class="slide-content1">';
    $output .= '<div class="slide-content2">';
    $output .= '<div class="slide-content3' . $ccls . '"' . $cstyle . '>';
    $output .= '<div class="slide-content4">';
    $output .= '<h2 class="slide-title' . $tcls . '"' . $tstyle . '>';
    $output .= urldecode($a['title']);
    $output .= '</h2>';
    $output .= '<div class="slide-desc' . $dcls . '"' . $dstyle . '>';
    $output .= urldecode($a['desc']);
    $output .= '</div>';
    $output .= '<div class="slide-readmore">';
    $output .= '<a href="#" class="mb2-pb-btn' . $btncls . '"' .
    $btnstyle . '><span class="btn-intext">' . urldecode($a['btntext']) . '</span></a>';
    $output .= '</div>';
    $output .= theme_mb2nl_shortcodes_swiper_descnav($gl0pbmainsliderid, ['btnlink' => '#',
    'btntext' => urldecode($a['btntext']), 'link_target' => 0, 'cls' => $ctnavcls]);
    $output .= '</div>'; // ...slide-content4
    $output .= '</div>'; // ...slide-content3
    $output .= '</div>'; // ...slide-content2
    $output .= '<div class="slide-descbg"' . $colorstyle . '></div>'; // ...theme-slide-content2
    $output .= '</div>'; // ...slide-content1

    $output .= '<div class="slidehero-img-wrap">';
    $output .= '<div class="slidehero-img-wrap2">';
    $output .= '<div class="slidehero-img-wrap3" style="width:' . $a['herow'] . 'px;' .
    $a['herohpos'] . ':' . $a['heroml'] . '%;margin-top:' . $a['heromt'] . 'px;">';
    $output .= '<img class="slidehero-img" src="' . $a['heroimgurl'] . '" alt="">';
    $output .= '<div class="slidehero-img-grad grad-left" style="background-image:linear-gradient(to right,' .
    $a['ocolor'] . ',rgba(255,255,255,0)); "></div>';
    $output .= '<div class="slidehero-img-grad grad-right" style="background-image:linear-gradient(to right,rgba(255,255,255,0),' .
    $a['ocolor'] . '); "></div>';
    $output .= '</div>'; // ...hero-img-wrap23
    $output .= '</div>'; // ...hero-img-wrap2
    $output .= '</div>'; // ...hero-img-wrap

    $output .= '</div>'; // ...pbmainslider-item-inner1
    $output .= '</div>'; // ...pbmainslider-item-inner

    // Slider image.
    $isimage = $a['image'];

    if (!$isimage && !$a['heroimg']) {
        $isimage = theme_mb2nl_dummy_image('1900x750');
    }

    $output .= '<div class="theme-slider-img"><img src="' . $isimage . '" alt="' .
    $a['title'] . '"><div class="img-cover" style="background-image:url(\'' . $isimage . '\')"></div></div>';
    $output .= '</div>'; // ...pbmainslider-item

    return $output;

}
