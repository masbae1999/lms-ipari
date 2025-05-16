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

mb2_add_shortcode('mb2pb_heading', 'mb2_shortcode_mb2pb_heading');

/**
 *
 * Method to define shortcode
 *
 * @return HTML
 */
function mb2_shortcode_mb2pb_heading($atts, $content = null) {

    global $PAGE;

    $atts2 = [
        'id' => 'heading',
        'tag' => 'h4',
        'size' => 1.33,
        'align' => 'none',
        'btext' => '',
        'atext' => '',
        'fwcls' => 'global',
        'lhcls' => 'global',
        'lspacing' => 0,
        'wspacing' => 0,
        'upper' => 0,
        'nline' => 0,
        'mt' => 0,
        'mb' => 30,
        'width' => 2000,
        'color' => '',
        'acolor' => '',
        'bcolor' => '',
        'afwcls' => 'global',
        'bfwcls' => 'global',
        'custom_class' => '',

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

        'template' => '',
    ];

    $a = mb2_shortcode_atts($atts2, $atts);

    $output = '';
    $style = '';
    $elstyle = '';
    $astyle = '';
    $bstyle = '';
    $textstyle = '';
    $cls = '';
    $acls = '';
    $bcls = '';
    $textcls = '';
    $typedid = uniqid('typed_');
    $a['linktext'] = $a['linktext'] ? $a['linktext'] : get_string('viewall', 'local_mb2builder');

    $cls .= ' heading-' . $a['align'];
    $cls .= ' ' . $a['tag'];
    $cls .= ' upper' . $a['upper'];
    $cls .= ' lh' . $a['lhcls'];
    $cls .= ' link' . $a['link'];
    $cls .= ' ' . theme_mb2nl_tsize_cls($a['size']);
    $cls .= $a['custom_class'] !== '' ? ' ' . $a['custom_class'] : '';

    $acls .= ' fw' . $a['afwcls'];
    $bcls .= ' fw' . $a['bfwcls'];

    $textcls .= ' fw' . $a['fwcls'];
    $textcls .= ' nline' . $a['nline'];

    $tmplcls = $a['template'] ? ' mb2-pb-template-heading' : '';

    $style .= ' style="';
    $style .= 'margin-top:' . $a['mt'] . 'px;';
    $style .= 'margin-bottom:' . $a['mb'] . 'px;';
    $style .= 'max-width:' . $a['width'] . 'px;margin-left:auto;margin-right:auto;';
    $style .= '"';

    // Style for heading element.
    $elstyle .= ' style="';
    $elstyle .= $a['lspacing'] != 0 ? 'letter-spacing:' . $a['lspacing'] . 'px;' : '';
    $elstyle .= $a['wspacing'] != 0 ? 'word-spacing:' . $a['wspacing'] . 'px;' : '';
    $elstyle .= $a['size'] ? 'font-size:' . $a['size'] . 'rem;' : '';
    $elstyle .= '--mb2-pb-heading-thshadow:' . $a['thshadow'] . 'em;';
    $elstyle .= '--mb2-pb-heading-tvshadow:' . $a['tvshadow'] . 'em;';
    $elstyle .= '--mb2-pb-heading-tbshadow:' . $a['tbshadow'] . 'px;';
    $elstyle .= $a['tcshadow'] ? '--mb2-pb-heading-tcshadow:' .
    $a['tcshadow'] . ';' : '--mb2-pb-heading-tcshadow:transparent;';
    $elstyle .= '"';

    // Style for after heading element.
    $astyle .= ' style="';
    $astyle .= $a['acolor'] ? 'color:' . $a['acolor'] . ';' : '';
    $astyle .= '"';

    // Style for before heading element.
    $bstyle .= ' style="';
    $bstyle .= $a['bcolor'] ? 'color:' . $a['bcolor'] . ';' : '';
    $bstyle .= '"';

    // Style for text heading element.
    $textstyle .= ' style="';
    $textstyle .= $a['color'] ? 'color:' . $a['color'] . ';' : '';
    $textstyle .= '"';

    $content = $content ? $content : 'Heading text here';
    $atts2['content'] = $content;
    $opts = theme_mb2nl_page_builder_2arrays($atts, $atts2);

    $output .= '<div class="mb2-pb-element mb2-pb-heading' . $tmplcls . '"' . $style .
    theme_mb2nl_page_builder_el_datatts($atts, $atts2) . '>';
    $output .= '<div class="element-helper"></div>';
    $output .= theme_mb2nl_page_builder_el_actions('element', 'heading');
    $output .= '<h4 id="' . $typedid . '" class="heading' . $cls . '"' . $elstyle . '>';
    $output .= '<span class="btext' . $bcls . '"' . $bstyle . '>' . $a['btext'] . '</span>';
    $output .= '<span class="headingtext' . $textcls . '"' . $textstyle . '>';
    $output .= $a['typed'] ? theme_mb2nl_typed_content(urldecode($content), $a['typedtext']) : urldecode($content);
    $output .= '</span>';
    $output .= '<span class="atext' . $acls . '"' . $astyle . '>' . $a['atext'] . '</span>';
    $output .= '<span class="heading-more"' . $textstyle . '><span class="heading-more-text">' .
    $a['linktext'] . '</span><i class="fa fa-arrow-right"' . $astyle . '></i></span>';
    $output .= '</h4>';
    $output .= '</div>';

    return $output;

}
