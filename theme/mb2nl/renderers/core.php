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
 *
 */


/**
 * Core renderer
 */
class theme_mb2nl_core_renderer extends \theme_boost\output\core_renderer {

    /** @var custom_menu_item language The language menu if created */
    protected $language = null;

    /**
     * Returns HTML to display a "Turn editing on/off" button in a form.
     *
     * @param moodle_url $url The URL + params to send through when clicking the button
     * @param string $method
     * @return string HTML the button
     */
    public function edit_button(moodle_url $url, string $method = 'post') {
        return null;
    }



    /**
     * Renders the "breadcrumb" for all pages.
     *
     * @return string the HTML for the navbar.
     */
    public function navbar(): string {
        return $this->render_from_template('core/navbar', $this->page->navbar);
    }




    /**
     * Renders the "secontady navigation menu" for all pages.
     *
     * @return string the HTML for the navbar.
     */
    public function secnav(): string {

        $snav = '';
        $onav = '';
        $secnav = '';
        $overflow = '';

        if (!$this->page->has_secondary_navigation()) {
            return ''; // Function must returns a string.
        }

        $tablistnav = $this->page->has_tablist_secondary_navigation();
        $moremenu = new \core\navigation\output\more_menu($this->page->secondarynav, 'nav-tabs', true, $tablistnav);
        $secnav = $moremenu->export_for_template($this);

        $overflowdata = $this->page->secondarynav->get_overflow_menu_data();
        if (!is_null($overflowdata)) {
            $overflow = $overflowdata->export_for_template($this);
            $onav = $this->render_from_template('core/url_select', $overflow);
        }

        if (!isset($secnav['moremenuid']) && is_null($overflowdata)) {
            return '';
        }

        $snav = $this->render_from_template('core/moremenu', $secnav);

        return $snav . $onav;

    }




    /**
     * The standard tags that should be included in the <head> tag
     * including a meta description for the front page
     *
     * @return string HTML fragment.
     */
    public function standard_head_html() {

        global $SITE, $CFG;

        $output = parent::standard_head_html();

        // Setup help icon overlays.
        // Prevent to show this code in Moodle 4.1+.
        $CFG->version < 2022112800 ? $this->page->requires->yui_module('moodle-core-popuphelp', 'M.core.init_popuphelp') : '';
        $this->page->requires->strings_for_js([
            'morehelp',
            'loadinghelp',
        ], 'moodle');

        if ($this->page->pagelayout === 'frontpage') {
            $summary = s(strip_tags(theme_mb2nl_format_txt($SITE->summary, FORMAT_HTML)));
            if (!empty($summary)) {
                $output .= "<meta name=\"description\" content=\"$summary\">\n";
            }
        }

        return $output;
    }






    /**
     *
     * Method to load theme element form 'layout/elements' folder
     *
     */
    public function theme_part($name, $vars=[]) {

        global $CFG;

        $element = $name . '.php';
        $candidate1 = $this->page->theme->dir . '/layout/parts/' . $element;

        // Require for child theme.
        if (file_exists($candidate1)) {
            $candidate = $candidate1;
        } else {
            $candidate = $CFG->dirroot . theme_mb2nl_themedir() . '/mb2nl/layout/parts/' . $element;
        }

        if (!is_readable($candidate)) {
            debugging("Could not include element $name.");
            return;
        }

        // The use of function extract() is forbidden 'extract($vars)'.
        ob_start();
        include($candidate);
        $output = ob_get_clean();
        return $output;

    }







    /**
     *
     * Method to get custom menu
     *
     */
    public function custom_menu($custommenuitems = '') {
        global $CFG, $DB;

        if (empty($custommenuitems) && ! empty($CFG->custommenuitems)) {
            $custommenuitems = $CFG->custommenuitems;
        }

        // Deal with company custom menu items.
        $iomadcompany = theme_mb2nl_get_iomad_company();

        if ($iomadcompany) {
            $custommenuitems = $iomadcompany->custommenuitems;
        }

        $custommenu = new custom_menu($custommenuitems, current_language());

        return $this->render_custom_menu($custommenu) . $this->page->headingmenu;

    }







    /**
     *
     * Method to render custom menu
     *
     */
    protected function render_custom_menu(custom_menu $menu) {
        global $CFG, $SITE;

        $content = '';
        $this->output = $this->page->get_renderer('core');

        // Set menu class related to the position.
        $pos = theme_mb2nl_theme_setting($this->page, 'headernav') ? 1 : 2;
        $cls = $pos == 2 ? 'navigation-bar' : 'navigation-header';

        $content .= '<div id="main-navigation" class="' . $cls . '">';
        $content .= $pos == 2 ?
        '<div class="main-navigation-inner"><div class="container-fluid"><div class="row"><div class="col-md-12">' : '';
        $content .= $this->output->theme_part('mobile_navtop');
        $content .= '<ul class="mb2mm">';

        $content .= theme_mb2nl_mycourses_list();
        $content .= theme_mb2nl_user_bookmarks();

        foreach ($menu->get_children() as $item) {
            $content .= $this->render_custom_menu_item($item, 1);
        }

        $content .= theme_mb2nl_language_list();

        $content .= '</ul>';
        $content .= $this->output->theme_part('mobile_navbottom');
        $content .= $pos == 2 ? '</div></div></div></div>' : ''; // ...main-navigation-inner
        $content .= '</div>'; // ...main-navigation

        return $content;

    }









    /**
     *
     * Method to render custom menu item
     *
     */
    protected function render_custom_menu_item(custom_menu_item $menunode, $level = 1) {

        $output = '';
        $licls = '';
        $acls = '';

        $url = $menunode->get_url();
        $haschild = $menunode->has_children();
        $label = $menunode->get_text();

        // Define classess.
        $licls .= 'level-' . $level;
        $licls .= $haschild ? ' isparent onhover' : '';

        if ($level > 1 && trim($label) === '###') {
            return '<li class="dropdown-divider m-0" style="border-width:2px;"></li>';
        }

        // Dropdown list style.
        $ddstyle = ' style="';
        $ddstyle .= '--mb2mm-mindent:' . ($level * 18) . 'px;';
        $ddstyle .= '"';

        // Set a specific class based on the mneu name.
        // It helps to hide/show items via custom CSS.
        $licls .= ' mitem_' . theme_mb2nl_string_url_safe($label, true);

        $acls .= 'mb2mm-action';

        $output .= '<li class="' . $licls . '">';

        $output .= $url ? '<a class="' . $acls . '" href="' .  $url . '">' : '<button type="button" class="'.$acls.' themereset">';
        $output .= '<span class="mb2mm-item-content">';
        $output .= '<span class="mb2mm-label">';
        $output .= $label;
        $output .= '</span>'; // ...mb2mm-label
        $output .= '</span>'; // ...mb2mm-item-content
        $output .= $haschild ? '<span class="mb2mm-arrow"></span>' : '';
        $output .= $url ? '</a>' : '</button>';

        // Display menu toggle button for click menu mode and for mobile menu.
        $output .= $haschild ? '<button type="button" class="mb2mm-toggle themereset" title="' .
        get_string('togglemenuitem', 'theme_mb2nl', ['menuitem' => $label]) . '" aria-expanded="false"></button>' : '';

        if ($haschild) {
            $output .= $level == 1 ? '<div class="mb2mm-ddarrow"></div>' : '';

            $level++;

            $output .= '<ul class="mb2mm-dd"' . $ddstyle . '>';

            foreach ($menunode->get_children() as $menunode) {
                $output .= $this->render_custom_menu_item($menunode, $level);
            }

            $level = $level - 1;

            $output .= '</ul>';
        }

        $output .= '</li>';

        return $output;

    }




    /**
     *
     * Method to render tabtree
     *
     */
    protected function render_tabtree(tabtree $tabtree) {

        $output = '';

        if (empty($tabtree->subtree)) {
            return '';
        }

        $firstrow = $secondrow = '';

        foreach ($tabtree->subtree as $tab) {
            $firstrow .= $this->render($tab);

            if (($tab->selected || $tab->activated) && !empty($tab->subtree) && $tab->subtree !== []) {
                $secondrow = $this->tabtree($tab->subtree);
            }
        }

        $output .= html_writer::start_tag('div', ['class' => 'theme-tabs moodle-tabs']);
        $output .= html_writer::tag('ul', $firstrow, ['class' => 'nav nav-tabs']);
        $output .= html_writer::end_tag('div');

        $output .= $secondrow;

        return $output;

    }





    /**
     *
     * Method to render tab tree object
     *
     */
    protected function render_tabobject(tabobject $tab) {

        if (($tab->selected && (!$tab->linkedwhenselected)) || $tab->activated) {
            return html_writer::tag('li', html_writer::tag('a', $tab->text, ['class' => 'nav-link active']),
            ['class' => 'nav-item']);
        } else if ($tab->inactive) {
            return html_writer::tag('li', html_writer::tag('a', $tab->text, ['class' => 'nav-link']), ['class' => 'nav-item']);
        } else {
            $isactive = $tab->inactive ? ' active' : '';

            if (!($tab->link instanceof moodle_url)) {
                // ...backward compartibility when link was passed as quoted string
                $link = '<a class="nav-link' .  $isactive .'" href="' . $tab->link . '" title="' . $tab->title . '">' .
                $tab->text . '</a>';
            } else {
                $link = html_writer::link($tab->link, $tab->text, ['class' => 'nav-link' . $isactive, 'title' => $tab->title]);
            }

            return html_writer::tag('li', $link, ['class' => 'nav-item']);
        }

    }


}
