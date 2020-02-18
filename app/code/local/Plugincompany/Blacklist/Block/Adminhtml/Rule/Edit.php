<?php
/**
 * Copyright (c) 2014-2026 Plugin Company
 *
 * This file is part of Fraud Prevention, released under the MIT License.
 * See the LICENSE file in the project root.
 */
/**
 * Blacklist Rule admin edit form
 *
 * @category    Plugincompany
 * @package     Plugincompany_Blacklist
 * @author      Plugin Company
 */
class Plugincompany_Blacklist_Block_Adminhtml_Rule_Edit
    extends Mage_Adminhtml_Block_Widget_Form_Container {
    /**
     * constructor
     * @access public
     * @return void
     * @author Plugin Company
     */
    public function __construct(){
        parent::__construct();
        $this->_blockGroup = 'plugincompany_blacklist';
        $this->_controller = 'adminhtml_rule';
        $this->_updateButton('save', 'label', Mage::helper('plugincompany_blacklist')->__('Save Blacklisting Rule'));
        $this->_updateButton('delete', 'label', Mage::helper('plugincompany_blacklist')->__('Delete Blacklisting Rule'));
        $this->_addButton('saveandcontinue', array(
            'label'        => Mage::helper('plugincompany_blacklist')->__('Save And Continue Edit'),
            'onclick'    => 'saveAndContinueEdit()',
            'class'        => 'save',
        ), -100);
        $this->_formScripts[] = "
            function saveAndContinueEdit(){
                editForm.submit($('edit_form').action+'back/edit/');
            }
        ";
    }
    /**
     * get the edit form header
     * @access public
     * @return string
     * @author Plugin Company
     */
    public function getHeaderText(){
        if( Mage::registry('current_rule') && Mage::registry('current_rule')->getId() ) {
            return Mage::helper('plugincompany_blacklist')->__("Edit Blacklisting Rule '%s'", $this->escapeHtml(Mage::registry('current_rule')->getName()));
        }
        else {
            return Mage::helper('plugincompany_blacklist')->__('Add Blacklisting Rule');
        }
    }
}
