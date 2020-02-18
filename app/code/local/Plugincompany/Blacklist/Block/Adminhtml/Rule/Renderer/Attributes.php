<?php
/**
 * Copyright (c) 2014-2026 Plugin Company
 *
 * This file is part of Fraud Prevention, released under the MIT License.
 * See the LICENSE file in the project root.
 */
?>
<?php
class Plugincompany_Blacklist_Block_Adminhtml_Rule_Renderer_Attributes extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
    public function render(Varien_Object $row)
    {
        $value =  $row->getData($this->getColumn()->getIndex());

        $value = explode(',', $value);

        $return = array();
        foreach ($value as $attr) {
            $return[] = ucwords(str_replace('_', ' ', $attr));
        }

        $return = implode('<br />', $return);

        return $return;

    }
}