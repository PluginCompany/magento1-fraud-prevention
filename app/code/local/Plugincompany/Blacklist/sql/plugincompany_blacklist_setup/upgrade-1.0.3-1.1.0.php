<?php
/**
 * Copyright (c) 2014-2026 Plugin Company
 *
 * This file is part of Fraud Prevention, released under the MIT License.
 * See the LICENSE file in the project root.
 */
/**
 * Blacklist module upgrade script
 *
 * @category    Plugincompany
 * @package     Plugincompany_Blacklist
 * @author      Plugin Company
 */
$this->startSetup();

$this->getConnection()
    ->addColumn($this->getTable('plugincompany_blacklist/rule'), 'customer_groups', array(
        'type' => Varien_Db_Ddl_Table::TYPE_TEXT,
        'nullable' => false,
        'comment' => 'Customer groups'
    ));

$this->getConnection()
    ->addColumn($this->getTable('plugincompany_blacklist/item'), 'email_domain', array(
        'type' => Varien_Db_Ddl_Table::TYPE_TEXT,
        'nullable' => false,
        'comment' => 'Email Domain'
    ));
$this->getConnection()
    ->addColumn($this->getTable('plugincompany_blacklist/item'), 'customer_ip', array(
        'type' => Varien_Db_Ddl_Table::TYPE_TEXT,
        'nullable' => false,
        'comment' => 'Customer IP'
    ));

$collection = Mage::getModel('plugincompany_blacklist/item')->getCollection();
foreach($collection as $item)
{
    //update customer IP from order data
    $order = Mage::getSingleton('sales/order')->load($item->getOrderId());
    $item->setCustomerIp($order->getRemoteIp());
    $item->save();
}

//add customer attributes
$installer = new Mage_Eav_Model_Entity_Setup('core_setup');
$installer->startSetup();
$entity = $installer->getEntityTypeId('customer');

$installer->addAttribute($entity, 'pc_blacklisted', array(
    'type' => 'int',
    'label' => 'Fraud Status',
    'visible' => TRUE,
    'required' => FALSE,
    'default_value' => '',
    'adminhtml_only' => '1',
    "input"    => "select",
    "class"    => "",
    "source"   => "plugincompany_blacklist/attribute_source_customeraccount",
    "position" => 99999999
));

$forms = array(
    'adminhtml_customer',
    'customer_account_edit'
);
$attribute = Mage::getSingleton('eav/config')->getAttribute($installer->getEntityTypeId('customer'), 'pc_blacklisted');
$attribute->setData('used_in_forms', $forms);
$attribute->setSortOrder(999999);
$attribute->save();

$installer->endSetup();

//order attribute
$this->getConnection()
    ->addColumn($this->getTable('sales/order'),'whitelisted', array(
        'type'      => Varien_Db_Ddl_Table::TYPE_INTEGER,
        'nullable'  => false,
        'comment'   => 'Whitelisted yes no',
        'default'   => 0
    ));

$this->endSetup();
