<?php
/**
 * Copyright (c) 2014-2026 Plugin Company
 *
 * This file is part of Fraud Prevention, released under the MIT License.
 * See the LICENSE file in the project root.
 */
/**
 * Fraud Suspicion admin edit form
 *
 * @category    Plugincompany
 * @package     Plugincompany_Blacklist
 * @author      Plugin Company
 */
class Plugincompany_Blacklist_Block_Adminhtml_Item_Edit
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
        $this->_controller = 'adminhtml_item';
        $this->_updateButton('save', 'label', Mage::helper('plugincompany_blacklist')->__('Save Fraud Suspicion'));
        $this->_updateButton('delete', 'label', Mage::helper('plugincompany_blacklist')->__('Delete Fraud Suspicion'));
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
        if( Mage::registry('current_item') && Mage::registry('current_item')->getId() ) {
            return Mage::helper('plugincompany_blacklist')->__("Edit Fraud Suspicion Record", $this->escapeHtml(Mage::registry('current_item')->getId()));
        }
        else {
            return Mage::helper('plugincompany_blacklist')->__('Add Fraud Suspicion Record');
        }
    }
}
