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

mb2_add_shortcode('mb2pb_boxescontent', 'mb2pb_shortcode_boxescontent');
mb2_add_shortcode('mb2pb_boxescontent_item', 'mb2pb_shortcode_boxescontent_item');

/**
 *
 * Method to define boxes content item shortcode
 *
 * @return HTML
 */
function mb2pb_shortcode_boxescontent($atts, $content = null) {

    global $gl0bxmb,
    $gl0bxheight,
    $gl0bxcontenttlefs,
    $gl0bxcontenttlefw,
    $gl0bxcontentcwidth,
    $gl0bxcontentbtntext,
    $gl0bxcontentbtntype,
    $gl0bxcontentbtnsize,
    $gl0bxcontentbtnfwcls,
    $gl0bxcontentbtnborder,
    $gl0bxcontentbtnrounded,
    $gl0bxcontentbtnborder;

    $atts2 = [
        'id' => 'boxescontent',
        'boxid' => 'boxescontent',
        'columns' => 3, // ...max 5
        'gutter' => 'normal',
        'type' => 1,
        'rounded' => 0,
        'tfs' => 1.4,
        'tfw' => 'global',
        'wave' => 0,
        'height' => 0,
        'mt' => 0,
        'mb' => 0, // 0 because box item has margin bottom 30 pixels
        'boxmb' => 0,
        'padding' => 'm',

        'shadow' => 0,
        'border' => 0,

        'custom_class' => '',
        'itemlink' => 0,

        'btnhor' => 0,
        'cwidth' => 2000,
        'linkbtn' => 0,
        'btntype' => 'primary',
        'btnsize' => 'normal',
        'btnfwcls' => 'global',
        'btnrounded' => 0,
        'btnborder' => 0,
        'btntext' => '',

        'ccolor' => '',
        'bgcolor' => '',
        'bocolor' => '',
        'tcolor' => '',
        'txcolor' => '',

        'tcenter' => 0,

        'template' => '',
    ];

    $a = mb2_shortcode_atts($atts2, $atts);

    $output = '';
    $cls = '';
    $style = '';
    $bxstyle = '';

    $gl0bxmb = $a['boxmb'];
    $gl0bxheight = $a['height'];
    $gl0bxcontenttlefs = $a['tfs'];
    $gl0bxcontenttlefw = $a['tfw'];
    $gl0bxcontentcwidth = $a['cwidth'];
    $gl0bxcontentbtntext = $a['btntext'];
    $gl0bxcontentbtntype = $a['btntype'];
    $gl0bxcontentbtnsize = $a['btnsize'];
    $gl0bxcontentbtnfwcls = $a['btnfwcls'];
    $gl0bxcontentbtnborder = $a['btnborder'];
    $gl0bxcontentbtnrounded = $a['btnrounded'];
    $gl0bxcontentbtnborder = $a['border'];

    $cls .= ' gutter-' . $a['gutter'];
    $cls .= ' theme-col-' . $a['columns'];
    $cls .= ' rounded' . $a['rounded'];
    $cls .= ' linkbtn' . $a['linkbtn'];
    $cls .= ' wave' . $a['wave'];
    $cls .= ' type-' . $a['type'];
    $cls .= ' btnhor' . $a['btnhor'];
    $cls .= ' padding' . $a['padding'];
    $cls .= ' shadow' . $a['shadow'];
    $cls .= ' itemlink' . $a['itemlink'];
    $cls .= ' tcenter' . $a['tcenter'];
    $cls .= $a['custom_class'] ? ' ' . $a['custom_class'] : '';
    $templatecls = $a['template'] ? ' mb2-pb-template-boxescontent' : '';

    $style .= ' style="';
    $style .= $a['mt'] ? 'margin-top:' . $a['mt'] . 'px;' : '';
    $style .= $a['mb'] ? 'margin-bottom:' . $a['mb'] . 'px;' : '';
    $style .= '"';

    $bxstyle .= ' style="';
    $bxstyle .= $a['ccolor'] ? '--mb2-pb-bxaccolor:' . $a['ccolor'] . ';' : '';
    $bxstyle .= $a['bgcolor'] ? '--mb2-pb-bxbgcolor:' . $a['bgcolor'] . ';' : '';
    $bxstyle .= $a['bocolor'] ? '--mb2-pb-bxbocolor:' . $a['bocolor'] . ';' : '';
    $bxstyle .= $a['tcolor'] ? '--mb2-pb-bxtcolor:' . $a['tcolor'] . ';' : '';
    $bxstyle .= $a['txcolor'] ? '--mb2-pb-bxtxcolor:' . $a['txcolor'] . ';' : '';
    $bxstyle .= '"';

    $content = $content;

    if (! $content) {
        for ($i = 1; $i <= 3; $i++) {
            $content .= '[mb2pb_boxescontent_item title="Box title here" label="' .
            $i . '" ]Box content here.[/mb2pb_boxescontent_item]';
        }
    }

    $output .= '<div class="mb2-pb-element mb2-pb-boxescontent' . $templatecls . '"' .
    $style . theme_mb2nl_page_builder_el_datatts($atts, $atts2) . '>';
    $output .= '<div class="element-helper"></div>';
    $output .= theme_mb2nl_page_builder_el_actions('element', 'boxescontent');
    $output .= '<div class="mb2-pb-element-inner theme-boxes theme-boxescontent' . $cls . '"' . $bxstyle . '>';
    $output .= '<div class="mb2-pb-sortable-subelements">';
    $output .= mb2_do_shortcode($content);
    $output .= '</div>';
    $output .= '</div>';
    $output .= '</div>';

    return $output;

}


/**
 *
 * Method to define boxes content item shortcode
 *
 * @return HTML
 */
function mb2pb_shortcode_boxescontent_item($atts, $content = null) {

    global $gl0bxmb,
    $gl0bxheight,
    $gl0bxcontenttlefs,
    $gl0bxcontenttlefw,
    $gl0bxcontentcwidth,
    $gl0bxcontentbtntext,
    $gl0bxcontentbtntype,
    $gl0bxcontentbtnsize,
    $gl0bxcontentbtnfwcls,
    $gl0bxcontentbtnborder,
    $gl0bxcontentbtnrounded,
    $gl0bxcontentbtnborder;

    $atts2 = [
        'id' => 'boxescontent_item',
        'icon' => '',
        'title' => 'Box title here',
        'link' => '',
        'bgimage' => '',
        'link_target' => 0,

        'btncolor' => '',
        'btnbgcolor' => '',
        'btnbghcolor' => '',
        'btnhcolor' => '',
        'btnborcolor' => '',
        'btnborhcolor' => '',

        'ccolor' => '',
        'bgcolor' => '',
        'bocolor' => '',
        'tcolor' => '',
        'txcolor' => '',

        'template' => '',
    ];

    $a = mb2_shortcode_atts($atts2, $atts);

    $output = '';
    $fcls = '';
    $boxstyle = '';
    $stylebgimg = '';
    $boxscttyle = '';
    $heightstyle = '';
    $btnstyle = '';
    $cls = '';

    $a['btntext'] = $gl0bxcontentbtntext ? $gl0bxcontentbtntext : get_string('readmorefp', 'local_mb2builder');
    $content = ! $content ? 'Box content here.' : $content;
    $atts2['content'] = $content;

    $boxscttyle .= ' style="';
    $boxscttyle .= 'max-width:' . $gl0bxcontentcwidth . 'px;';
    $boxscttyle .= '"';

    $boxstyle .= ' style="';
    $boxstyle .= $gl0bxmb ? 'margin-bottom:' . $gl0bxmb . 'px;' : '';
    $boxstyle .= $gl0bxcontentbtnborder ? '--mb2-pb-bxborder:' . $gl0bxcontentbtnborder .'px;' : '';
    $boxstyle .= $a['bgimage'] ? 'background-image:url(\''. $a['bgimage'] . '\');' : '';
    $boxstyle .= $a['bgcolor'] ? '--mb2-pb-bxbgcolor:' . $a['bgcolor'] .';' : '';
    $boxstyle .= $a['bocolor'] ? '--mb2-pb-bxbocolor:' . $a['bocolor'] .';' : '';
    $boxstyle .= $a['ccolor'] ? '--mb2-pb-bxaccolor:' . $a['ccolor'] . ';' : '';
    $boxstyle .= $a['tcolor'] ? '--mb2-pb-bxtcolor:' . $a['tcolor'] . ';' : '';
    $boxstyle .= $a['txcolor'] ? '--mb2-pb-bxtxcolor:' . $a['txcolor'] . ';' : '';
    $boxstyle .= '"';

    if ($gl0bxheight) {
        $heightstyle .= ' style="';
        $heightstyle .= 'min-height:' . $gl0bxheight . 'px;';
        $heightstyle .= '"';
    }

    $btnstyle .= ' style="';
    $btnstyle .= $a['btncolor'] ? '--mb2-pb-btn-color:' . $a['btncolor'] . ';' : '';
    $btnstyle .= $a['btnbgcolor'] ? '--mb2-pb-btn-bgcolor:' . $a['btnbgcolor'] . ';' : '';
    $btnstyle .= $a['btnbghcolor'] ? '--mb2-pb-btn-bghcolor:' . $a['btnbghcolor'] . ';' : '';
    $btnstyle .= $a['btnhcolor'] ? '--mb2-pb-btn-hcolor:' . $a['btnhcolor'] . ';' : '';
    $btnstyle .= $a['btnborcolor'] ? '--mb2-pb-btn-borcolor:' . $a['btnborcolor'] . ';' : '';
    $btnstyle .= $a['btnborhcolor'] ? '--mb2-pb-btn-borhcolor:' . $a['btnborhcolor'] . ';' : '';
    $btnstyle .= '"';

    $fcls .= ' fw' . $gl0bxcontenttlefw;

    $output .= '<div class="mb2-pb-subelement mb2-pb-boxescontent_item theme-box"' .
    theme_mb2nl_page_builder_el_datatts($atts, $atts2) . '>';
    $output .= theme_mb2nl_page_builder_el_actions('subelement');
    $output .= '<div class="subelement-helper"></div>';
    $output .= '<div class="mb2-pb-subelement-inner">';

    $output .= '<div class="boxcontent position-relative' . $cls . '"' . $boxstyle . '>';
    $output .= '<div class="boxcontent-inner"' . $heightstyle . '>';
    $output .= '<div class="boxcontent-content"' . $boxscttyle . '>';
    $output .= '<h4 class="boxcontent-title' . $fcls . '" style="font-size:' . $gl0bxcontenttlefs . 'rem;">';
    $output .= $a['title'];
    $output .= '</h4>';
    $output .= '<div class="boxcontent-desc">' . urldecode($content) . '</div>';
    $output .= '</div>'; // ...boxcontent-content
    $output .= '<div class="boxcontent-readmore">';
    $output .= '<a href="#" class="arrowlink"' . $btnstyle . '>' . $a['btntext'] . '</a>';
    $output .= '<a href="#" class="mb2-pb-btn type' . $gl0bxcontentbtntype . ' size' . $gl0bxcontentbtnsize . ' rounded' .
    $gl0bxcontentbtnrounded . ' btnborder' . $gl0bxcontentbtnborder . ' fw' .
    $gl0bxcontentbtnfwcls . '"' . $btnstyle . '>' . $a['btntext'] . '</a>';
    $output .= '</div>'; // ...theme-boxicon-readmore
    $output .= '</div>'; // ...boxcontent-inner
    $output .= '<div class="bgcolor"></div>';
    $output .= '<div class="elcolor-el">';
    $output .= '<div class="elcolor1"></div>';
    $output .= '<div class="elcolor2"></div>';
    $output .= '</div>'; // ...elcolor-el
    $output .= '</div>'; // ...boxcontent
    $output .= '</div>'; // ...mb2-pb-subelement-inner
    $output .= '</div>'; // ...mb2-pb-boxescontent_item

    return $output;

}
