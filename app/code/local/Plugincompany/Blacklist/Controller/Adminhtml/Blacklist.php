<?php
/**
 * Copyright (c) 2014-2026 Plugin Company
 *
 * This file is part of Fraud Prevention, released under the MIT License.
 * See the LICENSE file in the project root.
 */
/**
 * module base admin controller
 *
 * @category    Plugincompany
 * @package     Plugincompany_Blacklist
 * @author      Plugin Company
 */
class Plugincompany_Blacklist_Controller_Adminhtml_Blacklist
    extends Mage_Adminhtml_Controller_Action {
    /**
     * upload file and get the uploaded name
     * @access public
     * @param string $input
     * @param string $destinationFolder
     * @param array $data
     * @return string
     * @throws Exception
     * @author Plugin Company
     */
    protected function _uploadAndGetName($input, $destinationFolder, $data) {
        try{
            if (isset($data[$input]['delete'])){
                return '';
            }
            else{
                $uploader = new Varien_File_Uploader($input);
                $uploader->setAllowRenameFiles(true);
                $uploader->setFilesDispersion(true);
                $uploader->setAllowCreateFolders(true);
                $result = $uploader->save($destinationFolder);
                return $result['file'];
            }
        }
        catch (Exception $e) {
            if ($e->getCode() != Varien_File_Uploader::TMP_NAME_EMPTY) {
                throw $e;
            }
            else{
                if (isset($data[$input]['value'])){
                    return $data[$input]['value'];
                }
            }
        }
        return '';
    }
    /**
     * Check if admin has permissions to visit related pages
     * @access protected
     * @return boolean
     * @author Plugin Company
     */
    protected function _isAllowed() {
        return Mage::getSingleton('admin/session')->isAllowed('sales/plugincompany_blacklist/rule');
    }
}
