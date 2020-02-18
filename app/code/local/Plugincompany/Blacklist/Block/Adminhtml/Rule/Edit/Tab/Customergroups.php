<?php
/**
 * Copyright (c) 2014-2026 Plugin Company
 *
 * This file is part of Fraud Prevention, released under the MIT License.
 * See the LICENSE file in the project root.
 */
/**
 * customer group tab
 *
 * @category    Plugincompany
 * @package     Plugincompany_Blacklist
 * @author      Plugin Company
 */
class Plugincompany_Blacklist_Block_Adminhtml_Rule_Edit_Tab_Customergroups
    extends Mage_Adminhtml_Block_Widget_Form {
    /**
     * prepare the form
     * @access protected
     * @return Plugincompany_Blacklist_Block_Adminhtml_Rule_Edit_Tab_Customergroups
     * @author Plugin Company
     */
    protected function _prepareForm(){
        $form = new Varien_Data_Form();
        $form->setFieldNameSuffix('rule');
        $this->setForm($form);

        $fieldset = $form->addFieldset('rule_cgroups_form', 
            array('legend'=>Mage::helper('plugincompany_blacklist')->__('Enabled for Customer Groups')));
        
        $groups = Mage::getResourceModel('customer/group_collection')->toOptionArray();
        array_unshift($groups,array(
            'value' => 'all',
            'label' => $this->__('- All Groups -')
        ));
            
        $fieldset->addField('customer_groups', 'multiselect', array(
            'name'      => 'customer_groups[]',
            'label'     => Mage::helper('catalogrule')->__('Customer Groups'),
            'title'     => Mage::helper('catalogrule')->__('Customer Groups'),
            'required'  => true,
            'values'    => $groups
        ));
        
        $form->addValues(Mage::registry('current_rule')->getData());
        
        return parent::_prepareForm();
    }
}
