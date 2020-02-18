<?php
/**
 * Copyright (c) 2014-2026 Plugin Company
 *
 * This file is part of Fraud Prevention, released under the MIT License.
 * See the LICENSE file in the project root.
 */
/**
 * Blacklist Item admin edit tabs
 *
 * @category    Plugincompany
 * @package     Plugincompany_Blacklist
 * @author      Plugin Company
 */
class Plugincompany_Blacklist_Block_Adminhtml_Item_Edit_Tabs
    extends Mage_Adminhtml_Block_Widget_Tabs {
    /**
     * Initialize Tabs
     * @access public
     * @author Plugin Company
     */
    public function __construct() {
        parent::__construct();
        $this->setId('item_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('plugincompany_blacklist')->__('Fraud Suspicion'));
    }
    /**
     * before render html
     * @access protected
     * @return Plugincompany_Blacklist_Block_Adminhtml_Item_Edit_Tabs
     * @author Plugin Company
     */
    protected function _beforeToHtml(){
        $this->addTab('form_item', array(
            'label'        => Mage::helper('plugincompany_blacklist')->__('Fraud Suspicion Details'),
            'title'        => Mage::helper('plugincompany_blacklist')->__('Fraud Suspicion Details'),
            'content'     => $this->getLayout()->createBlock('plugincompany_blacklist/adminhtml_item_edit_tab_form')->toHtml(),
        ));

        $this->addTab('item_additional', array(
            'label'        => Mage::helper('plugincompany_blacklist')->__('Additional Information'),
            'title'        => Mage::helper('plugincompany_blacklist')->__('Additional Information'),
            'content'     => $this->getLayout()->createBlock('plugincompany_blacklist/adminhtml_item_edit_tab_additional')->toHtml(),
        ));
/*        if (!Mage::app()->isSingleStoreMode()){
            $this->addTab('form_store_item', array(
                'label'        => Mage::helper('plugincompany_blacklist')->__('Store views'),
                'title'        => Mage::helper('plugincompany_blacklist')->__('Store views'),
                'content'     => $this->getLayout()->createBlock('plugincompany_blacklist/adminhtml_item_edit_tab_stores')->toHtml(),
            ));
        }*/
        return parent::_beforeToHtml();
    }
    /**
     * Retrieve blacklist item entity
     * @access public
     * @return Plugincompany_Blacklist_Model_Item
     * @author Plugin Company
     */
    public function getItem(){
        return Mage::registry('current_item');
    }
}
