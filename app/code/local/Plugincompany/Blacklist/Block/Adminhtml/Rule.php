<?php
/**
 * Copyright (c) 2014-2026 Plugin Company
 *
 * This file is part of Fraud Prevention, released under the MIT License.
 * See the LICENSE file in the project root.
 */
/**
 * Blacklist Rule admin block
 *
 * @category    Plugincompany
 * @package     Plugincompany_Blacklist
 * @author      Plugin Company
 */
class Plugincompany_Blacklist_Block_Adminhtml_Rule
    extends Mage_Adminhtml_Block_Widget_Grid_Container {
    /**
     * constructor
     * @access public
     * @return void
     * @author Plugin Company
     */
    public function __construct(){
        $this->_controller         = 'adminhtml_rule';
        $this->_blockGroup         = 'plugincompany_blacklist';
        parent::__construct();
        $this->_headerText         = Mage::helper('plugincompany_blacklist')->__('Manage Blacklisting Rules');
        $this->_updateButton('add', 'label', Mage::helper('plugincompany_blacklist')->__('Add Blacklisting Rule'));
    }
}
