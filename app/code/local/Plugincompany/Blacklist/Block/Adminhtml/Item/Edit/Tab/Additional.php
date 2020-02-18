<?php
/**
 * Copyright (c) 2014-2026 Plugin Company
 *
 * This file is part of Fraud Prevention, released under the MIT License.
 * See the LICENSE file in the project root.
 */
/**
 * Blacklist Item edit form tab
 *
 * @category    Plugincompany
 * @package     Plugincompany_Blacklist
 * @author      Plugin Company
 */
class Plugincompany_Blacklist_Block_Adminhtml_Item_Edit_Tab_Additional
    extends Mage_Adminhtml_Block_Widget_Form {
    /**
     * prepare the form
     * @access protected
     * @return Plugincompany_Blacklist_Block_Adminhtml_Item_Edit_Tab_Form
     * @author Plugin Company
     */
    protected function _prepareForm(){
        $form = new Varien_Data_Form();
        $form->setHtmlIdPrefix('item_');
        $form->setFieldNameSuffix('item');
        $this->setForm($form);
        $fieldset = $form->addFieldset('item_additional', array('legend'=>Mage::helper('plugincompany_blacklist')->__('Additional Information')));

        $users = array();
        $users[''] = '';
        foreach (Mage::getResourceModel('admin/user_collection') as $item) {
            $users[$item->getUserId()] = $item->getFirstname() . ' ' . $item->getLastname();
        }

        $fieldset->addField('marked_by_admin', 'select', array(
            'label' => Mage::helper('plugincompany_blacklist')->__('Admin'),
            'name'  => 'marked_by_admin',
            'options' => $users,
            'disabled' => true
        ));


        $fieldset->addField('comment', 'textarea', array(
            'label' => Mage::helper('plugincompany_blacklist')->__('Comment'),
            'name'  => 'comment',

        ));

        $formValues = Mage::registry('current_item')->getDefaultValues();
        if (!is_array($formValues)){
            $formValues = array();
        }
        if (Mage::getSingleton('adminhtml/session')->getItemData()){
            $formValues = array_merge($formValues, Mage::getSingleton('adminhtml/session')->getItemData());
            Mage::getSingleton('adminhtml/session')->setItemData(null);
        }
        elseif (Mage::registry('current_item')){
            $formValues = array_merge($formValues, Mage::registry('current_item')->getData());
        }
        $form->setValues($formValues);
        return parent::_prepareForm();
    }
}
