<?php
/**
 * Copyright (c) 2014-2026 Plugin Company
 *
 * This file is part of Fraud Prevention, released under the MIT License.
 * See the LICENSE file in the project root.
 */
?>
<?php
class Plugincompany_Blacklist_Block_Adminhtml_Customer_Grid_Renderer_Fraud extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
    public function render(Varien_Object $row)
    {
        $value =  $row->getData($this->getColumn()->getIndex());
        if(!$value){
            $value = 0;
        }
        $options = $this->getColumn()->getOptions();
        return $options[$value];
    }
}