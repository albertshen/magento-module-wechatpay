<?php

namespace Albert\Magento\WeChatPay\Model\Config\Backend;

use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\App\Config\ScopeConfigInterface;

class Certificate extends \Magento\Config\Model\Config\Backend\File
{

    /**
     * Prepend path with scope info
     *
     * E.g. 'stores/2/path' , 'websites/3/path', 'default/path'
     *
     * @param string $path
     * @return string
     */
    protected function _prependScopeInfo($path)
    {
        $fieldConfig = $this->getFieldConfig();
        $uploadDir = $fieldConfig['upload_dir']['value'];
        $scopeInfo = $uploadDir . '/' . $this->getScope();
        if (ScopeConfigInterface::SCOPE_TYPE_DEFAULT != $this->getScope()) {
            $scopeInfo .= '/' . $this->getScopeId();
        }
        return $scopeInfo . '/' . $path;
    }

    /**
     * Retrieve upload directory path
     *
     * @param string $uploadDir
     * @return string
     * @since 100.1.0
     */
    protected function getUploadDirPath($uploadDir)
    {
        $this->_mediaDirectory = $this->_filesystem->getDirectoryWrite(DirectoryList::ROOT);
        return $this->_mediaDirectory->getAbsolutePath($uploadDir);
    }


    /**
     * Getter for allowed extensions of uploaded files.
     *
     * @return string[]
     */
    protected function _getAllowedExtensions()
    {
        return ['pem', 'crt'];
    }
}