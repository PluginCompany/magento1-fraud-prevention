<?php
/**
 * Copyright (c) 2014-2026 Plugin Company
 *
 * This file is part of Fraud Prevention, released under the MIT License.
 * See the LICENSE file in the project root.
 */
?>
<?php
class Plugincompany_Blacklist_Block_Adminhtml_Sales_Order_Edit_Formwrapper extends Mage_Core_Block_Template {

    public function __construct()
    {
        $this->setTemplate('plugincompany/blacklist/formwrapper.phtml');

        return $this;
    }

    public function getFormHtml()
    {
        return $this->getLayout()->createBlock('plugincompany_blacklist/adminhtml_sales_order_edit_form')->toHtml();
    }

    public function getFormUrl()
    {
        $url = Mage::helper('adminhtml')->getUrl('adminhtml/blacklist_item/addByOrderId',array('_current'=>true));
        return $url;
    }

}