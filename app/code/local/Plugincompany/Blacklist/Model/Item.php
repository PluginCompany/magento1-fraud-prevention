<?php
/**
 * Copyright (c) 2014-2026 Plugin Company
 *
 * This file is part of Fraud Prevention, released under the MIT License.
 * See the LICENSE file in the project root.
 */
/**
 * Blacklist Item model
 *
 * @category    Plugincompany
 * @package     Plugincompany_Blacklist
 * @author      Plugin Company
 */
class Plugincompany_Blacklist_Model_Item
    extends Mage_Core_Model_Abstract {
    /**
     * Entity code.
     * Can be used as part of method name for entity processing
     */
    const ENTITY    = 'plugincompany_blacklist_item';
    const CACHE_TAG = 'plugincompany_blacklist_item';
    /**
     * Prefix of model events names
     * @var string
     */
    protected $_eventPrefix = 'plugincompany_blacklist_item';

    /**
     * Parameter name in event
     * @var string
     */
    protected $_eventObject = 'item';
    /**
     * constructor
     * @access public
     * @return void
     * @author Plugin Company
     */
    public function _construct(){
        parent::_construct();
        $this->_init('plugincompany_blacklist/item');
    }
    /**
     * before save blacklist item
     * @access protected
     * @return Plugincompany_Blacklist_Model_Item
     * @author Plugin Company
     */
    protected function _beforeSave(){
        parent::_beforeSave();
        $now = Mage::getSingleton('core/date')->gmtDate();
        if ($this->isObjectNew()){
            $this->setCreatedAt($now);
        }
        $this->setUpdatedAt($now);
        
        //add e-mail domain
        $this->setEmailDomain(Mage::helper('plugincompany_blacklist')->getEmailDomain($this->getEmail()));
        
        return $this;
    }
    /**
     * save item relation
     * @access public
     * @return Plugincompany_Blacklist_Model_Item
     * @author Plugin Company
     */
    protected function _afterSave() {
        return parent::_afterSave();
    }
    /**
     * get default values
     * @access public
     * @return array
     * @author Plugin Company
     */
    public function getDefaultValues() {
        $values = array();
        $values['status'] = 1;
        return $values;
    }

    public function loadByOrderId($orderId)
    {
        if (!$orderId) {
            return false;
        }

        $collection = $this->getCollection();
        $collection
            ->addFieldToFilter('order_id', $orderId);

        if ($collection->count()) {
            return $collection;
        }
        return false;
    }

    public function addByOrderId($orderId,$adminId = null, $comment = null,$addressType = null)
    {
        if ($this->loadByOrderId($orderId)) {
           throw new Exception('Order already marked as suspicious');
        }

        $order = Mage::getModel('sales/order')->load($orderId);

        if (!$addressType) {
            switch(Mage::getStoreConfig('plugincompany_blacklist/plugincompany_blacklist_group/address_type',$order->getStore())){
                case 1:
                    $addressType = 'shipping';
                    break;
                case 2:
                    $addressType = 'billing';
                    break;
                case 3:
                    $addressType = 'both';
                    break;
                case 0:
                    throw new Exception('No address type selected to blacklist');
            }
        }

        $addressData = array();
        if ($order->getShippingAddress() && ($addressType == 'shipping' || $addressType == 'both')) {
            $addressData[] = $order->getShippingAddress()->getData();
        }
        if ($addressType == 'billing' || $addressType == 'both') {
            $addressData[] = $order->getBillingAddress()->getData();
        }


        foreach ($addressData as $address) {
            if(empty($address['customer_id'])) {
                $address['customer_id'] = $order->getCustomerId();
            }
            $this
                ->setData($address)
                ->setCustomerIp(
                    Mage::helper('plugincompany_blacklist')->getRemoteAddressFromOrder($order)
                )
                ->setEntityId(null)
                ->setMarkedByAdmin($adminId)
                ->setOrderId($orderId)
                ->setComment($comment)
                ->save()
            ;
        }

        //save order to allow aftersave event, this will cause order to be blacklisted
        $order
            ->setNothing(true)
            ->save();

        return $this;
    }

    public function removeByOrderId($orderId)
    {
        if ($collection = $this->loadByOrderId($orderId)) {
            foreach ($collection as $item) {
                $item->delete();
            }
        }else{
            throw new Exception('Order is not marked as suspicious');
        }

        return $this;
    }
    
}
