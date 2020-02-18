<?php
/**
 * Copyright (c) 2014-2026 Plugin Company
 *
 * This file is part of Fraud Prevention, released under the MIT License.
 * See the LICENSE file in the project root.
 */
class Plugincompany_Blacklist_Block_Adminhtml_Sales_Order_Edit_Form extends Mage_Core_Block_Template {

    public function __construct()
    {
        $this->setTemplate('plugincompany/blacklist/optionform.phtml');
        return $this;
    }

    public function canShowComment()
    {
        $showComment = Mage::getStoreConfig('plugincompany_blacklist/plugincompany_blacklist_group/show_comment');
        return $showComment;
    }

    public function canShowAddress()
    {
        $addresType = Mage::getStoreConfig('plugincompany_blacklist/plugincompany_blacklist_group/address_type');
        if ($addresType == 0) {
            $showAddress = true;
        }else{
            $showAddress = false;
        }

        return $showAddress;
    }

    public function canShowShipping()
    {
        $order = Mage::registry('current_order');
        if (!$order->getIsVirtual()) {
            return true;
        }
        return false;
    }

    public function getFormUrl()
    {
        $url = Mage::helper('adminhtml')->getUrl('adminhtml/blacklist_item/addByOrderId',array('_current'=>true));
        return $url;
    }

}