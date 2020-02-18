<?php
/**
 * Copyright (c) 2014-2026 Plugin Company
 *
 * This file is part of Fraud Prevention, released under the MIT License.
 * See the LICENSE file in the project root.
 */
class Plugincompany_Blacklist_Adminhtml_Blacklist_OrderController
    extends Mage_Adminhtml_Controller_Action
{
    
    protected function _getOrder()
    {
        $orderId = $this
            ->getRequest()
            ->getParam('order_id');
        if($orderId){
            return Mage::getModel('sales/order')->load($orderId);
        }
        return false;
    }
    
    public function whitelistOrderAndCustomerAction()
    {
        if($order = $this->_getOrder()){
            $whitelister = Mage::helper('plugincompany_blacklist/whitelister');
            $whitelister
                ->whitelistOrder($order)
                ->whitelistCustomerByOrder($order)
            ;
            Mage::getSingleton('admin/session')
                ->addSuccess(Mage::helper('plugincompany_blacklist')->__("Order and customer sucessfully whitelisted"));
        }
        $this->_redirect('adminhtml/sales_order/view', array('_current' => true));
    }
    
    public function whitelistOrderAction()
    {
        if($order = $this->_getOrder()){
            Mage::helper('plugincompany_blacklist/whitelister')->whitelistOrder($order);

            Mage::getSingleton('admin/session')
                ->addSuccess(Mage::helper('plugincompany_blacklist')->__("Order sucessfully whitelisted"));
        }
        $this->_redirect('adminhtml/sales_order/view', array('_current' => true));
    }
    /**
     * Check if admin has permissions to visit related pages
     * @access protected
     * @return boolean
     * @author Plugin Company
     */
    protected function _isAllowed() {
        return Mage::getSingleton('admin/session')->isAllowed('sales/plugincompany_blacklist/item');
    }
}