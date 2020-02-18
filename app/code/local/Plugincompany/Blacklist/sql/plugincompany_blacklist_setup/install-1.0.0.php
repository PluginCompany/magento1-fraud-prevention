<?php
/**
 * Copyright (c) 2014-2026 Plugin Company
 *
 * This file is part of Fraud Prevention, released under the MIT License.
 * See the LICENSE file in the project root.
 */
/**
 * Blacklist module install script
 *
 * @category    Plugincompany
 * @package     Plugincompany_Blacklist
 * @author      Plugin Company
 */
$this->startSetup();
$this->run("DROP TABLE IF EXISTS `{$this->getTable('plugincompany_blacklist/item')}`");
$table = $this->getConnection()
    ->newTable($this->getTable('plugincompany_blacklist/item'))
    ->addColumn('entity_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'identity'  => true,
        'nullable'  => false,
        'primary'   => true,
        ), 'Blacklist Item ID')
    ->addColumn('customer_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'nullable'  => false,
        'unsigned'  => true,
        ), 'Customer ID')
    ->addColumn('order_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'nullable'  => false,
        'unsigned'  => true,
    ), 'Order ID')

    ->addColumn('postcode', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(
        ), 'Postcode')

    ->addColumn('firstname', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(
        ), 'First name')

    ->addColumn('middlename', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(
        ), 'Middle name')

    ->addColumn('lastname', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(
        ), 'Last name')

    ->addColumn('street', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(
        ), 'Street')

    ->addColumn('city', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(
        ), 'City')

    ->addColumn('region', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(
        ), 'Region')

    ->addColumn('country_id', Varien_Db_Ddl_Table::TYPE_TEXT, 2, array(
        'nullable'  => false,
        ), 'Country')

    ->addColumn('email', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(
        ), 'E-mail')

    ->addColumn('telephone', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(
        ), 'Telephone')

    ->addColumn('fax', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(
        ), 'Fax')

    ->addColumn('address_type', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(
        ), 'Address Type')

    ->addColumn('company', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(
        ), 'Company')

    ->addColumn('marked_by_admin', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
    ), 'Admin ID')

    ->addColumn('comment', Varien_Db_Ddl_Table::TYPE_TEXT, 1024, array(
    ), 'Company')

    ->addColumn('updated_at', Varien_Db_Ddl_Table::TYPE_TIMESTAMP, null, array(
            ), 'Blacklist Item Modification Time')
    ->addColumn('created_at', Varien_Db_Ddl_Table::TYPE_TIMESTAMP, null, array(
        ), 'Blacklist Item Creation Time') 
    ->setComment('Blacklist Item Table');
$this->getConnection()->createTable($table);
$this->run("DROP TABLE IF EXISTS `{$this->getTable('plugincompany_blacklist/rule')}`");
$table = $this->getConnection()
    ->newTable($this->getTable('plugincompany_blacklist/rule'))
    ->addColumn('entity_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'identity'  => true,
        'nullable'  => false,
        'primary'   => true,
        ), 'Blacklist Rule ID')
    ->addColumn('name', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(
        'nullable'  => false,
        ), 'Rule name')

    ->addColumn('attributes', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(
        'nullable'  => false,
        ), 'Attribute combination')

    ->addColumn('strikes', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'nullable'  => false,
        'unsigned'  => true,
        ), 'Strikes')

    ->addColumn('status', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
        ), 'Enabled')

     ->addColumn('status', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        ), 'Blacklist Rule Status')
    ->addColumn('updated_at', Varien_Db_Ddl_Table::TYPE_TIMESTAMP, null, array(
            ), 'Blacklist Rule Modification Time')
    ->addColumn('created_at', Varien_Db_Ddl_Table::TYPE_TIMESTAMP, null, array(
        ), 'Blacklist Rule Creation Time') 
    ->setComment('Blacklist Rule Table');
$this->getConnection()->createTable($table);
$this->run("DROP TABLE IF EXISTS `{$this->getTable('plugincompany_blacklist/item_store')}`");
$table = $this->getConnection()
    ->newTable($this->getTable('plugincompany_blacklist/item_store'))
    ->addColumn('item_id', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
        'nullable'  => false,
        'primary'   => true,
        ), 'Blacklist Item ID')
    ->addColumn('store_id', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
        'unsigned'  => true,
        'nullable'  => false,
        'primary'   => true,
        ), 'Store ID')
    ->addIndex($this->getIdxName('plugincompany_blacklist/item_store', array('store_id')), array('store_id'))
    ->addForeignKey($this->getFkName('plugincompany_blacklist/item_store', 'item_id', 'plugincompany_blacklist/item', 'entity_id'), 'item_id', $this->getTable('plugincompany_blacklist/item'), 'entity_id', Varien_Db_Ddl_Table::ACTION_CASCADE, Varien_Db_Ddl_Table::ACTION_CASCADE)
    ->addForeignKey($this->getFkName('plugincompany_blacklist/item_store', 'store_id', 'core/store', 'store_id'), 'store_id', $this->getTable('core/store'), 'store_id', Varien_Db_Ddl_Table::ACTION_CASCADE, Varien_Db_Ddl_Table::ACTION_CASCADE)
    ->setComment('Blacklist Items To Store Linkage Table');
$this->getConnection()->createTable($table);
$this->run("DROP TABLE IF EXISTS `{$this->getTable('plugincompany_blacklist/rule_store')}`");
$table = $this->getConnection()
    ->newTable($this->getTable('plugincompany_blacklist/rule_store'))
    ->addColumn('rule_id', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
        'nullable'  => false,
        'primary'   => true,
        ), 'Blacklist Rule ID')
    ->addColumn('store_id', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
        'unsigned'  => true,
        'nullable'  => false,
        'primary'   => true,
        ), 'Store ID')
    ->addIndex($this->getIdxName('plugincompany_blacklist/rule_store', array('store_id')), array('store_id'))
    ->addForeignKey($this->getFkName('plugincompany_blacklist/rule_store', 'rule_id', 'plugincompany_blacklist/rule', 'entity_id'), 'rule_id', $this->getTable('plugincompany_blacklist/rule'), 'entity_id', Varien_Db_Ddl_Table::ACTION_CASCADE, Varien_Db_Ddl_Table::ACTION_CASCADE)
    ->addForeignKey($this->getFkName('plugincompany_blacklist/rule_store', 'store_id', 'core/store', 'store_id'), 'store_id', $this->getTable('core/store'), 'store_id', Varien_Db_Ddl_Table::ACTION_CASCADE, Varien_Db_Ddl_Table::ACTION_CASCADE)
    ->setComment('Blacklist Rules To Store Linkage Table');
$this->getConnection()->createTable($table);
$this->endSetup();
