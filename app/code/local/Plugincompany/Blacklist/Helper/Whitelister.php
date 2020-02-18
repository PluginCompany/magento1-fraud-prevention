<?php
/**
 * Copyright (c) 2014-2026 Plugin Company
 *
 * This file is part of Fraud Prevention, released under the MIT License.
 * See the LICENSE file in the project root.
 */
class Plugincompany_Blacklist_Helper_Whitelister extends Mage_Core_Helper_Abstract
{
    /**
     * @param Mage_Sales_Model_Order $order
     * @return $this
     * @throws Exception
     */
    public function whitelistOrder($order)
    {
        $collection = Mage::getModel('sales/order_status_history')->getCollection();
        $collection
            ->addFieldToFilter('entity_name', 'order')
            ->addFieldToFilter('parent_id', $order->getId())
            ->addFieldToFilter('status',array('neq'=>'blacklist'))
            ->setOrder('entity_id')
        ;
        $item = $collection->getFirstItem();
        $order
            ->setStatus($item->getStatus())
            ->addStatusToHistory($item->getStatus(), 'Order whitelisted by admin', false)
            ->setWhitelisted(true)
            ->save()
        ;
        return $this;
    }

    /**
     * @param Mage_Sales_Model_Order $order
     * @return $this
     */
    public function whitelistCustomerByOrder($order)
    {
        $customerId = $order->getCustomerId();
        if(!$customerId) return $order;

        $customer = Mage::getModel('customer/customer')->load($customerId);
        if($customer && $customer->getId()){
            $customer
                ->setPcBlacklisted(Plugincompany_Blacklist_Model_Attribute_Source_Customeraccount::WHITELISTED)
                ->save();
        }
        return $this;
    }

}
