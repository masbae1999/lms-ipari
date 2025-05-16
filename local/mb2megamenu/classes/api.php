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


if ( ! class_exists( 'Mb2megamenuApi' ) )
{
    class Mb2megamenuApi
    {



        /**
         *
         * Method to get a list of all services.
         *
         */
        public static function get_list_records($limitfrom = 0, $limitnum = 0, $fields = '*' )
        {
            global $DB;

            $records = $DB->get_records('local_mb2megamenu_menus', null, 'id', $fields, $limitfrom, $limitnum);

            return $records;

        }




        /**
         *
         * Method to get sindle record.
         *
         */
        public static function get_record($itemid = 0)
        {
            global $DB;

            $record = $DB->get_record('local_mb2megamenu_menus', array('id' => $itemid), '*', MUST_EXIST);

            return $record;

        }




        /**
         *
         * Method to update the prev or next record
         *
         */
        public static function get_record_near($id, $type = 'prev')
        {

            $items = self::get_list_records();
            $newitems = self::get_sortorder_items();
            $nearitem = 0;

            $sortorder = $items[$id]->sortorder;

            // Get preview item
            if ($type === 'prev' && isset($newitems[$sortorder-1]))
            {
                $nearitem = $newitems[$sortorder-1];
            }

            // Get next item
            if ($type === 'next' && isset($newitems[$sortorder+1]))
            {
                $nearitem = $newitems[$sortorder+1];
            }

            return $nearitem;


        }




        /**
         *
         * Method to update the prev or next record
         *
         */
        public static function get_sortorder_items()
        {

            $newitems = array();
            $items = self::get_list_records();

            // Create new array of items
            // Set 'sortorder' as a key of array's values
            foreach ( $items as $item )
            {
                $newitems[$item->sortorder] = $item->id;
            }

            // Sort new array by sortorder
            ksort( $newitems );

            return $newitems;

        }





        /**
         *
         * Method to add new record.
         *
         */
        public static function add_record($data)
        {
            global $DB;

            $items = self::get_list_records();

            // Add record
            $data->id = $DB->insert_record('local_mb2megamenu_menus', array('sortorder' => count($items) + 1, 'attribs' => '' ));

            // Cache content
            $cache = cache::make('local_mb2megamenu', 'menudata');
            $cache->set( $data->id, array( 'menuitems' => $data->menuitems, 'attribs' => $data->attribs ) );

            return self::update_record_data($data, true);

        }






        /**
         *
         * Method to set editor options.
         *
         */
        public static function file_area_options()
        {
            global $CFG;
            require_once($CFG->libdir.'/formslib.php');
            $options = array();

            $options['subdirs'] = false;
            $options['maxfiles'] = 1;
            $options['context'] = context_system::instance();

            return $options;

        }




        /**
         *
         * Method to update the record in the database.
         *
         */
        public static function update_record_data($data, $editpage = false)
        {
            global $DB;

            if ( is_array($data->attribs) )
            {
                // Convert php arrays to json array
                $data->attribs = json_encode($data->attribs);
            }

            // Update existing item
            $DB->update_record('local_mb2megamenu_menus', $data);

            // Update cache
            $cache = cache::make('local_mb2megamenu', 'menudata');
            $cache->set( $data->id, array( 'menuitems' => $data->menuitems, 'attribs' => $data->attribs ) );

        }




        /**
         *
         * Method to check if user can delete item.
         *
         */
        public static function can_delete()
        {
            return has_capability('local/mb2megamenu:manageitems', context_system::instance());
        }




        /**
         *
         * Method to delete item.
         *
         */
        public static function delete($itemid)
        {
            global $DB;

            if (!self::can_delete())
            {
                return;
            }

            $DB->delete_records('local_mb2megamenu_menus', array('id' => $itemid));

            self::update_sortorder($itemid);

        }




        /**
         *
         * Method to update sortorder after delet item.
         *
         */
        public static function update_sortorder($itemid = 0)
        {
            $items = self::get_list_records();
            $sortorder_items = self::get_sortorder_items();
            $sortorder_items = array_diff($sortorder_items, array($itemid));
            $i = 0;

            foreach ($sortorder_items as $item)
            {
                $i++;
                $callbacksorted = $items[$item];
                $callbacksorted->sortorder = $i;
                self::update_record_data($callbacksorted);
            }

        }





        /**
         *
         * Method to change item status.
         *
         */
        public static function switch_status($itemid = 0)
        {

            $items = self::get_list_records();
            $item = $items[$itemid];
            $item->enable = !$item->enable;
            self::update_record_data($item);

        }





        /**
         *
         * Method to get form data.
         *
         */
        public static function get_form_data($form, $itemid)
        {
            global $CFG, $USER;
            require_once($CFG->libdir . '/formslib.php');
            $data = new stdClass();

            if ( empty( $itemid ) )
            {
                $data->id = null;
                $data->timecreated = null;
                $data->createdby = null;
                $data->attribs = array();
                $data->name = get_string('nameplch', 'local_mb2megamenu', time());
                
                // Set default attributes attribs[]
                // We can't do this with the setDefault method because of the []
                $data->attribs['mcminheight'] = '80';
                $data->attribs['mcminwidth'] = '200';
                $data->attribs['mcpadding'] = 'small';
                $data->attribs['hfs'] = '1';
                $data->attribs['hifs'] = '1';
                $data->attribs['hfw'] = 'var(--mb2-pb-fwbold)';
                $data->attribs['rounded'] = 0;
                $data->attribs['openon'] = 1;
                $data->attribs['htu'] = 1;
                $data->attribs['mcitemstyle'] = 'border';                
            }
            else
            {
                $data = self::get_record($itemid);

                // Make an arrays from attributes and languages
                // and fill in menu form
                $data->attribs = json_decode($data->attribs, true);  
                
                // Set default value
                // This is required because user can use empty value
                $data->attribs['hfs'] = $data->attribs['hfs'] ? $data->attribs['hfs'] : '1';
                $data->attribs['hifs'] = $data->attribs['hifs'] ? $data->attribs['hifs'] : '1';
                $data->attribs['mcminheight'] = $data->attribs['mcminheight'] ? $data->attribs['mcminheight'] : '80';
                $data->attribs['mcminwidth'] = $data->attribs['mcminwidth'] ? $data->attribs['mcminwidth'] : '200';
            }

            // Set date created and modified
            $data->timecreated = $data->timecreated ? $data->timecreated : time();
            $data->timemodified = $data->timecreated < time() ? time() : 0;

            // Set create and modifier
            $data->createdby = $data->createdby ? $data->createdby : $USER->id;
            $data->modifiedby = $data->timecreated == time() ? 0 : $USER->id;

            $form->set_data($data);

            return $form->get_data();

        }






        /**
         *
         * Method to move up item.
         *
         */
        public static function duplicate( $itemid = 0 )
        {

            global $DB;

            // Get records and copied item
            $items = self::get_list_records();
            $data = self::get_record($itemid);

            // Get data of copied item
            // and convert data into array
            $data = (array) $data;

            // Unset ID value, wee have to create NEW item
            unset($data['id']);

            // Prepare some data values
            $data['sortorder'] = count($items) + 1;
            $data['name'] =  $data['name'] . ' - ' . get_string('copy');
            $data['enable'] = 0;    

            // Create new record
            return $DB->insert_record('local_mb2megamenu_menus', $data);

        }




        /**
         *
         * Method to move up item.
         *
         */
        public static function move_up( $itemid = 0 )
        {

            $items = self::get_list_records();
            $previtem = self::get_record_near($itemid, 'prev');

            if ( $previtem )
            {
                // Move down prev item
                $itemprev = $items[$previtem];
                $itemprev->sortorder = $itemprev->sortorder + 1;
                self::update_record_data($itemprev);

                // Move up current item
                $currentitem = $items[$itemid];
                $currentitem->sortorder = $currentitem->sortorder - 1;
                self::update_record_data($currentitem);
            }

        }






        /**
         *
         * Method to move down item.
         *
         */
        public static function move_down($itemid = 0)
        {

            $items = self::get_list_records();
            $nextitem = self::get_record_near($itemid, 'next');

            if ($nextitem)
            {
                // Move up next item
                $itemnext = $items[$nextitem];
                $itemnext->sortorder = $itemnext->sortorder - 1;
                self::update_record_data($itemnext);

                // Move down current item
                $currentitem = $items[$itemid];
                $currentitem->sortorder = $currentitem->sortorder + 1;
                self::update_record_data($currentitem);
            }

        }




        /*
		 *
		 * Method to save file in filearea
		 *
		 */
		public static function save_file( $itemid, $overwrite = false )
		{
			global $USER;

			if ( ! $itemid )
			{
				return;
			}

			$fs = get_file_storage();
			$context = context_user::instance( $USER->id );
			$newcontextid = context_system::instance();

			// Get file from draft area
			$draftfiles = $fs->get_area_files( $context->id, 'user', 'draft', $itemid, 'id DESC', false );

			if ( ! $draftfiles )
			{
				return;
			}

			$draftfile = reset( $draftfiles );

			// Define options for new file record
			$file_record = array(
				'contextid'=> $newcontextid->id,
				'component' => 'local_mb2megamenu',
				'filearea' => 'mb2megamenumedia',
				'itemid'=> 0,
				'filepath' => '/',
				'filename' => $draftfile->get_filename(),
				'userid' => $USER->id
			);

			$oldfile = $fs->get_file( $newcontextid->id, 'local_mb2megamenu', 'mb2megamenumedia', 0, '/', $draftfile->get_filename(), $USER->id );

			// Check if file with the same name key_exists
			// We don't want delete any files, so we have to change image name
			if ( $oldfile )
			{
				$newname = explode( '.', $draftfile->get_filename() );
				$filetype = end( $newname );
				$newname = str_replace( '.' . $filetype, '',  $draftfile->get_filename() ) . uniqid( '_' );
				$file_record['filename'] = $newname . '.' . $filetype;
			}

            return $fs->create_file_from_storedfile( $file_record, $draftfile );

		}





        /*
		 *
		 * Method to save file in filearea
		 *
		 */
		public static function delete_file( $filename )
		{
			global $USER;

			if ( ! $filename )
			{
				return;
			}

			$fs = get_file_storage();
			$contextid = context_system::instance();

			$file = $fs->get_file( $contextid->id, 'local_mb2megamenu', 'mb2megamenumedia', 0, '/', $filename, $USER->id );

			if ( $file )
			{
				$file->delete();
			}

		}



        /*
		 *
		 * Method to save file in filearea
		 *
		 */
        public static function file_manager_iframe()
		{

			global $CFG;
			$output = '';

			require_once( $CFG->dirroot . '/local/mb2megamenu/form-media.php' );
			$ajaxurl = new moodle_url( $CFG->wwwroot . '/local/mb2megamenu/ajax/image-upload.php', array() );

			$output .= '<div class="mb2-pb-uploadmedia-wrap" data-url="' . $ajaxurl . '">';
			$mform = new media_edit_form( 'index.php' );
			$output .= $mform->render();
			$output .= '</div>';

			return $output;

		}


        /**
         *
         * Method to set import menu data
         *
         */
        public static function menu_importer_data($dataid)
        {
            global $USER;

            $data = new stdClass();
            $items = self::get_list_records();
            $menu = self::menu_importer_menus()[$dataid];

            $data->id = null;
            $data->enable = 1;
            $data->timecreated = time();
            $data->timemodified = 0;
            $data->name = $menu['name'];
            $data->menuitems = $menu['menuitems'];
            $data->createdby = $USER->id;
            $data->modifiedby = 0;
            $data->attribs = $menu['attribs'];

            return $data;

        }


        /*
		 *
		 * Method to get import data menus
		 *
		 */
        public static function menu_importer_menus()
		{

            // Require import file
            require_once( LOCAL_MB2MEGAMENU_PATH_IMPORT );

            $menusconst = 'LOCAL_MB2MEGAMENU_MENUS';
            $menus = unserialize( base64_decode( constant( $menusconst ) ) );

            return $menus;


        }



        /*
		 *
		 * Method to set import form
		 *
		 */
        public static function menu_importer()
		{
            global $USER;

            $output = '';
            $can_manage = has_capability('local/mb2megamenu:manageitems', context_system::instance());
            $baseurl = new moodle_url('/local/mb2megamenu/');

            if ( ! $can_manage )
            {
                return;
            }
            
            $menus = self::menu_importer_menus();

            $output .= '<div class="mb2megamenu-menu-import" data-baseurl="' . $baseurl . '" data-sesskey="' . $USER->sesskey . '">';
            $output .= '<button type="button" class="themereset mb2megamenu-import-header">';
            $output .= get_string('importmenu','local_mb2megamenu');
            $output .= '</button>';
            $output .= '<div class="mb2megamenu-import-items">';

            foreach($menus as $k=>$menu)
            {              
                $output .= '<div class="mb2megamenu-import-item">';
                $output .= '<div class="item-name">' . $menu['name'] . '</div>';
                $output .= '<button type="button" class="mb2-pb-btn typesuccess sizexs rounded1 noaction" data-id="' . $k . '">';
                $output .= '<span class="import">' . get_string('import','local_mb2megamenu') . '</span>';
                $output .= '<span class="importing" aria-hidden="true">' . get_string('importing','local_mb2megamenu') . '</span>';
                $output .= '<span class="importdone" aria-hidden="true">' . get_string('importdone','local_mb2megamenu') . '</span>';
                $output .= '</button>';
                $output .= '</div>'; // mb2megamenu-import-item
            }

            $output .= '</div>'; // mb2megamenu-import-items
            $output .= '</div>';

            return $output;

        }       



    }
}