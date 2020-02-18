<?php
/**
 * Copyright (c) 2014-2026 Plugin Company
 *
 * This file is part of Fraud Prevention, released under the MIT License.
 * See the LICENSE file in the project root.
 */
/**
 * Blacklist Rule admin grid block
 *
 * @category    Plugincompany
 * @package     Plugincompany_Blacklist
 * @author      Plugin Company
 */
class Plugincompany_Blacklist_Block_Adminhtml_Rule_Grid
    extends Mage_Adminhtml_Block_Widget_Grid {
    /**
     * constructor
     * @access public
     * @author Plugin Company
     */
    public function __construct(){
        parent::__construct();
        $this->setId('ruleGrid');
        $this->setDefaultSort('entity_id');
        $this->setDefaultDir('ASC');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(true);
    }
    /**
     * prepare collection
     * @access protected
     * @return Plugincompany_Blacklist_Block_Adminhtml_Rule_Grid
     * @author Plugin Company
     */
    protected function _prepareCollection(){
        $collection = Mage::getModel('plugincompany_blacklist/rule')->getCollection();
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }
    /**
     * prepare grid collection
     * @access protected
     * @return Plugincompany_Blacklist_Block_Adminhtml_Rule_Grid
     * @author Plugin Company
     */
    protected function _prepareColumns(){
        $this->addColumn('entity_id', array(
            'header'    => Mage::helper('plugincompany_blacklist')->__('Id'),
            'index'        => 'entity_id',
            'type'        => 'number'
        ));
        $this->addColumn('name', array(
            'header'    => Mage::helper('plugincompany_blacklist')->__('Rule Name'),
            'align'     => 'left',
            'index'     => 'name',
        ));

        $this->addColumn('attributes', array(
            'header'=> Mage::helper('plugincompany_blacklist')->__('Rule Attribute'),
            'index' => 'attributes',
            'type'=> 'text',
            'renderer' => 'Plugincompany_Blacklist_Block_Adminhtml_Rule_Renderer_Attributes'

        ));
        $this->addColumn('strikes', array(
            'header'=> Mage::helper('plugincompany_blacklist')->__('Permitted Strikes'),
            'index' => 'strikes',
            'type'=> 'number',

        ));
        if (!Mage::app()->isSingleStoreMode() && !$this->_isExport) {
            $this->addColumn('store_id', array(
                'header'=> Mage::helper('plugincompany_blacklist')->__('Store Views'),
                'index' => 'store_id',
                'type'  => 'store',
                'store_all' => true,
                'store_view'=> true,
                'sortable'  => false,
                'filter_condition_callback'=> array($this, '_filterStoreCondition'),
            ));
        }
        $this->addColumn('created_at', array(
            'header'    => Mage::helper('plugincompany_blacklist')->__('Created at'),
            'index'     => 'created_at',
            'width'     => '120px',
            'type'      => 'datetime',
        ));
        $this->addColumn('updated_at', array(
            'header'    => Mage::helper('plugincompany_blacklist')->__('Updated at'),
            'index'     => 'updated_at',
            'width'     => '120px',
            'type'      => 'datetime',
        ));

        $this->addColumn('status', array(
            'header'    => Mage::helper('plugincompany_blacklist')->__('Status'),
            'index'        => 'status',
            'type'        => 'options',
            'options'    => array(
                '1' => Mage::helper('plugincompany_blacklist')->__('Enabled'),
                '0' => Mage::helper('plugincompany_blacklist')->__('Disabled'),
            )
        ));

        $this->addColumn('action',
            array(
                'header'=>  Mage::helper('plugincompany_blacklist')->__('Action'),
                'width' => '100',
                'type'  => 'action',
                'getter'=> 'getId',
                'actions'   => array(
                    array(
                        'caption'   => Mage::helper('plugincompany_blacklist')->__('Edit'),
                        'url'   => array('base'=> '*/*/edit'),
                        'field' => 'id'
                    )
                ),
                'filter'=> false,
                'is_system'    => true,
                'sortable'  => false,
        ));
        $this->addExportType('*/*/exportCsv', Mage::helper('plugincompany_blacklist')->__('CSV'));
        $this->addExportType('*/*/exportExcel', Mage::helper('plugincompany_blacklist')->__('Excel'));
        $this->addExportType('*/*/exportXml', Mage::helper('plugincompany_blacklist')->__('XML'));
        return parent::_prepareColumns();
    }
    /**
     * prepare mass action
     * @access protected
     * @return Plugincompany_Blacklist_Block_Adminhtml_Rule_Grid
     * @author Plugin Company
     */
    protected function _prepareMassaction(){
        $this->setMassactionIdField('entity_id');
        $this->getMassactionBlock()->setFormFieldName('rule');
        $this->getMassactionBlock()->addItem('delete', array(
            'label'=> Mage::helper('plugincompany_blacklist')->__('Delete'),
            'url'  => $this->getUrl('*/*/massDelete'),
            'confirm'  => Mage::helper('plugincompany_blacklist')->__('Are you sure?')
        ));
        $this->getMassactionBlock()->addItem('status', array(
            'label'=> Mage::helper('plugincompany_blacklist')->__('Change status'),
            'url'  => $this->getUrl('*/*/massStatus', array('_current'=>true)),
            'additional' => array(
                'status' => array(
                        'name' => 'status',
                        'type' => 'select',
                        'class' => 'required-entry',
                        'label' => Mage::helper('plugincompany_blacklist')->__('Status'),
                        'values' => array(
                                '1' => Mage::helper('plugincompany_blacklist')->__('Enabled'),
                                '0' => Mage::helper('plugincompany_blacklist')->__('Disabled'),
                        )
                )
            )
        ));
        return $this;
    }
    /**
     * get the row url
     * @access public
     * @param Plugincompany_Blacklist_Model_Rule
     * @return string
     * @author Plugin Company
     */
    public function getRowUrl($row){
        return $this->getUrl('*/*/edit', array('id' => $row->getId()));
    }
    /**
     * get the grid url
     * @access public
     * @return string
     * @author Plugin Company
     */
    public function getGridUrl(){
        return $this->getUrl('*/*/grid', array('_current'=>true));
    }
    /**
     * after collection load
     * @access protected
     * @return Plugincompany_Blacklist_Block_Adminhtml_Rule_Grid
     * @author Plugin Company
     */
    protected function _afterLoadCollection(){
        $this->getCollection()->walk('afterLoad');
        parent::_afterLoadCollection();
    }
    /**
     * filter store column
     * @access protected
     * @param Plugincompany_Blacklist_Model_Resource_Rule_Collection $collection
     * @param Mage_Adminhtml_Block_Widget_Grid_Column $column
     * @return Plugincompany_Blacklist_Block_Adminhtml_Rule_Grid
     * @author Plugin Company
     */
    protected function _filterStoreCondition($collection, $column){
        if (!$value = $column->getFilter()->getValue()) {
            return;
        }
        $collection->addStoreFilter($value);
        return $this;
    }
}
