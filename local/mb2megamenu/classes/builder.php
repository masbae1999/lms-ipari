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
require_once(__DIR__ . '/settings.php');

if ( ! class_exists( 'Mb2megamenuBuilder' ) )
{

    class Mb2megamenuBuilder
    {

        public static function get_menu_builder()
        {
            global $USER;

            $output = '';
            $itemid = optional_param('itemid', 0, PARAM_INT);
            $record = $itemid ? Mb2megamenuApi::get_record($itemid) : new stdClass();
            $data = isset( $record->menuitems ) ? json_decode( $record->menuitems ) : '';

            $output .= self::language_el();
            $output .= '<div class="mb2megamenu-builder-wrap" data-sesskey="' . $USER->sesskey . '">';
            $output .= '<div class="mb2megamenu-builder-area">';
            $output .= '<div class="mb2megamenu-sortable mb2megamenu-main-sortable">';

            if ( $data )
            {
                $output .= self::get_menu_item_html($data);
            }

            $output .= '</div>'; // mb2megamenu-sortable
            $output .= '</div>'; // mb2megamenu-builder-area
            $output .= '<div class="mb2megamenu-builder-footer">';
            $output .= '<button id="mb2megamenu-add-menu-item" type="button" class="mb2-pb-btn typesuccess sizexs">' .
            get_string('additem', 'local_mb2megamenu') . '</button>';
            $output .= '</div>'; // mb2megamenu-builder-footer
            $output .= '</div>'; // mb2megamenu-builder-wrap

            return $output;

        }





        public static function get_menu_item_html($items)
        {
            $output = '';

            foreach ( $items as $item )
            {
                $datatts = self::get_item_settings($item->settings);

                $islabel = $item->settings->label ? $item->settings->label : get_string('nolabel', 'local_mb2megamenu');

                $output .= '<div class="mb2megamenu-item"' . $datatts . '>';
                $output .= '<div class="mb2megamenu-item-header">';
                $output .= '<div class="mb2megamenu-drag-handle mb2megamenu-item-label">';
                $output .= '<span class="item-label">' . $islabel . '</span>';
                $output .= '<div class="item-actions"></div>';
                $output .= '<button type="button" class="themereset mb2megamenu-item-form-toggle"><span class="sr-only">' . get_string('edit') . '</span></button>';
                $output .= '</div>'; // mb2megamenu-drag-handle mb2megamenu-item-label
                $output .= '</div>'; // mb2megamenu-item-header
                $output .= '<div class="mb2megamenu-item-content">';
                $output .= '<div class="mb2megamenu-item-subform">';

                $output .= '<div class="mb2megamenu-subform-item">';
                $output .= '<div class="form-label">';
                $output .= '<label>' . get_string('url') . '</label>';
                $output .= '</div>'; // form-label
                $output .= '<div class="form-controls">';
                $output .= '<input type="text" class="form-control code" name="url" value="' . $item->settings->url . '" placeholder="https://">';
                $output .= '</div>'; // form-controls
                $output .= '</div>'; // mb2megamenu-subform-item

                $output .= '<div class="mb2megamenu-subform-item">';
                $output .= '<div class="form-controls">';
                $ischecked = $item->settings->blank ? ' checked' : '';
                $output .= '<input type="checkbox" name="blank" value="' . $item->settings->blank . '"' . $ischecked . '>';
                $output .= ' <label>' . get_string('linktarget', 'local_mb2megamenu') . '</label>';
                $output .= '</div>'; // form-controls
                $output .= '</div>'; // mb2megamenu-subform-item

                $output .= '<div class="mb2megamenu-subform-item">';
                $output .= '<div class="form-label">';
                $output .= '<label>' . get_string('navlabel', 'local_mb2megamenu') . '</label>';
                $output .= '</div>'; // form-label
                $output .= '<div class="form-controls">';
                $output .= '<input type="text" name="label" class="form-control" value="' . $item->settings->label . '">';
                $output .= '</div>'; // form-controls
                $output .= '</div>'; // mb2megamenu-subform-item
                
//                $output .= '<div class="mb2megamenu-subform-item">';
//                $output .= '<div class="form-label">';
//                $output .= '<label>' . get_string('navlabel', 'local_mb2megamenu') . '</label>';
//                $output .= '</div>'; // form-label
//                $output .= '<div class="form-controls">';
//                $output .= '<textarea name="desc" class="form-control">' . $item->settings->desc . '</textarea>';
//                $output .= '</div>'; // form-controls
//                $output .= '</div>'; // mb2megamenu-subform-item

                $output .= '<div class="mb2megamenu-subform-item">';
                $output .= '<div class="form-controls">';
                $output .= '<button type="button" class="mb2megamenu-item-options mb2-pb-btn typeinfo sizexs" data-modal="#mb2megamenu-modal-settings">' .
                get_string('menuitemopts', 'local_mb2megamenu') . '</button>';

                if ( self::language_labels() )
                {
                    $output .= ' <button type="button" class="mb2megamenu-item-lang_options mb2-pb-btn sizexs" data-modal="#mb2megamenu-modal-languages">' .
                get_string('languageopts', 'local_mb2megamenu') . '</button>';
                }
                
                $output .= '</div>'; // form-controls
                $output .= '</div>'; // mb2megamenu-subform-item

                $output .= '<div class="mb2megamenu-subform-footer">';
                $output .= '<button type="button" class="themereset mb2megamenu-item-footer-btn btn-remove">' . get_string('remove') . '</button> | ';
                $output .= '<button type="button" class="themereset mb2megamenu-item-footer-btn btn-cancel">' . get_string('cancel') . '</button>';
                $output .= '</div>'; // mb2megamenu-subform-footer

                $output .= '</div>'; // mb2megamenu-item-subform
                $output .= '<div class="mb2megamenu-sortable mb2megamenu-item-sortable">';

                if ( isset( $item->attr ) )
                {
                    $output .= self::get_menu_item_html($item->attr);
                }

                $output .= '';
                $output .= '</div>'; // mb2megamenu-sortable mb2megamenu-item-sortable
                $output .= '</div>'; // mb2megamenu-item-content
                $output .= '</div>'; // mb2megamenu-item
            }

            return $output;

        }



        public static function get_item_settings($settings)
        {
            $output = '';

            foreach( $settings as $k=>$v )
            {
                $output .= ' data-' . $k . '="' . $v . '"';
            }

            return $output;
        }



        public static function language_el()
        {

            $output = '';
            $strings = self::language_strings();

            $output .= '<div id="mb2megamenu-lang"';

            foreach( $strings as $id => $string )
            {
                $output .= ' data-' . $id . '="' . $string . '"';
            }

            $output .= '></div>';

            return $output;

        }




        public static function language_labels()
        {
            global $CFG, $OUTPUT;

            $output = '';
            $langarr = get_string_manager()->get_list_of_translations();
            
            if ( count( $langarr ) <= 1 )
            {
                return;
            }

            foreach( $langarr as $k=>$v)
            {
                
                if ( $CFG->lang === $k )
                {
                    continue;
                }
                
                $flag = $OUTPUT->image_url('flags/48x32/' . strtoupper($k), 'theme_mb2nl');
                $img = '<img src="' . $flag . '" alt="' . $v . '">';
                    
                $output .= '<div class="mb2megamenu-modalform-item">';

                $output .= '<div class="form-label language-label">';
                $output .= '<label>' . $img . $v . '</label>';
                $output .= '</div>'; //form-label

                $output .= '<div class="form-controls">';

                $output .= '<div class="mb2megamenu-langform-item">';                
                $output .= '<label>' . get_string('navlabel', 'local_mb2megamenu') . '</label>';
                $output .= '<input type="text" name="label__' . $k . '" class="form-control" value="">';
                $output .= '</div>'; // mb2megamenu-langform-item

                $output .= '<div class="mb2megamenu-langform-item">';                
                $output .= '<label>' . get_string('sublabel', 'local_mb2megamenu') . '</label>';
                $output .= '<textarea name="sublabel__' . $k . '" class="form-control"></textarea>';
                $output .= '</div>'; // mb2megamenu-langform-item

                $output .= '<div class="mb2megamenu-langform-item">';                
                $output .= '<label>' . get_string('hlabel', 'local_mb2megamenu') . '</label>';
                $output .= '<input type="text" name="hlabel__' . $k . '" class="form-control" value="">';
                $output .= '</div>'; // mb2megamenu-langform-item
                
                $output .= '<div class="mb2megamenu-langform-item">';                
                $output .= '<label>' . get_string('sublabel2', 'local_mb2megamenu') . '</label>';
                $output .= '<input type="text" name="sublabel2__' . $k . '" class="form-control" value="">';
                $output .= '</div>'; // mb2megamenu-langform-item                

                $output .= '</div>'; // form-controls
                $output .= '</div>'; // mb2megamenu-modalform-item                
                
            }

            return $output;

        }

       




        public static function language_strings()
        {

            return array(
                'remove' => get_string('remove'),
                'edit' => get_string('edit'),
                'cancel' => get_string('cancel'),
                'url' => get_string('url'),
                'moveup' => get_string('moveup'),
                'movedown' => get_string('movedown'),
                'navlabel' => get_string('navlabel', 'local_mb2megamenu'),
                'menuitem' => get_string('menuitem', 'local_mb2megamenu'),
                'nolabel' => get_string('nolabel', 'local_mb2megamenu'),
                'linktarget' => get_string('linktarget', 'local_mb2megamenu'),
                'menuitemopts' => get_string('menuitemopts', 'local_mb2megamenu'),
                'levelup' => get_string('levelup', 'local_mb2megamenu'),
                'leveldown' => get_string('leveldown', 'local_mb2megamenu'),
                'languageopts' => get_string('languageopts', 'local_mb2megamenu')
            );

        }




        public static function get_modal_template($type, $size = 'md')
        {
            global $CFG;

            $output = '';
            $content = '';
            $title = '';
            $ftbuttons = '';
            $islang = self::language_labels() ? 1 : 0;

            if ( $type === 'settings' )
            {
                $title = get_string('menuitemopts', 'local_mb2megamenu');
                $content = Mb2megamenuSettings::menuitem_settings_form();
                $ftbuttons .= '<button type="button" class="mb2-pb-btn typesuccess mb2megamenu-modalbtn-save">' . get_string('save') . '</button>';
                $ftbuttons .= '<button type="button" class="mb2-pb-btn mb2megamenu-modalbtn-cancel" data-dismiss="modal">' . get_string('cancel') . '</button>';
            }
            elseif ( $type === 'icons' )
            {
                $title = get_string('icon');
                $content = Mb2megamenuSettings::font_icon_tabs();
                $ftbuttons .= '<button type="button" class="mb2-pb-btn mb2megamenu-modalbtn-cancel" data-dismiss="modal">' . get_string('cancel') . '</button>';
            }
            elseif ( $type === 'images' )
            {
                $title = get_string('bgimage', 'local_mb2megamenu');
                $content = Mb2megamenuSettings::images();
                $ftbuttons .= '<button type="button" class="mb2-pb-btn typesuccess mb2megamenu-modalbtn-cancel" data-modal="#mb2megamenu-modal-file-manager">' . get_string('upload') . '</button>';
                $ftbuttons .= '<button type="button" class="mb2-pb-btn mb2megamenu-modalbtn-cancel" data-dismiss="modal">' . get_string('cancel') . '</button>';
            }
            elseif ( $type === 'file-manager' )
            {
                $title = get_string('bgimage', 'local_mb2megamenu');
                $content = Mb2megamenuApi::file_manager_iframe();
                $ftbuttons .= '<button type="button" id="applay-file-manager" class="mb2-pb-btn typesuccess">' . get_string('save') . '</button>';
                $ftbuttons .= '<button type="button" class="mb2-pb-btn mb2megamenu-modalbtn-cancel" data-dismiss="modal">' . get_string('cancel') . '</button>';
            }
            elseif ( $type === 'languages' )
            {
                $title = get_string('languageopts', 'local_mb2megamenu');
                $content = self::language_labels();
                $ftbuttons .= '<button type="button" class="mb2-pb-btn typesuccess mb2megamenu-lang_modalbtn-save">' . get_string('save') . '</button>';
                $ftbuttons .= '<button type="button" class="mb2-pb-btn mb2megamenu-modalbtn-cancel" data-dismiss="modal">' . get_string('cancel') . '</button>';
            }

            $output .= '<div id="mb2megamenu-modal-' . $type . '" class="mb2megamenu-modal modal fade" role="dialog" data-lang="' . $CFG->lang . '" data-islangopt="' . $islang . '">';
            $output .= '<div class="modal-dialog modal-dialog-scrollable modal-' . $size . '" role="document">';
            $output .= '<div class="modal-content">';
            $output .= '<div class="modal-header">';
            $output .= '<h5 class="modal-title">' . $title . '</h5>';
            $output .= '</div>'; // modal-header
            $output .= '<div class="modal-body">';
            $output .= $content;
            $output .= '</div>'; // modal-body
            $output .= '<div class="modal-footer">';
            $output .= $ftbuttons;
            $output .= '</div>'; // modal-footer
            $output .= '</div>'; // modal-content
            $output .= '</div>'; // modal-dialog
            $output .= '</div>'; // mb2megamenu-modal-settings

            return $output;


        }



    }

}
