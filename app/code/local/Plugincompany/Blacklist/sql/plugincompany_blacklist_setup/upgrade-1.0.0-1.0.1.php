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

$status = Mage::getModel('sales/order_status');

$status->setStatus('blacklist')->setLabel('Blacklisted')
    ->assignState(Mage_Sales_Model_Order::STATE_HOLDED)
    ->save();

$this->endSetup();
