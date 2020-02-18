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
class Plugincompany_Blacklist_Block_Adminhtml_Item_Edit_Tab_Form
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
        $fieldset = $form->addFieldset('item_form', array('legend'=>Mage::helper('plugincompany_blacklist')->__('Fraud Suspicion Details')));

        $fieldset->addField('customer_id', 'text', array(
            'label' => Mage::helper('plugincompany_blacklist')->__('Customer ID'),
            'name'  => 'customer_id',

        ));

        $fieldset->addField('company', 'text', array(
            'label' => Mage::helper('plugincompany_blacklist')->__('Company'),
            'name'  => 'company',

        ));

        $fieldset->addField('firstname', 'text', array(
            'label' => Mage::helper('plugincompany_blacklist')->__('First name'),
            'name'  => 'firstname',

        ));

        $fieldset->addField('middlename', 'text', array(
            'label' => Mage::helper('plugincompany_blacklist')->__('Middle name'),
            'name'  => 'middlename',

        ));

        $fieldset->addField('lastname', 'text', array(
            'label' => Mage::helper('plugincompany_blacklist')->__('Last name'),
            'name'  => 'lastname',

        ));

        $fieldset->addField('email', 'text', array(
            'label' => Mage::helper('plugincompany_blacklist')->__('E-mail'),
            'name'  => 'email',

        ));

        $fieldset->addField('telephone', 'text', array(
            'label' => Mage::helper('plugincompany_blacklist')->__('Telephone'),
            'name'  => 'telephone',

        ));

        $fieldset->addField('fax', 'text', array(
            'label' => Mage::helper('plugincompany_blacklist')->__('Fax'),
            'name'  => 'fax',

        ));

        $fieldset->addField('address_type', 'select', array(
            'label' => Mage::helper('plugincompany_blacklist')->__('Address Type'),
            'name'  => 'address_type',
            'options' => array(
                'shipping' => 'Shipping address',
                'billing' => 'Billing address',
            )
        ));

        $fieldset->addField('street', 'text', array(
            'label' => Mage::helper('plugincompany_blacklist')->__('Street'),
            'name'  => 'street',

        ));

        $fieldset->addField('city', 'text', array(
            'label' => Mage::helper('plugincompany_blacklist')->__('City'),
            'name'  => 'city',

        ));

        $fieldset->addField('postcode', 'text', array(
            'label' => Mage::helper('plugincompany_blacklist')->__('Postcode'),
            'name'  => 'postcode',

        ));

        $fieldset->addField('region', 'text', array(
            'label' => Mage::helper('plugincompany_blacklist')->__('Region'),
            'name'  => 'region',

        ));

        $fieldset->addField('country_id', 'select', array(
            'label' => Mage::helper('plugincompany_blacklist')->__('Country'),
            'name'  => 'country_id',

            'values'=> Mage::getResourceModel('directory/country_collection')->toOptionArray(),
        ));
        
        $fieldset->addField('customer_ip', 'text', array(
            'label' => Mage::helper('plugincompany_blacklist')->__('Visitor IP'),
            'name'  => 'customer_ip',
        ));

        if (Mage::app()->isSingleStoreMode()){
            $fieldset->addField('store_id', 'hidden', array(
                'name'      => 'stores[]',
                'value'     => Mage::app()->getStore(true)->getId()
            ));
            Mage::registry('current_item')->setStoreId(Mage::app()->getStore(true)->getId());
        }
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
