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

require_once($CFG->libdir . '/formslib.php');
require_once( __DIR__ . '/classes/builder.php' );

class service_edit_form extends moodleform {

    /**
     * Defines the standard structure of the form.
     *
     * @throws \coding_exception
     */
    protected function definition()
    {
        $mform =& $this->_form;
        global $CFG;
        
        $size = array('size' => 60 );
        $context = context_system::instance();

        // Hidden fields
        $mform->addElement('hidden', 'id');
        $mform->addElement('hidden', 'timecreated');
        $mform->addElement('hidden', 'timemodified');
        $mform->addElement('hidden', 'createdby');
        $mform->addElement('hidden', 'modifiedby');
        $mform->addElement('hidden', 'attribs[]');
        $mform->setType('attribs[]', PARAM_RAW);
        $mform->addElement('hidden', 'menuitems');
        $mform->setType('id', PARAM_INT);
        $mform->setType('timecreated', PARAM_INT);
        $mform->setType('timemodified', PARAM_INT);
        $mform->setType('createdby', PARAM_INT);
        $mform->setType('modifiedby', PARAM_INT);
        $mform->setType('menuitems', PARAM_RAW);

        $mform->addElement('text', 'name', get_string('name', 'local_mb2megamenu'), $size);
        $mform->addRule('name', null, 'required');
        $mform->setType('name', PARAM_NOTAGS);

        $mform->addElement('select', 'enable', get_string('enable', 'local_mb2megamenu'), array(
            1 => get_string('yes'),
            0 => get_string('no')
        ));        
        $mform->setType('enable', PARAM_INT);
        $mform->setDefault('enable', 1);
        
        $mform->addElement('hidden', 'attribs[openon]', get_string('openon', 'local_mb2megamenu'), array(
            1 => get_string('openonhover', 'local_mb2megamenu'),
            2 => get_string('openonclick', 'local_mb2megamenu')
        ));       
        $mform->setType('attribs[openon]', PARAM_INT);       
        
        $mform->addElement('html', '<div style="height:1.2rem;"></div>');
        $mform->addElement('html', '<div class="mb2tmpl-accc"><div class="mb2tmpl-acc-title ts_cheader h6 d-flex align-items-center m-0 p-2 w-100 border-0"><button class="themereset ts-btn ts-togglegroup ts_togglejs collapsed p-0 lhsmall d-inline-flex align-items-center mr-2" type="button" aria-expanded="false" aria-label="' . get_string('settingstogglecat','theme_mb2nl', get_string('settingsstyledd', 'local_mb2megamenu')) . '"><span class="toggle-icon d-inline-flex align-items-center" aria-hidden="true"></span></button>' . 
                           get_string('settingsstyledd', 'local_mb2megamenu') . '</div><div class="mb2tmpl-acccontent ts_jsontent d-none"><div>');
                
        $mform->addElement('select', 'attribs[rounded]', get_string('rounded', 'local_mb2megamenu'), array(
            1 => get_string('yes'),
            0 => get_string('no')
        ));       
        $mform->setType('attribs[rounded]', PARAM_INT); 
        
        $mform->addElement('html', '<div class="mb-3 pt-4"><strong>' . get_string('normalstate', 'local_mb2megamenu') . '</strong></div>'); 
                
        $mform->addElement('text', 'attribs[ddcolor]', get_string('color', 'local_mb2megamenu'), array('class'=>'mb2color'));       
        $mform->setType('attribs[ddcolor]', PARAM_TEXT); 
        
        $mform->addElement('text', 'attribs[ddsubcolor]', get_string('subcolor', 'local_mb2megamenu'), array('class'=>'mb2color'));       
        $mform->setType('attribs[ddsubcolor]', PARAM_TEXT); 
        
        $mform->addElement('text', 'attribs[ddiconcolor]', get_string('iconcolor', 'local_mb2megamenu'), array('class'=>'mb2color'));       
        $mform->setType('attribs[ddiconcolor]', PARAM_TEXT); 

        $mform->addElement('text', 'attribs[ddbgcolor]', get_string('bgcolor', 'local_mb2megamenu'), array('class'=>'mb2color'));       
        $mform->setType('attribs[ddbgcolor]', PARAM_TEXT); 
        
        $mform->addElement('text', 'attribs[ddbordercolor]', get_string('bordercolor', 'local_mb2megamenu'), array('class'=>'mb2color'));       
        $mform->setType('attribs[ddbordercolor]', PARAM_TEXT);  
        
        $mform->addElement('html', '<div class="mb-3 pt-4"><strong>' . get_string('hoverstate', 'local_mb2megamenu') . '</strong></div>');         
        
        $mform->addElement('text', 'attribs[ddhcolor]', get_string('color', 'local_mb2megamenu'), array('class'=>'mb2color'));       
        $mform->setType('attribs[ddhcolor]', PARAM_TEXT); 
        
        $mform->addElement('text', 'attribs[ddsubhcolor]', get_string('subcolor', 'local_mb2megamenu'), array('class'=>'mb2color'));       
        $mform->setType('attribs[ddsubhcolor]', PARAM_TEXT); 
        
        $mform->addElement('text', 'attribs[ddiconhcolor]', get_string('iconcolor', 'local_mb2megamenu'), array('class'=>'mb2color'));       
        $mform->setType('attribs[ddiconhcolor]', PARAM_TEXT); 
        
        $mform->addElement('text', 'attribs[ddhbgcolor]', get_string('bgcolor', 'local_mb2megamenu'), array('class'=>'mb2color'));       
        $mform->setType('attribs[ddhbgcolor]', PARAM_TEXT);
        
        $mform->addElement('html', '</div></div></div>');
        
        $mform->addElement('html', '<div class="mb2tmpl-accc"><div class="mb2tmpl-acc-title ts_cheader h6 d-flex align-items-center m-0 p-2 w-100 border-0"><button class="themereset ts-btn ts-togglegroup ts_togglejs collapsed p-0 lhsmall d-inline-flex align-items-center mr-2" type="button" aria-expanded="false" aria-label="' . get_string('settingstogglecat','theme_mb2nl', get_string('settingsstyleheading', 'local_mb2megamenu')) . '"><span class="toggle-icon d-inline-flex align-items-center" aria-hidden="true"></span></button>' . 
                           get_string('settingsstyleheading', 'local_mb2megamenu') . '</div><div class="mb2tmpl-acccontent ts_jsontent d-none"><div>');
                                                           
        $mform->addElement('text', 'attribs[mcminwidth]', get_string('mcminwidth', 'local_mb2megamenu'));       
        $mform->setType('attribs[mcminwidth]', PARAM_INT);  

        $mform->addElement('text', 'attribs[mcminheight]', get_string('mcminheight', 'local_mb2megamenu'));       
        $mform->setType('attribs[mcminheight]', PARAM_INT);  
        
        $mform->addElement('select', 'attribs[mcpadding]', get_string('colpadding', 'local_mb2megamenu'), array(
            'small' => get_string('colpaddingsm', 'local_mb2megamenu'),
            'big' => get_string('colpaddingbig', 'local_mb2megamenu')
        ));   
        $mform->setType('attribs[mcpadding]', PARAM_TEXT); 

        $mform->addElement('text', 'attribs[hfs]', get_string('hfs', 'local_mb2megamenu'));       
        $mform->setType('attribs[hfs]', PARAM_TEXT);  
        
        $mform->addElement('select', 'attribs[hfw]', get_string('hfw', 'local_mb2megamenu'), array(
            'var(--mb2-pb-fwlight)' => get_string('hfwlight', 'local_mb2megamenu'),
            'var(--mb2-pb-fwnormal)' => get_string('hfwnormal', 'local_mb2megamenu'),
            'var(--mb2-pb-fwmedium)' => get_string('hfwmedium', 'local_mb2megamenu'),
            'var(--mb2-pb-fwbold)' => get_string('hfwbold', 'local_mb2megamenu')
        )); 

        $mform->addElement('select', 'attribs[htu]', get_string('htu', 'local_mb2megamenu'), array(
            1 => get_string('yes'),
            0 => get_string('no')
        ));       
        $mform->setType('attribs[htu]', PARAM_INT); 
        
        $mform->addElement('text', 'attribs[hifs]', get_string('hifs', 'local_mb2megamenu'));       
        $mform->setType('attribs[hifs]', PARAM_TEXT);  
        
        $mform->addElement('html', '<div class="mb-3 pt-4"><strong>' . get_string('mcitems', 'local_mb2megamenu') . '</strong></div>'); 

        $mform->addElement('select', 'attribs[mcitemstyle]', get_string('mcitemstyle', 'local_mb2megamenu'), array(
            'minimal' => get_string('mcitemstylemin', 'local_mb2megamenu'),
            'bg' => get_string('mcitemstylebg', 'local_mb2megamenu'),
            'border' => get_string('mcitemstyleborder', 'local_mb2megamenu')
        )); 

        $mform->addElement('html', '<div class="mb-3 pt-4"><strong>' . get_string('normalstate', 'local_mb2megamenu') . '</strong></div>'); 
                           
        $mform->addElement('text', 'attribs[mcitemcolor]', get_string('color', 'local_mb2megamenu'), array('class'=>'mb2color'));       
        $mform->setType('attribs[mcitemcolor]', PARAM_TEXT); 
        
        $mform->addElement('text', 'attribs[mcitemsubcolor]', get_string('subcolor', 'local_mb2megamenu'), array('class'=>'mb2color'));       
        $mform->setType('attribs[mcitemsubcolor]', PARAM_TEXT); 
        
        $mform->addElement('text', 'attribs[mcitemiconcolor]', get_string('iconcolor', 'local_mb2megamenu'), array('class'=>'mb2color'));       
        $mform->setType('attribs[mcitemiconcolor]', PARAM_TEXT); 
        
        $mform->addElement('text', 'attribs[mcitembordercolor]', get_string('bordercolor', 'local_mb2megamenu'), array('class'=>'mb2color'));       
        $mform->setType('attribs[mcitembordercolor]', PARAM_TEXT);  


        $mform->addElement('html', '<div class="mb-3 pt-4"><strong>' . get_string('hoverstate', 'local_mb2megamenu') . '</strong></div>');         
        
        $mform->addElement('text', 'attribs[mcitemhcolor]', get_string('color', 'local_mb2megamenu'), array('class'=>'mb2color'));       
        $mform->setType('attribs[mcitemhcolor]', PARAM_TEXT); 
        
        $mform->addElement('text', 'attribs[mcitemsubhcolor]', get_string('subcolor', 'local_mb2megamenu'), array('class'=>'mb2color'));       
        $mform->setType('attribs[mcitemsubhcolor]', PARAM_TEXT); 
        
        $mform->addElement('text', 'attribs[mcitemiconhcolor]', get_string('iconcolor', 'local_mb2megamenu'), array('class'=>'mb2color'));       
        $mform->setType('attribs[mcitemiconhcolor]', PARAM_TEXT); 
        
        $mform->addElement('text', 'attribs[mcitemhbgcolor]', get_string('bgcolor', 'local_mb2megamenu'), array('class'=>'mb2color'));       
        $mform->setType('attribs[mcitemhbgcolor]', PARAM_TEXT);
        
                
        $mform->addElement('html', '</div></div></div>');

        $mform->addElement('html', '<div class="mb2tmpl-accc"><div class="mb2tmpl-acc-title ts_cheader h6 d-flex align-items-center m-0 p-2 w-100 border-0"><button class="themereset ts-btn ts-togglegroup ts_togglejs collapsed p-0 lhsmall d-inline-flex align-items-center mr-2" type="button" aria-expanded="false" aria-label="' . get_string('settingstogglecat','theme_mb2nl', get_string('settingsstylmobile', 'local_mb2megamenu')) . '"><span class="toggle-icon d-inline-flex align-items-center" aria-hidden="true"></span></button>' . 
                           get_string('settingsstylmobile', 'local_mb2megamenu') . '</div><div class="mb2tmpl-acccontent ts_jsontent d-none"><div>');
              
        $mform->addElement('html', '<div class="mb-3 pt-4"><strong>' . get_string('normalstate', 'local_mb2megamenu') . '</strong></div>'); 
       
        $mform->addElement('text', 'attribs[m1lcolor]', get_string('color', 'local_mb2megamenu'), array('class'=>'mb2color'));       
        $mform->setType('attribs[m1lcolor]', PARAM_TEXT);

        $mform->addElement('text', 'attribs[m1subcolor]', get_string('subcolor', 'local_mb2megamenu'), array('class'=>'mb2color'));       
        $mform->setType('attribs[m1subcolor]', PARAM_TEXT);

        $mform->addElement('text', 'attribs[m1iconcolor]', get_string('iconcolor', 'local_mb2megamenu'), array('class'=>'mb2color'));       
        $mform->setType('attribs[m1iconcolor]', PARAM_TEXT);

        $mform->addElement('text', 'attribs[m1lbgcolor]', get_string('bgcolor', 'local_mb2megamenu'), array('class'=>'mb2color'));       
        $mform->setType('attribs[m1lbgcolor]', PARAM_TEXT);

        $mform->addElement('text', 'attribs[m1lbordercolor]', get_string('bordercolor', 'local_mb2megamenu'), array('class'=>'mb2color'));       
        $mform->setType('attribs[m1lbordercolor]', PARAM_TEXT);

        $mform->addElement('html', '<div class="mb-3 pt-4"><strong>' . get_string('openstate', 'local_mb2megamenu') . '</strong></div>'); 

        $mform->addElement('text', 'attribs[m1locolor]', get_string('color', 'local_mb2megamenu'), array('class'=>'mb2color'));       
        $mform->setType('attribs[m1locolor]', PARAM_TEXT);

        $mform->addElement('text', 'attribs[m1lbgocolor]', get_string('bgcolor', 'local_mb2megamenu'), array('class'=>'mb2color'));       
        $mform->setType('attribs[m1lbgocolor]', PARAM_TEXT);

        $mform->addElement('text', 'attribs[m1subocolor]', get_string('subcolor', 'local_mb2megamenu'), array('class'=>'mb2color'));       
        $mform->setType('attribs[m1subocolor]', PARAM_TEXT);

        $mform->addElement('text', 'attribs[m1iconocolor]', get_string('iconcolor', 'local_mb2megamenu'), array('class'=>'mb2color'));       
        $mform->setType('attribs[m1iconocolor]', PARAM_TEXT);
        
        $mform->addElement('html', '</div></div></div>');                
        
        $mform->addElement('html', '<div style="margin:2.4rem 0;"><hr></div>');
                
        $mform->addElement('html', '<h4>' . get_string('menuitems', 'local_mb2megamenu') . '</h4>');
        
        $mform->addElement( 'html', Mb2megamenuBuilder::get_menu_builder() );

        $this->add_action_buttons();
    }
}
