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

mb2_add_shortcode('mb2pb_row', 'mb2_shortcode_mb2pb_row');

/**
 *
 * Method to define shortcode
 *
 * @return HTML
 */
function mb2_shortcode_mb2pb_row ($atts, $content = null) {
    global $PAGE;

    $atts2 = [
        'id' => 'row',
        'isslider' => 0,

        'bgcolor' => '',
        'obgimg' => 1,

        'bgfixed' => 0,
        'colgutter' => 's',
        'prbg' => 0,
        'scheme' => 'light',
        'bgimage' => '',

        'bordert' => 0,
        'borderb' => 0,
        'bordertcolor' => '#dddddd',
        'borderbcolor' => '#dddddd',
        'borderfw' => 1,

        'heroimg' => 0,
        'heroimgurl' => '',
        'herov' => 'center',

        'herow' => 1200,
        'herohpos' => 'left',
        'heroml' => 0,
        'heromt' => 0,
        'heroonsmall' => 1,
        'herogradl' => 0,
        'herogradr' => 0,
        'heroalttext' => '',

        'bgtext' => 0,
        'bgtextmob' => 0,
        'bgtexttext' => 'Sample text',
        'btsize' => 290,
        'btfweight' => 600,
        'btlh' => 1,
        'btlspacing' => 0,
        'btwspacing' => 0,
        'btupper' => 0,
        'bth' => 'left',
        'btv' => 'center',
        'btcolor' => 'rgba(0,0,0,.05)',

        'bgvideo' => '',
        'rowhidden' => 0,
        'rowlang' => '',
        'pt' => 60,
        'pb' => 0,
        'fw' => 0,
        'va' => 0,
        'parallax' => 0,
        'rowaccess' => 0,
        'custom_class' => '',
        'template' => '',
        'wave' => 'none',
        'wavecolor' => '#ffffff',
        'wavepos' => 0,
        'wavefliph' => 0,
        'wavetop' => 0,
        'wavewidth' => 100,
        'waveheight' => 150,
        'waveover' => 1,
        'mt' => 0,

        'gradient' => 0,
        'graddeg' => 45,
        'gradloc1' => 0,
        'gradloc2' => 100,
        'gradcolor1' => '#37E2D5',
        'gradcolor2' => '#590696',
    ];

    $a = mb2_shortcode_atts($atts2, $atts);

    $output = '';
    $headercls = '';
    $wrapstyle = '';
    $btcls = '';
    $btcls2 = '';
    $btstyle = '';
    $wavestyle = '';
    $wavenum = 0;
    $innercls = '';

    $innercls .= ' ' . theme_mb2nl_tsize_cls($a['pt'], 'rowpt-', false);
    $innercls .= ' ' . theme_mb2nl_tsize_cls($a['pb'], 'rowpb-', false);

    $cls = $a['custom_class'] ? ' ' . $a['custom_class'] : '';
    $cls .= ' pre-bg' . $a['prbg'];
    $cls .= ' ' . $a['scheme'];
    $cls .= ' hidden' . $a['rowhidden'];
    $cls .= ' access' . $a['rowaccess'];
    $cls .= ' colgutter-' . $a['colgutter'];
    $cls .= ' isfw' . $a['fw'];
    $cls .= ' va' . $a['va'];
    $cls .= ' wave-' . $a['wave'];
    $cls .= ' bgfixed' . $a['bgfixed'];
    $cls .= ' wavefliph' . $a['wavefliph'];
    $cls .= ' wavepos' . $a['wavepos'];
    $cls .= ' waveover' . $a['waveover'];
    $cls .= ' parallax' . $a['parallax'];
    $cls .= ' rowgrad' . $a['gradient'];

    $cls .= ' obgimg' . $a['obgimg'];

    $cls .= ' bordert' . $a['bordert'];
    $cls .= ' borderb' . $a['borderb'];
    $cls .= ' borderfw' . $a['borderfw'];

    $cls .= ' heroimg' . $a['heroimg'];
    $cls .= ' herov' . $a['herov'];
    $cls .= ' herogradl' . $a['herogradl'];
    $cls .= ' herogradr' . $a['herogradr'];
    $cls .= ' heroonsmall' . $a['heroonsmall'];
    $cls .= theme_mb2nl_is_image($a['heroimgurl']) ? ' heroisimg' : ' heroisvideo';

    $cls .= ' bgtext' . $a['bgtext'];
    $cls .= ' bgtextmob' . $a['bgtextmob'];

    $cls .= $a['template'] ? ' mb2-pb-template-row' : '';

    $btcls2 .= ' btupper' . $a['btupper'];
    $btcls .= ' bth' . $a['bth'];
    $btcls .= ' btv' . $a['btv'];

    $btstyle .= ' style="';
    $btstyle .= 'font-size:' . $a['btsize'] . 'px;';
    $btstyle .= 'font-weight:' . $a['btfweight'] . ';';
    $btstyle .= 'line-height:' . $a['btlh'] . ';';
    $btstyle .= 'letter-spacing:' . $a['btlspacing'] . 'px;';
    $btstyle .= 'word-spacing:' . $a['btwspacing'] . 'px;';
    $btstyle .= 'color:' . $a['btcolor'] . ';';
    $btstyle .= '"';

    $langarr = explode(',', $a['rowlang']);
    $trimmedlangarr = array_map('trim', $langarr);

    $isid = theme_mb2nl_get_id_from_class($a['custom_class']);
    $idattr = $isid ? 'id="' . $isid . '" ' : '';

    $wrapstyle .= ' style="';
    $wrapstyle .= $a['bgimage'] ? 'background-image:url(\'' . $a['bgimage'] . '\');' : '';
    $wrapstyle .= 'margin-top:' . $a['mt'] . 'px;';
    $wrapstyle .= $a['bgcolor'] ? '--mb-pb-row_bgcolor:' . $a['bgcolor'] . ';' : '';
    $wrapstyle .= $a['bordertcolor'] ? '--mb-pb-row_bordertcolor:' . $a['bordertcolor'] . ';' : '';
    $wrapstyle .= $a['borderbcolor'] ? '--mb-pb-row_borderbcolor:' . $a['borderbcolor'] . ';' : '';
    $wrapstyle .= '"';

    $rowstyle = ' style="';
    $rowstyle .= 'padding-top:' . $a['pt'] . 'px;';
    $rowstyle .= 'padding-bottom:' . $a['pb'] . 'px;';
    $rowstyle .= '"';

    $output .= '<div ' . $idattr . 'class="mb2-pb-row mb2-pb-fprow' . $cls . '"' .
    $wrapstyle . theme_mb2nl_page_builder_el_datatts($atts, $atts2) . '>';
    $output .= theme_mb2nl_page_builder_el_actions('row', 'row', ['lang' => $trimmedlangarr]);
    $output .= '<div class="section-inner mb2-pb-row-inner' . $innercls . '"' . $rowstyle . '>';
    $output .= '<div class="row-topgap w-100 fwbold upper1"></div>';
    $output .= '<div class="container-fluid">';

    $output .= '<div class="row mb2-pb-sortable-columns">';
    $output .= mb2_do_shortcode($content);

    $output .= '</div>'; // ...row mb2-pb-sortable-columns
    $output .= '</div>'; // ...container-fluid

    $wavestyle .= ' style="';
    $wavestyle .= 'width:' . $a['wavewidth'] . '%;';
    $wavestyle .= 'height:' . $a['waveheight'] . 'px;';
    $wavestyle .= '"';

    $output .= '<div class="rowgrad" style="background-image:linear-gradient(' . $a['graddeg'] . 'deg,' .
    $a['gradcolor1'] . ' ' . $a['gradloc1'] . '%,' . $a['gradcolor2'] . ' ' . $a['gradloc2'] . '%);"></div>';

    $output .= '<div class="hero-img-wrap">';
    $output .= '<div class="hero-img-wrap2">';
    $output .= '<div class="hero-img-wrap3" style="width:' . $a['herow'] . 'px;' . $a['herohpos'] . ':' .
    $a['heroml'] . '%;--mb2-pb-herovm:' . $a['heromt'] . 'px;">';

    $output .= '<video class="hero-video" autoplay muted loop >';
    $output .= '<source src="' . $a['heroimgurl'] . '">';
    $output .= '</video>';

    $output .= '<img class="hero-img" src="' . $a['heroimgurl'] . '" alt="' . $a['heroalttext'] . '">';

    $output .= '<div class="hero-img-grad grad-left" style="background-image:linear-gradient(to right,' .
    $a['bgcolor'] . ',rgba(255,255,255,0));"></div>';
    $output .= '<div class="hero-img-grad grad-right" style="background-image:linear-gradient(to right,rgba(255,255,255,0),' .
    $a['bgcolor'] . ');"></div>';
    $output .= '</div>'; // ...hero-img-wrap23
    $output .= '</div>'; // ...hero-img-wrap2
    $output .= '</div>'; // ...hero-img-wrap

    $output .= '<div class="bgtext' . $btcls . '">';
    $output .= '<div class="bgtext-text' . $btcls2 . '"' . $btstyle . '>';
    $output .= $a['bgtexttext'];
    $output .= '</div>';
    $output .= '</div>';

    $waves = theme_mb2nl_get_waves();

    foreach ($waves as $a['wave']) {
        $wavenum++;

        $output .= '<div class="mb2-pb-row-wave wave-' . $wavenum . '">';
        $output .= '<svg xmlns="http://www.w3.org/2000/svg" viewBox="' . $a['wave']['box'] . '" preserveAspectRatio="none"' .
        $wavestyle . '><path fill="' . $a['wavecolor'] . '" fill-opacity="1" d="' . $a['wave']['d'] . '"></path></svg>';
        $output .= '</div>';
    }

    $output .= '</div>'; // ...mb2-pb-row-inner

    $output .= '<div class="section-video">';
    $output .= '<video autoplay muted loop >';
    $output .= '<source src="' . $a['bgvideo'] . '">';
    $output .= '</video>';
    $output .= '</div>'; // ...section-video

    // Parallax.
    $output .= '<img class="parallax-img" src="' . $a['bgimage'] . '" alt="">';

    $output .= '</div>'; // ...mb2-pb-row

    return $output;

}
