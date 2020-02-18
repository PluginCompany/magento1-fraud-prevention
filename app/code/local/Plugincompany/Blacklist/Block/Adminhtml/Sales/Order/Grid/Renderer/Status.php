<?php
/**
 * Copyright (c) 2014-2026 Plugin Company
 *
 * This file is part of Fraud Prevention, released under the MIT License.
 * See the LICENSE file in the project root.
 */
?>
<?php
class Plugincompany_Blacklist_Block_Adminhtml_Sales_Order_Grid_Renderer_Status extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
    public function render(Varien_Object $row)
    {
        $value =  $row->getData($this->getColumn()->getIndex());
        $statuses = Mage::getSingleton('sales/order_config')->getStatuses();


        if ($value == 'blacklist') {
            return '<span style="color:red;font-weight:bold">' . $statuses[$value] . '</span>';
        }

        return $statuses[$value];
    }
}