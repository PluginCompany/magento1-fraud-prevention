<?php
/**
 * Copyright (c) 2014-2026 Plugin Company
 *
 * This file is part of Fraud Prevention, released under the MIT License.
 * See the LICENSE file in the project root.
 */
?>
<?php
class Plugincompany_Blacklist_Model_System_Config_Source_Addresstype
{
    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        return array(
            array('value' => 0, 'label'=>Mage::helper('adminhtml')->__('Choose when submitting fraud suspicion')),
            array('value' => 1, 'label'=>Mage::helper('adminhtml')->__('Shipping address')),
            array('value' => 2, 'label'=>Mage::helper('adminhtml')->__('Billing address')),
            array('value' => 3, 'label'=>Mage::helper('adminhtml')->__('Both shipping and billing address')),
        );
    }

    /**
     * Get options in "key-value" format
     *
     * @return array
     */
    public function toArray()
    {
        return array(
            0 => Mage::helper('adminhtml')->__('Choose when submitting suspicion'),
            1 => Mage::helper('adminhtml')->__('Mark shipping address'),
            2 => Mage::helper('adminhtml')->__('Mark billing address'),
            3 => Mage::helper('adminhtml')->__('Mark both shipping and billing address (will submit suspicion twice)'),
        );
    }

}