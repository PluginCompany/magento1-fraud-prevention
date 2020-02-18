<?php
/**
 * Copyright (c) 2014-2026 Plugin Company
 *
 * This file is part of Fraud Prevention, released under the MIT License.
 * See the LICENSE file in the project root.
 */
class Plugincompany_Blacklist_Model_Observer_AddCustomerGridColumn
{
    
    protected $_observer;
    
    public function execute(Varien_Event_Observer $observer)
    {
        $this->_observer = $observer;
        
        try{
            $this->_execute();
        }catch(Exception $e){
            Mage::logException($e);
        }
    }
    
    protected function _execute()
    {
        if(!$this->_canRun()) return;
        $this->_addFraudStatusColumn();
    }
    
    protected function _canRun()
    {
        if( !$this->_alreadyRan() 
            && $this->_isObservedBlockTheRightOne()
            && $this->_isCorrectRequestPath() 
        ){
            return true;
        }
    }

    protected function _isObservedBlockTheRightOne()
    {
        if($this->_isObservedBlockMassAction()){
            return true;
        }
        if($this->_isObservedBlockGrid()){
            return true;
        }
    }
    
    protected function _isCorrectRequestPath()
    {
        $req = Mage::app()->getRequest()->getControllerName() . Mage::app()->getRequest()->getActionName();
        if (in_array($req,array('customerindex','customergrid'))) {
            return true;
        }
    }
    
    protected function _getBlock()
    {
        return $this->_observer->getEvent()->getBlock();
    }
    protected function _isObservedBlockMassAction()
    {
        return $this->_getBlock() instanceof Mage_Adminhtml_Block_Widget_Grid_Massaction;
    }

    protected function _isObservedBlockGrid()
    {
        return $this->_getBlock() instanceof Mage_Adminhtml_Block_Customer_Grid;
    }

    protected function _getGridBlockFromEventBlock()
    {
        if($this->_isObservedBlockGrid()){
            return $this->_getBlock();
        }
        return $this->_getBlock()->getParentBlock();
    }
    
    protected function _alreadyRan(){
        if(Mage::registry('fraud_status_column_added')){
            return true;
        }
    }
    
    protected function _addFraudStatusColumn()
    {
        $gridBlock = $this->_getGridBlockFromEventBlock();

        $gridBlock->addColumnAfter('fraud_status', array(
            'header' => Mage::helper('plugincompany_blacklist')->__('Fraud Status'),
            'index' => 'pc_blacklisted',
            'order' => 1000000,
            'filter' => false,
            'sortable' => true,
            'type' => 'options',
            'width' => '100px',
            'renderer' => 'Plugincompany_Blacklist_Block_Adminhtml_Customer_Grid_Renderer_Fraud',
            'options' => Mage::getModel('plugincompany_blacklist/attribute_source_customeraccount')->getGridOptions(),
            'filter_condition_callback' => array($this, '_filterOnFraudStatus')
        ),'website_id');

        Mage::register('fraud_status_column_added', true);

        $gridBlock->sortColumnsByOrder();
    }

    public function updateCollection(Varien_Event_Observer $observer){
        if(!$this->_canUpdateCollection()) return;
        $this->_observer = $observer;
        
        try{
            $this->_updateCollection();
        }catch(Exception $e){
            Mage::logException($e);
        }
    }
    
    protected function _canUpdateCollection()
    {
        if($this->_isCorrectRequestPath() 
            && !Mage::registry('customer_collection_fraud_column_added'))
        {
            return true;
        }
    }
    
    protected function _updateCollection()
    {
        Mage::register('customer_collection_fraud_column_added',true);
        $this->_getObservedCollection()
            ->addAttributeToSelect('pc_blacklisted');
        return $this;
    }
    
    protected function _getObservedCollection()
    {
        return $this->_observer->getEvent()->getCollection();
    }
}