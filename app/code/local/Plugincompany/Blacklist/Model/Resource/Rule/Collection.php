<?php
/**
 * Copyright (c) 2014-2026 Plugin Company
 *
 * This file is part of Fraud Prevention, released under the MIT License.
 * See the LICENSE file in the project root.
 */
/**
 * Blacklist Rule collection resource model
 *
 * @category    Plugincompany
 * @package     Plugincompany_Blacklist
 * @author      Plugin Company
 */
class Plugincompany_Blacklist_Model_Resource_Rule_Collection
    extends Mage_Core_Model_Resource_Db_Collection_Abstract {
    protected $_joinedFields = array();
    /**
     * constructor
     * @access public
     * @return void
     * @author Plugin Company
     */
    protected function _construct(){
        parent::_construct();
        $this->_init('plugincompany_blacklist/rule');
        $this->_map['fields']['store'] = 'store_table.store_id';
    }
    /**
     * Add filter by store
     * @access public
     * @param int|Mage_Core_Model_Store $store
     * @param bool $withAdmin
     * @return Plugincompany_Blacklist_Model_Resource_Rule_Collection
     * @author Plugin Company
     */
    public function addStoreFilter($store, $withAdmin = true){
        if (!isset($this->_joinedFields['store'])){
            if ($store instanceof Mage_Core_Model_Store) {
                $store = array($store->getId());
            }
            if (!is_array($store)) {
                $store = array($store);
            }
            if ($withAdmin) {
                $store[] = Mage_Core_Model_App::ADMIN_STORE_ID;
            }
            $this->addFilter('store', array('in' => $store), 'public');
            $this->_joinedFields['store'] = true;
        }
        return $this;
    }

    public function addCustomerGroupFilter($groupId)
    {
        $this
            ->getSelect()
            ->where("find_in_set('all', customer_groups)")
        ;
        if($groupId != null && $groupId !== ''){
            $this
                ->getSelect()
                ->orWhere("find_in_set({$groupId}, customer_groups)");
        }
        return $this;
    }
    
    public function addIsActiveFilter()
    {
        $this->addFieldToFilter('status',1);
        return $this;
    }
    
    /**
     * Join store relation table if there is store filter
     * @access protected
     * @return Plugincompany_Blacklist_Model_Resource_Rule_Collection
     * @author Plugin Company
     */
    protected function _renderFiltersBefore(){
        if ($this->getFilter('store')) {
            $this->getSelect()->join(
                array('store_table' => $this->getTable('plugincompany_blacklist/rule_store')),
                'main_table.entity_id = store_table.rule_id',
                array()
            )->group('main_table.entity_id');
            /*
             * Allow analytic functions usage because of one field grouping
             */
            $this->_useAnalyticFunction = true;
        }
        return parent::_renderFiltersBefore();
    }
    /**
     * get rules as array
     * @access protected
     * @param string $valueField
     * @param string $labelField
     * @param array $additional
     * @return array
     * @author Plugin Company
     */
    protected function _toOptionArray($valueField='entity_id', $labelField='name', $additional=array()){
        return parent::_toOptionArray($valueField, $labelField, $additional);
    }
    /**
     * get options hash
     * @access protected
     * @param string $valueField
     * @param string $labelField
     * @return array
     * @author Plugin Company
     */
    protected function _toOptionHash($valueField='entity_id', $labelField='name'){
        return parent::_toOptionHash($valueField, $labelField);
    }
    /**
     * Get SQL for get record count.
     * Extra GROUP BY strip added.
     * @access public
     * @return Varien_Db_Select
     * @author Plugin Company
     */
    public function getSelectCountSql(){
        $countSelect = parent::getSelectCountSql();
        $countSelect->reset(Zend_Db_Select::GROUP);
        return $countSelect;
    }
}
