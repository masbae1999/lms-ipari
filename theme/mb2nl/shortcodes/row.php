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


mb2_add_shortcode('row', 'mb2_shortcode_row');

/**
 *
 * Method to define shortcode
 *
 * @return HTML
 */
function mb2_shortcode_row ($atts, $content= null) {
    global $PAGE;

    $atts2 = [
        'isslider' => 0,
        'rowheader' => 0,
        'rowheader_content' => '',
        'rowheader_textcolor' => '',
        'rowheader_bgcolor' => '',
        'rowheader_mb' => 30,
        'colgutter' => 's',

        'bgcolor' => '',
        'obgimg' => 1,

        'bgvideo' => '',
        'prbg' => 0,
        'scheme' => 'light',
        'bgimage' => '',
        'bgfixed' => 0,
        'rowhidden' => 0,
        'rowlang' => '',
        'parallax' => 0,

        'bordert' => 0,
        'borderb' => 0,
        'bordertcolor' => '#dddddd',
        'borderbcolor' => '#dddddd',
        'borderfw' => 1,

        'heroimg' => 0,
        'herohpos' => 'left',
        'heroimgurl' => '',
        'herov' => 'center',
        'heroonsmall' => 1,
        'heroalttext' => '',

        'herow' => 1200,
        'heroml' => 0,
        'heromt' => 0,
        'herogradl' => 0,
        'herogradr' => 0,

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

        'pt' => 60,
        'pb' => 0,
        'fw' => 0,
        'mt' => 0,
        'va' => 0,
        'wave' => 'none',
        'wavecolor' => '#ffffff',
        'wavepos' => 0,
        'wavefliph' => 0,
        'wavetop' => 0,
        'wavewidth' => 100,
        'waveheight' => 150,
        'waveover' => 1,
        'rowaccess' => 0,
        'custom_class' => '',

        'gradient' => 0,
        'graddeg' => 90,
        'gradloc1' => 0,
        'gradloc2' => 100,
        'gradcolor1' => '#37E2D5',
        'gradcolor2' => '#590696',
    ];

    $a = mb2_shortcode_atts($atts2, $atts);

    $output = '';
    $rowstyle = '';
    $btcls = '';
    $btcls2 = '';
    $btstyle = '';
    $wrapstyle = '';
    $wavestyle = '';
    $wavenum = 0;
    $innercls = '';

    $innercls .= ' ' . theme_mb2nl_tsize_cls($a['pt'], 'rowpt-', false);
    $innercls .= ' ' . theme_mb2nl_tsize_cls($a['pb'], 'rowpb-', false);

    $cls = $a['custom_class'] ? ' ' . $a['custom_class'] : '';
    $cls .= ' pre-bg' . $a['prbg'];
    $cls .= ' ' . $a['scheme'];
    $cls .= ' bgfixed' . $a['bgfixed'];
    $cls .= ' wave-' . $a['wave'];
    $cls .= ' va' . $a['va'];
    $cls .= ' bgfixed' . $a['bgfixed'];
    $cls .= ' wavefliph' . $a['wavefliph'];
    $cls .= ' wavepos' . $a['wavepos'];
    $cls .= ' colgutter-' . $a['colgutter'];
    $cls .= ' parallax' . $a['parallax'];
    $cls .= ' heroimg' . $a['heroimg'];
    $cls .= ' herov' . $a['herov'];
    $cls .= ' herogradl' . $a['herogradl'];
    $cls .= ' herogradr' . $a['herogradr'];
    $cls .= ' bgtextmob' . $a['bgtextmob'];
    $cls .= ' waveover' .$a['waveover'];
    $cls .= ' heroonsmall' . $a['heroonsmall'];
    $cls .= ' bordert' . $a['bordert'];
    $cls .= ' borderb' . $a['borderb'];
    $cls .= ' borderfw' . $a['borderfw'];
    $cls .= ' obgimg' . $a['obgimg'];
    $cls .= theme_mb2nl_is_image($a['heroimgurl']) ? ' heroisimg' : ' heroisvideo';
    $cls .= ' isfw' . $a['fw'];
    $cls .= $a['isslider'] ? ' isslider' : '';
    $cls .= $a['bgimage'] ? ' lazy' : '';

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

    $langarr = explode(',', trim($a['rowlang']));
    $trimmedlangarr = array_map('trim', $langarr);

    if (trim($a['rowlang']) && ! in_array(current_language(), $trimmedlangarr)) {
        return;
    }

    if ($a['rowhidden'] && ! is_siteadmin()) {
        return;
    }

    if ($a['rowhidden'] && is_siteadmin()) {
        $cls .= ' hiddenel';
    }

    if ($a['rowaccess'] == 1) {
        if (! isloggedin() || isguestuser()) {
            return;
        }
    } else if ($a['rowaccess'] == 2) {
        if (isloggedin() && ! isguestuser()) {
            return;
        }
    }

    $isid = theme_mb2nl_get_id_from_class($a['custom_class']);
    $idattr = $isid ? 'id="' . $isid . '" ' : '';

    $wrapstyle .= ' style="';
    $wrapstyle .= 'margin-top:' . $a['mt'] . 'px;';
    $wrapstyle .= $a['bgcolor'] ? '--mb-pb-row_bgcolor:' . $a['bgcolor'] . ';' : '';
    $wrapstyle .= $a['bordertcolor'] ? '--mb-pb-row_bordertcolor:' . $a['bordertcolor'] . ';' : '';
    $wrapstyle .= $a['borderbcolor'] ? '--mb-pb-row_borderbcolor:' . $a['borderbcolor'] . ';' : '';
    $wrapstyle .= '"';

    $databgimage = $a['bgimage'] ? ' data-bg="' . $a['bgimage'] . '"' : '';

    $rowstyle .= ' style="';
    $rowstyle .= 'padding-top:' . $a['pt'] . 'px;';
    $rowstyle .= 'padding-bottom:' . $a['pb'] . 'px;';
    $rowstyle .= '"';

    $output .= '<div ' . $idattr . 'class="mb2-pb-row' . $cls . '"' . $wrapstyle . $databgimage . '>';
    $output .= '<div class="section-inner mb2-pb-row-inner' . $innercls . '"' . $rowstyle . '>';
    $output .= '<div class="row-topgap w-100"></div>';
    $output .= '<div class="container-fluid">';
    $output .= '<div class="row">';
    $output .= mb2_do_shortcode($content);
    $output .= '</div>';
    $output .= '</div>';

    $output .= $a['gradient'] ? '<div class="rowgrad" style="background-image:linear-gradient(' .
    $a['graddeg'] . 'deg,' . $a['gradcolor1'] . ' ' . $a['gradloc1'] . '%,' . $a['gradcolor2'] . ' ' .
    $a['gradloc2'] . '%);"></div>' : '';

    if ($a['heroimg']) {
        $output .= '<div class="hero-img-wrap" aria-hidden="true">';
        $output .= '<div class="hero-img-wrap2">';
        $output .= '<div class="hero-img-wrap3" style="width:' . $a['herow'] . 'px;' . $a['herohpos'] . ':' .
        $a['heroml'] . '%;--mb2-pb-herovm:' . $a['heromt'] . 'px;">';

        if (theme_mb2nl_is_image($a['heroimgurl'])) {
            $output .= '<img class="hero-img lazy" src="' . theme_mb2nl_lazy_plc(true) . '" data-src="' .
            $a['heroimgurl'] . '" alt="' . $a['heroalttext'] . '">';
        } else {
            $output .= '<video class="hero-video" autoplay muted loop tabindex="-1">';
            $output .= '<source src="' . $a['heroimgurl'] . '">';
            $output .= '</video>';
        }

        $output .= $a['herogradl'] ?
        '<div class="hero-img-grad grad-left" style="background-image:linear-gradient(to right,' .
        $a['bgcolor'] . ',rgba(255,255,255,0)); "></div>' : '';
        $output .= $a['herogradr'] ?
        '<div class="hero-img-grad grad-right" style="background-image:linear-gradient(to right,rgba(255,255,255,0),' .
        $a['bgcolor'] . '); "></div>' : '';
        $output .= '</div>'; // ...hero-img-wrap3
        $output .= '</div>'; // ...hero-img-wrap2
        $output .= '</div>'; // ...hero-img-wrap
    }

    if ($a['bgtext']) {
        $output .= '<div class="bgtext' . $btcls . '" aria-hidden="true">';
        $output .= '<div class="bgtext-text' . $btcls2 . '"' . $btstyle . '>';
        $output .= $a['bgtexttext'];
        $output .= '</div>';
        $output .= '</div>';
    }

    if ($a['wave'] !== 'none' && $a['wave'] != 0) {
        $waves = theme_mb2nl_get_waves();
        $wavestyle .= ' style="';
        $wavestyle .= 'width:' . $a['wavewidth'] . '%;';
        $wavestyle .= 'height:' . $a['waveheight'] . 'px;';
        $wavestyle .= '"';

        foreach ($waves as $a['wave']) {
            $wavenum++;

            $output .= '<div class="mb2-pb-row-wave wave-' . $wavenum . '">';
            $output .= '<svg xmlns="http://www.w3.org/2000/svg" viewBox="' . $a['wave']['box'] . '" preserveAspectRatio="none"' .
            $wavestyle . '><path fill="' . $a['wavecolor'] . '" fill-opacity="1" d="' . $a['wave']['d'] . '"></path></svg>';
            $output .= '</div>';
        }
    }

    $output .= '</div>';

    if ($a['bgvideo']) {
        $output .= '<div class="section-video">';
        $output .= '<video autoplay muted loop >';
        $output .= '<source src="' . $a['bgvideo'] . '">';
        $output .= '</video>';
        $output .= '</div>'; // ...section-video
    }

    if ($a['parallax']) {
        $output .= '<img class="parallax-img lazy" src="' . theme_mb2nl_lazy_plc() . '" data-src="' .
        $a['bgimage'] . '" alt="" aria-hidden="true">';
    }

    $output .= '</div>';

    return $output;

}
