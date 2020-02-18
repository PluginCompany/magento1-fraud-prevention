<?php
/**
 * Copyright (c) 2014-2026 Plugin Company
 *
 * This file is part of Fraud Prevention, released under the MIT License.
 * See the LICENSE file in the project root.
 */
?>
<?php
class Plugincompany_Blacklist_Block_Adminhtml_Item_Renderer_Order extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
    public function render(Varien_Object $row)
    {
        $value =  $row->getData($this->getColumn()->getIndex());

        if ($value == 0) {
            return "";
        }

        $url = Mage::helper('adminhtml')->getUrl('adminhtml/sales_order/view', array('order_id' => $value));

        $value = Mage::getModel('sales/order')->load($value)->getIncrementId();

        $html = "<a href='$url'>$value</a>";

        return $html;

    }
}