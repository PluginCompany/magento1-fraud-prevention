<?php
/**
 * Copyright (c) 2014-2026 Plugin Company
 *
 * This file is part of Fraud Prevention, released under the MIT License.
 * See the LICENSE file in the project root.
 */
/**
 * Blacklist Rule model
 *
 * @category    Plugincompany
 * @package     Plugincompany_Blacklist
 * @author      Plugin Company
 */
class Plugincompany_Blacklist_Model_Rule
    extends Mage_Core_Model_Abstract {
    /**
     * Entity code.
     * Can be used as part of method name for entity processing
     */
    const ENTITY    = 'plugincompany_blacklist_rule';
    const CACHE_TAG = 'plugincompany_blacklist_rule';
    /**
     * Prefix of model events names
     * @var string
     */
    protected $_eventPrefix = 'plugincompany_blacklist_rule';

    /**
     * Parameter name in event
     * @var string
     */
    protected $_eventObject = 'rule';
    /**
     * constructor
     * @access public
     * @return void
     * @author Plugin Company
     */
    public function _construct(){
        parent::_construct();
        $this->_init('plugincompany_blacklist/rule');
    }
    /**
     * before save blacklist rule
     * @access protected
     * @return Plugincompany_Blacklist_Model_Rule
     * @author Plugin Company
     */
    protected function _beforeSave(){
        parent::_beforeSave();
        $now = Mage::getSingleton('core/date')->gmtDate();
        if ($this->isObjectNew()){
            $this->setCreatedAt($now);
        }
        $this->setUpdatedAt($now);
        return $this;
    }
    /**
     * save rule relation
     * @access public
     * @return Plugincompany_Blacklist_Model_Rule
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
    
    protected function _isCustomerWhitelisted($customer)
    {
        if(
            !$customer 
            || $customer->getPcBlacklisted() != Plugincompany_Blacklist_Model_Attribute_Source_Customeraccount::WHITELISTED
        ){
            return false;
        }
        return true;
    }

    /*
     * Can pass either order or quote object
     */
    public function canBlacklistOrder($order)
    {
        $customer = Mage::getModel('customer/customer')->load($order->getCustomerId());
        if($this->_isCustomerWhitelisted($customer)){
            return false;
        }
        if($order->getWhitelisted()){
            return false;
        }
        
        $rules = $this->getCollection();
        $rules
            ->addStoreFilter($order->getStoreId())
            ->addIsActiveFilter()
        ;
        
        $groupId = $order->getCustomerGroupId();
        $rules->addCustomerGroupFilter($groupId);

        $billingAddress = $order->getBillingAddress()->getData();
        $billingAddress = $this->_addOrderAttrToAddress($billingAddress, $order);

        if ($order->getShippingAddress()) {
            $shippingAddress = $order->getShippingAddress()->getData();
            $shippingAddress = $this->_addOrderAttrToAddress($shippingAddress , $order);
       }else{
            $shippingAddress = false;
        }

        foreach ($rules as $rule) {
            $attributes = explode(',', $rule->getAttributes());
            if ($this->checkRuleTrigger($attributes, $billingAddress, $rule->getStrikes())
              || $this->checkRuleTrigger($attributes, $shippingAddress, $rule->getStrikes())){
                return $rule->getId();
            }
        }
    }
    
    /*
     * Check if address is blacklisted
     * customer_ip can optionally be added to address object beforehand
     */
    public function isAddressBlacklisted(Mage_Customer_Model_Address $address)
    {
        $customer = $address->getCustomer();
        
        if($this->_isCustomerWhitelisted($customer)){
            return false;
        }
        
        $rules = $this->getCollection();
        $rules
            ->addStoreFilter($customer->getStoreId())
            ->addIsActiveFilter()
        ;

        $groupId = $customer->getGroupId();
        $rules->addCustomerGroupFilter($groupId);

        $addressData = $address->getData();
        if(!isset($addressData['email'])){
            $addressData['email'] = $customer->getEmail();
        }
        $addressData['email_domain'] = Mage::helper('plugincompany_blacklist')->getEmailDomain($addressData['email']);

        foreach ($rules as $rule) {
            $attributes = explode(',', $rule->getAttributes());
            if ($this->checkRuleTrigger($attributes, $addressData, $rule->getStrikes())){
                return $rule->getId();
            }
        }
        
    }

    public function checkRuleTrigger($attributes, $address, $strikes)
    {
        if (!$address) {
            return false;
        }

        $attributes = $this->removeEmailDomainFromAttributesIfExcluded($attributes, $address);
        if(empty($attributes)) {
            return false;
        }

        //if customer is guest and rule is based on customer ID
        // rule should not be triggered
        $customerId = isset($address['customer_id']) ? $address['customer_id'] : null;
        if(!$customerId && in_array('customer_id', $attributes)){
            return false;
        }

        $collection = Mage::getModel('plugincompany_blacklist/item')->getCollection();
        foreach ($attributes as $code) {
            $attrVal = isset($address[$code]) ? $address[$code] : null;
            $collection->addFieldToFilter($code, 
                array(
                    array('eq' => $attrVal),
                    array('null' => true)
                )
            );
        }
        
        //remove all results of which all attributes are NULL
        foreach($collection as $k => $item){
            if($this->_isAllNull($item,$attributes)){
                $collection->removeItemByKey($k);
            }
        }

        if ($collection->count() >= $strikes) {
            return true;
        }
    }

    private function removeEmailDomainFromAttributesIfExcluded($attributes, $address)
    {
        if(empty($address)) {
            return $attributes;
        }
        if(!in_array('email_domain', $attributes)) {
            return $attributes;
        }
        if(in_array($address['email_domain'], $this->getExcludedEmailDomains())) {
            return array_diff($attributes, array('email_domain'));
        }
        return $attributes;
    }

    private function getExcludedEmailDomains()
    {
        return Mage::helper('plugincompany_blacklist')->getExcludedEmailDomains();
    }

     protected function _isAllNull($item, $attributes){
         $hasValue = array_filter(array_intersect_key($item->getData(),array_flip($attributes)));
         if(empty($hasValue)){
             return true;
         }
     }
    
    protected function _addOrderAttrToAddress(Array $address, $order)
    {
        $address['customer_ip'] = Mage::helper('plugincompany_blacklist')->getRemoteAddressFromOrder($order);
        if(isset($address['email'])) {
            $address['email_domain'] = Mage::helper('plugincompany_blacklist')->getEmailDomain($address['email']);
        }
        return $address;
    }
    
    
}
