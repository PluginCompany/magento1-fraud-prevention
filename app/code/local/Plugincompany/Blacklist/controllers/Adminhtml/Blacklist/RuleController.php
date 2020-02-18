<?php
/**
 * Copyright (c) 2014-2026 Plugin Company
 *
 * This file is part of Fraud Prevention, released under the MIT License.
 * See the LICENSE file in the project root.
 */
/**
 * Blacklist Rule admin controller
 *
 * @category    Plugincompany
 * @package     Plugincompany_Blacklist
 * @author      Plugin Company
 */
class Plugincompany_Blacklist_Adminhtml_Blacklist_RuleController
    extends Plugincompany_Blacklist_Controller_Adminhtml_Blacklist {
    /**
     * init the rule
     * @access protected
     * @return Plugincompany_Blacklist_Model_Rule
     */
    protected function _initRule(){
        $ruleId  = (int) $this->getRequest()->getParam('id');
        $rule    = Mage::getModel('plugincompany_blacklist/rule');
        if ($ruleId) {
            $rule->load($ruleId);
        }
        Mage::register('current_rule', $rule);
        return $rule;
    }
     /**
     * default action
     * @access public
     * @return void
     * @author Plugin Company
     */
    public function indexAction() {
        $this->loadLayout();
        $this->_title(Mage::helper('plugincompany_blacklist')->__('Blacklist'))
             ->_title(Mage::helper('plugincompany_blacklist')->__('Blacklist Rules'));
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
     * edit blacklist rule - action
     * @access public
     * @return void
     * @author Plugin Company
     */
    public function editAction() {
        $ruleId    = $this->getRequest()->getParam('id');
        $rule      = $this->_initRule();
        if ($ruleId && !$rule->getId()) {
            $this->_getSession()->addError(Mage::helper('plugincompany_blacklist')->__('This blacklist rule no longer exists.'));
            $this->_redirect('*/*/');
            return;
        }
        $data = Mage::getSingleton('adminhtml/session')->getRuleData(true);
        if (!empty($data)) {
            $rule->setData($data);
        }
        Mage::register('rule_data', $rule);
        $this->loadLayout();
        $this->_title(Mage::helper('plugincompany_blacklist')->__('Blacklist'))
             ->_title(Mage::helper('plugincompany_blacklist')->__('Blacklist Rules'));
        if ($rule->getId()){
            $this->_title($rule->getName());
        }
        else{
            $this->_title(Mage::helper('plugincompany_blacklist')->__('Add blacklist rule'));
        }
        if (Mage::getSingleton('cms/wysiwyg_config')->isEnabled()) {
            $this->getLayout()->getBlock('head')->setCanLoadTinyMce(true);
        }
        $this->renderLayout();
    }
    /**
     * new blacklist rule action
     * @access public
     * @return void
     * @author Plugin Company
     */
    public function newAction() {
        $this->_forward('edit');
    }
    /**
     * save blacklist rule - action
     * @access public
     * @return void
     * @author Plugin Company
     */
    public function saveAction() {
        if ($data = $this->getRequest()->getPost('rule')) {
            try {
                $rule = $this->_initRule();
                $data['attributes'] = implode(',',$data['attributes']);
                $data['customer_groups'] = implode(',',$data['customer_groups']);
                $rule->addData($data);
                $rule->save();
                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('plugincompany_blacklist')->__('Blacklist Rule was successfully saved'));
                Mage::getSingleton('adminhtml/session')->setFormData(false);
                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('*/*/edit', array('id' => $rule->getId()));
                    return;
                }
                $this->_redirect('*/*/');
                return;
            }
            catch (Mage_Core_Exception $e){
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                Mage::getSingleton('adminhtml/session')->setRuleData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            }
            catch (Exception $e) {
                Mage::logException($e);
                Mage::getSingleton('adminhtml/session')->addError(Mage::helper('plugincompany_blacklist')->__('There was a problem saving the blacklist rule.'));
                Mage::getSingleton('adminhtml/session')->setRuleData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(Mage::helper('plugincompany_blacklist')->__('Unable to find blacklist rule to save.'));
        $this->_redirect('*/*/');
    }
    /**
     * delete blacklist rule - action
     * @access public
     * @return void
     * @author Plugin Company
     */
    public function deleteAction() {
        if( $this->getRequest()->getParam('id') > 0) {
            try {
                $rule = Mage::getModel('plugincompany_blacklist/rule');
                $rule->setId($this->getRequest()->getParam('id'))->delete();
                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('plugincompany_blacklist')->__('Blacklist Rule was successfully deleted.'));
                $this->_redirect('*/*/');
                return;
            }
            catch (Mage_Core_Exception $e){
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
            }
            catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(Mage::helper('plugincompany_blacklist')->__('There was an error deleting blacklist rule.'));
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                Mage::logException($e);
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(Mage::helper('plugincompany_blacklist')->__('Could not find blacklist rule to delete.'));
        $this->_redirect('*/*/');
    }
    /**
     * mass delete blacklist rule - action
     * @access public
     * @return void
     * @author Plugin Company
     */
    public function massDeleteAction() {
        $ruleIds = $this->getRequest()->getParam('rule');
        if(!is_array($ruleIds)) {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('plugincompany_blacklist')->__('Please select blacklist rules to delete.'));
        }
        else {
            try {
                foreach ($ruleIds as $ruleId) {
                    $rule = Mage::getModel('plugincompany_blacklist/rule');
                    $rule->setId($ruleId)->delete();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('plugincompany_blacklist')->__('Total of %d blacklist rules were successfully deleted.', count($ruleIds)));
            }
            catch (Mage_Core_Exception $e){
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
            catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(Mage::helper('plugincompany_blacklist')->__('There was an error deleting blacklist rules.'));
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
        $ruleIds = $this->getRequest()->getParam('rule');
        if(!is_array($ruleIds)) {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('plugincompany_blacklist')->__('Please select blacklist rules.'));
        }
        else {
            try {
                foreach ($ruleIds as $ruleId) {
                $rule = Mage::getSingleton('plugincompany_blacklist/rule')->load($ruleId)
                            ->setStatus($this->getRequest()->getParam('status'))
                            ->setIsMassupdate(true)
                            ->save();
                }
                $this->_getSession()->addSuccess($this->__('Total of %d blacklist rules were successfully updated.', count($ruleIds)));
            }
            catch (Mage_Core_Exception $e){
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
            catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(Mage::helper('plugincompany_blacklist')->__('There was an error updating blacklist rules.'));
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
        $fileName   = 'rule.csv';
        $content    = $this->getLayout()->createBlock('plugincompany_blacklist/adminhtml_rule_grid')->getCsv();
        $this->_prepareDownloadResponse($fileName, $content);
    }
    /**
     * export as MsExcel - action
     * @access public
     * @return void
     * @author Plugin Company
     */
    public function exportExcelAction(){
        $fileName   = 'rule.xls';
        $content    = $this->getLayout()->createBlock('plugincompany_blacklist/adminhtml_rule_grid')->getExcelFile();
        $this->_prepareDownloadResponse($fileName, $content);
    }
    /**
     * export as xml - action
     * @access public
     * @return void
     * @author Plugin Company
     */
    public function exportXmlAction(){
        $fileName   = 'rule.xml';
        $content    = $this->getLayout()->createBlock('plugincompany_blacklist/adminhtml_rule_grid')->getXml();
        $this->_prepareDownloadResponse($fileName, $content);
    }
    /**
     * Check if admin has permissions to visit related pages
     * @access protected
     * @return boolean
     * @author Plugin Company
     */
    protected function _isAllowed() {
        return Mage::getSingleton('admin/session')->isAllowed('sales/plugincompany_blacklist/rule');
    }
}
