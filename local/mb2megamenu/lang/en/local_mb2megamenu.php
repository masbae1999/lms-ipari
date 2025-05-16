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
 * @package    local_mb2megamenu
 * @copyright  2019 - 2020 Mariusz Boloz (mb2themes.com)
 * @license    Commercial https://themeforest.net/licenses
 */

defined('MOODLE_INTERNAL') || die();

$string['pluginname'] = 'Mb2 Mega Menu';

// Roles
$string['mb2megamenu:manageitems'] = 'Manage menu items';

// Caches 
$string['cachedef_menudata'] = 'Mb2 Mega Menu data cache';

// Admin menu
$string['options'] = 'Global options';
$string['managemenus'] = 'Manage menus';
$string['images'] = 'Images';

// Admin settings
$string['enablemenu'] = 'Enable menu';

// Manage menu items
$string['addmenu'] = 'Add menu';
$string['menucreated'] = 'Menu created';
$string['menudeleted'] = 'Menu deleted';
$string['deletemenu'] = 'Delete menu';
$string['confirmdeletemenu'] = 'Do you really want to delete menu: {$a}?';

// Items table
$string['createdby'] = 'Created by';
$string['modifiedby'] = 'Modified by';
$string['strftimedatemonthabbr'] = '%d %b %Y';
$string['activemenu'] = 'Active menu';
$string['copied'] = 'Copied: {$a}';
$string['importmenu'] = 'Import demo menu';
$string['import'] = 'Import';
$string['importing'] = 'Import in progress';
$string['importdone'] = 'Import completed';

// Edit form
$string['enable'] = 'Enable menu';
$string['exticon'] = 'External link icon';
$string['name'] = 'Name';
$string['nameplch'] = 'Menu: {$a}';
$string['editmenuitem'] = 'Edit menu';
$string['linktarget'] = 'Link target';
$string['navlabel'] = 'Navigation label';
$string['menuitem'] = 'Menu item';
$string['nolabel'] = '(no label)';
$string['linktarget'] = 'Open link in a new window/tab';
$string['menuitemopts'] = 'Options';
$string['selecticon'] = 'Select icon';
$string['bgcolor'] = 'Background color';
$string['color'] = 'Color';
$string['subcolor'] = 'Subtext color';
$string['iconcolor'] = 'Icon color';
$string['bordercolor'] = 'Border color';
$string['rounded'] = 'Dropdown rounded';
$string['openon'] = 'Open submenu on:';
$string['openonhover'] = 'Hover';
$string['openonclick'] = 'Click';
$string['settingsstyledd'] = 'Style - dropdown';
$string['settingsstyleheading'] = ' Style - mega menu column (second-level item)';
$string['settingsstylmobile'] = 'Style - mobile menu (first-level items)';
$string['mcminheight'] = 'Column minimum height (px)';
$string['mcminwidth'] = 'Column minimum width (px)';
$string['menuitems'] = 'Menu items';
$string['hsubcolor'] = 'Subtext color'; 
$string['hiconcolor'] = 'Icon color';
$string['hiconbgcolor'] = 'Icon background color';
$string['hfs'] = 'Heading font size (rem)';
$string['hifs'] = 'Heading icon font size (rem)';
$string['hfw'] = 'Heading font weight';
$string['htu'] = 'Heading uppercase';
$string['mcatv'] = 'Center content vertically';
$string['hfwlight'] = 'Light';
$string['hfwnormal'] = 'Normal';
$string['hfwmedium'] = 'Medium';
$string['hfwbold'] = 'Bold';
$string['mcitems'] = 'Child menu items (third-level items)';
$string['mcitemstyle'] = 'Menu items style';
$string['mcitemstylemin'] = 'Minimal';
$string['mcitemstylebg'] = 'Background color on hover';
$string['mcitemstyleborder'] = 'Border';
$string['normalstate'] = 'Normal state';
$string['hoverstate'] = 'Hover state';
$string['openstate'] = 'Open state (only for items with child items)';
$string['levelup'] = 'Move up one level';
$string['leveldown'] = 'Move down one level';
$string['languageopts'] = 'Language options';
$string['colpadding'] = 'Column padding';
$string['colpaddingsm'] = 'Small';
$string['colpaddingbig'] = 'Big';

// Modal form
$string['hidetext'] = 'Hide menu item text';
$string['hidetextdesc'] = 'Check this option to hide menu item text on desktop. On mobile, menu item text will be always visible.';
$string['megamenu'] = 'Mega menu';
$string['megamenudesc'] = 'Check the mega menu option to enable mega menu. This option will only work for the <strong>first-level</strong> menu items.';
$string['columns'] = 'Mega menu columns';
$string['cwidth'] = 'Column custom width';
$string['distcols'] = 'Distribute columns evenly';
$string['distcolsdesc'] = 'Check this option to distribute columns evenly to fit width of mega menu container.';
$string['cwidthdesc'] = 'Set column custom width in pixels. The maximum width of column is equal to: 100% / numebr of columns.';
$string['columnsdesc'] = 'Select the number of columns you want to use in mega menu.';
$string['searchiconfor'] = 'Search icons for...';
$string['searchimagefor'] = 'Search image for...';
$string['bgimage'] = 'Background image';
$string['bgimagedesc'] = 'In case of the <strong>first-level</strong> menu item, the background image will be set for a mega menu dropdown element. In case of the <strong>second-level</strong> menu item, the background image will be set for a mega menu column.';
$string['bgcolor'] = 'Background color';
$string['bgcolordesc'] = 'In case of the <strong>first-level</strong> menu item, the background color will be set for a mega menu dropdown element. In case of the <strong>second-level</strong> menu item, the background color will be set for a mega menu column. The <strong>hover state color</strong> option works only for a mega menu column.';
$string['hlabel'] = 'Highlight label text';
$string['hlabeldesc'] = 'Enter the navigation item highlight text, for example: NEW or HOT or TOP.';
$string['textcolor'] = 'Text color';
$string['bgcolor'] = 'Background color';
$string['megawidth'] = 'Mega menu dropdown element width';
$string['maxwidth'] = 'Max width';
$string['contentwidth'] = 'Content width';
$string['auto'] = 'Auto';
$string['megawidthdesc'] = '<strong>Max width</strong> is a width of the current window width. <strong>Content width</strong> is a width defined in the theme layout options. <strong>Auto</strong> width is a width based on the mega menu columns width.';
$string['cssclass'] = 'CSS class';
$string['cssclassdesc'] = 'Add a custom css class and then refer to it in your css style.';
$string['sublabel'] = 'Subtext';
$string['sublabeldesc'] = 'Enter the navigation item subtext. It will be displayed beneath the navigation item label text.';
$string['sublabel2'] = 'Read more text';
$string['sublabel2desc'] = 'Enter the read more text. It will be displayed beneath the navigation item subtext element.';
$string['none'] = 'None';

// Menu builder
$string['additem'] = 'Add menu item';
$string['selectmenu'] = 'Select menu';

// Frontend
$string['togglemenu'] = 'Open submenu of {$a}';