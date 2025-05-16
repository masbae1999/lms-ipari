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

mb2_add_shortcode('heading', 'mb2_shortcode_heading');

/**
 *
 * Method to define shortcode
 *
 * @return HTML
 */
function mb2_shortcode_heading ($atts, $content = null) {

    global $PAGE;

    $atts2 = [
        'tag' => 'h4',
        'size' => 2.4,
        'align' => 'none',

        'fwcls' => 'global',
        'lhcls' => 'global',
        'lspacing' => 0,
        'wspacing' => 0,
        'upper' => 0,
        'mt' => 0,
        'mb' => 30,
        'width' => 2000,

        'btext' => '',
        'atext' => '',
        'color' => '',
        'acolor' => '',
        'bcolor' => '',
        'afwcls' => 'global',
        'bfwcls' => 'global',
        'nline' => 0,

        'thshadow' => 0.06,
        'tvshadow' => 0.04,
        'tbshadow' => 0,
        'tcshadow' => '',

        'link' => 0,
        'linkurl' => '#',
        'linktarget' => 0,
        'linktext' => get_string('viewall', 'local_mb2builder'),

        'typed' => 0,
        'typespeed' => 50,
        'backspeed' => 50,
        'backdelay' => 1500,
        'typedtext' => 'first word|second word|third word',

        'custom_class' => '',
    ];

    $a = mb2_shortcode_atts($atts2, $atts);

    $output = '';
    $style = '';
    $typeddata = '';
    $astyle = '';
    $bstyle = '';
    $textstyle = '';
    $acls = '';
    $bcls = '';
    $textcls = '';
    $typedid = uniqid('typed_');
    $target = $a['linktarget'] ? ' target="_blank"' : '';

    $cls = $a['custom_class'] != '' ? ' ' . $a['custom_class'] : '';
    $cls .= ' heading-' . $a['align'];
    $cls .= ' upper' . $a['upper'];
    $cls .= ' fw' . $a['fwcls'];
    $cls .= ' lh' . $a['lhcls'];
    $cls .= ' ' . theme_mb2nl_tsize_cls($a['size']);

    $acls .= ' fw' . $a['afwcls'];
    $bcls .= ' fw' . $a['bfwcls'];
    $textcls .= ' fw' . $a['fwcls'];
    $textcls .= ' nline' . $a['nline'];

    // Style for heading element.
    $style .= ' style="';
    $style .= 'margin-top:' . $a['mt'] . 'px;';
    $style .= 'margin-bottom:' . $a['mb'] . 'px;';
    $style .= 'max-width:' . $a['width'] . 'px;margin-left:auto;margin-right:auto;';
    $style .= $a['lspacing'] != 0 ? 'letter-spacing:' . $a['lspacing'] . 'px;' : '';
    $style .= $a['wspacing'] != 0 ? 'word-spacing:' . $a['wspacing'] . 'px;' : '';
    $style .= $a['size'] ? 'font-size:' . $a['size'] . 'rem;' : '';
    $style .= '--mb2-pb-heading-thshadow:' . $a['thshadow'] . 'em;';
    $style .= '--mb2-pb-heading-tvshadow:' . $a['tvshadow'] . 'em;';
    $style .= '--mb2-pb-heading-tbshadow:' . $a['tbshadow'] . 'px;';
    $style .= $a['tcshadow'] ? '--mb2-pb-heading-tcshadow:' .
    $a['tcshadow'] . ';' : '--mb2-pb-heading-tcshadow:transparent;';
    $style .= '"';

    // Style for after heading element.
    if ($a['acolor']) {
        $astyle .= ' style="';
        $astyle .= 'color:' . $a['acolor'] . ';';
        $astyle .= '"';
    }

    // Style for before heading element.
    if ($a['bcolor']) {
        $bstyle .= ' style="';
        $bstyle .= 'color:' . $a['bcolor'] . ';';
        $bstyle .= '"';
    }

    // Style for text heading element.
    if ($a['color']) {
        $textstyle .= ' style="';
        $textstyle .= 'color:' . $a['color'] . ';';
        $textstyle .= '"';
    }

    if ($a['typed']) {
        $typeddata .= ' data-typespeed="' . $a['typespeed'] . '"';
        $typeddata .= ' data-backspeed="' . $a['backspeed'] . '"';
        $typeddata .= ' data-backdelay="' . $a['backdelay'] . '"';
        $typeddata .= ' data-typedtext="' . $a['typedtext'] . '"';
    }

    $output .= '<' . $a['tag'] . $style . $typeddata . ' id="' . $typedid . '" class="heading' . $cls . '">';
    $output .= $a['btext'] ? '<span class="btext' . $bcls . '"' . $bstyle . '>' .
    theme_mb2nl_format_str($a['btext']) . '</span>' : '';
    $output .= '<span class="headingtext' . $textcls . '"' . $textstyle . '>';
    $output .= $a['typed'] ? theme_mb2nl_typed_content(theme_mb2nl_format_str($content), $a['typedtext']) :
        theme_mb2nl_format_str($content);
    $output .= '</span>';
    $output .= $a['atext'] ? '<span class="atext' . $acls . '"' . $astyle . '>' .
    theme_mb2nl_format_str($a['atext']) . '</span>' : '';
    $output .= $a['link'] ? '<a href="' . $a['linkurl'] . '"' . $target . ' class="heading-more"' . $textstyle . '>' .
    theme_mb2nl_format_str($a['linktext']). '<i class="fa fa-arrow-right"' . $astyle . '></i></a>' : '';
    $output .= '</' . $a['tag'] . '>';

    return $output;

}
