<?php
/**
 * Copyright (c) 2014-2026 Plugin Company
 *
 * This file is part of Fraud Prevention, released under the MIT License.
 * See the LICENSE file in the project root.
 */
?>
<?php
class Plugincompany_Blacklist_Model_Observer
{
    public function salesOrderSaveAfter(Varien_Event_Observer $observer)
    {
        $order = $observer->getEvent()->getOrder();

        $canBlacklist = Mage::getModel('plugincompany_blacklist/rule')->canBlacklistOrder($order);

        if ($canBlacklist && $order->getStatus() != 'blacklist') {
            //blacklist order
            $order->setStatus('blacklist');
            $order->addStatusToHistory('blacklist', 'Order automatically blacklisted due to blacklisting rule ' . $canBlacklist, false);
            $order->save();
            
            //blacklist customer
            if(Mage::helper('plugincompany_blacklist')->canBlacklistCustomerBasedOnOrder()
                && $order->getCustomerId()
            ){
                $customer = Mage::getModel('customer/customer')->load($order->getCustomerId());
                $customer
                    ->setPcBlacklisted(Plugincompany_Blacklist_Model_Attribute_Source_Customeraccount::BLACKLISTED)
                    ->save();
            }
        }elseif (!$canBlacklist && $order->getStatus() == 'blacklist') {
            //set last non-blacklisted status
            $collection = Mage::getModel('sales/order_status_history')->getCollection();
            $collection
                ->addFieldToFilter('entity_name', 'order')
                ->addFieldToFilter('parent_id', $order->getId())
                ->addFieldToFilter('status',array('neq'=>'blacklist'))
                ->setOrder('entity_id')
            ;
            $item = $collection->getFirstItem();
            $order->setStatus($item->getStatus());
            $order->addStatusToHistory($item->getStatus(), 'Blacklisting automatically removed and old status reverted', false);
            $order->save();
        }

        $this->disableSendEmailForOrderIfApplicable($order);
    }

    /**
     * @param Mage_Sales_Model_Order $order
     * @return Mage_Sales_Model_Order
     */
    private function disableSendEmailForOrderIfApplicable($order)
    {
        if($order->getStatus() == 'blacklist') {
            return $order->setCanSendNewEmailFlag(false);
        }
        return $order->setCanSendNewEmailFlag(true);
    }


    public function applyRenderer(Varien_Event_Observer $observer)
    {
        //find correct block
        if (!$observer->getEvent()->getBlock() instanceof Mage_Adminhtml_Block_Widget_Grid_Massaction) {
            return;
        }

        if(!$this->canAddFraudColumn()){
            return;
        }

        $orderGrid = $observer
            ->getEvent()
            ->getBlock()
            ->getParentBlock();
        
        $column = $orderGrid->getColumn('status');
        
        //apply renderer if column is present
        if($column){
            $column->setData('renderer','Plugincompany_Blacklist_Block_Adminhtml_Sales_Order_Grid_Renderer_Status');
        }
    }

    public function addOrderGridColumn(Varien_Event_Observer $observer)
    {
        if(Mage::registry('suspicion_column_added')){
            return false;
        }

        $block = $observer->getEvent()->getBlock();
        if (!$this->_isMassActionBlock($block) && !$this->_isOrderGridBlock($block)) {
            return;
        }

        if(!$this->canAddFraudColumn()) {
            return;
        }

        $gridBlock = $this->_getGridBlockFromEventBlock($block);

        $gridBlock->addColumnAfter('suspicion', array(
            'header' => Mage::helper('sales')->__('Marked'),
            'index' => 'marked',
            'renderer' => 'Plugincompany_Blacklist_Block_Adminhtml_Sales_Order_Grid_Renderer_Suspfraud',
            'order' => 1000000,
            'filter' => false,
            'sortable' => true,
            'width' => '17px'
        ),'status');
        
        Mage::register('suspicion_column_added', true);

        $gridBlock->sortColumnsByOrder();
    }

    protected function _isMassActionBlock($block)
    {
        return $block instanceof Mage_Adminhtml_Block_Widget_Grid_Massaction;
    }

    protected function _isOrderGridBlock($block)
    {
        return $block instanceof Mage_Adminhtml_Block_Sales_Order_Grid;
    }

    public function salesOrderGridCollectionLoadBefore(Varien_Event_Observer $observer)
    {
        if(Mage::registry('blacklist_column_joined')){
            return;
        }
        if(!$this->canAddFraudColumn()) {
            return;
        }
        $collection = $observer->getEvent()->getOrderGridCollection();
        $this->_fixSalesOrderCollectionWhereClause($collection);
        $collection
            ->getSelect()
            ->distinct(true)
            ->joinLeft(
                array('blacklist' => $this->_getBlacklistItemOrderSql()),
                'main_table.entity_id=blacklist.order_id',
                array(
                    'marked' => 'blacklist.entity_id'
                )
            );
        Mage::register('blacklist_column_joined', true);
    }

    /**
     * @param Mage_Sales_Model_Resource_Order_Grid_Collection $collection
     * @throws Zend_Db_Select_Exception
     */
    protected function _fixSalesOrderCollectionWhereClause($collection)
    {
        $select = $collection->getSelect();
        if ($where = $select->getPart('where')) {
            foreach ($where as $key=> $condition) {
                if (strpos($condition, 'created_at') !== false) {
                    $new_condition = str_replace(
                        array(
                            " `created_at`",
                            "(`created_at`"
                        ),
                        array(
                            " `main_table`.`created_at`",
                            "(`main_table`.`created_at`"
                        ),
                        $condition
                    );
                    $where[$key] = $new_condition;
                }
            }
            $select->setPart('where', $where);
        }
    }

    protected function _getBlacklistItemOrderSql()
    {
        $blacklistCollection =
            Mage::getModel('plugincompany_blacklist/item')
                ->getCollection()
                ->addFieldToFilter('order_id',array('neq'=> '0'))
        ;
        $blacklistCollection
            ->getSelect()
            ->group('order_id');
        return $blacklistCollection->getSelectSql();
    }

    protected function _getGridBlockFromEventBlock($block)
    {
        if($this->_isOrderGridBlock($block)){
            return $block;
        }
        return $block->getParentBlock();
    }

    public function addButtons(Varien_Event_Observer $observer)
    {
        //Sales Order View Button
            if ($observer->getEvent()->getBlock() instanceof Mage_Adminhtml_Block_Sales_Order_View)
            {
                $block = $observer->getEvent()->getBlock();
                $orderId = Mage::app()->getRequest()->getParam('order_id');

                $isSuspicious = Mage::getModel('plugincompany_blacklist/item')->loadByOrderId($orderId);
                
                if (!$isSuspicious) {
                    $block->addButton('mark_suspicious', array(
                        'label'     => Mage::helper('plugincompany_blacklist')->__('Mark Suspicious'),
                        'onclick'   => "showSuspicionOptionsWindow()"
                    ));
                }else{
                    $message = Mage::helper('plugincompany_blacklist')->__('Are you sure?');
                    $url = Mage::helper('adminhtml')->getUrl('adminhtml/blacklist_item/removeByOrderId',array('_current'=>true));
                    $block->addButton('remove_suspicion', array(
                        'label'     => Mage::helper('plugincompany_blacklist')->__('Remove Suspicion'),
                        'onclick'   => "confirmSetLocation('{$message}', '{$url}')"
                    ));
                }
                
                $order = Mage::registry('current_order');
                if($order->getStatus() == 'blacklist'){
                    //add order whitelist button
                    $message = Mage::helper('plugincompany_blacklist')->__('Are you sure? This will whitelist the current order and remove the blacklist status.');
                    $url = Mage::helper('adminhtml')->getUrl('adminhtml/blacklist_order/whitelistOrder',array('_current'=>true));
                    $block->addButton('whitelist_order', array(
                        'label'     => Mage::helper('plugincompany_blacklist')->__('Whitelist Order'),
                        'onclick'   => "confirmSetLocation('{$message}', '{$url}')",
                        "sort"      => 10000
                    ));
                    //add customer whitelist button
                    $message = Mage::helper('plugincompany_blacklist')->__('Are you sure? This will whitelist the current order and future orders from this customer.');
                    $url = Mage::helper('adminhtml')->getUrl('adminhtml/blacklist_order/whitelistOrderAndCustomer',array('_current'=>true));
                    $block->addButton('whitelist_order_customer', array(
                        'label'     => Mage::helper('plugincompany_blacklist')->__('Whitelist Customer'),
                        'onclick'   => "confirmSetLocation('{$message}', '{$url}')"
                    ));
                }
            }

    }
    
    public function hidePaymentMethodBlock(Varien_Event_Observer $observer)
    {
        if(!Mage::helper('plugincompany_blacklist')->canHidePaymentMethods()) return;
        
        if(!$observer->getEvent()->getBlock() instanceof Mage_Checkout_Block_Onepage_Payment_Methods){
            return;
        }
        
        $quote = Mage::getSingleton('checkout/session')->getQuote();

        $isBlacklisted = Mage::getModel('plugincompany_blacklist/rule')->canBlacklistOrder($quote);
        if(!$isBlacklisted) return;
        
        $transport = $observer->getEvent()->getTransport();
        $html = '<dl class="sp-methods" id="checkout-payment-method-load"><dt style="font-weight:normal">';
        $html .= Mage::helper('plugincompany_blacklist')->getPaymentBlockText();
        $html .= '</dt></dl>';
        $transport->setData('html', $html);
    }
    
    public function salesModelServiceQuoteSubmitFailure(Varien_Event_Observer $observer)
    {
        $quote = $observer->getEvent()->getQuote();
        $helper = Mage::helper('plugincompany_blacklist');
        
        if($helper->canBillingPaymentFailedStrike()){
            $addressData = $quote->getBillingAddress()->getData();
            $this->_addStrike($addressData, $quote);
        }
        
        if($helper->canShippingPaymentFailedStrike() && !$quote->getIsVirtual()){
            $addressData = $quote->getShippingAddress()->getData();
            $this->_addStrike($addressData, $quote);
        }
    }
    
    protected function _addOrderAttrToAddress(Array $address, $order)
    {
        $address['customer_ip'] = $this->getRemoteAddressFromOrder($order);
        return $address;
    }

    private function getRemoteAddressFromOrder($order)
    {
        if($order->getXForwardedFor()){
            return $order->getXForwardedFor();
        }
        return $order->getRemoteIp();
    }
    
    protected function _addStrike($addressData, $quote)
    {
        $addressData = $this->_addOrderAttrToAddress($addressData, $quote);
        $item = Mage::getModel('plugincompany_blacklist/item');
        $item
            ->setData($addressData)
            ->setEntityId(null)
            ->setComment("Marked suspicious due to failed payment transaction")
            ->save()
        ;
        return $this;
    }
    
    public function controllerActionPredispatch(Varien_Event_Observer $observer)
    {
        $session = Mage::getSingleton('customer/session');
        $cookie = Mage::getSingleton('core/cookie');
        $action = Mage::app()->getRequest()->getActionName();
        
        if($session->isLoggedIn()){
            $customer = $session->getCustomer();
            if($customer->getPcBlacklisted() == Plugincompany_Blacklist_Model_Attribute_Source_Customeraccount::BLACKLISTED){
                $cookie->set('blacklisted', 1 ,time()+86400,'/');
                $session->logout();
            }
        }
        
        if($session->isLoggedIn() || $action == 'loginPost'){
            $cookie->delete('blacklisted','/');
        }
        elseif($cookie->get('blacklisted','/')){
            $mCount = (int)$cookie->get('blacklisted_message_count','/');
            $cookie->set('blacklisted_message_count', $mCount + 1 ,time()+86400,'/');
            if($mCount < 2){
                Mage::getSingleton('core/session')->addError(Mage::helper('plugincompany_blacklist')->getAccountBlacklistError());
            }
        }
    }
    
    public function checkCustomerAddressQuote(Varien_Event_Observer $observer)
    {
        if(!Mage::helper('plugincompany_blacklist')->canBlacklistCustomerBasedOnAddress()) return;
        
        $quoteAddress = $observer->getEvent()->getQuoteAddress();
        $quote = $quoteAddress->getQuote();
        
        $isBlacklisted = Mage::getModel('plugincompany_blacklist/rule')->canBlacklistOrder($quote);
        if(!$isBlacklisted) return;
        
        $cookie = Mage::getSingleton('core/cookie');
        $session = Mage::getSingleton('customer/session');

        $cookie->set('blacklisted', 1 ,time()+86400,'/');
        
        if($session->isLoggedIn()) {
            $customer = $session->getCustomer();
            $customer
                ->setPcBlacklisted(Plugincompany_Blacklist_Model_Attribute_Source_Customeraccount::BLACKLISTED)
                ->save();
            $session->logout();
        }else{
            $session->logout();
            $session->clear();
            Mage::getSingleton('checkout/session')->clear();
        }
    }
    
    public function checkCustomerAddressAccount(Varien_Event_Observer $observer)
    {
        if(!Mage::helper('plugincompany_blacklist')->canBlacklistCustomerBasedOnAddress()) return;
        
        //prevent infinite loop
        if(Mage::registry('customer_blacklisted_now')){
            return;
        }

        $address = $observer->getEvent()->getCustomerAddress();
        $address
            ->setCustomerIp(Mage::helper('core/http')->getRemoteAddr());

        $isBlacklisted = Mage::getModel('plugincompany_blacklist/rule')->isAddressBlacklisted($address);
        if(!$isBlacklisted) return;

        $cookie = Mage::getSingleton('core/cookie');
        $session = Mage::getSingleton('customer/session');

        $customer = $session->getCustomer();
        
        $cookie->set('blacklisted', 1 ,time()+86400,'/');

        if($session->isLoggedIn()) {
            if(!Mage::registry('customer_blacklisted_now')){
                Mage::register('customer_blacklisted_now', true);
            }
            $customer
                ->setPcBlacklisted(Plugincompany_Blacklist_Model_Attribute_Source_Customeraccount::BLACKLISTED)
                ->save();
            $session->logout();
        }else{
            $session->logout();
            $session->clear();
            Mage::getSingleton('checkout/session')->clear();
        }
    }
    
    public function preventInfiniteLoop()
    {
        if (!Mage::registry('pc_blacklist_salesordersaveafter')) {
            Mage::register('pc_blacklist_salesordersaveafter', 1);
            return false;
        }elseif (Mage::registry('pc_blacklist_salesordersaveafter') == 2) {
            return true;
        }else{
            Mage::unregister('pc_blacklist_salesordersaveafter');
            Mage::register('pc_blacklist_salesordersaveafter', 2);
            return false;
        }
    }

    private function canAddFraudColumn()
    {
        //check request path
        $req = Mage::app()->getRequest()->getControllerName() . Mage::app()->getRequest()->getActionName();
        return in_array($req, array('sales_orderindex','sales_ordergrid'));
    }

}