<?php
/**
 * Copyright (c) 2014-2026 Plugin Company
 *
 * This file is part of Fraud Prevention, released under the MIT License.
 * See the LICENSE file in the project root.
 */
/**
 * country attribute source model
 *
 * @category    Plugincompany
 * @package     Plugincompany_Blacklist
 * @author      Plugin Company
 */
class Plugincompany_Blacklist_Model_Attribute_Source_Attributes
{
    public function toOptionArray()
    {
        $resource = Mage::getSingleton('core/resource');
       $readConnection = $resource->getConnection('core_read');
        /**
         * Get the table name
         */
        $tableName = $resource->getTableName('plugincompany_blacklist/item');
        $details=$readConnection->describeTable($tableName);

        $array = array();
        foreach ($details as $k => $detail) {
            if (in_array($k, array('marked_by_admin','entity_id', 'strikes', 'updated_at', 'created_at', 'status'))) {
               continue;
            }
            $array[] = array('label' => ucwords(str_replace('_',' ',$k)),'value'=>$k);
        }

        return $array;

    }
}
