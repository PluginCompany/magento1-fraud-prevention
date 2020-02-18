<?php
/**
 * Copyright (c) 2014-2026 Plugin Company
 *
 * This file is part of Fraud Prevention, released under the MIT License.
 * See the LICENSE file in the project root.
 */
/**
 * country attribute source model
 *
 * @category    Plugincompany
 * @package     Plugincompany_Blacklist
 * @author      Plugin Company
 */
class Plugincompany_Blacklist_Model_Attribute_Source_Customeraccount
    extends Mage_Eav_Model_Entity_Attribute_Source_Abstract {
    
    const NEUTRAL = 0;
    const WHITELISTED = 1;
    const BLACKLISTED = 2;
    /**
     * Customer account status
     * @access public
     * @return mixed
     * @author Plugin Company
     */
    public function getAllOptions()
    {
        if (is_null($this->_options)) {
            $this->_options = array(
                array(
                    'label' => Mage::helper('plugincompany_blacklist')->__('Neutral'),
                    'value' => self::NEUTRAL 
                ),
                array(
                    'label' => Mage::helper('plugincompany_blacklist')->__('Whitelisted'),
                    'value' => self::WHITELISTED
                ),
                array(
                    'label' => Mage::helper('plugincompany_blacklist')->__('Blacklisted'),
                    'value' => self::BLACKLISTED
                ),
            );
        }
        return $this->_options;
    }

    public function toOptionArray()
    {
        return $this->getAllOptions();
    }
    
    public function getGridOptions()
    {
        $options = array();
        foreach($this->getAllOptions() as $option){
            $options[$option['value']] = $option['label'];
        }
        return $options;
    }
}
