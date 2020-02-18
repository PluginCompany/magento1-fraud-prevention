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

$fraudItems = Mage::getModel('plugincompany_blacklist/item')->getCollection()
;

if ($fraudItems->getSize() > 0) {
    foreach ($fraudItems as $fraudItem) {
        $fraudItem
            ->setEmailDomain('needsupdate')
            ->save();
    }
}
$this->endSetup();
