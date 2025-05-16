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

require_once( __DIR__ . '/../../config.php' );
require_once( __DIR__ . '/menu_form.php');
require_once( __DIR__ . '/classes/api.php' );
require_once( __DIR__ . '/classes/builder.php' );
require_once( __DIR__ . '/classes/helper.php' );
require_once( __DIR__ . '/lib.php' );
require_once( $CFG->libdir . '/adminlib.php' );

// Optional parameters
$itemid = optional_param('itemid', 0, PARAM_INT);
$returnurl = optional_param( 'returnurl', '/local/mb2megamenu/index.php', PARAM_LOCALURL );

// Link generation
$urlparameters = array( 'itemid' => $itemid, 'returnurl' => $returnurl );
$baseurl = new moodle_url( '/local/mb2megamenu/edit.php', $urlparameters );
$returnurl = new moodle_url( $returnurl );

// Configure the context of the page
admin_externalpage_setup( 'local_mb2megamenu_managemenus', '', null, $baseurl );
require_capability( 'local/mb2megamenu:manageitems', context_system::instance() );

// Get existing items
$items = Mb2megamenuApi::get_list_records();

// Create an editing form
$mform = new service_edit_form($PAGE->url);

// Cancel processing
if ($mform->is_cancelled())
{
    $message = '';
}

// Getting the data
$menurecord = new stdClass();
$data = Mb2megamenuApi::get_form_data($mform, $itemid);

// Processing of received data
if ( ! empty( $data ) )
{    
    if ( $itemid )
    {
        Mb2megamenuApi::update_record_data($data, true);
        $message = get_string( 'updated', 'core', Mb2megamenuApi::get_record($itemid)->name );
    }
    else
    {
        Mb2megamenuApi::add_record($data);
        $message = get_string('menucreated', 'local_mb2megamenu');
    }
}

if (isset($message))
{
    redirect($returnurl, $message);
}

// The page title
$titlepage = get_string('editmenuitem', 'local_mb2megamenu');
$PAGE->navbar->add($titlepage);
$PAGE->set_heading($titlepage);
$PAGE->set_title($titlepage);
echo $OUTPUT->header();

if ( Mb2megamenuHelper::active_menu() == $itemid )
{
    echo '<span class="badge badge-success">' . get_string('activemenu', 'local_mb2megamenu') . '</span>';
}

echo $OUTPUT->heading($titlepage);

// Displays the form
$mform->display();

// Get modal settings
echo Mb2megamenuBuilder::get_modal_template('settings');
echo Mb2megamenuBuilder::get_modal_template('languages');
echo Mb2megamenuBuilder::get_modal_template('icons', 'xl');
echo Mb2megamenuBuilder::get_modal_template('images', 'xl');

// This modal window must be here, because of the from inside
// We have to avoid placing media form in the main form
echo Mb2megamenuBuilder::get_modal_template('file-manager', ''); 

echo $OUTPUT->footer();
?>