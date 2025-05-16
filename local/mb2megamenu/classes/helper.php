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
 * Defines forms.
 *
 * @package    local_mb2megamenu
 * @copyright  2019 - 2020 Mariusz Boloz (mb2themes.com)
 * @license    Commercial https://themeforest.net/licenses
 */

defined('MOODLE_INTERNAL') || die();

require_once(__DIR__ . '/api.php');

if ( ! class_exists( 'Mb2megamenuHelper' ) )
{
    class Mb2megamenuHelper
    {

        /*
         *
         * Method to menu
         *
         */
        public static function menu_template( $id, $pos )
        {
            global $OUTPUT;

            $output = '';
            $menuid = 0;

            // Check if theme part method exists
            // This is important to avoid php error in several Moodle places
            if ( ! method_exists('theme_mb2nl_core_renderer', 'theme_part') )
            {
                return;
            }

            // Set menu class related to the position
            $cls = $pos == 2 ? 'navigation-bar' : 'navigation-header';

            // Get menu
            $firstmenu = self::get_firts_menu();    // Menu ID

            if ( $id && self::get_available_menus( $id, true ) )
            {
                $menuid = $id;
            }
            elseif ( $firstmenu )
            {
                $menuid = $firstmenu;
            } 

            // Check if menu exists
            if ( ! $menuid )
            {
                return;
            }

            $id = self::menu_attrid($menuid);
            
            // Get cache data
            $cache = cache::make('local_mb2megamenu', 'menudata');

            // If cache not exists, create it
            if ( ! $cache->get($menuid) )
            {
                $menu = self::get_available_menus( $menuid );         
                $cache->set( $menuid, array( 'menuitems' => $menu->menuitems, 'attribs' => $menu->attribs ) );
            }
           
            $items = json_decode($cache->get($menuid)['menuitems']);
            $attribs = json_decode($cache->get($menuid)['attribs']);
            $GLOBALS['mb2mm__itemcounter'] = 0;

            // Get style from theme settings
            $themestyle = function_exists( 'theme_mb2nl_main_menu_style' ) ? theme_mb2nl_main_menu_style() : '';

            $output .= '<div id="main-navigation" class="' . $cls . '" style="' . self::menu_style($attribs) . $themestyle . '">';

            $output .= $pos == 2 ? '<div class="main-navigation-inner"><div class="container-fluid"><div class="row"><div class="col-md-12">' : '';

            $output .= $OUTPUT->theme_part('mobile_navtop');

            $output .= '<!-- Mb2 Mega Menu --><ul id="' . $id . '" class="mb2mm' . self::menu_cls($attribs) . '">';  
            
            if ( function_exists( 'theme_mb2nl_mycourses_list' ) )
            {
                $output .= theme_mb2nl_mycourses_list();
            }
            
            if ( function_exists( 'theme_mb2nl_user_bookmarks' ) )
            {
                $output .= theme_mb2nl_user_bookmarks();
            }
            
            foreach( $items as $item )
            {               
                $output .= self::menu_item_template($item, 0, $attribs);
            }
            
            if ( function_exists( 'theme_mb2nl_language_list' ) )
            {
                $output .= theme_mb2nl_language_list();
            }
                       
            $output .= '</ul><!-- // Mb2 Mega Menu -->';

            $output .= $OUTPUT->theme_part('mobile_navbottom');

            $output .= $pos == 2 ? '</div></div></div></div>' : ''; // main-navigation-inner

            $output .= '</div>'; // main-navigation
            
            return $output;            

        }
        
        
        
        
        
        
        /*
         *
         * Method to menu
         *
         */
        public static function menu_item_template( $item, $levelid = 0, $attribs = null )
        {            

            $output = '';
            $colstyle = ' style="';
            $datatts = '';
            $wrapdatatts = '';
            $opt = get_config('local_mb2megamenu');
            
            $ischild = count( $item->attr );
            
            // Check for the mega menu item
            $mega = $item->settings->level == 1 && $ischild && self::get_setting($item, 'mega');
            
            // Click/hover class
            $hccls = $attribs->openon == 2 ? ' onclick' : ' onhover';

            // External icon
            $exticon = $opt->exticon && $item->settings->blank ? '<span class="mb2mm-exticon"><i class="ri-external-link-line"></i></span>' : '';
            
            // CSS classes            
            $cls = 'level-' . $item->settings->level;
            $cls .= $ischild ? ' isparent' . $hccls : '';
            $cls .= $mega ? ' ismega' : '';     
            $cls .= self::get_setting($item, 'cssclass') ? ' ' . self::get_setting($item, 'cssclass') : '';
            
            // Css classess for mega wrap   
            $wrapw = self::get_setting($item, 'width');
            $wrapcls = ' width-' . $wrapw;
            $wrapcls .= $wrapw !== 'aw' ? ' width-max' : '';
            $wrapcls .= self::get_setting($item, 'distcols') ? ' cols-' . self::get_setting($item, 'columns') : ' cols-dist';
            $wrapcls .= ' colscw-' . self::get_setting($item, 'columnscw');            

            // Additional class for menu item to make is 100% height
            $cls .= $mega && $wrapw !== 'aw' ? ' height100' : '';
            
            // Style for mega parent element
            $colstyle .= $mega && self::get_setting($item, 'bgcolor') ? '--mb2mm-mddbgcolor:' . self::get_setting($item, 'bgcolor') . ';' : '';          

            // Dropdown list style
            $ddstyle = ' style="';
            $ddstyle .= '--mb2mm-mindent:' . ($item->settings->level * 18) . 'px;';
            $ddstyle .= '"';
            
            // Wrap background image
            if ( self::get_setting($item, 'image') )
            {
                $wrapcls .= ' lazy';
                $wrapdatatts .= ' data-bg="' . self::get_setting($item, 'image') . '"';
            }

            // Wrap width data attribute for js
            // It's require for auto width mega container
            $wrapdatatts .= ' data-aw="' . self::get_setting($item, 'columns') * $attribs->mcminwidth . '"';
            
            // Set item url and target attribute
            $url = $item->settings->url;
            $target = $item->settings->blank ? ' target="_blank"' : '';

            // Item label
            $itemlabel = self::get_item_lang_label($item, 'label');//$item->settings->label;
            $cls .= self::get_setting($item, 'hidetext') ? ' mb2mm-empty' : '';
           
            // Set menu item calss
            $itemcls = 'mb2mm-action';

            // Reset mega columns counter 
            if ( $item->settings->level == 1 )
            {
                $GLOBALS['mb2mm__itemcounter'] = 0;
            }
            
            if ( $levelid == 1 )
            {
                // Set a specific class for additional column, 
                // for example for the foutrh column in the three-column layout.
                // We want to set width of this column 100%
                $GLOBALS['mb2mm__itemcounter']++;
               
                if ( isset( $attribs->columns ) && $attribs->columns > 1 )
                {
                    $cls .= $GLOBALS['mb2mm__itemcounter'] > $attribs->columns ? ' mb2mm-extracol' : '';
                    $cls .= $GLOBALS['mb2mm__itemcounter'] == 1 ? ' mb2mm-firstcol' : '';
                    $cls .= $GLOBALS['mb2mm__itemcounter'] == $attribs->columns ? ' mb2mm-lastcol' : '';
                    $cls .= isset( $attribs->childnum ) && $attribs->childnum > $attribs->columns ? ' mb2mm-isextracol' : '';
                }

                // Set different clickable element class
                $itemcls = 'mb2mm-heading';

                // Column width and columns                
                $colstyle .= self::get_setting($item, 'cwidth') ? '--mb2mm-mcflexwidth:' . self::get_setting($item, 'cwidth') . 'px;' : '';
                $cw = self::get_setting($item, 'cwidth') ? self::get_setting($item, 'cwidth') : $attribs->mcminwidth;
                $datatts .= ' data-cw="' . $cw . '"';
                $cls .= self::get_setting($item, 'cwidth') ? ' iscw' : '';
                
                // Mega column style 'li' element                
                $colstyle .= self::get_setting($item, 'bgcolor') ? '--mb2mm-mcbgcolor:' . self::get_setting($item, 'bgcolor') . ';' : '';
                $colstyle .= self::get_setting($item, 'bghcolor') ? '--mb2mm-mcbghcolor:' . self::get_setting($item, 'bghcolor') . ';' : '';
                
                // Column heading - normal state
                $colstyle .= self::get_setting($item, 'hcolor') ? '--mb2mm-hcolor:' . self::get_setting($item, 'hcolor') . ';' : '';
                $colstyle .= self::get_setting($item, 'hsubcolor') ? '--mb2mm-hsubcolor:' . self::get_setting($item, 'hsubcolor') . ';' : '';
                $colstyle .= self::get_setting($item, 'hsub2color') ? '--mb2mm-hsub2color:' . self::get_setting($item, 'hsub2color') . ';' : '';
                $colstyle .= self::get_setting($item, 'hiconcolor') ? '--mb2mm-hiconcolor:' . self::get_setting($item, 'hiconcolor') . ';' : '';
                $colstyle .= self::get_setting($item, 'hiconbgcolor') ? '--mb2mm-hiconbgcolor:' . self::get_setting($item, 'hiconbgcolor') . ';' : ''; 
                
                // Column heading - hover state                
                $colstyle .= self::get_setting($item, 'hhcolor') ? '--mb2mm-hhcolor:' . self::get_setting($item, 'hhcolor') . ';' : '--mb2mm-hhcolor:var(--mb2mm-hcolor);'; 
                $colstyle .= self::get_setting($item, 'hsubhcolor') ? '--mb2mm-hsubhcolor:' . self::get_setting($item, 'hsubhcolor') . ';' : '--mb2mm-hsubhcolor:var(--mb2mm-hsubcolor);'; 
                $colstyle .= self::get_setting($item, 'hsub2hcolor') ? '--mb2mm-hsub2hcolor:' . self::get_setting($item, 'hsub2hcolor') . ';' : '--mb2mm-hsub2hcolor:var(--mb2mm-hsub2color);';
                $colstyle .= self::get_setting($item, 'hiconhcolor') ? '--mb2mm-hiconhcolor:' . self::get_setting($item, 'hiconhcolor') . ';' : '--mb2mm-hiconhcolor:var(--mb2mm-hiconcolor);'; 
                $colstyle .= self::get_setting($item, 'hiconhbgcolor') ? '--mb2mm-hiconhbgcolor:' . self::get_setting($item, 'hiconhbgcolor') . ';' : '--mb2mm-hiconhbgcolor:var(--mb2mm-hiconbgcolor);';
                
                // Column bg color class                
                if ( self::get_setting($item, 'image') || self::get_setting($item, 'bgcolor') || self::get_setting($item, 'bghcolor') )
                {
                    $cls .= ' mb2mm-bgcol';
                }

                // Column backgroud image
                if ( self::get_setting($item, 'image') )
                {
                    $cls .= ' lazy';
                    $datatts .= ' data-bg="' . self::get_setting($item, 'image') . '"';
                }                

                // Set icon calss for icon background
                if ( self::get_setting($item, 'hiconbgcolor') || self::get_setting($item, 'hiconbghcolor') )
                {
                    $cls .= ' mb2mm-iconbg';
                }
                
            }
            elseif ( $levelid == 2 )
            {
                $itemcls = 'mb2mm-mega-action';
            }
            
            // Style for highlight label
            $colstyle .= self::get_setting($item, 'hlabelcolor') ? '--mb2mm-hlabelcolor:' . self::get_setting($item, 'hlabelcolor') . ';' : '';
            $colstyle .= self::get_setting($item, 'hlabelbgcolor') ? '--mb2mm-hlabelbgcolor:' . self::get_setting($item, 'hlabelbgcolor') . ';' : '';
            
            $colstyle .= '"'; // End column style

            // Sublabel2
            $sublabel2 = self::get_setting($item, 'sublabel') && self::get_setting($item, 'sublabel2') && $levelid == 1;
            $itemcls .= $sublabel2 ? ' issublabel2' : ''; 
                    
            // Icon
            $icon = self::get_setting($item, 'icon');
            $isicon = $icon ? '<span class="mb2mm-icon"><i class="' . $icon . '"></i></span>' : '';
            
            $output .= '<li class="' . $cls . '"' . self::set_inline_style($colstyle) . $datatts . '>';
            
            $output .= $url ? '<a class="' . $itemcls . '" href="' .  $url . '"' . $target . '>' : '<button type="button" class="' . $itemcls . ' themereset">';
            $output .= $isicon;  
            $output .= '<span class="mb2mm-item-content">';
            $output .= '<span class="mb2mm-label">';
            $output .= $itemlabel;        
            $output .= $exticon;
            $output .= '</span>'; // mb2mm-label
            $output .= self::get_setting($item, 'sublabel') ? '<span class="mb2mm-sublabel">' . self::get_item_lang_label($item, 'sublabel') . '</span>' : '';
            
            $output .= $sublabel2 ? '<span class="mb2mm-sublabel-more" aria-hidden="true">' . self::get_item_lang_label($item, 'sublabel2') . $exticon . '</span>' : '';           
            $output .= '</span>'; // mb2mm-item-content

            // Highlight label
            $output .= self::get_setting($item, 'hlabel') ? '<span class="mb2mm-hlabel" aria-hidden="true">' . 
            self::get_item_lang_label($item, 'hlabel') . '</span>' : '';

            // Display parent menu arrow for hover menu mode
            // We don't need the arrow on the mega column heading ($levelid !== 1)
            $output .= $ischild && $levelid !== 1 ? '<span class="mb2mm-arrow"></span>' : '';
            
            $output .= $url ? '</a>' : '</button>';

            // Display menu toggle button for click menu mode and for mobile menu
            $output .= $ischild ? '<button type="button" class="mb2mm-toggle themereset" aria-label="' . get_string('togglemenu', 'local_mb2megamenu', $itemlabel) . '" aria-expanded="false"></button>' : '';
            
            if ( count( $item->attr ) )
            {               

                $output .= $item->settings->level == 1 ? '<div class="mb2mm-ddarrow"></div>' : '';
                
                if ( $mega )
                {
                    $output .= '<div class="mb2mm-dd mb2mm-wrap' . $wrapcls . '"' . $wrapdatatts . '>';
                    $output .= '<div class="mb2mm-content">';
                    $output .= '<div class="mb2mm-row">';
                }                 
                
                // Dropdown list class
                $ddcls = 'mb2mm-dd'; 
               
                if ( $mega )
                {
                    $ddcls = 'mb2mm-list';
                }
                elseif ( $levelid == 1 )
                {
                    $ddcls = 'mb2mm-mega-dd';
                    $levelid = 2;                    
                }
                else 
                {
                    $levelid = 0;
                }              

                $output .= '<ul class="' . $ddcls . '"' . self::set_inline_style($ddstyle) . '>';
                
                if ( $mega )
                {
                    $levelid = 1;
                    $attribs->columns = self::get_setting($item, 'columns');
                    $attribs->childnum = self::get_setting($item, 'childnum');
                }               

                foreach( $item->attr as $subitem )
                {                    
                    $output .= self::menu_item_template( $subitem, $levelid, $attribs );
                }
               
                $output .= '</ul>'; 
                
                if ( $mega )
                {
                    $output .= '</div>'; // mb2mm-row
                    $output .= '</div>'; // mb2mm-content
                    $output .= '</div>'; // mb2mm-wrap
                }
            }
            
            $output .= '</li>';
            
            return $output;
            
            
        }     




        /*
         *
         * Method to get menu item label
         *
         */
        public static function get_item_lang_label($item, $name = 'label')
        { 

            $language = current_language();
            $langname = $name . '__' . $language;

            if ( isset( $item->settings->$langname ) && $item->settings->$langname !=='' )
            {
                return $item->settings->$langname;
            }
            else 
            {
                return self::get_setting($item, $name);
            }

        }     
        
       
        
        
        
        /*
         *
         * Method to get menu item setting
         *
         */
        public static function get_setting($item, $name)
        {            
             
            if ( isset( $item->settings->$name ) )
            {
                 return $item->settings->$name;
            }
             
            return null;
             
        }      
        






        /*
         *
         * Method to check if there are inline style attribute
         *
         */
        public static function set_inline_style($style)
        {

            $style2check = str_replace('style=""', '', $style);
            $style2check = trim($style2check);

            if ( $style2check !== '' )
            {
                return $style;
            }

            return null;

        }
        
        
        
        
        
        /*
         *
         * Method to get the first menu from menu list
         *
         */
        public static function get_firts_menu()
        {           
            $menus = self::get_available_menus();
            
            if ( ! $menus )
            {
                return false;
            }
            
            foreach ( $menus as $menu )
            {                
                return $menu->id;
                break;                        
            }
            
            return false;            
            
        }
        
        
        
        
        /*
         *
         * Method to get available menus (enable = 1)
         *
         */
        public static function get_available_menus($id = 0, $check = false)
        {
            global $DB;
            
            $sqlwhere = ' WHERE 1=1';
            
            $recordsql = 'SELECT * FROM {local_mb2megamenu_menus}';  
            
            $sqlwhere .= $id ? '  AND id=' . $id : '';
            $sqlwhere .= '  AND enable=1';
            
            $sqlorder = ' ORDER BY sortorder';

            if ( $DB->record_exists_sql( $recordsql . $sqlwhere . $sqlorder ) )
            {   
                // We need only check if menu exists
                if ( $check )
                {
                    return true;
                }

                if ( $id )
                {
                    // Get the requiested record
                    return $DB->get_record_sql( $recordsql . $sqlwhere . $sqlorder );
                }
                else
                {
                    // Get all available records
                    return $DB->get_records_sql( $recordsql . $sqlwhere . $sqlorder );
                }                
            }
            
            return false;
            
        }





        /*
         *
         * Method to get available menus (enable = 1)
         *
         */
        public static function get_menus_for_select()
        {

            $menus = array(0 => get_string('selectmenu', 'local_mb2megamenu'));
            $avmenus = self::get_available_menus();

            if ( ! $avmenus )
            {
                return $menus;
            }

            foreach ( $avmenus as $menu )
            {
                $menus[$menu->id] = $menu->name;
            }

            return $menus;

        }
        




        /*
         *
         * Method to get user
         *
         */
        public static function get_user($id)
        {
            global $DB;

            if ( ! $id )
            {
                return;
            }

            return $DB->get_record( 'user', array( 'id'=> $id ) );
        }





        /**
         *
         * Method to get item datetime.
         *
         */
        public static function get_datetime($time)
        {

        	if (!$time)
        	{
        		return;
        	}

        	$time_bool = date('I',$time);

        	// Check if is daylight savings time
        	// If yes add one hour to the base time
        	if ($time_bool)
        	{
        		$time = $time+60*60;
        	}

        	return $time;

        }


        
        
        
        
        /*
         *
         * Method to menu
         *
         */
        public static function css_vars_attribs()
        {            
            return array(
                
                // Drpdown items
                'ddbgcolor' => array(),
                'ddbordercolor' => array(),
                'ddcolor' => array(),
                'ddsubcolor' => array(),
                'ddiconcolor' => array(),
                
                // Drpdown hover state
                'ddhbgcolor' => array(),  
                'ddhcolor' => array(),
                'ddsubhcolor' => array(),
                'ddiconhcolor' => array(),
                
                // Mega column headings
                'hfs' => array('rem'),
                'hifs' => array('rem'),
                'hfw' => array(),

                // Mega columns
                'mcminwidth' => array('px'),
                'mcminheight' => array('px'),

                // Mega menu items - normal state
                'mcitemcolor' => array(),
                'mcitemsubcolor' => array(),
                'mcitemiconcolor' => array(),
                'mcitembordercolor' => array(),

                // Mega menu items - hover state
                'mcitemhcolor' => array(),
                'mcitemsubhcolor' => array(),
                'mcitemiconhcolor' => array(),
                'mcitemhbgcolor' => array(),

                // Mobile menu - normal state
                'm1lcolor' => array(),
                'm1subcolor' => array(),
                'm1iconcolor' => array(),
                'm1lbgcolor' => array(),
                'm1lbordercolor' => array(),

                // Mobile menu - open state
                'm1locolor' => array(),
                'm1lbgocolor' => array(),
                'm1subocolor' => array(),
                'm1iconocolor' => array(),

                // Static variables
                'mddbgcolor' => array('', 'var(--mb2mm-ddbgcolor)'),
                //'--mb2mm-mddbgcolor: var(--mb2mm-ddbgcolor);'

            );             
        }


        
        
        /*
         *
         * Method to menu
         *
         */
        public static function menu_style($attribs)
        {
                
            $style = '';
            
            //$attribs = json_decode($menu->attribs); 
            $attrvars = self::css_vars_attribs();
            
            foreach( $attrvars as $val=>$atts )
            {        
                $suff = isset( $atts[0] ) && $atts[0] ? $atts[0] : '';  
                
                // Set value for static parameters
                $attribs->$val = isset( $atts[1] ) && $atts[1] ? $atts[1] : $attribs->$val;
               
                // Skip empty variables
                // Default values of the variables are defined in css file
                if ( ! isset( $attribs->$val ) || $attribs->$val === '' )
                {
                    continue;
                }
                
                // Prevent to use a comma separator
                // If user use it, change it to a dot
                if ( $val === 'hfs' || $val === 'hifs' )
                {
                    $attribs->$val = str_replace(',', '.', $attribs->$val);
                }        

                
                $style .= '--mb2mm-' . $val . ':' . $attribs->$val . $suff . ';';
                
            }                
            
            return $style;
            
        }



        /*
         *
         * Method to set menu css classess
         *
         */
        public static function menu_cls($attribs)
        {
            
            $cls = '';
            //$attribs = json_decode($menu->attribs);            

            $cls .= ' ddrounded' . $attribs->rounded;           // Dropdown rounded
            $cls .= ' chupper' . $attribs->htu;                 // Colun heading uppercase
            $cls .= ' mb2mm-mcis_' . $attribs->mcitemstyle;     // Mega menu items (third-level) style     
            $cls .= isset($attribs->mcpadding) ? ' cpadding_' . $attribs->mcpadding : '';         // Mega column padding   

            return $cls;

        }
        
        
        
        
        
        /*
         *
         * Method to menu
         *
         */
        public static function menu_attrid( $menuid )
        {
            
            return 'mb2mm_' . $menuid;                                
            
        }



        /*
         *
         * Method to menu
         *
         */
        public static function active_menu()
        {
            $opt = get_config('local_mb2megamenu');
            $firstmenu = self::get_firts_menu();    // Menu ID

            if ( ! $opt->enablemenu )
            {
                return null;
            }

            if ( $firstmenu )
            {
                return $firstmenu;
            }

            return null;

        }   
        


    }

}