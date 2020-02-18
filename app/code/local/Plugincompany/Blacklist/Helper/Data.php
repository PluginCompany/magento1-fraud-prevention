<?php
/**
 * Copyright (c) 2014-2026 Plugin Company
 *
 * This file is part of Fraud Prevention, released under the MIT License.
 * See the LICENSE file in the project root.
 */
/**
 * Blacklist default helper
 *
 * @category    Plugincompany
 * @package     Plugincompany_Blacklist
 * @author      Plugin Company
 */
class Plugincompany_Blacklist_Helper_Data
    extends Mage_Core_Helper_Abstract {
    /**
     * convert array to options
     * @access public
     * @param $options
     * @return array
     * @author Plugin Company
     */
    public function convertOptions($options){
        $converted = array();
        foreach ($options as $option){
            if (isset($option['value']) && !is_array($option['value']) && isset($option['label']) && !is_array($option['label'])){
                $converted[$option['value']] = $option['label'];
            }
        }
        return $converted;
    }

    public function getEmailDomain($email)
    {
        $email = explode('@',$email);
        if(isset($email[1])){
            return $email[1];
        }
        return null;
    }

    /**
     * @param Mage_Sales_Model_Order $order
     * @return $this
     */
    public function getRemoteAddressFromOrder($order)
    {
        if($order->getXForwardedFor()){
            return trim(explode(',', $order->getXForwardedFor())[0]);
        }
        return $order->getRemoteIp();
    }
    
    public function canHidePaymentMethods()
    {
        return Mage::getStoreConfigFlag('plugincompany_blacklist/plugincompany_blacklist_payment/enable');
    }

    public function getPaymentBlockText()
    {
        return Mage::getStoreConfig('plugincompany_blacklist/plugincompany_blacklist_payment/text');
    }

    public function getAccountBlacklistError()
    {
        return Mage::getStoreConfig('plugincompany_blacklist/plugincompany_blacklist_customeraccount/message');
    }
    
    public function canBillingPaymentFailedStrike()
    {
        return Mage::getStoreConfigFlag('plugincompany_blacklist/plugincompany_blacklist_paymentfailed/billing');
    }
    
    public function canShippingPaymentFailedStrike()
    {
        return Mage::getStoreConfigFlag('plugincompany_blacklist/plugincompany_blacklist_paymentfailed/shipping');
    }
    
    public function canBlacklistCustomerBasedOnOrder()
    {
        return Mage::getStoreConfigFlag('plugincompany_blacklist/plugincompany_blacklist_customeraccount/afterorder');
    }
    
    public function canBlacklistCustomerBasedOnAddress()
    {
        return Mage::getStoreConfigFlag('plugincompany_blacklist/plugincompany_blacklist_customeraccount/addresssave');
    }
    
    public function canClearSessionBasedOnAddress()
    {
        return Mage::getStoreConfigFlag('plugincompany_blacklist/plugincompany_blacklist_customeraccount/afterorder');
    }

    public function getExcludedEmailDomains()
    {
        return explode("\n", Mage::getStoreConfig('plugincompany_blacklist/field_matching/excluded_email_domains'));
    }


}
