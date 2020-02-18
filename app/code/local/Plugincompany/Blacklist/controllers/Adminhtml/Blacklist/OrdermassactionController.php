<?php
/**
 * Copyright (c) 2014-2026 Plugin Company
 *
 * This file is part of Fraud Prevention, released under the MIT License.
 * See the LICENSE file in the project root.
 */
class Plugincompany_Blacklist_Adminhtml_Blacklist_OrdermassactionController
    extends Mage_Adminhtml_Controller_Action
{

    public function addsuspicionAction()
    {
        $orderIds = $this->getOrderIds();

        $successCount = 0;
        $errorCount = 0;

        foreach($orderIds as $orderId) {
            try {
                $this->addSuspicionByOrderId($orderId);
                $successCount++;
            }
            catch (Exception $e) {
                $errorCount++;
            }
        }

        if ($successCount) {
            $this->_getSession()->addSuccess(
                $this->__("Successfully marked %s order(s) as suspicious.", $successCount)
            );
        }

        if ($errorCount) {
            $this->_getSession()->addNotice(
                $this->__("Failed to mark %s order(s) as suspicious. Maybe they are already marked?", $errorCount)
            );
        }

        $this->redirectToOrderGrid();
    }


    /**
     * @param int|string $orderId
     * @return $this
     * @throws Exception
     */
    private function addSuspicionByOrderId($orderId)
    {
        Mage::getModel('plugincompany_blacklist/item')
            ->addByOrderId(
                $orderId,
                $this->getAdminId(),
                $this->__("Fraud suspicion added by order grid mass action.")
            );
        $this->saveOrderByIdWithStatusMessage(
            $orderId,
            $this->__('Fraud suspicion added through mass action')
        );
        return $this;
    }

    public function removesuspicionAction()
    {
        $orderIds = $this->getOrderIds();

        $successCount = 0;
        $errorCount = 0;

        foreach($orderIds as $orderId) {
            try {
                $this->removeSuspicionByOrderId($orderId);
                $successCount++;
            }
            catch (Exception $e) {
                $errorCount++;
            }
        }

        if ($successCount) {
            $this->_getSession()->addSuccess(
                $this->__("Successfully removed %s fraud suspicion record(s).", $successCount)
            );
        }

        if ($errorCount) {
            $this->_getSession()->addNotice(
                $this->__("Failed to remove %s fraud suspicion record(s). Maybe the corresponding orders aren't marked?", $errorCount)
            );
        }

        $this->redirectToOrderGrid();
    }

    /**
     * @param int|string $orderId
     * @return $this
     * @throws Exception
     */
    private function removeSuspicionByOrderId($orderId)
    {
        Mage::getModel('plugincompany_blacklist/item')->removeByOrderId($orderId);
        $this->saveOrderByIdWithStatusMessage(
            $orderId,
            $this->__('Fraud suspicion record removed through mass action')
        );
        return $this;
    }

    /**
     * @return array
     */
    private function getOrderIds()
    {
        $request = $this->getRequest();
        $ids = $request->getParam('order_ids');
        if(empty($ids)) {
            $ids = [];
        }
        return $ids;
    }

    private function getAdminId()
    {
        return Mage::getSingleton('admin/session')->getUser()->getUserId();
    }

    /**
     * @param int|string $orderId
     * @param string $message
     * @return $this
     * @throws Exception
     */
    private function saveOrderByIdWithStatusMessage($orderId, $message)
    {
        $order = Mage::getModel('sales/order')->load($orderId);
        $order->addStatusToHistory($order->getStatus(), $message, false);
        $order->save();
        return $this;
    }

    public function whitelistorderAction()
    {
        $orderIds = $this->getOrderIds();

        $successCount = 0;
        $errorCount = 0;

        foreach($orderIds as $orderId) {
            try {
                $this->whitelistOrderById($orderId);
                $successCount++;
            }
            catch (Exception $e) {
                $errorCount++;
            }
        }

        if ($successCount) {
            $this->_getSession()->addSuccess(
                $this->__("Successfully whitelisted %s order(s).", $successCount)
            );
        }

        if ($errorCount) {
            $this->_getSession()->addNotice(
                $this->__("Failed to whitelist %s orders(s). Maybe these orders are already whitelisted?", $errorCount)
            );
        }

        $this->redirectToOrderGrid();
    }

    /**
     * @param $orderId
     * @throws Exception
     */
    private function whitelistOrderById($orderId)
    {
        $order = Mage::getModel('sales/order')->load($orderId);
        if($order->getWhitelisted()) {
            throw new Exception('Order already whitelisted');
        }
        Mage::helper('plugincompany_blacklist/whitelister')->whitelistOrder($order);
    }

    public function whitelistorderandcustomerAction()
    {
        $orderIds = $this->getOrderIds();

        $successCount = 0;
        $errorCount = 0;

        foreach($orderIds as $orderId) {
            try {
                $this->whitelistOrderAndCustomerByOrderId($orderId);
                $successCount++;
            }
            catch (Exception $e) {
                $errorCount++;
            }
        }

        if ($successCount) {
            $this->_getSession()->addSuccess(
                $this->__("Successfully whitelisted %s customer(s) and related orders.", $successCount)
            );
        }

        if ($errorCount) {
            $this->_getSession()->addError(
                $this->__("Failed to whitelist %s customers(s). ", $errorCount)
            );
        }

        $this->redirectToOrderGrid();
    }

    /**
     * @param $orderId
     * @throws Exception
     */
    private function whitelistOrderAndCustomerByOrderId($orderId)
    {
        $order = Mage::getModel('sales/order')->load($orderId);
        Mage::helper('plugincompany_blacklist/whitelister')
            ->whitelistOrder($order)
            ->whitelistCustomerByOrder($order)
        ;
    }

    private function redirectToOrderGrid()
    {
        return $this->_redirect('*/sales_order/index');
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