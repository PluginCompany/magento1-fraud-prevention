<?php
/**
 * Copyright (c) 2014-2026 Plugin Company
 *
 * This file is part of Fraud Prevention, released under the MIT License.
 * See the LICENSE file in the project root.
 */
/**
 * Blacklist Rule edit form tab
 *
 * @category    Plugincompany
 * @package     Plugincompany_Blacklist
 * @author      Plugin Company
 */
class Plugincompany_Blacklist_Block_Adminhtml_Rule_Edit_Tab_Form
    extends Mage_Adminhtml_Block_Widget_Form {
    /**
     * prepare the form
     * @access protected
     * @return Plugincompany_Blacklist_Block_Adminhtml_Rule_Edit_Tab_Form
     * @throws Mage_Core_Model_Store_Exception
     * @author Plugin Company
     */
    protected function _prepareForm(){
        $form = new Varien_Data_Form();
        $form->setHtmlIdPrefix('rule_');
        $form->setFieldNameSuffix('rule');
        $this->setForm($form);
        $fieldset = $form->addFieldset('rule_form', array('legend'=>Mage::helper('plugincompany_blacklist')->__('Blacklisting Rule Settings')));

        $fieldset->addField('name', 'text', array(
            'label' => Mage::helper('plugincompany_blacklist')->__('Rule Name'),
            'name'  => 'name',
            'required'  => true,
            'class' => 'required-entry',
        ));

        $fieldset->addField('attributes', 'multiselect', array(
            'label' => Mage::helper('plugincompany_blacklist')->__('Rule Attributes'),
            'name' => 'attributes',
            'required' => true,
            'class' => 'required-entry',
            'note' => 'Select the attribute(s) to be compared with existing fraud suspicion records when a new order is placed.',
            'values' => Mage::getModel('plugincompany_blacklist/attribute_source_attributes')->toOptionArray()
        ));

        $fieldset->addField('strikes', 'text', array(
            'label' => Mage::helper('plugincompany_blacklist')->__('Permitted Strikes'),
            'name'  => 'strikes',
            'note'	=> $this->__('The amount of fraud suspicion records with matching rule attributes required to blacklist a new order.'),
            'required'  => true,
            'class' => 'required-entry validate-greater-than-zero',

        ));
        $fieldset->addField('status', 'select', array(
            'label' => Mage::helper('plugincompany_blacklist')->__('Status'),
            'name'  => 'status',
            'values'=> array(
                array(
                    'value' => 1,
                    'label' => Mage::helper('plugincompany_blacklist')->__('Enabled'),
                ),
                array(
                    'value' => 0,
                    'label' => Mage::helper('plugincompany_blacklist')->__('Disabled'),
                ),
            ),
        ));
        if (Mage::app()->isSingleStoreMode()){
            $fieldset->addField('store_id', 'hidden', array(
                'name'      => 'stores[]',
                'value'     => Mage::app()->getStore(true)->getId()
            ));
            Mage::registry('current_rule')->setStoreId(Mage::app()->getStore(true)->getId());
        }
        $formValues = Mage::registry('current_rule')->getDefaultValues();
        if (!is_array($formValues)){
            $formValues = array();
        }
        if (Mage::getSingleton('adminhtml/session')->getRuleData()){
            $formValues = array_merge($formValues, Mage::getSingleton('adminhtml/session')->getRuleData());
            Mage::getSingleton('adminhtml/session')->setRuleData(null);
        }
        elseif (Mage::registry('current_rule')){
            $formValues = array_merge($formValues, Mage::registry('current_rule')->getData());
        }
        $form->setValues($formValues);
        return parent::_prepareForm();
    }
}
