<?php
/**
 * Blacklist Rule admin edit tabs
 *
 * @category    Plugincompany
 * @package     Plugincompany_Blacklist
 * @author      Plugin Company
 */
class Plugincompany_Blacklist_Block_Adminhtml_Rule_Edit_Tabs
    extends Mage_Adminhtml_Block_Widget_Tabs {
    /**
     * Initialize Tabs
     * @access public
     * @author Plugin Company
     */
    public function __construct() {
        parent::__construct();
        $this->setId('rule_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('plugincompany_blacklist')->__('Blacklisting Rule'));
    }
    /**
     * before render html
     * @access protected
     * @return Plugincompany_Blacklist_Block_Adminhtml_Rule_Edit_Tabs
     * @author Plugin Company
     */
    protected function _beforeToHtml(){
        $this->addTab('form_rule', array(
            'label'        => Mage::helper('plugincompany_blacklist')->__('Rule Settings'),
            'title'        => Mage::helper('plugincompany_blacklist')->__('Rule Settings'),
            'content'     => $this->getLayout()->createBlock('plugincompany_blacklist/adminhtml_rule_edit_tab_form')->toHtml(),
        ));
        if (!Mage::app()->isSingleStoreMode()){
            $this->addTab('form_store_rule', array(
                'label'        => Mage::helper('plugincompany_blacklist')->__('Store Views'),
                'title'        => Mage::helper('plugincompany_blacklist')->__('Store Views'),
                'content'     => $this->getLayout()->createBlock('plugincompany_blacklist/adminhtml_rule_edit_tab_stores')->toHtml(),
            ));
        }
        
        $this->addTab('form_customer_groups', array(
            'label'        => Mage::helper('plugincompany_blacklist')->__('Customer Groups'),
            'title'        => Mage::helper('plugincompany_blacklist')->__('Customer Groups'),
            'content'     => $this->getLayout()->createBlock('plugincompany_blacklist/adminhtml_rule_edit_tab_customergroups')->toHtml(),
        ));
        return parent::_beforeToHtml();
    }
    /**
     * Retrieve blacklist rule entity
     * @access public
     * @return Plugincompany_Blacklist_Model_Rule
     * @author Plugin Company
     */
    public function getRule(){
        return Mage::registry('current_rule');
    }
}
