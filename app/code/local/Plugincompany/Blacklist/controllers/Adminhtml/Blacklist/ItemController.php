<?php
/**
 * Copyright (c) 2014-2026 Plugin Company
 *
 * This file is part of Fraud Prevention, released under the MIT License.
 * See the LICENSE file in the project root.
 */
/**
 * Fraud Suspicion admin controller
 *
 * @category    Plugincompany
 * @package     Plugincompany_Blacklist
 * @author      Plugin Company
 */
class Plugincompany_Blacklist_Adminhtml_Blacklist_ItemController
    extends Plugincompany_Blacklist_Controller_Adminhtml_Blacklist {
    /**
     * init the item
     * @access protected
     * @return Plugincompany_Blacklist_Model_Item
     */
    protected function _initItem(){
        $itemId  = (int) $this->getRequest()->getParam('id');
        $item    = Mage::getModel('plugincompany_blacklist/item');
        if ($itemId) {
            $item->load($itemId);
        }
        Mage::register('current_item', $item);
        return $item;
    }
    /**
     * default action
     * @access public
     * @return void
     * @author Plugin Company
     */
    public function indexAction() {
        $this->loadLayout();
        $this->_title(Mage::helper('plugincompany_blacklist')->__('Fraud Prevention'))
            ->_title(Mage::helper('plugincompany_blacklist')->__('Fraud Suspicions'));
        $this->renderLayout();
    }
    /**
     * grid action
     * @access public
     * @return void
     * @author Plugin Company
     */
    public function gridAction() {
        $this->loadLayout()->renderLayout();
    }
    /**
     * edit blacklist item - action
     * @access public
     * @return void
     * @author Plugin Company
     */
    public function editAction() {
        $itemId    = $this->getRequest()->getParam('id');
        $item      = $this->_initItem();
        if ($itemId && !$item->getId()) {
            $this->_getSession()->addError(Mage::helper('plugincompany_blacklist')->__('This fraud suspicion no longer exists.'));
            $this->_redirect('*/*/');
            return;
        }
        $data = Mage::getSingleton('adminhtml/session')->getItemData(true);
        if (!empty($data)) {
            $item->setData($data);
        }
        Mage::register('item_data', $item);
        $this->loadLayout();
        $this->_title(Mage::helper('plugincompany_blacklist')->__('Fraud Prevention'))
            ->_title(Mage::helper('plugincompany_blacklist')->__('Fraud Suspicions'));
        if ($item->getId()){
            $this->_title($item->getId());
        }
        else{
            $this->_title(Mage::helper('plugincompany_blacklist')->__('Add Fraud Suspicion'));
        }
        if (Mage::getSingleton('cms/wysiwyg_config')->isEnabled()) {
            $this->getLayout()->getBlock('head')->setCanLoadTinyMce(true);
        }
        $this->renderLayout();
    }
    /**
     * new blacklist item action
     * @access public
     * @return void
     * @author Plugin Company
     */
    public function newAction() {
        $this->_forward('edit');
    }
    /**
     * save blacklist item - action
     * @access public
     * @return void
     * @author Plugin Company
     */
    public function saveAction() {
        if ($data = $this->getRequest()->getPost('item')) {
            try {
                if (empty($data['marked_by_admin'])) {
                    $data['marked_by_admin'] = Mage::getSingleton('admin/session')->getUser()->getUserId();
                }
                $item = $this->_initItem();
                $item->addData($data);
                $item->save();
                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('plugincompany_blacklist')->__('Fraud suspicion was successfully saved.'));
                Mage::getSingleton('adminhtml/session')->setFormData(false);
                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('*/*/edit', array('id' => $item->getId()));
                    return;
                }
                $this->_redirect('*/*/');
                return;
            }
            catch (Mage_Core_Exception $e){
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                Mage::getSingleton('adminhtml/session')->setItemData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            }
            catch (Exception $e) {
                Mage::logException($e);
                Mage::getSingleton('adminhtml/session')->addError(Mage::helper('plugincompany_blacklist')->__('There was a problem saving the fraud suspicion.'));
                Mage::getSingleton('adminhtml/session')->setItemData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(Mage::helper('plugincompany_blacklist')->__('Unable to find fraud suspicion to save.'));
        $this->_redirect('*/*/');
    }
    /**
     * delete blacklist item - action
     * @access public
     * @return void
     * @author Plugin Company
     */
    public function deleteAction() {
        if( $this->getRequest()->getParam('id') > 0) {
            try {
                $item = Mage::getModel('plugincompany_blacklist/item');
                $item->setId($this->getRequest()->getParam('id'))->delete();
                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('plugincompany_blacklist')->__('Fraud suspicion was successfully deleted.'));
                $this->_redirect('*/*/');
                return;
            }
            catch (Mage_Core_Exception $e){
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
            }
            catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(Mage::helper('plugincompany_blacklist')->__('There was an error deleting blacklisted item.'));
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                Mage::logException($e);
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(Mage::helper('plugincompany_blacklist')->__('Could not find blacklisted item to delete.'));
        $this->_redirect('*/*/');
    }
    /**
     * mass delete blacklist item - action
     * @access public
     * @return void
     * @author Plugin Company
     */
    public function massDeleteAction() {
        $itemIds = $this->getRequest()->getParam('item');
        if(!is_array($itemIds)) {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('plugincompany_blacklist')->__('Please select blacklisted items to delete.'));
        }
        else {
            try {
                foreach ($itemIds as $itemId) {
                    $item = Mage::getModel('plugincompany_blacklist/item');
                    $item->setId($itemId)->delete();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('plugincompany_blacklist')->__('Total of %d blacklisted items were successfully deleted.', count($itemIds)));
            }
            catch (Mage_Core_Exception $e){
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
            catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(Mage::helper('plugincompany_blacklist')->__('There was an error deleting blacklisted items.'));
                Mage::logException($e);
            }
        }
        $this->_redirect('*/*/index');
    }
    /**
     * mass status change - action
     * @access public
     * @return void
     * @author Plugin Company
     */
    public function massStatusAction(){
        $itemIds = $this->getRequest()->getParam('item');
        if(!is_array($itemIds)) {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('plugincompany_blacklist')->__('Please select blacklisted items.'));
        }
        else {
            try {
                foreach ($itemIds as $itemId) {
                    $item = Mage::getSingleton('plugincompany_blacklist/item')->load($itemId)
                        ->setStatus($this->getRequest()->getParam('status'))
                        ->setIsMassupdate(true)
                        ->save();
                }
                $this->_getSession()->addSuccess($this->__('Total of %d blacklisted items were successfully updated.', count($itemIds)));
            }
            catch (Mage_Core_Exception $e){
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
            catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(Mage::helper('plugincompany_blacklist')->__('There was an error updating blacklisted items.'));
                Mage::logException($e);
            }
        }
        $this->_redirect('*/*/index');
    }
    /**
     * mass Country change - action
     * @access public
     * @return void
     * @author Plugin Company
     */
    public function massCountryIdAction(){
        $itemIds = $this->getRequest()->getParam('item');
        if(!is_array($itemIds)) {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('plugincompany_blacklist')->__('Please select blacklisted items.'));
        }
        else {
            try {
                foreach ($itemIds as $itemId) {
                    $item = Mage::getSingleton('plugincompany_blacklist/item')->load($itemId)
                        ->setCountryId($this->getRequest()->getParam('flag_country_id'))
                        ->setIsMassupdate(true)
                        ->save();
                }
                $this->_getSession()->addSuccess($this->__('Total of %d blacklisted items were successfully updated.', count($itemIds)));
            }
            catch (Mage_Core_Exception $e){
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
            catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(Mage::helper('plugincompany_blacklist')->__('There was an error updating blacklisted items.'));
                Mage::logException($e);
            }
        }
        $this->_redirect('*/*/index');
    }
    /**
     * export as csv - action
     * @access public
     * @return void
     * @author Plugin Company
     */
    public function exportCsvAction(){
        $fileName   = 'item.csv';
        $content    = $this->getLayout()->createBlock('plugincompany_blacklist/adminhtml_item_grid')->getCsv();
        $this->_prepareDownloadResponse($fileName, $content);
    }
    /**
     * export as MsExcel - action
     * @access public
     * @return void
     * @author Plugin Company
     */
    public function exportExcelAction(){
        $fileName   = 'item.xls';
        $content    = $this->getLayout()->createBlock('plugincompany_blacklist/adminhtml_item_grid')->getExcelFile();
        $this->_prepareDownloadResponse($fileName, $content);
    }
    /**
     * export as xml - action
     * @access public
     * @return void
     * @author Plugin Company
     */
    public function exportXmlAction(){
        $fileName   = 'item.xml';
        $content    = $this->getLayout()->createBlock('plugincompany_blacklist/adminhtml_item_grid')->getXml();
        $this->_prepareDownloadResponse($fileName, $content);
    }
    /**
     * Check if admin has permissions to visit related pages
     * @access protected
     * @return boolean
     * @author Plugin Company
     */
    protected function _isAllowed() {
        return Mage::getSingleton('admin/session')->isAllowed('sales/plugincompany_blacklist/item');
    }

    public function addByOrderIdAction()
    {
        $post = $this->getRequest()->getPost();
        $orderId = $this->getRequest()->getParam('order_id');
        $adminId = Mage::getSingleton('admin/session')->getUser()->getUserId();

        $comment = null;
        if (isset($post['suspicion_comment'])) {
            $comment = $post['suspicion_comment'];
        }

        $addressType = null;
        if (isset($post['address_type'])) {
            $addressType = $post['address_type'];
        }

        try
        {
            Mage::getModel('plugincompany_blacklist/item')->addByOrderId($orderId, $adminId, $comment, $addressType);
        }
        catch (Exception $e)
        {
            Mage::getSingleton('core/session')->addError($this->__($e->getMessage()));
            $errors = true;
        }

        if (!empty($errors)) {
            try {
                $order = Mage::getModel('sales/order')->load($orderId);
                $order->addStatusToHistory($order->getStatus(), 'Order marked as suspicious', false);
                $order->save();
            } catch (Exception $e){
                Mage::getSingleton('core/session')->addError($this->__($e->getMessage()));
                $errors = true;
            }
        }

        if (!empty($errors)) {
            Mage::getSingleton('core/session')->addSuccess($this->__('Order successfully marked as suspicious'));
        }

        $this->_redirect('adminhtml/sales_order/view', array('_current' => true));
    }

    public function removeByOrderIdAction()
    {
        $orderId = $this->getRequest()->getParam('order_id');
        try{
            Mage::getModel('plugincompany_blacklist/item')->removeByOrderId($orderId);
        }
        catch (Exception $e)
        {
            Mage::getSingleton('core/session')->addError($this->__($e->getMessage()));
            $errors = true;
        }

        if (!empty($errors)) {
            try {
                $order = Mage::getModel('sales/order')->load($orderId);
                $order->addStatusToHistory($order->getStatus(), 'Order fraud suspicion removed', false);
                $order->save();
            } catch (Exception $e){
                Mage::getSingleton('core/session')->addError($this->__($e->getMessage()));
                $errors = true;
            }
        }

        if (!empty($errors)) {
            Mage::getSingleton('core/session')->addSuccess($this->__('Suspicion successfully removed'));
        }

        $this->_redirect('adminhtml/sales_order/view', array('_current' => true));
    }

}

