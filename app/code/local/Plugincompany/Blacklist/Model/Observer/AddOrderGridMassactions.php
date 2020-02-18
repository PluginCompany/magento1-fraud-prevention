<?php
/**
 * Copyright (c) 2014-2026 Plugin Company
 *
 * This file is part of Fraud Prevention, released under the MIT License.
 * See the LICENSE file in the project root.
 */
class Plugincompany_Blacklist_Model_Observer_addOrderGridMassactions
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
        $this->_addMassActionEntries();
        Mage::register('pc_fp_massactions_added', true);
    }
    
    protected function _canRun()
    {
        if( !$this->_alreadyRan()
            && $this->_isCorrectRequestPath()
            && $this->_isObservedBlockTheRightOne()
        ){
            return true;
        }
    }

    protected function _isObservedBlockTheRightOne()
    {
        return $this->_isObservedBlockMassAction();
    }
    
    protected function _isCorrectRequestPath()
    {
        if(Mage::registry('is_pc_fp_massaction_path') === null) {
            Mage::register(
                'is_pc_fp_massaction_path',
                in_array($this->getRequestPath(), array('sales_ordergrid','sales_orderindex'))
            );
        }
        return Mage::registry('is_pc_fp_massaction_path');
    }

    private function getRequestPath()
    {
        return Mage::app()->getRequest()->getControllerName() . Mage::app()->getRequest()->getActionName();
    }
    
    protected function _getBlock()
    {
        return $this->_observer->getEvent()->getBlock();
    }
    protected function _isObservedBlockMassAction()
    {
        return $this->_getBlock() instanceof Mage_Adminhtml_Block_Widget_Grid_Massaction;
    }

    protected function _alreadyRan(){
        return (bool)Mage::registry('pc_fp_massactions_added');
    }

    protected function _addMassActionEntries()
    {
        $massActionBlock = $this->_getBlock();
        $massActionBlock
            ->addItem('pc_fp_mark_suspicious', array(
                'label'=> Mage::helper('sales')->__('Mark Suspcicious (FP)'),
                'url'  => Mage::helper('adminhtml')->getUrl('*/blacklist_ordermassaction/addsuspicion'),
            ))
            ->addItem('pc_fp_remove_suspicion', array(
                'label'=> Mage::helper('sales')->__('Remove Suspicion (FP)'),
                'url'  => Mage::helper('adminhtml')->getUrl('*/blacklist_ordermassaction/removesuspicion'),
            ))
            ->addItem('pc_fp_whitelist_order', array(
                'label'=> Mage::helper('sales')->__('Whitelist Order (FP)'),
                'url'  => Mage::helper('adminhtml')->getUrl('*/blacklist_ordermassaction/whitelistorder'),
            ))
            ->addItem('pc_fp_whitelist_customer', array(
                'label'=> Mage::helper('sales')->__('Whitelist Customer (FP)'),
                'url'  => Mage::helper('adminhtml')->getUrl('*/blacklist_ordermassaction/whitelistorderandcustomer'),
            ))
        ;
    }

}