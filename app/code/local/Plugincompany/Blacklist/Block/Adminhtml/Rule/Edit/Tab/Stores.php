<?php
/**
 * Copyright (c) 2014-2026 Plugin Company
 *
 * This file is part of Fraud Prevention, released under the MIT License.
 * See the LICENSE file in the project root.
 */
/**
 * store selection tab
 *
 * @category    Plugincompany
 * @package     Plugincompany_Blacklist
 * @author      Plugin Company
 */
class Plugincompany_Blacklist_Block_Adminhtml_Rule_Edit_Tab_Stores
    extends Mage_Adminhtml_Block_Widget_Form {
    /**
     * prepare the form
     * @access protected
     * @return Plugincompany_Blacklist_Block_Adminhtml_Rule_Edit_Tab_Stores
     * @author Plugin Company
     */
    protected function _prepareForm(){
        $form = new Varien_Data_Form();
        $form->setFieldNameSuffix('rule');
        $this->setForm($form);
        $fieldset = $form->addFieldset('rule_stores_form', array('legend'=>Mage::helper('plugincompany_blacklist')->__('Store Views')));
        $field = $fieldset->addField('store_id', 'multiselect', array(
            'name'  => 'stores[]',
            'label' => Mage::helper('plugincompany_blacklist')->__('Store Views'),
            'title' => Mage::helper('plugincompany_blacklist')->__('Store Views'),
            'required'  => true,
            'values'=> Mage::getSingleton('adminhtml/system_store')->getStoreValuesForForm(false, true),
        ));
//        $renderer = $this->getLayout()->createBlock('adminhtml/store_switcher_form_renderer_fieldset_element');
        $renderer = $this->getLayout()->createBlock('plugincompany_blacklist/adminhtml_rule_edit_tab_stores_renderer_fieldset_element');
        $field->setRenderer($renderer);
        $form->addValues(Mage::registry('current_rule')->getData());
        return parent::_prepareForm();
    }
}
