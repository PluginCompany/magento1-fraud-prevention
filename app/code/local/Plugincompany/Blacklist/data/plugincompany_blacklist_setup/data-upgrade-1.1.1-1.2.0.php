<?php
/**
 * Copyright (c) 2014-2026 Plugin Company
 *
 * This file is part of Fraud Prevention, released under the MIT License.
 * See the LICENSE file in the project root.
 */
/**
 * Blacklist module upgrade script
 *
 * @category    Plugincompany
 * @package     Plugincompany_Blacklist
 * @author      Plugin Company
 */
$this->startSetup();

$fraudItems = Mage::getModel('plugincompany_blacklist/item')
    ->getCollection()
    ->addFieldToFilter('order_id',array('gt'=>0))
;

$order = Mage::getModel('sales/order');
if ($fraudItems->getSize() > 0) {
    foreach ($fraudItems as $fraudItem) {
        $order->load($fraudItem->getOrderId());
        $realRemoteAddress = Mage::helper('plugincompany_blacklist')->getRemoteAddressFromOrder($order);
        if(
            $realRemoteAddress != $fraudItem->getCustomerIp()
            && $order->getRemoteIp() == $fraudItem->getCustomerIp()
        ){
            $fraudItem
                ->setCustomerIp($realRemoteAddress)
                ->save();
        }
    }
}
$this->endSetup();
